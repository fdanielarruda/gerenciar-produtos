<?php echo form_open('auth/register_token'); ?>

<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" required class="form-control" id="title" name="title" />
</div>

<?php if ($this->session->flashdata('error')): ?>
    <p style="color:red;"><?= $this->session->flashdata('error') ?></p>
<?php endif; ?>

<button type="submit" class="btn btn-primary">Salvar</button>

<?php echo form_close(); ?>