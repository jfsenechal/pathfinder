<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\School;
use AfmLibre\Pathfinder\Repository\SchoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SchoolController
 * @package AfmLibre\Pathfinder\Controller
 * @Route("/school")
 */
class SchoolController extends AbstractController
{
    private $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    /**
     * @Route("/", name="pathfinder_school_index")
     */
    public function index(Request $request)
    {
        $schools = $this->schoolRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/school/index.html.twig',
            [
                'schools' => $schools,
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_school_show")
     */
    public function show(School $school)
    {
        return $this->render(
            '@AfmLibrePathfinder/school/show.html.twig',
            [
                'school' => $school,
            ]
        );
    }
}
