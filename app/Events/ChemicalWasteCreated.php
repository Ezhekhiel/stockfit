<?php

namespace App\Events;

use App\Models\chemical___waste;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChemicalWasteCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $area;

    public function __construct($area)
    {
        $this->area = $area;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chemical-waste'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chemical.created';
    }
}
