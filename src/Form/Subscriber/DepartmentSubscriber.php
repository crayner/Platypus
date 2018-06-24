<?php
namespace App\Form\Subscriber;

use App\Form\DepartmentMemberType;
use App\Form\DepartmentStaffType;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DepartmentSubscriber implements EventSubscriberInterface
{
	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		// Tells the dispatcher that you want to listen on the form.pre_set_data
		// event and that the preSetData method should be called.
		return array(
			FormEvents::PRE_SUBMIT   => 'preSubmit',
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::SUBMIT => 'submit',
		);
	}

    /**
     * preSubmit
     *
     * @param FormEvent $event
     */
	public function preSubmit(FormEvent $event)
	{
		$data = $event->getData();
		$dept = $event->getForm()->getData();

		if (!empty($data['members']))
		{
			foreach ($data['members'] as $q => $w)
			{
				$data['members'][$q]['department'] = strval($dept->getId());
			}
		}
    }

    /**
     * preSetData
     *
     * @param FormEvent $event
     */
	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (! is_null($data->getId()))
		{
			$data->getMembers()->count();
			$form->add('members', CollectionType::class,
				[
					'entry_type'    => DepartmentStaffType::class,
					'allow_add'     => true,
					'allow_delete'  => true,
					'help'  => 'department.members.help',
                    'route' => 'department_members_manage',
				]
			);
		}

	}


    /**
     * submit
     *
     * @param FormEvent $event
     */
    public function submit(FormEvent $event)
    {
        $dept = $event->getData();

        $members = $dept->getMembers();
        $members = $members->filter(function($entry){
            return empty($entry->getDepartment());
        });
        foreach($members->getIterator() as $member)
            $member->setDepartment($dept);
    }
}