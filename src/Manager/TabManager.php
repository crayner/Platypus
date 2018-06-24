<?php
namespace App\Manager;

use App\Manager\Interfaces\TabManagerInterface;
use App\Manager\Traits\TabTrait;

abstract class TabManager implements TabManagerInterface
{
    use TabTrait;
}