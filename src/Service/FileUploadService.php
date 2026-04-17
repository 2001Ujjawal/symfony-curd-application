<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    private string $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $folder = ''): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);

        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $targetPath = $this->targetDirectory;

        if ($folder) {
            $targetPath .= '/' . $folder;

            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0777, true);
            }
        }
        try {
            $file->move($targetPath, $newFilename);
        } catch (FileException $e) {
            throw new $e;
        }
        return $newFilename;
    }
    
}
