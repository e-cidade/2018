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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
require ("libs/db_utils.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_db_depart_classe.php");
include ('classes/db_db_almox_classe.php');
include ("classes/db_matestoqueitemlote_classe.php");
include ("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS ["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_depart = new cl_db_depart();
$clmatestoqueitemlote = new cl_matestoqueitemlote();
$cldb_almox = new cl_db_almox();
$clrotulo = new rotulocampo();
$clrotulo->label("");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
  src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
<style>
<?//$cor ="#999999 "?>.bordas {
	border: 2px solid #cccccc;
	border-top-color: #999999;
	border-right-color: #999999;
	border-left-color: #999999;
	border-bottom-color: #999999;
	background-color: #999999;
}

.bordas_corp {
	border: 1px solid #cccccc;
	border-top-color: #999999;
	border-right-color: #999999;
	border-left-color: #999999;
	border-bottom-color: #999999;
	background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
  marginheight="0">
<table border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr>
    <td align="center" valign="top">
    <form name='form1'>
    <table border='0'>
      <tr>
        <td colspan=6 align=center>
      <?php 
        if (!$lNovaConsulta) {
	        echo "<input type='button' value='Voltar' onclick='parent.db_iframe_lancamentos.hide();' >";
        }
      ?>
      </td>
      </tr>
  
  
<?
  
db_input('codmater', 10, '', true, 'hidden', 3);

if (isset($codmater) && $codmater != "") {
  $where = "";
  $and = "";
  if (isset($departamento) && $departamento != 0 && $departamento != "") {
    $where .= " and m80_coddepto=$departamento ";
  }
  if (isset($lancamento) && $lancamento != 0 && $lancamento != "") {
    $where .= " and m80_codtipo=$lancamento ";
  }
  if (isset($db_where) && $db_where != "") {
    if ($db_where == "D") {
      $depto_atual = db_getsession("DB_coddepto");
      $where .= "  and m80_coddepto=$depto_atual ";
    } else {
      
      $where .= " and $db_where  ";
    }
  }
  $where .= " and instit = " . db_getsession("DB_instit");
  if (isset($db_inner) && $db_inner != "") {
    $inner = "  $db_inner  ";
  } else {
    $inner = "";
  }
  $whereLote = '';
  if (isset($lotesitem) && $lotesitem != 0) {
    
    $whereLote .= " and m77_sequencial = {$lotesitem}";
  }
  if (isset($m70_coddepto) && trim($m70_coddepto) != "") {
    $whereLote .= " and m70_coddepto = {$m70_coddepto}"; 
  }
   
  $sOrder        = "order by m80_data,m80_codigo";
  $sSQLInfoLote  = "select m77_sequencial as db_m77_sequencial,";
  $sSQLInfoLote .= "       m77_lote,";
  $sSQLInfoLote .= "       m77_dtvalidade,";
  $sSQLInfoLote .= "       m81_descr,";
  $sSQLInfoLote .= "       m82_quant,";
  $sSQLInfoLote .= "       m71_quantatend,";
  $sSQLInfoLote .= "       m80_data,";
  $sSQLInfoLote .= "       descrdepto";
  $sSQLInfoLote .= "  from matestoqueitemlote";
  $sSQLInfoLote .= "        inner join matestoqueitem   on m77_matestoqueitem = m71_codlanc";
  $sSQLInfoLote .= "        inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc";
  $sSQLInfoLote .= "        inner join matestoqueini    on m82_matestoqueini  = m80_codigo";
  $sSQLInfoLote .= "        inner join matestoquetipo   on m80_Codtipo        = m81_codtipo";
  $sSQLInfoLote .= "        inner join matestoque       on m70_codigo         = m71_codmatestoque";
  $sSQLInfoLote .= "        inner join db_depart        on m70_coddepto       = coddepto";
  $sSQLInfoLote .= $inner;
  $sSQLInfoLote .= " where m70_codmatmater = {$codmater}";
  $sSQLInfoLote .= $where;
  
  $rsInfoLote = $clmatestoqueitemlote->sql_record($sSQLInfoLote);
  $aLotes [0] = "Todos";
  for($iItem = 0; $iItem < $clmatestoqueitemlote->numrows; $iItem ++) {
    
    $oLotes = db_utils::fieldsMemory($rsInfoLote, $iItem);
    $aLotes [$oLotes->db_m77_sequencial] = "Lote {$oLotes->m77_lote} (" . db_formatar($oLotes->m77_dtvalidade, "d") . ")";
  
  }
  $sSQLInfoLote .= $whereLote;
  $sSQLInfoLote .= $sOrder;
  ?>
<tr>
        <td><b>Lote:</b>
    <?
  db_select("lotesitem", $aLotes, 1, 1, "onchange='document.form1.submit()'");
  ?>
  </td>
        <td><b>Deposito:</b>
    <?
  $rsDepositos = $cldb_almox->sql_record($cldb_almox->sql_query(null, "coddepto,descrdepto"));
  db_selectrecord("m70_coddepto", $rsDepositos, true, 1, null, null, "", " ", "document.form1.submit()");
  ?>
  </td>
        </form>
      </tr>
      <tr>
        <td colspan=6 align=center>

<?
  db_lovrot($sSQLInfoLote, 15, "", "", "");
}
?>  
 
</td>
      </tr>
    </table>
    
    </td>
  </tr>
</table>
<script>
</script>
</body>
</html>