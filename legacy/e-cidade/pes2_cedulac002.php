<?php
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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_cfpess_classe.php"));

$oDaoCfpess = new cl_cfpess;

/**
 * Tipo de relatório comprovante de rendimento
 * Retorna false caso der erro na consulta
 */
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('comprovanterendimentos', db_anofolha(), db_mesfolha());
if(!$iTipoRelatorio) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de impressão invalido, verifique parametros.');
}

$oPost  = db_utils::postMemory($_POST);
$sOrdem = '';

$sSqlDbConfig  = " select z01_cgccpf as cgc,                                                   ";
$sSqlDbConfig .= "                         z01_nome as nomeinst,                               ";
$sSqlDbConfig .= "                         z01_ender as ender,                                 ";
$sSqlDbConfig .= "                         z01_telef as telef,                                 ";
$sSqlDbConfig .= "                         z01_munic as munic                                  ";
$sSqlDbConfig .= "							      from orcunidade                                          ";
$sSqlDbConfig .= "							           inner join rhlotaexe on rh26_orgao   = o41_orgao    ";
$sSqlDbConfig .= "							                               and rh26_unidade = o41_unidade  ";
$sSqlDbConfig .= "							                               and o41_anousu   = rh26_anousu  ";
$sSqlDbConfig .= "							           inner join rhlota    on r70_codigo   = rh26_codigo  ";
$sSqlDbConfig .= "							           inner join cgm       on r70_numcgm   = z01_numcgm   ";
$sSqlDbConfig .= "							     where o41_cnpj   = trim('{$oPost->cnpj}')                 ";
$sSqlDbConfig .= "							       and z01_cgccpf = trim('{$oPost->cnpj}')                 ";

$rsSqlDbConfig    = db_query($sSqlDbConfig);
if (!$rsSqlDbConfig) {

  db_redireciona('db_erros.php?fechar=true&db_erro=não foi possível pesquisar os dados para geração do comprovante.');
  exit;
}
$iNumRowsDbConfig = pg_num_rows($rsSqlDbConfig);
if ($iNumRowsDbConfig > 0) {

	$oDbConfig  = db_utils::fieldsMemory($rsSqlDbConfig, 0);
	$prefeitura = db_translate($oDbConfig->nomeinst);
	$enderpref  = db_translate($oDbConfig->ender);
	$municpref  = db_translate($oDbConfig->munic);
	$telefpref  = $oDbConfig->telef;
	$cgcpref    = $oDbConfig->cgc;
}

$sWhere     = " where rh95_fontepagadora = trim('$oPost->cnpj') ";
$sWhere    .= "   and rhdirfgeracao.rh95_ano = {$oPost->anobase} ";
$sWhere    .= "   and rhdirfgeracaodadospessoalvalor.rh98_instit = ".db_getsession("DB_instit");
$sPreOrdem  = "";
switch ($oPost->tipo) {

	/**
	 * Filtro lotação
	 */
  case 'l':

  	if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {

			if (isset($oPost->listlotacoes) && !empty($oPost->listlotacoes)) {

			  $sLotacoes = implode("', '", explode(",", $oPost->listlotacoes));
			  $sWhere   .= " and r70_estrut in ('{$sLotacoes}') ";
			}
  	} else {

  	  if (isset($oPost->r70_estrut_ini) && isset($oPost->r70_estrut_fim)) {

        if (!empty($oPost->r70_estrut_ini) && !empty($oPost->r70_estrut_fim)) {
          $sWhere .= " and r70_estrut between '{$oPost->r70_estrut_ini}' and '{$oPost->r70_estrut_fim}' ";
        } else if (!empty($oPost->r70_estrut_ini)) {
          $sWhere .= " and ( r70_estrut >= '{$oPost->r70_estrut_ini}' ) ";
        } else if (!empty($oPost->r70_estrut_fim)) {
          $sWhere .= " and ( r70_estrut <= '{$oPost->r70_estrut_fim}' ) ";
        }
      }
  	}


  	/** Sempre ordenar pelo código da lotação quando selecionar o tipo de resumo por lotação **/
  	$sOrdem = 'r70_codigo,';

    break;

  /**
   * Filtro Matricula
   */
  case 'm':

    if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {

			if (isset($oPost->listmatriculas) && !empty($oPost->listmatriculas)) {

			  $sMatriculas = implode(",", explode(",", $oPost->listmatriculas));
			  $sWhere     .= " and rh99_regist in ({$sMatriculas}) ";
			}
    } else {

			if (isset($oPost->rh01_regist_ini) && isset($oPost->rh01_regist_fim)) {

			  if (!empty($oPost->rh01_regist_ini) && !empty($oPost->rh01_regist_fim)) {
			    $sWhere .= " and rh99_regist between {$oPost->rh01_regist_ini} and {$oPost->rh01_regist_fim}";
			  } else if (!empty($oPost->rh01_regist_ini)) {
			    $sWhere .= " and ( rh99_regist >= {$oPost->rh01_regist_ini} ) ";
			  } else if (!empty($oPost->rh01_regist_fim)) {
			    $sWhere .= " and ( rh99_regist <= {$oPost->rh01_regist_fim} ) ";
			  }
			}
    }
    break;

  /**
   * Filtro Autônomos/Fornecedores
   */
  case 'pf':

    $sWhere     .= " and length(trim(rh96_cpfcnpj)) = 11 ";
    if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {

      if (isset($oPost->listcgms) && !empty($oPost->listcgms)) {
        $sWhere   .= " and z01_numcgm in ({$oPost->listcgms}) ";
      }
    } else {

      if (isset($oPost->z01_numcgm_ini) && isset($oPost->z01_numcgm_fim)) {

        if (!empty($oPost->z01_numcgm_ini) && !empty($oPost->z01_numcgm_fim)) {
          $sWhere .= " and z01_numcgm between '{$oPost->z01_numcgm_ini}' and '{$oPost->z01_numcgm_fim}' ";
        } else if (!empty($oPost->z01_numcgm_ini)) {
          $sWhere .= " and ( z01_numcgm >= '{$oPost->z01_numcgm_ini}' ) ";
        } else if (!empty($oPost->z01_numcgm_fim)) {
          $sWhere .= " and ( z01_numcgm <= '{$oPost->z01_numcgm_fim}' ) ";
        }
      }
    }
    break;

  /**
   * Filtro Pessoa Jurídica
   */
  case 'pj':

    $sWhere     .= " and length(trim(rh96_cpfcnpj)) = 14 ";
    if (isset($oPost->tipofiltro) && $oPost->tipofiltro == 's') {

      if (isset($oPost->listcgms) && !empty($oPost->listcgms)) {
        $sWhere   .= " and z01_numcgm in ({$oPost->listcgms}) ";
      }
    } else {

      if (isset($oPost->z01_numcgm_ini) && isset($oPost->z01_numcgm_fim)) {

        if (!empty($oPost->z01_numcgm_ini) && !empty($oPost->z01_numcgm_fim)) {
          $sWhere .= " and z01_numcgm between '{$oPost->z01_numcgm_ini}' and '{$oPost->z01_numcgm_fim}' ";
        } else if (!empty($oPost->z01_numcgm_ini)) {
          $sWhere .= " and ( z01_numcgm >= '{$oPost->z01_numcgm_ini}' ) ";
        } else if (!empty($oPost->z01_numcgm_fim)) {
          $sWhere .= " and ( z01_numcgm <= '{$oPost->z01_numcgm_fim}' ) ";
        }
      }
    }
    break;
}

/**
 * Filtro Sem Retenção
 */
if ($oPost->semirf == 'n') {

  $sWhere .= " and ( select sum(rh98_valor)                                        ";
  $sWhere .= "         from rhdirfgeracaodadospessoalvalor                         ";
  $sWhere .= "        where rh98_rhdirftipovalor = 6                               ";
  $sWhere .= "          and rh98_rhdirfgeracaodadospessoal = rh96_sequencial ) > 0 ";
}

/**
 * Filtro Ordem
 */
if ($oPost->ordem == 'a') {
  $sOrdem .= 'z01_nome asc';
} else {
  $sOrdem .= 'regist asc';
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
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 11                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                  ),0) as molestia_grave_inativos_13,                                                                                                                   \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 12                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                ),0) as molestia_grave_ativos,                                                                                                                          \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 12                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
$sSqlRendimento .= "                ),0) as molestia_grave_ativos_13,                                                                                                                       \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor IN (13,14)                                                                                                               \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as plano_saude,                                                                                                                                   \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 17                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_rendimentos_tributaveis,                                                                                                                   \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 18                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_previdencia,                                                                                                                               \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 19                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_pensao,                                                                                                                                    \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 20                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_irrf,                                                                                                                                      \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 21                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_despesa_acao,                                                                                                                              \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 22                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_quantidade_meses,                                                                                                                          \n";
$sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
$sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
$sSqlRendimento .= "                    where rh98_rhdirftipovalor = 23                                                                                                                     \n";
$sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
$sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
$sSqlRendimento .= "                 ),0) as rra_isentos                                                                                                                                    \n";
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
$sSqlRendimento .= "                 inner join rhpessoalmov                   on rh02_anousu                                                   = {$oPost->anofolha}                        \n";
$sSqlRendimento .= "                                                          and rh02_mesusu                                                   = {$oPost->mesfolha}                        \n";
$sSqlRendimento .= "                                                          and rh02_regist                                                   = rh99_regist                               \n";
$sSqlRendimento .= "                                                          and rh02_instit                                                   = ".db_getsession("DB_instit")."            \n";
$sSqlRendimento .= "                 inner join rhlota                         on rhlota.r70_codigo                                             = rhpessoalmov.rh02_lota                    \n";
$sSqlRendimento .= "                                                          and rhlota.r70_instit                                             = rhpessoalmov.rh02_instit                  \n";
$sSqlRendimento .= "         {$sWhere}                                                                                                                                                      \n";
$sSqlRendimento .= "        ) as x                                                                                                                                                          \n";
$sSqlRendimento .= " order by {$sOrdem}                                                                                                                                                     \n";

$rsSqlRendimento = db_query($sSqlRendimento);
if (!$rsSqlRendimento) {
  
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi possível pesquisar os dados para a geração do comprovantes');
  exit;
}
$iNumRows        = pg_num_rows($rsSqlRendimento);

if ($iNumRows == 0) {

	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
  exit;
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf, $iTipoRelatorio);
$oDirf = new Dirf2012($oPost->anobase, $oPost->cnpj);

for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

	$oRendimento = db_utils::fieldsMemory($rsSqlRendimento, $iInd);
  $nome_pens   = $oDirf->getInformacoesComplementares($oRendimento->rh96_numcgm);

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
  $pdf1->resp            = db_translate($oPost->resp);
  $pdf1->pensionistas    = db_translate($nome_pens);
  $pdf1->ano             = $oPost->anobase;
  $pdf1->matricula       = str_replace('}','',str_replace('{','',$oRendimento->regist));
  $pdf1->lotacao         = $oRendimento->r70_codigo;
  $pdf1->num_comprovante = ($iInd+1);

  /**
   * Informações Bloco Rendimentos Isentos e Não Tributáveis
   */
   
  $pdf1->w_salario       = ($oRendimento->rendimento < 0 ?0:$oRendimento->rendimento);
  $pdf1->w_contr         = $oRendimento->prev_oficial;
  $pdf1->w_privad        = $oRendimento->prev_privada;
  $pdf1->w_pensao        = $oRendimento->pensao;
  $pdf1->w_irfonte       = $oRendimento->irrf;
  $pdf1->w_parte         = $oRendimento->aposentadoria_65 + $oRendimento->aposentadoria_65_13;
  $pdf1->w_diaria        = $oRendimento->diaria;
  $pdf1->w_aviso         = $oRendimento->molestia_grave_inativos+$oRendimento->molestia_grave_inativos_13+$oRendimento->molestia_grave_ativos+$oRendimento->molestia_grave_ativos_13;
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

  /**
   * Informações RRA
   */
  $pdf1->nRRARentimentosTributaveis = $oRendimento->rra_rendimentos_tributaveis;
  $pdf1->nRRAPrevidencia            = $oRendimento->rra_previdencia;
  $pdf1->nRRAPensao                 = $oRendimento->rra_pensao;
  $pdf1->nRRAIRRF                   = $oRendimento->rra_irrf;
  $pdf1->nRRADespesasAcaoJudicial   = $oRendimento->rra_despesa_acao;
  $pdf1->iRRAQuantidadeMeses        = $oRendimento->rra_quantidade_meses;
  $pdf1->nRRARendimentosIsentos     = $oRendimento->rra_isentos;
  $pdf1->imprime();
}

$pdf1->objpdf->Output();
