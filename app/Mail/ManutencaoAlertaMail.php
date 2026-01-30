<?php

namespace App\Mail;

use App\Models\ManutencaoAlerta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class ManutencaoAlertaMail extends Mailable
{
    use Queueable, SerializesModels;

    public ManutencaoAlerta $alerta;

    public function __construct(ManutencaoAlerta $alerta)
    {
        $this->alerta = $alerta->load('equipamento');
    }

    public function build()
    {
        $equip = $this->alerta->equipamento;
        $subject = 'Alerta de Manutenção - ' . ($equip->nome ?? 'Equipamento');

        return $this->subject($subject)
            ->view('emails.manutencao_alerta', [
                'alerta' => $this->alerta,
                'equipamento' => $equip,
            ]);
    }
}
