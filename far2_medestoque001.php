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

/**
* Este arquivo pertence a rotina de geração do relatório de estoque de medicamentos.
* Neste é encontrado o Formulário HTML e todas as funcionalidade deste.
* 
* @access 	  public
* @author     Adriano Quilião de Oliveira <adriano.oliveira@dbseller.com.br, adriano@webseller.com.br> 
* @copyright  GNU - LICENÇA PÚBLICA GERAL GNU - Versão 2, junho de 1991. 
* @version	  1.0
* @magic	  phpdoc.de compatibility
* @todo		  phpdoc.de compatibility
* @package	  Saude
* @subpackage Farmacia
*/
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$db_opcao = 2;
$oRotulo = new rotulocampo;
$oRotulo->label("coddepto");
$oRotulo->label("descrdepto");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <center>
      <fieldset style="margin-top: 40px; width:40%">
        <legend><b>Estoque de Medicamento</b></legend>
        <form name="form1" method="post" action="">
          <table  border="0"  align="center">
            <tr>
              <td colspan="2" >
                <fieldset>
                  <Legend><b>Medicamento</b></legend>
                  <table> 
                    <tr>
                      <td>
                        <?db_ancora("<b>Depósito:</b>", "js_BuscaDados(true);", $db_opcao);?>
                      </td>  
                      <td>
                        <?db_input("coddepto", 6, @$Icoddepto, true, "text", $db_opcao, "onchange='js_BuscaDados(false);'");?>
                      </td>                    
                      <td>
                        <?db_input("descrdepto", 50, @$Idescrdepto, true, 'text', 3);?>
                      </td>
                      <td>
                        <input name="Incluir" type="button" value="Incluir" onclick="js_incluirDeposito();">
                      </td>
                    <tr>
                    <tr>
                      <td>
                      </td>
                      <td colspan='2'> 
                        <select multiple size='6'
                                name='selectUnidade' 
                                id='selectUnidade' 
                                style="width: 100%;" 
                                onDblClick="js_excluirDeposito();">
                        </select>
                      </td> 
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td  style="width: 50%">
                <fieldset>
                  <legend><b>Opções</b></legend>
                  <table>
                    <tr>   
			          <td nowrap title="Estoque zerado.">
                        <b>Estoque zerado:</b>
                      </td> 
                      <td>
                        <?db_select("estoqueZerado", array("S"=>"SIM","N"=>"NÃO"), true, 2, "");?>
	                  <td>
	                </tr>
	                <tr>   
			          <td>
			            <b>Tipo:</b>
			          </td>
				      <td>
				        <?db_select("tipo", array("S"=>"SINTÉTICO"), true, 2);?>
	                  </td>
	                </tr>
	              </table>
                </fieldset>
              </td>
              <td>
                <fieldset>
                  <legend><b>Filtros</b></legend>
		          <table>
                    <tr>
                      <td align="left"  title="Quebra" >
                        <b>Quebra:</b>
	                  </td>
			          <td>  
			            <?db_select("quebra", array("N"=>"NENHUMA","D"=>"DEPÓSITO"), true, 2);?>
                      </td>
	                </tr>
	                <tr>  
			          <td align="center"  title="Ordem dos dados." >
                        <b>Ordem:</b>
	                  </td>
	                  <td>	
			            <?db_select("ordem", array("A"=>"ALFABÉTICA","N"=>"NUMERICA"), true, 2);?>
                      </td>
			        </tr>
	              </table>
		        </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center" style="padding-top: 15px;"> 
                <input name="processar" id="processar" type="button" value="Processar" onclick="js_mandaDados();" >
              </td>
            </tr>
          </table>
        </form>
      </fieldset>
    </center>
    <?db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit")
             );
    ?>
  </body>
</html>
<script>
/**
 * Função responsável por limpar os campos do formulário de busca de depósito
 * foco para o campo código(coddepto).
 *
 * @param {}
 * @return {}
 */
function js_limparCampos() {

  $("descrdepto").value = "";
  $("coddepto").value   = "";
  $("coddepto").focus();
	  
}

/**
 * Função que tem por objetivo icluir um novo depósito no 'select multiple' 
 * de depósito.
 *
 * @param {}
 * @return {}
 */
function js_incluirDeposito() {

  if ($("coddepto").value.trim() == "" || $("descrdepto").value.trim() == "") {

    alert("Informe um depósito antes de Incluir.");
    return;
		  
  }                                                                        
  if ($("selectUnidade").length >= 1 && !js_validarDeposito()) {
	  
    alert("Depósito já inserido na lista.");
    js_limparCampos();
    return;
    
  }
  var oOption   = document.createElement("option");
  oOption.text  = $("descrdepto").value;
  oOption.value = $("coddepto").value;
  $("selectUnidade").add(oOption);
  js_limparCampos();
	
}

/**
 * Esta função verifica se um depósito já não foi incluido no 'select multiple'
 * para evitar a duplicação de depósitos.
 *
 * @param {}
 * @return {boolean} TRUE se o depósito não foi incluido ainda e FALSE caso contrario.
 */
function js_validarDeposito() {

  for (var i = 0; i < $("selectUnidade").length; i++) {

    if ($("selectUnidade").options[i].value == $("coddepto").value) {
      return false;
    }   
	                                
  }
  return true;
	  
}

/**
 * Excluir deposilo do 'select multiple'. Esta função é evocada quando realizado o duplo click no registro.
 *
 * @param {}
 * @return {}
 */  
function js_excluirDeposito() {

  $("selectUnidade").remove($("selectUnidade").selectedIndex);

}

/**
 * Buscar o depósito com o auxílio da 'func'. 
 *
 * @param {boolean} Exibir ou não a 'func'. (TRUE abre iframe e clica no desejado, FALSE busca por código sem abrir)
 * @return {}
 */
function js_BuscaDados(lExibir){  

   if (lExibir) {
	   
	js_OpenJanelaIframe('', 'db_iframe_db_depart', 'func_db_almoxdepto.php?funcao_js=parent.js_inserirDepositoExibindo|coddepto|descrdepto',
			            'Pesquisa', true);
    
  } else {
	  
    js_OpenJanelaIframe('', 'db_iframe_db_depart', 
    	                'func_db_almoxdepto.php?pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_inserirDepositoNaoExibindo',
    	                'Pesquisa', false);
    
  }

}

/**
 * Função com o depósito de retorno utilizando a exibição da 'func'. 
 *
 * @param {integer, string} É retonado da 'func' o código e a descrição do depósito respectivamente.
 * @return {}
 */
function js_inserirDepositoExibindo(codDepto, descrDepto){
	
  $("coddepto").value   = codDepto;
  $("descrdepto").value = descrDepto;
  db_iframe_db_depart.hide();

}

/**
 * Função com o depósito de retorno não utilizando a exibição da 'func'. 
 *
 * @param {string, boolean} É retonado da 'func' a descrição do depósito(se houver, caso contrário uma mensagem de erro) 
 * e uma variável booleana que retorna TRUE se encontrou o código e FALSE se não encontrou.
 * @return {}
 */
function js_inserirDepositoNaoExibindo (descrDepto, lEncontrou){

  $("descrdepto").value = descrDepto;
  if(lEncontrou){

	$("coddepto").value = '';
	$("coddepto").focus();

  } 
  db_iframe_db_depart.hide();

}

/**
 * Função que retorna todos os códigos dos depósitos que estão na 'func' utilizando como separador o '|'(pipe).
 *
 * @param {}
 * @return {string} Códigos dos depósitos separados por pipe('|').
 */
function js_getStringFormatadaDepositos() {

  var sDepositos = "";
  for (var i = 0; i < $("selectUnidade").length; i++) {

    sDepositos += ((i+1) != $("selectUnidade").length ? 
    	           $("selectUnidade").options[i].value + "|" : 
        	       $("selectUnidade").options[i].value
        	      );  
			                                
  }
  return sDepositos;
  
}

/**
 * Função que chama o arquivo de geração do relatório.
 *
 * @param {}
 * @return {} 
 */
function js_mandaDados() {

  var sQuery = "";
  sQuery    += 'depositos=' + js_getStringFormatadaDepositos();
  sQuery    += '&tipo=' + $("tipo").value;
  sQuery    += '&estoqueZerado=' + $("estoqueZerado").value; 
  sQuery    += '&ordem=' + $("ordem").value;
  sQuery    += '&quebra=' + $("quebra").value;
	 
  var jan = window.open('far2_medestoque002.php?' + sQuery, '', 
   		                'width='+(screen.availWidth-5)+',height=' + (screen.availHeight-40) + ',scrollbars=1,location=0 '
			           );
  jan.moveTo(0,0);
	 
}
</script>