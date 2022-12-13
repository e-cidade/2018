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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_db_depart_classe.php"));
include(modification("classes/db_db_usuarios_classe.php"));

$cldb_depart   = new cl_db_depart();
$cldb_usuarios = new cl_db_usuarios();

$codUsuario = db_getsession("DB_id_usuario");
$codDepart  = db_getsession("DB_coddepto");

$rsUsuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($codUsuario,"nome as nomeusuario"));
if ($cldb_usuarios->numrows > 0) {
	db_fieldsmemory($rsUsuario,0);
}

$rsDepart  = $cldb_depart->sql_record($cldb_depart->sql_query($codDepart,"descrdepto as nomedepart"));
if ($cldb_depart->numrows > 0) {
	db_fieldsmemory($rsDepart,0);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/geradorrelatorios.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<style>
.marcado{ 
          border-colappse:collapse;
          border-right:1px inset black;
          border-bottom:1px inset black;
          cursor:normal;
          font-family: Arial, Helvetica, sans-serif;
          font-size: 12px;
          background-color:#CCCDDD
        }
</style>
<body bgcolor="#cccccc">
<center>
  <form name="form1">
    <table style="padding-top:25px;">
      <tr> 
        <td> 
	      	<fieldset>
  	    	  <legend align="center">
 	  	  	    <b>Gerador de Relatórios</b>
  	  	    </legend>
		        <table>
					    <tr>
					  	  <td valign="top">
					  	  	<fieldset>
					  	  	  <legend align="center">
					  	  	  	<b>Dados Usuário</b>
					  	  	  </legend>
					  	  	  <table>
					  	  	  	<tr>
					  	  	  	  <td>
					  	  	  	  	<b>Usuário:</b>
					  	  	  	  </td>
					  	  	  	  <td>
					  	  	  	  	<?
			   		    	  	  	  db_input("codrelatorio",40,"",true,"hidden",3,"");
					  	  	  	  	  db_input("codUsuario"  ,10,"",true,"hidden",3,"");
					  	  	  	  	  db_input("nomeusuario" ,40,"",true,"text",3,"");
					  	  	  	  	?>
					  	  	  	  </td>
						  	  		</tr>
						  	  	  	<tr>
						  	  	  	  <td>
						  	  	  	  	<b>Departamento:</b>
						  	  	  	  </td>
						  	  	  	  <td>
						  	  	  	  	<?
						  	  	  	  	  db_input("codDepart" ,10,"",true,"hidden",3,"");
				        						  db_input("nomedepart",40,"",true,"text",3,"");
						  	  	  	  	?>
						  	  	  	  </td>
						  	  		</tr>
					  	  	  </table>
					  	  	</fieldset>
					  	  </td>
					  	</tr>
					  	<tr>
					  	  <td>
					  	    <fieldset>
			  		  	    <table align="center">
				     		  	  <td width="5%">
			    			  	  	 <input type="radio" name="relTipoRad" onClick='js_pesquisaRelatorios("Usuario");' >
					    	  	  </td>
			  		  	      <td width="30%">
					  	         	<b>Usuário</b>
					  	        </td>
						  	      <td width="5%">
						  	        <input type="radio" name="relTipoRad" onClick='js_pesquisaRelatorios("Depto");'  >
						  	      </td>
			  		  	      <td width="30%">
			  		  	      	<b>Departamento</b>
				 	   	        </td>			  
				 	   	        <td width="5%">
						  	        <input type="radio" name="relTipoRad" onClick='js_pesquisaRelatorios("Publico");' title="Relatórios não vinculados a usuários e departamentos." checked >
						  	      </td>
			  		  	      <td width="25%">
			  		  	      	<b>Público</b>
				 	   	        </td>			  	  	  
					  	      </table>
					  	    </fieldset>
					  	  </td>
					  	</tr>
		  	      <tr>  
					  	  <td valign="top">
					  	    <fieldset>
							      <legend align="center">
							  	    <b>Relatórios Salvos</b>
							      </legend>
							      <table cellspacing="0" style="border:0px inset white; width:550px;" >
							      <thead style="display:block; position:absolute; overflow:none;">
			      				 	<tr>
										 	  <th class="table_header" width="15px" ><b>&nbsp;</b></th>
										 	  <th class="table_header" width="75px" ><b>Código</b></th>
									      <th class="table_header" width="436px"><b>Nome Relatório</b></th>
									      <th class="table_header" width="12px" ><b>&nbsp;</b></th>
							        </tr>
							        </thead>
							        <tbody id="relatoriosSalvos" style="height:200px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:16px; display:block;">
									    </tbody>
			    				  </table>
									  <table align="center">
									    <tr>
									      <td>
										      <input name="imprimir" type="button" value="Imprimir" 		  onClick="js_impRel();"/>
										    </td>
									      <td>
										      <input name="alterar"  type="button" value="Alterar"  		  onClick="js_alteraRel();"/>
										    </td>					  
									      <td>
										      <input name="excluir"  type="button" value="Excluir"  		  onClick="js_excluiRel();"/>
										    </td>
									      <td>
										      <input name="novo" 	   type="button" value="Novo Relatório" onClick="js_novoRel();"/>
										    </td>
					              <td>
					                <input name="menu"     type="button" value="Lançar Menu"    onClick="js_lancarMenu();"/>
					              </td>
					              <td>
					                <input name="importar" id="importar" type="button" value="Importar" onClick="js_importarRelatorio();"/>
					              </td>                                                                 
					              <td>                                                                  
					                <input name="exportar" id="exportar" type="button" value="Exportar" onClick="js_exportarRelatorio();"/>
					              </td>
									    </tr>	
						  	    </table>
					        </fieldset>			  	
			          </td>
		          </tr>
		        </table>
		      </fieldset>
        </td>
      </tr>
    </table>
  </form>
</center>
<div id='uploadIframeBox' style='display: none'></div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  oWindowUpload = null;
  
	js_pesquisaRelatorios('Publico');

	sUrl = 'sys4_consultaviewRPC.php';
	
	function js_exportarRelatorio() {
		
		js_divCarregando(_M('configuracao.configuracao.sys4_geradorrelatorio001.mensagem_ajax_exportando_arquivo'),'msgBox');

		var sQuery  = "tipo=exportarRelatorio";
		    sQuery += "&iCodigoRelatorio="+$F('codrelatorio');

		var oAjax   = new Ajax.Request(sUrl, 
				                           {
                                   method: 'post', 
                							     parameters: sQuery, 
                                   onComplete: js_retornaExportacao 
              										 });
	}

	function js_retornaExportacao (oAjax) {
		
		js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");

		if (oRetorno.iStatus == 1) {
      js_arquivo_abrir(oRetorno.sNomeArquivo);
		}	else {

			alert(oRetorno.sMensagem.urlDecode());
			return false;
		}	

	}

	function js_importarRelatorio() {
		
	  require_once("scripts/widgets/dbtextField.widget.js");
	  require_once("scripts/widgets/DBAncora.widget.js");

	  if ( oWindowUpload && oWindowUpload instanceof windowAux) {
			oWindowUpload.destroy();
	  }  
	  
	  var sContent  = "";
	      sContent += "<div id='mensagem'></div>                                                                                                               ";
	      sContent += "<div>                                                                                                                                   ";
	      sContent += "  <center class='container'>                                                                                                            ";
	      sContent += "  	 <input type='hidden' name='caminho_arquivo' id='caminho_arquivo' />                                                                 ";
	      sContent += "    <fieldset style='height: 100px;'>                                                                                                   ";
	      sContent += "    	 <legend>Importar Relatório:</legend>                                                                                              ";
	      sContent += "    	 <table class='form-container'>                                                                                                    ";
	      sContent += "    	   <tr>                                                                                                                            ";
	      sContent += "    	     <td width='200'>                                                                                                              ";
	      sContent += "    	       <b>Arquivo para Importação:</b>                                                                                             ";
	      sContent += "    	     </td>                                                                                                                         ";
	      sContent += "    	     <td>                                                                                                                          ";
	      sContent += "            <form name='form_arquivo' id='form_arquivo' method='post' enctype='multipart/form-data'>                                    ";
	      //sContent += "            	 <input type='text' readonly name='arquivo_mostra' id='arquivo_mostra' style='float:left; width: 375px; cursor:default;'/> "; 
	      //sContent += "              <img id='imagem_pesquisa' src='imagens/tree/folderopen.gif' style='float: left;' title='Selecione o arquivo xml para '/>                                        ";
	      sContent += "            	 <input type='file' size='30' name='arquivo' id='arquivo'/>                                                    ";
	      sContent += "            </form>                                                                                                                     ";
	      sContent += "    	     </td>                                                                                                                         ";
	      sContent += "    	   </tr>                                                                                                                           ";
	      sContent += "    	   <tr>                                                                                                                            ";
        sContent += "    	     <td id='ancoraGrupoRelatorio'></td>                                                                                           ";
        sContent += "    	     <td id='inputGrupoRelatorio'></td>                                                                                            ";
	      sContent += "    	   </tr>                                                                                                                           ";
	      sContent += "    	   <tr>                                                                                                                            ";
        sContent += "    	     <td id='ancoraTipoRelatorio'></td>                                                                                            ";
        sContent += "    	     <td id='inputTipoRelatorio'></td>                                                                                             ";
	      sContent += "    	   </tr>                                                                                                                           ";
	      sContent += "    	 </table>                                                                                                                          ";
	      sContent += "    </fieldset>                                                                                                                         ";
	      sContent += "    <input type='button' onclick='js_envioArquivo()' value='Enviar' />                                                                  ";
	      sContent += "  </center>                                                                                                                             ";
	      sContent += "</div>                                                                                                                                  ";
                                                                                                                            
	  oWindowUpload = new windowAux('windowUpload', 'Importação de Arquivo', 650, 300);
    
	  oWindowUpload.setContent(sContent);
    
	  var w = ((document.body.clientWidth - 400) / 2);
	  var h = ((document.body.clientHeight / 2) - 200);
	  
		oWindowUpload.setShutDownFunction(function(){
			oWindowUpload.hide();
		});

	  oWindowUpload.show();

		oMessageBoard          = new DBMessageBoard('messageBoardUpload',
		                                            _M('configuracao.configuracao.sys4_geradorrelatorio001.mensagem_message_board_titulo'),
		                                            _M('configuracao.configuracao.sys4_geradorrelatorio001.mensagem_message_board_texto_usuario'),
		                                             $('mensagem'));

		
    /**
     * Cria pos Inputs e Ancoras
     */

    oAncoraGrupoRelatorio  = new DBAncora("Grupo Relatório:", "#");
    oAncoraTipoRelatorio   = new DBAncora("Tipo Relatório:" , "#");
    
    oInputIdGrupoRelatorio = new DBTextField("id_grupo_relatorio", "oInputIdGrupoRelatorio", '', 10);
    oInputIdTipoRelatorio  = new DBTextField("id_tipo_relatorio",  "oInputIdTipoRelatorio", '', 10); 
    oInputGrupoRelatorio   = new DBTextField("grupo_relatorio", "oInputGrupoRelatorio", '', 40);   
    oInputTipoRelatorio    = new DBTextField("tipo_relatorio",  "oInputTipoRelatorio", '', 40);
    oInputGrupoRelatorio.setReadOnly(true);
    oInputTipoRelatorio .setReadOnly(true);
    
    oAncoraGrupoRelatorio.onClick(function(){
      js_pesquisaGrupo(true);
      Jandb_iframe_grupo.style.zIndex = '999999';
    });   

    oAncoraTipoRelatorio.onClick(function(){
    	js_pesquisaTipo(true);
    	Jandb_iframe_tipo.style.zIndex = '999999';
    });   

    
    oAncoraGrupoRelatorio.show( $('ancoraGrupoRelatorio') );
    oAncoraTipoRelatorio.show( $('ancoraTipoRelatorio') );

    oInputIdGrupoRelatorio.show( $('inputGrupoRelatorio') );
    oInputIdTipoRelatorio.show( $('inputTipoRelatorio') );
    oInputGrupoRelatorio.show( $('inputGrupoRelatorio') , true );
    oInputTipoRelatorio.show( $('inputTipoRelatorio'), true);

    oInputIdGrupoRelatorio.getElement().onchange = function () {
    	js_pesquisaGrupo(false);
    };
    
    oInputIdTipoRelatorio.getElement().onchange = function () {
    	js_pesquisaTipo(false);
    };
    
	}

  function js_pesquisaGrupo(mostra){
    
    if ( mostra ) {
  
      js_OpenJanelaIframe('','db_iframe_grupo','func_db_gruporelatorio.php?funcao_js=parent.js_mostragrupo|db13_sequencial|db13_descricao','Pesquisa Grupo Relatório',true);
      
    } else {
      if ( oInputIdGrupoRelatorio.getValue() != '' ) {
        js_OpenJanelaIframe('','db_iframe_grupo','func_db_gruporelatorio.php?pesquisa_chave=' + oInputIdGrupoRelatorio.getValue() + '&funcao_js=parent.js_mostragrupoHide','Pesquisa',false);
      } else {
      	oInputGrupoRelatorio.setValue('');
      }
    }
  }

  function js_mostragrupoHide(chave,erro) {
  	
    oInputGrupoRelatorio.setValue(chave);
    
    if ( erro ) {
  	  
  	  oInputIdGrupoRelatorio.getElement().focus();
  	  oInputIdGrupoRelatorio.setValue('');
    }
  }

  function js_mostragrupo(chave1,chave2) {
  	
    oInputIdGrupoRelatorio.setValue(chave1);
    oInputGrupoRelatorio.setValue(chave2);
    db_iframe_grupo.hide();
  }

  function js_pesquisaTipo(mostra){
  	
    if ( mostra ) {
      js_OpenJanelaIframe('','db_iframe_tipo','func_db_tiporelatorio.php?funcao_js=parent.js_mostratipoLookUp|db14_sequencial|db14_descricao','Pesquisa',true);
    } else {
  	  
      if (oInputIdTipoRelatorio.getValue() != '') {
        js_OpenJanelaIframe('','db_iframe_tipo','func_db_tiporelatorio.php?pesquisa_chave='+oInputIdTipoRelatorio.getValue()+'&funcao_js=parent.js_mostraTipoHide', 'Pesquisa Tipo Relatório', false);
      } else {
        oInputTipoRelatorio.setValue('');
      }
    }
  }
  
  function js_mostraTipoHide(chave,erro) {
  
    oInputTipoRelatorio.setValue(chave);
    
    if ( erro ) {
  	  
  	  oInputIdTipoRelatorio.getElement().focus();
  	  oInputIdTipoRelatorio.setValue('');
    }
  }

  function js_mostratipoLookUp(chave1,chave2) { 
  
  	oInputIdTipoRelatorio.setValue(chave1);
  	oInputTipoRelatorio.setValue(chave2);
    db_iframe_tipo.hide();
  }
  
	function js_pesquisaRelatorios(sPesquisa){
	  
	  js_divCarregando('Aguarde, Consultando Relatórios...','msgBox');

	  $("exportar").disabled  = true;
	  $('codrelatorio').value = '';
	  
		var ConsultaTipo = 'consultaRelatorios';
   	  
   	var sQuery  = "tipo="+ConsultaTipo;
   		  sQuery += "&sTipoPesquisa="+sPesquisa;
   	    sQuery += "&codDepto="+document.form1.codDepart.value;
   	    sQuery += "&idUsuario="+document.form1.codUsuario.value;
   	      
   	var url     = 'sys4_consultaviewRPC.php';
   	var oAjax   = new Ajax.Request( url, { method     : 'post', 
                                           parameters : sQuery, 
                                           onComplete : js_carregaGrid });
	}
	
	function js_carregaGrid(oAjax){
	  
	  var aRetorno = eval("("+oAjax.responseText+")");
	  var sLinha = "";
	  
	  if (aRetorno.erro == true){
	  
	  	js_removeObj("msgBox");
	  	$('relatoriosSalvos').innerHTML = "";
	  	return false;
	  	
	  } else {
	  	
	  	 var objRelatorios = aRetorno.objRel;
	  	
	     if (objRelatorios) {
	  
	  	  for ( var iInd = 0; iInd < objRelatorios.length; iInd++ ) {
			    with (objRelatorios[iInd]) {
			  	  sLinha += " <tr id='"+idrel+"' class='linhagrid' >";		  	
			  	  sLinha += "   <td style='width:15px;'  class='linhagrid' onClick='js_marcaLinha(\""+idrel+"\");' style='text-align:center;'><a href='#' onClick='js_infoRel("+idrel+"); return false;' >MI</a></td> ";
			  	  sLinha += "   <td style='width:75px;'  class='linhagrid' onClick='js_marcaLinha(\""+idrel+"\");' style='text-align:center;'>"+idrel+"</td> ";
			  	  sLinha += "   <td style='width:436px;' class='linhagrid' onClick='js_marcaLinha(\""+idrel+"\");' style='text-align:left;'>"+nomerelatorio.urlDecode()+"</td> ";
			  	  sLinha += " </tr> ";		  		
			    }	  	
	  	  }
	    }
	  
	    sLinha += "<tr><td style='height:100%;'>&nbsp;</td></tr>";
	    $('relatoriosSalvos').innerHTML = sLinha;
	    js_removeObj("msgBox");
	  }
	}


	function js_infoRel(iCodRel){
	  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inforel','sys4_inforelatorio001.php?codrel='+iCodRel,'Pesquisa',true);
	}
   
   
  function js_marcaLinha(idRel){

    var iNroLinhas = $("relatoriosSalvos").rows.length;

    $('codrelatorio').value = idRel;
  	  
  	for (var i=0; i < iNroLinhas; i++) {
  	 	$("relatoriosSalvos").rows[i].className = 'linhagrid';
  	 	$("exportar").disabled = false;
  	}
  	  
  	$(idRel).className = 'marcado'; 

	}
	
	
	function js_novoRel(){
	  location.href = "sys4_escolhetiporel001.php";	
	}
	

	
	function js_excluiRel(){
	
	  var objMarcado = js_getElementbyClass(document.all,'marcado');
	  
	  if ( objMarcado == ""){
			alert("Nenhum relatório selecionado!");
			return false;	    
	  } else {
	  
	    if (confirm("Deseja realmente excluir relatório?"))  {       
      	  
        js_divCarregando('Aguarde, excluindo relatório...','msgBox');
	  
   		  var ConsultaTipo = 'excluirRelatorio';
   	  
   	 	  var sQuery  = "tipo="+ConsultaTipo;
   	     	  sQuery += "&codRelatorio="+objMarcado[0].id;
   	      
   	 	  var url     = 'sys4_consultaviewRPC.php';
   	 	  var oAjax   = new Ajax.Request( url, {
                                               method: 'post', 
                                               parameters: sQuery, 
                                               onComplete: js_retornoExclusao 
                                             }
                                      );
      }
    } 			
	}
	
	function js_retornoExclusao(oAjax){
	
	  js_removeObj("msgBox");
	  
	  var aRetorno = eval("("+oAjax.responseText+")");
	  
 	  if (aRetorno.erro == true){
  		alert(aRetorno.msg.urlDecode());
  		return false;
 	  } else {
 	  	alert("Relatório excluído com sucesso!");
  		js_pesquisaRelatorios()
 	  }
		
	}
	
	function js_impRel(){
	  
	  var objMarcado = js_getElementbyClass(document.all,'marcado');
	  
    if (objMarcado.length == 0 ) {
      alert("Nenhum relatório selecionado!");
      return false;
    } 
    
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_lancarmenu',
                        'sys4_geradorteladinamica001.php?lEsconderMenus=true&iCodRelatorio='+objMarcado[0].id,
                        'Filtros',true);
    
	}
	
	function js_alteraRel(){
	  
	  var objMarcado = js_getElementbyClass(document.all,'marcado');
		
	  if (objMarcado.length == 0 ) {
	  	alert("Nenhum relatório selecionado!");
	  	return false;
	  }	

    var iCodRelatorio = objMarcado[0].id;
	  var sUrl          = 'sys4_consultaviewRPC.php';    

      
    js_divCarregando('Aguarde...','msgBox');
    
    var sQuery  = 'tipo=carregarRelatorio';
        sQuery += '&iCodRelatorio='+iCodRelatorio;
    
    var oAjax        = new Ajax.Request( sUrl, {
                                                 method: 'post', 
                                                 parameters: sQuery, 
                                                 onComplete: js_retornoInclusao
                                               }
                                        );
	
	} 
	
	function js_retornoInclusao(oAjax){
	   
	  js_removeObj('msgBox');
	
	  var sExpReg  = new RegExp('\\\\n','g');
	  var aRetorno = eval("("+oAjax.responseText+")");
	    
	  if ( aRetorno.erro ) {
	     alert(aRetorno.msg.urlDecode().replace(sExpReg,'\n'));
	     return false;
	  } else {
 	     document.location.href = 'sys4_confrelatorio001.php?lSql='+aRetorno.lSql;
	  }  
	
	}
	
	function js_lancarMenu(){
	
    var sAcao = "cadastrarMenu";
    var objMarcado = js_getElementbyClass(document.all,'marcado');    
    if (objMarcado.length == 0 ) {
      alert("Nenhum relatório selecionado!");
      return false;
    } 
    
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_lancarmenu',
                        'sys4_mostraMenus001.php',
                        'Lancar Menu',true);
	
     
  }
  
  
  function js_CadastrarMenu(iItemPai,iModulo){
  
    db_iframe_lancarmenu.hide();
    
	  var sAcao = "cadastrarMenu";
	  var objMarcado = js_getElementbyClass(document.all,'marcado');    
    if (objMarcado.length == 0 ) {
      alert("Nenhum relatório selecionado!");
      return false;
    } 
    
    var iCodRelatorio = objMarcado[0].id;
    
	  var sQuery  = "tipo="+sAcao;
        sQuery += "&iCodRelatorio="+iCodRelatorio;
        sQuery += "&itemPai="+iItemPai;
        sQuery += "&iModulo="+iModulo;
    var url     = "sys4_consultaviewRPC.php";
    var oAjax   = new Ajax.Request( url, {
                                             method: 'post', 
                                             parameters: sQuery,
                                             onComplete: js_retornoCadastroMenu
                                           }
                                    );
      
  }
  
  function js_retornoCadastroMenu(oAjax){
    
    var sExpReg  = new RegExp('\\\\n','g');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if ( oRetorno.lErro ) {  
      alert(oRetorno.sMensagem.urlDecode().replace(sExpReg,'\n'));
    } else {
      alert('Menu cadastrado com sucesso!');
      document.form1.submit();  
    }  
       
  }


  function js_envioArquivo() {

	  if ( $F('arquivo') == '' ) {
      alert(_M('configuracao.configuracao.sys4_geradorrelatorio001..erro_arquivo_nao_informado'));
      return false;
	  }

	  if ($F('id_grupo_relatorio') == '') {
		  alert(_M('configuracao.configuracao.sys4_geradorrelatorio001.erro_codigo_grupo_relatorio'));
		  return false;
	  }

	  if ($F('id_tipo_relatorio') == '') {
		  alert(_M('configuracao.configuracao.sys4_geradorrelatorio001.erro_codigo_tipo_relatorio'));
		  return false;
	  }
	  
    js_divCarregando(_M('configuracao.configuracao.sys4_geradorrelatorio001.mensagem_ajax_enviando_arquivo_servidor'), 'msgbox');
   
    var sIdCampo             = 'arquivo';
    var sCampoRetorno        = 'caminho_arquivo';
    var sParametros          = "clone=form_arquivo&idcampo="+sIdCampo+"&function=retornoUploadArquivo&camporetorno="+sCampoRetorno;
    var iFrame               = document.createElement("iframe");
        iFrame.src           = "func_iframeupload.php?" + sParametros;
        iFrame.id            = 'uploadIframe';
        iFrame.width         = '100%';
    
    $('uploadIframeBox').appendChild(iFrame);
	}
	 
  function retornoUploadArquivo(sArquivo) {
    $('caminho_arquivo').value = sArquivo;
  }
	 
  function js_endloading() {
	  js_removeObj('msgbox');
	  $('uploadIframeBox').removeChild($('uploadIframe'));
	  js_enviarDados();
	}

	function js_enviarDados() {

	 var sUrl         = 'sys4_consultaviewRPC.php';   
   var sArquivo     = $F('caminho_arquivo');
   var iCodigoGrupo = $F('id_grupo_relatorio');
   var iTipoGrupo   = $F('id_tipo_relatorio');
     
   js_divCarregando(_M('configuracao.configuracao.sys4_geradorrelatorio001.mensagem_ajax_salvando_dados'),'msgbox');
   
   var sQuery  = 'tipo=importarRelatorio';
       sQuery += '&arquivo='+sArquivo;
       sQuery += '&codigo_grupo='+iCodigoGrupo;
       sQuery += '&codigo_tipo='+iTipoGrupo;
   
   var oAjax   = new Ajax.Request( sUrl, { method: 'post', parameters: sQuery, onComplete: js_retornoEnviarDados } );
	 
	}

	function js_retornoEnviarDados (oAjax) {

		js_removeObj('msgbox');
		
		var oRetorno = eval("("+oAjax.responseText+")");

		if (oRetorno.iStatus == 1) {
			alert(_M('configuracao.configuracao.sys4_geradorrelatorio001.mensagem_ajax_salvando_dados_ok'));
			js_pesquisaRelatorios("Publico");
			oWindowUpload.hide();
			return true;
		}	else {
			alert(oRetorno.sMensagem.urlDecode());
			return false;
		}

		
	}
</script>