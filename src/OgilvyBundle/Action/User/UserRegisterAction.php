<?php

namespace OgilvyBundle\Action\User;

use OgilvyBundle\Entity\WebsiteUser;
use OgilvyBundle\CoreFrontController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class UserRegisterAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Register');

        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = [];
        $data['errors'] = [];

        $userRegisterEntity = new UserRegisterEntity();
        $userRegisterForm = $_this->createForm(
          new UserRegisterForm(),
          $userRegisterEntity,
          [
            'action' => $_this->generateUrl('user_register_page'),
            'method' => 'POST',
          ]
        );

        $userRegisterForm->handleRequest($request);


        // has submitted
        if ($userRegisterForm->isSubmitted()) {
            if ($userRegisterForm->isValid()) {
                $email = strtolower($userRegisterEntity->getEmail());
                $password = $userRegisterEntity->getPassword();

                if ($email == '') {
                    $userRegisterForm->get('email')->addError(
                      new FormError('Email is required!')
                    );
                }

                if ($password == '') {
                    $userRegisterForm->get('password')->addError(
                      new FormError('Password is required!')
                    );
                }
                $tmpUser = $_this->_getEntityByConditions(
                  'OgilvyBundle:WebsiteUser',
                  [
                    'email' => $email,
                  ]
                );

                if ($tmpUser) {
                    $userRegisterForm->get('email')->addError(
                      new FormError('Email is existed')
                    );
                    $returnData['status'] = 0;
                    $returnData['message'] = 'Email is existed!';
                } else {
                    $user = new WebsiteUser();
                    $user->setUsername($email);
                    $user->setEmail($email);
                    $user->setPassword(
                      $_this->encodePassword($email, $password)
                    );
                    $user->setStatus(1);
                    $user->setCreatedAt(time());
                    $user->setActivedAt(time());

                    $user = $_this->_saveEntity($user);
                    $userId = $user->getId();

                    $returnData['status'] = 1;
                    $returnData['message'] = 'Sent active code to '.$email.'. Please active the account!';
                }
            } else {
                $returnData['status'] = 0;
                $returnData['message'] = 'Invalid params!';
            }

            if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                $errors = Yaml::parse($userRegisterForm->getErrorsAsString());
                $returnData['errors'] = $errors;

                return new JsonResponse($returnData);
            } else {
                $errors = Yaml::parse($userRegisterForm->getErrorsAsString());
                $data['errors'] = $errors;
            }
        }

        $data['user_register_form'] = $userRegisterForm->createView();

        return $_this->render(
          '@front/user/user_register_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}

class UserRegisterForm extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('email', EmailType::class, ['required' => true])
          ->add(
            'password',
            RepeatedType::class,
            [
              'type' => PasswordType::class,
              'invalid_message' => 'The password fields must match.',
              'options' => ['attr' => []],
              'required' => true,
              'first_options' => ['label' => 'Password'],
              'second_options' => ['label' => 'Repeat Password'],
            ]
          );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          [
            'data_class' => 'OgilvyBundle\Action\User\UserRegisterEntity',
          ]
        );
    }
}

class UserRegisterEntity
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

    private $password;

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
}