<?php

namespace OgilvyBundle\Action\Node;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class NodeDetailAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $nodeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreFrontController $_this,
      Request $request,
      $nodeId
    ) {

        $detailEntity = $_this->_getEntityByID('OgilvyBundle:Node', $nodeId);
        if (!$detailEntity) {
            return $_this->_error404Action($request);
        }

        $nodeTitle = $detailEntity->getTitle();
        $nodeSummary = $detailEntity->getSummary();
        $nodeBody = $detailEntity->getBody();


        $_this->_setPageTitle($nodeTitle);
        $_this->_setMetaTags(
          [
            'title' => $nodeTitle,
            'description' => $nodeSummary,
          ]
        );

        $data = [];
        $data['title'] = $nodeTitle;
        $data['summary'] = $nodeSummary;
        $data['body'] = $nodeBody;

        return $_this->render(
          '@front/node/node_detail_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}