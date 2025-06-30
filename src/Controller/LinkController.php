<?php

namespace App\Controller;

use App\Dto\LinkDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class LinkController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function home(Request $request, LinkService $service): Response
    {
        $allLinks = $service->getAll();
        $allUrls = [];
        foreach ($allLinks as $link) {
            $allUrls[] = new LinkDto($link, $request->getSchemeAndHttpHost());
        }

        if ($request->isMethod('POST')) {
            $originalUrl = trim($request->request->get('url'));

            if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
                return $this->render('home.html.twig', [
                    'error' => 'Неверный URL',
                    'all_urls' => $allUrls,
                ]);
            }

            ['link' => $link, 'isNew' => $isNew] = $service->findOrCreate($originalUrl);
            $dto = new LinkDto($link, $request->getSchemeAndHttpHost());

            if(!$isNew) {
                return $this->render('home.html.twig', [
                    'all_urls' => $allUrls,
                    'short_url' => $dto->shortUrl,
                    'message' => 'Эта ссылка уже была сокращена ранее',
                ]);
            }

            return $this->render('home.html.twig', [
               'all_urls' => [
                   ...$allUrls,
                   new LinkDto($link, $request->getSchemeAndHttpHost())
               ],
                'short_url' => $dto->shortUrl
            ]);
        }

        return $this->render('home.html.twig', ['all_urls' => $allUrls]);
    }

    #[Route('/short/{id}', name: 'redirect')]
    public function redirectToOriginal(string $id, LinkService $service): Response
    {
        $link = $service->getByShortId($id);

        if (!$link) {
            return new Response('Ссылка не найдена', 404);
        }

        $service->increaseVisits($link);
        return new RedirectResponse($link->getOriginalUrl());
    }
}
