<?php

class Controller
{
    /**
     * @param $order_id
     * @return Order
     */
    public function indexAction(ServiceInterface $service, $order_id)
    {
        return $service->get($order_id);
    }

    /**
     * @param $id
     * @return int
     */
    public function test($id)
    {
        return $id;
    }
}