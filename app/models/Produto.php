<?php



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
            error_log("=== [salvarBanco] INICIANDO SALVAMENTO ===");
            error_log("Produto recebido: " . print_r($produto, true));
    
            // Corrigido: nomes iguais ao controller
            $sku          = $produto['sku'] ?? 0;
            $name         = $produto['name'] ?? '';
            $descricao    = $produto['description'] ?? '';
            $preco        = $produto['price'] ?? 0;
            $promocional  = $produto['promotional_price'] ?? 0;
            $marca        = $produto['brand'] ?? '';
            $categoria    = $produto['category'] ?? '';
            $subcategory  = $produto['subcategory'] ?? '';
            $endcategory  = $produto['endcategory'] ?? '';
    
            error_log("Valores tratados:");
            error_log("sku={$sku} name={$name} preco={$preco} promo={$promocional}");
    
            // ==========================================
            // SALVAR PRODUTO
            // ==========================================
            $stmt = $this->conn->prepare("
                INSERT INTO products 
                    (sku, name, description, price, promotional_price, brand, category, subcategory, endcategory)
                VALUES 
                    (:sku, :name, :description, :price, :promotional_price, :brand, :category, :subcategory, :endcategory)
                RETURNING id
            ");
    
            $stmt->execute([
                ':sku' => $sku,
                ':name' => $name,
                ':description' => $descricao,
                ':price' => $preco,
                ':promotional_price' => $promocional,
                ':brand' => $marca,
                ':category' => $categoria,
                ':subcategory' => $subcategory,
                ':endcategory' => $endcategory
            ]);
    
            $productId = $stmt->fetchColumn();
            error_log("[salvarBanco] Produto salvo com ID = {$productId}");
    
            // ==========================================
            // SALVAR VARIAÇÕES (CORRIGIDO)
            // ==========================================
            if (!empty($produto['variations'])) {
    
                error_log("[salvarBanco] Salvando variações...");
                $sqlVar = $this->conn->prepare("
                    INSERT INTO product_variations 
                        (product_id, ref, sku, qty, ean)
                    VALUES 
                        (:product_id, :ref, :sku, :qty, :ean)
                ");
    
                foreach ($produto['variations'] as $v) {
    
                    error_log("Variação recebida: " . print_r($v, true));
    
                    $sqlVar->execute([
                        ':product_id' => $productId,
                        ':ref' => $v['ref'] ?? '',
                        ':sku' => $v['sku'] ?? null,
                        ':qty' => $v['qty'] ?? 0,
                        ':ean' => $v['ean'] ?? ''
                    ]);
    
                    error_log("Variação salva com sucesso.");
                }
            } else {
                error_log("[salvarBanco] Nenhuma variação encontrada em \$produto['variations']");
            }
    
            error_log("=== [salvarBanco] FINALIZADO ===");
    
        } catch (Exception $e) {
            error_log("[ERRO SALVAR BANCO] " . $e->getMessage());
            throw $e;
        }
    }    

    public function listarTodos() {
        $stmt = $this->conn->prepare("
            SELECT 
                p.id,
                p.sku,
                p.name,
                p.price,
                p.promotional_price,
                p.brand,
                p.category,
                p.subcategory,
                p.endcategory,
            MIN(v.ref) AS ref,               
                MIN(v.sku) AS variation_sku      
            FROM products p
            LEFT JOIN product_variations v ON v.product_id = p.id
            GROUP BY p.id
            ORDER BY p.id DESC
        ");
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function atualizaPrecoEstoque($products)
    {
        foreach ($products as $p) {
    
            $ref = $p['ref'] ?? null;
            $sku = $p['sku'] ?? null;
    
            if (!$ref && !$sku) continue;
    
            // 1) Buscar a variação correspondente
            $sql = "SELECT product_id FROM product_variations 
                    WHERE ref = :ref OR sku = :sku 
                    LIMIT 1";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ref' => $ref,
                ':sku' => $sku
            ]);
    
            $variation = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$variation) {
                error_log("Variação não encontrada para ref={$ref} sku={$sku}");
                continue;
            }
    
            $product_id = $variation['product_id'];
    
            // 2) Atualizar tabela products
            $sqlProduct = "
                UPDATE products
                SET price = :price,
                    promotional_price = :promo,
                    cost = :cost,
                    updated_at = NOW()
                WHERE id = :id
            ";
    
            $stmt2 = $this->conn->prepare($sqlProduct);
            $stmt2->execute([
                ':price' => $p['price'],
                ':promo' => $p['promotional_price'],
                ':cost' => $p['cost'],
                ':id' => $product_id
            ]);
    
            // 3) Atualizar stock na variação
            $sqlVar = "
                UPDATE product_variations
                SET qty = :qty
                WHERE product_id = :id AND (ref = :ref OR sku = :sku)
            ";
    
            $stmt3 = $this->conn->prepare($sqlVar);
            $stmt3->execute([
                ':qty' => $p['stock'][0]['availableStock'],
                ':id' => $product_id,
                ':ref' => $ref,
                ':sku' => $sku
            ]);
        }
    }
        
    public function obterSkuPrecodePorRef($ref)
    {
        error_log("=== [PRECODE] Buscando SKU pelo REF ===");
        error_log("REF recebido: $ref");
    
        $endpoint = "/v3/products/query/$ref/ref";
        error_log("URL chamada: $endpoint");
    
        // Chamada da API
        $res = ApiClient::get($endpoint);
    
        // LOGAR resposta bruta
        error_log("[API RESPONSE RAW] " . print_r($res, true));
    
        // Validar code
        if (!isset($res['http_code'])) {
            error_log("[ERRO] API não retornou http_code!");
            return null;
        }
    
        if ($res['http_code'] != 200) {
            error_log("[ERRO API] Código HTTP: " . $res['http_code']);
            return null;
        }
    
        // Validar corpo
        if (!isset($res['body'])) {
            error_log("[ERRO] API sem body!");
            return null;
        }
    
        $produto = $res['body']['produto'] ?? null;
    
        if (!$produto) {
            error_log("[ERRO] API retornou body sem 'produto'");
            error_log(print_r($res['body'], true));
            return null;
        }
    
        // Validar atributos
        if (!isset($produto['atributos'][0]['sku'])) {
            error_log("[ERRO] Produto encontrado, mas sem atributos->sku");
            error_log(print_r($produto, true));
            return null;
        }
    
        // --- SKU válido encontrado ---
        $skuValido = $produto['atributos'][0]['sku'];
    
        error_log("[PRECODE] SKU encontrado: $skuValido");
    
        return $skuValido;
    }
    

    public function obterRefPorSkuLocal($id_product)
{
    $stmt = $this->conn->prepare("
        SELECT ref
        FROM product_variations
        WHERE product_variations.product_id = :id_product
    ");

    $stmt->execute([':id_product' => $id_product]);

    return $stmt->fetchColumn();
}

}