<?php ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        /* Caixa geral da variação */
.variation-box {
    width: 420px;          /* controla largura da área */
    padding: 20px;
    margin-bottom: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 10px;             /* espaço entre inputs */
}

/* Título da variação */
.variation-box h4 {
    margin: 0 0 5px 0;
    font-size: 17px;
    font-weight: 600;
}

/* Labels */
.variation-box label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
    display: block;
}

/* Inputs */
.variation-box input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

/* Botão de adicionar */
.add-btn {
    color: #0a65cc;
    cursor: pointer;
    font-weight: 600;
    margin-top: 5px;
}

/* Remover variação */
.remove-var {
    margin-top: 10px;
    color: #cc0000;
    font-size: 14px;
    cursor: pointer;
    font-weight: bold;
}

    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">HUB Precode</div>
        <nav class="nav">
            <a href="#" class="active">Cadastrar Produto</a>
            <a href="/produto/listar">Gerenciar Produtos</a>
            <a href="/pedido">Pedidos</a>
        </nav>
    </aside>

    <main class="main">

        <section class="card">
            <h2>📦 Cadastrar Novo Produto</h2>
            <p class="lead">Preencha os dados abaixo para enviar um novo produto para a API</p>
            <div class="section-title">Informações do Produto</div>
            <div class="section-sub">Todos os campos são obrigatórios para o cadastro</div>
            <form id="form-produto">
                <div class="field">
                    <label>SKU Pai (opcional - inteiro ou null)</label>
                    <input type="number" id="sku" placeholder="Ex: 1234 ou vazio">
                </div>

                <!-- Nome -->
                <div class="field">
                    <label>Nome do Produto (mínimo 20 caracteres) *</label>
                    <input type="text" id="name" required minlength="20">
                </div>

                <!-- shortName -->
                <div class="field">
                    <label>Título Curto (opcional)</label>
                    <input type="text" id="shortName">
                </div>

                <!-- Descrição -->
                <div class="field">
                    <label>Descrição Completa (mín 100 caracteres) *</label>
                    <textarea id="description" required minlength="100"></textarea>
                </div>

                <!-- Status -->
                <div class="field">
                    <label>Status *</label>
                    <select id="status">
                        <option value="enabled">habilitado</option>
                        <option value="disabled">desabilitado</option>
                    </select>
                </div>

                <!-- Preços -->
                <div class="field">
                    <label>Preço *</label>
                    <input type="number" step="0.01" id="price" required>
                </div>

                <div class="field">
                    <label>Preço Promocional *</label>
                    <input type="number" step="0.01" id="promotional_price" required>
                </div>

                <div class="field">
                    <label>Custo *</label>
                    <input type="number" step="0.01" id="cost" required>
                </div>

                <!-- Dimensões -->
                <div class="field">
                    <label>Peso (kg) *</label>
                    <input type="number" step="0.001" id="weight" required>
                </div>

                <div class="field">
                    <label>Largura (cm) *</label>
                    <input type="number" step="0.001" id="width" required>
                </div>

                <div class="field">
                    <label>Altura (cm) *</label>
                    <input type="number" step="0.001" id="height" required>
                </div>

                <div class="field">
                    <label>Comprimento (cm) *</label>
                    <input type="number" step="0.001" id="length" required>
                </div>

                <!-- Marca -->
                <div class="field">
                    <label>Marca *</label>
                    <input type="text" id="brand" required>
                </div>

                <!-- Categoria -->
                <div class="field">
                    <label>Categoria (opcional)</label>
                    <input type="text" id="category">
                </div>

                <!-- Variações -->
                <h3>Variações do Produto (obrigatório pelo menos 1)</h3>

                <div id="variations"></div>

                <div class="add-btn" onclick="addVariation()">+ Adicionar Variação</div>

                <br><br>


                <div class="field full">
                    <button class="btn" type="submit">Cadastrar Produto</button>
                </div>
            </form>
            <div id="toast"
                style="position:fixed;bottom:20px;right:20px;background:#4f46e5;
                  color:#fff;padding:12px 18px;border-radius:6px;display:none;">
                Produto cadastrado!
            </div>
        </section>
    </main>
    <script>
        let variationCount = 0;

        function addVariation() {
            variationCount++;

            const div = document.createElement("div");
            div.classList.add("variation-box");
            div.setAttribute("data-var", variationCount);

            div.innerHTML = `
        <h4>Variação #${variationCount}</h4>

        <label>REF (opcional)</label>
        <input type="text" class="ref">

        <label>SKU (opcional - inteiro ou null)</label>
        <input type="number" class="skuVar">

        <label>Quantidade</label>
        <input type="number" class="qty">

        <label>EAN</label>
        <input type="text" class="ean">

        <label>URL da Imagem (JPG quadrada) *</label>
        <input type="text" class="image">

        <h4>Especificações</h4>
        <div class="specs"></div>
        <div class="add-btn" onclick="addSpec(this)">Adicionar Especificação</div>

        <div class="remove-var" onclick="this.parentElement.remove()">Excluir Variação</div>
    `;

            document.getElementById("variations").appendChild(div);
        }

        function addSpec(btn) {
            const box = document.createElement("div");
            box.classList.add("spec-box");

            box.innerHTML = `
        <label>Key</label>
        <input type="text" class="specKey">
        <label>Value</label>
        <input type="text" class="specValue">
        <div class="remove-var" onclick="this.parentElement.remove()">Remover</div>
    `;

            btn.previousElementSibling.appendChild(box);
        }

        document.getElementById("form-produto").addEventListener("submit", async function(e) {
            e.preventDefault();

            const productJSON = {
                product: {
                    sku: document.getElementById("sku").value || null,
                    name: document.getElementById("name").value,
                    shortName: document.getElementById("shortName").value,
                    description: document.getElementById("description").value,
                    status: document.getElementById("status").value,
                    price: parseFloat(document.getElementById("price").value),
                    promotional_price: parseFloat(document.getElementById("promotional_price").value),
                    cost: parseFloat(document.getElementById("cost").value),
                    weight: parseFloat(document.getElementById("weight").value),
                    width: parseFloat(document.getElementById("width").value),
                    height: parseFloat(document.getElementById("height").value),
                    length: parseFloat(document.getElementById("length").value),
                    brand: document.getElementById("brand").value,
                    category: document.getElementById("category").value,
                    variations: []
                }
            };

            // Montar variações
            const variationsDiv = document.querySelectorAll(".variation-box");

            variationsDiv.forEach(v => {
                const variation = {
                    ref: v.querySelector(".ref").value,
                    sku: v.querySelector(".skuVar").value || null,
                    qty: v.querySelector(".qty").value,
                    ean: v.querySelector(".ean").value,
                    images: [v.querySelector(".image").value],
                    specifications: []
                };

                const specs = v.querySelectorAll(".spec-box");

                specs.forEach(s => {
                    variation.specifications.push({
                        key: s.querySelector(".specKey").value,
                        value: s.querySelector(".specValue").value
                    })
                });

                productJSON.product.variations.push(variation);
            });

            document.getElementById("resultado").textContent = JSON.stringify(productJSON, null, 2);

            // Depois isso vai ser enviado ao PHP:
            try {
                const resposta = await fetch("/produto/create", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(productJSON)
                });

                const json = await resposta.json();

                resultado.style.display = "block";
                resultado.style.background = json.status === "erro" ? "#ffdddd" : "#ddffdd";
                resultado.innerText = JSON.stringify(json, null, 2);

            } catch (error) {
                resultado.style.display = "block";
                resultado.style.background = "#ffdddd";
                resultado.innerText = "Erro ao enviar o produto: " + error;
            }
        });
    </script>
</body>

</html>