<?php
/**
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

require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_parissqn_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_levinscr_classe.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clparissqn               = new cl_parissqn;
$clissbase                = new cl_issbase;
$cllevinscr               = new cl_levinscr;

$cllevvalor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y60_contato");
$data = date("d-m-Y",db_getsession("DB_datausu"));
$data = split('-',$data);
$dia  = $data[0];
$mes  = $data[1];
$ano  = $data[2];

?>
<script type="text/javascript">
  function js_calcula(campo,evt){

    obj = document.form1;
    if( campo.name == 'y63_bruto' ){

      bruto = new Number(campo.value);
      aliq  = new Number(obj.y63_aliquota.value);
    }
    if( campo.name == 'y63_aliquota' ){

      aliq  = new Number(campo.value);
      bruto = new Number(obj.y63_bruto.value);
    }
    if( campo.name == 'y63_pago' ){

      aliq  = new Number(obj.y63_aliquota.value);
      bruto = new Number(obj.y63_bruto.value);
    }

    if(aliq > 100 || aliq <= 0 ){

      alert('Aliquota inválida!');
      obj.y63_aliquota.value = '';
      obj.y63_aliquota.focus();
      return false;
    }

    total = new Number((bruto * aliq) / 100);
    obj.apagar.value = total;

    pago   = new Number(obj.y63_pago.value);
    valtot = new Number(total-pago);
    obj.y63_saldo.value = valtot.toFixed(2);

    var evt = (evt) ? evt : (window.event) ? window.event : "";
    if(evt.keyCode==13){
      document.form1.<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>.click();
    }
  }

  function js_over(cods){

    var tab  = document.getElementById('tab2') ;
    arr_cods = cods.split("-");
    str      = document.form1.str.value;
    arr_dad  = str.split("#");
    if(arr_dad.length>0){
        novalinha  = document.getElementById('tab2').insertRow(document.getElementById('tab2').rows.length);
        novacoluna = novalinha.insertCell(0);
        novacoluna.innerHTML = "Ordem";
        novacoluna = novalinha.insertCell(1);
        novacoluna.innerHTML = "Documento";
        novacoluna = novalinha.insertCell(2);
        novacoluna.innerHTML = "Valor";
    }
    for(i=0; i<arr_dad.length; i++){
      arr_linh = arr_dad[i].split("-");
      if(arr_cods[0] == arr_linh[0] && arr_cods[1] == arr_linh[1] ){
        novalinha  = document.getElementById('tab2').insertRow(document.getElementById('tab2').rows.length);
        novacoluna = novalinha.insertCell(0);
        novacoluna.innerHTML = arr_linh[4];
        novacoluna = novalinha.insertCell(1);
        novacoluna.innerHTML = arr_linh[2];
        novacoluna = novalinha.insertCell(2);
        novacoluna.innerHTML = arr_linh[3];
      }
    }
    document.getElementById('tab2').style.visibility = 'visible';
  }

  function js_out(cods){

    var tab = document.getElementById('tab2') ;
    while(tab.rows.length>0){
        tab.deleteRow(tab.rows.length-1);
    }
    document.getElementById('tab2').style.visibility = 'hidden';
  }

</script>
<form name="form1" method="post" action="fis1_levvalor001.php">
<center>
  <table id="tab2" border="1" style="position:absolute; z-index:1; top:; left:10; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
  </table>
<table border="0">
  <tr>
    <td align='center'>
<fieldset>
  <legend>Dados</legend>
<table border="0" >
  <tr>
    <td nowrap title="<?=@$Ty63_sequencia?>">
       <?=@$Ly63_sequencia?>
    </td>
    <td>
<?
db_input('valores',60,0,true,'hidden',3);
db_input('notas',60,0,true,'hidden',3);
db_input('y63_sequencia',10,$Iy63_sequencia,true,'text',3);
?>
    </td>
    <td>
      <?
      db_input('y63_codlev',4,$Iy63_codlev,true,'hidden',3)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty63_ano?>">
       <?=@$Ly63_ano?>
    </td>
    <td>
      <?
      $result=$cllevanta->sql_record($cllevanta->sql_query_file($y63_codlev,"y60_dtini,y60_dtfim"));
      db_fieldsmemory($result,0);
      $arr_ini = split("-",$y60_dtini);
      $arr_fim = split("-",$y60_dtfim);
      $ini = $arr_ini[0];
      $fim = $arr_fim[0];

      $anos=array();

      //Pega os exercícios do periodo
      for($i=$ini; $i<$fim+1; $i++){
       $anos[$i]=$i;
      }

      db_select("y63_ano",$anos,true,$db_opcao,"","","","","");
      ?>
    </td>
    <td nowrap title="<?=@$Ty63_mes?>">
       <?=@$Ly63_mes?>
    </td>
    <td>
    <?php
      if(empty($y63_mes)){
        $y63_mes = $arr_ini[1];
      }

      $result=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
      db_select("y63_mes",$result,true,$db_opcao,"","","","","");

      echo $Ly63_dtvenc;

      if(empty($y63_dtvenc_mes)){

        $y63_dtvenc_dia = $arr_ini[2];
        $y63_dtvenc_mes = $arr_ini[1];
        $y63_dtvenc_ano = $arr_ini[0];
      }
      db_inputdata('y63_dtvenc',@$y63_dtvenc_dia,@$y63_dtvenc_mes,@$y63_dtvenc_ano,true,'text',$db_opcao,"");
    ?>
  </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty63_histor?>">
       <?=@$Ly63_histor?>
    </td>
    <td colspan='3'>
    <?php
    db_textarea('y63_histor',0,70,$Iy63_histor,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty63_bruto?>">
       <?=@$Ly63_bruto?>
    </td>
    <td>
    <?php

    $db_op = $db_opcao;
    if(isset($y63_sequencia) && $y63_sequencia != null){

      $cllevantanotas->sql_record($cllevantanotas->sql_query_file(null,"y79_codigo","","y79_sequencia=$y63_sequencia"));
      if( $cllevantanotas->numrows>0){
        $db_op = 3;
      }
    }
    ?>
   <input title="Valor  Campo:y63_bruto" name="y63_bruto"  type="text"     id="y63_bruto"  value="<?=@$y63_bruto?>"  size="10"
   	maxlength="15"  onblur="js_ValidaMaiusculo(this,'f',event);js_calcula(this,event);"
	                onKeyUp="js_ValidaCampos(this,4,'Valor','f','f',event);"
                        onchange='js_calcula(this,event);'>

    </td>
    <td nowrap title="Valor à pagar">
      <strong>Valor a Pagar:</strong>
    </td>
    <td>
    <?php
      db_input('apagar',10,0,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty63_aliquota?>">
       <?=@$Ly63_aliquota?>
    </td>
    <td>
    <?php
    if(empty($y63_aliquota)){

       $result44  = $cllevinscr->sql_record($cllevinscr->sql_query_file($y63_codlev,"y62_inscr"));
       $numrows44 = $cllevinscr->numrows;
       if ( $numrows44 > 0) {

         db_fieldsmemory($result44, 0);
         $result33  = $clissbase->sql_record($clissbase->sql_query_aliquota($y62_inscr,"q81_valexe"));
         $numrows33 = $clissbase->numrows;
         if($numrows33 > 0){

           db_fieldsmemory($result33, 0);
           $y63_aliquota = $q81_valexe;
         } else {

           $result66=$clparissqn->sql_record($clparissqn->sql_query_file('','q60_aliq'));
           db_fieldsmemory($result66,0);
           $y63_aliquota=$q60_aliq;
         }
       } else {

        $result66=$clparissqn->sql_record($clparissqn->sql_query_file('','q60_aliq'));
        db_fieldsmemory($result66,0);
        $y63_aliquota=$q60_aliq;
       }
    }
    db_input('y63_aliquota',10,$Iy63_aliquota,true,'text',$db_opcao,"onchange='js_calcula(this);'",null,null,null,8)
    ?>
    <td nowrap title="<?=@$Ty63_pago?>">
       <?=@$Ly63_pago?>
    </td>
    <td>
    <?php
    empty($y63_pago) ? $y63_pago = '0' : '';
    db_input('y63_pago',10,$Iy63_pago,true,'text',3);

    if($db_opcao!=33){

      echo '<input name="pgto" type="button" value="Pagamentos" onclick="js_pgto();" >';
      echo '<input name="nota" type="button" value="Notas" onclick="js_nota();" >';
    }
    ?>
    </td>
  </tr>
  <tr>
    <td colspan='2'>&nbsp;</td>
    <td colspan='1' align='center'>
       <?=@$Ly63_saldo?>
    </td>
    <td nowrap title="<?=@$Ty63_saldo?>">
    <?php
      db_input('y63_saldo',10,$Iy63_saldo,true,'text',3)
    ?>
    </td>
  </tr>
 </table>
</fieldset>
  </td>
</tr>
  <tr>
    <td align="center">
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

  <?
  if(isset($opcao)){
  ?>
  <input name="novo" type="button" value="Novo" onclick="js_novo();" >
  <?
  }
  ?>
    </td>
  </tr>
<tr>
  <td valign="top" align='left'><br/>
   <?php
  	if(isset($db_opcaoal)){
  		 db_input("db_opcaoal",10,"",true,"hidden",3);
  	}
    $chavepri= array("y63_codlev"=>@$y63_codlev,"y63_sequencia"=>@$y63_sequencia);
    $cliframe_alterar_excluir->chavepri      = $chavepri;
    $cliframe_alterar_excluir->sql           = $cllevvalor->sql_query_file("","y63_sequencia,y63_mes,y63_ano,y63_codlev,y63_sequencia,y63_bruto,y63_aliquota,y63_pago,y63_saldo,y63_dtvenc,y63_histor,(y63_pago+y63_saldo) as y63_apagar"," y63_ano,y63_mes","y63_codlev=$y63_codlev");
    $cliframe_alterar_excluir->campos        = "y63_sequencia,y63_mes,y63_ano,y63_bruto,y63_aliquota,y63_apagar,y63_pago,y63_saldo,y63_dtvenc,y63_histor";
    $cliframe_alterar_excluir->legenda       = "VALORES LANÇADOS";
    $cliframe_alterar_excluir->iframe_height = "140";
    $cliframe_alterar_excluir->iframe_width  = "740";
    $cliframe_alterar_excluir->js_mouseout   = "parent.js_out";
    $cliframe_alterar_excluir->js_mouseover  = "parent.js_over";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);

    $sql = $cllevvalor->sql_query_file("","sum(y63_bruto) as tot_bruto,sum(y63_pago) as tot_pago,sum(y63_saldo) as tot_saldo,sum(y63_pago+y63_saldo) as tot_apagar","","y63_codlev=$y63_codlev");
    $result = $cllevvalor->sql_record($sql);
    if ($cllevvalor->numrows>0){
      db_fieldsmemory($result,0);
    }
   ?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty63_bruto?>" colspan='2' style="text-align:center">
       <strong>Total Bruto:</strong>
        <?php
          db_input('tot_bruto',10,$Iy63_bruto,true,'text',3);
        ?>
      <strong>Total à Pagar:</strong>
        <?php
          db_input('tot_apagar',10,0,true,'text',3);
        ?>
       <strong>Total Pago:</strong>
        <?php
          db_input('tot_pago',10,$Iy63_pago,true,'text',3);
        ?>
       <strong>Total Saldo à Pagar:</strong>
        <?php
          db_input('tot_saldo',10,$Iy63_saldo,true,'text',3)
        ?>
    </td>
  </tr>
  </table>
  </center>
<?php
  $result = $cllevantanotas->sql_record($cllevantanotas->sql_query(null,"y63_sequencia,y63_codlev,y79_documento,y79_ordem,y79_valor","y79_ordem","y63_codlev=$y63_codlev"));
  $numrows = $cllevantanotas->numrows;
  $str = '';
  $sep = '';
  for($i=0; $i<$numrows; $i++){

    db_fieldsmemory($result,$i);
    $str .= "$sep$y63_codlev-$y63_sequencia-$y79_documento-$y79_valor-$y79_ordem";
    $sep = '#';
  }
  db_input('str',10,0,true,'hidden',3);
?>


</form>
<script type="text/javascript">
function js_novo(){

  obj = document.createElement('input');
  obj.setAttribute('name','novo');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','ok');
  document.form1.appendChild(obj);
  document.form1.submit();
}

//mostra iframe dos pagamentos
function js_pgto(){

   valores = document.form1.valores.value;

   if(top.corpo.iframe_levvalor.db_iframe_pgto){
      db_iframe_pgto.show();
   }else{

     valores=document.form1.valores.value;
      if(valores!=""){
        js_OpenJanelaIframe('top.corpo.iframe_levvalor','db_iframe_pgto','fis1_levvalorpgtos001.php?<?=($db_opcao==33?'db_opcao=33&':'')?>valores='+valores,'Pesquisa',true,0);
      }else{
        js_OpenJanelaIframe('top.corpo.iframe_levvalor','db_iframe_pgto','fis1_levvalorpgtos001.php?<?=($db_opcao==33?'db_opcao=33':'')?>','Pesquisa',true,0);
      }
   }
}
//esconde o iframe dos pagamentos
function js_fecha(){
  db_iframe_pgto.hide();
  js_calcula(document.form1.y63_pago);
}

//mostra iframe de notas
function js_nota(){
   notas = document.form1.notas.value;

   if(top.corpo.iframe_levvalor.db_iframe_nota){
      db_iframe_nota.show();
   }else{
      if(notas!=""){
         js_OpenJanelaIframe('top.corpo.iframe_levvalor','db_iframe_nota','fis1_levantanotas001.php?<?=($db_opcao==33?'db_opcao=33&':'')?>notas='+notas,'Pesquisa',true,0);
      }else{
         js_OpenJanelaIframe('top.corpo.iframe_levvalor','db_iframe_nota','fis1_levantanotas001.php?<?=($db_opcao==33?'db_opcao=33':'')?>','Pesquisa',true,0);
      }
   }
}
//esconde o iframe de notas
function js_fecha02(){
  db_iframe_nota.hide();

  if(document.form1.y63_bruto.value>0){
    document.form1.y63_bruto.readOnly=true;;
    document.form1.y63_bruto.style.backgroundColor = '#DEB887';
  }else{
    document.form1.y63_bruto.readOnly=false;;
    document.form1.y63_bruto.style.backgroundColor = '';
  }
  js_calcula(document.form1.y63_pago);
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_levvalor','db_iframe_levvalor','func_levvalor.php?funcao_js=parent.js_preenchepesquisa|y63_sequencia','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_levvalor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>