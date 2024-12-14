<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'color', 'is_default', 'order',
        'project_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function (TicketStatus $item) {
            if ($item->is_default) {
                $query = TicketStatus::where('id', '<>', $item->id)
                    ->where('is_default', true);
                if ($item->project_id) {
                    $query->where('project_id', $item->project_id);
                }
                $query->update(['is_default' => false]);
            }

            $query = TicketStatus::where('order', '>=', $item->order)->where('id', '<>', $item->id);
            if ($item->project_id) {
                $query->where('project_id', $item->project_id);
            }
            $toUpdate = $query->orderBy('order', 'asc')
                ->get();
            $order = $item->order;
            foreach ($toUpdate as $i) {
                if ($i->order == $order || $i->order == ($order + 1)) {
                    $i->order = $i->order + 1;
                    $i->save();
                    $order = $i->order;
                }
            }
        });
    }

    /**
     * Relasi ke tiket.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'status_id', 'id')->withTrashed();
    }

    /**
     * Relasi ke proyek.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Hitung jumlah tiket berdasarkan status.
     *
     * @return int
     */
    public function getTicketCountAttribute(): int
    {
        return $this->tickets()->count();
    }
}
