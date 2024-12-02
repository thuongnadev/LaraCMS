<?php

namespace Modules\Comment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentReport extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'comment_id', 'reply_id', 'account_id'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function replies(): belongsTo
    {
        return $this->belongsTo(CommentReply::class, 'comment_reply_id');
    }

    public function account(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
