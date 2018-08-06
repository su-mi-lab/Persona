<?php

class Order
{
    /**
     * @var int
     */
    public $order_id;

    /**
     * @var Item
     */
    public $item;

    /**
     * @var User
     */
    public $user;

    /**
     * Order constructor.
     * @param User $user
     * @param Item $item
     * @param int $order_id
     */
    public function __construct(
        User $user,
        Item $item,
        int $order_id
    )
    {
        $this->user = $user;
        $this->item = $item;
        $this->order_id = $order_id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

}