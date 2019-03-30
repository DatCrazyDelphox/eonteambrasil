<?php
include("conexao.php");
include_once("functions.php");
if(ProtegePag() == true){
global $banco;
?>

	<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li class="active">Seja Bem Vindo</li>
                </ul>
                <!-- END BREADCRUMB -->  
                
                <!-- PAGE TITLE -->
          <div class="page-title">                    
          <h2><span class="fa fa-home"></span> Seja Bem Vindo</h2>
          </div>
                <!-- END PAGE TITLE --> 
                
                <?php
				if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
				?>
                <div class="col-md-12">
                        <?php
						$UserOnline = $_SESSION['login'];
                        $SQLUrlT = "SELECT status, tempo, cemail, email FROM urlteste WHERE CadUser = :CadUser";
						$SQLUrlT = $banco->prepare($SQLUrlT);
						$SQLUrlT->bindParam(':CadUser', $UserOnline, PDO::PARAM_STR);
						$SQLUrlT->execute();
						$TotalUrlT = count($SQLUrlT->fetchAll());
                        
                        echo "<div class=\"panel panel-default\">
                                <div class=\"panel-heading\">
                                    <h3 class=\"panel-title\">Url de Teste</h3>
                                </div>
                                <div class=\"panel-body\">";
                                
								if($TotalUrlT > 0){
									$UrlTeste = UrlTeste(1);
									echo "<div class=\"col-md-9\"><input type=\"text\" class=\"form-control\" value=\"".$UrlTeste."\"></div>";
									echo "<div class=\"col-md-3\"><a target=\"_blank\" href=\"".$UrlTeste."\" class=\"btn btn-default\"><i class=\"fa fa-link\"></i> Clique Aqui</a></div>";
								}
								else{
									echo "Teste não configurado. Para configurar, clique em <b>Configurações</b>.";
								}
								                                    
                                echo "</div>      
                                <div class=\"panel-footer\">";
								
								$ColorUrl = $TotalUrlT > 0 ? "info" : "danger";
								
                                    echo "<a class=\"btn btn-".$ColorUrl." pull-right\" Onclick=\"ConfigTeste()\">Configurações</a>";
									
                                echo "</div>                            
                            </div>";
                            
                         ?>
                            
                        </div>
                            
                
                <?php
				}
				
				$userCota = $_SESSION['id'];
				$VerificarLimiteTeste = VerificarLimiteTeste($userCota);
				$VerificarLimiteTeste = $VerificarLimiteTeste == 0 ? "Ilimitado" : $VerificarLimiteTeste;
				$VerificarCotaTeste = VerificarCotaTeste($userCota);
				
				$CotaTesteDisponivel = $VerificarLimiteTeste - $VerificarCotaTeste;
				
				if($VerificarLimiteTeste == 0){
						$LimiteTesteCota = "Ilimitado";	
				}
				elseif($CotaTesteDisponivel > 0){
						$LimiteTesteCota = $CotaTesteDisponivel;	
				}
				else{
						$LimiteTesteCota = "Esgotado";
				}
				
				
				if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
				?>

						<div class="col-md-4">
                            <!-- START WIDGET MESSAGES -->
                            <div class="pointer widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-info"></span>
                                </div>                             
                                <div class="widget-data">
                                <div class="widget-int num-count" style="font-size:25px;">Créditos Disponíveis</div>
                                    <div class="widget-title" style="font-size:20px;"><?php echo $CotaRev; ?></div>
                                </div>      
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                        </div>
                        
                        <div class="col-md-4">
                            <!-- START WIDGET MESSAGES -->
                            <div class="pointer widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-user"></span>
                                </div>                             
                                <div class="widget-data">
                                <div class="widget-int num-count" style="font-size:25px;">Limite Teste</div>
                                    <div class="widget-title" style="font-size:12px;"><?php echo $VerificarLimiteTeste; ?></div>
                                </div>      
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                        </div>
                        
                        <div class="col-md-4">
                            <!-- START WIDGET MESSAGES -->
                            <div class="pointer widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-user"></span>
                                </div>                             
                                <div class="widget-data">
                                <div class="widget-int num-count" style="font-size:25px;">Teste Disponível</div>
                                    <div class="widget-title" style="font-size:12px;"><?php echo $LimiteTesteCota; ?></div>
                                </div>      
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                        </div>
          
                <?php
				}
				
				
				if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
					//Servidor
					$SQLArq = "SELECT * FROM arquivo";
					$SQLArq = $banco->prepare($SQLArq);
					$SQLArq->execute();
				}
				else{
					//Servidor
					$operadora = $_SESSION['operadora'];
					$OpTodos = "Todos";
					$SQLArq = "SELECT * FROM arquivo WHERE operadora = :operadora OR operadora = :OpTodos";
					$SQLArq = $banco->prepare($SQLArq);
					$SQLArq->bindParam(':operadora', $operadora, PDO::PARAM_STR);
					$SQLArq->bindParam(':OpTodos', $OpTodos, PDO::PARAM_STR);
					$SQLArq->execute();
				}
				
				while($LnArq = $SQLArq->fetch()){
					
				$apn = empty($LnArq['apn']) ? "" : "<p><b><font color=\"#FF0000\">APN:</font></b> ".$LnArq['apn']."</p>";
				
					$SQLImagem = "SELECT imagem FROM imagem_perfil WHERE id = :id";
					$SQLImagem = $banco->prepare($SQLImagem);
					$SQLImagem->bindParam(':id', $LnArq['imagem'], PDO::PARAM_STR);
					$SQLImagem->execute();
					$LnImagem = $SQLImagem->fetch();
					$img = "<img src=\"img/perfil/".$LnImagem['imagem']."\" height=\"83\" width=\"254\" title=\"".$LnArq['nome']."\">";
					
					$UrlArq = empty($LnArq['file']) ? $LnArq['url'] : UrlAtual()."download/".$LnArq['file'];
					
				echo "<div class=\"col-md-4\">

                            <!-- NEWS WIDGET -->
                            <div class=\"panel panel-".$LnArq['tipo']."\">
                                <div class=\"panel-heading\">
                                    <h3 class=\"panel-title\">".$LnArq['nome']."</h3>         
                                </div>
                                <div class=\"panel-body scroll\" style=\"height: 230px;\">  
                                        
                                    <center>".$img."</center>
                                    <br /><br />
									<h6>".$LnArq['titulo']."</h6>
                                    <p>".$LnArq['descricao']."</p>
									".$apn."
                                                                                           
                              </div>
                                
                                <div class=\"panel-footer\"> 
                                  <a target=\"_blank\" href=\"".$UrlArq."\" class=\"btn btn-".$LnArq['tipo']." btn-block\">".$LnArq['botao']."</a>
                                </div>
                                
                            </div>
                            <!-- END NEWS WIDGET -->

                        </div> ";
					
				}
				
				
				
				
				
				?>
			<div class="col-md-4">
                            <!-- NEWS WIDGET -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Aplicativo de VPN para Android</h3>         
                                </div>
                                <div class="panel-body scroll mCustomScrollbar _mCS_2 mCS-autoHide" style="height: 230px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">  
                                        
                                    <center><img src="/SharedAssets/img/x1.png" height="85" width="80" title="Isto é um logo." class="mCS_img_loaded"></center>
                                    <br><br>
									<h6>Baixe o app de VPNs do grupo Eon Team Brasil</h6>
									<p>Este aplicativo é de uso exclusivo para Android e TV BOX</p>
                                                                                           
                              </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; height: 0px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
                                
                                <div class="panel-footer"> 
                                  <a target="_blank" href="/SharedAssets/EonTeamBrasil.apk" class="btn btn-success btn-block">Baixar APK</a>
                                </div>
                                
                            </div>
                            <!-- END NEWS WIDGET -->
                        </div> <div class="col-md-4">

                            <!-- NEWS WIDGET -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Arquivo VPN para computador</h3>         
                                </div>
                                <div class="panel-body scroll mCustomScrollbar _mCS_2 mCS-autoHide" style="height: 230px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">  
                                        
                                    <center><img src="/SharedAssets/img/x2.png" height="85" width="80" title="Isto é um logo." class="mCS_img_loaded"></center>
                                    <br><br>
									<h6>Baixe o arquivo VPN para computador do grupo Eon Team Brasil</h6>
									<p>Este arquivo é de uso exclusivo para PC.</p>
                                                                                           
                              </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; height: 0px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
                                
                                <div class="panel-footer"> 
                                  <a target="_blank" href="/SharedAssets/codes.zip" class="btn btn-success btn-block">Baixar arquivo de VPN para PC</a>
								  <a target="_blank" href="https://swupdate.openvpn.org/community/releases/openvpn-install-2.4.6-I602.exe" class="btn btn-success btn-block">Baixar o Open VPN</a>
                                </div>
                                
                            </div>
                            <!-- END NEWS WIDGET -->
							</div> <div class="col-md-4">

                            <!-- NEWS WIDGET -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Aplicativo Ki4a VPN</h3>         
                                </div>
                                <div class="panel-body scroll mCustomScrollbar _mCS_2 mCS-autoHide" style="height: 230px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">  
                                        
                                    <center><img src="/SharedAssets/img/x3.png" height="80" width="80" title="Isto é um logo." class="mCS_img_loaded"></center>
                                    <br><br>
									<h6>Baixe o arquivo Ki4a para Android e TV BOX</h6>
									<p>App Disponível na Play Store</p>
                              </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; height: 0px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
                                
                                <div class="panel-footer"> 
                                  <a target="_blank" href="https://play.google.com/store/apps/details?id=com.staf621.ki4a&hl=pt-br" class="btn btn-success btn-block">Baixar</a>
                                </div>
                                
                            </div> 
                            </div>
                            <div class="col-md-4">
  
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">APP VPN Alternativo para TV BOX</h3>         
                                </div>
                                <div class="panel-body scroll mCustomScrollbar _mCS_2 mCS-autoHide" style="height: 230px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">  
                                        
                                    <center><img src="https://lh3.googleusercontent.com/O8qu-o0uaswqFu5pIZ-JZfK2AZ-bXeB1rpLQpbyz7BrRDWfzyUsW_wpGeuCXJTLobw=s180-rw" height="85" width="80" title="Isto é um logo." class="mCS_img_loaded"></center>
                                    <br><br>					
                                    <h6>App para caso o App oficial Eon Team Brasil acabar não funcionando corretamente em TV BOXes</h6>
                                    <p>Lembre-se de que só é recomendado o uso desse aplicativo caso o APP oficial não funcionar em uma Set-top box (TV BOX)</p>
                                                               
                                     </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; height: 0px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
    
                                      <div class="panel-footer"> 
                                      <a target="_blank" href="/SharedAssets/codes.zip" class="btn btn-success btn-block">Baixar arquivo de VPN</a>
                                        <a target="_blank" href="https://play.google.com/store/apps/details?id=it.colucciweb.free.openvpn" class="btn btn-success btn-block">Baixar APP</a>
                                </div>
    
                                      </div>
                                      </div>
                                      
                                      
               <!-- END NEWS WIDGET -->



		<div id="StatusGeral"></div>        
<!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>  
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>  
        <script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>  
                <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/DataTables-br.js"></script>  
        
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END TEMPLATE -->
        
        <?php
		if( ($_SESSION['acesso'] == 1) || ($_SESSION['acesso'] == 2)){
		?>
        <script type='text/javascript'>
		function ConfigTeste(){
			
			panel_refresh($(".page-container"));
			
			$.post('ScriptModalTesteConfig.php', function(resposta) {
				
				setTimeout(panel_refresh($(".page-container")),500);
				
				$("#StatusGeral").html('');
				$("#StatusGeral").html(resposta);
			});	
		}
		</script>
        <?php
		}
		?>
       
    <!-- END SCRIPTS -->    
<?php
}else{
	echo Redirecionar('login.php');
}	
?>