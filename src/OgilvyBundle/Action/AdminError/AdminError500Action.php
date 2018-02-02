<?php

namespace OgilvyBundle\Action\AdminError;

use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError500Action
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('500');

        $data = [];
        $data['description'] = 'Error 500';

        $response = $_this->render(
          '@admin/admin_error/admin_error_500_page.html.twig',
          [
            'data' => $data,
          ]
        );
        $response->setStatusCode(500);

        return $response;
    }
}
