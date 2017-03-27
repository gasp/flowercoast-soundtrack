<?php

$error = '';
if (isset($_GET['error'])) {

  switch ($_GET['error']) {
    case 'invalid':
      $error = '<div class="alert alert-danger" role="alert"><strong>Invalid code!</strong> You entered a wrong code, please double check on your card.</div>';
      break;

    case 'expired':
      $error = '<div class="alert alert-danger" role="alert"><strong>Session expired!</strong> You have been kicked off because idling too long on the page, please re-enter your code.</div>';
      break;

    case 'many':
      $error = '<div class="alert alert-danger" role="alert"><strong>Too many connections!</strong> You have been using this code quite a lot, dude !</div>';
      break;

    default:
      $error = '<div class="alert alert-danger" role="alert"><strong>Sorry!</strong> Something went wrong.</div>';
      break;
  }
}

include 'templates/index.tpl.html';
?>
