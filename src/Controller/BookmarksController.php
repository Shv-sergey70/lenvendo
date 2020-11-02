<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     */
    public function detail(BookmarkRepository $bookmarkRepository, int $id): Response
    {
        $bookmark = $bookmarkRepository->find($id);

        if (!$bookmark) {
            throw $this->createNotFoundException("Not found bookmark with id {$id}");
        }

        return $this->render('bookmarks/detail.html.twig', [
            'bookmark' => $bookmark,
        ]);
    }

    /**
     * @param Request                $request
     * @param BookmarkRepository     $bookmarkRepository
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface  $urlGenerator
     *
     * @Route("/add", name="app_bookmarks_add")
     *
     * @return Response
     */
    public function add(
        Request $request,
        BookmarkRepository $bookmarkRepository,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $url = $request->query->get('url');

        if (! empty($url)) {
            if ($bookmarkRepository->findBy(['url' => $url])) {
                $error = 'Такая закладка уже существует';
            }
        }

        if (!$isBookmarkExist) {
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {

            }

            $faker = Factory::create();

            $entity = new Bookmark();
            $entity->setUrl($url)
                ->setFavicon($faker->url)
                ->setPageTitle($faker->text(25))
                ->setDescription($faker->text);

            $entityManager->persist($entity);
            $entityManager->flush();

            return new RedirectResponse($urlGenerator->generate('app_bookmarks'));
        }

        return $this->render('bookmarks/add.html.twig', [
            'alreadyExists' => $isBookmarkExist,
        ]);
    }
}
