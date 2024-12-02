<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Post\Entities\Post;

class PostContent extends Component
{
    public $config;
    public $post;
    
    public function mount($config)
    {
        $this->config = $config['component'] ?? [];
        $postId = $this->config['post'] ?? null;

        if ($postId) {
            $this->post = Post::findOrFail($postId);
        } else {
            $this->post = null;
        }
    }

    public function render()
    {
        return view('theme::livewire.post-content', [
            'post' => $this->post,
        ]);
    }
}
