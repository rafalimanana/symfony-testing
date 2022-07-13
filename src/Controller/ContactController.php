<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(
        Request $request, 
        ContactService $service
    ): Response
    {

        $twig_param = [] ;

        if ($request->isMethod("POST")) {
            $twig_param = $service->handleFormPanther($request);
            if (!isset($twig_param['errors'])) {
                $this->addFlash(
                    'succes',
                    'Votre email a bien été envoyé'
                );
            }
        }

        return $this->render('page/contact.html.twig', $twig_param);
    }
}
