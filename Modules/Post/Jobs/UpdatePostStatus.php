<?php

namespace Modules\Post\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Post\Entities\Post;

class UpdatePostStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $postId;

    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    public function handle()
    {
        $post = Post::find($this->postId);
        if ($post && $post->published_at <= now() && $post->status === 'draft') {
            $post->update(['status' => 'published']);
        }
    }
}
