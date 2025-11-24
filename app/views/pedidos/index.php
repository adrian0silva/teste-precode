<?php ?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Pedidos</title></head>
<body>
    <h1>Lista de Pedidos</h1>
    <ul>
        <?php foreach ($users as $u): ?>
            <li><a href="/user/<?= $u['id'] ?>"><?=htmlspecialchars($u['name'])?></a> — <?=htmlspecialchars($u['email'])?></li>
        <?php endforeach; ?>
    </ul>
    <p><a href="/">Voltar</a></p>
</body>
</html>