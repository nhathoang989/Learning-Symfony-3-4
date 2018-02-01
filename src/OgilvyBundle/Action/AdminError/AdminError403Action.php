<?php

namespace OgilvyBundle\Action\AdminError;

use OgilvyBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError403Action
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Access Denied!');

        $data = [];
        $data['description'] = 'Access Denied';

        $response = $_this->render(
          '@admin/admin_error/admin_error_403_page.html.twig',
          [
            'data' => $data,
          ]
        );
        $response->setStatusCode(403);

        return $response;
    }
}