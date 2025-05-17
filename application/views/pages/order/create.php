<h5 class="mb-4">Carrinho</h5>

<!-- MENSAGENS PARA CUPOM -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert" onclick="removeSuccessParam()"><?= $this->session->flashdata('success') ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible" role="alert" onclick="removeSuccessParam()"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Preço unitário</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($cart)): ?>
            <tr>
                <td colspan="6" class="text-center">Nenhum produto no carrinho</td>
            </tr>
        <?php else: ?>
            <?php
            $total = 0;
            foreach ($cart as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['name'] ?></td>
                    <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    <td>
                        <a href="<?= site_url("order/increase_quantity/{$item['id']}") ?>" class="btn btn-sm btn-success">+1</a>
                        <a href="<?= site_url("order/decrease_quantity/{$item['id']}") ?>" class="btn btn-sm btn-warning">-1</a>
                        <a href="<?= site_url("order/remove_item/{$item['id']}") ?>" class="btn btn-sm btn-danger">Remover</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>

    <?php if (!empty($cart)): ?>
        <tfoot>
            <tr>
                <td colspan="4" class="fw-bold">Subtotal</td>
                <td colspan="2" class="fw-bold">R$ <?= number_format($total, 2, ',', '.') ?></td>
            </tr>

            <?php
            $discount = 0;
            $applied_coupon = $this->session->userdata('applied_coupon') ?? null;

            if ($applied_coupon) {
                $discount = $applied_coupon['discount'] ?? 0;
                $total_after_discount = max($total - $discount, 0);
            ?>
                <tr>
                    <td colspan="4" class="fw-bold">Desconto (<?= $applied_coupon['code'] ?>)
                        <a href="<?= site_url('order/remove_coupon') ?>" class="text-danger">Remover</a>
                    </td>
                    <td colspan="2" class="fw-bold text-success">- R$ <?= number_format($discount, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="fw-bold">Total com desconto</td>
                    <td colspan="2" class="fw-bold">R$ <?= number_format($total_after_discount, 2, ',', '.') ?></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="4" class="fw-bold">Total</td>
                    <td colspan="2" class="fw-bold">R$ <?= number_format($total, 2, ',', '.') ?></td>
                </tr>
            <?php } ?>

            <?php
            $frete = 0;
            $cep_validado = $this->session->userdata('cep');
            $info = $this->session->userdata('cep_info');

            if ($cep_validado) {
                $base_total = $applied_coupon ? $total_after_discount : $total;

                if ($base_total > 200) {
                    $frete = 0;
                } elseif ($base_total >= 52 && $base_total <= 166.59) {
                    $frete = 15;
                } else {
                    $frete = 20;
                }
            ?>
                <tr>
                    <td colspan="4" class="fw-bold">
                        <strong>Endereço:</strong> <?= "{$info['logradouro']}, {$info['bairro']} - {$info['localidade']}/{$info['uf']}" ?>
                        <br><strong>CEP:</strong> <?= $this->session->userdata('cep') ?>
                        <a href="<?= site_url('order/remove_cep') ?>" class="text-danger">Remover</a>
                    </td>
                    <td colspan="2" class="fw-bold">R$ <?= number_format($frete, 2, ',', '.') ?></td>
                </tr>
            <?php } ?>

            <?php if ($cep_validado): ?>
                <tr>
                    <td colspan="4" class="fw-bold text-primary">Total Final</td>
                    <td colspan="2" class="fw-bold text-primary">
                        R$ <?= number_format(($applied_coupon ? $total_after_discount : $total) + $frete, 2, ',', '.') ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tfoot>
    <?php endif; ?>
</table>

<br />

<?php echo form_open('order/add_to_cart'); ?>
<div class="mb-3">
    <h5 class="card-title mb-0">Adicionar produtos ao carrinho</h5>
    <small class="text-muted">Escolha o produto e a quantidade desejada</small>
</div>

<!-- ADICIONAR PRODUTOS -->
<div class="row g-3 align-items-end">
    <div class="col-md-6">
        <label for="product_id" class="form-label fw-semibold">Produto</label>
        <select class="form-select" id="product_id" name="product_id">
            <option value="">Selecione um produto</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= $product['id'] ?>">
                    <?= $product['name'] ?> - R$ <?= number_format($product['price'], 2, ',', '.') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label for="quantity" class="form-label fw-semibold">Quantidade</label>
        <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
    </div>

    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100 fw-bold">
            <i class="bi bi-cart-plus"></i> Adicionar
        </button>
    </div>
</div>
<?php echo form_close(); ?>

<!-- GERENCIAR CUPOM -->
<?php echo form_open('order/apply_coupon'); ?>
<div class="row g-3 mb-4 mt-2">
    <div class="col-md-9">
        <label for="coupon_code" class="form-label fw-semibold">Código do Cupom</label>
        <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Digite o cupom" required
            value="<?= $this->session->userdata('applied_coupon')['code'] ?? '' ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-secondary fw-bold w-100">
            <i class="bi bi-ticket"></i> Aplicar Cupom
        </button>
    </div>
</div>
<?php echo form_close(); ?>

<!-- GERENCIAR CEP -->
<?php echo form_open('order/apply_cep'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-9">
        <label for="cep" class="form-label fw-semibold">CEP para cálculo do frete</label>
        <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" required
            value="<?= $this->session->userdata('cep') ?? '' ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-info fw-bold w-100">
            <i class="bi bi-geo-alt"></i> Calcular Frete
        </button>
    </div>
</div>
<?php echo form_close(); ?>

<?php echo form_open('order/checkout'); ?>
<?php $old = $this->session->flashdata('old_input') ?? []; ?>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">Nome</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Seu nome completo" required
            value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : '' ?>">
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Seu email" required
            value="<?= isset($old['customer_email']) ? htmlspecialchars($old['customer_email']) : '' ?>">
    </div>
</div>

<div class="row g-3 mb-4 mt-3">
    <div class="col-12">
        <button type="submit" class="btn btn-success w-100 fw-bold">
            <i class="bi bi-check-circle"></i> Finalizar Compra
        </button>
    </div>
</div>
<?php echo form_close(); ?>