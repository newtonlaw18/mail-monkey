<?php 
  include 'mailchimp.php'
  // header('Location: '.$_SERVER['PHP_SELF']);  
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/lists.js"></script>

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
            <li class="nav-item">
              <a class="nav-link" href="index.html">Home</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="lists.php">Lists</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="campaigns.html">Campaigns</a>
            </li>
          </ul>
        </div>
      </nav>
      <br>
      <div class="container">
        <div class="row text-center">
          <div class="col-md-12">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">List Name</th>
                  <th scope="col">Date Created</th>
                  <th scope="col">No. of Subscriber</th>
                </tr>
              </thead>
              <tbody>       
                  <?php 
                    $lists = get_all_lists(); 
                    // $names = get_all_list_names();
                    for($i = 0; $i<$lists['total'];$i++){
                      echo '
                      <tr>
                        <th scope="row">'.($i+1).'</th>
                        <td>'.$lists['list_info'][$i]->name.'</td>
                        <td>'.$lists['list_info'][$i]->date_created.'</td>
                        <input type="hidden" name="list_id" value="'.$lists['list_info'][$i]->id.'">
                        <td>'.
                            $lists['list_info'][$i]->stats->member_count
                          .'
                        </td>
                      </tr>';
                    }
                  ?>
              </tbody>
            </table>
            <br><br>
            <button type="button" id="new-list-btn" class="btn btn-danger" data-toggle="modal" data-target="#createNewList" data-whatever="@mdo">Create New List</button>
            <a class="btn btn-danger" href="add-subs.php">Add Emails To Existing List</a>
            <a class="btn btn-danger" href="new-campaign.php">Create New Campaign</a>
          </div>
        </div>
          
          <form action="mailchimp.php" method="post">
          <div class="modal fade" id="createNewList" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <p class="text-danger">*Upon successful creation of the list, you'll notice that my personal email has been added to the list automatically. *</p>
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">List Name:</label>
                        <input type="text" class="form-control" id="list_name" name="list_name">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" class="btn btn-primary">
                  </div>
                </div>
              </div>
            </div>
            </form>

      </div>
      
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>


      <script type="text/javascript">
        $("document").ready(function(){
          
        });
      </script>
    </body>
</html>