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

/**
 * Bens por Departamento
 * @author matheus.felini@dbseller.com.br
 */
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("fpdf151/pdf.php"));
require_once modification("model/patrimonio/Bem.model.php");
require_once modification("model/patrimonio/BemClassificacao.model.php");
require_once modification("model/patrimonio/BemTipoAquisicao.php");
require_once modification("model/patrimonio/BemTipoDepreciacao.php");
require_once modification("model/patrimonio/PlacaBem.model.php");
require_once modification("model/CgmFactory.model.php");

$oDaoBens           = db_utils::getDao("bens");
$oDaoBensMater      = db_utils::getDao("bensmater");
$oDaoDbDepart       = db_utils::getDao("db_depart");;
$oDaoDepartOrg      = db_utils::getDao("db_departorg");
$iAnoUsoSessao      = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");
$oGet               = db_utils::postMemory($_GET);

ini_set("memory_limit", "-1");

/**
 * Variavel que armazena todos os filtros utilizados pelo usuário
 * Ao terminar de validar os filtro selecionados pelo usuário, é efetuado
 * um implode da string " and " no array.
 * @var array
 */
$aWhereParametros   = array();

$aWhereParametros[] = "t52_instit = {$iInstituicaoSessao}";
$sWhereDivisao      = "";
$oGet->lUsarDivisao = $oGet->lUsarDivisao == '1' ? true : false;

/**
 * Filtra o SQL por departamentos divisões
 */
if (trim($oGet->sDepartamentos) != "") {

  $sWhereDivisao .= "and db_depart.instit = {$iInstituicaoSessao}";
  if (trim($oGet->sDivisoes) != "") {

    $aWhereParametros[] = "t33_divisao in ({$oGet->sDivisoes})";
    if ($oGet->lUsarDivisao) {
      $sWhereDivisao .= " and t30_codigo in ({$oGet->sDivisoes})";
    }
  }
  $sWhereDepartamentos = "coddepto in ({$oGet->sDepartamentos}) $sWhereDivisao";
  $sSqlDbDepartamentos = $oDaoDbDepart->sql_query_div(null, "*", null, $sWhereDepartamentos);
  $lTodosDepartamentos = false;

} else {

  $sSqlDbDepartamentos = $oDaoDbDepart->sql_query_div(null, "*", null, "o40_instit = {$iInstituicaoSessao}");
  $lTodosDepartamentos = true;
}

$rsDbDepartamentos = $oDaoDbDepart->sql_record($sSqlDbDepartamentos);
if ($oDaoDbDepart->numrows == 0) {

  $oParms = new stdClass();
  $oParms->sDepartamentos = $oGet->sDepartamentos;
  $sMsg = _M('patrimonial.patrimonio.pat2_benspordepartamento002.departamento_nao_encontrado',$oParms);
  db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
}

/**
 * Classificação
 */
if (trim($oGet->iClassificacao) != "") {
  $aWhereParametros[] = "t64_class = '{$oGet->iClassificacao}'";
}
/**
 * Bens
 */
if (trim($oGet->sBens) != "") {
  $aWhereParametros[] = "t52_bem in ({$oGet->sBens})";
}
/**
 * Situação dos Bens
 */
if (trim($oGet->sSituacaoBens) != "") {

  $sSqlSituaBens       = "(select t56_situac ";
  $sSqlSituaBens      .= "   from histbem ";
  $sSqlSituaBens      .= "  where t56_codbem = t52_bem";
  $sSqlSituaBens      .= "  order by t56_data desc ";
  $sSqlSituaBens      .= "  limit 1 ) in ({$oGet->sSituacaoBens})";
  $aWhereParametros[]  = $sSqlSituaBens;
}
/**
 * Filtro de baixados ou nao
 */
if (trim($oGet->iListarBens) == 2) {

	$aWhereParametros[] = "t55_codbem is null";
} elseif (trim($oGet->iListarBens) == 3) {
	$aWhereParametros[] = "t55_codbem is not null";
}

/**
 * Contas Estruturais
 */
if (trim($oGet->sContasEstruturais) != "") {

  $aWhereParametros[] = "c60_codcon in ({$oGet->sContasEstruturais})";

  $oDaoConplano       = db_utils::getDao("conplano");
  $sWhereConplano     = "c60_codcon in ({$oGet->sContasEstruturais}) and c60_anousu = {$iAnoUsoSessao}";
  $sSqlConplano       = $oDaoConplano->sql_query_file(null, null, "*", null, $sWhereConplano);
  $rsConplano         = $oDaoConplano->sql_record($sSqlConplano);
  /*
   * Configura um header para o relatório caso tenha somente um plano de conta escolhido
   */
  if ($oDaoConplano->numrows == 1) {

    $oConta  = db_utils::fieldsMemory($rsConplano, 0);
    $head6   = "Conta: {$oConta->c60_estrut} - {$oConta->c60_descr}";
  }
}

/**
 * Configurações da data de aquisição
 */
if (trim($oGet->dtAquisicaoInicial) != "" && trim($oGet->dtAquisicaoFinal) == "") {

  $dtAquisicaoInicial = implode("-", array_reverse(explode("/", $oGet->dtAquisicaoInicial)));
  $aWhereParametros[] = "t52_dtaqu >= cast('{$dtAquisicaoInicial}' as date)";
} else if (trim($oGet->dtAquisicaoInicial) == "" && trim($oGet->dtAquisicaoFinal) != "") {

  $dtAquisicaoFinal   = implode("-", array_reverse(explode("/", $oGet->dtAquisicaoFinal)));
  $aWhereParametros[] = "t52_dtaqu <= cast('{$dtAquisicaoFinal}' as date)";
} else if (trim($oGet->dtAquisicaoInicial) != "" && trim($oGet->dtAquisicaoFinal) != "") {

  $dtAquisicaoInicial = implode("-", array_reverse(explode("/", $oGet->dtAquisicaoInicial)));
  $dtAquisicaoFinal   = implode("-", array_reverse(explode("/", $oGet->dtAquisicaoFinal)));
  $aWhereParametros[] = "t52_dtaqu between cast('{$dtAquisicaoInicial}' as date) and cast('{$dtAquisicaoFinal}' as date)";
}
/**
 * Descrição do Bem
 */
if (trim($oGet->sDescricaoBem) != "") {
  $aWhereParametros[] = "t52_descr ilike '%{$oGet->sDescricaoBem}%'";
}

/**
 * Configura variável que deverá ordenar os bens
 */
$sOrdemBuscaBens = null;
switch ($oGet->iOrdem) {

  /*
   * Placa
   */
  case "1":
    $sOrdemBuscaBens = "t64_class, t52_ident::numeric";
    break;

  /*
   * Descrição
   */
  case "3":
    $sOrdemBuscaBens = "t52_descr";
    break;

  /*
   * Ordem Default
   */
  default:
    $sOrdemBuscaBens = "t52_bem";
}

/**
 * Tratamento da propriedade iConvenio
 */
switch ($oGet->iConvenio) {

  /*
   * Ambos vinculados a convênio
   */
  case "2":
    if (!empty($oGet->sCedentes)) {
      $aWhereParametros[] = "t04_sequencial in({$oGet->sCedentes})";
    } else {
      $aWhereParametros[] = "t04_sequencial is not null";
    }
    break;

    /*
     * Apenas não vinculados a convênio
     */
  case "3":
    $aWhereParametros[] = "t04_sequencial is null";
    break;
}

/**
 * Configura as datas de baixa selecionado pelo usuário
 */
$dtBaixaInicial   = "";
$dtBaixaFinal     = "";
if (trim($oGet->dtBaixaInicial) != "" && trim($oGet->dtBaixaFinal) != "") {

  $dtBaixaInicial = implode("-", array_reverse(explode("/", $oGet->dtBaixaInicial)));
  $dtBaixaFinal   = implode("-", array_reverse(explode("/", $oGet->dtBaixaFinal)));
} else if (trim($oGet->dtBaixaInicial) != "" && trim($oGet->dtBaixaFinal) == "") {

  $dtBaixaInicial = implode("-", array_reverse(explode("/", $oGet->dtBaixaInicial)));
  $dtBaixaFinal   = date("Y-m-d", $iAnoUsoSessao);
} else if (trim($oGet->dtBaixaInicial) == "" && trim($oGet->dtBaixaFinal) != "") {

  $dtBaixaInicial = date("Y-m-d", $iAnoUsoSessao);
  $dtBaixaFinal   = implode("-", array_reverse(explode("/", $oGet->dtBaixaFinal)));
}


/**
 * Query que busca os bens que se encaixam nos filtros selecionados pelo usuário
 */
$sWhereParametros = implode(" and ", $aWhereParametros);

/**
 * Adiciona a busca por data de baixa caso o usuário tenha setado as datas no formulário
 * Caso o usuário tenha filtrado por itens não baixados, o filtro de período de baixa será ignorado.
 * Caso o usuário tenha filtrado por todos os itens, busca os baixados no período e os não baixados independente do período de baixa.
 * Caso o usuário tenha filtrado por itens baixados, busca somente os baixados no período.
 */
if (!empty($dtBaixaInicial) && !empty($dtBaixaFinal)) {

  $sWhereDataBaixa = " t55_baixa between cast('{$dtBaixaInicial}' as date) and cast('{$dtBaixaFinal}' as date) ";
  switch (trim($oGet->iListarBens)) {
    case 1:
      $sWhereDataBaixa = " and (t55_codbem is null or {$sWhereDataBaixa}) ";
      break;
    case 2:
      $sWhereDataBaixa = "";
      break;
    case 3:
      $sWhereDataBaixa = " and {$sWhereDataBaixa} ";
      break;
  }

  $sWhereParametros .= " {$sWhereDataBaixa} ";
}

$sCamposBens  = "case when t54_codbem is null ";
$sCamposBens .= "     then 'Material'";
$sCamposBens .= "     else 'Imóvel'";
$sCamposBens .= " end as tipobem,";
$sCamposBens .= "case when t55_codbem is null ";
$sCamposBens .= "     then 'Não' ";
$sCamposBens .= "     else 'Sim' ";
$sCamposBens .= " end as situacaobem,";

/**
 * t44_valoratual = valor depreciavel
 */
//$sCamposBens .= "((t52_valaqu - t44_valorresidual) - t44_valoratual ) as valordepreciado,";
$sCamposBens .= "(select sum(t58_valorcalculado) 
                    from benshistoricocalculobem 
                         inner join benshistoricocalculo on t57_sequencial = t58_benshistoricocalculo 
                   where t58_bens = t52_bem 
                     and t57_ativo is true 
                     and t57_processado is true 
                     and t58_benstipodepreciacao <> 6) as valordepreciado,";
$sCamposBens .= "(t44_valoratual + t44_valorresidual) as valoratual,";
$sCamposBens .= "bens.*, ";
$sCamposBens .= "db_depart.*, ";
$sCamposBens .= "bensdiv.*, ";
$sCamposBens .= "clabens.*, ";
$sCamposBens .= "conplano.*, ";
$sCamposBens .= "departdiv.*, ";
$sCamposBens .= "benscedente.*, ";
$sCamposBens .= "benscadcedente.*, ";
$sCamposBens .= "bensmater.*, ";
$sCamposBens .= "bensimoveis.*, ";
$sCamposBens .= "bensbaix.*, ";
$sCamposBens .= "bensdepreciacao.*,";
$sCamposBens .= "(  select t70_descr ";
$sCamposBens .= "     from histbem ";
$sCamposBens .= "          inner join situabens on t70_situac = t56_situac ";
$sCamposBens .= "    where t56_codbem = t52_bem ";
$sCamposBens .= " order by t56_histbem desc limit 1 ) as estadobem";

/**
 * Monta o SQL de acordo validando se o usuário selecionou todos departamentos ou não
 */
if ($lTodosDepartamentos) {

  $sSqlBens  = "   select {$sCamposBens} ";
  $sSqlBens .= "     from bens ";
  $sSqlBens .= "          inner join db_depart      on db_depart.coddepto 				= bens.t52_depart";
  $sSqlBens .= "          left  join bensdiv        on t52_bem 										= t33_bem";
  $sSqlBens .= "          left  join departdiv      on t33_divisao 								= t30_codigo";

  $sSqlBens .= "          inner join clabens        on clabens.t64_codcla 				= bens.t52_codcla";
  $sSqlBens .= "          inner join clabensconplano on clabensconplano.t86_clabens = clabens.t64_codcla ";
  $sSqlBens .= "                                   and clabensconplano.t86_anousu  = {$iAnoUsoSessao}";
  $sSqlBens .= "          inner join conplano        on conplano.c60_codcon = clabensconplano.t86_conplano ";
  $sSqlBens .= "                                   and conplano.c60_anousu 				= {$iAnoUsoSessao}";

  $sSqlBens .= "          inner join db_departorg   on db_departorg.db01_coddepto = db_depart.coddepto";
  $sSqlBens .= "                                   and db_departorg.db01_anousu   = {$iAnoUsoSessao}";
  $sSqlBens .= "          left join benscedente     on t09_bem                		= t52_bem";
  $sSqlBens .= "          left join benscadcedente  on t09_benscadcedente     		= t04_sequencial";
  $sSqlBens .= "  				left join bensmater       on t53_codbem                 = t52_bem";
  $sSqlBens .= "  				left join bensimoveis     on t54_codbem                 = t52_bem";
  $sSqlBens .= "  				left join bensbaix        on t55_codbem                 = t52_bem";
  $sSqlBens .= "  				left join bensdepreciacao on t44_bens                   = t52_bem";
  $sSqlBens .= "    where {$sWhereParametros}";
  $sSqlBens .= " order by db01_orgao, db01_coddepto, t52_depart, t30_codigo, {$sOrdemBuscaBens} ";

} else {

  $sSqlBens  = "   select {$sCamposBens}";
  $sSqlBens .= "          from bens ";
  $sSqlBens .= "          inner join db_depart 			 on db_depart.coddepto  = bens.t52_depart ";
  $sSqlBens .= "          left  join bensdiv   			 on t52_bem 						 = t33_bem ";
  $sSqlBens .= "          left  join departdiv 			 on t33_divisao         = t30_codigo ";

  $sSqlBens .= "          inner join clabens   			 on clabens.t64_codcla  = bens.t52_codcla ";
  $sSqlBens .= "         inner join clabensconplano  on clabensconplano.t86_clabens = clabens.t64_codcla ";
  $sSqlBens .= "                                    and clabensconplano.t86_anousu  = {$iAnoUsoSessao}";
  $sSqlBens .= "         inner join conplano         on conplano.c60_codcon = clabensconplano.t86_conplano ";
  $sSqlBens .= " 	                             		  and conplano.c60_anousu = {$iAnoUsoSessao}";

  $sSqlBens .= "  				left  join benscedente     on t09_bem            = t52_bem";
  $sSqlBens .= "  				left  join benscadcedente  on t09_benscadcedente = t04_sequencial";
  $sSqlBens .= "  				left  join bensmater       on t53_codbem         = t52_bem";
  $sSqlBens .= "  				left  join bensimoveis     on t54_codbem         = t52_bem";
  $sSqlBens .= "  				left  join bensbaix        on t55_codbem         = t52_bem";
  $sSqlBens .= "  				left  join bensdepreciacao on t44_bens           = t52_bem";
  $sSqlBens .= " 		where t52_depart in ({$oGet->sDepartamentos}) and {$sWhereParametros} ";
  $sSqlBens .= " order by t52_depart,t30_codigo, {$sOrdemBuscaBens} ";
}

/**
 * Executa a query que busca os bens que serão impressos no relatório
 */
$rsBuscaBens      = db_query($sSqlBens);
$iLinhasBuscaBens = pg_num_rows($rsBuscaBens);
if ($iLinhasBuscaBens == 0) {
  $sMsg = _M('patrimonial.patrimonio.pat2_benspordepartamento002.nao_existem_bens');
  db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  exit;
}

/**
 * Array que armazena os dados de órgão e unidade de um departamento
 * @var array
 */
$aOrgaosUnidade = array();

/**
 * Array que irá armazenar todos os dados já organizados obedecendo a seguinte estrutura
 * - Departamento
 * -- Divisao
 * --- Classificação
 * ---- Bem
 * @var array
 */
$aDepartamentos = array();

/**
 * Este for percorre o resultset retornado do banco de dados e cria um
 * novo array de dados com todos os bens já organizados por divisão e classificação.
 */
for ($iRow = 0; $iRow < $iLinhasBuscaBens; $iRow++) {

  $oBem = db_utils::fieldsMemory($rsBuscaBens, $iRow);

  /**
   * Busca o órgao
   */
  if (!array_key_exists($oBem->t52_depart, $aOrgaosUnidade)) {

    $sCamposDepartamentoOrgao = "db01_orgao, db01_unidade, o40_descr as orgao, o41_descr as unidade";
    $sWhereDepartamentoOrgao  = "db01_coddepto in ({$oBem->t52_depart}) and db01_anousu = {$iAnoUsoSessao}";
    $sSqlDepartamentoOrgao    = $oDaoDepartOrg->sql_query_orgunid(null, null, $sCamposDepartamentoOrgao, null, $sWhereDepartamentoOrgao);
    $rsDepartamentoOrgao      = $oDaoDepartOrg->sql_record($sSqlDepartamentoOrgao);

    /**
     * Adicionamos o órgao/unidade para o departamento dentro de um array $aOrgaosUnidade
     * Para usar posteriormente durante a impressão das páginas do relatório
     */
    $oDadoDepartamentoOrgao                        = db_utils::fieldsMemory($rsDepartamentoOrgao, 0);
    $oDadoDepartamentoOrgao->codigodepartamento    = $oBem->t52_depart;
    $oDadoDepartamentoOrgao->descricaodepartamento = $oBem->descrdepto;
    $aOrgaosUnidade[$oBem->t52_depart]             = $oDadoDepartamentoOrgao;
  }

  /**
   * Configura a variável t30_codigo caso o bem não tenha divisao
   */
  $iCodigoDivisao    = $oBem->t30_codigo;
  $sDescricaoDivisao = $oBem->t30_descr;
  if (empty($oBem->t30_codigo)) {

    $iCodigoDivisao    = 0;
    $sDescricaoDivisao = "BENS SEM DIVISÃO";
  }

  $iDepartamento                = $oBem->t52_depart;
  $sDepartamento                = $oBem->descrdepto;

  $iClassificacao               = $oBem->t64_codcla;
  $sClassificacao               = $oBem->t64_class;
  $sClassificacaoDescricao      = $oBem->t64_descr;

  $before                       = memory_get_usage();
  $oStdBem                      = new stdClass();
  $oStdBem->t52_depart          = $oBem->t52_depart;
  $oStdBem->t52_dtaqu           = $oBem->t52_dtaqu;
  $oStdBem->t52_bem             = $oBem->t52_bem;
  $oStdBem->t52_obs							= $oBem->t52_obs;
  $oStdBem->t52_descr           = substr($oBem->t52_descr,0, 35);
  $oStdBem->t52_valaqu          = $oBem->t52_valaqu;
  $oStdBem->t44_valorresidual   = $oBem->t44_valorresidual;
  $oStdBem->valordepreciado     = $oBem->valordepreciado;
  $oStdBem->valoratual          = $oBem->valoratual;
  $oStdBem->t44_valoratual      = $oBem->t44_valoratual;
  $oStdBem->estadobem           = $oBem->estadobem;
  $oStdBem->tipobem             = $oBem->tipobem;
  $oStdBem->situacaobem         = $oBem->situacaobem;
  $oStdBem->t52_depart          = $oBem->t52_depart;
  $oStdBem->t52_ident           = $oBem->t52_ident;
  $oStdBem->t44_benstipodepreciacao = $oBem->t44_benstipodepreciacao;
  $after                        = memory_get_usage();
  $allocatedSize                = ($after - $before);
  unset($oBem);


  /**
   * Criamos um novo array contendo os dados já organizados que serão impressos no relatório.
   *
   * Valida se o Departamento já existe no array que deve ser configurado.
   */
  if (empty($aDepartamentos[$iDepartamento])) {

    /*
     * Caso o departamento não exista, é criado uma estrutura dentro do array $aDepartamentos
     * com os dados que serão preenchidos no decorrer do FOR
     */
    $oDepartamento                                                                                        = new stdClass();
    $oDepartamento->iCodigoDepartamento                                                                   = $iDepartamento;
    $oDepartamento->sDescricaoDepartamento                                                                = $sDepartamento;
    $oDepartamento->aDivisao                                                                              = array();
    $oDepartamento->aDivisao[$iCodigoDivisao]                                                             = new stdClass();
    $oDepartamento->aDivisao[$iCodigoDivisao]->iCodigoDivisao                                             = $iCodigoDivisao;
    $oDepartamento->aDivisao[$iCodigoDivisao]->sDescricaoDivisao                                          = $sDescricaoDivisao;
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao                                             = array();
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]                            = new stdClass();
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->iCodigoClassificacao      = $iClassificacao;
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->sClassificacao            = $sClassificacao;
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->sDescricaoClassificacao   = $sClassificacaoDescricao;
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->aBens                     = array();
    $oDepartamento->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->aBens[]                   = $oStdBem;

    /*
     * Adicionamos o objeto dentro do array de departamentos.
     */
    $aDepartamentos[$iDepartamento] = $oDepartamento;

  } else {

    /**
     * Caso o departamento exista no array, validamos se existe a divisão para o departamento em questão.
     */
    if ( empty ($aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]) ) {

      /*
       * Caso não exista criamos uma nova posição no array aDivisao com a classificação já setada para o bem
       */
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]                                                           = new stdClass();
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->iCodigoDivisao                                           = $iCodigoDivisao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->sDescricaoDivisao                                        = $sDescricaoDivisao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao                                           = array();
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]                          = new stdClass();
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->iCodigoClassificacao    = $iClassificacao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->sClassificacao          = $sClassificacao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->sDescricaoClassificacao = $sClassificacaoDescricao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->aBens                   = array();
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->aBens[]                 = $oStdBem;

    } else {

      /*
       * Caso a divisão já exista, apenas adicionamos uma nova posição no array aClassificacao com o bem em questão
       */
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->iCodigoClassificacao    = $iClassificacao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->sClassificacao          = $sClassificacao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->sDescricaoClassificacao = $sClassificacaoDescricao;
      $aDepartamentos[$iDepartamento]->aDivisao[$iCodigoDivisao]->aClassificacao[$iClassificacao]->aBens[]                 = $oStdBem;
    }
  }
}

/**
 * Configura variável que valida se deve imprimir o valor ou não.
 * Facilita a forma de validação
 */
$oGet->lImprimeValorAquisicao   = $oGet->lImprimeValorAquisicao 	== 't' ? true : false;
$oGet->lCaracteristicaAdicional = $oGet->lCaracteristicaAdicional == 't' ? true : false;

$head1   = "Relatório de Bens por Departamento";
$oPdf    = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth             = 4;
$iWidth              = 100;
$iTotalClassificacao = 0;
$lPrimeiroLaco       = true;

/**
 * Variáveis configuradas para impressão de bens SEM VALORES
 */
$iWCodigoSemValor    = 20;
$iWPlacaSemValor     = 20;
$iWDescricaoSemValor = 50;
$iWAquisicaoSemValor = 20;
$iWEstadoSemValor    = 20;
$iWDefinicaoSemValor = 20;
$iWSituacaoSemValor  = 20;

/**
 * Percorremos o array de departamentos imprimindo as divisões, classificações e bens
 */
$iTamanhoWidth = 190;
if ($oGet->lImprimeValorAquisicao) {
  $iTamanhoWidth = 280;
}

$oTotal = new stdClass();
$oTotal->nValorAquisicao   = 0;
$oTotal->nValorResidual    = 0;
$oTotal->nValorDepreciado  = 0;
$oTotal->nValorAtual       = 0;
$oTotal->nValorDepreciavel = 0;

foreach ($aDepartamentos as $iIndiceDepartamento => $oDepartamento) {

  /**
   * Caso o usuário tenha escolhido a opção para imprimir os valores, o relatório será gerado em formato
   * paisagem. Do contrário será gerado em formato retrato.
   *
   * A variável $oGet->iQuebraPagina é referente a quebra de página por departamento
   */
  if ($oGet->iQuebraPagina == 2 || $oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    setHeader($oPdf, $iHeigth, $oGet->lImprimeValorAquisicao, $oGet->lCaracteristicaAdicional, $oDepartamento->iCodigoDepartamento, $aOrgaosUnidade);
    $lPrimeiroLaco = false;
  }

  foreach ($oDepartamento->aDivisao as $iIndiceDivisao => $oDivisao) {

    $iTotaBemPorDivisao = 0;

    $oTotalDivisao = new stdClass();
    $oTotalDivisao->nValorAquisicao   = 0;
    $oTotalDivisao->nValorResidual    = 0;
    $oTotalDivisao->nValorDepreciado  = 0;
    $oTotalDivisao->nValorAtual       = 0;
    $oTotalDivisao->nValorDepreciavel = 0;

    $oPdf->SetFont("arial", "b", 8);
    $oPdf->Cell($iTamanhoWidth, $iHeigth, "Divisão: {$oDivisao->iCodigoDivisao} - {$oDivisao->sDescricaoDivisao}", "T", 1);
    $iTotalBemNaClassificacao = 0;
    foreach ($oDivisao->aClassificacao as $iIndiceClassificacao => $oClassificacao) {

      $iTotaBemPorClassificacao = 0;
      $oPdf->SetFont("arial", "b", 6);
      $oPdf->Cell($iTamanhoWidth, 4, "Classificação: {$oClassificacao->sClassificacao} - {$oClassificacao->sDescricaoClassificacao}", "T", 1);

      /**
       * Imprimimos os bens da classificação
       */
      foreach ($oClassificacao->aBens as $iIndiceBem => $oBem) {

        /**
         * Configuramos variáveis
         */
        $dtAquisicaoBem    = implode("/", array_reverse(explode("-", $oBem->t52_dtaqu)));
        $sPlacaConfigurada = str_pad($oBem->t52_ident, 7, "0", STR_PAD_LEFT);

        $oPdf->SetFont("arial", "", 6);
        $oPdf->cell($iWCodigoSemValor,    $iHeigth, $oBem->t52_bem     , 0, 0, "C");
        $oPdf->cell($iWPlacaSemValor,     $iHeigth, $sPlacaConfigurada , 0, 0, "C");
        $oPdf->cell($iWDescricaoSemValor, $iHeigth, $oBem->t52_descr   , 0, 0, "L");
        $oPdf->cell($iWAquisicaoSemValor, $iHeigth, $dtAquisicaoBem    , 0, 0, "C");

        /**
         * Caso o usuário tenha solicitado a impressão dos valores, os mesmos serão
         * impressos neste bloco
         */
        if ($oGet->lImprimeValorAquisicao) {


          $oDaoBensHistoricoCalculoBem  = db_utils::getDao('benshistoricocalculobem');
          $sSqlBuscaHistoricoCalculoBem = $oDaoBensHistoricoCalculoBem->sql_query_calculo(null,
                                                                                          "t58_valoranterior",
                                                                                          "t58_sequencial asc limit 1",
                                                                                          "    t58_bens = {$oBem->t52_bem}
                                                                                           and t58_benstipodepreciacao <> 6");
          $rsValorAnterior = db_query($sSqlBuscaHistoricoCalculoBem);
          $nValorAnterior  = $oBem->t52_valaqu;

          if ($nValorAnterior == 0) {
            $nValorAnterior = $oBem->t44_valoratual;
          }

          if (pg_num_rows($rsValorAnterior) == 1) {
            $nValorAnterior = db_utils::fieldsMemory($rsValorAnterior, 0)->t58_valoranterior;
          }

          $nValorDepreciado = $nValorAnterior - $oBem->t44_valoratual;
          if ($oBem->t44_benstipodepreciacao == 4 || pg_num_rows($rsValorAnterior) == 0) {
            $nValorDepreciado = 0;
          }

          $oPdf->cell(24, $iHeigth, db_formatar($oBem->t52_valaqu, "f"),        0, 0, "R");
          $oPdf->cell(22, $iHeigth, db_formatar($oBem->t44_valorresidual, "f"), 0, 0, "R");
          $oPdf->cell(24, $iHeigth, db_formatar($oBem->valordepreciado, "f"),        0, 0, "R");
          $oPdf->cell(20, $iHeigth, db_formatar($oBem->valoratual, "f"),        0, 0, "R");
          $oPdf->cell(24, $iHeigth, db_formatar($oBem->t44_valoratual, "f"),    0, 0, "C");

          $oTotal->nValorAquisicao   += $oBem->t52_valaqu;
          $oTotal->nValorResidual    += $oBem->t44_valorresidual;
          $oTotal->nValorDepreciado  += $oBem->valordepreciado;
          $oTotal->nValorAtual       += $oBem->valoratual;
          $oTotal->nValorDepreciavel += $oBem->t44_valoratual;

          $oTotalDivisao->nValorAquisicao   += $oBem->t52_valaqu;
          $oTotalDivisao->nValorResidual    += $oBem->t44_valorresidual;
          $oTotalDivisao->nValorDepreciado  += $oBem->valordepreciado;
          $oTotalDivisao->nValorAtual       += $oBem->valoratual;
          $oTotalDivisao->nValorDepreciavel += $oBem->t44_valoratual;


        }

        $oPdf->cell($iWEstadoSemValor,    $iHeigth, $oBem->estadobem   , 0, 0, "L");
        $oPdf->cell($iWDefinicaoSemValor, $iHeigth, $oBem->tipobem     , 0, 0, "L");
        $oPdf->cell($iWEstadoSemValor,    $iHeigth, $oBem->situacaobem , 0, 1, "L");

        if ($oGet->lCaracteristicaAdicional) {
          $oPdf->cell($iTamanhoWidth, $iHeigth, $oBem->t52_obs,  1, 1, "L", 1);
        }

        /**
         * Verifica se precisa adicionar uma nova página para imprimir os bens
         */
        if ($oPdf->gety() > $oPdf->h - 30) {
          setHeader($oPdf, $iHeigth, $oGet->lImprimeValorAquisicao, $oGet->lCaracteristicaAdicional, $oBem->t52_depart, $aOrgaosUnidade);
        }

        $iTotalBemNaClassificacao++;
        $iTotaBemPorClassificacao++;
        $iTotaBemPorDivisao++;
      }
      $oPdf->SetFont("arial", "b", 6);
      $oPdf->Cell($iTamanhoWidth, 4, "Total da Classificação: {$iTotaBemPorClassificacao}", "T", 1);
    }
    $oPdf->Cell($iTamanhoWidth, 4, "Total da Divisão: {$iTotaBemPorDivisao}", "T", 1);

    if ($oGet->lImprimeValorAquisicao) {

      $oPdf->SetFont("arial", "b", 6);
      $oPdf->cell(110, $iHeigth, "Total:", 0, 0, "R");
      $oPdf->cell(24, $iHeigth, db_formatar($oTotalDivisao->nValorAquisicao  ,"f"),   0, 0, "R");
      $oPdf->cell(22, $iHeigth, db_formatar($oTotalDivisao->nValorResidual   ,"f"),0, 0, "R");
      $oPdf->cell(24, $iHeigth, db_formatar($oTotalDivisao->nValorDepreciado ,"f"),0, 0, "R");
      $oPdf->cell(20, $iHeigth, db_formatar($oTotalDivisao->nValorAtual      ,"f"),0, 0, "R");
      $oPdf->cell(24, $iHeigth, db_formatar($oTotalDivisao->nValorDepreciavel,"f"),0, 1, "C");
      $oPdf->SetFont("arial", "", 6);
    }

  }
}

$oPdf->SetFont("arial", "b", 7);
if ($oGet->lImprimeValorAquisicao) {

  $oPdf->SetFont("arial", "b", 8);
  $oPdf->cell(110, $iHeigth, "Total:", 0, 0, "R", 1);
  $oPdf->cell(24, $iHeigth, db_formatar($oTotal->nValorAquisicao  ,"f"),0, 0, "R", 1);
  $oPdf->cell(22, $iHeigth, db_formatar($oTotal->nValorResidual   ,"f"),0, 0, "R", 1);
  $oPdf->cell(24, $iHeigth, db_formatar($oTotal->nValorDepreciado ,"f"),0, 0, "R", 1);
  $oPdf->cell(20, $iHeigth, db_formatar($oTotal->nValorAtual      ,"f"),0, 0, "R", 1);
  $oPdf->cell(24, $iHeigth, db_formatar($oTotal->nValorDepreciavel,"f"),0, 0, "C", 1);
  $oPdf->cell(55, $iHeigth, "",0, 1, "C", 1);
  $oPdf->SetFont("arial", "", 6);
}
$oPdf->SetFont("arial", "", 6);

/**
 * Função que imprime o cabeçalho do relatório
 * @param PDF     $oPdf
 * @param integer $iHeigth
 * @param boolean $lImprimeValores
 * @param integer $iCodigoDepartamento
 * @param array   $aOrgaosUnidade
 */
function setHeader($oPdf, $iHeigth, $lImprimeValores, $lImprimeCaracteristicaAdicional, $iCodigoDepartamento, $aOrgaosUnidade) {


  global $head3, $head4, $head5;

  /**
   * Verifica se os valores terão de ser impressos. Caso SIM
   * é adicionado uma página no formato PAISAGEM.
   */
  $head3 = "Órgão: {$aOrgaosUnidade[$iCodigoDepartamento]->db01_orgao} - {$aOrgaosUnidade[$iCodigoDepartamento]->orgao}";
  $head4 = "Unidade: {$aOrgaosUnidade[$iCodigoDepartamento]->db01_unidade} - {$aOrgaosUnidade[$iCodigoDepartamento]->unidade}";
  $head5 = "Departamento: {$aOrgaosUnidade[$iCodigoDepartamento]->codigodepartamento} - {$aOrgaosUnidade[$iCodigoDepartamento]->descricaodepartamento}";

  if ($lImprimeValores) {
    $oPdf->AddPage("L");
    $iWidthPagina = "280";
  } else {
    $oPdf->AddPage("P");
    $iWidthPagina = "190";
  }
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->setfillcolor(235);
  $oPdf->cell(20, $iHeigth, "Código",    1, 0, "C", 1);
  $oPdf->cell(20, $iHeigth, "Placa",     1, 0, "C", 1);
  $oPdf->cell(50, $iHeigth, "Descrição", 1, 0, "C", 1);
  $oPdf->cell(20, $iHeigth, "Aquisição", 1, 0, "C", 1);

  if ($lImprimeValores) {

    $oPdf->cell(24, $iHeigth, "Vlr. Aquisição",  1, 0, "C", 1);
    $oPdf->cell(22, $iHeigth, "Vlr. Residual",   1, 0, "C", 1);
    $oPdf->cell(24, $iHeigth, "Vlr. Depreciado", 1, 0, "C", 1);
    $oPdf->cell(20, $iHeigth, "Vlr. Atual",      1, 0, "C", 1);
    $oPdf->cell(24, $iHeigth, "Vlr. Depreciavel", 1, 0, "C", 1);
  }

  $oPdf->cell(20, $iHeigth, "Estado",    1, 0, "C", 1);
  $oPdf->cell(20, $iHeigth, "Definição", 1, 0, "C", 1);
  $oPdf->cell(15, $iHeigth, "Baixado",  1, 1, "C", 1);

  if ($lImprimeCaracteristicaAdicional) {
    $oPdf->cell($iWidthPagina, $iHeigth, "Características Adicionais",  1, 1, "L", 1);
  }
}
$oPdf->Output();
?>
