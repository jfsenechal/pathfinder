<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Helper\SessionHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

trait GetCharacterTrait
{
    private ?Character $characterSelected = null;
    private ?CharacterRepository $characterRepository = null;

    #[Required]
    public function setTuteurUtils(CharacterRepository $characterRepository): void
    {
        $this->characterRepository = $characterRepository;
    }

    public function hasCharacter(Request $request): Response|Character
    {
        if (!$user = $this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $session = $request->getSession();
        if ($uuid = $session->get(SessionHelper::KEY_CHARACTER_SELECTED)) {
            try {
                if ($this->characterSelected = $this->characterRepository->findOneByUuid($uuid)) {
                    return $this->characterSelected;
                }
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'Error to get character: '.$exception->getMessage());

                return $this->redirectToRoute('pathfinder_character_index');
            }
        }

        $characters = $this->characterRepository->findByUser($user);
        if (count($characters) === 1) {
            $this->characterSelected = $characters[0];
            $session->set(SessionHelper::KEY_CHARACTER_SELECTED, $this->characterSelected->uuid);

            return $this->characterSelected;
        }

        $this->addFlash('warning', 'Please select your character to working on');

        return $this->redirectToRoute('pathfinder_character_index');
    }

}