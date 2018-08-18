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
 * Date: 18/08/2018
 * Time: 09:38
 */
namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaginationController
{
    /**
     * setPaginationCache
     *
     * @param Request $request
     * @param string $name
     * @param int $limit
     * @return JsonResponse
     * @Route("/pagination/cache/{name}/{limit}/{offset}/", name="pagination_cache")
     */
    public function setPaginationCache(Request $request, string $name, int $limit, int $offset)
    {
        $session = $request->getSession();

        $pagination = $session->get('pagination');

        $data = $pagination[$name] ?: [];


        $pagination[$name] = [];
        $data['limit'] = $limit;
        $data['offset'] = $offset;

        $pagination[$name] = $data;

        $session->set('pagination', $pagination);

        return new JsonResponse(
            [
                'data' => $data,
            ],
            200);
    }
}