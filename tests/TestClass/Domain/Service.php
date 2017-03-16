<?php

class Service implements ServiceInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repo;

    public function __construct(RepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param $order_id
     * @return Order
     */
    public function get($order_id)
    {
        return $this->repo->get($order_id);
    }
}