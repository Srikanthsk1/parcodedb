<style>
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overscroll-behavior: none;  
        /* touch-action: none;  */
    }
</style>
<?php

include_once 'connectdb.php';
session_start();


if($_SESSION['useremail']=="" OR $_SESSION['role']=="User"){

  header('location:../index.php');
  
  }


  if($_SESSION['role']=="Admin" ){

    include_once "header.php";

    
  }else{

    include_once "headeruser.php";

  }

error_reporting(0);

$id=$_GET['id'];

if(isset($id)){

$delete=$pdo->prepare("delete from tbl_user where userid =".$id);

if($delete->execute()){

  $_SESSION['status']="Account deleted successfully";
  $_SESSION['status_code']="success";

}else{

$_SESSION['status']="Account Is Not Deleted";
$_SESSION['status_code']="warning";
     }





}








if (isset($_POST['btnsave'])) {
    $username = $_POST['txtname'];
    $useremail = $_POST['txtemail'];
    $userpassword = $_POST['txtpassword'];
    $userrole = $_POST['txtselect_option'];

    // Additional fields for User role
    $company = isset($_POST['txtcompany']) ? $_POST['txtcompany'] : null;
    $phone = isset($_POST['txtphone']) ? $_POST['txtphone'] : null;
    $address = isset($_POST['txtaddress']) ? $_POST['txtaddress'] : null;

    if (isset($_POST['txtemail'])) {
        $select = $pdo->prepare("SELECT useremail FROM tbl_user WHERE useremail = :email");
        $select->bindParam(':email', $useremail);
        $select->execute();

        if ($select->rowCount() > 0) {
            $_SESSION['status'] = "Email already exists. Create Account From New Email";
            $_SESSION['status_code'] = "warning";
        } else {
            // Insert query with additional fields for User role
            $insert = $pdo->prepare("INSERT INTO tbl_user (username, useremail, userpassword, role, company_name, phone, address) 
                                     VALUES (:name, :email, :password, :role, :company, :phone, :address)");

            $insert->bindParam(':name', $username);
            $insert->bindParam(':email', $useremail);
            $insert->bindParam(':password', $userpassword);
            $insert->bindParam(':role', $userrole);
            $insert->bindParam(':company', $company);
            $insert->bindParam(':phone', $phone);
            $insert->bindParam(':address', $address);

            if ($insert->execute()) {
                $_SESSION['status'] = "User registered successfully";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Error inserting the user into the database";
                $_SESSION['status_code'] = "error";
            }
        }
    }
}






?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Registration</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Starter Page</li> -->
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
     
        

        <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0">Registration</h5>
            </div>
            <div class="card-body">

<div class="row">

<div class="col-md-4">

<form action="" method="post">
               
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" placeholder="Enter Name" name="txtname" required>
                  </div>


                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control"  placeholder="Enter email" name="txtemail" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control"  placeholder="Password" name="txtpassword" required>
                  </div>
                 
                  <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="txtselect_option" id="roleSelect" required>
                          <option value="" disabled selected>Select Role</option>
                          <option value="Admin">Admin</option>
                          <option value="User">User</option>
                         
                        </select>
                      </div>
               
<!-- Additional fields for User role -->
                      <div id="userFields" style="display: none;">
                          <div class="form-group">
                              <label for="exampleInputCompany">Company Name</label>
                              <input type="text" class="form-control" placeholder="Enter Company Name" name="txtcompany">
                          </div>

                          <div class="form-group">
                              <label for="exampleInputPhone">Phone Number</label>
                              <input type="text" class="form-control" placeholder="Enter Phone Number" name="txtphone">
                          </div>

                          <div class="form-group">
                              <label for="exampleInputAddress">Address</label>
                              <textarea class="form-control" placeholder="Enter Address" name="txtaddress"></textarea>
                          </div>
                      </div>
               

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                </div>
              </form>

<script>
                  // Show additional fields when "User" role is selected
                  document.getElementById('roleSelect').addEventListener('change', function () {
                      const userFields = document.getElementById('userFields');
                      if (this.value === 'User') {
                          userFields.style.display = 'block';
                      } else {
                          userFields.style.display = 'none';
                      }
                  });
              </script>

</div>









<div class="col-md-8">

<table class="table table-striped table-hover ">
<thead>
<tr>
 <td>#</td>
 <td>Name</td>
 <td>Email</td>
 <td>Password</td>
 <td>Role</td> 
 <td>Delete</td>   
</tr>

</thead>


<tbody>

<?php

$select = $pdo->prepare("select * from tbl_user order by userid ASC");
$select->execute();

while($row=$select->fetch(PDO::FETCH_OBJ))
{

echo'
<tr>
<td>'.$row->userid.'</td>
<td>'.$row->username.'</td>
<td>'.$row->useremail.'</td>
<td>'.$row->userpassword.'</td>
<td>'.$row->role.'</td>
<td>

<a href="registration.php?id='.$row->userid.'" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a>
</td>


</tr>';

}

?>

</tbody>

</table>




</div>


           

       
             
            </div>



            </div>
          </div>
     

       
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php

include_once "footer.php";


?>

<?php
  if(isset($_SESSION['status']) && $_SESSION['status']!='')
 
  {

?>
<script>

  
     Swal.fire({
        icon: '<?php echo $_SESSION['status_code'];?>',
        title: '<?php echo $_SESSION['status'];?>'
      });

</script>
<?php
unset($_SESSION['status']);
  }
  ?>