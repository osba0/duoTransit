 <!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <div style="color:#0d3095; font-size: 18px;">
      Bonjour,
      <br/><br/>
      Veuillez recevoir la liste de(s) commande(s) rejetée(s) du {{ date("d/m/Y", strtotime( '-1 days' ) ) }}.
      <br/><br/>
      <table class="table table-striped">  
         <thead style="width:100%; background: rgba(52,73,94,0.94); color: #ffffff;">
            <th style="height: 37px; padding: 0 15px; font-size: 15px">N° CMD</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px; background: #3490dc;">Motif</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Type de commande</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Fournisseur</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Entrepots</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Clients</th>
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Transitaire</th> 
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Date réception</th> 
            <th style="height: 37px; padding: 0 15px; font-size: 15px">Facture</th> 
         </thead>
         <tbody>
            @foreach($commandes as $commande)
                 <tr>
                     <td style="padding: 0 15px; font-size: 14px" height="35">{{ $commande->rencmd }}</td>
                     <td style="padding: 0 15px; font-size: 14px;">{{ $commande->motif }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->typcmd }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->fonmfo }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->nomEntrepot }}</td>
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->clnmcl }}</td> 
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->nom }}</td> 
                     <td style="padding: 0 15px; font-size: 14px">{{ $commande->recrea }}</td> 
                     <td style="padding: 0 15px; font-size: 14px">
                        
                        @if(isset($commande->refasc) && !is_null($commande->refasc))
                           @if(strpos($commande->refasc,'\"') > 1)
                              @php 
                                 $format = json_decode($commande->refasc); 
                                 $factures = json_decode($format); 
                              @endphp 
                           @else
                              @php $factures = json_decode($commande->refasc); 
                              @endphp
                           @endif
                        @else
                           @php $factures = []; @endphp
                        @endif

                      
                      @if(count((array) $factures) > 0) 
                        
                           @foreach((array) $factures as $key=>$fact)
                              @if($fact != "[]") 
                                 <a href="{{ url('') }}/assets/factures/{{ $fact }}"> n°{{$key+1}} </a>, 
                              @else
                              -
                              @endif
                           @endforeach
                      @else
                      -
                      @endif
                       
                     </td>
                 </tr>
             @endforeach
         
         </tbody>
      </table>
      <br/><br/>
      <div style="text-align: center; color: black; font-size: 14px;
border: 1px solid black;
padding: 5px 0;">Ceci est un <b>email</b> automatique, <b>merci de ne pas répondre.</b></div>
      <br/><br/>
      Cordialement,<br/><br/>
      <h3 style="margin:0"><u>DuoTransit</u></h3>
   </div>
   


</body>
</html> 