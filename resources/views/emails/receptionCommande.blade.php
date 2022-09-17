 <!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <div style="color:#0d3095; font-size: 18px;">
      Bonjour,
      <br/><br/>
      Veuillez recevoir la liste des commandes receptionnés du {{ date("d/m/Y", strtotime( '-1 days' ) ) }}.
      <br/><br/>
      <table class="table table-striped">  
         <thead style="width:100%; background: rgba(52,73,94,0.94); color: #ffffff;">
            <th style="height: 37px; padding: 0 15px; font-size: 15px">N° Commande</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Type de commande</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Fournisseur</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Entrepots</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Clients</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Transitaire</th> 
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Facture</th> 
         </thead>
         <tbody>
            @foreach($commandes as $commande)
                 <tr>
                     <td style="padding: 0 15px; font-size: 14px" height="35">{{ $commande->rencmd }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->typcmd }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->fonmfo }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->nomEntrepot }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->clnmcl }}</td> 
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->nom }}</td> 
                     <td style="padding: 0 15px; font-size: 14px">
                        @if($commande->refasc !='')
                        <a href="{{ env('APP_URL') }}/assets/factures/{{ $commande->refasc }}">Voir</a>
                        @else -
                        @endif
                        
                     </td>
                 </tr>
             @endforeach
         </tbody>
      </table>
      Cordialement,<br/><br/>
      <h3 style="margin:0"><u>DuoTransit</u></h3>
   </div>
   


</body>
</html> 