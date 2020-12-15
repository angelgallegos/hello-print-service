<?php

namespace App\Mutators\Request;

use App\Models\Request;

interface RequestMutatorInterface
{
    /**
     * All mutators for the Request type should
     * append a value to the message property
     *
     * @param Request $request
     * @return Request
     */
    public function append(Request $request): Request;
}