<?php
namespace App\Controller;

use App\Model\APIplan;

class APIplanController
{
    public function getData(): void {
        // Pobierz dane z modelu
        $data = APIplan::getAllData();

        // Ustaw nagłówek odpowiedzi na JSON
        header('Content-Type: application/json');

        // Zwróć dane w formacie JSON
        echo json_encode($data);
    }
}