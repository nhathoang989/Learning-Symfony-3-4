<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminNode\AdminNodeAction;
use OgilvyBundle\Action\AdminNode\AdminNodeEditAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminNodeController extends CoreAdminController
{

    /**
     * @Route("/admin/node", name="admin_node_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminNodeAction(Request $request)
    {
        return AdminNodeAction::all($this, $request);
    }

    /**
     * @Route("/admin/node/{nodeId}/edit", name="admin_node_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $nodeId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminNodeEditAction(Request $request, $nodeId)
    {
        return AdminNodeEditAction::all($this, $request, $nodeId);
    }

}
