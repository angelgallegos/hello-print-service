<?php

namespace App\Mutators\Request\TopicB;

use App\Models\Request;
use App\Mutators\Request\RequestMutatorAbstract;

class RequestMutator extends RequestMutatorAbstract
{
    /**
     * @inheritDoc
     */
    public function append(Request $request): Request
    {
        $request->setMessage(
            "{$request->getMessage()} Bye"
        );

        $request->setStatus("farewell");

        return $request;
    }
}