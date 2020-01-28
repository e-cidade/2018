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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_atendrequiitem_classe.php");
include("classes/db_atendrequiitemmei_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueinimeiari_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_departorg_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmatrequiitem = new cl_matrequiitem;
$clatendrequiitem = new cl_atendrequiitem;
$clatendrequiitemmei = new cl_atendrequiitemmei;
$clatendrequi = new cl_atendrequi;
$clmatestoque =  new cl_matestoque;
$clmatestoqueini =  new cl_matestoqueini;
$clmatestoqueinimei =  new cl_matestoqueinimei;
$clmatestoqueinimeiari =  new cl_matestoqueinimeiari;
$clmatestoqueitem =  new cl_matestoqueitem;
$clmatparam = new cl_matparam;
$cldb_departorg = new  cl_db_departorg;
$cldb_almoxdepto = new cl_db_almoxdepto;
$clmatrequiitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");
$clrotulo->label("m70_quant");
$sqlerro=false;
$passou=false;
$erro_msg = "";

if (isset($confirma)) {
  db_inicio_transacao();
  $sqlerro=false;
  if ($atendimento_control=="") {
    $clatendrequi->m42_login=db_getsession("DB_id_usuario");
    $clatendrequi->m42_depto=db_getsession("DB_coddepto");
    $clatendrequi->m42_data=date('Y-m-d',db_getsession("DB_datausu"));
    $clatendrequi->m42_hora=db_hora();
    $clatendrequi->incluir(null);
    $erro_msg=$clatendrequi->erro_msg;
    $codigo=$clatendrequi->m42_codigo;
    if ($clatendrequi->erro_status==0) {
      $sqlerro=true;
    } else {
    }
    if ($sqlerro == false) {
      $clmatestoqueini->m80_login          = db_getsession("DB_id_usuario");
      $clmatestoqueini->m80_data           = date("Y-m-d",db_getsession("DB_datausu"));
      $clmatestoqueini->m80_hora           = date('H:i:s');
      $clmatestoqueini->m80_obs            = "";
      $clmatestoqueini->m80_codtipo        = "17";
      $clmatestoqueini->m80_coddepto       = db_getsession("DB_coddepto");
      $clmatestoqueini->incluir(@$m80_codigo);
      if ($clmatestoqueini->erro_status==0) {
        $sqlerro=true;
        $erro_msg = $clmatestoqueini->erro_msg;
      }
      $m80_codigo = $clmatestoqueini->m80_codigo;
    }
  } else {
    $codigo=$atendimento_control;
  }
  if ($sqlerro==false) {
    $clatendrequiitem->m43_codatendrequi=$codigo;
    $clatendrequiitem->m43_codmatrequiitem=$codreqitem;
    $clatendrequiitem->m43_quantatend=$tot_quant;
    $clatendrequiitem->incluir(null);
    if ($clatendrequiitem->erro_status==0) {
      $sqlerro=true;
      $erro_msg=$clatendrequiitem->erro_msg;
    }
    $codigo_atenditem=$clatendrequiitem->m43_codigo;
  }
  $dados=split("quant_","$quantis");
  for ($y=1; $y<count($dados); $y++) {
    if ($sqlerro==false) {
      $info=split("_",$dados[$y]);
      $codestoque=$info[0];
      $quantidade=$info[2];
      $quant_resta=$quantidade;
      $quant_inc="";
      $acaba=false;
      $result_matestoqueitem=$clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null,"*","m71_data desc","m71_codmatestoque = $codestoque and m71_quantatend < m71_quant"));
      $numrows=$clmatestoqueitem->numrows;
      for ($w=0; $w<$numrows; $w++) {
        if ($sqlerro==false) {
          db_fieldsmemory($result_matestoqueitem,$w);
          $valor_inc="";
          $quantatual = $m71_quant;
          $m71_quant -= $m71_quantatend;
          if ($quant_resta<$m71_quant) {
            $clmatestoqueitem->m71_quantatend=$m71_quantatend+$quant_resta;
            $quant_inc=$quant_resta;
            //	     db_msgbox($m71_valor.'---'.$m71_quant.'---'.$quantatual);
            $valor_inc=$m71_valor/$quantatual;
            $acaba=true;
          } else {
            $clmatestoqueitem->m71_quantatend=$m71_quantatend+$m71_quant;
            $quant_inc=$m71_quant;
            $valor_inc=$m71_valor/$quantatual;
            $quant_resta=$quant_resta-$m71_quant;
          }
          $clmatestoqueitem->m71_valor = $m71_valor;
          $clmatestoqueitem->m71_quant = $quantatual;
          $clmatestoqueitem->m71_codlanc = $m71_codlanc;
          $clmatestoqueitem->alterar($m71_codlanc);
          
          if ($clmatestoqueitem->erro_status==0) {
            $sqlerro=true;
            $erro_msg=$clmatestoqueitem->erro_msg;
            break;
          }
          if ($sqlerro==false) {
            $clatendrequiitemmei->m44_codatendreqitem=$codigo_atenditem;
            $clatendrequiitemmei->m44_codmatestoqueitem=$m71_codlanc;
            $clatendrequiitemmei->m44_quant=$quant_inc;
            $clatendrequiitemmei->incluir(null);
            if ($clatendrequiitemmei->erro_status==0) {
              $sqlerro=true;
              $erro_msg=$clatendrequiitemmei->erro_msg;
              break;
            }
          }
          if ($sqlerro == false) {
            $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
            $clmatestoqueinimei->m82_matestoqueini  = $m80_codigo;
            $clmatestoqueinimei->m82_quant          = $quant_inc;
            $clmatestoqueinimei->incluir(@$m82_codigo);
            if ($clmatestoqueinimei->erro_status==0) {
              $erro_msg = $clmatestoqueinimei->erro_msg;
              $sqlerro=true;
              break;
            }
            $codigo_inimei=$clmatestoqueinimei->m82_codigo;
          }
          if ($sqlerro == false) {
            $clmatestoqueinimeiari->m49_codatendrequiitem = $codigo_atenditem;
            $clmatestoqueinimeiari->m49_codmatestoqueinimei = $codigo_inimei;
            $clmatestoqueinimeiari->incluir(null);
            if ($clmatestoqueinimeiari->erro_status==0) {
              $erro_msg = $clmatestoqueinimeiari->erro_msg;
              $sqlerro=true;
              break;
            }
          }
          if ($acaba==true) {
            break;
          }
        }
      }
    } else {
      break;
    }
  }
  if ($sqlerro==false) {
    $result_matestoqueitem=$clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null,"sum(m71_quant) as quantidade,sum(m71_valor) as valor,sum(m71_quantatend) as quantatend",null,"m71_codmatestoque = $codestoque"));
    db_fieldsmemory($result_matestoqueitem,0);
    $clmatestoque->m70_codigo=$codestoque;
    $quant=$quantidade-$quantatend;
    $clmatestoque->m70_quant="$quant";
    $valor_inc=round($valor/$quantidade,2)*$quant;
    $clmatestoque->m70_valor="$valor_inc";
    //     echo $codestoque." => ".$quant." => ".$valor_inc." ==> ".$acaba." ==> ".$acaba1."<br>";
    $clmatestoque->alterar($codestoque);
    if ($clmatestoque->erro_status==0) {
      $sqlerro=true;
      $erro_msg=$clmatestoque->erro_msg;
    }
  }
  $passou=true;
  /*  db_msgbox($erro_msg);
exit;*/
  //  $sqlerro = true;
  db_fim_transacao($sqlerro);
  //  exit;
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
<?//$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
/*         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
*/	 
}
<?//$cor="999999"?>
.bordas_corp{
/*       border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
*/
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
       }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <form name='form1' onsubmit='js_buscavalores();' >
  <center>
  <tr>
  <br><br>
    <td><b>Material: <b></td>
    <td>
    <?
    db_input('codmater',6,'',true,'text',3);
    db_input('descrmater',30,'',true,'text',3);
    ?>
    </td>
    <td>
    <b>Quant. Atendida: </b>
    <?
    db_input('quantatend',6,'',true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td>
    <b>Observação:</b>
    </td>
    <td colspan=2>
    <?
     db_textarea('obs',0,50,'',true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr> 
    <td  align="center" valign="top"  colspan=3 > 
    <br><br>
      <table border='0' >   
      
        <?
	$result=$clmatestoque->sql_record($clmatestoque->sql_query_almox(null,"*",null,"m70_codmatmater=$codmater"));
//	$result=$clmatestoque->sql_record($clmatestoque->sql_query(null,"*","","m70_codmatmater=$codmater"));
	$numrows = $clmatestoque->numrows;
	if($numrows>0){
	  echo "<tr class='bordas'>
		  <td class='bordas' align='center'><b><small><b>Cod. Depto</b></small></b></td>
		  <td class='bordas' align='center'><b><small><b>Descrição</b></small></b></td>
		  <td class='bordas' align='center'><b><small><b>Quant. Disponível<b></small></b></td>
		  <td class='bordas' align='center'><b><small><b>Quant. Atendida</b></small></b></td>
		</tr>";
	}else echo"<b>Nenhum registro encontrado...</b>";
	$quantresta="$quantatend";
	for($i=0; $i<$numrows; $i++){
	  db_fieldsmemory($result,$i);
	  echo "<tr>	    
		  <td	 class='bordas_corp' align='center'><small>$m70_coddepto </small></td>
		  <td	 class='bordas_corp' align='center'><small>$descrdepto</small></td>
		  <td	 class='bordas_corp' align='center'><small>$m70_quant</small></td>
		  <td class='bordas_corp' align='center'><small>";
          $quant="quant_$m70_codigo"."_"."$i";
	  if ($numrows==1){
            $$quant="$quantatend";
	  }else{
	    if ($m70_quant>$quantresta){
	      $$quant="$quantresta";
	      $quantresta='0';
	    }else{
	      $$quant=$m70_quant;
	      $rst=$quantresta-$m70_quant;
	      $quantresta="$rst";
	    }
          }
	  db_input("quant_$m70_codigo"."_"."$i",6,"",true,'text',1,"onchange='js_verifica($quantatend,this.value,this.name);'");
	  echo "  </small></td>
		</tr>";
	}
        ?>     
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=3 align=center>
       <br>
       <br>
       <input name="confirma" type="submit"  value="Confirma">
       <input name="voltar" type="button" value="Voltar" onclick="parent.db_iframe_lanca.hide();" >
    </td>
  </tr>
  <?
  db_input('quantis',100,'',true,'hidden',3);
  db_input('atendimento_control',10,'',true,'hidden',3);
  db_input('codreqitem',10,'',true,'hidden',3);
  db_input('tot_quant',10,'',true,'hidden',3);
  db_input('m40_codigo',10,'',true,'hidden',3);
  db_input('m80_codigo',10,'',true,'hidden',3);
  ?>
<script>
function js_buscavalores(){
  obj= document.form1;
  quants="";
  tot_quant=0;
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,6)=="quant_"){
      var objvalor=new Number(obj.elements[i].value);
      if (objvalor!=0){
        quants+=obj.elements[i].name+"_"+obj.elements[i].value;
        objvalor=new Number(obj.elements[i].value);
        tot_quant+=objvalor;
      }
    }
  }  
  document.form1.quantis.value = quants;
  document.form1.tot_quant.value = tot_quant;
}
document.form1.atendimento_control.value=parent.document.form1.atendimento.value;
document.form1.m80_codigo.value=parent.document.form1.m80_codigo.value;
</script>
<?
  if ($numrows==1&&$sqlerro==false&&$passou==false){
    echo "<script>document.form1.confirma.click();</script>";
  }
?>
  </form> 
  </center>
</table>
<script>
function js_verifica(max,quan,nome){
  obj= document.form1;
  quanttotal=0;
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,6)=="quant_"){
      var objvalor= new Number(obj.elements[i].value);
      quanttotal+=objvalor;
    }
  }  
  if (max<quanttotal){
     alert("Informe uma quantidade valida!!");
     eval("document.form1."+nome+".value='';");
     eval("document.form1."+nome+".focus();");
  }
}

</script>
<?
if (isset($confirma)){
  if($sqlerro == true){ 
  db_msgbox(@$erro_msg);
    if($clatendrequi->erro_campo!=""){
      echo "<script> document.form1.".$clatendrequi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clatendrequi->erro_campo.".focus();</script>";
    } 
  }else{ 
  if ($erro_msg==""){
    $erro_msg="Atendimento Efetuado com Sucesso!";
  }
  db_msgbox(@$erro_msg);
     echo "<script>parent.document.form1.atendimento.value=$codigo;</script>";
     echo "<script>parent.document.form1.m80_codigo.value=$m80_codigo;</script>";
     echo "<script>parent.itens.location.href='mat1_atendrequiitemalt001.php?m40_codigo='+$m40_codigo;</script>";
     echo "<script>parent.db_iframe_lanca.hide();</script>";
   }
}
?>
</body>
</html>