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
        
        $produtoModel = new Produto();
        
        foreach ($pedido['itens'] as $i => $item) {
            $idProduto = $item['idProduto'];
            
            $ref = $produtoModel->obterRefPorSkuLocal($idProduto);
            
            if (!$ref) {
                echo json_encode([
                    "status" => "erro", 
                    "msg" => "REF não encontrada para idProduto $idProduto"
                ]);
                continue;
            }
            
            $skuValido = $produtoModel->obterSkuPrecodePorRef(ref: $ref);

            if (!$skuValido) {
                echo json_encode([
                    "status" => "erro", 
                    "msg" => "SKU da Precode não encontrado para REF $ref"
                ]);
                continue;
            }
            
            $pedido['itens'][$i]['sku'] = $skuValido;
        }
        
        $payload = ["pedido" => $pedido];
        
        $res = ApiClient::post("/v1/pedido/pedido", $payload);
            
        if ($res['http_code'] != 200 && $res['http_code'] != 201) {
            echo json_encode(["status" => "erro", "api" => $res]);
            return;
        }
    
        $ret = $res['body']['pedido'] ?? null;
    
        if (!$ret) {
            echo json_encode(["status" => "erro", "msg" => "API não retornou pedido"]);
            return;
        }

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