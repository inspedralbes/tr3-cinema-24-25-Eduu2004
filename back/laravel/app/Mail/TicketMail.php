<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticketData;

    /**
     * Create a new message instance.
     *
     * @param array $ticketData Dades del tiquet (ex. seient, hora, pel·lícula, etc.)
     */
    public function __construct(array $ticketData)
    {
        $this->ticketData = $ticketData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Genera el PDF utilitzant una vista Blade (per exemple, resources/views/ticket.blade.php)
        $pdf = Pdf::loadView('ticket', ['ticketData' => $this->ticketData]);

        return $this->subject('El teu tiquet de cinema')
                    ->markdown('emails.ticket') // Vista del cos del correu
                    ->attachData($pdf->output(), 'ticket_' . $this->ticketData['seat']['row'] . '-' . $this->ticketData['seat']['number'] . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
