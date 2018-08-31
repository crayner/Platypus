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
 * Date: 31/08/2018
 * Time: 10:29
 */
namespace App\Manager;

use App\Entity\Locality;
use App\Manager\Traits\EntityTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class LocalityManager
 * @package App\Manager
 */
class LocalityManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Locality::class;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     * @return LocalityManager
     */
    public function setValidator(ValidatorInterface $validator): LocalityManager
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * save
     *
     * @param Locality $entity
     * @return LocalityManager
     */
    public function save(Locality $entity): LocalityManager
    {
        $errors = $this->getValidator()->validate(
            $entity,
            new \App\Validator\Locality(['rule' => $this->getCountryValidationRule($entity->getCountry())])
        );

        foreach($errors as $error)
            $this->getMessageManager()->add('danger', $error->getMessage());

        if ($errors->count() === 0)
            $this->setEntity($entity)->saveEntity();

        return $this;
    }

    /**
     * getCountryValidationRule
     *
     * @param string $country
     * @return array
     */
    private function getCountryValidationRule(string $country): array
    {
        $rules = [
            'AU' => [
                'postCode' => [
                    'required' => true,
                    'regex' => '/^[0-9]{4}$/'
                ],
                'territory' => [
                    'required' => true,
                    'regex' => '/^(NSW|VIC|QLD|TAS|SA|WA|NT|ACT)$/'
                ],
            ],
        ];

        return empty($rules[$country]) ? [] : $rules[$country];
    }
}