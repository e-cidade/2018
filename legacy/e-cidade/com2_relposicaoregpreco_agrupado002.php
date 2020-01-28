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
  $casadec = db_utils::fieldsMemory($rsCasasDecimais, 0)->casadec;
}

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

$sSql  = "  select solicita.*,                                                                                                     ";
$sSql .= "         solicitaregistropreco.*,                                                                                        ";
$sSql .= "         solicitem.*,                                                                                                    ";
$sSql .= "         solicitemregistropreco.*,                                                                                       ";
$sSql .= "         solicitemunid.*,                                                                                                ";
$sSql .= "         matunid.*,                                                                                                      ";
$sSql .= "         solicitempcmater.*,                                                                                             ";
$sSql .= "         pcmater.*                                                                                                       ";
$sSql .= "    from solicita                                                                                                        ";
$sSql .= "         inner join solicitaregistropreco  on solicita.pc10_numero           = solicitaregistropreco.pc54_solicita       ";
$sSql .= "         inner join solicitem              on solicita.pc10_numero           = solicitem.pc11_numero                     ";
$sSql .= "         inner join solicitemregistropreco on solicitem.pc11_codigo          = solicitemregistropreco.pc57_solicitem     ";
$sSql .= "         inner join solicitemunid          on solicitem.pc11_codigo          = solicitemunid.pc17_codigo                 ";
$sSql .= "         inner join matunid                on solicitemunid.pc17_unid        = matunid.m61_codmatunid                    ";
$sSql .= "         inner join solicitempcmater       on solicitem.pc11_codigo          = solicitempcmater.pc16_solicitem           ";
$sSql .= "         inner join pcmater                on solicitempcmater.pc16_codmater = pcmater.pc01_codmater                     ";
$sSql .= "   where {$sWhere} {$sOrder}";


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

$lPreenchimento    = 0;
$alt      = 4;

$aDadosPosRegPreco = array();
$aDadosSolicita    = array();

$lTotalGeral     = true;
$lUltimoControle = null;

/**
 * Agrupa os registros do record set retornado pelo sql
 */
for ( $iInd = 0; $iInd  < $iRsSql; $iInd++ ) {

  $oSolicita   = db_utils::fieldsMemory($rsSql, $iInd);
  $oCompilacao = new compilacaoRegistroPreco($oSolicita->pc11_numero);
  $oLicitacao  = $oCompilacao->getLicitacao();

  $sLicitacao = "";
  if ($oLicitacao) {

    $sLicitacao  = "{$oLicitacao->getEdital()} / {$oLicitacao->getAno()} - ";
    $sLicitacao .= "{$oLicitacao->getModalidade()->getDescricao()}";
  }

  $oSolicita->oDadosFornecedor   = $oCompilacao->getFornecedorItem($oSolicita->pc01_codmater, $oSolicita->pc11_codigo);

  $oSolicita->empenhada          = $oCompilacao->getValorEmpenhadoItem($oSolicita->pc11_codigo);
  $oSolicita->solicitada         = $oCompilacao->getValorSolicitadoItem($oSolicita->pc11_codigo);

  $oDadosEstimativa                 = new stdClass();
  $oDadosEstimativa->iSeq           = $oSolicita->pc11_seq;
  $oDadosEstimativa->iCodItem       = $oSolicita->pc01_codmater;
  $oDadosEstimativa->sDescrItem     = $oSolicita->pc01_descrmater;
  $oDadosEstimativa->sCompl         = $oSolicita->pc11_resum;
  $oDadosEstimativa->sUnidade       = $oSolicita->m61_descr;
  $oDadosEstimativa->sFornecedor    = $oSolicita->oDadosFornecedor->vencedor;
  $oDadosEstimativa->iEmpenhada     = $oSolicita->empenhada;
  $oDadosEstimativa->iSolicitada    = $oSolicita->solicitada;
  $oDadosEstimativa->lControlaValor = ($oCompilacao->getFormaDeControle() == aberturaRegistroPreco::CONTROLA_VALOR);

  $oDadosEstimativa->nSolicitar    = ($oSolicita->pc57_quantmax - $oSolicita->solicitada);
  $oDadosEstimativa->nEmpenhar     = ($oSolicita->solicitada - $oSolicita->empenhada);

  $nQuantMin                     = (empty($oSolicita->pc57_quantmin)                   ? '0' : $oSolicita->pc57_quantmin);
  $nQuantMax                     = (empty($oSolicita->pc57_quantmax)                   ? '0' : $oSolicita->pc57_quantmax);
  $nVlrUnitario                  = (empty($oSolicita->oDadosFornecedor->valorunitario) ? '0' : $oSolicita->oDadosFornecedor->valorunitario);

  /**
   * Verifica se controla o registro de preço por valor e altera o conteúdo das colunas
   */
  if ($oDadosEstimativa->lControlaValor) {

    $oDadosEstimativa->nSolicitar = ($oSolicita->pc11_vlrun - $oSolicita->solicitada);
    $nVlrUnitario = $oSolicita->pc11_vlrun;
  }

  $aDadosPosRegPreco[$oSolicita->pc10_numero]['oAbertura']      = $oCompilacao->getCodigoAbertura();
  $aDadosPosRegPreco[$oSolicita->pc10_numero]['oCompilacao']    = $oSolicita->pc11_numero;
  $aDadosPosRegPreco[$oSolicita->pc10_numero]['lControlaValor'] = $oDadosEstimativa->lControlaValor;
  $aDadosPosRegPreco[$oSolicita->pc10_numero]['sLicitacao']     = $sLicitacao;

  /**
   * Se escolher quebra por departamento, desmembramos as compilações nas suas estimativas
   * e agrupamos as estimativas por departamentos
   */

    if ( !isset($aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]) ) {

      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['oDados']          = $oDadosEstimativa;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalQntMin']    = $nQuantMin;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalQntMax']    = $nQuantMax;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalVlrUnid']   = $nVlrUnitario;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalQntSolic']  = $oSolicita->pc11_quant;
    } else {

      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalQntMin']   += $nQuantMin;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalQntMax']   += $nQuantMax;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalVlrUnid']  += $nVlrUnitario;
      $aDadosPosRegPreco[$oSolicita->pc10_numero][$oSolicita->pc11_numero][$oSolicita->pc11_codigo]['nTotalQntSolic'] += $oSolicita->pc11_quant;
    }

  if ($lUltimoControle != null && $lUltimoControle != $oDadosEstimativa->lControlaValor) {
    $lTotalGeral = false;
  }

  $lUltimoControle = $oDadosEstimativa->lControlaValor;
}

$nTotalGeralRegistros   = 0;
$nTotalGeralSolicitada  = 0;
$nTotalGeralEmpenhada   = 0;
$nTotalGeralSolicitar   = 0;
$nTotalGeralEmpenhar    = 0;

/**
 * Percore o array $aDadosPosRegPreco agrupando pelo departamento
 */
foreach ($aDadosPosRegPreco as $iNroSolicitacao => $aDados ) {

	$nTotalRegistros   = 0;
	$nTotalSolicitada  = 0;
	$nTotalEmpenhada   = 0;
	$nTotalSolicitar   = 0;
  $nTotalEmpenhar    = 0;

  imprimeCabecalho($oPdf, $alt, $aDados['oAbertura'], $aDados['oCompilacao'], $aDados['sLicitacao'], $aDados['lControlaValor']);
  $lPreenchimento = 1;

	/**
	 * Percore os registros por dados compilação
   */
  foreach ($aDados as $iIndice => $aDadosCompilacao ) {

    if (is_array($aDadosCompilacao)) {

      foreach ($aDadosCompilacao as $sIndice => $aDadosSolicita) {

        if ( $oPdf->gety() > $oPdf->h - 30 ) {

          imprimeCabecalho($oPdf, $alt, $aDados['oAbertura'], $aDados['oCompilacao'], $aDados['sLicitacao'], $aDados['lControlaValor']);
          $lPreenchimento = 1;
        }

        $lPreenchimento = $lPreenchimento == 0 ? 1 : 0;
        $oPdf->setfont('arial','',6);

        $oPdf->cell(15, $alt, $aDadosSolicita['oDados']->iSeq                                                 , 0, 0, "C", $lPreenchimento);
        $oPdf->cell(15, $alt, $aDadosSolicita['oDados']->iCodItem                                             , 0, 0, "C", $lPreenchimento);
        $oPdf->cell(28, $alt, substr($aDadosSolicita['oDados']->sDescrItem, 0, 20)                            , 0, 0, "L", $lPreenchimento);
        $oPdf->cell(39, $alt, str_replace("\\n", "\n",substr(trim($aDadosSolicita['oDados']->sCompl), 0, 20)) , 0, 0, "L", $lPreenchimento);
        $oPdf->cell(16, $alt, $aDadosSolicita['oDados']->sUnidade                                             , 0, 0, "C", $lPreenchimento);
        $oPdf->cell(16, $alt, db_formatar($aDadosSolicita['nTotalVlrUnid'], 'v', " ", $casadec)               , 0, 0, "R", $lPreenchimento);
        $oPdf->cell(($aDadosSolicita['oDados']->lControlaValor ? 50 : 32), $alt, substr($aDadosSolicita['oDados']->sFornecedor, 0, ($aDadosSolicita['oDados']->lControlaValor ? 35 : 20)), 0, 0, "L", $lPreenchimento);

        if (!$aDadosSolicita['oDados']->lControlaValor) {
          $oPdf->cell(18, $alt, $aDadosSolicita['nTotalQntMin'].'/'.$aDadosSolicita['nTotalQntMax'], 0, 0, "R", $lPreenchimento);
        }

        $oPdf->cell(25, $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($aDadosSolicita['oDados']->iSolicitada, 'v', " ", $casadec) : $aDadosSolicita['oDados']->iSolicitada), 0, 0, "R", $lPreenchimento);
        $oPdf->cell(25, $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($aDadosSolicita['oDados']->iEmpenhada, 'v', " ", $casadec) : $aDadosSolicita['oDados']->iEmpenhada), 0, 0, "R", $lPreenchimento);
        $oPdf->cell(25, $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($aDadosSolicita['oDados']->nSolicitar, 'v', " ", $casadec) : $aDadosSolicita['oDados']->nSolicitar), 0, 0, "R", $lPreenchimento);
        $oPdf->cell(25, $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($aDadosSolicita['oDados']->nEmpenhar, 'v', " ", $casadec) : ($aDadosSolicita['oDados']->nEmpenhar ? $aDadosSolicita['oDados']->nEmpenhar : '0')), 0, 1, "R", $lPreenchimento);

        /**
         * Total de cada numero de solicitacao
         */
        $iCodigoCompilacao   = $aDados['oCompilacao'];
        $nTotalSolicitada   += $aDadosSolicita['oDados']->iSolicitada;
        $nTotalEmpenhada    += $aDadosSolicita['oDados']->iEmpenhada;
        $nTotalSolicitar    += $aDadosSolicita['oDados']->nSolicitar;
        $nTotalEmpenhar     += $aDadosSolicita['oDados']->nEmpenhar;
        $nTotalRegistros++;

      }
		}
	}

  $oPdf->setfont('arial','b',8);
  $oPdf->cell(279, 1,    ''                                                                            , "T", 1, "L", 0);
  $oPdf->cell(113, $alt, 'TOTAL DO REGISTRO DE PRECO:'                                                 ,   0, 0, "R", 0);
  $oPdf->cell(16,  $alt, $iCodigoCompilacao                                                            ,   0, 0, "R", 0);
  $oPdf->cell(32,  $alt, $nTotalRegistros                                                              ,   0, 0, "R", 0);
  $oPdf->cell(18,  $alt, ''                                                                            ,   0, 0, "R", 0);
  $oPdf->cell(25,  $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($nTotalSolicitada, 'v', " ", $casadec) : $nTotalSolicitada),   0, 0, "R", 0);
  $oPdf->cell(25,  $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($nTotalEmpenhada, 'v', " ", $casadec) : $nTotalEmpenhada),   0, 0, "R", 0);
  $oPdf->cell(25,  $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($nTotalSolicitar, 'v', " ", $casadec) : $nTotalSolicitar),   0, 0, "R", 0);
  $oPdf->cell(25,  $alt, ($aDadosSolicita['oDados']->lControlaValor ? db_formatar($nTotalEmpenhar, 'v', " ", $casadec) : ($nTotalEmpenhar ? $nTotalEmpenhar : '0')),   0, 1, "R", 0);

  /**
   * Total Geral soma os totais de cada solicitacao
   */

  $nTotalGeralRegistros   += $nTotalRegistros;
  $nTotalGeralSolicitada  += $nTotalSolicitada;
  $nTotalGeralEmpenhada   += $nTotalEmpenhada;
  $nTotalGeralSolicitar   += $nTotalSolicitar;
  $nTotalGeralEmpenhar    += $nTotalEmpenhar;

}

if ($lTotalGeral) {
  $oPdf->cell(113, $alt, 'TOTAL GERAL:'                                                                 , 0, 0, "R", 0);
  $oPdf->cell(16,  $alt, ''                                                                             , 0, 0, "R", 0);
  $oPdf->cell(32,  $alt, $nTotalGeralRegistros                                                          , 0, 0, "R", 0);
  $oPdf->cell(18,  $alt, ''                                                                             , 0, 0, "R", 0);
  $oPdf->cell(25,  $alt, $nTotalGeralSolicitada                                                         , 0, 0, "R", 0);
  $oPdf->cell(25,  $alt, $nTotalGeralEmpenhada                                                          , 0, 0, "R", 0);
  $oPdf->cell(25,  $alt, $nTotalGeralSolicitar                                                          , 0, 0, "R", 0);
  $oPdf->cell(25,  $alt, $nTotalGeralEmpenhar                                                           , 0, 1, "R", 0);
}

Header("Content-disposition: inline; filename=posicao_registro_preco_" . time() . ".pdf");
$oPdf->Output();

/**
 * @param $oPdf
 * @param $alt
 * @param $iAbertura
 * @param $iCompilacao
 * @param $sLicitacao
 * @param $lControlaValor
 */
function imprimeCabecalho(&$oPdf, $alt, $iAbertura, $iCompilacao, $sLicitacao, $lControlaValor) {

  $oPdf->addpage("L");
  $oPdf->setfont('arial', 'b', 8);

  $oPdf->cell(20, $alt, "Abertura:", 'LTB', 0, "C", 1);

  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(28, $alt, $iAbertura, 'TB', 0, "L", 1);

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, $alt, "Compilação:", 'TB', 0, "C", 1);

  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(28, $alt, $iCompilacao, 'TB', 0, "L", 1);

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(20, $alt, "Licitação:", 'TB', 0, "C", 1);

  $oPdf->setfont('arial', '', 6);
  $oPdf->cell(($lControlaValor ? 63 : 45), $alt, $sLicitacao, 'RTB', 0, "L", 1);

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(($lControlaValor ? 50 : 68), $alt, ($lControlaValor ? "Valor" : "Quantidade"), 1, 0, "C", 1);
  $oPdf->cell(50, $alt, "Saldos"    , 1, 1, "C", 1);

  $oPdf->cell(15, $alt, "Seq."       , 1, 0, "C", 1);
  $oPdf->cell(15, $alt, "Item"       , 1, 0, "C", 1);
  $oPdf->cell(28, $alt, "Descrição"  , 1, 0, "C", 1);
  $oPdf->cell(39, $alt, "Complemento", 1, 0, "C", 1);
  $oPdf->cell(16, $alt, "Unidade"    , 1, 0, "C", 1);
  $oPdf->cell(16, $alt, ($lControlaValor ? "Valor" : "Vlr Uni.")   , 1, 0, "C", 1);
  $oPdf->cell(($lControlaValor ? 50 : 32), $alt, "Fornecedor" , 1, 0, "C", 1);

  if (!$lControlaValor) {
    $oPdf->cell(18, $alt, "Min/Máx"    , 1, 0, "C", 1);
  }

  $oPdf->cell(25, $alt, ($lControlaValor ? "Solicitado" : "Solicitada") , 1, 0, "C", 1);
  $oPdf->cell(25, $alt, ($lControlaValor ? "Empenhado" : "Empenhada")  , 1, 0, "C", 1);
  $oPdf->cell(25, $alt, "Solicitar"  , 1, 0, "C", 1);
  $oPdf->cell(25, $alt, "Empenhar"   , 1, 1, "C", 1);
}