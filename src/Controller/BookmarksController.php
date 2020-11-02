<?php

namespace App\Controller;

use App\Repository\BookmarkRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarksController extends AbstractController
{
    /**
     * @Route("/bookmarks", name="app_bookmarks")
     */
    public function index(Request $request, BookmarkRepository $bookmarkRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $bookmarkRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('bookmarks/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
