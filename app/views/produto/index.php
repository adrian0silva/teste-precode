<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <style>
        /* Reset simples */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Inter, Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            min-height: 100vh;
            display: flex;
        }


        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #0f1724;
            color: #e6edf3;
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .brand {
            font-weight: 700;
            font-size: 18px;
        }

        .nav {
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav a {
            display: block;
            padding: 10px 12px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
        }

        .nav a.active,
        .nav a:hover {
            background: #111827;
            color: #ffffff;
        }


        /* Main */
        .main {
            flex: 1;
            padding: 28px 36px;
        }

        header h1 {
            font-size: 20px;
            margin-bottom: 18px;
            color: #0b1220;
        }


        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 28px;
            max-width: 1100px;
            margin: 0 auto;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .card h2 {
            color: #0f4bd8;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .card p.lead {
            color: #64748b;
            margin-bottom: 18px;
        }


        .section-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .section-sub {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 12px;
        }


        form {
            display: grid;
            gap: 18px;
            grid-template-columns: 1fr;
        }

        @media(min-width: 768px) {
            form {
                grid-template-columns: repeat(2, 1fr);
            }
        }


        label {
            display: block;
            font-size: 13px;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e6edf3;
            border-radius: 8px;
            background: #fafafa;
            color: #111827;
            font-size: 14px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }


        .full {
            grid-column: 1 / -1;
        }


        .btn {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover {
            background: #4338ca;
        }


        .result {
            margin-top: 18px;
            padding: 14px;
            background: #f8fafc;
            border-radius: 8px;
            color: #0b1220;
            font-size: 14px;
            border: 1px solid #e6edf3;
        }


        /* Form grid helpers to match screenshot spacing */
        .field {
            display: flex;
            flex-direction: column;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }


        /* Tiny helpers */
        .muted {
            color: #9ca3af;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">Menu Principal</div>
        <nav class="nav">
            <a href="#" class="active">Cadastrar Produto</a>
            <a href="/pedido">Gerenciar Produtos</a>
            <a href="/pedido">Pedidos</a>
        </nav>
    </aside>
    Cadastro de produtos
</body>
</html>