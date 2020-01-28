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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/BusinessException.php");
require_once("dbforms/db_classesgenericas.php");

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

$sTituloArquivo = "Programas Tem�ticos";
if ($oGet->iTipo == 4) {
  $sTituloArquivo = "Programas de Gest�o, Manuten��o e Servi�os";
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
  <center>
    <fieldset class='fieldset_global'>

      <legend>
        <b> <?php echo $sTituloArquivo; ?> </b>
      </legend>

      <div id='ctnPPALei' class='ppa'>
          <table>
            <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisaPPALei(true);",1);
                ?>
              </td>
              <td colspan='5'>
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',1," onchange='js_pesquisaPPALei(false);'");
                db_input('o01_descricao',45,$Io01_descricao,true,'text',3,'')
                ?>
              </td>
            </tr>
             <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <b>Vers�o:</b>
              </td>
              <td id='verppa'>

              </td>
            </tr>
      </div>

      <div id="ctnObjetivo" class='lancador_objetivos'>
        <tr>
        <td nowrap>
          <b>Tipo de Programa:</b>
        </td>
          <td>
            <?php
            $aTipoPrograma = array('0' => "Todos",
                                    '3' => "Programas Tem�ticos");

            if ($oGet->iTipo == 4) {
              $aTipoPrograma    = array('0' => "Todos",
                                         '4' => "Programas de Gest�o, Manuten��o e Servi�os");
            }

            db_select("tipo_programa", $aTipoPrograma, true, 1, "class='db_select'");
            ?>
          </td>
        </tr>
        <tr>
        <td nowrap>
          <b>Modelo:</b>
        </td>
          <td>
            <?php
            $aTipoModelo = array('1' => "PPA",
                                 '2' => "LDO");

            db_select("tipo_modelo", $aTipoModelo, true, 1, "class='db_select'");
            ?>
          </td>
        </tr>     
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
            <input type="button" onClick="js_lancarPrograma()" value="Lan�ar" />
          </td>

        </tr>
        
        
      </table>

      <fieldset style="margin-top:10px">
        <legend>Programa(s) Selecionado(s)</legend>
        <div  id="gridprograma" style="width: 580px;">
        </div>
      </fieldset>

      </div>

    </fieldset>

     <div id="ctnBotao" class='ctnbotao'>
      <input type="button" id="btnImprimir"  name="btnImprimir"  value="Imprimir" onclick="js_imprimir();"/>
    </div>
    </center>
  </body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>
var sUrl            = "orc1_programa.RPC.php";
var aProgramas      = new Array();
var oGridProgramas  = js_montaGrid();
js_drawSelectVersaoPPA($('verppa'));

/**
 * fun��o responsavel por montar grid
 */
function js_montaGrid() {

  var aAlinhamentos = new Array();
  var aHeader       = new Array();
  var aWidth        = new Array();

  aHeader[0]       = 'Programa';
  aHeader[1]       = 'Descri��o';
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
 * Fun��o respons�vel por lan�ar o programa na grid
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

      alert("Programa j� adicionado.");
      return false;
    }
  }

  aProgramas.push(oPrograma);
  renderizarGrid(aProgramas);
  $('o54_programa').value = '';
  $('o54_descr').value    = '';

}

/**
 * fun��o respons�vel por remover programa lan�ado na grid
 */
function js_removeProgramaLancado(iIndice) {

  aProgramas.splice(iIndice, 1);
  renderizarGrid (aProgramas);
}

/**
 * Fun��o respons�vel por renderizar a grid de programas, mostrando todos programas selecionados
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
 * Fun��es para busca
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

  js_OpenJanelaIframe('top.corpo', 'db_iframe_orcprograma', sFuncao,'Pesquisar', lMostra, '10');
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
  js_OpenJanelaIframe('top.corpo', 'db_iframe_ppalei', sFunc, 'Pesquisa de Leis para o PPA', lMostra);
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
  js_divCarregando("Aguarde, enquanto s�o carregadas as vers�es...", "msgBox");
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

    alert("Necess�rio selecionar uma Lei e uma vers�o para imprimir o relat�rio.");
    return false;
  }

  if (aProgramas.length == 0) {

    var sMsgConfirma = "Nenhum programa selecionado.\n\nConfirma a impress�o?";
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

  var oGet = js_urlToObject();

  //Tipo do relat�rio, de acordo com menu acessado
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