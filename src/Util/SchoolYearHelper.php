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
 * Date: 23/06/2018
 * Time: 08:11
 */
namespace App\Util;

use App\Entity\SchoolYear;
use App\Manager\SchoolYearManager;
use App\Repository\SchoolYearRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SchoolYearHelper
 * @package App\Util
 */
class SchoolYearHelper
{
    /**
     * @var \App\Repository\SchoolYearRepository|\Doctrine\Common\Persistence\ObjectRepository 
     */
    private static $schoolYearRepository;

    /**
     * SchoolYearHelper constructor.
     *
     * @param SchoolYearManager $manager
     * @param UserHelper $userHelper
     * @throws \Exception
     */
    public function __construct(SchoolYearManager $manager, UserHelper $userHelper)
    {
        self::$schoolYearRepository = $manager->getRepository(SchoolYear::class);
    }

    /**
     * @var SchoolYear|null
     */
    private static $currentSchoolYear;
    
    /**
     * getCurrentSchoolYear
     *
     * @return SchoolYear|null
     */
    public static function getCurrentSchoolYear(): ?SchoolYear
    {
        if (! is_null(self::$currentSchoolYear))
            return self::$currentSchoolYear;

        UserHelper::getCurrentUser();
        if (UserHelper::getCurrentUser() instanceof UserInterface)
        {
            $settings = UserHelper::getCurrentUser()->getUserSettings();
            if (isset($settings['schoolYear']))
                self::$currentSchoolYear = self::$schoolYearRepository->findOneBy(['id' => $settings['schoolYear']]);
            else
                self::$currentSchoolYear = self::$schoolYearRepository->findOneBy(['status' => 'current']);
        }
        else
            self::$currentSchoolYear = self::$schoolYearRepository->findOneBy(['status' => 'current']);

        return self::$currentSchoolYear;
    }

    /**
     * @return SchoolYearRepository
     */
    public static function getSchoolYearRepository(): SchoolYearRepository
    {
        return self::$schoolYearRepository;
    }

    /**
     * @var SchoolYear|null
     */
    private static $nextSchoolYear;

    /**
     * getNextSchoolYear
     *
     * @param SchoolYear|null $schoolYear
     * @return SchoolYear|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public static function getNextSchoolYear(SchoolYear $schoolYear = null): ?SchoolYear
    {
        if (self::$nextSchoolYear && is_null($schoolYear))
            return self::$nextSchoolYear;

        $schoolYear = $schoolYear ?: self::getCurrentSchoolYear();

        self::$nextSchoolYear = self::getSchoolYearRepository()->createQueryBuilder('y')
            ->where('y.firstDay > :firstDay')
            ->setParameter('firstDay', $schoolYear->getFirstDay()->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return self::$nextSchoolYear;
    }
}
