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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
include("../classes/db_matordem_classe.php");
include("../classes/db_matordemitem_classe.php");
include("../classes/db_matestoqueitemoc_classe.php");
include("../classes/db_matestoqueitem_classe.php");
include("../classes/db_matestoqueitemnota_classe.php");
include("../classes/db_empempenho_classe.php");
include("../classes/db_pcmater_classe.php");
include("../classes/db_matmater_classe.php");
include("../classes/db_matunid_classe.php");
include("../dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueitemnota = new cl_matestoqueitemnota;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatordemitem = new cl_matordemitem;
$clmatordem  = new cl_matordem;
$clempempenho = new cl_empempenho;
$clpcmater = new cl_pcmater;
$clmatmater = new cl_matmater;
$clmatunid = new cl_matunid;

$clmatordemitem->rotulo->label();
$clmatordem->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("e62_item");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e62_descr");

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
<?//$cor="#999999"?>
.bordas{
  border: 2px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
}
.bordas_corp{
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}

</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='a=1'> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr> 
<td  align="left" valign="top" bgcolor="#CCCCCC"> 
<form name='form1'>
<center>
<table border='0'>   
<?
if (isset($m51_codordem) && $m51_codordem!= "") {
  $result=$clmatestoqueitemnota->sql_record($clmatestoqueitemnota->sql_query_itensunid(null,null,"pc01_descrmater,m60_descr,e62_descr, m73_codmatestoqueitem,e60_codemp,m52_numemp,e62_item,m70_codmatmater,m71_valor,m71_quant,m52_codlanc,m71_codlanc,m52_quant,m71_quantatend,m52_valor,m75_quant,m75_quantmult,m75_codmatunid,m61_usaquant",""," m74_codempnota=$m72_codnota"));
  $numrows = $clmatestoqueitemnota->numrows;
  if($numrows>0){
    echo "<tr class='bordas'>
    <td class='bordas' align='center'><b><small>$RLe60_codemp</small></b></td>
    <td class='bordas' align='center'><b><small>$RLpc01_descrmater</small></b></td>
    <td class='bordas' align='center'><b><small>$RLe62_descr</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_valor</small></b></td>
    <td class='bordas' align='center'><b><small>Valor Total</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_quant</small></b></td>
    <td class='bordas' align='center'><b><small>Recebido</small></b></td>
    <td class='bordas' align='center'><b><small>Valor Rec.</small></b></td>
    <td class='bordas' align='center'><b><small>Unidade de Entrada</small></b></td>
    <td class='bordas' align='center'><b><small>Quant. Unid.</small></b></td>
    <td class='bordas' align='center'><b><small>Item de Entrada</small></b></td>";
    /*       echo "<tr class='bordas'>
    <td class='bordas' align='center'><b><small>$RLe60_codemp</small></b></td>
    <td class='bordas' align='center'><b><small>$RLpc01_descrmater</small></b></td>
    <td class='bordas' align='center'><b><small>$RLe62_descr</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_valor</small></b></td>
    <td class='bordas' align='center'><b><small>Valor Total</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_quant</small></b></td>
    <td class='bordas' align='center'><b><small>Quant. Max. Disponível</small></b></td>
    <td class='bordas' align='center'><b><small>Quant. Min. Disponível</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_quant</small></b></td>
    <td class='bordas' align='center'><b><small>Valor</small></b></td>
    <td class='bordas' align='center'><b><small>Cod. Item</small></b></td>
    <td class='bordas' align='center'><b><small>Descr. Item</small></b></td>";*/
  }else echo"<b>Nenhum registro encontrado...</b>";
  echo "</tr>";
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
    $soma_quant=0;
    $result_soma=$clmatestoqueitemnota->sql_record($clmatestoqueitemnota->sql_query_itens(null,null,"m71_quant as quant_jah",""," m74_codempnota<>$m72_codnota and m52_codlanc=$m52_codlanc"));
    $numrows_soma=$clmatestoqueitemnota->numrows;
    for($w=0;$w<$numrows_soma;$w++){
      db_fieldsmemory($result_soma,$w);
      $soma_quant+=$quant_jah;
    }
    $valoruni = $m52_valor/$m52_quant;
    $m52_quant=$m52_quant-$soma_quant;
    
    $valor  = "total_$i";
    $$valor = db_formatar($m71_valor,"f");
    
    echo "<tr>	    
    <td class='bordas_corp' align='center'><small>$e60_codemp </small></td>
    <td class='bordas_corp' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>
    <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr,0,20)."&nbsp;</small></td>  
    <td class='bordas_corp' align='right'><b><small>".db_formatar($valoruni,'v',' ',4)."</small></b></td>";
    echo "  <td class='bordas_corp' align='right'><b><small>";
    db_input("total_$i",10, 0, true, 'text', 3);  // Alterado por Tarcisio
    echo "</small></b></td>";
    echo "<td class='bordas_corp' align='center'><small>$m75_quant</small></td>";
    echo "<td class='bordas_corp' align='center'><small>";
    $op=1;
    if ($m52_quant==$m71_quantatend||$m52_quant==0){
      $op=3;
    }
    db_input("quant_$m71_codlanc"."_"."$i",10,'',true,'text',$op,"onchange='js_verifica($m52_quant,this.value,this.name,$valoruni,$i,$m71_quantatend);'");
    echo "   </small></td>
    <td class='bordas_corp' align='center'><small>";
    $js_script = "onChange=js_recalcula('valor_".$i."','total_".$i."');";
    db_input("valor_$i", 10, 0, true, 'text', 1, $js_script);
    echo "   </small></td>";
    echo "<td class='bordas_corp' align='left' nowrap ><small>";
    $result_unid=$clmatunid->sql_record($clmatunid->sql_query_file(null,"case when m61_usaquant is true then to_char(m61_codmatunid,'99999') || 't' else to_char(m61_codmatunid,'99999') || 'f' end as m61_codmatunid, m61_abrev","m61_abrev"));
    $couni="codunid_$i";
    $$couni=$m75_codmatunid.$m61_usaquant;
    echo " <select onChange='js_unid(this.value,$i);'  name='codunid_$i' id='codunid_$i'>";
    for($y=0;$y<$clmatunid->numrows;$y++){
      db_fieldsmemory($result_unid,$y);
      echo "<option value=\"$m61_codmatunid\" ".(isset($couni)?($$couni==$m61_codmatunid?"selected":""):"").">$m61_abrev</option>\n";
    }
    echo " </select>";
    
    //db_selectrecord("codunid_$i",$result_unid,true,2,"onchange='js_unid(this.value,$i);'","","","","js_unid(this.value,$i);",1);
    echo "</small></td>";
    echo "<td class='bordas_corp' align='left' nowrap ><small>";
    $mult="qntmul_$i";
    $$mult=$m75_quantmult;
    db_input("qntmul_$i",6,0,true,'text',1);
    if ($m61_usaquant=='f'){
      echo "<script>eval(\"document.form1.qntmul_\"+$i+\".disabled=true\");</script>";		 
    }
    echo "</small></td>
    <td class='bordas_corp' align='center'><small>$m70_codmatmater-$m60_descr</small>
		</td>";
		
		echo "<td>";
		$var = "qantigas_$m71_codlanc"."_"."$i";
		$$var = $m75_quant;
    db_input($var,10,'',true,'hidden',3);
		echo "</td>";

    echo "</tr>";
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
function js_recalcula(chave1,chave2){
  var valor            = new Number(eval("document.form1."+chave1+".value"));
  total            = new Number(eval("document.form1."+chave2+".value"));
  val              = eval("document.form1."+chave1+".value");
  campo_total      = eval("document.form1."+chave2);
  campo_total_nota = eval("parent.document.form1.e70_valor");
  
  if (isNaN(parseFloat(valor))){
    alert("Verifique o valor.");
    return false;
  }
  
  if (valor <= 0){
    alert("Valor Recebido dever ser maior que zero!");
    return false;
  }else{
    campo_total.value      = valor;
    campo_total_nota.value = valor;
  }
}
function js_verifica(max,quan,nome,valoruni,contador,atendida){
  if (isNaN(parseFloat(quan))){
    alert("Verifique a quantidade.");
    return false;
  }
  
  if (max<quan){
    alert("Informe uma quantidade valida!!");
    eval("document.form1."+nome+".value='';");
    eval("document.form1."+nome+".focus();");
    eval("document.form1.valor_"+contador+".value='';");
  }else if (quan<atendida) {
    alert("item ja possui atendimento!!");
    eval("document.form1."+nome+".value='';");
    eval("document.form1."+nome+".focus();");
    eval("document.form1.valor_"+contador+".value='';");
  }else{
    i=nome.split("_");
    pos=i[2];
    quant=new Number(quan);
    valor=new Number(valoruni);
    valortot=quant*valor;
    if (valortot==0){
      eval("document.form1.qntmul_"+pos+".value='0';");
      eval("document.form1.qntmul_"+pos+".disabled=true;");
    }
    eval("document.form1.valor_"+contador+".value=valortot.toFixed(2)");
  }
}
function js_unid(value,pos){
  cont=value.length;
  cont=new Number(cont);
  cont=cont-1;
  uso=value.substring(cont);
  cod=value.substring('0',cont);
  if (uso=='f'){
    eval("document.form1.qntmul_"+pos+".value='1';");
    eval("document.form1.qntmul_"+pos+".disabled=true;");
  }else{
    eval("document.form1.qntmul_"+pos+".disabled=false;");
  }
}
</script>
</body>
</html>