<?php

namespace PhpWinTools\WmiScripting\Query;

use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Models\Win32Model;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectSet;

class Builder
{
    protected $model;

    protected $from;

    protected $services;

    protected $selects = ['*'];

    protected $wheres = [];

    protected $relationships = [];

    public function __construct(Win32Model $model, Connection $connection)
    {
        $this->model = $model;
        $this->from = $model->getWmiClassNameAttribute();
        $this->services = $connection->connect();
    }

    public function all()
    {
        return $this->execQuery("select * from {$this->from}")->getSet();
    }

    /**
     * @return ModelCollection|Win32Model[]
     */
    public function get()
    {
        return $this->getModelCollection();
    }

    /**
     * @return ModelCollection|Win32Model[]
     */
    public function getModelCollection()
    {
        return $this->getObjectSet()->getSet();
    }

    /**
     * @return ObjectSet
     */
    public function getObjectSet(): ObjectSet
    {
        return $this->execQuery($this->queryString());
    }

    /**
     * @param string $query
     *
     * @return ObjectSet
     */
    public function execQuery($query): ObjectSet
    {
        return $this->services
            ->resolvePropertySets($this->relationships)
            ->execQuery($query)
            ->instantiateModels($this->model);
    }

    /**
     * @return string
     */
    public function queryString(): string
    {
        return $this->compileQuery();
    }

    public function select(array $columns = ['*'])
    {
        $this->selects = $columns;

        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->wheres[] = [
            'column'    => $column,
            'operator'  => $operator,
            'value'     => $value,
        ];

        return $this;
    }

    public function with($relationship)
    {
        if (is_array($relationship)) {
            $this->relationships = array_merge($this->relationships, $relationship);

            return $this;
        }

        $this->relationships[] = $relationship;

        return $this;
    }

    protected function compileQuery()
    {
        $select = implode(', ', $this->selects);
        $query = "select {$select} from {$this->from}";
        $wheres = [];

        foreach ($this->wheres as $where) {
            $wheres[] = "{$where['column']} {$where['operator']} "
                . (is_string($where['value']) ? "'{$where['value']}'" : $where['value']);
        }

        return empty($wheres) ? $query : $query . ' where ' . implode(' and ', $wheres);
    }
}
