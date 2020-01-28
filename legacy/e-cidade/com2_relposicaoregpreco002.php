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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/compilacaoRegistroPreco.model.php"));
require_once(modification("model/estimativaRegistroPreco.model.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("model/configuracao/DBDepartamento.model.php"));

$oDaoEmpParametro = db_utils::getDao("empparametro");
$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$sSqlCasasDecimais = $oDaoEmpParametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec as casadec");
$rsCasasDecimais   = $oDaoEmpParametro->sql_record($sSqlCasasDecimais);

if ($oDaoEmpParametro->numrows > 0) {
  db_fieldsmemory($rsCasasDecimais, 0);
}

/**
 * $oGet->dtinicrg            = Filtro informa o inicio da data de criação do registro.
 * $oGet->dtfimcrg            = Filtro informa o fim da data de criação do registro.
 * $oGet->dtinivlrg           = Filtro informa o inicio da data de validade do registro.
 * $oGet->dtfimvlrg           = Filtro informa o fim da data de validade do registro.
 * $oGet->numini              = Filtro informa o numero inicial da solicitacao.
 * $oGet->numfim              = Filtro informa o numero final da solicitacao.
 * $oGet->itens               = Filtro informa os itens selecionados para o relatorio.
 * @var relatorio
 */

$sWhere                = "";
$sAnd                  = "";
$sOrder                = "order by solicita.pc10_numero, solicita.pc10_depto, solicitem.pc11_numero, solicitem.pc11_seq";
$sHeaderDtCriacao      = "Criação do Registro: Todos";
$sHeaderDtVal          = "Validade do Registro: Todos";
$sHeaderNum            = "Compilação: Todos";
$sHeaderItens          = "";

/**
 * Verifica as datas de criação do registro informadas no formulario.
 */
$dtIniCrg = implode("-", array_reverse(explode("/", $oGet->dtinicrg)));
$dtFimCrg = implode("-", array_reverse(explode("/", $oGet->dtfimcrg)));



if ((trim($dtIniCrg) != "") && (trim($dtFimCrg) != "")) {

	$sHeaderDtCriacao = "Criação do Registro: ".$oGet->dtinicrg." até ".$oGet->dtfimcrg;
  $sWhere          .= "{$sAnd} solicita.pc10_data  between '{$oGet->dtinicrg}' and '{$oGet->dtfimcrg}' ";
  $sAnd             = " and ";
} else if (trim($oGet->dtinicrg) != "") {

	$sHeaderDtCriacao = "Criação do Registro: ".$oGet->dtinicrg;
  $sWhere .= "{$sAnd} ( solicita.pc10_data >= '{$oGet->dtinicrg}' ) ";
  $sAnd    = " and ";
} else if (trim($oGet->dtfimcrg) != "") {

	$sHeaderDtCriacao = "Criação do Registro: ".$oGet->dtfimcrg;
  $sWhere .= "{$sAnd} ( solicita.pc10_data <= '{$oGet->dtfimcrg}' ) ";
  $sAnd    = " and ";
}

/**
 * Verifica as datas de validade do registro informadas no formulario.
 */
$dtIniVlrg = implode("-", array_reverse(explode("/", $oGet->dtinivlrg)));
$dtFimVlrg = implode("-", array_reverse(explode("/", $oGet->dtfimvlrg)));

if ((trim($dtIniVlrg) != "") && (trim($dtFimVlrg) != "")) {

	$sHeaderDtVal = "Validade do Registro: ".$dtIniVlrg." até ".$dtFimVlrg;
  $sWhere      .= "{$sAnd} ( pc54_datainicio >= '{$dtIniVlrg}' and pc54_datatermino <= '{$dtFimVlrg}' )  ";
  $sAnd         = " and ";
} else if (trim($dtIniVlrg) != "") {

	$sHeaderDtVal = "Validade do Registro: ".$dtIniVlrg;
  $sWhere      .= "{$sAnd} ( pc54_datainicio >= '{$dtIniVlrg}' ) ";
  $sAnd         = " and ";
} else if (trim($dtFimVlrg) != "") {

	$sHeaderDtVal = "Validade do Registro: ".$dtFimVlrg;
  $sWhere .= "{$sAnd} ( pc54_datatermino <= '{$dtFimVlrg}' ) ";
  $sAnd    = " and ";
}

/**
 * Verifica os numeros da solicitação informados no formulario.
 */
if ((trim($oGet->numini) != "") && (trim($oGet->numfim) != "")) {

	$sHeaderNum = "Compilação: ".$oGet->numini." á ".$oGet->numfim;
  $sWhere    .= "{$sAnd} solicita.pc10_numero between '{$oGet->numini}' and '{$oGet->numfim}' ";
  $sAnd       = " and ";
} else if (trim($oGet->numini) != "") {

	$sHeaderNum = "Compilação: ".$oGet->numini;
  $sWhere .= "{$sAnd} ( solicita.pc10_numero >= '{$oGet->numini}' ) ";
  $sAnd    = " and ";
} else if (trim($oGet->numfim) != "") {

	$sHeaderNum = "Compilação: ".$oGet->numfim;
  $sWhere .= "{$sAnd} ( solicita.pc10_numero <= '{$oGet->numfim}' ) ";
  $sAnd    = " and ";
}

/**
 * Verifica os itens selecionados no formulario.
 */
if(trim($oGet->itens) != "") {

	$sHeaderItens = "Itens: ( ".$oGet->itens." )";
  $sWhere      .= "{$sAnd} pc01_codmater in ($oGet->itens) ";
  $sAnd         = " and ";
}

$sWhere .= "{$sAnd} solicita.pc10_solicitacaotipo = 6 ";

/**
 * Cabeçalho do RELATÓRIO POSIÇÃO DO REGISTRO DE PREÇO
 */
$head2 = "RELATÓRIO POSIÇÃO DO REGISTRO DE PREÇO";
$head4 = $sHeaderDtCriacao;
$head5 = $sHeaderDtVal;
$head6 = $sHeaderNum;
$head7 = $sHeaderItens;

$sSql  = "  select solicita.*,";
$sSql .= "         solicitaregistropreco.*,";
$sSql .= "         solicitem.*,";
$sSql .= "         solicitemregistropreco.*,";
$sSql .= "         solicitemunid.*,";
$sSql .= "         matunid.*,";
$sSql .= "         solicitempcmater.*,";
$sSql .= "         pcmater.*,";
$sSql .= "         solicitavinculo.pc53_solicitapai,";
$sSql .= "         solicitavinculo.pc53_solicitafilho,";
$sSql .= "         estimativa.pc10_depto as deptoestimativa,";
$sSql .= "         estimativa.pc10_numero as codigoestimativa,";
$sSql .= "         itemestimativa.pc11_codigo as codigoitemestimativa,";
$sSql .= "         itemestimativavalores.pc57_quantmax as qtdemaxima,";
$sSql .= "         itemestimativavalores.pc57_quantidadeexecedente as qteexcedente";
$sSql .= "    from solicita";
$sSql .= "         inner join solicitavinculo                        on solicita.pc10_numero                    = solicitavinculo.pc53_solicitafilho";
$sSql .= "         inner join solicitaregistropreco                  on solicita.pc10_numero                    = solicitaregistropreco.pc54_solicita";
$sSql .= "         inner join solicitem                              on solicita.pc10_numero                    = solicitem.pc11_numero";
$sSql .= "         inner join solicitemregistropreco                 on solicitem.pc11_codigo                   = solicitemregistropreco.pc57_solicitem";
$sSql .= "         inner join solicitemunid                          on solicitem.pc11_codigo                   = solicitemunid.pc17_codigo";
$sSql .= "         inner join matunid                                on solicitemunid.pc17_unid                 = matunid.m61_codmatunid";
$sSql .= "         inner join solicitempcmater                       on solicitem.pc11_codigo                   = solicitempcmater.pc16_solicitem";
$sSql .= "         inner join pcmater                                on solicitempcmater.pc16_codmater          = pcmater.pc01_codmater";
$sSql .= "         inner join solicitemvinculo vinculoitemestimativa on solicitem.pc11_codigo                   = vinculoitemestimativa.pc55_solicitemfilho";
$sSql .= "         inner join solicitem itemestimativa               on vinculoitemestimativa.pc55_solicitempai = itemestimativa.pc11_codigo";
$sSql .= "         inner join solicita estimativa                    on estimativa.pc10_numero                  = itemestimativa.pc11_numero";
$sSql .= "         inner join solicitemregistropreco itemestimativavalores on itemestimativa.pc11_codigo        = itemestimativavalores.pc57_solicitem";
$sSql .= "   where  {$sWhere}  {$sOrder}, estimativa.pc10_instit asc";

/**
 * Busca a quantidade liquidada do item da solicitação
 * @param  integer $iCodigoItemSolicitacao
 * @return integer
 */
function buscaQuantidadeLiquidada($iCodigoItemSolicitacao) {

  $oDaoSolicitem              = db_utils::getDao("solicitem");
  $sCampos                    = " coalesce(sum(empnotaitem.e72_qtd), 0) as total";
  $sWhere                     = " pc11_codigo  =  {$iCodigoItemSolicitacao} and e72_vlrliq > 0";
  $sSqlQuantidadesLiquidadas  = $oDaoSolicitem->sql_query_empenhado_liquidado(null, $sCampos, null, $sWhere);
  $rsQuantidades              = $oDaoSolicitem->sql_record($sSqlQuantidadesLiquidadas);
  $iTotalLiquidado            = 0;

  if ($oDaoSolicitem->numrows == 1) {
    $iTotalLiquidado = db_utils::fieldsMemory($rsQuantidades, 0)->total;
  }

  return $iTotalLiquidado;
}

/**
 * Busca a quantidade reservada do item da solicitação
 * @param  integer $iCodigoItemSolicitacao
 * @return integer
 */
function buscaQuantidadeReservada ($iCodigoItemSolicitacao) {

  $oDaoSolicitem              = db_utils::getDao("solicitem");
  $sCampos                    = " coalesce(sum(pc13_quant), 0) as total";
  $sWhere                     = " pc11_codigo  =  {$iCodigoItemSolicitacao}";
  $sSqlQuantidadesReservadas  = $oDaoSolicitem->sql_query_dot(null, $sCampos, null, $sWhere);
  $rsQuantidades              = $oDaoSolicitem->sql_record($sSqlQuantidadesReservadas);
  $iTotalReservado            = 0;

  if ($oDaoSolicitem->numrows == 1) {
    $iTotalReservado = db_utils::fieldsMemory($rsQuantidades, 0)->total;
  }
  return $iTotalReservado;
}

$rsSql   = db_query($sSql);
$iRsSql  = pg_num_rows($rsSql);

if ($iRsSql == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 8);

$lPreenchimento      = 0;
$iAlt                = 4;

$oDadosPosRegPreco = array();
$aDadosSolicita    = array();
$oResumoFinal      = new stdClass();

$oDadosPosRegPreco              = new stdClass();
$oDadosPosRegPreco->iRegistros  = 0;
$oDadosPosRegPreco->iSolicitada = 0;
$oDadosPosRegPreco->iReservada  = 0;
$oDadosPosRegPreco->iEmpenhada  = 0;
$oDadosPosRegPreco->iLiquidada  = 0;
$oDadosPosRegPreco->iSolicitar  = 0;
$oDadosPosRegPreco->iEmpenhar   = 0;

$lTotalGeral     = true;
$lUltimoControle = null;

/**
 * Agrupa os registros do record set retornado pelo sql
 * Vincula item da estimativas a Solicitação, agrupando por instituição e departamento
 */

for ( $iInd = 0; $iInd  < $iRsSql; $iInd++ ) {

  $oSolicita                        = db_utils::fieldsMemory($rsSql, $iInd);
  $iCodigoCompilacao                = $oSolicita->pc11_numero;
  $iCodigoEstimativa                = $oSolicita->codigoestimativa;
  $iCodigoItemEstimativa            = $oSolicita->codigoitemestimativa;

  $oCompilacao                      = new compilacaoRegistroPreco($iCodigoCompilacao);
  $oSolicita->oDadosFornecedor      = $oCompilacao->getFornecedorItem($oSolicita->pc01_codmater, $oSolicita->pc11_codigo);
  $oSolicita->empenhada             = $oCompilacao->getValorEmpenhadoItem($oSolicita->pc11_codigo);
  $oSolicita->solicitada            = $oCompilacao->getValorSolicitadoItem($oSolicita->pc11_codigo);

  $lControlaValor                   = ($oCompilacao->getFormaDeControle() == aberturaRegistroPreco::CONTROLA_VALOR);

  $oStdItemEstimativa               = new stdClass();
  $oStdItemEstimativa->iSeq         = $oSolicita->pc11_seq;
  $oStdItemEstimativa->iCodItem     = $oSolicita->pc01_codmater;
  $oStdItemEstimativa->sDescrItem   = $oSolicita->pc01_descrmater;
  $oStdItemEstimativa->sCompl       = $oSolicita->pc11_resum;
  $oStdItemEstimativa->sUnidade     = $oSolicita->m61_descr;
  $oStdItemEstimativa->sFornecedor  = $oSolicita->oDadosFornecedor->vencedor;
  $oStdItemEstimativa->iEmpenhada   = $oSolicita->empenhada;
  $oStdItemEstimativa->iLiquidada   = buscaQuantidadeLiquidada($oSolicita->pc11_codigo);
  $oStdItemEstimativa->iSolicitada  = $oSolicita->solicitada;
  $oStdItemEstimativa->iReservada   = buscaQuantidadeReservada($oSolicita->pc11_codigo);
  $oStdItemEstimativa->iSolicitar   = ($oSolicita->pc57_quantmax - $oSolicita->solicitada);
  $oStdItemEstimativa->iEmpenhar    = ($oSolicita->solicitada - $oSolicita->empenhada);
  $oStdItemEstimativa->nQuantMin    = (empty($oSolicita->pc57_quantmin)                   ? '0' : $oSolicita->pc57_quantmin);
  $oStdItemEstimativa->nQuantMax    = (empty($oSolicita->pc57_quantmax)                   ? '0' : $oSolicita->pc57_quantmax);
  $oStdItemEstimativa->nVlrUnitario = (empty($oSolicita->oDadosFornecedor->valorunitario) ? '0' : $oSolicita->oDadosFornecedor->valorunitario);

  $oEstimativa                      = new estimativaRegistroPreco($iCodigoEstimativa);
  $iDepartamentoEstimativa          = $oEstimativa->getCodigoDepartamento();
  $iInstituicaoEstimativa           = $oEstimativa->getCodigoInstituicao();

  $oItem                            = $oEstimativa->getItemByCodigo($iCodigoItemEstimativa);
  

  if (!is_object($oItem)) {

    $sMsgErro = urlencode('Não foram encontrados itens para a estimativa de código: ' . $iCodigoItemEstimativa . '.');
    db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsgErro);
  }
  $oStdItemEstimativa->iSolicitada  = $oSolicita->solicitada;
  $oStdItemEstimativa->iReservada   = buscaQuantidadeReservada($oItem->getCodigoItemSolicitacao());
  $oStdItemEstimativa->iEmpenhada   = $oSolicita->empenhada;
  $oStdItemEstimativa->iLiquidada   = buscaQuantidadeLiquidada($oItem->getCodigoItemSolicitacao());
  $oStdItemEstimativa->iSolicitar   = $oSolicita->pc57_quantmax - $oStdItemEstimativa->iSolicitada;

  /**
   * Verifica se controla o registro de preço por valor e troca a quantidade pelo valor
   */
  if ($lControlaValor) {
    $oStdItemEstimativa->nVlrUnitario = $oSolicita->pc11_vlrun;
    $oStdItemEstimativa->iSolicitar   = $oStdItemEstimativa->nVlrUnitario - $oStdItemEstimativa->iSolicitada;
  }

  $oStdItemEstimativa->iEmpenhar = ($oStdItemEstimativa->iSolicitada - $oStdItemEstimativa->iEmpenhada);

  /**
   * Atualiza totalizador de registros global
   */
  $oDadosPosRegPreco->iRegistros  ++;
  $oDadosPosRegPreco->iSolicitada += $oStdItemEstimativa->iSolicitada;
  $oDadosPosRegPreco->iReservada  += $oStdItemEstimativa->iReservada;
  $oDadosPosRegPreco->iEmpenhada  += $oStdItemEstimativa->iEmpenhada;
  $oDadosPosRegPreco->iLiquidada  += $oStdItemEstimativa->iLiquidada;
  $oDadosPosRegPreco->iSolicitar  += $oStdItemEstimativa->iSolicitar;
  $oDadosPosRegPreco->iEmpenhar   += $oStdItemEstimativa->iEmpenhar;

  /**
   * Adiciona Detalhes da Compilação, nos Dados da posição registro de preço
   */

  if (empty($oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao])) {

    $oStdDetalhesCompilacao                           = new stdClass();
    $oStdDetalhesCompilacao->iAbertura                = $oCompilacao->getCodigoAbertura();
    $oStdDetalhesCompilacao->iCompilacao              = $oCompilacao->getCodigo();
    $oStdDetalhesCompilacao->aInstituicoesEstimativas = array();

    $oStdDetalhesCompilacao->iSolicitada              = 0;
    $oStdDetalhesCompilacao->iReservada               = 0;
    $oStdDetalhesCompilacao->iEmpenhada               = 0;
    $oStdDetalhesCompilacao->iLiquidada               = 0;
    $oStdDetalhesCompilacao->iSolicitar               = 0;
    $oStdDetalhesCompilacao->iEmpenhar                = 0;
    $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao] = $oStdDetalhesCompilacao;
  }

  $oCompilacao               = $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao];
  $oCompilacao->iSolicitada += $oStdItemEstimativa->iSolicitada;
  $oCompilacao->iReservada  += $oStdItemEstimativa->iReservada;
  $oCompilacao->iEmpenhada  += $oStdItemEstimativa->iEmpenhada;
  $oCompilacao->iLiquidada  += $oStdItemEstimativa->iLiquidada;
  $oCompilacao->iSolicitar  += $oStdItemEstimativa->iSolicitar;
  $oCompilacao->iEmpenhar   += $oStdItemEstimativa->iEmpenhar;

  $oCompilacao->lControlaValor = $lControlaValor;

  /**
   * Se não houver Instituição setada para a compilação agrupar as estimativas,
   * então ela será criada neste momento
   */
  if (empty($oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa])) {

    $oInstituicao                    = new Instituicao($iInstituicaoEstimativa);
    $oStdInstituicao                 = new stdClass();
    $oStdInstituicao->iCodigo        = $oInstituicao->getSequencial();
    $oStdInstituicao->sNome          = $oInstituicao->getDescricao();
    $oStdInstituicao->iRegistros     = 0;
    $oStdInstituicao->iSolicitada    = 0;
    $oStdInstituicao->iReservada     = 0;
    $oStdInstituicao->iEmpenhada     = 0;
    $oStdInstituicao->iLiquidada     = 0;
    $oStdInstituicao->iSolicitar     = 0;
    $oStdInstituicao->iEmpenhar      = 0;
    $oStdInstituicao->aDepartamentos = array();
    $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa] = $oStdInstituicao;
    unset($oInstituicao);
  }

  /**
   *  Atualiza totalizadores da Instituição
   */
  $oAgrupamentoInstituicao = $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa];
  $oAgrupamentoInstituicao->iRegistros  ++;
  $oAgrupamentoInstituicao->iSolicitada += $oStdItemEstimativa->iSolicitada;
  $oAgrupamentoInstituicao->iReservada  += $oStdItemEstimativa->iReservada;
  $oAgrupamentoInstituicao->iEmpenhada  += $oStdItemEstimativa->iEmpenhada;
  $oAgrupamentoInstituicao->iLiquidada  += $oStdItemEstimativa->iLiquidada;
  $oAgrupamentoInstituicao->iSolicitar  += $oStdItemEstimativa->iSolicitar;
  $oAgrupamentoInstituicao->iEmpenhar   += $oStdItemEstimativa->iEmpenhar;
  $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa] = $oAgrupamentoInstituicao;

  /**
   * Se não houver Departamento setada para a Instituição agrupar,
   * então ele será criado neste momento
   **/
  if (empty($oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa])) {

    $oDepartamento                  = new DBDepartamento($iDepartamentoEstimativa);
    $oStdDepartamento               = new stdClass();
    $oStdDepartamento->iCodigo      = $oDepartamento->getCodigo();
    $oStdDepartamento->sNome        = $oDepartamento->getNomeDepartamento();
    $oStdDepartamento->iRegistros   = 0;
    $oStdDepartamento->iSolicitada  = 0;
    $oStdDepartamento->iReservada   = 0;
    $oStdDepartamento->iEmpenhada   = 0;
    $oStdDepartamento->iLiquidada   = 0;
    $oStdDepartamento->iSolicitar   = 0;
    $oStdDepartamento->iEmpenhar    = 0;
    $oStdDepartamento->aEstimativas = array();
    $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa] = $oStdDepartamento;
    unset($oDepartamento);
  }

  /**
   *  Atualiza totalizadores do Departamento
   **/
  $oAgrupamentoDepartamento = $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa];
  $oAgrupamentoDepartamento->iRegistros  ++;
  $oAgrupamentoDepartamento->iSolicitada += $oStdItemEstimativa->iSolicitada;
  $oAgrupamentoDepartamento->iReservada  += $oStdItemEstimativa->iReservada;
  $oAgrupamentoDepartamento->iEmpenhada  += $oStdItemEstimativa->iEmpenhada;
  $oAgrupamentoDepartamento->iLiquidada  += $oStdItemEstimativa->iLiquidada;
  $oAgrupamentoDepartamento->iSolicitar  += $oStdItemEstimativa->iSolicitar;
  $oAgrupamentoDepartamento->iEmpenhar   += $oStdItemEstimativa->iEmpenhar;
  $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa]= $oAgrupamentoDepartamento;

  /**
   * Se não houver Estimativa setada para o Departamento agrupar,
   * então ela será criada neste momento
   **/
  if (empty($oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa]->aEstimativas[$iCodigoEstimativa])) {

    $oStdEstimativa              = new stdClass();
    $oStdEstimativa->iCodigo     = $iCodigoEstimativa;
    $oStdEstimativa->iRegistros  = 0;
    $oStdEstimativa->iSolicitada = 0;
    $oStdEstimativa->iReservada  = 0;
    $oStdEstimativa->iEmpenhada  = 0;
    $oStdEstimativa->iLiquidada  = 0;
    $oStdEstimativa->iSolicitar  = 0;
    $oStdEstimativa->iEmpenhar   = 0;
    $oStdEstimativa->aItens      = array();
    $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa]->aEstimativas[$iCodigoEstimativa] = $oStdEstimativa;
  }

  /**
   *  Atualiza totalizadores da Estimativa
   **/
  $oAgrupamentoEstimativa = $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa]->aEstimativas[$iCodigoEstimativa];
  $oAgrupamentoEstimativa->iRegistros  ++;
  $oAgrupamentoEstimativa->iSolicitada += $oStdItemEstimativa->iSolicitada;
  $oAgrupamentoEstimativa->iReservada  += $oStdItemEstimativa->iReservada;
  $oAgrupamentoEstimativa->iEmpenhada  += $oStdItemEstimativa->iEmpenhada;
  $oAgrupamentoEstimativa->iLiquidada  += $oStdItemEstimativa->iLiquidada;
  $oAgrupamentoEstimativa->iSolicitar  += $oStdItemEstimativa->iSolicitar;
  $oAgrupamentoEstimativa->iEmpenhar   += $oStdItemEstimativa->iEmpenhar;
  $oAgrupamentoEstimativa->aItens[]     = $oStdItemEstimativa;
  $oDadosPosRegPreco->aSolicitacoes[$iCodigoCompilacao]->aInstituicoesEstimativas[$iInstituicaoEstimativa]->aDepartamentos[$iDepartamentoEstimativa]->aEstimativas[$iCodigoEstimativa] = $oAgrupamentoEstimativa;

  if ($lUltimoControle != null && $lUltimoControle != $lControlaValor) {
    $lTotalGeral = false;
  }

  $lUltimoControle = $lTotalGeral;
}

$lPrimeiraPagina = true;

foreach ($oDadosPosRegPreco->aSolicitacoes as $iCodigoCompilacao => $oCompilacao ) {

  imprimeCabecalho($oPdf, $iAlt, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $lPrimeiraPagina, $oCompilacao->lControlaValor);

  $lPrimeiraPagina = false;

  foreach ($oCompilacao->aInstituicoesEstimativas as $iCodigoInstituicao => $oInstituicao) {

    verificaQuebraCabecalho($oPdf, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $iAlt, $oCompilacao->lControlaValor);
    $oPdf->setfont('arial', 'b', 6);
    $sInstituicao = " {$oInstituicao->iCodigo} - {$oInstituicao->sNome}";
    $oPdf->cell( 30, $iAlt, "INSTITUIÇÃO:" , "TB", 0, "L", 1 );
    $oPdf->setfont('arial', '', 6);
    $oPdf->cell( 249, $iAlt, $sInstituicao  , "TB", 1, "L", 1 );

    foreach ($oInstituicao->aDepartamentos as $iCodigoDepartamento => $oDepartamento) {

      /**
       *  Embora o código esteja preparado para imprimir e totalizar várias estimativas por departamento,
       *  pela regra de negócio mais atual, ficou definido que um departamento terá somente uma estimativa
       *  por registro de preço.
       *  Neste caso, para seguir a regra, pegamos a primeira estimativa presente no array $aEstimativa do
       *  departamento $oDepartamento
       **/


      $oPrimeiraEstimativa                             = array_pop($oDepartamento->aEstimativas);
      $iCodigoEstimativa                               = $oPrimeiraEstimativa->iCodigo;
      $oDepartamento->aEstimativas[$iCodigoEstimativa] = $oPrimeiraEstimativa;
      verificaQuebraCabecalho($oPdf, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $iAlt, $oCompilacao->lControlaValor);

      $oPdf->setfont('arial', 'b', 6);
      $sDepartamento = "{$oDepartamento->iCodigo} - {$oDepartamento->sNome} ";
      $oPdf->cell( 30, $iAlt, "DEPARTAMENTO:" , "TB", 0, "L", 1 );
      $oPdf->setfont('arial', '', 6);
      $oPdf->cell( 67, $iAlt, $sDepartamento  , "TB", 0, "L", 1 );

      $oPdf->setfont('arial', 'b', 6);
      $oPdf->cell( 20, $iAlt, "ESTIMATIVA:"  , "TB", 0, "L", 1 );
      $oPdf->setfont('arial', '', 6);
      $oPdf->cell( 162, $iAlt, $iCodigoEstimativa  , "TB", 1, "L", 1 );


      /**
       * Percorre cada uma das estimativas do departamento, vinculada a compilação
       **/
      foreach ($oDepartamento->aEstimativas as $iCodigoEstimativa => $oEstimativa) {

        $lPreenchimento = 1;

        /**
         * Percorre cada um dos itens da estimativa e imprime
         **/
        foreach($oEstimativa->aItens as $oItemEstimativa) {

          verificaQuebraCabecalho($oPdf, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $iAlt, $oCompilacao->lControlaValor);
          $oPdf->setfont('arial', '', 6);
          $lPreenchimento = $lPreenchimento == 0 ? 1 : 0;
          $oPdf->cell(15, $iAlt, $oItemEstimativa->iSeq        , 0, 0, "C", $lPreenchimento);
          $oPdf->cell(15, $iAlt, $oItemEstimativa->iCodItem    , 0, 0, "C", $lPreenchimento);
          $oPdf->cell(28, $iAlt, substr($oItemEstimativa->sDescrItem, 0, 20), 0, 0, "L", $lPreenchimento);
          $oPdf->cell(39, $iAlt, str_replace("\\n", "\n",substr(trim($oItemEstimativa->sCompl), 0, 20)) , 0, 0, "L", $lPreenchimento);
          $oPdf->cell(16, $iAlt, $oItemEstimativa->sUnidade    , 0, 0, "C", $lPreenchimento);
          $oPdf->cell(16, $iAlt, db_formatar($oItemEstimativa->nVlrUnitario, 'v', " ", $casadec), 0, 0, "R", $lPreenchimento);
          $oPdf->cell(($oCompilacao->lControlaValor ? 50 : 32), $iAlt, substr($oItemEstimativa->sFornecedor, 0, ($oCompilacao->lControlaValor ? 35 : 20)), 0, 0, "L", $lPreenchimento);

          if (!$oCompilacao->lControlaValor) {
            $oPdf->cell(18, $iAlt, $oItemEstimativa->nQuantMax   , 0, 0, "R", $lPreenchimento);
          }

          $oPdf->cell(25, $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oItemEstimativa->iSolicitada, 'v', " ", $casadec) : $oItemEstimativa->iSolicitada) , 0, 0, "R", $lPreenchimento);
          $oPdf->cell(25, $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oItemEstimativa->iEmpenhada, 'v', " ", $casadec) : $oItemEstimativa->iEmpenhada)  , 0, 0, "R", $lPreenchimento);
          $oPdf->cell(25, $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oItemEstimativa->iSolicitar, 'v', " ", $casadec) : $oItemEstimativa->iSolicitar)  , 0, 0, "R", $lPreenchimento);
          $oPdf->cell(25, $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oItemEstimativa->iEmpenhar, 'v', " ", $casadec) : ($oItemEstimativa->iEmpenhar ? $oItemEstimativa->iEmpenhar : '0'))   , 0, 1, "R", $lPreenchimento);
        }
      }

      verificaQuebraCabecalho($oPdf, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $iAlt, $oCompilacao->lControlaValor);

      /**
       * Imprime total do departamento
       */
      $oPdf->setfont('arial','b',7);
      $oPdf->cell(279, 1,    ''                           , "T", 1, "L", 0);
      $oPdf->cell(113, $iAlt, 'TOTAL DO DEPARTAMENTO:'    ,   0, 0, "R", 0);
      $oPdf->cell(16,  $iAlt, ""                          ,   0, 0, "R", 0);
      $oPdf->cell(32,  $iAlt, $oDepartamento->iRegistros  ,   0, 0, "R", 0);
      $oPdf->cell(18,  $iAlt, ''                          ,   0, 0, "R", 0);
      $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oDepartamento->iSolicitada, 'v', " ", $casadec) : $oDepartamento->iSolicitada) ,   0, 0, "R", 0);
      $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oDepartamento->iEmpenhada, 'v', " ", $casadec) : $oDepartamento->iEmpenhada)  ,   0, 0, "R", 0);
      $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oDepartamento->iSolicitar, 'v', " ", $casadec) : $oDepartamento->iSolicitar)  ,   0, 0, "R", 0);
      $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oDepartamento->iEmpenhar, 'v', " ", $casadec) : ($oDepartamento->iEmpenhar ? $oDepartamento->iEmpenhar : '0'))   ,   0, 1, "R", 0);
      $oPdf->ln(2);

    }

    verificaQuebraCabecalho($oPdf, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $iAlt, $oCompilacao->lControlaValor);

    /**
     * Imprime total da instituição
     */
    $oPdf->setfont('arial','b',8);
    $oPdf->cell(279, 1,    ''                          , "T", 1, "L", 0);
    $oPdf->cell(113, $iAlt, 'TOTAL DA INSTITUIÇÃO:'     ,   0, 0, "R", 0);
    $oPdf->cell(16,  $iAlt, ""                          ,   0, 0, "R", 0);
    $oPdf->cell(32,  $iAlt, $oInstituicao->iRegistros   ,   0, 0, "R", 0);
    $oPdf->cell(18,  $iAlt, ''                          ,   0, 0, "R", 0);
    $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oInstituicao->iSolicitada, 'v', " ", $casadec) : $oInstituicao->iSolicitada)  ,   0, 0, "R", 0);
    $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oInstituicao->iEmpenhada, 'v', " ", $casadec) : $oInstituicao->iEmpenhada)   ,   0, 0, "R", 0);
    $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oInstituicao->iSolicitar, 'v', " ", $casadec) : $oInstituicao->iSolicitar)   ,   0, 0, "R", 0);
    $oPdf->cell(25,  $iAlt, ($oCompilacao->lControlaValor ? db_formatar($oInstituicao->iEmpenhar, 'v', " ", $casadec) : ($oInstituicao->iEmpenhar ? $oInstituicao->iEmpenhar : '0'))    ,   0, 1, "R", 0);
    $oPdf->ln(5);

  }
}

verificaQuebraCabecalho($oPdf, $oCompilacao->iAbertura, $oCompilacao->iCompilacao, $iAlt, $oCompilacao->lControlaValor);

if ($lTotalGeral) {

  $oPdf->setfont('arial','b',8);
  $oPdf->cell(279, 1,    ''                                , "T", 1, "L", 0);
  $oPdf->cell(113, $iAlt, 'TOTAL GERAL:'                   ,   0, 0, "R", 0);
  $oPdf->cell(16,  $iAlt, ""                               ,   0, 0, "R", 0);
  $oPdf->cell(32,  $iAlt, $oDadosPosRegPreco->iRegistros   ,   0, 0, "R", 0);
  $oPdf->cell(18,  $iAlt, ''                               ,   0, 0, "R", 0);
  $oPdf->cell(25,  $iAlt, $oDadosPosRegPreco->iSolicitada  ,   0, 0, "R", 0);
  $oPdf->cell(25,  $iAlt, $oDadosPosRegPreco->iEmpenhada   ,   0, 0, "R", 0);
  $oPdf->cell(25,  $iAlt, $oDadosPosRegPreco->iSolicitar   ,   0, 0, "R", 0);
  $oPdf->cell(25,  $iAlt, $oDadosPosRegPreco->iEmpenhar    ,   0, 1, "R", 0);
}

/**
 * Inicio dos procedimentos para impressão do Resumo
 */
$oPdf->addpage("L");
$oPdf->setfont('arial', 'b', 8);
$oPdf->cell(279, $iAlt, "RESUMO"  , '', 1, "C", 1);

foreach ($oDadosPosRegPreco->aSolicitacoes as $iCodigoCompilacao => $oCompilacao ) {

  verificaQuebraPagina($oPdf);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, $iAlt, "Abertura:"  , 'TB', 0, "C", 1);
  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(28, $iAlt, $oCompilacao->iAbertura, 'TB', 0, "L", 1);
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, $iAlt, "Compilação:", 'TB', 0, "C", 1);
  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(211, $iAlt, $iCodigoCompilacao , 'TB', 1, "L", 1);

  foreach ($oCompilacao->aInstituicoesEstimativas as $iCodigoInstituicao => $oInstituicao) {

    /**
     * Imprime estimativa do departamento (única)
     */
    foreach ($oInstituicao->aDepartamentos as $iCodigoDepartamento => $oDepartamento) {

      verificaQuebraPagina($oPdf);
      $oPrimeiraEstimativa                              = array_pop($oDepartamento->aEstimativas);
      $iCodigoEstimativa                               = $oPrimeiraEstimativa->iCodigo;
      $oDepartamento->aEstimativas[$iCodigoEstimativa] = $oPrimeiraEstimativa;

      $oPdf->setfont('arial', 'b', 6);
      $sDepartamento = "{$oDepartamento->iCodigo} - {$oDepartamento->sNome} ";
      $oPdf->cell( 30, $iAlt, "DEPARTAMENTO:" , "", 0, "L", 0 );
      $oPdf->setfont('arial', '', 6);
      $oPdf->cell( 67, $iAlt, $sDepartamento  , "", 0, "L", 0 );

      $oPdf->setfont('arial', 'b', 6);
      $oPdf->cell( 20, $iAlt, "ESTIMATIVA:"  , "", 0, "L", 0 );
      $oPdf->setfont('arial', '', 6);
      $oPdf->cell( 10, $iAlt, $iCodigoEstimativa  , "", 0, "L", 0 );

      $aSolicitacoes = buscaSolicitacoesVinculadas($iCodigoEstimativa);
      $sSolicitacoes = implode(", ", $aSolicitacoes);
      $oPdf->setfont('arial', 'b', 6);
      $oPdf->cell( 35, $iAlt, "SOLICITAÇÕES VINCULADAS:"  , "", 0, "L", 0 );
      $oPdf->setfont('arial', '', 6);
      $oPdf->cell( 10, $iAlt, $sSolicitacoes  , "", 1, "L", 0 );
    }
  }
}

$oPdf->Output();

/**
 * Função que verifica quebra de página no Pdf
 * @param    PDF      $oPdf
 * @return   boolean
 **/
function verificaQuebraPagina($oPdf) {

  if ( $oPdf->gety() > $oPdf->h - 40) {

    $oPdf->addpage("L");
    return true;
  }
  return false;
}

/**
 * Função que imprime cabecalho quando necessário
 * @param PDF     $oPdf
 * @param integer $iAbertura
 * @param integer $iCompilacao
 * @param integer $iAlt
 */
function verificaQuebraCabecalho($oPdf, $iAbertura, $iCompilacao, $iAlt, $lControlaValor) {

  if (verificaQuebraPagina($oPdf)) {
    imprimeCabecalho($oPdf, $iAlt, $iAbertura, $iCompilacao, false, $lControlaValor);
  }
}

/**
 * Busca Solicitacoes vinculada a uma estimativa, retornando um array com os respectivos códigos
 **/
function buscaSolicitacoesVinculadas ($iEstimativa) {

  $sSqlResumo  = "select distinct solicitacao.pc10_numero                                                                            ";
  $sSqlResumo .= "from   solicita as estimativa                                                                                      ";
  $sSqlResumo .= "	inner join solicitavinculo 	as vinculoaberturaestimativa                                                         ";
  $sSqlResumo .= "							   	on vinculoaberturaestimativa.pc53_solicitafilho  = estimativa.pc10_numero                        ";
  $sSqlResumo .= "  inner join solicitavinculo 	as vinculooaberturacompilacao                                                        ";
  $sSqlResumo .= "   								on vinculooaberturacompilacao.pc53_solicitapai	 = vinculoaberturaestimativa.pc53_solicitapai    ";
  $sSqlResumo .= "	inner join solicitavinculo  as vinculocompilacaosolicitacao                                                      ";
  $sSqlResumo .= "								on vinculocompilacaosolicitacao.pc53_solicitapai = vinculooaberturacompilacao.pc53_solicitafilho   ";
  $sSqlResumo .= "	inner join solicita as solicitacao                                                                               ";
  $sSqlResumo .= "							    on solicitacao.pc10_numero = vinculocompilacaosolicitacao.pc53_solicitafilho                     ";
  $sSqlResumo .= "					    	 and solicitacao.pc10_depto = estimativa.pc10_depto                                                ";
  $sSqlResumo .= "  where estimativa.pc10_numero = {$iEstimativa}                                                                    ";

  $rsSql   = db_query($sSqlResumo);
  $iRsSql  = pg_num_rows($rsSql);

  if ($rsSql == false) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao executar a query.');
  }

  $aSolicitacoesVinculadas = array();

  for ($iSolicitacao = 0; $iSolicitacao < $iRsSql; $iSolicitacao++) {

    $oSolicitacao = db_utils::fieldsMemory($rsSql, $iSolicitacao);
    $aSolicitacoesVinculadas[] = $oSolicitacao->pc10_numero;
  }

  return $aSolicitacoesVinculadas;
}

/**
 * Imprime cabeçalho do relatorio
 **/
function imprimeCabecalho(&$oPdf, $iAlt, $iAbertura, $iCompilacao, $lQuebraPagina = false, $lControlaValor) {


  if ($lQuebraPagina) {
    $oPdf->addpage("L");
  }

  $oPdf->setfont('arial', 'b', 8);

  $oPdf->cell(20, $iAlt, "Abertura:"  , 'TB', 0, "C", 1);

  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(28, $iAlt, $iAbertura   , 'TB', 0, "L", 1);

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, $iAlt, "Compilação:", 'TB', 0, "C", 1);

  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(($lControlaValor ? 111 : 93), $iAlt, $iCompilacao , 'RTB', 0, "L", 1);

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(($lControlaValor ? 50 : 68), $iAlt, ($lControlaValor ? "Valor" : "Quantidade") , 1, 0, "C", 1);
  $oPdf->cell(50, $iAlt, "Saldos"     , "TB", 1, "C", 1);

  $oPdf->cell(15, $iAlt, "Seq."       , "TB", 0, "C", 1);
  $oPdf->cell(15, $iAlt, "Item"       , 1, 0, "C", 1);
  $oPdf->cell(28, $iAlt, "Descrição"  , 1, 0, "C", 1);
  $oPdf->cell(39, $iAlt, "Complemento", 1, 0, "C", 1);
  $oPdf->cell(16, $iAlt, "Unidade"    , 1, 0, "C", 1);
  $oPdf->cell(16, $iAlt, ($lControlaValor ? "Valor" : "Vlr Uni.")   , 1, 0, "C", 1);
  $oPdf->cell(($lControlaValor ? 50 : 32), $iAlt, "Fornecedor" , 1, 0, "C", 1);

  if (!$lControlaValor) {
    $oPdf->cell(18, $iAlt, "Máx"        , 1, 0, "C", 1);
  }

  $oPdf->cell(25, $iAlt, ($lControlaValor ? "Solicitado" : "Solicitada") , 1, 0, "C", 1);
  $oPdf->cell(25, $iAlt, ($lControlaValor ? "Empenhado" : "Empenhada")  , 1, 0, "C", 1);
  $oPdf->cell(25, $iAlt, "Solicitar"  , 1, 0, "C", 1);
  $oPdf->cell(25, $iAlt, "Empenhar"   , "TB", 1, "C", 1);

}
?>
