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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo;

$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label("c61_reduz");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load('prototype.js, 
              strings.js, 
              scripts.js, 
              windowAux.widget.js, 
              dbmessageBoard.widget.js, 
              datagrid.widget.js, 
              DBAbas.widget.js, 
              DBAbasItem.widget.js');

db_app::load("estilos.css, grid.style.css, DBtab.style.css");
?>

<style type="text/css">
  #ctnDesdobramentos td {
    padding:0 4px;
  }
</style>
<script>
function js_emite(){
  //js_controlarodape(true);
  qry  = 'ano_base='+ document.form1.ano_base.value;
  qry += '&oriret='+ document.form1.oriret.value;
  qry += '&codret='+ document.form1.codret.value;
  qry += '&nomeresp=' + document.form1.nomeresp.value;
  qry += '&cpfresp=' + document.form1.cpfresp.value;
  qry += '&dddresp=' + document.form1.dddresp.value;
  qry += '&foneresp=' + document.form1.foneresp.value;
  qry += '&r70_numcgm=' + document.form1.r70_numcgm.value;
  if(document.form1.pref_fun){
    qry += '&pref_fun=' + document.form1.pref_fun.value;
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_geradirf','pes4_geradirf002.php?'+qry,'Gerando Arquivo',true);
}

function js_erro(msg){
  //js_controlarodape(false);
  (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_geradirf.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_geradirf.hide();
}
function js_controlarodape(mostra){
  if(mostra == true){
    document.form1.rodape.value = (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML;
    (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}

function js_detectaarquivo(arquivo,pdf){
//  js_controlarodape(false);
  (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_geradirf.hide();
  listagem = arquivo+"#Download Arquivo TXT |";
  listagem+= pdf+"#Download Relatório";
  js_montarlista(listagem,"form1");
}

</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1"> 

<div id="ctnAbas" style="margin-top:20"></div> 


  <div id="ctnProcessamento">

    <center>
      <form name="form1" id='frmProcessaDirf' method="post" action="" >
        <table border='0'>
         <tr>
           <td>
             <fieldset><legend><b>Processamento da DIRF</b></legend> 
              <table>
          <tr>
            <td align="right" nowrap title="Digite o Ano Base" >
              <strong>Ano Base:&nbsp;&nbsp;</strong>
            </td>
            <td align="left">
              <?php
              $sqlanomes = "select max(r11_anousu||lpad(r11_mesusu,2,0)) from cfpess";
              $resultanomes = db_query($sqlanomes);
              db_fieldsmemory($resultanomes,0);
              $ano_base = substr($max,0,4)-1;
                db_input('ano_base',4, 1,true,'text',2,'', null, null, null, 4);
              ?>
            </td>
            </tr>
          </tr>
          <tr>
            <td nowrap title="">
              <b>Buscar Pagamentos Efetuados na Contabilidade:&nbsp;&nbsp;</b>
            </td>
            <td nowrap>
              <?php
                  $arr = array('s' => 'Sim','n'=>'Não');
                db_select("dadosfinanceiros",$arr,true,1,'onchange="js_desabilitaAba(this.value)"');
                  ?>
            </td>
          </tr>
         </table>
         </fieldset>
         </td>
         </tr>
         </tr> 
           <td colspan="2">
          <fieldset>
            <legend><b>CNPJ</b></legend>
            <table>
              <tr>
                <td nowrap align="right" title="CNPJ">
                  <b>CNPJ:</b>
                </td>
                <td>
                  <?php
                $instit = db_getsession("DB_instit");
                $sSqlUnidades  = "select distinct  o41_cnpj, ";
                $sSqlUnidades .= "       case when o41_cnpj = cgc then nomeinst else o41_descr end as nome_fundo ";
                $sSqlUnidades .= "  from orcunidade  ";
                $sSqlUnidades .= "       inner join orcorgao  on o41_orgao  = o40_orgao ";
                $sSqlUnidades .= "                           and o40_anousu = o41_anousu ";
                $sSqlUnidades .= "       inner join db_config on codigo     = o41_instit ";
                $sSqlUnidades .= " where o41_instit = {$instit} ";
                $sSqlUnidades .= "   and o41_anousu = ".db_getsession("DB_anousu");
                $result = db_query($sSqlUnidades);
                db_selectrecord("cnpj", $result, true     , @$db_opcao, "",           "",          "",       "", "","2");

                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
          <tr>
             <td colspan="2" align = "center"> 
              <input  name="gera" id="gera" type="button" value="Gerar" onclick="js_processarDirf();" >
              <input  name="btnCnpjUnidade" id="btnCnpjUnidade" type="button" value="Inconsistências CNPJ" 
                      onclick="js_verificaPendencias()">
              <?php if(db_getsession("DB_id_usuario") == 1 ): ?> <input  name="geraDebug" id="geraDebug" type="button" value="Gerar com Debug" onclick="js_processarDirf(true);" > <?php endif; ?>
            </td>
          </tr>
         </table> 
      </form>

    </center>

  </div>

  <div id="ctnFinanceiro">

    <fieldset style="margin:0 auto; width:700px;">
      <legend><strong>Desdobramentos:</strong></legend>
      <table style=" width: 100%">
        <tr>
          <td>
            <?php
            db_ancora("$Lc61_reduz", 'js_pesquisaConta(true);', 1); 
            db_input('c61_reduz',  8, "", true, 'text', 1, "onchange='js_pesquisaConta(false);'"); 
            db_input('c60_descr', 40, "", true, 'text', 3); 
            ?>
          </td>
          <td align="right">
            <input type="button" id="btnAdicionarConta" value="Adicionar" onclick="Desdobramentos.adicionar();" />
            <input type="button" id="btnRemoverConta" value="Remover" onClick="Desdobramentos.remover();" />
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="ctnDesdobramentos"></div>
          </td>
        </tr>
      </table>         
    </fieldset>

  </div>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

/**
* Grid com os desdobramentos
*/
oDesdobramentos              = new DBGrid('datagridConta');
oDesdobramentos.sName        = 'datagridConta';
oDesdobramentos.nameInstance = 'oDesdobramentos';
oDesdobramentos.setCellWidth( ['20%', '80%'] );
oDesdobramentos.setCellAlign( ['left', 'left'] );
oDesdobramentos.setCheckbox(0);
oDesdobramentos.setHeader( ['Reduzido', 'Descrição'] );
oDesdobramentos.show( $('ctnDesdobramentos') );
oDesdobramentos.clearAll(true);

/**
 * Bloque os botoes de adicionar e libera os botoes de remover 
 */
js_bloquearBotao('btnAdicionarConta', true);
js_bloquearBotao('btnRemoverConta', false);


/**
 * Cria 2 abas, Processamento e Dados Financeiros
 */
var oDBAba            = new DBAbas( $('ctnAbas') );
var oAbaProcessamento = oDBAba.adicionarAba("Processamento", $('ctnProcessamento') );
var oAbaFinanceiro    = oDBAba.adicionarAba("Dados Financeiros", $('ctnFinanceiro'));

/**
 * Singleton para adicionar/remover itens da grid das contas 
 */
Desdobramentos = {

  /**
   * Array com as contas 
   */
  aDesdobramentos: new Array(),

  /**
   * Retorna array com os desdobramentos
   * @return array - reduzidos
   */
  getDesdobramentos: function() {

    var aDesdobramentos = new Array();

    Desdobramentos.aDesdobramentos.each(function(oDesdobramento, iIndice) {
      aDesdobramentos.push(oDesdobramento.iReduzido);
    });

    return aDesdobramentos;
  },

  /**
   * Adicionar conta
   * Adiciona ao array aDesdobramentos objeto com reduzido e descricao da conta
   * Não deixa incluir itens duplicados
   * Apos incluir renderiza grid novamente, funcao montaGrid()
   */
  adicionar: function() {

    var oConta = new Object();

    oConta.iReduzido  = new Number($('c61_reduz').value);
    oConta.sDescricao = $('c60_descr').value;

    /**
     * Nao deixa incluir reduzidos repeditos
     */
    if ( Desdobramentos.aDesdobramentos[oConta.iReduzido] ) {

      alert('Conta já adicionada.');
      return;
    }

    /**
     * desbloquea botao Adicionar  
     */
    js_bloquearBotao('btnAdicionarConta', true);

    $('c61_reduz').focus(); 
    $('c61_reduz').value = ''; 
    $('c60_descr').value = ''; 

    /**
     * Adiciona conta ao array e renderiza grid novamente
     */
    Desdobramentos.aDesdobramentos[oConta.iReduzido] = oConta;
    Desdobramentos.montaGrid();
  },

  /**
   * Remove conta e renderiza novamente grid, funcao montaGrid() 
   */
  remover: function() {

    var aSelecionados  = oDesdobramentos.getSelection();
    var iSelecionados  = aSelecionados.length;
    var aLinhasRemover = new Array();

    aSelecionados.each(function(aLinha, iIndiceRemover) {

      var iReduzido = new Number(aLinha[0]);
      delete(Desdobramentos.aDesdobramentos[iReduzido]);
    });

    Desdobramentos.montaGrid();
  },

  /**
   * Pescorre array com as contas e adiciona a grid 
   */
  montaGrid: function() {

    oDesdobramentos.clearAll(true);

    var iConta = Desdobramentos.aDesdobramentos.length;

    if ( iConta == 0 ) {
      return;
    }

    Desdobramentos.aDesdobramentos.each(function(oLinha, iIndice) {

      var aLinha = new Array();

      aLinha[0] = oLinha.iReduzido;
      aLinha[1] = oLinha.sDescricao;

      oDesdobramentos.addRow(aLinha);
    });

    oDesdobramentos.renderRows();  
  }

}

/**
 * Pesquca conta  
 * 
 * @param bool lMostra - true mostra tela de pesquisa/ false pesquisa pelo input
 */
function js_pesquisaConta(lMostra) {

  var sFuncao = 'func_conplanoreduz.php?funcao_js=parent.';
  js_bloquearBotao('btnAdicionarConta', true);

  if ( lMostra ) {
    sFuncao += 'js_pesquisaContaAncora|c61_reduz|c60_descr';
  } else {

   if ( $F('c61_reduz') == '' ) {
   
     js_bloquearBotao('btnAdicionarConta', true);
     $('c60_descr').value = ''; 
     return false;
   }

    sFuncao += 'js_pesquisaContaInput&pesquisa_chave=' + $F('c61_reduz');
  }

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_conplanoreduz', sFuncao, 'Pesquisa', lMostra);
}

/**
 * Retorno da pesquisa da conta pela ancora  
 * 
 * @param integer iReduzido  - codigo do reduzido
 * @param string  sDescricao - descricao da conta
 */
function js_pesquisaContaAncora(iReduzido, sDescricao) {

  $('c61_reduz').value  = iReduzido;
  $('c60_descr').value  = sDescricao;

  db_iframe_conplanoreduz.hide();
  js_bloquearBotao('btnAdicionarConta', false);
}

/**
 * Retorno da pesquisa da conta pelo change do input 
 * 
 * @param string sDescricao - descricao da conta
 * @param bool   lErro      - true caso nao encontre conta 
 */
function js_pesquisaContaInput(sDescricao, lErro) {

  if ( lErro ) {

    js_bloquearBotao('btnAdicionarConta', true);
    $('c61_reduz').value = '';
  }

  $('c60_descr').value = sDescricao;

  if ( !lErro ) {
    js_bloquearBotao('btnAdicionarConta', false);
  }
}

/**
 * Funcao para bloquear botao 
 * 
 * @param string sId       - id do botao
 * @param bool   lBloquear - bloquear ou desbloquerar botao
 */
function js_bloquearBotao(sId, lBloquear) {

  if ( lBloquear ) {
  
    $(sId).setAttribute('disabled', 'true');
    return;
  }

  $(sId).removeAttribute('disabled');
}

function js_processarDirf(debug) {


  if ($F('ano_base') == "") {
 
    alert('Informe o ano base!');
    return false;
  }


  $('frmProcessaDirf').disable();

  js_divCarregando('Aguarde, verificando configurações...', 'msgBox');

  var oParam = {
     exec: 'validarBasesRRA'
  }

  oAjax = new Ajax.Request(
    'pes4_processardirf.RPC.php',
    {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: function (oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.avisarfaltabases) {

          if (!confirm(oRetorno.message.urlDecode())) {
            $('frmProcessaDirf').enable();
            return;
          }
        }

        var oParam = new Object();

        if (debug) {
          oParam.lDebug = true;
        }

        oParam.iAno = $F('ano_base');
        oParam.sCnpj = $F('cnpj');
        oParam.lProcessaEmpenho = $F('dadosfinanceiros') == 's' ? true : false;
        oParam.aDesdobramentos = Desdobramentos.getDesdobramentos();
        oParam.exec = 'processarDirf';

        js_divCarregando('Aguarde, processando Dados para a Dirf', 'msgBox');

        oAjax = new Ajax.Request(
          'pes4_processardirf.RPC.php',
          {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoProcessaDirf
          }
        );
      }
    }
  );

}

function js_retornoProcessaDirf(oAjax) {
  
  $('frmProcessaDirf').enable();
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 1) {
  
    alert('Processamento efetuado com sucesso.');

    if(oRetorno.lDebug) {

      var divLinkLog     = document.createElement('div');
      divLinkLog.style   = ' margin: 20PX auto; width: 120px;';

      var sLinkLog       = document.createElement('a');
      sLinkLog.href      = 'tmp/LogDirf.txt';
      sLinkLog.target    = '_blanck';
      sLinkLog.innerHTML = 'LogDirf.txt';

      divLinkLog.appendChild(sLinkLog);

      document.getElementById('gera').parentNode.appendChild(divLinkLog);
    }
    
    if ( oRetorno.aArquivosInconsistentes.length > 0) {
        
    	var listagem = '';
    	var sSepara = '';    	
      for (iIndice = 0; iIndice < oRetorno.aArquivosInconsistentes.length; iIndice++) {
    	  listagem += sSepara + oRetorno.aArquivosInconsistentes[iIndice].urlDecode()+"#Download Relatório de Inconsistências"+iIndice;
    	  sSepara = '|';
      }


      js_montarlista(listagem,"form1");
    }
  
  } else {
    alert(oRetorno.message.urlDecode());
  }

}

function js_verificaPendencias() {

  $('frmProcessaDirf').disable();
  var oParam   = new Object();
  oParam.exec  = 'getUnidadesCnpjInvalido';  
  js_divCarregando('Aguarde, pesquisar dados', 'msgBox');
  oAjax        = new Ajax.Request('pes4_processardirf.RPC.php',
                                  {
                                   method:'post',
                                   parameters:'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoUnidadesCnpjInvalido
                                  })
}

function js_retornoUnidadesCnpjInvalido(oAjax) {
  
  js_removeObj('msgBox');  
  if ($('wndUnidades')) {
    oWindowUnidades.shutDown();
  }
  var iWidth   = document.width/1.5;
  var iHeight  = (document.body.clientHeight/1.5); 
  var oRetorno    = eval("("+oAjax.responseText+")");
  oWindowUnidades = new windowAux('wndUnidades', 'Unidades Com CNPJ Inconsistentes', iWidth, iHeight);
  sContent  = "<div class='infoLancamentoContabil' style='text-align:center;padding:2px;width:99%'>";
  sContent += "  <div style='width:100%'>";
  sContent += "  <fieldset style='margin:0' id='ctnDados'>";
  sContent += "  </fieldset>";
  sContent += "  </div>";
  sContent += "</div>";
  oWindowUnidades.setContent(sContent);
  var sMsg  = "As unidades abaixo podem estar com o CNPJ inconsistente, o que poderá ocorrer divergências na DIRF.";
      sMsg += "Solicite à contabilidade a correção dos CNPJ, caso esteja incorreto. ";
  oMessage  = new DBMessageBoard('msgboard', 
                                 'Unidades com cnpj Inválido',
                                 sMsg,
                                 $("windowwndUnidades_content"));
  oMessage.show();
  oWindowUnidades.setShutDownFunction(function (){
     
    oWindowUnidades.destroy();
    $('frmProcessaDirf').enable();
  });
  oGridUnidades = new DBGrid('gridUnidades');
  oGridUnidades.nameInstance = 'oGridUnidades';
  oGridUnidades.setCellWidth(new Array('5%', '5%', '45%', '30%', '30%'));
  oGridUnidades.setHeight((300));
  oGridUnidades.setCellAlign(new Array("right", "right", "left", "center", "center", "center"));
  oGridUnidades.setHeader(new Array('Orgão', "Unidade", 'Descrição', 'CNPJ U','CNPJ Inst', "Ano"));
  oGridUnidades.show($('ctnDados'));
  oWindowUnidades.show();
  oGridUnidades.clearAll(true);
  for (var i =0; i < oRetorno.unidades.length;i++) {
   
    with (oRetorno.unidades[i]){ 
    
     var aLinha = new Array();
         aLinha[0]  = o41_orgao;
         aLinha[1]  = o41_unidade;
         aLinha[2]  = o41_descr.urlDecode();
         aLinha[3]  = o41_cnpj;
         aLinha[4]  = cnpj_instituicao;
         aLinha[5]  = o41_anousu;
         oGridUnidades.addRow(aLinha);
    }
  }
  oGridUnidades.renderRows();
}
</script>