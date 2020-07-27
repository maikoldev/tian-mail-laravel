<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
  use Queueable, SerializesModels;

  public $data, $pdf;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($data, $pdf)
  {
    $this->data = $data;
    $this->pdf = $pdf;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->attachData($this->pdf->output(), 'CertificadoManipuladorDeAlimentos.pdf')
      ->attachData(file_get_contents($this->data['avatar']), $this->data['avatar']->getClientOriginalName())
      ->attachData(file_get_contents($this->data['documentFile']), $this->data['documentFile']->getClientOriginalName())
      ->from('wordpress@manipulacionalimentos.co', 'Curso BPM - Carnet manipulación de alimentos')
      ->markdown('emails.certificate', ['data' => $this->data])
      ->subject('Curso BPM - Carnet manipulación de alimentos');
  }
}
