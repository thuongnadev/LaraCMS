<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Post\Entities\Post;
use Modules\Theme\Traits\HandleColorTrait;
use Illuminate\Support\Facades\Cache;

class PostDetail extends Component
{
    use HandleColorTrait;

    public $slug;
    public $post;
    public $relatedPosts;
    public $config = [];
    public $primaryColor;
    public $style;

    public function mount($slug)
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->slug = $slug;
        $this->post = $this->fetchPost();
        $this->relatedPosts = $this->fetchRelatedPosts();
        $this->style = Cache::get('post_config_style', '');
    }

    public function fetchPost()
    {
        $query = Post::with(['tags', 'categories', 'media']);

        if (isset($this->config['category'])) {
            $categoryIds = json_decode($this->config['category'], true);
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        return $query->where('slug', $this->slug)->firstOrFail();
    }

    public function fetchRelatedPosts()
    {
        $query = Post::with(['tags', 'categories', 'media'])
            ->where('id', '!=', $this->post->id)
            ->where('status', 'published');

        $categoryIds = $this->post->categories->pluck('id')->toArray();
        $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });

        $tagIds = $this->post->tags->pluck('id')->toArray();
        $query->orWhereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        });

        return $query->latest()->limit(6)->get();
    }

    public function render()
    {
        $url = url()->current();
        $title = $this->post->title;
        return view('theme::livewire.post-detail', [
            'post' => $this->post,
            'relatedPosts' => $this->relatedPosts,
            'style' => $this->style,
            'url' => $url,
            'title' => $title,
        ]);
    }
}
