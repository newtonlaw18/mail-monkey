<?php include 'mailchimp.php' ?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <title>MailChimp API Test</title>
    </head>
    <body>
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.html">MailMonkey</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="lists.php">Lists</a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Add Emails To List</h2>
              <form action="mailchimp.php" method="post" name="addEmailsToListForm">
                <div class="form-group row">
                  <label for="staticEmail" class="col-sm-2 col-form-label">List</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="list_id">
                    <?php 
                      $lists = get_all_lists(); 
                      for($i = 0; $i<$lists['total'];$i++){
                        echo '
                            <option value="'.$lists['list_info'][$i]->id.'">'.$lists['list_info'][$i]->name.'</option>
                        ';
                      }
                    ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Emails</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" id="message-text" rows="5" name="emails" placeholder="john@gmail.com;emma@gmail.com" required></textarea>
                    <small id="passwordHelpBlock" class="form-text text-muted">
                      *To add multiple emails, simply add ';' after each email *
                    </small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 text-center">
                    <input type="submit" name="submit" class="btn btn-primary">
                  </div>
                </div>
                
              </form>
            </div>
          </div>
      </div>
      
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>