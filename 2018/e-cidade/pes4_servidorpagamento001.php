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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhresponsavel_classe.php");

$oPost           = db_utils::postMemory($_POST);
$oGet            = db_utils::postMemory($_GET);

$clrhresponsavel = new cl_rhresponsavel();
$clrhresponsavel->rotulo->label();
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
	<?
	  db_app::load("scripts.js, strings.js,arrays.js, prototype.js,datagrid.widget.js");
	  db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
	  db_app::load("estilos.css, grid.style.css");
	?>
	<style>
		fieldset table td:first-child {
		
		  width: 90px;
		  white-space: nowrap;
		}
		
		td {
		  white-space: nowrap;
		}
		
		#rh108_status {
		  width: 100%;
		}
	</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<form name="form1" method="post" action="">
	<table border="0" align="center" cellspacing="0" cellpadding="0">
	  <tr>
	    <td height="40px">&nbsp;</td>
	  </tr>
	  <tr> 
	    <td valign="top" bgcolor="#CCCCCC"> 
	      <fieldset>
	        <legend>
	          <b>Processar Servidores Responsáveis pelo Pagamento</b>
					  <table border="0">
					    <tr>
					      <td title="">
					        <b>Tipo de Folha:</b>
					      </td>
					      <td colspan="2"> 
					        <?
					          $aTipoFolha = array("0" => "Selecione",
					                              "1" => "Salário", 
					                              "5" => "13º Salário");             
					          db_select("tipofolha", $aTipoFolha, true, 1, 
					                    "onchange='js_desabilitaSelecionar();' style='width:100%;'"); 
					        ?>
					      </td>
					    </tr>
					    <tr>
					      <td title="<?=@$Trh108_regist?>">
                  <?
                    db_ancora('<b>Responsável:</b>', "js_pesquisarh107_sequencial(true);", 1);
                  ?>
					      </td>
					      <td> 
                  <?
                    db_input('rh107_sequencial', 10, $Irh107_sequencial, true, 'text', 1, 
                             "onchange='js_pesquisarh107_sequencial(false);'");
                  ?>
					      </td>
					      <td>
                  <?
                    db_input('rh107_nome', 40, $Irh107_nome, true, 'text', 3);
                  ?>
					      </td>
					    </tr>
					  </table>
	        </legend>
	      </fieldset>
	    </td>
	  </tr>
	</table>
	<table align="center">
	  <tr>
	    <td>
	      <?
          db_input('servidoresvinculados', 10, 0, true, 'hidden', 3);
        ?>
	    </td>
	  </tr>
    <tr>
      <td>
        <input type="button" id="processar" name="processar" value="Processar" onclick="return js_processar();">
      </td>
    </tr>
	</table>
</form>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
var sUrlRpc = 'pes4_servidorpagamento.RPC.php';

/**
 * Desabilita combox com value = 'Selecionar'.
 **/
function js_desabilitaSelecionar() {

  var iTipoFolha = $('tipofolha').value;
  if (iTipoFolha != '0') {
    $('tipofolha').options[0].disabled = true; 
  }
}

/**
 * Valida campos selecionados.
 **/
function js_processar() {

  if ($('tipofolha').value == 0) {
  
    alert("Selecione um tipo de folha!");
    return false;
  } else {
  
    if (confirm("Deseja realmente processar os servidores responsáveis pelo pagamento?")) {
        
	    $('processar').disabled = true;
	    js_divCarregando('Aguarde processando...', 'msgBox');
	    
	    var oParam         = new Object();
	    oParam.exec        = 'processar';
	    oParam.iTipoFolha  = $('tipofolha').value;
	    oParam.aMatriculas = $('servidoresvinculados').value;
	    var oAjax          = new Ajax.Request(sUrlRpc,
	                                                 { method:'post',
	                                                   parameters:'json='+Object.toJSON(oParam),
	                                                   onComplete: function (oAjax) {
	
	                                                     js_removeObj("msgBox");
	                                                     
	                                                     var aRetorno = eval("("+oAjax.responseText+")");
	                                                     
	                                                     $('processar').disabled         = false;
	                                                     $('servidoresvinculados').value = '';    
																										   if (aRetorno.status == 2) {
																										   
																										     alert(aRetorno.message.urlDecode());
																										     return false;
																										   } else {
	                                                       
	                                                       $('tipofolha').value        = '0';
	                                                       $('rh107_sequencial').value = '';
	                                                       $('rh107_nome').value       = '';
	                                                       
	                                                       alert("Processamento concluído com sucesso.");
	                                                       return true;
	                                                     }
	                                                   }
	                                                 }
	                                                );
    }
  }
}

/**
 * Consulta os responsaveis cadastrados.
 **/
function js_pesquisarh107_sequencial(mostra) {

  if (mostra == true) {
  
	  var sUrlPesquisa = 'func_rhresponsavel.php?funcao_js='+
	                     'parent.js_mostrarh107_sequencial1|rh107_sequencial|rh107_nome';
	  js_OpenJanelaIframe('', 'db_iframe_rhresponsavel', sUrlPesquisa, 'Pesquisa', true);
  } else {
  
    if ($('rh107_sequencial').value != '') {
     
	    var sUrlPesquisa = 'func_rhresponsavel.php?pesquisa_chave='+
	                        $('rh107_sequencial').value+
	                        '&funcao_js=parent.js_mostrarh107_sequencial';
	    js_OpenJanelaIframe('', 'db_iframe_rhresponsavel', sUrlPesquisa, 'Pesquisa', false);
    } else {
    
      $('rh107_nome').value           = '';
      $('servidoresvinculados').value = ''; 
    }
  }
}

/**
 * Preenche os dados do responsável e abre janela com os servidores vinculados.
 **/
function js_mostrarh107_sequencial(chave1, chave2, erro) {
  
  if (chave2 == '') {
    var chave2 = chave1;
  }
  
  $('rh107_nome').value = chave2; 
  if (erro == true) { 
  
    $('rh107_sequencial').focus(); 
    $('rh107_sequencial').value     = '';
    $('servidoresvinculados').value = ''; 
  } else {
    js_montaJanela();
  }
}

/**
 * Preenche os dados do responsável e abre janela com os servidores vinculados.
 **/
function js_mostrarh107_sequencial1(chave1, chave2) {

  $('rh107_sequencial').value = chave1;
  $('rh107_nome').value       = chave2;
  
  db_iframe_rhresponsavel.hide();
  js_montaJanela();
}

/**
 * Monta a janela dos servidores vinculados.
 **/
function js_montaJanela() {
   
  if ($('windowServidores')) {
    oWindowServidores.destroy();
  }
   
	/**
	 * Instância a windowAux.
	 **/
	oWindowServidores = new windowAux('windowServidores', 'Pesquisa', 600, 400);
   
	/**
	 * Instância a DBGrid. 
	 **/
	oDBGridServidores = new DBGrid("listaServidores");
	oDBGridServidores.nameInstance = "oDBGridServidores";
	oDBGridServidores.setCellWidth(new Array('30%', '70%'));
	oDBGridServidores.setCellAlign(new Array('left', 'left'));
	oDBGridServidores.setCheckbox(0);
	oDBGridServidores.setHeader(new Array('Matricula', 'Nome'));
   
  var sContainers  = "<fieldset id='boxDataGrid'>";
      sContainers += "  <legend><b></b></legend>";
      sContainers += "</fieldset>";
      sContainers += "<table align='center'>";
      sContainers += "  <tr>";
      sContainers += "    <td>";
      sContainers += "      <input type='button' id='confirmar' name='confirmar' value='Confirmar'>";
      sContainers += "    </td>";
      sContainers += "  </tr>";
      sContainers += "</table>";    
  oWindowServidores.setContent(sContainers);
      
  oMessageBoard = new DBMessageBoard('msgboard', 
                                     'Adicionar Servidores Vinculados',
                                     'Adiciona matriculas dos servidores vinculados a um responsável.',
                                     $('windowwindowServidores_content')
                                    );
	oWindowServidores.setShutDownFunction(function() {
	  oWindowServidores.destroy();
	});
	    
  oWindowServidores.show();   
  oDBGridServidores.show($('boxDataGrid'));
  js_buscaServidoresVinculados();
}

/**
 * Busca dados servidores vinculados a um responsável.
 **/
function js_buscaServidoresVinculados() {

  if ($('rh107_sequencial').value == "") {
    
    alert('Informe o responsável!');
    return false;
  } else {
  
	  $('confirmar').disabled = true;
	  js_divCarregando('Aguarde buscando servidores vinculados...', 'msgBox');
	  
	  var oParam                = new Object();
	  oParam.exec               = 'getServidoresVinculados';
	  oParam.iCodigoResponsavel = $('rh107_sequencial').value;
	  var oAjax                 = new Ajax.Request(sUrlRpc,
	                                               { method:'post',
	                                                 parameters:'json='+Object.toJSON(oParam),
	                                                 onComplete: js_preencherGrid
	                                               }
	                                              );
  }
  
}

/**
 * Preenche os dados no datagrid.
 **/
function js_preencherGrid(oAjax) {
      
  js_removeObj("msgBox");
  
  var aRetorno       = eval("("+oAjax.responseText+")");     
  var aListaServidor = aRetorno.aListaServidor;
   
  if (aRetorno.status == 2) {
  
    alert(aRetorno.message.urlDecode());
    return false;
  } else {
  
	  oDBGridServidores.clearAll(true);
    
	  if (aListaServidor.length > 0) {
	    
	    oDBGridServidores.clearAll(true);
	    aListaServidor.each(function (oDadoRetorno, iInd) {
	      
	      aLinha    = new Array();
	      aLinha[0] = oDadoRetorno.rh108_regist;
	      aLinha[1] = oDadoRetorno.z01_nome.urlDecode();

	      oDBGridServidores.addRow(aLinha); 
	    });
	      
	    oDBGridServidores.renderRows();
	    $('confirmar').disabled = false;
      $('confirmar').observe('click', function () {
      
        $('servidoresvinculados').value = js_retornaServidoresSelecionados(oDBGridServidores.getSelection());
        oWindowServidores.destroy();
      });
	  }
	  
	  return true;
  }
}

function js_retornaServidoresSelecionados(aServidoresSelecionados) {

  var aSelecionados = new Array();
  if (aServidoresSelecionados.length > 0) {
              
    aServidoresSelecionados.each(function (aDadoRetorno, iInd) {
      aSelecionados[iInd] = aDadoRetorno[0];
    });
  }
  
  return aSelecionados.implode(',');
}
</script>
</html>