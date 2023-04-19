@component('mail::message')
    <p>
        ¡Felicidades {{ $data['name'] }}! Acabas de finalizar todos los módulos del curso virtual BPM ofertado por Tian IPS,
        adjunto te enviamos el certificado y carnet como manipulador de alimentos.
    </p>

    <p>
        Nombres: <br />
        {{ $data['name'] }}
    </p>
    <p>
        Apellidos:<br />
        {{ $data['lastname'] }}
    </p>
    <p>
        Tipo de documento:<br />
        {{ $data['document_type'] }}
    </p>
    <p>
        No. de documento de identidad:<br />
        {{ $data['document_number'] }}
    </p>
    <p>
        Número de celular:<br />
        {{ $data['phone'] }}
    </p>
    <p>
        Correo electrónico:<br />
        <a href="mailto:{{ $data['email'] }}">{!! $data['email'] !!}</a>
    </p>
@endcomponent
