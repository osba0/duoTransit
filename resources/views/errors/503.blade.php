<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Erreur 503</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/app.css') }}">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/errors.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  </head>

  <body>

  <div class="container">
    <div class="row">

      <div class="col-md-12">
        <h1><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 503</h1>
        <h2>Service en maintenance</h2>
      
        <p>Notre équipe technique effectue présentement un entretien de l'application.</p>
        <p>Merci de votre compréhension.</p>
       
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
           © {{ now()->year }} <a href="#">{{ config('app.name', 'Laravel') }}</a>. All rights reserved.
        </div>
      </div>
    </div>
  </footer>
  </body>
</html>
