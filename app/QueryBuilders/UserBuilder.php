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
            )
            ->orderBy('created_at', 'asc');
    }

    public function trainerAndOwnerList(): self
    {
        return $this->whereHas('roles', function($query) {
            $query->where('name', 'Owner')
                ->orWhere('name', 'Trainer');
            })->orderBy('created_at', 'asc');
    }
}
