<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= isset($title) ? $title : 'Minha Aplicação'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= site_url(); ?>">
                Home
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-dark hover-nav" href="<?= site_url('product'); ?>">
                            Produtos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-dark hover-nav" href="<?= site_url('coupon'); ?>">
                            Cupons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-dark hover-nav" href="<?= site_url('order/my_requests'); ?>">
                            Meus Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-dark hover-nav" href="<?= site_url('cart'); ?>">
                            Carrinho
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php $this->load->view('partials/alerts'); ?>
        <?php $this->load->view($view); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>