<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h5 class="mb-0">API Tokens</h5>
    <button class="btn btn-success" onclick="window.location.href='<?= site_url('auth/create_token') ?>'">
        Cadastrar
    </button>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Token</th>
            <th>Expira em</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($tokens as $token): ?>
            <tr>
                <td><?php echo $token['id']; ?></td>
                <td><?php echo $token['title']; ?></td>
                <td><?php echo $token['token']; ?></td>
                <td><?php echo $token['expires_at'] ?? ''; ?></td>
                <td>
                    <a href="<?php echo site_url('auth/token_delete/' . $token['id']); ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($tokens)): ?>
            <tr>
                <td colspan="3">Nenhum token encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>