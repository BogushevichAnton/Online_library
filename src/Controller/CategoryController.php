<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {

        /*$entityManager = $this->getDoctrine()->getManager();

        $category = new Category();
        $category->setName('Cyberpunk');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
*/
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
