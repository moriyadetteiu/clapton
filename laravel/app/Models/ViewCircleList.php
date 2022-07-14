<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeOrderByPlacement(Builder $builder): Builder
    {
        return $builder
            ->orderBy('event_date_id')
            ->orderBy('placement_hole')
            ->orderBy('placement_line')
            ->orderBy('placement_number')
            ->orderBy('placement_a_or_b');
    }

    public function getPlacementFullAttribute(): string
    {
        return "{$this->placement_hole}{$this->placement_line}-{$this->placement_number}{$this->placement_a_or_b}";
    }

    public function getMemoAttribute(): string
    {
        $memos = [];

        if ($this->care_about_circle_memo) {
            $memos[] = $this->care_about_circle_memo;
        }

        if ($this->want_circle_product_memo) {
            $memos[] = $this->want_circle_product_memo;
        }

        return implode("\n", $memos);
    }
}
