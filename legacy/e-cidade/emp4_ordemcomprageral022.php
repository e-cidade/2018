<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("classes/db_empempenho_classe.php");
require("classes/db_matordemitem_classe.php");
include("classes/db_empparametro_classe.php");

$oPost            = db_utils::postMemory($_POST,0);
$oDaoEmpenho      = new cl_empempenho;
$oDaoMatOrdemItem = new cl_matordemitem;
$clrotulo         = new rotulocampo;
$oDaoEmpenho->rotulo->label();
$clrotulo->label("z01_nome");

//aqui criamos a clausula where, conforme dados passados pelo usuário.
$sWhere = " e60_anousu = ".db_getsession("DB_anousu")." and e60_instit = ".db_getsession("DB_instit");
$sOrder = "e60_emiss desc";$sOrder = "e60_emiss desc";$sOrder = "e60_emiss desc";$sOrder = "e60_emiss desc";
if (isset($oPost->data) && $oPost->data != '' && isset($oPost->data1) && $oPost->data1 != '') {

   $dDataIni  = implode("-", array_reverse(explode("/", $oPost->data)));
   $dDataFim  = implode("-", array_reverse(explode("/", $oPost->data1)));
   $sWhere   .= " and e60_emiss between '{$dDataIni}' and '{$dDataFim}'";

} else if (isset($oPost->data) && $oPost->data != '') {

  $dDataIni = implode("-", array_reverse(explode("/", $oPost->data)));
  $sWhere   = " and e60_dtemiss >= '{$dDataIni}'";

} else if (isset($oPost->data1) && $oPost->data1 != '') {

  $dDataFim = implode("-", array_reverse(explode("/", $oPost->data1)));
  $sWhere   = " and e60_dtemiss <= '{$dDataFim}'";

}

if (isset($oPost->e60_numcgm) && $oPost->e60_numcgm != '') {
   
  $sWhere .= " and   e60_numcgm = {$oPost->e60_numcgm}";
  $sOrder = "e60_numcgm";

}

$sWhere .= " and not EXISTS (select 1 from empanulado where e94_numemp = e60_numemp) ";
$sWhere .= " and exists ( SELECT 1";
$sWhere .= "                from empempitem ";
$sWhere .= "                     left join matordemitem on e62_sequen = m52_sequen ";
$sWhere .= "                                           and e62_numemp = m52_numemp ";
$sWhere .= "                     left join matordemitemanu on m36_matordemitem = m52_codlanc ";
$sWhere .= "               where e62_numemp = e60_numemp ";
$sWhere .= "               group by e62_sequen,e62_vltot ";
$sWhere .= "               having (sum(coalesce(m52_valor, 0))-sum(coalesce(m36_vrlanu, 0))) < e62_vltot) ";

if (isset($oPost->emp_liberado) &&  $oPost->emp_liberado == 't') {
	
	$sWhere .= "and exists(select 1 from empempenholiberado where e22_numemp= e60_numemp)";
	$sWhere .= "and exists(select 1 from desdobramentosliberadosordemcompra where pc33_codele = e64_codele ";
	$sWhere .= "                                                              and pc33_anousu = ".db_getsession("DB_anousu").")"; 
	
}

$sSqlEmpenhos = $oDaoEmpenho->sql_query_empnome(null, "*", $sOrder, $sWhere);


$rsEmpenho    = $oDaoEmpenho->sql_record($sSqlEmpenhos);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>  
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>  
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
 <table  align="center" width="70%">
   <tr>
     <td>
       <fieldset><legend><b>Emissão</b></legend>
         <table>
           <tr >
             <td  align="center" nowrap title="Ordem Alfabética/Numérica" >
               <strong>Emitir por:&nbsp;&nbsp;</strong>               
	             <? 
	               $tipo_ordem = array("E"=>"Empenho","F"=>"Fornecedor");
	               db_select("emitir",$tipo_ordem,true,2);
               ?>
             </td>
           </tr>
          </table>
       </fieldset>
     </td>  
   </tr>  
   <tr>
     <td colspan=2 align='center'>
       <input  name="processar" value='Processar' type='button' onclick='js_submitframe();' >  
     	 <input  name="voltar" value='Voltar' type='button' onclick="location.href='emp4_ordemcomprageral01.php'" >  
     </td>
   </tr>
   <tr>
     <td>
       <fieldset><legend><b>Empenhos</b></legend>
         <?
           if ($oDaoEmpenho->numrows > 0) {
             echo "<table cellspacing=0 style='border:2px inset white' width='100%'>";
             echo " <tr>";
             echo "   <th class='table_header' title='Marca/desmarca todos' align='center'>";
             echo "     <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>";
             echo "     <a onclick='js_marca()' style='cursor:pointer'>M</a></b>";
             echo "    </th>";
             echo "    <th class='table_header'>";
             echo         str_replace(":","",$Le60_numemp);
             echo "    </th>";
             echo "    <th class='table_header'>";
             echo         str_replace(":","",$Le60_codemp);
             echo "    </th>";
             echo "    <th class='table_header'>";
             echo         "Data de Liberação";
             echo "    </th>";
             echo "    <th class='table_header'>";
             echo         str_replace(":","",$Lz01_nome);
             echo "    </th>";
             echo "    <th class='table_header'>";
             echo         str_replace(":","",$Le60_emiss);
             echo "    </th>";
             echo "    <th class='table_header'>";
             echo "       Depto Origem ";        
             echo "    </th>";
             echo "    <th class='table_header' style='width:18px'>";
             echo "      &nbsp;";
             echo "    </th>";
             echo "  </tr>";           
             echo "  <tbody style='height:150;overflow:scroll;overflow-x:hidden;background-color:white'>";
             for ($i = 0; $i < $oDaoEmpenho->numrows; $i++ ) {
               
               $oEmpenho = db_utils::fieldsMemory($rsEmpenho, $i);

									
							 $sSqlEmpOrigem = "select * from fc_origem_empenho({$oEmpenho->e60_numemp})";
							 $rsEmpOrigem   = db_query($sSqlEmpOrigem) or die($sSqlEmpOrigem); 
							 $oEmpOrigem    = db_utils::fieldsMemory($rsEmpOrigem,0); 

							 for ($x = 0; $x < pg_num_rows($rsEmpOrigem); $x++) {		
									$oEmpOrigem   = db_utils::fieldsMemory($rsEmpOrigem,$x); 
									$aEmpOrigem[] = $oEmpOrigem->ridepto;
							 }
               
							 echo " <tr id='trchk{$oEmpenho->e60_numemp}' style='height:1em'>";	    
               echo "  <td class='linhagrid' title='Inverte a marcação' align='center'>";
               echo "     <input type='checkbox' id='chk{$oEmpenho->e60_numemp}' class='itensEmpenho'";
               echo "           value='{$oEmpenho->e60_numemp}' onclick='js_marcaLinha(this);js_countSelecionados();'>";
               echo "     <input type='hidden' id='{$oEmpenho->e60_numemp}' value='{$oEmpOrigem->ridepto}'> ";
							 echo "  </td>"; 
               echo "   <td class='linhagrid'>";
               echo       $oEmpenho->e60_numemp; 
               echo "    </td>";
               echo "    <td class='linhagrid'>";
               echo     "<a href='#' onclick='js_JanelaAutomatica(\"empempenho\", {$oEmpenho->e60_numemp});'>";
               echo       "{$oEmpenho->e60_codemp}/{$oEmpenho->e60_anousu}</a>";
               echo "    </td>";
               echo "    <td class='linhagrid'>";
               echo         $oEmpenho->e22_data ? db_formatar($oEmpenho->e22_data, 'd') : '&nbsp;';
               echo "    </td>";               
               echo "    <td class='linhagrid'>";
               echo         $oEmpenho->z01_nome;
               echo "    </td>";
               echo "    <td class='linhagrid'>";
             
               echo         db_formatar($oEmpenho->e60_emiss,"d");
               echo "    </td>";
               echo "    <td class='linhagrid'>";
							 echo         implode(" - ",array_unique($aEmpOrigem));
							 echo "    </td>";
               echo "</tr>";
							 unset($aEmpOrigem);
			   			 
								
						 }
             echo "<tr style='height:auto'><td>&nbsp;</td></tr>";							 
             echo "  </tbody>";
             
             echo "<tfoot>";
             echo "<tr>";
             echo "    <td colspan='8' style='text-align:left' class='table_footer'>";
             echo       "Total de Registros: ";
             echo       "<span style='border-right:2px groove white;color:blue'>".pg_num_rows($rsEmpenho)." </span> ";
             echo       " <span style='color:blue;' id='totalSelecionados'>&nbsp;0</span> Itens selecionados";             
             echo "    </td>";
             echo "</tr>";
             
             echo "</tfoot>";
             
             echo "</table>";
           } else {
             echo "Não foram encontrados empenhos com o filtro selecionado.";
           }
          ?>
      </fieldset>  
    </td>
  </tr>
 </table> 
</form>
 <?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 ?>
</body>
</html>
<script>

function js_countSelecionados() {

  var aCheckbox = $$('input.itensEmpenho');
  var iTotal    = 0;
  aCheckbox.each(
    function(checkbox, iSequencial) {
      if(checkbox.checked) {
        iTotal++;
      }
    }
  ) 
  
  $('totalSelecionados').innerHTML = "&nbsp;"+iTotal;
  
}



function js_submitframe() {
    
  var iTotItens      = 0;
	var sSep           = "";
  var	iDepto         = "";
	var sListaEmpenhos = "";
	var lVerificaDepto = false;
  var aEmpenhos      = js_getElementbyClass(form1,'itensEmpenho');

	for (iInd = 0; iInd < aEmpenhos.length; iInd++) {
   
    if (aEmpenhos[iInd].checked) {

			iDepto				 += sSep+document.getElementById(aEmpenhos[iInd].value).value;
			sListaEmpenhos += sSep+aEmpenhos[iInd].value;
      sSep           = "_";
      iTotItens++;
      
    }  
   
	}

  if (iTotItens == 0) {
    alert("Não há empenhos selecionados.");
  } else {
    url           = "emp4_ordemcomprageral033.php?listagem_depto="+iDepto+"&listagem_empenhos="+sListaEmpenhos+"&emitir="+$F("emitir");
    location.href =  url;
  }  

}

function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'itensEmpenho');
	 for (i = 0;i < itens.length;i++){
     if (itens[i].disabled == false){
        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
     }
	 }
	 js_countSelecionados();
}
function js_marcaLinha(obj){
 
  if (obj.checked){
   $('tr'+obj.id).className='marcado';
  }else{
   $('tr'+obj.id).className='normal';
  }  

}

</script>