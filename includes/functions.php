<?php


function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
