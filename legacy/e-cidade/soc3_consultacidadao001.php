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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");


$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("as02_nis");
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
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <center>
    <form name="form1" method="post" action="">
    <fieldset style="width:300px;">
      <legend><b>Consulta Cidadão</b></legend>
      <table>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
            <? db_ancora("Cidadão: ","js_pesquisaCidadao(true, false);",1);?>
          <td nowrap="nowrap">
            <?php
              db_input("codigoCidadao", 10, '', true, "text", 1, "onchange='js_pesquisaCidadao(false, false);'");
              db_input("nome",          25, '', true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
            <?php
              db_ancora("NIS:", "js_pesquisaCidadao(true, true);", 1);
            ?>
          </td>
          <td>
          <?php 
            db_input("as02_nis", 10, $Ias02_nis, true, "text", 1, "onchange='js_pesquisaCidadao(false, true);'");
          ?>
          </td>
        </tr>
      </table>
     </fieldset>
     <input type="button" id="btnConsultar" value="Consultar" onclick="js_consultar();"
            style="margin-top: 10px;" />
     </form>
  </center>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

$('codigoCidadao').value = "";
$('nome').value          = "";
$('as02_nis').value     = "";
/**
 * Função para busca e validação do NIS 
 */
function js_pesquisaCidadao(lMostra, lNis) {

  var sUrl = 'func_cidadaofamiliacompleto.php?';
  
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostraCidadao|ov02_sequencial|ov02_nome|as02_nis'; 
    js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão',true);
  } else {

    if ($F('as02_nis') != '' && lNis) {
      sUrl += 'pesquisa_chave='+$F('as02_nis');
      sUrl += '&lNis=true';
    }
    
    if ($F('codigoCidadao') != ''  && !lNis) {
      
      sUrl += 'pesquisa_chave='+$F('codigoCidadao');
      sUrl += '&lCidadao=true';
    }
    
    sUrl += '&funcao_js=parent.js_mostraCidadao2';

    if ($F('as02_nis') != '' || $F('codigoCidadao') != '') {

     js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão', false);
    } else {
      
      $('codigoCidadao').value = "";
      $('nome').value          = "";
      $('as02_nis').value     = "";
    }
  }
}
 
function js_mostraCidadao (iCidadao, sCidadao, iNis) {

  if (iCidadao != "") {
    
    $('codigoCidadao').value   = iCidadao;
    $('nome').value            = sCidadao;
    $('as02_nis').value        = iNis;
  }
  db_iframe_cidadaofamilia.hide();
}

function js_mostraCidadao2(lErro, iCidadao, sCidadao, iNis) {

  $('nome').value            = sCidadao;
  $('codigoCidadao').value   = iCidadao;
  $('as02_nis').value        = iNis;
  
  if (lErro) {
    $('codigoCidadao').value = "";
    $('as02_nis').value      = "";
  }
}




function js_consultar(){

  if ($('codigoCidadao').value.trim() !== ""){
    
    var sUrlPesquisa = 'soc3_consultacidadao003.php?codigoCidadao=' + $F('codigoCidadao');
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_consulta_cidadao',
                        sUrlPesquisa,
                        'Consulta Cidadão',
                        true);
    return true;
  }
  alert("Selecione um Cidadão para efetuar a pesquisa.");
  return false;
}
</script>
</body>
</html>