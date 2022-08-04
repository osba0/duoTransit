 <!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <div style="color:#0d3095; font-size: 18px;">
      Bonjour,
      <br/><br/>
      Veuillez consulter le rapport d'empotage ci-joint N°<b>{{ $numeroDossier }}</b> - N° TC <b>{{ $numtc }}</b> - N° Type TC <b>{{ $typetc }}</b>- N° Plomb <b>{{ $plomb }}</b> pour le compte de <b>{{ $societe["clnmcl"] }}</b>.
      <br/><br/>
      Cordialement,<br/><br/>
      <h3 style="margin:0"><u>{{ $transitaire['nom'] }}</u></h3>
      Tél: {{ $transitaire['telephone'] }}<br/>
      Fax: {{ $transitaire['fax'] }}<br/>
      Email: {{ $transitaire['email'] }}<br/>
      Adresse: {{ $transitaire['adresse'] }}<br/>
   </div>
</body>
</html> 