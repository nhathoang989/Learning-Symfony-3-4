<?php

namespace OgilvyBundle\Action\ApiCore;

use OgilvyBundle\Action\ApiCore\ApiCoreUser\ApiCoreUserLogin;
use OgilvyBundle\Action\ApiCore\ApiCoreUser\ApiCoreUserRegister;
use OgilvyBundle\CoreCommonController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiCoreAction
{

    /**
     * @param \OgilvyBundle\CoreCommonController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $group
     * @param $action
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public static function all(
      CoreCommonController $_this,
      Request $request,
      $group,
      $action
    ) {
        $method = $request->getMethod();

        switch ($method) {
            case 'GET':
                $returnData = self::get($_this, $request, $group, $action);
                break;
            case 'POST':
                $returnData = self::post($_this, $request, $group, $action);
                break;
            default:
                $returnData = [];
                $returnData['status'] = 0;
                $returnData['message'] = 'Method is invalid!';
                break;
        }

        $response = new JsonResponse($returnData);

        return $response;
    }

    /**
     * @param \OgilvyBundle\CoreCommonController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $group
     * @param $action
     *
     * @return array
     */
    private static function get(
      CoreCommonController $_this,
      Request $request,
      $group,
      $action
    ) {
        switch ($group) {
            case 'social':
                switch ($action) {
                    default:
                        $returnData = ['status' => 0, 'message' => 'Not found'];
                        break;
                }
                break;
            default:
                $returnData = ['status' => 0, 'message' => 'Not found'];
                break;
        }

        return $returnData;
    }

    /**
     * @param \OgilvyBundle\CoreCommonController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $group
     * @param $action
     *
     * @return array
     */
    private static function post(
      CoreCommonController $_this,
      Request $request,
      $group,
      $action
    ) {
        switch ($group) {
            case 'user':
                switch ($action) {
                    case 'login':
                        $returnData = ApiCoreUserLogin::post($_this, $request);
                        break;
                    case 'register':
                        $returnData = ApiCoreUserRegister::post(
                          $_this,
                          $request
                        );
                        break;
                    default:
                        $returnData = ['status' => 0, 'message' => 'Not found'];
                        break;
                }
                break;
            default:
                $returnData = ['status' => 0, 'message' => 'Not found'];
                break;
        }

        return $returnData;
    }
}