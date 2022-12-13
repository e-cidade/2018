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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("fpdf151/pdfwebseller.php"));
require_once(modification("classes/db_calendario_classe.php"));
require_once(modification("classes/db_periodocalendario_classe.php"));
require_once(modification("classes/db_escoladiretor_classe.php"));
require_once(modification("classes/db_ensino_classe.php"));
require_once(modification("classes/db_edu_parametros_classe.php"));
require_once(modification("classes/db_aluno_classe.php"));
require_once(modification("classes/db_serie_classe.php"));
require_once(modification("classes/db_matricula_classe.php"));
require_once(modification("libs/db_utils.php"));

$oDaoEnsino            = new cl_ensino();
$oDaoEscolaDiretor     = new cl_escoladiretor();
$oDaoCalendario        = new cl_calendario();
$oDaoPeriodoCalendario = new cl_periodocalendario();
$oDaoEduParametros     = new cl_edu_parametros();
$oDaoAluno             = new cl_aluno();
$oDaoSerie             = new cl_serie();
$oDaoMatricula         = new cl_matricula();
$iResultado            = $iResultado;
$sSql                  = $oDaoCalendario->sql_query_file("", "ed52_i_ano as ano_calendario,ed52_c_descr as
                                                         descr_calendario", "", "ed52_i_codigo = $sCalendario"
                                                        );
$rsAno                 = $oDaoCalendario->sql_record($sSql);
$oDadosCalendario      = db_utils::fieldsmemory($rsAno, 0);
$sSql2                 = $oDaoEduParametros->sql_query("", "ed233_c_limitemov, ed233_c_database", "",
                                                       "ed233_i_escola = $iEscola"
                                                      );
$rsParametros          = $oDaoEduParametros->sql_record($sSql2);

$iDiaLimite = DBDate::getQuantidadeDiasMes($iMes, $oDadosCalendario->ano_calendario);
  if ($oDaoEduParametros->numrows > 0) {

  $oDadosEscola = db_utils::fieldsmemory($rsParametros, 0);
  if (!strstr($oDadosEscola->ed233_c_database, "/")) {
  ?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
         deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
         Valor atual do parâmetro: <?=trim($oDadosEscola->ed233_c_database) == "" ? "Não informado":
                                     $oDadosEscola->ed233_c_database
                                   ?><br><br></b>
      <input type='button' value='Fechar' onclick='window.close()'>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;

 }

 if (!strstr($oDadosEscola->ed233_c_limitemov, "/")) {

  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Parâmetro Dia/Mês Limite da Movimentação (Procedimentos->Parâmetros)<br>
         deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
         Valor atual do parâmetro Dia/Mês Limite da Movimentação: <?= trim($oDadosEscola->ed233_c_limitemov) == "" ?
                                                                    "Não informado":$oDadosEscola->ed233_c_limitemov
                                                                  ?><br>
      <input type='button' value='Fechar' onclick='window.close()'>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;

 }

 $aDataBase    = explode("/", $oDadosEscola->ed233_c_database);
 $iDiaDatabase = $aDataBase[0];
 $iMesDatabase = $aDataBase[1];
 if (@!checkdate($iMesDatabase, $iDiaDatabase, $oDadosCalendario->ano_calendario)) {

  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
         deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida.<br><br>
         Valor atual do parâmetro:     <?= $oDadosEscola->ed233_c_database?><br>
         Data Base para Cálculo Idade: <?=$iDiaDatabase."/".$iMesDatabase."/".
                                        $oDadosCalendario->ano_calendario
                                       ?> (Data Inválida)<br><br></b>
      <input type='button' value='Fechar' onclick='window.close()'>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;

 }

 $aLimiteMov    = explode("/",$oDadosEscola->ed233_c_limitemov);
 $iDiaLimiteMov = $aLimiteMov[0];
 $iMesLimiteMov = $aLimiteMov[1];
 if (@!checkdate($iMesLimiteMov, $iDiaLimiteMov, $oDadosCalendario->ano_calendario)) {

  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Parâmetro Dia/Mês Limite da Movimentação (Procedimentos->Parâmetros)<br>
         deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida.<br><br>
         Valor atual do parâmetro Dia/Mês Limite da Movimentação: <?= trim($oDadosEscola->ed233_c_limitemov) == ""?
                                                                    "Não informado" : $oDadosEscola->ed233_c_limitemov
                                                                  ?><br>
         Data Limite da Movimentação: <?= $iDiaLimiteMov."/".$iMesLimiteMov."/".
                                         $oDadosCalendario->ano_calendario
                                      ?> (Data Inválida)<br></b>
      <input type='button' value='Fechar' onclick='window.close()'>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;

 }

 $dDataBaseCalc  = $oDadosCalendario->ano_calendario."-".(strlen($iMesDatabase) == 1 ? "0".$iMesDatabase:$iMesDatabase).
                                                     "-".(strlen($iDiaDatabase) == 1 ? "0".$iDiaDatabase:$iDiaDatabase);
 $dDataLimiteMov = $oDadosCalendario->ano_calendario."-".(strlen($iMesLimiteMov) == 1 ? "0".
 $iMesLimiteMov:$iMesLimiteMov)."-".(strlen($iDiaLimiteMov) == 1 ? "0".$iDiaLimiteMov:$iDiaLimiteMov);

} else {

  $dDataBaseCalc  = $oDadosCalendario->ano_calendario."-12-31";
  $dDataLimiteMov = $oDadosCalendario->ano_calendario."-01-01";

}

if ($sDiretor!= "") {

  $aAssinatura = explode("-", $sDiretor);
  $z01_nome    = $aAssinatura[1];
  $sFuncao     = $aAssinatura[0].":";

} else {

  $z01_nome = "......................................................................................";
  $sFuncao  = "Emissor:";

}

if ($sModalidade == "1") {

  $iComecaIdade = 5;
  $iTerminaIdade = 16;

} else if($sModalidade == "3") {

  $iComecaIdade = 14;
  $iTerminaIdade = 25;
}

if ($iResultado == 1) {

  $sDescricao1 = "Aprovado";
  $sDescricao2 = "Reprovado";
} else {

  $sDescricao1 = "Novo";
  $sDescricao2 = "Repetente";
}

$sDescricao3 = "Sem Informação";

$dDataMatrFim       = $oDadosCalendario->ano_calendario."-".(strlen($iMes) == 1 ? "0".$iMes:$iMes)."-".$iDiaLimite;
$sCondicaoMatricula = " ed60_d_datamatricula <= '$dDataMatrFim'";
$sWhereMatriculado  = " ed57_i_escola=$iEscola and ed52_i_codigo = $sCalendario and ed11_i_ensino in ($iNivelEnsino)";
$sWhereMatriculado .= " and ed60_d_datamatricula <= '$dDataMatrFim' and ed221_c_origem = 'S' limit 1";
$sSqlMatriculado    = $oDaoAluno->sql_query_alunomatricula("", "*", "", $sWhereMatriculado);
$rsMatriculado      = $oDaoAluno->sql_record($sSqlMatriculado);
$iLinhasMatriculado = $oDaoAluno->numrows;
if ($iLinhasMatriculado == 0) {

  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
 <?
 exit;

}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1       = "Expansão de Matricula";
$head2       = "Mês: ".db_mes($iMes, 1);
$head3       = "Calendário: ".$oDadosCalendario->descr_calendario;
$head4       = "Data Base calculo da idade: ".db_formatar($dDataBaseCalc, 'd');
$head5       = "Nível de ensino:";
$head6       = '';
$aCodEnsinos = explode(",", $iNivelEnsino);
for ($iCont = 0; $iCont < count($aCodEnsinos); $iCont++) {

  $sSql3        = $oDaoEnsino->sql_query("", "ed10_c_descr as descrensino", "", " ed10_i_codigo = $aCodEnsinos[$iCont]");
  $rsResult3    = $oDaoEnsino->sql_record($sSql3);
  $oDadosEnsino = db_utils::fieldsmemory($rsResult3, 0);
  $head6       .= "->".$oDadosEnsino->descrensino."\n";

}

$sCamposSerie  = " distinct ed11_i_codigo,ed11_c_abrev,ed11_i_ensino,ed10_c_descr,ed11_i_sequencia" ;
$sWhereSerie   = " ed57_i_escola=$iEscola and ed52_i_codigo=$sCalendario and ed11_i_ensino in ($iNivelEnsino)" ;
$sWhereSerie  .= " and ed221_c_origem = 'S' and ed60_c_situacao != 'TROCA DE MODALIDADE'" ;
$sWhereSerie  .= " and ed60_d_datamatricula <= '$dDataMatrFim'" ;
$sOrderSerie   = " ed10_c_descr,ed11_i_sequencia" ;
$sSqlSerie     = $oDaoSerie->sql_query_relatorio("",$sCamposSerie, $sOrderSerie, $sWhereSerie) ;
$rsSerie       = $oDaoSerie->sql_record($sSqlSerie) ;
$iLinhasSerie  = $oDaoSerie->numrows ;
$oPdf->Addpage();
$iTroca        = 1;
$sCor          = "0";
$oPdf->setfillcolor(223);
$iPrimeiro     = "";
$oPdf->setfont('arial','',7);

///////////////////////////////////////TABELA 1 - Expansão Etapa/Sexo

$sCamposTabela1  = " ed221_i_serie as seriealuno, ed47_v_sexo,";
$sCamposTabela1 .= " case when ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null
                     then ".(isset($iResultado)&&$iResultado==2?"fc_edurfanterior(ed60_i_codigo)":
                    "case when ed60_c_concluida = 'S' then fc_edurfatual(ed60_i_codigo) else
                     fc_edurfanterior(ed60_i_codigo) end")."";
$sCamposTabela1 .= " when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA'";
$sCamposTabela1 .= " or ed60_c_situacao = 'TROCA DE MODALIDADE'";
$sCamposTabela1 .= "      ) and ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov'";
$sCamposTabela1 .= " then 'A' when (ed60_c_situacao = 'EVADIDO' or ed60_c_situacao = 'CANCELADO'";
$sCamposTabela1 .= " or ed60_c_situacao = 'FALECIDO')";
$sCamposTabela1 .= " and ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov'";
$sCamposTabela1 .= " then 'A' when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO')";
$sCamposTabela1 .= " and ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov'";
$sCamposTabela1 .= " then 'A' else 'X' end as resfinal";
$sWhereTabela1   = " ed57_i_escola = $iEscola";
$sWhereTabela1  .= " AND ed52_i_codigo = $sCalendario";
$sWhereTabela1  .= " AND ed221_c_origem = 'S'";
$sWhereTabela1  .= " AND ed60_d_datamatricula <= '$dDataMatrFim'";
$sWhereTabela1  .= " AND ed11_i_ensino in ($iNivelEnsino)";
$sOrderTabela1   = " ed10_c_descr,ed11_i_sequencia,ed47_v_sexo,resfinal";
$sSqlTabela1     = $oDaoMatricula->sql_query_expansaomat("", $sCamposTabela1, $sOrderTabela1, $sWhereTabela1);
$rsTabela1       = $oDaoMatricula->sql_record($sSqlTabela1);
$iLinhasTabela1  = $oDaoMatricula->numrows;

//passo os dados para o pdf
$iPosyIniTabela1 = $oPdf->Gety();
$oPdf->cell(190, 4, "Expansão de Matricula por Etapa/Sexo", 1, 1, "C", $sCor);
$iPosZeroY       = $oPdf->Gety();
$iPosZeroX       = $oPdf->Getx();
$oPdf->cell(20, 4, "Sexo", "LRT", 2, "R", $sCor);
$oPdf->cell(20, 4, "Etapa", "LRB", 0, "L", $sCor);
$oPdf->line(10, $iPosZeroY, 30, $iPosZeroY+8);
$oPdf->setXY(30, $iPosZeroY);
$iPosY           = $oPdf->Gety();
$iPosX           = $oPdf->Getx();
$oPdf->cell(69, 4, "Masculino", 1, 0, "C", $sCor);
$oPdf->cell(69, 4, "Feminino", 1, 0, "C", $sCor);
$oPdf->cell(32, 8, "Total", 1, 0, "C", $sCor);
$oPdf->SetXY($iPosX, $iPosY+4);
$oPdf->cell(23, 4, $sDescricao1, 1, 0, "C", $sCor);
$oPdf->cell(23, 4, $sDescricao2, 1, 0, "C", $sCor);
$oPdf->cell(23, 4, $sDescricao3, 1, 0, "C", $sCor);
$oPdf->cell(23, 4, $sDescricao1, 1, 0, "C", $sCor);
$oPdf->cell(23, 4, $sDescricao2, 1, 0, "C", $sCor);
$oPdf->cell(23, 4, $sDescricao3, 1, 0, "C", $sCor);
$oPdf->SetXY(10, $iPosY+8);
$iFirst = "";

for ($iCont = 0; $iCont < 7; $iCont++) {
  $aVet[$iCont] = 0;
}

for ($iContSerie = 0; $iContSerie < $iLinhasSerie; $iContSerie++) {

  $oDadosSerie = db_utils::fieldsmemory($rsSerie, $iContSerie);
  $oPdf->cell(20, 4, $oDadosSerie->ed11_c_abrev, 1, 0, "C", $sCor);
  $iMascAprov  = 0;
  $iMascReprov = 0;
  $iMascSi     = 0;
  $iFemAprov   = 0;
  $iFemReprov  = 0;
  $iFemSi      = 0;
  $iTotalSerie = 0;
  for ($iContTab = 0; $iContTab < $iLinhasTabela1; $iContTab++) {

    $oDadosMatricula = db_utils::fieldsmemory($rsTabela1,$iContTab);
    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A"
        && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo) {

      $iMascAprov  = $iMascAprov+1;
      $aVet[0]     = $aVet[0]+1;
      $iTotalSerie = $iTotalSerie+1;

    }

    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R"
        && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo) {

      $iMascReprov = $iMascReprov+1;
      $aVet[1]     = $aVet[1]+1;
      $iTotalSerie = $iTotalSerie+1;

    }

    if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S"
        && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo) {

      $iMascSi     = $iMascSi+1;
      $aVet[2]     = $aVet[2]+1;
      $iTotalSerie = $iTotalSerie+1;

    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A"
        && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo) {

      $iFemAprov   = $iFemAprov+1;
      $aVet[3]     = $aVet[3]+1;
      $iTotalSerie = $iTotalSerie+1;
    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R"
        && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo) {

      $iFemReprov  = $iFemReprov+1;
      $aVet[4]     = $aVet[4]+1;
      $iTotalSerie = $iTotalSerie+1;

    }

    if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S"
        && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo) {

      $iFemSi      = $iFemSi+1;
      $aVet[5]     = $aVet[5]+1;
      $iTotalSerie = $iTotalSerie+1;

    }

  }

  $oPdf->cell(23, 4, $iMascAprov == 0 ? '' : $iMascAprov, 1, 0, "C", 0);
  $oPdf->cell(23, 4, $iMascReprov == 0 ? '' : $iMascReprov, 1, 0, "C", 0);
  $oPdf->cell(23, 4, $iMascSi == 0 ? '' : $iMascSi, 1, 0, "C", 0);
  $oPdf->cell(23, 4, $iFemAprov == 0 ? '' : $iFemAprov, 1, 0, "C", 0);
  $oPdf->cell(23, 4, $iFemReprov == 0 ? '' : $iFemReprov, 1, 0, "C", 0);
  $oPdf->cell(23, 4, $iFemSi == 0 ? '' : $iFemSi, 1, 0, "C", 0);
  $oPdf->cell(32, 4, "$iTotalSerie", 1, 1, "C", $sCor);
  $aVet[6] = $aVet[6]+$iTotalSerie;

}

$oPdf->cell(20, 4, "Total", 1, 0, "C", $sCor);
for ($iCont = 0; $iCont < 7; $iCont++) {

  if ($iCont == 6) {

    $iTamanho = 32;
    $iQuebra  = 1;

  } else {

    $iTamanho = 23;
    $iQuebra  = 0;
  }

  $oPdf->cell($iTamanho, 4, "$aVet[$iCont]", 1, $iQuebra, "C", $sCor);

}

$oPdf->cell(190, 4, "", 0, 1, "C", 0);

///////////////////////////////////////TABELA 2 - Expansão Idade/Sexo

$sCamposTabela2  = " coalesce(fc_idade(ed47_d_nasc,'$dDataBaseCalc'::date),0) as idadealuno,";
$sCamposTabela2 .= " ed47_v_sexo, case when ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null
                     then ".(isset($iResultado)&&$iResultado==2?"fc_edurfanterior(ed60_i_codigo)":"case
                     when ed60_c_concluida = 'S' then fc_edurfatual(ed60_i_codigo) else
                     fc_edurfanterior(ed60_i_codigo) end")." ";
$sCamposTabela2 .= " when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA'";
$sCamposTabela2 .= " or ed60_c_situacao = 'TROCA DE MODALIDADE') and ed60_d_datasaida > ";
$sCamposTabela2 .= " '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov'";
$sCamposTabela2 .= " then 'A' when (ed60_c_situacao = 'EVADIDO' or ed60_c_situacao = 'CANCELADO' or";
$sCamposTabela2 .= "  ed60_c_situacao = 'FALECIDO')";
$sCamposTabela2 .= " and ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov' then 'A'";
$sCamposTabela2 .= " when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO') and ed60_d_datasaida >";
$sCamposTabela2 .= " '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov' then 'A' else 'X' end as resfinal";
$sWhereTabela2   = " ed57_i_escola = $iEscola AND ed52_i_codigo = $sCalendario AND ed221_c_origem = 'S'";
$sWhereTabela2  .= " AND ed60_d_datamatricula <= '$dDataMatrFim' AND ed11_i_ensino in ($iNivelEnsino)";
$sOrderTabela2   = " idadealuno,ed47_v_sexo,resfinal";
$sSqlTabela2     = $oDaoMatricula->sql_query_expansaomat("", $sCamposTabela2, $sOrderTabela2, $sWhereTabela2);
$rsTabela2       = $oDaoMatricula->sql_record($sSqlTabela2);
$iLinhasTabela2  = $oDaoMatricula->numrows;
$oPdf->cell(190, 4, "Expansão de Matricula por Idade/Sexo", 1, 1, "C", $sCor);
$iPosZeroY       = $oPdf->Gety();
$iPosZeroX       = $oPdf->Getx();
$oPdf->cell(20, 4, "Sexo", "LRT", 2, "R", $sCor);
$oPdf->cell(20, 4, "Idade", "LRB", 0, "L", $sCor);
$oPdf->line(10, $iPosZeroY, 30, $iPosZeroY+8);
$oPdf->setXY(30, $iPosZeroY);
$iPosY           = $oPdf->Gety();
$iPosX           = $oPdf->Getx();
$oPdf->cell(69, 4, "Masculino", 1, 0, "C", $sCor);
$oPdf->cell(69, 4, "Feminino", 1, 0, "C", $sCor);
$oPdf->cell(32, 8, "Total", 1, 0, "C", $sCor);
$oPdf->SetXY($iPosX, $iPosY+4);
$oPdf->cell(23, 4, "$sDescricao1", 1, 0, "C", $sCor);
$oPdf->cell(23, 4, "$sDescricao2", 1, 0, "C", $sCor);
$oPdf->cell(23, 4, "$sDescricao3", 1, 0, "C", $sCor);
$oPdf->cell(23, 4, "$sDescricao1", 1, 0, "C", $sCor);
$oPdf->cell(23, 4, "$sDescricao2", 1, 0, "C", $sCor);
$oPdf->cell(23, 4, "$sDescricao3", 1, 0, "C", $sCor);

for ($iCont = 0; $iCont < 7; $iCont++) {
   $aVet2[$iCont] = 0;
}

$iPosY = $iPosY+8;

for ($iIdade = $iComecaIdade; $iIdade < $iTerminaIdade; $iIdade++) {

  $oPdf->SetXY(10, $iPosY);
  if ($sModalidade == "1") {

    if ($iIdade == 5) {
      $oPdf->cell(20, 4, "-6", 1, 0, "C", $sCor);
    } else if ($iIdade == 15) {
      $oPdf->cell(20, 4, "+14", 1, 0, "C", $sCor);
    } else {
      $oPdf->cell(20, 4, $iIdade, 1, 0, "C", $sCor);
    }

  } else if ($sModalidade == "3") {

    if ($iIdade == 14) {
      $oPdf->cell(20, 4, "-15", 1, 0, "C", $sCor);
    } else if ($iIdade == 22) {
      $oPdf->cell(20, 4, "22/35", 1, 0, "C", $sCor);
    } else if ($iIdade == 23) {
      $oPdf->cell(20, 4, "36/50", 1, 0, "C", $sCor);
    } else if ($iIdade == 24) {
      $oPdf->cell(20, 4, "+50", 1, 0, "C", $sCor);
    } else {
      $oPdf->cell(20, 4, "$iIdade", 1, 0, "C", $sCor);
    }

  }

$iMascAprov  = 0;
$iMascReprov = 0;
$iMascSi     = 0;
$iFemAprov   = 0;
$iFemReprov  = 0;
$iFemSi      = 0;
$iTotalSerie = 0;
for ($iContTab2 = 0; $iContTab2 < $iLinhasTabela2; $iContTab2++) {

  $oDadosMatricula = db_utils::fieldsmemory($rsTabela2, $iContTab2);
  if ($sModalidade == "1") {

    if ($iIdade == 5) {

      if ($oDadosMatricula->idadealuno < 6) {

        if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

          $iMascAprov  = $iMascAprov+1;
          $aVet2[0]    = $aVet2[0]+1;
          $iTotalSerie = $iTotalSerie+1;

        }

        if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

          $iMascReprov = $iMascReprov+1;
          $aVet2[1]    = $aVet2[1]+1;
          $iTotalSerie = $iTotalSerie+1;

        }

        if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

          $iMascSi     = $iMascSi+1;
          $aVet2[2]    = $aVet2[2]+1;
          $iTotalSerie = $iTotalSerie+1;

        }

        if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

          $iFemAprov   = $iFemAprov+1;
          $aVet2[3]    = $aVet2[3]+1;
          $iTotalSerie = $iTotalSerie+1;

        }

        if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

          $iFemReprov  = $iFemReprov+1;
          $aVet2[4]    = $aVet2[4]+1;
          $iTotalSerie = $iTotalSerie+1;

        }

        if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

          $iFemSi      = $iFemSi+1;
          $aVet2[5]    = $aVet2[5]+1;
          $iTotalSerie = $iTotalSerie+1;

        }

      }

    } else if ($iIdade == 15) {

       if ($oDadosMatricula->idadealuno > 14) {

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

           $iMascAprov  = $iMascAprov+1;
           $aVet2[0]    = $aVet2[0]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

           $iMascReprov = $iMascReprov+1;
           $aVet2[1]    = $aVet2[1]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

           $iMascSi     = $iMascSi+1;
           $aVet2[2]    = $aVet2[2]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

           $iFemAprov   = $iFemAprov+1;
           $aVet2[3]    = $aVet2[3]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

           $iFemReprov  = $iFemReprov+1;
           $aVet2[4]    = $aVet2[4]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

           $iFemSi      = $iFemSi+1;
           $aVet2[5]    = $aVet2[5]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

       }

    } else {

       if ($oDadosMatricula->idadealuno == $iIdade) {

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

           $iMascAprov  = $iMascAprov+1;
           $aVet2[0]    = $aVet2[0]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

           $iMascReprov = $iMascReprov+1;
           $aVet2[1]    = $aVet2[1]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

           $iMascSi     = $iMascSi+1;
           $aVet2[2]    = $aVet2[2]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

           $iFemAprov   = $iFemAprov+1;
           $aVet2[3]    = $aVet2[3]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

           $iFemReprov   = $iFemReprov+1;
           $aVet2[4]     = $aVet2[4]+1;
           $iTotalSerie  = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

           $iFemSi      = $iFemSi+1;
           $aVet2[5]    = $aVet2[5]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

       }

    }

  } else if ($sModalidade == "3") {

     if ($iIdade == 14) {

       if ($oDadosMatricula->idadealuno < 15) {

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

           $iMascAprov  = $iMascAprov+1;
           $aVet2[0]    = $aVet2[0]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

           $iMascReprov = $iMascReprov+1;
           $aVet2[1]    = $aVet2[1]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

           $iMascSi     = $iMascSi+1;
           $aVet2[2]    = $aVet2[2]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

           $iFemAprov   = $iFemAprov+1;
           $aVet2[3]    = $aVet2[3]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

           $iFemReprov  = $iFemReprov+1;
           $aVet2[4]    = $aVet2[4]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

         if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

           $iFemSi      = $iFemSi+1;
           $aVet2[5]    = $aVet2[5]+1;
           $iTotalSerie = $iTotalSerie+1;

         }

        }

      } else if ($iIdade == 22) {

        if ($oDadosMatricula->idadealuno > 21 && $oDadosMatricula->idadealuno < 36) {

          if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

            $iMascAprov  = $iMascAprov+1;
            $aVet2[0]    = $aVet2[0]+1;
            $iTotalSerie = $iTotalSerie+1;

          }

          if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

            $iMascReprov = $iMascReprov+1;
            $aVet2[1]    = $aVet2[1]+1;
            $iTotalSerie = $iTotalSerie+1;

          }

          if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

            $iMascSi     = $iMascSi+1;
            $aVet2[2]    = $aVet2[2]+1;
            $iTotalSerie = $iTotalSerie+1;

          }

          if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

            $iFemAprov   = $iFemAprov+1;
            $aVet2[3]    = $aVet2[3]+1;
            $iTotalSerie = $iTotalSerie+1;

          }

          if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

            $iFemReprov  = $iFemReprov+1;
            $aVet2[4]    = $aVet2[4]+1;
            $iTotalSerie = $iTotalSerie+1;

          }

          if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

            $iFemSi      = $iFemSi+1;
            $aVet2[5]    = $aVet2[5]+1;
            $iTotalSerie = $iTotalSerie+1;

          }

         }

      } else if ($iIdade == 23) {

         if ($oDadosMatricula->idadealuno > 35 && $oDadosMatricula->idadealuno < 51) {

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

             $iMascAprov  = $iMascAprov+1;
             $aVet2[0]    = $aVet2[0]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

             $iMascReprov = $iMascReprov+1;
             $aVet2[1]    = $aVet2[1]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

             $iMascSi     = $iMascSi+1;
             $aVet2[2]    = $aVet2[2]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

             $iFemAprov   = $iFemAprov+1;
             $aVet2[3]    = $aVet2[3]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

             $iFemReprov  = $iFemReprov+1;
             $aVet2[4]    = $aVet2[4]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

             $iFemSi      = $iFemSi+1;
             $aVet2[5]    = $aVet2[5]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

         }

      } else if ($iIdade == 24) {

         if ($oDadosMatricula->idadealuno > 50) {

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

             $iMascAprov  = $iMascAprov+1;
             $aVet2[0]    = $aVet2[0]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

             $iMascReprov = $iMascReprov+1;
             $aVet2[1]    = $aVet2[1]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

             $iMascSi     = $iMascSi+1;
             $aVet2[2]    = $aVet2[2]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

             $iFemAprov   = $iFemAprov+1;
             $aVet2[3]    = $aVet2[3]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

             $iFemReprov  = $iFemReprov+1;
             $aVet2[4]    = $aVet2[4]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

             $iFemSi      = $iFemSi+1;
             $aVet2[5]    = $aVet2[5]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

         }

      } else {

         if ($oDadosMatricula->idadealuno == $iIdade) {

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "A") {

             $iMascAprov  = $iMascAprov+1;
             $aVet2[0]    = $aVet2[0]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "R") {

             $iMascReprov = $iMascReprov+1;
             $aVet2[1]    = $aVet2[1]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "M" && $oDadosMatricula->resfinal == "S") {

             $iMascSi     = $iMascSi+1;
             $aVet2[2]    = $aVet2[2]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "A") {

             $iFemAprov   = $iFemAprov+1;
             $aVet2[3]    = $aVet2[3]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "R") {

             $iFemReprov  = $iFemReprov+1;
             $aVet2[4]    = $aVet2[4]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

           if ($oDadosMatricula->ed47_v_sexo == "F" && $oDadosMatricula->resfinal == "S") {

             $iFemSi      = $iFemSi+1;
             $aVet2[5]    = $aVet2[5]+1;
             $iTotalSerie = $iTotalSerie+1;

           }

        }

     }

   }

 }

 $oPdf->cell(23, 4, $iMascAprov == 0 ? '' : $iMascAprov, 1, 0, "C", 0);
 $oPdf->cell(23, 4, $iMascReprov == 0 ? '' : $iMascReprov, 1, 0, "C", 0);
 $oPdf->cell(23, 4, $iMascSi == 0 ? '' : $iMascSi, 1, 0, "C", 0);
 $oPdf->cell(23, 4, $iFemAprov == 0 ? '' : $iFemAprov, 1, 0, "C", 0);
 $oPdf->cell(23, 4, $iFemReprov == 0 ? '' : $iFemReprov, 1, 0, "C", 0);
 $oPdf->cell(23, 4, $iFemSi == 0 ? '' : $iFemSi, 1, 0, "C", 0);
 $oPdf->cell(32, 4, "$iTotalSerie", 1, 1, "C", $sCor);
 $aVet2[6] = $aVet2[6]+ $iTotalSerie;
 $iPosY += 4;
}

$oPdf->SetXY(10, $iPosY);
$oPdf->cell(20, 4, "Total", 1, 0, "C", $sCor);
for ($iCont = 0; $iCont < 7; $iCont++){

  if ($iCont == 6){

    $iTamanho = 32;
    $iQuebra  = 1;

  } else {

    $iTamanho = 23;
    $iQuebra  = 0;

  }

  $oPdf->cell($iTamanho, 4, "$aVet2[$iCont]", 1, $iQuebra, "C", $sCor);
}

$oPdf->cell(190, 4, "", 0, 1, "C", 0);

///////////////////////////////////////TABELA 3 - Expansão Etapa/Idade

$sCamposTabela3  = " coalesce(fc_idade(ed47_d_nasc,'$dDataBaseCalc'::date),0) as idadealuno,";
$sCamposTabela3 .= " ed221_i_serie as seriealuno, case";
$sCamposTabela3 .= " when ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null then 'M1'";
$sCamposTabela3 .= " when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA'";
$sCamposTabela3 .= " or ed60_c_situacao = 'TROCA DE MODALIDADE') and ed60_d_datasaida > '$dDataMatrFim'";
$sCamposTabela3 .= " and ed60_d_datasaida > '$dDataLimiteMov' then 'M1'";
$sCamposTabela3 .= " when (ed60_c_situacao = 'EVADIDO' or ed60_c_situacao = 'CANCELADO' or ed60_c_situacao =";
$sCamposTabela3 .= " 'FALECIDO') and ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov'";
$sCamposTabela3 .= " then 'M1' when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO')";
$sCamposTabela3 .= " and ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov' then 'M1'";
$sCamposTabela3 .= " else '' end as situacao";
$sWhereTabela3   = " ed57_i_escola = $iEscola AND ed52_i_codigo = $sCalendario AND ed221_c_origem = 'S'";
$sWhereTabela3  .= " AND ed60_d_datamatricula <= '$dDataMatrFim' AND ed11_i_ensino in ($iNivelEnsino)";
$sOrderTabela3   = " ed10_c_descr,ed11_i_sequencia,idadealuno";
$sSqlTabela3     = $oDaoMatricula->sql_query_expansaomat("", $sCamposTabela3, $sOrderTabela3, $sWhereTabela3);
$rsTabela3       = $oDaoMatricula->sql_record($sSqlTabela3);
$iLinhasTabela3  = $oDaoMatricula->numrows;
$iPosY= $oPdf->Gety();
$oPdf->cell(190,4,"Expansão de Matrícula por Etapa/idade",1,1,"C",$sCor);
$iAltIni = $oPdf->getY();
$oPdf->cell(15,3,"Idade","LRT",2,"R",$sCor);
$oPdf->cell(15,3,"Etapa","LRB",0,"L",$sCor);
$oPdf->line(10,$iAltIni,25,$iAltIni+6);
$oPdf->setXY(25,$iAltIni);
for ($iIdade = $iComecaIdade; $iIdade < $iTerminaIdade; $iIdade++) {

  if ($sModalidade == "1") {

    if ($iIdade == 5) {
      $oPdf->cell(15, 6, "-6", 1, 0, "C", $sCor);
    } else if($iIdade == 15){
      $oPdf->cell(15, 6, "+14", 1, 0, "C", $sCor);
    } else {
      $oPdf->cell(15, 6, $iIdade, 1, 0, "C", $sCor);
    }

  } else if ($sModalidade == "3") {

    if ($iIdade == 14) {
      $oPdf->cell(15,6,"-15",1,0,"C",$sCor);
    } else if ($iIdade == 22) {
      $oPdf->cell(15, 6, "22/35", 1, 0, "C", $sCor);
    } else if($iIdade == 23) {
      $oPdf->cell(15, 6, "36/50", 1, 0, "C", $sCor);
    } else if ($iIdade == 24) {
      $oPdf->cell(15, 6, "+50", 1, 0, "C", $sCor);
    } else {
      $oPdf->cell(15, 6, "$iIdade", 1, 0, "C", $sCor);
    }

  }

}

$oPdf->cell(10, 6, "Total", 1, 1, "C", $sCor);
for ($iCont = 0; $iCont < 12; $iCont++) {
  $aVet[$iCont] = 0;
}

$iFirst = "";
for ($iContTab3 = 0; $iContTab3 < $iLinhasSerie; $iContTab3++) {

  $oDadosSerie = db_utils::fieldsmemory($rsSerie, $iContTab3);
  $oPdf->cell(15, 4,"$oDadosSerie->ed11_c_abrev", 1, 0, "C", $sCor);
  $iLinha      = 0;
  $iVCont      = 1;
  $iQuantidade = 0;
  for ($iIdade = $iComecaIdade; $iIdade < $iTerminaIdade; $iIdade++) {

    $iQuantidade = 0;
    for ($iCont = 0; $iCont < $iLinhasTabela3; $iCont++) {

      $oDadosMatricula = db_utils::fieldsmemory($rsTabela3, $iCont);
      if ($sModalidade == "1") {

        if ($iIdade == 5) {

          if ($oDadosMatricula->idadealuno < 6 && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo
              && $oDadosMatricula->situacao != "") {

            $iQuantidade += 1;
            $iLinha       = $iLinha+1;

          }

        } else if ($iIdade == 15) {

          if ($oDadosMatricula->idadealuno > 14 && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo
              && $oDadosMatricula->situacao != "") {

            $iQuantidade += 1;
            $iLinha       = $iLinha+1;

          }

        } else {

          if ($oDadosMatricula->idadealuno == $iIdade && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo
              && $oDadosMatricula->situacao != "") {

            $iQuantidade += 1;
            $iLinha       = $iLinha+1;

          }

        }

        } else if($sModalidade == "3") {

          if ($iIdade == 14) {

            if ($oDadosMatricula->idadealuno < 15 && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo
                && $oDadosMatricula->situacao != "") {

              $iQuantidade += 1;
              $iLinha       = $iLinha+1;

            }

          } else if($iIdade == 22) {

            if ($oDadosMatricula->idadealuno > 21 && $oDadosMatricula->idadealuno < 36
               && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo && $oDadosMatricula->situacao != "") {

              $iQuantidade += 1;
              $iLinha       = $iLinha+1;

            }

          } else if($iIdade == 23) {

           if ($oDadosMatricula->idadealuno > 35 && $oDadosMatricula->idadealuno < 51 &&
               $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo && $oDadosMatricula->situacao != "") {

             $iQuantidade += 1;
             $iLinha       = $iLinha+1;

           }

          } else if($iIdade == 24) {

           if ($oDadosMatricula->idadealuno > 50 && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo
               && $oDadosMatricula->situacao != "") {

             $iQuantidade += 1;
             $iLinha       = $iLinha+1;

           }

          } else {

            if ($oDadosMatricula->idadealuno == $iIdade && $oDadosMatricula->seriealuno == $oDadosSerie->ed11_i_codigo
                && $oDadosMatricula->situacao != "") {

              $iQuantidade += 1;
              $iLinha       = $iLinha+1;

            }

          }

      }
   }

  $aVet[$iVCont] = $aVet[$iVCont]+$iQuantidade;
  $iVCont        = $iVCont+1;
  if ($iIdade == ($iTerminaIdade-1)) {

    $oPdf->cell(15, 4, $iQuantidade == 0 ? '' : $iQuantidade, 1, 0, "C", $sCor);
    $oPdf->cell(10, 4, "$iLinha", 1, 1, "C", $sCor);

  } else {
    $oPdf->cell(15, 4, $iQuantidade == 0 ? '' : $iQuantidade, 1, 0, "C", $sCor);
  }

 }

}

$oPdf->cell(15, 4, "Total", 1, 0, "C", $sCor);
$iTotal = 0;

for ($iCont = 1; $iCont < 12; $iCont++) {

   $oPdf->cell(15, 4, "$aVet[$iCont]", 1, 0, "C", $sCor);
   $iTotal = $iTotal+$aVet[$iCont];

}

$oPdf->cell(10, 4, "$iTotal", 1, 1, "C", $sCor);

$oPdf->cell(180, 8, "", 0, 1, "C", $sCor);
$oPdf->cell(90, 4, $sFuncao." ".$z01_nome, 0, 0, "L", $sCor);
$oPdf->cell(90, 4, "Data: ..........................................................", 0, 1, "L", $sCor);
$oPdf->cell(90, 4,"Recebimento: ......................................................................................",0,0,"L",$sCor);
$oPdf->cell(90, 4, "Data: ..........................................................", 0, 1, "L", $sCor);

////Listagem dos alunos
if ($sImprimeLista == "yes") {

  $oPdf->Addpage();
  $oPdf->setfillcolor(223);
  $sCamposLista   = " ed47_i_codigo, ed47_v_nome, ed47_v_sexo, ed47_d_nasc,";
  $sCamposLista  .= " coalesce(fc_idade(ed47_d_nasc,'$dDataBaseCalc'::date),0) as idadealuno,";
  $sCamposLista  .= " ed57_c_descr||'/'||ed11_c_descr as ed11_c_descr, ed60_d_datamatricula,";
  $sCamposLista  .= " case when ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null then 'M1'";
  $sCamposLista  .= " when (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA' or";
  $sCamposLista  .= " ed60_c_situacao = 'TROCA DE MODALIDADE') and ed60_d_datasaida > '$dDataMatrFim'";
  $sCamposLista  .= " and ed60_d_datasaida > '$dDataLimiteMov' then 'M1'";
  $sCamposLista  .= " when (ed60_c_situacao = 'EVADIDO' or ed60_c_situacao = 'CANCELADO' or";
  $sCamposLista  .= " ed60_c_situacao = 'FALECIDO') and ed60_d_datasaida > '$dDataMatrFim' and";
  $sCamposLista  .= " ed60_d_datasaida > '$dDataLimiteMov' then 'M1'";
  $sCamposLista  .= " when (ed60_c_situacao = 'AVANÇADO' or ed60_c_situacao = 'CLASSIFICADO') and";
  $sCamposLista  .= " ed60_d_datasaida > '$dDataMatrFim' and ed60_d_datasaida > '$dDataLimiteMov' then 'M1'";
  $sCamposLista  .= " else '' end as situacao";
  $sWhereLista    = " ed57_i_escola = $iEscola AND ed52_i_codigo = $sCalendario";
  $sWhereLista   .= " AND ed221_c_origem = 'S' AND ed60_d_datamatricula <= '$dDataMatrFim'";
  $sWhereLista   .= " AND ed11_i_ensino in ($iNivelEnsino)";
  $sOrderLista    = " idadealuno,ed47_d_nasc,ed47_v_nome";
  $sSqlLista      = $oDaoMatricula->sql_query_expansaomat("", $sCamposLista, $sOrderLista, $sWhereLista);
  $rsLista        = $oDaoMatricula->sql_record($sSqlLista);
  $iLinhasLista   = $oDaoMatricula->numrows;
  $iPrimeiro      = "";
  $iContador      = 0;
  $iContadorgeral = 0;
  for ($iContLista = 0; $iContLista < $iLinhasLista; $iContLista++) {

     $oDadosMatricula = db_utils::fieldsmemory($rsLista, $iContLista);
     if ($iPrimeiro != $oDadosMatricula->idadealuno) {

       $iPrimeiro = $oDadosMatricula->idadealuno;
       if ($iContLista > 0) {

         $oPdf->setfont('arial', '', 7);
         $oPdf->cell(190, 4, "Subtotal de alunos: $iContador", 0, 1, "R", 0);

       }

       $oPdf->setfont('arial', 'b', 10);
       $oPdf->cell(190, 4, "Idade: $oDadosMatricula->idadealuno", "B", 1, "L", 0);
       $oPdf->setfont('arial', 'b', 7);
       $oPdf->cell(10, 4, "Seq", "B", 0, "C", 0);
       $oPdf->cell(10, 4, "Idade", "B", 0, "C", 0);
       $oPdf->cell(25, 4, "Nascimento", "B", 0, "C", 0);
       $oPdf->cell(15, 4, "Codigo", "B", 0, "C", 0);
       $oPdf->cell(70, 4, "Nome", "B", 0, "L", 0);
       $oPdf->cell(10, 4, "Sexo", "B", 0, "C", 0);
       $oPdf->cell(30, 4, "Turma/Etapa", "B", 0, "C", 0);
       $oPdf->cell(20, 4, "Data Matrícula", "B", 1, "C", 0);
       $iContador = 0;

     }

     if ($oDadosMatricula->situacao != "") {

       $iContador++;
       $iContadorgeral++;
       $oPdf->setfont('arial', '', 7);
       $oPdf->cell(10, 4, $iContador, 0, 0, "C", 0);
       $oPdf->cell(10, 4, $oDadosMatricula->idadealuno, 0, 0, "C", 0);
       $oPdf->cell(25, 4, trim($oDadosMatricula->ed47_d_nasc) == "" ? "Nao Informado":db_formatar
                   ($oDadosMatricula->ed47_d_nasc, 'd'), 0, 0, "C", 0
                  );
       $oPdf->cell(15, 4, $oDadosMatricula->ed47_i_codigo, 0, 0, "C", 0);
       $oPdf->cell(70, 4, $oDadosMatricula->ed47_v_nome, 0, 0, "L", 0);
       $oPdf->cell(10, 4, $oDadosMatricula->ed47_v_sexo, 0, 0, "C", 0);
       $oPdf->cell(30, 4, $oDadosMatricula->ed11_c_descr, 0, 0, "C", 0);
       $oPdf->cell(20, 4, db_formatar($oDadosMatricula->ed60_d_datamatricula, 'd'), 0, 1, "C", 0);

     }

  }

  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(190, 4, "Subtotal de alunos: $iContador", 0, 1, "R", 0);
  $oPdf->setfont('arial', 'b', 9);
  $oPdf->cell(190, 8, "", 0, 1,"L", 0);
  $oPdf->cell(190, 4, "Total de alunos: $iContadorgeral", 0, 1, "L", 0);

}
$oPdf->Output();
?>