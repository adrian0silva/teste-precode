<?php

class PedidoController extends Controller {
    public function index() {
        $pedidos = Pedido::all();
        $this->render('pedido.index', ['pedidos' => $pedidos]);
    }

    // public function show($id = null) {
    //     $
    // }
}