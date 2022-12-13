<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_db_depusu_classe.php");

$oDaoPcParam             = new cl_pcparam;
$oDaoDepartamentoUsuario = new cl_db_depusu;

$oGet = db_utils::postMemory($_GET);
$sSqlParam     = $oDaoPcParam->sql_query_file(db_getsession("DB_instit"),
                                              "*",
                                              null,
                                              ""
                                              );
$rsParam           = $oDaoPcParam->sql_record($sSqlParam);
$oDadosSolicitacao = db_utils::fieldsMemory($rsParam, 0);
$iTipoConsulta     = $oDadosSolicitacao->pc30_consultarelatoriodepartamento;
$iIdUsuario        = db_getsession("DB_id_usuario");

$lNecessitaLiberarSolicitacao = $oDadosSolicitacao->pc30_liberado == 'f';
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsolicita = new cl_solicita;
$clsolicita->rotulo->label("pc10_numero");
$clsolicita->rotulo->label("pc10_data");

$sWhereContrato = " and 1 = 1 ";

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
            <td width="4%" align="right" nowrap title="<?=$Tpc10_numero?>">
              <?=$Lpc10_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_data?>">
              <strong>Data de Solicitação De:</strong>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_inputdata('pc10_data_inicial', "", "", '', true, "text", 1, "", "chave_pc10_data_inicial");
                echo "&nbsp;<strong>Até:</strong>&nbsp;";
                db_inputdata('pc10_data_final', "", "", '', true, "text", 1, "", "chave_pc10_data_final");
                db_input("param",10,"",false,"hidden",3);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_solicita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $where_depart = '';
      $sDepartamento = "";

      if (isset($lFiltroContrato) && $lFiltroContrato == 1 ){

        $sWhereContrato .= ' and acordopcprocitem.ac23_sequencial is null ';

      }


      switch ($iTipoConsulta) {
        case 1:

          $sSqlDepartamentoUsuario = $oDaoDepartamentoUsuario->sql_query($iIdUsuario, null, "db_depusu.coddepto",
          																															"db_depusu.coddepto", "");
          $rsDepartamentoUsuario   = $oDaoDepartamentoUsuario->sql_record($sSqlDepartamentoUsuario);
          $iLinhas = $oDaoDepartamentoUsuario->numrows;
          if ($oDaoDepartamentoUsuario->numrows > 0) {

            $sDepartamentoUsuario = "";
            $sVirgula             = "";
            for ($i = 0; $i < $iLinhas; $i++) {

              $sDepartamento .= $sVirgula. db_utils::fieldsMemory($rsDepartamentoUsuario, $i)->coddepto;
              $sVirgula       = ",";
            }
          }

          break;

        case 2:

          $sDepartamento = db_getsession("DB_coddepto");
          break;
      }
      $sFiltrarDepartamento = "";
      if (!empty($sDepartamento)) {

        $sFiltrarDepartamento = " and coddepto in ($sDepartamento)";
      }

      if (!isset($passar)) {
        $where_depart = " and pc81_solicitem ";
        if (isset($param) && $param == "") {
          $nulo = " is null ";
        } else {
          $nulo = "";
        }

        if (trim($nulo) == "") {
          $where_depart  = " and (e55_sequen is null or (e55_sequen is not null and e54_anulad is not null))";
        } else {
          $where_depart .= $nulo;
        }
      }
      if (!empty($ativas)) {
        $where_depart .= " and not exists(select 1 from solicitaanulada where pc67_solicita = pc10_numero)";
      }
      if (isset($_GET["validar_liberacao_solicitacao"]) && $lNecessitaLiberarSolicitacao) {
        $where_depart .= " and exists (select 1 from solicitem where pc11_numero = solicita.pc10_numero and pc11_liberado is true)";
      }
      if (isset($anular) && $anular=="true") {
        $where_depart = " and e54_autori is not null and e54_anulad is null and (e61_numemp is null or (e60_numemp is not null and e60_vlremp=e60_vlranu))";
      }
      if (isset($anular) && $anular=="false") {
        $where_depart .= " and ( e54_autori is null or ( e54_autori is not null and e54_anulad is null and (e61_numemp is null or (e60_numemp is not null and e60_vlremp=e60_vlranu))))";
      }
      if (isset($anular)) {
        $where_depart .= " and pc11_codigo is not null ";
      }

      if (isset($departamento) && trim($departamento)!="") {
        $where_depart .= " and pc10_depto=$departamento ";
      }
      if (isset($gerautori)) {
        $where_depart .= " and pc10_correto='t' ";
      }

      if (isset($proc) and $proc=="true") {
        $where_depart .= " and pc81_codproc is not null";
      }

      if (isset($nada)) {
        $where_depart = "";
      }

      if (isset($param_mostra_regprecos) && $param_mostra_regprecos == "nao") {
        $where_depart .= " and pc10_solicitacaotipo <> 5";
      }

      if (!empty($oGet->tiposolicitacao)) {
        $where_depart .= " and pc10_solicitacaotipo in ({$oGet->tiposolicitacao}) ";
      }



      if (!isset($pesquisa_chave)) {

        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_solicita.php")==true) {
            include("funcoes/db_func_solicita.php");
          } else {
            $campos = "solicita.*";
          }
        }
        $where_depart .= " and pc10_instit = " . db_getsession("DB_instit");
        $campos = " distinct ".$campos;


        if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {

          $sWhere = " pc10_numero=$chave_pc10_numero $where_depart {$sFiltrarDepartamento} ";
          $sql    = $clsolicita->sql_query(null,$campos,"pc10_numero desc ", $sWhere . $sWhereContrato);

        } else if ( !empty($chave_pc10_data_inicial) ) {

          $chave_pc10_data_inicial = implode('-', array_reverse(explode('/', $chave_pc10_data_inicial)));
          $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc "," pc10_data >= '$chave_pc10_data_inicial' $sWhereContrato ");

          if ( !empty($chave_pc10_data_final) ) {

            $chave_pc10_data_final = implode('-', array_reverse(explode('/', $chave_pc10_data_final)));
            $sWhere  = " pc10_data >= '$chave_pc10_data_inicial' AND pc10_data <= '$chave_pc10_data_final' ";
            $sWhere .= " {$where_depart} {$sFiltrarDepartamento}";
            $sql     = $clsolicita->sql_query("",$campos,"pc10_numero desc ",$sWhere . $sWhereContrato);
          }

        } else {
          $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc "," 1=1 $where_depart {$sFiltrarDepartamento}  $sWhereContrato");
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false);

      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $sWhere = " pc10_numero=$pesquisa_chave $where_depart {$sFiltrarDepartamento} ";
          $sSql = $clsolicita->sql_query(null, "distinct *", "", $sWhere . $sWhereContrato. " and pc10_instit = " . db_getsession("DB_instit"));
          $result = $clsolicita->sql_record($sSql);
          if ($clsolicita->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc10_numero',false);</script>";
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