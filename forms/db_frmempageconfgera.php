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

$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e87_descgera");

$dados="ordem";
require_once("std/db_stdClass.php");
$iTipoControleRetencaoMesAnterior = 0;
$aParametrosEmpenho = db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")));
if (count($aParametrosEmpenho) > 0) {
  $iTipoControleRetencaoMesAnterior = $aParametrosEmpenho[0]->e30_retencaomesanterior;
}
if (empty($_POST['comboboxCNPJ'])) {
  $_POST['comboboxCNPJ'] = 0;
}
?>
<script>

iTipoControleRetencaoMesAnterior = <?=$iTipoControleRetencaoMesAnterior?>;
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";

  if((evt.charCode >46 && evt.charCode <58) || evt.charCode ==0){//8:backspace|46:delete|190:.
    return true;
  }else{
    return false;
  }
}

function js_atualizar(opcao){
  obj = ordem.document.form1;
  var coluna='';
  var sep='';
  var ncoluna='';
  var nsep='';
  var lMostraMsgErroRetencao  = false;
  var sMsgRetencaoMesAnterior = "Atenção:\n";
  var sVirgula                = "";

  for(i=0; i<obj.length; i++){

    tipo = obj[i].type;
    if(tipo=="checkbox" && obj[i].checked==true){

      coluna += sep+obj[i].name;
      sep = ",";

      var lRetencaoMesAnterior = ordem.document.getElementById('validarretencao'+obj[i].value).innerHTML;
      var iNota                = ordem.document.getElementById('codord'+obj[i].value).innerHTML;
      if (lRetencaoMesAnterior == "true") {

        lMostraMsgErroRetencao   = true;
        sMsgRetencaoMesAnterior += sVirgula+"Movimento "+obj[i].value+" da OP "+iNota+" possui retenções ";
        sMsgRetencaoMesAnterior += " configuradas em mês  diferente do mês atual\n";
        sVirgula = ", ";

      }
    }else if(tipo=="checkbox" && obj[i].checked==false){
      ncoluna += nsep+obj[i].name;
      nsep = ",";
    }
  }
  /**
   * verificamos o parametro para controle de retencões em meses anteriores.
   * caso seje 0 - não faz nenhuma critica ao usuário. apenas realiza o pagamento.
   *           1 - Avisa ao usuário e pede uma confirmação para realizar o pagamento.
   *           2 - Avisa ao usuário e cancela o pagamento do movimento
   */
  var sMsgConfirmaPagamento = "";
  if (iTipoControleRetencaoMesAnterior == 1) {

    if (lMostraMsgErroRetencao) {

      sMsgConfirmaPagamento  =  sMsgRetencaoMesAnterior;
      sMsgConfirmaPagamento += "É Recomendável recalcular as retenções.\n";
      sMsgConfirmaPagamento += "Deseja realmente efetuar a emissão do arquivo texto com  movimentos selecionados?";

      if (!confirm(sMsgConfirmaPagamento)) {
        return false;
      }
    }
  } else if (iTipoControleRetencaoMesAnterior == 2) {

    if (lMostraMsgErroRetencao) {

      sMsgConfirmaPagamento    =  sMsgRetencaoMesAnterior;
      sMsgRetencaoMesAnterior += "Recalcule as Retenções do movimento.";
      alert(sMsgRetencaoMesAnterior);
      return false;

    }
  }

  if(coluna != ""){

    document.form1.movs.value = coluna;
    document.form1.nmov.value = ncoluna;
    return true;

  }else{
    if(opcao=="1"){
      alert("Selecione algum item para gerar arquivo.");
    }else{
      alert("Selecione algum item para cancelar.");
    }
    return false;
  }
}
function js_mostravalores(){
  obj = ordem.document.form1;
  valores = "";
  vir = "";
  for(i=0;i<obj.length;i++){
    if(obj.elements[i].checked==true){
      valores += vir+obj.elements[i].name;
      vir = ",";
    }
  }
  if(valores!=""){
    js_OpenJanelaIframe('top.corpo','db_iframe_mostratotal','func_mostratotal.php?valores='+valores,'Pesquisa',true,'20','390','400','300');
  }else{
    alert("Selecione algum movimento.");
  }
}
function js_valores(opcao,valortotal,valortipo,descrtipo,codtipo){
  obj = ordem.document.form1;
  if(opcao){
    valortipo = 0;
    valortotal = 0;
    for(i=0;i<obj.length;i++){
      if(obj.elements[i].type=="checkbox" && obj.elements[i].checked==true){
  if(obj.elements[i].name == eval("ordem.document.form1.valor_"+obj.elements[i].name+"_"+codtipo+".name")){
    alert(obj.elements[i].name);
    valortipo += new Number(eval("ordem.document.form1.valor_"+obj.elements[i].name+"_"+codtipo+".value"));
  }
  if(obj.elements[i].type=="text"){
    valortotal += new Number(obj.elements[i].value);
  }
      }
    }
    document.getElementById("descr").innerHTML=descrtipo;
    valortipo  = new Number(valortipo);
    valortotal = new Number(valortotal);
    document.getElementById("valgertot").innerHTML=valortipo.toFixed(2);
    document.getElementById("valtottip").innerHTML=valortotal.toFixed(2);
    document.getElementById('valores').style.visibility='visible';
  }else{
    document.getElementById('valores').style.visibility='hidden';
  }
}
</script>
<form name="form1" method="post" action=""><BR><BR>
<?=db_input('movs',100,'',true,'hidden',1);?>
<?=db_input('nmov',100,'',true,'hidden',1);?>
<center>
  <div align="left" id="valores" style="position:absolute; z-index:1; top:360; left:420; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
      <span id="descr"></span><br>
      Total tipo: <span id="valtottip"></span><br>
      Total geral: <span id="valgertot"></span><br>
  </div>
  <fieldset style="width: 700px">
    <legend><strong>Dados da Geração</strong></legend>
    <table border="0" style="width: 700px;">
      <tr>
        <td nowrap="nowrap">
          <strong>Descrição do Arquivo:</strong>
        </td>
        <td>
          <?php
            db_input('e87_descgera',40,$Ie87_descgera,true,'text',1);
          ?>
        </td>
        <td>
          <strong>Data da Geração:</strong>
        </td>
        <td nowrap="nowrap">
          <?php
            if (!isset($dtin_dia)) {
              $dtin_dia = date('d',db_getsession('DB_datausu'));
            }
            if (!isset($dtin_mes)) {
              $dtin_mes = date('m',db_getsession('DB_datausu'));
            }
            if (!isset($dtin_ano)) {
              $dtin_ano = date('Y',db_getsession('DB_datausu'));
            }
            db_inputdata('dtin',$dtin_dia,$dtin_mes,$dtin_ano,true,'text',1);
          ?>
        </td>
      </tr>
      <tr>
        <td><strong>Banco:</strong></td>
        <td>
          <?php
            $arr_bancos      = array();
            $sSqlBuscaBancos = $cldb_bancos->sql_query_empage(null,
                                                              "distinct db90_codban,
                                                              db90_descr",
                                                              "db90_descr",
                                                              " e90_codmov is null ");
            $result_bancos = $cldb_bancos->sql_record($sSqlBuscaBancos);
            $numrows_bancos = $cldb_bancos->numrows;
            for($i=0;$i<$numrows_bancos;$i++){
              db_fieldsmemory($result_bancos,$i);
              if($i==0 && !isset($db_bancos)){
                $db_bancos = $db90_codban;
              }
              $arr_bancos[$db90_codban] = $db90_descr;
            }

            $qualdescr = "";
            if(isset($db_bancos) && isset($arr_bancos[$db_bancos])){
              $qualdescr = $arr_bancos[$db_bancos];
            }
            db_select("db_bancos", $arr_bancos, true, 1, "onchange=js_pesquisaCNPJBanco();");
          ?>
        </td>
        <td nowrap="nowrap"><strong>Autoriza Pagamento:</strong></td>
        <td>
          <?php
             if (!isset($deposito_dia)) {
               $deposito_dia = date('d',db_getsession('DB_datausu'));
             }
             if (!isset($deposito_mes)) {
               $deposito_mes = date('m',db_getsession('DB_datausu'));
             }
             if (!isset($deposito_ano)) {
               $deposito_ano = date('Y',db_getsession('DB_datausu'));
             }
             db_inputdata('deposito',$deposito_dia,$deposito_mes,$deposito_ano,true,'text',1,"onchange='js_geradescr();'","","","parent.js_geradescr();");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>CNPJ:</strong>
        </td>
        <td>
          <select id="comboboxCNPJ" name="comboboxCNPJ" style="width:100%;">
            <option value="0">Selecione...</option>
          </select>
        </td>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
  </fieldset>
  <p align="center">
    <input  name="btnCarregaMovimentos" type="button" value="Carregar Movimentos" onclick='js_reload()'>
    <input name="atualizar" type="submit"  value="Gerar arquivo TXT" onclick='return js_atualizar(1);'>
    <input name="desatualizar" type="submit" value="Cancelar selecionados" onclick='return js_atualizar(2);'>
    <input name="total" type="button" value="Ver totais" onclick='js_mostravalores();'>
    <input type="button" value="Relatório Arquivos a Gerar" onclick='js_redirecionarRelatorioArquivosGerar();'>
  </p>
  <iframe name="ordem"
          src='emp4_empageconfgera001_ordem.php?db_banco=<?=(@$db_bancos)?>&comboboxCNPJ=<?php echo $_POST['comboboxCNPJ'];?>'
          width="100%"
          height="450px"
          marginwidth="0"
          marginheight="0"
          frameborder="0">
  </iframe>
  <p align="left">
    <span style="color:red;">**</span><b>Conta de outro credor</b>
  </p>
</center>
</form>
<script>

$('db_bancos').style.width = '100%';
function js_reload(){

  if ($F('comboboxCNPJ') == "0") {

    alert("Selecione um CNPJ para carregar os movimentos bancários.");
    return false;
  }
  document.form1.submit();
}
function js_geradescr(){
  data =     document.form1.deposito_dia.value;
  data+= "/"+document.form1.deposito_mes.value;
  data+= "/"+document.form1.deposito_ano.value;
  document.form1.e87_descgera.value = '<?=($qualdescr)?> '+data;
}
js_geradescr();

function js_pesquisaCNPJBanco(sCNPJ) {

  js_divCarregando("Aguarde, carregando os CNPJ do banco...", "msgBox");

  var oParam          = new Object();
  oParam.exec         = "getContasPorCodigoBanco";
  oParam.sCodigoBanco = $F("db_bancos");

  new Ajax.Request("con1_contabancaria.RPC.php",
                  {method: 'post',
                   parameters: 'json='+Object.toJSON(oParam),
                   onComplete: function(oAjax) {
                       js_preencheComboBoxContaBancaria(oAjax, sCNPJ);
                   }
                  });

}

function js_preencheComboBoxContaBancaria(oAjax, sCnpj) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  $('comboboxCNPJ').options.length = 0;
  var oOption = new Option("Selecione...", "0");
  $('comboboxCNPJ').appendChild(oOption);
  oRetorno.aContasBancarias.each(function (oDado, iLinha) {

    var sValorOption = oDado.db83_identificador;
    var sLabelOption = oDado.db83_identificador;
    if (oDado.db83_identificador.trim() == "") {

      sLabelOption = "CNPJ Inexistente";
      sValorOption = oDado.db83_identificador;
    }

    var oOption = new Option(sLabelOption, sValorOption);
    $('comboboxCNPJ').appendChild(oOption);
  });
  $('comboboxCNPJ').value = sCnpj;
}

/**
 * Redireciona para Relatório > Arquivos Agenda > Arquivos à Gerar
 *
 * @access public
 * @return void
 */
function js_redirecionarRelatorioArquivosGerar() {

  js_divCarregando("Aguarde, redirecionando para Arquivos à Gerar...", "msgBox");
  location.href = 'cai2_geratxt001.php';
}

js_pesquisaCNPJBanco('<?php echo isset($_POST["comboboxCNPJ"])?$_POST["comboboxCNPJ"]:0;?>');

</script>