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
     * @param Request            $request
     * @param BookmarkRepository $bookmarkRepository
     * @param PaginatorInterface $paginator
     *
     * @Route("/bookmarks", name="app_bookmarks")
     *
     * @return Response
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

    /**
     * @param BookmarkRepository $bookmarkRepository
     * @param int                $id
     *
     * @Route("/bookmarks/{id}", name="app_bookmarks_detail")
     *
     * @return Response
     */
    public function detail(BookmarkRepository $bookmarkRepository, int $id): Response
    {
        $bookmark = $bookmarkRepository->find($id);

        if (! $bookmark) {
            throw $this->createNotFoundException("Not found bookmark with id {$id}");
        }

        return $this->render('bookmarks/detail.html.twig', [
            'bookmark' => $bookmark,
        ]);
    }
}
