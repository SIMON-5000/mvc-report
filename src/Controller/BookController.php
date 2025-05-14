<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/library', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/library/add-book', name: 'add_book')]
    public function addBook(): Response
    {
        return $this->render('book/add-book.html.twig');
    }
    
    #[Route('/library/create', name: 'create_book', methods: ["POST"])]
    public function createBook(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $title = strval($request->request->get( 'title'));
        $author = strval($request->request->get('author'));
        $isbn = strval($request->request->get('isbn'));
        
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);


        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('show_books');
        // return new Response('Saved new product with id '.$book->getId());
    }

    #[Route('/library/books', name: 'show_books')]
    public function viewAllProduct(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();
    
        $data = [
            'books' => $books
        ];
    
        return $this->render('book/books.html.twig', $data);
    }
}
