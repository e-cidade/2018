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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?php 
db_app::load("scripts.js");
db_app::load("strings.js");
db_app::load("prototype.js");

db_app::load("estilos.css");

db_app::load("grid.style.css");
db_app::load("datagrid.widget.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");

?>

<style>

  #windowErroAvisos{
    overflow: hidden;
  }
  #windowwindowErroAvisos_content{
    overflow: hidden !important;
  }
</style>

</head>


<body bgcolor="#CCCCCC">
<form class="container" name="form1" method="post" action="" target="">
<fieldset>
  <legend>Geração de arquivo para INSS</legend>
<table class="form-container">
  <tr>
    <td>
      Período das Obras:
      <input id        = "iMes" 
             maxlength = "2" 
             size      = "2"  
             onChange  = "js_ValidaCampos(this,1,'Mês da obra','f','f',event);" 
             name      = "iMes"/> 
       &nbsp; / &nbsp; 
      <input id        = "iAno" 
             maxlength = "4" 
             size      = "4"
             onChange  = "js_ValidaCampos(this,1,'Ano da obra','f','f',event);" 
             name      = "iAno"  /> 
    </td>
 </tr>
</table>  
</fieldset>
      <input name="gerar" onClick="js_gerarArquivo('false');" type="button" value="Gerar TXT" >
</form>

<div id="idTeste">

</div>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

var sUrlRPC = 'pro4_gerarTxtINSS.RPC.php';

// função que enviaa os dados para o RPC

function js_gerarArquivo(lAviso){

  var iMes   = $F('iMes');
  var iAno   = $F('iAno');
  
  if (iMes == null || iMes == '') {
    
    alert(_M('tributario.projetos.pro4_gerarTxt.selecione_mes'));
    return false;
  } 
  if (iAno == null || iAno == '') {
    
    alert(_M('tributario.projetos.pro4_gerarTxt.selecione_ano'));
    return false; 
  }
  
  var oParametros     = new Object();
  var msgDiv          = _M('tributario.projetos.pro4_gerarTxt.processando_arquivo');
  oParametros.exec    = 'gerarTXT';  
  oParametros.iMes    = iMes;
  oParametros.iAno    = iAno;
  oParametros.lAviso  = lAviso;
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoTxt
                                             });   
}
// retorno do RPC, tratamos se existem erros ou avisos para mostrar em uma window aux
function js_retornoTxt(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.iStatus == 1) {
     
      if (oRetorno.iInconsistencia == 1) {
        
        js_MostraErros();               // cria a janela para mostrar os erros
        
        js_listaErros(oRetorno.aErros); // função para popular os erros
        
        
      } else {
        
        
        windowErroAvisos.destroy();
         
        var listagem  = oRetorno.sArquivo + "# Download do Arquivo " + oRetorno.sArquivo;
            js_montarlista(listagem,'form1');
        
      }   
          
    } else {
    
      alert(oRetorno.sMessage.urlDecode());
    }

}

//---    WINDOW AUX para exibição de erros e avisos ======================//

function js_MostraErros() {

  var iLarguraJanela = screen.availWidth  - 400;
  var iAlturaJanela  = screen.availHeight - 250;
  
  windowErroAvisos   = new windowAux( 'windowErroAvisos',
                                      'Erros e Avisos Encontrados',
                                      iLarguraJanela, 
                                      iAlturaJanela
                                    );
  
  var sConteudoErroAvisos  = "<div>";
      sConteudoErroAvisos += "<div id='sTituloWindow'></div> "; // container do message box
      
      sConteudoErroAvisos += "<div id='sContGrid'></div> ";    // container da grid com erros e avisos; 
      
      sConteudoErroAvisos += "<center><div id='ctnGerarRelatorio' style='margin-top:10px;'>";
      sConteudoErroAvisos += " <input type='button' value='Emitir Relatório' onclick='js_emiteRelatorio();' />";
      sConteudoErroAvisos += " <input type='button' id='gerararquivo' value='Processar Arquivo' onclick='js_gerarArquivo(\"true\");'   />";
      sConteudoErroAvisos += " <input type='button' value='Cancelar' onclick='windowErroAvisos.destroy();'         />";
      
      sConteudoErroAvisos += "</div><center>";
      
      sConteudoErroAvisos += "</div>";
      
   windowErroAvisos.setContent(sConteudoErroAvisos);
 
   //============  MESAGE BORD PARA TITULO da JANELA de ERROS   
  var sTextoMessageBoard  = 'Registros do tipo <strong>ERRO</strong>, devem ser corrigidos. <br> ';
      sTextoMessageBoard += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registros do tipo <strong>AVISO</strong> podem ser ignorados.';
      messageBoard        = new DBMessageBoard('msgboard1',
                                               'Dados das Inconsistências do Arquivo.',
                                                sTextoMessageBoard,
                                                $('sTituloWindow'));
                                                
    /*funcao para corrigir a exibição do window aux, apos fechar a primeira vez
    */
    windowErroAvisos.setShutDownFunction(function () {
      windowErroAvisos.destroy();
    });             
                                       
   windowErroAvisos.show();
   messageBoard.show();
   js_montaGridErros();

}


// função para chamada da grid que tera erros e avisos

function js_montaGridErros() {
  
  oGridErroAvisos = new DBGrid('Erro e Avisos');
  oGridErroAvisos.nameInstance = 'oGridErroAvisos';
  oGridErroAvisos.allowSelectColumns(false);
  
  oGridErroAvisos.setCellWidth(new Array( '10px' ,
                                          '20px',
                                          '80px'
                                           ));
  
  oGridErroAvisos.setCellAlign(new Array( 'left'  ,
                                           'left'  ,
                                           'left'
                                           ));
  
  oGridErroAvisos.setHeader(new Array('Tipo',
                                      'Registro',
                                      'Detalhes'
                                       ));                                   
                                        
  oGridErroAvisos.setHeight(300);
  oGridErroAvisos.show($('sContGrid'));
  oGridErroAvisos.clearAll(true);                                      
    
}

// recebe o array de inconcistencias para popular a grid
function js_listaErros(aErros){

          aErros.each( 
                        function (oDado, iInd) {       
                            var aRow    = new Array();
                            
                                /* verifica se ha erros para bloquear a geração do arquivo
                                   caso tenha somente avisos, sera permitida a geração 
                                */
                                   
                                if (oDado.tipo == "ERRO") {
                                  $('gerararquivo').disabled = true;
                                } 
                                
                                aRow[0] = oDado.tipo;
                                aRow[1] = oDado.registro.urlDecode();
                                aRow[2] = oDado.detalhe.urlDecode();
                                oGridErroAvisos.addRow(aRow);
                           });
          oGridErroAvisos.renderRows(); 
}



function js_emiteRelatorio(){

  var sFonte  = "pro3_inconsistenciaInss.php";  // relatorio de erros
      sQuery  = "?sDataA=a";
      sQuery += "&sDataB=b";
      jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}


</script>
<script>

$("iMes").addClassName("field-size1");
$("iAno").addClassName("field-size1");

</script>