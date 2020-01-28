<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oDaoMatricula  = db_utils::getdao("matricula");
$oDaoCalendario = db_utils::getdao("calendario");
$db_opcao       = 1;
$db_botao       = true;
$sNomeEscola    = db_getsession("DB_nomedepto");
$iEscola        = db_getsession("DB_coddepto");
$iModulo        = db_getsession("DB_modulo");
/**
 *
 * Função que busca os dados da escola
 * @param  $iModulo codigo da escola da sessao
 */
function GetDadosEscola($iModulo) {

  $sHtml     = '';
  $sDisplay  = 'style="display: none;"';
  $sSelected = '';

  if ($iModulo == 7159) {

    $sSelected = 'selected="selected"';
    $sDisplay  = '';
  }

  $sHtml         .= '<td align="left" '.$sDisplay.'> ';
  $sHtml         .= '<b>Selecione a escola:</b>';
  $oDaoEscola     = db_utils::getdao('escola');
  $sSqlEscola     = $oDaoEscola->sql_query_file("","ed18_i_codigo,ed18_c_nome","","");
  $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);
  $iLinhas        = $oDaoEscola->numrows;

  $sHtml .= '<select name="escola" id="escola" onChange="js_escola(this.value);" style="height:18px;font-size:10px;">';
  $sHtml .= "  <option value='' {$sSelected}>Selecione a Escola</option>";

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $oDadosEscola = db_utils::fieldsmemory($rsResultEscola, $iCont);
    $sHtml       .= " <option value='$oDadosEscola->ed18_i_codigo' >$oDadosEscola->ed18_c_nome</option>";

  }

  $sHtml .= ' </select>';
  $sHtml .= '</td>';


  echo $sHtml;
}

?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
   </tr>
  </table>
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <a name="topo"></a>
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset style="width:95%"><legend><b>Relatório de Alunos Matriculados  </b></legend>
       <table border="0">
        <tr>
         <?
           GetDadosEscola(db_getsession('DB_modulo'));
         ?>
         <td>
          <b>Selecione o Calendário:</b>
          <select name="calendario" id="select_calendario" onchange="js_etapa(this.value)"
                  style="width:150px;height:18px;font-size:10px;;" >
          </select>
         </td>
         <td>
          <b>Selecione a Etapa:</b>
          <select name="etapa" id="etapa" onchange="js_liberaBotao(this.value)"
                  style="width:150px;height:18px;font-size:10px;;" >
          </select>
         </td>
         <td>
          <b>Filtro:</b>
          <select name="filtro" style="font-size:9px;width:180px;height:18px;">
           <option value="2" <?=@$filtro==2?"selected":""?>>TURMAS</option>
           <option value="1" <?=@$filtro==1?"selected":""?>>TURMAS E PERCENTUAIS</option>
          </select>
         </td>
         <td valign='bottom'>
           <input type="button" name="procurar" id="procurar" value="Processar"
                  onclick="js_procurar(document.form1.calendario.value,document.form1.etapa.value,document.form1.escola.value)" disabled>
         </td>
        </tr>
       </table>
      </fieldset>
    </form>
  </div>
  <div>
    <?php
      if (isset($procurar)) {

        $iEscola = $escola;
        if ( empty($escola) ) {
          $iEscola = db_getsession('DB_coddepto');
        }

        $oCalendario = CalendarioRepository::getCalendarioByCodigo($calendario);
        $oEscola     = EscolaRepository::getEscolaByCodigo($iEscola);
        $aEtapas     = array($etapa);

        if ($etapa == 0) {

          $aEtapas   = array();
          $sCampos   = "distinct (ed11_i_codigo), ed11_i_ensino, ed11_i_sequencia";
          $sWhere    = "     ed57_i_escola     = $iEscola ";
          $sWhere   .= " and ed57_i_calendario = $calendario ";
          $sOrder    = "ed11_i_ensino, ed11_i_sequencia";
          $oDaoTurma = new cl_turma();
          $sSql      = $oDaoTurma->sql_query_turma(null, $sCampos, $sOrder, $sWhere);
          $rs        = db_query($sSql);

          if ($rs && pg_num_rows($rs) > 0) {

            $iLinhas = pg_num_rows($rs);
            for ($i=0; $i < $iLinhas ; $i++) {

              $aEtapas[] = db_utils::fieldsMemory($rs, $i)->ed11_i_codigo;
            }
          }
        }

        $oViewHtml = new HtmlAlunosMatriculados($oCalendario, $aEtapas, $oEscola);

        if ( $filtro == 1 ) {
          $oViewHtml->setExibePercentual(true);
        }

        try {
          $sHtml = $oViewHtml->exibir();
        } catch (Exception $o) {
          echo $o->getMessage();
        }

        echo $sHtml;
      }
    ?>
  </div>
 </body>
 <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script>

(function (){

  if ( $F('escola') != "" ) {
    js_escola($F('escola'));
  }
})();

function js_procurar(calendario,etapa,escola) {

	location.href = "edu3_alunomatriculado001.php?procurar&calendario="+calendario+"&etapa="+etapa+"&escola="+escola+"&filtro="+document.form1.filtro.value;
}

function js_botao(valor) {

  if (valor != "") {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }

}


function js_escola(escola) {

  var oParam    = new Object();

  oParam.exec   = "PesquisaCalendario";
  oParam.escola =  escola;

  var url       = 'edu4_escola.RPC.php';

  js_webajax(oParam,'js_retornoPesquisaCalendario',url);

}

function js_retornoPesquisaCalendario(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    sHtml += '<option value="">Selecione o Calendário</option>';

    for (var i = 0;i < oRetorno.aResult.length; i++) {

      with (oRetorno.aResult[i]) {

        sHtml += '<option value="'+oRetorno.aResult[i].ed52_i_codigo+'">';
        sHtml += oRetorno.aResult[i].ed52_c_descr.urlDecode()+'</option>';

      }

    }

    $('select_calendario').innerHTML             = sHtml;
    document.form1.select_calendario[0].selected = true;


  }

  $('select_calendario').disabled  = false;

}

function js_etapa(calendario) {

  $('etapa').innerHTML = "";
  $('etapa').disabled  = true;
  var oParam        = new Object();

  oParam.exec       = "PesquisaEtapa";

  <? if ($iModulo != 7159) { ?>

    oParam.escola = <?=$iEscola?>

  <? } else { ?>

    oParam.escola  =  $('escola').value;

  <? } ?>

  oParam.calendario = calendario;

  var url           = 'edu4_escola.RPC.php';

  js_webajax(oParam,'js_retornoPesquisaEtapa',url);

}

function js_retornoPesquisaEtapa(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    if (oRetorno.aResult1.length == 0) {
      sHtml += '<option value="">Não há Etapa</option>';
    } else {

      sHtml += '<option value="">Selecione a Etapa</option>';
      sHtml += '<option value="0">Todas</option>';

      for (var i = 0;i < oRetorno.aResult1.length; i++) {

        sHtml += '<option value="'+oRetorno.aResult1[i].ed11_i_codigo+'">'+
                 oRetorno.aResult1[i].ed11_c_descr.urlDecode()+'</option>';

      }

    }

  $('etapa').innerHTML   = sHtml;
  $('etapa').disabled    = false;

  }

}

function js_liberaBotao(valor){

  if (valor.trim() != "") {
    $('procurar').disabled = false;
  } else {
    $('procurar').disabled = true;
  }

}


function js_matriculas(turma,descrturma,calendario,etapaturma){

  js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','edu3_alunomatriculado002.php?turma='+turma+'&etapaturma='+etapaturma,'Alunos Matriculados na Turma '+descrturma,true);
  location.href = "#topo";
}

</script>

<?

if ($iModulo != 7159) {
?>

<script>

  js_escola(<?=$iEscola?>);
</script>

<?

  }

?>