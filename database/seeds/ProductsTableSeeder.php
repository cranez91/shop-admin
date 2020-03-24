<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'Notebook',
            'slug' => 'notebook',
            'description' => 'The canvas fabric cloth is handcrafted with beautiful embroidery to make it really special.',
            'image' => 'notebook.jpg',
            'price' => '23.5',
        ]);

        DB::table('products')->insert([
            'name' => 'Tape Dispenser',
            'slug' => 'tape-dispenser',
            'description' => 'Add style and elegance to your home or office workspace with this new facet design.',
            'image' => 'tape_dispenser.jpg',
            'price' => '25',
        ]);

        DB::table('products')->insert([
            'name' => 'Standard Calculator',
            'slug' => 'standard-calculator',
            'description' => 'This standard functional desktop calculator with large 12-digit display takes up little space on your desktop and fits easily into your briefcase.',
            'image' => 'standard_calculator.jpg',
            'price' => '19.5',
        ]);
    }
}
