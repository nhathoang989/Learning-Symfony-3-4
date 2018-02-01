<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\Install\InstallAction;
use OgilvyBundle\Controller\CoreCommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends CoreCommonController
{

    /**
     * @Route("/install", name="install_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function installAction(Request $request)
    {
        return InstallAction::all($this, $request);
    }

}
