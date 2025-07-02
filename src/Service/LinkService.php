<?php

namespace App\Service;

use App\Dto\LinkDto;
use App\Entity\Link;
use App\Repository\LinkRepository;


class LinkService
{
    public function __construct(private LinkRepository $repository) {}

    public function findOrCreate(string $originalUrl): Link
    {
        $link = new Link();
        $link->setOriginalUrl($originalUrl);
        $link->setShortId($this->generateUniqueId());
        $link->setVisits(0);
        $link->setCreatedAt(new \DateTimeImmutable());
        $link->setIsDeleted(false);

        $this->repository->save($link);

        return $link;
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
       $all = $this->repository->findAll();
       $result = [];
       foreach ($all as $link) {
           if (!$link->isDeleted()) {
               $result[] = $link;
           }
       }
       return $result;
    }

    public function increaseVisits(Link $link): void
    {
        $link->setVisits($link->getVisits() + 1);
        $link->setLastVisitedAt(new \DateTimeImmutable());
        $this->repository->save($link);
    }

    public function delete(Link $link): void
    {
        $link->setIsDeleted(true);
        $this->repository->save($link);
    }

    private function generateUniqueId(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $id = substr(str_shuffle($characters), 0, 5);
        } while ($this->repository->findOneBy(['shortId' => $id]));

        return $id;
    }

    public function getById(int $id): ?Link
    {
        return $this->repository->findOneBy(['id' => $id]);
    }
}
