<?php

interface ServiceInterface
{
    /**
     * @param $order_id
     * @return Order
     */
    public function get($order_id);
}