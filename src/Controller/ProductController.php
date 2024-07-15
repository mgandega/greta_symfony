<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    public $productName = 'food';

    #[Route('/product', name: 'app_product')]
    public function calculTva($prix, $product)
    {
        if ($product == $this->productName) {
            return $prix * 0.5;
        }else{
            throw new Exception('le produit doit être'.$this->productName);
        }
    }
}
