<div class="max-w-7xl mx-auto px-2">
    @isset($post)
        <div class="post-content prose max-w-none">
            {!! $post->content !!}
        </div>
    @endisset
</div>
