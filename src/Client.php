<?php
 
namespace vsyakiyjr\onevision;
 
class Client
{
    /**
     * @var string
     */
    protected $url = 'https://api.onevisionpay.com';
 
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @param $apiKey
     * @param $secretKey
     */
    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @return array
     */ 
    public function sendRequest($endpoint, array $params = [], array $headers = [])
    { 
		$secret_key = $this->secretKey;  

        $dataObject = $params;

        $dataJson = json_encode($dataObject);  
           
        $data = base64_encode($dataJson); 
        $sign = hash_hmac('sha512', $data, $this->secretKey); 
 
        $obj = [
            "data" => $data,
			"sign" => $sign
        ];
		   
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . base64_encode($this->apiKey);
  
        $curl = curl_init($this->url . $endpoint); 
   
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        curl_close($curl);

        return (array) json_decode($result, true); 
    } 
}