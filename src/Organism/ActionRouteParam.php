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
 * Date: 11/09/2018
 * Time: 08:16
 */
namespace App\Organism;

/**
 * Class ActionRouteParamTransformer
 * @package App\Organism
 */
class ActionRouteParam
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ActionRouteParam
     */
    public function setId(?int $id): ActionRouteParam
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ActionRouteParam
     */
    public function setName(?string $name): ActionRouteParam
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @var string|null
     */
    private $value;

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return ActionRouteParam
     */
    public function setValue(?string $value): ActionRouteParam
    {
        $this->value = $value;
        return $this;
    }
}