<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Mail\CertificateMail;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('certificates');
    }

    public function allCertificates()
    {
        $certificates = Certificate::all()->sortByDesc('id');

        $certificates->map(function ($certificate) {
            if ($certificate->certificate_status != 'Expired') {
                if ($certificate->certificate_expiration_date < Carbon::now()) {
                    $certificate->certificate_status = 'Expired';
                    $certificate->save();
                }
            }

            $certificate->certificate_date = Carbon::parse($certificate->certificate_date)->format('d-m-Y H:i A');
            $certificate->certificate_expiration_date = Carbon::parse($certificate->certificate_expiration_date)->format('d-m-Y H:i A');
        });

        $certificates = $certificates->values()->all();

        return $certificates;
    }

    public function validationView()
    {
        return view('validation');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userCertificate = Certificate::where('document_number', $request->documentNumber)->first();

        if ($userCertificate) {
            if ($userCertificate->certificate_expiration_date > Carbon::now()) {
                return response()->json([
                    'message' => 'El usuario ya tiene un certificado.',
                    'certificate' => $userCertificate,
                ], 200);
            }
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        $certificate = new Certificate([
            'user_type' => $request->userType,
            'name' => $request->fullname,
            'lastname' => $request->lastname,
            'document_type' => $request->documentType,
            'document_number' => $request->documentNumber,
            'phone' => $request->phone,
            'email' => $request->email,
            'certificate_status' => 'Generated'
        ]);

        // Check if document number exists
        $uuid = Uuid::uuid4();

        // Save photo
        $photo = $request->file('avatar');
        $photoName = $uuid->toString() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('photos', $photoName, 'public');

        // Saving photo
        $certificate->photo_url = '/storage/photos/' . $photoName;

        // Save certificate
        $date = Carbon::now()->locale('es');
        $dateObject = $date->toObject();
        $dateTimestamp = $date->timestamp;

        $certificate->certificate_number = rand(100000000000, 999999999999);
        $certificate->certificate_date = Carbon::now()->format('Y-m-d H:i:s');
        $certificate->certificate_expiration_date = Carbon::now()->addYears(1)->format('Y-m-d H:i:s');

        $pdfName = $uuid->toString() . '.pdf';

        $data = $certificate->toArray();
        $data['photo_name'] = $photoName;
        $data['pdf_name'] = $pdfName;
        $data['date_text'] = $date->dayName . ', ' . $dateObject->day . ' de ' . $date->monthName . ' de ' . $dateObject->year;
        $data['date_expiration'] = Carbon::now()->addYears(1)->format('d-m-Y');

        // Generate PDF
        $pdf = PDF::loadView('documents.certificate', compact('data'));
        Storage::put('public/certificates/' . $pdfName, $pdf->output());

        // Saving certificate
        $certificate->certificate_url = '/storage/certificates/' . $pdfName;
        $data['certificate_url'] = $certificate->certificate_url;
        $certificate->save();

        $mailsTo = explode(',', env('OTHER_EMAILS'));

        if ($certificate->user_type == 'Particular') {
            try {
                Mail::to($certificate->email)
                    ->cc($mailsTo)
                    ->send(new CertificateMail($data));

                $certificate->certificate_status = 'Sent';
                $certificate->save();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error al enviar el correo.',
                ], 500);
            }
        }
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

    public function validation($certificateId)
    {
        $certificate = Certificate::where('certificate_number', $certificateId)->first();

        if ($certificate) {
            if ($certificate->certificate_expiration_date > Carbon::now()) {
                return response()->json([
                    'message' => 'El certificado es válido.',
                    'certificate' => $certificate,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'El certificado ha expirado.',
                    'certificate' => $certificate,
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'El certificado no existe.',
            ], 404);
        }
    }

    public function resend($certificateId)
    {
        $certificate = Certificate::where('certificate_number', $certificateId)->first();

        if ($certificate) {
            $data = $certificate->toArray();
            $data['photo_name'] = explode('/', $certificate->photo_url)[3];
            $data['pdf_name'] = explode('/', $certificate->certificate_url)[3];

            try {
                Mail::to($certificate->email)->send(new CertificateMail($data));
                return response()->json([
                    'message' => 'El certificado ha sido reenviado.',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'El certificado no se pudo enviar.',
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'El certificado no existe.',
            ], 404);
        }
    }
}
