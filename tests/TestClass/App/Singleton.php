<?php

class Singleton
{
    private $count = 0;

    /**
     * @return $this
     */
    public function countUp()
    {
        $this->count++;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

}