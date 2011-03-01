<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Init social.ini file
     * @return Zend_Config_Ini
     */
    protected function _initSocial()
    {
        return new Zend_Config_Ini(APPLICATION_PATH . '/configs/social.ini', 'production');
    }

}

