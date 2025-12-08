<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger
    ) {
    }

    public function upload(UploadedFile $file, ?string $oldFilename = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $newFilename);
            
            // Supprimer l'ancienne image si elle existe
            if ($oldFilename && file_exists($this->getTargetDirectory() . '/' . $oldFilename)) {
                unlink($this->getTargetDirectory() . '/' . $oldFilename);
            }
        } catch (FileException $e) {
            throw new \RuntimeException('Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
        }

        return $newFilename;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}

