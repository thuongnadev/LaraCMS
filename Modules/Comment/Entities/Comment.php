<?php

namespace Modules\Comment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'account_id', 'show', 'pin', 'flag', 'like', 'dislike', 'commentable_id', 'commentable_type'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(CommentDislike::class, 'comment_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CommentReport::class);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(CommentUrl::class, 'comment_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(CommentFile::class, 'comment_id');
    }
}
