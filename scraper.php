<?php
require 'vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

$client = new Client(HttpClient::create([
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, como Gecko) Chrome/58.0.3029.110 Safari/537.3',
    ],
]));

$url = 'https://www.amazon.es/Skechers-Stand-Zapatillas-Durabuck-Blanco/dp/B081ZR9V8X'; 

$crawler = $client->request('GET', $url);

$productNames = [];
$ratings = [];
$prices = [];

$crawler->filter('img.shopbylook-btf-image-elem')->each(function ($node) use (&$productNames) {
    $productName = $node->attr('alt');
    if (!empty($productName)) {
        $productNames[] = $productName;
    }
});

$crawler->filter('div.a-row.sbl-item-rating')->each(function ($node) use (&$ratings) {
    $rating = $node->filter('span')->first()->text(); 
    $ratings[] = trim($rating);
});

$crawler->filter('div.sbl-item-price')->each(function ($node) use (&$prices) {
    $price = $node->filter('span.a-price')->first()->filter('.a-offscreen')->text(); 
    $prices[] = trim($price); 
});

echo "Productos y sus valoraciones y precios:\n";
echo str_repeat('_' , 40) . PHP_EOL;
for ($i = 0; $i < count($productNames); $i++) {
    echo "Zapatilla " . ($i + 1) . ":\n";
    echo $productNames[$i] . PHP_EOL; 
    if (isset($ratings[$i])) {
        echo "Rating: " . $ratings[$i] . " estrellas" . PHP_EOL; 
    } else {
        echo "Rating: No disponible" . PHP_EOL; 
    }

    if (isset($prices[$i])) {
        echo "Precio: " . $prices[$i] . PHP_EOL; 
    } else {
        echo "Precio: No disponible" . PHP_EOL; 
    }

     echo str_repeat('_' , 40) . PHP_EOL; 
}

echo "Total de productos encontrados: " . count($productNames) . PHP_EOL;
?>
