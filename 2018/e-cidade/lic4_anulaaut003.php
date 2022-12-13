<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_pcorcamitem_classe.php"));
require_once(modification("classes/db_pcorcamjulg_classe.php"));
require_once(modification("classes/db_pcorcamval_classe.php"));
require_once(modification("classes/db_orcreserva_classe.php"));
require_once(modification("classes/db_orcreservasol_classe.php"));
require_once(modification("classes/db_orcreservaaut_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_pcdotac_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_empautidot_classe.php"));
require_once(modification("classes/db_pcprocitem_classe.php"));
require_once(modification("classes/db_solicitemprot_classe.php"));
require_once(modification("classes/db_solandam_classe.php"));
require_once(modification("classes/db_solandpadraodepto_classe.php"));
require_once(modification("classes/db_proctransfer_classe.php"));
require_once(modification("classes/db_proctransferproc_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_solordemtransf_classe.php"));

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
$clsolandpadraodepto = new cl_solandpadraodepto;
$clproctransfer      = new cl_proctransfer;
$clproctransferproc  = new cl_proctransferproc;
$clprotprocesso      = new cl_protprocesso;
$clsolordemtransf    = new cl_solordemtransf;
$clrotulo            = new rotulocampo;
$clrotulo->label("pc80_codproc");
$clrotulo->label("e54_codcom");
$clrotulo->label("e54_codtipo");
$clrotulo->label("pc23_valor");

$db_opcao = 1;
$db_botao = true;

$sqlerro   = false;
if (isset($incluir)) {

  $valor = split(",",$valores);
  // arrays para dados do empautoriza
  $arr_vals = Array();
  $arr_cgms = Array();
  $arr_help = Array();
  $indexaut = 0;
  
  // arrays para dados do empautitem
  $arr_proc = Array();
  $arr_hell = Array();
  $indexitm = 0;
  $vir = "";
  
  // Rotina para pegar saldo das dotacoes
  $vetor_dotacao = array();
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  db_inicio_transacao();
  if ($reservar == "true") {
    $flag_reservar = true;
  }
  
  if ($reservar == "false") {
    $flag_reservar = false;
  }
  for ($i = 0; $i < sizeof($valor); $i++) {

    $dados = split("_",$valor[$i]);

    $rsParam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
    $oParam  = db_utils::fieldsMemory($rsParam,0);

    if (isset ($oParam->pc30_contrandsol) && $oParam->pc30_contrandsol == 't') {

      $sSqlItensTransf = $clempautitem->sql_query_anuaut(null,null,"pc11_codigo",null,"e55_autori = ".$dados[1]." and e54_anulad is null");
      $rsItensTransf   = $clempautitem->sql_record();
      $iNroItensTransf = $clempautitem->numrows;

      for ($x = 0; $x < $iNroItensTransf; $x++) {

        $oItensTransf      = db_utils::fieldsMemory($rsItensTransf,$x);
        $sSqlSolicitemProc = $clsolicitemprot->sql_query_transf(null,"max(p63_codtran) as codtran ",null," pc49_solicitem = {$oItensTransf->pc11_codigo}");
        $rsSolicitemProt   = $clsolicitemprot->sql_record($sSqlSolicitemProc);
        $iNroSolicitemProt = $clsolicitemprot->numrows;

        for ($y = 0; $y < $iNroSolicitemProt; $y++) {

          $oSolicitemProt = db_utils::fieldsMemory($rsSolicitemProt,$y);

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

    $clempautoriza->sql_anulaautorizacao($dados[1],false,$erro_msg,$sqlerro,$flag_saldo,$vetor_dotacao,$flag_reservar);
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  db_fim_transacao($sqlerro);
}
$numrows_itens = 0;
if (isset($e54_autori) && trim($e54_autori) != "") {
  
  $sql_itens     = $clempautitem->sql_query_anuaut(null,null," distinct e54_autori,
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
                                                                        "e55_autori = $e54_autori 
                                                                        and e54_anulad is null 
                                                                        and(e61_numemp is null or(e61_numemp is not null and e60_vlremp=e60_vlranu))");
                                                                        
  $result_itens  = $clempautitem->sql_record($sql_itens);
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
.bordas {

  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}
.bordas01 {

  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #DEB887;
}
.bordas02 {

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
    <?php
    db_input('valores',8,0,true,'hidden',3);
    $locationh = false;
    if ($numrows_itens == 0) {
      $locationh = true;
      echo "                                                                                                                                                                                                                                                                                   <br><br><br><br><br><br><br>
            <strong>Autorização não existente ou já empenhada.</strong>\n
           ";
      echo "
            <script>
	      parent.document.form1.incluir.disabled=true;
            </script>
           ";
    } else {

      if (isset($e54_autori)) {
	     db_fieldsmemory($result_itens,0);
      }
      echo "<center>";
      echo "<table border='1' align='center'>\n";
      
      echo "<tr bgcolor=''>\n";
      echo "  <td nowrap class='bordas02' align='center' title='Marcar todos os itens de todas autorizações'><strong>";db_ancora("M","js_marcatudo();",1);echo "</strong></td>\n";
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
      $contador= 1;
      $testatot= 0;
      $valortotalautori = 0;
      
      for ($i=0;$i<$numrows_itens;$i++) {

        db_fieldsmemory($result_itens,$i);

        if ($dot_ant!=$e56_coddot || $forn_ant!=$z01_numcgm) {

          if ($contador != 1) {

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
          if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {

            $result_testitem=db_query($sql_itens);
            $numrows_testitem=pg_numrows($result_testitem);
            for ($w = 0;$w < $numrows_testitem; $w++) {

              db_fieldsmemory($result_testitem,$w);
              $result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($cod_item));
              if ($clsolicitemprot->numrows > 0) {

                $result_andam = $clsolandam->sql_record($clsolandam->sql_query_file(null, "*", "pc43_codigo desc limit 1", "pc43_solicitem=$cod_item"));
                if ($clsolandam->numrows > 0) {

                  db_fieldsmemory($result_andam, 0);						
                  $result_tipo = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$cod_item and pc47_ordem=$pc43_ordem"));
                  if ($clsolandpadraodepto->numrows > 0) {

                    db_fieldsmemory($result_tipo, 0);
                    if ($pc47_pctipoandam != 5 || $pc48_depto != db_getsession("DB_coddepto")) {
                      $simnaod = " disabled ";
                    }
                  }
                }
              }
            }
          }
          echo "</tr>\n";
          echo "  <td nowrap colspan='1' class='bordas' align='center'><strong><input type='checkbox' name='aut_$e54_autori' value='aut_'></strong></td>\n";
          echo "  <td nowrap colspan='10' class='bordas' align='left'><strong>AUTORIZAÇÃO  ".($e54_autori)."</strong></td>\n";
          echo "</tr>\n";
          $dot_ant = $e56_coddot;
          $forn_ant= $z01_numcgm;
          $contador++;
          $valortotalautori = 0;
        }

        $bordas = "bordas";
        echo "<tr>\n";
        echo "  <td nowrap class='$bordas' align='center'>&nbsp;</td>\n";
        echo "  <td nowrap class='$bordas' align='center'>$pc81_codprocitem</td>\n";
        echo "  <td nowrap class='$bordas' align='center'>$pc01_codmater</td>\n";
        echo "  <td class='$bordas' align='left' >".ucfirst(strtolower($pc01_descrmater))."</td>\n";
        echo "  <td class='$bordas' align='left' >$z01_nome</td>\n";
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
      echo "<script>
        parent.document.form1.incluir.disabled=false;
            </script>";
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
function js_marcatudo() {

  x = document.form1;
  for (i = 0;i < x.length;i++) {

    if (x.elements[i].type == 'checkbox') {

      if (x.elements[i].disabled == false) {

        if (x.elements[i].checked == true) {
          x.elements[i].checked=false;
        } else {
          x.elements[i].checked=true;
        }        
      }
    }
  }
}
</script>
<?php
if (isset($incluir)) {

  if ($sqlerro == false && $locationh == true) {
    echo "<script>parent.document.form1.voltar.click();</script>";
  } else if ($sqlerro == true) {
    db_msgbox($erro_msg);
  }
}
?>
