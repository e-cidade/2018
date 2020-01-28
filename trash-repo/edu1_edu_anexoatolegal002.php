<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_edu_anexoatolegal_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoAnexoAtoLegal = db_utils::getdao("edu_anexoatolegal");

function validaNomeArquivo($sNome) {

  $sNome       = TiraAcento($sNome);
  $aCaracteres = array(" ", "[", "]", "{", "}", "*", "/", "&",
                       "\\", "$", "%", "#", "@", "!", "'");
  $sNome       = str_replace($aCaracteres, "_", $sNome);

  return $sNome;

}

$db_opcao = 22;
$db_botao = false;

if (isset($alterar)) {

  db_inicio_transacao();
  
  $db_opcao = 2;
  $oDaoAnexoAtoLegal->ed292_sequencial = $ed292_sequencial;
  $oDaoAnexoAtoLegal->ed292_obs        = $ed292_obs;

  if (isset($_FILES['ed292_arquivo']['name'])
      && $_FILES['ed292_arquivo']['name'] != "") {

    $sNomeArquivo = $_FILES['ed292_arquivo']['name'];
    $sPatch       = $_FILES['ed292_arquivo']['tmp_name'];
    $oidArquivo   = pg_lo_import($conn, $sPatch);

    if (!empty($sNomeArquivo)) {

      $oDaoAnexoAtoLegal->ed292_nomearquivo = validaNomeArquivo($sNomeArquivo);
      $oDaoAnexoAtoLegal->ed292_arquivo     = $oidArquivo;

    }

  }

  $oDaoAnexoAtoLegal->alterar($ed292_sequencial);
  
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

  $db_opcao = 2;
  $sSql     = $oDaoAnexoAtoLegal->sql_query($chavepesquisa);
  $rsSql    = $oDaoAnexoAtoLegal->sql_record($sSql); 
  
  db_fieldsmemory($rsSql, 0);
  
  $db_botao = true;


} elseif (isset($iAnexo) && isset($iAtoLegal)) {

  $db_opcao = 2;

  $sWhere   = " ed292_sequencial = ".$iAnexo." and ed292_atolegal = ".$iAtoLegal;
  $sSql     = $oDaoAnexoAtoLegal->sql_query("", "*", "", $sWhere);
  $rsSql    = $oDaoAnexoAtoLegal->sql_record($sSql);

  if ($oDaoAnexoAtoLegal->numrows > 0) {

    db_fieldsmemory($rsSql, 0);
    $db_botao = true;

  }

}

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <link href="estilos.css" rel="stylesheet" type="text/css">

    <?
      $sLib  = "scripts.js,prototype.js,webseller.js,strings.js,datagrid.widget.js,grid.style.css,";
      db_app::load($sLib);
    ?>

  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
    <table width="500" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
          <center>
	          <?
	            include("forms/db_frmedu_anexoatolegal.php");
	          ?>
          </center>
	      </td>
      </tr>
    </table>
  </center>
  </body>
</html>

<?

if (isset($alterar)) {
  
  if ($oDaoAnexoAtoLegal->erro_status == "0") {
    $oDaoAnexoAtoLegal->erro(true,false);
    $db_botao = true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoAnexoAtoLegal->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAnexoAtoLegal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAnexoAtoLegal->erro_campo.".focus();</script>";
    
    }

  } else {
    $oDaoAnexoAtoLegal->erro(true, false);
    db_redireciona("edu1_edu_anexoatolegal001.php?chavepesquisa=$ed05_i_codigo");
  }
  
}

?>

<script>
  js_tabulacaoforms("form1", "ed292_atolegal", true, 1, "ed292_atolegal", true);
</script>