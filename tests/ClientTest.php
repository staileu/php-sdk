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
        echo $client->getAccessToken() . " \n";
        echo $client->fetchUser()->id . " \n";
        echo $client->fetchUser()->username . " \n";
        echo $client->fetchUser()->email . " \n";
        echo $client->fetchUser()->firstName . " \n";
        echo $client->fetchUser()->lastName . " \n";
        echo $client->fetchUser()->birthday . " \n";
        echo $client->fetchUser()->avatarUrl;
    }
}
