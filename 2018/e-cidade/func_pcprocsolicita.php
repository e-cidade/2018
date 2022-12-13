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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_db_depusu_classe.php");

$oDaoPcParam             = new cl_pcparam;
$oDaoDepartamentoUsuario = new cl_db_depusu;

$sSqlParam     = $oDaoPcParam->sql_query_file(db_getsession("DB_instit"), "pc30_consultarelatoriodepartamento");
$rsParam       = $oDaoPcParam->sql_record($sSqlParam);
$iTipoConsulta = db_utils::fieldsMemory($rsParam, 0)->pc30_consultarelatoriodepartamento;
$iIdUsuario    = db_getsession("DB_id_usuario");

db_postmemory($_POST);
db_postmemory($_GET);
parse_str($_SERVER["QUERY_STRING"]);

$clsolicita = new cl_solicita;
$oRotulo = new rotulocampo();
$oRotulo->label('pc81_codproc');
$oRotulo->label('pc10_numero');
$oRotulo->label('pc10_data');
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
              db_input("pc10_numero", 10, $Ipc10_numero, true, "text",4, "", "chave_pc10_numero");
              ?>
            </td>
          </tr>

          <tr>
            <td><b>Processo de Compras:</b></td>
            <td>
              <?php
                db_input("pc81_codproc", 10, $Ipc81_codproc, true, "text", 4, "", "chave_pc81_codproc");
              ?>
            </td>
          </tr>


          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_data?>">
              <strong>Data Inicial:</strong>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_inputdata('pc10_data_inicial', "", "", '', true, "text", 1, "", "chave_pc10_data_inicial");
              ?>
            </td>
          </tr>
          <tr>
            <td align="right"><strong>Data Final:</strong></td>
            <td>
              <?php
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



      if (!isset($pesquisa_chave)) {

        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_solicita.php")==true) {
            include("funcoes/db_func_solicita.php");
          } else {
            $campos = "solicita.*";
          }
        }
        $where_depart .= " and pc10_instit = " . db_getsession("DB_instit");
        $campos = " distinct {$campos}";

        $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc "," 1=1 $where_depart {$sFiltrarDepartamento}  $sWhereContrato");
        if (isset($chave_pc10_numero) && (trim($chave_pc10_numero) != "") ) {

          $sWhere = " pc10_numero=$chave_pc10_numero $where_depart {$sFiltrarDepartamento} ";
          $sql    = $clsolicita->sql_query(null,$campos,"pc10_numero desc ", $sWhere . $sWhereContrato);

        } else if (isset($chave_pc81_codproc) && trim($chave_pc81_codproc) != "") {

          $sWhereAgrupado = " 1=1 {$sWhereContrato} and pc81_codproc = {$chave_pc81_codproc} ";
          $sql    = $clsolicita->sql_query(null, $campos, "pc10_numero desc ", $sWhereAgrupado);

        } else if (isset($chave_pc10_data_inicial) && (trim($chave_pc10_data_inicial) != "") ) {

          $chave_pc10_data_inicial = implode('-', array_reverse(explode('/', $chave_pc10_data_inicial)));
          $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc "," pc10_data >= '$chave_pc10_data_inicial' $sWhereContrato ");

          if (isset($chave_pc10_data_final) && (trim($chave_pc10_data_final) != "")) {

            $chave_pc10_data_final = implode('-', array_reverse(explode('/', $chave_pc10_data_final)));
            $sWhere = " pc10_data >= '$chave_pc10_data_inicial' AND pc10_data <= '$chave_pc10_data_final' ";
            $sWhere = " {$where_depart} {$sFiltrarDepartamento}";
            $sql    = $clsolicita->sql_query("",$campos,"pc10_numero desc ", "1=1 ".$sWhere . $sWhereContrato);
          }
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false);

      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $sWhere = " pc10_numero=$pesquisa_chave $where_depart {$sFiltrarDepartamento} ";
          $sSql = $clsolicita->sql_query(null, "distinct *", "", $sWhere . $sWhereContrato);
          $result = $clsolicita->sql_record($sSql);
          if ($clsolicita->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc10_numero', '{$pc81_codproc}',false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', '',true);</script>";
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