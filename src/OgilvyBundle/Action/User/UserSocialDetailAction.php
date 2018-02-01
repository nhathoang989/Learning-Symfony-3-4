<?php

namespace OgilvyBundle\Action\User;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserSocialDetailAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $socialId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreFrontController $_this,
      Request $request,
      $socialId
    ) {
        $firebaseApiKey = $_this->_getConfigValue('firebase_api_key');
        $firebaseAuthDomain = $_this->_getConfigValue('firebase_auth_domain');
        $firebaseDatabaseURL = $_this->_getConfigValue('firebase_database_url');
        $firebaseStorageBucket = $_this->_getConfigValue(
          'firebase_storage_bucket'
        );
        $firebaseMessagingSenderId = $_this->_getConfigValue(
          'firebase_messaging_sender_id'
        );


        $_this->_setPageTitle('Processing');
        switch ($socialId) {
            case 'facebook':
                $socialType = 'facebook';
                break;
            case 'google':
                $socialType = 'google';
                break;
            case 'github':
                $socialType = 'github';
                break;
            case 'linkedin':
                $socialType = 'linkedin';
                break;
            default:
                $socialType = 'facebook';
                break;
        }
        $data = [];
        $data['social_type'] = $socialType;
        $data['firebase_api_key'] = $firebaseApiKey;
        $data['firebase_auth_domain'] = $firebaseAuthDomain;
        $data['firebase_database_url'] = $firebaseDatabaseURL;
        $data['firebase_storage_bucket'] = $firebaseStorageBucket;
        $data['firebase_messaging_sender_id'] = $firebaseMessagingSenderId;

        return $_this->render(
          '@front/user/user_social_detail_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}