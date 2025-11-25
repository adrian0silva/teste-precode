<?php

class Pedido
{
    private $conn;
    public function __construct() {
        try {
            $db = new Database();
            $this->conn = $db->connect();
    
            // Log de sucesso
            if ($this->conn) {
                error_log("[DB] Conexão estabelecida com sucesso.");
            } else {
                error_log("[DB] Conexão retornou nula.");
            }
    
        } catch (Exception $e) {
            // Log de erro
            error_log("[DB ERRO] Falha ao conectar: " . $e->getMessage());
            throw $e; // opcional
        }
    }

    public function salvarPedido($pedido, $numeroGerado) {

        error_log("=== [PEDIDO] Iniciando salvamento ===");
        error_log("JSON Recebido:");
        error_log(json_encode($pedido, JSON_PRETTY_PRINT));
    
        try {
    
            // ---------------------------
            // SALVAR PEDIDO
            // ---------------------------
    
            error_log("[PEDIDO] Preparando INSERT do pedido...");
    
            $stmt = $this->conn->prepare("
                INSERT INTO pedidos (
                    id_pedido_parceiro,
                    valor_frete,
                    prazo_entrega,
                    valor_total_compra,
                    forma_pagamento,
                    status
                ) VALUES (
                    :idParceiro, :frete, :prazo, :total, :pagamento, 'novo'
                )
                RETURNING id
            ");
    
            $paramsPedido = [
                ':idParceiro' => $pedido['idPedidoParceiro'],
                ':frete'      => $pedido['valorFrete'],
                ':prazo'      => $pedido['prazoEntrega'],
                ':total'      => $pedido['valorTotalCompra'],
                ':pagamento'  => $pedido['formaPagamento']
            ];
    
            error_log("[PEDIDO] Bind Params:");
            error_log(print_r($paramsPedido, true));
    
            $stmt->execute($paramsPedido);
    
            $pedidoId = $stmt->fetchColumn();
    
            error_log("[PEDIDO] Pedido salvo com ID: $pedidoId");
    
    
            // ---------------------------
            // SALVAR ITENS
            // ---------------------------
    
            $sqlItem = $this->conn->prepare("
                INSERT INTO itens_pedido (pedido_id, sku, valor_unitario, quantidade)
                VALUES (:pedido_id, :sku, :valor, :qtd)
            ");
    
            foreach ($pedido['itens'] as $item) {
    
                $paramsItem = [
                    ':pedido_id' => $pedidoId,
                    ':sku'       => $item['sku'],
                    ':valor'     => $item['valorUnitario'],
                    ':qtd'       => $item['quantidade']
                ];
    
                error_log("[ITEM] Inserindo item...");
                error_log(print_r($paramsItem, true));
    
                $sqlItem->execute($paramsItem);
    
                error_log("[ITEM] Item inserido com sucesso.");
            }
    
            error_log("=== [PEDIDO] Finalizado com sucesso. ===");
            return $pedidoId;
    
        } catch (Exception $e) {
    
            error_log("=== [ERRO] Falha ao salvar pedido ===");
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
    
            return false;
        }
    }
    

    public function listarTodos()
    {
        $sql = "
            SELECT 
                p.id,
                p.id_pedido_parceiro,
                p.valor_frete,
                p.prazo_entrega,
                p.valor_total_compra,
                p.forma_pagamento,
                p.status,
                p.created_at,
                p.updated_at,
                COALESCE(SUM(i.valor_unitario * i.quantidade), 0) AS total_itens
            FROM pedidos p
            LEFT JOIN itens_pedido i ON i.pedido_id = p.id
            GROUP BY p.id
            ORDER BY p.id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarItens($pedidoId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                sku,
                valor_unitario,
                quantidade,
                (valor_unitario * quantidade) AS subtotal
            FROM itens_pedido
            WHERE pedido_id = :id
        ");

        $stmt->execute([':id' => $pedidoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
