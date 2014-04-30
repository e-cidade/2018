<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oDaoMatMater = db_utils::getDao('matmater');
$oDaoMatMater->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #cccccc; margin-top: 30px;">
<form id="form1">
<div align="center">
  <fieldset style="width: 550px;">
    <legend><b>Consulta Material</b></legend>
    <table width="100%">
      <tr>
        <td>
          <?php
            db_ancora("{$Lm60_codmater}", "js_pesquisaMaterial(true);", 1);
          ?>
        </td>
        <td>
          <?php 
            db_input('m60_codmater', 8, $Im60_codmater, true, 'text', 1, "onchange='js_pesquisaMaterial(false);'");
            db_input('m60_descr', 45, $Im60_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <br>
  <input type="button" id="btnConsultaMaterial" name="btnConsultaMaterial" value="Consultar" />&nbsp;
  <input type="reset" id="btnLimparMaterial" name="btnLimparMaterial" value="Limpar" />
</div>
</form>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

$('btnConsultaMaterial').observe('click', function() {

  if ($F('m60_codmater') == "") {
    alert("Selecione um material para continuar.");
    return false;
  }

  var sUrlDireciona = "mat3_consultamaterial002.php?iCodigoMaterial="+$F('m60_codmater');
  var sTituloJanela = "Material: "+$F('m60_codmater')+" - "+$F('m60_descr');
  js_OpenJanelaIframe('top.corpo', 'db_iframe_consultamaterial', sUrlDireciona, sTituloJanela, true);
});

/**
 * Funções de pesquisa do Material
 */
function js_pesquisaMaterial(lMostra) {

  var sUrlOpen = "func_matmater.php?pesquisa_chave="+$F('m60_codmater')+"&funcao_js=parent.js_completaMaterial";
  if (lMostra) {
    sUrlOpen = "func_matmater.php?funcao_js=parent.js_preencheMaterial|m60_codmater|m60_descr";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_matmater', sUrlOpen, 'Pesquisa Material', lMostra);
}
function js_completaMaterial(sDescricao, lErro) {
  
  $("m60_descr").setValue(sDescricao);
  if (lErro) {
    $("m60_codmater").setValue('');
  }
}
function js_preencheMaterial(iCodigo, sDescricao) {

  $('m60_codmater').setValue(iCodigo);
  $('m60_descr').setValue(sDescricao);
  db_iframe_matmater.hide();
}
</script>