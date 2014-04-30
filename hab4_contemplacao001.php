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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_depart_classe.php");

$clDBUsuarios = new cl_db_usuarios();
$clDBDepart   = new cl_db_depart(); 

/**
 *  Consulta dados do usuário
 */
$rsDadosUsuario = $clDBUsuarios->sql_record($clDBUsuarios->sql_query_file(db_getsession('DB_id_usuario')));
$oUsuario       = db_utils::fieldsMemory($rsDadosUsuario,0);

$codusuario     = $oUsuario->id_usuario;
$nomeusuario    = $oUsuario->nome;


/**
 *  Consulta dados do departamento
 */
$rsDadosDepto   = $clDBDepart->sql_record($clDBDepart->sql_query_file(db_getsession('DB_coddepto')));
$oDepto         = db_utils::fieldsMemory($rsDadosDepto,0);

$coddepto       = $oDepto->coddepto;
$nomedepto      = $oDepto->descrdepto;


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
  db_app::load("classes/DBViewLancamentoAtributoDinamico.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("widgets/dbcomboBox.widget.js");
  db_app::load("widgets/dbtextField.widget.js");
  db_app::load("widgets/dbtextFieldData.widget.js");
  db_app::load("widgets/windowAux.widget.js");  
?>
<style>
  .field {
    border : 0px;
    border-top: 2px groove white; 
  }
  
 fieldset table tr td:FIRST-CHILD {
   width: 80px;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_gridAtividades();  a=1" bgcolor="#cccccc">
<center>
  <form name="form1" method="post" action="">
    <table  align="center" style="padding-top: 25px;">
      <tr>
        <td>
	        <fieldset>  
	          <table cellpadding="3" border="0" width="100%">
	            <tr>
	              <td>
                  <strong>Usuário :</strong>
                </td>
	              <td>
	                <?
                    db_input("codusuario",10,'',true,'',3);
                    db_input("nomeusuario",50,'',true,'',3);
	                ?>
	              </td>
	            </tr>
	            <tr>
	              <td><strong>Departamento :</strong></td>
	              <td>
                  <?
                    db_input("coddepto",10,'',true,'',3);
                    db_input("nomedepto",50,'',true,'',3);
                  ?>
	              </td>
	            </tr>   
	            <tr>
	              <td>
                  <strong>Período de Envio :</strong>
                </td>
	              <td>
  			          <?
  			            db_inputdata('datai', '', '', '', true, 'text', 1, "");
  		              echo "&nbsp;&nbsp;à&nbsp;&nbsp;"; 
  			            db_inputdata('dataf', '', '', '', true, 'text', 1, "");
  			          ?>
	              </td>
              </tr>                 
	          </table>
	        </fieldset>  
        </td>
      </tr>
      <tr>
         <td align="center">
          <input  type="button" id='pesquisar' value="Pesquisar" onclick="js_consultaAtividades();" />
         </td>
      </tr>      
      <tr>
        <td>
          <fieldset> 
          <legend><strong>Lista de Atividades</strong></legend> 
            <table cellpadding="3" border="0">
              <tr>
                <td>
                  <div id="gridAtividades" style="margin-top: 10px;"> </div>
                </td>
              </tr>            
            </table>
          </fieldset>  
        </td>
      </tr>  
    </table>
  </form>   
</center>   
<?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var sUrlRPC = 'hab4_contemplacao.RPC.php';  
  var oParam  = new Object();
  var windowAtividades = '';

 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridAtividades() {

  oGridAtividades              = new DBGrid('Interessados');
  oGridAtividades.nameInstance = 'oGridAtividades';

  oGridAtividades.setCellWidth(new Array('70px','70px','70px','200px','280px','80px','100px','0px'));
  oGridAtividades.setCellAlign(new Array('center','center','center','left','left','center','center','center'));
  oGridAtividades.setHeader   (new Array( 'Inscrição',
                                          'Processo',
                                          'CGM',
                                          'Nome',
                                          'Programa',
                                          'Data Envio',
                                          'Atividade',
                                          'Obs'
                                        ));
  
  oGridAtividades.aHeaders[7].lDisplayed = false;
  
  oGridAtividades.setHeight(300);
  oGridAtividades.show($('gridAtividades'));
  oGridAtividades.clearAll(true);
}
/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function js_consultaAtividades() {

   var sDataInicial    = $F('datai');
   var sDataFinal      = $F('dataf'); 
   var oParametros     = new Object();

   oParametros.sMethod      = 'consultarAtividades'; 
   oParametros.sDataInicial = sDataInicial;
   oParametros.sDataFinal   = sDataFinal;  
    
   js_divCarregando("Aguarde, Consultando Atividades ...",'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters: 'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaAtividades
                                              });
}
/*
 * funcao para montar a grid com os registros de interessados
 *  retornado do RPC hab4_contemplacao.RPC.php
 *
 */ 
function js_retornoCompletaAtividades(oAjax) {
    
    js_removeObj('msgBox');
    
    
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 1) {
    
      oGridAtividades.clearAll(true);
      
      if ( oRetorno.aAtividades.length == 0 ) {
      
        alert('Nenhum registro encontrado!');
        return false;
      } 
      
      oRetorno.aAtividades.each( 
        
        function (oAtividade) {
               
          aRow     = new Array();  
          aRow[0]  = oAtividade.iInscricao;
          aRow[1]  = "<span class='link_botao' onclick='js_verprocessos("+oAtividade.iCodProcesso+");' >"+oAtividade.iCodProcesso+"</span>";
          aRow[2]  = oAtividade.iCgm;
          aRow[3]  = "&nbsp;"+oAtividade.sNome.urlDecode();
          aRow[4]  = "&nbsp;"+oAtividade.sDescrPrograma.urlDecode();
          aRow[5]  = oAtividade.sData;
          aRow[6]  = "<span class='link_botao'onclick='js_lancarAtividades(\"ativ_"+oAtividade.iInscricao+"\");'>"+oAtividade.sDescrWorkFlowAtiv.urlDecode()+"</span>";
          aRow[7]  = "<span id='ativ_"+oAtividade.iInscricao+"'>"+Object.toJSON(oAtividade)+"</span>";         
          
          oGridAtividades.addRow(aRow);
        }
      );
                       
       oGridAtividades.renderRows(); 
    } 
}
 
                            /////////////  FINAL da GRID ///////////////////////

                            
/* 
 * Função responsavel por montar a janela auxiliar, para ações no link 'Atividades'.  
 */
function js_lancarAtividades(sId) {

  var oAtividade = ($(sId).innerHTML).evalJSON();

  if ( windowAtividades != '' ) {
    windowAtividades.destroy();   
  }

  var sHora     = '<?php echo date('H:i')?>';
  var sData     = '<?php echo date('d/m/Y',db_getsession('DB_datausu'))?>';
   
  oTxtCgm       = new DBTextField('oTxtCgm' ,'oTxtCgm' ,oAtividade.iCgm ,10);
  oTxtNome      = new DBTextField('oTxtNome','oTxtNome',oAtividade.sNome.urlDecode(),50);
  oTxtData      = new DBTextField("oTxtData","oTxtData",sData           ,10);
  oTxtHora      = new DBTextField('oTxtHora','oTxtHora',sHora           ,10);
  oTxtObs       = new DBTextField('oTxtObs' ,'oTxtObs' ,''              ,62);
  oTxtJson      = new DBTextField('oTxtJson','oTxtJson',$(sId).innerHTML,62);
    
  oCboConcluido = new DBComboBox('oCboConcluido','oCboConcluido');
  oCboConcluido.addItem('t','Sim');
  oCboConcluido.addItem('f','Não');

  oTxtCgm.setReadOnly(true);
  oTxtNome.setReadOnly(true);
  oTxtHora.setReadOnly(true);
  oTxtData.setReadOnly(true);
    
  oTxtJson.sStyle = 'display:none';
  
  oTxtObs.setExpansible(true,100,450);

  var sContent  = "  <table align='center' width='98%'>                                                             ";
      sContent += "    <tr>                                                                                         ";
      sContent += "      <td>                                                                                       ";
		  sContent += "    <fieldset>                                                                                   ";
		  sContent += "      <legend>                                                                                   ";
      sContent += "        <b>Dados Gerais :<b>                                                                     ";
      sContent += "      </legend>                                                                                  ";
		  sContent += "      <table align='left'>                                                                       ";
		  sContent += "        <tr>                                                                                     ";
		  sContent += "          <td><b>Usuário :</b></td>                                                              ";
		  sContent += "          <td>                                                                                   ";
      sContent +=              oTxtJson.toInnerHtml();
		  sContent +=              oTxtCgm.toInnerHtml();
		  sContent +=              oTxtNome.toInnerHtml();
		  sContent += "          </td>                                                                                  ";    
		  sContent += "        </tr>                                                                                    ";
		  sContent += "        <tr>                                                                                     ";
		  sContent += "          <td><b>Data :</b></td>                                                                 ";
		  sContent += "          <td>                                                                                   ";
		  sContent +=              oTxtData.toInnerHtml();
		  sContent += "            &nbsp; <b>Hora : </b>                                                                ";
		  sContent +=              oTxtHora.toInnerHtml();    
		  sContent += "          </td>                                                                                  ";    
		  sContent += "        </tr>                                                                                    ";
	    sContent += "        <tr>                                                                                     ";
		  sContent += "          <td><b>Obs :</b></td>                                                                  ";
		  sContent += "          <td>                                                                                   ";
      sContent +=              oTxtObs.toInnerHtml();
		  sContent += "          </td>                                                                                  ";    
		  sContent += "        </tr>                                                                                    ";
		  sContent += "        <tr>                                                                                     ";
		  sContent += "          <td><b> Concluído : </b></td>                                                          ";
		  sContent += "          <td>                                                                                   ";
      sContent +=              oCboConcluido.toInnerHtml();    
		  sContent += "          </td>                                                                                  ";    
		  sContent += "        </tr>                                                                                    ";    
		  sContent += "      </table>                                                                                   ";
		  sContent += "    </fieldset>                                                                                  ";
      sContent += "    <div id='atributos'></div>                                                                   ";
		  sContent += "    <table align='center'>                                                                       ";
		  sContent += "      <tr>                                                                                       ";
		  sContent += "        <td>                                                                                     ";
		  sContent += "          <input type='button' value='Executar Atividade' id='btnExecutaAtividade' \>            ";
		  sContent += "        </td>                                                                                    ";
		  sContent += "      </tr>                                                                                      ";      
		  sContent += "    </table>                                                                                     ";
      sContent += "      </td>                                                                                      ";        
      sContent += "    </tr>                                                                                        ";
      sContent += "  </table>                                                                                       ";
		       
		  windowAtividades = new windowAux('atividades', '&nbsp; Lançamento de Atividades', 600, 400);
			windowAtividades.setContent(sContent);
        
      var oMessage = new DBMessageBoard('msgboard1', 
                                        'Lançamento de Atividades',
                                        'Lançamento  '+ oAtividade.sDescrWorkFlowAtiv.urlDecode(),
                                        $("windowatividades_content"));
      oMessage.show();        
        
			windowAtividades.show(60, 400);
				
	  	windowAtividades.setShutDownFunction(function (){     
		    windowAtividades.destroy();  
        windowAtividades = '';    
		  });
        
        
      if ( oAtividade.iGrupoAtributos != '' ) {
       
        $('btnExecutaAtividade').observe('click',function(event) {
          oAtributoDinamico.save();
        })
        
        oAtributoDinamico = new DBViewLancamentoAtributoDinamico();
        oAtributoDinamico.setSaveCallBackFunction( js_executarAtividade );
        oAtributoDinamico.setAlignForm('left');
        oAtributoDinamico.setParentNode($('atributos'));
        oAtributoDinamico.newAttribute(oAtividade.iGrupoAtributos);
      
      } else {
        
        $('btnExecutaAtividade').observe('click',function(event) {
          js_executarAtividade(null);
        })          
      }
}

function js_executarAtividade(iGrupoValorAtributo) {

  var oParametros = new Object();
  
  var oAtividade = ($('oTxtJson').value).evalJSON();
  
  oParametros.sMethod             = 'salvarAtividade'; 
  oParametros.sObs                = $('oTxtObs').value;
  oParametros.lConcluido          = $('oCboConcluido').value;
  oParametros.iWorkFlowAtiv       = oAtividade.sCodWorkFlowAtiv;
  oParametros.iCodProcesso        = oAtividade.iCodProcesso;
  oParametros.iCgm                = oAtividade.iCgm;
  oParametros.iCodInteresse       = oAtividade.iCodInteresse;
  oParametros.iGrupoValorAtributo = iGrupoValorAtributo;
  
    
  js_divCarregando("Executando Atividade...",'msgBox');
  
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                           {method: "post",
                                            parameters: 'json='+Object.toJSON(oParametros),
                                            onComplete: js_retornoExecutarAtividades
                                           });  
}


function js_retornoExecutarAtividades(oAjax) {
    
  js_removeObj('msgBox');
    
  var oRetorno = eval("("+oAjax.responseText+")");

  alert(oRetorno.sMsg.urlDecode());
    
  if (oRetorno.iStatus == 2) {
    return false;
  } else {
    windowAtividades.destroy();  
    windowAtividades = '';     
    js_consultaAtividades();
  }
}


/*
 * função responsavel pela ação do link processos
 * enviamos o codigo do processo para a consulta
 * @param {integer} iProcesso codigo do processo selecionado  
 */
function js_verprocessos(iProcesso) { 

 js_OpenJanelaIframe('top.corpo', 'db_iframe', 'pro3_conspro002.php?codproc='+iProcesso, 'Consulta de Processos', true);
}


</script>