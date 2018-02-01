<?php

namespace OgilvyBundle\Action\Error;

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
        $data = [];

        return $_this->render(
          '@admin/admin/admin_error_403_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}