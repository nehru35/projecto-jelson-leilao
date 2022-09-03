<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(!empty($_POST["companyname"])) {
    $companyname= $_POST["companyname"];
    $sql ="SELECT companyname FROM tblcompany WHERE companyname=:companyname";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':companyname', $companyname, PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query -> rowCount() > 0)
    {
        echo "<script>alert('something wrong! company name already exists');</script>";
    } else{

        if(isset($_POST['submit']))
        {
            $username=$_POST['fullname'];
            $companyname=$_POST['companyname'];
            $permision='Admin';
            $confirmpassword=md5($_POST['confirmpassword']); 
            $sql="INSERT INTO  tbladmin(AdminName,UserName,CompanyName,Password) VALUES(:permision,:username,:companyname,:confirmpassword)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username',$username,PDO::PARAM_STR);
            $query->bindParam(':companyname',$companyname,PDO::PARAM_STR);
            $query->bindParam(':permision',$permision,PDO::PARAM_STR);
            $query->bindParam(':confirmpassword',$confirmpassword,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId)
            {
                $regno=$_POST['regno'];
                $companyname=$_POST['companyname'];
                $companyemail=$_POST['companyemail'];
                $country=$_POST['country'];
                $sql="INSERT INTO  tblcompany(regno,companyname,companyemail,country) VALUES(:regno,:companyname,:companyemail,:country)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':regno',$regno,PDO::PARAM_STR);
                $query->bindParam(':companyname',$companyname,PDO::PARAM_STR);
                $query->bindParam(':companyemail',$companyemail,PDO::PARAM_STR);
                $query->bindParam(':country',$country,PDO::PARAM_STR);
                $query->execute();
                $lastInsertId2 = $dbh->lastInsertId();
                if($lastInsertId2)
                {
                    echo "<script>alert('Registo realizado com sucesso. você receberá um e-mail quando sua conta for aprovada');</script>";
                }
                else
                {
                    echo "<script>alert('Algo deu errado. Por favor, tente novamente');</script>";

                }
            }
            else 
            {
                echo "<script>alert('Algo deu errado. Por favor, tente novamente');</script>";
            }
        }
    }
}


?>
<script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'companyname='+$("#companyname").val(),
            type: "POST",
            success:function(data){
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
</script>
<script>
    function checkAvailability2() 
    {
        $("#loaderIcon").show();
        jQuery.ajax(
        {
            url: "check_availability.php",
            data:'fullname='+$("#fullname").val(),
            type: "POST",
            success:function(data)
            {
                $("#user-availability-status2").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
</script>
<script type="text/javascript">
    function valid()
    {
        if(document.signup.password.value!= document.signup.confirmpassword.value)
        {
            alert("Senha e campo Confirmar senha não coincidem !!");
            document.signup.confirmpassword.focus();
            return false;
        }
        return true;
    }
</script>

<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo" align="center">
                                <img class="img-avatar" src="profileimages/compconsult2.jpg" alt="">
                            </div>
                            <h6 class="font-weight-light">A inscrição é fácil. Leva apenas alguns passos</h6>
                            <form class="pt-3" method="post" enctype="multipart/form-data" name="signup" onSubmit="return valid();">
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control form-control-lg" name="fullname" id="fullname" onBlur="checkAvailability2()" placeholder="Username" required>
                                    <span id="user-availability-status2" style="font-size:12px;"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control form-control-lg" name="companyname" id="companyname" onBlur="checkAvailability()" placeholder="Company Name" required>
                                    <span id="user-availability-status" style="font-size:12px;"></span> 
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control form-control-lg" name="regno" id="regno" placeholder="Company Reg No." required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" class="form-control form-control-lg" name="companyemail" id="companyemail" placeholder=" Company Email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <select class="form-control form-control-lg" name="country" id="country" required>
                                        <option>País</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Tanzania">Tanzania</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="United States of America">United States of America</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="India">India</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Argentina">Argentina</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="password" class="form-control form-control-lg" name="password" id="password" placeholder="Password" required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="confirmpassword" class="form-control form-control-lg" id="confirmpassword" placeholder=" confirm password" required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" required> Eu concordo com todos os Termos e Condições
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" name="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">Cadastrar</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                já tem uma conta?
                                    <a href="index.php" class="text-primary">
                                        Login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
</body>
</html>