<?php
namespace App\Twig\Extension;

use App\Manager\MenuManager;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;

class MenuExtension extends AbstractExtension
{
    /**
     * @var MenuManager
     */
    private $manager;

    /**
     * @var Router
     */
    private $router;

    /**
     * @return string
     */
    public function getName()
    {
        return 'menu_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('sectionMenuTest', array($this->manager, 'sectionMenuTest')),
            new \Twig_SimpleFunction('routeExists', array($this, 'routeExists')),
        ];
    }

    public function __construct(MenuManager $manager, RouterInterface $router)
    {
        $this->manager = $manager;
        $this->router = $router;
    }

    /**
     * @param   string $value
     *
     * @return  bool
     */
    public function routeExists($value): bool
    {
        return null !== $this->router->getRouteCollection()->get($value);
    }
}