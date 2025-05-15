<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Utils;
use App\Utils\ControllerHelpers;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/library', name: 'library')]
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
        $title = strval($request->request->get('title'));
        $author = strval($request->request->get('author'));
        $isbn = strval($request->request->get('isbn'));
        // $img = strval($request->request->get('img'));

        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImg('book.png');

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        // Knows which Entity by typoe of object?
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

    #[Route('/library/book/{id}', name: 'book_by_id')]
    public function showBookById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id: '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/book.html.twig', $data);
    }

    #[Route('/library/book/edit/{id}', name: 'edit_book')]
    public function editBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id: '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/edit-book.html.twig', $data);
    }

    /**
     * Updating a book
     * Here using EntityManagerInterface
     */
    #[Route('/library/book/update/{id}', name: 'update_book', methods: ["POST"])]
    public function updateBook(
        BookRepository $bookRepository,
        ControllerHelpers $helpers,
        EntityManagerInterface $entityManager,
        Request $request,
        int $id
    ): Response {
        $params = ['title', 'author', 'isbn', 'img'];
        $formData = $helpers->getParamBag($request, $params);

        /** @var Book */
        $book = $bookRepository
            ->find($id);

        $book->setTitle($formData['title']);
        $book->setAuthor($formData['author']);
        $book->setIsbn($formData['isbn']);
        $book->setImg($formData['img']);

        $entityManager->flush();

        return $this->redirectToRoute('show_books');
    }

    #[Route('/library/book/remove/{id}', name: 'remove_book')]
    public function removeBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id: '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/remove-book.html.twig', $data);
    }

    #[Route('/product/delete/{id}', name: 'delete_book')]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        // Try this method as well
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id: '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('show_books');
    }



    // ----- API ROUTES -----

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
