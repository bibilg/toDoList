<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $task = new Tasks();
        
        $form = $this->createForm(TasksType::class, $task); // Cette manière si on a utilisé une classe Type dédié. Sinon on crée une Tasks puis createFormBuilder->add()....->getForm()

        $form->handleRequest($request); // Permets de traiter les données du formulaire 

        if($form->isSubmitted() && $form->isValid()){ // Si le formulaire est envoyé et valide 

            $task->setCreationDate(new \DateTime());

            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
