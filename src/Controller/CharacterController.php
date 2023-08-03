<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Ability\AbilityCalculator;
use AfmLibre\Pathfinder\Armor\ArmorCalculator;
use AfmLibre\Pathfinder\Attack\AttackCalculator;
use AfmLibre\Pathfinder\Character\Message\CharacterCreated;
use AfmLibre\Pathfinder\Character\Message\CharacterUpdated;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Form\CharacterEditType;
use AfmLibre\Pathfinder\Form\CharacterType;
use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Repository\CharacterArmorRepository;
use AfmLibre\Pathfinder\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Repository\FavoriteSpellRepository;
use AfmLibre\Pathfinder\Repository\CharacterWeaponRepository;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use AfmLibre\Pathfinder\Repository\RaceTraitRepository;
use AfmLibre\Pathfinder\SavingThrow\SavingThrowCalculator;
use AfmLibre\Pathfinder\Skill\SkillCalculator;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/character')]
class CharacterController extends AbstractController
{
    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly FavoriteSpellRepository $characterSpellRepository,
        private readonly ClassRepository $classTRepository,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository,
        private readonly RaceTraitRepository $raceTraitRepository,
        private readonly CharacterArmorRepository $characterArmorRepository,
        private readonly CharacterWeaponRepository $characterWeaponRepository,
        private readonly CharacterFeatRepository $characterFeatRepository,
        private readonly AbilityCalculator $abilityCalculator,
        private readonly SavingThrowCalculator $savingThrowCalculator,
        private readonly SkillCalculator $skillCalculator,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_character_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/character/index.html.twig',
            [
                'characters' => $this->characterRepository->searchByUser(),
            ]
        );
    }

    #[Route(path: '/new', name: 'pathfinder_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $character->uuid = $character->generateUuid();
            $character->current_level = $this->levelRepository->findOneByClassAndLevel(
                $character->classT,
                $character->select_level
            );
            $this->characterRepository->persist($character);
            $this->characterRepository->flush();
            $this->dispatcher->dispatch(new CharacterCreated($character->getId()));

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/new.html.twig',
            [
                'character' => $character,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{uuid}', name: 'pathfinder_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $spells = SpellUtils::groupByLevel($characterSpells);

        $raceModifier = $this->raceTraitRepository->findOneByRaceAndName($character->race, "Caractéristiques");

        $currentLevel = $character->current_level;

        $abilities = $this->abilityCalculator->calculate($character);
        $savingThrows = $this->savingThrowCalculator->calculate($character);
        $skills = $this->skillCalculator->calculate($character);

        $characterArmors = $this->characterArmorRepository->findByCharacter($character);
        $armorClass = ArmorCalculator::createArmorAbility(
            $character,
            $characterArmors,
            SizeEnum::SIZE_MIDDLE
        );

        $weapons = $this->characterWeaponRepository->findByCharacter($character);
        $characterWeapons = array_map(function (CharacterWeapon $characterWeapon) use ($character) {
            $weapon = $characterWeapon->weapon;
            $characterWeapon->damageRoll = AttackCalculator::createDamageAbility($character, $weapon);
            $characterWeapon->attackRoll = AttackCalculator::createAttackRoll(
                $character,
                $weapon,
                SizeEnum::SIZE_MIDDLE
            );

            return $characterWeapon;
        }, $weapons);

        $characterFeats = $this->characterFeatRepository->findByCharacter($character);

        $bmo = AttackCalculator::createBmo($character, SizeEnum::SIZE_MIDDLE);
        $dmd = ArmorCalculator::createDmd($character, SizeEnum::SIZE_MIDDLE);

        return $this->render(
            '@AfmLibrePathfinder/character/show.html.twig',
            [
                'character' => $character,
                'spells' => $spells,
                'currentLevel' => $currentLevel,
                'raceModifier' => $raceModifier,
                'abilities' => $abilities,
                'savingThrows' => $savingThrows,
                'characterArmors' => $characterArmors,
                'characterWeapons' => $characterWeapons,
                'characterFeats' => $characterFeats,
                'bmoAbility' => $bmo,
                'dmdAbility' => $dmd,
                'armorClass' => $armorClass,
                'skills' => $skills,
            ]
        );
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character): Response
    {
        $form = $this->createForm(CharacterEditType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterRepository->flush();
            $this->dispatcher->dispatch(new CharacterUpdated($character->id));

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/edit.html.twig',
            [
                'character' => $character,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{uuid}', name: 'pathfinder_character_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->uuid, $request->request->get('_token'))) {
            $id = $character->uuid;
            $this->$this->dispatcher->dispatch(new CharacterUpdated($id));
            $this->characterRepository->remove($character);
            $this->characterRepository->flush();
        }

        return $this->redirectToRoute('pathfinder_character_index', [], Response::HTTP_SEE_OTHER);
    }
}
