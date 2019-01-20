<?php

class ClientTest extends \PHPUnit\Framework\TestCase {

    public function buildClient(): \STAILEUAccounts\Client
    {
        return new \STAILEUAccounts\Client("ZIR7blbDfmFjavbl", "96YtRbuJrSKCtdQbkEQfR8WZSOHdRlrk");
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
        $code = "IL4OFt0R7jP3C8RCJus38pNpRvzXDFz5HRAH2TsjxKFME4Zau0Zn9WMnxXbaHwWr";
        $client = $this->buildClient();
        $isValid = $client->verify($code);
        $this->assertNotNull($isValid);
        $this->assertTrue($isValid);
        $client->fetchUser();
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
}
