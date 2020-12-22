<?php

namespace Lyncas\Repositories;

use DI\Container;
use Lyncas\Exceptions\ValidationException;
use Lyncas\Models\Book;
use Lyncas\Services\Paginate;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookRepository extends Repository
{
    use Paginate;

    public function search(Request $request)
    {
        $params = $this->getSearchParams($request->getQueryParams());
        $result = $this->googleBooksApi->request($params);
        $this->setBookCollection($result);
        return $this->paginate();
    }

    private function getSearchParams(array $args)
    {
        if (!isset($args['search']) || empty($args['search'])) {
            throw new ValidationException('o campo search é obrigatório!');
        }

        $search = $args['search'];
        $this->getPageNumber($args);
        $this->getPerPageNumber($args);
        
        return [
            'q' => $search,
            'startIndex' => $this->per_page * ($this->current_page - 1),
            'maxResults' => $this->per_page
        ];
    }

    private function getPageNumber(array $args)
    {
        $this->current_page = isset($args['page']) ? intval($args['page']) : 1;
        if ($this->current_page < 1) {
            $this->current_page = 1;
        }
        return $this->current_page;
    }

    private function getPerPageNumber(array $args)
    {
        $this->per_page = isset($args['per_page']) ? intval($args['per_page']) : 10;
        if ($this->per_page > 40) {
            $this->per_page = 40;
        }
        if ($this->per_page < 10) {
            $this->per_page = 10;
        }
        return $this->per_page;
    }

    private function setBookCollection($result)
    {
        $this->setTotal($result->totalItems);

        $this->collection = [];
        foreach ($result->items as $item) {
            $book = new Book();
            $book->id = $item->id;
            $book->title = (isset($item->volumeInfo) && isset($item->volumeInfo->title)) ? $item->volumeInfo->title : null;
            $book->authors = (isset($item->volumeInfo) && isset($item->volumeInfo->authors)) ? implode(",", $item->volumeInfo->authors) : 'Sem Autor';
            $book->published_at = (isset($item->volumeInfo) && isset($item->volumeInfo->publishedDate)) ? intval($item->volumeInfo->publishedDate) : null;
            $book->description = (isset($item->volumeInfo) && isset($item->volumeInfo->description)) ? $item->volumeInfo->description : 'Sem Descrição';
            $book->image_url = (isset($item->volumeInfo) && isset($item->volumeInfo->imageLinks) && isset($item->volumeInfo->imageLinks->thumbnail)) ? $item->volumeInfo->imageLinks->thumbnail : null;

            $this->collection[] = $book;
        }

        return $this->collection;
    }

    private function setTotal($totalItems)
    {
        $this->total = $totalItems;
        $this->last_page = (($this->total%$this->per_page)==0) ? $this->total/$this->per_page : floor($this->total/$this->per_page) + 1;
        $this->next_page = ($this->current_page + 1) > $this->last_page ? null : $this->current_page + 1;
        $this->prev_page = ($this->current_page - 1 > 0) ? $this->current_page - 1 : null;
        return $this->total;
    }
}
