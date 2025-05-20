<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Updates a book by id.
     * Flushed in controller
     * @param int $id
     * @param string $title
     * @param string $author
     * @param string $isbn
     * @param string $img
     * @return void
     */
    public function updateBook(
        int $id,
        string $title,
        string $author,
        string $isbn,
        string $img
        ): void
    {
        /** @var Book */
        $book = $this
            ->find($id);

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImg($img);
    }

    /**
     * Creates and persists a book.
     * Gets flushed in controller
     * @param string $title
     * @param string $author
     * @param string $isbn
     * @param string $img
     * @return void
     */
    public function createBook(string $title, string $author, string $isbn, string $img='book.png'): void 
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImg($img);

        $this->getEntityManager()->persist($book);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
