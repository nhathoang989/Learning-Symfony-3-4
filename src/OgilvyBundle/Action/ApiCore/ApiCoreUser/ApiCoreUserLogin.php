<?php

namespace OgilvyBundle\Action\ApiCore\ApiCoreUser;


use OgilvyBundle\CoreCommonController;
use Symfony\Component\HttpFoundation\Request;

class ApiCoreUserLogin
{

    /**
     * @param \OgilvyBundle\CoreCommonController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public static function post(
      CoreCommonController $_this,
      Request $request
    ) {
        $returnData = [];
        $returnData['status'] = 1;
        $returnData['message'] = 'Success';
        $data = [];
        $returnData['data'] = $data;

        return $returnData;
    }

}