<?php
namespace App\Manager\Traits;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait TabTrait
{
    /**
     * Use this method as a callable to test if the tab is to be displayed.
     * @param string|array $method
     * @return bool
     */
    public function isDisplay($method = []): bool
    {
        if (is_string($method)) {
            if (empty($method))
                return true;
            if (method_exists($this, $method))
                return $this->$method();

            return false;
        }
        if (is_string($method['method']) && is_array($method['with']))
        {
            if (method_exists($this, $method['method'])) {
                $func = $method['method'];
                return $this->$func($method['with']);
            }
            return false;
        }
        throw new \InvalidArgumentException('The arguments passed to the tab display test must be an array = [method => name, with => [parameterArray]] or a string of methodName.');
    }

    /**
     * getTabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        if (! property_exists($this, 'tabs'))
            throw new InvalidOptionsException('The class \''.get_class($this).'\' must define \'$tabs\' as an array');
        if (! is_array($this->tabs))
            throw new InvalidOptionsException('The class \''.get_class($this).'\' must define \'$tabs\' as an array');


        foreach($this->tabs as $q=>$tab) {
            $resolver = new OptionsResolver();
            $resolver->setRequired(
                [
                    'include',
                    'label',
                    'message',
                    'name',
                ]
            );
            $resolver->setDefaults(
                [
                    'display' => true,
                    'translation' => false,
                ]
            );
            $this->tabs[$q] = $resolver->resolve($tab);
        }
        return $this->tabs;
    }
}