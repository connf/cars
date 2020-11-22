<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VehiclesImported extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $total;
    public $success;
    public $errored;
    public $errors;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->date = Carbon::now();

        $this->total = $data['total'];
        $this->success = $data['success'];
        $this->errored = $data['errored'];

        $this->errors = $data['errors'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from(config('mail.from.address'), config('mail.from.name'))
            ->to(env('EMAIL_RECIPIENT'))
            ->markdown('emails.import.results');

        return $this;
    }
}
