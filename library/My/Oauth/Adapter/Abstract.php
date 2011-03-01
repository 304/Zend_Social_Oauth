<?php
/**
 * Abstract adapter for oauth service
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
abstract class My_Oauth_Adapter_Abstract
{
    protected $_config = array();

    public function __construct($options = null)
    {
        if ( isset($options) && is_array($options) ) {
            $this->_config = array_merge($this->_config, $options);
        }
    }

    /**
     * Set Callback Url
     * @param string $callbackUrl
     * @return My_Oauth_Adapter_Abstract
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->_config['callbackUrl'] = $callbackUrl;
        return $this;
    }

    /**
     * Set Consumer Key
     * @param string $key
     * @return My_Oauth_Adapter_Abstract
     */
    public function  setConsumerKey($key)
    {
        $this->_config['consumerKey'] = $key;
        return $this;
    }

    /**
     * Set consumer secret
     * @param string $secret
     * @return My_Oauth_Adapter_Abstract
     */
    public function setConsumerSecret($secret)
    {
        $this->_config['consumerSecret'] = $secret;
        return $this;
    }

    /**
     * Get config options
     * @return array
     */
    public function getConfigOptions()
    {
        return $this->_config;
    }

    /**
     * Get user information
     */
    abstract public function getUserInfo($accessToken);

    /**
     * Get list of user friends
     */
    abstract public function getFriendList($accessToken);
}
