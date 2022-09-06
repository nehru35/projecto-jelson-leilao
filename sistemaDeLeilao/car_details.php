<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['submit']))
{
  $fromdate=$_POST['fromdate'];
  $todate=$_POST['todate']; 
  $message=$_POST['message'];
  $useremail=$_SESSION['login'];
  $status=0;
  $vhid=$_GET['vhid'];
  $bookingno=mt_rand(100000000, 999999999);
  $ret="SELECT * FROM tblbooking where (:fromdate BETWEEN date(FromDate) and date(ToDate) || :todate BETWEEN date(FromDate) and date(ToDate) || date(FromDate) BETWEEN :fromdate and :todate) and VehicleId=:vhid";
  $query1 = $dbh -> prepare($ret);
  $query1->bindParam(':vhid',$vhid, PDO::PARAM_STR);
  $query1->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
  $query1->bindParam(':todate',$todate,PDO::PARAM_STR);
  $query1->execute();
  $results1=$query1->fetchAll(PDO::FETCH_OBJ);

  if($query1->rowCount()==0)
  {

    $sql="INSERT INTO  tblbooking(userEmail,VehicleId,FromDate,ToDate,message,Status,BookingNumber) VALUES(:useremail,:vhid,:fromdate,:todate,:message,:status,:bookingno)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
    $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
    $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
    $query->bindParam(':todate',$todate,PDO::PARAM_STR);
    $query->bindParam(':message',$message,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->bindParam(':bookingno',$bookingno,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
      echo "<script>alert('Oferta feita com sucesso com sucesso.');</script>";
      echo "<script type='text/javascript'> document.location = 'my_booking.php'; </script>";
    }
    else 
    {
      echo "<script>alert('Algo deu errado. Por favor, tente novamente');</script>";
      echo "<script type='text/javascript'> document.location = 'car_list.php'; </script>";
    } 
  }  else{
   echo "<script>alert('Já tem oferta aceite');</script>"; 
   echo "<script type='text/javascript'> document.location = 'car_list.php'; </script>";
 }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <title>Detalhes do carro</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <meta content="Author" name="WebThemez">
  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet"> 
</head>

<body id="body"> 
 <?php include('includes/header.php');?>
 <section id="innerBanner"> 
  <div class="inner-content">
    <h2><span>DETALHES DO CARRO</span><br>Carros em perfeitas condições</h2>
    <div> 
    </div>
  </div> 
</section><!-- #Page Banner -->

<main id="main">
    <!--==========================
      Clients Section
      ============================-->
      <section id="clients"  class="wow fadeInUp">
        <div class="container">
          <div class="section-header">
            <h2>Detalhes do carro</h2>
            <p>Carro limpo,carece de alguns acertos básicos, como bancos, forros, ar condicionado, faróis, piscas, e sem problemas mecânicos graves.</p>
          </div>
          <?php 
          $vhid=intval($_GET['vhid']);
          $sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid  from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:vhid";
          $query = $dbh -> prepare($sql);
          $query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
          $query->execute();
          $results=$query->fetchAll(PDO::FETCH_OBJ);
          $cnt=1;
          if($query->rowCount() > 0)
          {
            foreach($results as $result)
            {  
              $_SESSION['brndid']=$result->bid;  
              ?>  
              <div class="owl-carousel clients-carousel">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="" style="height: 150px; width:300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" alt="" style="height: 150px; width: 300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" alt="" style="height: 150px; width: 300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage4);?>" alt="" style="height: 150px; width: 300px;">
              </div>
            </div>
          </section><!-- #clients -->

          <!--Listing-detail-->
          <section class="listing-detail">
            <div class="container">
              <div class="listing_detail_head row">
                <div class="col-md-9">
                  <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
                </div>
                <div class="col-md-3">
                  <div class="price_info">
                    <p>Kzs<?php echo htmlentities($result->PricePerDay);?> </p>Valor inicial

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-9">
                  <div class="main_features">
                    <ul>

                      <li> <i class="fa fa-calendar" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->ModelYear);?></h5>
                        <p>Ano</p>
                      </li>
                      <li> <i class="fa fa-cogs" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->FuelType);?></h5>
                        <p>Tipo de combustível</p>
                      </li>

                      <li> <i class="fa fa-user-plus" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->SeatingCapacity);?></h5>
                        <p>Assentos</p>
                      </li>
                    </ul>
                  </div>
                  <div class="listing_more_info">
                    <div class="listing_detail_wrap"> 
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs gray-bg" role="tablist">
                        <li role="presentation" class="active"><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab" style="background-color: #49a3ff;" data-toggle="tab">Visão geral do veículo </a></li>

                        <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Accessórios</a></li>
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content"> 
                        <!-- vehicle-overview -->
                        <div role="tabpanel" class="tab-pane active" id="vehicle-overview">

                          <p><?php echo htmlentities($result->VehiclesOverview);?></p>
                        </div>


                        <!-- Accessories -->
                        <div role="tabpanel" class="tab-pane" id="accessories"> 
                          <!--Accessories-->
                          <table>
                            <thead>
                              <tr>
                                <th colspan="2">Accessórios</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>AC</td>
                                <?php if($result->AirConditioner==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                  <?php 
                                } else { ?> 
                                 <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                 <?php 
                               } ?> </tr>

                               <tr>
                                <td>Sistema de frenagem antitravamento</td>
                                <?php if($result->AntiLockBrakingSystem==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else {?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Direcção hidráulica</td>
                                <?php if($result->PowerSteering==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>


                              <tr>

                                <td>Janelas elétricas</td>

                                <?php if($result->PowerWindows==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Leitor de CD</td>
                                <?php if($result->CDPlayer==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Assentos de couro</td>
                                <?php if($result->LeatherSeats==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Travamento central</td>
                                <?php if($result->CentralLocking==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Fechaduras elétricas das portas</td>
                                <?php if($result->PowerDoorLocks==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>
                              <tr>
                                <td>Assistente de freio</td>
                                <?php if($result->BrakeAssist==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php  } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Airbag do motorista</td>
                                <?php if($result->DriverAirbag==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                               <td>Air Bag do passageiro</td>
                               <?php if($result->PassengerAirbag==1)
                               {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else {?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Sensor de colisão</td>
                              <?php if($result->CrashSensor==1)
                              {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php 
                              } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php
                              } ?>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                </div>
                <?php 
              }
            } ?>

          </div>

          <!--Side-Bar-->
          <aside class="col-md-3">

            <div class="share_vehicle">
              <p>Compartilhar: <a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a> </p>
            </div>
            <div class="sidebar_widget">
              <div class="widget_heading">
                <h5><i class="fa fa-envelope" aria-hidden="true"></i>Ofertas agora</h5>
              </div>
              <form method="post">
                <div class="form-group">
                  <label>Da data:</label>
                  <input type="date" class="form-control" name="fromdate" placeholder="From Date" required>
                </div>
                <div class="form-group">
                  <label>Até a presente data:</label>
                  <input type="date" class="form-control" name="todate" placeholder="To Date" required>
                </div>
                <div class="form-group">
                  <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
                </div>
                <?php if($_SESSION['login'])
                {?>
                  <div class="form-group">
                    <input type="submit" class="btn" style="background-color: #49a3ff;"  name="submit" value="Enviar Agora">
                  </div>
                  <?php 
                } else { ?>
                  <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal" style="background-color: #49a3ff;">Entrar com oferta</a>

                  <?php 
                } ?>
              </form>
            </div>
          </aside>
          <!--/Side-Bar--> 
        </div>

        <div class="space-20"></div>
        <div class="divider"></div>

        <!--Similar-Cars-->
        <div class="similar_cars">
          <h3>Carros semelhantes</h3>
          <div class="row">
            <?php 
            $bid=$_SESSION['brndid'];
            $sql="SELECT tblvehicles.VehiclesTitle,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id,tblvehicles.SeatingCapacity,tblvehicles.VehiclesOverview,tblvehicles.Vimage1 from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.VehiclesBrand=:bid";
            $query = $dbh -> prepare($sql);
            $query->bindParam(':bid',$bid, PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            {
              foreach($results as $result)
              { 
                ?>      
                <div class="col-md-3 grid_listing">
                  <div class="product-listing-m gray-bg">
                    <div class="product-listing-img"> <a href="car_details.php?vhid=<?php echo htmlentities($result->id);?>"><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" style="height: 200px; width: 360px;" alt="image" /> </a>
                    </div>
                    <div class="product-listing-content">
                      <h5><a href="car_details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
                      <p class="list-price">Kzs<?php echo htmlentities($result->PricePerDay);?></p>

                      <ul class="features_list">

                       <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> assentos</li>
                       <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> modelo</li>
                       <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
                     </ul>
                   </div>
                 </div>
               </div>
               <?php 
             }
           } ?>       

         </div>
       </div>
       <!--/Similar-Cars--> 

     </div>
   </section>
   <!--/Listing-detail--> 

    <!--==========================
      Call To Action Section
      ============================-->
      <section id="call-to-action" class="wow fadeInUp">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 text-center text-lg-left">
              <h3 class="cta-title">Faça sua oferta</h3>
              <p class="cta-text">Faça uma boa escolha de lote e bons remates para poder alcançar o sonho de ter o carro próprio.</p>
            </div>
            <div class="col-lg-3 cta-btn-container text-center">
              <a class="cta-btn align-middle" href="contact.php">Contacte-nos</a>
            </div>
          </div>

        </div>
      </section><!-- #call-to-action -->




    </main>

  <!--==========================
    Footer
    ============================-->
    <?php include('includes/footer.php');?><!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <!--Login-Form -->
    <?php include('includes/login.php');?>
    <!--/Login-Form --> 

    <!--Register-Form -->
    <?php include('includes/registration.php');?>

    <!--/Register-Form --> 

    <!--Forgot-password-Form -->
    <?php include('includes/forgotpassword.php');?>

    <!-- JavaScript  -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/superfish/hoverIntent.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/magnific-popup/magnific-popup.min.js"></script>
    <script src="lib/sticky/sticky.js"></script> 
    <script src="contact/jqBootstrapValidation.js"></script>
    <script src="contact/contact_me.js"></script>
    <script src="js/main.js"></script>

  </body>
  </html>
