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
require("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_pactovalor_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

$clpactovalor = new cl_pactovalor;
$db_opcao     = 1;
$db_botao     = true;
$lSqlErro     = false;
$sMsgErro     = "";

if(isset($oPost->incluir)){
	
  db_inicio_transacao();
  
  $clpactovalor->o87_pactoplano             = $oPost->o87_pactoplano;
  $clpactovalor->o87_pactoprograma          = $oPost->o87_pactoprograma;
  $clpactovalor->o87_orcprojativativprojeto = $oPost->o87_orcprojativativprojeto;
  $clpactovalor->o87_orcprojativanoprojeto  = $oPost->o87_orcprojativanoprojeto;
  $clpactovalor->o87_pactoatividade         = $oPost->o87_pactoatividade;
  $clpactovalor->o87_pactoacoes             = $oPost->o87_pactoacoes;
  $clpactovalor->o87_categoriapacto         = $oPost->o87_categoriapacto;
  $clpactovalor->o87_pactoitem              = $oPost->o87_pactoitem;
  $clpactovalor->o87_quantidade             = $oPost->o87_quantidade;
  $clpactovalor->o87_vlraproximado          = $oPost->o87_vlraproximado;
  $clpactovalor->o87_orcprogramaano         = $oPost->o87_orcprojativanoprojeto;
  
  $clpactovalor->incluir($oPost->o87_sequencial);
  
  
  if ( $clpactovalor->erro_status == 0 ) {
  	$lSqlErro = true;
  }
  
  $sMsgErro = $clpactovalor->erro_msg;
  
  db_fim_transacao($lSqlErro);
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" style="padding-top:25px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
			<?
			include("forms/db_frmpactovalor.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($oPost->incluir)){
	
  if ($lSqlErro) {
  	
    db_msgbox($sMsgErro);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpactovalor->erro_campo!=""){
      echo "<script> document.form1.".$clpactovalor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpactovalor->erro_campo.".focus();</script>";
    }
    
  }else{
  	
    db_msgbox($sMsgErro);
    
    echo " <script>
              parent.document.formaba.itememp.disabled = false;
              parent.iframe_valpacto.location.href     = 'orc1_pactovalor012.php?chavepesquisa=".$clpactovalor->o87_sequencial."';
              parent.iframe_itememp.location.href      = 'orc1_empempitempacto001.php?codpacto=".$clpactovalor->o87_sequencial."'; 
           </script>";  	
  }
  
}
?>