<?php

class User
{
    public $name = 'User Name';

    /**
     * @var UserJob
     */
    public $job;

    public function __construct(UserJob $job)
    {
        $this->job = $job;
    }

    /**
     * @return UserJob
     */
    public function getJob()
    {
        return $this->job;
    }
}