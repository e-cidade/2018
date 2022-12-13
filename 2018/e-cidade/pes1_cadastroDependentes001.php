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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

//Objeto com os itens do array $_GET
$oGet  = db_utils::postMemory($_GET);

//Objeto com os itens do array $_POST
$oPost = db_utils::postMemory($_POST);

$oRotulos = new rotulocampo();
$oRotulos->label('rh01_regist');
$oRotulos->label('z01_nome');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      /**
       * Base para funcionamento
       */
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js"); 
      /**
       * widgets
       */
      db_app::load("windowAux.widget.js");
      db_app::load("messageboard.widget.js");
      db_app::load("datagrid.widget.js");
      
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <BR>
    <BR>
    <BR>
    <BR>
    <center>
      <div style="width:600; margin-left:50%; position: absolute; left: -300px; " id="base">
          <table align="center" border="0" cellspacing="5" cellpadding="5">
            <tr>
              <td nowrap title="<?=@$Trh01_regist?>">
                <?
                  db_ancora($Lrh01_regist,"js_pesquisaMatricula(true);",1);
                ?>
              </td>
              <td nowrap>
                <?
                  db_input('rh01_regist',6 ,$Irh01_regist,true,'text',1,"onchange='js_pesquisaMatricula(false);'");
                  db_input('z01_nome'   ,40,$Iz01_nome   ,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td align="center" colspan="2">
                <input type="submit" value="Pesquisar" name="pesquisar" onclick="js_processaConsulta();">
              </td>
            </tr>
          </table>
      </div>
    </center>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    
    ?>
  </body>
</html>
<script>
var iTamanhoTela = screen.availWidth;
var iCentro = ((iTamanhoTela / 2) - 400);

/**
 * Escopo geral do script
 */
 var me = this;
/**
 * Mostra tela de manutenção de documentos
 */
function js_abreJanelaManutencao(){
  
  var sUrl = "pes1_cadastroDependentes002.php";
  
  me.windowDocumentos                    = new windowAux('windowDocumentos','Manutencão de Dependentes', 850, 600);                      
  me.windowDocumentos.setContent         ("<div id='messageDocumentos'></div><div id='conteudoDocumentos'></div>");
  me.windowDocumentos.setShutDownFunction(function() {  
    if($('windowDocumentos')){
      js_fechaJanelaManutencao();
    }
  });
      
  me.windowDocumentos.show(25, iCentro); 
  
  var sTitle        = "Manutenção de Dependentes";
  var sMessage      = "<B>Matrícula:</B> "+$F('rh01_regist')+"<br>";
      sMessage     += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Servidor:</B>  "+$F('z01_nome');
      
  me.oMessageBoard = new messageBoard('msgboard1',sTitle,sMessage,$('messageDocumentos'));
  me.oMessageBoard . show();
  $('msgboard1').style.width = '';
  var oIframeConteudo = document.createElement("iframe");
      oIframeConteudo.src         = sUrl + "?iMatricula=" + $F('rh01_regist');
      oIframeConteudo.frameBorder = 0;
      oIframeConteudo.id          = 'db_iframe_manutencaoDocumentos';
      oIframeConteudo.name        = 'db_iframe_manutencaoDocumentos';
      oIframeConteudo.scrolling   = 'auto';
      oIframeConteudo.width       = me.windowDocumentos.getWidth() - 50  + 'px';
      
  var Altura = me.windowDocumentos.getHeight() - $('msgboard1').clientHeight - 35;
  
  oIframeConteudo.height      = Altura+'px';
  
  $('conteudoDocumentos').appendChild(oIframeConteudo);
  return false;
}
/**
 * Processa formulário com os dados digitados
 */
function js_processaConsulta() {
  
  if ( $('rh01_regist').value == '' ) {
    alert('Informe a matrícula do funcionário.');
    return false;
  } else {
    
    if($('windowDocumentos')){
      js_fechaJanelaManutencao();
    }
    return js_abreJanelaManutencao();
  }
}

/**
 * Pesquisa dados da matricula conforme variável de visualização
 */
function js_pesquisaMatricula(lShowWindow){
  
  if($('windowDocumentos')){
    js_fechaJanelaManutencao();
  }
  if ( lShowWindow ) {
    
    js_OpenJanelaIframe('',
                        'db_iframe_rhpessoal',
                        'func_rhpessoal.php?funcao_js=parent.js_retornaDadosAncora|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>',
                        'Pesquisa',
                        true);
  } else {
    
    if ($('rh01_regist').value != '') { 
      js_OpenJanelaIframe('', 
                          'db_iframe_rhpessoal', 
                          'func_rhpessoal.php?pesquisa_chave='+$('rh01_regist').value+'&funcao_js=parent.js_retornaDadosDigitacao&instit=<?=(db_getsession("DB_instit"))?>',
                          'Pesquisa',
                          false);
    } else {
      $('z01_nome').value = '';
    }
  }
}

/**
 * Retorna os dados buscados apartir do evento change do campo matricula
 */
function js_retornaDadosDigitacao(sChave,lErro) {

   if($('windowDocumentos')){
     js_fechaJanelaManutencao();
   }
  $('z01_nome').value    = sChave; 
  
  if ( lErro == true ) { 
    
    $('rh01_regist').focus(); 
    $('rh01_regist').value = ''; 
  }
}
/**
 * Retorna os dados buscados da OpenJanelaIframe
 */
function js_retornaDadosAncora(sBusca1, sBusca2) {
  
  if($('windowDocumentos')){
    js_fechaJanelaManutencao();
  }
  
  $('rh01_regist').value = sBusca1;
  $('z01_nome')   .value = sBusca2;
  db_iframe_rhpessoal.hide();
}

function js_fechaJanelaManutencao(){
  me.windowDocumentos.destroy();
}
</script>