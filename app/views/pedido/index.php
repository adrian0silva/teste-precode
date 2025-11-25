<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gerenciar Pedidos - Precode</title>
  <link rel="stylesheet" href="/css/styles.css">
  <style>

    /* Card */
    .card{background:#fff;border-radius:10px;padding:28px;max-width:1100px;margin:0 auto;box-shadow:0 6px 18px rgba(15,23,42,0.06)}
    .title{display:flex;align-items:center;gap:10px}
    .title h2{color:#0f4bd8;font-size:28px;margin-bottom:6px}
    .lead{color:#64748b;margin-bottom:18px}

    .panel{background:#fff;border-radius:8px;padding:18px;border:1px solid #0b1220;margin-top:20px}
    .panel h3{font-size:20px;margin-bottom:8px}
    .panel p{color:#6b7280;margin-bottom:12px}

    /* Table */
    .table-wrap{overflow:auto}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    thead th{background:#fafbfd;text-align:left;padding:14px;border-bottom:1px solid #eef2f7;color:#475569;font-weight:600}
    tbody td{padding:16px;border-bottom:1px solid #f1f5f9;color:#111827;vertical-align:middle}
    tbody tr:hover td{background:#fbfdff}

    .col-small{text-align:center;width:80px}
    .col-price{text-align:right;width:130px;font-weight:700}
    .col-date{text-align:center;width:140px}
    .col-actions{text-align:center;width:150px}

    /* Badges */
    .badge{display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:600}
    .badge.green{background:#d1fadf;color:#166534}
    .badge.blue{background:#dbeafe;color:#1e40af}
    .badge.red{background:#fee2e2;color:#991b1b}
    .badge.neutral{background:#f3f4f6;color:#6b7280}

    /* Button */
    .btn-primary{background:#4f46e5;color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:6px}
    .btn-primary:hover{background:#4338ca}

    pre.result{white-space:pre-wrap;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e6edf3;margin-top:12px}

    /* MODAL FUNDO */
#modalPedido {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.55);
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

/* CAIXA DO MODAL */
/* --- GRID DO MODAL --- */
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

/* --- LINHAS DE INPUTS --- */
.modal-form .row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
  width: 100%;
}

/* --- INPUTS E SELECTS UNIFORMES --- */
.modal-form input,
.modal-form select {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #d0d5dd;
  border-radius: 6px;
  font-size: 15px;
  background: #fff;
  box-sizing: border-box;
}


@keyframes modalIn {
  from { transform: translateY(-20px); opacity: 0; }
  to   { transform: translateY(0); opacity: 1; }
}

/* TÍTULO */
.modal-box h2 {
  font-size: 22px;
  color: #111827;
  margin-bottom: 18px;
  font-weight: 600;
}

/* FORM */
.modal-form .row {
  display: flex;
  gap: 12px;
  margin-bottom: 12px;
}

.modal-form input,
.modal-form select {
  flex: 1;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  font-size: 15px;
  background: #f9fafb;
  transition: 0.2s;
}

.modal-form input:focus,
.modal-form select:focus {
  border-color: #3b82f6;
  background: #fff;
  outline: none;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}

/* SUBTÍTULOS DAS SEÇÕES */
.modal-box h3 {
  margin: 18px 0 10px 0;
  font-size: 18px;
  color: #374151;
  font-weight: 600;
}

/* BOTÕES */
.modal-footer {
  text-align: right;
  margin-top: 16px;
}

.btn-secondary {
  background: #9ca3af;
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.btn-secondary:hover {
  background: #6b7280;
}

.btn-primary {
  background: #4f46e5;
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.btn-primary:hover {
  background: #4338ca;
}

  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">HUB Precode</div>
    <nav class="nav">
      <a href="/">Cadastrar Produto</a>
      <a href="/produto/listar">Gerenciar Produtos</a>
      <a href="#" class="active">Pedidos</a>
    </nav>
  </aside>

  <main class="main">
    <section class="card">
      <div class="title">
        <h2>🧾 Gerenciar Pedidos</h2>
      </div>
      <p class="lead">Crie e gerencie pedidos do marketplace</p>

      <div style="text-align:right;margin-top:10px;margin-bottom:8px;">
      <button class="btn-primary" id="btnNovoPedido">➕ Novo Pedido</button>
<!-- MODAL NOVO PEDIDO -->
<div id="modalPedido">
  <div class="modal-box">

    <h2>➕ Criar Novo Pedido</h2>

    <form id="formNovoPedido" class="modal-form">

      <div class="row">
        <input name="idPedidoParceiro" placeholder="Código do Pedido" required />
        <input name="valorFrete" type="number" step="0.01" placeholder="Frete" required />
      </div>

      <div class="row">
        <input name="prazoEntrega" type="number" placeholder="Prazo de Entrega (dias)" />
        <select name="formaPagamento" required>
          <option value="">Forma de Pagamento</option>
          <option value="4">Visa</option>
          <option value="4">Mastercard</option>
          <option value="4">Boleto</option>
          <option value="4">Pix</option>
        </select>
      </div>

      <h3>Dados do Cliente</h3>

      <div class="row">
        <input name="cpfCnpj" placeholder="CPF/CNPJ" required />
        <input name="nomeRazao" placeholder="Nome / Razão Social" required />
      </div>

      <div class="row">
        <input name="fantasia" placeholder="Fantasia" required />
        <input name="email" placeholder="Email" required />
      </div>

      <h3>Endereço</h3>

      <div class="row">
        <input name="cep" placeholder="CEP" required />
        <input name="endereco" placeholder="Endereço" required />
      </div>

      <div class="row">
        <input name="numero" placeholder="Número" required />
        <input name="bairro" placeholder="Bairro" required />
      </div>

      <div class="row">
        <input name="cidade" placeholder="Cidade" required />
        <input name="uf" placeholder="UF" maxlength="2" required />
      </div>

      <h3>Itens</h3>

      <div class="row">
      <select name="sku" id="selectProdutos" required style="padding:10px;border:1px solid #ddd;border-radius:6px;width:100%;">
        <option value="">Selecione um produto...</option>
        </select>
        <input name="valorUnitario" type="number" step="0.01" placeholder="Valor Unitário" required />
        <input name="quantidade" type="number" placeholder="Quantidade" required />
      </div>

      <div class="modal-footer">
        <button type="button" id="btnCancelarModal" class="btn-secondary">Cancelar</button>
        <button type="submit" class="btn-primary">Enviar Pedido</button>
      </div>

      <pre id="resultadoPedido" style="display:none; margin-top:14px;"></pre>
    </form>

  </div>
</div>

      </div>

      <div class="panel">
        <h3>Lista de Pedidos</h3>
        <p>Gerencie aprovações e cancelamentos de pedidos</p>

        <div class="table-wrap">
          <table id="pedidos-table">
            <thead>
              <tr>
                <th>Número</th>
                <th class="col-price">Valor</th>
                <th class="col-date">Data</th>
                <th>Status</th>
                <th class="col-actions">Ações</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($pedidos)): ?>
    <tr>
        <td colspan="8" style="text-align:center; color:#94a3b8; padding:18px;">
            Nenhum pedido encontrado
        </td>
    </tr>
<?php else: ?>

    <?php foreach ($pedidos as $p): ?>

        <?php
        // Badge de status
        $badge = match ($p['status']) {
            'aprovado'   => '<span class="badge blue">Aprovado</span>',
            'cancelado'  => '<span class="badge red">Cancelado</span>',
            'processado' => '<span class="badge green">Processado</span>',
            default      => '<span class="badge neutral">Pendente</span>',
        };
        ?>

        <tr>
            <td><strong><?= $p['id_pedido_parceiro'] ?></strong></td>

            <td class="col-price">
                R$ <?= $p['valor_total_compra'] ?>
            </td>

            <td class="col-date">
                <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
            </td>

            <td><?= $badge ?></td>

            <td class="col-actions">

<?php if ($p['status'] === 'novo'): ?>

    <div style="display:flex;flex-direction:column;gap:6px;align-items:center;">

    <button class="btn-primary btn-aprovar"
        data-id="<?= $p['id'] ?>"
        style="padding:6px 10px;font-size:13px">
    Aprovar
</button>

<button class="btn-primary btn-cancelar"
        data-id="<?= $p['id'] ?>"
        style="background:#dc2626;padding:6px 10px;font-size:13px">
    Cancelar
</button>

    </div>

<?php else: ?>

    <span style="color:#6b7280;font-size:13px;">—</span>

<?php endif; ?>

</td>

        </tr>

    <?php endforeach; ?>

<?php endif; ?>
            </tbody>
          </table>
        </div>

        <div id="empty" class="empty" style="display:none;text-align:center;color:#94a3b8;padding:26px;">Nenhum pedido encontrado.</div>

        <pre id="api-result" class="result" style="display:none"></pre>

      </div>
    </section>
  </main>

<script>
// 👉 Aprovar pedido
document.querySelectorAll(".btn-aprovar").forEach(btn => {
    btn.onclick = async () => {
        const id = btn.dataset.id;

        const resp = await fetch("/pedido/aprovar", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ id })
        });

        const json = await resp.json();
        console.log("APROVAR:", json);

        if (json.status === "sucesso") {
            btn.closest("tr").querySelector("td:nth-child(5)").innerHTML =
                '<span class="badge blue">Aprovado</span>';

            // remove botões
            btn.closest("td").innerHTML = '<span style="color:#6b7280;">—</span>';
        }
    };
});


// 👉 Cancelar pedido
document.querySelectorAll(".btn-cancelar").forEach(btn => {
    btn.onclick = async () => {
        const id = btn.dataset.id;

        const resp = await fetch("/pedido/cancelar", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ id })
        });

        const json = await resp.json();
        console.log("CANCELAR:", json);

        if (json.status === "sucesso") {
            btn.closest("tr").querySelector("td:nth-child(5)").innerHTML =
                '<span class="badge red">Cancelado</span>';

            // remove botões
            btn.closest("td").innerHTML = '<span style="color:#6b7280;">—</span>';
        }
    };
});

// abrir modal
document.getElementById("btnNovoPedido").onclick = () => {
    document.getElementById("modalPedido").style.display = "flex";
    fetch("/produto/json")
    .then(r => r.json())
    .then(lista => {
        let select = document.getElementById("selectProdutos");
        select.innerHTML = '<option value="">Selecione um produto...</option>';

        lista.forEach(p => {
            const nome = p.name || "Sem nome";
            select.innerHTML += `
                    <option value="${p.id}"
                            data-sku="${p.sku}"
                            data-ref="${p.ref}">
                        ${p.sku} — ${p.name}
                    </option>`;
        });
    })
    .catch(e => console.error("Erro ao carregar produtos:", e));
};

// fechar modal
document.getElementById("btnCancelarModal").onclick = () => {
    document.getElementById("modalPedido").style.display = "none";
};


// enviar para API local + Precode
document.getElementById("formNovoPedido").addEventListener("submit", async (e) => {
    e.preventDefault();

    const f = new FormData(e.target);
    const dados = Object.fromEntries(f.entries());
    const select = document.getElementById("selectProdutos");
    console.log("select")
    console.log(select)
    const idProduto = select.value;
    console.log("idProduto")
    console.log(idProduto)
    // monta JSON para Precode
    const payload = {
        pedido: {
            idPedidoParceiro: dados.idPedidoParceiro,
            valorFrete: parseFloat(dados.valorFrete),
            prazoEntrega: parseInt(dados.prazoEntrega) || 0,
            valorTotalCompra: parseFloat(dados.valorUnitario) * parseInt(dados.quantidade)
                              + parseFloat(dados.valorFrete),

            formaPagamento: dados.formaPagamento,

            dadosCliente: {
                cpfCnpj: dados.cpfCnpj,
                nomeRazao: dados.nomeRazao,
                fantasia: dados.fantasia,
                sexo: "",
                dataNascimento: "",
                email: dados.email,

                dadosEntrega: {
                    cep: dados.cep,
                    endereco: dados.endereco,
                    numero: dados.numero,
                    bairro: dados.bairro,
                    complemento: "",
                    cidade: dados.cidade,
                    uf: dados.uf,
                    responsavelRecebimento: dados.nomeRazao
                },

                telefones: {
                    residencial: "00000000",
                    comercial: "",
                    celular: "00000000"
                }
            },

            pagamento: [{
                valor: parseFloat(dados.valorUnitario) * parseInt(dados.quantidade),
                quantidadeParcelas: 1,
                meioPagamento: dados.formaPagamento
            }],

            itens: [{
                idProduto: parseInt(idProduto),
                sku: parseInt(dados.sku),
                valorUnitario: parseFloat(dados.valorUnitario),
                quantidade: parseInt(dados.quantidade)
            }]
        }
    };


    // Enviar para sua API local (salvar banco)
    const localResp = await fetch("/pedido/salvar", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    });

    const localJson = await localResp.json();


    // Enviar para API PRECODE
    const precodeResp = await fetch("https://www.replicade.com.br/api/v1/pedido/pedido", {
        method: "POST",
        headers: {
            "Authorization": "Basic aXdPMzVLZ09EZnRvOHY3M1I6",
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    });

    const precodeJson = await precodeResp.json();


    const result = document.getElementById("resultadoPedido");
    result.style.display = "block";
    result.innerText =
        "LOCAL API:\n" + JSON.stringify(localJson, null, 2) +
        "\n\nPREC0DE:\n" + JSON.stringify(precodeJson, null, 2);

    setTimeout(() => location.reload(), 1200);
});

</script>
</body>
</html>