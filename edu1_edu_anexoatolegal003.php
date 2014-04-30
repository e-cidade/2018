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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_edu_anexoatolegal_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoAtoLegal      = db_utils::getdao('atolegal');
$oDaoAnexoAtoLegal = db_utils::getdao('edu_anexoatolegal');
$db_botao          = false;
$db_opcao          = 33;

/*
 * Reordena os anexos do ato legal que restaram na tabela
 * caso eles existam obviamente.
 */
function reordenaTabela($iAtoLegal) {
  
  $oDaoAnexoAtoLegal = db_utils::getdao('edu_anexoatolegal');

  $sOrderByOrdena    = " ed292_ordem ASC ";
  $sWhereOrdena      = " ed292_atolegal = ".$iAtoLegal;
  $sSqlOrdena        = $oDaoAnexoAtoLegal->sql_query("", "*", $sOrderByOrdena, $sWhereOrdena);
  $rsSqlOrdena       = $oDaoAnexoAtoLegal->sql_record($sSqlOrdena);

  if ($oDaoAnexoAtoLegal->numrows > 0) {

    $iTamanho = $oDaoAnexoAtoLegal->numrows;

    for ($iCont = 0; $iCont < $iTamanho; $iCont++) {
      
      $oDaoAnexoAtoLegal = db_utils::getdao('edu_anexoatolegal');
      $oDados            = db_utils::fieldsmemory($rsSqlOrdena, $iCont);
      
      $oDaoAnexoAtoLegal->ed292_ordem       = $iCont + 1;
      $oDaoAnexoAtoLegal->ed292_nomearquivo = $oDados->ed292_nomearquivo;
      $oDaoAnexoAtoLegal->ed292_arquivo     = $oDados->ed292_arquivo;
      $oDaoAnexoAtoLegal->ed292_obs         = $oDados->ed292_obs;
      $oDaoAnexoAtoLegal->ed292_sequencial  = $oDados->ed292_sequencial;
      $oDaoAnexoAtoLegal->alterar($oDados->ed292_sequencial);

    }

  }

}

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $db_botao = true;
  $oDaoAnexoAtoLegal->excluir($ed292_sequencial);
  db_fim_transacao();
  
  if ($oDaoAnexoAtoLegal->erro_status != 0) {
    reordenaTabela($ed05_i_codigo);
  }
  

} elseif (isset($iAnexo) && isset($iAtoLegal)) {

   $db_opcao = 3;

   $sSql     = $oDaoAnexoAtoLegal->sql_query($iAnexo);
   $rsSql    = $oDaoAnexoAtoLegal->sql_record($sSql); 
   db_fieldsmemory($rsSql, 0);
   $db_botao = true;

} elseif (isset($chavepesquisa)) {

  $db_opcao = 3;
  $sSql     = $oDaoAtoLegal->sql_query($chavepesquisa);
  $rsQuery  = $oDaoAtoLegal->sql_record($sSql);

  db_fieldsmemory($rsQuery, 0);
  $db_botao = false;

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

if (isset($excluir)) {

  if ($oDaoAnexoAtoLegal->erro_status == "0") {
    $oDaoAnexoAtoLegal->erro(true, false);
  } else {
    $oDaoAnexoAtoLegal->erro(true, false);
    db_redireciona("edu1_edu_anexoatolegal001.php?chavepesquisa=".$ed05_i_codigo);
  }

}

if ($db_opcao == 33) {
  echo "<script>$('pesquisar').click();</script>";
}

?>

<script>
  js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>