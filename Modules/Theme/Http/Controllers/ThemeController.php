<?php

namespace Modules\Theme\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\Page;
use Modules\Page\Entities\PageComponent;
use Modules\Theme\Traits\handleColorTrait;
use Modules\Post\Entities\Post;
use Modules\ProductVps\Entities\ProductVps;

class ThemeController extends Controller
{
    use handleColorTrait;

    public $primaryColor;

    public function index(Request $request)
    {
        $pageId = $request->route('page_id');

        $page = Page::findOrFail($pageId);

        $pageComponents = PageComponent::with('pageComponentConfigurationValues')
            ->where('page_id', $pageId)
            ->get();

        $configuration = $pageComponents->map(function ($component) use ($page) {
            $layout = [];
            $componentData = ['name' => $component->component->name ?? ""];
            $seoData = [
                'seo_title' => $page->seo_title ?? '',
                'seo_description' => $page->seo_description ?? '',
                'seo_keywords' => $page->seo_keywords ?? '',
            ];

            foreach ($component->pageComponentConfigurationValues as $config) {
                switch ($config->name) {
                    case 'heading':
                    case 'heading_color':
                    case 'heading_sub':
                    case 'heading_sub_color':
                    case 'heading_first_part':
                    case 'heading_second_part':
                    case 'first_part_color':
                    case 'second_part_color':
                    case 'background_color':
                    case 'background_image':
                    case 'layout_style':
                        $layout[$config->name] = $config->pivot->value ?? '';
                        break;
                    case 'seo_title':
                    case 'seo_description':
                    case 'seo_keywords':
                        $seoData[$config->name] = $config->pivot->value ?? '';
                        break;
                    default:
                        $componentData[$config->name] = $config->pivot->value ?? 4;
                        break;
                }
            }

            if (!empty($layout['background_image'])) {
                $bgImageData = json_decode($layout['background_image'], true);
                if (is_array($bgImageData) && !empty($bgImageData)) {
                    $layout['background_image'] = reset($bgImageData);
                }
            }

            return [
                'layout' => [
                    'heading' => !empty($layout['heading']) ? $layout['heading'] : false,
                    'heading_color' => !empty($layout['heading_color']) ? $layout['heading_color'] : false,
                    'heading_sub' => !empty($layout['heading_sub']) ? $layout['heading_sub'] : false,
                    'heading_sub_color' => !empty($layout['heading_sub_color']) ? $layout['heading_sub_color'] : false,
                    'heading_first_part' => !empty($layout['heading_first_part']) ? $layout['heading_first_part'] : false,
                    'heading_second_part' => !empty($layout['heading_second_part']) ? $layout['heading_second_part'] : false,
                    'first_part_color' => !empty($layout['first_part_color']) ? $layout['first_part_color'] : false,
                    'second_part_color' => !empty($layout['second_part_color']) ? $layout['second_part_color'] : false,
                    'background_color' => !empty($layout['background_color']) ? $layout['background_color'] : false,
                    'background_image' => !empty($layout['background_image']) ? $layout['background_image'] : false,
                    'style' => !empty($layout['layout_style']) ? $layout['layout_style'] : false,
                    'seo_title' => $seoData['seo_title'],
                    'seo_description' => $seoData['seo_description'],
                    'seo_keywords' => $seoData['seo_keywords'],
                ],
                'component' => $componentData
            ];
        });

        $this->primaryColor = $this->getFilamentPrimaryColor();

        return view('theme::pages.index', [
            'configuration' => $configuration,
            'primaryColor' => $this->primaryColor
        ]);
    }

    public function postDetail($slug)
    {
        $name = Post::where('slug', $slug)->pluck('title')->first();

        if (!$name) {
            abort(404);
        }

        $this->primaryColor = $this->getFilamentPrimaryColor();

        return view('theme::pages.post.detail', [
            'slug' => $slug,
            'name' => $name,
            'primaryColor' => $this->primaryColor
        ]);
    }

    public function productDetail($slug)
    {
        $name = ProductVps::where('slug', $slug)->pluck('name')->first();

        if (!$name) {
            abort(404);
        }

        $this->primaryColor = $this->getFilamentPrimaryColor();

        return view('theme::pages.product.detail', [
            'slug' => $slug,
            'name' => $name,
            'primaryColor' => $this->primaryColor
        ]);
    }

    public function domainLookupDetail()
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();

        return view('theme::pages.domain-lookup.index', [
            'primaryColor' => $this->primaryColor
        ]);
    }
}
