<?php

namespace App\Console\Commands;

use App\Mail\ManutencaoAlertaMail;
use App\Models\ManutencaoAlerta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DispararAlertas extends Command
{
    protected $signature = 'alertas:disparar';
    protected $description = 'Verifica alertas de manutenção e envia e-mail para admins/gestores quando vencidos';

    public function handle(): int
    {
        $agora = Carbon::now();

        $alertas = ManutencaoAlerta::with('equipamento')
            ->orderBy('id')
            ->get()
            ->filter(function (ManutencaoAlerta $alerta) use ($agora) {
                // Tipo data
                if ($alerta->tipo === 'data') {
                    if ($alerta->recorrente && $alerta->dias_recorrencia) {
                        $inicio = $alerta->data_inicio_recorrencia ?? $alerta->created_at;
                        if (! $inicio) return false;
                        $ultima = $alerta->last_sent_at ?? $inicio;
                        return $ultima->addDays($alerta->dias_recorrencia)->lessThanOrEqualTo($agora);
                    }
                    if ($alerta->data_alerta) {
                        return $agora->toDateString() >= $alerta->data_alerta->toDateString()
                            && $alerta->last_sent_at === null;
                    }
                    return false;
                }

                // Tipo horímetro
                if ($alerta->tipo === 'horimetro' && $alerta->horimetro_alvo !== null) {
                    $horimetroAtual = $alerta->equipamento?->horimetro ?? 0;
                    return $horimetroAtual >= $alerta->horimetro_alvo
                        && $alerta->last_sent_at === null;
                }

                return false;
            });

        // Temporariamente não envia e-mail; apenas reporta quantos alertas estariam prontos.
        $this->info('Alertas prontos para envio (e-mail desativado): ' . $alertas->count());

        return Command::SUCCESS;
    }
}
