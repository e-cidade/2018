<?PHP
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_placaixa_classe.php");
require_once("classes/db_placaixarec_classe.php");
require_once("dbforms/db_classesgenericas.php");

$clplacaixa    = new cl_placaixa;
$clplacaixarec = new cl_placaixarec;
$clrotulo      = new rotulocampo;

$clplacaixa->rotulo->label();
$clrotulo->label("nomeinst");

$clplacaixarec->rotulo->label();
$clrotulo->label("k80_data");
$clrotulo->label("k13_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("k02_drecei");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_codigo");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("q02_inscr");
$clrotulo->label("j01_matric");

$db_opcao = 1;

/*
 * definimos qual funcao sera usada para consultar a matricula.
* se o campo db_config.db21_usasisagua for true, usamos a func_aguabase.
* se for false, usamos a func_iptubase
*/
$oDaoDBConfig = db_utils::getDao("db_config");
$rsInstit     = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_file(db_getsession("DB_instit")));
$oInstit      = db_utils::fieldsMemory($rsInstit, 0);
$sFuncaoBusca = "js_pesquisaMatricula";
if ($oInstit->db21_usasisagua == "t") {
  $sFuncaoBusca = "js_pesquisa_agua";
}
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?PHP
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("datagrid.widget.js");
    db_app::load("strings.js");
    db_app::load("grid.style.css");
    db_app::load("estilos.css");
    db_app::load("classes/dbViewAvaliacoes.classe.js");
    db_app::load("widgets/windowAux.widget.js");
    db_app::load("widgets/dbmessageBoard.widget.js");
    db_app::load("dbcomboBox.widget.js");
  ?>
<style>

  #k81_origem {
    width: 95px;
  }
  .tamanho-primeira-col{
    width:150px;
  }

  .input-menor {
    width:100px;
  }

  .input-maior {
    width:400px;
  }

   #k81_codigo {
     width: 95px;
   }
   #k81_codigodescr {
     width: 77%;
   }

   #k81_obs {
     width:100%;
     height: 50px;
   }



</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>

<form name="form1" method="post" action="<?=$db_action?>">

<fieldset style="margin-top: 30px; width: 800px;">
  <legend><strong>Planilha de Arrecadação</strong></legend>
  <fieldset style='width:95%;'>
    <legend><strong>Dados da Planilha</strong></legend>

    <table  width="100%" border="0">
      <!-- Número da Planilha -->
      <tr>
        <td class='tamanho-primeira-col' nowrap><strong>Código da Planilha:</strong></td>
        <td>
          <?
          db_input('k80_codpla',10, $Ik80_codpla,true,'text',3,"")
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap width="50px"><strong>Data:</strong></td>
        <td>
          <?php
          db_inputdata('k80_data',@$k80_data_dia,@$k80_data_mes,@$k80_data_ano,true,'text',3,"")
          ?>
        </td>
      </tr>


        <tr>
          <td nowrap="nowrap">
            <strong>Processo Administrativo:</strong>
          </td>

          <td >
             <? db_input('k144_numeroprocesso', 10, null, true, 'text', 3, null,null,null,null,15);?>
          </td>
        </tr>

  </table>
  </fieldset>





<br>
<div id='ctnReceitas' style="margin-top: 20px;">
</div>
</fieldset>

<input type="button" value='Estornar Planilha' id='salvar' style="margin-top: 10px;" onclick='js_estornarPlanilha()'/>
<input type="button" value='Pesquisa Planilha' id='salvar' style="margin-top: 10px;" onclick='js_pesquisaPlanilha()'/>
</form>

</center>

<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>

<script>

/**
 * função para montar a grid de receitas:
 */


 var oGridReceitas;
 var aReceitas       = [];
 var iIndiceReceitas = 0;
 var iAlteracao      = null;
 var sRPC            = 'cai4_planilhaarrecadacao.RPC.php';

 function js_gridReceitas() {

   oGridReceitas = new DBGrid('ctnReceitas');
   oGridReceitas.nameInstance = 'oGridReceitas';
   oGridReceitas.allowSelectColumns(true);
   oGridReceitas.setCellWidth(['1%',
                               '40%',
                               '40%',
                               '20%']);

   oGridReceitas.setCellAlign(['center',
                               'left',
                               'left',
                               'right']);


   oGridReceitas.setHeader(['Indice',
                            'Conta da Contabilidade',
                            'Conta da Tesouraria',
                            'Valor']);


   oGridReceitas.aHeaders[0].lDisplayed = false;

   oGridReceitas.setHeight(100);
   oGridReceitas.show($('ctnReceitas'));
   js_limpaGrid();

  }

function js_limpaGrid(){

  document.form1.reset();
  oGridReceitas.clearAll(true);
  aReceitas       = [];
  iIndiceReceitas = 0;
  iAlteracao      = null;
}

/**
 * Função para redesenhar a grid na tela
 */
function js_renderizarGrid() {

  oGridReceitas.clearAll(true);
  for (var iIndice in aReceitas) {

    var oReceita = aReceitas[iIndice];

    if (typeof(oReceita) == 'function') {
      continue;
    }
    var aRow = [];
    aRow[0]  = iIndice;
    aRow[1]  = oReceita.k81_conta + " - " + oReceita.k13_descr;
    aRow[2]  = oReceita.k81_receita + " - " + oReceita.k02_drecei;
    aRow[3]  = js_formatar(oReceita.k81_valor, "f");
    oGridReceitas.addRow(aRow);
  }
  oGridReceitas.renderRows();
}



/**
 * Funções para buscar dados de planilha
 */
function js_pesquisaPlanilha() {

  js_limpaGrid();
  js_OpenJanelaIframe('top.corpo','db_iframe_placaixa','func_placaixa.php?lAutenticada=true&funcao_js=parent.js_getPlanilha|k80_codpla','Pesquisa',true);
}


function js_getPlanilha(iCodigoPlanilha) {

  db_iframe_placaixa.hide();
  js_divCarregando("Aguarde, importando dados da planilha...", "msgBox");

  var oParametro       = {};
  oParametro.exec      = 'importarPlanilha';
  oParametro.iPlanilha = iCodigoPlanilha;

  var oAjax            = new Ajax.Request(sRPC,
      {
       method: 'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_completaImportar
       });
}



function js_completaImportar (oAjax) {

  js_removeObj('msgBox');
  var oRetorno          = eval("("+oAjax.responseText+")");
  $('k80_codpla').value = oRetorno.oPlanilha.iPlanilha;
  $('k80_data').value   = oRetorno.oPlanilha.dtDataCriacao;

  if (oRetorno.oPlanilha.k144_numeroprocesso != null) {
    $('k144_numeroprocesso').value = oRetorno.oPlanilha.k144_numeroprocesso.urlDecode();
  }



  //Adiciona as novas receitas importadas ao array de receitas
  oRetorno.oPlanilha.aReceitas.each(
      function ( oReceita ) {

        var oReceitaImportada            = {};
        oReceitaImportada.k81_receita    = oReceita.iReceita;

        oReceitaImportada.k81_origem     = oReceita.iOrigem;
        oReceitaImportada.k81_numcgm     = oReceita.iCgm;
        oReceitaImportada.q02_inscr      = oReceita.iInscricao;
        oReceitaImportada.j01_matric     = oReceita.iMatricula;

        oReceitaImportada.c58_sequencial = oReceita.iCaracteriscaPeculiar;
        oReceitaImportada.k81_conta      = oReceita.iContaTesouraria;
        oReceitaImportada.k81_datareceb  = oReceita.dtRecebimento;
        oReceitaImportada.k81_obs        = oReceita.sObservacao.urlDecode();
        oReceitaImportada.k81_codigo     = oReceita.iRecurso;
        oReceitaImportada.k81_valor      = oReceita.nValor;

        oReceitaImportada.k13_descr      = oReceita.sDescricaoConta.urlDecode();
        oReceitaImportada.k02_drecei     = oReceita.sDescricaoReceita.urlDecode();

        //Adiciona índice na receita e adiciona no array de receitas (cria propriedade no objeto)
        oReceitaImportada.iIndice        = "a"+iIndiceReceitas;
        aReceitas["a"+iIndiceReceitas]   = oReceitaImportada;
        iIndiceReceitas++;
    });
  js_renderizarGrid();
}

/**
 *
 * @returns {boolean}
 */
function js_estornarPlanilha() {

  if ($F('k80_codpla') == '') {
    alert("Informe o código de uma planilha.")
  }

  if (!confirm("Confirma a estorno da planilha "+$F('k80_codpla')+"?")) {
    return false;
  }

  var oParam       = new Object();
  oParam.iPlanilha =  $F('k80_codpla');
  oParam.exec      = 'estornarPlanilha';

  js_divCarregando("Aguarde, estornando planilha...", "msgBox");

  var oAjax        = new Ajax.Request(sRPC,
      {
       method: 'post',
       parameters: 'json='+Object.toJSON(oParam),
       onComplete: js_completaEstorno
       });
}


function js_completaEstorno(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());

  if(oRetorno.status != 2) {
    document.form1.reset();
    oGridReceitas.clearAll(true);
  }
  js_pesquisaPlanilha();
}


js_gridReceitas();
js_pesquisaPlanilha();
</script>