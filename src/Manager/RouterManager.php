<?php
namespace App\Manager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RouterManager
{
	/**
	 * @var string
	 */
	private $currentRoute;

	/**
	 * @var RequestStack
	 */
	private $request;

	/**
	 * RouterManager constructor.
	 */
	public function __construct(RequestStack $request)
	{
		if (! is_null($request->getCurrentRequest()))
			$this->setCurrentRoute($request->getCurrentRequest());

		$this->request = $request;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCurrentRoute(): ?string
	{
		$this->setCurrentRoute();

		return $this->currentRoute;
	}

	public function setCurrentRoute(Request $request = null)
	{
		if (! empty($this->currentRoute))
			return $this;

		if (is_null($request))
			$request = $this->request->getCurrentRequest();

		if ($request instanceof Request)
			$this->currentRoute = $request->get('_route');

		while (empty($this->currentRoute) && ! empty($this->request))
        {
            $this->request = $this->request->getParentRequest();
            $this->currentRoute = $this->request->get('_route');
        }

		return $this;
	}
}