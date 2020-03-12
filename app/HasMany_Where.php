<?php
namespace  App;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
class HasMany_Where extends Relation
{

    public function __construct(Builder $query, $attribute, $array)
    {
        $query->whereIn($attribute,$array);
        $this->query = $query;
        $this->related = $query->getModel();
       // parent::__construct($query, null);
    }

    public function addConstraints()
    {

    }

    public function addEagerConstraints(array $models)
    {
        // not implemented yet
    }

    public function initRelation(array $models, $relation)
    {
        // not implemented yet
    }

    public function match(array $models, Collection $results, $relation)
    {
        // not implemented yet
    }

    public function getResults()
    {
        return $this->get();
    }
}