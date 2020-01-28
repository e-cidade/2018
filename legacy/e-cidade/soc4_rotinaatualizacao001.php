<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cidadao_classe.php");
require_once("classes/db_cidadaofamilia_classe.php");
require_once("classes/db_cidadaocadastrounico_classe.php");

$db_opcao      = 1;
$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("as02_dataatualizacao");
$oRotuloCampos->label("as04_sequencial");
$oRotuloCampos->label("ov02_nome");
$oRotuloCampos->label("as15_codigofamiliarcadastrounico");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("prototype.js, scripts.js, strings.js");
    db_app::load("estilos.css");
    ?>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px;" >
    <center>
      <div style="display: table">
        <fieldset>
          <legend><b>Rotina de Atualização</b></legend>
          <form method="post" name='form1'>
            <table >
              <tr>
                <td>
                  <?php
                  db_ancora("<b>Código da Família: </b>", "js_pesquisaCidadaoFamilia(true);", $db_opcao);
                  ?>
                </td>
                <td>
                  <?php
                  db_input("as04_sequencial", 10, $Ias04_sequencial, true, "hidden", 1);
                  db_input("as15_codigofamiliarcadastrounico", 10, $Ias15_codigofamiliarcadastrounico, true,
                  		"text", 1, "onchange='js_pesquisaCidadaoFamilia(false);'");
                  db_input("ov02_nome", 40, $Iov02_nome, true, "text", 3);
                  
                 
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b><?=@$Las02_dataatualizacao?></b>
                </td>
                <td>
                  <?php
                  db_inputdata("as02_dataatualizacao", null, null, null, true, "text", 1);
                  ?>
                </td>
              </tr>
            </table>
          </form>
        </fieldset>
      </div>
      <input type="button" id="btnAtualizar" value="Atualizar Data" onclick="return js_validaCampos();" />
    </center>
    <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script>
var sUrlRPC = 'soc4_datascadastrounico.RPC.php';
/*
 * Pesquisa o responsável pela família que esteja no cadastro único
 */

js_limparDados();
function js_pesquisaCidadaoFamilia(lMostra) {

  if (lMostra == true) {

  	js_OpenJanelaIframe('top.corpo', 
  	  	                'db_iframe_cidadaofamilia', 
  	  	                'func_cidadaofamilia.php?'+
  	  	                'funcao_js=parent.js_mostracidadaofamilia1|as04_sequencial|ov02_nome|as15_codigofamiliarcadastrounico', 
  	  	                'Pesquisar Código da Família', 
  	  	                true
  	  	               );
  } else {

  	if ($F('as15_codigofamiliarcadastrounico') != '') {

    	js_OpenJanelaIframe('top.corpo', 
                          'db_iframe_cidadaofamilia', 
                          'func_cidadaofamilia.php?pesquisa_chave='+$F('as15_codigofamiliarcadastrounico')+
                                                 '&funcao_js=parent.js_mostracidadaofamilia'+
                                                 '&sTipoRetorno=relatorio', 
                          'Pesquisar Código da Família', 
                          false
                         );
  	} else {
      $('as04_sequencial').value = '';
  	}
  }
}

function js_mostracidadaofamilia(iCodigoFamilia, erro, sNome, iSequencial) {

   $('as04_sequencial').value = iSequencial;
   $('ov02_nome').value       = sNome;
   
   if (erro == true) {

   	$('as04_sequencial').value                     = '';
   	$('ov02_nome').value                           = '';
   	$('as15_codigofamiliarcadastrounico').values   = "";
   	$('as15_codigofamiliarcadastrounico').focus();
   }
 }

 function js_mostracidadaofamilia1(iSequencial, sNome, iCodigoFamilia) {

 	$('as04_sequencial').value                  = iSequencial;
 	$('as15_codigofamiliarcadastrounico').value = iCodigoFamilia;
 	$('ov02_nome').value                        = sNome;
 	db_iframe_cidadaofamilia.hide();
 }

/*
 * Inclui a data de atualização dos dados da família
 */
function js_incluirAtualizacao() {

  var oParametro            = new Object();
  oParametro.exec           = 'salvarAtualizacao';
  oParametro.iCodigoFamilia = $F('as04_sequencial');
  oParametro.dtAtualizacao  = $F('as02_dataatualizacao');

  js_divCarregando("Aguarde... Salvando data de atualização.", "msgBox");
  var oAjax = new Ajax.Request(
  	                           sUrlRPC,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oParametro),
    	                           onComplete: js_salvarAtualizacao
  	                           }
  	                          );
}

/*
 * Retorna o objeto referente a atualização
 */
function js_salvarAtualizacao(oResponse) {

	js_removeObj("msgBox");
	var oRetorno = eval("("+oResponse.responseText+")");
	if (oRetorno.status == 1) {
		alert ('Dados salvos com sucesso');
		js_limparDados();
  }
}

/*
 * Limpa os dados na tela após salvos
 */
function js_limparDados() {

	$('as04_sequencial').value                  = '';
	$('ov02_nome').value                        = '';
	$('as02_dataatualizacao').value             = '';
	$('as15_codigofamiliarcadastrounico').value = '';
	
}

/*
 * Valida os campos
 */
function js_validaCampos() {

	if ($('as04_sequencial').value == '') {

		alert ('Deve ser informado o código do responsável pela família');
		$('as04_sequencial').focus();
		return false;
	}
	if ($('as02_dataatualizacao').value == '') {

		alert ('Deve ser informada a data de atualização');
		$('as02_dataatualizacao').focus();
		return false;
	}
	js_incluirAtualizacao();
}
</script>