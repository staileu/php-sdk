<?php

namespace STAILEUAccounts;

use GuzzleHttp\Psr7\Response;

/**
 * The main class of the sdk
 *
 * Class Client
 * @package STAILEUAccounts
 * @license MIT
 * @author STAN-TAb Corp.
 * @see https://docs.stail.eu
 */
class Client {

    /**
     * @var \GuzzleHttp\Client
     */
    private $client = NULL;

    /**
     * @var User
     */
    private $user = NULL;

    /**
     * @var string
     */
    private $appId = '';

    /**
     * @var string
     */
    private $appSecret = '';

    private $userPanelEndpoint = 'https://user.stail.eu';

    private $apiEndpoint = 'https://api-v2.stail.eu';

    private $accessToken = '';

    /**
     * @var Response
     */
    private $lastApiResponse = NULL;

    const SCOPE_READ_PROFILE = 'read-profile';

    const SCOPE_READ_EMAIL = 'read-email';

    const SCOPE_READ_REAL_NAME = 'read-real-name';

    const SCOPE_READ_BIRTHDAY = 'read-birthday';

    public function __construct(string $appId, string $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->client = new \GuzzleHttp\Client(['http_errors' => false]);
    }

    /**
     * Get the authorization url
     *
     * @param string $redirectUri
     * @param array $scope
     * @return string
     */
    public function getAuthorizeUrl(string $redirectUri, array $scope): string
    {
        $scope = implode('%20', $scope);
        return $this->userPanelEndpoint . '/authorize?client_id=' . $this->appId . '&scope=' . $scope . '&redirect_uri=' . $redirectUri;
    }

    /**
     * Verify the oauth code
     * If this method return true, the sdk will save the access token which can be used to authenticated later operation on this account, then you can call the fetchUser() method
     * If this method return false, it means that the sdk reject this oauth code or can not validate this code because of a issue on the API
     *
     * @param string $code
     * @return bool
     */
    public function verify(string $code): bool
    {
        $response = $this->client->post($this->apiEndpoint . '/oauth/token', [
            'json' => [
                'client_secret' => $this->appSecret,
                'code' => $code
            ]
        ]);
        $this->lastApiResponse = $response;
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if (!is_array($body) && !isset($body['success']) && $body['success'] !== true) {
            return false;
        }
        $this->accessToken = $body['data']['auth']['token'];
        return true;
    }

    /**
     * Get the user object
     * This will request on the /user/self API route to get all asked data (to retrieve the information that the application has requested)
     * If this method return false, it means that the sdk can not get the data because of a non 200 response on this request, this can mean that your app have no longer the authorization to retrieve these data or a expired access token
     *
     * @return false|User
     */
    public function fetchUser()
    {
        $response = $this->client->get($this->apiEndpoint . '/user/self', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);
        $this->lastApiResponse = $response;

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if (!is_array($body) && !isset($body['success']) && $body['success'] !== true) {
            return false;
        }

        $userRow = $body['data']['user'];

        $user = new User();
        $user->id = $userRow['id'];
        $user->username = $userRow['username'];
        $user->email = $userRow['email'];
        $user->firstName = $userRow['first_name'];
        $user->lastName = $userRow['last_name'];
        $user->birthday = $userRow['birthday'];
        $user->avatarUrl = $this->apiEndpoint . '/avatar/' . $user->id;
        $this->user = $user;

        return $user;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return NULL|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $appId
     * @return Client
     */
    public function setAppId(string $appId): self
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @param string $appSecret
     * @return Client
     */
    public function setAppSecret(string $appSecret): self
    {
        $this->appSecret = $appSecret;
        return $this;
    }

    /**
     * @param string $accessToken
     * @return Client
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @param string $userPanelEndpoint
     * @return Client
     */
    public function setUserPanelEndpoint(string $userPanelEndpoint): self
    {
        $this->userPanelEndpoint = $userPanelEndpoint;
        return $this;
    }

    /**
     * @param string $apiEndpoint
     * @return Client
     */
    public function setApiEndpoint(string $apiEndpoint): self
    {
        $this->apiEndpoint = $apiEndpoint;
        return $this;
    }

    /**
     * Get the last api response in guzzle http response interface
     *
     * @return Response|NULL
     */
    public function getLastApiResponse()
    {
        return $this->lastApiResponse;
    }
}
