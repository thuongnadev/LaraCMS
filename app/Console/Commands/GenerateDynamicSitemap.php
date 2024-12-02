<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DynamicSitemapGenerator;

class GenerateDynamicSitemap extends Command
{
    protected $signature = 'sitemap:generate-dynamic';
    protected $description = 'Generate a dynamic sitemap';

    public function handle(DynamicSitemapGenerator $generator)
    {
        $sitemap = $generator->generate();
        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Dynamic sitemap generated successfully.');
    }
}