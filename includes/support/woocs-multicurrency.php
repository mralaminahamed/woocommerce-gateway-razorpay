<?php
function currencyConvert($amountInPaise,$order){
    global $WOOCS;
    $orderCurrency = getOrderCurrency($order);
    $currencies    = $WOOCS->get_currencies();
    $orderRate     = $currencies[$orderCurrency]['rate'];
    return round($orderRate*$amountInPaise,0);
}

function getOrderCurrency($order)
{
    if (version_compare(WOOCOMMERCE_VERSION, '2.7.0', '>='))
     {
        return $order->get_currency();
     }

  return $order->get_order_currency();
}