<?php

namespace App\Controller;

use App\Entity\Tareas;
use App\Entity\TareasUsuario;
use App\Entity\Usuarios;
use App\Form\TareasUsuariosType;
use App\Repository\TareasUsuarioRepository;
use App\Repository\TareasRepository;
use App\Repository\UsuariosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TareasUsuariosEditType;

class TareasUsuarioController extends AbstractController
{

    #[Route('/tareas/usuarios/confirmacion', name: 'app_tareas_usuario_success')]
    public function success(Request $request, TareasUsuarioRepository $tareaRepoUsu, UsuariosRepository $usuariosRepository): Response
    {
        $session = $request->getSession();
        $idUsuario = $session->get('id');

        $tareasUsuario = $tareaRepoUsu->findBy(['idUsuario' => $idUsuario]);
        $usuarioEncontrado = $usuariosRepository->findOneBy(['id' => $idUsuario]);

        return $this->render('tareas_usuario/index.html.twig', [
            'datos' => $tareasUsuario,
            'usuario' => $usuarioEncontrado,
        ]);
    }


    #[Route('/tareas/anyadir', name: 'app_tareas_usuario')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $tareas = new Tareas();
        $tareaUsuario = new TareasUsuario();

        $formularioTareas = $this->createForm(TareasUsuariosType::class, $tareas);

        $formularioTareas->handleRequest($request);
        if ($formularioTareas->isSubmitted() && $formularioTareas->isValid()) {
            $session = $request->getSession();
            $idUsuario = $session->get('id');

            $usuario = $entityManager->getRepository(Usuarios::class)->find($idUsuario);

            $tareaUsuario->setIdTarea($tareas);
            $tareaUsuario->setIdUsuario($usuario);

            $entityManager->persist($tareas);
            $entityManager->persist($tareaUsuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_tareas_usuario_success');
        }

        return $this->render('tareas_usuario/form.html.twig', [
            'formulario' => $formularioTareas->createView(),
        ]);
    }

    #[Route('/borrar/{id}', name: 'borrar_tareas')]
    public function borrarTareas($id, Request $request, TareasRepository $tareaRepo, EntityManagerInterface $entityManager)
    {
        $resultado = $tareaRepo->find($id);

        $tareaUsuarioRepo = $entityManager->getRepository(TareasUsuario::class);
        $tareasUsuarios = $tareaUsuarioRepo->findBy(['idTarea' => $resultado]);

        foreach ($tareasUsuarios as $tareaUsuario) {
            $entityManager->remove($tareaUsuario);
        }

        $entityManager->remove($resultado);
        $entityManager->flush();

        return $this->redirectToRoute('app_tareas_usuario_success'); // Cambia esto a la ruta deseada
    }

    #[Route('/borrar/{id}', name: 'borrar_tareas')]
    public function borrarTareasT($id, Request $request, TareasRepository $tareaRepo, TareasUsuarioRepository $tareaUsuarioRepo, EntityManagerInterface $entityManager)
    {
        $resultado = $tareaRepo->find($id);
    
        if (!$resultado) {
            return $this->redirectToRoute('some_error_route');
        }
    
        $tareasUsuarios = $tareaUsuarioRepo->findBy(['idTarea' => $resultado]);
    
        foreach ($tareasUsuarios as $tareaUsuario) {
            $entityManager->remove($tareaUsuario);
        }
    
        $entityManager->remove($resultado);
        $entityManager->flush();
    
        $users = $entityManager->getRepository(Usuarios::class)->findAll();  // Fetch users
        $tareas = $entityManager->getRepository(Tareas::class)->findAll();    // Fetch tasks
    
        return $this->render('usuarios/comprobrar.html.twig', [
            'usuario' => $users,
            'tareas' => $tareas,
        ]);
    }


    #[Route('/borrar/usuario/{id}', name: 'borrar_usuarios')]
    public function borrarUsuario($id, Request $request,UsuariosRepository $usuariosRepository ,TareasRepository $tareaRepo, TareasUsuarioRepository $tareaUsuarioRepo, EntityManagerInterface $entityManager)
    {
        $resultado = $usuariosRepository->find($id);
    
        $usuarios = $usuariosRepository->findBy(['id' => $resultado->getId()]);
        $tareasUsuarios = $tareaUsuarioRepo->findBy(['usuario' => $usuarios]);

     
    
        foreach ($usuarios as $usuario) {
            $entityManager->remove($usuario);
        }
    
        $entityManager->remove($resultado);
        $entityManager->flush();
    
        $users = $entityManager->getRepository(Usuarios::class)->findAll();  // Fetch users
        $tareas = $entityManager->getRepository(Tareas::class)->findAll();    // Fetch tasks
    
        return $this->render('usuarios/comprobrar.html.twig', [
            'usuario' => $users,
            'tareas' => $tareas,
        ]);
    }


    #[Route(path: '/buscar/tarea', name: 'buscar_tareas')]
    public function busqueda(Request $request, TareasRepository $tareaRepository, TareasUsuarioRepository $tareaRepoUsu, UsuariosRepository $usuariosRepository): Response
    {
        $session = $request->getSession();
        $idUsuario = $session->get('id');
        $usuarioEncontrado = $usuariosRepository->findOneBy(['id' => $idUsuario]);
    
        $query = $request->query->get('q', '');
    
        if (empty($query)) {
            $tareas = $tareaRepoUsu->findBy(['idUsuario' => $idUsuario]); 
        } else {
            $tareas = $tareaRepository->buscarTarea($query, $idUsuario); 
        }
    
        return $this->render('tareas_usuario/index.html.twig', [
            'datos' => $tareas,
            'query' => $query,
            'usuario' => $usuarioEncontrado,
        ]);
    }

    #[Route('/tareas/editar/{id}', name: 'editar_tareas')]
    public function editarTareas($id, Request $request, TareasRepository $tareaRepo, EntityManagerInterface $entityManager)
    {
        $resultado = $tareaRepo->find($id);

        $formularioTareas = $this->createForm(TareasUsuariosEditType::class, $resultado);
        $formularioTareas->handleRequest($request);

        if ($formularioTareas->isSubmitted() && $formularioTareas->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tareas_usuario_success');
        }

        return $this->render('tareas_usuario/formEdit.html.twig', [
            'formulario' => $formularioTareas->createView(),
            'id' => $id,
        ]);
    }
}
