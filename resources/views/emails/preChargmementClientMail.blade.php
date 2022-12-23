 <!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <div style="color:#0d3095; font-size: 18px;">
      Bonjour,
      <br/><br/>
      Veuillez consulter les commandes préchargées <a href="{{ url('') }}{{ $pathFile }}" target="_blank">
         <u><b>Cliquez ici >>></b></u>
      </a>
      <br/><br/>
      Cordialement,<br/><br/>
      <h3 style="margin:0"><u>{{ $societe['clnmcl'] }}</u></h3>
      Tél: {{ $societe['cltele'] }}<br/>
      Adresse: {{ $societe['cladcl'] }}<br/>
   </div>
   


</body>
</html> 
