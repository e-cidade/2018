<?
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
?>
<form name="form1" method="post" action="">
<fieldset style="width: 700px;"><legend><b>Dados do Favorecido </b></legend>
<table>
   <tr>
      <td>
        <? $db_opcao = 3; db_ancora ( $Lz01_nome, "js_pesquisacgm(true)", 1 )?>
      </td>
      <td>
        <? 
          db_input ( "z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, " onchange='js_pesquisacgm(false);' " );
          db_input ( "z01_nome", 41, $Iz01_nome, true, "text", 3 );
        ?>
    </td>
   </tr>
   <tr> 
      <td colspan="2" align="center">
        <input type='button' value='Novo CGM' onclick="js_novoCgm()">
        <input type='button' value='Alterar CGM'onclick="js_alterarCgm($F('z01_numcgm'))">
     </td>
   </tr>
</table>
</fieldset>
<fieldset>
  <legend align='left'>
    <b>Dados da Conta Bancaria </b>
  </legend>
  <div id="ctnContaBancaria">
    <table>
      <tr>
        <td>
          <? db_ancora('<b>Banco:</b>',"js_pesquisabanco(true);",$db_opcao,""); ?>                    
        </td>
        <td colspan="3">
          <?
            db_input('db89_db_bancos',10,$Idb89_db_bancos,true,'text',3,'');
            db_input('db90_descr',40,'',true,'text',3,'');
          ?>
        </td>                 
      </tr>
      <tr>
        <td>
          <b>Código da Agência</b>
        </td>
        <td>
          <?
            db_input('db89_codagencia',10,$Idb89_codagencia,true,'text',$db_opcao);
            db_input('db83_bancoagencia',10,'',true,'hidden',3);
          ?>
        </td>                 
        <td>
          <b>DV Agência:</b>
        </td>
        <td>
            <? db_input('db89_digito',5,$Idb89_digito,true,'text',$db_opcao,'');?>
        </td>                  
      </tr>
      <tr>
        <td>
          <b>Conta Bancária:</b>
        </td>
        <td>
          <?
            db_input('db83_conta',10,$Idb83_conta,true,'text',$db_opcao);
            db_input('c56_contabancaria',10,'',true,'hidden',1);
          ?>
        </td>                 
        <td>
          <b>DV Conta:</b>
        </td>
        <td>
          <?
            db_input('db83_dvconta',5,$Idb83_dvconta,true,'text',$db_opcao,'');
          ?>
        </td>                  
      </tr>               
      <tr>
        <td>
          <b>Identificador (CNPJ)</b>
        </td>
        <td colspan="4">
          <? db_input('db83_identificador',54,$Idb83_identificador,true,'text',$db_opcao,'');?>
        </td>                 
      </tr>
      <tr>
        <td>
          <b>Código da Operação</b>                     
        </td>
        <td>
          <? db_input('db83_codigooperacao',10,$Idb83_codigooperacao,true,'text',$db_opcao,''); ?>
        </td>    
        <td>
          <b>Tipo da Conta:</b>
        </td>
        <td>
          <?
            $aTipoConta = array( 0 => 'Conta Corrente',
                                 1 => 'Conta Poupança' );
            db_select('c63_tipoconta',$aTipoConta,true,1);
          ?>
        </td>                  
      </tr>
      <tr>
        <td>
          <b>Conta Interna Contábil:</b>                     
        </td>
        <td colspan="3">
          <? db_input('v86_containterna',54,$Iv86_containterna,true,'text',$db_opcao,''); ?>
        </td>    
      </tr>
      
      
    </table>
  </div>
</fieldset>
<br>
<input type="submit" value="Salvar" name="Salvar" onclick="return js_ValidaForm();"></form>

<script>

/**
 * Valida formulário
 */
function js_ValidaForm(){
  js_Salvar();
  return false;
}

/**
 * Função para habilitar a aba da página 
 * e redirecionar com a variável setada.
 */
function js_liberaAbas(sChave, lBloqueada) {

  parent.document.formaba.favorecidotaxas.disabled = lBloqueada;
  if(!lBloqueada){
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_favorecidotaxas.location.href='jur1_favorecidotaxa001.php?v86_sequencial=' + sChave;
  }
}

/**
 * Coleta os dados do formulário e manda via RPC
 */
function js_Salvar(){
  oParam                     = new Object();
  oParam.exec                = 'salvarDados';
  oParam.z01_numcgm          = $F('z01_numcgm');
  oParam.db89_db_bancos      = $F('db89_db_bancos');
  oParam.db90_descr          = $F('db90_descr');
  oParam.db89_codagencia     = $F('db89_codagencia');
  oParam.db89_digito         = $F('db89_digito');
  oParam.db83_conta          = $F('db83_conta').trim();
  oParam.db83_dvconta        = $F('db83_dvconta').trim();
  oParam.db83_identificador  = $F('db83_identificador');
  oParam.db83_codigooperacao = $F('db83_codigooperacao');
  oParam.db83_tipoconta      = $F('c63_tipoconta');
  oParam.v86_containterna    = $F('v86_containterna');
  
  js_divCarregando('Salvando dados do Favorecido...', 'msgBox');
  var oAjax = new Ajax.Request("jur1_favorecido.RPC.php",
                               {method    : 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: 
                                  function(oAjax) {
      
                                    js_removeObj('msgBox');
                                    var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
                                    alert(oRetorno.message.urlDecode());
                                    js_liberaAbas(oRetorno.registroSalvo,false);
                                    parent.mo_camada('favorecidotaxas');
                                    
                                  }
                               }
                              ) ;
}

/**
 * Função para capturar os dados do favorecido via RPC
 */

function js_getDadosFavorecido(iCgm){
  var oParam = new Object();
  oParam.exec = 'getDadosFavorecido';
  oParam.iCgm = iCgm;
  
  js_divCarregando('Verificando dados do favorecido.', 'msgBox');
  var oAjax = new Ajax.Request("jur1_favorecido.RPC.php",
                               {method    : 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                            
                                              js_removeObj('msgBox');
                                              var oRetorno = eval("("+oAjax.responseText+")");
                                              
                                              if (oRetorno.status== "2") {
                                                alert(oRetorno.message.urlDecode());
                                              } else {
                                              
                                                if(oRetorno.numrows > 0){

                                                  $('db89_db_bancos').value       = oRetorno.db89_db_bancos.trim();  
                                                  $('db90_descr').value           = oRetorno.db90_descr.trim();    
                                                  $('db89_codagencia').value      = oRetorno.db89_codagencia.trim();
                                                  $('db89_digito').value          = oRetorno.db89_digito.trim();
                                                  $('db83_conta').value           = oRetorno.db83_conta.trim();  
                                                  $('db83_dvconta').value         = oRetorno.db83_dvconta.trim();    
                                                  $('db83_identificador').value   = oRetorno.db83_identificador.trim();    
                                                  $('db83_codigooperacao').value  = oRetorno.db83_codigooperacao;
                                                  $('db83_codigooperacao').value  = oRetorno.db83_codigooperacao;
                                                  $('db83_codigooperacao').value  = oRetorno.db83_codigooperacao;
                                                  $('v86_containterna').value     = oRetorno.v86_containterna;
                                                  $('c63_tipoconta').value        = oRetorno.db83_tipoconta; 
                                                  js_liberaAbas(oRetorno.v86_sequencial, false);

                                                } else {
                                                                     
                                                  $('db89_db_bancos').value       = "";  
                                                  $('db90_descr').value           = "";    
                                                  $('db89_codagencia').value      = "";
                                                  $('db89_digito').value          = "";
                                                  $('db83_conta').value           = "";  
                                                  $('db83_dvconta').value         = "";    
                                                  $('db83_identificador').value   = "";    
                                                  $('db83_codigooperacao').value  = "";
                                                  $('v86_containterna').value     = "";
                                                  $('c63_tipoconta').value        = ""; 
                                                  js_liberaAbas(null, true);
                                                  
                                                                     }
                                              }
                                            }
                               }
                              ) ;
  
}


/**
 * Captura digitação no campo agencia e chama o autocomplete 
 */
if ($('db89_codagencia')) {

  oAutoCompleteAgencia = new dbAutoComplete($('db89_codagencia'),'jur1_favorecido.RPC.php');
 
  oAutoCompleteAgencia.setScrollBar(true);
  oAutoCompleteAgencia.show();
  oAutoCompleteAgencia.setMinLength(3);
  oAutoCompleteAgencia.setValidateFunction(
  
     function() {
     
       var lReturn = true;
       
       if (document.form1.db89_db_bancos.value == "") {
         lReturn  = false;
       } 
       return lReturn;
    }
  );
  
  oAutoCompleteAgencia.setQueryStringFunction(
  
    function () {
    
      var oParam      = new Object();
      oParam.exec     = 'getAgencia';
      oParam.sBanco   = document.form1.db89_db_bancos.value;
      oParam.sAgencia = document.form1.db89_codagencia.value;

      var sQuery  = 'json='+ Object.toJSON(oParam);
      return sQuery;
    }  
  );
  
  oAutoCompleteAgencia.setCallBackFunction(
  
    function(iId,sLabel) {                                   
    
        aDados                     = sLabel.urlDecode().split('-');
        $('db89_codagencia').value = aDados[0].trim();  
        $('db89_digito').value     = aDados[1].trim();    
    }
  );
}

/**
 * Capatura digitação no campo conta e chama o autocomplete 
 */
if ($('db83_conta')) {

  oAutoCompleteConta = new dbAutoComplete($('db83_conta'),'jur1_favorecido.RPC.php');
  oAutoCompleteConta.setScrollBar(true);
  oAutoCompleteConta.show();
  oAutoCompleteConta.setMinLength(3);
  oAutoCompleteConta.setValidateFunction(
    function() {
    
      var lReturn = true;
      
      if (document.form1.db89_db_bancos.value == "" && document.form1.db89_codagencia.value == "") {
        lReturn  = false;
      } 
      return lReturn;
    }
  );
  
  oAutoCompleteConta.setQueryStringFunction(
  
    function () {
      var oParam      = new Object();
      oParam.exec     = 'getConta';
      oParam.sBanco   = document.form1.db89_db_bancos.value;
      oParam.sAgencia = document.form1.db89_codagencia.value;
      oParam.sConta   = document.form1.db83_conta.value;

      var sQuery  = 'json='+ Object.toJSON(oParam);
      return sQuery;
    }  
  
  );
  
  oAutoCompleteConta.setCallBackFunction(
      
    function(iId,sLabel) {
    
        var oDados                      = eval("("+iId.urlDecode()+")");
        $('db83_conta').value           = oDados.db83_conta.trim();  
        $('db83_dvconta').value         = oDados.db83_dvconta.trim();    
        $('db83_identificador').value   = oDados.db83_identificador.trim();    
        $('db83_codigooperacao').value  = oDados.db83_codigooperacao;
        $('c63_tipoconta').setValue(oDados.db83_tipoconta); 

    }
  );
}

function js_pesquisacgm(lMostra){

  if (lMostra) {
     js_OpenJanelaIframe('', 
                         'db_iframe_cgm', 
                         'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm&filtro=1',
                         'Pesquisar CGM',
                         true,'0');
  } else {
    if(document.form1.z01_numcgm.value != ''){ 
       js_OpenJanelaIframe('',
                           'db_iframe_acordogrupo',
                           'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                           '&funcao_js=parent.js_mostracgm&filtro=1',
                           'Pesquisa',
                           false,
                           '0');
    } else {
      document.form1.z01_numcgm.value = ''; 
    }
  }
}

function js_pesquisabanco(mostra){

  if (mostra) {
     js_OpenJanelaIframe('', 
                         'db_iframe_banco', 
                         'func_db_bancos.php?funcao_js=parent.js_mostrabanco|db90_codban|db90_descr',
                         'Pesquisar Bancos',
                         true,
                         '0');
  }
}
function js_mostrabanco(chave1,chave2) {

  $('db89_db_bancos').value = chave1;
  $('db90_descr').value = chave2;
  db_iframe_banco.hide();
}

function js_mostracgm(erro, chave){

  if(erro == true) { 
  
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  } else {
    document.form1.z01_nome.value = chave; 
    js_getDadosFavorecido($F('z01_numcgm'));
  }

}

function js_mostracgm1(chave1, chave2) {
  
  $('z01_nome').value   = chave1;
  $('z01_numcgm').value = chave2;
  db_iframe_cgm.hide();
  js_getDadosFavorecido(chave2);
}

function js_novoCgm() {

  js_OpenJanelaIframe('', 
                      'db_iframe_novocgm', 
                      'prot1_cadgeralmunic001.php?lMenu=false&lFisico=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_favorecido.retornoCgm',
                      'Novo CGM',
                      true,
                      '0');
}

function js_alterarCgm(iCgm) {

  if (iCgm != "") {
    js_OpenJanelaIframe('', 
                        'db_iframe_novocgm', 
                        'prot1_cadgeralmunic002.php?chavepesquisa='+iCgm+
                        '&lMenu=false&lCpf=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_favorecido.retornoCgm',
                        'Novo CGM',
                        true,
                        '0');
  }
  
}

function retornoCgm(iCgm) {
  
  db_iframe_novocgm.hide();
  $('z01_numcgm').value = iCgm;
  js_pesquisacgm(false); 
}
js_tabulacaoforms("form1","z01_numcgm",true,1,"z01_numcgm",true);
</script>
