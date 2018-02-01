<?php

namespace OgilvyBundle\Action\AdminError;

use OgilvyBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError404Action
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Page is not found!');

        $data = [];
        $data['description'] = 'Page is not found';

        $response = $_this->render(
          '@admin/admin_error/admin_error_404_page.html.twig',
          [
            'data' => $data,
          ]
        );
        $response->setStatusCode(404);

        return $response;
    }
}
