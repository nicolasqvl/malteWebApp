<?php

namespace App\EventSubscriber;

use App\Entity\Kit;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['createQrcode'],
            BeforeEntityDeletedEvent::class => ['deleteQrcodeFile']
        ];
    }

    public function createQrcode(AfterEntityPersistedEvent $event)
    {

        $entity = $event->getEntityInstance();

        if (!($entity instanceof Kit)) return;

        $kitId = $entity->getId();
        $kitName = $entity->getName();

        // ----- Create QrCode
        // Path of uploads file
        $path = dirname(__DIR__, 2).'/public/';

        // set qrcode
        $result = $this->builder
            ->data($kitId)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->labelText("Lot: {$kitName} ID: {$kitId}")
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->build()
        ;

        //generate name
        $namePng = "{$kitName}" . '.png';

        //Save img png
        $result->saveToFile($path.'uploads/'.$namePng);

        return $result->getDataUri();
    }

    public function deleteQrcodeFile(BeforeEntityDeletedEvent $event){

        $entity = $event->getEntityInstance();
        // Path of uploads file
        $path = dirname(__DIR__, 2).'/public/';

        if (!($entity instanceof Kit)) return;

        $nameFile = $entity->getQrName();
        $file = $path.'uploads/'.$nameFile;

        // Delete Qrcode file in uploads folder
        if(file_exists ($file)) return unlink($file);

    }
}
