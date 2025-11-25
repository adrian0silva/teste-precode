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
    $input = json_decode(file_get_contents("php://input"), true);
    $id = $input['id'];

    $pedidoModel = new Pedido();
    $pedido = $pedidoModel->buscarPorId($id);

    // Envia aprovação para Precode
    $resp = ApiClient::put("/v1/pedido/pedido", [
        "pedido" => [
            "codigoPedido" => 0,
            "idPedidoParceiro" => $pedido['id_pedido_parceiro']
        ]
    ]);

    // Atualizar no banco
    $pedidoModel->atualizarStatus($id, "aprovado");

    echo json_encode(["status" => "sucesso"]);
    }

    public function cancelar()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];
    
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->buscarPorId($id);
    
        $resp = ApiClient::delete("/v1/pedido/pedido", [
            "pedido" => [
                "codigoPedido" => 0,
                "idPedidoParceiro" => $pedido['id_pedido_parceiro']
            ]
        ]);
    
        $pedidoModel->atualizarStatus($id, "cancelado");
    
        echo json_encode(["status" => "sucesso"]);
    }
    
    
}