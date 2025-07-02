<?php

namespace App\Dto;

use App\Entity\Link;

class LinkDto
{
    public int $id;
    public string $originalUrl;
    public string $shortUrl;
    public int $visits;
    public ?\DateTimeImmutable $created_at;
    public ?\DateTimeImmutable $last_visit;
    public function __construct(Link $link, string $baseUrl)
    {
        $this->id = $link->getId();
        $this->originalUrl = $link->getOriginalUrl();
        $this ->shortUrl = $baseUrl .  '/short/' . $link->getShortId();
        $this->visits = $link->getVisits();
        $this->last_visit = $link->getLastVisitedAt();
        $this->created_at = $link->getCreatedAt();
    }


}
