<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CreateBookType;
use App\Form\EditBookType;
use App\Form\ViewBooksType;
use App\Form\ViewBookType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
       // $books = $this->getDoctrine();
        $entityManager = $this->getDoctrine()->getManager();
        if( !empty($_POST["value"]))
        {
            $value = $_POST['value'];


            $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => $value]);
            $category_id = $category->getId();
            $books = $category->getBooks();
            /*$books = $this->getDoctrine()
                ->getRepository(Book::class)
                ->findBy(['category' => $category_id]);
*/

        }
else {
    $books = $this->getDoctrine()
        ->getRepository(Book::class)
        ->findAll();
}


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
 * @Route("/book/edit/{id}",name="edit_book", methods={"GET","HEAD", "POST"})
 */

public function edit($id, Request $request)
{
    $entityManager = $this->getDoctrine()->getManager();
    $book_edit = $entityManager->getRepository(Book::class)->find($id);
    if (!$book_edit) {
        throw $this->createNotFoundException(
            'No book found for id '.$id
        );
    }

    $form = $this->createForm(EditBookType::class, $book_edit);
    return $this->render('create_book/edit_book.html.twig', [
        'form' => $form->createView(), 'book_edit' => $book_edit, 'label' => ''
    ]);

    /*
       if (!$book_edit) {
           throw $this->createNotFoundException(
               'No book found for id '.$id
           );
       }

       $book_edit->setName('New book name!');
       $entityManager->flush();


       return $this->redirectToRoute('book_show', [
           'id' => $book_edit->getId()
       ]);*/

   // return $this->redirectToRoute('book_all');
}

    /**
     * @Route("/book/edit/{id}", methods={"PUT"})
     */
    public function trash_edit($id, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $book_edit = $entityManager->getRepository(Book::class)->find($id);

            //return $this->redirectToRoute('book_all');
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditBookType::class, $book_edit);
        $params = $request->request->get('edit_book');
        $param_name = $params['name'];
        $param_image = $params['image'];
        $category_id = $params['category'];

        $categories_book = $book_edit->getCategory();

        if ($param_name == '' or $param_image == '') {
            //   $book = new Book();
            //  $form = $this->createForm(CreateBookType::class, $book);
            //  return new Response()
            return $this->render('create_book/edit_book.html.twig', ['form' => $form->createView(), 'book_edit' => $book_edit, 'label' => 'error']);

            /*  return new Response(
                  'Error in Name'
              );*/
            // dd($params);

        }

       else {
           $book_edit->setName($param_name);
           $book_edit->setImage($param_image);



            if(isset($_POST['check']) &&
                      $_POST['check'] == '1')
                  {
                      foreach($categories_book as $category)
                      {
                          $book_edit->removeCategory($category);
                      }
                      foreach($category_id as $category_id_for_add)
                      {
                          $category_name = $entityManager->getRepository(Category::class)->find($category_id_for_add);
                          $book_edit->addCategory($category_name);
                          $entityManager->persist($category_name);
                      }
                      $entityManager->persist($book_edit);
                      $entityManager->flush();
                  }
            else
            {
                foreach($category_id as $category_id_for_add)
                {
                    $category_name = $entityManager->getRepository(Category::class)->find($category_id_for_add);
                    $book_edit->addCategory($category_name);
                    $entityManager->persist($category_name);
                }
                $entityManager->persist($book_edit);
                $entityManager->flush();
            }


       }


        return $this->render('create_book/edit_book.html.twig', [
            'form' => $form->createView(), 'book_edit' => $book_edit, 'label' => ''
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


