<?php

declare(strict_types=1);
function show($data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function renderHeader($data, $rank = 'customer'): void
{
    if ($rank == 'customer') {
        include __DIR__ . '/../views/' . THEME . '/header.php';
    } else {
        include __DIR__ . '/../views/' . THEME . '/admin/header.php';
    }
}

function renderFooter($data, $rank = 'customer'): void
{
    if ($rank == 'customer') {
        include __DIR__ . '/../views/' . THEME . '/footer.php';
    } else {
        include __DIR__ . '/../views/' . THEME . '/admin/footer.php';
    }
}

function renderSidebar($data, $rank = 'customer'): void
{
    if ($rank == 'customer') {
        include __DIR__ . '/../views/' . THEME . '/sidebar.php';
    } else {
        include __DIR__ . '/../views/' . THEME . '/admin/sidebar.php';
    }
}

function renderProduct($data): void
{
    include __DIR__ . '/../views/' . THEME . '/product.inc.php';
}

function setSessionMessage($type, $message)
{
    $_SESSION['msg'] = ['type' => $type, 'message' => $message];
}

function displaySessionMessage(): void
{
    if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
        echo '<div class="alert alert-' . $_SESSION['msg']['type'] . ' " role="alert">';
        echo $_SESSION['msg']['message'];
        echo '</div>';
        unset($_SESSION['msg']);
    }
}

function renderPagination(int $totalItems, int $itemsPerPage, int $currentPage, string $baseUrl): string
{
    $totalPages = (int)ceil($totalItems / $itemsPerPage);
    $paginationHtml = '<ul class="pagination">';

    // Previous button
    if ($currentPage > 1) {
        $paginationHtml .= '<li><a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">&laquo;</a></li>';
    } else {
        $paginationHtml .= '<li class="disabled"><span>&laquo;</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            $paginationHtml .= '<li class="active"><span>' . $i . '</span></li>';
        } else {
            $paginationHtml .= '<li><a href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($currentPage < $totalPages) {
        $paginationHtml .= '<li><a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">&raquo;</a></li>';
    } else {
        $paginationHtml .= '<li class="disabled"><span>&raquo;</span></li>';
    }

    $paginationHtml .= '</ul>';
    return $paginationHtml;
}
