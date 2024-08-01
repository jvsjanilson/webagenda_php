<?php

namespace App\Observers;

use App\Models\Agenda;

class AgendaObserver
{
    /**
     * Handle the Agenda "created" event.
     */
    public function creating(Agenda $agenda): void
    {
        $agenda->user_id = auth()->user()->id;
    }

    /**
     * Handle the Agenda "updated" event.
     */
    public function updating(Agenda $agenda): void
    {
        if ($agenda->entregue == 1) {
            $agenda->user_id = auth()->user()->id;
        }
    }

    /**
     * Handle the Agenda "deleted" event.
     */
    public function deleted(Agenda $agenda): void
    {
        //
    }

    /**
     * Handle the Agenda "restored" event.
     */
    public function restored(Agenda $agenda): void
    {
        //
    }

    /**
     * Handle the Agenda "force deleted" event.
     */
    public function forceDeleted(Agenda $agenda): void
    {
        //
    }
}
