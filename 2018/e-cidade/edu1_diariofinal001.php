<?
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

//MODULO: educação
//CLASSE DA ENTIDADE diario
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("model/educacao/DBEducacaoTermo.model.php");

db_postmemory($HTTP_POST_VARS);

function getSemDecimal($fDados) {

  return floor($fDados);

}
$resultedu          = eduparametros(db_getsession("DB_coddepto"));
$cldiariofinal      = new cl_diariofinal;
$clprocedimento     = new cl_procedimento;
$clregencia         = new cl_regencia;
$cldiarioresultado  = new cl_diarioresultado;
$clprocresultado    = new cl_procresultado;
$clavalfreqres      = new cl_avalfreqres;
$clperiodoavaliacao = new cl_periodoavaliacao;
$cldiarioavaliacao  = new cl_diarioavaliacao;
$claprovconselho    = new cl_aprovconselho;
$db_opcao           = 1;
$db_botao           = true;
$result1            = $clregencia->sql_record($clregencia->sql_query("","*","","ed59_i_codigo = $regencia"));
db_fieldsmemory($result1,0);
$iCodigoEnsino         = $ed29_i_ensino;
$sLabelAprovado        = 'APROVADO';
$sLabelReprovado       = 'REPROVADO';
$sLabelAprovadoParcial = 'APROVADO PARCIAL';

$oTurma         = TurmaRepository::getTurmaByCodigo( $ed59_i_turma );
$iAnoCalendario = $oTurma->getCalendario()->getAnoExecucao();

$aTermosAprovado  = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'A', $iAnoCalendario);
if (count($aTermosAprovado) > 0) {
  $sLabelAprovado = $aTermosAprovado[0]->sDescricao;
}

$aTermosReprovado = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'R', $iAnoCalendario);
if (count($aTermosReprovado) > 0) {
  $sLabelReprovado = $aTermosReprovado[0]->sDescricao;
}

$aTermosAprovadoParcial = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'P', $iAnoCalendario);
if (count($aTermosAprovadoParcial) > 0) {
  $sLabelAprovadoParcial = $aTermosAprovadoParcial[0]->sDescricao;
}
if (trim($ed57_c_medfreq) == "PERÌODOS") {
  $tipofreq = "Aulas Dadas";
} else {
  $tipofreq = "Dias Letivos";
}
$result2 = $clprocedimento->sql_record($clprocedimento->sql_query("",
                                                                  "ed37_c_tipo",
                                                                  "",
                                                                  "ed40_i_formaavaliacao = $ed40_i_formaavaliacao"
                                                                 )
                                      );
db_fieldsmemory($result2,0);
$result3 = $clprocresultado->sql_record($clprocresultado->sql_query("",
                                                                    "ed43_i_codigo,ed40_i_calcfreq",
                                                                    "",
                                                                    " ed43_i_procedimento = $ed220_i_procedimento"
                                                                   )
                                       );
if ($clprocresultado->numrows > 0) {
  db_fieldsmemory($result3,0);
}

$n_resultados = $clprocresultado->numrows;
$sCampos      = "ed43_i_codigo as codgeraresultado,ed43_c_reprovafreq as reprovafreq,ed43_c_obtencao as formaobtencao";
$sWhere       = " ed43_i_procedimento = $ed220_i_procedimento AND ed43_c_geraresultado = 'S'";
$result3      = $clprocresultado->sql_record($clprocresultado->sql_query("",
                                                                         $sCampos,
                                                                         "",
                                                                         $sWhere
                                                                        )
                                            );
if ($clprocresultado->numrows > 0) {
  db_fieldsmemory($result3,0);
} else {

  echo "<br><br><center>Nenhum resultado do procedimento de avaliação desta turma tem a opção de gerar
         resultado final!</center>";
  exit;

}

if (isset($alterar)) {

  $sql   = " UPDATE diariofinal SET ";
  $sql  .= "       ed74_c_resultadoaprov = '$valor' ";
  $sql  .= "      WHERE ed74_i_codigo = $codigo ";
  $query = db_query($sql);

}

function SomaFaltas($codigo,$diario, $codaluno, $regencia, $reprovafreq, $calcfreq, $codturma, $ed59_i_serie, $iAno) {

  $sql  = " SELECT sum(ed72_i_numfaltas) as faltas, ";
  $sql .= "        sum(ed78_i_aulasdadas) as aulas, ";
  $sql .= "        sum(ed80_i_numfaltas) as abonos ";
  $sql .= "   FROM diarioavaliacao ";
  $sql .= "        inner join avalfreqres     on ed67_i_procavaliacao   = ed72_i_procavaliacao ";
  $sql .= "        inner join regenciaperiodo on ed78_i_procavaliacao   = ed72_i_procavaliacao ";
  $sql .= "        left  join abonofalta      on ed80_i_diarioavaliacao = ed72_i_codigo ";
  $sql .= "  WHERE ed67_i_procresultado = {$codigo} ";
  $sql .= "    AND ed78_i_regencia      = {$regencia} ";
  $sql .= "    AND ed72_i_diario        = {$diario} ";
  $sql .= "    AND ed72_c_amparo        = 'N' ";

  if ($calcfreq == 2) {

    $arred      = eduparametros(db_getsession("DB_coddepto"));
    $arred      = $arred == "S"?2:0;
    $sql1       = " SELECT (((coalesce(sum(ed78_i_aulasdadas),0)-coalesce(sum(ed72_i_numfaltas),0)+ ";
    $sql1      .= "                                                   coalesce(sum(ed80_i_numfaltas),0)) ";
    $sql1      .= "                       / coalesce(sum(ed78_i_aulasdadas),1)::float ";
    $sql1      .= "                     )*100) as perc_total ";
    $sql1      .= "   FROM diarioavaliacao ";
    $sql1      .= "        inner join procavaliacao    on ed41_i_codigo           = ed72_i_procavaliacao ";
    $sql1      .= "        inner join periodoavaliacao on ed09_i_codigo           = ed41_i_periodoavaliacao ";
    $sql1      .= "        inner join avalfreqres      on ed67_i_procavaliacao    = ed41_i_codigo ";
    $sql1      .= "        inner join diario           on ed95_i_codigo           = ed72_i_diario ";
    $sql1      .= "        inner join regencia         on ed59_i_codigo           = ed95_i_regencia ";
    $sql1      .= "        inner join regenciaperiodo  on ed78_i_procavaliacao    = ed41_i_codigo ";
    $sql1      .= "                                   and ed78_i_regencia         = ed95_i_regencia  ";
    $sql1      .= "        left  join abonofalta        on ed80_i_diarioavaliacao = ed72_i_codigo ";
    $sql1      .= "  WHERE ed67_i_procresultado = {$codigo} ";
    $sql1      .= "    AND ed95_i_regencia in (select ed59_i_codigo "; 
    $sql1      .= "                              from regencia  ";
    $sql1      .= "                             where ed59_i_turma    = {$codturma} ";
    $sql1      .= "                               AND ed59_i_serie    = {$ed59_i_serie} "; 
    $sql1      .= "                               AND ed59_c_condicao = 'OB') ";
    $sql1      .= "    AND ed95_i_aluno  = {$codaluno} ";
    $sql1      .= "    AND ed72_c_amparo = 'N' ";
    $sql1      .= "    AND ed09_c_somach = 'S' ";

    $result1    = db_query($sql1);
    $perc_total = ArredondamentoFrequencia::arredondar(pg_result($result1,0,'perc_total'), $iAno);

  } else {
    $perc_total = 0;
  }

  $result5 = db_query($sql);
  $faltas  = pg_result($result5,0,'faltas') == ""?0:pg_result($result5,0,'faltas');
  $aulas   = pg_result($result5,0,'aulas')  == ""?0:pg_result($result5,0,'aulas');
  $abonos  = pg_result($result5,0,'abonos') == ""?0:pg_result($result5,0,'abonos');
  if ($aulas == 0) {
    if ($reprovafreq == "N") {
      $perc_presenca = 1;
    } else {
      $perc_presenca = -1;
    }
  } else {

    $presenca      = $aulas-$faltas+$abonos;
    $perc_presenca = ($presenca/($aulas == 0?1:$aulas));
  }
  $nPercentualPresenca = ArredondamentoFrequencia::arredondar($perc_presenca * 100, $iAno);
  return ($nPercentualPresenca)."|".$faltas."|".$abonos."|".$aulas."|".$perc_total;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
.valor{
 color: #000000;
 font-family : Tahoma;
 font-size: 11;
 font-weight: bold;
}
.alunopq{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
 padding-top: 0px;
 padding-bottom: 0px;
}
</style>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<input name="ed43_i_codigo" type="hidden" value="<?=$ed43_i_codigo?>">
<input name="regencia" type="hidden" value="<?=$regencia?>">
<?
if ($n_resultados == 0) {

  echo "<br><br><center>Procedimento de avaliação escolhido para esta turma não contém
                        nenhum resultado cadastrado!</center>";
} else {

  $sWhere  = "WHERE ed95_i_regencia = $regencia ";
  $sWhere .= "  AND ed60_i_turma = $ed59_i_turma ";
  $sWhere .= "  AND ed221_c_origem = 'S' ";

  if ($iTrocaTurma == 1) {
    $sWhere .= "  AND ed60_c_situacao <> 'TROCA DE TURMA'";
  }

  $sCampos  = " convencaoamp.*,ed60_c_concluida,ed60_c_parecer,diariofinal.*,ed60_i_numaluno,ed60_i_codigo,ed60_c_ativa,";
  $sCampos .= " ed47_v_nome,ed47_i_codigo,ed60_c_situacao,ed81_c_todoperiodo,ed81_i_justificativa,ed81_i_convencaoamp, ";
  $sCampos .= "ed81_c_todoperiodo,ed95_c_encerrado, ed95_i_codigo, ed60_matricula,";
  $sCampos .= "exists(select 1 ";
  $sCampos .= "         from progressaoparcialaluno ";
  $sCampos .= "              inner join progressaoparcialalunodiariofinalorigem on ed107_progressaoparcialaluno = ed114_sequencial ";
  $sCampos .= "        where ed74_i_codigo = ed107_diariofinal";
  $sCampos .= "      ) as disciplina_com_progressao, ed59_c_condicao";
  $sql      = " SELECT {$sCampos} ";
  $sql     .= "   FROM matricula ";
  $sql     .= "        inner join aluno          on ed47_i_codigo     = ed60_i_aluno ";
  $sql     .= "        inner join diario         on ed95_i_aluno      = ed47_i_codigo ";
  $sql     .= "        inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql     .= "        inner join regencia       on ed59_i_codigo     = ed95_i_regencia ";
  $sql     .= "                                 and ed59_i_serie      = ed221_i_serie ";
  $sql     .= "        inner join diariofinal    on ed74_i_diario     = ed95_i_codigo ";
  $sql     .= "        left  join amparo         on ed81_i_diario     = ed95_i_codigo ";
  $sql     .= "        left  join convencaoamp   on ed250_i_codigo    = ed81_i_convencaoamp ";
  $sql     .= "        {$sWhere} ";
  $sql     .= "  ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa ";
  $result2  = db_query($sql);
  $linhas   = pg_num_rows($result2);
  if ($ed59_c_encerrada == "S") {
    $pri_calculo = pg_result($result2,0,'ed74_i_calcfreq');
  } else {
    $pri_calculo = $ed40_i_calcfreq;
  }
  if ($pri_calculo == 2) {
    $add_coluna = 1;
  } else {
    $add_coluna = 0;
  }
?>
<table border='0' width="96%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
 <tr>
  <td class='titulo'>
   &nbsp;<?=$ed232_c_descr?> - RESULTADO FINAL | Turma <?=$ed57_c_descr?> - <?=$ed11_c_descr?> -
           Calendário <?=$ed52_c_descr?>
  </td>
  <td class='titulo' align="right">
   <input type="button" id="aprovforcada" value="Alterar Resultado Final" onclick="js_alteraresultado(<?=$regencia?>)">
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <table border='3px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
    <tr align="center" >
     <td colspan="4" class='cabec1'>Alunos</td>
     <td colspan="2" class="cabec1">Aproveitamento</td>
     <td colspan="<?=5+$add_coluna?>" class="cabec1">Cálculo Frequência
          <?=$ed40_i_calcfreq == 1 || $pri_calculo == 1?"(Carga Horária por Disciplina)":"(Carga Horária Total-CHT)"?>
     </td>
     <td class="cabec1">Resultado Final</td>
     <td class="cabec1">Observações</td>
    </tr>
    <tr align="center">
     <td class="cabec1">N°</td>
     <td class="cabec1">Nome</td>
     <td class="cabec1">Situação</td>
     <td class="cabec1">Código</td>
     <?if (trim($ed37_c_tipo) == "PARECER") {?>
         <td colspan="2" class="cabec1">Resultado</td>
     <?} else {?>

         <td class="cabec1">Valor</td>
         <td class="cabec1">Resultado</td>

     <?}?>

     <td class="cabec1">Aulas</td>
     <td class="cabec1">Faltas</td>
     <td class="cabec1">Abonos</td>
     <?if ($pri_calculo == 2) {?>

         <td class="cabec1">% DISC.</td>
         <td class="cabec1">% CHT</td>

     <?} else {?>
         <td class="cabec1">%</td>
     <?}?>
     <td class="cabec1">Resultado</td>
     <td class="cabec1">Final</td>
     <td class="cabec1">Observações</td>
    </tr>
    <?
    if ($linhas > 0) {

      $cor1 = "#f3f3f3";
      $cor2 = "#DBDBDB";
      $cor  = "";
      for ($x = 0; $x < $linhas; $x++) {

        db_fieldsmemory($result2,$x);

        if (isset($ed74_i_percfreq) && !empty($ed74_i_percfreq)) {
          $ed74_i_percfreq = ArredondamentoFrequencia::formatar($ed74_i_percfreq, $ed52_i_ano);
        }

        if ($ed60_c_parecer == "S"){

          $ed37_c_tipo_ant = $ed37_c_tipo;
          $ed37_c_tipo     = "PARECER";

        }

        if ($cor == $cor1) {
          $cor = $cor2;
        } else {
          $cor = $cor1;
        }
        if ($ed60_c_situacao != "MATRICULADO") {

          $disabled    = "disabled";
          $cordisabled = "#FFD5AA";

        } else {

          $disabled    = "";
          $cordisabled = "#FFFFFF";

        }
      ?>
        <tr bgcolor="<?=$cor?>">
        <td align="right" class='aluno'><?=$ed60_i_numaluno?></td>
        <td class='aluno'>
        <a class="aluno" href="javascript:js_movimentos(<?=$ed60_i_codigo?>)"><?=$ed47_v_nome?>
        </a>
         <?=$ed60_c_parecer == "S"?"<b>&nbsp;&nbsp;&nbsp;(NEE - Parecer)</b>":""?>
        </td>
        <td align="center" class='aluno'>
        <?
        if (trim($ed81_c_todoperiodo) == "S" && $ed60_c_ativa == "S") {
          if ($ed81_i_justificativa != "") {
          	$valor = 'APROVADO';
            echo "AMPARADO";
          } else {
            echo "$ed250_c_abrev";
          }
        } else {
          echo Situacao($ed60_c_situacao,$ed60_i_codigo);
        }
        ?>
        </td>
        <td align="right" class='aluno'><b><?=$ed47_i_codigo?></b></td>
        <?
        if (trim($ed60_c_concluida) == "S") {

          if (trim($ed60_c_situacao != "MATRICULADO")) {

        ?>
            <td class='aluno'>&nbsp;</td>
            <td align='center' class='aluno'>&nbsp;</td>
            <td align='center' class='aluno'>&nbsp;</td>
            <td align='center' class='aluno'>&nbsp;</td>
            <td align='center' class='aluno'>&nbsp;</td>
            <td align='center' class='aluno'>&nbsp;</td>
            <td align='center' class='aluno'>&nbsp;</td>
          <?if ($pri_calculo == 2) {?>
              <td align='center' class='aluno'>&nbsp;</td>
          <?}?>
            <td align='center' class='aluno'>&nbsp;</td>
        <?

          } else {

            if (trim($ed81_c_todoperiodo) == "S") {

            ?>
              <td class='aluno'>&nbsp;</td>
              <td class='aluno'>&nbsp;<?=$ed74_c_valoraprov?></td>
              <td class='aluno' colspan="<?=5+$add_coluna?>">&nbsp;</td>
              <td class='aluno' align="center"><font color="<?=$ed74_c_resultadofinal == 'R'?'#FF0000':'#000000'?>">
                  <?=$ed74_c_resultadofinal == "A"?"{$sLabelAprovado}":"$sLabelReprovado"?></font></td>

              <?if (trim($ed74_c_resultadofinal) != "") {?>
                  <td class='aluno' align="center"><b>
                     <a style="color:green;text-decoration:none;"
                        href="javascript:js_observacoes(<?=$ed47_i_codigo?>,'<?=$ed47_v_nome?>',<?=$ed74_i_codigo?>);"
                        title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b>
                  </td>
          <?
                } else {?>
                  <td align='center' class='aluno'>&nbsp;</td>
              <?}
            } else if (trim($ed59_c_freqglob) != "F") {

         ?>
            <?if (trim($ed37_c_tipo) == "PARECER") {?>
                <td colspan="2" class='aluno'align="center">
                  <?=$ed74_c_resultadoaprov == "A"?"{$sLabelAprovado}":"$sLabelReprovado"?>
                </td>
            <?} else {?>

              <?if (trim($ed37_c_tipo) == "NOTA") {?>
                    <td class='aluno' align="right"><?=ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano)?></td>
              <?} else {?>
                  <td class='aluno' align="center"><?=$ed74_c_valoraprov?></td>
              <?}?>
                <td class='aluno' align="center"><?=$ed74_c_resultadoaprov == "A"?"{$sLabelAprovado}":"{$sLabelReprovado}"?></td>
            <?}?>
            <?if (trim($ed59_c_freqglob) == "A") {?>
                <td class='aluno' colspan="<?=5+$add_coluna?>" align="center">Disciplina sem frequência</td>
            <?} else {
                $perc_freq = SomaFaltas($ed74_i_procresultadofreq,$ed74_i_diario,$ed47_i_codigo,$regencia,$reprovafreq,
                                        $ed74_i_calcfreq,$ed59_i_turma,$ed59_i_serie, $ed52_i_ano
                                       );
                $perc_array    = explode("|",$perc_freq);
           ?>
                <td align="center" class='aluno'><?=@$perc_array[3]==""?0:@$perc_array[3]?></td>
                <td align="center" class='aluno'><?=@$perc_array[1]==""?0:@$perc_array[1]?></td>
                <td align="center" class='aluno'><?=@$perc_array[2]==""?0:@$perc_array[2]?></td>
              <?if ($resultedu == 'S') {?>
                <?if ($pri_calculo == 2) {?>
                    <td class='aluno' align="right"><?=number_format(@$perc_array[0],2,".",".")?></td>
                    <td class='aluno' align="right">
                       <a style="color:black"
                           href="javascript:js_calculofreq(<?=$ed74_i_procresultadofreq?>,<?=$ed59_i_turma?>,
                                                           <?=$ed47_i_codigo?>,
                                                           '<?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>')">
                                                            <?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>
                       </a>
                    </td>
                <?} else {?>
                    <td class='aluno' align="right"><?=$ed74_i_percfreq?></td>
                <?}?>
              <?} else {?>
                <?if ($pri_calculo == 2) {?>
                    <td class='aluno' align="right"><?=$perc_array[0]?></td>
                    <td class='aluno' align="right">
                      <a style="color:black"
                         href="javascript:js_calculofreq(<?=$ed74_i_procresultadofreq?>,
                                                        <?=$ed59_i_turma?>,
                                                        <?=$ed47_i_codigo?>,
                                                        '<?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>')">
                                                        <?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>
                      </a>
                    </td>
                <?} else {?>
                    <td class='aluno' align="right"><?=$ed74_i_percfreq?></td>
                <?}?>
              <?}?>
           <?
                if ($reprovafreq == "N") {
                  $res_freqq = "Não reprova por frequência";
                } else {
                  $res_freqq = $ed74_c_resultadofreq=="A"?"{$sLabelAprovado}":"{$sLabelReprovado}";
                }
           ?>
                <td class='aluno' align="center"><?=$res_freqq?></td>
            <?}?>
              <td class='aluno' align="center">
                <font color="<?=$ed74_c_resultadofinal=='R'?'#FF0000':'#000000'?>">
                  <?
                   echo $ed74_c_resultadofinal == "A"?"{$sLabelAprovado}":"{$sLabelReprovado}";
                   if ($disciplina_com_progressao == 't') {
                     echo " (Progressão Parcial / Dependência)";
                   }
                  ?>
                </font>
              </td>
            <?if (trim($ed74_c_resultadofinal) != "") {?>
                <td class='aluno' align="center"><b>
                  <a style="color:blue;text-decoration:none;"
                     href="javascript:js_observacoes(<?=$ed47_i_codigo?>,'<?=$ed47_v_nome?>',<?=$ed74_i_codigo?>);"
                     title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O </a></b>
                </td>
         <?
              } else {?>
                <td align='center' class='aluno'>&nbsp;</td>
            <?}
            } else {
              $perc_freq = SomaFaltas($ed43_i_codigo,$ed74_i_diario,$ed47_i_codigo,$regencia,$reprovafreq,
                                      $ed40_i_calcfreq,$ed59_i_turma,$ed59_i_serie, $ed52_i_ano
                                     );
              $perc_array    = explode("|",$perc_freq);
         ?>
              <td colspan="2" class='aluno' align="center">Disciplina sem aproveitamento</td>
              <td align="center" class='aluno'><?=@$perc_array[3] == ""?0:@$perc_array[3]?></td>
              <td align="center" class='aluno'><?=@$perc_array[1] == ""?0:@$perc_array[1]?></td>
              <td align="center" class='aluno'><?=@$perc_array[2] == ""?0:@$perc_array[2]?></td>
            <?if ($resultedu == 'S') {?>
              <?if ($pri_calculo == 2) {?>
                  <td class='aluno' align="right"><?=$perc_array[0]?></td>
                  <td class='aluno' align="right">
                  <a style="color:black"
                     href="javascript:js_calculofreq(<?=$ed43_i_codigo?>,
                                                     <?=$ed59_i_turma?>,
                                                     <?=$ed47_i_codigo?>,
                                                     '<?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>')">
                                                     <?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>
                  </a>
                 </td>
              <?} else {?>
                  <td class='aluno' align="right"><?=$ed74_i_percfreq?></td>
              <?}?>
            <?} else {?>
              <?if ($pri_calculo == 2) {?>
                  <td class='aluno' align="right"><?=$perc_array[0]?></td>
                  <td class='aluno' align="right">
                  <a style="color:black"
                     href="javascript:js_calculofreq(<?=$ed43_i_codigo?>,
                                                     <?=$ed59_i_turma?>,
                                                     <?=$ed47_i_codigo?>,
                                                     '<?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>')">
                                                     <?=ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano)?>
                  </a>
                 </td>
              <?} else {?>
                  <td class='aluno' align="right"><?=$ed74_i_percfreq?></td>
              <?}?>
            <?}?>
               <td class='aluno' align="center"><?=$ed74_c_resultadofreq=="A"?"{$sLabelAprovado}":"{$sLabelReprovado}"?></td>
               <td class='aluno' align="center">
                  <font color="<?=$ed74_c_resultadofinal=='R'?'#FF0000':'#000000'?>">
                        <?=$ed74_c_resultadofinal=="A"?"{$sLabelAprovado}":"{$sLabelReprovado}"?>
                  </font>
               </td>
             <?if (trim($ed74_c_resultadofinal) != "") {?>
                 <td class='aluno' align="center"><b>
                    <a style="color:blue;text-decoration:none;"
                       href="javascript:js_observacoes(<?=$ed47_i_codigo?>,'<?=$ed47_v_nome?>',<?=$ed74_i_codigo?>);"
                       title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b>
                 </td>
         <?
               } else {?>
                 <td align='center' class='aluno'>&nbsp;</td>
             <?}
            }
          }
        } else {

          if (trim($ed81_c_todoperiodo) == "S") {

            db_inicio_transacao();
            $cldiariofinal->ed74_i_procresultadoaprov = "";
            $cldiariofinal->ed74_c_valoraprov         = "";
            $cldiariofinal->ed74_c_resultadoaprov     = "A";
            $cldiariofinal->ed74_i_procresultadofreq  = "";
            $cldiariofinal->ed74_i_percfreq           = "";
            $cldiariofinal->ed74_c_resultadofreq      = "A";
            $cldiariofinal->ed74_c_resultadofinal     = "A";
            $cldiariofinal->ed74_i_calcfreq           = $ed40_i_calcfreq;
            $cldiariofinal->ed74_i_codigo             = $ed74_i_codigo;
            $cldiariofinal->alterar($ed74_i_codigo);
            db_fim_transacao();
        ?>
            <td class='aluno'>&nbsp;</td>
            <td class='aluno'>&nbsp;</td>
            <td class='aluno' colspan="<?=5+$add_coluna?>">&nbsp;</td>

        <?
          } else {
            $valor = "";
            if (trim($ed59_c_freqglob) != "F") {
              if (trim($ed37_c_tipo) == "NOTA") {
                $campo = "ed73_i_valornota";
                $campoaval = "ed72_i_valornota";
                $where1 = "0";
              } else if (trim($ed37_c_tipo) == "NIVEL") {
                $campo = "ed73_c_valorconceito";
                $campoaval = "ed72_c_valorconceito";
                $where1 = "''";
              } else if (trim($ed37_c_tipo) == "PARECER") {
                $campo = "ed73_t_parecer";
                $campoaval = "ed72_t_parecer";
                $where1 = "''";
              }
              $sCampos = "ed43_i_codigo as codigo,$campo as valor,ed73_c_aprovmin,ed43_c_reprovafreq as reprovafreq";
              $sWhere = " ed73_i_diario = $ed74_i_diario AND ed43_i_codigo = $codgeraresultado";
              $result3 = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("",
                                                                                      $sCampos,
                                                                                      "ed43_i_sequencia",
                                                                                      $sWhere
                                                                                     )
                                                       );
              if ($cldiarioresultado->numrows == 0 || $ed60_c_situacao != "MATRICULADO") {

                $codigo        = "";
                $valor         = "";
                $resultado     = "";
                $codigofreq    = "";
                $valorfreq     = "";
                $resultadofreq = "";
                ?>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
              <?if ($pri_calculo == 2) {?>
                  <td class='aluno'>&nbsp;</td>
              <?}?>
	            <td class='aluno'>&nbsp;</td>
            <?} else if ($cldiarioresultado->numrows == 1) {

                db_fieldsmemory($result3,0);

                /**
                 * Validamos se o aluno eh avaliado por parecer, quando turma eh por nota, atribuindo o resultado como
                 * aprovado (ou de acordo com o termo cadastrado para o ensino)
                 */
                if ($ed60_c_parecer == "S") {
                  $valor = "{$sLabelAprovado}";
                }

                $sql_v_obri    = " SELECT ed41_i_codigo ";
                $sql_v_obri   .= "   FROM diarioavaliacao ";
                $sql_v_obri   .= "        inner join diario           on ed95_i_codigo        = ed72_i_diario  ";
                $sql_v_obri   .= "        inner join procavaliacao    on ed41_i_codigo        = ed72_i_procavaliacao ";
                $sql_v_obri   .= "        inner join periodoavaliacao on ed09_i_codigo        = ed41_i_periodoavaliacao ";
                $sql_v_obri   .= "        inner join formaavaliacao   on ed37_i_codigo        = ed41_i_formaavaliacao ";
                $sql_v_obri   .= "        inner join avalcompoeres    on ed44_i_procavaliacao = ed41_i_codigo ";
                $sql_v_obri   .= "  WHERE ed95_i_calendario   = {$ed57_i_calendario} ";
                $sql_v_obri   .= "    AND ed41_i_procedimento = {$ed220_i_procedimento} ";
                $sql_v_obri   .= "    AND ed95_i_aluno        = {$ed47_i_codigo} ";
                $sql_v_obri   .= "    AND ed95_i_regencia     = {$regencia} ";
                $sql_v_obri   .= "    AND (case   ";
                $sql_v_obri   .= "              when '{$ed37_c_tipo}' = 'NOTA' ";
                $sql_v_obri   .= "                   then ed72_i_valornota is null ";
                $sql_v_obri   .= "              when '{$ed37_c_tipo}' = 'PARECER' ";
                $sql_v_obri   .= "                   then ed72_t_parecer = '' ";
                $sql_v_obri   .= "              when '{$ed37_c_tipo}' = 'NIVEL' ";
                $sql_v_obri   .= "                   then ed72_c_valorconceito is null ";
                $sql_v_obri   .= "         end) ";
                $sql_v_obri   .= "    AND ed44_c_obrigatorio = 'S' ";
                $sql_v_obri   .= "    AND ed72_c_amparo      = 'N'";
                $result_v_obri = db_query($sql_v_obri);
                if (pg_num_rows($result_v_obri) > 0) {
                  $valor = "";
                }

                if (trim($valor) != '' || $ed220_c_aprovauto == "S" || $ed60_c_parecer == "S"  || trim($ed37_c_tipo == "PARECER")) {
                  if (trim($ed37_c_tipo == "PARECER")) {
                    $valor = "Parecer";
                    if ($ed73_c_aprovmin == "S") {
                      $resultado = "A";
                    } else if ($ed73_c_aprovmin == "N") {
                      $resultado = "R";
                    } else {
                      $resultado = "";
                    }
                    ?>
                   <td align="center" class='aluno' colspan="2">
                   <?

                    if ($ed73_c_aprovmin == "S") {
                      $mresultado = "{$sLabelAprovado}";
                    } else if ($ed73_c_aprovmin == "N") {
                      $mresultado = "{$sLabelReprovado}";
                    } else {
                      $mresultado = "&nbsp;";
                    }
                    if ($ed220_c_aprovauto == "S") {
                      $mresultado = "{$sLabelAprovado}";
                      $resultado = "A";
                    }

                    if ($ed59_c_condicao == 'OP') {

                      $mresultado = "{$sLabelAprovado}";
                      $resultado = "A";
                    }

                    echo $mresultado;
                   ?>
                   </td>
                <?} else {
                    $resultado = $ed73_c_aprovmin == "N"?"R":"A";
                    ?>
                      <td align="<?=trim($ed37_c_tipo)=='NIVEL'?'center':'right'?>" class='valor'>
                       <?=trim($ed37_c_tipo)=="NIVEL"?$valor:ArredondamentoNota::formatar($valor, $ed52_i_ano)?>
                      </td>
                    <td align="center" class='aluno'>
                   <?
                     if ($ed73_c_aprovmin == "S") {
                       $mresultado = "{$sLabelAprovado}";
                     } else if ($ed73_c_aprovmin == "N") {
                       $mresultado = "{$sLabelReprovado}";
                     } else {
                       $mresultado = "&nbsp;";
                     }
                     if ($ed220_c_aprovauto == "S") {
                       $mresultado = "{$sLabelAprovado}";
                       $resultado = "A";
                     }
                     if ($ed59_c_condicao == 'OP') {

                       $mresultado = "{$sLabelAprovado}";
                       $resultado = "A";
                     }
                     echo $mresultado;
                    ?>
                    </td>
                <?}
                  if (trim($ed59_c_freqglob == "A")) {
                    $perc_freq = "&nbsp;";
                    $res_freq = "Disciplina sem frequência";
                    $codigofreq = "";
                    $valorfreq = "";
                    $resultadofreq = "A";
                  } else {
                    $perc_freq = SomaFaltas($codigo,$ed74_i_diario,$ed47_i_codigo,$regencia,$reprovafreq,
                                            $ed40_i_calcfreq,$ed59_i_turma,$ed59_i_serie, $ed52_i_ano
                                           );

                    $perc_array    = explode("|",$perc_freq);
                    if ($perc_freq < 0) {

                      $perc_freq = "&nbsp;";
                      $res_freq = "Preencha $tipofreq (Geral)";
                      $codigofreq = "";
                      $valorfreq = "";
                      $resultadofreq = "";
                    } else {
                      if ($pri_calculo == 2) {
                        $perc_freq = $perc_array[4];
                      } else {
                        $perc_freq = $perc_array[0];
                      }
                      if ($reprovafreq == "N") {
                        $res_freq = "Não reprova por frequência";
                        $resultadofreq = "A";
                      } else {
                        $res_freq = $perc_freq>=$ed40_i_percfreq?"{$sLabelAprovado}":"{$sLabelReprovado}";
                        $resultadofreq = $perc_freq>=$ed40_i_percfreq?"A":"R";
                        if ($ed220_c_aprovauto == "S") {

                          $res_freq      = "{$sLabelAprovado}";
                          $resultadofreq = "A";
                        }
                      }
                      $codigofreq = $codigo;
                      $valorfreq = $perc_freq;
                    }
                  }
                  if (trim($ed59_c_freqglob == "A")) {?>
                    <td colspan="<?=5+$add_coluna?>" align="center" class='aluno'><?=$res_freq?></td>
                <?} else {?>
                    <td align="center" class='aluno'><?=@$perc_array[3]==""?0:@$perc_array[3]?></td>
                    <td align="center" class='aluno'><?=@$perc_array[1]==""?0:@$perc_array[1]?></td>
                    <td align="center" class='aluno'><?=@$perc_array[2]==""?0:@$perc_array[2]?></td>
                  <?if ($resultedu == 'S') {?>
                      <td align="right" class='aluno'><?=$perc_array[0]?></td>
                    <?if ($pri_calculo == 2) {?>
                        <td align="right" class='aluno'>
                         <a style="color:black"
                             href="javascript:js_calculofreq(<?=$codigo?>,<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,
                                                             '<?=number_format(@$perc_array[4],2,".",".")?>')">
                            <?=@$perc_array[4]==""?0.00:number_format(@$perc_array[4],2,".",".")?>
                         </a>
                        </td>
                    <?}?>
                  <?} else {?>
                      <td align="right" class='aluno'><?=$perc_array[0]?></td>
                    <?if ($pri_calculo == 2) {?>
                        <td align="right" class='aluno'>
                          <a style="color:black"
                             href="javascript:js_calculofreq(<?=$codigo?>,<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,
                                                             '<?=number_format(@$perc_array[4],2,".",".")?>')">
                           <?=@$perc_array[4]==""?0:number_format(@$perc_array[4],2,".",".")?>
                          </a>
                         </td>
                    <?}?>
                  <?}?>
                    <td align="center" class='aluno'><?=$res_freq?></td>
                <?}?>
           <?
                } else {
                  $codigo        = "";
                  $valor         = "";
                  $resultado     = "";
                  $codigofreq    = "";
                  $valorfreq     = "";
                  $resultadofreq = "";
           ?>
                  <td class='aluno'>&nbsp;</td>
                  <td class='aluno'>&nbsp;</td>
                  <td class='aluno'>&nbsp;</td>
                  <td class='aluno'>&nbsp;</td>
                  <td class='aluno'>&nbsp;</td>
                  <td class='aluno'>&nbsp;</td>
                <?if ($pri_calculo == 2) {?>
                    <td class='aluno'>&nbsp;</td>
                <?}?>
                  <td class='aluno'>&nbsp;</td>
              <?
                }
              } else {
                $codigo        = "";
                $valor         = "";
                $resultado     = "";
                $codigofreq    = "";
                $valorfreq     = "";
                $resultadofreq = "";
              ?>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
                <td class='aluno'>&nbsp;</td>
              <?if ($pri_calculo == 2) {?>
                  <td class='aluno'>&nbsp;</td>
              <?}?>
                <td class='aluno'>&nbsp;</td>
            <?}
              db_inicio_transacao();


              if ($ed59_c_condicao == 'OP') {

                $resultado = 'A';
              }

              $cldiariofinal->ed74_i_procresultadoaprov = $codigo;
              $mValorAprovacao = ArredondamentoNota::Arredondar($valor, $ed52_i_ano);
              $cldiariofinal->ed74_c_valoraprov         = $mValorAprovacao == 0 ? "{$mValorAprovacao}" : $mValorAprovacao;
              $cldiariofinal->ed74_c_resultadoaprov     = $resultado;
              $cldiariofinal->ed74_i_procresultadofreq  = $codigofreq;
              $cldiariofinal->ed74_i_percfreq           = $valorfreq;
              $cldiariofinal->ed74_c_resultadofreq      = $resultadofreq;
              $cldiariofinal->ed74_i_calcfreq           = $ed40_i_calcfreq;
              $cldiariofinal->ed74_i_codigo             = $ed74_i_codigo;
              $cldiariofinal->alterar($ed74_i_codigo);
              db_fim_transacao();
            } else {
              $sql        = " SELECT ed78_i_procavaliacao,ed78_i_aulasdadas,ed09_c_abrev as abrevpreencha ";
              $sql       .= "   FROM diarioavaliacao ";
              $sql       .= "        inner join procavaliacao    on ed41_i_codigo        = ed72_i_procavaliacao ";
              $sql       .= "        inner join regenciaperiodo  on ed78_i_procavaliacao = ed72_i_procavaliacao ";
              $sql       .= "        inner join periodoavaliacao on ed09_i_codigo        = ed41_i_periodoavaliacao ";
              $sql       .= "  WHERE ed78_i_regencia = {$regencia} ";
              $sql       .= "    AND ed72_i_diario   = {$ed74_i_diario} ";
              $sql       .= "    AND ed09_c_somach   = 'S' ";
              $sql       .= "  ORDER BY ed09_i_sequencia ";
              $result6    = db_query($sql);
              $linhas6    = pg_num_rows($result6);
              $embranco   = false;
              $per_branco = "";
              $vrg        = "";
              for ($a = 0; $a < $linhas6; $a++) {
                if (pg_result($result6,$a,'ed78_i_aulasdadas') == "") {
                  $embranco    = true;
                  $per_branco .= $vrg.pg_result($result6,$a,'abrevpreencha');
                  $vrg = " - ";
                }
              }
              
              if ($embranco == true) {
                $perc_freq = -1;
              } else {

                $sql           = " SELECT sum(ed72_i_numfaltas) as faltas, ";
                $sql          .= "        sum(ed78_i_aulasdadas) as aulas, ";
                $sql          .= "        sum(ed80_i_numfaltas) as abonos ";
                $sql          .= "   FROM diarioavaliacao ";
                $sql          .= "        inner join regenciaperiodo  on ed78_i_procavaliacao   = ed72_i_procavaliacao ";
                $sql          .= "        inner join procavaliacao    on ed41_i_codigo          = ed72_i_procavaliacao ";
                $sql          .= "        inner join periodoavaliacao on ed09_i_codigo          = ed41_i_periodoavaliacao ";
                $sql          .= "        left  join abonofalta       on ed80_i_diarioavaliacao = ed72_i_codigo ";
                $sql          .= "  WHERE ed72_i_diario   = {$ed74_i_diario} ";
                $sql          .= "    AND ed78_i_regencia = {$regencia} ";
                $sql          .= "    AND ed09_c_somach   = 'S' ";
                $sql          .= "    AND ed72_c_amparo   = 'N' ";
                $result5       = db_query($sql);
                $faltas        = pg_result($result5,0,'faltas');
                $aulas         = pg_result($result5,0,'aulas');
                $abonos        = pg_result($result5,0,'abonos');
                $presenca      = $aulas-$faltas+$abonos;
                $perc_presenca = $presenca/$aulas;
                if ($resultedu == 'S') {
                  $perc_freq = ArredondamentoFrequencia::arredondar($perc_presenca*100, $ed52_i_ano);
                } else {
                  $perc_freq = ArredondamentoFrequencia::arredondar($perc_presenca*100, $ed52_i_ano);
                }
              }
              
              if ($perc_freq < 0) {

                $perc_freq     = "&nbsp;";
                $res_freq      = "Preencha $tipofreq ($per_branco)";
                $codigofreq    = "";
                $valorfreq     = "";
                $resultadofreq = "";
                $faltas        = "";
                $aulas         = "";
                $abonos        = "";
                $preencha      = true;
              } else {

                $perc_freq     = $perc_freq;
                $res_freq      = $perc_freq>=$ed40_i_percfreq?"{$sLabelAprovado}":"{$sLabelReprovado}";
                $codigofreq    = "";
                $valorfreq     = $perc_freq;
                $resultadofreq = $perc_freq>=$ed40_i_percfreq?"A":"R";
              }
              if (trim($ed60_c_situacao != "MATRICULADO")) {
                $perc_freq     = "";
                $res_freq      = "";
                $codigofreq    = "";
                $valorfreq     = "";
                $resultadofreq = "";
                ?>
                <td class='aluno'>&nbsp;</td>
                <td align='center' class='aluno'>&nbsp;</td>
                <td align='center' class='aluno'>&nbsp;</td>
                <td align='center' class='aluno'>&nbsp;</td>
                <td align='center' class='aluno'>&nbsp;</td>
                <td align='center' class='aluno'>&nbsp;</td>
              <?if ($pri_calculo == 2) {?>
                  <td align='center' class='aluno'>&nbsp;</td>
              <?}?>
                <td align='center' class='aluno'>&nbsp;</td>
              <?
              } else {
              ?>
                <td colspan="2" align='center' class='aluno'>Disciplina sem aproveitamento</td>
              <?if (isset($preencha)) {?>
                  <td colspan="<?=5+$add_coluna?>" align='center' class='aluno'><?=$res_freq?></td>
              <?} else {?>
                  <td align='center' class='aluno'><?=$aulas==""?0:$aulas?></td>
                  <td align='center' class='aluno'><?=$faltas==""?0:$faltas?></td>
                  <td align='center' class='aluno'><?=$abonos==""?0:$abonos?></td>
                <?if ($resultedu == "S") {
                ?>
                    <td align='right' class='aluno'><?=ArredondamentoFrequencia::arredondar($perc_presenca*100, $ed52_i_ano);?></td>
                <?} else {?>
                    <td align='right' class='aluno'><?=ArredondamentoFrequencia::arredondar($perc_presenca*100, $ed52_i_ano);?></td>
                <?}?>
                  <td align='center' class='aluno'><?=$res_freq?></td>
              <?}
              }
              if ($ed220_c_aprovauto == "S") {
                $resultadofreq = "A";
              }

              if ($ed59_c_condicao == 'OP') {

                $resultado = 'A';
              }
              db_inicio_transacao();
              $cldiariofinal->ed74_i_procresultadoaprov = "";
              $cldiariofinal->ed74_c_valoraprov         = "";
              $cldiariofinal->ed74_c_resultadoaprov     = "A";
              $cldiariofinal->ed74_i_procresultadofreq  = $codigofreq;
              $cldiariofinal->ed74_i_percfreq           = $valorfreq;
              $cldiariofinal->ed74_c_resultadofreq      = $resultadofreq;
              $cldiariofinal->ed74_i_calcfreq           = $ed40_i_calcfreq;
              $cldiariofinal->ed74_i_codigo             = $ed74_i_codigo;
              $cldiariofinal->alterar($ed74_i_codigo);
              db_fim_transacao();
            }
          }
          if (trim($ed37_c_tipo) == "NOTA") {
            $campoaval = "ed72_i_valornota is null";
          } else if(trim($ed37_c_tipo) == "NIVEL") {
            $campoaval = "ed72_c_valorconceito = ''";
          } else if (trim($ed37_c_tipo) == "PARECER") {
            $campoaval = "ed72_t_parecer = '' ";
          }
          $sWhere   = " ed72_i_diario = $ed74_i_diario AND $campoaval AND ed72_c_amparo = 'N' ";
          $sWhere  .= " AND ed09_c_somach = 'S' AND ed37_c_tipo = '$ed37_c_tipo'";
          $result33 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",
                                                                                   "ed72_i_codigo",
                                                                                   "ed41_i_sequencia",
                                                                                   $sWhere
                                                                                  )
                                                    );
          $sWhere = " ed74_i_diario = $ed74_i_diario AND ed74_c_resultadoaprov != '' AND ed74_c_resultadofreq != ''";
          $sql    = $cldiariofinal->sql_query_file("",
                                                   "ed74_c_resultadoaprov,ed74_c_resultadofreq",
                                                   "",
                                                   $sWhere
                                                  );
          $result5 = $cldiariofinal->sql_record($sql);
          if ($cldiariofinal->numrows > 0) {
            db_fieldsmemory($result5,0);
            if ($ed74_c_resultadoaprov == "A" && $ed74_c_resultadofreq == "A") {
              $resultadofinal = "{$sLabelAprovado}";
              $res_final      = "A";
            } else {
              $resultadofinal = "{$sLabelReprovado}";
              $res_final      = "R";
            }
          } else {
            $resultadofinal = "&nbsp;";
            $sObs           = "&nbsp;";
            $res_final      = "";
          }
          if ($ed81_c_todoperiodo == "S") {
            $resultadofinal = "$sLabelAprovado";
            $res_final      = "A";
          }
          $sWhere               = "ed253_i_diario = $ed74_i_diario";
          $result_aprovconselho = $claprovconselho->sql_record($claprovconselho->sql_query_tipo_aprovacao("",
                                                                                                "ed253_i_codigo,
                                                                                                ed122_descricao",
                                                                                                "",
                                                                                                $sWhere
                                                                                               )
                                                              );
          if ($claprovconselho->numrows == 0) {

            $sql_rf   = "UPDATE diariofinal SET ed74_c_resultadofinal = '$res_final' WHERE ed74_i_codigo = $ed74_i_codigo";
            $query_rf = db_query($sql_rf);
          } else {

            db_fieldsmemory($result_aprovconselho, 0);
            $resultadofinal = "{$sLabelAprovado} ($ed122_descricao)";
            $res_final      = "A";
          }
       ?>
          <td align="center" class='aluno'>
          <?if(isset ($valor) && $valor == "" && $ed81_c_todoperiodo != "S" &&
               $ed59_c_freqglob <> 'F' && $ed60_c_parecer != "S") {

              $resultadofinal = '';

              $sql_rf   = "UPDATE diariofinal SET ed74_c_resultadofinal = '',ed74_c_resultadoaprov ='' WHERE ed74_i_codigo = $ed74_i_codigo";
              $query_rf = db_query($sql_rf);
            }else {
              $resultadofinal = $resultadofinal;
            }

            /**
             * Setamos e validamos a cor de acordo com o resultado final
             */
            $sCorResultadoFinal = 'green';

            if ($res_final=='R') {
              $sCorResultadoFinal = 'red';
            }

            /**
             * Caso o aluno possua amparo para todos os periodos, salvamos o resultado final normalmente, mas
             * apresentamos na tela como 'AMPARADO'
             */
            if ($ed81_c_todoperiodo == "S") {

              $sCorResultadoFinal = 'black';
              $resultadofinal     = "AMPARADO";
            }

            ?>
            <font color="<?=$sCorResultadoFinal?>"><?=$resultadofinal?></font>
          </td>
        <?if (trim($ed74_c_resultadofinal) != "") {?>
            <td align='center' class='aluno'><b>
              <a style="color:blue;text-decoration:none;"
                 href="javascript:js_observacoes(<?=$ed47_i_codigo?>,'<?=$ed47_v_nome?>',<?=$ed74_i_codigo?>);"
                 title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b>
            </td>
        <?} else {?>
            <td align='center' class='aluno'><b>
              <a style="color:blue;text-decoration:none;"
                 href="javascript:js_observacoes(<?=$ed47_i_codigo?>,'<?=$ed47_v_nome?>',<?=$ed74_i_codigo?>);"
                 title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b></td>
        <?}?>
        </tr>
      <?
        }
        if ($ed60_c_parecer == "S") {
          $ed37_c_tipo = $ed37_c_tipo_ant;
        }
      }
    }?>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<?
    if (trim(@$ed60_c_concluida) == "N") {
      $sql_r    = " SELECT DISTINCT max(ed09_i_sequencia) ";
      $sql_r   .= "   FROM diarioavaliacao ";
      $sql_r   .= "        inner join diario           on diario.ed95_i_codigo           = diarioavaliacao.ed72_i_diario ";
      $sql_r   .= "        inner join procavaliacao    on procavaliacao.ed41_i_codigo    = diarioavaliacao.ed72_i_procavaliacao ";
      $sql_r   .= "        inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
      $sql_r   .= "  WHERE diario.ed95_i_regencia = $regencia ";
      $sql_r   .= "    AND  ";
      $sql_r   .= "        (    diarioavaliacao.ed72_i_numfaltas is not null ";
      $sql_r   .= "          OR diarioavaliacao.ed72_i_valornota is not null ";
      $sql_r   .= "          OR diarioavaliacao.ed72_c_valorconceito != '' ";
      $sql_r   .= "          OR diarioavaliacao.ed72_t_parecer != '') ";
      $result_r = db_query($sql_r);
      $linhas   = pg_num_rows($result_r);
      db_fieldsmemory($result_r,0);
      if ($max == "") {
        db_inicio_transacao();
        $clregencia->ed59_c_ultatualiz = "SI";
        $clregencia->ed59_i_codigo     = $regencia;
        $clregencia->alterar($regencia);
        db_fim_transacao();
      } else {
        $result_p = $clperiodoavaliacao->sql_record($clperiodoavaliacao->sql_query_file("",
                                                                                        "ed09_c_abrev",
                                                                                        "",
                                                                                        "ed09_i_sequencia = $max"
                                                                                       )
                                                   );
        db_fieldsmemory($result_p,0);
        db_inicio_transacao();
        $clregencia->ed59_c_ultatualiz = $ed09_c_abrev;
        $clregencia->ed59_i_codigo     = $regencia;
        $clregencia->alterar($regencia);
        db_fim_transacao();
      }
    }
    ?>
    <script>
    function js_parecer(campo,codigo) {
      location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>"
                                            +"&codigo="+codigo
                                            +"&valor="+campo.value
                                            +"&iTrocaTurma=<?=$iTrocaTurma?>"
                                            +"&alterar";
    }

    function js_encerrar(regencia,turma) {

      js_OpenJanelaIframe('',
		                  'db_iframe_encerrar',
		                  'edu1_encerraraval001.php?regencia='+regencia,
		                  'Encerramento de Avaliações Turma '+turma,true,0,30,700,380
		                 );

    }

    function js_movimentos(matricula) {

      js_OpenJanelaIframe('',
		                  'db_iframe_movimentos',
		                  'edu1_matricula005.php?matricula='+matricula,
		                  'Movimentação da Matrícula',true
		                 );

    }

    function js_alteraresultado(regencia) {

      js_OpenJanelaIframe('',
		                  'db_iframe_alteraresultado',
		                  'edu1_aprovconselhoabas001.php?regencia='+regencia+"&iTrocaTurma=<?=$iTrocaTurma?>",
		                  'Alterar Resultado Final de Alunos',true,0,0,screen.availWidth-50,screen.availHeight
		                 );

    }

    function js_calculofreq(codresultado,codturma,codaluno,perctotal) {

      js_OpenJanelaIframe('',
		                  'db_iframe_calculofreq',
		                  'edu1_diariofinalcalcfreq001.php?codresultado='+codresultado+'&codturma='+codturma+
		                  '&codaluno='+codaluno+'&codaluno='+codaluno+'&perctotal='+perctotal,
		                  'Quadro Geral do Cálculo da Frequência',true,0,0,screen.availWidth-50,screen.availHeight
		                 );

    }

    function js_observacoes(codaluno,aluno,diario) {

	  js_OpenJanelaIframe('','db_iframe_obs','edu1_diariofinalobs001.php?ed93_i_diarioavaliacao='+diario+
			              '&aluno='+aluno+'&codaluno='+codaluno,'Observações',
			               true,0,0,screen.availWidth-50,screen.availHeight
			              );
    }
</script>
<?}?>

<?php

?>