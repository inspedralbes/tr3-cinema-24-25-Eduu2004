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
        $pdf = Pdf::loadView('ticket', ['ticketData' => $this->ticketData]);

        $filename = 'ticket_' . $this->ticketData['seat']['row'] . '-' . $this->ticketData['seat']['number'] . '.pdf';

        return $this->subject('El teu tiquet de cinema')
                    ->markdown('emails.ticket')
                    ->attachData($pdf->output(), $filename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
