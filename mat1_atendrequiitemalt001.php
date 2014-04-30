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
include("classes/db_matrequiitem_classe.php");
include("classes/db_atendrequiitem_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_departorg_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/materialestoque.model.php");
require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatrequiitem = new cl_matrequiitem;
$clatendrequiitem = new cl_atendrequiitem;
$clmatestoque = new cl_matestoque;
$clmatparam = new cl_matparam;
$cldb_almoxdepto = new cl_db_almoxdepto;
$cldb_departorg = new  cl_db_departorg;

$clmatrequiitem->rotulo->label();


$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>
function js_marca(obj){
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
	     part = OBJ.elements[i].name.split("CHECK_");
			 marca = OBJ.elements[i].checked;
       eval("opcao=document.form1.bloq_"+part[1]+".value");
     	 if (marca&&opcao!="3"){
		     eval("document.form1.quant_"+part[1]+".disabled=false");
	     }else{
		     eval("document.form1.quant_"+part[1]+".disabled=true");
	     }
     }
   }
   return false;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>

  <tr>
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <center>
    <table>
    <tr>
    <td>
    <fieldset><legend><b>Itens</b></legend>
 <table border='0' cellspacing="0" style='border:2px inset white'>
<?

if (isset($m40_codigo) && $m40_codigo!= "") {

  $sqlmatrequiitem = $clmatrequiitem->sql_query(null,"*","","m41_codmatrequi=$m40_codigo");
  $result          = $clmatrequiitem->sql_record($sqlmatrequiitem);
  $numrows         = $clmatrequiitem->numrows;

  if ($numrows>0) {

    echo "<tr >
            <td class='table_header' align='center'><b><small>$RLm41_codmatmater</small></b></td>
            <td class='table_header' align='center'><b><small>$RLm60_descr</small></b></td>
            <td class='table_header' align='center'><b><small>Unid. Saída</small></b></td>
            <td class='table_header' align='center'><b><small>$RLm41_obs</small></b></td>
            <td class='table_header' align='center'><b><small><b>Quant. Solicitada<b></small></b></td>
            <td class='table_header' align='center'><b><small><b>Quant. Atendida<b></small></b></td>
            <td class='table_header' align='center'><b><small><b>Quant. Disponível em Estoque<b></small></b></td>
            <td class='table_header' align='center'><b><small><b>Lote<b></small></b></td>
            <td class='table_header' align='center'><b><small>$RLm41_quant</small></b></td>
	        <td class='table_header'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
						";
//            <td class='bordas' align='center'><b><small>Lançar </small></b></td>";

    echo " </tr>";
  } else {
    echo"<b>Nenhum registro encontrado...</b>";
  }

  for ($i=0; $i<$numrows; $i++) {
    db_fieldsmemory($result,$i);

    $lVerificaEstoque = false;
    if ($m60_controlavalidade == 1 || $m60_controlavalidade == 2) {
      $lVerificaEstoque = true;
    }
    // Busca quantidade em Estoque
    $quant_estoque     = '0';
    $sqlmatestoque     = $clmatestoque->sql_query_almox(null,"sum(m70_valor) as vlrtot,
                                                              sum(m70_quant) as quant_estoque",null,"m70_codmatmater=$m41_codmatmater ");
    $result_matestoque = $clmatestoque->sql_record($sqlmatestoque);
    echo $sqlmatestoque."<br>";
    if ($clmatestoque->numrows != 0) {
      db_fieldsmemory($result_matestoque, 0);
    }

    $sqlatendrequiitem = $clatendrequiitem->sql_query(null,"*",null,"m43_codmatrequiitem=$m41_codigo");
    $result2           = $clatendrequiitem->sql_record($sqlatendrequiitem);
    $numrows2          = $clatendrequiitem->numrows;

    if ($numrows2 ==0 ) {
      echo "<tr style='background-color:white' >
              <td	class='linhagrid' align='center'><small>$m41_codmatmater </small></td>
              <td	class='linhagrid' align='center'><small>$m60_descr </small></td>
              <td	class='linhagrid' align='center'><small>$m61_descr </small></td>
              <td	class='linhagrid' nowrap align='left' title='$m41_obs'><small>".substr($m41_obs,0,20)."&nbsp;</small></td>
              <td	class='linhagrid' align='center'><small>$m41_quant</small></td>
              <td	class='linhagrid' align='center'><small>0</small></td>";
      echo "  <td class='linhagrid' align='center'><small>$quant_estoque</small></td>";
      echo "<td class='linhagrid'>&nbsp;";
      if ($lVerificaEstoque){

        echo "<a onclick='js_mostraLotes($m41_codmatmater,".db_getsession("DB_coddepto").")'>lote</>";
      }
      echo "</td>";
      $op = 1;
      if ($quant_estoque == 0) {
        $op = 3;
      }
      $quant_sol  = $m41_quant;
      $quant      = $quant_estoque;
      $quantidade = "quant_$m41_codmatmater"."_"."$m41_codigo"."_"."$i";
      if ($quant_sol<$quant_estoque) {
        $quant_auto = "$quant_sol";
      } else {
        $quant_auto = "$quant_estoque";
      }
      $$quantidade = "$quant_auto";

      echo "<td class='linhagrid' align='center'><small>";
      $bloqueado = "bloq_$m41_codmatmater"."_"."$m41_codigo"."_"."$i";
      $$bloqueado = $op ;
      db_input("bloq_$m41_codmatmater"."_"."$m41_codigo"."_"."$i",6,0,true,'hidden',3,"");
      db_input("quant_$m41_codmatmater"."_"."$m41_codigo"."_"."$i",6,0,true,'text',$op,"onchange='js_verifica($quant,this.value,this.name,$m41_quant);'");
      echo "</small></td>";
      echo " <td class='linhagrid' title='Inverte a marcação' align='center'>
      <input type='checkbox' name='CHECK_$m41_codmatmater"."_"."$m41_codigo"."_"."$i"."'
       id='CHECK_".$m41_codmatmater."_"."$m41_codigo"."_"."$i"."' onclick='js_bloq(this.name,this.checked);' ></td>";
      /*
      if ($quant_estoque==0) {
        echo "<td class='bordas_corp' align='center'>
                <input name='lancar' type='button' value='Lançar' disabled onclick='js_lancadados($quant,$m41_codigo,$m41_codmatmater,\"$m60_descr\",\"$m41_obs\",$i,$m40_codigo);' > ";
        echo "</td>";
      } else {
        echo "<td class='bordas_corp' align='center'>
                <input name='lancar' type='button' value='Lançar' onclick='js_lancadados($quant,$m41_codigo,$m41_codmatmater,\"$m60_descr\",\"$m41_obs\",$i,$m40_codigo);' >";
        echo "</td>";
      }
			*/
      echo"  </tr>";
    } else {
      echo "<tr style='background-color:#FFFFFF'>
              <td	 class='linhagrid' align='center'><small>$m41_codmatmater </small></td>
              <td	 class='linhagrid' align='center'><small>$m60_descr </small></td>
              <td	 class='linhagrid' align='center'><small>".@$m61_descr."</small></td>
              <td	 class='linhagrid' nowrap align='left' title='$m41_obs'><small>".substr($m41_obs,0,20)."&nbsp;</small></td>
              <td	 class='linhagrid' align='center'><small>$m41_quant</small></td>";
      $quant_soma=0;
      for ($y=0; $y<$numrows2; $y++) {
        db_fieldsmemory($result2,$y);
        $quant_soma+=$m43_quantatend;
      }
      $quant      = $quant_estoque;
      $quant_sol  = $m41_quant-$quant_soma ;
      $quantidade = "quant_$m41_codmatmater"."_"."$m41_codigo"."_"."$i";

      if ($quant_sol<$quant_estoque) {
        $quant_auto = "$quant_sol";
      } else {
        $quant_auto = "$quant_estoque";
      }

      $$quantidade = "$quant_auto";
      $op          = 1;

      if ($quant_estoque == 0 || $m41_quant == $quant_soma) {
        $op = 3;
      }

      echo "<td class='linhagrid' align='center'><small>$quant_soma</small></td>
            <td class='linhagrid' align='center'><small>$quant_estoque</small></td>";

      echo "<td class='linhagrid'>&nbsp;";
      if ($lVerificaEstoque){

       echo "<a onclick='js_mostraLotes($m41_codmatmater,".db_getsession("DB_coddepto").")'>lote</>";
      }
      echo "</td>";
      echo "<td class='linhagrid' align='center'><small>";
      db_input("quant_$m41_codmatmater"."_"."$m41_codigo"."_"."$i",6,0,true,'text',$op,"onchange='js_verifica($quant,this.value,this.name,$quant_sol);'");
      $bloqueado = "bloq_$m41_codmatmater"."_"."$m41_codigo"."_"."$i";
      $$bloqueado = $op ;
      db_input("bloq_$m41_codmatmater"."_"."$m41_codigo"."_"."$i",6,0,true,'hidden',3,"");
      echo "</small></td>";
      echo " <td class='linhagrid' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$m41_codmatmater"."_"."$m41_codigo"."_"."$i"."' id='CHECK_".$m41_codmatmater."_"."$m41_codigo"."_"."$i"."' onclick='js_bloq(this.name);' > </td>";
      echo "</tr>";
    }
  }
}

?>
</table>
</fieldset>
</td>
</tr>
 </table>
    </form>
    </center>
    </td>
  </tr>
</table>
<script>
function js_desab(){
	var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].name.substr(0,6) == 'quant_'){
       OBJ.elements[i].disabled=true;
     }
   }
}
function js_bloq(name,marca){
	part = name.split("CHECK_");
	eval("opcao=document.form1.bloq_"+part[1]+".value");
	if (marca&&opcao!="3"){
		eval("document.form1.quant_"+part[1]+".disabled=false");
	}else{
		eval("document.form1.quant_"+part[1]+".disabled=true");
	}
}
function js_verifica(max,quan,nome,sol){
   if (max<quan){
     alert("Informe uma quantidade valida!!\nQuantidade não disponível");
     eval("document.form1."+nome+".value='';");
     eval("document.form1."+nome+".focus();");
   }else if (sol<quan) {
     alert("Informe uma quantidade valida!!");
     eval("document.form1."+nome+".value='';");
     eval("document.form1."+nome+".focus();");
   }
}
function js_lancadados(quansol,codreqitem,codmater,descrmater,obs,i,m40_codigo){
  quantatend=eval('document.form1.quant_'+codmater+'_'+codreqitem+'_'+i+'.value;');
  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','mat1_lancarequi001.php?codreqitem='+codreqitem+'&codmater='+codmater+'&descrmater='+descrmater+'&obs='+obs+'&quantatend='+quantatend+'&m40_codigo='+m40_codigo+'&m80_codigo='+parent.document.form1.m80_codigo.value,'Pesquisa',true);
}
function js_mostraLotes(iCodItem, iCodEstoque) {

  js_OpenJanelaIframe('top.corpo','db_iframe_lotes','mat4_mostralotes.php?iCodMater='+iCodItem+'&iCodDepto='+iCodEstoque,'Lotes ',true);

}
js_desab();
</script>
</body>
</html>