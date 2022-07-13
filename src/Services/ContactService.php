<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Contact;
use Swift_Mailer;


class ContactService {

	private $em;
    private $mailer;
    
    public function __construct(
        EntityManagerInterface $em,
        Swift_Mailer $mailer
    ){
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function handleFormPanther($request)
    {
        $errors = [];
        $data = [];
        $twig_param = [];
        $name = $request->request->get("name");
        $email = $request->request->get("email");
        $subject = $request->request->get("subject");
        $from = $request->request->get("from");
        $msg = $request->request->get("message");
        $phone = $request->request->get("phone");

        $data["name"] = $name ;
        $data["email"] = $email ;
        $data["subject"] = $subject ;
        $data["msg"] = $msg ;
        $data["phone"] = $phone ;

        if (!$name) {
            $errors["name"] = "The field must not be empty" ;
        }
        if (!$subject) {
            $errors["subject"] = "The field must not be empty" ;
        }
        if (!$phone) {
            $errors["phone"] = "The field must not be empty" ;
        }
        if (
            !$email || 
            !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            $errors["email"] = "This value is not a valid email address." ;
        }

        $twig_param["data"] = $data ;

        if ($errors) {
            $twig_param["errors"] = $errors ;
        } else {
            $message = (new \Swift_Message($subject))
                ->setFrom($email)
                ->setTo("rafalimananafabrice@gmail.com")
                ->setBody($msg);

        	$this->mailer->send($message);  
        }

        return $twig_param;
    }
}