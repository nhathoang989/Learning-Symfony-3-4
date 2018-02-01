<?php
/**
 * Created by PhpStorm.
 * User: nhathoang.nguyen
 * Date: 1/29/2018
 * Time: 11:47 AM
 */

namespace AppBundle\Controller\Api;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiController extends FOSRestController
{
    /**
     * @Route("/api")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = array(
            "hello" => "world",
            "user" => $user,
            );
        $view = $this->view($data);
        return $this->handleView($view);
    }

    protected function getFailedResult($form){
        $errors = $form->getErrors(true)->getChildren('errors');
        return $this->getResult(false, null, $errors);
    }
    protected function getResult($success, $data, $errors = null){
        $responseData = array(
            'success'=>$success,
            'data'=>$data,
            'errors' => $errors
        );
        $view = $this->view($responseData);
        return $this->handleView($view);
    }
}