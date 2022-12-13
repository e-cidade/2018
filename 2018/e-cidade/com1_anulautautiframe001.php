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
include(modification("libs/db_liborcamento.php"));
include(modification("libs/db_utils.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_pcorcamitem_classe.php"));
include(modification("classes/db_pcorcamjulg_classe.php"));
include(modification("classes/db_pcorcamval_classe.php"));
include(modification("classes/db_orcreserva_classe.php"));
include(modification("classes/db_orcreservasol_classe.php"));
include(modification("classes/db_orcreservaaut_classe.php"));
include(modification("classes/db_pcparam_classe.php"));
include(modification("classes/db_pcdotac_classe.php"));
include(modification("classes/db_empautoriza_classe.php"));
include(modification("classes/db_empautitem_classe.php"));
include(modification("classes/db_empautidot_classe.php"));
include(modification("classes/db_pcprocitem_classe.php"));
include(modification("classes/db_solicitemprot_classe.php"));
include(modification("classes/db_solandam_classe.php"));
include(modification("classes/db_solandpadrao_classe.php"));
include(modification("classes/db_solandpadraodepto_classe.php"));
include(modification("classes/db_proctransfer_classe.php"));
include(modification("classes/db_proctransferproc_classe.php"));
include(modification("classes/db_protprocesso_classe.php"));
include(modification("classes/db_solordemtransf_classe.php"));

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcorcamitem       = new cl_pcorcamitem;
$clpcorcamjulg       = new cl_pcorcamjulg;
$clpcorcamval        = new cl_pcorcamval;
$clorcreserva        = new cl_orcreserva;
$clorcreservasol     = new cl_orcreservasol;
$clorcreservaaut     = new cl_orcreservaaut;
$clempautoriza       = new cl_empautoriza;
$clempautitem        = new cl_empautitem;
$clempautidot        = new cl_empautidot;
$clpcparam           = new cl_pcparam;
$clpcdotac           = new cl_pcdotac;
$clpcprocitem        = new cl_pcprocitem;
$clsolicitemprot     = new cl_solicitemprot;
$clsolandam          = new cl_solandam;
$clsolandpadrao      = new cl_solandpadrao;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clproctransfer      = new cl_proctransfer;
$clproctransferproc  = new cl_proctransferproc;
$clprotprocesso      = new cl_protprocesso;
$clsolordemtransf    = new cl_solordemtransf;

$clrotulo = new rotulocampo;
$clrotulo->label("pc80_codproc");
$clrotulo->label("e54_codcom");
$clrotulo->label("e54_codtipo");
$clrotulo->label("pc23_valor");
$db_opcao = 1;
$db_botao = true;

$sqlerro  = false;

if (isset($incluir)) {

  db_inicio_transacao();
  $valor = explode(",",$valores);
  // arrays para dados do empautoriza
  $arr_vals = Array();
  $arr_cgms = Array();
  $arr_help = Array();
  $indexaut = 0;

  // arrays para dados do empautitem
  $arr_proc = Array();
  $arr_hell = Array();
  $indexitm = 0;
  $vir	    = "";
  $vetor_dotacao = array();


  for ($i=0; $i<sizeof($valor); $i++) {

    $dados   = explode("_",$valor[$i]);
    $rsParam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
    $oParam  = db_utils::fieldsMemory($rsParam,0);

    if (isset ($oParam->pc30_contrandsol) && $oParam->pc30_contrandsol == 't') {

      $rsItensTransf   = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null,"pc11_codigo",null,"e55_autori = ".$dados[1]." and e54_anulad is null"));
      $iNroItensTransf = $clempautitem->numrows;

      for ($x=0; $x < $iNroItensTransf; $x++) {

        $oItensTransf = db_utils::fieldsMemory($rsItensTransf,$x);

        $rsSolicitemProt   = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_transf(null,"max(p63_codtran) as codtran ",null," pc49_solicitem = {$oItensTransf->pc11_codigo}"));

        $iNroSolicitemProt = $clsolicitemprot->numrows;

        for ($y=0; $y < $iNroSolicitemProt; $y++) {

          $oSolicitemProt = db_utils::fieldsMemory($rsSolicitemProt,$y);
          if ($oSolicitemProt->codtran != "") {

            $clproctransferproc->excluir($oSolicitemProt->codtran);
            if ($clproctransferproc->erro_status == "0") {
              $erro_msg = $clproctransferproc->erro_msg;
              $sqlerro  = true;
            }

            $clsolordemtransf->excluir(null," pc41_codtran = {$oSolicitemProt->codtran} ");
            if ($clsolordemtransf->erro_status == "0") {
              $erro_msg = $clsolordemtransf->erro_msg;
              $sqlerro  = true;
            }

            $clproctransfer->excluir($oSolicitemProt->codtran);
            if ($clproctransfer->erro_status == "0") {
              $erro_msg = $clproctransfer->erro_msg;
              $sqlerro  = true;
            }
          }
        }
      }
    }

  }


  if ($reservar == "true"){
    $flag_reservar = true;
  }

  if ($reservar == "false"){
    $flag_reservar = false;
  }

  for ($i=0; $i<sizeof($valor); $i++) {


    try {

      $aPartesCodigo = explode("_", $valor[$i]);
      $clempautoriza->anulaAutorizacao($aPartesCodigo[1], $flag_reservar);
      $msg = "Autorizações anuladas com sucesso.";
    } catch (Exception $eErro) {
      $sqlerro  = true;
      $erro_msg = $eErro->getMessage();
    }

  }

  db_fim_transacao($sqlerro);

}

$numrows_itens = 0;
if (isset($e54_autori) && trim($e54_autori)!="") {
  $sql_itens  = $clempautitem->sql_query_anuaut(null,
                                                null,
                                                " distinct pc11_codigo as cod_item",
                                                null,
                                                "e55_autori={$e54_autori}
                                                 and e54_anulad is null
                                                 and (e61_numemp is null
                                                      or (e61_numemp is not null
                                                          and e60_vlremp=e60_vlranu))");
  $result_itens  = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,
                                                                             null,
                                                                             "distinct e54_autori,
                                                                              pc81_codproc,
                                                                              pc81_codprocitem,
                                                                              pc01_codmater,
                                                                              pc01_descrmater,
                                                                              e56_coddot,
                                                                              e56_orctiporec,
                                                                              pc81_solicitem,
                                                                              z01_numcgm,
                                                                              z01_nome,
                                                                              e55_vltot,
                                                                              e55_quant",
                                                                             "z01_numcgm,
                                                                              e56_coddot,
                                                                              e56_orctiporec,
                                                                              pc01_codmater,
                                                                              pc81_codprocitem",
                                                                             "e55_autori={$e54_autori}
                                                                              and e54_anulad is null
                                                                              and (e61_numemp is null
                                                                                   or(e61_numemp is not null
                                                                                       and e60_vlremp=e60_vlranu))"));
  $numrows_itens = $clempautitem->numrows;

} else if (isset($pc80_codproc) && trim($pc80_codproc)!="") {

  $sql_itens  = $clempautitem->sql_query_anuaut(null,
                                                null,
                                                "distinct pc11_codigo as cod_item",
                                                null,
                                                "pc81_codproc={$pc80_codproc}
                                                 and e54_anulad is null
                                                 and (e61_numemp is null
                                                      or (e61_numemp is not null and e60_vlremp=e60_vlranu))");
  $result_itens  = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,
                                                                             null,
                                                                             "distinct e54_autori,
                                                                             pc81_codproc,
                                                                             pc81_codprocitem,
                                                                             pc01_codmater,
                                                                             pc01_descrmater,
                                                                             e56_coddot,
                                                                             e56_orctiporec,
                                                                             pc81_solicitem,
                                                                             z01_numcgm,
                                                                             z01_nome,
                                                                             e55_vltot,
                                                                             e55_quant",
                                                                             "z01_numcgm,
                                                                             e56_coddot,
                                                                             e56_orctiporec,
                                                                             pc01_codmater,
                                                                             pc81_codprocitem",
                                                                             "pc81_codproc={$pc80_codproc}
                                                                             and e54_anulad is null
                                                                             and (e61_numemp is null
                                                                                  or (e61_numemp is not null
                                                                                     and e60_vlremp=e60_vlranu))"));
  $numrows_itens = $clempautitem->numrows;

} else if (isset($pc10_numero) && trim($pc10_numero)!="") {

  $sql_itens  = $clempautitem->sql_query_anuaut(null,
                                                null,
                                                "distinct pc11_codigo as cod_item",
                                                null,
                                                "pc11_numero={$pc10_numero}
                                                 and e54_anulad is null
                                                 and (e61_numemp is null
                                                      or (e61_numemp is not null
                                                          and e60_vlremp=e60_vlranu))");
  $result_itens  = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,
                                                                             null,
                                                                             "distinct e54_autori,
                                                                              pc81_codproc,
                                                                              pc81_codprocitem,
                                                                              pc01_codmater,
                                                                              pc01_descrmater,
                                                                              e56_coddot,
                                                                              pc81_solicitem,
                                                                              z01_numcgm,
                                                                              z01_nome,
                                                                              e55_vltot,
                                                                              e55_quant",
                                                                             "z01_numcgm,
                                                                               e56_coddot,
                                                                               pc01_codmater,
                                                                               pc81_codprocitem",
                                                                             "pc11_numero={$pc10_numero}
                                                                                and e54_anulad is null and
                                                                                (e61_numemp is null or
                                                                                (e61_numemp is not null and e60_vlremp=e60_vlranu))"));
  $numrows_itens = $clempautitem->numrows;

}
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      .bordas{
        border: 1px solid #cccccc;
        border-top-color: #999999;
        border-right-color: #999999;
        border-left-color: #999999;
        border-bottom-color: #999999;
        background-color: #cccccc;
      }
      .bordas01{
        border: 1px solid #cccccc;
        border-top-color: #999999;
        border-right-color: #999999;
        border-left-color: #999999;
        border-bottom-color: #999999;
        background-color: #DEB887;
      }
      .bordas02{
        border: 2px solid #cccccc;
        border-top-color: #999999;
        border-right-color: #999999;
        border-left-color: #999999;
        border-bottom-color: #999999;
        background-color: #999999;
      }
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form1">
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            <?
            db_input('valores',8,0,true,'hidden',3);
            $locationh = false;
            $simnaod = "  ";
            if ($numrows_itens==0) {
              $locationh = true;
              echo "<br><br><br><br><br><br><br>";
              echo "      <strong>Autorização não existente ou já empenhada.</strong>\n";
              echo "<script>";
              echo "  parent.document.form1.incluir.disabled=true;";
              echo "</script> ";
            } else {
              if (isset($e54_autori)) {
                db_fieldsmemory($result_itens,0);
              }
              echo "<center>";
              echo "<table border='1' align='center'>\n";
              echo "<tr>";
              echo "  <td colspan='11' align='center'>$Lpc80_codproc";
              echo "    ";
              db_input('pc80_codproc',8,$Ipc80_codproc,true,'text',3);
              echo "    ";
              db_input('e54_codcom',8,$Ie54_codcom,true,'hidden',3);
              echo "    ";
              db_input('e54_codcom',8,$Ie54_codcom,true,'hidden',3);
              echo "    ";
              db_input('e54_codtipo',8,$Ie54_codtipo,true,'hidden',3);
              echo "  </td>";
              echo "</tr>";
              echo "<tr bgcolor=''>\n";
              echo "  <td nowrap class='bordas02' align='center' title='Marcar todos os itens de todas autorizações'><strong>";
              db_ancora("M","js_marcatudo();",1);
              echo "</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Descrição</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Fornecedor</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Dotação</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Quant.</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Val Unit.</strong></td>\n";
              echo "  <td nowrap class='bordas02' align='center'><strong>Val Tot.</strong></td>\n";
              echo "</tr>\n";

              $dot_ant = "";
              $forn_ant= "";
              $contraant = "";
              $contador= 1;
              $testatot= 0;
              $valortotalautori = 0;

              $e54_autori_ant = null;
              for ($i=0; $i<$numrows_itens; $i++) {
                db_fieldsmemory($result_itens,$i);

                if ($dot_ant!=$e56_coddot || $forn_ant!=$z01_numcgm ||$contraant != $e56_orctiporec || $e54_autori != $e54_autori_ant) {
                  if ($contador!=1) {
                    echo "<tr>\n";
                    echo "  <td nowrap colspan='8' class='$bordas'align='right'><strong>Valor Total </strong></td>\n";
                    echo "  <td nowrap colspan='1' class='$bordas'align='right'><strong>R$ ".db_formatar($valortotalautori,"f")."</strong></td>\n";
                    echo "<tr>\n";
                    echo "<tr>\n";
                    echo "  <td nowrap colspan='11' align='center' >&nbsp;</td>\n";
                    echo "</tr>\n";
                  }
                  //----------------Controla andamento da solicitação-------------
                  //------------------------Rogerio--------------------------
                  $result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
                  db_fieldsmemory($result_conand, 0);
                  if (isset($pc30_contrandsol) && $pc30_contrandsol == 't') {
                    $result_testitem=db_query($sql_itens);
                    for ($w=0; $w<pg_numrows($result_testitem); $w++) {
                      db_fieldsmemory($result_testitem,$w);
                      $result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($cod_item));
                      if ($clsolicitemprot->numrows > 0) {
                        $result_andam = $clsolandam->sql_record($clsolandam->sql_query_file(null, "*", "pc43_codigo desc limit 1", "pc43_solicitem=$cod_item"));
                        if ($clsolandam->numrows > 0) {
                          db_fieldsmemory($result_andam, 0);
                          $result_tipo = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "pc48_depto", null, "pc47_solicitem=$cod_item and pc47_ordem=$pc43_ordem"));
                          if ($clsolandpadraodepto->numrows > 0) {
                            db_fieldsmemory($result_tipo, 0);
                            if ( $pc48_depto != db_getsession("DB_coddepto")) {
                              $simnaod = " disabled ";
                            }
                          }
                        }
                      }
                    }
                  }
                  echo "</tr>\n";
                  echo "  <td nowrap colspan='1' class='bordas' align='center'><strong>";
                  echo "  <input type='checkbox' name='aut_$e54_autori' $simnaod  value='aut_'></strong></td>\n";
                  echo "  <td nowrap colspan='10' class='bordas' align='left'><strong>AUTORIZAÇÃO  ".($e54_autori)."</strong></td>\n";
                  echo "</tr>\n";
                  $dot_ant = $e56_coddot;
                  $forn_ant= $z01_numcgm;
                  $contraant = $e56_orctiporec;
                  $e54_autori_ant = $e54_autori;
                  $contador++;
                  $valortotalautori = 0;
                }

                $bordas = "bordas";
                echo "<tr>\n";
                echo "  <td nowrap class='$bordas' align='center'>&nbsp;</td>\n";
                echo "  <td nowrap class='$bordas' align='center'>$pc81_codprocitem</td>\n";
                echo "  <td nowrap class='$bordas' align='center'>$pc01_codmater</td>\n";
                echo "  <td 	   class='$bordas' align='left'  >".ucfirst(strtolower($pc01_descrmater))."</td>\n";
                echo "  <td 	   class='$bordas' align='left'  >$z01_nome</td>\n";
                echo "  <td nowrap class='$bordas' align='center'>$e56_coddot</td>\n";
                echo "  <td nowrap class='$bordas' align='right' >$e55_quant</td>\n";
                echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar($e55_vltot/$e55_quant,"f")."</td>\n";
                echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar($e55_vltot,"f")."</td>\n";
                $valortotalautori += $e55_vltot;
                echo "</tr>\n";
              }
              echo "<tr>\n";
              echo "  <td nowrap colspan='8' class='$bordas'align='right'><strong>Valor Total </strong></td>\n";
              echo "  <td nowrap colspan='1' class='$bordas'align='right'><strong>R$ ".db_formatar($valortotalautori,"f")."</strong></td>\n";
              echo "<tr>\n";
              echo "</table>\n";
              echo "</center>";
              echo "<script>";
              echo "parent.document.form1.incluir.disabled=false;";
              echo "</script>";
            }
            ?>
          </center>
        </td>
      </tr>
    </table>
  </form>
  </body>
  </html>
  <script>
    function js_marcatudo(){
      x = document.form1;
      for(i=0;i<x.length;i++){
        if(x.elements[i].type=='checkbox'){
          if(x.elements[i].disabled==false){
            if(x.elements[i].checked==true){
              x.elements[i].checked=false;
            }else{
              x.elements[i].checked=true;
            }
          }
        }
      }
    }
  </script>
<?
if (isset($incluir)) {
  if ($sqlerro==false && $locationh==true) {
    if (strlen($erro_msg) > 0){
      db_msgbox($erro_msg);
    }

    if (!empty($msg)) {
      db_msgbox($msg);
    }

    echo "<script>parent.document.form1.voltar.click();</script>";
  } else if ($sqlerro==true) {
    db_msgbox($erro_msg);
  }

}
?>