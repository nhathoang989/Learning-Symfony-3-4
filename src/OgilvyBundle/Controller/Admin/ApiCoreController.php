<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\ApiCore\ApiCoreAction;
use OgilvyBundle\Controller\CoreCommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiCoreController extends CoreCommonController
{

    /**
     * @Route("/api/core/{group}/{action}", name="api_core_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $group
     * @param $action
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function apiCoreAction(Request $request, $group, $action)
    {
        return ApiCoreAction::all($this, $request, $group, $action);
    }
}
