<?php
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

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("k02_codigo");
$clrotulo->label("k02_drecei");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e48_cgm");

require_once(modification("libs/db_utils.php"));
$clsaltes = db_utils::getDao("saltes");
require_once(modification("libs/JSON.php"));
// seleciona conta a creditar
$db_opcao = 1;


$sqlsaltes = $clsaltes->sql_query_anousu(null,
                                         "k13_conta,
                                         k13_descr",
                                         null,
                                         "c61_instit = ".db_getsession("DB_instit") . "
                                          and k13_limite is null
                                           or k13_limite > '".date("Y-m-d",db_getsession("DB_datausu"))."'");

/* [Extensão] - Filtro da Despesa */

$result_conta_creditar = $clsaltes->sql_record($sqlsaltes);
$oJson = new Services_JSON();
echo "<script>\n";
echo "aContasDebito = eval('(".$oJson->encode(db_utils::getCollectionByRecord($result_conta_creditar,false, false, true)).")');\n";

if ($folha==1) {
  echo "isFolha=true\n";
} else {
  echo "isFolha=false\n";
}
echo "</script>\n";
?>
<style type="text/css">
.primeiraColuna{
 width: 70px;
}
</style>
<form name="form1" method="post" action="">
<table>
  <tr>
    <td>
      <fieldset>
        <legend><b>Filtros</b></legend>
        <table id='folha' style='display: none'>
          <tr>
          <td align="left" nowrap class="primeiraColuna" >
            <b>Ano / Mês :</b>
          </td>
          <td>
            <?
              $anofolha = db_anofolha();
              db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto()'");
            ?>
            &nbsp;/&nbsp;
            <?
              $mesfolha = db_mesfolha();
              db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto()'");
            ?>
          </td>
        </tr>
        <tr>
          <td  class="primeiraColuna">
            <b>Ponto:</b>
          </td>
          <td>
           <?

             $aSigla = array( "r14"=>"Salário",
                              "r48"=>"Complementar",
                              "r35"=>"13o. Salário",
                              "r20"=>"Rescisão",
                              "r22"=>"Adiantamento");

             db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto()'");
           ?>
          </td>
        </tr>
        <tr>
          <td  class="primeiraColuna">
            <b>Tipo:</b>
          </td>
          <td>
           <?
             $aTipos = array(
                             "1" => "Salário        ",
                             "2" => "Previdência    ",
                             "3" => "FGTS           ",
                           );

             db_select('tipo',$aTipos,true,4);
            ?>
           </td>
         </tr>
        <tr id='linhaComplementar' style='display:none'>
        </tr>
         <tr>
            <td class="primeiraColuna">
               <? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);", 1); ?>
            </td>
            <td>
               <? db_input('o15_codigo',10,$Io15_codigo,true,'text', 1," onchange='js_pesquisac62_codrec(false);'") ?>
               <? db_input('o15_descr',40,$Io15_descr,true,'text',3,'')   ?>
            </td>
         </tr>
         <tr>
           <td align="left" nowrap class="primeiraColuna">
             <?
              db_ancora(@$Lk02_codigo,"js_pesquisatabrec(true);",4);
             ?>
           </td>
           <td>
            <?
             db_input('k02_codigo',10,$Ik02_codigo,true,'text',2,"onchange='js_pesquisatabrec(false);'");
             db_input('k02_drecei',40,$Ik02_drecei,true,'text',3);
             ?>
           </td>
         </tr>
        </table>
        <table  style='display: none' id='fornecedores'>
          <tr>
            <td class="primeiraColuna">
               <b>Data Inicial:</b>
            </td>
            <td>
              <?
                db_inputdata("datainicial",null,null,null,true,"text", 1);
              ?>
            </td>
            <td class="primeiraColuna">
              <b>Data Final:</b>
            </td>
            <td>
              <?
                db_inputdata("datafinal",null,null,null,true,"text", 1);
              ?>
            </td>
          </tr>

          <tr>
            <td class="primeiraColuna">
               <? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec2(true);", 1); ?>
            </td>
            <td colspan="4">
               <? db_input('o15_codigo2',10,$Io15_codigo,true,'text', 1," onchange='js_pesquisac62_codrec2(false);'") ?>
               <? db_input('o15_descr2',40,$Io15_descr,true,'text',3,'')   ?>
            </td>
         </tr>
         </table>
         <table>
          <tr>
           <td nowrap title="<?=@$Tz01_numcgm?>" class="primeiraColuna">
            <?
            db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
            ?>
            </td>
            <td  colspan='4' nowrap>
            <?
             db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
             db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
            ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="4" style='text-align:center'>
      <input type='button' value='Pesquisar' onclick="js_getMovimentos()">
    </td>
  </tr>
</table>
<table width="70%">
  <tr>
  <td>
  <fieldset style='width: 100%'>
    <legend><b>Valores a serem transferidos</b></legend>
    <div id='gridSlip' >
    </div>
  </fieldset>
  </td>
  </tr>
  <tr>
    <td colspan="2" style='text-align:center'>
      <input type='button' value='Processar' onclick="js_gerarsLip();">
    </td>
  </tr>
</table>
</form>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>

<script type="text/javascript">


function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
   }else{
       if(document.form1.o15_codigo.value != ''){
           js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
       }else{
           document.form1.o15_descr.value = '';
       }
   }
}
function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave;
   if(erro==true){
      document.form1.o15_codigo.focus();
      document.form1.o15_codigo.value = '';
   }
}

function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
}

function js_pesquisac62_codrec2(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec',
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec3|o15_codigo|o15_descr','Pesquisa',
                           true);
   }else{
       if(document.form1.o15_codigo2.value != ''){
           js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec',
                               'func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo2.value+
                               '&funcao_js=parent.js_mostraorctiporec2','Pesquisa',false);
       }else{
           document.form1.o15_descr2.value = '';
       }
   }
}
function js_mostraorctiporec2(chave,erro){
   document.form1.o15_descr2.value = chave;
   if(erro==true){
      document.form1.o15_codigo2.focus();
      document.form1.o15_codigo2.value = '';
   }
}

function js_mostraorctiporec3(chave1,chave2) {
    document.form1.o15_codigo2.value = chave1;
    document.form1.o15_descr2.value = chave2;
    db_iframe_orctiporec.hide();
}

function js_pesquisatabrec(mostra){
       if(mostra==true){
         js_OpenJanelaIframe('top.corpo',
                             'db_iframe_tabrec',
                             'func_tabrec.php?tiporec=E&funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei|recurso|arretipo|k00_descr'
                             ,'Pesquisa',true,'15');
       }else{
         if( document.form1.k02_codigo.value != ''){
           js_OpenJanelaIframe('top.corpo',
                               'db_iframe_tabrec',
                               'func_tabrec.php?tiporec=E&pesquisa_chave='+
                               document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec',
                               'Pesquisa',
                               false);
         }else{
           document.form1.k02_drecei.value = '';
         }
       }
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome',
                        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){
        js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+
                            '&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}

function js_mostracgm1(chave1,chave2){

  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  func_nome.hide();

}
function js_mostratabrec(chave2,erro,chave3,chave4,chave5) {

  document.form1.k02_drecei.value  = chave2;

  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }

}
function js_mostratabrec1(chave1,chave2,chave3,chave4,chave5){

     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     db_iframe_tabrec.hide();
}
/**
 * Iniciamos a construcao do aplicativo.
 */
function init() {

  iTipoGerarSlip         = 1;
  oGridSlip              = new DBGrid("gridSlip");
  oGridSlip.nameInstance = "oGridSlip";

  /**
   * sobreescrevemos o metodo selectSingle do DBGrid
   */
  oGridSlip.selectSingle = function (oCheckbox, sRow, oRow,lVerificaSaldo) {

    if (lVerificaSaldo == null) {
      var lVerificaSaldo = true;
    }

    if (oCheckbox.checked) {

      oRow.isSelected    = true;
      $(sRow).className  = 'marcado';
      if (lVerificaSaldo) {
        $('TotalForCol8').innerHTML = js_formatar(oGridSlip.sum(8).toFixed(2),'f');
      }
      $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)+1;
    } else {

      $(sRow).className = oRow.getClassName();
      oRow.isSelected   = false;
      if (lVerificaSaldo) {
        $('TotalForCol8').innerHTML = js_formatar(oGridSlip.sum(13).toFixed(2),'f');
      }
      $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)-1;
    }
  }

  /**
   * sobreescrevemos o metodo selectall do DBGrid
   */
  oGridSlip.selectAll = function(idObjeto, sClasse, sLinha) {

    var obj = document.getElementById(idObjeto);
    if (obj.checked){
      obj.checked = false;
    } else{
      obj.checked = true;
    }

    itens = this.getElementsByClass(sClasse);
    for (var i = 0;i < itens.length;i++){

      if (itens[i].disabled == false){
        if (obj.checked == true){

          if ($(this.aRows[i].sId).style.display != 'none') {
            if (!itens[i].checked) {

              itens[i].checked=true;
              this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);

            }

          }
        } else {

          if (itens[i].checked) {

            itens[i].checked=false;
            this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);
          }
        }
      }
    }
    $('TotalForCol8').innerHTML = js_formatar(oGridSlip.sum(8).toFixed(2),'f');
  }
  oGridSlip.setCheckbox(0);
  oGridSlip.setCellAlign(new Array("right", "Right", "right", "right", "left", "right","left", "right","right"));
  var aHeaders = new Array(
                           "Arrecadacao",
                           "OP",
                           "Cta Credito",
                           "Cta Debito",
                           "Receita",
                           "Recurso",
                           "Credor",
                           "Valor",
                           "Nº Favorecido"
                          );
  oGridSlip.setHeader(aHeaders);
  oGridSlip.aHeaders[9].lDisplayed = false;
  oGridSlip.hasTotalizador = true;
  oGridSlip.show($('gridSlip'));
  $('ponto').style.width="150px";
  $('tipo').style.width="150px";
  $('gridSlipstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
  if (isFolha) {
    $('folha').style.display='';
  } else {
    $('fornecedores').style.display='';
  }
}
sUrlRPC = "emp4_gerarslipRPC.php";

function js_getMovimentos() {

  js_divCarregando("Aguarde, consultando informações.","msgBox");
  js_controleBotoes(true);
  var oRequisicao             = new Object();
  oRequisicao.exec            = "getArrecExtra";
  oRequisicao.isFolha         = isFolha;
  oRequisicao.paramFolha      = js_getQueryDadosFolha();
  oRequisicao.iRecurso        = $F('o15_codigo');
  oRequisicao.iNumCgm         = $F('z01_numcgm');

  if (!isFolha) {

    oRequisicao.iRecurso        = $F('o15_codigo2');
    dtInicial = $F('datainicial');
    dtFinal   = $F('datafinal');
    oRequisicao.dtIni   = dtInicial;
    oRequisicao.dtFim   = dtFinal;

  }
  oRequisicao.iReceita        = $F('k02_codigo');

  var oAjax = new Ajax.Request(
                           sUrlRPC,
                           {
                            method    : 'post',
                            parameters:'json='+Object.toJSON(oRequisicao),
                            onComplete: js_retornoGetMovimentos
                            }
                          );

}

function js_retornoGetMovimentos(oAjax) {

  js_removeObj("msgBox");
  js_controleBotoes(false);
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridSlip.clearAll(true);
  if (oRetorno.status == 1) {

    var iRowAtiva = 0;
    for (var i = 0; i < oRetorno.itens.length;i++ ){

      with (oRetorno.itens[i]) {

  	  	var aLinha = new Array();
  	 	  aLinha[0]  = k12_numpre;
  	 	  aLinha[1]  = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
  	 	  aLinha[1] += e50_codord+"</a>";
  	 	  aLinha[2]  = js_createComboContas(k12_numpre, aContasDebito, credito, false);
  	 	  aLinha[3]  = debito;
  	 	  aLinha[4]  = k02_drecei.urlDecode().substring(0,20);
  	 	  aLinha[5]  = k00_recurso;
  	 	  aLinha[6]  = z01_nome.urlDecode().substring(0, 30);
  	 	  aLinha[7]  = js_formatar(k12_valor, "f");
  	 	  aLinha[8]  = z01_numcgm;

  		  oGridSlip.addRow(aLinha);

  		  oGridSlip.aRows[i].aCells[7].sEvents  = "onmouseover='js_setAjuda(\""+z01_nome.urlDecode()+"\",true)'";
        oGridSlip.aRows[i].aCells[7].sEvents += "onmouseOut='js_setAjuda(null,false)'";
        oGridSlip.aRows[i].aCells[4].sEvents  = "onmouseover='js_setAjuda(\""+descrdebito.urlDecode()+"\",true)'";
        oGridSlip.aRows[i].aCells[4].sEvents += "onmouseOut='js_setAjuda(null,false)'";
  	  }
    }

    oGridSlip.renderRows();
    oGridSlip.setNumRows(oRetorno.itens.length);
    $('gridSlipstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_objectToJson(oObject) { return JSON.stringify(oObject); 

   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;

}

function js_gerarsLip() {

  var aItens = oGridSlip.getSelection("object");
  if (aItens.length == 0) {

    alert("Nenhum Registro Selecionado.");
    return false;
  }

  var oRequisicao             = new Object();
  oRequisicao.exec            = "gerarSlipsExtra";
  oRequisicao.aSlips          = new Array();
  oRequisicao.isFolha         = isFolha;
  oRequisicao.paramFolha      = js_getQueryDadosFolha();
  for (var i = 0; i < aItens.length;i++ ) {

    var oSlip = new Object();
    oSlip.iCtaCredito  = aItens[i].aCells[3].getValue();
    if (aItens[i].aCells[3].getValue() == "") {

      alert('Arrecadação '+aItens[i].aCells[1].getValue()+" sem conta crédito informada.");
      delete oSlip;
      delete oRequisicao;
      return false;

    }
    oSlip.iCtaDebito   = aItens[i].aCells[4].getValue();
    oSlip.iRecurso     = aItens[i].aCells[6].getValue(6);
    oSlip.iArrecadacao = aItens[i].aCells[1].getValue();
    oSlip.iCGM         = aItens[i].aCells[9].getValue();
    oSlip.iOrdem       = aItens[i].aCells[2].getValue();
    oSlip.nValor       = js_strToFloat(aItens[i].aCells[8].getValue()).valueOf();

    oRequisicao.aSlips.push(oSlip);
  }
  if (!confirm('Confirma a emissão dos slips?')) {
    return false;
  }
  js_divCarregando("Aguarde, Gerando slips.","msgBox");
  var oAjax = new Ajax.Request(
                           sUrlRPC,
                           {
                            method    : 'post',
                            parameters:'json='+Object.toJSON(oRequisicao),
                            onComplete: js_retornoGerarSlips
                            }
                          );
}

function js_retornoGerarSlips(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1 ) {

    var sSlis = oRetorno.aSlipsRetorno.implode(',');
    if (confirm(('Slip(s) ('+sSlis+') gerado(s) com sucesso.\n\nDeseja Emiti-lo(s)?'))) {
      js_emiteSlips(sSlis);
    }
    js_getMovimentos();
  }
}

function js_emiteSlips(sSlips) {
   window.open('cai3_emiteslips002.php?slips='+sSlips,'','location=0');
}

function js_controleBotoes(lDisabled) {

   var aItens = $$('input[type=submit], input[type=button], button');
   aItens.each(function(input,id) {

     input.disabled = lDisabled;

   });
}
var sUrl = 'pes1_rhempenhofolhaRPC.php';

 function js_consultaPontoComplementar(){

   js_divCarregando('Consultando ponto complementar...','msgBox');
   js_bloqueiaTela(true);

   var sQuery  = 'sMethod=consultaPontoComplementar';
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');

   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoPontoComplementar
                                          }
                                  );

 }

 function js_retornoPontoComplementar(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);

   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');


   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;

   if ( iLinhasSemestre > 0 ) {


     sLinha += " <td align='left' title='Nro. Complementar'> ";
     sLinha += "   <strong>Nro. Complementar:</strong>       ";
     sLinha += " </td>                                       ";
     sLinha += " <td>                                        ";
     sLinha += "   <select id='semestre' name='semestre' style='width:150px'>    ";
     sLinha += "     <option value = '0'>Todos</option>      ";

     for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
       with( aRetorno.aSemestre[iInd] ){
         sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>";
       }
     }

     sLinha += " </td>                                       ";

   } else {

     sLinha += " <td colspan='2' align='center'>                                ";
     sLinha += "   <font color='red'>Sem complementar para este período.</font> ";
     sLinha += " </td>                                                          ";

   }

   $('linhaComplementar').innerHTML     = sLinha;
   $('linhaComplementar').style.display = '';

 }

 function js_validaTipoPonto(){

   if ( $F('ponto') == 'r48') {
     js_consultaPontoComplementar();
   } else {
     $('linhaComplementar').style.display = 'none';
   }

 }
 function js_bloqueiaTela(lBloq){

   if ( lBloq ) {
     $('anofolha').disabled = true;
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;

     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = true;
       }
     }

   } else {

     $('anofolha').disabled = false;
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;

     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = false;
       }
     }

   }
 }


 function js_getQueryDadosFolha(){

   var oParam       = new Object();
   oParam.iAnoFolha = $F('anofolha');
   oParam.iMesFolha = $F('mesfolha');
   oParam.sSigla    = $F('ponto');
   oParam.iTipo     = $F('tipo');
   oParam.sSemestre = "0";

       if ( $F('ponto') == 'r48' ) {
         if ($('semestre')) {
           oParam.sSemestre = $F('semestre');
         }
       }

   return oParam;

 }


function js_setAjuda(sTexto,lShow) {

  if (lShow) {

    el =  $('gridSlip');
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;

  } else {
   $('ajudaItem').style.display = 'none';
  }
}


function js_createComboContas(iCodMov,aContas, iContaConfig, lDisabled) {

  var sDisabled = "";
  if (lDisabled == null) {
   lDisabled = false;
  }
  if (lDisabled) {
    sDisabled = " disabled ";
  }
  var sCombo  = "<select id='ctapag"+iCodMov+"' class='ctapag' style='width:100%'";
  sCombo     += "<option value=''>Selecione</option>";
  if (aContas != null) {

    for (var i = 0; i < aContas.length; i++) {

      var sSelected = "";
      if (iContaConfig == aContas[i].k13_conta) {
        sSelected = " selected ";
      }
      var sDescrConta =  aContas[i].k13_conta+" - "+aContas[i].k13_descr.urlDecode();
      sCombo += "<option "+sSelected+" value = "+aContas[i].k13_conta+">"+sDescrConta+"</option>";

    }
  }
  sCombo  += "</select>";
  return sCombo;
}
init();
</script>