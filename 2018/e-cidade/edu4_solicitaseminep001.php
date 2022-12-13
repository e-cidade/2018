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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
db_postmemory($_POST);
$oRotuloCampo = new rotulocampo();
$oRotuloCampo->label("ed52_d_inicio");
$oRotuloCampo->label("ed52_d_fim");

$oDaoCalendarioEscola = db_utils::getdao("calendarioescola");
$db_opcao =  1;
$iEscola  = db_getsession("DB_coddepto");


$iAno = date("Y");

if ( isset($ed52_i_ano)) {
  $iAno = $ed52_i_ano;
}

if ( !empty($iAno)) {

  for ($iConta = 0; $iConta <= 31; $iConta++) {

    $iDiferencaTempo   = mktime(0, 0, 0, 5, $iConta, $iAno);

    $iDiaSemana = date("w", $iDiferencaTempo);

    if ( $iDiaSemana == 3) {

      $data_censo_dia = strlen($iConta) == 1?"0".$iConta:$iConta;
      $data_censo_mes = "05";
      $data_censo_ano = $iAno;
    }
  }
}
$sEscolaOrder    = " ed52_d_inicio asc, ed52_d_fim desc ";
$sCampos         = " ed52_d_inicio , ed52_d_fim ";
$sWhere          = " ed52_i_ano = $iAno AND ed38_i_escola = $iEscola ";

$sSqlAnoCenso    = $oDaoCalendarioEscola->sql_query("",$sCampos, $sEscolaOrder ,"$sWhere");
$rsAnoCenso      = $oDaoCalendarioEscola->sql_record($sSqlAnoCenso);
$oDadosInicioFim = db_utils::fieldsmemory($rsAnoCenso,0);


$bVerif = false;

$ed52_d_inicio     = db_formatar($oDadosInicioFim->ed52_d_inicio,'d');
$aDataIni          = explode ('/',$ed52_d_inicio);
$ed52_d_inicio_dia = $aDataIni[0];
$ed52_d_inicio_mes = $aDataIni[1];
$ed52_d_inicio_ano = $aDataIni[2];

$ed52_d_fim        = db_formatar($oDadosInicioFim->ed52_d_fim,'d');
$aDataFim          = explode ('/',$ed52_d_fim);
$ed52_d_fim_dia = $aDataFim[0];
$ed52_d_fim_mes = $aDataFim[1];
$ed52_d_fim_ano = $aDataFim[2];


?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, strings.js, datagrid.widget.js, prototype.js, arrays.js");
      db_app::load("estilos.css, grid.style.css");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
     .interno {

        border:0px;
        border-top: 2px groove white;
     }
     div.formulario table tr td:FIRST-CHILD {
       width:100px;
     }
    </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin-top: 25px'>
    <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
    <form name="form1" method="post" action="" align = "center">
    <center>
    <div style="display: table;text-align: center" class='formulario'>
      <fieldset >
        <legend><b>Gerar Arquivo de solicitação de código INEP - Docente/Aluno </b></legend>
        <table border="0">
          <tr>
            <td>
              <fieldset style="border:0px">
                <table>
                  <tr>
                    <td>
                      <b>Data do Censo:</b>
                    </td>
                    <td>
                      <?db_inputdata('data_censo',@$data_censo_dia,@$data_censo_mes,@$data_censo_ano,true,'text',1,
                                     "onchange=\"js_ano();\"","","","parent.js_ano();")?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Ano do Censo:</b>
                    </td>
                    <td>
                      <?
                         if (!isset($iAno)) {

                           $iAno = date("Y")-1;

                           for ($iDia = 1; $iDia <= 31; $iDia++) {

                             if ( date("w", mktime(0, 0, 0, 5, $iDia, $iAno)) == 3) {

                               $data_censo_dia = strlen($x)==1?"0".$x:$x;
                               $data_censo_mes = "05";
                               $data_censo_ano = $iAno;
                             }
                           }

                         }

                         $iAno = date("Y");
                         $iOp1 = $iAno;//opçao 1 do combobox de data, ano atual - 1;
                         $iOp2 = $iOp1 - 1;//opçao 2 do combobox da data, ano atual - 2;
                         // Arrumar a variavel ed52_i_ano que mostrara o ano que deve ser gerado as informações da rotina

                         $aOptions=array($iOp1 => $iOp1,
                                         $iOp2 => $iOp2
                                         );
                         db_select("ed52_i_ano",$aOptions,false,1,'onChange= "js_completaData()"');
                       ?>
                     </td>
                   </tr>
                   </table>
                 </fieldset>
                </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <fieldset class='interno'>
                      <legend><b>Calendário</b></legend>
                      <table style="width:100%">
                        <tr>
                          <td nowrap="nowrap">
                            <?=$Led52_d_inicio?>
                          </td>
                          <td nowrap="nowrap">

                           <? db_inputdata('ed52_d_inicio',@$ed52_d_inicio_dia,@$ed52_d_inicio_mes,
                                          @$ed52_d_inicio_ano,true,'text',$db_opcao,"");?>
                          </td>
                          <td nowrap="nowrap">
                            <?=$Led52_d_fim?>
                          </td >
                          <td nowrap="nowrap">
                            <? db_inputdata('ed52_d_fim',@$ed52_d_fim_dia,@$ed52_d_fim_mes,
                                       @$ed52_d_fim_ano,true,'text',$db_opcao,"");?>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>
                <tr>
                  <td colspan="6">
                   <fieldset class='interno'>
                     <legend><b>Outras Opções</b></legend>
                       <table style="width:100%">
                         <tr>
                           <td>
                             <b>Tipo de Arquivos:</b>
                          </td>
                          <td>
                            <?
                            $aOptions=array("1"=>"Todos","2"=>"Docentes", "3"=>"Alunos");
                            db_select("tipoarquivo",$aOptions,"",1);
                            ?>
                          </td>
                          <td style="text-align: right">
                            <b>Formato de Arquivo:</b>
                          </td>
                          <td  style="text-align: right">
                          <?
                          $aOptions=array("1"=>"TXT","2"=>"PDF");
                          db_select("formatoarquivo",$aOptions,"",1);
                          ?>
                          </td>
                         </tr>
                       </table>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
         <div style= 'width: 30%'>
           <fieldset>
             <legend><b>Escolas</b></legend>
             <div id="ctnEscolas">
             </div>
           </fieldset>
         </div>
         <input name="gerararquivo" type="button" id="arquivo"  value="Gerar Arquivo" onclick = 'return js_geraArquivo();'; >
    </form>
  </center>
  </body>
</html>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_completaData() {

  document.form1.submit();
}

function validaFormulario() {

  datacenso = document.form1.data_censo.value ;
  if (datacenso == "" || datacenso == null ) {

    alert ("Selecione o ano do censo");
    document.form1.datacenso.focus();
  }
}

sUrlRPC = 'edu4_censoalunosseminep.RPC.php';
  function js_ano() {

    datacenso = document.form1.data_censo.value;

    if (datacenso != "" && datacenso.length == 10) {

      datacenso                       = datacenso.split("/");
      document.form1.ed52_i_ano.value = datacenso[2];

      document.form1.submit();
    } else {

       document.form1.ed52_i_ano.value    = "";
       document.form1.ed52_d_inicio.value = "";
       document.form1.ed52_d_fim.value    = "";

    }

   }

function js_valida() {

  if (document.form1.data_censo.value == "" || document.form1.ed52_i_ano.value == ""
             || document.form1.ed52_d_inicio.value == "" || document.form1.ed52_d_fim.value == "") {

    alert("Preencha todos os campos do formulário!");
    return false;

  }

  if (document.form1.ed52_i_ano.value != document.form1.ed52_d_inicio_ano.value
         || document.form1.ed52_i_ano.value != document.form1.ed52_d_fim_ano.value) {

    alert("Data Inicial e Final do Calendário deve estar dentro do Ano do Censo!");
    return false;

  }

  dataini  = document.form1.ed52_d_inicio_ano.value+document.form1.ed52_d_inicio_mes.value;
  dataini += document.form1.ed52_d_inicio_dia.value;
  datafim  = document.form1.ed52_d_fim_ano.value+document.form1.ed52_d_fim_mes.value;
  datafim += document.form1.ed52_d_fim_dia.value;

  if (parseInt(dataini) >= parseInt(datafim)) {

    alert("Data Final do Calendário deve ser maior que a Data Inicial!");
    return false;

  }
  return true;
}

$('ed52_i_ano').style.width     = '100%';
$('formatoarquivo').style.width = '100%';
$('tipoarquivo').style.width    = '100%';

function js_geraArquivo() {

  if (!js_valida()) {
    return false;
  }

  var iFormato  = document.form1.formatoarquivo.value;
  var iTipo     = document.form1.tipoarquivo.value;
  var iAno      = document.form1.ed52_i_ano.value;
  var aLinhas = oDataGridEscola.getSelection("object");
  if (aLinhas.length == 0) {

  	alert('Nenhuma escola selecionada.');
    return false;
  }
  if (iFormato == 1 ) { // iFormato = 1, tipo de relatorio TXT

    js_divCarregando('Aguarde, processando os dados', 'msgBox');
    var oParam          = new Object();
    oParam.iTipoGeracao = iTipo;
    oParam.iAno         = iAno;
    oParam.exec         = 'gerarArquivosCensoSemInep';
    oParam.aEscola      = new Array();
  	aLinhas.each(function(iAut, iSeq){
  		oParam.aEscola.push(iAut.aCells[0].getValue());
    });
    var oAjax = new Ajax.Request(sUrlRPC,
                                {
                                 method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_downloadArquivos
                                }
                                );

  } else {

     var aEscolas = new Array();
     aLinhas.each(function(iAut, iSeq) {
      aEscolas.push(iAut.aCells[0].getValue());
    });
    var sEscolas = aEscolas.implode(',', aEscolas);
    janPdfDoc = window.open('edu2_alunosdocentesseminep002.php?ano='+iAno+'&tipo='+iTipo+'&sEscola='+sEscolas,
                             '',
                             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                             );
  }
}

function js_downloadArquivos(oResponse) {

   js_removeObj('msgBox');
   var oRetorno = eval("("+oResponse.responseText+")");
   if (oRetorno.arquivos.length == 0) {

     alert('Nenhum Aluno/Docente encontrado para o envio do arquivo.');
     return false;
   }
   var sListagemArquivos = '';
   var sSeparador        = '';
   oRetorno.arquivos.each(function(sArquivo, id) {

      sListagemArquivos += sSeparador+sArquivo+"#"+sArquivo;
      sSeparador        = "|";
   });
  js_montarlista(sListagemArquivos, 'form1');
}

function js_pesquisaEscola() {


	var oParametro          = new Object();
	oParametro.exec         = 'getEscolas';
	oParametro.filtraModulo = true;

  var oAjax = new Ajax.Request(
  		                         sUrlRPC,
  		                         {
  			                         method:     'post',
  			                         parameters: 'json='+Object.toJSON(oParametro),
  			                         onComplete: js_retornaPesquisaEscola
  		                         }
  		                        );
}

function js_retornaPesquisaEscola(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');
	oDataGridEscola.clearAll(true);
	oRetorno.aDados.each(function(oLinha, iContador) {

	  var aLinha = new Array();
    aLinha[0] = oLinha.codigo_escola;
    aLinha[1] = oLinha.nome_escola.urlDecode();
    if (oRetorno.iTotalLinhas == 1) {
      oDataGridEscola.addRow(aLinha, false, false, true);
    } else {
    	oDataGridEscola.addRow(aLinha);
    }
	});

	oDataGridEscola.renderRows();
}

function js_gridEscola() {

	oDataGridEscola              = new DBGrid("gridEscola");
	oDataGridEscola.nameInstance = 'oDataGridEscola';
	oDataGridEscola.setCheckbox(0);
	oDataGridEscola.setCellAlign(new Array("center", "left"));
	oDataGridEscola.setHeader(new Array("Código", "Nome"));
	oDataGridEscola.setCellWidth(new Array("20%","80%"));
	oDataGridEscola.show($('ctnEscolas'));
}

js_gridEscola();
js_pesquisaEscola();
</script>