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
require_once("classes/db_inscricaopassivo_classe.php");

$oGet = db_utils::postMemory($_GET);
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?php
    db_app::load("scripts.js, strings.js, estilos.css, prototype.js, datagrid.widget.js, grid.style.css");
    db_app::load("classes/DBViewSlipPagamento.classe.js, widgets/dbtextField.widget.js, widgets/dbcomboBox.widget.js");
  ?>
</head>
<body style="background-color: #cccccc; margin-top: 25px;">
  <div align="center">

    <fieldset style="width:400px">
        <legend>
          <b>
           Passivo Sem Suporte Orçamentário - Baixa por Pagamento
          </b>
        </legend>

        <form name="form1" method="get" target=""  action="con4_baixainscricaopassivopagamento001.php">
          <table>
           <tr>
              <td  align="left" >
                <b><?db_ancora('Inscricao Passivo:',"js_pesquisaInscricaoPassivo(true); ",1);?></b>
              </td>
              <td align="left" nowrap>
                <? db_input("c36_sequencial",6,1,true,"text",4,"onblur='js_pesquisaInscricaoPassivo(false)'");?>
              </td>
            </tr>
          </table>
        </fieldset>
        <br />
          <input name="btnProcessar" id="btnProcessar" type="button"  value="Processar">
        </form>
  </div>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>

$('btnProcessar').observe("click", function() {

	if ($F('c36_sequencial') == "") {

		alert("Inscrição não informada.");
		return false;
	}

	location.href = 'con4_baixainscricaopassivopagamento001.php?c36_sequencial='+$F('c36_sequencial');

});

/**
 * Função para pesquisar uma inscrição, seja via inserção direta ou seleção pela âncora
 */
function js_pesquisaInscricaoPassivo(lMostra){

  sFuncao = 'func_inscricaopassivobaixapagamento.php?';

  if (lMostra) {
    sFuncao += 'funcao_js=parent.js_mostraInscricaoEscolhida|c36_sequencial';
  } else {
    sFuncao += 'pesquisa_chave='+ $F("c36_sequencial") + '&funcao_js=parent.js_mostraInscricao';
  }
  return js_OpenJanelaIframe('top.corpo','db_iframe_inscricaopassivo',sFuncao,'Pesquisa',lMostra);
}

/**
 * Mostra uma inscrição digitada, validando se a mesma existe
 */
function js_mostraInscricao(iCodigo, lErro){

  if(lErro) {

    alert("Não foi possível encontrar nenhuma inscrição referente ao código "+ $("c36_sequencial").value);
    $("c36_sequencial").value = '';
    $("c36_sequencial").focus();
    return false;
  }

  $("c36_sequencial").value = iCodigo;
  return true;
}

/**
 * Exibe inscrição escolhida na tabela pesquisada
 */
function js_mostraInscricaoEscolhida(iCodigo) {

   $("c36_sequencial").value = iCodigo;
   db_iframe_inscricaopassivo.hide();
   return true;
}

</script>