<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $task = new Tasks();
        
        $form = $this->createForm(TasksType::class, $task); // Cette manière si on a utilisé une classe Type dédié. Sinon on crée une Tasks puis createFormBuilder->add()....->getForm()

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
