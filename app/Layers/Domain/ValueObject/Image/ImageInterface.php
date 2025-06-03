<?php

namespace App\Layers\Domain\ValueObject\Image;

use Illuminate\Http\UploadedFile;

interface ImageInterface
{
    public function getRequestFile(): ?UploadedFile;
    public function getCurrentUrl(): ?string;
    public function empty(): bool;
    public function hasFile(): bool;
    public static function make(?UploadedFile $file = null, ?string $url = null): self;
    public function getStorageName(): string;
    public function getBaseName(): string;
}
