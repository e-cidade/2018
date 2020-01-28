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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("dbtextField.widget.js");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js");   
?>
<style>

 .field {
   border : 0px;
   border-top: 2px groove white; 
 }
  
 fieldset.field table tr td:FIRST-CHILD {
   width: 150px;
 	 white-space: nowrap;
 }
   
 .link_botao {
   color: blue;
   cursor: pointer;
   text-decoration: underline;
 }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_gridInteressados(); js_montaProgramas(); a=1" bgcolor="#cccccc">
<center>

<div id='ficha' style="position: absolute; float:left;background-color:#ccc; width: 100%; height: 100%; display: none; padding-top: 10px;">
</div>

<form name="form1" method="post" action="">
  <fieldset style="margin-top:50px; width: 700px;">
    <legend><strong>Seleção de Interessados</strong></legend>
    <table  align="center" width="100%" cellpadding="5" border="0">
      <tr>
        <td colspan="2">
          <fieldset  class="field"> 
          <legend><strong>Filtros</strong></legend> 
            <table cellpadding="3" border="0" width="100%">
              <tr>
                <td><strong>Grupo :</strong></td>
                <td>
				          <?
				            $sSqlGrupoPrograma = "select ht03_sequencial, ht03_descricao from habitgrupoprograma  order by ht03_sequencial";
				            $rsGrupoPrograma   = db_query($sSqlGrupoPrograma);
				            db_selectrecord("ht03_habittipogrupoprograma", $rsGrupoPrograma, true, 1,"style='width:100%'",'','','','js_montaProgramas();',1);          
				          ?> 
                </td>
              </tr>
              <tr>
                <td><strong>Programa :</strong></td>
                <td>
                  <select id='programa' style="width:100%" onchange="js_completaSelecionado(this.value);">
                    <option value=" ">Nenhum</option>
                  </select>
                </td>
              </tr>              
            </table>
          </fieldset>  
        </td>
      </tr>
    
      <tr>
         <td >
          
          <fieldset class="field">
            <table cellpadding="3" border="0" width="100%">
              <tr>
                <td >
			            <b>Programa p/ Inscrição :</b>
			          </td>
			          <td>  
			            <select id='programa_inscricao' style="width:100%">
			              <option value=" ">Nenhum</option>
			            </select>
			          </td>
			         </tr>
			            
            
           </table> 
          </fieldset>  
            
         </td>
      </tr>
      <tr>
         <td colspan="2">
          <input style="margin-left: 175px;" type="button" id='pesquissa_interessados' value="Pesquisar Interessados" 
                                                                                      onclick="lista_interessados();" />
           <input type="button" style="margin-left: 10px;" id='gerar_inscricao' value="Gerar Inscrição" 
                                                                                       onclick="js_gerarInscricao();" />
         </td>
      </tr>      
      
      <tr>
        <td colspan="2">
          <fieldset> 
          <legend><strong>Lista de Interessados</strong></legend> 
            <table cellpadding="3" border="0">
              <tr>
                <td>
                  <div id="ctnGridInteressados" style="margin-top: 10px;"> </div>
                </td>
              </tr>            
            </table>
          </fieldset>  
        </td>
      </tr>  
    </table>
  </fieldset> 
</form>   



</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var sUrlRPC = 'hab4_selecaoinscricao.RPC.php';  
  var oParam  = new Object();
/*  
 *  função para montar o select de programas a partir do grupo selecionado
 *  
 */  
function js_montaProgramas() {

  oGridInteressados.clearAll(true);
  $("programa").options.length           = 0;
  $("programa_inscricao").options.length = 0;
  $("programa_inscricao").options[0]     = new Option('Nenhum', '');
  $("programa").options[0]               = new Option('Nenhum', '');
  $("programa_inscricao").disabled       = false;
  var iGrupo                             = $F('ht03_habittipogrupoprograma');
  var oParametros                        = new Object();
  var msgDiv                             = "Carregando Lista de Programas \n Aguarde ...";
  oParametros.exec                       = 'Programas';  
  oParametros.iGrupo                     = iGrupo;   
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoProgramas
                                             });   
}
/*
 * funcao para montar inserir os programas no select
 *
 */ 
function js_retornoProgramas(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    var iProgramas = 1; // responsavel pelo indice de options que será incluido no select de programas
    
    if (oRetorno.status == 1) {
      
      if ( oRetorno.dados.length == 0 ) {
      
        //alert('Nenhum Programa encontrado!');
        return false;
      } 
      oRetorno.dados.each( 
           function (oDado, iInd) {       

              aRow = new Array();                                                              
              aRow[0]  = oDado.sequencial;
              aRow[1]  = oDado.descricao.urlDecode();
             
              $("programa").          options[iProgramas] = new Option(aRow[1], aRow[0]);
              $("programa_inscricao").options[iProgramas] = new Option(aRow[1], aRow[0]);              
              iProgramas++;              
           });
    }
}

/*
  preenche o select "programa p/ inscrição" , com o valor do programa selecionado no filtro
*/
function js_completaSelecionado(iCod) {

  var iCodPrograma                       = iCod;
  $("programa_inscricao").options.length = 0;
  var sNome                              = $('programa').options[$('programa').selectedIndex].innerHTML;
  $("programa_inscricao").options[0]     = new Option(sNome, iCodPrograma);
  $("programa_inscricao").disabled       = true;

}


 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridInteressados() {

  oGridInteressados = new DBGrid('Interessados');
  oGridInteressados.nameInstance = 'oGridInteressados';
  oGridInteressados.setCheckbox(0);
  oGridInteressados.allowSelectColumns(true);
  oGridInteressados.setCellWidth(new Array( '70px' ,
                                            '280px',
                                            '100px',
                                            '100px',
                                            '100px',
                                            '200px',
                                            '280px'
                                           ));
  
  oGridInteressados.setCellAlign(new Array( 'left'  ,
                                            'left'  ,
                                            'center',
                                            'left'  ,
                                            'center',
                                            'left'  ,
                                            'left'
                                           ));
  
  
  oGridInteressados.setHeader(new Array( 'CGM',
                                         'Nome',
                                         'CPF',
                                         'Situação CPF',
                                         'Consulta',
                                         'Grupo',
                                         'Programa'
                                        ));
                                       
  oGridInteressados.aHeaders[6].lDisplayed = false; 
  oGridInteressados.aHeaders[7].lDisplayed = false; 

  oGridInteressados.setHeight(300);
  oGridInteressados.show($('ctnGridInteressados'));
  oGridInteressados.clearAll(true);
  
}
/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function lista_interessados() {


   var iGrupoInteresse    = $F('ht03_habittipogrupoprograma');
   var iProgramaInteresse = $F('programa');
   var iProgramaInscricao = $F('programa_inscricao');
    
   var msgDiv             = "Aguarde ...";
   var oParametros        = new Object();
   
   oParametros.exec               = 'Interessados';
   oParametros.iGrupoInteresse    = iGrupoInteresse;
   oParametros.iProgramaInteresse = iProgramaInteresse;   
   oParametros.iProgramaInscricao = iProgramaInscricao;
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaInteressados
                                             });
                                            
}
/*
 * funcao para montar a grid com os registros de interessados
 *  retornado do RPC
 *
 */ 
function js_retornoCompletaInteressados(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      oGridInteressados.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        alert('Nenhum registro encontrado!');
        return false;
      } 
      oRetorno.dados.each( 
                    function (oDado, iInd) {       

                        var aRow    = new Array();  
                            aRow[0] = oDado.cgm;
                            aRow[1] = oDado.nome.urlDecode();
                            aRow[2] = oDado.cpf;
                            aRow[3] = oDado.situacao.urlDecode();                            
                            aRow[4] = "<span class='link_botao' onclick='js_VerFicha("+aRow[0]+");' >"+oDado.consulta.urlDecode()+"</span>";
                            aRow[5] = oDado.grupo.urlDecode();
                            aRow[6] = oDado.programa.urlDecode();                                                   
                            oGridInteressados.addRow(aRow);
                       });
      oGridInteressados.renderRows(); 
    } 
}

 /*
  * Inicia o envio dos checkbox selecionados no grid
  */
function js_gerarInscricao() {

   var iGrupoInteresse    = $F('ht03_habittipogrupoprograma');
   var iProgramaInteresse = $F('programa');
   var iProgramaInscricao = $F('programa_inscricao'); 
   
   var msgDiv             = "Aguarde ...";
   
   var aListaCheckbox     = oGridInteressados.getSelection();
   var aListaInteressados = new Array();
   
   aListaCheckbox.each(
     function ( aRow ) {
       aListaInteressados.push(aRow[0]);
    }
   );
   
   /*
    * Definimos as propriedades do objeto que será postado para o RCP
   */
   var oParametros                = new Object();
   oParametros.exec               = 'GerarInscricao';
   oParametros.sListaInteressados = aListaInteressados.join(',');
   oParametros.iGrupoInteresse    = iGrupoInteresse;
   oParametros.iProgramaInteresse = iProgramaInteresse;   
   oParametros.iProgramaInscricao = iProgramaInscricao;
   

   if (iProgramaInscricao == null || iProgramaInscricao == '' || iProgramaInscricao == 0 || iProgramaInscricao == '0' ) {
   
     alert('Selecione um programa !!');
     return false;
   } else if (oParametros.sListaInteressados == null || oParametros.sListaInteressados == "") {
   
      alert('Nenhum Candidato Selecionado !! ');
      return false;
   } else {

     js_divCarregando(msgDiv, 'msgBox');
     var oAjaxArquivos  = new Ajax.Request(sUrlRPC,
                                               {method: "post",
                                                parameters:'json='+Object.toJSON(oParametros),
                                                onComplete: retorno_geraInscricao
                                               });
  }
}
 /*
  * Trata o Retorno do Processamento de Interessados
  */
function retorno_geraInscricao(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");

   js_removeObj('msgBox');
    
   alert(oRetorno.message.urlDecode());
   
   if (oRetorno.status == 2) {
     return false;
   } else {
     oGridInteressados.clearAll(true);		
   }

}


///////  visualisação ficha socioeconomica

function js_VerFicha(iCgm) {

   var oParametros  = new Object();
   oParametros.exec = 'Ficha';
   oParametros.iCgm = iCgm;
   var msgDiv       = "Carregando Dados da Ficha \n Aguarde...";

     js_divCarregando(msgDiv, 'msgBox');
     var oAjaxArquivos  = new Ajax.Request(sUrlRPC,
                                               {method: "post",
                                                parameters:'json='+Object.toJSON(oParametros),
                                                onComplete: retorno_Ficha
                                               });

  
 
}
function retorno_Ficha(oAjax) {
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
      
      if ( oRetorno.dados.length == 0 ) {
      
        js_removeObj('msgBox');
        alert('Nenhum registro encontrado!');
        return false;
      } else {
      
          oRetorno.dados.each( 
                        function (oDado, iInd) {       
      
                                aRow     = new Array();  
                                aRow[0]  = oDado.iAval;
                                aRow[1]  = oDado.iGrupo; 
                           });
      }
    }
    
  js_removeObj('msgBox');
  oFicha = new dbViewAvaliacao(aRow[0], aRow[1]);
  oFicha.mostrarMensagensSucesso(false);
  oFicha.show();
  oFicha.disable();  
}

</script>