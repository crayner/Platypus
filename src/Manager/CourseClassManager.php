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
 * Date: 21/09/2018
 * Time: 12:48
 */
namespace App\Manager;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use Doctrine\DBAL\Connection;

/**
 * Class CourseClassManager
 * @package App\Manager
 */
class CourseClassManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = CourseClass::class;

    /**
     * @var array
     */
    protected $tabs = [
        [
            'name' => 'details',
            'label' => 'Details',
            'include' => 'Course/class_details.html.twig',
            'message' => 'courseClassDetailsMessage',
            'translation' => 'Course',
        ],
        [
            'name' => 'participants',
            'label' => 'Participants',
            'include' => 'Course/class_participants.html.twig',
            'message' => 'courseClassParticipantsMessage',
            'translation' => 'Course',
        ],
    ];

    /**
     * removeClassParticipant
     *
     * @param int $id
     * @return CourseClassManager
     * @throws \Exception
     */
    public function removeClassParticipant(int $id): CourseClassManager
    {
        $person = $this->getRepository(Person::class)->find($id);

        if ($person instanceof Person && $this->getEntity() instanceof CourseClass)
        {
            $courseClassPerson = $this->getRepository(CourseClassPerson::class)->findOneBy(['person' => $person, 'courseClass' => $this->getEntity()]);

            if ($courseClassPerson instanceof CourseClassPerson || false) {
                $this->getEntityManager()->remove($courseClassPerson);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->addMessage('success', 'Removed %{person} from %{class}', ['%{person}' => $person->getFullName(), '%{class}' => $this->getEntity()->getName()], 'Course');
                return $this;
            }
            $this->getMessageManager()->addMessage('warning', 'I did not find a participant to remove!', [], 'Course');
            return $this;
        }
        $this->getMessageManager()->addMessage('danger', 'The class or person was not found and removal do not happen!', [], 'Course');
        return $this;
    }
}