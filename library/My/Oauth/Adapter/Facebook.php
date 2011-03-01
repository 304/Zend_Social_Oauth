<?php
/**
 * Facebook adapter for oauth service
 * @author Yaklushin Vasiliy <3a3a3a3@gmail.com>
 */
class My_Oauth_Adapter_Facebook extends My_Oauth_Adapter_Abstract
{
    protected $_config = array(
        'version'              => '2.0',
        'requestTokenUrl'      => 'https://graph.facebook.com/oauth/authorize',
        'userAuthorizationUrl' => 'https://graph.facebook.com/oauth/authorize',
        'accessTokenUrl'       => 'https://graph.facebook.com/oauth/access_token',
        'siteUrl'              => 'https://graph.facebook.com/oauth',
    );

    /**
     * Get user information
     * @return stdClass
     */
    public function getUserInfo($accessToken)
    {
        $url = 'https://graph.facebook.com/me';

        $content = file_get_contents($url.'?'.$accessToken);

        $decodedContent = json_decode($content);

        if (is_null($decodedContent) ) {
            throw new My_Oauth_Exception('Cannot decode json response from url ['.$url.']');
        }

        // check for facebook error
        if ( isset($decodedContent->error) ) {
            throw new My_Oauth_Exception($decodedContent->error->message);
        }

        return $decodedContent;
    }

    /**
     * Get list of user friends
     * @return stdClass
     */
    public function getFriendList($accessToken)
    {
        $url = 'https://graph.facebook.com/me/friends';

        $content = file_get_contents($url.'?'.$accessToken);

        $decodedContent = json_decode($content);

        if (is_null($decodedContent) ) {
            throw new My_Oauth_Exception('Cannot decode json response from url ['.$url.']');
        }

        // check for facebook error
        if ( isset($decodedContent->error) ) {
            throw new My_Oauth_Exception($decodedContent->error->message);
        }

        return $decodedContent;
    }
}
