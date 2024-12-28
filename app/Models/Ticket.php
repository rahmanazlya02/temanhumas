<?php

namespace App\Models;

use App\Notifications\TicketCreated;
use App\Notifications\TicketStatusUpdated;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Carbon;
use App\Events\TicketUpdated;

class Ticket extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'content',
        'owner_id',
        'responsible_id',
        'status_id',
        'project_id',
        'order',
        'type_id',
        'priority_id',
        'deadline',
        'reminder',
        'epic_id'
    ];
    

    public static function boot()
    {
        parent::boot();

        // Menyinkronkan user yang bertanggung jawab ketika task baru dibuat atau diupdate
        static::saved(function ($ticket) {
            // Trigger event setelah ticket disimpan
            event(new TicketUpdated($ticket));
        });
        
        // Menyinkronkan ketika task dihapus, hapus user yang bertanggung jawab dari project
        static::deleted(function ($ticket) {
            // Cek apakah ticket memiliki responsible_id dan project_id
            if ($ticket->responsible_id && $ticket->project_id) {
                // Ambil project terkait
                $project = $ticket->project;

                // Hapus user responsible dari project
                $project->users()->detach($ticket->responsible_id);
            }
        });

        static::creating(function (Ticket $item) {
            $project = Project::where('id', $item->project_id)->first();
            $count = Ticket::where('project_id', $project->id)->count();
            $order = $project->tickets?->last()?->order ?? -1;
            //$item->code = $project->ticket_prefix . '-' . ($count + 1);
            $item->order = $order + 1;
            // if (empty($item->reminder) && !empty($item->deadline)) {
            //     $item->reminder = Carbon::parse($item->deadline)->subHours(12);
            // };
            // Check if deadline is set and if it's within 12 hours from now
            if (empty($item->reminder) && !empty($item->deadline)) {
                $deadline = Carbon::parse($item->deadline);

                // If deadline is within 12 hours from now, set reminder to the deadline
                if ($deadline->diffInHours(Carbon::now()) < 12) {
                    $item->reminder = $deadline; // Set reminder as the deadline
                } else {
                    // Optionally, you can set a default reminder time, e.g., 12 hours before the deadline
                    $item->reminder = $deadline->subHours(12);
                }
            }
        });

        static::updating(function (Ticket $item) {
            $old = Ticket::where('id', $item->id)->first();

            // Check if the deadline has changed or is being updated
            if ($old->deadline !== $item->deadline && !empty($item->deadline)) {
                $deadline = Carbon::parse($item->deadline);

                // If deadline is within 12 hours from now, set reminder to the deadline
                if ($deadline->diffInHours(Carbon::now()) < 12) {
                    $item->reminder = $deadline; // Set reminder as the deadline
                } else {
                    // Optionally, set the reminder to 12 hours before the deadline
                    $item->reminder = $deadline->subHours(12);
                }
            }

            // Ticket activity based on status
            $oldStatus = $old->status_id;
            if ($oldStatus != $item->status_id) {
                TicketActivity::create([
                    'ticket_id' => $item->id,
                    'old_status_id' => $oldStatus,
                    'new_status_id' => $item->status_id,
                    'user_id' => auth()->user()->id
                ]);
                foreach ($item->watchers as $user) {
                    $user->notify(new TicketStatusUpdated($item));
                }
            }
        });
    }

    public function name():String
    {
        return $this->name;
    }
    
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'status_id', 'id')->withTrashed();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id')->withTrashed();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'type_id', 'id')->withTrashed();
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id', 'id')->withTrashed();
    }

    // Relasi epic - bisa jadi belongsTo atau hasOne
    public function epic()
    {
        return $this->belongsTo(Epic::class, 'epic_id'); // Jika epic_id adalah foreign key
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TicketActivity::class, 'ticket_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class, 'ticket_id', 'id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class, 'ticket_id', 'id');
    }

    public function watchers(): Attribute
    {
        return new Attribute(
            get: function () {
                $users = $this->project->users;
                $users->push($this->owner);
                if ($this->responsible) {
                    $users->push($this->responsible);
                }
                return $users->unique('id');
            }
        );
    }
}
