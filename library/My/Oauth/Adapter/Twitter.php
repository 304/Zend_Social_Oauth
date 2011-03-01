<?php
/**
 * Twitter adapter for oauth service
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
class My_Oauth_Adapter_Twitter extends My_Oauth_Adapter_Abstract
{
    protected $_config = array(
        'requestTokenUrl'      => 'http://twitter.com/oauth/request_token',
        'userAuthorizationUrl' => 'http://twitter.com/oauth/authorize',
        'accessTokenUrl'       => 'http://twitter.com/oauth/access_token',
        'siteUrl'              => 'http://twitter.com/oauth',
    );

    /**
     * Get user information
     * @return SimpleXMLElement
     */
    public function getUserInfo($accessToken)
    {
        $url = 'http://api.twitter.com/1/users/show.xml';

        $userId = $accessToken->getParam('user_id');

        $client = $accessToken->getHttpClient($this->getConfigOptions());
        $client->setUri($url);
        $client->setMethod(Zend_Http_Client::GET);
        $client->setParameterGet('user_id', $userId);

        $request = $client->request();
        
        if ( $request->getStatus() !== 200 ) {
            throw new My_Oauth_Exception('Incorrect result of request to url ['.$url.']');
        }
        
        $body = $request->getBody();

        return simplexml_load_string($body);
    }

    /**
     * Get list of user friends
     * @return SimpleXMLElement
     */
    public function getFriendList($accessToken)
    {
        $url = 'http://api.twitter.com/1/followers/ids.xml';

        $userId = $accessToken->getParam('user_id');

        $client = $accessToken->getHttpClient($this->getConfigOptions());
        $client->setUri($url);
        $client->setMethod(Zend_Http_Client::GET);
        $client->setParameterGet('user_id', $userId);
        
        $request = $client->request();

        if ( $request->getStatus() !== 200 ) {
            throw new My_Oauth_Exception('Incorrect result of request to url ['.$url.']');
        }

        $body = $request->getBody();

        return simplexml_load_string($body);
    }
}
