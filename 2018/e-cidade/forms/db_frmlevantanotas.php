<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: fiscal
$cllevantanotas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y63_codlev");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir_html = new cl_iframe_alterar_excluir_html;
?>

<script>

var aListaNotas = new Array();
var aTempRegist = new Array();

function js_novo(){

  js_alteraTela(true);

  var oObj = document.form1;

  iOrdem   = aTempRegist[0];
  iDoc     = aTempRegist[1];
  nValor   = aTempRegist[2];
  dtData   = aTempRegist[3];

  js_incluirlinhas(iOrdem,iDoc,nValor,dtData);

  oObj.y79_data_dia        = "";
  oObj.y79_valor.value     = "";
  oObj.y79_ordem.value     = "";
  oObj.y79_documento.value = "";
  oObj.y79_data_dia.focus();
}

function js_alteraTela(lHabilita){

  var elemFormFrame = criatabela.document.getElementById('tab').rows;
  var iNroElemFrame = elemFormFrame.length;

  if ( lHabilita ) {

    aListaNotas.each(
	    function ( aNota, iIndex ) {
	      var iId     = new Number(aNota[0]);
	      var sFuncao = new String(aNota[1]);

	      for ( var iInd=1; iInd < iNroElemFrame; iInd++ ) {
	        if ( elemFormFrame[iInd].cells[0].innerHTML == iId ) {
	          elemFormFrame[iInd].cells[4].innerHTML = sFuncao;
	        }
	      }
	    }
    );

    aListaNotas = new Array();
    $('novo').style.display = 'none';
    $('atualizar').disabled = false;

  } else {

    $('novo').style.display = '';
    $('atualizar').disabled = true;

	  for ( var iInd=1; iInd < iNroElemFrame; iInd++ ) {

	    var iId     = new Number(elemFormFrame[iInd].cells[0].innerHTML);
	    var sFuncao = new String(elemFormFrame[iInd].cells[4].innerHTML);

	    var aNota   = new Array();
      aNota[0]    = iId;
      aNota[1]    = sFuncao;

      aListaNotas.push(aNota);
      elemFormFrame[iInd].cells[4].innerHTML = 'A E';

	  }

  }

}

function js_alterar(ordem,doc,valor,data){

  js_alteraTela(false);

  var obj    = document.form1;
  valor= new Number(valor);
  valor=valor.toFixed(2);
  obj.y79_valor.value= valor;
  matriz=data.split('/');
  obj.y79_data_dia.value  = matriz[0];
  obj.y79_data_mes.value  = matriz[1];
  obj.y79_data_ano.value  = matriz[2];
  obj.y79_documento.value = doc;
  obj.y79_ordem.value     = ordem;

  aTempRegist[0] = ordem;
  aTempRegist[1] = doc;
  aTempRegist[2] = valor;
  aTempRegist[3] = data;

}

function js_incluir(){

  js_alteraTela(true);

 //retorno o próximo código da ordem
  dad = js_retorna_dados();
  arr_dad = dad.split('#');
  arr_dad.sort()
  val =  new Number( arr_dad.pop());

  var obj    = document.form1;
  var valor  = new Number(obj.y79_valor.value);
  if(isNaN(valor) || valor==""){
    alert("Verifique o valor.");
    document.form1.y79_valor.focus();
    return false;
  }
  d=obj.y79_data_dia.value;
  m=obj.y79_data_mes.value;
  a=obj.y79_data_ano.value;
  if(d=="" || m=="" || a==""){
    alert("Verifique a data.");
    obj.y79_data_dia.focus();
    return false;
  }
  if(obj.y79_documento.value == ''){
    alert("Preencha o campo documento.");
    obj.y79_documento.focus();
    return false;
  }
  doc = obj.y79_documento.value;

  valor=valor.toFixed(2);
  data=d+'/'+m+'/'+a;

  ordem = obj.y79_ordem.value;
  if(ordem==''){
     ordem = val +1;
  }
  js_incluirlinhas(ordem,doc,valor,data);
 // obj.y79_data_dia.value='';
//  obj.y79_data_mes.value='';
//  obj.y79_data_ano.value='';
  obj.y79_valor.value="";
  obj.y79_ordem.value="";
  obj.y79_documento.value="";
//  obj.y79_documento.focus();
  obj.y79_data_dia.focus();
}
function js_fechar(){
    js_dados();
    var inputs  = document.getElementsByTagName("INPUT");
    valores='';
    pago=new Number(0);
    espa='';
    for(i=0; i<inputs.length; i++){
        nome=inputs[i].name.substr(0,5);
	if(nome=='linha' && inputs[i].value!=''){
	   matriz=inputs[i].value.split('#');
           valores+= espa+matriz[1]+'_sep_'+matriz[2]+'_sep_'+matriz[3];
	   espa='HHH';
	   str=new Number(matriz[2]);
	   pago=(pago+str);
	   inputs[i].value='';
	}
    }
	  pago=pago.toFixed(2)

	  parent.document.form1.y63_bruto.value = pago;
	  parent.document.form1.notas.value     = valores;

          parent.js_fecha02();
}

function js_ent(obj,evt,campo){
  var evt = (evt) ? evt : (window.event) ? window.event : "";

  if(evt.keyCode==13){
    eval("document.form1."+campo+".focus();");
  }
}
</script>
<center>
<table >
<tr>
<td align='center'>
<form name="form1" method="post" action="">
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty79_data?>">
    <b>Dia</b>
    </td>
    <td>
<?
/*if(empty($y79_data_dia)){
  $y79_data_dia = date("d",db_getsession("DB_datausu"));
  $y79_data_mes = date("m",db_getsession("DB_datausu"));
  $y79_data_ano = date("Y",db_getsession("DB_datausu"));
}*/
//db_inputdata('y79_data',@$y79_data_dia,@$y79_data_mes,@$y79_data_ano,true,'text',$db_opcao,"");
//db_input('y79_data_dia',2,$Iy79_ordem,true,'text',$db_opcao);
echo "<input name=\"y79_data_dia\" type=\'text' size='2' value=\"".@$y79_data_dia."\" maxlength='2'
       onKeyDown=\"js_ent(this,event,'y79_documento')\"
       onFocus=\"ContrlDigitos=0\"
       onKeyUp=\"js_Passa(this.name,31,1,2005)\"/> ";
db_input('y79_data_mes',10,0,true,'hidden');
db_input('y79_data_ano',10,0,true,'hidden');
?>
    </td>
    <td nowrap title="<?=@$Ty79_documento?>">
       <b>Doc.</b>
    </td>
    <td>
<?
db_input('y79_ordem',10,$Iy79_ordem,true,'hidden');
//db_input('y79_documento',10,$Iy79_documento,true,'text',$db_opcao,"");
?>
  <input name="y79_documento" type="text" size="10" value="<?=@$y79_documento?>" onKeyDown="js_ent(this,event,'y79_valor')">

  <?=@$Ly79_valor?>
   <input title="Valor  Campo:y79_valor" name="y79_valor"  type="text"     id="y79_valor"  value=""  size="10"
   	maxlength="15"  onblur="js_ValidaMaiusculo(this,'f',event);"
	                  onKeyUp="js_ValidaCampos(this,4,'Valor','f','f',event);"
                    onKeyDown="js_ent(this,event,'lancar')">




     <input name="lancar" type="button" id="db_opcao" value="Lançar" onclick='js_incluir();' <?=($db_botao==false?"disabled":"")?>>
    </td>
  </tr>

  <tr>
    <td colspan='4' align="center">
     <input name="fechar" id="atualizar" type="button" value="atualizar" onclick='js_fechar();' <?=($db_botao==false?"disabled":"")?>>
     <input name="novo"   id="novo"      type="button" value="Novo"      onclick='js_novo();' style="display:none">
     <br>
     <small><b style="color:darkblue;">Para que os dados sejam salvos, clique em atualizar!</b></small>
    </td>
  </tr>
  </table>
</form>
  </center>
  </td>
</tr>
<tr>
  <td valign="top">
   <?php

	   $cliframe_alterar_excluir_html->colunas       = array("y79_ordem"=>$Ly79_ordem,"y79_documento"=>"$Ly79_documento","y79_valor"=>$Ly79_valor,"y79_data"=>$Ly79_data);
	   $cliframe_alterar_excluir_html->iframe_width  = "375";
	   $cliframe_alterar_excluir_html->iframe_nome   = "criatabela";
	   $cliframe_alterar_excluir_html->iframe_height = "250";
	   $cliframe_alterar_excluir_html->load          = "parent.js_monta();";
           if($db_opcao==3){
  	         $cliframe_alterar_excluir_html->db_opcao = "3";
           }
           if(isset($sql)){
  	         $cliframe_alterar_excluir_html->sql      = $sql;
           }
	   $cliframe_alterar_excluir_html->iframe_alterar_excluir_html();
   ?>
   </td>
  </tr>
</table>
<script>
function js_monta(){
<?
if(isset($notas)){
  $matriz01=split('HHH',$notas);
  for($i=0; $i<count($matriz01); $i++){
    $matriz=split('_sep_',$matriz01[$i]);
    if($db_opcao==33){
      echo " js_incluirlinhas_disabled('".$matriz[3]."','".$matriz[0]."','".$matriz[1]."','".$matriz[2]."');\n\n  ";
    }else{
      echo " js_incluirlinhas('".$matriz[3]."','".$matriz[0]."','".$matriz[1]."','".$matriz[2]."','".$matriz[3]."');\n\n  ";
    }

  }

}
?>
}
document.form1.y79_data_dia.focus();
document.form1.y79_data_mes.value = parent.document.form1.y63_mes.value;
document.form1.y79_data_ano.value = parent.document.form1.y63_ano.value;
</script>