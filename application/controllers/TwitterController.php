<?php

class TwitterController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function indexAction()
    {
        $oauth = Nls_Oauth::getInstance();
        
        $config = $this->getInvokeArg('bootstrap')->getResource('social');

        if ( ! ($config->twitter instanceof Zend_Config) ) {
            throw new Zend_Config_Exception("Field 'twitter' hasn't defined in config");
        }

        $twitterAdapter = new Nls_Oauth_Adapter_Twitter($config->twitter->toArray());
        $oauth->authorize($twitterAdapter);
    }

    public function resultAction()
    {
        $oauth = Nls_Oauth::getInstance();
        $oauth->requestAccessToken($_GET);
        

        echo '<pre>';
        var_dump($oauth->getUserProfile());
        echo '</pre>';
    }
    


}

