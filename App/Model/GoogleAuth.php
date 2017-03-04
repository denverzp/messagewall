<?php
namespace App\Controller;

use \Google_Client;

/**
 * @source: https://developers.google.com/api-client-library/php/auth/web-App
 */
class GoogleAuth
{
    /**
     *
     * @return Google_Client
     */
    private function instance()
    {
        $client = new Google_Client();

        $client->setApplicationName("StudyOAuth_site");
        $client->setAuthConfig(DIR_APP . 'secret/client.apps.googleusercontent.com.json');
        $client->setDeveloperKey("studyoauth");
        $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        
        return $client;
    }
    
    /**
     * Send request to OAuth 2.0 server
     */
    public function auth()
    {
        $client = $this->instance();

        $auth_url = $client->createAuthUrl();

        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    }
    
    /**
     * Handle the OAuth 2.0 server response
     */
    public function getToken()
    {
        $client = $this->instance();
        
        $client->authenticate($_GET['code']);
        
        $token = $client->getAccessToken();
        
        return $token;
    }
}
