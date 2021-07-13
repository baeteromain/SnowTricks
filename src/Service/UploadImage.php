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
    public function saveImage(Image $image): Image
    {
        // Récupère le fichier de l'image uploadée
        $file = $image->getFile();
        // Créer un nom unique pour le fichier
        $fichier = md5(uniqid()) . '.' . $file->guessExtension();
        // Déplace le fichier
        $file->move($this->params->get('trick_image_directory'), $fichier);
        // Donner le nom au fichier dans la base de données
        $image->setName($fichier);

        return $image;
    }

}