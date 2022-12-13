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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_issarqsimples_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$clissarqsimples  = new cl_issarqsimples();
$clissarqsimples->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q17_nomearq");
$clrotulo->label("k15_codbco");
$clrotulo->label("k15_codage");
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
   db_app::load("datagrid.widget.js");
   db_app::load("grid.style.css");
   db_app::load("estilos.css");
   db_app::load("widgets/windowAux.widget.js");
   db_app::load("widgets/dbmessageBoard.widget.js");  
?>
</head>
<style>
  .link {
    text-decoration: underline;
    color: blue;
    cursor: pointer;
  }
</style>

<body bgcolor=#CCCCCC leftmargin="0" enctype="form/" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pesquisa();a=1" >
<center>
  <form name='form1' id='form1' enctype="multipart/form-data" method='post'> 
     <fieldset style="margin-top: 50px; width: 700px;">
       <legend><strong>Processa Arquivo de retorno - Simples Nacional</strong></legend>
       
         <table>
          <tr>
           <td nowrap title="<?=@$Tq17_sequencial?>">
             <strong>
              <? db_ancora("Código", "js_pesquisaq17_sequencial(true);", 1); ?>
             </strong>
           </td>
           <td> 
             <?
                db_input('q17_sequencial', 10, $Iq17_sequencial, true, 'text', 3);
                db_input('q17_nomearq', 60, $Iq17_nomearq, true, 'text', 3, '');
             ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk15_codbco?>">
              <? db_ancora("$Lk15_codbco","js_pesquisacadban(true);",1); ?>
            </td>
            <td> 
             <?
                db_input('k15_codbco', 10, $Ik15_codbco, true, 'text', 3);
                db_input('nomebanco', 60, '', true, 'text', 3, '');
             ?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$Lk15_codage;?><b> / Conta</b></td>
            <td>
              <?
                db_input('k15_codage', 10, $Ik15_codage, true, 'text', 3, '');
                db_input('k15_conta', 20, '', true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>   
     </fieldset>
 
    <div style="margin-top: 10px;">
      <input name="processar" type="button" id="validar"   value="Validar"   onclick="js_validar();" />
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
    </div> 

</form>

 </center>
 
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
                  
var sUrlRPC = "iss4_processaarquivosimples.RPC.php";
/*
 * função para verificar as inconsistencias, antes do processamento do arquivo de retorno
 */
function js_validar(){

   //alert(111111);

  var iIssArquivo         = $F("q17_sequencial");
  var iBanco              = $F("k15_codbco");
  var iAgencia            = $F("k15_codage");
  var iConta              = $F("k15_conta");
  var iArquivo            = $F("q17_sequencial");
  
  var msgDiv              = "Realizando o Pré Processamento \n Aguarde ...";
  var oParametros         = new Object();
  oParametros.exec        = 'preProcessamento';  
  oParametros.iIssArquivo = iIssArquivo;
  oParametros.iBanco      = iBanco     ;  
  oParametros.iAgencia    = iAgencia   ;
  oParametros.iConta      = iConta     ;
  oParametros.iArquivo    = iArquivo   ;

  
  if (iIssArquivo == '') {

    alert('Selecione um Arquivo.');
    return false;
  }

  if (iBanco == '') {

    alert('Selecione um Banco.');
    return false;
  }

  if (iAgencia == '') {

    alert('Selecione uma Agência.');
    return false;
  }

  if (iConta == '') {

    alert('Selecione uma Conta.');
    return false;
  }    

  js_divCarregando(msgDiv,'msgBox');

  document.form1.validar.disabled = true;
  
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                            {method: "post",
                                             parameters:'json='+Object.toJSON(oParametros),
                                             onComplete: js_inconsistencias
                                            });  
  
}

function js_inconsistencias(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    document.form1.validar.disabled = false;
    
    if (oRetorno.iStatus == 1) {
    
        js_MostraErros();               // cria a janela para mostrar os erros
    
      if (oRetorno.iInconsistencia == 1) {
        
        js_listaErros(oRetorno.aErros); // função para popular os erros
        
      }   
       
       
    } else {
      
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
}

/**
 * funcao para percorrer erros e avisos e exibir na grid para correcao
 */
function js_listaErros(aErros){

  var sHtmlFunction = '';

  aErros.each( 
                function (oDado, iInd) {       
                    var aRow    = new Array();
                    
                        /* verifica se ha erros para bloquear o processamento
                           caso tenha somente avisos, sera permitida a geração 
                        */
                           
                        if (oDado.sTipo == "ERRO") {
                          $('gerararquivo').disabled = true;
                        } 

                        switch (oDado.sErro) {

                          case 'variasinscricoes' :
                            sHtmlFunction = "<a class='link' onclick='js_exibeCgmVariasInscricoes(\""+oDado.sCnpj+"\",\""+oDado.iRegistro+"\");'> MI </a>";
                          break;

                          case 'cnpjDuplicado' :
                            sHtmlFunction = "<a class='link' onclick='js_exibeCnpjDuplicado(\""+oDado.sCnpj+"\",\""+oDado.iRegistro+"\");'> MI </a>";
                          break;                            
                          
                          case 'semInscricaoAtiva' :
                            sHtmlFunction = "<a class='link' onclick='js_geraComplementar(\""+oDado.sCnpj+"\",\""+oDado.iRegistro+"\");'> MI </a>";
                          break;

                          case 'semCgm' :
                            sHtmlFunction = "<a class='link' onclick='js_pesquisaCgm();'> MI </a>";
                          break;  
                          
                          default :
                            sHtmlFunction = "";
                          break;  
                        }

                        aRow[0] = oDado.iRegistro;
                        aRow[1] = oDado.sTipo;
                        aRow[2] = oDado.sCnpj;
                        aRow[3] = oDado.sDetalhe.urlDecode();
                        aRow[4] = sHtmlFunction;
                        oGridErroAvisos.addRow(aRow);
                   });
  oGridErroAvisos.renderRows(); 
}




//---    WINDOW AUX para exibição de erros e avisos ======================//

function js_MostraErros() {

  var iLarguraJanela = screen.availWidth  - 200;
  var iAlturaJanela  = screen.availHeight - 150;
  
  windowErroAvisos   = new windowAux( 'windowErroAvisos',
                                      'Erros e Avisos Encontrados',
                                      iLarguraJanela, 
                                      iAlturaJanela
                                    );
  
  
  var sConteudoErroAvisos  = "<div>";
  
      sConteudoErroAvisos += "  <div id='sTituloWindow'></div> "; // container do message box
      
      sConteudoErroAvisos += "  <div id='sContGrid'></div> ";    // container da grid com erros e avisos; 
      
      sConteudoErroAvisos += "<center> ";
      sConteudoErroAvisos += "  <div id='ctnGerarRelatorio' style='margin-top:10px;'>";
      sConteudoErroAvisos += "    <input type='button' value='Emitir Relatório' onclick='js_emiteRelatorio();' />";
      sConteudoErroAvisos += "    <input type='button' id='gerararquivo' value='Processar Arquivo' onclick='js_processar();'   />";
      sConteudoErroAvisos += "    <input type='button' value='Cancelar' onclick='windowErroAvisos.destroy();'   />";
      sConteudoErroAvisos += "  </div> ";
      sConteudoErroAvisos += "<center> ";
      
      sConteudoErroAvisos += "</div>";
      
   windowErroAvisos.setContent(sConteudoErroAvisos);
 
   //============  MESSAGE BOARD PARA TITULO da JANELA de ERROS   
  var sTextoMessageBoard  = 'Registros do tipo <strong>ERRO</strong>, devem ser corrigidos. <br> ';
      sTextoMessageBoard += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registros do tipo <strong>AVISO</strong> podem ser ignorados.';
      messageBoard        = new DBMessageBoard('msgboard1',
                                               'Dados das Inconsistências do Arquivo.',
                                                sTextoMessageBoard,
                                                $('sTituloWindow'));
                                                
   /*
     funcao para corrigir a exibição do window aux, apos fechar a primeira vez
   */
   windowErroAvisos.setShutDownFunction(function () {
     windowErroAvisos.destroy();
   });             

   windowErroAvisos.show();      
   messageBoard.show();
                                 
   js_montaGridErros();                              
                                                     
}

//função para chamada da grid que tera erros e avisos

function js_montaGridErros() {

  //if (typeof(oGridErroAvisos) != "object"){
  
  oGridErroAvisos = new DBGrid('Erro e Avisos');
  oGridErroAvisos.nameInstance = 'oGridErroAvisos';
  oGridErroAvisos.allowSelectColumns(false);
  
  oGridErroAvisos.setCellWidth(new Array( '10px',
                                          '10px',
                                          '20px',
                                          '80px',
                                          ' 5px'
                                        ));
  
  oGridErroAvisos.setCellAlign(new Array('left',
                                         'left',
                                         'left',
                                         'left',
                                         'center'
                                        ));
  
  oGridErroAvisos.setHeader(new Array( 'Linha do Arquivo',
                                       'Tipo',
                                       'Cnpj',
                                       'Inconsistência',
                                       'Ação'
                                      ));                                   
                                        
  oGridErroAvisos.setHeight(400);
  oGridErroAvisos.show($('sContGrid'));
  oGridErroAvisos.clearAll(true);                                      
 // }  
}
                 
function js_pesquisaq17_sequencial(mostra){
  
  if (mostra == true) {
    
     js_OpenJanelaIframe('CurrentWindow.corpo',
                         'db_iframe_issarqsimples',
                         'func_issarqsimples.php?semproc=1&funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_nomearq',
                         'Arquivos de Retorno',
                         true);
  }
}
function js_pesquisacadban(mostra){
  if (mostra==true){
     js_OpenJanelaIframe('CurrentWindow.corpo',
                         'db_iframe_cadban',
                         'func_cadban.php?method=sql_query_tabplan&funcao_js=parent.js_mostracadban|k15_codbco|k15_codage|z01_nome|k15_conta',
                         'Consulta Bancos',
                         true);
  }
}

function js_mostraissarqsimples1(chave1, chave2){

  $('q17_sequencial').value = chave1;
  $('q17_nomearq')   .value = chave2;
  db_iframe_issarqsimples.hide();
  if ($('k15_codbco').value == ''){
     js_pesquisacadban(true);
  }
}

function fechaIframeCgc(){

  db_iframe_cgc.hide();
  windowErroAvisos.destroy();
  js_validar();
}

function js_mostracadban(chave1, chave2, chave3, chave4) {
  
  $('k15_codbco').value = chave1;
  $('k15_codage').value = chave2;
  $('nomebanco').value = chave3;
  $('k15_conta').value = chave4;
  db_iframe_cadban.hide();
}

function js_pesquisa(){
  
  js_OpenJanelaIframe( 'CurrentWindow.corpo',
                       'db_iframe_issarqsimples',
                       'func_issarqsimples.php?semproc=1&funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_nomearq',
                       'Pesquisa',
                       true
                     );
}
function js_emiteRelatorio(){

    var sFonte  = "iss3_inconsistenciaSimples.php";  // relatorio de erros
        jan = window.open(sFonte,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
}

  function js_processar() {

    (window.CurrentWindow || parent.CurrentWindow).corpo.windowErroAvisos.divContent.getElementsBySelector('#gerararquivo')[0].disabled = true;

    js_divCarregando("Processando Arquivo...",'msgBox');
    
    new Ajax.Request(sUrlRPC,{method     : "post",
                               parameters : 'json='+Object.toJSON({exec      : 'processar', 
                                                                   iRegistro : $F("q17_sequencial"),
                                                                   sArquivo  : $F("q17_nomearq"),
                                                                   iBanco    : $F("k15_codbco"),
                                                                   iAgencia  : $F("k15_codage"),
                                                                   iConta    : $F("k15_conta")

                                                          }),
                                onComplete : function(oAjax){
                                  
                                  js_removeObj('msgBox');
                                  
                                  var oRetorno = eval("("+oAjax.responseText+")");

                                  (window.CurrentWindow || parent.CurrentWindow).corpo.windowErroAvisos.divContent.getElementsBySelector('#gerararquivo')[0].disabled = false;

                                  if (oRetorno.iStatus == 1) {
                                    
                                    alert("Arquivo Processado.");
                                    window.location.href = "iss4_processaarquivosimples001.php";
                                  } else {
                                    alert("Erro ao Processar Arquivo: " + oRetorno.sMessage.urlDecode());
                                  }
                                }
                               });  
  }
<?php require_once("iss4_processaarquivosimples.js")?>

</script>
