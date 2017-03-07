<?php

namespace App\Model;

use App\Engine\Model;
use Google_Client;
use Google_Service_Plus;

/**
 * @source: https://developers.google.com/api-client-library/php/auth/web-App
 */
class GoogleAuth extends Model
{
    /**
     * @return Google_Client
     */
    private function instance()
    {
        $client = new Google_Client();

//        $client->setApplicationName($this->config->get('google_project_name')); //"StudyOAuth_site"
//
//        $client->setAuthConfig(DIR_ROOT . 'secret/' . $this->config->get('google_project_json')); //client.apps.googleusercontent.com.json
//
//	    $client->setDeveloperKey($this->config->get('google_project_code')); //"studyoauth"

        $client->setApplicationName('StudyOAuth_site');

        $client->setAuthConfig(DIR_ROOT . 'secret/client.apps.googleusercontent.com.json');

        $client->setDeveloperKey('studyoauth');

        $client->addScope(Google_Service_Plus::PLUS_LOGIN);
        $client->addScope(Google_Service_Plus::PLUS_ME);
        $client->addScope(Google_Service_Plus::USERINFO_EMAIL);
        $client->addScope(Google_Service_Plus::USERINFO_PROFILE);

        return $client;
    }

    /**
     * Send request to OAuth 2.0 server.
     */
    public function auth()
    {
        $client = $this->instance();

        $auth_url = $client->createAuthUrl();

        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    }

    /**
     * Get userinfo from google+.
     *
     * @return mixed
     */
    public function userinfo()
    {
        if (true === array_key_exists('auth_token', $this->session->data) && true === array_key_exists('access_token', $this->session->data['auth_token'])) {
            $client = $this->instance();

            $client->setAccessToken($this->session->data['auth_token']['access_token']);

            $userinfo = new Google_Service_Plus($client);

            $info = $userinfo->people->get('me');

	        //if not isset image - set default
	        if(!isset($info['modelData']['image']['url']) || empty($info['modelData']['image']['url'])){

		        $info['modelData']['image']['url'] = HTTP_SERVER . 'image/profile.png';
	        }

            return [
                'id' => $info['id'],
                'name' => $info['displayName'],
                'email' => $info['modelData']['emails'][0]['value'],
                'image' => $info['modelData']['image']['url'],
                'gender' => $info['gender'],
                'language' => $info['language'],
                'url' => $info['url'],
            ];
        }
    }

    /**
     * Handle the OAuth 2.0 server response.
     */
    public function getToken($code)
    {
        $client = $this->instance();

        $client->authenticate($code);

        return $client->getAccessToken();
    }
}
