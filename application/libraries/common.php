<?php

class Common {

    public function Generate_hash($length) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . rand(0, 99999);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function get_thumb($val) {

        $a = explode('/', $val);
        end($a);
        $key = key($a);
        $thumb = '';
        foreach ($a as $m => $l) {

            if ($m == $key) {
                $thumb .= 'thumbnail/' . $l;
            } else {
                $thumb .= $l . '/';
            }
        }

        return $thumb . ',' . $a[$key];
    }

    public function encrypt_string($input, $key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $cipher = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $input, MCRYPT_MODE_CBC, $iv);
        return base64_encode($iv . $cipher);
    }

    public function decrypt_string($input, $key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $ciphertext = base64_decode($input);
        $iv = substr($ciphertext, 0, $iv_size);
        $cipher = substr($ciphertext, $iv_size);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $cipher, MCRYPT_MODE_CBC, $iv), "\0");
    }

    function gcm($id = NULL, $load = NULL,$key) {
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $id,
            'data' => $load,
        );
      
        $headers = array(
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );


        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, true));

        // Execute post

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $status = "";
        	
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            $status = "FAIL";
        }

        $report = array();
        // Close connection
        curl_close($ch);
     //echo $result; die;
        //print_r($id);
    }

}

?>