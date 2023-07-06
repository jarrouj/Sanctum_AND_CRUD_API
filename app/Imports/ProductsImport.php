<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{

    public function model(array $row)
{
    $name = $row[0];
    $price = $row[1];
    $description = $row[2];
    $category_id = $row[3];

    var_dump($row);
    var_dump($name);
    var_dump($price);
    var_dump($description);
    var_dump($category_id);

    return new Product([
        'name' => $name,
        'price' => $price,
        'description' => $description,
        'category_id' => $category_id,
    ]);
}

}
