<?php

declare(strict_types=1);

namespace App\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ContactController extends AbstractController
{
/**
 * @Route("/contact", name="contact", methods={"POST"})
 */
    public function contact(Request $request, MailerInterface $mailer): Response
    {   
        $email = $request->get('email');
        $name = $request->get('lastname');
        $firstname = $request->get('firstname');
        $object = $request->get('message');
        $title = $request->get('title');
        if (empty($email) || empty($name) || empty($firstname) || empty($object) || empty($title)) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }
        $emailMessage = (new Email())
            ->from($email)
            ->to('simon@simon511000.fr ')
            ->subject($title)
            ->text(sprintf("From: %s (%s)\n\n%s", $name,$firstname, $object));

        $mailer->send($emailMessage);

        return new Response(null, Response::HTTP_CREATED);
    }
}