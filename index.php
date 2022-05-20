<?php
include('conn.php');
?>
<html>
    <head>
        <title>Zoom Meeting Scheduler</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </head>
    <body>
        <form action="zoom_api.php" method="POST">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="topic">Topic</label>
                    <input type="text" class="form-control" id="topic" name="topic" placeholder="Meeting topic">
                </div>
                <div class="form-group col-md-4">
                    <label for="datetime">Meeting Schedule Time</label>
                    <input type="datetime-local" class="form-control" id="datetime" name="datetime" >
                </div>
                <div class="form-group col-md-4">
                    <label for="duration">Meeting Duration</label>
                    <input type="number" class="form-control" id="duration" name="duration" placeholder="Duration in minutes" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="zoom_account">Select Zoom Account</label>
                    <select id="selected_zoom_account" name="selected_zoom_account" class="form-control">
                        <?php 
                            /**
                             * Selecting Clients
                             */
                            $zoom_account_query = "SELECT * FROM zoom_accounts";
                            $result_zoom_account = $conn->query($zoom_account_query);
                            if ($result_zoom_account->num_rows > 0) {
                                while($row = $result_zoom_account->fetch_assoc()){
                                    echo '<option value="'.$row['id'].'">'.$row['user_name'].'</option>';
                                }
                            }
                            else{
                                echo '<option>No clients Found</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="client">Select Client</label>
                    <select id="selected_client" name="selected_client" class="form-control">
                        <?php 
                            /**
                             * Selecting Clients
                             */
                            $client_query = "SELECT * FROM clients";
                            $result_clients = $conn->query($client_query);
                            if ($result_clients->num_rows > 0) {
                                while($row = $result_clients->fetch_assoc()){
                                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                }
                            }
                            else{
                                echo '<option>No clients Found</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="client">Select Participants</label>
                    <select id="selected_participants" name="selected_participants[]" class="form-control" multiple>
                        <?php 
                            /**
                             * Selecting Participants
                             */
                            $participant_query = "SELECT * FROM participants";
                            $result_participants = $conn->query($participant_query);
                            if ($result_participants->num_rows > 0) {
                                while($row = $result_participants->fetch_assoc()){
                                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                }
                            }
                            else{
                                echo '<option>No participant Found</option>';
                            }
                        ?>
                    </select>
                </div> 
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
    </body>
</html>
