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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
include("../classes/db_matordem_classe.php");
include("../classes/db_matordemitem_classe.php");
include("../dbforms/db_funcoes.php");
include("../classes/db_empempenho_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clempempenho = new cl_empempenho;
$clmatordemitem = new cl_matordemitem;
$clmatordem  = new cl_matordem;

$clmatordemitem->rotulo->label();
$clmatordem->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("e62_item");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e62_descr");
$clrotulo->label("pc23_obs");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script>


</script>

<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <center>
 <table border='1' cellspacing="0" cellpadding="0">   
 
 <?
 $where="";
 
 if (isset($m51_codordem) && $m51_codordem!= "") {

 if (isset($e60_numemp) && $e60_numemp!=''){
  $where= " and m52_numemp = $e60_numemp ";
 }

     
      $campos  = "e60_codemp, m52_numemp, e62_item, e62_vltot, e62_quant, pc01_descrmater, m52_sequen, e62_descr, m52_quant, m52_vlruni, m52_valor, pc23_obs, solicitem.pc11_codigo, ";
      $campos  = "distinct ".$campos;
      $campos .= "(select coalesce(sum(m36_qtd), 0) from matordemitemanu where m36_matordemitem = m52_codlanc) as qtdanulado,";
      $campos .= "(select coalesce(sum(m36_vrlanu),0) from matordemitemanu where m36_matordemitem = m52_codlanc) as valoranulado";

      $sSqlItens = $clmatordemitem->sql_query_ordcons(null,"$campos","","m52_codordem=$m51_codordem $where"); 
      $result    = $clmatordemitem->sql_record($sSqlItens);
      $numrows   = $clmatordemitem->numrows;

    	if ( $numrows > 0 ) {

	       echo "<tr class='bordas'>
          	      <td class='bordas' align='center'><b><small> $RLe60_codemp      </small></b></td>
	                <td class='bordas' align='center'><b><small> $RLe60_numemp      </small></b></td>
	                <td class='bordas' align='center'><b><small> $RLe62_item        </small></b></td>
	                <td class='bordas' align='center'><b><small> $RLpc01_descrmater </small></b></td>
	                <td class='bordas' align='center'><b><small> $RLm52_sequen      </small></b></td>
	                <td class='bordas' align='center'><b><small> $RLe62_descr       </small></b></td>
	                <td class='bordas' align='center'><b><small> $RLpc23_obs        </small></b></td>
                  <td class='bordas' align='center'><b><small> $RLm52_quant       </small></b></td>
	                <td class='bordas' align='center'><b><small> Valor unitário     </small></b></td>
                  <td class='bordas' align='center'><b><small> Valor total        </small></b></td>
                  <td class='bordas' align='center'><b><small> Qtd Anulada        </small></b></td>
                  <td class='bordas' align='center'><b><small> Valor Anulado      </small></b></td>";
      } else echo"<b>Nenhum registro encontrado...</b>";
	 
      echo " </tr>";

      for ($i=0; $i<$numrows; $i++) {
				
	       db_fieldsmemory($result, $i);
	       
	       if ( $pc23_obs == "" && !empty($pc11_codigo) ) {

          /*
           * Buscamos a observação do lançamento de valores da licitação da solicitação de origem caso se trate de registro de preço
           */
	         $sSqlObs  = "select pc23_obs "; 
	         $sSqlObs .= " from solicitemvinculo ";
           $sSqlObs .= " inner join solicitem      on solicitem.pc11_codigo          = solicitemvinculo.pc55_solicitempai ";
           $sSqlObs .= " inner join pcprocitem     on pcprocitem.pc81_solicitem      = solicitem.pc11_codigo ";
           $sSqlObs .= " inner join liclicitem     on liclicitem.l21_codpcprocitem   = pcprocitem.pc81_codprocitem ";
                       
           $sSqlObs .= " inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo ";
           $sSqlObs .= " inner join pcorcamjulg    on pcorcamjulg.pc24_orcamitem     = pcorcamitemlic.pc26_orcamitem "; 
           $sSqlObs .= "                          and pcorcamjulg.pc24_pontuacao     = 1 ";
           $sSqlObs .= " inner join pcorcamval     on pcorcamval.pc23_orcamitem      = pcorcamjulg.pc24_orcamitem ";
           $sSqlObs .= "                           and pcorcamval.pc23_orcamforne    = pcorcamjulg.pc24_orcamforne "; 
           $sSqlObs .= " where solicitemvinculo.pc55_solicitemfilho = {$pc11_codigo}";
            
           $rsObs = db_query($sSqlObs);
           if (pg_num_rows($rsObs) > 0) {
             db_fieldsmemory($rsObs,0);              
           }
	       }
	       
	       echo "<tr>	    
   	              <td	 class='bordas_corp' align='center'><small>$e60_codemp </small></td>
   	              <td	 class='bordas_corp' align='center'><small>$m52_numemp </small></td>
    	            <td	 class='bordas_corp' align='center'><small>$e62_item  </small></td>		    
    		          <td	 class='bordas_corp' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>
	                <td	 class='bordas_corp' align='center'><small>$m52_sequen</small></td>
                  <td	 class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr,0,20)."&nbsp;</small></td>
                  <td  class='bordas_corp' nowrap align='left' title='$pc23_obs'><small>".substr($pc23_obs,0,15)."...&nbsp;</small></td>
	                <td	 class='bordas_corp' align='center'><small>$m52_quant</small></td>
	                <td	 class='bordas_corp' align='right'><small>$m52_vlruni</small></td>
    	            <td	 class='bordas_corp' align='right'><small>".db_formatar($m52_valor,'f')."</small></td>
	                <td	 class='bordas_corp' align='right'><small>$qtdanulado</small></td>
	                <td	 class='bordas_corp' align='right'><small>$valoranulado</small></td>
               </tr> ";
	    }
 }
?>     
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
<script>
 


</script>
</body>
</html>