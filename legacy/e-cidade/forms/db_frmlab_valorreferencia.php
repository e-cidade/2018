<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: Laboratorio
$cllab_valorreferencia->rotulo->label();
$cllab_tiporeferenciaalfa->rotulo->label();
$cllab_tiporeferenciaalnumerico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la27_i_codigo");
$clrotulo->label("la28_i_codigo");
$clrotulo->label("la13_c_descr");
$clrotulo->label("la25_c_descr");
$clrotulo->label("la30_casasdecimaisapresentacao");
$clrotulo->label("la51_i_valorrefsel");

$sNomeBotao      = "incluir";
$iBloqueiaAncora = 1;
switch ($db_opcao) {
  case 2:
  case 22:
    $sNomeBotao      = "alterar";
    $iBloqueiaAncora = 3;
    break;
  case 3:
  case 33:
    $sNomeBotao = "excluir";
    $iBloqueiaAncora = 3;
    break;
  default:
    $sNomeBotao = "incluir";
    break;
}
?>

<fieldset >
  <legend><b>Valor de Referência</b></legend>
  <form name="form1" method="post" action="">
    <table class='form-container' >
      <tr>
        <td nowrap title="<?=$Tla27_i_codigo?>">
          <?=$Lla27_i_codigo?>
        </td>
        <td>
          <?php
          db_input('la27_i_codigo',10,$Ila27_i_codigo,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla27_i_unidade?>">
          <?php
            db_ancora($Lla27_i_unidade,"js_pesquisala27_i_unidade(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?php
          db_input('la27_i_unidade',10,$Ila27_i_unidade,true,'text',$db_opcao," onchange='js_pesquisala27_i_unidade(false);'");
          db_input('la13_c_descr',50,$Ila13_c_descr,true,'text',3,'')
         ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tla27_i_atributo?>">
          <?php
            db_ancora(@$Lla27_i_atributo,"js_pesquisala27_i_atributo(true);",$iBloqueiaAncora);
          ?>
        </td>
        <td>
          <?php
            db_input('la27_i_atributo', 10, $Ila27_i_atributo, true, 'text',
                     $iBloqueiaAncora," onchange='js_pesquisala27_i_atributo(false);'");
            db_input('la25_c_descr',50,$Ila25_c_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td>
           <b><strong>Tipo do Valor <strong></b>
        </td>
        <td>
          <?php
            $aTipos = Array("0"=>"Selecione:::","1"=>"Alfanumérico","2"=>"Numérico");
            db_select("iTipo",$aTipos,"",$db_opcao,"onchange=\"js_trocatipo(this.value);\"");
          ?>
        </td>
      </tr>
    </table>

    <!-- Tabela Tipo de referencial alpha -->
    <fieldset id="alfa" style='display:none;'>
      <legend>Alfanumerico</legend>
      <div style="text-align:left">
      <label id='labelAlfa'> <?=$Lla29_i_fixo?> </label>
      <?php
        db_input('marcado',1,@$Imarcado,true,'checkbox',$db_opcao,"onclick='js_bloqueia(this.value)'");
        db_input('la29_i_codigo',10,$Ila29_i_codigo,true,'hidden',$db_opcao,"");
        db_input('la29_i_fixo',10,$Ila29_i_fixo, true, 'text', 1, "onchange=\"js_validaTamanho();\"","","",
                 "parent.js_validaTamanho();");
      ?>
      </div>

      <fieldset class='separator'>
        <legend><?=$Lla51_i_valorrefsel?></legend>
        <label id='alfaValorReferencial'>
          <?php
            $sSqlValoresReferenciaSelecionaveis = $cllab_valorreferenciasel->sql_query("",
                                                                                      "la28_i_codigo as chave,
                                                                                      la28_c_descr as descricao"
                                                                                     );
            $rResult = $cllab_valorreferenciasel->sql_record($sSqlValoresReferenciaSelecionaveis);
            $aReferencialsel = array();
            for ($x = 0; $x < $cllab_valorreferenciasel->numrows; $x++) {
              $oValor = db_utils::fieldsMemory($rResult, $x);
              $aReferencialsel[$oValor->chave] = $oValor->descricao;
            }
            db_select("la28_i_codigo",$aReferencialsel,$Ila28_i_codigo,$db_opcao,"");
          ?>
          <input type="button" name="lanc" id="lanc" value="Lancar"
                 <?=$db_opcao == 3 ? 'disabled ' : ''?> onclick="js_lanc();">
        </label>
        <br>
        <select name="boxValorRefSel" id="boxValorRefSel" size="5" value=""
                ondblclick="js_delete();" style=""
               <?=$db_opcao == 3 ? 'disabled ' : ''?>>
        </select>
        <br><span style="font-size:1.px">*Clique duas vezes para deletar</span>
        <input name="str_valorRefSel" id="str_ValorRefSel" value="" type="hidden" >
        <?php
          if (isset($aValorRefSel)) {

            if (count($aValorRefSel>0)) {

              echo"<script>";
              for($x=0;$x<count($aValorRefSel);$x++){
                  echo"document.form1.boxValorRefSel.add(new Option('".$aValorRefSel[$x][2]."','".$aValorRefSel[$x][1]."'),null);  ";
              }
              echo"</script>";
            }
          }
        ?>
      </fieldset>


    </fieldset>
    <!-- Tabela Tipo de referencial numerico -->
    <br>
    <fieldset id="numerico" style='display:none'>

      <legend>
        <b>Numérico</b>
      </legend>
      <table   border="0">
        <tr>
          <td nowrap title="<?=@$Tla30_f_normalmin?>">
            <?=@$Lla30_f_normalmin?>
          </td>
          <td>
            <?
            db_input('la30_i_codigo', 5, $Ila30_i_codigo, true,'hidden',$db_opcao,"");
            db_input('la30_f_normalmin',5,$Ila30_f_normalmin,true,'text',$db_opcao,
                     "onchange=\"js_validaNormal();\"","","","parent.js_validaNormal();");
            ?>
          </td>
          <td nowrap title="<?=$Tla30_f_normalmax?>">
            <?php
              echo $Lla30_f_normalmax;
            ?>
          </td>
          <td>
            <?php
              db_input('la30_f_normalmax',5,$Ila30_f_normalmax,true,'text',$db_opcao,"onchange=\"js_validaNormal();\"","","","parent.js_validaNormal();")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla30_f_absurdomin?>">
           <?=$Lla30_f_absurdomin?>
          </td>
          <td>
            <?
            db_input('la30_f_absurdomin',5,$Ila30_f_absurdomin,true,'text',$db_opcao,"onchange=\"js_validaAbsurdo();\"","","","parent.js_validaAbsurdo();")
            ?>
          </td>
          <td nowrap title="<?=@$Tla30_f_absurdomax?>">
            <?php
              echo $Lla30_f_absurdomax
            ?>
          </td>
          <td>
            <?
              db_input('la30_f_absurdomax',5, $Ila30_f_absurdomax, true, 'text', $db_opcao,
                       "onchange=\"js_validaAbsurdo();\"","","","parent.js_validaAbsurdo();"
                      );
            ?>
           </td>
        </tr>
        <tr>
          <td colspan = '2' class="bold">Casas decimais para apresentação:</td>
          <td colspan = '2'>
            <select id= 'la30_casasdecimaisapresentacao' >
              <option value='null'>Sem Regra</option>
              <option value='1'>1</option>
              <option value='2'>2</option>
              <option value='3'>3</option>
              <option value='4'>4</option>
              <option value='5'>5</option>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="6">
            <fieldset class="separator">
              <legend>Valores de Referência  De / Até</legend>
              <table>
                <tr>
                  <td>
                    <b>
                      Anos Inicial:
                    </b>
                  </td>
                  <td>
                    <input type="text" id="anosinicial" size="10">
                  </td>
                  <td>
                    <b>
                      Meses Inicial:
                    </b>
                  </td>
                  <td>
                    <input type="text" id="mesesinicial" size="10">
                  </td>
                  <td>
                    <b>
                      Dias Inicial:
                    </b>
                  </td>
                  <td>
                    <input type="text" id="diasinicial" size="10">
                  </td>
                <tr>
                  <td>
                    <b>
                      Anos Final:
                    </b>
                  </td>
                  <td>
                    <input type="text" id="anosfinal" size="10">
                  </td>
                  <td>
                    <b>
                      Meses Final:
                    </b>
                  </td>
                  <td>
                    <input type="text" id="mesesfinal" size="10">
                  </td>
                  <td>
                    <b>
                      Dias Final:
                    </b>
                  </td>
                  <td>
                    <input type="text" id="diasfinal" size="10">
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="6">
              <fieldset class="separator">
                <legend>Faixa Válida Para</legend>
              <table>
                <tr>
                  <td class="bold">
                  Sexo:
                  </td>
                  <td>
                    <input type="checkbox" id="masculino" checked>
                    <label class="bold" for="masculino"> Masculino</label>
                  </td>
                  <td>
                    <input type="checkbox" id="feminino" checked>
                    <label class="bold" for="feminino">Feminimo</label>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="6">
            <fieldset class="separator" style="border-bottom: 2px groove white">
              <legend>Cálculo</legend>
              <table>
                <tr>
                  <td>
                    <b>Cálculo:</b>
                  </td>
                  <td>
                    <select id="tipocalculo" onchange="showHideCampoCalculavel(this.value);">
                      <option value="0">Sem Cálculo</option>
                      <option value="1">Valor Absoluto</option>
                      <option value="2">Percentual</option>
                    </select>
                  </td>
                  <td nowrap>
                    <?php
                    db_ancora("Atributo Base:","js_pesquisala27_i_atributobase(true);",$db_opcao, "", "link-atributo-base");
                    ?>
                  </td>
                  <td nowrap>
                    <?
                    db_input('la27_i_atributobase', 10, $Ila27_i_atributo, true, 'text',
                      $db_opcao," onchange='js_pesquisala27_i_atributobase(false);' disabled");
                    db_input('la25_c_descrbase', 25,$Ila25_c_descr,true,'text',3,'');
                    ?>
                  </td>
                </tr>
                <tr>
          <td nowrap title="<?=@$Tla30_c_calculavel?>">
            <?=@$Lla30_c_calculavel?>
          </td>
          <td colspan="5">
           <?php
            db_input('la30_c_calculavel',60,$Ila30_c_calculavel,true,'text',$db_opcao, "disabled")
           ?>
          </td>
        </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: center">
            <input type="button" id='salvarAtributo' value="Salvar" onclick="js_salvarReferenciaNumerica()">
          </td>
        </tr>
        <tr>
          <td colspan="6">
            <fieldset>
              <legend>Valores Inclusos</legend>
              <div id="ctnGridValores">
              </div>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>

    <input name="<?=$sNomeBotao?>" type="submit" id="db_opcao" value="<?=ucfirst($sNomeBotao)?>"
           <?=($db_botao==false?"disabled":"")?> onclick="return js_valida()">
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</fieldset>


<script>
var $JQuery = jQuery.noConflict();//retirar conflito jquery com outras bibliotecas

/* INICIO - Mostra e oculta campo calculavel
MARCO adicionado 11-02-2015*/

var campoCalculavel = document.getElementById('la30_c_calculavel');//pega o campo Calculavel
campoCalculavel.value = 'Altere o valor do campo Cálculo para habilitar este campo';//insere a mensagem de inicio

var campo_atri_base = document.getElementById('la27_i_atributobase');//campo Atributo Base

//habilita e desabilita campo
function showHideCampoCalculavel(valor)
{
    if(valor == 1)
    {
        campoCalculavel.disabled = 0;//ativa
        campoCalculavel.value = '';//limpa campo
    }
    else
    {
        campoCalculavel.disabled = 1;//desativa
        campoCalculavel.value = 'Altere o valor do campo Cálculo para habilitar este campo';//limpa campo
    }

   if(valor == 2)
   {
      campo_atri_base.disabled = 0;//ativa
   }
   else
   {
      campo_atri_base.disabled = 1;//desativa
   }
}

//aceita apenas caracteres ==>  ( ) . * / % numeros
$JQuery(document).ready(function() {
    $JQuery('input#la30_c_calculavel').filter_input({regex:'[0-9-*+/^().]'});//plugin jquery

});

/* FIM - Mostra e oculta campo calculavel*/


var sUrlRPC = 'lab4_atributosexame.RPC.php';
document.form1.la29_i_fixo.disabled=true;
F = document.form1;
<?php

  if(isset($la27_i_codigo) && $db_opcao != 3) {
	  echo("js_trocatipo(F.iTipo.value)");
  }
?>

var URL_MSG_DB_FRMLAB_VALORREFERENCIA = 'saude.laboratorio.db_frmlab_valorreferencia.';
var oGridValores          = new DBGrid("gridValores");
oGridValores.nameInstance = 'oGridValores';
oGridValores.setHeader(['Faixa', 'Normal Min', 'Normal Max', 'Idade Final', 'Sexo', 'Ação']);
oGridValores.show($('ctnGridValores'));

function js_valida(){
	if(F.iTipo.value=='0'){
	   alert('Selecione um tipo!');
       return false;
    }
	F.str_ValorRefSel.value
	if((F.la29_i_fixo.value=='')||(F.la29_i_fixo.value=='0')){
	   var Tam1=F.boxValorRefSel.length;
       sep='';
       for(x=0;x<Tam1;x++){
           F.str_ValorRefSel.value += sep+F.boxValorRefSel.options[x].value;
           sep=',';
       }
    }
    return true;
}

function js_lanc() {

  var F=document.form1;
  var Tam1=F.boxValorRefSel.length;
  if(F.la28_i_codigo.value==''){

    alert('Selecione um referencial!');
    return false;
  }
  for(x=0;x<Tam1;x++){
    if(F.la28_i_codigo.value==F.boxValorRefSel.options[x].value){
      alert('Referencial ja selecionado!');
      return false;
    }
  }
  F.boxValorRefSel.add(new Option(F.la28_i_codigo.options[F.la28_i_codigo.selectedIndex].text,F.la28_i_codigo.value),null);
}
function js_delete(){
    var F=document.form1;
    if(confirm('Excluir Sinonimia:'+F.boxValorRefSel.options[F.boxValorRefSel.selectedIndex].text+'?')){
       F.boxValorRefSel.remove(F.boxValorRefSel.selectedIndex);
    }
}

function js_trocatipo(tipo) {

  var alfa     = document.getElementById('alfa');
  var numerico = document.getElementById('numerico');
  $('db_opcao').style.display = '';
  if (tipo == 1){

    alfa.style.display     = '';
    numerico.style.display = 'none';
  } else if (tipo == 2) {

    alfa.style.display          ='none';
    numerico.style.display      = '';
    $('db_opcao').style.display ='none';

    js_getValoresReferencia();
  } else {

    alfa.style.display     = 'none';
    numerico.style.display = 'none';
  }
}

// Lookup
function js_pesquisala27_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_undmedida','func_lab_undmedida.php?funcao_js=parent.js_mostralab_undmedida1|la13_i_codigo|la13_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la27_i_unidade.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_undmedida','func_lab_undmedida.php?pesquisa_chave='+document.form1.la27_i_unidade.value+'&funcao_js=parent.js_mostralab_undmedida','Pesquisa',false);
     }else{
       document.form1.la13_c_descr.value = '';
     }
  }
}
function js_mostralab_undmedida(chave,erro){
  document.form1.la13_c_descr.value = chave;
  if(erro==true){
    document.form1.la27_i_unidade.focus();
    document.form1.la27_i_unidade.value = '';
  }
}
function js_mostralab_undmedida1(chave1,chave2){
  document.form1.la27_i_unidade.value = chave1;
  document.form1.la13_c_descr.value = chave2;
  db_iframe_lab_undmedida.hide();
}

function js_pesquisala27_i_atributo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_atributo','func_lab_atributo.php?analitico=1&funcao_js=parent.js_mostralab_atributo1|la25_i_codigo|la25_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la27_i_atributo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_atributo','func_lab_atributo.php?analitico=1&pesquisa_chave='+document.form1.la27_i_atributo.value+'&funcao_js=parent.js_mostralab_atributo','Pesquisa',false);
     }else{
       document.form1.la25_i_codigo.value = '';
     }
  }
}
function js_mostralab_atributo(chave,erro){
  document.form1.la25_c_descr.value = chave;
  if(erro==true){
    document.form1.la27_i_atributo.focus();
    document.form1.la27_i_atributo.value = '';
  }
}
function js_mostralab_atributo1(chave1,chave2){
  document.form1.la27_i_atributo.value = chave1;
  document.form1.la25_c_descr.value = chave2;
  db_iframe_lab_atributo.hide();
}

function js_pesquisala27_i_atributobase(mostra){
  if(mostra==true){
      //MARCO adicionado 12/02/2015
      if(document.getElementById('tipocalculo').value == 2)//para nao deixar a pessoa clicar no link
         js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_atributo','func_lab_atributo.php?analitico=1&funcao_js=parent.js_mostralab_atributobase1|la25_i_codigo|la25_c_descr','Pesquisa',true);
  }else{
    if(document.form1.la27_i_atributo.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_atributo','func_lab_atributo.php?analitico=1&pesquisa_chave='+document.form1.la27_i_atributo.value+'&funcao_js=parent.js_mostralab_atributobase','Pesquisa',false);
    }else{
      document.form1.la25_i_codigo.value = '';
    }
  }
}
function js_mostralab_atributobase(chave,erro){
  document.form1.la25_c_descrbase.value = chave;
  if(erro==true){
    document.form1.la27_i_atributobase.focus();
    document.form1.la27_i_atributobase.value = '';
  }
}
function js_mostralab_atributobase1(chave1,chave2){
  document.form1.la27_i_atributobase.value = chave1;
  document.form1.la25_c_descrbase.value = chave2;
  db_iframe_lab_atributo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_valorreferencia','func_lab_valorreferencia.php?funcao_js=parent.js_preenchepesquisa|la27_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_lab_valorreferencia.hide();
  //js_getValoresReferencioa(chave);
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

var sMsgNumerosPositivos = _M(URL_MSG_DB_FRMLAB_VALORREFERENCIA+'apenas_positivos');
function js_validaNormal() {

  normal = false;

  if ( $F('la30_f_normalmin') != '' && ! isNumeric($F('la30_f_normalmin'), true) ) {

    $('la30_f_normalmin').value = '';
    alert(sMsgNumerosPositivos);
  }
  if ( $F('la30_f_normalmax') != '' && ! isNumeric($F('la30_f_normalmax'), true) ) {

    $('la30_f_normalmax').value = '';
    alert(sMsgNumerosPositivos);
  }

  if(document.form1.la30_f_normalmax.value != ""  && document.form1.la30_f_normalmin.value != "" ){
    if(parseInt(F.la30_f_normalmax.value,10) < parseInt(F.la30_f_normalmin.value,10)){
   	  alert("Valor Normal Máximo menor que Valor Normal Mínimo");
   	  document.form1.la30_f_normalmax.value = "";
   	  normal=false;
    }
  }
  return normal;
}

function js_validaAbsurdo() {

  absurdo = false;

  if ( $F('la30_f_absurdomin') != '' && ! isNumeric($F('la30_f_absurdomin'), true) ) {

    $('la30_f_absurdomin').value = '';
    alert(sMsgNumerosPositivos);
  }
  if ( $F('la30_f_absurdomax') != '' && ! isNumeric($F('la30_f_absurdomax'), true) ) {

    $('la30_f_absurdomax').value = '';
    alert(sMsgNumerosPositivos);
  }

  if(document.form1.la30_f_absurdomax.value != ""  && document.form1.la30_f_absurdomin.value != "" ){
      if(parseInt(F.la30_f_absurdomax.value,10) < parseInt(F.la30_f_absurdomin.value,10)){
  	      alert("Valor Absurdo Máximo menor que Valor Absurdo Mínimo");
	      document.form1.la30_f_absurdomax.value = "";
	      absurdo=false;
	  }
  }
  return absurdo;
}


function js_bloqueia(valor){

  if (document.getElementById("marcado").checked==true) {
	document.getElementById("la29_i_fixo").disabled=false;
	document.getElementById("la29_i_fixo").readonly=false;

  } else {
	document.getElementById("la29_i_fixo").value='';
	document.getElementById("la29_i_fixo").disabled=true;
  }
}

function js_validaTamanho() {

	var tamanho = false;

  if(document.form1.la29_i_fixo.value != ""){
	if(document.form1.la29_i_fixo.value == 0 || document.form1.la29_i_fixo.value > 100) {
	  alert("Valor Fixo tem que ser maior que 1 e menor igual a 100");
	  document.form1.la29_i_fixo.value = "";
	  tamanho=false;
	}

  }
  return tamanho;
}



function js_getValoresReferencia() {

  var oParam = {exec:'getValoresReferenciaAtributo', iCodigoAtributo: $F('la27_i_atributo')};
  js_divCarregando("Aguarde, pesquisando faixas de Referencia", 'msgBox');
  $('iTipo').disabled = false;
  var oAjax  = new Ajax.Request(sUrlRPC,
                               {
                                method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete: function(oResponse) {

                                  js_removeObj('msgBox');
                                  var oRetorno = eval("("+oResponse.responseText+")");
                                  js_preencheGridValoresNumericos(oRetorno.aValoresNumericos);
                                  if (oRetorno.aValoresNumericos.length > 0) {
                                    $('iTipo').disabled = true;
                                  }
                                }
                               }
                               )
}


function js_preencheGridValoresNumericos(aValores) {
  oGridValores.clearAll(true);

  aValores.each(function(oValor, iSeq) {

    var aLinha = [];
    aLinha[0]  = oValor.codigo;
    aLinha[1]  = oValor.valor_inicial;
    aLinha[2]  = oValor.valor_final;
    aLinha[3]  = js_montaStringIdade(oValor.limite_idade);
    aLinha[4]  = oValor.sexo.implode(",");
    aLinha[5]  = '<input type="button" value="E" onclick="js_excluirReferencia('+oValor.codigo+')">';
    aLinha[5]  += '<input type="button" style="margin-left:15px" value="A" onclick="js_alterarReferencia('+oValor.codigo+')">';
    oGridValores.addRow(aLinha);
  });
  oGridValores.renderRows();

}

function js_montaStringIdade(oIdade) {

  var sStringIdade = '';
  if (oIdade.anos > 0) {
    sStringIdade += oIdade.anos+" A ";
  }

  if (oIdade.iMeses > 0) {
    sStringIdade += oIdade.meses+" M ";
  }

  if (oIdade.dias > 0) {
    sStringIdade += oIdade.dias+" D ";
  }
  return sStringIdade;

}

function js_salvarReferenciaNumerica() {

  var oParam               = {exec:'salvarReferenciaNumerica'}
  oParam.iAtributo         = $F('la27_i_atributo');
  oParam.iCodigoReferencia = $F('la27_i_codigo');
  oParam.iUnidadeMedida    = $F('la27_i_unidade');

  var aSexos = new Array();
  if ($('masculino').checked) {
    aSexos.push('M');
  }
  if ($('feminino').checked) {
    aSexos.push('F');
  }

  //MARCO adicionado 12-02-2015
  //altera o caracter de + para @, depois na hora de inserir volta o @ para + no BD
  var valorCalculavel = $F('la30_c_calculavel');
  if(!$JQuery('input#la30_c_calculavel').attr('disabled'))
    valorCalculavel = valorCalculavel.replace('+', '@');
  else
    valorCalculavel = '';
  //END MARCO

  var oReferencia                 = {};
  oReferencia.iCodigo             = $F('la30_i_codigo');
  oReferencia.iValorMinimo        = $F('la30_f_normalmin');
  oReferencia.iValorMaximo        = $F('la30_f_normalmax');
  oReferencia.iValorAbsurdoMinimo = $F('la30_f_absurdomin');
  oReferencia.iValorAbsurdoMaximo = $F('la30_f_absurdomax');
  oReferencia.iCasasDecimais      = $F('la30_casasdecimaisapresentacao');
  //oReferencia.sCalculavel         = $F('la30_c_calculavel');
  oReferencia.sCalculavel         = valorCalculavel;//MARCO adicionado 12-02-2015
  oReferencia.iAnosInicial        = $F('anosinicial') == '' ? 0 : $F('anosinicial');
  oReferencia.iMesesInicial       = $F('mesesinicial') == '' ? 0 : $F('mesesinicial');
  oReferencia.iDiasInicial        = $F('diasinicial') == '' ? 0 : $F('diasinicial');
  oReferencia.iAnosFinal          = $F('anosfinal') == '' ? 0 : $F('anosfinal');
  oReferencia.iMesesFinal         = $F('mesesfinal') == '' ? 0 : $F('mesesfinal');
  oReferencia.iDiasFinal          = $F('diasfinal') == '' ? 0 : $F('diasfinal');
  oReferencia.aSexos              = aSexos;
  oReferencia.iTipoCalculo        = $F('tipocalculo');
  oReferencia.iAtributoBase       = $F('la27_i_atributobase');
  oParam.oReferencia              = oReferencia;
  js_divCarregando("Aguarde, salvando faixa de Referência", 'msgBox');
  var oAjax  = new Ajax.Request(sUrlRPC,
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: function(oResponse) {

        js_removeObj('msgBox');
        var oRetorno = eval("("+oResponse.responseText+")");
        alert(oRetorno.message.urlDecode());
        if (oRetorno.status == 1) {

          $('la30_i_codigo').value       = '';
          $('la30_f_normalmin').value    = '';
          $('la30_f_normalmax').value    = '';
          $('la30_f_absurdomin').value   = '';
          $('la30_f_absurdomax').value   = '';
          $('la30_c_calculavel').value   = '';
          //$('anosinicial').value         = $F('anosfinal');
          //$('mesesinicial').value        = $F('mesesfinal');
          //$('diasinicial').value         = $F('diasfinal');
          $('anosinicial').value         = '';
          $('mesesinicial').value        = '';
          $('diasinicial').value         = '';
          $('anosfinal').value           = '';
          $('mesesfinal').value          = '';
          $('diasfinal').value           = '';
          $('tipocalculo').value         = 0;
          $('la27_i_atributobase').value = '';
          $('la25_c_descrbase').value    = '';
          js_getValoresReferencia();

        }
      }
    }
  )
document.getElementById('la30_casasdecimaisapresentacao').value = null;
}

function js_excluirReferencia(iReferencia) {

  if (!confirm('Confirma a Exclusão da referência?')) {
    return false;
  }
  var oParam = {exec:'removerReferenciaNumerica', iCodigo: iReferencia};
  js_divCarregando("Aguarde, Removendo referencia", 'msgBox');
  $('iTipo').disabled = false;
  var oAjax  = new Ajax.Request(sUrlRPC,
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: function(oResponse) {

        js_removeObj('msgBox');
        var oRetorno = eval("("+oResponse.responseText+")");
        alert(oRetorno.message.urlDecode());
        js_getValoresReferencia();

      }
    }
  )
}

$('labelAlfa').style.marginRight = '30px';
$('alfaValorReferencial').addClassName('field-size-max');
$('la28_i_codigo').style.width = '88%';
$('boxValorRefSel').addClassName('field-size-max');
$('boxValorRefSel').style.marginTop = '5px';

$('anosinicial').oninput = function() {
  js_ValidaCampos( $('anosinicial'), 4, 'Anos Inicial', 't', 'f' );
};

$('mesesinicial').oninput = function() {
  js_ValidaCampos( $('mesesinicial'), 4, 'Meses Inicial', 't', 'f' );
};

$('diasinicial').oninput = function() {
  js_ValidaCampos( $('diasinicial'), 4, 'Dias Inicial', 't', 'f' );
};

$('anosfinal').oninput = function() {
  js_ValidaCampos( $('anosfinal'), 4, 'Anos Final', 't', 'f' );
};

$('mesesfinal').oninput = function() {
  js_ValidaCampos( $('mesesfinal'), 4, 'Meses Final', 't', 'f' );
};

$('diasfinal').oninput = function() {
  js_ValidaCampos( $('diasfinal'), 4, 'Dias Final', 't', 'f' );
};

function js_alterarReferencia(iReferencia){
  var oParam = {exec:'getValorReferenciaAtributo', iCodigo: iReferencia};
  js_divCarregando("Aguarde, Editando referencia", 'msgBox');

  var oAjax  = new Ajax.Request(sUrlRPC,
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: function(oResponse) {
        js_removeObj('msgBox');
        var response = oResponse.responseText.evalJSON();
        $('la30_i_codigo').value = response.oValorReferencia.codigo;
        $('la30_f_normalmin').value = response.oValorReferencia.valor_inicial;
        $('la30_f_normalmax').value = response.oValorReferencia.valor_final;
        $('la30_f_absurdomin').value = response.oValorReferencia.absurdo_minimo;
        $('la30_f_absurdomax').value = response.oValorReferencia.absurdo_maximo;

        $('anosinicial').value = response.oValorReferencia.limite_idade_inicial.anos;
        $('mesesinicial').value = response.oValorReferencia.limite_idade_inicial.meses;
        $('diasinicial').value = response.oValorReferencia.limite_idade_inicial.dias;

        $('anosfinal').value = response.oValorReferencia.limite_idade_final.anos;
        $('mesesfinal').value = response.oValorReferencia.limite_idade_final.meses;
        $('diasfinal').value = response.oValorReferencia.limite_idade_final.dias;

        $('masculino').checked = false;
        $('feminino').checked = false;

        if(jQuery.inArray('M', response.oValorReferencia.sexo) >= 0) {
          $('masculino').checked = true;
        }

        if(jQuery.inArray('F', response.oValorReferencia.sexo) >= 0) {
          $('feminino').checked = true;
        }

        $('tipocalculo').value = response.oValorReferencia.tipo_calculo;
        showHideCampoCalculavel(response.oValorReferencia.tipo_calculo);
        $('la27_i_atributobase').value = response.oValorReferencia.atributo_base;
        $('la25_c_descrbase').value = response.oValorReferencia.atributo_base_nome;
        $('la30_c_calculavel').value = response.oValorReferencia.calculavel;

      }
    }
  )
}

</script>
