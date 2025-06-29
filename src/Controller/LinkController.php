<?php

namespace App\Controller;

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
        if ($request->isMethod('POST')) {
            $originalUrl = trim($request->request->get('url'));

            if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
                return $this->render('home.html.twig', ['error' => 'Неверный URL']);
            }

            $link = $service->findOrCreate($originalUrl);
            $shortUrl = $request->getSchemeAndHttpHost() . '/short/' . $link->getShortId();

            return $this->render('home.html.twig', ['short_url' => $shortUrl]);
        }

        return $this->render('home.html.twig');
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
