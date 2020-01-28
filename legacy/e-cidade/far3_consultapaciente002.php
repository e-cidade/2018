<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//modulo farmacia
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_retirada_classe.php");
//db_postmemory($HTTP_POST_VARS);
//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clfar_retirada = new cl_far_retirada;
$clfar_retirada->rotulo->label();
?>

<form name="formconsulta">
	<input name="xData1" value="<?=@$data1?>" type="hidden">
	<input name="xData2" value="<?=@$data2?>" type="hidden">	
</form>

<table width="100%">
 <tr>
  <td align="center" valign="top">
  	
<?
   if(isset($consultar)){

    ?><fieldset style="width:95%"><legend><b>Retirada</b></legend>
	
    <?$campos = "fa04_i_codigo,
	             fa07_i_matrequi,
                 fa04_d_data,
                 fa04_c_numeroreceita,
				 fa03_c_descr,
                 z01_nome
              ";
    $where = "";
    if(isset($chave_fa04_i_cgsund) && (trim($chave_fa04_i_cgsund)!="") ){
     $where .= " AND fa04_i_cgsund = $chave_fa04_i_cgsund";
    }
    if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
     $where .= " AND z01_nome like '$chave_z01_nome%'";
    }
    if(isset($data1) && (trim($data1)!="" and $data1!="//") and isset($data2) && (trim($data2)!="" and $data2!="//")){
	 $d1= substr($data1,6,4)."/".substr($data1,3,2)."/".substr($data1,0,2);
     $d2= substr($data2,6,4)."/".substr($data2,3,2)."/".substr($data2,0,2);   	
     $where .="  AND fa04_d_data BETWEEN '$d1' AND '$d2' ";	 
    }
     
    $sql = $clfar_retirada->sql_query(""," distinct ".$campos,""," fa04_i_cgsund = $fa04_i_cgsund".$where);
    $repassa = array("fa04_i_cgsund"=>$fa04_i_cgsund);
    if(isset($chave_fa04_i_cgsund)){
    $repassa = array("chave_fa04_i_cgsund"=>$chave_fa04_i_cgsund,"chave_fa04_i_cgsund"=>$chave_fa04_i_cgsund);
    }
    if(isset($consultar)){
    	db_lovrot(@$sql,10,"()","","js_itens|fa04_i_codigo|fa07_i_matrequi","","NoMe",$repassa);
    }
    ?></fieldset><br><br><?
   }
   ?>
    </td>
  </tr>
</table>
  <center>
<table>
   <tr>
    <td>
       <input name="imprimir1" type="submit" id="imprimir1" value="Imprimir Sintético" onclick='js_imprimesintetico();' >
       <input name="imprimir2" id="imprimir2" type="button" value="Imprimir Analítico" onclick='js_imprimeanalitico();' >
  </td>
 </tr>
</table>
</center>
<script>

function js_itens(fa04_i_codigo,fa07_i_matrequi){
	parametros = '?consultar&chavepesquisaconsulta='+fa04_i_codigo+'&fa04_i_cgsund=<?=$fa04_i_cgsund?>&z01_v_nome=<?=$z01_v_nome?>&fa07_i_matrequi='+fa07_i_matrequi;
	parametros += "&data1="+document.formconsulta.xData1.value+"&data2="+document.formconsulta.xData2.value;
    location.href='far3_consultapaciente003.php'+parametros;                                             
}
function js_imprimesintetico(){
	parametros = '?fa04_i_cgsund=<?=$fa04_i_cgsund?>&z01_v_nome=<?=$z01_v_nome?>';
	parametros += "&data1="+document.formconsulta.xData1.value+"&data2="+document.formconsulta.xData2.value;
    jan = window.open('far2_sintetico001.php'+parametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);	
}
function js_imprimeanalitico(){
	parametros = '?fa04_i_cgsund=<?=$fa04_i_cgsund?>&z01_v_nome=<?=$z01_v_nome?>';
	parametros += "&data1="+document.formconsulta.xData1.value+"&data2="+document.formconsulta.xData2.value;
    jan = window.open('far2_analitico001.php'+parametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
}
</script>