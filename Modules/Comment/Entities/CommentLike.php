<?php

namespace Modules\Comment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'reply_id', 'account_id'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function replies(): belongsTo
    {
        return $this->belongsTo(CommentReply::class);
    }

    public function account(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
