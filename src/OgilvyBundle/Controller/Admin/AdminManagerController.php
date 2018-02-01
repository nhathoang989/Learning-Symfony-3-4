<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminManager\AdminManagerAction;
use OgilvyBundle\Action\AdminManager\AdminManagerChangePasswordAction;
use OgilvyBundle\Action\AdminManager\AdminManagerEditAction;
use OgilvyBundle\Action\AdminManager\AdminManagerNewAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminManagerController extends CoreAdminController
{

    /**
     * @Route("/admin/manager", name="admin_manager_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminManagerAction(Request $request)
    {
        return AdminManagerAction::all($this, $request);
    }

    /**
     * @Route("/admin/manager/{managerId}", name="admin_manager_defail_page", requirements={"managerId": "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $managerId
     *
     * @return
     */
    public function adminAccountDetailAction(Request $request, $managerId)
    {
        return AdminManagerDetailAction::all($this, $request, $managerId);
    }

    /**
     * @Route("/admin/manager/{managerId}/edit", name="admin_manager_edit_page", requirements={"managerId": "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $managerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminManagerEditAction(Request $request, $managerId)
    {
        return AdminManagerEditAction::all($this, $request, $managerId);
    }

    /**
     * @Route("/admin/manager/{managerId}/change-password", name="admin_manager_change_password_page", requirements={"managerId": "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $managerId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminManagerChangePasswordAction(Request $request, $managerId)
    {
        return AdminManagerChangePasswordAction::all($this, $request, $managerId);
    }

    /**
     * @Route("/admin/manager/new", name="admin_manager_new_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminManagerNewAction(Request $request)
    {
        return AdminManagerNewAction::all($this, $request);
    }

    /**
     * @Route("/admin/manager/{managerId}/delete", name="admin_manager_delete_page", requirements={"managerId": "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $managerId
     *
     * @return
     */
    public function adminManagerDeleteAction(Request $request, $managerId)
    {
        return AdminManagerDeleteAction::all($this, $request, $managerId);
    }


}
