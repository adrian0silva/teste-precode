<?php

require_once __DIR__ . "/../config/db.php";
class Produto
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

    public function salvarBanco(array $produto): void
    {
        try {
            // SKU pai real
            $sku = $produto['codigoAgrupador'] ?? null;
    
            if (!$sku) {
                throw new Exception("SKU não encontrado no retorno da API.");
            }
    
            $titulo     = $produto['titulo'] ?? '';
            $descricao  = $produto['descricao'] ?? '';
            $preco      = $produto['precoSite'] ?? 0;
            $estoque    = 0; // estoque real pode vir apenas das variações
    
            // SALVAR PRODUTO
            $stmt = $this->conn->prepare("
                INSERT INTO products (sku, titulo, descricao, preco, estoque)
                VALUES (:sku, :titulo, :descricao, :preco, :estoque)
            ");
    
            $stmt->execute([
                ':sku'      => $sku,
                ':titulo'   => $titulo,
                ':descricao'=> $descricao,
                ':preco'    => $preco,
                ':estoque'  => $estoque
            ]);
    
            // SALVAR VARIAÇÕES
            if (!empty($produto['atributos'])) {
    
                $sqlVar = $this->conn->prepare("
                    INSERT INTO product_variations (sku_produto, sku_variacao, ref, ean, qty)
                    VALUES (:sku_produto, :sku_variacao, :ref, :ean, :qty)
                ");
    
                foreach ($produto['atributos'] as $v) {
                    $sqlVar->execute([
                        ':sku_produto'  => $sku,
                        ':sku_variacao' => $v['sku'] ?? null,
                        ':ref'          => $v['ref'] ?? '',
                        ':ean'          => $v['ean'] ?? '',
                        ':qty'          => $v['qty'] ?? 0
                    ]);
                }
            }
    
        } catch (Exception $e) {
            error_log("[ERRO SALVAR BANCO] " . $e->getMessage());
            throw $e;
        }
    }    

}