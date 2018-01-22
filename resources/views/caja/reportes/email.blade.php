<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>¡Curso Laravel!</h2>

<div>
   ¡Bienvenido al sitio Web de {{ $persona }} !
   <img id="imagen" style="float: right"  src="{{ $nueva_imagen->embed($pathToFile) }}"/>
</div>

</body>
</html>