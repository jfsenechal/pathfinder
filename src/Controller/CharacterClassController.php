<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Form\SearchNameType;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SpellController
 * @package AfmLibre\Pathfinder\Controller
 * @Route("/class")
 */
class CharacterClassController extends AbstractController
{
    private CharacterClassRepository $characterClassRepository;
    private SpellClassRepository $spellClassRepository;
    private ClassFeatureRepository $classFeatureRepository;

    public function __construct(
        CharacterClassRepository $characterClassRepository,
        SpellClassRepository $spellClassRepository,
        ClassFeatureRepository $classFeatureRepository
    ) {
        $this->characterClassRepository = $characterClassRepository;
        $this->spellClassRepository = $spellClassRepository;
        $this->classFeatureRepository = $classFeatureRepository;
    }

    /**
     * @Route("/", name="pathfinder_class_index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SearchNameType::class);
        $name = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['name'];
        }

        $characterClasses = $this->characterClassRepository->searchByName($name);

        return $this->render(
            '@AfmLibrePathfinder/class/index.html.twig',
            [
                'characterClasses' => $characterClasses,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_class_show")
     */
    public function show(CharacterClass $characterClass)
    {
        $spellsClass = $this->spellClassRepository->searchByNameAndClass(null, $characterClass);
        $classFeatures = $this->classFeatureRepository->findByCharacterClass($characterClass);

        return $this->render(
            '@AfmLibrePathfinder/class/show.html.twig',
            [
                'characterClass' => $characterClass,
                'spellsClass' => $spellsClass,
                'classFeatures' => $classFeatures,
            ]
        );
    }
}
