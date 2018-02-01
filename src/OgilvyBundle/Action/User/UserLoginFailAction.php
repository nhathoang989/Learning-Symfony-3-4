<?php

namespace OgilvyBundle\Action\User;

use OgilvyBundle\CoreFrontController;
use OgilvyBundle\Entity\LoginLog;
use OgilvyBundle\Utility\SitePassword;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserLoginFailAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $useAjax = $_this->getContainerParameter('ajax_user_login_page');
        $_username = $_this->_getArrayValue($_POST, '_username', '');
        $_password = $_this->_getArrayValue($_POST, '_password', '');

        $errors = [];
        $isValid = true;
        if ($_username === '') {
            $errors['username']['ERROR'] = 'Email is required.';
            $isValid = false;
        }
        if ($_password === '') {
            $errors['password']['ERROR'] = 'Password is required.';
            $isValid = false;
        }

        if ($useAjax) {
            $returnData = [];
            $returnData['status'] = 0;
            if ($isValid) {

                $testData = $_this->_getSingleResultByDQL(
                  "SELECT wu.password, wu.status 
                    FROM OgilvyBundle:WebsiteUser wu 
                    WHERE wu.username = :username",
                  ['username' => $_username]
                );
                if ($testData) {
                    /*$encodePass = SitePassword::encodePassword($_this, $_username, $_password);
                    if($testData['password'] == $encodePass) {
                        $returnData['message'] = 'Account locked.  Please use Forgot Password function to unlock or contact sales@brianriedlinger.com for assistance.';
                    } else {*/
                    if ($testData['status'] == 9) {
                        $returnData['message'] = 'Your account has been blacklisted.';
                    } elseif ($testData['status'] == 0) {
                        $returnData['message'] = 'Account locked.';
                    } else {
                        $loginLog = $_this->_getEntityByConditions(
                          'OgilvyBundle:LoginLog',
                          [
                            'username' => $_username,
                          ]
                        );
                        $failTotal = 0;
                        if ($loginLog) {
                            $failTotal = $_this->_ctInt(
                              $loginLog->getFailTotal()
                            );
                        } else {
                            $loginLog = new LoginLog();
                            $loginLog->setUsername($_username);
                        }
                        $failTotal++;
                        $loginLog->setUpdatedAt(time());
                        $loginLog->setFailTotal($failTotal);
                        $_this->_saveEntity($loginLog);

                        if ($failTotal > 4) {
                            $websiteUserEntity = $_this->_getEntityByConditions(
                              'OgilvyBundle:WebsiteUser',
                              [
                                'username' => $_username,
                              ]
                            );

                            $websiteUserEntity->setStatus(0);
                            // lock account
                            $_this->_saveEntity($websiteUserEntity);
                            $returnData['message'] = 'Account locked.';
                        } else {
                            $returnData['message'] = 'Email or password invalid.  Please try again.';
                        }
                    }
                } else {
                    $returnData['message'] = 'Email or password invalid.  Please try again.';
                }


                /*}
            } else {
                $returnData['message'] = 'Email or password invalid.  Please try again.';
            }*/
            } else {
                $returnData['message'] = 'Email or password invalid.  Please try again.';
            }
            $returnData['errors'] = $errors;

            return new JsonResponse($returnData);
        } else {
            return $_this->redirectToRoute(
              'user_login_page',
              ['type' => 'error']
            );
        }

    }
}