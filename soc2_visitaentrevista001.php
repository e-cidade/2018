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

$oRotulo = new rotulocampo();
$oRotulo->label("as04_dataentrevista");
$oRotulo->label("as05_datavisita");

$aAlfabeto = array("A"=>"A", "B"=>"B", "C"=>"C", "D"=>"D", "E"=>"E", "F"=>"F", "G"=>"G", "H"=>"H", "I"=>"I", "J"=>"J",
                   "K"=>"K", "L"=>"L", "M"=>"M", "N"=>"N","O"=>"O","P"=>"P","Q"=>"Q","R"=>"R","S"=>"S","T"=>"T","U"=>"U",
                   "V"=>"V", "W"=>"W", "X"=>"X", "Y"=>"Y", "Z"=>"Z");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, prototype.js, strings.js, dates.js");
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
    <form action="">
      <fieldset style="width: 400px;">
        <legend><b>Filtros do relatório</b></legend>
        <fieldset class="fieldset-hr">  
          <legend><b>Filtros</b></legend>
          <table>
            <tr>
              <td nowrap="nowrap"><b>Data Atualização:</b></td>
              <td nowrap="nowrap">
                <?php
                  db_inputdata('dtAtualizacao_inicio', '', '', '', true, 'text', 1, "");
                ?>
              </td>
              <td nowrap="nowrap"><b>até:</b></td>
              <td nowrap="nowrap">
                <?php
                  db_inputdata('dtAtualizacao_fim', '', '', '', true, 'text', 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap"><b>Data Visita:</b></td>
              <td nowrap="nowrap">
                <?php
                  db_inputdata('dtVisita_inicio', '', '', '', true, 'text', 1, "");
                ?>
              </td>
              <td nowrap="nowrap"><b>até:</b></td>
              <td nowrap="nowrap">
                <?php
                  db_inputdata('dtVisita_fim', '', '', '', true, 'text', 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap"><b>Por Letra:</b></td>
              <td nowrap="nowrap">
                <?php db_select('letra_inicio', $aAlfabeto, true, 1, "");?>
              </td>
              <td nowrap="nowrap"><b>até:</b></td>
              <td nowrap="nowrap">
                <?php db_select('letra_fim', $aAlfabeto, true, 1, "");?>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class="fieldset-hr">  
          <legend><b>Visualização</b></legend>
          <table>
            <tr>
              <td nowrap="nowrap" colspan="4">
                <?php db_input('quebra_pagina', 50, '', true, 'checkbox', 1);?>
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

$('letra_fim').value = "Z";

/**
 * Quando clicado em Imprimir, processa as informações e envia ao fonte do relatório.
 */
$("btnProcessar").observe("click", function() {

  dtAtualizacaoInicio = js_formatar($F('dtAtualizacao_inicio'), 'd');
  dtAtualizacaoFim    = js_formatar($F('dtAtualizacao_fim'), 'd');
  dtVisitaInicio      = js_formatar($F('dtVisita_inicio'), 'd');
  dtVisitaFim         = js_formatar($F('dtVisita_fim'), 'd');

  if (dtAtualizacaoInicio != "" && dtAtualizacaoFim != "") {

    if (! js_verificaObjetoData(new Date(dtAtualizacaoInicio), new Date(dtAtualizacaoFim))) {
      
      alert("A Data final da Atualização maior que a data inicial.");
      return false;
    }
  }

  if (dtVisitaInicio != "" && dtVisitaFim != "") {

    if (! js_verificaObjetoData(new Date(dtVisitaInicio), new Date(dtVisitaFim))) {
      alert("A Data final da Visita maior que a data inicial.");
      return false;
    }
  }
  
  var sLocation  = "soc2_visitaentrevista002.php?";
  sLocation += "dtVisitaInicio="+dtVisitaInicio+"&dtVisitaFinal="+dtVisitaFim;
  sLocation += "&dtAtualizacaoInicio="+dtAtualizacaoInicio+"&dtAtualizacaoFinal="+dtAtualizacaoFim;
  sLocation += "&sLetraInicio="+$F('letra_inicio')+"&sLetraFinal="+$F('letra_fim');
  sLocation += "&sQuebraPagina="+$('quebra_pagina').checked;
  jan = window.open(sLocation,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);  
});

/**
 * Compara dois objetos de Date()  
 * Se oDataInicio < oDataFim retorna true
 * else retorna false
 */
function js_verificaObjetoData(oDataInicio, oDataFim) {

   if (oDataInicio.getTime() > oDataFim.getTime()) {
     return false;
   }
   return true;
}

</script>