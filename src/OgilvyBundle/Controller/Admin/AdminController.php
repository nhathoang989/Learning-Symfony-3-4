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
        $this->request = $request;
        $action = new AdminAction();
        
        
        
        return $action->all($this, $request);
    }

    /**
     * @Route("/admin/login", name="admin_login_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminLoginAction(Request $request)
    {
        //dump($this->getUser());die;
        $this->request = $request;
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();//$_this->getUser();
        //dump($object);die;
        return AdminLoginAction::all($this, $request, $currentUser);
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
