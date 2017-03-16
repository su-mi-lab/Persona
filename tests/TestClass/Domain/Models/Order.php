<?php

class Order
{
    /**
     * @var int
     */
    public $order_id = 1;

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
     */
    public function __construct(
        User $user,
        Item $item
    )
    {
        $this->user = $user;
        $this->item = $item;
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