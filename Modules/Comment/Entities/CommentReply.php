<?php

namespace Modules\Comment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'account_id', 'comment_id', 'show', 'pin', 'flag', 'like', 'dislike'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'reply_id');
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(CommentDislike::class,'reply_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CommentReport::class, 'reply_id');
    }

    public function urls(): HasMany
    {
        return $this->hasMany(CommentUrl::class, 'comment_reply_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(CommentFile::class, 'comment_reply_id');
    }

}
