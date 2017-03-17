<?php

interface RepositoryInterface
{
    /**
     * @param $order_id
     * @return Order
     */
    public function get($order_id);
}