<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    private $statusCode;
    private $statusTitle;
    public function __construct($resource, $statusCode='200', $statusTitle='sucess') {
        // $this->var = $var;
        parent::__construct($resource);
        $this->statusCode = $statusCode;
        $this->statusTitle = $statusTitle;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'status' => $this->statusCode,
            'title' => $this->statusTitle,
            'data' => $this->collection,
        ];
    }
}
