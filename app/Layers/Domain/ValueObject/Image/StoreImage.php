<?php

namespace App\Layers\Domain\ValueObject\Image;

use Illuminate\Http\UploadedFile;

class StoreImage implements ImageInterface
{
    private const STORAGE_NAME = 'store';

    /**
     * @param UploadedFile|null $file
     * @param string|null $url
     */
    private function __construct(
        private readonly ?UploadedFile $file,
        private readonly ?string $url,
    ) {
    }

    /**
     * @param UploadedFile|null $file
     * @param string|null $url
     * @return self
     */
    public static function make(?UploadedFile $file = null, ?string $url = null): self
    {
        return new self($file, $url);
    }

    /**
     * @return UploadedFile|null
     */
    public function getRequestFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @return string|null
     */
    public function getCurrentUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return is_null($this->file) && is_null($this->url);
    }

    /**
     * @return bool
     */
    public function hasFile(): bool
    {
        return !is_null($this->file);
    }

    /**
     * @return bool
     */
    public function hasCurrentUrl(): bool
    {
        return !is_null($this->url);
    }

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return self::STORAGE_NAME;
    }

    /**
     * @return string
     */
    public function getBaseName(): string
    {
        return pathinfo($this->getCurrentUrl(), PATHINFO_BASENAME);
    }

    /**
     * @return string
     */
    public function getStoragePath(): string
    {
        return $this->getStorageName() . '/' . $this->getBaseName();
    }
}
