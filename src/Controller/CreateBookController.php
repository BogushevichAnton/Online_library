<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\CreateBookType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CreateBookController extends AbstractController
{
    /**
     * @Route("/create/book", name="create_book", methods={"GET","HEAD"})
     */
    public function create(Request $request)
    {
        $book = new Book();
  //      $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CreateBookType::class, $book);


        //$category = $entityManager->getRepository(Category::class)->findAll();
      return $this->render('create_book/index.html.twig', [
            'form' => $form->createView(),'label' => ''
        ]);
    }

    /**
     * @Route("/create/book", methods={"POST"})
     */
    public function trash(Request $request)
    {
        $book = new Book();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateBookType::class, $book);

        if(isset($_POST['button1'])) {


            //  $form->handleRequest($request);
            $params = $request->request->get('create_book');


            $param_name = $params['name'];
            $param_image = $params['image'];

            if ($param_name == '') {
             //   $book = new Book();
              //  $form = $this->createForm(CreateBookType::class, $book);
                //  return new Response()
                return $this->render('create_book/index.html.twig', ['form' => $form->createView(), 'label' => 'error']);

                /*  return new Response(
                      'Error in Name'
                  );*/
                // dd($params);

            }


            $param_category_id = $params['category'];


            $category = $entityManager->getRepository(Category::class)->find($param_category_id);
            //   $category->setName($category);

            $book = new Book();
            $book->setName($param_name);
            $book->setImage($param_image);
            $book->addCategory($category);


            /*   dump($book);

               dump($category);
               dd($category);
       */
            $entityManager->persist($category);
            $entityManager->persist($book);
            $entityManager->flush();


            // $category = $entityManager->getRepository(Category::class)->find();

            return $this->render('main.html.twig');
        }
        else {
          //  return $this->render('main.html.twig');
            $form = $this->createFormBuilder()
                ->add('category', EntityType::class, ['class' => Category::class])
                ->getForm();
            return $this->render('create_book/index.html.twig', ['form' => $form->createView(),  'label' => '']);
        }
    }
}
