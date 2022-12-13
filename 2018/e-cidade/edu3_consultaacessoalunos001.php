<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_aluno");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("e60_emiss");
$db_opcao = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, prototype.js, strings.js, windowAux.widget.js, datagrid.widget.js, dbmessageBoard.widget.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
    <form name="form1" id='frmAcessoEstabelecimento' method="post">
      <center>      
        <div style='display:table;'>
          <fieldset>
          <legend style="font-weight: bold">Consulta Acesso Alunos </legend>
            <table border='0'>
              <tr> 
                <td align="left" nowrap title="<?=$Te60_emiss?>">
                  <b>Período : </b>
                </td>
                <td align="left" nowrap>
                  <? 
                    db_inputdata('datainicial',null,null,null,true,'text',1,"");                 
                    echo "<b> a </b>";
                    db_inputdata('datafinal',null,null,null,true,'text',1,"");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted47_i_codigo?>">
                  <?
                  db_ancora(@$Led61_i_aluno,"js_pesquisaed47_i_codigo(true);",$db_opcao);
                  ?>
                </td>
                <td>
                  <?
                  db_input('ed47_i_codigo',10,@$Ied47_i_codigo,true,'text',3,"");
                  db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,"");
                  ?>
                </td>
              </tr>  
            </table>
          </fieldset>
        </div>
        <input name="btnPesquisar"      id="btnPesquisar"      type="button"  
               value="Pesquisar">         
        <input name="btnAtualizarDados" id="btnAtualizarDados" type="button"  
               value="Atualizar Dados">
        <input name="btnLimparFiltros" id="btnLimpatFiltros" type="button"  
               value="Limpar Filtros">
      </center>
    </form>
  <? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<script>

var sUrlRpc = 'edu03_consultaacessoalunos.RPC.php';

function js_pesquisaed47_i_codigo(mostra) {
  if (mostra) {
    js_OpenJanelaIframe('', 
                        'db_iframe_aluno', 
                        'func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome', 
                        'Pesquisar Alunos',true);
  }
}

function js_mostraaluno1(chave1, chave2 ) {

  document.form1.ed47_i_codigo.value = chave1;
  document.form1.ed47_v_nome.value   = chave2;
  db_iframe_aluno.hide();
}

$('btnAtualizarDados').observe('click', function() {
   
  $('frmAcessoEstabelecimento').disable();
     
  var oParametros = new Object();
  oParametros.exec = 'atualizarDados';
  
  js_divCarregando('Aguarde, Atualizando leituras...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
  var oAjax = new Ajax.Request(sUrlRpc ,
                               { 
                                 method:'post', 
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: function (oResponse) {
                                   
                                   js_removeObj('msgBox');
                                   var oRetorno = eval("("+oResponse.responseText+")");      
                                   alert(oRetorno.message.urlDecode());
                                   $('frmAcessoEstabelecimento').enable();
                                 }
                               }
  
  )
});

$('btnPesquisar').observe('click', function() {
  
  oWindowDadosAcesso = new windowAux('wndDadosAcesso', 
                                     'Lista de Acessos', 
                                     document.body.getWidth() - 10                                   
                                    );
                                    
  oWindowDadosAcesso.setShutDownFunction(function() {
     oWindowDadosAcesso.destroy();
  });
  
  var sContentWindow  = "<div>";
  sContentWindow     += "  <fieldset>";                             
  sContentWindow     += "    <legend><b>Acessos</b></legend>";                              
  sContentWindow     += "     <div id='ctnDataGridDadosAcesso'></div>";                              
  sContentWindow     += "   </fieldset>";                              
  sContentWindow     += "</div>";                              
  sContentWindow     += "<center>";                                  
  sContentWindow     += "  <input type='button' value='Fechar' id='btnFechar'>";                                  
  sContentWindow     += '</center>';                                  
  oWindowDadosAcesso.setContent(sContentWindow);
  
  var sMensagemFiltros  = '';
  if ($F('datainicial') != "") {
    sMensagemFiltros  += '<b>Data Inicial: </b>'+$F('datainicial')+'  ';
  }
  if ($F('datafinal') != "") {
    sMensagemFiltros += '<b>Data Final: </b>'+$F('datafinal')+'  ';
  }
  if ($F('ed47_i_codigo') != "") {
    sMensagemFiltros += '<b>Aluno: </b>'+$F('ed47_i_codigo')+' - '+$F('ed47_v_nome')+'  ';
  }  
  var oMessageBoard    = new DBMessageBoard('msgBoardAcesso', 
                                           'Consulta de Acessos', 
                                           'Filtros para pesquisa: '+sMensagemFiltros,
                                           oWindowDadosAcesso.getContentContainer());
  oMessageBoard.show();
  oWindowDadosAcesso.show();
  
  $('btnFechar').observe('click', function() {
    oWindowDadosAcesso.destroy();
  });
  oDBGridAcessos = new DBGrid('idDBGridAcessos');
  oDBGridAcessos.nameInstance = 'oDBGridAcessos'; 

  var aHeader = new Array('Código', 'Nome', 'Data / Hora', 'Entrada / Saída');
  var aAligns = new Array('center', 'left', 'center', 'center');
  oDBGridAcessos.setCellWidth(new Array('10%', '60%', '20%', '10%'));
  oDBGridAcessos.setCellAlign(aAligns);
  oDBGridAcessos.setHeader(aHeader);
  oDBGridAcessos.setHeight((oWindowDadosAcesso.getHeight()/1.4));
  oDBGridAcessos.show($('ctnDataGridDadosAcesso'));
  js_getDadosAcesso();                                     
}); 


function js_getDadosAcesso() {

  var oParametros         = new Object();
  oParametros.exec        = 'getDadosAcessoAluno';
  oParametros.dataInicial = $F('datainicial');
  oParametros.dataFinal   = $F('datafinal');
  oParametros.iAluno      = $F('ed47_i_codigo');
  js_divCarregando('Aguarde, pesquisando leituras...<br>Esse procedimento pode levar algum tempo.', 'msgBox');
  var oAjax = new Ajax.Request(sUrlRpc,
                               { 
                                 method:'post', 
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: function (oResponse) {
                                   
                                   js_removeObj('msgBox');
                                   var oRetorno = eval("("+oResponse.responseText+")");
                                   oDBGridAcessos.clearAll(true);
                                   oRetorno.acessos.each(function(oAcesso, iSeq) {
                                       
                                       var aRow = new Array();
                                       aRow[0]  = oAcesso.ed101_aluno;
                                       aRow[1]  = oAcesso.ed47_v_nome.urlDecode();
                                       aRow[2]  = oAcesso.ed101_dataleitura+" "+oAcesso.ed101_horaleitura.urlDecode();                                   
                                       aRow[3]  =  oAcesso.ed101_entrada == 't' ?"Entrada":"Saída";                                  
                                       oDBGridAcessos.addRow(aRow);
                                   });
                                   oDBGridAcessos.renderRows();
                                 }
                               });
}

$('btnLimpatFiltros').observe('click', function(){
   $('frmAcessoEstabelecimento').reset();
});

</script>