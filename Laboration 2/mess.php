<?php
	require_once("get.php");
	require_once("sec.php");
	checkUser();
?>
<!DOCTYPE html>
<html lang="sv">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="css/screen.css" media="screen"/>
    <link rel="stylesheet" href="css/lightbox.css" media="screen"/>
    <link rel="stylesheet" href="css/messageStyle.css">
    
	<title>Messy Labbage</title>

	<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	  </head>

	  <body>
	    <div class="container">
			<div class="header">
			        <ul class="nav nav-pills pull-right">
			             <li><button class="btn" id="logout">Logga ut</button></li>
			        </ul>
			        <h3 class="text-muted">Messy Labbage</h3>
			      </div>
			<!-- page header -->
			<div class="jumbotron">
	      	  
	        	  <h1>Messy Labbage</h1>
	        	  <p class="lead">Software developed by the MakeMyPageBetterPlease Company</p>
	      	 </div>
			<div class="image-row">
			
			<!-- This holds all the links -->
			<div class="row">
			<div class="col-md-6">
			<?php
			/* Produces all the links to the producers */
				require_once("get.php");
				$ps = getProducers();
				
				foreach($ps as $p) {
					echo('<a onclick="changeProducer(' .$p["producerID"] .');" href="#mess_anchor">' .$p["name"] .'</a><br />');	
				}
			?>
			</div>
			<div class="col-md-6">
				<img src="pics/food.jpg" height="220px"/>
			</div>
			</div>
			<div class="clear"></div>

			<!-- This is the part that will be populated with data from AJAX -->	
			<div id="mess_anchor"></div>	
			<div id="mess_container">
				<div class="row">
					<!-- Headline will be updated here -->
		  	     	<div class="col-md-6" height="250px">
						<h1 id="mess_p_headline"></h1>
		  	     		<p id="mess_p_kontakt"></p>
		  	  			<a id="p_img_link" class="example-image-link" href="" data-lightbox="example-set" title="">
		  					<img id="p_img"  class="example-image" src="" alt="" width="100" height="100"/>
		  				</a>
		  	     	</div>
		  	     	
	        		<div class="col-md-6">
				<p>Skriv ditt meddelande så dyker det upp i listan</p>
				<input id="mess_inputs" type="hidden" value="" />
				Namn: <br /><input id="name_txt" type="text" name="name" value="<?php echo $_SESSION['user']; ?>" /><br />
				Meddelande: <br /><textarea id="message_ta" cols="50" rows="5" name="message"></textarea><br /><br />
				<button id="add_btn" class="btn btn-primary"> Skicka ditt meddelande</button>
				</div>
				<div class="col-md-6">
				<strong>Meddelanden:</strong><br />
				<div id="mess_p_mess">
					<!-- Här populeras meddelandena -->
				</div>
			</div>
			
	  	</div><!-- mess_container -->

	    </div> <!-- /container -->
	    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/lightbox-min.js"></script>
		<script type="text/javascript" src="js/modernizr.custom-min.js"></script>
		
		<!-- This script is running to get the data for the producers -->
		<script type="text/javascript" src="js/message.js"></script>
			
	  </body>
	</html>