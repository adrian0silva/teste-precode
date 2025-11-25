<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gerenciar Produtos - Precode</title>

  <!-- Fonte -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #f5f7fb;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
    }

    /* SIDEBAR */
    .sidebar {
      width: 260px;
      background: #0f172a;
      color: #cbd5e1;
      padding: 32px 26px;
      display: flex;
      flex-direction: column;
      gap: 28px;
      box-shadow: 4px 0 18px rgba(0, 0, 0, 0.1);
    }

    .brand {
      font-weight: 700;
      font-size: 20px;
      color: #fff;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .nav a {
      padding: 12px 14px;
      text-decoration: none;
      border-radius: 8px;
      font-size: 15px;
      color: #cbd5e1;
      transition: 0.2s;
    }

    .nav a:hover {
      background: #1e293b;
      color: #fff;
    }

    .nav a.active {
      background: #3b82f6;
      color: #fff;
      font-weight: 600;
    }

    /* MAIN */
    .main {
      flex: 1;
      padding: 36px 46px;
    }

    header h1 {
      font-size: 24px;
      margin-bottom: 24px;
      color: #0f172a;
      font-weight: 600;
    }

    /* CARD */
    .card {
      background: #fff;
      border-radius: 14px;
      padding: 32px;
      max-width: 1200px;
      margin: 0 auto;
      box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);
    }

    .title {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .title h2 {
      font-size: 28px;
      color: #2563eb;
      font-weight: 700;
    }

    .lead {
      color: #64748b;
      margin-bottom: 26px;
    }

    .panel {
      background: #fff;
      border-radius: 10px;
      padding: 22px;
      border: 1px solid #e2e8f0;
      margin-top: 14px;
    }

    .panel h3 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .panel p {
      color: #6b7280;
      margin-bottom: 16px;
    }

    /* TABELA */
    .table-wrap {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 14px;
      font-size: 15px;
    }

    thead th {
      background: #eef2ff;
      padding: 14px;
      text-align: left;
      font-weight: 600;
      color: #334155;
      border-bottom: 2px solid #e2e8f0;
    }

    tbody td {
      padding: 16px;
      border-bottom: 1px solid #f1f5f9;
      color: #1e293b;
    }

    tbody tr:hover td {
      background: #f8fafc;
    }

    .col-price {
      text-align: right;
    }

    /* ESTADO VAZIO */
    .empty {
      padding: 28px;
      text-align: center;
      color: #94a3b8;
      font-size: 16px;
    }

    /* RESULTADOS */
    pre.result {
      white-space: pre-wrap;
      background: #f8fafc;
      padding: 14px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      margin-top: 16px;
      font-size: 14px;
    }

    /* MODAL */
    #editModal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(2, 6, 23, 0.6);
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .modal-box {
      width: 720px;
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 12px 40px rgba(2, 6, 23, 0.4);
    }

    .modal-box h3 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 14px;
    }

    .form-row {
      display: flex;
      gap: 12px;
      margin-bottom: 12px;
    }

    .form-row label {
      font-weight: 500;
      margin-bottom: 4px;
      display: block;
    }

    .form-row input,
    .form-row select {
      width: 100%;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #e6edf3;
      font-size: 14px;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 14px;
    }

    .btn {
      padding: 8px 14px;
      border-radius: 8px;
      border: 1px solid #e6edf3;
      cursor: pointer;
    }

    .btn-primary {
      background: #2563eb;
      color: #fff;
      border: none;
    }

    .btn-outline {
      background: #fff;
      border: 1px solid #cbd5e1;
      color: #0f172a;
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
    <header>
      <h1>Gerenciamento Central de Produtos</h1>
    </header>

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

          <div class="form-row">
            <div style="flex:1">
              <label>Preço (price)</label>
              <input id="edit_price" type="number" step="0.01" required>
            </div>

            <div style="flex:1">
              <label>Promo (promotional_price)</label>
              <input id="edit_promotional_price" type="number" step="0.01" required>
            </div>

            <div style="flex:1">
              <label>Custo (cost)</label>
              <input id="edit_cost" type="number" step="0.01" required>
            </div>
          </div>

          <div class="form-row">
            <div style="flex:1">
              <label>Shipping Time (dias)</label>
              <input id="edit_shippingTime" type="number" required>
            </div>

            <div style="flex:1">
              <label>Status</label>
              <select id="edit_status">
                <option value="enabled">enabled</option>
                <option value="disabled">disabled</option>
              </select>
            </div>

            <div style="flex:1">
              <label>AvailableStock</label>
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
    document.getElementById('editResult').style.display = 'block';
    document.getElementById('editResult').innerText = text;

    try {
      const json = JSON.parse(text);
      if (json && json.products) {
        setTimeout(() => {
          document.getElementById('editModal').style.display = 'none';
          location.reload();
        }, 800);
      }
    } catch (err) {}
  });
</script>

</body>
</html>
