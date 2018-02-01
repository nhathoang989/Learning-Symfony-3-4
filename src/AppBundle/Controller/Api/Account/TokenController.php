<?php
/**
 * Created by PhpStorm.
 * User: NhatHoang.Nguyen
 * Date: 1/31/2018
 * Time: 4:17 PM
 */

namespace AppBundle\Controller\Api\Account;

use AppBundle\Entity\OAuthAccessToken;
use AppBundle\Entity\Manager\OAuthClientManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\OAuthServerBundle\Controller\TokenController as BaseTokenController;
use FOS\OAuthServerBundle\Model\AccessTokenManagerInterface;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;
use Symfony\Component\HttpFoundation\Request;

class TokenController extends BaseTokenController
{
    protected $clientManager;
    protected $tokenManager;
    protected $entityManager;
    public function __construct(OAuth2 $server, ClientManagerInterface $clientManager, AccessTokenManagerInterface $tokenManager, EntityManagerInterface $entityManager)
    {
        parent::__construct($server);
        $this->clientManager = $clientManager;
        $this->tokenManager = $tokenManager;
        $this->entityManager = $entityManager;
    }
    public function tokenAction(Request $request)
    {
        if ($request === null) {
            $request = Request::createFromGlobals();
        }
        // get the hashid from the request
        $property = $request->isMethod(Request::METHOD_POST) ? 'request' : 'query';
        $hashid = $request->$property->get('restaurant_id');
        $request->$property->remove('restaurant_id');
        if (!$hashid) {
            // continue with creating an access token from existing parameters
            $response = parent::tokenAction($request);
            $responseToken = json_decode($response->getContent());

            $responseData = array(
                'success'=>isset($responseToken->access_token),
                'data'=>$responseToken
            );
            $response->setContent(json_encode($responseData));
            return $response;
        }
        try {
            // find the relevant oauth client
            if (!($oauthClient = $this->clientManager->findOAuthClientByRestaurantHashid($hashid))) {
                throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_REQUEST, 'Invalid restaurant ID.');
            }
            // build a standard client credentials request
            $request->$property->set('client_id', $oauthClient->getPublicId());
            $request->$property->set('client_secret', $oauthClient->getSecret());
            $request->$property->set('grant_type', OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS);
            $request->$property->set('scope', 'widget');
            // handle the request, decoding the created token so we can get a managed entity
            $response = parent::tokenAction($request);
            /*
            $responseToken = json_decode($response->getContent());
            if (!$responseToken || !($token = $this->tokenManager->findTokenByToken($responseToken->access_token))) {
                throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_REQUEST, 'Unable to decode the token.');
            }
            // associate the token with the restaurant and update
            $restaurant = $this->entityManager->getRepository('AppBundle:Restaurant')->findOneBy(array('hashid' => $hashid));
            $token->setRestaurant($restaurant);
            $this->tokenManager->updateToken($token);
            */

            return $response;
        } catch (OAuth2ServerException $e) {
            return $e->getHttpResponse();
        }
    }
}