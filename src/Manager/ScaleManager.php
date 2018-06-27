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
 * Date: 26/06/2018
 * Time: 16:12
 */
namespace App\Manager;

use App\Entity\Scale;
use App\Entity\ScaleGrade;
use App\Manager\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ScaleManager
 * @package App\Manager
 */
class ScaleManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Scale::class;

    /**
     * @var string
     */
    private $transDomain = 'System';

    /**
     * getTabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        return Yaml::parse("
scale_details:
    label: scale.details.tab
    include: School/scale_details.html.twig
    message: scaleDetailsMessage
    translation: School
scale_grade_collection:
    label: scale.grades.tab
    include: School/scale_grade_collection.html.twig
    message: ScaleFieldsMessage
    translation: School
");
    }

    /**
     * findGrades
     *
     * @param $id
     * @return ArrayCollection
     * @throws \Exception
     */
    public function findGrades($id): ArrayCollection
    {
        $this->find($id);
        if (empty($id) || $id === 'Add')
            return new ArrayCollection();
        $result = $this->getEntityManager()->createQueryBuilder()
            ->from(ScaleGrade::class, 'g')
            ->select('g')
            ->where('g.scale = :scale')
            ->setParameter('scale', $this->getEntity())
            ->orderBy('g.sequence', 'ASC')
            ->getQuery()
            ->getResult();
        return new ArrayCollection($result);
    }
}