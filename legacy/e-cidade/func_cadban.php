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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));

include(modification("dbforms/db_funcoes.php"));

include(modification("classes/db_cadban_classe.php"));
include(modification("classes/db_cgm_classe.php"));

$method = "sql_query";

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet = db_utils::postMemory($_GET);

$clcadban = new cl_cadban;
$clcgm    = new cl_cgm;

$clcadban->rotulo->label("k15_codigo");
$clcgm->rotulo->label("z01_nome");

$instit = db_getsession("DB_instit");
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
            <td width="4%" align="right" nowrap title="<?=$Tk15_codigo?>">
              <?=$Lk15_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input("k15_codigo",6,$Ik15_codigo,true,"text",4,"","chave_k15_codigo");
           ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
           ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cadban.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $and    = " and ";
      $sWhere = "k15_instit = ".db_getsession("DB_instit");

      if ( !isset($pesquisa_chave) ) {

        /**
         * Pesquisa pelo banco
         */
        if ( !empty($oGet->lPesquisaBanco) ) {

          $campos  = 'k15_codbco, z01_nome';
          $sOrder  = 'k15_codbco';
          $sWhere .= 'group by ' . $campos;
        }

        /**
         * Pesquisa pela agencia
         */
        if ( !empty($oGet->iCodigoBanco) ) {

          $campos  = 'k15_codage, k15_agenci, k15_codbco, z01_nome, k15_codigo';
          $sOrder  = 'k15_codage';
          $sWhere .= "and k15_codbco = '" . $oGet->iCodigoBanco . "'  group by " . $campos;
        }

        if ( !isset($campos) ) {

          $sOrder = 'k15_codigo';

          if (file_exists("funcoes/db_func_cadban.php")==true) {

            include(modification("funcoes/db_func_cadban.php"));
          } else {
            $campos = "cadban.*";
          }
        }

        if ( isset($chave_k15_codigo) && (trim($chave_k15_codigo)!="") ) {
          $sql = $clcadban->$method("",$campos,"k15_codigo","k15_codigo = $chave_k15_codigo $and $sWhere");
        } elseif ( isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ) {
          $sql = $clcadban->$method("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' $and $sWhere ");
        } else {
          $sql = $clcadban->$method("",$campos, $sOrder," $sWhere");
        }

        $repassa = array();

        if (isset($chave_z01_nome)) {
          $repassa = array("chave_k15_codigo"=>$chave_k15_codigo,"chave_z01_nome"=>$chave_z01_nome);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","nome",$repassa);

      } else {

        if ( !empty($pesquisa_chave) ) {

          /**
           * Pesquisa pela agencia
           */
          if ( !empty($oGet->iCodigoBanco) ) {

            $sWhere .= 'and k15_codbco = ' . $oGet->iCodigoBanco;
          }

          /**
           * Pesquisa pelo banco
           */
          if ( !empty($oGet->lPesquisaBanco) ) {

            $sWhere .= 'and k15_codbco = ' . $oGet->pesquisa_chave;

            $sSql    = $clcadban->sql_query(null, "*", null, $sWhere);

          /**
           * Pesquisa pela agencia
           */
          } elseif ( !empty($oGet->lPesquisaAgencia) ) {

            $sWhere .= "and k15_codbco = {$oGet->iCodigoBanco} and k15_codage = '{$oGet->pesquisa_chave}'";
            $sSql    = $clcadban->sql_query(null, "*", null, $sWhere);
          } else {
            $sSql = $clcadban->$method("","*","","k15_codigo = $pesquisa_chave $and $sWhere");
          }
    die($sSql);
          $result  = $clcadban->sql_record($sSql);

          if ($clcadban->numrows!=0) {

            db_fieldsmemory($result,0);

            if ( !empty($oGet->lPesquisaAgencia) ) {
              echo "<script>".$funcao_js."('$k15_agenci',false);</script>";
            } else {
              echo "<script>".$funcao_js."('$z01_nome',false);</script>";
            }

          } else {
           echo "<script>".$funcao_js."('chave(".$pesquisa_chave.") não encontrado',true);</script>";
          }

        } else {
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
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_z01_nome",true,1,"chave_z01_nome",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
