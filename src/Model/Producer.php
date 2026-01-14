<?php

declare(strict_types=1);

namespace SAC\App\Model;

class Producer
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $siteUrl,
        public ?string $logoFilename,
        public ?int $ordering,
        public ?string $sourceId,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'site_url' => $this->siteUrl,
            'logo_filename' => $this->logoFilename,
            'ordering' => $this->ordering,
            'source_id' => $this->sourceId,
        ];
    }

    public static function fromArray($array): self
    {
        return new self(
            $array['id'],
            $array['name'],
            $array['site_url'],
            $array['logo_filename'],
            (int) $array['ordering'],
            $array['source_id'],
        );
    } 
}   

