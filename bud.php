<?php


class Token {

    protected $access_token;
    protected $expires_in;
    protected $token_type;
    protected $scope;

    public function __construct($res)
    {
        $res = json_decode($res);
        $this->access_token = $res->access_token;
        $this->expires_in= $res->expires_in;
        $this->token_type = $res->token_type;
        $this->scope = $res->scope;
    }

    public function gen_headers($extra_headers = []){
        return array_merge([
            'Content-Type: application/json',
            'Authorization: ' . $this->token_type . " " . $this->access_token
        ], $extra_headers);
    }
}

class DeathStar{

    protected $token;
    protected $host = "http://localhost:5000";
    protected $client_id = "R2D2";
    protected $client_secret = "Alderan";

    public function auth_request($type, $endpoint , $content){

    }

    public function getToken(){
        $req = curl_init( $this->host . "/token" );
        curl_setopt_array($req, [
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => ["Client ID" => $this->client_id, "Client Secret" => $this->client_secret],
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $res = curl_exec($req);
        if ($res === false)
            throw new Exception(curl_error($req), curl_errno($req));
        $this->token = new Token($res);
        curl_close($req);
    }

    public function deleteExhaust($id){
        $req = curl_init( $this->host . "/reactor/exhaust/" . $id);
        curl_setopt_array($req, [
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => $this->token->gen_headers(['x-torpedoes: 2']),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $res = curl_exec($req);
        if ($res === false)
            throw new Exception(curl_error($req), curl_errno($req));
        curl_close($req);
    }

    public function getLeia(){
        $req = curl_init($this->host . "/prisoner/leia");
        curl_setopt_array($req, [
            CURLOPT_HTTPHEADER => $this->token->gen_headers(),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $res = curl_exec($req);
        if ($res === false)
            throw new Exception(curl_error($req), curl_errno($req));
        curl_close($req);
    }

}

$ds = new DeathStar();

$ds->getToken();
$ds->deleteExhaust(1);
$ds->getLeia();

