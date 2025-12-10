<?php

namespace App\Mail;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupRefusedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $group;
    public $note;
    public $stagiaire;

    public function __construct(Group $group, $note, $stagiaire)
    {
        $this->group = $group;
        $this->note = $note;
        $this->stagiaire = $stagiaire;
    }

    public function build()
    {
        return $this->subject('Notification : Groupe refusé')
                    ->view('emails.group_refused');
    }
}

