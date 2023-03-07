<?php 
namespace App\EventSubscriber;
use App\Repository\SeanceRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class CalendarSubscriber implements EventSubscriberInterface{
    private $SeanceRepository;
    private $router;
    public function __construct(
        SeanceRepository $seanceRepository,
        UrlGeneratorInterface $router
    )
    {
        $this->SeanceRepository=$seanceRepository;
        $this->router=$router;
    }
    public static function getSubscribedEvents()
    {
        return[
            CalendarEvents::SET_DATA=>'onCalendarSetData',
        ];
    }
    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
    
        $seances = $this->SeanceRepository
            ->createQueryBuilder('seance')
            ->where('seance.date BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();
    
        foreach ($seances as $seance) {
            $seanceEvent = new Event(
                $seance->getAdresse_Client(),
                $seance->getDate(),
                $seance->getDate()
            );
    
            $seanceEvent->addOption(
                'url',
                $this->router->generate('detail_seance', [
                    'id' => $seance->getId(),
                ])
            );
    
            // Add the event to the calendar
            $calendar->addEvent($seanceEvent);
        }
    }
}