<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminUser\AdminUserAction;
use OgilvyBundle\Action\AdminUser\AdminUserChangePasswordAction;
use OgilvyBundle\Action\AdminUser\AdminUserEditAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminUserController extends CoreAdminController
{

    /**
     * @Route("/admin/user", name="admin_user_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminUserAction(Request $request)
    {
        return AdminUserAction::all($this, $request);
    }

    /**
     * @Route("/admin/user/{userId}/edit", name="admin_user_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $userId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminUserEditAction(Request $request, $userId)
    {
        return AdminUserEditAction::all($this, $request, $userId);
    }

    /**
     * @Route("/admin/user/{userId}/change-password", name="admin_user_change_password_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $userId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminUserChangePasswordAction(Request $request, $userId)
    {
        return AdminUserChangePasswordAction::all($this, $request, $userId);
    }

}
