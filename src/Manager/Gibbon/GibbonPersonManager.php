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
 * User' => 'craig
 * Date' => '12/09/2018
 * Time' => '11:28
 */
namespace App\Manager\Gibbon;

use App\Entity\House;
use App\Entity\Person;
use App\Entity\PersonRole;
use App\Entity\SchoolYear;
use Doctrine\Common\Persistence\ObjectManager;
use Hillrange\Security\Entity\User;

/**
 * Class GibbonPersonManager
 * @package App\Manager\Gibbon
 */
class GibbonPersonManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonPerson';

    /**
     * @var string
     */
    protected $nextGibbonName = '';

    /**
     * @var array
     */
    protected $entityName = [
        Person::class,
        User::class,
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonPersonID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
            'link' => [
                [
                    'field' => 'id',
                    'functions' => [
                        'integer' => '',
                    ],
                    'entityName' => User::class,
                ],
            ],
        ],
        'title' => [
            'field' => 'title',
            'functions' => [
                'trim' => '.',
                'lowercase' => '',
            ],
        ],
        'surname' => [
            'field' => 'surname',
            'functions' => [
                'length' => 30,
            ],
        ],
        'firstName' => [
            'field' => 'first_name',
            'functions' => [
                'length' => 30,
            ],
        ],
        'preferredName' => [
            'field' => 'preferred_name',
            'functions' => [
                'length' => 30,
            ],
        ],
        'officialName' => [
            'field' => 'official_name',
            'functions' => [
                'length' => 150,
            ],
        ],
        'nameInCharacters' => [
            'field' => 'name_in_characters',
            'functions' => [
                'length' => 20,
            ],
        ],
        'gender' => [
            'field' => 'gender',
            'functions' => [
                'length' => 1,
                'lowercase' => '',
            ],
        ],
        'username' => [
            'link' => [
                [
                    'entityName' => User::class,
                    'functions' => [
                        'length' => 64,
                    ],
                    'field' => 'username',
                ],
            ],
            'field' => 'user_id',
            'functions' => [
                'call' => ['function' =>'setUserId', 'options' => []],
            ],
        ],
        'password' => [
            'field' => 'password',
            'entityName' => User::class,
            'functions' => [
                'call' => ['function' =>'combinePassword', 'options' => []],
                'length' => 64,
            ],
            'combineField' => [
                'password',
                'passwordStrong',
            ],

        ],
        'passwordStrong' => [
            'field' => '',
        ],
        'passwordStrongSalt' => [
            'field' => '',
        ],
        'canLogin' => [
            'field' => 'enabled',
            'entityName' => User::class,
            'functions' => [
                'boolean' => '',
            ],
        ],
        'status' => [
            'field' => 'status',
            'functions' => [
                'lowercase' => '',
            ],
        ],
        'gibbonRoleIDPrimary' => [
            'field' => 'primary_role_id',
            'functions' => [
                'call' => ['function' =>'isPersonRole', 'options' => ''],
            ],
        ],
        'gibbonRoleIDAll' => [
            'field' => '',
            'functions' => [],
            'joinTable' => [
                'name' => 'person_secondary_roles',
                'join' => 'person_id',
                'inverse' => 'role_id',
                'call' => ['function' =>'getSecondaryRoles', 'options' => ''],
            ],
        ],
        'email' => [
            'field' => 'email',
            'functions' => [
                'length' => 50,
                'nullable' => '',
            ],
            'link' => [
                [
                    'entityName' => User::class,
                    'field' => 'email',
                    'functions' => [
                        'length' => 50,
                        'nullable' => '',
                    ],
                ],
            ],
        ],
        'emailAlternate' => [
            'field' => 'email_alternate',
            'functions' => [
                'length' => 50,
                'nullable' => '',
            ],
        ],
        'image_240' => [
            'field' => 'photo',
            'functions' => [
                'length' => 150,
                'nullable' => '',
            ],
        ],
        'lastIPAddress' => [
            'field' => '',
        ],
        'lastFailIPAddress' => [
            'field' => '',
        ],
        'failCount' => [
            'field' => '',
        ],
        'address1' => [
            'field' => '',
            'combineField' => [
                'address1',
                'address1District',
                'address1Country',
            ],
            'joinTable' => [
                'name' => 'person_address',
                'join' => 'person_id',
                'inverse' => 'address_id',
                'call' => ['function' => 'getAddressDetails', 'options' => ''],
            ],
        ],
        'address1District' => [
            'field' => '',
        ],
        'address1Country' => [
            'field' => '',
        ],
        'address2' => [
            'field' => '',
            'combineField' => [
                'address2',
                'address2District',
                'address2Country',
            ],
            'joinTable' => [
                'name' => 'person_address',
                'join' => 'person_id',
                'inverse' => 'address_id',
                'call' => ['function' => 'getAddressDetails', 'options' => ''],
            ],
        ],
        'address2District' => [
            'field' => '',
        ],
        'address2Country' => [
            'field' => '',
        ],
        'phone1' => [
            'field' => '',
            'combineField' => [
                'phone1',
                'phone1CountryCode',
                'phone1Type',
            ],
            'joinTable' => [
                'name' => 'person_phone',
                'join' => 'person_id',
                'inverse' => 'phone_id',
                'call' => ['function' => 'getPhoneDetails', 'options' => ''],
            ],
        ],
        'phone1Type' => [
            'field' => '',
        ],
        'phone1CountryCode' => [
            'field' => '',
        ],
        'phone2' => [
            'field' => '',
            'combineField' => [
                'phone2',
                'phone2CountryCode',
                'phone2Type',
            ],
            'joinTable' => [
                'name' => 'person_phone',
                'join' => 'person_id',
                'inverse' => 'phone_id',
                'call' => ['function' => 'getPhoneDetails', 'options' => ''],
            ],
        ],
        'phone2Type' => [
            'field' => '',
        ],
        'phone2CountryCode' => [
            'field' => '',
        ],
        'phone3' => [
            'field' => '',
            'combineField' => [
                'phone3',
                'phone3CountryCode',
                'phone3Type',
            ],
            'joinTable' => [
                'name' => 'person_phone',
                'join' => 'person_id',
                'inverse' => 'phone_id',
                'call' => ['function' => 'getPhoneDetails', 'options' => ''],
            ],
        ],
        'phone3Type' => [
            'field' => '',
        ],
        'phone3CountryCode' => [
            'field' => '',
        ],
        'phone4' => [
            'field' => '',
            'combineField' => [
                'phone4',
                'phone4CountryCode',
                'phone4Type',
            ],
            'joinTable' => [
                'name' => 'person_phone',
                'join' => 'person_id',
                'inverse' => 'phone_id',
                'call' => ['function' => 'getPhoneDetails', 'options' => ''],
            ],
        ],
        'phone4Type' => [
            'field' => '',
        ],
        'phone4CountryCode' => [
            'field' => '',
        ],
        'website' => [
            'field' => 'website',
            'functions' => [
                'length' => 255,
                'nullable' => '',
            ],
        ],
        'languageFirst' => [
            'field' => 'language_first',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'languageSecond' => [
            'field' => 'language_second',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'languageThird' => [
            'field' => 'language_third',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'countryOfBirth' => [
            'field' => 'country_of_birth',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'birthCertificateScan' => [
            'field' => 'birth_certificate_scan',
            'functions' => [
                'length' => 150,
                'nullable' => '',
            ],
        ],
        'ethnicity' => [
            'field' => 'ethnicity',
            'functions' => [
                'length' => 32,
                'nullable' => '',
            ],
        ],
        'citizenship1' => [
            'field' => 'citizenship_1',
            'functions' => [
                'length' => 16,
                'nullable' => '',
            ],
        ],
        'citizenship1Passport' => [
            'field' => 'citizenship_1_passport',
            'functions' => [
                'length' => 16,
                'nullable' => '',
            ],
        ],
        'citizenship1PassportScan' => [
            'field' => 'citizenship_1_passport_scan',
            'functions' => [
                'length' => 150,
                'nullable' => '',
            ],
        ],
        'citizenship2' => [
            'field' => 'citizenship_2',
            'functions' => [
                'length' => 16,
                'nullable' => '',
            ],
        ],
        'citizenship2Passport' => [
            'field' => 'citizenship_2_passport',
            'functions' => [
                'length' => 16,
                'nullable' => '',
            ],
        ],
        'religion' => [
            'field' => 'religion',
            'functions' => [
                'length' => 16,
                'nullable' => '',
            ],
        ],
        'nationalIDCardNumber' => [
            'field' => 'national_card',
            'functions' => [
                'length' => 32,
                'nullable' => '',
            ],
        ],
        'nationalIDCardScan' => [
            'field' => 'national_card_scan',
            'functions' => [
                'length' => 150,
                'nullable' => '',
            ],
        ],
        'residencyStatus' => [
            'field' => 'residential_status',
            'functions' => [
                'length' => 32,
                'nullable' => '',
                'lowercase' => '',
            ],
        ],
        'profession' => [
            'field' => 'profession',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'employer' => [
            'field' => 'employer',
            'functions' => [
                'length' => 30,
                'nullable' => '',
           ],
        ],
        'jobTitle' => [
            'field' => 'job_title',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'emergency1Name' => [
            'field' => '',
        ],
        'emergency1Number1' => [
            'field' => '',
        ],
        'emergency1Number2' => [
            'field' => '',
        ],
        'emergency1Relationship' => [
            'field' => '',
        ],
        'emergency2Name' => [
            'field' => '',
        ],
        'emergency2Number1' => [
            'field' => '',
        ],
        'emergency2Number2' => [
            'field' => '',
        ],
        'emergency2Relationship' => [
            'field' => '',
        ],
        'gibbonHouseID' => [
            'field' => 'house_id',
            'functions' => [
                'call' => ['function' =>'isHouse', 'options' => ''],
            ],
        ],
        'studentID' => [
            'field' => 'student_identifier',
            'functions' => [
                'length' => 20,
                'nullable' => '',
            ],
        ],
        'lastSchool' => [
            'field' => 'last_school',
            'functions' => [
                'length' => 100,
                'nullable' => '',
            ],
        ],
        'nextSchool' => [
            'field' => 'next_school',
            'functions' => [
                'length' => 100,
                'nullable' => '',
            ],
        ],
        'departureReason' => [
            'field' => 'departure_reason',
            'functions' => [
                'length' => 50,
                'nullable' => '',
            ],
        ],
        'transport' => [
            'field' => 'transport',
            'functions' => [
                'length' => 255,
                'nullable' => '',
            ],
        ],
        'transportNotes' => [
            'field' => 'transport_notes',
            'functions' => [
                'nullable' => '',
            ],
        ],
        'calendarFeedPersonal' => [
            'field' => 'personal_calendar_feed',
            'functions' => [
                'nullable' => '',
                'length' => 255,
            ],
        ],
        'viewCalendarSchool' => [
            'field' => 'view_school_calendar',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'viewCalendarPersonal' => [
            'field' => 'view_personal_calendar',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'viewCalendarSpaceBooking' => [
            'field' => 'view_space_booking_calendar',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'lockerNumber' => [
            'field' => 'locker_number',
            'functions' => [
                'length' => 20,
                'nullable' => '',
            ],
        ],
        'vehicleRegistration' => [
            'field' => 'vehicle_registration',
            'functions' => [
                'length' => 20,
                'nullable' => '',
            ],
        ],
        'personalBackground' => [
            'field' => 'personal_background',
            'functions' => [
                'length' => 150,
                'nullable' => '',
            ],
        ],
        'privacy' => [
            'field' => 'privacy',
            'functions' => [
                'nullable' => '',
            ],
        ],
        'dayType' => [
            'field' => 'day_type',
            'functions' => [
                'nullable' => '',
                'length' => 30,
            ],
        ],
        'studentAgreements' => [
            'field' => 'student_agreements',
            'functions' => [
                'nullable' => '',
            ],
        ],
        'googleAPIRefreshToken' => [
            'field' => 'google_refresh_token',
            'functions' => [
                'nullable' => '',
                'length' => 255,
            ],
        ],
        'receiveNotificationEmails' => [
            'field' => 'receive_email_notifications',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'fields' => [
            'field' => 'fields',
            'functions' => [
                'array' => '',
            ],
        ],
        'gibbonSchoolYearIDClassOf' => [
            'field' => 'graduation_school_year_id',
            'functions' => [
                'call' => ['function' =>'isGraduationYear', 'options' => ''],
            ],
        ],
        'lastTimestamp' => [
            'field' => 'last_login',
            'entityName' => User::class,
            'functions' => [
                'datetime' => '',
            ],
        ],
        'passwordForceReset' => [
            'field' => 'credentials_expired',
            'entityName' => User::class,
            'functions' => [
                'boolean' => '',
            ],
        ],
    ];

    /**
     * @var Person
     */
    private $personOne;
    
    /**
     * @var User
     */
    private $userOne;

    /**
     * preTruncate
     *
     * @param string $entityName
     * @param ObjectManager $manager
     */
    public function preTruncate(string $entityName, ObjectManager $manager): void
    {
        if ($entityName === Person::class) {
            if (!empty($this->personOne)) return;
            $repository = $manager->getRepository(Person::class);

            $this->personOne = $repository->createQueryBuilder('p')
                ->where('p.id = :id')
                ->setParameter('id', 1)
                ->getQuery()
                ->getArrayResult();;
        }
        if ($entityName === User::class) {
            if (!empty($this->userOne)) return;
            $repository = $manager->getRepository(User::class);

            $this->userOne = $repository->createQueryBuilder('p')
                ->where('p.id = :id')
                ->setParameter('id', 1)
                ->getQuery()
                ->getArrayResult();;
        }
    }

    /**
     * postTruncate
     *
     * @param string $entityName
     * @param ObjectManager $manager
     */
    public function postTruncate(string $entityName, ObjectManager $manager): void
    {
        if ($entityName === Person::class) {
            if (empty($this->personOne))
                return;
            $fields = $manager->getClassMetadata($entityName);

            foreach ($this->personOne[0] as $field => $value)
                if ($field !== $fields->columnNames[$field]) {
                    $this->personOne[0][$fields->columnNames[$field]] = $value;
                    unset($this->personOne[0][$field]);
                }
            $this->personOne[0]['fields'] = serialize($this->personOne[0]['fields']);
            $this->writeEntityRecords($entityName, $this->personOne);

            $this->personOne = null;
        }
        if ($entityName === User::class) {
            if (empty($this->userOne))
                return;
            $fields = $manager->getClassMetadata($entityName);

            foreach ($this->userOne[0] as $field => $value)
                if ($field !== $fields->columnNames[$field]) {
                    $this->userOne[0][$fields->columnNames[$field]] = $value;
                    unset($this->userOne[0][$field]);
                }

            $this->userOne[0]['last_login'] = $this->userOne[0]['last_login']->format('Y-m-d H:i:s');
            $this->userOne[0]['last_modified'] = $this->userOne[0]['last_modified']->format('Y-m-d H:i:s');
            $this->userOne[0]['created_on'] = $this->userOne[0]['created_on']->format('Y-m-d H:i:s');
            $this->userOne[0]['direct_roles'] = serialize($this->userOne[0]['direct_roles']);
            $this->userOne[0]['user_settings'] = serialize($this->userOne[0]['user_settings']);
            $this->userOne[0]['groups'] = serialize($this->userOne[0]['groups']);
            $this->userOne[0]['expired'] = $this->userOne[0]['expired'] ? '1' : '0';
            $this->userOne[0]['credentials_expired'] = $this->userOne[0]['credentials_expired'] ? '1' : '0';
            $this->writeEntityRecords($entityName, $this->userOne);
            $this->userOne = null;
        }
    }

    /**
     * @var array
     */
    private $personRoles;

    /**
     * isPersonRole
     *
     * @param $value
     * @param $options
     * @return int|null
     */
    public function isPersonRole($value): ?int
    {
         if (isset($this->personRoles[intval($value)]))
             return intval($value);

         $role = $this->getObjectManager()->getRepository(PersonRole::class)->find(intval($value));
         if ($role instanceof PersonRole)
         {
             $this->personRoles[intval($value)] = $role;
             return intval($value);
         }
         return null;
    }

    /**
     * getSecondaryRoles
     *
     * @param $value
     * @return array
     */
    public function getSecondaryRoles($value): array
    {
        $poss = explode(',', $value);
        $result = [];
        foreach($poss as $r) {
            $r = intval($r);
            if ($this->isPersonRole($r))
                if (!in_array($r, $result))
                    $result[] = $r;
        }
        return $result;
    }

    /**
     * getAddressDetails
     *
     * @param $value
     * @return array
     */
    public function getAddressDetails($value)
    {
        if (empty($value[0]))
            return [];

        trigger_error('The address needs handling...');
    }

    /**
     * getPhoneDetails
     *
     * @param $value
     * @return array
     */
    public function getPhoneDetails($value)
    {
        if (empty($value[0]))
            return [];

        trigger_error('The phone needs handling...');
    }

    /**
     * @var array
     */
    private $houses;

    /**
     * isHouse
     *
     * @param $value
     * @param $options
     * @return int|null
     */
    public function isHouse($value): ?int
    {
        if (isset($this->houses[intval($value)]))
            return intval($value);

        $role = $this->getObjectManager()->getRepository(House::class)->find(intval($value));
        if ($role instanceof House)
        {
            $this->houses[intval($value)] = $role;
            return intval($value);
        }
        return null;
    }
    
    /**
     * @var array
     */
    private $graduationYears;
    
    /**
     * isGraduationYear
     *
     * @param $value
     * @param $options
     * @return int|null
     */
    public function isGraduationYear($value): ?int
    {
        if (isset($this->graduationYears[intval($value)]))
            return intval($value);

        $role = $this->getObjectManager()->getRepository(SchoolYear::class)->find(intval($value));

        if ($role instanceof SchoolYear)
        {
            $this->graduationYears[intval($value)] = $role;
            return intval($value);
        }

        return null;
    }

    /**
     * combinePassword
     *
     * @param $value
     * @return string
     */
    public function combinePassword($value): string
    {
        return trim(implode('', $value));
    }

    /**
     * setUserId
     *
     * @param $value
     * @param $options
     * @return int
     */
    public function setUserId($value, $options): int
    {
        return intval($options['datum']['gibbonPersonID']);
    }


    /**
     * postFieldData
     *
     * @param string $entityName
     * @param array $newData
     * @param string $field
     * @param $value
     * @return array
     */
    public function postFieldData(string $entityName, array $newData, string $field, $value): array
    {

        if ($field === 'username' && $entityName === User::class)
        {
            $newData['username_canonical'] = $value;
        }
        if ($field === 'email' && $entityName === User::class)
        {
            $newData['email_canonical'] = $value;
        }
        return $newData;
    }
}