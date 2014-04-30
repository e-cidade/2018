<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once ("libs/db_stdlib.php");
  require_once ("libs/db_conecta.php");
  require_once ("libs/db_sessoes.php");
  require_once ("libs/db_usuariosonline.php");
  require_once ("dbforms/db_funcoes.php");
  require_once ("libs/db_app.utils.php");
  require_once ("classes/db_aguahidromatric_classe.php");

  $cl_aguahidromatric = new cl_aguahidromatric();

  db_postmemory($HTTP_POST_VARS);
  $db_opcao = 1;
  $db_botao = true;
  $sqlerro = false;

  if(isset($incluir)){
  	
  	db_inicio_transacao();

    $cl_aguahidromatric->x04_codhidrometro = $x04_codhidrometro_origem;
    
    $cl_aguahidromatric->x04_matric = $x04_matric_destino;
    
    $cl_aguahidromatric->alterar($x04_codhidrometro_origem);

    if ( $cl_aguahidromatric->erro_status == 0 ) {
    	 
    	$erro_msg = $cl_aguahidromatric->erro_msg;
    	$sqlerro = true;
    	
    }else{
    	
  		require_once("classes/db_histocorrencia_classe.php");
  		require_once("classes/db_histocorrenciamatric_classe.php");
  	
  		$clhistocorrencia                    = new cl_histocorrencia;
  	
  		$sOcorrencia                         = "Transferência do hidrômetro x04_codhidrometro {$x04_codhidrometro_origem}";
  		$sOcorrencia                        .= " da matrícula {$x04_matric_origem} para {$x04_matric_destino}.";
  	
  		$clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
  		$clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
  		$clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
  		$clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
  		$clhistocorrencia->ar23_descricao    = "Transferência de hidrômetro";
  		$clhistocorrencia->ar23_ocorrencia   = $sOcorrencia;
  		$clhistocorrencia->ar23_tipo         = 1;
  		$clhistocorrencia->ar23_hora         = date("H:i");
  		$clhistocorrencia->ar23_data         = date("d")."/".date("m")."/".date("Y");
  	
  		$clhistocorrencia->incluir(null);
  	
  		if ( $clhistocorrencia->erro_status == 0 ) {
  			 
  			$erro_msg = $clhistocorrencia->erro_msg;
  			$sqlerro = true;
  	
  		} else {
  	
  			$clhistocorrenciamatricorigem = new cl_histocorrenciamatric;
  	
  			$clhistocorrenciamatricorigem->ar25_matric         = $x04_matric_origem;
  			$clhistocorrenciamatricorigem->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;
  	
  			$clhistocorrenciamatricorigem->incluir(null);
  	
  			if ( $clhistocorrenciamatricorigem->erro_status == 0 ) {
  				 
  				$erro_msg = $clhistocorrenciamatricorigem->erro_msg;
  				$sqlerro = true;
  				
  			}else{

  			  $clhistocorrenciamatricdestino = new cl_histocorrenciamatric;
  	      
  			  $clhistocorrenciamatricdestino->ar25_matric         = $x04_matric_destino;
  			  $clhistocorrenciamatricdestino->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;
  	      
  			  $clhistocorrenciamatricdestino->incluir(null);
  	      
  			  if ( $clhistocorrenciamatricdestino->erro_status == 0 ) {
  			  	 
  			  	$erro_msg = $clhistocorrenciamatricdestino->erro_msg;
  			  	$sqlerro = true;
  			  	
  			  }
  				
  			}
  	
  		}     
    	
    }
    
    db_fim_transacao($sqlerro);

    if ($sqlerro == true) {
    	
      db_msgbox($erro_msg);
    	
    }else{
    	
      echo('<script>alert("Transferência de hidrômetro da matrícula '.$x04_matric_origem.' para '.$x04_matric_destino.' efetuada com sucesso.");</script>');
    	
    }
    
    
  }
?>
<html>
<head>
<?php
  db_app::load('estilos.css');
?>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top: 50px;">
    <tr>
      <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
        <center>
          <?php include("forms/db_frmaguahidrometrotransferencia.php"); ?>
        </center>
      </td>
    </tr>
  </table>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<?php
  
  if(isset($incluir) && !$sqlerro){
  	
    echo "<script>js_limpa_formulario();</script>";
      	
  };
  
?>