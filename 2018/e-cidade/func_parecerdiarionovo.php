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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_parecerturma_classe.php");
require_once ("classes/db_parecer_classe.php");

db_postmemory($_POST);
$oGet = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clparecerturma = new cl_parecerturma;
$oDaoParecer    = new cl_parecer;
$oDaoParecer->rotulo->label("ed92_i_codigo");
$oDaoParecer->rotulo->label("ed92_c_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Ted92_i_codigo?>">
              <?=$Led92_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("ed92_i_codigo",10,$Ied92_i_codigo,true,"text",4,"","chave_ed92_i_codigo");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Ted92_c_descr?>">
              <?=$Led92_c_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("ed92_c_descr",40,$Ied92_c_descr,true,"text",4,"","chave_ed92_c_descr");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_parecer001.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

        $aWhere = array();

        if (!empty( $oGet->iTurma )) {
          $aWhere[] = " ed105_i_turma = {$oGet->iTurma} ";
        }

        if ( !empty($oGet->iPeriodoAvalicao) ) {
          $aWhere[] = " (ed120_periodoavaliacao = {$oGet->iPeriodoAvalicao} or ed120_periodoavaliacao is null) ";
        }

        if ( !empty($oGet->iRegencia) ) {

          $oRegencia = RegenciaRepository::getRegenciaByCodigo($oGet->iRegencia);
          $oRegencia->getDisciplina()->getCodigoDisciplina();
          $aWhere[] = " ( ed106_disciplina = {$oRegencia->getDisciplina()->getCodigoDisciplina()} or ed106_disciplina is null) ";
        }

        if (isset($campos)==false) {

            if(file_exists("funcoes/db_func_parecer.php")==true){
              include("funcoes/db_func_parecer.php");
            } else {
              $campos = "parecer.*";
            }
          }

        if (!isset($pesquisa_chave)) {



          if(isset($chave_ed92_i_codigo) && (trim($chave_ed92_i_codigo)!="") ) {
            $aWhere[] = " ed92_i_codigo = {$chave_ed92_i_codigo} ";
          }else if(isset($chave_ed92_c_descr) && (trim($chave_ed92_c_descr)!="") ) {
            $aWhere[] = " ed92_c_descr like '$chave_ed92_c_descr%' ";
          }

          $sWhere  = implode(" and ", $aWhere);
          $sSql    = $oDaoParecer->sql_query_turma_disciplina_periodo("", $campos, "ed92_i_sequencial", $sWhere);
          $repassa = array();
          if(isset($chave_ed92_c_descr)){
            $repassa = array("chave_ed92_i_codigo"=>$chave_ed92_i_codigo,"chave_ed92_c_descr"=>$chave_ed92_c_descr);
          }
          db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

        } else {

          if ( !empty($pesquisa_chave) ) {

            $aWhere[] = " ed92_i_codigo = {$pesquisa_chave} ";
            $sWhere   = implode(" and ", $aWhere);
            $sSql     = $oDaoParecer->sql_query_turma_disciplina_periodo("", $campos, "ed92_i_sequencial", $sWhere);

            $sResult  = $oDaoParecer->sql_record($sSql);
            if ($oDaoParecer->numrows!=0) {

              db_fieldsmemory($sResult,0);
              echo "<script>".$funcao_js."('$ed92_c_descr',false);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
  js_tabulacaoforms("form2","chave_ed92_c_descr",true,1,"chave_ed92_c_descr",true);
</script>