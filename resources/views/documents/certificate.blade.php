<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certificado Manipulador De Alimentos.pdf</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 15px;
      margin-top: 60px;
    }

    p {
      font-size: 14px;
      margin-bottom: 5px;
      margin-top: 0;
    }

    header {
      background-color: #00579A;
      color: white;
      padding: 10px;
    }

    header img {
      margin-top: 5px;
      margin-bottom: 10px;
      width: 300px;
    }

    header p {
      font-size: 12px;
    }

    .name {
      font-size: 13px;
      padding: 20px;
    }

    .name .fullname {
      font-size: 30px;
      margin-bottom: 10px;
      text-decoration: underline;
    }

    .name .document strong {
      font-size: 14px;
    }

    .texts {
      margin-bottom: 20px;
    }

    .textos p {
      font-size: 13px;
    }

    .modules {
      font-size: 14px;
      margin-bottom: 30px;
      text-align: center;
      width: 100%;
    }

    .modules tr:first-child {
      background-color: #00579A;
      color: #FFFFFF;
      font-size: 18px;
    }

    .signature {
      margin-bottom: 20px;
    }

    .signature .wrap-img {
      margin-bottom: 10px;
      margin-top: 10px;
    }

    .number-certificate {
      font-size: 14px;
      text-align: center;
      width: 100%;
    }

    .number-certificate strong {
      font-size: 16px;
    }

    .carnet .title {
      font-size: 20px;
    }

    .carnet .wrapper {
      margin-top: 50px;
    }

    .carnet .front {
      height: 432px;
      position: relative;
      padding: 35px 25px;
      width: 280px;
    }

    .carnet .back {
      padding: 35px 20px;
    }

    .carnet .front .background {
      position: absolute;
    }

    .carnet .front .avatar {;
      height: 130px;
      left: 106px;
      position: absolute;
      top: 118px;
      width: 110px;
      border: 4px solid #00579A;
      border-radius: 12px;
    }

    .carnet .front .wrap {
      z-index: 99;
    }

    .carnet .front .wrap .name {
      color: #FFFFFF;
      font-size: 14px;
      position: relative;
      text-decoration: underline;
      top: 220px;
    }

    .carnet .front .wrap .field {
      background-color: #FFFFFF;
      color: #000000;
      font-size: 11px;
      left: 122px;
      padding: 1px 10px;
      position: relative;
      text-align: left;
      width: 90px
    }

    .carnet .front .wrap .id {
      top: 244px;
    }

    .carnet .front .wrap .valid {
      top: 246px;
    }

    .carnet .front .wrap .serial {
      top: 248px;
    }

    .col-6 {
      background-color: #E8E8E8;
      float: left;
      width: 50%;
    }

    table, th, td {
      border: 2px solid #00579A;
      border-collapse: collapse;
    }

    th, td {
      padding: 5px;
    }

    .text-center {
      text-align: center;
    }

    .page-break {
      page-break-after: always;
    }

    .text-blue {
      color: #00579A;
    }

  </style>
</head>
<body>
  <header class="text-center">
    <div>
      <img src="images/logo-blanco.png">
    </div>
    <p>Empresa autorizada para realizar capacitación en manipulación de alimentos por la Resolución No DPA.<br>
      1152.13.3. 023 de la Secretaria de Salud Municipal - Palmira.
    </p>
  </header>

  <section class="name text-center">
    <p><strong>Certifica que:</strong></p>
    <p class="fullname text-blue">{{ strtoupper($data['fullname']) }} {{ strtoupper($data['lastname']) }}</p>
    <p class="document">con {{ $data['documentType'] }} No. <strong>{{ $data['documentNumber'] }}</strong></p>
  </section>

  <section class="texts">
    <p>Cursó y aprobó la capacitación de Manipulación de los Alimentos, Inocuidad y Buenas Prácticas de Manufactura
      con una intensidad de 10 horas, la cual tiene una vigencia de 12 meses a partir de la fecha de emisión del presente
      documento.
    </p>
    <br>
    <p>Los temas abordados en la presente certificación se distribuyen en 8 módulos, los cuales se alinean al plan de
      capacitación continuo y permanente en Prácticas Higiénicas en Manipulación de Alimentos, Buenas Prácticas de
      Manufactura y educación sanitaria, contemplado en los Artículos 12 y 13 de la Resolución 2674 de 2013 y el
      Decreto 3075 de 1997.
    </p>
  </section>

  <table class="modules">
    <tr>
      <th colspan="2">Módulos de la capacitación</th>
    </tr>
    <tr>
      <td>Introducción a la manipulación de alimentos</td>
      <td>Microorganismos y ETAs</td>
    </tr>
    <tr>
      <td>Perfiles de manipuladores de alimentos</td>
      <td>Lugar de trabajo y el Plan de Higiene y Saneamiento</td>
    </tr>
    <tr>
      <td>Clasificación y manejo de los alimentos</td>
      <td>El manipulador de alimentos en el trabajo</td>
    </tr>
    <tr>
      <td>Inocuidad de los alimentos y peligros de contaminación</td>
      <td>Las BPM y normatividad vigente</td>
    </tr>
  </table>

  <section class="signature text-center">
    <p>La presente certificación se expide cumpliendo con la normatividad vigente y bajo la firma autorizada por
      Secreataria de salud Municipal - Palmira
    </p>
    <div class="wrap-img">
      <img src="images/firma.jpg">
    </div>
    <p>Jairo Rodriguez Salazar</p>
    <p>C.C. 16.264.937</p>
  </section>

  <table class="number-certificate">
    <tr>
      <td>
        <p>No. de certificado</p>
        <p><strong>{{ $data['certificateNumber'] }}</strong></p>
      </td>
      <td>
        <p>Fecha de expedición</p>
        <p><strong>{{ $data['dateText'] }}</strong></p>
      </td>
    </tr>
  </table>

  <div class="page-break"></div>

  <section class="carnet text-center text-blue">
    <p class="title"><strong>Carnet Manipulador de alimentos</strong></p>
    <p class="text">Puedes imprimir tu carnet si lo deseas</p>

    <div class="wrapper">
      <div class="col-6">
        <div class="front">
          <img class="background" src="images/carnet-front.jpeg" width="280">
          <img class="avatar" src="{{ $data['avatar'] }}">
          <div class="wrap">
            <p class="name"><strong>{{ strtoupper($data['fullname']) }} {{ strtoupper($data['lastname']) }}</strong></p>
            <p class="id field">{{ $data['documentNumber'] }}</p>
            <p class="valid field">{{ $data['certificateExpiration'] }}</p>
            <p class="serial field">{{ $data['certificateNumber'] }}</p>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="back">
          <img src="images/carnet-back.png" width="280">
        </div>
      </div>
    </div>
  </section>
</body>
</html>