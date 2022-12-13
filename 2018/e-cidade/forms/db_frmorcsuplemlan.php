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

//MODULO: orcamento
$clorcsuplemlan->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o39_codproj");
$clrotulo->label("nome");

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To49_codsup?>">
       <? db_ancora(@$Lo39_codproj,"js_pesquisao39_codproj(true);",$db_opcao);   ?>
    </td>
    <td> 
       <? db_input('o39_codproj',4,'',true,'text',3,'') ?>
       <? db_input('o39_descr',60,'',true,'text',3,'')      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To49_data?>">
       <?=@$Lo49_data?>
    </td>
    <td> 
       <? db_inputdata('o49_data',@$o49_data_dia,@$o49_data_mes,@$o49_data_ano,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>


  <td nowrap><b> Processar Projeto </b></td>
    <td> 
     <input type='checkbox' name='processa_projeto' id='processa_projeto'>
       <i> * Processa todas as suplementações e fecha o projeto </i>
     <br>
    </td>
  </tr>
  
  <!--  -->
  <tr>
   <td colspan=2 align=center>
    <fieldset>
      <legend><b>Suplementações do Projeto</b></legend>
      <div id='ctnDataGridSuplementacoes'>
      </div>
    </fieldset>
   </td>
   </tr>
  <!--  -->
 
   </table>
  </center>

  <? if (isset($o39_codproj) && $o39_codproj !=""){  ?>
   <input type="button" value="Processar" onclick="js_processarSuplementacao();">
   <input type=hidden name="Processar" value="Processar">
 <?  }  ?>

 </form>
<script>


function js_processa(){   
  obj = document.form1;
  if (obj.o49_data_dia.value!='' && obj.o49_data_mes.value!='' && obj.o49_data_ano.value!='' ){
      js_gera_chaves();
      document.form1.submit();
  } else { 
      alert('Preencha a Data !');
  }    

}  
function js_pesquisao39_codproj(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto_np.php?funcao_js=parent.js_mostraprojeto|o39_codproj','Pesquisa',true);
  }
}
function js_mostraprojeto(chave,erro){
   <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave; ";
   ?>
   db_iframe_orcprojeto.hide();
}
function js_initGrid() {

  oGridSuplementacoes = new DBGrid('gridSuplementacoes');
  oGridSuplementacoes.nameInstance = 'oGridSuplementacoes';
  oGridSuplementacoes.setCheckbox(0);
  oGridSuplementacoes.setCellAlign(new Array('right', 'right', 'Left', 'right', 'right'));
  oGridSuplementacoes.setHeader(new Array('Codigo', 'Tipo', 'Descricao', 'Suplementado', 'Reduzido'));
  oGridSuplementacoes.show($('ctnDataGridSuplementacoes'));
  if ($F('o39_codproj') != "") {
    js_getSuplementacoesProjeto();
  }
}
function js_getSuplementacoesProjeto() {
  
  var oParam         = new Object();
  oParam.iProjeto    = $F('o39_codproj');
  oParam.exec        = 'getSuplementacoesProjeto';
  js_divCarregando('Aguarde, pesquisando dados...', 'msgBox');
  var oAjax          = new Ajax.Request(
                                        'orc4_suplementacoes.RPC.php',
                                        {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParam),
                                        onComplete:js_retornoGetSuplementacoesProjeto
                                        } 
                                       );
}

function js_retornoGetSuplementacoesProjeto(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+")");
  oGridSuplementacoes.clearAll(true);
  for (var i = 0; i < oRetorno.itens.length; i++) {
    
    with (oRetorno.itens[i]) {
      
      var aLinha = new Array();  
      aLinha[0]  = codigo;
      aLinha[1]  = tipo;
      aLinha[2]  = descricaotipo.urlDecode();
      aLinha[3]  = js_formatar(valorsuplementado, 'f');
      aLinha[4]  = js_formatar(valorreduzido, 'f');
      oGridSuplementacoes.addRow(aLinha);
    }
  }
  oGridSuplementacoes.renderRows();
}

function js_processarSuplementacao() {

  var oParam               = new Object();
  oParam.iProjeto          = $F('o39_codproj');
  oParam.exec              = 'processarSuplementacoes';
  oParam.dataprocessamento = $F('o49_data');
  oParam.lFecharProcesso   = $('processa_projeto').checked;
  if (oParam.lFecharProcesso) {
  
    if (!confirm('Confirmar o processamento de todas as suplementações do Decreto?')) {
      return false;
    } 
    oGridSuplementacoes.selectAll('mtodositensgridSuplementacoes',
                                  'checkboxgridSuplementacoes',
                                  'gridSuplementacoesrowgridSuplementacoes'
                                  );
  }
  oParam.aSuplementacoes   = new Array();
  var aSuplementacoes      = oGridSuplementacoes.getSelection('object');
  if (aSuplementacoes.length == 0) {
  
    alert('nenhuma Suplementação foi Selecionada!');
    return false;
  }
  
  aSuplementacoes.each(function(oSup, id) {
    oParam.aSuplementacoes.push(oSup.aCells[0].getValue());
  });
  js_divCarregando('Aguarde, pesquisando dados...', 'msgBox');
  var oAjax   = new Ajax.Request(
                                 'orc4_suplementacoes.RPC.php',
                                        {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParam),
                                        onComplete:js_retornoProcessarSuplementacao
                                        } 
                                       );
}

function js_retornoProcessarSuplementacao(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    alert('Suplementação(ões) processadas com sucesso.');
    js_getSuplementacoesProjeto();
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
} 
js_initGrid();
</script>