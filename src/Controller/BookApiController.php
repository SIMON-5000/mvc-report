<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookApiController extends AbstractController
{
    #[Route('/api/library', name: 'api_all_books')]
    public function apiAllBooks(
        BookRepository $bookRepository
    ): Response {

        $books = $bookRepository
            ->findAll();

        return $this->json($books, 200, [], ['json_encode_options' => JSON_PRETTY_PRINT]);
    }

    #[Route('/api/book/{id}', name: 'api_book_by_id')]
    public function apiBookById(
        BookRepository $bookRepository,
        int $id
    ): Response {

        $book = $bookRepository
            ->find($id);


        return $this->json($book, 200, [], ['json_encode_options' => JSON_PRETTY_PRINT]);
    }
}
