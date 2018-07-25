<?php


class Token {

    protected $access_token;
    protected $expires_in;
    protected $token_type;
    protected $scope;

    public function __construct($res)
    {
        $res = json_decode($res);
        if (!isset($res->access_token) || !isset($res->expires_in) ||
            !isset($res->token_type) || !isset($res->scope))
            throw new Exception("Unable to set access token.");
        $this->access_token = $res->access_token;
        $this->expires_in= $res->expires_in;
        $this->token_type = $res->token_type;
        $this->scope = $res->scope;
    }

    public function encode(){
        return json_encode([
            'access_token' => $this->access_token,
            "expires_in" => $this->expires_in,
            "token_type" => $this->token_type,
            "scope" => $this->scope
        ], JSON_UNESCAPED_SLASHES);
    }

    public function gen_headers($extra_headers = []){
        return array_merge([
            'Content-Type: application/json',
            'Authorization: ' . $this->token_type . " " . $this->access_token
        ], $extra_headers);
    }
}

class DeathStar{

    public $token;
    protected $host = "http://localhost:5000";
    protected $client_id = "R2D2";
    protected $client_secret = "Alderan";

    public function execRequest($req){
        return curl_exec($req);
    }

    public function getToken(){
        $req = curl_init( $this->host . "/token" );
        curl_setopt_array($req, [
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => ["Client ID" => $this->client_id, "Client Secret" => $this->client_secret],
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $res = $this->execRequest($req);
        if ($res === false)
            throw new Exception(curl_error($req), curl_errno($req));
        $this->token = new Token($res);
        curl_close($req);
        return $this->token;
    }

    public function deleteExhaust($id){
        if (!isset($this->token))
            throw new Exception("User is not authenticated");
        $req = curl_init( $this->host . "/reactor/exhaust/" . $id);
        curl_setopt_array($req, [
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => $this->token->gen_headers(['x-torpedoes: 2']),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $res = $this->execRequest($req);
        if ($res === false)
            throw new Exception(curl_error($req), curl_errno($req));
        curl_close($req);
        return $res;
    }

    public function translateDroidSpeak($res, $keys){
        foreach($keys as $key)
            $res->$key = $this->DroidToGalactic($res->$key);
        return $res;
    }

    public function getLeia(){
        if (!isset($this->token))
            throw new Exception("User is not authenticated");
        $req = curl_init($this->host . "/prisoner/leia");
        curl_setopt_array($req, [
            CURLOPT_HTTPHEADER => $this->token->gen_headers(),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $res = $this->execRequest($req);
        if ($res === false)
            throw new Exception(curl_error($req), curl_errno($req));
        curl_close($req);
        return $this->translateDroidSpeak(json_decode($res), ['cell', 'block']);
    }

    function DroidToGalactic($bin){
        $text = array();
        $bin = explode(" ", $bin);
        for($i=0; count($bin)>$i; $i++)
            $text[] = chr(bindec($bin[$i]));
        return implode($text);
    }

}
// Examples

//$ds = new DeathStar();
//$ds->getToken();
//$ds->getLeia();
//$ds->deleteExhaust(1);

// Have them commented for unit test running, they can break tests
// To test it run the flask server in parallel