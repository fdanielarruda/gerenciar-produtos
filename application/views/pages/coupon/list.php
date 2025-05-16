<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h5 class="mb-0">Cupons</h5>
    <button class="btn btn-success" onclick="window.location.href='<?= site_url('coupon/create') ?>'">
        Cadastrar
    </button>
</div>

<?php if ($this->input->get('success') && $this->input->get('success') == 3): ?>
    <div class="alert alert-success alert-dismissible" role="alert" onclick="removeSuccessParam()">
        Cupom deletado com sucesso!
    </div>
<?php endif; ?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Desconto</th>
            <th>Valor Mínimo</th>
            <th>Data de Validade</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($coupons as $coupon): ?>
            <tr>
                <td><?php echo $coupon['id']; ?></td>
                <td><?php echo $coupon['code']; ?></td>
                <td><?php echo $coupon['type'] === 'percentage' ? $coupon['discount'] . '%' : number_format($coupon['discount'], 2, ',', '.'); ?></td>
                <td><?php echo number_format($coupon['min_value'], 2, ',', '.'); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($coupon['valid_until'])); ?></td>
                <td>
                    <?php
                    $isExpired = strtotime($coupon['valid_until']) < time();
                    $isActive = $coupon['is_active'];

                    if (!$isActive) {
                        echo '<span class="badge bg-danger">Inativo</span>';
                    } elseif ($isExpired) {
                        echo '<span class="badge bg-warning text-dark">Expirado</span>';
                    } else {
                        echo '<span class="badge bg-success">Válido</span>';
                    }
                    ?>
                </td>
                <td>
                    <a href="<?php echo site_url('coupon/edit/' . $coupon['id']); ?>">Editar</a>
                    <a href="<?php echo site_url('coupon/delete/' . $coupon['id']); ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($coupons)): ?>
            <tr>
                <td colspan="7">Nenhum cupom encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>