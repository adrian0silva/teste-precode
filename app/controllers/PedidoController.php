<?php

class PedidoController extends Controller {
    public function index() {
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->listarTodos();
        $this->render('pedido.index', ['pedidos' => $pedidos]);
    }

    public function salvar()
    {
        header("Content-Type: application/json");
        

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
    
        if (!$data || empty($data['pedido'])) {
            echo json_encode(["status" => "erro", "msg" => "JSON inválido"]);
            return;
        }
    
        $pedido = $data['pedido'];
        error_log('[PEDIDO] JSON Recebido:' . print_r($pedido, true));
        // ===============================
        // 1. PEGAR SKU LOCAL
        // ===============================
        $idProduto = $pedido['itens'][0]['idProduto'];
        $skuDigitado = $pedido['itens'][0]['sku'];
    
        // ===============================
        // 2. BUSCAR REF NO BANCO
        // ===============================
        $produtoModel = new Produto();
        $ref = $produtoModel->obterRefPorSkuLocal($idProduto);
        error_log("Sku Digitado: ".print_r($skuDigitado,true));
        error_log("Ref Valido: ".print_r($ref,true));
        if (!$ref) {
            echo json_encode(["status" => "erro", "msg" => "REF não encontrado para o SKU local"]);
            return;
        }
    
        // ===============================
        // 3. PEGAR SKU VÁLIDO DA PRECODE
        // ===============================
        $skuValido = $produtoModel->obterSkuPrecodePorRef($ref);

        if (!$skuValido) {
            echo json_encode(["status" => "erro", "msg" => "SKU da Precode não encontrado"]);
            return;
        }
    
        // ===============================
        // 4. SUBSTITUIR NO JSON DO PEDIDO
        // ===============================
        $pedido['itens'][0]['sku'] = $skuValido;
    
        error_log("[PEDIDO] SKU VALIDO DA PRECODE: $skuValido");
    
        // ===============================
        // 5. ENVIAR PARA API DA PRECODE
        // ===============================
        $res = ApiClient::post("/v1/pedido/pedido", ["pedido" => $pedido]);
    
        error_log("[PEDIDO] Resposta API:");
        error_log(print_r($res, true));
    
        if ($res['http_code'] != 200 && $res['http_code'] != 201) {
            echo json_encode(["status" => "erro", "api" => $res]);
            return;
        }
    
        $ret = $res['body']['pedido'] ?? null;
    
        if (!$ret) {
            echo json_encode(["status" => "erro", "msg" => "API não retornou pedido"]);
            return;
        }
    
        // ===============================
        // 6. SALVAR NO BANCO LOCAL
        // ===============================
        $pedidoModel = new Pedido();
        $pedidoId = $pedidoModel->salvarPedido($pedido, $ret['numeroPedido']);
    
        echo json_encode([
            "status" => "sucesso",
            "salvou_no_banco" => $pedidoId,
            "api" => $ret
        ]);
    }
    
    public function aprovar()
{
    header("Content-Type: application/json");

    if (empty($_POST['id'])) {
        echo json_encode(["status" => "erro", "msg" => "ID não enviado"]);
        return;
    }

    $pedidoId = $_POST['id'];

    // Buscar dados do pedido no banco
    $pedidoModel = new Pedido();
    $pedido = $pedidoModel->buscarPorId($pedidoId);

    if (!$pedido) {
        echo json_encode(["status" => "erro", "msg" => "Pedido não encontrado"]);
        return;
    }

    // Prepara payload para API da Precode
    $payload = [
        "pedido" => [
            "codigoPedido" => 0,
            "idPedidoParceiro" => $pedido['id_pedido_parceiro']
        ]
    ];

    error_log("[APROVAR] Enviando para Precode:");
    error_log(json_encode($payload, JSON_PRETTY_PRINT));

    $res = ApiClient::put("/v1/pedido/pedido", $payload);

    error_log("[APROVAR] Resposta da Precode:");
    error_log(print_r($res, true));

    if ($res['http_code'] != 200 && $res['http_code'] != 201) {
        echo json_encode(["status" => "erro", "api" => $res]);
        return;
    }

    $ret = $res['body']['pedido'] ?? null;

    if (!$ret) {
        echo json_encode(["status" => "erro", "msg" => "API não retornou estrutura de pedido"]);
        return;
    }

    // Atualizar status localmente
    $pedidoModel->atualizarStatus($pedidoId, "aprovado");

    echo json_encode([
        "status" => "sucesso",
        "pedido" => $ret
    ]);
    }

    public function cancelar()
    {
        header("Content-Type: application/json");
    
        if (empty($_POST['id'])) {
            echo json_encode(["status" => "erro", "msg" => "ID não enviado"]);
            return;
        }
    
        $pedidoId = $_POST['id'];
    
        // Buscar pedido no banco
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->buscarPorId($pedidoId);
    
        if (!$pedido) {
            echo json_encode(["status" => "erro", "msg" => "Pedido não encontrado"]);
            return;
        }
    
        // Payload conforme documentação
        $payload = [
            "pedido" => [
                "codigoPedido" => 0,
                "idPedidoParceiro" => $pedido['id_pedido_parceiro']
            ]
        ];
    
        error_log("[CANCELAR] Enviando para API Precode (DELETE)");
        error_log(json_encode($payload, JSON_PRETTY_PRINT));
    
        // Envia DELETE com corpo JSON
        $res = ApiClient::delete("/v1/pedido/pedido", $payload);
    
        error_log("[CANCELAR] Resposta da API:");
        error_log(print_r($res, true));
    
        if (!in_array($res['http_code'], [200, 201, 204])) {
            echo json_encode(["status" => "erro", "api" => $res]);
            return;
        }
    
        $ret = $res['body']['pedido'] ?? null;
    
        // Atualizar status localmente
        $pedidoModel->atualizarStatus($pedidoId, "cancelado");
    
        echo json_encode([
            "status" => "sucesso",
            "pedido" => $ret ?: [],
            "mensagem" => "Pedido cancelado com sucesso"
        ]);
    }
    
}