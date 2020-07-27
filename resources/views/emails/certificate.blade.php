@component('mail::message')
¡Felicidades {{ $data['fullname'] }}! Acabas de finalizar todos los módulos del curso virtual BPM ofertado por Tian IPS, adjunto te enviamos el certificado y carnet como manipulador de alimentos.

Nombres:
{{ $data['fullname'] }}

Apellidos:
{{ $data['lastname'] }}

Tipo de documento:
{{ $data['documentType'] }}

No. de documento de identidad:
{{ $data['documentNumber'] }}

Número de celular:
{{ $data['phone'] }}

Correo electrónico:
<a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
@endcomponent
