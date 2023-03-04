<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FullCalendarController extends AbstractController
{
    #[Route('/full/calendar', name: 'app_full_calendar')]
    public function index(SeanceRepository $seanceRepository): Response
    {
        //modifier cette ligne pour juste récupérer les évènements futures
        $events=$seanceRepository->findAll();
        $rdvs=[];
        foreach($events as $event){
            $rdvs[]=[
                'id'=>$event->getId(),
                'start'=>$event->getDate()->format('Y-m-d H:i:s'),
                'end'=>$event->getDate()->format('Y-m-d H:i:s'),
                //'title'=>$event->getAdresse_Client(),
                'description'=>$event->getDuree(),
                //'backgroundColor'=>$event->getBackgroundColor(()),
                //'borderColor'=>$event=>getBorderColor(),
                //'textcolor'

            ];
            }
            $data=json_encode(($rdvs));
        return $this->render('full_calendar/index.html.twig',compact('data'));
    }
    #[Route('/calendar', name: 'app_calndar')]
    public function Calendar(){
        return $this->render('full_calendar/reservation.html.twig');
    }
    
}
