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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_autotipo_classe.php");
require_once("classes/db_autotipobaixaproc_classe.php");
require_once("classes/db_autotipobaixaprocproc_classe.php");
require_once("classes/db_autotipobaixa_classe.php");
require_once("dbforms/db_funcoes.php");

$clautotipo              = new cl_autotipo;
$clautotipobaixaproc     = new cl_autotipobaixaproc;
$clautotipobaixaprocproc = new cl_autotipobaixaprocproc;
$clautotipobaixa         = new cl_autotipobaixa;

$clautotipobaixaprocproc->rotulo->label();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

if (isset($cancelabaixa)) {
	
  $sqlerro = false;
  
  db_inicio_transacao();
  
  if ($sqlerro == false) {
    
    if (isset($y114_processo) && !empty($y114_processo)) {
      
      $clautotipobaixaprocproc->excluir(null,"y114_processo = {$y114_processo}");
      if ($clautotipobaixaprocproc->erro_status == 0) {
        
         $sqlerro = true;
         $erro    = $clautotipobaixaprocproc->erro_msg;
      }
    }
  }
  
  if ($sqlerro == false) {
  
	  $result_autotipo = $clautotipo->sql_record($clautotipo->sql_query_file(null,"*",null,"y59_codauto=$y50_codauto"));
	  $numrows         = $clautotipo->numrows;
	  $cods            = split('#',$chaves);
	  if ($numrows == count($cods)) {
	  	
	    if ($numrows != 0) {
	    	
	      for($y = 0; $y < $numrows; $y++) {
	      	
	        db_fieldsmemory($result_autotipo,$y);
	        $sSqlAutoTipoBaixa    = $clautotipobaixa->sql_query_file($y59_codigo,"distinct y86_codbaixaproc");
		      $result_autotipobaixa = $clautotipobaixa->sql_record($sSqlAutoTipoBaixa);
		      $num                  = $clautotipobaixa->numrows;
		      if ($num != 0) {
		      	
		        for ($i = 0; $i < $num; $i++) {
		        	
		          db_fieldsmemory($result_autotipobaixa,$i);
	            if ($sqlerro == false) {
	            	
	    	        $clautotipobaixa->excluir(null,"y86_codbaixaproc=$y86_codbaixaproc");
		            $erro = $clautotipobaixa->erro_msg;
		            if ($clautotipobaixa->erro_status == 0) {
		            	
		              $sqlerro = true;
		              $erro    = $clautotipobaixa->erro_msg;
			            break;
	     	        }
		          }
		          
	            if ($sqlerro == false) {
	            	
		            $clautotipobaixaproc->excluir($y86_codbaixaproc);
		            if ($clautotipobaixaproc->erro_status == 0) {
		            	
		              $sqlerro = true;
		              $erro    = $clautotipobaixaproc->erro_msg;
			            break;
	     	        }
		          }
		        }
		      }
	      }
	    }
	  } else {
	  	
	    for ($x = 0; $x < count($cods); $x++) {
	    	
	      if ($sqlerro == false) {
	      	
	        if (isset($cods[$x]) && !empty($cods[$x])) {
	        	
		        $clautotipobaixa->excluir($cods[$x]);
		        $erro = $clautotipobaixa->erro_msg;
		        if ($clautotipobaixa->erro_status == 0) {
		          
		          $sqlerro = true;
		          $erro    = $clautotipobaixa->erro_msg;
		          break;
		        }
	        }
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
	    <?
	      include("forms/db_frmautobaixaprocalt.php");
	    ?>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
if (isset($cancelabaixa)) {
	
  if ($sqlerro == true) {
  	 
    db_msgbox($erro);
    if ($clautotipobaixaproc->erro_campo != "") {
    	
      echo "<script> document.form1.".$clautotipobaixaproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautotipobaixaproc->erro_campo.".focus();</script>";
    } 
  } else { 
  	
  	if (isset($erro) && !empty($erro)) {
  	  db_msgbox($erro); 
  	}
  }
}
?>
</html>