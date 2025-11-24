<?php
class ProdutoController extends Controller {
    public function index() {
        $this->render('produtos.create', ['title' => 'CadastroProduto', 'Bem vindo a tela de cadastro de produto']);
    }

    public function create($data) {
        $body = [
            'sku' => $_POST['sku'],
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'description' => $_POST['description']
        ];

        $res = ApiClient::post('/products', $body);
        if ($res['http_code'] >= 200 && $res['http_code'] < 300) {
            Produto::salvarBanco($res['body']['id'] ?? null, $body);
            $message = "Produto enviado com sucesso!";
        } else {
            $message = "Erro: " . ($res['body']['message'] ?? $res['error']);
        }
    }
}