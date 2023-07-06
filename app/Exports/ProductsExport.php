<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;




class ProductsExport implements FromQuery, WithHeadings, WithMapping
{

        public function query()
        {
            return Product::query()
            ->select('id', 'name', 'price', 'description', 'category_id')
            ->with('category');
        }

        public function headings(): array
        {
            return [
                'ID',
                'Name',
                'Price',
                'Description',
                'Category',
            ];
        }
        public function map($product): array
        {
            return [
                $product->id,
                $product->name,
                $product->price,
                $product->description,
                $product->category->name,
            ];
        }
}
