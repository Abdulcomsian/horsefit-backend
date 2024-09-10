<?php

namespace App\QueryBuilders;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function filters(): self
    {
        return $this
            ->when(
                request('search'),
                fn($query) => $query
                    ->Where('name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('email', 'LIKE', '%' . request('search') . '%')
            )->when(
                request('entries'),
                fn($query) => $query
                    ->limit(request('entries'))
            )->orderBy('updated_at', 'desc');
    }
}
