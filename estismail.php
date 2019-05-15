<?php

namespace Estismail;

class Estismail
{
    private $url = 'https://v1.estismail.com/';
    private $api_key;
    private $list_id;
    private $required_fields = [
        'email.index' => [
            'list_id'
        ],
        'email.add' => [
            'list_id',
            'email'
        ],
        'email.view' => [
            'list_id'
        ]
    ];

    /**
     * Estismail constructor.
     * @param string $api
     */
    public function __construct(string $api)
    {
        $this->api_key = $api;
        return $this;
    }

    /**
     * @param $api
     * @return Estismail
     */
    public static function init($api)
    {
        return new self($api);
    }

    /**
     * @param $params
     * @return bool|string
     */
    public function getListEmails($params)
    {
        $url = $this->url.'mailer/emails';
        return $this->checkRequireParams('email.index', $params)
            ? $this->sendRequest('GET', $url, $params)
            : 'no required field';
    }

    /**
     * @param $params
     * @return string
     */
    public function addListEmail($params)
    {
        $url = $this->url.'mailer/emails';

        if( !$this->checkRequireParams('email.add', $params) ) {
            return 'no required field';
        }

        $response = $this->sendRequest('POST', $url, $params);

        return $response['id'];
    }

    /**
     * @param $type
     * @param array $params
     * @return bool
     */
    protected function checkRequireParams($type,$params = [])
    {
        foreach($this->required_fields[$type] as $value) {
            if( !isset($params[$value]) ) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $method
     * @param $url
     * @param array $data
     * @return bool|string
     */
    protected function sendRequest($method = 'GET', $url, $data = [])
    {
        $ch = curl_init();
        if($method == 'GET') {
            $url .= '?'.http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($method !== 'GET') {
            $type_option = ($method == 'POST') ? CURLOPT_POST : CURLOPT_CUSTOMREQUEST;
            curl_setopt($ch, $type_option, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Estis-Auth: '.$this->api_key
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response,true);
    }
}