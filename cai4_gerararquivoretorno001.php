<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empagegera_classe.php");
$clempagegera = new cl_empagegera;
$clrotulo     = new rotulocampo;
$clempagegera->rotulo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, datagrid.widget.js, windowAux.widget.js, prototype.js, dbmessageBoard.widget.js");
     db_app::load("strings.js, estilos.css, grid.style.css");
    ?>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px">
    <center>
      <form name="form1" method="post">
      <div style="display: table;">
        <fieldset>
          <legend><b>Selecione o Arquivo</b></legend>     
          <table border='0'>
            <tr> 
              <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
              <td align="left" nowrap>
            <?
             db_input("e87_codgera",8,$Ie87_codgera,true,"text",4,"onchange='js_pesquisa_gera(false);'"); 
             db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
            ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </div>
      <input type="button" value='Visualizar Registros' id='btnVisualizar'>
      </form>
    </center>
  </body>
</html>        
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisa_gera(mostra){
  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_empagegera',
                        'func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera',
                        'Pesquisa',
                        true);
  } else {
     if (document.form1.e87_codgera.value != '') { 
       
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_empagegera',
                            'func_empagegera.php?pesquisa_chave='+document.form1.e87_codgera.value+
                            '&funcao_js=parent.js_mostragera',
                            'Pesquisa',
                            false);
     } else {
       document.form1.e87_descgera.value = ''; 
     }
  }
}
function js_mostragera(chave,erro) {

  document.form1.e87_descgera.value = chave; 
  if (erro) {
   
    document.form1.e87_codgera.focus(); 
    document.form1.e87_codgera.value = ''; 
  } else {
    js_openRegistrosArquivo($F('e87_codgera'));
  }
}
function js_mostragera1(chave1,chave2) {
  
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
  js_openRegistrosArquivo(chave1);
}
var sURLRPC  = 'cai4_gerararquivoretorno.RPC.php';
function js_openRegistrosArquivo(iArquivo) {
  
  if (iArquivo == '') {
   return false;
  }
  var iWidth = document.viewport.getDimensions().width;
  oJanelaRegistros = new windowAux('wndRegistros', 'Registros do Arquivo '+iArquivo, iWidth/2, 400);
  oJanelaRegistros.setShutDownFunction(function() {
  
     oJanelaRegistros.destroy();
  });
  var sContent  = "<div style='width:100%'>";
  sContent     += "  <fieldset>";
  sContent     += "    <legend>";
  sContent     += "      <b>Registros</b>";
  sContent     += "    </legend>";
  sContent     += "    <div id='ctnGridRegistros'>";
  sContent     += "    </div>";
  sContent     += "  </fieldset>";
  sContent     += "  <center>";
  sContent     += "   <input type='button' value='Gerar' id='btnProcessar' onclick='js_gerarTXT()'>";
  sContent     += "   <span id='download'></div>";
  sContent     += "  </center>";
  sContent     += "</div>";
  oJanelaRegistros.setContent(sContent);
  oMessageBoard = new DBMessageBoard('msgboard1',
                                     'Arquivo '+iArquivo, 
                                     'Informe os dados de Retorno do arquivo.',
                                     oJanelaRegistros.getContentContainer());
                                     
  oGridRegistros              = new DBGrid('gridRegistros');
  oGridRegistros.nameInstance = 'oGridRegistros';
  oGridRegistros.setCellWidth(new Array("15%", "50%", "25%", "5%"));
  oGridRegistros.setCellAlign(new Array("right", "left", "right"));
  oGridRegistros.setHeader(new Array("Movimento", "Fornecedor", "Valor", "Código Retorno"));
  oJanelaRegistros.show();  
  oGridRegistros.show($('ctnGridRegistros'));                                       
  js_getRegistros();
}
function js_getRegistros() {
  
  
   js_divCarregando('Aguarde, Buscando registros....', 'msgbox');
   var oParam            = new Object();
   oParam.exec           = 'getRegistros';
   oParam.iCodigoArquivo = $F('e87_codgera');
   var oAjax             = new Ajax.Request(sURLRPC,
                                            {
                                             method:'post',
                                             parameters: 'json='+Object.toJSON(oParam),
                                             onComplete:js_preencheGridRegistros
                                            }
                                            );
}

function js_preencheGridRegistros(oAjax) {
 
  js_removeObj('msgbox');
  oGridRegistros.clearAll(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.registros.length > 0) {
    
    var sTitulo = "Arquivo "+$F('e87_codgera');
    oMessageBoard.setTitle(sTitulo+" - "+oRetorno.codigobanco+" - "+oRetorno.descricaobanco.urlDecode()); 
    oRetorno.registros.each(function(oRegistro, iLinha) {
    
      var aLinha = new Array();
      aLinha[0]  = oRegistro.e81_codmov;
      aLinha[1]  = oRegistro.z01_nome.urlDecode();
      aLinha[2]  = js_formatar(oRegistro.e81_valor.urlDecode(), 'f');
      aLinha[3]  = "<input type='text' id='retorno"+oRegistro.e81_codmov+"' style='width:100%' value='00' maxlength='2'>";
      oGridRegistros.addRow(aLinha);
    });
    oGridRegistros.renderRows();
  }
}
$('btnVisualizar').observe('click', function() {
  js_openRegistrosArquivo($F('e87_codgera'));
});
function  js_gerarTXT () {

  var aRegistros          = oGridRegistros.aRows;
  var aRegistrosProcessar = new Array();
  aRegistros.each(function (oRegistro, iSeq) {
     
     var oRegistroProcessar             = new Object();
     oRegistroProcessar.codigomovimento = oRegistro.aCells[0].getValue(); 
     oRegistroProcessar.codigoretorno   = oRegistro.aCells[3].getValue();
     aRegistrosProcessar.push(oRegistroProcessar); 
  });  
  
  js_divCarregando('Aguarde, gerando arquivo', 'msgbox'); 
  var oParam            = new Object();
  oParam.exec           = 'processarArquivo';
  oParam.iCodigoArquivo = $F('e87_codgera');
  oParam.aRegistros     = aRegistrosProcessar;
  var oAjax             = new Ajax.Request(sURLRPC,
                                           {
                                            method:'post',
                                            parameters: 'json='+Object.toJSON(oParam),
                                            onComplete:js_downloadArquivo
                                           }
                                          );
} 
function js_downloadArquivo(oAjax) {
  
  js_removeObj('msgbox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    var sLink = '<a href="db_download.php?arquivo=tmp/'+oRetorno.nomearquivo+'">Baixar '+oRetorno.nomearquivo+'</a>';
    $('download').innerHTML = sLink;
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
</script>