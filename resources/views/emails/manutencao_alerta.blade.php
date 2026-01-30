<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Alerta de Manutenção</title>
</head>
<body style="margin:0; padding:0; background:#f6f7fb; font-family: 'Segoe UI', Arial, sans-serif; color:#0f172a;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f7fb; padding:36px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" width="620" cellpadding="0" cellspacing="0"
               style="background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #e9edf5;">
          
          <!-- Header -->
          <tr>
            <td style="padding:18px 22px; border-bottom:1px solid #eef2f7;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="font-size:12px; letter-spacing:.12em; text-transform:uppercase; color:#64748b; font-weight:700;">
                    Alerta de manutenção
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Title -->
          <tr>
            <td style="padding:22px 22px 8px;">
              <div style="font-size:18px; font-weight:700; color:#0f172a; line-height:1.25;">
                {{ $equipamento->nome ?? 'Equipamento' }}
              </div>
              <div style="margin-top:6px; font-size:13px; color:#64748b;">
                Código: <span style="font-weight:700; color:#0f172a;">{{ $equipamento->codigo ?? $equipamento->id }}</span>
              </div>
            </td>
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding:12px 22px 18px;">
              
              <!-- Alert card -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                     style="background:#f8fafc; border:1px solid #e6edf5; border-radius:12px;">
                <tr>
                  <td style="padding:14px 14px;">
                    
                    @if($alerta->tipo === 'horimetro')
                      <div style="font-size:14px; color:#0f172a; line-height:1.5;">
                        Este alerta será disparado quando o horímetro atingir
                        <span style="font-weight:700; color:#16a34a;">
                          {{ number_format($alerta->horimetro_alvo, 2, ',', '.') }} h
                        </span>.
                      </div>
                    @else
                      @if($alerta->recorrente)
                        <div style="font-size:14px; color:#0f172a; line-height:1.5;">
                          Alerta recorrente a cada
                          <span style="font-weight:700; color:#16a34a;">
                            {{ $alerta->dias_recorrencia }} dia(s)
                          </span>.
                        </div>
                      @else
                        <div style="font-size:14px; color:#0f172a; line-height:1.5;">
                          Alerta agendado para
                          <span style="font-weight:700; color:#16a34a;">
                            {{ \Illuminate\Support\Carbon::parse($alerta->data_alerta)->format('d/m/Y') }}
                          </span>.
                        </div>
                      @endif
                    @endif

                  </td>
                </tr>
              </table>

              <!-- Message block -->
              @if($alerta->mensagem)
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:14px;">
                  <tr>
                    <td style="border-left:3px solid #22c55e; padding-left:12px;">
                      <div style="font-size:13px; font-weight:700; color:#0f172a; margin-bottom:6px;">
                        Mensagem
                      </div>
                      <div style="font-size:13px; color:#475569; line-height:1.6; white-space:pre-line;">
                        {{ $alerta->mensagem }}
                      </div>
                    </td>
                  </tr>
                </table>
              @endif

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:14px 22px 20px; border-top:1px solid #eef2f7;">
              <div style="font-size:12px; color:#94a3b8; line-height:1.5;">
                Este alerta foi gerado automaticamente pelo <span style="font-weight:700; color:#64748b;">AgroWerk</span>.
              </div>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
