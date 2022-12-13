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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label('ve01_codigo');
$clrotulo->label('ve01_placa');
$clrotulo->label('ve40_veiccadcentral');
$clrotulo->label('descrdepto');
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_pesquisa() {

  var iVeiculo = $F('ve01_codigo');
  var sPlaca   = $F('ve01_placa');
  var iCentral = $F('ve40_veiccadcentral');
  var sUrl = 'vei2_listaveiculosconsulta.php?iVeiculo='+iVeiculo+'&sPlaca='+sPlaca+'&iCentral='+iCentral;
  
  js_OpenJanelaIframe('top.corpo','func_veiculo', sUrl,'Consulta de Veículos',true, '20');
  
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" style="margin-top: 25px">
<form name="form1" method="post">
<center>
<table>
  <tr>
    <td>
    <fieldset>
    <legend>
      <b>Filtros para pesquisa de Veículos</b>
    </legend>
      <table>
      <tr>
        <td nowrap title="<?=@$Tve01_codigo?>">
          <?
          db_ancora(@$Lve01_codigo,"js_pesquisave01_codigo(true);",4);
          ?>
        </td>
        <td> 
          <?
          db_input('ve01_codigo',10,$Ive01_codigo,true,'text',4," onchange='js_pesquisave01_codigo(false);'")
          ?>
          <?
          db_input('ve01_placadescr',10,'0',true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?=@$Lve01_placa?>
        </td>
        <td>
          <?
          db_input('ve01_placa', 10, $Ive01_placa, true, 'text', 4, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tve40_veiccadcentral?>">
          <?
          db_ancora(@$Lve40_veiccadcentral,"js_pesquisacentral(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
          db_input('ve40_veiccadcentral',10,$Ive40_veiccadcentral,true, 
                   'text',$db_opcao," onchange='js_pesquisacentral(false);'")
          ?>
          <?
          db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
          ?>
        </td>
      </tr>
    </table>
   </fieldset>
   </td>
  </tr>
  <tr> 
    <td align="center" colspan="2">
      <input onClick="js_validarVeiculo();"  type="button" 
             value="Pesquisar" name="pesquisar" 
             onBlur='js_tabulacaoforms("form1","ve01_codigo",true,0,"ve01_codigo",true);'>
    </td>
      </tr>
   </table>   
</form>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

/**
 * Valida codigo do veiculo antes de abrir tela de consulta, funcao js_pesquisa()
 *
 * @return {void}
 */
function js_validarVeiculo() {

  var iVeiculo = document.getElementById('ve01_codigo').value;
  var sIframe  = 'db_iframe_validar_veiculos';
  var sArquivo = 'func_veiculosconsulta.php?pesquisa_chave='+ iVeiculo +'&funcao_js=parent.js_validarVeiculo.retorno';
  js_OpenJanelaIframe('top.corpo', sIframe, sArquivo, '', false);
}

/**
 * Retorno da funcao validar veiculo
 *
 * @return {void}
 */
js_validarVeiculo.retorno = function(sDescricaoVeiculo, lErro) {

  /**
   * Veiculo nao encontrado 
   */
  if (lErro) {

    document.getElementById('ve01_codigo').focus(); 
    document.getElementById('ve01_codigo').value = ''; 
    document.getElementById('ve01_placadescr').value = sDescricaoVeiculo;
    return;
  }

  js_pesquisa();
}

function js_pesquisave01_codigo(mostra){
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculosconsulta.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_placa','Pesquisa',true);
  } else {
     if (document.form1.ve01_codigo.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculosconsulta.php?pesquisa_chave='+document.form1.ve01_codigo.value+'&funcao_js=parent.js_mostraveiculos','Pesquisa',false);
     } else {
       document.form1.ve01_placadescr.value = ''; 
     }
  }
}
function js_mostraveiculos(chave,erro) {

  document.form1.ve01_placadescr.value = chave; 
  if (erro == true) { 
    document.form1.ve01_codigo.focus(); 
    document.form1.ve01_codigo.value = ''; 
  }
}
function js_mostraveiculos1(chave1,chave2) {

  document.form1.ve01_codigo.value = chave1;
  document.form1.ve01_placadescr.value  = chave2;
  db_iframe_veiculos.hide();
}


function js_pesquisacentral(mostra) {

  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_central', 
                        'func_veiccadcentral.php?funcao_js=parent.js_mostracentral1|ve36_sequencial|descrdepto',
                        'Pesquisa de Central', 
                        true);
  } else {
  
     if (document.form1.ve40_veiccadcentral.value != '') { 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_central',
                            'func_veiccadcentral.php?pesquisa_chave='+document.form1.ve40_veiccadcentral.value+
                            '&funcao_js=parent.js_mostracentral', 
                            'Pesquisa de Central',
                            false);
     } else {
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostracentral(chave,erro, descrdepto) {

  document.form1.descrdepto.value = descrdepto; 
  if (erro == true) {
   
    document.form1.ve40_veiccadcentral.focus(); 
    document.form1.descrdepto.value = ''; 
  }
}
function js_mostracentral1(chave1, chave2) {

  document.form1.ve40_veiccadcentral.value = chave1;
  document.form1.descrdepto.value  = chave2;
  db_iframe_central.hide();
}
js_tabulacaoforms("form1","ve01_codigo",true,0,"ve01_codigo",true);
</script>
