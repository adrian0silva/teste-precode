<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gerenciar Produtos - Precode</title>

  <!-- Fonte -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/styles.css">
  <style>
/* LINHA DE CAMPOS */
.form-row {
  display: flex;
  gap: 16px;
  margin-bottom: 18px;
}

/* GRUPO (label + input) */
.field {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* LABEL ACIMA DO INPUT */
.field label {
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 6px;
}

/* INPUTS */
.field input,
.field select {
  padding: 10px 12px;
  font-size: 15px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  background: #f9fafb;
  transition: border-color .2s, box-shadow .2s, background .2s;
}

/* HOVER */
.field input:hover,
.field select:hover {
  background: #f3f4f6;
}

/* FOCUS */
.field input:focus,
.field select:focus {
  border-color: #4f46e5;
  background: #fff;
  outline: none;
  box-shadow: 0 0 0 3px rgba(99,102,241,.25);
}

/* MODAL */
.modal-box {
  background: #fff;
  padding: 26px;
  border-radius: 12px;
  width: 700px;
  box-shadow: 0 12px 32px rgba(0,0,0,.15);
}

.modal-box h3 {
  margin-bottom: 18px;
  font-size: 22px;
  color: #111827;
  font-weight: 600;
}

  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">HUB Precode</div>

    <nav class="nav">
      <a href="/produto/index">Cadastrar Produto</a>
      <a href="#" class="active">Gerenciar Produtos</a>
      <a href="/pedido">Pedidos</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <section class="card">

      <div class="title">
        <h2>📦 Produtos Cadastrados</h2>
      </div>

      <p class="lead">
        Visualize, monitore e atualize suas informações de catálogo.
      </p>

      <div class="panel">
        <h3>Lista de Produtos</h3>
        <p>Clique em editar para atualizar preço e estoque quando necessário.</p>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>SKU</th>
                <th>Nome</th>
                <th class="col-price">Preço</th>
                <th class="col-price">Promo</th>
                <th>Marca</th>
                <th>Categoria</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($produtos as $p): ?>
              <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['sku'] ?></td>
                <td><?= $p['name'] ?></td>
                <td class="col-price">R$ <?= number_format($p['price'], 2, ',', '.') ?></td>
                <td class="col-price">R$ <?= number_format($p['promotional_price'], 2, ',', '.') ?></td>
                <td><?= $p['brand'] ?></td> 
                <td><?= $p['category'] ?></td>
                <td class="col-actions">
    <button class="edit-btn btn"
        data-ref="<?= htmlspecialchars($p['ref'] ?? '') ?>"
        data-sku="<?= htmlspecialchars($p['sku'] ?? '') ?>"

        data-price="<?= htmlspecialchars($p['price']) ?>"
        data-promotional="<?= htmlspecialchars($p['promotional_price']) ?>"
        data-cost="<?= htmlspecialchars($p['cost']) ?>"

        data-available="<?= htmlspecialchars($p['available_stock'] ?? $p['stock'] ?? 0) ?>"

        data-shipping-time="<?= htmlspecialchars($p['shippingTime'] ?? 0) ?>"
        data-status="<?= htmlspecialchars($p['status'] ?? 'enabled') ?>"
    >
        ✏️ Editar
    </button>
</td>

              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div id="empty" class="empty" style="display:none">Nenhum produto encontrado.</div>

        <pre id="api-result" class="result" style="display:none"></pre>

      </div>
    </section>

    <!-- MODAL -->
    <div id="editModal">
    <div class="modal-box">

<h3>Editar Preço & Estoque</h3>

<form id="editForm">

  <input type="hidden" id="edit_ref">
  <input type="hidden" id="edit_sku">

  <!-- LINHA 1 -->
  <div class="form-grid">
    <div class="form-group">
      <label for="edit_price">Preço (price)</label>
      <input id="edit_price" type="number" step="0.01" required>
    </div>

    <div class="form-group">
      <label for="edit_promotional_price">Promo (promotional_price)</label>
      <input id="edit_promotional_price" type="number" step="0.01" required>
    </div>

    <div class="form-group">
      <label for="edit_cost">Custo (cost)</label>
      <input id="edit_cost" type="number" step="0.01" required>
    </div>
  </div>

  <!-- LINHA 2 -->
  <div class="form-grid">
    <div class="form-group">
      <label for="edit_shippingTime">Shipping Time (dias)</label>
      <input id="edit_shippingTime" type="number" required>
    </div>

    <div class="form-group">
      <label for="edit_status">Status</label>
      <select id="edit_status">
        <option value="enabled">Habilitado</option>
        <option value="disabled">Desabilitado</option>
      </select>
    </div>

    <div class="form-group">
      <label for="edit_availableStock">AvailableStock</label>
      <input id="edit_availableStock" type="number" required>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" id="cancelEdit" class="btn btn-outline">Cancelar</button>
    <button type="submit" class="btn btn-primary">Salvar e Enviar</button>
  </div>

  <pre id="editResult" class="result" style="display:none"></pre>
</form>
</div>



      
    </div>
    <div id="toast"
          style="position:fixed;bottom:20px;right:20px;background:#4f46e5;
                  color:#fff;padding:12px 18px;border-radius:6px;display:none;">
        Estoque atualizado!
      </div>
  </main>

<script>
  /* ABRIR MODAL */
  document.addEventListener('click', (ev) => {
    const btn = ev.target.closest('.edit-btn');
    if (!btn) return;

    document.getElementById('edit_ref').value = btn.dataset.ref || '';
    document.getElementById('edit_sku').value = btn.dataset.sku || '';
    document.getElementById('edit_price').value = btn.dataset.price || '';
    document.getElementById('edit_promotional_price').value = btn.dataset.promotional || '';
    document.getElementById('edit_cost').value = btn.dataset.cost || '';
    document.getElementById('edit_shippingTime').value = btn.dataset.shippingTime || '0';
    document.getElementById('edit_status').value = btn.dataset.status || 'enabled';
    document.getElementById('edit_availableStock').value = btn.dataset.available || '0';

    document.getElementById('editModal').style.display = 'flex';
  });

  /* FECHAR MODAL */
  document.getElementById('cancelEdit').addEventListener('click', () => {
    document.getElementById('editModal').style.display = 'none';
  });

  /* ENVIAR ATUALIZAÇÃO */
  document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const products = [{
      ref: document.getElementById('edit_ref').value || null,
      sku: document.getElementById('edit_sku').value ? parseInt(document.getElementById('edit_sku').value) : null,
      promotional_price: parseFloat(document.getElementById('edit_promotional_price').value),
      price: parseFloat(document.getElementById('edit_price').value),
      priceSite: 0,
      cost: parseFloat(document.getElementById('edit_cost').value),
      shippingTime: parseInt(document.getElementById('edit_shippingTime').value),
      status: document.getElementById('edit_status').value,
      stock: [{
        stores: 1,
        availableStock: parseInt(document.getElementById('edit_availableStock').value),
        realStock: parseInt(document.getElementById('edit_availableStock').value)
      }]
    }];

    const resp = await fetch('/produto/updateInventory', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ products })
    });

    const text = await resp.text();
    // document.getElementById('editResult').style.display = 'block';
    // document.getElementById('editResult').innerText = text;
    document.getElementById('editModal').style.display = 'none';
    document.getElementById("toast").style.display = "block";
    setTimeout(() => {
      document.getElementById("toast").style.display = "none";
    }, 2000);

    try {
      const json = JSON.parse(text);
      if (json && json.products) {
        setTimeout(() => {
          atualizarLinhaTabela(products[0]);
          document.getElementById('editModal').style.display = 'none';
        }, 600);
      }
    } catch (err) {}
  });
  function atualizarLinhaTabela(p) {
    const linhas = document.querySelectorAll("tbody tr");

    linhas.forEach(tr => {
        const sku = tr.children[1].innerText.trim(); // SKU da tabela

        if (sku == p.sku) {

            tr.children[3].innerHTML = "R$ " + p.price.toFixed(2).replace(".", ",");
            tr.children[4].innerHTML = "R$ " + p.promotional_price.toFixed(2).replace(".", ",");

            tr.children[7].querySelector(".edit-btn").dataset.price = p.price;
            tr.children[7].querySelector(".edit-btn").dataset.promotional = p.promotional_price;
            tr.children[7].querySelector(".edit-btn").dataset.cost = p.cost;
            tr.children[7].querySelector(".edit-btn").dataset.available = p.stock[0].availableStock;

            // feedback visual
            tr.style.background = "#e0ffe3";
            setTimeout(() => tr.style.background = "", 1200);
        }
    });
    
}

</script>

</body>
</html>
