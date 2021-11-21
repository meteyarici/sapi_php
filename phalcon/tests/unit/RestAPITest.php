<?php

namespace Test;

class RestAPITest extends \Test\UnitTestCase
{    
    public function setUp()
    {                
        parent::setUp();          
    }
    
    public function test_userAuthenticationRequestWithCorrectCredentials_tokenIsReturnedInResponse()
    {
        $response = $this->_http->request('POST', 'authenticate', [
            'form_params' => [
                'email' => 'mete1',
                'password' => '1234'
            ]
        ]);
        
        $json = json_decode($response->getBody());
        $this->assertTrue(isset($json->{"auth_token"}));

        $this->assertEquals(2, substr_count($json->auth_token, "."));
    }
    
    public function test_userAuthenticationRequestWithIncorrectCredentials_errorIsReturnedInResponse()
    {
        $response = $this->_http->request('POST', 'authenticate', [
            'form_params' => [
                'email' => 'mete1',
                'password' => '1234'
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $status = json_decode($response->getBody())->{"status"};
        $this->assertEquals("error", $status);  
    }
    
    public function test_WhenUserIsNotAuthenticated_AuthErrorIsInResponse()
    {
        $response = $this->_http->request('GET', 'auth_test');
        $responseText = $response->getBody()->getContents();        
        
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $status = json_decode($response->getBody())->{"status"};
        $this->assertEquals("error", $status);  
    }
    


}
