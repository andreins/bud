<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 25/07/2018
 * Time: 11:39
 */

use PHPUnit\Framework\TestCase;

include('bud.php');

class BudTest extends TestCase
{
    protected $tokenString = '{"access_token": "e31a726c4b90462ccb7619e1b51f3d0068bf8006","expires_in": 99999999999,"token_type": "Bearer","scope":"TheForce"}';
    protected $tokenArray = [
        "access_token" => "e31a726c4b90462ccb7619e1b51f3d0068bf8006",
        "expires_in" => 99999999999,
        "token_type" => "Bearer",
        "scope" => "TheForce"
    ];

    public function testCreation(){
        $this->assertInstanceOf(DeathStar::class, new Deathstar());
        $token = new Token($this->tokenString);
        $this->assertInstanceOf(Token::class, $token);
        $this->assertEquals($token->encode(), json_encode($this->tokenArray));
    }

    public function testInvalidToken(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unable to set access token.");
        $token = new Token('');
    }

    public function testGetToken(){
        //working example
        $stub = $this->getMockBuilder(DeathStar::class)->setMethods(["execRequest"])->getMock();
        $stub->method('execRequest')
            ->willReturn($this->tokenString);
        $this->assertEquals(new Token($this->tokenString),
            $stub->getToken());

        //cURL failed
        $stub = $this->getMockBuilder(DeathStar::class)->setMethods(["execRequest"])->getMock();
        $stub->method('execRequest')
            ->willReturn(false);
        $this->expectException(Exception::class);
        $stub->getToken();
    }

    public function testUnauth(){
        $ds = new DeathStar();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User is not authenticated");
        $ds->deleteExhaust(1);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User is not authenticated");
        $ds->getLeia();
    }

    public function testExhaustDelete(){
        $stub = $this->getMockBuilder(DeathStar::class)->setMethods(["execRequest"])->getMock();
        $stub->method('execRequest')->willReturn("random value");
        $stub->token = new Token($this->tokenString);
        $this->assertEquals("random value", $stub->deleteExhaust(1));
    }

    public function testGetLeia(){
        $stub = $this->getMockBuilder(DeathStar::class)->setMethods(["execRequest"])->getMock();
        $stub->token = new Token($this->tokenString);
        $stub->method('execRequest')->willReturn('{"cell" : "01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111",
            "block" : "01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100"}');
        $this->assertEquals((object) ["cell" => "Cell 2187", "block" => "Detention Block AA-23,"], $stub->getLeia());
    }

}
