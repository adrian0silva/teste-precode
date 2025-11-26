# Teste Precode - Hub de Integração com Marketplaces

Este projeto é uma simulação de um **HUB de integração com Marketplaces**, desenvolvido como parte do teste técnico da Precode. O sistema tem como objetivo controlar catálogo, pedidos, preços e estoque dos clientes de forma automatizada via API.

---

## Tecnologias Utilizadas

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Banco de Dados:** PostgreSQL  
- **Integração:** API do Marketplace [Replicade](https://www.replicade.com.br/api/)  

---

## Funcionalidades

### 1. Cadastro de Produtos
- Cadastro de novos produtos via API.  
- Interface web (HTML/CSS/JS) para preenchimento de dados do produto.  
- Exibe o retorno da API (sucesso ou erro).  

### 2. Atualização de Preço e Estoque
- Visualização de todos os produtos cadastrados.  
- Possibilidade de atualizar preço e saldo de cada produto.  
- Atualizações notificadas diretamente na API do Marketplace.  

### 3. Envio e Atualização de Pedidos
- Criação de pedidos utilizando produtos cadastrados.  
- Possibilidade de atualizar o status do pedido: aprovação de pagamento ou cancelamento.  
- Integração completa com a API para envio e atualização dos pedidos.

---

## Configuração

1. Clone o repositório:

```bash
git clone https://github.com/adrian0silva/teste-precode.git

---

## Servidor PHP
php -S localhost:8000 -t public

Acesse: http://localhost:8000

----

Banco de Dados

Docker:

docker compose up -d

Local: configure PostgreSQL localmente e ajuste credenciais no Database.php ou config.php.
