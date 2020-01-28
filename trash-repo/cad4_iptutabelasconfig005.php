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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_db_sysarquivo_classe.php");
require_once("classes/db_iptutabelasdepend_classe.php");
require_once("classes/db_iptutabelas_classe.php");
require_once("classes/db_iptutabelasconfig_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cldbsysarquivo       = new cl_db_sysarquivo;
$cliptutabelas        = new cl_iptutabelas;
$cliptutabelasdepend  = new cl_iptutabelasdepend;
$cliptutabelasconfig  = new cl_iptutabelasconfig;

$db_opcao             = 22;
$db_botao             = false;
$lDisabled            = true;

if (isset($oGet->chavepesquisa)) {
  
  $db_opcao               = 3;
  
  $sWhere                 = "j122_sequencial = {$oGet->chavepesquisa}";
  $sSqlIptuTabelasConfig  = $cliptutabelasconfig->sql_query(null, "*", null, $sWhere);
  $rsSqlIptuTabelasConfig = $cliptutabelasconfig->sql_record($sSqlIptuTabelasConfig);
  if ($cliptutabelasconfig->numrows > 0) {
    
    db_fieldsmemory($rsSqlIptuTabelasConfig, 0);
    echo " <script>                                                                                                                             ";
    echo "   parent.iframe_camposchave.location.href         = 'cad4_iptutabelasconfigcampochave001.php?j122_sequencial={$j122_sequencial}';    ";
    echo "   parent.iframe_camposcorrecao.location.href      = 'cad4_iptutabelasconfigcampocorrecao001.php?j122_sequencial={$j122_sequencial}'; ";
    echo "   parent.document.formaba.dadostabela.disabled    = false;                                                                           ";
    echo "   parent.document.formaba.camposchave.disabled    = false;                                                                           ";
    echo "   parent.document.formaba.camposcorrecao.disabled = false;                                                                           ";
    echo " </script>                                                                                                                            ";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 90px;
              white-space: nowrap
}

#j121_codarq {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
          include("forms/db_frmiptutabelasconfig.php");
        ?>
      </center>
    </td>
  </tr>
</table>
</body>
<?
if (isset($oPost->alterar)) {
  
  if ($lSqlErro) {
    
    db_msgbox($sMsgErro);
    echo "<script> document.form1.db_opcao.disabled=true;</script>  ";
    if ($cliptutabelasconfig->erro_campo != "") {
      
      echo "<script> document.form1.".$cliptutabelasconfig->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptutabelasconfig->erro_campo.".focus();</script>";
    }
  } else {
    db_msgbox($sMsgErro);
  }
}

if ($db_opcao == 22) {

  echo " <script>                                                                                                                 ";
  echo "   document.form1.pesquisar.click();                                                                                      ";
  echo "   parent.document.formaba.dadostabela.disabled    = true;                                                                ";
  echo "   parent.document.formaba.camposchave.disabled    = true;                                                                ";
  echo "   parent.document.formaba.camposcorrecao.disabled = true;                                                                ";
  echo " </script>                                                                                                                ";
}
?>
</html>