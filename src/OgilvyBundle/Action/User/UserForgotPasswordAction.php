<?php

namespace OgilvyBundle\Action\User;


use OgilvyBundle\CoreFrontController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class UserForgotPasswordAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Forgot Password');

        $useAjax = $_this->getContainerParameter(
          'ajax_user_forgot_password_page'
        );
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = [];
        $data['errors'] = [];

        $entity = new UserForgotPasswordEntity();
        $form = $_this->createForm(
          new UserForgotPasswordForm(),
          $entity,
          [
            'action' => $_this->generateUrl('user_forgot_password_page'),
            'method' => 'POST',
          ]
        );

        $form->handleRequest($request);
        $email = strtolower($entity->getEmail());

        if ($email == '') {
            $form->get('email')->addError(new FormError('Email is required!'));
        }

        // has submitted
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $tmpUser = $_this->_getEntityByConditions(
                  'OgilvyBundle:WebsiteUser',
                  [
                    'email' => $email,
                  ]
                );

                if (!$tmpUser) {
                    $form->get('email')->addError(
                      new FormError('Email is NOT existed')
                    );
                    $returnData['status'] = 0;
                    $returnData['message'] = 'Email is NOT existed!';
                } else {

                    $returnData['status'] = 1;
                    $returnData['message'] = 'Sent reset code to '.$email.'. Please check the email!';
                }
            } else {
                $returnData['status'] = 0;
                $returnData['message'] = 'Invalid params!';
            }

            if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                $errors = Yaml::parse($form->getErrorsAsString());
                $returnData['errors'] = $errors;

                return new JsonResponse($returnData);
            } else {
                $errors = Yaml::parse($form->getErrorsAsString());
                $data['errors'] = $errors;
            }
        }


        $data['user_forgot_password_form'] = $form->createView();
        $data['use_ajax'] = $useAjax;

        return $_this->render(
          '@front/user/user_forgot_password_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}

class UserForgotPasswordForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
          ->add('email', EmailType::class, ['required' => true]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          [
            'data_class' => 'OgilvyBundle\Action\User\UserForgotPasswordEntity',
          ]
        );
    }
}

class UserForgotPasswordEntity
{

    private $email;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
}