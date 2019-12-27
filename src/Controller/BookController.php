<?php

namespace App\Controller;

use App\Form\ViewBooksType;
use App\Form\ViewBookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Appointments;

class BookController extends AbstractController
{
    /**
     * @Route("/books", name="book_all")
     */

    public function all(Request $request, PaginatorInterface $paginator)
    {
        // Устанавливаем количество записей, которые будут выводиться на одной странице
       // $quantity = 5;
        // Ограничиваем количество ссылок, которые будут выводиться перед и
        // после текущей страницы
      //  $limit=3;
      //  $em = $this->getDoctrine()->getManager();

        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();


        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $books,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

      //  $entityManager = $this->getDoctrine()->getManager();
        if (!$books) {
            throw $this->createNotFoundException(
                'library is empty'
            );
        }

       // dump($books);
        //dd($books);
      //  $form = $this->createForm(ViewBooksType::class, $book);

        return $this->render('create_book/view_books.html.twig', [
            'appointments' => $appointments,
        ]);

       // return new Response('Saved new product with id '.$book->getId());
    }





    /**
     * @Route("/book/{id}", name="book_show" , methods={"GET","HEAD"})
     */


public function show($id)
{

    $book = $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);
    if (!$book) {
        throw $this->createNotFoundException(
            'No book found for id '.$id
        );
    }

   // $categoryNames[] = $book->getCategory();
    //$categoryNames->getName();
  //  dump($categoryNames);
    $form = $this->createForm(ViewBookType::class, $book);
    return $this->render('create_book/view_book.html.twig', [
        'form' => $form->createView(), 'book' => $book]);

  }

/**
 * @Route("/book/edit/{id}")
 */

public function edit($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $book_edit = $entityManager->getRepository(Book::class)->find($id);
 
    if (!$book_edit) {
        throw $this->createNotFoundException(
            'No book found for id '.$id
        );
    }
 
    $book_edit->setName('New book name!');
    $entityManager->flush();


    return $this->redirectToRoute('book_show', [
        'id' => $book_edit->getId()
    ]);
}

    /**
     * @Route("/book/{id}", methods={"POST"})
     */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $book = $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);
    $entityManager->remove($book);
     $entityManager->flush();

    return $this->redirectToRoute('book_all');
  //
    //dump($book);
   // dd($book);

}
}


