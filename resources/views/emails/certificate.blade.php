@component('mail::message')
    ¡Felicidades {{ $data['name'] }}! Acabas de finalizar todos los módulos del curso virtual BPM ofertado por Tian IPS,
    adjunto te enviamos el certificado y carnet como manipulador de alimentos.

    Nombres:
    {{ $data['name'] }}

    Apellidos:
    {{ $data['lastname'] }}

    Tipo de documento:
    {{ $data['document_type'] }}

    No. de documento de identidad:
    {{ $data['document_number'] }}

    Número de celular:
    {{ $data['phone'] }}

    Correo electrónico:
    <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
@endcomponent
