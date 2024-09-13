<?php

namespace App\QueryBuilders;
use Illuminate\Database\Eloquent\Builder;

class HorseBuilder extends Builder
{
    public function filters(): self
    {
        return $this
            ->when(
                request('search'),
                fn($query) => $query
                    ->Where('name', 'LIKE', '%' . request('search') . '%')
            )
            ->orderBy('created_at', 'asc');
    }
}
