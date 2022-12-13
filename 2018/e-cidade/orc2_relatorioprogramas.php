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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oListaOrgao       = new cl_arquivo_auxiliar;
$oDAOPPAEstimativa = new cl_ppaestimativa();
$oDAOPPAEstimativa->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label("o01_descricao");
$oRotulo->label("o01_descricao");
$oRotulo->label("o01_sequencial");

$oRotuloOrcobjetivo = new rotulo("orcobjetivo");
$oRotuloOrcobjetivo->label();

$oGet = db_utils::postMemory($_GET);

$sTituloArquivo = "Programas Temáticos";
if ($oGet->iTipo == 4) {
  $sTituloArquivo = "Programas de Gestão, Manutenção e Serviços";
}


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript"    type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript"    type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript"    type="text/javascript" src="scripts/prototype.js"></script>
  <script src="scripts/widgets/DBLancador.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
  <script src="scripts/datagrid.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
  <link href="estilos.css"             rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css"  rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
  <style>

    body {
      margin:0;
      padding:0;
    }
    .db_select {
      width:100%;
    }

    .ppa {
      width:610px;
      margin:0 auto;
    }

    .fieldset_global{
      width:560px;
      margin:0 auto;
    }

    .ctnbotao{
      position:relative;
      top:10px;
    }

  </style>
</head>

<body bgcolor="#CCCCCC" style="margin-top: 25px" >

<div class="container">
  <fieldset class='fieldset_global'>

    <legend>
      <b> <?php echo $sTituloArquivo; ?> </b>
    </legend>

    <div id='ctnPPALei' class='ppa'>
      <table>
        <tr>
          <td nowrap title="<?=@$To05_ppalei?>" style="width: 105px;">
            <label for="o05_ppalei">
              <?php
              db_ancora("<b>Lei do PPA:</b>","js_pesquisaPPALei(true);",1);
              ?>
            </label>
          </td>
          <td colspan='5'>
            <?
            $So05_ppalei = "Lei do PPA";
            db_input('o05_ppalei',10,$Io01_sequencial,true,'text',1," onchange='js_pesquisaPPALei(false);'");
            db_input('o01_descricao',45,$Io01_descricao,true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To05_ppaversao?>">
            <label for="o05_ppaversao"><b>Versão:</b></label>
          </td>
          <td id='verppa'>

          </td>
        </tr>
      </table>
    </div>

    <div id="ctnObjetivo" class='lancador_objetivos'>
      <table>
        <tr  style="width: 105px;">
          <td nowrap>
            <label for="tipo_programa"><b>Tipo de Programa:</b></label>
          </td>
          <td>
            <?php
            $aTipoPrograma = array('0' => "Todos",
                                   '3' => "Programas Temáticos");

            if ($oGet->iTipo == 4) {
              $aTipoPrograma    = array('0' => "Todos",
                                        '4' => "Programas de Gestão, Manutenção e Serviços");
            }

            db_select("tipo_programa", $aTipoPrograma, true, 1, "class='db_select'");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <?db_ancora('<b>Programa:</b>', 'js_pesquisaPrograma(true);', 1)?>
          </td>

          <td nowrap="nowrap" colspan='5'>
            <?php
            db_input('o54_programa', 10, @$Io54_programa, true, 'text', 1, " onchange='js_pesquisaPrograma(false);' ");
            db_input('o54_descr', 45,    @$Io54_descr, true, 'text', 3, "");
            ?>
          </td>

          <td>
            <input type="button" onClick="js_lancarPrograma()" value="Lançar" />
          </td>

        </tr>


      </table>

      <fieldset style="margin-top:10px">
        <legend>Programa(s) Selecionado(s)</legend>
        <div  id="gridprograma" style="width: 580px;">
        </div>
      </fieldset>
      <fieldset style="border-bottom: none; border-left: none; border-right: none;">
        <legend class="bold">Opções de Impressão</legend>
        <table>
          <tr>
            <td nowrap>
              <label for="tipo_modelo"><b>Modelo:</b></label>
            </td>
            <td>
              <?php
              $aTipoModelo = array('1' => "PPA", '2' => "LDO");
              db_select("tipo_modelo", $aTipoModelo, true, 1, "class='db_select'");
              ?>
            </td>
          </tr>
          <tr id="trIndices">
            <td><label for="indice_por_ano"><b>Imprimir anos e índices das Metas/Iniciativas:</b></label></td>
            <td>
              <select id="indice_por_ano">
                <option value="f" selected>Não</option>
                <option value="t">Sim</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>

    </div>

  </fieldset>

  <div id="ctnBotao" class='ctnbotao'>
    <input type="button" id="btnImprimir"  name="btnImprimir"  value="Imprimir" onclick="js_imprimir();"/>
  </div>
</div>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>


  var getParameters = js_urlToObject();

  var selectModelo  = $('tipo_modelo');
  selectModelo.style.width = '60px';
  var selectIndices = $('indice_por_ano');
  selectIndices.style.width = '60px';

  if (Number(getParameters.iTipo) === 4) {
    $('trIndices').style.display = 'none';
    selectIndices.value = 'f';
  }

  var sUrl            = "orc1_programa.RPC.php";
  var aProgramas      = new Array();
  var oGridProgramas  = js_montaGrid();
  js_drawSelectVersaoPPA($('verppa'));

  /**
   * função responsavel por montar grid
   */
  function js_montaGrid() {

    var aAlinhamentos = new Array();
    var aHeader       = new Array();
    var aWidth        = new Array();

    aHeader[0]       = 'Programa';
    aHeader[1]       = 'Descrição';
    aHeader[2]       = 'Remover';

    aWidth[0]        = '15%';
    aWidth[1]        = '70%';
    aWidth[2]        = '15%';

    aAlinhamentos[0] = 'left';
    aAlinhamentos[1] = 'left';
    aAlinhamentos[2] = 'center';

    oGridProgramas              = new DBGrid('gridprograma');
    oGridProgramas.sName        = 'datagridProgramas';
    oGridProgramas.nameInstance = 'oGridProgramas';
    oGridProgramas.setCellWidth( aWidth );
    oGridProgramas.setCellAlign( aAlinhamentos );
    oGridProgramas.setHeader( aHeader );
    oGridProgramas.allowSelectColumns(true);
    oGridProgramas.show( $('gridprograma') );
    oGridProgramas.clearAll(true);
    return oGridProgramas;
  }


  /**
   * Função responsável por lançar o programa na grid
   **/
  function js_lancarPrograma() {

    var sDescricaoPrograma = $F('o54_descr');

    if ( sDescricaoPrograma == '' ) {
      return false;
    }

    oPrograma                    = new Object();
    oPrograma.iPrograma          = $F('o54_programa');
    oPrograma.sDescricaoPrograma = sDescricaoPrograma;
    oPrograma.iIndice            = aProgramas.length;

    for ( var iIndice = 0; iIndice < aProgramas.length; iIndice++ ) {

      if(aProgramas[iIndice].iPrograma == oPrograma.iPrograma) {

        alert("Programa já adicionado.");
        return false;
      }
    }

    aProgramas.push(oPrograma);
    renderizarGrid(aProgramas);
    $('o54_programa').value = '';
    $('o54_descr').value    = '';

  }

  /**
   * função responsável por remover programa lançado na grid
   */
  function js_removeProgramaLancado(iIndice) {

    aProgramas.splice(iIndice, 1);
    renderizarGrid (aProgramas);
  }

  /**
   * Função responsável por renderizar a grid de programas, mostrando todos programas selecionados
   * utiliza o array de programas
   */
  function renderizarGrid (aProgramas) {

    oGridProgramas.clearAll(true);

    for ( var iIndice = 0; iIndice < aProgramas.length; iIndice++ ) {

      oPrograma  = aProgramas[iIndice];
      var aLinha = new Array();
      aLinha[0]  = oPrograma.iPrograma;
      aLinha[1]  = oPrograma.sDescricaoPrograma;
      sDisabled  = '';
      aLinha[2]  = '<input type="button" value="Remover" onclick="js_removeProgramaLancado(' + iIndice + ')" ' + sDisabled + ' />';
      oGridProgramas.addRow(aLinha, null, null, true);
    }

    oGridProgramas.renderRows();
  }

  /**
   * Funções para busca
   */
  function js_pesquisaPrograma(lMostra) {

    var iAno      = <?=db_getsession("DB_anousu"); ?>;
    var iTipo     = $F("tipo_programa");
    var sPesquisa = 'func_orcprograma.php';
    var sFuncao   = sPesquisa+'?iTipo='+iTipo+'&iAno='+iAno+'&funcao_js=parent.js_mostraPrograma|o54_programa|o54_descr';

    if (lMostra == false) {

      var iPrograma = $F('o54_programa');
      sFuncao       = sPesquisa+'?iTipo='+iTipo+'&iAno='+iAno+'&pesquisa_chave='+iPrograma+'&funcao_js=parent.js_completaPrograma';
    }

    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcprograma', sFuncao,'Pesquisar', lMostra, '10');
  }

  function js_completaPrograma(sDescricao, lErro) {

    $('o54_descr').value = sDescricao;

    if (lErro) {
      $('o54_programa').value = '';
    }
  }

  function js_mostraPrograma (iCodigo, sDescricao) {

    $('o54_programa').value = iCodigo;
    $('o54_descr').value = sDescricao;
    db_iframe_orcprograma.hide();
  }


  function js_pesquisaPPALei(lMostra) {

    var  sFunc = 'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao';

    if (!lMostra) {
      sFunc = 'func_ppalei.php?pesquisa_chave='+$F('o05_ppalei')+'&funcao_js=parent.js_mostrappalei';
    }
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_ppalei', sFunc, 'Pesquisa de Leis para o PPA', lMostra);
  }


  function js_mostrappalei(chave, erro) {

    $('o01_descricao').value = chave;
    js_limpaComboBoxPerspectivaPPA();
    trocaLei();

    if(erro==true) {
      $('o05_ppalei').focus();
      $('o05_ppalei').value = '';
      js_limpaComboBoxPerspectivaPPA();
    }
  }

  function js_mostrappalei1(chave1,chave2){

    $('o05_ppalei').value    = chave1;
    $('o01_descricao').value = chave2;
    db_iframe_ppalei.hide();
    js_divCarregando("Aguarde, enquanto são carregadas as versões...", "msgBox");
    trocaLei();
    js_removeObj("msgBox");
  }

  function trocaLei() {
    js_getVersoesPPA($F('o05_ppalei'));
  }

  function js_imprimir () {

    var iVersao = $F('o05_ppaversao');
    var iLei    = $F('o05_ppalei');
    var iModeloRelatorio = $F('tipo_modelo');

    if (!iVersao  || !iLei || iVersao == 0)  {

      alert("Necessário selecionar uma Lei e uma versão para imprimir o relatório.");
      return false;
    }

    if (aProgramas.length == 0) {

      var sMsgConfirma = "Nenhum programa selecionado.\n\nConfirma a impressão?";
      if (!confirm(sMsgConfirma)) {
        return false;
      }
    }

    var aProgramasCodigo   = new Array();

    aProgramas.each(function (oPrograma, iIndice){
      aProgramasCodigo.push(oPrograma.iPrograma);
    });

    var sProgramas = aProgramasCodigo.join();
    var iLei       = $F("o05_ppalei");
    var iVersao    = $F("o05_ppaversao");
    var iTipo      = $F("tipo_programa");

    var sQuery  = "&siLei="            + iLei;
    sQuery += "&iVersao="          + iVersao;
    sQuery += "&sProgramas="       + sProgramas;
    sQuery += "&iTipo="            + iTipo;
    sQuery += "&iModeloRelatorio=" + iModeloRelatorio;
    sQuery += "&imprimirIndices=" + selectIndices.value;

    var oGet = js_urlToObject();

    //Tipo do relatório, de acordo com menu acessado
    switch(oGet.iTipo) {

      case '3':
        var sRelatorio = 'orc2_relatorioprogramastematicos.php';
        break;

      case '4':
        var sRelatorio = 'orc2_relatorioprogramastipogestao.php';
        break;
    }

    oJanela = window.open(sRelatorio+'?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);
  }
</script>