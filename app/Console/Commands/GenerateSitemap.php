<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate dynamic sitemap';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Add Homepage
        $sitemap->add(Url::create('/'));

        // Add Categories (optional)
        /*
        foreach (Category::all() as $category) {
            $sitemap->add(
                Url::create("/category/{$category->slug}")
            );
        }
        */

        // Add Products dynamically (stock > 0)
        foreach (Product::where('stock', '>', 0)->get() as $product) {
            $sitemap->add(
                Url::create("/product/{$product->slug}")
                    ->setLastModificationDate($product->updated_at)
            );
        }

        // Save the sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Dynamic sitemap generated successfully.');
    }
}
