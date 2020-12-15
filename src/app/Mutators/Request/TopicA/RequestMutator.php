<?php

namespace App\Mutators\Request\TopicA;

use App\Models\Request;
use App\Mutators\Request\RequestMutatorAbstract;

class RequestMutator extends RequestMutatorAbstract
{
    /**
     * @inheritDoc
     */
    public function append(Request $request): Request
    {
        $names = explode(",", $this->configs->get("LIST_NAMES"));
        $rand = array_rand($names);
        $request->setMessage(
            "{$request->getMessage()} {$names[$rand]}"
        );

        $request->setStatus("named");

        return $request;
    }
}