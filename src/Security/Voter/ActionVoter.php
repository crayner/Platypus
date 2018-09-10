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
 * Date: 10/09/2018
 * Time: 11:37
 */
namespace App\Security\Voter;

use App\Entity\Action;
use App\Manager\ActionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ActionVoter
 * @package App\Security\Voter
 */
class ActionVoter implements VoterInterface
{
    /**
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * ActionVoter constructor.
     * @param ActionManager $actionManager
     * @param Request $request
     */
    public function __construct(ActionManager $actionManager, RouterInterface $router, AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
        $this->actionManager = $actionManager;
        $this->router =$router;
    }

    /**
     * vote
     *
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     * @return int
     * @throws \Exception
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (empty($subject) || ! $subject instanceof Request || ! in_array('ROLE_ACTION', $attributes))
            return VoterInterface::ACCESS_ABSTAIN;

        if (!$token->getUser() instanceof UserInterface)
            return VoterInterface::ACCESS_DENIED;

        $route = $subject->get('_route');
        $routeParams = $subject->get('_route_params');
        $actions = $this->actionManager->getRepository()->findByRoute($route);
        if (empty($actions)) {
            throw new \Exception(sprintf('No action has been created for route \'%s\'.', $route));
        }
        foreach($actions as $action)
        {
            if ($this->isAllowed($action, $token, $routeParams))
                return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;

    }

    /**
     * isAllowed
     *
     * @param Action $action
     * @param TokenInterface $token
     * @param array $routeParams
     * @return bool
     */
    private function isAllowed(Action $action, TokenInterface $token, array $routeParams): bool
    {
        $diff = array_diff($routeParams, $action->getRouteParams());
        if (count($diff) === 1 && isset($diff['_locale'])) {
            if ($this->decisionManager->decide($token, [$action->getRole()]))
                return true;
            if ($this->decisionManager->decide($token, $this->getRoles($action)))
                return true;
        }
        return false;
    }

    /**
     * getRoles
     *
     * @param Action $action
     * @return array
     */
    private function getRoles(Action $action): array
    {
        $roles = [];
        foreach($action->getPersonRoles() as $personRole)
        {
            switch ($personRole->getNameShort()) {
                case 'Adm':
                    $roles[] = 'ROLE_ADMIN';
                    break;
                case 'Tcr':
                    $roles[] = 'ROLE_TEACHER';
                    break;
                case 'Std':
                    $roles[] = 'ROLE_STUDENT';
                    break;
                case 'Prt':
                    $roles[] = 'ROLE_PARENT';
                    break;
                case 'SSt':
                    $roles[] = 'ROLE_STAFF';
                    break;
            }
        }
        dump($roles);
        return $roles;
    }
}