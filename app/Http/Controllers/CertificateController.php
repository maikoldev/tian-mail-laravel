<?php

namespace App\Http\Controllers;

use App\Mail\CertificateMail;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CertificateController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $date = Carbon::now()->locale('es');
    $dateObject = $date->toObject();

    $data = $request->all();
    $data['certificateNumber'] = $dateObject->day.sprintf("%'02d", $dateObject->month).$request->documentNumber;
    $data['dateText'] = $date->dayName.', '.$dateObject->day.' de '. $date->monthName.' de '.$dateObject->year;
    $data['certificateExpiration'] = Carbon::now()->addYears(1)->format('d-m-Y');

    $pdf = PDF::loadView('documents.certificate', compact('data'));

    $mailsTo = [$request->email, 'coordinador@tianips.com', 'carnet@manipulacionalimentos.co'];
    Mail::to($mailsTo)->send(new CertificateMail($data, $pdf));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
