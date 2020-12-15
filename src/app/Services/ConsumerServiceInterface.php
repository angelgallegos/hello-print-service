<?php

namespace App\Services;


interface ConsumerServiceInterface
{
    /**
     * Every consumer service should have a
     * consumer method
     *
     * @return mixed
     */
    public function consume();
}