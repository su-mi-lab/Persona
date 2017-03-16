<?php

class Repository implements RepositoryInterface
{
    /**
     * @param $order_id
     * @return Order
     */
    public function get($order_id)
    {
        return new Order(new User(new UserJob()), new Item());
    }
}