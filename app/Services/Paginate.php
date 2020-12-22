<?php

namespace Lyncas\Services;

use Lyncas\Models\Model;

trait Paginate
{
    protected $collection;
    protected $current_page;
    protected $last_page;
    protected $per_page;
    protected $total;

    public function paginate()
    {
        return [
            'statusCode' => 200,
            'data' => $this->getCollectionInArray(),
            'current_page' => $this->current_page,
            'last_page' => $this->last_page,
            'next_page' => $this->next_page,
            'prev_page' => $this->prev_page,
            'per_page' => $this->per_page,
            'total' => $this->total,
        ];
    }

    private function getCollectionInArray()
    {
        $result = [];
        foreach ($this->collection as $model)
        {
            $result[] = $model->toArray();
        }
        return $result;
    }
}
