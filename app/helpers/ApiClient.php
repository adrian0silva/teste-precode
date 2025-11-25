<?php
class ApiClient {
    private static $base = "https://www.replicade.com.br/api/v3";
    private static $token = "Basic aXdPMzVLZ09EZnRvOHY3M1I6";

    public static function post($path, array $data) {
        $url = self::$base . $path;
        $ch = curl_init();
        $json = json_encode($data);
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,    
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: " . self::$token,
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_TIMEOUT => 30
        ]);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        curl_close($ch);
        return ['http_code' => $info['http_code'] ?? 0, 'body' => json_decode($resp, true), 'error' => $err];
    }
}