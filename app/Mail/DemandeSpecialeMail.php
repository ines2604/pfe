<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DemandeSpeciale;

class DemandeSpecialeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demandeSpeciale; 

    /**
     * Créer une nouvelle instance du message.
     *
     * @param DemandeSpeciale $demandeSpeciale
     */
    public function __construct(DemandeSpeciale $demandeSpeciale)
    {
        $this->demandeSpeciale = $demandeSpeciale; 
    }

    /**
     * Construire le message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->demandeSpeciale->email, $this->demandeSpeciale->nom)
                    ->subject('nouvelle demande spéciale: ' . $this->demandeSpeciale->sujet) 
                    ->view('demande_spéciale'); 
    }
}