<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h5 class="mb-0">Produtos</h5>
    <button class="btn btn-success" onclick="window.location.href='<?= site_url('product/create') ?>'">
        Cadastrar
    </button>
</div>

<?php if ($this->input->get('success') && $this->input->get('success') == 3): ?>
    <div class="alert alert-success alert-dismissible" role="alert" onclick="removeSuccessParam()">
        Produto deletado com sucesso!
    </div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($products as $product): ?>
            <?php
            $highlight = ($product['id'] == $product['product_id'] ? 'background-color:#F2F2F2;' : '');
            ?>
            <tr>
                <td style="<?php echo $highlight; ?>"><?php echo $product['id']; ?></td>
                <td style="<?php echo $highlight; ?>"><?php echo $product['name']; ?></td>
                <td style="<?php echo $highlight; ?>"><?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                <td style="<?php echo $highlight; ?>"><?php echo $product['stock'] ?? 0; ?></td>
                <td style="<?php echo $highlight; ?>">
                    <a href="<?php echo site_url('product/create?id=' . $product['product_id']); ?>">Editar</a>
                    <a href="<?php echo site_url('product/delete/' . $product['id']); ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
            <tr>
                <td colspan="6">Nenhum cupom encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>