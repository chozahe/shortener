<?php

namespace App\Service;

use App\Entity\Link;
use App\Repository\LinkRepository;


class LinkService
{
    public function __construct(private LinkRepository $repository) {}

    public function findOrCreate(string $originalUrl): array
    {
        $existing = $this->repository->findOneBy(['originalUrl' => $originalUrl]);

        if ($existing) {
            return ['link' => $existing, 'isNew' => false];
        }

        $link = new Link();
        $link->setOriginalUrl($originalUrl);
        $link->setShortId($this->generateUniqueId());
        $link->setVisits(0);
        $link->setCreatedAt(new \DateTimeImmutable());

        $this->repository->save($link);

        return ['link' => $link, 'isNew' => true];
    }

    public function getByShortId(string $id): ?Link
    {
        return $this->repository->findOneBy(['shortId' => $id]);
    }

    /**
     * @return array<Link>
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function increaseVisits(Link $link): void
    {
        $link->setVisits($link->getVisits() + 1);
        $this->repository->save($link); // flush внутри
    }

    private function generateUniqueId(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $id = substr(str_shuffle($characters), 0, 5);
        } while ($this->repository->findOneBy(['shortId' => $id]));

        return $id;
    }
}
