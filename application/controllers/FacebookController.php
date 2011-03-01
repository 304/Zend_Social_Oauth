<?php

class FacebookController extends Zend_Controller_Action
{
    private $_oauth = null;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $config = $this->getInvokeArg('bootstrap')->getResource('social');

        if ( ! ($config->facebook instanceof Zend_Config) ) {
            throw new Zend_Config_Exception("Field 'facebook' hasn't defined in config");
        }

        $facebookAdapter = new My_Oauth_Adapter_Facebook($config->facebook->toArray());

        $this->_oauth = new My_OauthV2($facebookAdapter);
    }

    public function indexAction()
    {
        $this->_oauth->redirect();
    }

    public function resultAction()
    {
        $this->_oauth->getAccessToken($_GET);

        echo '<pre>';
        var_dump($this->_oauth->getUserInfo());
        var_dump($this->_oauth->getFriendList());
        echo '</pre>';
    }


}

