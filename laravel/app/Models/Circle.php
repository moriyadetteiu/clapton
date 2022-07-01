<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Circle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'kana', 'memo'];

    public function circlePlacements(): HasMany
    {
        return $this->hasMany(CirclePlacement::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function notParticipationCircles(): HasMany
    {
        return $this->hasMany(NotParticipationCircle::class);
    }

    public function participateInEventState(string $eventId): string
    {
        // FIXME: ステータスを文字列で返しているのは微妙なので、ステータスを表すクラスなり、定数なりを用意する
        //        できればフロントエンドにも共有できる仕組みがあるとよい
        $eventDates = Event::findOrFail($eventId)->eventDates()->pluck('id');
        if ($this->circlePlacements()->whereIn('event_date_id', $eventDates)->exists()) {
            return '参加';
        }

        if ($this->notParticipationCircles()->where('event_id', $eventId)->exists()) {
            return '不参加';
        }

        return '未確認';
    }
}
