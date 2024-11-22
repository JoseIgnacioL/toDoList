<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Usuarios;
use App\Entity\Tareas;
use App\Form\UsuariosType;
use App\Form\LoginType;
use App\Form\BusquedaType;
use App\Repository\UsuariosRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\TareasUsuarioRepository;



class UsuariosController extends AbstractController
{
    public static $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    
    public static  function listarUsuarios()
    {
        $usuarios = self::$entityManager->getRepository(Usuarios::class)->findAll();

        return $usuarios;
    }

    public static function listarTareas() {
        $tareas = self::$entityManager->getRepository(Tareas::class)->findAll();
        
        return $tareas;

    }

    #[Route('/iniciar-sesion', name: 'iniciar_sesion', methods: ['GET', 'POST'])]
    function index(Request $request, UsuariosRepository $usuariosRepository, UserPasswordHasherInterface $passwordHasher, TareasUsuarioRepository $tareaRepoUsu)
    {
        $usuario = new Usuarios();
        $formularioRegistro = $this->createForm(LoginType::class, $usuario);

        $formularioRegistro->handleRequest($request);

        if ($formularioRegistro->isSubmitted() && $formularioRegistro->isValid()) {

            $loginUsuario = $formularioRegistro["coreo"]->getData();
            $loginPassword = $formularioRegistro["contrasenyia"]->getData();

            $usuarioEncontrado = $usuariosRepository->findOneBy(['coreo' => $loginUsuario]);

            $session = new Session();
            $session->set('id', $usuarioEncontrado->getId());

            $tareasUsuario = $tareaRepoUsu->findBy(['idUsuario' => $session->get('id')]);

            $rol = $usuarioEncontrado->getRol();

            $users = $this->listarUsuarios();
            $tareas = $this->listarTareas();

            if ($usuarioEncontrado && $passwordHasher->isPasswordValid($usuarioEncontrado, $loginPassword)) {
                if ($rol == "admin") {
                    return $this->render('usuarios/comprobrar.html.twig', [
                        'usuario' => $users,
                        'tareas' => $tareas,
                    ]);
                } else {
                    return $this->render('tareas_usuario/index.html.twig', [
                        'datos' => $tareasUsuario,
                        'usuario' => $usuarioEncontrado,
                    ]);
                }
            } else {
                $this->addFlash('error', 'El usuario o la contraseña son incorrectas');
            }
        }


        return $this->render('usuarios/loginUsuarios.html.twig', [
            'formulario' => $formularioRegistro->createView(),
        ]);
    }
    #[Route('usuario/nuevo', name: 'usuario_nuevo', methods: ['GET', 'POST'])]
    function nuevoUsuario(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface  $passwordHasher)
    {
        $usuario = new Usuarios();
        $formulario = $this->createForm(UsuariosType::class, $usuario);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $correo = $formulario->get('coreo')->getData();

            $usuarioExistente = $entityManager->getRepository(Usuarios::class)->findOneBy(['coreo' => $correo]);

            if ($usuarioExistente) {
                $this->addFlash('error', 'El correo electrónico ya está registrado.');

                return $this->render('usuarios/registrarUsuarios.html.twig', [
                    'form' => $formulario->createView()
                ]);
            }

            $plainPassword = $formulario->get('contrasenyia')->getData();
            if ($plainPassword === null) {
                throw new \RuntimeException('La contraseña no puede ser nula.');
            }

            $hashedPassword = $passwordHasher->hashPassword($usuario, $plainPassword);
            $usuario->setContrasenyia($hashedPassword);

            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('iniciar_sesion');
        }

        return $this->render('usuarios/registrarUsuarios.html.twig', [
            'form' => $formulario->createView()
        ]);
    }


    #[Route('usuario/cerrar-sesion', name: 'cerrar_sesion')]
    public function cerrarSesion(Request $request, TokenStorageInterface $tokenStorage)
    {
        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('iniciar_sesion');
    }
}
