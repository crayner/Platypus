<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 29/10/2018
 * Time: 14:44
 */
namespace App\Form\Subscriber;

use App\Entity\TimetableColumn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class TimetableColumnInRowSubscriber
 * @package App\Form\Subscriber
 */
class TimetableColumnInRowSubscriber implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    /**
     * @var TimetableColumn|null
     */
    private $column;

    /**
     * TimetableColumnInRowSubscriber constructor.
     * @param TimetableColumn|null $column
     */
    public function __construct(?TimetableColumn $column)
    {
        $this->column = $column;
    }

    /**
     * preSubmit
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        if ($this->column instanceof TimetableColumn)
            $event->setData($this->column->getId());
    }
}