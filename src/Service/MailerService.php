<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerService
{
    
    private $mailer;
    
    
    public function __construct( MailerInterface $mailer)
     {
        
        $this->mailer=$mailer;
     }
    
    public function sendEmail(    $to,$message  ): void
    {
        
        $email = (new Email())
            ->from('tayssir.hajjii@gmail.com')
            ->to($to)
            ->subject('Reponse')
            ->text($message);
             
            $this->mailer->send($email);
      
        // ...
    }
}
?>