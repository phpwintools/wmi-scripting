<?php

namespace PhpWinTools\WmiScripting\Query;

use PhpWinTools\WmiScripting\Models\Win32Model;
use PhpWinTools\WmiScripting\Connections\Connection;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectSet;

class Builder
{
    protected $model;

    protected $services;

    protected $connection;

    protected $from;

    protected $selects = ['*'];

    protected $wheres = [];

    protected $relationships = [];

    public function __construct(Win32Model $model, Connection $connection)
    {
        $this->model = $model;
        $this->connection = $connection;
        $this->from = $model->getWmiClassNameAttribute();
        $this->services = $connection->connect();
    }

    public function all()
    {
        return $this->hydrate($this->query("select * from {$this->from}"));
    }

    public function hydrate(array $items = [])
    {
        return new ModelCollection(array_map(function ($item) {
            return $this->model::newInstance($item);
        }, $items));
    }

    /**
     * @return ModelCollection|Win32Model[]
     */
    public function get()
    {
        return $this->hydrate($this->query($this->queryString()));
    }

    /**
     * @param $query
     *
     * @return mixed|ObjectSet
     */
    public function query($query)
    {
        return $this->connection->query($query, $this->model, $this->relationships);
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
