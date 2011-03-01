<?php

class LinkedinController extends Zend_Controller_Action
{
    private $_oauth = null;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $config = $this->getInvokeArg('bootstrap')->getResource('social');

        if ( ! ($config->linkedin instanceof Zend_Config) ) {
            throw new Zend_Config_Exception("Field 'linkedin' hasn't defined in config");
        }

        $linkedinAdapter = new My_Oauth_Adapter_Linkedin($config->linkedin->toArray());

        $this->_oauth = new My_Oauth($linkedinAdapter);
    }

    public function indexAction()
    {
        $this->_oauth->redirect();
    }

    public function resultAction()
    {
        $accessToken = $this->_oauth->getAccessToken($_GET);

        echo '<pre>';
        var_dump($this->_oauth->getUserInfo());
        var_dump($this->_oauth->getFriendList());
        echo '</pre>';

    }


}

