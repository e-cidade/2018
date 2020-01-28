<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_empparametro_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamval = new cl_pcorcamval;
$clempparametro = new cl_empparametro;
$db_opcao=1;
$db_botao=true;

$numrows_pcorcamitem = 0;

$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"),"e30_numdec")); 

if ($clempparametro->numrows > 0){
	
  db_fieldsmemory($res_empparametro,0);

  if (trim($e30_numdec) == "" || $e30_numdec == 0){
    $numdec = 2;
  } else { 
    $numdec = $e30_numdec;
  }
   
} else {
	
  $numdec = 2;
   
}


if(isset($orcamento) && trim($orcamento)!=""){
	
  if($sol=="true"){
    $result_pcorcamitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmatersol(null,"distinct pc22_orcamitem,pc01_descrmater,pc11_resum,pc22_orcamitem","pc22_orcamitem","pc22_codorc=$orcamento and pc22_orcamitem in (select distinct pc23_orcamitem from pcorcamval)"));
  }else if($sol=="false"){
    $result_pcorcamitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc(null,"distinct pc81_codproc,pc22_orcamitem,pc01_descrmater,pc11_resum,pc22_orcamitem, pc80_tipoprocesso","pc22_orcamitem","pc22_codorc=$orcamento and pc22_orcamitem in (select distinct pc23_orcamitem from pcorcamval)"));
  }
  
  $numrows_pcorcamitem = $clpcorcamitem->numrows;

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
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}

#div_tipo {
  width     : 50%; 
  float     : left;
  text-align: right;
}

#div_modelo {
  width  : 50%; 
  float  : left;
  display: none;
}

#div_modelo strong {
  margin-left: 5px; 
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="5" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC" width="100%">
    <center>
    <?
    if($numrows_pcorcamitem==0){
      echo "<strong>Não existem itens para realizar troca neste orçamento.</strong>\n";
    }else{
    	
      $bordas = "bordas";
      
      echo "<center>";
      echo "<table border='1' align='center' width='100%'>\n";
      echo "<tr>";
      echo "  <td colspan='10' nowrap><strong><font size='3'>Itens do Orçamento</font></strong></td>";
      echo "</tr>";
      echo "<tr>\n";
      echo "  <td nowrap colspan='10' class='bordas02' align='center'><strong>Para poder trocar fornecedor de um item, clique em 'Trocar', no item desejado.</strong></td>\n";
      echo "</tr>\n";
      echo "<tr bgcolor=''>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Trocar</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Obs</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Fornecedor</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Quantidade.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Valor Unit.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Valor Tot.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Resumo</strong></td>\n";
      echo "</tr>\n";
  

 for($i=0;$i<$numrows_pcorcamitem;$i++){

	db_fieldsmemory($result_pcorcamitem,$i);
	
	$result_fornecedor = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,null,"distinct pc24_orcamforne,z01_nome","","pc24_orcamitem=$pc22_orcamitem and pc24_pontuacao=1"));
	
	if($clpcorcamjulg->numrows > 0){
		
	  db_fieldsmemory($result_fornecedor,0);

  	  if (!isset($pc24_orcamforne)){
        continue;
      }


	  $result_quantvalor = $clpcorcamval->sql_record($clpcorcamval->sql_query_file($pc24_orcamforne,$pc22_orcamitem,"distinct pc23_valor,pc23_quant,pc23_vlrun,pc23_obs"));

      if($clpcorcamval->numrows>0){
      	
	    db_fieldsmemory($result_quantvalor,0);
		echo "<tr>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc22_orcamitem</td>\n";
		echo "  <td nowrap class='$bordas' align='center' title='Clique para efetuar troca de fornecedor do item'>";db_ancora("Trocar","js_troca($pc22_orcamitem,$orcamento,$sol);",1);echo"</td>\n";
		echo "  <td class='$bordas' align='left' >  ".ucfirst(strtolower($pc01_descrmater))."&nbsp;</td>\n";
		echo "  <td class='$bordas' align='left' >  ".ucfirst(strtolower($pc23_obs))."&nbsp;</td>\n";
		echo "  <td class='$bordas' align='center' >$z01_nome</td>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc23_quant</td>\n";
		echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar(($pc23_vlrun),"f"," ",' ',"e",$numdec ,"f")."</td>\n";
		echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar(($pc23_valor),"f"," ",' ',"e",2)."</td>\n";
		echo "  <td class='$bordas' align='left'>".substr(stripslashes($pc11_resum),0,40)."&nbsp;</td>\n";
		echo "</tr>\n";
	  }
	}
  } 



      if (isset($pc81_codproc) && trim($pc81_codproc) != ""){
        $anousu    = db_getsession("DB_anousu");
        $id_modulo = db_getsession("DB_modulo");
        if (db_permissaomenu($anousu,$id_modulo,5013) == "true"){  // 5013 - Item de menu MAPA DAS PROPOSTAS DO ORÇAMENTO
          
          /**
           * Troca o rodape se o orçamento do processo de compras for por lote
           */
          if ($pc80_tipoprocesso == 2) {
            ?>
              <tr>
                <td nowrap colspan='9' height='50'>
                  <div id="div_tipo">
                    <input type='button' value='Imprimir mapa de propostas' onClick='js_escolher_relatorio(true, <?=$orcamento?>);'> 
                    <strong>Tipo:</strong>
                    <?php
                      $aTipoProcesso = array("2"=>"Por Lote", "1"=>"Por Item");
                      db_select("cbxTipoProcesso", $aTipoProcesso, true, 4, "onChange='js_mostrar_modelos(this.value);'");
                    ?>
                  </div>  
                  <div id="div_modelo">
                    <strong>Modelo:</strong>
                    <?php
                      $aModelos = array("1"=>"Modelo 1","2"=>"Modelo 2");
                      db_select("cbxModelo", $aModelos, true, 4);
                    ?>
                  </div>
                </td>
              </tr> 
            <?php
          } else {
            ?>
              <tr>
                <td nowrap align='center'colspan='9' height='50'>
                 <input type='button' value='Imprimir mapa de propostas' onClick='js_escolher_relatorio(false, <?=$orcamento?>);'> 
                 <strong>Modelo:</strong>
                 <?php
                   $aModelos = array("1"=>"Modelo 1","2"=>"Modelo 2");
                   db_select("cbxModelo", $aModelos, true, 4);
                 ?>
                </td>
              </tr>   
            <?php
          }   
        }
      }

      echo "</table>\n";
      echo "</center>";
    }
    ?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>
function js_mostrar_modelos(iTipoProcesso){
  
  if (iTipoProcesso == 1) {
    document.getElementById("div_modelo").style.display = "block";
    return true;
  }
  
  if (iTipoProcesso == 2) {
    document.getElementById("div_modelo").style.display = "none";
    return true;
  }
}

function js_escolher_relatorio(lTipoLote, iCodigoOrcamento) {
  
  var iTipoProcesso = 1;
  
  if (lTipoLote){
    iTipoProcesso = document.getElementById("cbxTipoProcesso").value;
  }
  
  if (iTipoProcesso == 2) {
    js_exibir_mapa_por_lote(iCodigoOrcamento);
  } else {
    js_exibir_mapa_por_item(iCodigoOrcamento);
  }
  
}

function js_exibir_mapa_por_item(iCodigoOrcamento) {
  
  var sTipoModelo = document.getElementById('cbxModelo').value;
  
  var sUrl          = 'com2_mapaorc002.php?';
  var sQueryString  = 'pc20_codorc=' + iCodigoOrcamento;
  sQueryString     += '&modelo=' + sTipoModelo;
  sQueryString     += '&tipoOrcamento=processo';
  sQueryString     += '&imp_troca=S';
  
  var jan = window.open(sUrl + sQueryString,
                        '',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_exibir_mapa_por_lote(iCodigoOrcamento) {
  
  var sUrl          = 'com2_mapaorcamentolote002.php?';
  var sQueryString  = 'iOrcamento=' + iCodigoOrcamento;
  sQueryString     += '&sJustificativa=S';
  
  var jan = window.open(sUrl + sQueryString,
                        '',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_troca(codigo,orcamento,sol){
  top.corpo.document.location.href = 'com1_trocpcorcamtroca001.php?pc25_orcamitem='+codigo+'&orcamento='+orcamento+'&sol='+sol;
}
</script>
</body>
</html>