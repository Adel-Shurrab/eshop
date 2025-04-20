<?php
renderHeader($data);
?>

<section id="order_complete">
    <div class="container">
        <div class="alert alert-success" style="margin-top: 20px;">
            <h2><?= htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
        <div class="text-center" style="margin: 30px 0;">
            <a href="<?= BASE_URL ?>" class="btn btn-primary">
                <i class="fa fa-home"></i>
                Return to Home
            </a>
        </div>
    </div>
</section>

<?php
renderFooter($data);
?>