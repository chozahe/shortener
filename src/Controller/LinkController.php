<?php

namespace App\Controller;

use App\Dto\LinkDto;
use App\Entity\Link;
use App\Form\LinkForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class LinkController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function home(Request $request, LinkService $service): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkForm::class, $link);
        $form->handleRequest($request);

        if($request->getMethod() === 'POST') {
            if ($form->isSubmitted() && $form->isValid()) {
                $service->create($link);
                return $this->render('home.html.twig', [
                    'form' => $form->createView(),
                    'short_url' => $request->getSchemeAndHttpHost() .  '/short/' . $link->getShortId()
                ]);
            }
            return $this->render('home.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->render('home.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /*public function home(Request $request, LinkService $service): Response
    {
        $allLinks = $service->getAll();

        if ($request->isMethod('POST')) {
            $originalUrl = trim($request->request->get('url'));

            if (!filter_var($originalUrl, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/.*)?$/i', $originalUrl)) {
                return $this->render('home.html.twig', [
                    'error' => 'Неверный URL'
                ]);
            }

            $link = $service->findOrCreate($originalUrl);
            $dto = new LinkDto($link, $request->getSchemeAndHttpHost());

            return $this->render('home.html.twig', [
                'short_url' => $dto->shortUrl
            ]);
        }

        return $this->render('home.html.twig');
    }*/

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(Request $request, LinkService $service): Response
    {
        $allLinks = $service->getAll();
        $allUrls = [];
        foreach ($allLinks as $link) {
            $allUrls[] = new LinkDto($link, $request->getSchemeAndHttpHost());
        }
        return $this->render('list.html.twig', ['all_urls' => $allUrls]);
    }

    #[Route('/short/{id}', name: 'redirect')]
    public function redirectToOriginal(string $id, LinkService $service): Response
    {
        $link = $service->getByShortId($id);
        $service->increaseVisits($link);

        return new RedirectResponse($link->getOriginalUrl());
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, LinkService $service): Response
    {
        $link = $service->getById($id);

        if (!$link) {
            return new Response('Ссылка не найдена', 404);
        }

        $service->delete($link);
        return new Response('Ссылка была успешно удалена!', 200);
    }
}
