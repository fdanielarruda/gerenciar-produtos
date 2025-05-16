<h5 class="mb-4">Gerenciar cupom</h5>

<?php if ($this->input->get('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert" onclick="removeSuccessParam()">
        Cupom salvo com sucesso!
    </div>
<?php endif; ?>

<?php echo form_open(isset($coupon_saved['id']) ? 'coupon/update/' . $coupon_saved['id'] : 'coupon/store'); ?>

<?php if (isset($coupon_saved['id'])): ?>
    <input type="hidden" name="coupon_id" value="<?= $coupon_saved['id'] ?>" />
<?php endif; ?>

<div class="mb-3">
    <label for="code" class="form-label">Código</label>
    <input type="text" required class="form-control" id="code" name="code"
        value="<?= set_value('code', isset($coupon_saved['code']) ? $coupon_saved['code'] : '') ?>" />
</div>

<div class="mb-3">
    <label for="discount" class="form-label">Valor do Desconto</label>
    <input type="number" required class="form-control" id="discount" name="discount" step="0.01" min="0"
        value="<?= set_value('discount', isset($coupon_saved['discount']) ? $coupon_saved['discount'] : '') ?>" />
</div>

<div class="mb-3">
    <label for="type" class="form-label">Tipo de Desconto</label>
    <select required class="form-control" id="type" name="type">
        <option value="">Selecione</option>
        <option value="percentage" <?= set_value('type', isset($coupon_saved['type']) ? $coupon_saved['type'] : '') === 'percentage' ? 'selected' : '' ?>>Porcentagem</option>
        <option value="amount" <?= set_value('type', isset($coupon_saved['type']) ? $coupon_saved['type'] : '') === 'amount' ? 'selected' : '' ?>>Valor Fixo</option>
    </select>
</div>

<div class="mb-3">
    <label for="min_value" class="form-label">Valor Mínimo</label>
    <input type="number" class="form-control" id="min_value" name="min_value" step="0.01" min="0"
        value="<?= set_value('min_value', isset($coupon_saved['min_value']) ? $coupon_saved['min_value'] : '') ?>" />
</div>

<div class="row mb-3">
    <div class="col-6">
        <label for="valid_until" class="form-label">Válido Até o Dia</label>
        <input type="date" class="form-control" id="valid_until" name="valid_until"
            value="<?= date("Y-m-d", strtotime(set_value('valid_until', isset($coupon_saved['valid_until']) ? $coupon_saved['valid_until'] : date('Y-m-d', strtotime('+1day'))))) ?>" />
    </div>
    <div class="col-6">
        <label for="valid_until_time" class="form-label">Válido Até o Horário</label>
        <input type="time" class="form-control" id="valid_until_time" name="valid_until_time"
            value="<?= date("H:i", strtotime(set_value('valid_until_time', isset($coupon_saved['valid_until']) ? $coupon_saved['valid_until'] : ''))) ?>" />
    </div>
</div>

<div class="mb-3">
    <label for="is_active" class="form-label">Ativo</label>
    <select required class="form-control" id="is_active" name="is_active">
        <option value="1" <?= set_value('is_active', isset($coupon_saved['is_active']) ? $coupon_saved['is_active'] : '') == 1 ? 'selected' : '' ?>>Sim</option>
        <option value="0" <?= set_value('is_active', isset($coupon_saved['is_active']) ? $coupon_saved['is_active'] : '') == 0 ? 'selected' : '' ?>>Não</option>
    </select>
</div>

<br />

<button type="submit" class="btn btn-primary">Salvar</button>

<?php echo form_close(); ?>