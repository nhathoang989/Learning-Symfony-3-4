<?php
/**
 * Created by PhpStorm.
 * User: NhatHoang.Nguyen
 * Date: 2/1/2018
 * Time: 5:34 PM
 */

namespace OgilvyBundle\Controller\FrontEnd;

use OgilvyBundle\Action\Page\IndexAction;
use OgilvyBundle\Controller\CoreFrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends CoreFrontController
{

    /**
     * @Route("/", name="index_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->request = $request;
        return IndexAction::all($this, $request);
    }
}