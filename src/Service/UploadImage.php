<?php

namespace App\Service;

use App\Entity\Image;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UploadImage
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     *
     * @param Image $image
     * @return Image $image
     */
    public function saveImage($image)
    {
        // Créer un nom unique pour le fichier
        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
        // Déplace le fichier
        $image->move($this->params->get('trick_image_directory'), $fichier);

        return $fichier;
    }
    public function saveAvatar($file)
    {
        // Créer un nom unique pour le fichier
        $fichier = md5(uniqid()) . '.' . $file->guessExtension();
        // Déplace le fichier
        $file->move($this->params->get('avatar_directory'), $fichier);

        return $fichier;
    }

}