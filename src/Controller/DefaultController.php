<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\TasksRepository;
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

        $repository = $this->getDoctrine()->getRepository(Tasks::class);
        $tasks = $repository->findAll();

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/complete/{id}", name="complete")
     */
    public function complete(Tasks $task, EntityManagerInterface $manager): Response
    {
        $task->setCompletionDate(new \DateTime());
        $manager->persist($task);
        $manager->flush();


        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Tasks $task, EntityManagerInterface $manager,Request $request): Response
    {
        $manager->remove($task);
        $manager->flush();

        $referer=filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL); // Chope le referer pour la redirection, filtre la variable pour sécuriser

        return $this->redirect($referer);
    }

    /**
     * @Route("/showcompleted", name="showcompleted")
     */
    public function showcompleted(TasksRepository $tasksRepository): Response
    {

        return $this->render('default/completed.html.twig', [
            "tasksCompleted" => $tasksRepository->findCompleted(),
        ]);
    }
}
