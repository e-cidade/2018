<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_criterioavaliacao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory( $_POST );

$oDaoCriterioAvaliacao = new cl_criterioavaliacao();
$oDaoCriterioAvaliacao->rotulo->label();

$iEscola = db_getsession( "DB_coddepto" );
$aWhere  = array();

$aWhere[] = " ed338_escola = {$iEscola} ";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">

          <tr>
            <td >
              <b>Código:</b>
            </td>
            <td>
              <?db_input("ed338_sequencial", 10, $Ied338_sequencial, true, "text", 4, "", "chave_ed338_sequencial");?>
            </td>
          </tr>
          <tr>
            <td >
              <b>Descrição:</b>
            </td>
            <td>
              <?db_input("ed338_descricao", 30, $Ied338_descricao, true, "text", 4, "", "chave_ed338_descricao");?>
            </td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_criterioavaliacao.hide();">
             </td>
          </tr>

        </table>
      </form>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset( $pesquisa_chave) ) {

        $sCampos = "criterioavaliacao.*";

        if ( isset($oPost->chave_ed338_sequencial) && !empty($oPost->chave_ed338_sequencial) ) {
          $aWhere[] = " ed338_sequencial = {$oPost->chave_ed338_sequencial} ";
        }

        if ( isset($oPost->chave_ed338_descricao) && !empty($oPost->chave_ed338_descricao) ) {
          $aWhere[] = " ed338_descricao ilike '{$oPost->chave_ed338_descricao}%' ";
        }

        $sWhere = implode( " and ", $aWhere);
        $sOrdem = "ed338_ordem";

        $sql    = $oDaoCriterioAvaliacao->sql_query(null, $sCampos, $sOrdem, $sWhere);

        $repassa = array();
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ( $pesquisa_chave != null && $pesquisa_chave != "") {

          $aWhere[] = " ed338_sequencial = {$pesquisa_chave} ";
          $sWhere   = implode( " and ", $aWhere);

          $result = $oDaoCriterioAvaliacao->sql_record($oDaoCriterioAvaliacao->sql_query(null, $sCampos, null, $sWhere));
          if ( $oDaoCriterioAvaliacao->numrows != 0 ) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed338_sequencial',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
