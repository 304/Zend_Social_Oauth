<?php

class TwitterController extends Zend_Controller_Action
{
    private $_oauth = null;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $config = $this->getInvokeArg('bootstrap')->getResource('social');

        if ( ! ($config->twitter instanceof Zend_Config) ) {
            throw new Zend_Config_Exception("Field 'twitter' hasn't defined in config");
        }

        $twitterAdapter = new My_Oauth_Adapter_Twitter($config->twitter->toArray());

        $this->_oauth = new My_Oauth($twitterAdapter);
    }

    public function indexAction()
    {
        $this->_oauth->redirect();
    }

    public function resultAction()
    {
        if ( $this->_request->getParam('denied', FALSE) ) {
            throw new My_Oauth_Exception('User denied access to his account');
        }

        if ( ! $this->_request->getParam('oauth_token') ||
             ! $this->_request->getParam('oauth_verifier') ) {
            throw new My_Oauth_Exception('Incorrect twitter response');
        }

        $accessToken = $this->_oauth->getAccessToken($this->_request->getParams());

        echo '<pre>';
        var_dump($this->_oauth->getUserInfo());
        var_dump($this->_oauth->getFriendList());
        echo '</pre>';
    }


}

