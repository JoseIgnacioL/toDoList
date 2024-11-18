<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TareasController extends AbstractController
{
    #[Route('/tareas', name: 'app_tareas')]
    public function index(): Response
    {
        return $this->render('tareas/index.html.twig', [
            'controller_name' => 'TareasController',
        ]);
    }
}
