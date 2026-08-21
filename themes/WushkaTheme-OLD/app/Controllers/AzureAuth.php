<?php 

/**
 *  This file handles all the configuration for SSO with Azure AD
 * 
 *  @requires composer and autoload psr-4
 *  @link https://packagist.org/packages/thenetworg/oauth2-azure Packagist
 *  @link https://github.com/TheNetworg/oauth2-azure Github Documentation 
 * 
 */

namespace App\Controllers;


/**
 *  Represents a SSO to interact with Azure AD with
 *  OAuth 2.0 service provider
 */
class AzureAuth{
    
        
    /**
     * token
     *
     * @var mixed
     */
    protected $token;
    
    /**
     *
     * starts session if inactive
     */
    public function __construct(){
        //Start Session if session is not active
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    }

    /**
     * Returns oauth service providers object
     *
     * @return object
     */
    public function init(){
        $oauthClient = new \TheNetworg\OAuth2\Client\Provider\Azure([
            'clientId'          => OAUTH_CLIENT_ID,
            'clientSecret'      => OAUTH_CLIENT_SECRET,
            'redirectUri'       => OAUTH_REDIRECT_URI,
            'tenant'            => OAUTH_TENANT_ID,
            //Optional
            'scopes'            => ['openid', 'profile', 'offline_access'],
            //Optional
            'defaultEndPointVersion' => '2.0'
        ]);

        return $oauthClient;

    }
    
    /**
     * Returns authorization URL
     *
     * @return string
     */
    public function getAuthorizationUrl(){
        $oauthClient = $this->init();
        $authUrl = $oauthClient->getAuthorizationUrl([
            'scope' => $oauthClient->scope
        ]);
        
        $_SESSION['oauth_state'] = $oauthClient->getState();
        
        return $authUrl;
    }

    /**
     * Validate code and state received from service provider
     * Returns tokenObject after validating
     * 
     * @param  string $code
     * @param  string $state
     * @return object
     */
    public function validateAuthState($code, $state){
        $expectedState = $_SESSION['oauth_state'];
        //Clear oauth session
        unset($_SESSION['oauth_state']);

        /**
         *  If there is no expected state in the session,
         *  do nothing and redirect to the home page
         */
        if (!isset($expectedState)) {
            error_log('Unexpected oAuth state');
            return [
                'type'      =>  'danger',
                'message'   =>  'Unexpected state.',
            ];
        }

        /**
         *  Verify that code and state are not empty
         */
        if(empty($code) || empty($state)){
            error_log('Empty code or state provided for oauth');
            return [
                'type'      =>  'danger',
                'message'   =>  'Invalid Parameter.'
            ];
        }

        /**
         *  Compare parameter state with session to
         *  verify state is valid
         */
        if ($expectedState != $state) {
            error_log('The provided auth state did not match the expected value.');
            return [
                'type'      =>  'danger',
                'message'   =>  'The provided auth state did not match the expected value.'
            ];
        }

        try{
            $tokenObject = $this->getTokenObject($code);
            if($tokenObject->getToken()){
                error_log('Token success');
                return [
                    'type'      =>      'success',
                    'token'     =>      $tokenObject
                ];
            }
            error_log('Token failed.');
            return [
                'type'      =>  'danger',
                'message'   =>  'Failed to get token.'
            ];
        }catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e){
            error_log('Token error: '. json_encode($e->getResponseBody()) );
            return [
                'type'      =>  'danger',
                'message'   =>  json_encode($e->getResponseBody())
            ];
        }
    }
    
    /**
     * Returns access token object from code param
     *
     * @param  string $code
     * @return string
     */
    public function getTokenObject($code){
        $oauthClient = $this->init();  
        $token = $oauthClient->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return $this->token = $token;
    }
    
    /**
     * authenticates user with token
     * returns error when user does not exist
     *
     * @return array when invalid
     */
    public function authenticateUser(){
        //Retrieves user details from microsoft graph with token recieved
        $user = $this->getUserInformation();
        //Get user id if it is registered before
        $user_id = $this->verifyUser($user); 
        if($user_id){
            //WP User object
            $o_user  = get_user_by( 'ID',$user_id );
            //Restrict sso login for admin
            if( in_array( 'administrator', $o_user->roles) ){
                return [
                    'type'      =>  'danger',
                    'message'   =>  'Not authorized for Single Sign On'
                ];
            }
            $valid = wushka_valid_login($o_user, 'nsw_doe');
            if(is_wp_error( $valid )){
                return [
                    'type'      =>  'danger',
                    'message'   =>  $valid->get_error_message()
                ];
            }
            //Set login cookies
            $this->setUserLogin($o_user);
            //Redirect to dashboard after successful login
            wp_redirect(home_url());						
            exit;
        }

        return [
            'type'      =>  'danger',
            'message'   =>  'User does not exist, please contact administration.'
        ];
    }
    
    /**
     * Get user information from microsoft graph with token 
     *
     * @return array
     */
    public function getUserInformation(){
        $oauthClient = $this->init();
        try{
            return $oauthClient->get($oauthClient->getRootMicrosoftGraphUri($this->token) . '/v1.0/me', $this->token);
        }catch(Exception $e){
            return [
                'type'      =>  'danger',
                'message'   =>  $e
            ];
        }
    }
    
    /**
     * Verify if user exit
     *
     * @param  array $user
     * @return int
     */
    protected function verifyUser($user){
        //Attribute mapping with user email
        $user_email = $user['userPrincipalName']; 
        return email_exists($user_email);
    }
    
    /**
     * Creates user login auth cookie
     *
     * @param  object $user
     * @return array when invalid
     */
    protected function setUserLogin($user){
        if(empty($user)){
            return [
                'type'      =>  'danger',
                'message'   =>  'Unable to login.'
            ];
        }

        $user_id  = $user->ID;
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        do_action( 'wp_login', $user->user_login, $user );
    }

    
    /**
     * Token expires after an hour it is issued
     * Refresh token allows the app to request a new access token without requiring the user to sign in again
     *
     * @return object
     */
    protected function refreshToken(){
        $oauthClient = $this->init();
        $token = $this->token;
        if (!isset($token)) {
            return;
        }

        if ($token->hasExpired()) {
            if (!is_null($token->getRefreshToken())) {
                $token = $oauthClient->getAccessToken('refresh_token', [
                            'scope' => $oauthClient->scope,
                            'refresh_token' => $token->getRefreshToken()
                        ]);
            } else {
                $token = null;
            }
        }

        return $token;
    }

}

