<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Entity\Keyword;
use App\Form\BookmarkFormType;
use App\Repository\BookmarkRepository;
use App\Service\PageLoader\FaviconHandler;
use App\Service\PageLoader\LoaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Faker\Factory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
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
     * @Route("/", name="app_bookmarks")
     *
     * @return Response
     */
    public function index(
        Request $request,
        BookmarkRepository $bookmarkRepository,
        PaginatorInterface $paginator
    ): Response {
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
     * @throws NonUniqueResultException
     */
    public function detail(BookmarkRepository $bookmarkRepository, int $id): Response
    {
        $bookmark = $bookmarkRepository->findByIdWithKeywords($id);

        if (!$bookmark) {
            throw $this->createNotFoundException("Not found bookmark with id {$id}");
        }

        return $this->render('bookmarks/detail.html.twig', [
            'bookmark' => $bookmark,
        ]);
    }

    /**
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param LoaderInterface        $loader
     *
     * @Route("/add", name="app_bookmarks_add")
     *
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager, LoaderInterface $loader): Response {
        $form = $this->createForm(BookmarkFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Bookmark $bookmark */
            $bookmark = $form->getData();

            $response = $loader->download($bookmark->getUrl());
            $favicon = $response->getFavicon();

            (new FaviconHandler(new Filesystem(), $favicon))->save();

            $bookmark
                ->setFavicon($response->getFavicon()->getName())
                ->setPageTitle($response->getTitle())
                ->setDescription($response->getDescription());

            foreach ($response->getKeywords() as $keyword) {
                $keywordEntity = new Keyword();
                $keywordEntity->setName($keyword);

                $entityManager->persist($keywordEntity);

                $bookmark->addKeyword($keywordEntity);
            }

            $entityManager->persist($bookmark);
            $entityManager->flush();

            $this->addFlash('flash_message', 'Закладка успешно создана!');

            return $this->redirectToRoute('app_bookmarks_detail', ['id' => $bookmark->getId()]);
        }

        return $this->render('bookmarks/add.html.twig', [
            'bookmarkForm' => $form->createView()
        ]);
    }
}
