<?php
/**
 * My Oauth implementation for Oauth version 1.0
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
class My_Oauth extends Zend_Oauth_Consumer
{
    /** @var My_Oauth_Adapter_Abstract */
    private $_adapter = null;

    public function __construct(My_Oauth_Adapter_Abstract $adapter)
    {
        $this->_adapter = $adapter;
        parent::__construct($adapter->getConfigOptions());
    }

    /**
     * Redirect user to remote service page
     */
    public function redirect()
    {
        $token = $this->getRequestToken();
        
        $session = new Zend_Session_Namespace('myOauth');
        $session->requestToken = serialize($token);

        parent::redirect();
    }

    /**
     * Get Access Token
     * @param array $queryData GET data returned in user's redirect from Provider
     * @return Zend_Oauth_Token_Access
     */
    public function getAccessToken($queryData)
    {
        $session = new Zend_Session_Namespace('myOauth');
        $requestToken = unserialize($session->requestToken);

        $accessToken = parent::getAccessToken($queryData, $requestToken);
        $session->accessToken = serialize($accessToken);

        return $accessToken;
    }

    /**
     * Get user information
     * @return SimpleXMLElement
     */
    public function getUserInfo()
    {
        $session = new Zend_Session_Namespace('myOauth');
        $accessToken = unserialize($session->accessToken);

        return $this->_adapter->getUserInfo($accessToken);
    }

    /**
     * Get list of user friends
     * @return SimpleXMLElement
     */
    public function getFriendList()
    {
        $session = new Zend_Session_Namespace('myOauth');
        $accessToken = unserialize($session->accessToken);

        return $this->_adapter->getFriendList($accessToken);
    }
}
