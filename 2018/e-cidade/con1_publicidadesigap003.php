<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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

$db_opcao = 3;
$db_botao = false;
$lSqlErro = false;
$iInstit  = db_getsession('DB_instit');

if (isset($oPost->excluir)) {
	
  db_inicio_transacao();
  
  $db_botao = true;
  
  $clpublicidadesigap->excluir($oPost->c48_sequencial);
  $sMsg = $clpublicidadesigap->erro_msg;
  if ($clpublicidadesigap->erro_status == 0) { 
    $lSqlErro = true;
  }
  
  db_fim_transacao($lSqlErro);
} else if (isset($oGet->chavepesquisa)) {

   $db_botao = true;
   
   $result = $clpublicidadesigap->sql_record($clpublicidadesigap->sql_query($oGet->chavepesquisa)); 
   db_fieldsmemory($result, 0);
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

#c48_descricao, #c48_meiocomunicacaosigap, #c48_tiporelatoriofiscal, #c48_meiocomunicacaosigap_select_descr, 
#c48_tiporelatoriofiscal_select_descr {
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
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
<?
if (isset($oPost->excluir)) {
  db_msgbox($sMsg);
}

if ($db_botao == false) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>