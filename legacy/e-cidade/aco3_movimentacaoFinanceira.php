<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("ac16_deptoresponsavel");
$oRotulo->label("e60_codemp");
$oRotulo->label("ac50_descricao");
$oRotuloAcordo = new rotulo("acordo");
$oRotuloAcordo->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" CONTENT="0" />
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAncora.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="width: 600px; margin: 0 auto;">
    <center>
      <input type="hidden" id="iNumeroEmpenhoInicial" />
      <input type="hidden" id="iNumeroEmpenhoFinal"   />
      <br><br>
      <fieldset>
        <legend><b>Filtros</b></legend>
        <table>

          <tr>
            <td title="<?php echo $Tac16_deptoresponsavel;?>" id="tdDepartamento" nowrap></td>
            <td>
              <input
                type   = "text"
                name   = "iCodigoDepartamento"
                id     = "iCodigoDepartamento"
                style  = "width: 90px;"
                onblur = "js_buscaDepartamento();"/>
            </td>
            <td>
              <input
                type   = "text"
                name   = "sDescricaoDepartamento"
                id     = "sDescricaoDepartamento"
                style  = "width: 350px; background-color: rgb(222, 184, 135);"
                readonly />
            </td>
          </tr>

          <tr>
            <td title="<?php echo $Te60_codemp?>" id="tdEmpenhoDe"></td>
            <td>
              <input
                type  = "text"
                name  = "iCodigoEmpenhoInicial"
                id    = "iCodigoEmpenhoInicial"
                style = "width: 90px; background-color: rgb(222, 184, 135);"
                readonly />
            </td>

            <td title="<?php echo $Te60_codemp?>">
              <span id="tdEmpenhoAte"></span>
              <input
                type   = "text"
                name   = "iCodigoEmpenhoFinal"
                id     = "iCodigoEmpenhoFinal"
                style  = "width: 90px; background-color: rgb(222, 184, 135);"
                readonly />
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=@$Tac50_descricao?>">
  		        <?php
  		          db_ancora('<b>Categoria:</b>', "onchange=pesquisaCategoria(true)", 1);
  		        ?>
  		      </td>
  		      <td>
  		        <?php
  		          db_input('ac50_sequencial', 10, $Iac50_descricao, true, 'text', 1,
  		                   "style='width: 90px;' onchange=pesquisaCategoria(false)");
  		          ?>
            </td>
            <td>
              <?php
  		          db_input('ac50_descricao', 47, $Iac50_descricao, true, 'text', 3);
  		        ?>
  		      </td>
  		    </tr>

          <tr>
            <td colspan="4">
              <div id="sContainer" style="width: 615px;"></div>
            </td>
          </tr>

          <tr>
            <td>
              <b>Vigência do Contrato:</b>
            </td>
            <td colspan="3">
              <?php
                 db_inputdata('dtVigenciaInicial', '', '', '', true, 'text', 1, "");
                 echo " <b>até:</b> ";
                 db_inputdata('dtVigenciaFinal', '', '', '', true, 'text', 1, "");
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <b>Origem:</b>
            </td>
            <td colspan="2">
              <select name="iOrigem" id="iOrigem">
                <option value="0">Todos</option>
                <option value="6">Empenho</option>
                <option value="1">Processo de Compras</option>
                <option value="2">Licitação</option>
                <option value="3">Manual</option>
              </select>
            </td>
          </tr>

          <tr>
            <td colspan="3">
            </td>
          </tr>

        </table>
      </fieldset>

      <p>
        <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onclick="js_imprimir()"/>
      </p>
    </center>
  </body>
</html>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function js_mostraDepartamento(iCodigoDepartamento, sDescricaoDepartamento) {

  $("iCodigoDepartamento").value    = iCodigoDepartamento;
  $("sDescricaoDepartamento").value = sDescricaoDepartamento;
  db_iframe_db_depart.hide();
}

function js_mostraDepartamento2(sRetorno, lErro) {

  $('sDescricaoDepartamento').value = sRetorno;
  if (lErro == true) {

    $('iCodigoDepartamento').focus();
    $('iCodigoDepartamento').value = '';
  }
}

function js_buscaDepartamento() {

  if ($('iCodigoDepartamento').value != '') {

    js_OpenJanelaIframe('',
      'db_iframe_comissao',
      'func_db_depart.php?pesquisa_chave='+$F('iCodigoDepartamento')+
      '&funcao_js=parent.js_mostraDepartamento2',
      'Pesquisar',
      false,
      '0');
  } else {
    $('sDescricaoDepartamento').value = '';
  }
}

/**
 * Formata o campo Empenho De
 */
function js_formataEmpenhoDe(iNumeroEmpenho, iCodigoEmpenho, iAnousu) {

  $("iCodigoEmpenhoInicial").value = iCodigoEmpenho + "/" + iAnousu;
  $("iNumeroEmpenhoInicial").value = iNumeroEmpenho;
  db_iframe_empempenho.hide();
}

/**
 * Formata o campo Empenho até
 */
function js_formataEmpenhoAte(iNumeroEmpenho, iCodigoEmpenho, iAnousu) {

  $("iCodigoEmpenhoFinal").value = iCodigoEmpenho + "/" + iAnousu;
  $("iNumeroEmpenhoFinal").value = iNumeroEmpenho;
  db_iframe_empempenho.hide();
}

/**
 * Lançador de contratos
 */
function js_criarDBLancador() {

  var iCodigoCategoria = $("ac50_sequencial").value;

  var sQueryString = "descricao=true&isLancador=true&iCodigoCategoria="+iCodigoCategoria;

  oLancadorContrato = new DBLancador("oLancadorContrato");
  oLancadorContrato.setNomeInstancia("oLancadorContrato");
  oLancadorContrato.setLabelAncora("Código acordo: ");
  oLancadorContrato.setParametrosPesquisa("func_acordoinstit.php", ['ac16_sequencial', 'ac16_resumoobjeto'], sQueryString);
  oLancadorContrato.show($("sContainer"));
}


//ANCORA EMPENHO DE
oAncoraEmpenhoDe = new DBAncora("Empenho de:", "#");
oElemento        = document.getElementById("tdEmpenhoDe");

oAncoraEmpenhoDe.onClick(function () {

  var oParametros = {

    sFontePesquisa   : "func_empempenho.php",
    aCamposRetorno   : ["e60_numemp", "e60_codemp", "e60_anousu"],
    sStringAdicional : ""
  };

  var sIframe = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');
  var sQuery  = oParametros.sFontePesquisa;

  sQuery += '?funcao_js=parent.js_formataEmpenhoDe|';
  sQuery += oParametros.aCamposRetorno.join("|");
  sQuery += oParametros.sStringAdicional == "" ? "" : '&' + oParametros.sStringAdicional;

  js_OpenJanelaIframe('',
    sIframe,
    sQuery,
    'Pesquisa',
    true);

});
oAncoraEmpenhoDe.show(oElemento);

//ANCORA EMPENHO ATÉ
oAncoraEmpenhoAte = new DBAncora("Até:", "#");
oElemento         = document.getElementById("tdEmpenhoAte");

oAncoraEmpenhoAte.onClick(function () {

  var oParametros = {

    sFontePesquisa   : "func_empempenho.php",
    aCamposRetorno   : ["e60_numemp", "e60_codemp", "e60_anousu"],
    sStringAdicional : ""
  };

  var sIframe = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');
  var sQuery  = oParametros.sFontePesquisa;

  sQuery += '?funcao_js=parent.js_formataEmpenhoAte|';
  sQuery += oParametros.aCamposRetorno.join("|");
  sQuery += oParametros.sStringAdicional == "" ? "" : '&' + oParametros.sStringAdicional;

  js_OpenJanelaIframe('',
    sIframe,
    sQuery,
    'Pesquisa',
    true);
});
oAncoraEmpenhoAte.show(oElemento);

//ANCORA DEPARTAMENTO
oAncoraDepartamento = new DBAncora("Departamento Responsável:", "#");
oElemento           = document.getElementById("tdDepartamento");

oAncoraDepartamento.onClick(function () {

  var oParametros = {

    sFontePesquisa   : "func_db_depart.php",
    aCamposRetorno   : ["coddepto", "descrdepto"],
    sStringAdicional : ""
  };

  var sQuery  = oParametros.sFontePesquisa;
  var sIframe = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');

  sQuery += '?funcao_js=parent.js_mostraDepartamento|';
  sQuery += oParametros.aCamposRetorno.join("|");
  sQuery += oParametros.sStringAdicional == "" ? "" : '&' + oParametros.sStringAdicional;

  js_OpenJanelaIframe('',
    sIframe,
    sQuery,
    'Pesquisa',
    true);
});

oAncoraDepartamento.show(oElemento);

function js_imprimir() {

  var dtVigenciaInicial     = $F("dtVigenciaInicial");
  var dtVigenciaFinal       = $F("dtVigenciaFinal");
  var iCodigoDepartamento   = $F("iCodigoDepartamento");
  var iOrigem               = $F("iOrigem");
  var iCodigoEmpenhoInicial = $F("iCodigoEmpenhoInicial");
  var iCodigoEmpenhoFinal   = $F("iCodigoEmpenhoFinal");
  var iNumeroEmpenhoInicial = $F("iNumeroEmpenhoInicial");
  var iNumeroEmpenhoFinal   = $F("iNumeroEmpenhoFinal");
  var iCategoria            = $F("ac50_sequencial");
  var sCategoria            = $F("ac50_descricao");
  var oContratos            = oLancadorContrato.getRegistros();
  var aContratos            = new Array();

  for (var iContrato = 0; iContrato < oContratos.length; iContrato++) {
    aContratos.push(oContratos[iContrato].sCodigo);
  }

  if (dtVigenciaInicial != '' && dtVigenciaFinal != '') {

    if( !js_comparadata(dtVigenciaInicial, dtVigenciaFinal, '<=') ) {

	    alert("A vigência de Início deve ser maior ou igual a vigência de Fim!");
	    return false;
	  }
  }

  var sQuery  = "";
      sQuery += "?dtVigenciaInicial="     + dtVigenciaInicial;
      sQuery += "&dtVigenciaFinal="       + dtVigenciaFinal;
      sQuery += "&iCodigoDepartamento="   + iCodigoDepartamento;
      sQuery += "&iOrigem="               + iOrigem;
      sQuery += "&iCodigoEmpenhoInicial=" + iCodigoEmpenhoInicial;
      sQuery += "&iCodigoEmpenhoFinal="   + iCodigoEmpenhoFinal;
      sQuery += "&iNumeroEmpenhoInicial=" + iNumeroEmpenhoInicial;
      sQuery += "&iNumeroEmpenhoFinal="   + iNumeroEmpenhoFinal;
      sQuery += "&iCategoria="            + iCategoria;
      sQuery += "&sCategoria="            + sCategoria;
      sQuery += "&aContratos="            + aContratos;


  var oJanela = window.open('aco3_movimentacaoFinanceira002.php' + sQuery, 'relatorioacordo',
                    'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0');
  oJanela.moveTo(0,0);
  return true;
}


function pesquisaCategoria(lMostra) {

  var sFuncaoPesquisa   = 'func_acordocategoria.php?funcao_js=parent.js_mostraCategoria|';
      sFuncaoPesquisa  += 'ac50_sequencial|ac50_descricao';

  if (!lMostra) {

    if ($('ac50_sequencial').value != '') {

      sFuncaoPesquisa   = "func_acordocategoria.php?pesquisa_chave="+$F('ac50_sequencial');
      sFuncaoPesquisa  += "&funcao_js=parent.js_completaCategoria";
     } else {
      $('ac50_descricao').value = '';
     }
  }
  js_criarDBLancador();
  js_OpenJanelaIframe('top.corpo', 'db_iframe_acordocategoria', sFuncaoPesquisa, 'Pesquisar Categorias de Acordo',lMostra);
}

function js_completaCategoria(chave1, chave2) {

  $('ac50_descricao').value  = chave1;
  $('ac50_sequencial').focus();
  js_criarDBLancador();
  db_iframe_acordocategoria.hide();
}

function js_mostraCategoria(chave1, chave2) {

  $('ac50_sequencial').value = chave1;
  $('ac50_descricao').value  = chave2;
  $('ac50_sequencial').focus();
  js_criarDBLancador();
  db_iframe_acordocategoria.hide();
}
js_criarDBLancador();
</script>
