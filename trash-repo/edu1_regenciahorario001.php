<?
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("std/DBDate.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_regenciahorario_classe.php");
require_once ("classes/db_regencia_classe.php");
require_once ("classes/db_regenteconselho_classe.php");
require_once ("classes/db_periodoescola_classe.php");
require_once ("classes/db_escola_classe.php");
require_once ("classes/db_diasemana_classe.php");
require_once ("classes/db_turmaturno_classe.php");
require_once ("classes/db_turma_classe.php");
require_once ("classes/db_rechumanoativ_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

db_postmemory($HTTP_POST_VARS);

$clregenciahorario = new cl_regenciahorario;
$clregencia        = new cl_regencia;
$clregenteconselho = new cl_regenteconselho;
$cldiasemana       = new cl_diasemana;
$clperiodoescola   = new cl_periodoescola;
$clescola          = new cl_escola;
$clturmaturno      = new cl_turmaturno;
$clturma           = new cl_turma;
$clrechumanoativ   = new cl_rechumanoativ;

$db_opcao          = 1;
$db_botao          = true;
$escola            = db_getsession("DB_coddepto");
$erro              = false;

$sCampos           = "ed57_i_sala,ed52_i_codigo as codcalendario,ed52_i_ano as anocal";
$sSqlDadosTurma    = $clturma->sql_query("",
                                          $sCampos,
                                          "",
                                          " ed57_i_codigo = $ed59_i_turma"
                                         );

$result_cal  = $clturma->sql_record($sSqlDadosTurma);
db_fieldsmemory($result_cal,0);
$sWhere      = " ed57_i_sala = $ed57_i_sala AND ed57_i_turno = $ed57_i_turno ";
$sWhere     .= " AND ed52_i_ano = $anocal AND ed57_i_codigo != '$ed59_i_turma'";
$sSqlTurma   = $clturma->sql_query("",
                                   "ed57_i_codigo as codturmaadd",
                                   "ed57_i_codigo",
                                   $sWhere
                                   );

$result_sala = $clturma->sql_record($sSqlTurma);
$maisturmas  = "";
$sep         = "";
for ($r = 0; $r < $clturma->numrows; $r++) {

  db_fieldsmemory($result_sala,$r);
  $maisturmas .= $sep.$codturmaadd;
  $sep         = ",";

}

if (isset($incluir)) {

  $db_botao = true;

  try {

  db_inicio_transacao();

  for ($x = 0; $x < $contp; $x++) {

    for ($y = 0; $y < $contd; $y++) {

      $valores  = "valorQ".$x.$y;
      $valores  = $$valores;
      $marcados = "marcadoQ".$x.$y;
      $marcados = $$marcados;

      if (trim($valores) == "" && trim($marcados) != "") {

        $clregenciahorario->ed58_ativo    = "false";
        $clregenciahorario->ed58_i_codigo = $marcados;
        $clregenciahorario->alterar($marcados);

      } else if (trim($valores) != "" && trim($marcados) != "") {

        $dados = explode("|",$valores);
        $clregenciahorario->ed58_i_regencia  = $dados[0];
        $clregenciahorario->ed58_i_diasemana = $dados[1];
        $clregenciahorario->ed58_i_periodo   = $dados[2];
        $clregenciahorario->ed58_i_rechumano = $dados[3];
        $clregenciahorario->ed58_i_codigo    = $marcados;
        $clregenciahorario->ed58_ativo       = "true";
        $clregenciahorario->ed58_tipovinculo = 2;
        $clregenciahorario->alterar($marcados);

      } else if (trim($valores) != "" && trim($marcados) == "") {

        $dados                               = explode("|",$valores);
        $clregenciahorario->ed58_i_regencia  = $dados[0];
        $clregenciahorario->ed58_i_diasemana = $dados[1];
        $clregenciahorario->ed58_i_periodo   = $dados[2];
        $clregenciahorario->ed58_i_rechumano = $dados[3];
        $clregenciahorario->ed58_ativo       = "true";
        $clregenciahorario->ed58_tipovinculo = 2;
        $clregenciahorario->incluir(null);
        $clregenciahorario->erro_msg;

      }

      if ($clregenciahorario->erro_status == "0") {

          $sMensagemErro   = "Erro ao alterar situa��o do per�odo.\\n ";
          $sMensagemErro  .= "Erro T�cnico : {$clregenciahorario->erro_msg}";
          throw new BusinessException($sMensagemErro);
        }
      unset($valores);
      unset($marcados);
    }
  }
  $result = $clregenteconselho->sql_record($clregenteconselho->sql_query("",
                                                                         "ed235_i_codigo",
                                                                         "",
                                                                         " ed235_i_turma = $ed59_i_turma"
                                                                        )
                                          );
  if (isset($conselheiro) && trim($conselheiro) == "") {

    if ($clregenteconselho->numrows > 0) {

      $clregenteconselho->excluir(""," ed235_i_turma = $ed59_i_turma");
      if ($clregenteconselho->erro_status == 0) {

        $sMensagemErro   = "Erro ao Excluir dados do conselheiro da turma.\\n ";
        $sMensagemErro  .= "Erro T�cnico : {$clregenteconselho->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }
    }

  } else if (isset($conselheiro) && trim($conselheiro) != "") {

    if ($clregenteconselho->numrows > 0) {

      db_fieldsmemory($result,0);
      $clregenteconselho->ed235_i_rechumano = $conselheiro;
      $clregenteconselho->ed235_i_codigo    = $ed235_i_codigo;
      $clregenteconselho->alterar($ed235_i_codigo);

    } else {

      $clregenteconselho->ed235_i_turma     = $ed59_i_turma;
      $clregenteconselho->ed235_i_rechumano = $conselheiro;
      $clregenteconselho->incluir(null);

    }
    if ($clregenteconselho->erro_status == 0) {

      $sMensagemErro   = "Erro ao salvar dados do conselheiro da turma.\\n ";
      $sMensagemErro  .= "Erro T�cnico : {$clregenteconselho->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
  }

  db_fim_transacao(false);
  $clregenciahorario->erro_msg = "Dados salvos com sucesso!";
  $clregenciahorario->erro(true,false);
  $redireciona  = "edu1_regenciahorario001.php?ed59_i_turma=$ed59_i_turma&ed57_c_descr=$ed57_c_descr";
  $redireciona .= "&ed57_i_turno=$ed57_i_turno&ed59_i_serie=$ed59_i_serie&ed11_c_descr=$ed11_c_descr";
  db_redireciona($redireciona);
  exit;

  } catch (BusinessException $eBusinessException) {

    db_fim_transacao(true);
    $clregenciahorario->erro_msg = $eBusinessException->getMessage();
    $clregenciahorario->erro(true, false);
  }


}

if (isset($limpar)) {
	try {

    db_inicio_transacao();
    $clregenteconselho->excluir(""," ed235_i_turma = $ed59_i_turma");
    /**
     * Apenas marcar os registros como ativo=false
     */
    $sWhereHorarios       = " ed58_i_regencia in (SELECT ed59_i_codigo ";
    $sWhereHorarios      .= "                       from regencia ";
    $sWhereHorarios      .= "                      WHERE ed59_i_turma = {$ed59_i_turma}";
    $sWhereHorarios      .= "                        AND ed59_i_serie = {$ed59_i_serie})";
    $sSqlHorariosAlterar  = $clregenciahorario->sql_query_file(null, "*", null, $sWhereHorarios);
    $rsHorariosAlterar    = $clregenciahorario->sql_record($sSqlHorariosAlterar);
    $iTotalLinhas         = $clregenciahorario->numrows;
    for ($iDiario = 0; $iDiario < $iTotalLinhas; $iDiario++) {

      $oDadosRegenciaHorario = db_utils::fieldsMemory($rsHorariosAlterar, $iDiario);
      $clregenciahorario->ed58_i_codigo    = $oDadosRegenciaHorario->ed58_i_codigo;
      $clregenciahorario->ed58_i_diasemana = $oDadosRegenciaHorario->ed58_i_diasemana;
      $clregenciahorario->ed58_i_periodo   = $oDadosRegenciaHorario->ed58_i_periodo;
      $clregenciahorario->ed58_i_rechumano = $oDadosRegenciaHorario->ed58_i_rechumano;
      $clregenciahorario->ed58_i_regencia  = $oDadosRegenciaHorario->ed58_i_regencia;
      $clregenciahorario->ed58_ativo       = "false";
      $clregenciahorario->alterar($oDadosRegenciaHorario->ed58_i_codigo);
      if ($clregenciahorario->erro_status == 0) {

        $sMensagemErro   = "Erro ao desativar hor�rios da turma.\\n ";
        $sMensagemErro  .= "Erro T�cnico : {$clregenciahorario->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }
      unset($oDadosRegenciaHorario);
    }
    db_fim_transacao(false);
    $clregenciahorario->erro_msg = "Dados salvos com sucesso!";
    $redireciona  = "edu1_regenciahorario001.php?ed59_i_turma=$ed59_i_turma&ed57_c_descr=$ed57_c_descr&";
    $redireciona .= "ed57_i_turno=$ed57_i_turno&ed59_i_serie=$ed59_i_serie&ed11_c_descr=$ed11_c_descr";
    db_redireciona($redireciona);
    exit;
	} catch (BusinessException $eBusinessException) {

	  echo $eBusinessException->getMessage();
	  $clregenciahorario->erro_msg = $eBusinessException->getMessage();
    db_fim_transacao(true);
	}

}

/**
 * Verificamos o tipo de frequencia e a forma de controle de frequencia da base curricular da turma
 */
$lControleIndividualPeriodo = false;
$sDesabilitaVinculo         = "";

$oTurma              = TurmaRepository::getTurmaByCodigo($ed59_i_turma);
$oBaseCurricular     = $oTurma->getBaseCurricular();

if ($oBaseCurricular->getControleFrequencia() == 'I' && $oBaseCurricular->getFrequencia() == 'P') {
  
  $lControleIndividualPeriodo = true;
  $sDesabilitaVinculo         = "disabled";
}

/**
 * Verificamos se a grade foi preenchida ou algum vinculo entre regente/disciplina, retornando o tipo de vinculo
 */
if (!isset($iTipoVinculo)) {
  
  $iTipoVinculo           = null;
  $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
  $sCamposRegenciaHorario = "distinct ed58_i_regencia, ed58_tipovinculo";
  $sWhereRegenciaHorario  = "ed59_i_turma = {$ed59_i_turma} AND ed57_i_escola = {$escola} AND ed58_ativo is true";
  $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null, $sCamposRegenciaHorario, null, $sWhereRegenciaHorario);
  $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
  
  if ($oDaoRegenciaHorario->numrows > 0) {
    
    $oDadosRegenciaHorario = db_utils::fieldsMemory($rsRegenciaHorario, 0);
    $iTipoVinculo          = $oDadosRegenciaHorario->ed58_tipovinculo;
  }
}

$sSelectGrade           = "";
$sSelectVinculo         = "";

if (empty($iTipoVinculo) || $iTipoVinculo == 2 || $lControleIndividualPeriodo) {
  $sSelectGrade = "selected";
} else {
  $sSelectVinculo = "selected";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%">
     <legend>
       <select id="escolha" name="escolha" style="font-weight:bold;font-size:11px;" 
               onchange="js_validaRegente();">
         <option value="gradeHorario" 
                 <?=$sSelectGrade?>>
           <b>Hor�rios de Reg�ncias na Turma <?=@$ed57_c_descr?> - Etapa <?=@$ed11_c_descr?></b>
         </option>
         <option value="vinculaRegente" 
                 <?=$sSelectVinculo?> 
                 <?=$sDesabilitaVinculo?>>
           <b>V�nculos Regente / Disciplina na Turma <?=@$ed57_c_descr?> - Etapa <?=@$ed11_c_descr?></b>
         </option>
       </select>
     </legend>
     <?
       if (!isset($excluir)) {
     
         /**
          * De acordo com o tipo de vinculo, carregamos o formulario correto
          */
         if (empty($iTipoVinculo) || $iTipoVinculo == 2 || $lControleIndividualPeriodo) {

           include("forms/db_frmregenciahorario.php");
           ?>
           <script>
             $('escolha').style.display         = "gradeHorario";
             $('frmGradeHorario').style.display = "inline";
           </script>
           <?
         } else {

           include("edu1_vinculaprofessordisciplina001.php");
           ?>
           <script>
             $('escolha').style.display                       = "vinculaRegente";
             $('frmVinculaProfessorDisciplina').style.display = "inline";
             $('divVinculos').style.display                   = "inline";
           </script>
           <?
         }
       }
     ?>
   </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
<script>
var sUrlRpc         = 'edu4_regente.RPC.php';
var sVinculoInicial = $('escolha').value;
var sTurma          = "<?=$ed57_c_descr?>";
var sEtapa          = "<?=$ed11_c_descr?>";

function js_escolha(sValor) {

  if (sValor == "gradeHorario") {
  
    location.href = 'edu1_regenciahorario001.php?ed59_i_turma='+<?=$ed59_i_turma?>
                                               +'&ed57_c_descr='+sTurma
                                               +'&ed57_i_turno='+<?=$ed57_i_turno?>
                                               +'&ed59_i_serie='+<?=$ed59_i_serie?>
                                               +'&ed11_c_descr='+sEtapa
                                               +'&iTipoVinculo=2';
    
  } else {
    
    location.href = 'edu1_regenciahorario001.php?ed59_i_turma='+<?=$ed59_i_turma?>
                                               +'&ed57_c_descr='+sTurma
                                               +'&ed57_i_turno='+<?=$ed57_i_turno?>
                                               +'&ed59_i_serie='+<?=$ed59_i_serie?>
                                               +'&ed11_c_descr='+sEtapa
                                               +'&iTipoVinculo=1';
  }
}

/**
 * Validamos se algum dos docentes da turma possui ausencia, e se ja tem substituto cadastrado
 */
function js_validaRegente() {

  var oParametro    = new Object();
  oParametro.exec   = 'validarRegente';
  oParametro.iTurma = <?=$ed59_i_turma?>;
  oParametro.iEtapa = <?=$ed59_i_serie?>;

  var oAjax = new Ajax.Request(
                               sUrlRpc,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaValidaRegente
                               }
                              );
}

function js_retornaValidaRegente(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.lTemRegenteAusente) {

    var sMsg  = "Existe regente com aus�ncia e substituto cadastrado. Para poder remover os v�nculos Regente/Disciplina";
        sMsg += " existentes, � necess�rio primeiramente excluir os v�nculos dos substitutos.";
        
    alert(sMsg);
    $('escolha').value = sVinculoInicial;
    return false;
  } else {
    validaTrocaVinculo();
  }
}

/**
 * Verificamos se eh possivel alterar o tipo de vinculo
 */
function validaTrocaVinculo() {

  var oParametro    = new Object();
  oParametro.exec   = 'validaTrocaVinculo';
  oParametro.iTurma = <?=$ed59_i_turma?>;
  
  var oAjax = new Ajax.Request(
                                sUrlRpc,
                                {
                                  method:     'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornoValidaTrocaVinculo
                                }
                              );
}

function js_retornoValidaTrocaVinculo(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.lTemVinculos) {

    if (confirm(oRetorno.message.urlDecode())) {
      js_excluiVinculos();
    } else {
      
      $('escolha').value = sVinculoInicial;
      return false;
    }
  } else {
    js_escolha($('escolha').value);
  }
}

/**
 * Excluimos os vinculos existentes
 */
function js_excluiVinculos() {

  var oParametro    = new Object();
  oParametro.exec   = 'excluiVinculos';
  oParametro.iTurma = <?=$ed59_i_turma?>;

  var oAjax = new Ajax.Request(
                                sUrlRpc,
                                {
                                  method:     'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornoExcluiVinculos
                                }
                              );
}

function js_retornoExcluiVinculos(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status != 2) {
    
    alert('V�nculos removidos com sucesso.');
    js_escolha($('escolha').value);
  } else {

    alert(oRetorno.message.urlDecode());
    return false;
  }
}
</script>