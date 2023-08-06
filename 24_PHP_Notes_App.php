<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <title>Notes App</title>
</head>


<body>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModal">Edit Your Notes Here</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action='/PHP_Course/24_PHP_Notes_App.php' method='post'>
                        <input type="hidden" name="editText" id="editText">
                        <div class="form-group ">
                            <label for="title">Notes Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                                aria-describedby="emailHelp" placeholder="Enter any title">
                        </div>
                        <div class="form-group">
                            <label for="desc">Notes Description</label>
                            <textarea class="form-control" id="descEdit" name="descEdit" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <a class="navbar-brand" href="#">Notes App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/PHP_Course/24_PHP_Notes_App.php">Home <span
                            class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <?php
    //Establish Connection To Database
    $servername='localhost';
    $username='root';
    $password='';
    $database='notes-app';

    $conn=mysqli_connect($servername,$username,$password,$database);

    if(!$conn){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Getting Some Error You Can Try Again Later.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
    }
    if(isset($_GET['delete'])){
        $sno=$_GET['delete'];
        $sql="DELETE FROM notes_app WHERE `notes_app`.`S_NO` = $sno";
        $res=mysqli_query($conn,$sql);
    }
    //getting the post request
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['editText'])){
            //Running The Update Query
            $sno=$_POST['editText'];
            $title=$_POST['titleEdit'];
            $descrip=$_POST['descEdit'];

            $sql="UPDATE `notes_app` SET `Notes_Title` = '$title', `Notes_Description` = '$descrip' WHERE `notes_app`.`S_NO` = $sno";
            $res=mysqli_query($conn,$sql);
        }
        else{
            $title=$_POST['title'];
            $description=$_POST['desc'];

            //turn on the sql query insert

            $sql="INSERT INTO `notes_app` (`S_NO`, `Notes_Title`, `Notes_Description`, `Time`) VALUES (NULL, '$title', '$description', current_timestamp())";
            $res=mysqli_query($conn,$sql);

            if(!$res){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Getting Some Error You Can Try Again Later.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            }
            else{
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your Notes Is Saved Successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            }
        }
    }

?>
    <div class="container my-5">
        <h2>Put Your Essential Notes Here</h2>
        <form action='/PHP_Course/24_PHP_Notes_App.php' method='post'>
            <div class="form-group ">
                <label for="title">Notes Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp"
                    placeholder="Enter any title">
            </div>
            <div class="form-group">
                <label for="desc">Notes Description</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
            <br>
        </form>
    </div>
    <div class="container my-1">
        <table class="table mt-4" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">SNO</th>
                    <th scope="col">Notes Title</th>
                    <th scope="col">Notes Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql="SELECT * FROM `notes_app`";
                    $res=mysqli_query($conn,$sql);
                    $num=mysqli_num_rows($res);
                    if($num>0){
                        $count=1;
                        while($row=mysqli_fetch_assoc($res)){
                            echo '<tr>
                            <th scope="row">'.$count.'</th>
                            <td>'.$row['Notes_Title'].'</td>
                            <td>'.$row['Notes_Description'].'</td>
                            <td><button type="button" class="edit btn btn-primary" id='.$row['S_NO'].'>Edit</button> <button type="button" class="delete btn btn-primary" id=d'.$row['S_NO'].'>Delete</button></td>
                            </tr>';
                            $count++;
                        }


                    }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
    <script>
        let edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((elements) => {
            elements.addEventListener('click', (e) => {
                let tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName('td')[0].innerText;
                description = tr.getElementsByTagName('td')[1].innerText;
                titleEdit.value = title;
                descEdit.value = description;
                editText.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle')
            })
        })

        let delets = document.getElementsByClassName('delete');
        Array.from(delets).forEach((elements) => {
            elements.addEventListener('click', (e) => {
                if (confirm("Do You Want To Delete This Note?")) {
                    let sno = e.target.id.substr(1,);
                    window.location = `/PHP_Course/24_PHP_Notes_App.php?delete=${sno}`;
                }

            })
        })
    </script>
</body>

</html>