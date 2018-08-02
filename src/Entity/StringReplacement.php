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
 * Date: 2/08/2018
 * Time: 11:47
 */
namespace App\Entity;


class StringReplacement
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getReplaceModeList(): array
    {
        return self::$replaceModeList;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Action
     */
    public function setId(?int $id): StringReplacement
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $original;

    /**
     * @return null|string
     */
    public function getOriginal(): ?string
    {
        return $this->original;
    }

    /**
     * @param null|string $original
     * @return StringReplacement
     */
    public function setOriginal(?string $original): StringReplacement
    {
        $this->original = $original;
        return $this;
    }

    /**
     * @var null|string
     */
    private $replacement;

    /**
     * @return null|string
     */
    public function getReplacement(): ?string
    {
        return $this->replacement;
    }

    /**
     * @param null|string $replacement
     * @return StringReplacement
     */
    public function setReplacement(?string $replacement): StringReplacement
    {
        $this->replacement = $replacement;
        return $this;
    }

    /**
     * @var array
     */
    private static $replaceModeList = [
        'whole',
        'partial',
    ];

    /**
     * @var null|string
     */
    private $replaceMode = 'whole';

    /**
     * @return null|string
     */
    public function getReplaceMode(): string
    {
        return $this->replaceMode === 'partial' ? 'partial' : 'whole';
    }

    /**
     * @param null|string $replaceMode
     * @return StringReplacement
     */
    public function setReplaceMode(?string $replaceMode): StringReplacement
    {
        $this->replaceMode = $replaceMode === 'partial' ? 'partial' : 'whole';
        return $this;
    }

    /**
     * @var boolean
     */
    private $caseSensitive = true;

    /**
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive ? true : false ;
    }

    /**
     * @param bool $caseSensitive
     * @return StringReplacement
     */
    public function setCaseSensitive(bool $caseSensitive): StringReplacement
    {
        $this->caseSensitive = $caseSensitive ? true : false ;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $priority = 0;

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority ?: 0;
    }

    /**
     * @param int|null $priority
     * @return StringReplacement
     */
    public function setPriority(?int $priority): StringReplacement
    {
        $this->priority = $priority ?: 0;
        $this->priority = $this->priority > 100 ? 100 : $this->priority;
        return $this;
    }

    public function __toString()
    {
        return $this->getOriginal();
    }
}