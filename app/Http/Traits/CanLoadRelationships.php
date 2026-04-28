<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanLoadRelationships
{
    public function loadRelationships(
        HasMany|Model|QueryBuilder|EloquentBuilder $for,
        array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {
        $relations = $relations ?? $this->relations ?? [];
        foreach ($relations as $relation) {
            if ($this->shouldIncludeRelation($relation)) {
                if ($for instanceof Model) {
                    $for->load($relation);
                } else {
                    $for->with($relation);
                }
            }
        }
        return $for;
    }
    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');
        if (!$include) {
            return false;
        }
        return in_array($relation, array_map('trim', explode(',', $include)));
    }
}