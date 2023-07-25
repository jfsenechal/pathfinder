<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\Spell;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api')]
class ApiController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    #[Route(path: '/setpath/', name: 'pathfinder_api_set_pathfinder')]
    public function index(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $type = $request->get('type');

        $class = match ($type) {
            'feat' => Feat::class,
            'race' => Race::class,
            'class' => ClassT::class,
            'spell' => Spell::class,
            default => null
        };

        if ($class === null) {
            return $this->json(['not found']);
        }

        $manager = $this->managerRegistry->getRepository($class);

        if ($object = $manager->find($id)) {
            if ($object->campaings && count($object->campaings) > 0) {
                $object->campaings = [];
            } else {
                $object->campaings = ['pathfinder-1'];
            }
            $manager->flush();

            return $this->json($object->campaings);
        }

        return $this->json(['oups']);
    }
}
