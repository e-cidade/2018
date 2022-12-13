<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_publicidadesigap_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clpublicidadesigap = new cl_publicidadesigap;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;
$iInstit  = db_getsession('DB_instit'); 

if (isset($oPost->incluir)) {
	
  db_inicio_transacao();
  
  $clpublicidadesigap->c48_instit = $iInstit;
  $clpublicidadesigap->incluir(null);
  $sMsg = $clpublicidadesigap->erro_msg;
  if ($clpublicidadesigap->erro_status == 0) {
  	$lSqlErro = true;
  } else {
  	$db_botao = true;
  }
  
  $c48_sequencial           = '';
  $c48_ano                  = '';
  $c48_mes                  = '';
  $c48_descricao            = '';
  $c48_datapublicacao_dia   = '';
  $c48_datapublicacao_mes   = '';
  $c48_datapublicacao_ano   = '';
  $c48_meiocomunicacaosigap = '';
  $c48_tiporelatoriofiscal  = '';
  
  db_fim_transacao($lSqlErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 160px;
  white-space: nowrap
}

#c48_sequencial {
  width: 80px;
}

#c48_ano {
  width: 40px;
}

#c48_mes {
  width: 25px;
}

#c48_descricao, #c48_meiocomunicacaosigap, #c48_tiporelatoriofiscal {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td height="40px">&nbsp;</td>
  </tr>
</table>
<table width="630" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmpublicidadesigap.php");
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
<script>
js_tabulacaoforms("form1","c48_meiocomunicacaosigap",true,1,"c48_meiocomunicacaosigap",true);
</script>
<?
if (isset($oPost->incluir)) {
	
	db_msgbox($sMsg);
  if ($clpublicidadesigap->erro_status == "0") {

  	$db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clpublicidadesigap->erro_campo != "") {
    	
      echo "<script> document.form1.".$clpublicidadesigap->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpublicidadesigap->erro_campo.".focus();</script>";
    }
  }
}
?>