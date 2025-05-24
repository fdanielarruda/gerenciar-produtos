<?php echo form_open('auth/authenticate'); ?>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" required class="form-control" id="email" name="email" />
</div>

<div class="mb-3">
    <label for="password" class="form-label">Senha</label>
    <input type="password" required class="form-control" id="password" name="password" />
</div>

<?php if ($this->session->flashdata('error')): ?>
    <p style="color:red;"><?= $this->session->flashdata('error') ?></p>
<?php endif; ?>

<button type="submit" class="btn btn-primary">Salvar</button>

<?php echo form_close(); ?>