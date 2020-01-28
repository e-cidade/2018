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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_utils.php");
require_once("classes/db_cfpess_classe.php");

$oDaoCfpess = new cl_cfpess;

/**
 * Tipo de relatório comprovante de rendimento
 * Retorna false caso der erro na consulta
 */
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('comprovanterendimentos', db_anofolha(), db_mesfolha());
if(!$iTipoRelatorio) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de impressão invalido, verifique parametros.');
}

validaUsuarioLogado();

$oPost  = db_utils::postMemory($_POST);

$matric          = $oPost->iMatric;
$anobase         = $oPost->anobase;
$instituicao     = $oPost->iInstit;
$anofolha        = db_anofolha();
$mesfolha        = db_mesfolha();
$tipo            = 'm';
$resp            = '';
$ordem           = 'a';

  $sSqlDbConfig  = " select ender,                                              ";
  $sSqlDbConfig .= "        cgc,                                                ";
  $sSqlDbConfig .= "        nomeinst,                                           ";
  $sSqlDbConfig .= "        munic,                                              ";
  $sSqlDbConfig .= "        db21_codcli as codcli,                              ";
  $sSqlDbConfig .= "        telef                                               ";
  $sSqlDbConfig .= "   from db_config where codigo = $instituicao               ";

$rsSqlDbConfig    = db_query($sSqlDbConfig);
$iNumRowsDbConfig = pg_num_rows($rsSqlDbConfig);
if ($iNumRowsDbConfig > 0) {

	$oDbConfig  = db_utils::fieldsMemory($rsSqlDbConfig, 0);
	$prefeitura = db_translate($oDbConfig->nomeinst);
	$enderpref  = db_translate($oDbConfig->ender);
	$municpref  = db_translate($oDbConfig->munic);
	$telefpref  = $oDbConfig->telef;
	$cgcpref    = $oDbConfig->cgc;
}

$sWhere     = " where rhdirfgeracao.rh95_ano = {$anobase} ";
switch ($tipo) {

  /**
   * Filtro Matricula
   */
  case 'm':

      if (isset($matric) && !empty($matric)) {

        $sMatriculas = implode("', '", explode(",", $matric));
        $sWhere     .= " and rh99_regist in ('{$sMatriculas}') ";
    }
    break;
}

$sSqlRendimento  = " select rh96_numcgm,                                                                                                                                                    \n";
$sSqlRendimento .= "        x.rh96_sequencial,                                                                                                                                              \n";
$sSqlRendimento .= "        z01_nome,                                                                                                                                                       \n";
$sSqlRendimento .= "        (select array_agg( distinct rh99_regist order by rh99_regist asc)                                                                                               \n";
$sSqlRendimento .= "           from rhdirfgeracaodadospessoal                                                                                                                               \n";
$sSqlRendimento .= "                inner join rhdirfgeracaodadospessoalvalor on rh96_sequencial = rh98_rhdirfgeracaodadospessoal                                                           \n";
$sSqlRendimento .= "                inner join rhdirfgeracaopessoalregist     on rh98_sequencial = rh99_rhdirfgeracaodadospessoalvalor                                                      \n";
$sSqlRendimento .= "          where rhdirfgeracaodadospessoal.rh96_sequencial =   x.rh96_sequencial                                                                                         \n";
$sSqlRendimento .= "          group by rh96_numcgm) as regist,                                                                                                                              \n";
$sSqlRendimento .= "        rh96_cpfcnpj,                                                                                                                                                   \n";
$sSqlRendimento .= "        x.r70_codigo,                                                                                                                                                   \n";
$sSqlRendimento .= "        x.r70_estrut,                                                                                                                                                   \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 1                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                  ),0) as rendimento,                                                                                                                                   \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 1                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                  ),0) as rendimento_13,                                                                                                                                \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 2                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                  ),0) as prev_oficial,                                                                                                                                 \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 2                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                  ),0) as prev_oficial_13,                                                                                                                              \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 3                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                ), 0) as prev_privada,                                                                                                                                  \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 3                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                ), 0) as prev_privada_13,                                                                                                                               \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 4                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                ),0) as depend,                                                                                                                                         \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 4                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                ),0) as depend_13,                                                                                                                                      \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 5                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as pensao,                                                                                                                                        \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 5                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                 ),0) as pensao_13,                                                                                                                                     \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 6                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                ),0) as irrf,                                                                                                                                           \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 6                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                ),0) as irrf_13,                                                                                                                                        \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 7                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                  ),0) as aposentadoria_65,                                                                                                                             \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 7                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                  ),0) as aposentadoria_65_13,                                                                                                                          \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 8                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as diaria,                                                                                                                                        \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 9                                                                                                                      \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as ind_rescisao,                                                                                                                                  \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 10                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as abono,                                                                                                                                         \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 15                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as outros5,                                                                                                                                       \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 11                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                  ),0) as molestia_grave_inativos,                                                                                                                      \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 12                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                ),0) as molestia_grave_ativos,                                                                                                                          \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor IN (13,14)                                                                                                               \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as plano_saude                                                                                                                                    \n";
$sSqlRendimento .= "   from ( select distinct                                                                                                                                               \n";
$sSqlRendimento .= "                 rh96_sequencial,                                                                                                                                       \n";
$sSqlRendimento .= "                 rh96_numcgm,                                                                                                                                           \n";
$sSqlRendimento .= "                 z01_nome,                                                                                                                                              \n";
$sSqlRendimento .= "                 rh96_cpfcnpj,                                                                                                                                          \n";
$sSqlRendimento .= "                 rh96_regist,                                                                                                                                           \n";
$sSqlRendimento .= "                 r70_codigo,                                                                                                                                            \n";
$sSqlRendimento .= "                 r70_estrut,                                                                                                                                            \n";
$sSqlRendimento .= "                 r70_descr                                                                                                                                              \n";
$sSqlRendimento .= "            from rhdirfgeracao                                                                                                                                          \n";
$sSqlRendimento .= "                 inner join rhdirfgeracaodadospessoal      on rhdirfgeracaodadospessoal.rh96_rhdirfgeracao                  = rhdirfgeracao.rh95_sequencial             \n";
$sSqlRendimento .= "                 inner join rhdirfgeracaodadospessoalvalor on rhdirfgeracaodadospessoalvalor.rh98_rhdirfgeracaodadospessoal = rhdirfgeracaodadospessoal.rh96_sequencial \n";
$sSqlRendimento .= "                 inner join cgm                            on cgm.z01_numcgm                                                = rhdirfgeracaodadospessoal.rh96_numcgm     \n";
$sSqlRendimento .= "                 inner join rhdirfgeracaopessoalregist     on rhdirfgeracaodadospessoalvalor.rh98_sequencial                = rh99_rhdirfgeracaodadospessoalvalor       \n";
$sSqlRendimento .= "                 inner join rhpessoalmov                   on rh02_anousu                                                   = {$anofolha}                               \n";
$sSqlRendimento .= "                                                          and rh02_mesusu                                                   = {$mesfolha}                               \n";
$sSqlRendimento .= "                                                          and rh02_regist                                                   = rh99_regist                               \n";
$sSqlRendimento .= "                                                          and rh02_instit                                                   = ".$instituicao."            \n";
$sSqlRendimento .= "                 inner join rhlota                         on rhlota.r70_codigo                                             = rhpessoalmov.rh02_lota                    \n";
$sSqlRendimento .= "                                                          and rhlota.r70_instit                                             = rhpessoalmov.rh02_instit                  \n";
$sSqlRendimento .= "         {$sWhere}                                                                                                                                                      \n";
$sSqlRendimento .= "        ) as x                                                                                                                                                          \n";

$rsSqlRendimento = db_query($sSqlRendimento);
$iNumRows        = pg_num_rows($rsSqlRendimento);
if ($iNumRows == 0) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Registros não Processados para o Ano Calendário, entre em contato com o setor responsável.');
  exit;
}

$pdf = new scpdf();
$pdf->Open();


$pdf1 = new db_impcarne($pdf, $iTipoRelatorio);
for ($iInd = 0; $iInd < $iNumRows; $iInd++) {


	$oRendimento = db_utils::fieldsMemory($rsSqlRendimento, $iInd);
  $nome_pens   = getInformacoesComplementares($oRendimento->rh96_numcgm, $anobase);

/**********************************************************************************
   Implementado por Jeferson Santos, quando for funcionarios do fundo municipal de
   saude das lotacoes abaixo, deve imprimir os dados da instituicao prefeitura,
   somente para Marica.
*/

  if ($oDbConfig->codcli == 19985){

     if ($oRendimento->r70_codigo == 222 || $oRendimento->r70_codigo == 223){

        $sSqlDbConfig  = " select ender,                                              ";
        $sSqlDbConfig .= "        cgc,                                                ";
        $sSqlDbConfig .= "        nomeinst,                                           ";
        $sSqlDbConfig .= "        munic,                                              ";
        $sSqlDbConfig .= "        telef                                               ";
        $sSqlDbConfig .= " from db_config                                             ";
        $sSqlDbConfig .= " where codigo = (select codigo from db_config where prefeitura = true) ";

        $rsSqlDbConfig    = db_query($sSqlDbConfig);
        $iNumRowsDbConfig = pg_num_rows($rsSqlDbConfig);
        if ($iNumRowsDbConfig > 0) {

              $oDbConfig2  = db_utils::fieldsMemory($rsSqlDbConfig, 0);
              $prefeitura  = db_translate($oDbConfig2->nomeinst);
              $enderpref   = db_translate($oDbConfig2->ender);
              $municpref   = db_translate($oDbConfig2->munic);
              $telefpref   = $oDbConfig2->telef;
              $cgcpref     = $oDbConfig2->cgc;
        }
     }
  }
/*=============================================================================================*/

  /**
   * Informações de Cabeçalho
   */
  $pdf1->prefeitura      = $prefeitura;
  $pdf1->enderpref       = $enderpref;
  $pdf1->municpref       = $municpref;
  $pdf1->telefpref       = $telefpref;
  $pdf1->cgcpref         = $cgcpref;

  /**
   * Informações Contribuintes
   */
  $pdf1->cpf             = $oRendimento->rh96_cpfcnpj;
  $pdf1->nome            = db_translate($oRendimento->z01_nome);
  $pdf1->resp            = db_translate($resp);
  $pdf1->pensionistas    = db_translate($nome_pens);
  $pdf1->ano             = $anobase;
  $pdf1->matricula       = str_replace('}','',str_replace('{','',$oRendimento->regist));
  $pdf1->lotacao         = $oRendimento->r70_codigo;
  $pdf1->num_comprovante = ($iInd+1);

  /**
   * Informações Bloco Rendimentos Isentos e Não Tributáveis
   */
  $oRendimento->rendimento -= ($oRendimento->aposentadoria_65 + $oRendimento->molestia_grave_inativos +
                               $oRendimento->molestia_grave_ativos
                              );
  $pdf1->w_salario       = ($oRendimento->rendimento < 0 ?0:$oRendimento->rendimento);
  $pdf1->w_contr         = $oRendimento->prev_oficial;
  $pdf1->w_privad        = $oRendimento->prev_privada;
  $pdf1->w_pensao        = $oRendimento->pensao + $oRendimento->pensao_13;
  $pdf1->w_irfonte       = $oRendimento->irrf;
  $pdf1->w_parte         = $oRendimento->aposentadoria_65 + $oRendimento->aposentadoria_65_13;
  $pdf1->w_diaria        = $oRendimento->diaria;
  $pdf1->w_aviso         = $oRendimento->molestia_grave_inativos+$oRendimento->molestia_grave_ativos;
  $pdf1->w_vlresc_ntrib  = $oRendimento->ind_rescisao;
  $pdf1->w_abono         = $oRendimento->abono;
  $pdf1->w_outros5       = $oRendimento->outros5;

  /**
   * Informações Bloco Rendimentos Sujeitos a Tributação Exclusiva
   * @var $n13Salario é a diferença entre os campos:
   *  1(rendimento),
   *  2(prev_oficial),
   *  3(prev_privada),
   *  4(depend),
   *  5(pensao),
   *  6(irrf).
   */
  $n13Salario            = ($oRendimento->rendimento_13
                            - $oRendimento->prev_oficial_13
                            - $oRendimento->prev_privada_13
                            - $oRendimento->depend_13
                            - $oRendimento->aposentadoria_65_13
                            - $oRendimento->pensao_13
                            - $oRendimento->irrf_13);
  if ($n13Salario < 0) {
  	$n13Salario = 0;
  }

  $pdf1->w_sal13         = $n13Salario;
  $pdf1->w_irrf13        = $oRendimento->irrf_13;
  $pdf1->w_outros6       = 0;

  /**
   * Informações Bloco Complementares
   */
  $pdf1->w_dmedic        = $oRendimento->plano_saude;

  $pdf1->imprime();
}

$pdf1->objpdf->Output();

function getInformacoesComplementares($iCgm, $iAno) {

    /**
     * Busca total da pensao por pensionista do CGM informado
     */
    $ssqlPensoes  = "select sum(r52_valor + r52_valcom + r52_valfer + r52_val13 + r52_valres) as valor, ";
    $ssqlPensoes .= "       z01_nome ";
    $ssqlPensoes .= "  from pensao ";
    $ssqlPensoes .= "       inner join cgm on z01_numcgm = r52_numcgm ";
    $ssqlPensoes .= "       inner join rhpessoal on r52_regist = rh01_regist ";
    $ssqlPensoes .= " where r52_anousu = {$iAno} ";
    $ssqlPensoes .= " and rh01_numcgm  = {$iCgm}";
    $ssqlPensoes .= " group by z01_nome";

    $rsTotalPensoes = db_query($ssqlPensoes);
    $iTotalPensoes  = pg_num_rows($rsTotalPensoes);
    $sInformacaoComplementar = '';

    if ($iTotalPensoes > 0) {

      $sInformacaoComplementar = 'PENSAO ALIMENTICIA: ';
      for ($iPensao = 0; $iPensao < $iTotalPensoes; $iPensao++) {

        $oDadosPensao  = db_utils::fieldsMemory($rsTotalPensoes, $iPensao);
        $aPartesNome   = explode(" ", $oDadosPensao->z01_nome);
        $sInformacaoComplementar .= "{$aPartesNome[0]} (".trim(db_formatar($oDadosPensao->valor, 'f')).") ";
      }
    }

    /**
     * Buscas os dados da base B932 que contem as rubricas de desconto dos planos de saúde,
     * o valor de desconto total de cada uma delas é somada com base no ano base da geração da DIRF
     */
    $sWhereBaseRubricas  = " select distinct r09_rubric                       ";
    $sWhereBaseRubricas .= "  from basesr                                     ";
    $sWhereBaseRubricas .= " where r09_anousu  =    ".db_anofolha();
    $sWhereBaseRubricas .= "   and r09_mesusu  =    ".db_mesfolha();
    $sWhereBaseRubricas .= "   and r09_base    = 'B932'                       ";
    $sWhereBaseRubricas .= "   and r09_instit  = " . db_getsession("DB_instit");

    $aTabelasCalculo         = array('gerfsal' => 'r14', 'gerfcom' => 'r48', 'gerfres' => 'r20', 'gerffer' => 'r31');
    $aQueryCalculoFinanceiro = array();

    foreach ($aTabelasCalculo as $sTabela => $sSigla) {

      $sSqlCalculoFinanceiro     = " select sum({$sSigla}_valor) as valor,rh27_descr                                             ";
      $sSqlCalculoFinanceiro    .= "   from {$sTabela}                                                                           ";
      $sSqlCalculoFinanceiro    .= "  inner join rhrubricas on rh27_rubric = {$sSigla}_rubric and rh27_instit = {$sSigla}_instit ";
      $sSqlCalculoFinanceiro    .= "  inner join rhpessoal on rh01_regist  = {$sSigla}_regist                                    ";
      $sSqlCalculoFinanceiro    .= " where {$sSigla}_rubric in ( {$sWhereBaseRubricas} )                                         ";
      $sSqlCalculoFinanceiro    .= "   and {$sSigla}_anousu  = {$iAno}                                                     ";
      $sSqlCalculoFinanceiro    .= "   and rh01_numcgm = {$iCgm}                                                                 ";
      $sSqlCalculoFinanceiro    .= "   and {$sSigla}_instit  = " . db_getsession("DB_instit");
      $sSqlCalculoFinanceiro    .= " group by {$sSigla}_rubric, rh01_numcgm, rh27_descr                                          ";
      $aQueryCalculoFinanceiro[] = $sSqlCalculoFinanceiro;
    }

    $sSqlBaseInfPlanoSaude = implode($aQueryCalculoFinanceiro, 'union');
    $rsBaseInfPlanoSaude   = db_query($sSqlBaseInfPlanoSaude);

    if ($rsBaseInfPlanoSaude && pg_num_rows($rsBaseInfPlanoSaude) > 0) {

      for ($iRubricaPlano = 0; $iRubricaPlano < pg_num_rows($rsBaseInfPlanoSaude); $iRubricaPlano++) {

        $oRubricaPlanoSaude       = db_utils::fieldsMemory($rsBaseInfPlanoSaude, $iRubricaPlano);
        $aNomeRubrica             = explode(" ", $oRubricaPlanoSaude->rh27_descr);
        $sInformacaoComplementar .= "{$aNomeRubrica[0]}(".trim(db_formatar($oRubricaPlanoSaude->valor, 'f')).")";
      }
    }

    return $sInformacaoComplementar;
  }