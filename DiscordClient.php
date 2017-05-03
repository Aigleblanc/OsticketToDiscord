<?php

namespace DiscordWebhooks;

class Client
{
     
    protected $url = null;
    protected $name = null;
    protected $avatar = null;
    protected $message = null;
    protected $tts = false;

    public function __construct($url) {
        $this->url = $url;
    }
     
    public function name($name) {
        $this->name = $name;
    }

    public function avatar($avatar) {
        $this->avatar = $avatar;
    }

    public function message($message) {
         $this->message = $message;
    }

    public function tts($tts) {
         $this->tts = $tts;
    }

    public function send($message = '') {
        if (!empty($message)) {
            $this->message = $message;
        }
        
        $url = $this->url;
        
        $data = array(
            'content'       => $this->message,
            'name'          => $this->name,
            'avatar_url'    => $this->avatar,
            'tts'           => $this->tts
        );
        
        $data_string = json_encode($data);
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        
        $output = curl_exec($curl);
        $output = json_decode($output, true);
        
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new Exception($output['message']);
        }
        
        curl_close($curl);
        return true;
    }
}
