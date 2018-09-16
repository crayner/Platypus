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
 * Date: 16/09/2018
 * Time: 14:52
 */
namespace App\Security\Voter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Class MenuVoter
 * @package App\Security\Voter
 */
class MenuVoter implements VoterInterface
{
    /**
     * vote
     *
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     * @return int
     * @throws \Exception
     */
    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        if (! in_array('ROLE_MENU', $attributes))
            return VoterInterface::ACCESS_ABSTAIN;

        $resolver = new OptionsResolver();
        $resolver->setRequired(['route']);
        $resolver->setDefaults([
            'routeParams' => [],
        ]);
        $resolver->setAllowedTypes('route', 'string');
        $resolver->setAllowedTypes('routeParams', 'array');

        $subject = $resolver->resolve($subject);

        return ActionVoter::getActionResult($subject['route'], $subject['routeParams'], $token);
    }
}