<?php

namespace App\Dto;

use App\Entity\Link;

class LinkDto
{
    private readonly int $id;
    private readonly string $originalUrl;
    private readonly string $shortUrl;
    private readonly int $visits;

    private readonly ?\DateTimeImmutable $createdAt;
    private readonly ?\DateTimeImmutable $lastVisit;
    private readonly ?\DateTimeImmutable $expiresAt;
    private readonly bool $isDisposable;

    public function __construct(Link $link, string $baseShortUrl)
    {
        $this->id = $link->getId();
        $this->originalUrl = $link->getOriginalUrl();
        $this->shortUrl = $baseShortUrl . '/short/' . $link->getShortId();
        $this->visits = $link->getVisits();
        $this->createdAt = $link->getCreatedAt();
        $this->lastVisit = $link->getLastVisitedAt();
        $this->expiresAt = $link->getExpiresAt();
        $this->isDisposable = $link->isDisposable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function getVisits(): int
    {
        return $this->visits;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLastVisit(): ?\DateTimeImmutable
    {
        return $this->lastVisit;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isDisposable(): bool
    {
        return $this->isDisposable;
    }

    public function isActive(): bool
    {
        if ($this->isDisposable && $this->visits >= 1) {
            return false;
        }

        if ($this->expiresAt !== null && $this->expiresAt < new \DateTimeImmutable()) {
            return false;
        }

        return true;
    }
}
