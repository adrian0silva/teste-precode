<?php

class Produto
{
    public static function salvarBanco($id,$data): void
    {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO products (sku, titulo, descricao, preco, estoque)
            VALUES (:sku, :titulo, :descricao, :preco, :estoque)
        ");

        $stmt->execute([
            ':sku' => $data["sku"],
            ':titulo' => $data["titulo"],
            ':descricao' => $data["descricao"],
            ':preco' => $data["preco"],
            ':estoque' => $data["estoque"]
        ]);
    }

}