<?php

namespace Tests\Lyncas\Repositories;

use DI\Container;
use Lyncas\Exceptions\ValidationException;
use Lyncas\Repositories\BookRepository;
use Lyncas\Services\GoogleBooksApi;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookRepositoryTest extends TestCase
{
    protected $bookRepository;

    protected function setUp():void
    {
        $googleBooksApiStub = $this->getGoogleBooksApiStub();
        $containerStub = $this->getContainerStub($googleBooksApiStub);
        $this->bookRepository = new BookRepository($containerStub);
    }

    private function getGoogleBooksApiStub()
    {
        $googleBooksApiStub = $this->createMock(GoogleBooksApi::class);
        $googleBooksApiStub->method('request')
            ->with($this->equalTo([
                'q' => 'Harry Potter',
                'startIndex' => 0,
                'maxResults' => 10
            ]))
            ->willReturn($this->getData());
        return $googleBooksApiStub;
    }

    private function getContainerStub(GoogleBooksApi $googleBooksApiStub)
    {
        $containerStub = $this->createMock(Container::class);
        $containerStub->method('get')
            ->with('googleBooksApi')
            ->willReturn($googleBooksApiStub);
        return $containerStub;
    }

    public function testIfgetSearchParamsThrows()
    {
        try {
            $params = [];
            $this->invokeMethod($this->bookRepository, 'getSearchParams', [$params]);
        } catch(ValidationException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals("o campo search é obrigatório!", $e->getMessage());
        }
    }

    public function testIfGetSearchParamsReturnsArray()
    {
        $params = ['search' => 'Harry Potter'];
        $result = $this->invokeMethod($this->bookRepository, 'getSearchParams', [$params]);
        $expected = [
            'q' => 'Harry Potter',
            'startIndex' => 0,
            'maxResults' => 10
        ];
        $this->assertEquals($expected, $result);
    }

    public function testGetPageNumberIfPageIsEmpty()
    {
        $params = [];
        $result = $this->invokeMethod($this->bookRepository, 'getPageNumber', [$params]);
        $this->assertEquals(1, $result);
    }

    public function testGetPageNumberIfPageIsNotEmpty()
    {
        $params = ['page' => 2];
        $result = $this->invokeMethod($this->bookRepository, 'getPageNumber', [$params]);
        $this->assertEquals(2, $result);
    }

    public function testGetPerPageNumberIfPerPageIsEmpty()
    {
        $params = [];
        $result = $this->invokeMethod($this->bookRepository, 'getPerPageNumber', [$params]);
        $this->assertEquals(10, $result);
    }

    public function testGetPerPageNumberIfPerPageIsNotEmpty()
    {
        $params = ['per_page' => 15];
        $result = $this->invokeMethod($this->bookRepository, 'getPerPageNumber', [$params]);
        $this->assertEquals(15, $result);
    }

    public function testGetPerPageNumberIfPerPageIsLowerThanTen()
    {
        $params = ['per_page' => 5];
        $result = $this->invokeMethod($this->bookRepository, 'getPerPageNumber', [$params]);
        $this->assertEquals(10, $result);
    }

    public function testSetBookCollectionReturnCollection()
    {
        $page = 1;
        $per_page = 10;
        $values = $this->getData();
        $this->invokeMethod($this->bookRepository, 'getPageNumber', [['page' => $page]]);
        $this->invokeMethod($this->bookRepository, 'getPerPageNumber', [['per_page' => $per_page]]);
        $result = $this->invokeMethod($this->bookRepository, 'setBookCollection', [$values]);
        $this->assertCount(1, $result);
        $total = $this->invokeMethod($this->bookRepository, 'setTotal', [$values->totalItems]);
        $this->assertEquals(20, $total);
    }

    public function testSearch()
    { 
        $data = $this->getData();
        $requestStub = $this->createMock(Request::class);
        $requestStub->method('getQueryParams')->willReturn([
            'search' => 'Harry Potter'
        ]);
        $result = $this->invokeMethod($this->bookRepository, 'search', [$requestStub]);
        $this->assertEquals($data->totalItems, $result['total']);
        $this->assertCount(count($data->items), $result['data']);
    }

    private function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function getData()
    {
        return json_decode(json_encode([
            'totalItems' => 20,
            'items' => [
                0 => [
                    'id' => 1,
                    'volumeInfo' => [
                        'title' => "Harry Potter",
                        'authors' => ['J.K. Rowling'],
                        'publishedDate' => "2005",
                        'description' => 'Você sabia que a autora de Harry Potter (J.K. Rowling)...',
                        'imageLinks' => [
                            'thumbnail' => 'http://books.google.com/books/content?id=z_JRBQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'
                        ]
                    ]
                ]
            ]
        ]));
    }
}
