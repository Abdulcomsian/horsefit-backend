<?php

namespace App\QueryBuilders;
use Illuminate\Database\Eloquent\Builder;

class RoleBuilder extends Builder
{
    public function filters(): self
    {
        return $this
            ->with('permissions')
            ->orderBy('created_at', 'desc')
            ->when(
                request('search'),
                fn ($query) => $query
                    ->where('name', 'LIKE', '%'.request('search').'%')
                    ->orWhere('description', 'LIKE', '%'.request('search').'%')
            )->when(
                request('entries'),
                fn ($query) => $query
                    ->limit(request('entries'))
            );
    }
}
