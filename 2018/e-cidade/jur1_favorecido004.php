<?
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_favorecido_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost         = db_utils::postMemory($_POST);
$oGet          = db_utils::postMemory($_GET);
$clfavorecido  = new cl_favorecido;
$db_opcao      = isset($oGet->db_opcao) ? $oGet->db_opcao : 1;
$db_botao      = true; 
$sBtnSalvar    = $db_opcao == 1 ? "Salvar"      : "Excluir";
$sJsBtnSalvar  = $db_opcao == 1 ? "js_salvar()" : "js_exluir()";


$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("z01_nome");
$oRotuloCampos->label("z01_numcgm");
$oRotuloCampos->label("db89_db_bancos");
$oRotuloCampos->label("db89_digito");
$oRotuloCampos->label("db89_codagencia");
$oRotuloCampos->label("db90_descr");
$oRotuloCampos->label("db83_bancoagencia");
$oRotuloCampos->label("db83_conta");
$oRotuloCampos->label("db83_dvconta");
$oRotuloCampos->label("db83_identificador");
$oRotuloCampos->label("db83_codigooperacao");
$oRotuloCampos->label("db83_tipoconta");
$oRotuloCampos->label("db83_tipocontadescr");
$oRotuloCampos->label("v86_containterna");

if ( isset($oGet->chavepesquisa) ) {
  $z01_numcgm  = $oGet->chavepesquisa;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js"); 
  db_app::load("strings.js");
  db_app::load("dbautocomplete.widget.js");
  db_app::load("DBViewContaBancaria.js");
  db_app::load("estilos.css");
  db_app::load("grid.style.css");
  db_app::load("dbtextField.widget.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("prototype.maskedinput.js");
  ?>
</head>
<body bgcolor=#CCCCCC>
<form class="container" id="form1" name="form1" onsubmit="return false;">
  <fieldset>
    <legend>Dados do Favorecido</legend>
    <table class="form-container">
       <tr>
          <td>
            <? db_ancora ( $Lz01_nome, "js_pesquisacgm(true)", 1 )?>
          </td>
          <td>
            <? 
              db_input ( "z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, " onchange='js_pesquisacgm(false);' " );
              db_input ( "z01_nome", 41, $Iz01_nome, true, "text", 3 );
            ?>
            
        </td>
       </tr>
       <!-- 
         Comentado por causa do cadastro antigo de de CGM
       <tr> 
         <td colspan="3" align="center">
            <input type='button' value='Novo CGM' onclick="js_novoCgm()">
            <input type='button' value='Alterar CGM'onclick="js_alterarCgm($F('z01_numcgm'))">
         </td>
       </tr>
        -->
       <tr>
      <td>
        Conta Interna Contábil:                     
      </td>
      <td>
        <? db_input('v86_containterna',55,$Iv86_containterna,true,'text',$db_opcao,''); ?>
      </td>    
    </tr>
    </table>
  </fieldset>
  <div id="ctnContaBancaria"></div> 
  <input id="btnSalvar"    type ="submit" value="Salvar"    onclick="js_salvar();" > 
  <input id="btnExcluir"   type ="button" value="Excluir"   onclick="js_excluir();" disabled="disabled"> 
  <input id="btnPesquisar" type ="button" value="Pesquisar" onclick="js_pesquisa();"> 
</form>
</body>
</html>
<script>

  function js_pesquisa(){
    var sUrl = 'func_favorecido.php?funcao_js=parent.js_mostrafavorecido|z01_nome|z01_numcgm';
    js_OpenJanelaIframe('',
								        'db_iframe_favorecido',
								        sUrl,
								        'Pesquisa Favorecido',
								        true, 
								        20,
								        screen.availWidth/4, 
								        screen.availWidth/2,600);
  }

  function js_mostrafavorecido(chave1, chave2) {
    
    $('z01_nome').value   = chave1;
    $('z01_numcgm').value = chave2;
    db_iframe_favorecido.hide();
    js_getDadosFavorecido(chave2);
  }
  var oContaBancaria = new DBViewContaBancaria(null, 'oContaBancaria',false);
//    oContaBancaria.readOnly(true);
  	  oContaBancaria.show('ctnContaBancaria');
//  	  oContaBancaria.makeAutoComplete();

  function js_salvar(){
    oParam                           = new Object();
    oParam.exec                      = 'salvarDados';
    oParam.oDados              	     = $('form1').serialize(true);
    oParam.oDados.iSequencialConta   = $F('inputSequencialConta');
    oParam.oDados.iSequencialAgencia = $F('inputSequencialAgencia');
    oParam.oDados.inputCodigoBanco   = $F('inputCodigoBanco');
    js_divCarregando('Salvando dados do Favorecido...', 'msgBox');
    var oAjax = new Ajax.Request("jur1_favorecido.RPC.php",
                                 {method    : 'post',
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: 
                                    function(oAjax) {
        
                                      js_removeObj('msgBox');
                                      var oRetorno = JSON.parse(oAjax.responseText);

                                      if (oRetorno.status == "2") {
                                        alert(oRetorno.message.urlDecode());
                                        return false;
                                      }

                                      parent.document.formaba.favorecidotaxas.disabled = false;
                                      CurrentWindow.corpo.iframe_favorecidotaxas.location.href='jur1_favorecidotaxa001.php?v86_sequencial=' + oRetorno.registroSalvo;
                                      alert(oRetorno.message.urlDecode());
                                      parent.mo_camada('favorecidotaxas');
                                      
                                    }
                                 }
                                ) ;
  }

  function js_excluir(){
    oParam                         = new Object();
    oParam.exec                    = 'excluir';
    oParam.oDados              	   = $('form1').serialize(true);
    oParam.oDados.iCgmFavorecido   = $F('z01_numcgm');
    if(confirm("Deseja excluir o favorecido selecionado?")){
      js_divCarregando('Excluindo Favorecido...', 'msgBox');
      var oAjax = new Ajax.Request("jur1_favorecido.RPC.php",
                                   {method    : 'post',
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: 
                                      function(oAjax) {
          
                                        js_removeObj('msgBox');
                                        var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
                                        alert(oRetorno.message.urlDecode());
                                        if(oRetorno.status == 1){
                                          window.location = "jur1_favorecido004.php"+window.location.search;
                                        }
                                      }
                                   }
                                  ) ;
    }
  }

  function js_pesquisacgm(lMostra){

    if (lMostra) {
       js_OpenJanelaIframe('', 
                           'db_iframe_cgm', 
                           'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm',
                           'Pesquisar CGM',
                           true,'0');
    } else {
      if(document.form1.z01_numcgm.value != ''){ 
         js_OpenJanelaIframe('',
                             'db_iframe_acordogrupo',
                             'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                             '&funcao_js=parent.js_mostracgm',
                             'Pesquisa',
                             false,
                             '0');
      } else {
        document.form1.z01_numcgm.value = ''; 
      }
    }
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

  function js_getDadosFavorecido(iCgm){
    
    var oParam = new Object();
    oParam.exec = 'getDadosFavorecido';
    oParam.iCgm = iCgm;
    
    js_divCarregando('Verificando dados do favorecido.', 'msgBox');
    var oAjax = new Ajax.Request(
                 "jur1_favorecido.RPC.php",
                 {
                 method    : 'post',
                 parameters: 'json='+Object.toJSON(oParam), 
                 onComplete: 
                   function(oAjax) {
                             
                     js_removeObj('msgBox');
                     var oRetorno = eval("("+oAjax.responseText+")");
                     
                     if (oRetorno.status== "2") {
                       alert(oRetorno.message.urlDecode());
                     } else {
                       
                       js_liberaBotoes(true);
                       $('btnExcluir').disabled=true;      
                       
                       if(oRetorno.numrows > 0){

                         $('v86_containterna').setValue(oRetorno.v86_containterna);
                         oContaBancaria.getDados(oRetorno.v86_contabancaria);
                         $('btnExcluir').disabled=false;      
                         parent.document.formaba.favorecidotaxas.disabled = false;
                         CurrentWindow.corpo.iframe_favorecidotaxas.location.href='jur1_favorecidotaxa001.php?v86_sequencial=' + oRetorno.v86_sequencial;
                        
//                         parent.mo_camada('favorecidotaxas');
                       }
                     }
                   } 
                 }
                );
    
  }
 function js_liberaBotoes(lOpcao){
   
   if(lOpcao){
      $('btnSalvar').disabled=false;
     } else {
      $('btnSalvar').disabled=false;
     }
   } 	   
</script>
<script>


$("z01_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("v86_containterna").addClassName("field-size9");

$("inputCodigoBanco").addClassName("field-size2");
$("inputNomeBanco").addClassName("field-size7");
$("inputNumeroAgencia").addClassName("field-size2");
$("inputNumeroConta").addClassName("field-size2");
$("inputDvAgencia").addClassName("field-size1");
$("inputDvConta").addClassName("field-size1");
$("inputIdentificador").addClassName("field-size9");
$("inputOperacao").addClassName("field-size2");

</script>
