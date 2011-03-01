<?php
/**
 * My Oauth implementation for Oauth version 2.0
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
class My_OauthV2
{
    /** @var My_Oauth_Adapter_Abstract */
    private $_adapter = null;

    public function __construct(My_Oauth_Adapter_Abstract $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Redirect user to remote service page
     */
    public function redirect()
    {
        $config = $this->_adapter->getConfigOptions();
        $url = $config['requestTokenUrl'];

        $query = http_build_query(array(
            'client_id'    => $config['consumerKey'],
            'redirect_uri' => $config['callbackUrl'],
        ));

        header('Location: ' . $url . '?' . $query);
        exit(1);
    }

    /**
     * Get Access Token
     * @param array $queryData GET data returned in user's redirect from Provider
     * @return string
     */
    public function getAccessToken($queryData)
    {
        $config = $this->_adapter->getConfigOptions();

        $query = http_build_query(array(
                    'client_id'     => $config['consumerKey'],
                    'redirect_uri'  => $config['callbackUrl'],
                    'client_secret' => $config['consumerSecret'],
                    'code'          => $queryData['code'],
        ));

        $accessTokenUrl = $config['accessTokenUrl'];

        $accessToken = file_get_contents($accessTokenUrl . '?' . $query);

        $session = new Zend_Session_Namespace('myOauth');
        $session->accessToken = $accessToken;

        return $accessToken;
    }
    
    /**
     * Get user information
     * @return stdClass
     */
    public function getUserInfo()
    {
        $session = new Zend_Session_Namespace('myOauth');
        $accessToken = $session->accessToken;

        return $this->_adapter->getUserInfo($accessToken);
    }

    /**
     * Get list of user friends
     * @return stdClass
     */
    public function getFriendList()
    {
        $session = new Zend_Session_Namespace('myOauth');
        $accessToken = $session->accessToken;

        return $this->_adapter->getFriendList($accessToken);

    }
}
