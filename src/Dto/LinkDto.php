<?php

namespace App\Dto;

use App\Entity\Link;

class LinkDto
{
    public string $originalUrl;
    public string $shortUrl;
    public int $visits;
    public ?\DateTimeImmutable $created_at;

    public function __construct(Link $link, string $baseUrl)
    {
        $this->originalUrl = $link->getOriginalUrl();
        $this ->shortUrl = $baseUrl .  '/short/' . $link->getShortId();
        $this->visits = $link->getVisits();
        $this->created_at = $link->getCreatedAt();
    }

}
