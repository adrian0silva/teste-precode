<?php
class ApiClient {
    private static $base = "https://www.replicade.com.br/api";
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

    public static function put($path, array $data)
    {
        $url = self::$base . $path;
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
        // =======================================
        // LOG — REQUISIÇÃO SENDO ENVIADA
        // =======================================
        error_log("\n==================== API PUT REQUEST ====================");
        error_log("URL:      {$url}");
        error_log("PAYLOAD:");
        error_log($json);
        error_log("TAMANHO JSON: " . strlen($json) . " bytes");
        error_log("==========================================================\n");
    
        $ch = curl_init();
    
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => [
                "Authorization: " . self::$token,
                "Content-Type: application/json",
                "Content-Length: " . strlen($json)
            ]
        ]);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        // =======================================
        // LOG — POSSÍVEL ERRO CURL
        // =======================================
        if ($response === false) {
            $error = curl_error($ch);
            error_log("[API PUT ERROR] Erro CURL: {$error}");
            curl_close($ch);
    
            return [
                "error" => $error,
                "http_code" => $httpCode,
                "body" => null
            ];
        }
    
        curl_close($ch);
    
        // =======================================
        // LOG — RESPOSTA RECEBIDA
        // =======================================
        error_log("\n==================== API PUT RESPONSE ====================");
        error_log("HTTP CODE: {$httpCode}");
        error_log("RAW RESPONSE:");
        error_log($response);
        error_log("===========================================================\n");
    
        return [
            "http_code" => $httpCode,
            "body" => json_decode($response, true)
        ];
    }
    
    public static function get($path) {
        $url = self::$base . $path;
    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: " . self::$token
            ]
        ]);
    
        $response = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
    
        return [
            "http_code" => $http,
            "body"      => json_decode($response, true)
        ];
    }
    public static function delete($path, array $data = [])
{
    $url = self::$base . $path;
    $ch = curl_init();
    $json = json_encode($data);

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: " . self::$token
        ],
        CURLOPT_POSTFIELDS => $json
    ]);

    $response = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        "http_code" => $http,
        "body" => json_decode($response, true),
        "error" => $err
    ];
}

}