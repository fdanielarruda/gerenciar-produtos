<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show pb-0" role="alert">
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
<?php endif; ?>

<script>
    function removeSuccessParam() {
        const url = new URL(window.location.href);
        url.searchParams.delete('success');
        window.location.href = url.toString();
    }
</script>