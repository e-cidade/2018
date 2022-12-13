<?php
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
$clrotulo->label("e82_codord");
$clrotulo->label("e42_sequencial");
$clrotulo->label("e87_descgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("k17_codigo");  
$db_opcao = 1;
?>
<form name="form1" method="post" action="">
<center>
<table width="90%">
<tr>
<td>
<fieldset>
<legend><b>Filtros</b>
</legend>
<table>
  <tr>
    <td valign="top">
      <fieldset>
        <legend>
            <b>Ordem Pagamento</b>
        </legend>
        <table>
          <tr>
           <td nowrap title="<?=@$Te82_codord?>" align='left'>
           <? db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
           </td>
          <td nowrap colspan="1"> 
           <? db_input('e82_codord',15,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'")  ?>
          <td>
           <? db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",$db_opcao);  ?>
          </td>
          <td>
          <? db_input('e82_codord2',15,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord02(false);'","e82_codord02")?>
         </td>
       </tr>
       <tr>
         <td align="left" nowrap title="<?=$Te60_numemp?>">
          <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
          </td>
         <td nowrap colspan="1"> 
          <input name="e60_codemp" id='e60_codemp' title='<?=$Te60_codemp?>' size="15" type='text'  onKeyPress="return js_mascara(event);" >
        </td>
      </tr>
      <tr>
            <td><input type='button' style="visibility: hidden"></td>
          </tr>
      </table>
      </fieldset>
    </td>
    <td valign="top">
      <fieldset>
        <legend>
            <b>Slip</b>
        </legend>
        <table>
          <tr>
            <td align="right" nowrap title="<?=$Tk17_codigo?>">
             <? db_ancora(@$Lk17_codigo,"js_pesquisak17_codigo(true);",$db_opcao);  ?>
             </td>
             <td nowrap> 
              <input name="k17_codigo" id='k17_codigo' title='<?=$Tk17_codigo?>' size="15" type='text'>
             </td>
             <td align="right" nowrap title="<?=$Tk17_codigo?>">
              <? db_ancora("<b>Até</b>","js_pesquisak17_codigo2(true);",$db_opcao);  ?>
              </td>
              <td nowrap> 
                <input name="k17_codigo2" id='k17_codigo2' title='<?=$Tk17_codigo?>' size="15" type='text'>
             </td>
          </tr>
          <tr>
            <td><input type='button' style="visibility: hidden"></td>
          </tr>
          <tr>
            <td><input type='button' style="visibility: hidden"></td>
          </tr>
        </table>
    </td>
    <td valign="top">
      <fieldset>
        <legend>
            <b>Dados Da OP</b>
        </legend>
        <table>
        <tr>
          <td>
            <b>
              <? db_ancora("<b>OP auxiliar:</b>","js_pesquisae42_sequencial(true);", 3);  ?>
              </b>
             </td>
             <td colspan="5">
             <?
              db_input("e42_sequencial", 10, $Ie42_sequencial, true,"text", 3);
             ?> 
             </td>
         </tr>
         <tr>
           <td>
             <b>Data de Pagamento: </b>
           </td>
           <td colspan='2'>
            <?
            db_inputdata("e42_dtpagamento",$e42_data[2], $e42_data[1], $e42_data[0],true,"text", 1);
            ?>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input id="alterardata" type="button"  value="Alterar Data"   onclick='return js_alterarData();'>
            </td>
          </tr>
        </table>
    </td>
  </tr>
<tr>
<td colspan="2">
 <fieldset>
 <legend>
   <b>Gerais</b>
 </legend>
 <table style="width: 100%">
  <tr>
    <td style='text-align:left' >
      <b>Data Inicial:</b>
    </td>
    <td nowrap colspan="1">
      <?
       db_inputdata("dataordeminicial",null,null,null,true,"text", 1);
      ?>
      &nbsp;<b>Data Final:</b>
      <?
       db_inputdata("dataordemfinal",null,null,null,true,"text", 1);
      ?>
    </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numcgm?>" align='left'>
    <?
  db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
  ?>        
  </td>
  <td  colspan='7' nowrap="nowrap"> 
  <?
   db_input('z01_numcgm',15,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
   db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
  ?>
  </td>
  <tr nowrap>
    <td nowrap title="<?=@$To15_codigo?>"><? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",$db_opcao); ?>
    </td>
    <td colspan=7 nowrap>
     <? db_input('o15_codigo',15,$Io15_codigo,true,'text',$db_opcao," onchange='js_pesquisac62_codrec(false);'") ?>
     <? db_input('o15_descr',40,$Io15_descr,true,'text',3,'')   ?>
    </td>
  </tr>
  </tr>
  <tr>
     <td nowrap="nowrap">
       <b>Retornar:</b>
      </td>
      <td> 
       <?
         $retornar = 3;
         $aTipos = array(
                         1 => "Autorizados",
                         2 => "Não Autorizados",
                         3 => "Todas"
                        );
        db_select("retornar", $aTipos, true, 1);                
       ?>
     </td>
    </tr>
    </table>
   </fieldset>
   </td>
 </tr>   
</table>
</fieldset>
  </td>
  </tr>
  <tr>
    <td align='center' colspan="5" >
      <input id="pesquisar"   type="button"  value="Pesquisar OP"   onclick='return js_pesquisarOrdens(1);'>
      <input id="pesquisar"   type="button"  value="Pesquisar Slip" onclick='return js_pesquisarOrdens(2);'>
      <input id="agendar"     type="button"  value="Autorizar"      onclick='return js_geraOrdem();'>
      <input id="desagendar"  type="button"  value="Desautorizar"   onclick='return js_desautorizaOrdem();'>
      <input id="imprimir"    type="button"  value="Imprimir OP"    onclick='js_emitir()'>
    </td>
  </tr>
  <tr>
  <td colspan='5'> 
  <table style='width: 100%'>
    <tr>
      <td>
        <fieldset >
          <legend><b>Notas de Liquidação</b></legend>
          <div id='gridAutoriza'>
          </div>
        </fieldset>
      </td>
    </tr>
</table>
</center>
</form>  
<div id='callback'>
</div>
<script type="text/javascript">
sDataDia = "<?=date("d/m/Y",db_getsession("DB_datausu"))?>";
function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_pagordem',
                        'func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord',
                        'Pesquisa Ordens de Pagamento',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione uma ordem menor que a segunda!");
      document.form1.e82_codord.focus(); 
      document.form1.e82_codord.value = ''; 
    }
  }
}
function js_mostrapagordem1(chave1){
  document.form1.e82_codord.value = chave1;
  db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae82_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_pagordem',
                        'func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord',
                        'Pesquisa Ordens de Pagamento',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione uma ordem maior que a primeira");
      document.form1.e82_codord02.focus(); 
      document.form1.e82_codord02.value = ''; 
    }
  }
}
function js_mostrapagordem102(chave1,chave2){
  document.form1.e82_codord02.value = chave1;
  db_iframe_pagordem.hide();
}
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_empempenho',
                        'func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp',
                        'Pesquisa de Empenhos',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25);
  }else{
   // js_OpenJanelaIframe('','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho.hide();
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'func_nome',
                        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisa CGM',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25
                       );
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('',
                            'func_nome',
                            'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+
                            '&funcao_js=parent.js_mostracgm',
                            'Pesquisa',false,
                            22,
                            0,
                            document.width-12,
                            document.body.scrollHeight-25
                            );
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
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}

function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('',
                           'db_iframe_orctiporec', 
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                           'Pesquisar Recursos',
                           true,
                           22,
                           0,
                           document.width-12,
                           document.body.scrollHeight-25
                           );
   }else{
       if(document.form1.o15_codigo.value != ''){ 
           js_OpenJanelaIframe('',
                               'db_iframe_orctiporec',
                               'func_orctiporec.php?pesquisa_chave='+
                               document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec',
                               'Pesquisa',
                               false,
                               22,
                               0,
                               document.width-12,
                               document.body.scrollHeight-25
                             );
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

function js_pesquisae42_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'func_nome', 
                        'func_empageordem.php?funcao_js=parent.js_mostraordem1|'+
                        'e42_sequencial|e42_dtpagamento',
                        'Pesquisa OP Auxiliar',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25
                        );
  } else {
    if ($F('e42_sequencial') != "") {
      js_OpenJanelaIframe('',
                          'func_nome',
                          'func_empageordem.php?pesquisa_chave='+
                          $F('e42_sequencial')+'&funcao_js=parent.js_mostraordemagenda',
                         'Pesquisa',
                         false,
                         22,
                         0,
                         document.width-12,
                         document.body.scrollHeight-25);
    } else {
      $('e42_sequencial').value = '';
    }   
  }
}

function js_mostraordem1(chave1,chave2){
  
  document.form1.e42_sequencial.value = chave1;
  document.form1.e42_dtpagamento.value = js_formatar(chave2,"d");
  func_nome.hide();
  
}

function js_mostraordemagenda(chave,erro){
  
  if(!erro) { 
    document.form1.e42_dtpagamento.value = chave;
  } else {
  
    document.form1.e42_sequencial.value  = ''; 
    document.form1.e42_dtpagamento.value = '';
    
  }
} 

function js_pesquisak17_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_slip',
                        'func_slip.php?funcao_js=parent.js_mostraslip1|k17_codigo',
                        'Pesquisa de Slips',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25);
  }
}
function js_mostraslip1(chave1){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}

function js_pesquisak17_codigo2(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_slip',
                        'func_slip.php?funcao_js=parent.js_mostraslip2|k17_codigo',
                        'Pesquisa de Slips',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-25);
  }
}
function js_mostraslip1(chave1){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}

function js_mostraslip2(chave){
  document.form1.k17_codigo2.value = chave;
  db_iframe_slip.hide();
}


sUrl = "emp4_ordemPagamentoRPC.php";
function js_pesquisarOrdens(iTipo) {

  js_divCarregando("Aguarde, pesquisando ordens.","msgBox");
  js_liberaBotoes(false);
  if (iTipo != null) {
    iTipoOperacao              = iTipo;
  } 
  var oParametros            = new Object();
  oParametros.iOrdemIni      = $F('e82_codord');
  oParametros.iOrdemFim      = $F('e82_codord02');
  oParametros.iCodEmp        = $F('e60_codemp');
  oParametros.dtDataIni      = $F('dataordeminicial');
  oParametros.dtDataFim      = $F('dataordemfinal');
  oParametros.iSlipInicial   = $F('k17_codigo');
  oParametros.iSlipFim       = $F('k17_codigo2');
  oParametros.iNumCgm        = $F('z01_numcgm');
  oParametros.sDtAut         = $F('e42_dtpagamento');
  oParametros.iRecurso       = $F('o15_codigo');
  oParametros.iOPauxiliar    = $F('e42_sequencial');
  oParametros.exec           = "consultarNotas";
  oParametros.iTipoConsulta  = iTipoOperacao;
  oParametros.iTipoRetorno   = $F('retornar');
  var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(oParametros) , 
                            onComplete: js_retornoPesquisaOrdens
                            }
                          );
}

function js_retornoPesquisaOrdens(oAjax) {

  js_removeObj("msgBox");
  js_liberaBotoes(true);
  gridAutoriza.clearAll(true);
  var oResponse = eval("("+oAjax.responseText+")");
  for (var iNotas = 0; iNotas < oResponse.itens.length; iNotas++) {
     
     with (oResponse.itens[iNotas]) {
        
     
        var iValMovimento = new Number(new Number(e81_valor));
        
        var aLinha = new Array();
        aLinha[0]  = e81_codmov;
        aLinha[1]  = e42_sequencial;
        aLinha[2]  = e50_codord;
        if (e60_codemp != 0) {
          
          aLinha[3]  = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
          aLinha[3] += e60_codemp+"/"+e60_anousu+"</a>";
            
        } else {
          aLinha[3] = "<a onclick='js_pesquisaSlip("+e50_codord+");return false;' href='#'>Slip<\a>";
        }
        aLinha[4]   = o15_codigo; 
        aLinha[5]   = "<div style='overflow:hidden'>"+z01_nome.urlDecode()+"</div>";
        aLinha[6]   = js_formatar(e50_data,"d");
        aLinha[7]   = e53_valor;
        aLinha[8]   = " "+iValMovimento;
        aLinha[9]   = valorretencao,
        aLinha[10]  = "<input type  = 'text' id='valorrow"+iNotas+"' style='width:100%;text-align:right;height:100%;border:1px inset'";
        aLinha[10] += " class='valores' onchange='js_calculaValor(this,"+iValMovimento+")'";
        aLinha[10] += "                 onkeypress='return js_teclas(event)'"; 
        if (new Number(valorretencao) > 0 || e60_codemp == 0) {
          aLinha[10] += " readonly "; 
        }
          
        aLinha[10] += "       value = '"+iValMovimento+"' id='valor"+e50_codord+"'>";
        gridAutoriza.addRow(aLinha);
        if (e42_sequencial != ""  && e42_sequencial == $F('e42_sequencial')) {
          gridAutoriza.aRows[iNotas].setClassName("naOPAuxiliar");
        } else if (e42_sequencial != "") {
          gridAutoriza.aRows[iNotas].setClassName("configurada");
        }
        gridAutoriza.aRows[gridAutoriza.iRowCount].sValue  = e81_codmov;
        
     }
     
   }
   gridAutoriza.renderRows();
}

function js_init() {

   
   gridAutoriza              = new DBGrid("gridAutoriza");
   gridAutoriza.nameInstance   = "gridAutoriza";
   gridAutoriza.allowSelectColumns(true);
   gridAutoriza.setCheckbox(0);
   gridAutoriza.setHeight(200);
   gridAutoriza.setCellAlign(new Array("right", "Right", "right","center", "right", 
                                       "left", "Left", "right", "right", "right","right"));
   gridAutoriza.setHeader(new Array("Movimento",
                                    "OP Aux.",
                                    "OP/Slip",
                                    "Empenho",
                                    "Recurso",
                                    "Nome",
                                    "Emissão",
                                    "Valor",
                                    "Valor Mov",
                                    "Retenção",
                                    "Vlr Autorizado"));
   gridAutoriza.aHeaders[1].lDisplayed = false;
   gridAutoriza.show(document.getElementById('gridAutoriza'));
}

function js_geraOrdem() {
    
   var aNotasSelecionadas = gridAutoriza.getSelection();
   if (aNotasSelecionadas.length == 0) {
      
      alert('Selecione ao menos uma nota!\nProcessamento cancelado');
      return false;
   }
   if ($F('e42_dtpagamento')  == "") {
    
      alert('Preencha a data de pagamento!\nProcessamento cancelado');
      return false;
   
   }
   var sTipoOperacao = '';
   if (iTipoOperacao == 1) {
     sTipoOperacao = 'As Ordem de Pagamento ';
   } else {
     sTipoOperacao = 'Os Slips ';
   }
   
   var sMsgConfirmacao = sTipoOperacao+' serão Autorizados ';
   if ($F('e42_sequencial') != "") {
     sMsgConfirmacao += "na OP auxiliar nº "+ $F('e42_sequencial')+".\n"; 
   } else {
      sMsgConfirmacao += "em uma nova OP auxiliar.\n";
   }
   
   sMsgConfirmacao += "Confirma a Operação?";
   if (!confirm(sMsgConfirmacao)) {
     return false;
   }
   js_liberaBotoes(false); 
   var oParams            = new Object();
   oParams.exec           = "lancarOrdem";
   oParams.e42_dtpaga     = $F('e42_dtpagamento'); 
   oParams.e42_sequencial = $F('e42_sequencial'); 
   oParams.iTipoOperacao  = iTipoOperacao; 
   var aNotasEnviar = new Array();
   for (var i = 0; i < aNotasSelecionadas.length; i++) {
     
     var oNota      = new Object();
     oNota.nValor   = aNotasSelecionadas[i][11]; 
     oNota.iCodMov  = aNotasSelecionadas[i][0];
     oNota.iCodNota = aNotasSelecionadas[i][3];
     aNotasEnviar.push(oNota); 
     
   }
   oParams.aNotas        = aNotasEnviar;
   var sJsonPars         = oParams.toSource();
   sJsonPars             = sJsonPars.replace("(","");
   sJsonPars             = sJsonPars.replace(")","");
   js_divCarregando("Aguarde, processando.","msgBox");
   var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+sJsonPars , 
                            onComplete: js_retornoGeraOrdem
                            }
                          );
}

function js_retornoGeraOrdem(oAjax) {
  
  js_removeObj("msgBox");
  js_liberaBotoes(true);
  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno == '2') {
    
    alert(oRetorno.message.urlDecode());
     
  } else {
  
    alert("Operação afetuada com sucesso");
   // js_emitir(oRetorno.dtAutoriza,oRetorno.iCodAgenda);
    js_pesquisarOrdens();
    
  } 
}
function js_desautorizaOrdem() {
    
   var aNotasSelecionadas = gridAutoriza.getSelection();
   if (aNotasSelecionadas.length == 0) {
      
      alert('Selecione ao menos uma nota!\nProcessamento cancelado');
      return false;
   }
   
   var sTipoOperacao = '';
   if (iTipoOperacao == 1) {
     sTipoOperacao = 'As Ordem de Pagamento ';
   } else {
     sTipoOperacao = 'Os Slips ';
   }
   
   var sMsgConfirmacao = sTipoOperacao+' serão Retiradadas da ';
   if ($F('e42_sequencial') != "") {
     sMsgConfirmacao += "na OP auxiliar nº "+ $F('e42_sequencial')+".\n"; 
   } else {
   
      alert('Nenhuma OP Auxiliar Selecionada.');
      return false;
   }
   
   sMsgConfirmacao += "Confirma a Operação?";
   if (!confirm(sMsgConfirmacao)) {
     return false;
   }
   js_liberaBotoes(false); 
   var oParams            = new Object();
   oParams.exec           = "cancelarOrdem";
   oParams.e42_dtpaga     = $F('e42_dtpagamento'); 
   oParams.e42_sequencial = $F('e42_sequencial'); 
   oParams.iTipoOperacao  = iTipoOperacao; 
   var aNotasEnviar = new Array();
   for (var i = 0; i < aNotasSelecionadas.length; i++) {
     
     var oNota      = new Object();
     oNota.nValor   = aNotasSelecionadas[i][11]; 
     oNota.iCodMov  = aNotasSelecionadas[i][0];
     oNota.iCodNota = aNotasSelecionadas[i][3];
     aNotasEnviar.push(oNota); 
     
   }
   oParams.aNotas        = aNotasEnviar;
   var sJsonPars         = oParams.toSource();
   sJsonPars             = sJsonPars.replace("(","");
   sJsonPars             = sJsonPars.replace(")","");
   js_divCarregando("Aguarde, Retirando Movimentos da Autorização.","msgBox");
   var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+sJsonPars , 
                            onComplete: js_retornoGeraOrdem
                            }
                          );
}

function js_liberaBotoes(lLiberar) {

  if (lLiberar) {
  
    $('pesquisar').disabled = false;
    $('agendar').disabled   = false;
  
  } else {

    $('pesquisar').disabled = true;
    $('agendar').disabled   = true;
      
  }
}

function js_calculaValor(oTextObj,iValTot) {
  
  if (oTextObj.value > iValTot || oTextObj.value == 0) {
     oTextObj.value  = iValTot;
  }
}

function js_emitir() {
  
  if ($F('e42_sequencial') == "") {
    
    alert('Informe a OP Auxiliar!');
    return false;
    
  }
  var iAgenda = $F('e42_sequencial');
  window.open('emp2_ordempagamentoauxiliar002.php?iAgenda='+iAgenda,'','location=0');
}

function js_pesquisaSlip(iCodigoSlip) {
  js_OpenJanelaIframe('top.corpo','db_iframe_slip2',
                       'cai3_conslip003.php?slip='+iCodigoSlip,'Consulta Lançamento',
                       true,
                       22,
                       0,
                       document.width-12,
                       document.body.scrollHeight-25
                       );
}
function js_alterarData() {
  
   if ($F('e42_dtpagamento') == "" ) {
     alert('Informe a data para o pagamento');
   }
   var oParam             = new Object();
   oParam.exec            = "alterarData"; 
   oParam.e42_dtpagamento = $F('e42_dtpagamento'); 
   oParam.e42_sequencial  = $F('e42_sequencial'); 
   var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(oParam) , 
                            onComplete: function (oRequest) {
                            
                                var oRetorno = eval("("+oRequest.responseText+")");
                                if (oRetorno.status == 1) {
                                  alert('Data alterada com sucesso');
                                }
                                }
                           }  
                          );
   
}
parent.$('CFdb_iframe_op').cells[0].innerHTML = "Manutenção de OP nº "+$F('e42_sequencial');
js_init();
</script>