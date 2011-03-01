<?php
/**
 * Linkedin adapter for oauth service
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
class My_Oauth_Adapter_Linkedin extends My_Oauth_Adapter_Abstract
{
    protected $_config = array(
        'version'              => '1.0',
        'requestTokenUrl'      => 'https://api.linkedin.com/uas/oauth/requestToken',
        'userAuthorizationUrl' => 'https://api.linkedin.com/uas/oauth/authorize',
        'accessTokenUrl'       => 'https://api.linkedin.com/uas/oauth/accessToken',
        'siteUrl'              => 'https://api.linkedin.com/uas/oauth',
    );

    /**
     * Get user information
     * @return SimpleXMLElement
     */
    public function getUserInfo($accessToken)
    {
        $url = 'https://api.linkedin.com/v1/people/~';

        $client = $accessToken->getHttpClient($this->getConfigOptions());
        $client->setUri($url);
        $client->setMethod(Zend_Http_Client::GET);
        
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
        $url = 'http://api.linkedin.com/v1/people/~/connections';

        $client = $accessToken->getHttpClient($this->getConfigOptions());
        $client->setUri($url);
        $client->setMethod(Zend_Http_Client::GET);
        
        $request = $client->request();

        if ( $request->getStatus() !== 200 ) {
            throw new My_Oauth_Exception('Incorrect result of request to url ['.$url.']');
        }

        $body = $request->getBody();

        return simplexml_load_string($body);
    }
}
