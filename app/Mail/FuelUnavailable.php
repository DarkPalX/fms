<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FuelUnavailable extends Mailable
{
    use Queueable, SerializesModels;
    public $req;
    public $fuel;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fuel,$req)
    {
        $this->req = $req;
        $this->fuel = $fuel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.fuelUnavailable')->subject('Advisory: '.$this->req->fuel_name_disable_hidden);
    }
}
