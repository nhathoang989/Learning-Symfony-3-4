<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\Admin\AdminAction;
use OgilvyBundle\Action\Admin\AdminLoginAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends CoreAdminController
{

    /**
     * @Route("/admin", name="admin_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAction(Request $request)
    {
        return AdminAction::all($this, $request);
    }

    /**
     * @Route("/admin/login", name="admin_login_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminLoginAction(Request $request)
    {
        return AdminLoginAction::all($this, $request);
    }

    /**
     * @Route("/admin/change-password", name="admin_change_password_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return
     */
    public function adminChangePasswordAction(Request $request)
    {
        return AdminChangePasswordAction::all(
          $this,
          $request
        );
    }

    /**
     * @Route("/admin/logout", name="admin_logout_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminLogoutAction(Request $request)
    {
        return $this->redirectToRoute('admin_page');
    }
}
