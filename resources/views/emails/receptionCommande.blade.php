 <!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <div style="color:#0d3095; font-size: 18px;">
      Bonjour,
      <br/><br/>
      Une nouvelle commande a été réceptionnée n° commande <b>{{ $commande['rencmd'] }}</b> - <b>{{ $commande['typeCmd'] }}</b>.
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