<?php

class APIRequester
{
    private $login;
    private $password;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @param $url
     * @return mixed
     * @throws Exception
     */
    public function requestGET(string $url): array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        $headers = [
            'Authorization: Basic ' . base64_encode($this->login . ':' . $this->password)
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception('Error occurred during the API request: ' . curl_error($curl));
        }

        $responseData = json_decode($response, true);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseData === null) {
            throw new Exception('Error occurred while decoding the API response.');
        }

        if (empty($responseData['data'])) {
            throw new Exception('API request failed (' . $responseData['error'] . ') with status code: ' . $statusCode);
        }

        if ($statusCode >= 400) {
            throw new Exception('API request failed (' . $responseData['error'] . ') with status code: ' . $statusCode);
        }

        return $responseData;
    }
}
