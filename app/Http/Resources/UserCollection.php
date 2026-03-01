<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $total = User::count();

        $offset = $request->get('offset') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderBy') ?? 'created_at';

        return [
            'data' => $this->collection,
            'info' => [
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
                'order' => $orderBy,
            ],
        ];
    }
}
