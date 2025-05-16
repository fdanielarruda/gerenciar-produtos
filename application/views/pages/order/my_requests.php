<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h5 class="mb-0">Meus Pedidos</h5>
</div>

<?php if ($this->input->get('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert" onclick="removeSuccessParam()">
        Pedido realizado com sucesso!
    </div>
<?php endif; ?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Detalhes</th>
            <th scope="col">Total</th>
            <th scope="col">Última atualização</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td>
                    <?php $products = $order['products'] ?? []; ?>
                    <ul>
                        <?php foreach ($products as $product): ?>
                            <?php if ($product) : ?>
                                <li>
                                    <?= $product['name'] ?> (<?= $product['quantity'] ?>) - R$ <?= number_format($product['price'], 2, ',', '.') ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li class="fw-bold text-success">Descontos: R$ <?= number_format($order['coupon_value'], 2, ',', '.') ?></li>
                        <li class="fw-bold text-primary">Frete: R$ <?= number_format($order['shipping'], 2, ',', '.') ?></li>
                    </ul>
                </td>
                <td>R$ <?= number_format($order['total'], 2, ',', '.') ?></td>
                <td><?= date('d/m/Y H:i:s', strtotime($order['updated_at'])) ?></td>
                <td>
                    <?php if ($order['status'] == 'pending'): ?>
                        <span class="badge bg-warning text-dark">Pendente</span>
                    <?php elseif ($order['status'] == 'paid'): ?>
                        <span class="badge bg-info text-dark">Pago</span>
                    <?php elseif ($order['status'] == 'completed'): ?>
                        <span class="badge bg-success">Concluído</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Cancelado</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($orders)): ?>
            <tr>
                <td colspan="5" class="text-center">Nenhum pedido encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
    const port = "<?= $_ENV['SOCKET_PORT'] ?>" ?? "3000";
    const socket = io("http://localhost:" + port);

    socket.on("order_updated", function(data) {
        console.log("Pedido atualizado via socket:", data);

        if (window.location.href.includes('order/my_requests')) {
            location.reload();
        }
    });
</script>