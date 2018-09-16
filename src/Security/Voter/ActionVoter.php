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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
    private static $actionManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AccessDecisionManagerInterface
     */
    private static $decisionManager;

    /**
     * ActionVoter constructor.
     * @param ActionManager $actionManager
     * @param Request $request
     */
    public function __construct(ActionManager $actionManager, RouterInterface $router, AccessDecisionManagerInterface $decisionManager)
    {
        self::$decisionManager = $decisionManager;
        self::$actionManager = $actionManager;
        $this->router = $router;
    }

    /**
     * @return ActionManager
     */
    public static function getActionManager(): ActionManager
    {
        return self::$actionManager;
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
    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $routeParams = [];
        if (in_array('USE_ROUTE', $attributes)) {
            $attributes[] = 'ROLE_ACTION';
            $r = $subject;
            foreach($subject as $item)
            {
                if (is_string($item))
                    $route = $item;
                if (is_array($item))
                    $routeParams = $item;
            }

        } elseif (! in_array('ROLE_ACTION', $attributes) || ! $subject instanceof Request)
            return VoterInterface::ACCESS_ABSTAIN;
        else {
            $route = $subject->get('_route');
            $routeParams = $subject->get('_route_params');
        }

        return self::getActionResult($route, $routeParams, $token);
    }

    /**
     * isAllowed
     *
     * @param Action $action
     * @param TokenInterface $token
     * @param array $routeParams
     * @return bool
     */
    private static function isAllowed(Action $action, TokenInterface $token, array $routeParams): bool
    {
        if (empty($action->getVoterRouteParams()))
            return true;

        $diff = array_diff($routeParams, $action->getVoterRouteParams());

        if ((count($diff) === 1 && isset($diff['_locale'])) || count($diff) === 0) {
            self::$found = true;
            if (self::$decisionManager->decide($token, self::getRoles($action)))
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
    public static function getRoles(Action $action): array
    {
        $roles = [];
        foreach(self::$actionManager->getAllExistingRoles($action->getId()) as $personRole)
        {
            switch ($personRole->getNameShort()) {
                case 'Reg':
                    $roles[] = 'ROLE_REGISTRAR';
                    break;
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

        return $roles;
    }

    /**
     * @var boolean
     */
    private static $found;

    /**
     * getActionResult
     *
     * @param string $route
     * @param array|null $routeParams
     * @param TokenInterface $token
     * @return int
     * @throws \Exception
     */
    public static function getActionResult(string $route, ?array $routeParams = [], TokenInterface $token): int
    {
        if (!$token->getUser() instanceof UserInterface)
            return VoterInterface::ACCESS_DENIED;

        $actions = self::getActionManager()->getRepository()->findByRoute($route);

        if (empty($actions))
            throw new \Exception(sprintf('No action has been created for route \'%s\'.', $route));

        self::$found = false;
        foreach($actions as $action)
        {
            if (self::isAllowed($action, $token, $routeParams))
                return VoterInterface::ACCESS_GRANTED;
        }

        if (! self::$found)
            throw new \Exception(sprintf('No action has been created for route \'%s\' that has the appropriate parameters.', $route));

        return VoterInterface::ACCESS_DENIED;
    }
}