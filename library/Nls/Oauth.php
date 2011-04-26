<?php
/**
 * Oauth implementation
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
class Nls_Oauth
{
    protected $_oauth;

    /**
     * Singleton instance
     *
     * @var Nls_Oauth
     */
    protected static $_instance = null;

    /**
     * Persistent storage handler
     *
     * @var Nls_Oauth_Storage_Interface
     */
    protected $_storage = null;

    /**
     * Access token
     * @var string|object
     */
    protected $_accessToken = null;

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct() {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone() {}

    /**
     * Returns an instance of Nls_Oauth
     *
     * Singleton pattern implementation
     *
     * @return Nls_Oauth Provides a fluent interface
     */
    public static function getInstance()
    {

        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        // initialize instance
        if ( self::$_instance->hasIdentity() ) {
            self::$_instance = self::$_instance->getIdentity();
        }

        return self::$_instance;
    }

    /**
     * Send user to Oauth service authorization
     *
     * @param Nls_Oauth_Adapter_Abstract $adapter
     * @return null
     */
    public function authorize(Nls_Oauth_Adapter_Abstract $adapter)
    {
        $this->setAdapter($adapter);
        
        $this->_oauth->authorize();
    }

    /**
     * Set Oauth Adapter
     * 
     * @param Nls_Oauth_Adapter_Abstract $adapter
     * @return Nls_Oauth
     */
    public function setAdapter(Nls_Oauth_Adapter_Abstract $adapter)
    {
        $this->_oauth = $this->_getVersionClass($adapter);

        if ($this->hasIdentity()) {
            $this->clearIdentity();
        }

        $this->getStorage()->write($this);

        return $this;
    }

    /**
     * Request Access Token from OAuth Service
     *
     * @param array|string $queryData
     * @return string|object
     */
    public function requestAccessToken($queryData)
    {
        $this->_accessToken = $this->_oauth->requestAccessToken($queryData);

        $this->getStorage()->write($this);

        return $this->_accessToken;
    }

    /**
     * Close current Oauth authorization
     */
    public function closeConnection()
    {
        // drop access token
        unset($this->_accessToken);

        // drop adapter config
        unset($this->_oauth);

        // clear session
        $this->getStorage()->clear();
    }

    /**
     * Check that Oauth authorization is valid and prepare for requests
     * @return bool
     */
    public function isValid()
    {
        if ( $this->_oauth instanceof Nls_Oauth_Version_Interface ) {
            if ( isset($this->_accessToken) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user profile
     *
     * @return Nls_Oauth_UserProfile
     */
    public function getUserProfile()
    {
        return $this->_oauth->getUserProfile();
    }

    /**
     * Get user friends
     *
     * @return Nls_Oauth_Friends
     */
    public function getFriends()
    {
        return $this->_oauth->getFriends();
    }

    /**
     * Get Access Token
     *
     * @return string|object
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

    /**
     * Set Access Token
     *
     * @param string|object $accessToken
     * @return Nls_Oauth
     */
    public function setAccessToken($accessToken)
    {
        $this->_accessToken = $accessToken;

        return $this;
    }
    
    /**
     * Select correct class for Oauth Version
     *
     * @param Nls_Oauth_Adapter_Abstract $adapter
     * @return Nls_Oauth_Version_Interface
     * @throw Nls_Oauth_Exception
     */
    private function _getVersionClass(Nls_Oauth_Adapter_Abstract $adapter)
    {
        $version = $adapter->getVersion();

        switch ($version) {
            case 1:
                return new Nls_Oauth_Version_One($adapter);
                break;

            case 2:
                return new Nls_Oauth_Version_Two($adapter);
                break;

            default:
                throw new Nls_Oauth_Exception('Cannot find implementation for version ['.$version.']');
                break;
        }

    }

    /**
     * Returns the persistent storage handler
     *
     * Session storage is used by default unless a different storage adapter has been set.
     *
     * @return Nls_Oauth_Storage_Interface
     */
    private function getStorage()
    {
        if (null === $this->_storage) {
            $this->setStorage(new Nls_Oauth_Storage_Session());
        }

        return $this->_storage;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  Nls_Oauth_Storage_Interface $storage
     * @return Nls_Oauth Provides a fluent interface
     */
    private function setStorage(Nls_Oauth_Storage_Interface $storage)
    {
        $this->_storage = $storage;
        return $this;
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return boolean
     */
    private function hasIdentity()
    {
        return !$this->getStorage()->isEmpty();
    }

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    private function getIdentity()
    {
        $storage = $this->getStorage();

        if ($storage->isEmpty()) {
            return null;
        }

        return $storage->read();
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return void
     */
    private function clearIdentity()
    {
        $this->getStorage()->clear();
    }
}