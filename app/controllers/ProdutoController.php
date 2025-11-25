<?php
require_once __DIR__ . "/../helpers/ApiClient.php";
require_once __DIR__ ."/../models/Produto.php";
class ProdutoController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->render('produto.create', ['title' => 'CadastroProduto', 'Bem vindo a tela de cadastro de produto']);
    }

    public function create() {
        header('Content-Type: application/json');
            
        // Log inicial
        error_log("=== [ProdutoController::create] Iniciando criação de produto ===");

        // Captura JSON
        $json = file_get_contents("php://input");

        // Log bruto do JSON
        error_log("JSON recebido: " . $json);

        $data = json_decode($json, true);

          // Log da conversão do JSON
        error_log("Array decodificado: " . print_r($data, true));
        if (!$data) {
            echo json_encode([
                "status" => "erro",
                "message" => "Nenhum dado recebido"
            ]);
            return;
        }

        $product = $data['product'];
        error_log("Received product raw: " . print_r($data['product'], true));

        $sku = $product['sku'] ?? null;
        if (!empty($sku)) {
            $sku = (int)$sku;
        } else {
            $sku = null; // não enviar se estiver vazio
        }

        // ==========================
        // TRATAMENTO DAS VARIAÇÕES
        // ==========================
        $validVariations = [];

    if (!empty($product['variations'])) {
        foreach ($product['variations'] as $v) {

            $specs = [];
            if (!empty($v['specifications'])) {
                foreach ($v['specifications'] as $s) {
                    if (!empty($s['key']) && !empty($s['value'])) {
                        $specs[] = $s;
                    }
                }
            }

            // variação válida (obrigatória)
            if (!empty($v['ref']) || !empty($v['sku']) || !empty($v['ean'])) {
                $validVariations[] = [
                    'ref' => $v['ref'] ?? "",
                    'sku' => !empty($v['sku']) ? (int)$v['sku'] : null,
                    'qty' => isset($v['qty']) ? (int)$v['qty'] : 0,
                    'ean' => $v['ean'] ?? "",
                    'images' => $v['images'] ?? [],
                    'specifications' => $specs
                ];
            }
        }
    }

    // ==========================
    // VERIFICA SE TEM VARIAÇÃO
    // ==========================
    if (empty($validVariations)) {
        echo json_encode([
            "status" => "erro",
            "message" => "O produto precisa ter pelo menos uma variação válida"
        ]);
        return;
    }

    // ==========================
    // MONTA BODY FINAL
    // ==========================
    $body = [
        'titulo' => $product['name'] ?? null,
        'name' => $product['name'] ?? null,
        'shortName' => $product['shortName'] ?? null,
        'description' => $product['description'] ?? null,
        'status' => $product['status'] ?? "enabled",
        'price' => (float)($product['price'] ?? 0),
        'promotional_price' => (float)($product['promotional_price'] ?? 0),
        'cost' => (float)($product['cost'] ?? 0),
        'weight' => (float)($product['weight'] ?? 0),
        'width' => (float)($product['width'] ?? 0),
        'height' => (float)($product['height'] ?? 0),
        'length' => (float)($product['length'] ?? 0),
        'brand' => $product['brand'] ?? "",
        'category' => $product['category'] ?? "",
        'subcategory' => $product['subcategory'] ?? "",
        'endcategory' => $product['endcategory'] ?? "",
        'attribute' => $product['attribute'] ?? [],
        'variations' => $validVariations
    ];

    if (!empty($sku)) {
        $body['sku'] = $sku;
    }
            // Log do payload que será enviado para API
    error_log("Body enviado para API: " . print_r($body, true));

    error_log("JSON FINAL ENVIADO PARA API: " . json_encode($body));

        $res = ApiClient::post('/products', [ 'product' => $body ]);

          // Log da resposta da API
    error_log("Resposta da API: " . print_r($res, true));
        if ($res['http_code'] >= 200 && $res['http_code'] < 300) {
            error_log("[API SUCESSO] Produto criado com sucesso");
            $model = new Produto();
            $model->salvarBanco($body);
            $message = "Produto enviado com sucesso!";
        } else {
            $message = "Erro: " . ($res['body']['message'] ?? $res['error']);
        }
    }
}