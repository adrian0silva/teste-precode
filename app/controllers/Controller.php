<?php
class Controller
    {    public function __construct() {
        // inicializações globais da aplicação
    }
    protected function render(string $view, array $data = [])
    {
        extract($data);
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: $viewFile");
        }
        require $viewFile;
    }
}