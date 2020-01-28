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

//MODULO: educação
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once('libs/db_utils.php');
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matricula_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoEduNumAlunoBloqueado = db_utils::getdao('edu_numalunobloqueado');
$clmatricula              = new cl_matricula;
$escola                   = db_getsession("DB_coddepto");
$oConfig                  = loadConfig('edu_parametros', 'ed233_i_escola = '.$escola);
if ($oConfig == null) {
  $oConfig->ed233_i_habilitaordemalfabeticaturma = 2; // Não habilita (não exibe o botão)
}
if (isset($atualizar)) {

  $tam = sizeof($matriculas);
  db_inicio_transacao();
  try {

    if ($lCancelarNumeracao == 1) {

      /**
       * Cancela os numeros bloqueados. essa informação vem de um campo hidden $lCancelarNumeracao;
       */
      $oDaoEduNumAlunoBloqueado->excluir(null,'ed289_i_turma = '.$ed60_i_turma);
      if ($oDaoEduNumAlunoBloqueado->erro_status == 0) {
        throw new Exception($oDaoEduNumAlunoBloqueado->erro_msg);
      }
    }


    for ($i = 0; $i < $tam; $i++) {

      $clmatricula->ed60_i_codigo   = $matriculas[$i];
      $clmatricula->ed60_i_numaluno = $numaluno[$i];
      $clmatricula->alterar($matriculas[$i]);
      if ($clmatricula->erro_status == '0') {
        break;
      }

      /**
       * Atualizamos os alunos que possuem troca de turma na numeração
       */
      if ($trocaTurma == 1) {

        $sWhereTrocaTurma              = "ed60_i_turma={$ed60_i_turma}";
        $sWhereTrocaTurma             .= " and ed60_c_situacao = 'TROCA DE TURMA'";
        $sSqlMatriculasComTrocaDeTurma = $clmatricula->sql_query_file(null, "*", null, $sWhereTrocaTurma);
        $rsMatriculasComTrocaTurma     = $clmatricula->sql_record($sSqlMatriculasComTrocaDeTurma);
        $iMatriculasTrocaTurma         = $clmatricula->numrows;
        if ($clmatricula->numrows > 0) {

          for ($iMatricula = 0; $iMatricula < $iMatriculasTrocaTurma; $iMatricula++) {

            $oDadosMatricula = db_utils::fieldsMemory($rsMatriculasComTrocaTurma, $iMatricula);
            $oDaoMatricula = new cl_matricula();
            $oDaoMatricula->ed60_c_ativa         = $oDadosMatricula->ed60_c_ativa;
            $oDaoMatricula->ed60_c_concluida     = $oDadosMatricula->ed60_c_concluida;
            $oDaoMatricula->ed60_c_parecer       = $oDadosMatricula->ed60_c_parecer;
            $oDaoMatricula->ed60_c_rfanterior    = $oDadosMatricula->ed60_c_rfanterior;
            $oDaoMatricula->ed60_c_tipo          = $oDadosMatricula->ed60_c_tipo;
            $oDaoMatricula->ed60_d_datamatricula = $oDadosMatricula->ed60_d_datamatricula;
            $oDaoMatricula->ed60_d_datamodif     = $oDadosMatricula->ed60_d_datamodif;
            $oDaoMatricula->ed60_d_datamodifant  = $oDadosMatricula->ed60_d_datamodifant;
            $oDaoMatricula->ed60_i_aluno         = $oDadosMatricula->ed60_i_aluno;
            $oDaoMatricula->ed60_i_turma         = $oDadosMatricula->ed60_i_turma;
            $oDaoMatricula->ed60_i_turmaant      = $oDadosMatricula->ed60_i_turmaant;
            $oDaoMatricula->ed60_c_situacao      = $oDadosMatricula->ed60_c_situacao;
            $oDaoMatricula->ed60_matricula       = $oDadosMatricula->ed60_matricula;
            $oDaoMatricula->ed60_i_codigo        = $oDadosMatricula->ed60_i_codigo;
            $oDaoMatricula->ed60_d_datasaida     = $oDadosMatricula->ed60_d_datasaida;
            $oDaoMatricula->ed60_i_numaluno      = "null";
            $oDaoMatricula->alterar($oDadosMatricula->ed60_i_codigo);
            if ($oDaoMatricula->erro_status == 0) {
              throw new Exception("Erro ao alterar classificação da turma.\\n{$oDaoMatricula->erro_msg}");
            }
          }
        }
      }
    }

    db_fim_transacao(false);
    ?>
   <script>
     alert('Turma reclassificada com sucesso!');
     parent.location.href = "edu1_alunoturma001.php?ed60_i_turma=<?=$ed60_i_turma?>&ed57_c_descr=<?=$ed57_c_descr?>"+
                           "&ed52_c_descr=<?=$ed52_c_descr?>&ordenar=true&numeracao=true";
     parent.db_iframe_classificacao.hide();
    </script>
    <?
    exit;
  } catch (Exception $eErro) {

    db_fim_transacao(true);
    db_msgbox($eErro->getMessage());

  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" id='form2' method="post" action="">
<table border="0" cellspacing="3" bgcolor="#CCCCCC" align="center">
 <tr>
 <?if (isset($numeracao) && $numeroaluno =="") {?>
  <td valign="top" align="center">
   <b>Novo N°</b>
  </td>
  <?}?>
  <td valign="top" align="center" colspan="2">
   <b>Nome Aluno</b>
  </td>
  <td></td>
 </tr>
 <tr>
  <td align="right" valign="top" style='overflow:hidden' >
   <?
   $sWhereTrocaTurma = '';
   if (isset($trocaTurma) && $trocaTurma == 1) {
     $sWhereTrocaTurma = " and ed60_c_situacao <> 'TROCA DE TURMA'";
   }
   // Busca das matrículas (alunos)
   $sql    = $clmatricula->sql_query("","*","ed60_i_numaluno, to_ascii(ed47_v_nome)",
                                    " ed60_i_turma = $ed60_i_turma {$sWhereTrocaTurma}");
   $result = $clmatricula->sql_record($sql);

   // Verifico se tem ou não ordem por numeração informada
   $sVisibility = 'hidden';
   if ($clmatricula->numrows > 0) {

     db_fieldsmemory($result, 0);
     if (!empty($ed60_i_numaluno)) {
       $sVisibility = 'visible';
     }

   }

   ?>

  <select name="numaluno[]" id="numaluno" size="15" style="position:relative;left:18px;font-size:9px;width:50px;
                                                           overflow:hidden;z-Index:0;
                                                           visibility:<?=$sVisibility?>;" multiple >
    <?
    $iContNum = 1;
    for ($x = 0; $x < $clmatricula->numrows; $x++) {

      if ($sVisibility == 'hidden') { // Sem classificação numérica
        echo '<option value="null">null</option>';
      } else {

        echo "<option value='".$iContNum."'>".$iContNum."</option>";
        $iContNum++;

      }

    }
    ?>
   </select>
  </td>
  <td align="right" valign="top">
   <select name="matriculas[]" id="matriculas" size="15" style="font-size:9px;width:280px;" multiple>
   <?
   for ($x = 0; $x < $clmatricula->numrows; $x++) {

     db_fieldsmemory($result,$x);
     $sNum = empty($ed60_i_numaluno) ? '' : $ed60_i_numaluno.' - ';
     echo "<option value='$ed60_i_codigo'>$sNum$ed47_v_nome</option>";

   }
   ?>
   </select>
  </td>
  <td valign="top">
    <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
    <br/>
   <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
    <br/>
  </td>
 </tr>
</table>
<table align="center" border="0" width="380">
 <tr>

  <td width="50">
   <input type="submit" name="atualizar" value="Confirmar" onClick="js_selecionar();">
   </td>
   <td>
   <input type="button" name="cancelar" value="Cancelar" onClick="js_fechar();">
  </td>
  <?
  if ($oConfig->ed233_i_habilitaordemalfabeticaturma == 1) {
  ?>
    <td width="50">
     <input type="button" id="ordenar" value="Ordenar Alfabeticamente" onClick="js_OrdenarLista('matriculas', true);"
       <?=$sVisibility == 'hidden' ? 'disabled' : ''?>>
    </td>
  <?
  }
  if ($ed60_i_numaluno == '') {
  ?>
      <td>
        <input type="button" id="numeracao" value="Gerar Numeração" onClick="js_numeracao('matriculas');"  >
      </td>
  <?} ?>
  <?if ( $ed60_i_numaluno != "") {?>
      <td>
        <input type="button" id="numeracao" name="numeracao" value="Cancelar Numeração"
          onClick="js_cancelarNumeracao();"
          style="visibility:visible;">
      </td>
  <?}?>
  <td>
   <input type="button" id="voltar" value="Restaurar"
          onClick="location.href = 'edu1_alunoturma002.php?ed60_i_turma=<?=$ed60_i_turma?>&ed57_c_descr=<?=$ed57_c_descr?>
                                    &ed52_c_descr=<?=$ed52_c_descr?>&trocaTurma=<?=$trocaTurma?>'"
          style="visibility:visible;">
  </td>

 </tr>
</table>
<input type="hidden" name="ed60_i_turma" value="<?=$ed60_i_turma?>">
<input type="hidden" name="ed57_c_descr" value="<?=$ed57_c_descr?>">
<input type="hidden" name="ed52_c_descr" value="<?=$ed52_c_descr?>">
<input type="hidden"   name="lCancelarNumeracao" id="lCancelarNumeracao" value='0'>
</form>
</body>
</html>
<script>

function js_sobe() {

  var F = document.getElementById("matriculas");

  if (F.selectedIndex != -1 && F.selectedIndex > 0
      && document.getElementById('numaluno').style.visibility == 'hidden') {
    js_numeracao();
  }

  if (F.selectedIndex != -1 && F.selectedIndex > 0) {

    var SI                 = F.selectedIndex - 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;
    F.options[SI]          = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }

}


function js_desce() {

  var F = document.getElementById("matriculas");

  if (F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)
      && document.getElementById('numaluno').style.visibility == 'hidden') {
    js_numeracao();
  }

  if (F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

    var SI                 = F.selectedIndex + 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;
    F.options[SI]          = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI - 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }
}

function js_selecionar() {

  var F = document.getElementById("matriculas").options;
  for (var i = 0;i < F.length; i++) {
    F[i].selected = true;
  }

  var F = document.getElementById("numaluno").options;
  for (var i = 0;i < F.length; i++) {
    F[i].selected = true;
  }
  return true;

}

function js_fechar() {
  parent.db_iframe_classificacao.hide();
}

function TiraAcento(string) {

  acentos = 'ÁÉÍÓÚÀÂÊÔÜÏÖÑÃÕÄ\'';
  letras  = 'AEIOUAAEOUIONAOA ';
  new_string = '';
  for (r = 0; r < string.length; r++) {

    let = string.substr(r,1);
    for (d = 0; d < acentos.length; d++) {

      if (let == acentos.substr(d,1)) {
        let = letras.substr(d,1);
        break;
      }

    }
    new_string = new_string+let;
  }
  return new_string;
}

/* Retira o número, o traço e o espaço em branco do início da string
   iTipoRetorno pode assumir 2 valores:
   1 -> retorna a string sem o número na frente
   2 -> retorna o que foi retirado da string

   Ex: js_retiraNumero('1 - Isabel', 1); // Retorna "Isabel"
   Ex: js_retiraNumero('1 - Isabel', 2); // Retorna "1 - "
*/
function js_retiraNumero(sStr, iTipoRetorno) {

  var sNum        = '';
  var iTam        = sStr.length;
  var iNumCharCut = 0;
  var lFound      = false;

  if (iTipoRetorno == undefined || iTipoRetorno < 1 || iTipoRetorno > 2) {
    iTipoRetorno = 1;
  }

  for (iNumCharCut = 0; iNumCharCut < iTam; iNumCharCut++) {

    if (sStr[iNumCharCut] == '-') {

      iNumCharCut += 2; // Contar o espaço em branco também
      lFound = true;
      break;

    }

  }

  if (lFound) {

    if (iTipoRetorno == 1) {
      return sStr.substr(iNumCharCut);
    } else {
      return sStr.substr(0, iNumCharCut);
    }

  } else {

    if (iTipoRetorno == 1) {
      return sStr;
    } else {
      return '';
    }

  }

}

function js_OrdenarLista(combo, lVerificaOrdemNumerica) {

  if (lVerificaOrdemNumerica == undefined) {
    lVerificaOrdemNumerica = false;
  }

  if (lVerificaOrdemNumerica) {

    if (document.getElementById('numaluno').options[0].value.isInt()) { // Se a ordem for numérica

      if (!confirm('Os alunos com matrículas posteriores posicionados ao fim da listagem serão reclassificados'+
                   ' na ordem alfabética, clique no botão OK para confirmar e no botão Cancelar para manter'+
                   ' classificação numérica atual.'
                  )) {
        return false;
      }

    }

  }

	var sTmp = '';
  var lb   = document.getElementById(combo);
  arrTexts = new Array();
  for (i = 0; i < lb.length; i++) {


    if (lb.options[i].text[0].isInt()) {
      texto       = TiraAcento(js_retiraNumero(lb.options[i].text, 1)); // Retiro o número da frente do texto para ordenar
    } else {
      texto       = TiraAcento(lb.options[i].text);
    }

    sTmp        = lb.options[i].text[0].isInt() ? js_retiraNumero(lb.options[i].text, 2) : '';
    arrTexts[i] = texto+"#"+lb.options[i].value+'#'+sTmp



  }

  arrTexts.sort();
  for (i = 0; i < lb.length; i++) {

    ArrayExplode        = arrTexts[i].split("#");
    lb.options[i].text  = ArrayExplode[2]+ArrayExplode[0];
    lb.options[i].value = ArrayExplode[1];

  }
  document.getElementById("ordenar").style.visibility = "visible";
  document.getElementById("voltar").style.visibility  = "visible";

}

function js_cancelarNumeracao() {

  if (!confirm('Os alunos com matrículas posteriores posicionados ao fim da listagem serão reclassificados'+
               ' na ordem alfabética, clique no botão OK para confirmar e no botão Cancelar para manter'+
               ' classificação numérica atual.'
              )) {
    return false;
  }

  $('lCancelarNumeracao').value = 1;
  if (document.getElementById('ordenar') != undefined) {
    document.getElementById('ordenar').disabled = true;
  }
  document.getElementById('numaluno').style.visibility  = 'hidden';
  document.getElementById('numeracao').value            = 'Gerar Numeração';
  document.getElementById('numeracao').onclick          = function () { js_numeracao('matriculas');};

  // Mudo o numero dos alunos para null
  var aOptNum   = document.getElementById('numaluno').options;
  var aOptAluno = document.getElementById('matriculas').options;

  for (var iCont = 0; iCont < aOptNum.length; iCont++) {

    aOptNum[iCont].value = 'null';
    aOptNum[iCont].text  = 'null';
    if (aOptAluno[iCont].text[0].isInt()) {
      aOptAluno[iCont].text = js_retiraNumero(aOptAluno[iCont].text, 1); // Retiro o número da frente do nome do aluno
    }

  }

  js_OrdenarLista('matriculas');

}
var running1 = false;
var running2 = false;
function js_comboScroll(cboOrigem, cboDestino, event) {

  cboDestino.scrollTop = cboOrigem.scrollTop;
  running1 = false;
  running2 = false;
  return false;
}

$('matriculas').observe("scroll",  function (Event) {

  if (running2) {

    running2 = false;
    return;
  }
  js_comboScroll($('matriculas'), $('numaluno'), Event);
  running1 = true;
});
$('numaluno').observe("scroll", function (Event) {

  if (running1) {

    running1 = false;
    return false;
  }
  js_comboScroll($('numaluno'), $('matriculas'), Event);
  running2 = true;
});

function js_numeracao(combo) {

  // Mudo o numero dos alunos para valores numéricos
  var aOptNum   = document.getElementById('numaluno').options;
  var iNum      = 1;
  for (var iCont = 0; iCont < aOptNum.length; iCont++) {

    aOptNum[iCont].value  = iNum;
    aOptNum[iCont].text   = iNum;
    iNum++;

  }

  if (document.getElementById('ordenar') != undefined) {
    document.getElementById('ordenar').disabled = false;
  }
  $('lCancelarNumeracao').value = 0;
  document.getElementById("voltar").style.visibility    = "visible";
  document.getElementById("numaluno").style.visibility  = "visible";
  document.getElementById('numeracao').value            = 'Cancelar Numeração';
  document.getElementById('numeracao').onclick          = function () {js_cancelarNumeracao();};

}

String.prototype.isInt = function() {

  var oRE = new RegExp("^[0-9]$");
  return this.match(oRE);

}

function arrayToObject(aArray) {

 var oOb = {};
  for( var iCont = 0; iCont < aArray.length; iCont++) {

   oOb[aArray[iCont]] = '';
  }

  return oOb;

}
</script>