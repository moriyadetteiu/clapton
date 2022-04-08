<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MySQLのビューへのModelなので、基本的にはReadOnlyとして扱うこと。
 *
 * note: 悩みポイントとして、getXxxAttributeなど実テーブルのModelと共通で使いたいメソッドをどのように扱うか？
 *       traitで共有もできるが、少し冗長な感じもする...
 *
 * note: 現状レコードの規模が膨大にならない想定でのviewなので、巨大化した際のパフォーマンスは心配ごととしてある。
 *       パフォーマンスが劣化してきた際の対応案として下記を想定している。
 *       ・MySQLのパーティションでteam毎の分割を行う
 *       ・マスタ系のjoinをやめる
 *          → マスタ名をレコードに加え、取り回しのいいようにしているだけなので、IDから別途引けばよい
 *       ・viewテーブル自体をやめる
 *          → careAboutCircleを軸にリレーションをたどっていけば同じことはできるので、どうしようもない場合はあり
 */
class ViewCircleList extends Model
{
    public function careAboutCircle(): BelongsTo
    {
        return $this->belongsTo(CareAboutCircle::class);
    }

    public function circlePlacement(): BelongsTo
    {
        return $this->belongsTo(CirclePlacement::class);
    }

    public function circlePlacementClassification(): BelongsTo
    {
        return $this->belongsTo(CirclePlacementClassification::class);
    }

    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function joinEvent(): BelongsTo
    {
        return $this->belongsTo(JoinEvent::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wantCircleProduct(): BelongsTo
    {
        return $this->belongsTo(WantCircleProduct::class);
    }

    public function wantPriority(): BelongsTo
    {
        return $this->belongsTo(WantPriority::class);
    }

    public function circleProduct(): BelongsTo
    {
        return $this->belongsTo(CircleProduct::class);
    }

    public function circleProductClassification(): BelongsTo
    {
        return $this->belongsTo(CircleProductClassification::class);
    }
}
