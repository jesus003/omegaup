<?php 

class OmegaupComponentPage extends StdComponentPage{

	
	private $user_html_menu;

	function __construct()
	{

		$this->doGetRequests();

		parent::__construct();

		$this->user_html_menu = ""; 

		$this->createUserMenu();

	}//__construct()





	private function doGetRequests(){

		/**
		  *
		  * GET Requests
		  **/
		if(isset($_GET["request"])){
			switch($_GET["request"]){
				case "logout" :
					LoginController::logOut();
					die(header("Location: ."));
				break;

			}			
		}


		/**
		  *
		  * POST Requests
		  **/
		if(isset($_POST["request"])){

			switch($_POST["request"]){
				case "login" :

					if( LoginController::testUserCredentials(  $_POST["user"], $_POST["pass"]  ) ){
						//login correcto
						
						LoginController::login( $_POST["user"], null );
						$this_user = LoginController::getCurrentUser();
						die(header("Location: profile.php?id=" . $this_user->getUserId()));
					}else{
						//login incorrecto
						Logger::log("nope");

					}

					
				break;
			}			

		}		



	}





	private function createUserMenu()
	{

		
		if(LoginController::isLoggedIn()){
			//user is NOT logged in
			
			$this_user = LoginController::getCurrentUser();
			$this->user_html_menu = '<img src="http://www.gravatar.com/avatar/'. md5($this_user->getUsername())  .'?s=16&amp;d=identicon&amp;r=PG"  >';
			$this->user_html_menu .= ' Hola <a href="profile.php?id='.$this_user->getUserId()  .'">' . $this_user->getUsername()  .'</a>&nbsp;';

			/**
			 *
			 * Test if user is admin 
			 **/
			$test_admin = UserRolesDAO::getByPK( $this_user->getUserId(), 1 );
			
			if(!is_null($test_admin)){
				//he is admin !
				$this->user_html_menu .= "| <a href='admin'>Administrar Omegaup</a>&nbsp;";					
			}


			$this->user_html_menu .= "| <a href='?request=logout'>Cerrar Sesion</a>&nbsp;";	
			return;
		}

		
		//user is not logged in
		$this->user_html_menu = "Bienvenido a Omegaup ! ";
		$this->user_html_menu .= "<a href='nativeLogin.php'>Inicia sesion !</a>";

	}








	/**
      * End page creation and ask for login
      * optionally sending a message to user
	  **/
	private function dieWithLogin($message = null)
	{
		$login_cmp = new LoginComponent();

		if( $message != null )
		{
			self::addComponent(new MessageComponent($message));				
		}

		self::addComponent($login_cmp);
		parent::render();
		exit();
	}





	/**
	  *
	  *
	  **/
	function render()
	{
		
		?>
		<!DOCTYPE html> 
		<html xmlns="http://www.w3.org/1999/xhtml "
		      xmlns:fb="http://www.facebook.com/2008/fbml ">

			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 

			
			<link rel="stylesheet" type="text/css" href="css/style.css">

			</head>
			
			<body>
			<div id="wrapper">
				<div class="login_bar" style="display: block">
				<?php echo $this->user_html_menu; ?>
				</div> 
				<div id="title">
					<div style="margin-left: 40%;"><img src="media/omegaup_curves.png"></div>
				</div>
			    
				<div id="content">
					
					
					<div class="post footer">
								<ul >
									<li><a href='probs.php'>Problemas</a></li>
									<li><a href='rank.php'>Ranking</a></li>
									<li><a href='recent.php'>Actividad reciente</a></li>
									<li><a href='faq.php'>FAQ</a></li>
									<li><a href='contests.php'>Concursos</a></li>
									<li><a href='schools.php'>Escuelas</a></li>
									<li><a href='help.php'>Colabora</a></li>
									<li><input type='text' placeholder='Buscar'></li>
								</ul>
					</div>

					<div class="post">
						<div class="copy">
							 <?php
							 /* ----------------------------------------------------------------------
										CONTENIDO
							 ---------------------------------------------------------------------- */
								foreach( $this->components as $cmp ){
									echo $cmp->renderCmp();
								}
							 ?>
						</div>
						<!-- .copy -->
					</div>
					<!-- .post -->
				
				    <div class="post footer">
				        <ul>
				            <li class="first"><a href=""></a></li>
				        </ul>
				        <p><?php //echo $GUI::getFooter(); ?></p>
				    </div>
					<!-- .post footer -->

					<div class="bottom"></div>

				</div>
				<!-- #content -->

			</div>
			<!-- #wrapper -->
			
			<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
			<script type="text/javascript" src="js/omegaup.js"></script>
			</body>
		</html>
		<?php
	}

}



