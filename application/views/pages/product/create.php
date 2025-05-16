<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h5 class="mb-0">Gerenciar Produto</h5>
    <button class="btn btn-success" onclick="window.location.href='<?= site_url('order/create') ?>'">
        Comprar
    </button>
</div>

<?php if ($this->input->get('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert" onclick="removeSuccessParam()">
        Produto salvo com sucesso!
    </div>
<?php endif; ?>

<?php echo form_open('product/store'); ?>

<?php if (isset($product_saved['id'])): ?>
    <input type="hidden" name="product_id" value="<?= $product_saved['id'] ?>" />
<?php endif; ?>

<div class="mb-3">
    <label for="name" class="form-label">Produto</label>
    <input type="text" required class="form-control" id="name" name="name"
        value="<?= set_value('name', isset($product_saved['name']) ? $product_saved['name'] : '') ?>" />
</div>

<div class="mb-3">
    <label for="price" class="form-label">Preço</label>
    <input type="number" required class="form-control" id="price" max="99999999.99" step="0.01" name="price"
        value="<?= set_value('price', isset($product_saved['price']) ? $product_saved['price'] : '') ?>" />
</div>

<div class="mb-3">
    <label for="quantity" class="form-label">Estoque</label>
    <input type="number" required class="form-control" id="quantity" min="0" step="1" name="quantity"
        value="<?= set_value('quantity', isset($product_saved['quantity']) ? $product_saved['quantity'] : '') ?>" />
</div>


<h6>Variações</h6>
<div id="variations-container">
    <?php if (isset($product_saved['variations']) && is_array($product_saved['variations'])): ?>
        <?php foreach ($product_saved['variations'] as $i => $variation): ?>
            <div class="variation row mb-3">
                <input type="hidden" name="variations[<?= $i ?>][id]" value="<?= $variation['id'] ?>" />

                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" name="variations[<?= $i ?>][name]" value="<?= htmlspecialchars($variation['name']) ?>" required class="form-control" placeholder="Ex: Cor, Tamanho" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Preço</label>
                    <input type="number" name="variations[<?= $i ?>][price]" value="<?= htmlspecialchars($variation['price']) ?>" required class="form-control" max="99999999.99" step="0.01" placeholder="Preço" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estoque</label>
                    <input type="number" name="variations[<?= $i ?>][quantity]" value="<?= htmlspecialchars($variation['quantity']) ?>" required class="form-control" min="0" step="1" placeholder="Quantidade" />
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-variation col-12">Remover</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<button type="button" id="add-variation" class="btn btn-secondary mb-3">Adicionar Variação</button>

<br />

<button type="submit" class="btn btn-primary">Salvar</button>

<?php echo form_close(); ?>
</div>

<script>
    let variationIndex = <?= isset($product_saved['variations']) ? count($product_saved['variations']) : 0 ?>;

    document.getElementById('add-variation').addEventListener('click', function() {
        const container = document.getElementById('variations-container');

        const variationDiv = document.createElement('div');
        variationDiv.classList.add('variation', 'row', 'mb-3');

        variationDiv.innerHTML = `
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" name="variations[${variationIndex}][name]" required class="form-control" placeholder="Ex: Cor, Tamanho" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Preço</label>
                    <input type="number" name="variations[${variationIndex}][price]" required class="form-control" max="99999999.99" step="0.01" placeholder="Preço" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estoque</label>
                    <input type="number" name="variations[${variationIndex}][quantity]" required class="form-control" min="0" step="1" placeholder="Quantidade" />
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-variation col-12">Remover</button>
                </div>
            `;

        container.appendChild(variationDiv);

        const removeButtons = document.querySelectorAll('.btn-remove-variation');
        removeButtons.forEach(btn => btn.style.display = 'inline-block');

        variationIndex++;
    });

    document.getElementById('variations-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-variation')) {
            const variationDiv = e.target.closest('.variation');
            variationDiv.remove();
        }
    });
</script>