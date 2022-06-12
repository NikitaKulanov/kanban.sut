<?php

namespace App\Contracts;

interface BindingModelContract
{
    /**
     * Get sender id - model creator
     *
     * @return int
     */
    public function getSenderId(): int;
}
