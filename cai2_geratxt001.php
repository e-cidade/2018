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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_bancos_classe.php");

$cldb_bancos = new cl_db_bancos;
db_postmemory($HTTP_POST_VARS);

$tipo_ordem = array("a" => "Nome fornecedor", "b" => "CGM fornecedor", "c" => "Recurso");
$aBancos    = Array();

$sSqlBancos     = $cldb_bancos->sql_query_empage(null, "distinct db90_codban,db90_descr", "db90_descr", " e90_codmov is null ");
$result_bancos  = $cldb_bancos->sql_record($sSqlBancos);
$numrows_bancos = $cldb_bancos->numrows;

for ($i = 0; $i < $numrows_bancos; $i++) {

  db_fieldsmemory($result_bancos,$i);

  if ( $i == 0 && !isset($db_bancos) ) {
    $db_bancos = $db90_codban;
  }

  $aBancos[$db90_codban] = $db90_descr;
}

$qualdescr = "";

if (isset($db_bancos) && isset($aBancos[$db_bancos])) {
  $qualdescr = $aBancos[$db_bancos];
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <form name="form1" method="post" action="" class="container">

    <fieldset style="width:400px;">

      <table class="form-container">

        <tr>
          <td>
            <strong>Banco:</strong>
          </td>
          <td>
           <?php db_select("db_bancos", $aBancos, true, 1, "onchange='js_buscarCNPJ();'"); ?>
          </td>
        </tr>

        <tr>
          <td>
            <strong>CNPJ:</strong>
          </td>
          <td>
           <?php db_select("comboboxCNPJ", array(0 => 'Selecione...'), true, 1); ?>
          </td>
        </tr>
        
        <tr>
          <td>
            <strong>Ordem:</strong>
          </td>
          <td>
            <?php db_select("ordem", $tipo_ordem, true, 2); ?>
          </td>
        </tr>

      </table>

    </fieldset>
  
    <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >

  </form>

  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

/**
 * Busca CNPJ das contas pelo codigo do banco 
 */
js_buscarCNPJ();

/**
 * Busca CNPJ das contas pelo codigo do banco 
 *
 * @access public
 * @return void
 */
function js_buscarCNPJ() {

  js_divCarregando("Aguarde, carregando os CNPJ do banco...", "msgBox");

  var oParam          = new Object();
  oParam.exec         = "getContasPorCodigoBanco";
  oParam.sCodigoBanco = $F("db_bancos");

  new Ajax.Request("con1_contabancaria.RPC.php",
                   {method: 'post',
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: js_retornoBuscaCNPJ});
}

/**
 * Retorno da funcao buscar CNPJ
 *
 * @param object oAjax
 * @access public
 * @return boolean
 */
function js_retornoBuscaCNPJ(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  /**
   * Erro no RPC 
   */
  if (oRetorno.status > 1) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  /**
   * Remove os options já existentes no select 
   */
  $('comboboxCNPJ').options.length = 0;

  var oOption = new Option("Selecione...", "0");
  $('comboboxCNPJ').appendChild(oOption);

  oRetorno.aContasBancarias.each(function (oDado, iLinha) {

    var sValorOption = oDado.db83_identificador;
    var sLabelOption = oDado.db83_identificador;

    if (oDado.db83_identificador.trim() == "") {

      sLabelOption = "CNPJ Inexistente";
      sValorOption = oDado.db83_identificador;
    }

    var oOption = new Option(sLabelOption, sValorOption);
    $('comboboxCNPJ').appendChild(oOption);
  });
}

/**
 * Emite relatório
 *
 * @access public
 * @return void
 */
function js_emite() {

  var sOrdem             = $('ordem').value;
  var iBanco             = $('db_bancos').value;
  var sCNPJContaBancaria = $('comboboxCNPJ').value;

  jan = window.open('cai2_geratxt002.php?ordem='+ sOrdem + '&db_banco=' + iBanco + '&sCNPJContaBancaria=' + sCNPJContaBancaria, 
                    '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>