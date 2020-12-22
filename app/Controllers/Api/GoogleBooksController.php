<?php

namespace Lyncas\Controllers\Api;

use DI\Container;
use Exception;
use Lyncas\Controllers\Controller;
use Lyncas\Repositories\BookRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

class GoogleBooksController extends Controller
{
    protected $bookRepository;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->bookRepository = new BookRepository($container);
    }

    public function index(Request $request)
    {
        try {
            $result = $this->bookRepository->search($request);
            return $this->withJson($result);
        } catch (Exception $e) {
            return $this->withJson([
                'statusCode' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
