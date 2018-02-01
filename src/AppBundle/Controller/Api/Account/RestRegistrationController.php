<?php
/**
 * Created by PhpStorm.
 * User: NhatHoang.Nguyen
 * Date: 1/29/2018
 * Time: 2:15 PM
 */

namespace AppBundle\Controller\Api\Account;

use AppBundle\Controller\Api\ApiController;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RestRegistrationController extends ApiController
{
    /**
     * @Route("/register")
     */
    public function registerAction (Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = new User(); //$userManager->createUser();
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }


        $form = $formFactory->createForm([
            'csrf_protection' => false
        ]);
        $form->setData($user);

        $form->submit($request->request->all());

        $repository = $this->getDoctrine()->getRepository(User::class);
        $_currentUser = $repository->findBy(
            ['phoneNumber' => $user->getPhoneNumber()]
        );

        if ($_currentUser !=null){
            return $this->getResult(false,null, array('phoneNumber existed!'));
        }

        //print_r($user);die;
        if (!$form->isValid()) {


            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {

                return $response;
            }
            return $this->getFailedResult($form);
        } else {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $userManager->updateUser($user);

            return $this->getResult(true, $user);

        }
    }
}
