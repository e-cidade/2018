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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cidadaofamilia_classe.php");

$db_opcao = 1;
$oRotulo  = new rotulocampo();
$oRotulo->label("as04_sequencial");
$oRotulo->label("as15_codigofamiliarcadastrounico");
$oRotulo->label("ov02_nome");

$aAlfabeto = array("A"=>"A", "B"=>"B", "C"=>"C", "D"=>"D", "E"=>"E", "F"=>"F", "G"=>"G", "H"=>"H", "I"=>"I", "J"=>"J",
    "K"=>"K", "L"=>"L", "M"=>"M", "N"=>"N","O"=>"O","P"=>"P","Q"=>"Q","R"=>"R","S"=>"S","T"=>"T","U"=>"U",
    "V"=>"V", "W"=>"W", "X"=>"X", "Y"=>"Y", "Z"=>"Z");
$aMesCompetencia = array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11, 12=>12);
$sAnoCompetencia = "";

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, strings.js");
      db_app::load("estilos.css");
    ?>
    <style type="text/css">
      .fieldset-hr {
        border:none;
        border-top: 1px outset #000;
      }
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
    <div>
      <center>
        <form action="" name="form1">
          <fieldset style="width: 400px;">
            <legend><b>Filtros do Relatório</b></legend>
            <fieldset class="fieldset-hr">
              <legend><b>Filtros</b></legend>
              <table>
                <tr>
                  <td nowrap="nowrap">
                    <?php 
                      db_ancora($Las04_sequencial, "js_pesquisaCodigoFamiliar(true);", $db_opcao);
                    ?>
                  </td>
                  <td nowrap="nowrap" colspan="3">
                    <?php
                      db_input("as04_sequencial", 10, $Ias04_sequencial, true, 'hidden', 3);
                      db_input("as15_codigofamiliarcadastrounico", 10, $Ias15_codigofamiliarcadastrounico, true, 'text', 
                               $db_opcao, 
                               "onchange='js_pesquisaCodigoFamiliar(false);'");
                      db_input("ov02_nome", 30, $Iov02_nome, true, 'text', 3);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><b>Por Letra:</b></td>
                  <td nowrap="nowrap">
                    <?php db_select('letra_inicio', $aAlfabeto, true, $db_opcao, "");?>
                    <b> até: </b>
                    <?php db_select('letra_fim', $aAlfabeto, true, $db_opcao, "");?>
                  </td>
                </tr>
                <tr>
                  <td nowrap><b>Mês/Ano da Competência:</b></td>
                  <td nowrap>
                    <?php 
                      db_select('aMesCompetencia', $aMesCompetencia, true, $db_opcao, "");
                    ?>
                    <b>/</b>
                    <?php
                      db_input("sAnoCompetencia", 10, $sAnoCompetencia, true, 'text', $db_opcao, '', '', '', '', 4); 
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset class="fieldset-hr">  
              <legend><b>Visualização</b></legend>
              <table>
                <tr>
                  <td nowrap="nowrap" colspan="4">
                    <?php db_input('quebra_pagina', 50, '', true, 'checkbox', $db_opcao);?>
                    <label for='quebra_pagina' ><b>Quebra página por letra</b></label> 
                  </td>
                </tr>
              </table>
            </fieldset>
          </fieldset>
          <input type="button" value="Imprimir" name='imprimir' id='btnProcessar'>
        </form>
      </center>
    </div>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script type="text/javascript">

$('letra_fim').value      = "Z";
$('aMesCompetencia').value = 1;

$("btnProcessar").observe("click", function() {
  
  var sLocation  = "soc2_beneficiosfamilia002.php?";
  sLocation += "sCodigoFamiliar="+$F('as15_codigofamiliarcadastrounico');
  sLocation += "&sLetraInicio="+$F('letra_inicio')+"&sLetraFinal="+$F('letra_fim');
  sLocation += "&iMes="+$F('aMesCompetencia')+"&sAno="+$F('sAnoCompetencia');
  sLocation += "&sQuebraPagina="+$('quebra_pagina').checked;
  jan = window.open(sLocation,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);  
});

function js_pesquisaCodigoFamiliar(lMostra) {

	var sTipoRetorno = "relatorio";
  if (lMostra == true) {

  	js_OpenJanelaIframe('top.corpo', 
  	  	                'db_iframe_cidadaofamilia', 
  	  	                'func_cidadaofamilia.php?'+
  	  	                'funcao_js=parent.js_mostracodigofamiliar1|as15_codigofamiliarcadastrounico|ov02_nome', 
  	  	                'Pesquisar Código da Família', 
  	  	                true
  	  	               );
  } else {

  	if (document.form1.as15_codigofamiliarcadastrounico.value != '') {

    	js_OpenJanelaIframe('top.corpo', 
                          'db_iframe_cidadaofamilia', 
                          'func_cidadaofamilia.php?pesquisa_chave='+document.form1.as15_codigofamiliarcadastrounico.value+
                                                 '&sTipoRetorno='+sTipoRetorno+
                                                 '&funcao_js=parent.js_mostracodigofamiliar', 
                          'Pesquisar Código da Família', 
                          false
                         );
  	} else {
      document.form1.as15_codigofamiliarcadastrounico.value = '';
  	}
  }
}

function js_mostracodigofamiliar(chave1, erro, chave2) {

  document.form1.ov02_nome.value = chave2;
  if (erro == true) {

  	document.form1.as15_codigofamiliarcadastrounico.focus();
  	document.form1.as15_codigofamiliarcadastrounico.value = '';
  	document.form1.ov02_nome.value                        = chave1;
  }
}

function js_mostracodigofamiliar1(chave1, chave2) {

	document.form1.as15_codigofamiliarcadastrounico.value = chave1;
	document.form1.ov02_nome.value                        = chave2;
	db_iframe_cidadaofamilia.hide();
}

</script>