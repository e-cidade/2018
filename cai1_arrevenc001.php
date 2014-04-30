<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_arrevenc_classe.php");
include("classes/db_arrevenclog_classe.php");
include("classes/db_arreinstit_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clarrevenc     = new cl_arrevenc;
$clarrevenclog  = new cl_arrevenclog;
$clarreinstit   = new cl_arreinstit;
$db_opcao = 1;
$db_botao = true;
echo " <br>incluir<br>";
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  $sqlerro = false;
  db_inicio_transacao();   
  $clarreinstit->sql_record($clarreinstit->sql_query_file(null,"*",null,"k00_numpre = {$k00_numpre} and k00_instit = ".db_getsession('DB_instit') ) ); 
  if ($clarreinstit->numrows == 0) {
    $msgerro = "Numpre de outra instituição inclusão abortada";
    $sqlerro = true;
  }else{
  	// varifica se ja tem prorrogação para este periodo
		 $dataini =   implode("-",array_reverse(explode("/",$k00_dtini)));
		 $datafim =   implode("-",array_reverse(explode("/",$k00_dtfim)));
		
		
		$sqlPeriodo = "select * from arrevenc 
		                 where k00_numpre = {$k00_numpre}
										   and k00_numpar = {$k00_numpar}
											 and (k00_dtini,k00_dtfim) overlaps ( DATE '{$dataini}' - '1 day'::interval, DATE '{$datafim}' + '1 day'::interval)"; 
		
		$rsPeriodo = pg_query($sqlPeriodo);
		$linhasPeriodo = pg_num_rows($rsPeriodo);
		 if($linhasPeriodo > 0){
		 	// não pode incluir
			$sqlerro = true;
			$msgerro = "Ja existe uma prorrogação neste período para este Numpre e Parcela ";
		 }
		 
		if($sqlerro == false){
	  	if($k75_sequencial == ""){
	  		$clarrevenclog->k75_instit = db_getsession("DB_instit");
	  	  $clarrevenclog->incluir(null);
				if($clarrevenclog->erro_status=="0"){
			      	$sqlerro = true;
							$msgerro = $clarrevenclog->erro_msg;
						}
				$k75_sequencial = $clarrevenclog-> k75_sequencial;
	  	}
	  	
			if($k00_numpar == 0 ){
				$sqlPar = "select distinct k00_numpar as parc from arrecad where k00_numpre = {$k00_numpre}";
				$rsPar  = pg_query($sqlPar);
				$linhaPar = pg_num_rows($rsPar);
				if($linhaPar>0){
					for($i=0;$i<$linhaPar;$i++){
						db_fieldsmemory($rsPar,$i);
						$clarrevenc->k00_arrevenclog = $k75_sequencial;
						$clarrevenc->k00_numpar = $parc ;
	          $clarrevenc->incluir(null);
			      if($clarrevenc->erro_status=="0"){
			      	$sqlerro = true;
							$msgerro = $clarrevenc->erro_msg;
						}
					}
				}
			}else{
				$clarrevenc->k00_arrevenclog = $k75_sequencial;
	      $clarrevenc->incluir(null);
				if($clarrevenc->erro_status=="0"){
			      	$sqlerro = true;
							$msgerro = $clarrevenc->erro_msg;
						}
				
			}
		
    }
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	
	include("forms/db_frmarrevenc.php");

	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
 if($sqlerro == true){
   db_msgbox($msgerro);
 }else{
 	$clarrevenc->erro(true,false);
	 echo "<script>location.href='cai1_arrevenc002.php?k75_sequencial=$k75_sequencial&k00_numpre=$k00_numpre&db_opcao=1'; </script>";
 }
}

?>