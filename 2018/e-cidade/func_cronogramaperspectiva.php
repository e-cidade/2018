<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cronogramaperspectiva_classe.php");
db_postmemory($_POST);

$oGet = db_utils::postMemory($_GET);

parse_str($_SERVER["QUERY_STRING"]);
$clcronogramaperspectiva = new cl_cronogramaperspectiva;
$clcronogramaperspectiva->rotulo->label("o124_sequencial");
$clcronogramaperspectiva->rotulo->label("o124_descricao");
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
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$To124_sequencial?>">
              <?=$Lo124_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("o124_sequencial",10,$Io124_sequencial,true,"text",4,"","chave_o124_sequencial");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$To124_descricao?>">
              <?=$Lo124_descricao?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("o124_descricao",100,$Io124_descricao,true,"text",4,"","chave_o124_descricao");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cronogramaperspectiva.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $aWhere = array();
      if (!empty($oGet->tipo)) {
        $aWhere[] = "o124_tipo = {$oGet->tipo}";
      }

      if (!empty($oGet->homologado)) {
        $aWhere[] = "o124_situacao = " . cronogramaFinanceiro::SITUACAO_HOMOLOGADO;
      }

      if (!empty($oGet->aberto)) {
        $aWhere[] = "o124_situacao = " . cronogramaFinanceiro::SITUACAO_ABERTO;
      }

      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){
          if(file_exists("funcoes/db_func_cronogramaperspectiva.php")==true){
            include("funcoes/db_func_cronogramaperspectiva.php");
          }else{
            $campos = "cronogramaperspectiva.*";
          }
        }

        if(isset($chave_o124_sequencial) && (trim($chave_o124_sequencial)!="") ){

          $aWhere[] = "o124_sequencial = $chave_o124_sequencial";
          $sql = $clcronogramaperspectiva->sql_query(null, $campos, "o124_sequencial", implode(' and ', $aWhere));

        }else if(isset($chave_o124_descricao) && (trim($chave_o124_descricao)!="") ){

          $aWhere[] = "o124_descricao like '$chave_o124_descricao%'";
          $sql = $clcronogramaperspectiva->sql_query(null, $campos, "o124_descricao", implode(' and ', $aWhere));
        }else{
          $sql = $clcronogramaperspectiva->sql_query(null, $campos, "o124_sequencial", implode(' and ', $aWhere));
        }

        $repassa = array();
        if(isset($chave_o124_descricao)){
          $repassa = array("chave_o124_sequencial"=>$chave_o124_sequencial,"chave_o124_descricao"=>$chave_o124_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $aWhere[] = "o124_sequencial = {$pesquisa_chave}";
          $result = $clcronogramaperspectiva->sql_record($clcronogramaperspectiva->sql_query(null, "*", null, implode(' and ', $aWhere)));
          if($clcronogramaperspectiva->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o124_descricao',false,'{$o124_ano}');</script>";
          }else{
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true, '');</script>";
          }
        }else{
          echo "<script>".$funcao_js."('',false,'');</script>";
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
<script>
  js_tabulacaoforms("form2","chave_o124_descricao",true,1,"chave_o124_descricao",true);
</script>