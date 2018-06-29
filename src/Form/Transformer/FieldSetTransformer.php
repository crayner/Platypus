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
 * Date: 29/06/2018
 * Time: 09:24
 */

namespace App\Form\Transformer;


use App\Entity\ExternalAssessmentField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class FieldSetTransformer implements DataTransformerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * @param EntityManagerInterface $om
     */
    public function __construct(EntityManagerInterface $om)
    {
        $this->om = $om;
    }

    /**
     * transform
     *
     * @param mixed $entity
     * @return string|null
     */
    public function transform($entity)
    {
        if($entity instanceof ExternalAssessmentField)
            return strval($entity->getId());
        return $entity;
    }

    /**
     * reverseTransform
     *
     * @param mixed $entity
     * @return ExternalAssessmentField|null
     */
    public function reverseTransform($id)
    {
        return $this->om->getRepository(ExternalAssessmentField::class)->find($id);
    }
}