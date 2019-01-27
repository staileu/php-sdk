<?php

class ClientTest extends \PHPUnit\Framework\TestCase {

    public function buildClient(): \STAILEUAccounts\Client
    {
        return new \STAILEUAccounts\Client("XXX", "XXX");
    }

    public function testAuthorizeUrl()
    {
        $authorizeUrl = $this->buildClient()->getAuthorizeUrl(
            "https://example.org/authorize",
            [
                \STAILEUAccounts\Client::SCOPE_READ_BIRTHDAY,
                \STAILEUAccounts\Client::SCOPE_READ_PROFILE,
                \STAILEUAccounts\Client::SCOPE_READ_REAL_NAME,
                \STAILEUAccounts\Client::SCOPE_READ_EMAIL,
            ]
        );
        $this->assertNotNull($authorizeUrl);
        $this->assertIsString($authorizeUrl);
        echo $authorizeUrl . " \n";
    }

    public function testVerifyAndFetchUser()
    {
        $code = "XXX";
        $client = $this->buildClient();
        $isValid = $client->verify($code);
        $this->assertNotNull($isValid);
        $this->assertTrue($isValid);
        $this->assertEquals(200, $client->getLastApiResponse()->getStatusCode());
        $client->fetchUser();
        $this->assertEquals(200, $client->getLastApiResponse()->getStatusCode());
        echo $client->getAccessToken() . " \n";
        echo $client->getUser()->id . " \n";
        echo $client->getUser()->username . " \n";
        echo $client->getUser()->email . " \n";
        echo $client->getUser()->firstName . " \n";
        echo $client->getUser()->lastName . " \n";
        echo $client->getUser()->birthday . " \n";
        echo $client->getUser()->avatarUrl . "\n";
        echo $client->getUser()->getBase64Avatar() . "\n";
        echo $client->getUser()->getBase64Avatar(true) . "\n";
    }

    public function testGetUserByIdOrUsername()
    {
        $userId = "XXX";
        $username = "XXX";
        $client = $this->buildClient();

        $user = $client->getUserByIdOrUsername($userId);
        $this->assertEquals(200, $client->getLastApiResponse()->getStatusCode());
        $this->assertNotFalse($user);
        $this->assertEquals($user->username, $username);
        $this->assertEquals($user->id, $userId);

        $user = $client->getUserByIdOrUsername($username);
        $this->assertEquals(200, $client->getLastApiResponse()->getStatusCode());
        $this->assertNotFalse($user);
        $this->assertEquals($user->username, $username);
        $this->assertEquals($user->id, $userId);
    }
}
