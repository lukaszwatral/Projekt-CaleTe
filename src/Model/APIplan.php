<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class APIplan
{
    public static function getAllData(): array {
        // Przykładowe dane
        return [
            ['id' => 1, 'name' => 'Item 1', 'value' => 'Value 1'],
            ['id' => 2, 'name' => 'Item 2', 'value' => 'Value 2'],
            ['id' => 3, 'name' => 'Item 3', 'value' => 'Value 3'],
        ];
    }
}