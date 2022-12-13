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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/exceptions/ParameterException.php"));

require_once modification("model/arrecadacao/abatimento/Desconto.model.php");

db_app::import("arrecadacao.RegraCompensacao");
db_app::import("CgmFactory");
db_app::import("recibo");
db_app::import("exceptions.*");

$oJson = new services_json();
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new db_stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  switch ($oParametros->exec) {

    /**
     * Valor com desconto
     * inicia uma trasacao no banco para simular o valor final do debito com desconto, depois desfaz
     */
    case 'getValorComDesconto' :

      $iNumpre                 = $oParametros->iNumpre;
      $iNumpar                 = $oParametros->iNumpar;
      $iReceita                = $oParametros->iReceita;
      $nPercentualDesconto     = $oParametros->nPercentual;
      $iInstituicao            = db_getsession('DB_instit');

      $nValorHistorico         = 0;
      $nValorCorrigido         = 0;
      $nValorJuros             = 0;
      $nValorMulta             = 0;
      $nValorTotal             = 0;

      $nValorHistoricoAnterior = 0;
      $nValorCorrigidoAnterior = 0;
      $nValorJurosAnterior     = 0;
      $nValorMultaAnterior     = 0;
      $nValorTotalAnterior     = 0;

      $nValorLimite            = 0;

      /**
       * Begin
       */
      db_inicio_transacao();

      $sWhereDebitosNumpre  = " and y.k00_hist <> 918";


      if ( $iReceita > 0 ) {
      	$sWhereDebitosNumpre .= " and y.k00_receit = {$iReceita}";
      }

      /**
       * Busca debitos do numpre antes da correção
       */
      $rsDebitosNumpre = debitos_numpre($iNumpre, 0, 0, db_getsession("DB_datausu"), db_getsession("DB_anousu"), $iNumpar, '', '', $sWhereDebitosNumpre);
      $aDebitosNumpre  = db_utils::getCollectionByRecord($rsDebitosNumpre);

      /**
       * Totaliza os valores antes da correção
      */
      foreach ( $aDebitosNumpre as $oDebitoNumpre ) {

      	$nValorHistoricoAnterior += $oDebitoNumpre->vlrhis;
      	$nValorCorrigidoAnterior += $oDebitoNumpre->vlrcor;
      	$nValorJurosAnterior     += $oDebitoNumpre->vlrjuros;
      	$nValorMultaAnterior     += $oDebitoNumpre->vlrmulta;
      	$nValorTotalAnterior     += $oDebitoNumpre->total;
      }

      $oDaoArrecad = db_utils::getDao('arrecad');

      $aWhereDebito[] = "arrecad.k00_numpre = {$iNumpre}";
      $aWhereDebito[] = "arrecad.k00_hist <> 918";
      $aWhereDebito[] = "arreinstit.k00_instit = {$iInstituicao}";

      if ( $iNumpar > 0 ) {
      	$aWhereDebito[] = "arrecad.k00_numpar = {$iNumpar}";
      }

      if ( $iReceita > 0 ) {
      	$aWhereDebito[] = "arrecad.k00_receit = {$iReceita}";
      }

      $sWhereDebito  = implode(' and ', $aWhereDebito);

      $sCamposDebito = "arrecad.*";
      $sSqlDebito    = $oDaoArrecad->sql_query_file_instit(null, $sCamposDebito, 'arrecad.k00_numpar', $sWhereDebito);
      $rsDebito      = $oDaoArrecad->sql_record($sSqlDebito);

      /**
       * Nao encontrou debitos na arrecad ou ocorreu erro na query
      */
      if ( $oDaoArrecad->numrows == 0 ) {
      	throw new Exception("Débito para o numpre {$iNumpre} não encontrados");
      }

      /**
       * Array com as parcelas encontradas
       */
      $aDebitos = db_utils::getCollectionByRecord($rsDebito);

      /**
       * Percorre as parcelas e altera arrecad com desconto, depois da rollback
       */
      foreach ($aDebitos as $oDebito) {

        $nValorLimite += $oDebito->k00_valor;

        $nValorDesconto  = $oDebito->k00_valor * $nPercentualDesconto / 100;
        $nValor          = $oDebito->k00_valor - $nValorDesconto;

        $oDaoArrecad->k00_valor = $nValor;
        $GLOBALS["HTTP_POST_VARS"]["k00_valor"] = $nValor;

        $sWhere = "arrecad.k00_numpre = {$oDebito->k00_numpre} and arrecad.k00_numpar = {$oDebito->k00_numpar} and k00_receit = {$oDebito->k00_receit}";
        $oDaoArrecad->alterar(null, $sWhere);

        if ( $oDaoArrecad->erro_status == "0" ) {
          throw new Exception($oDaoArrecad->erro_msg);
        }
      }


      /**
       * Busca debitos do numpre já corrigindo
       */
      $rsDebitosNumpre = debitos_numpre($iNumpre, 0, 0, db_getsession("DB_datausu"), db_getsession("DB_anousu"), $iNumpar, '', '', $sWhereDebitosNumpre);
      $aDebitosNumpre  = db_utils::getCollectionByRecord($rsDebitosNumpre);

      /**
       * Totaliza os valores já corrigidos
       */
      foreach ( $aDebitosNumpre as $oDebitoNumpre ) {

        $nValorHistorico += $oDebitoNumpre->vlrhis;
        $nValorCorrigido += $oDebitoNumpre->vlrcor;
        $nValorJuros     += $oDebitoNumpre->vlrjuros;
        $nValorMulta     += $oDebitoNumpre->vlrmulta;
        $nValorTotal     += $oDebitoNumpre->total;
      }

      /**
       * Formata valores
       */
      $oRetorno->nValorHistoricoAnterior = $nValorHistoricoAnterior;
      $oRetorno->nValorCorrigidoAnterior = $nValorCorrigidoAnterior;
      $oRetorno->nValorJurosAnterior     = $nValorJurosAnterior;
      $oRetorno->nValorMultaAnterior     = $nValorMultaAnterior;
      $oRetorno->nValorTotalAnterior     = $nValorTotalAnterior;

      $oRetorno->nValorLimite            = $nValorLimite;
      $oRetorno->nValorHistorico         = $nValorHistorico;
      $oRetorno->nValorCorrigido         = $nValorCorrigido;
      $oRetorno->nValorJuros             = $nValorJuros;
      $oRetorno->nValorMulta             = $nValorMulta;
      $oRetorno->nValorTotal             = $nValorTotal;


      /**
       * Transação efetuada somente para simulação do cálculo com juro, multa e valor corrigido
       * Depois da simulação, os dados retornaram ao valor de origem com rollback
       * Rollback
       */
      db_fim_transacao(true);

    break;

    /**
     * Incluir desconto
     */
    case 'incluirDesconto' :

      require_once modification("model/arrecadacao/abatimento/Desconto.model.php");

      /**
       * parametros vindos do formulario
       */
      $iNumpre             = $oParametros->iNumpre;
      $iNumpar             = $oParametros->iNumpar;
      $nValorHistorico     = $oParametros->nValorHistorico;
      $iReceita            = $oParametros->iReceita;
      $sObservacao         = addslashes(db_stdClass::normalizeStringJsonEscapeString($oParametros->sObservacao));
      $nPercentualDesconto = $oParametros->nPercentual;
      $iInstituicao        = db_getsession('DB_instit');

      /**
       * Valida formato dos dados enviados
       */
      if ( !DBNumber::isFloat($oParametros->nPercentualLimitado) ) {
        throw new ParameterException("Percentual de desconto inválido");
      }

      if ( !DBNumber::isFloat($oParametros->nValorDesconto) ) {
        throw new ParameterException("Valor do Desconto inválido");
      }

      $oDaoArrecad = db_utils::getDao('arrecad');

      $aParcelasDebitoCorrigido = array();

      /**
       * Busca debitos do numpre já corrigindo
       */
      $rsDebitosNumpre = debitos_numpre($iNumpre, 0, 0, db_getsession("DB_datausu"), db_getsession("DB_anousu"), $iNumpar, '', '',"");

      foreach ( db_utils::getCollectionByRecord($rsDebitosNumpre) as $oDadosDebito ) {

        if ( $iNumpar > 0 && $iNumpar != $oDadosDebito->k00_numpar ) {
          continue;
        }

        if ( $iReceita > 0 && $iReceita != $oDadosDebito->k00_receit) {
          continue;
        }

        $oValores = new stdClass();

        $nValorCorrecao = $oDadosDebito->vlrcor - $oDadosDebito->vlrhis;

        $oValores->nValorOriginalHistorico   = $oDadosDebito->vlrhis;
        $oValores->nValorOriginalCorrigido   = $nValorCorrecao;
        $oValores->nValorOriginalJuros       = $oDadosDebito->vlrjuros;
        $oValores->nValorOriginalMulta       = $oDadosDebito->vlrmulta;
        $oValores->nValorOriginalTotal       = $oDadosDebito->total;

        $oValores->nValorDescontadoHistorico = ( $oDadosDebito->vlrhis   * $nPercentualDesconto ) / 100;
        $oValores->nValorDescontadoCorrigido = ( $nValorCorrecao         * $nPercentualDesconto ) / 100;
        $oValores->nValorDescontadoJuros     = ( $oDadosDebito->vlrjuros * $nPercentualDesconto ) / 100;
        $oValores->nValorDescontadoMulta     = ( $oDadosDebito->vlrmulta * $nPercentualDesconto ) / 100;
        $oValores->nValorDescontadoTotal     = ( $oDadosDebito->total    * $nPercentualDesconto ) / 100;
        $oValores->iTipoDebito               =   $oDadosDebito->k00_tipo;
        $aParcelasDebitoCorrigido[$oDadosDebito->k00_numpar][$oDadosDebito->k00_receit] = $oValores;
      }

      db_inicio_transacao();

      $nTotalDesconto = 0;

      /**
       * Percorre arrecad e lanca desconto em cada parcela e receita
       * Subtrai o valor em percentual($nPercentualDesconto)
       */
      foreach ( $aParcelasDebitoCorrigido as $iNumpar => $aReceitasDebitoCorrigido ) {

        foreach ( $aReceitasDebitoCorrigido as $iReceita => $oDebito ) {

          $nValorDesconto  = $oDebito->nValorDescontadoHistorico;
          $nTotalDesconto += $nValorDesconto;
          $sObservacao     = addslashes(db_stdClass::normalizeStringJsonEscapeString($oParametros->sObservacao));

          $oDesconto = new Desconto();
          $oDesconto->setValor( $nValorDesconto );
          $oDesconto->setComposicaoDesconto($oDebito->nValorDescontadoJuros,
                                            $oDebito->nValorDescontadoMulta,
                                            $oDebito->nValorDescontadoCorrigido);
          $oDesconto->setNumpre( $iNumpre );
          $oDesconto->setNumpar( $iNumpar );
          $oDesconto->setCodigoReceita( $iReceita );
          $oDesconto->setTipoDebito( $oDebito->iTipoDebito );
          $oDesconto->setDataLancamento(new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
          $oDesconto->setPercentual($nPercentualDesconto);
          $oDesconto->setObservacao($sObservacao);
          $oDesconto->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );
          $oDesconto->setHoraLancamento(date('H:i'));
          $oDesconto->setInstituicao( InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')) );
          $oDesconto->salvar();

        }
      }

      db_fim_transacao(false);

      /**
       * @todo mensagem de credito lancado
       */
      $oRetorno->sMensagem = 'Lançado R$ '. db_formatar($nTotalDesconto, 'f', '0', 2) .' de desconto para o numpre: ' . $iNumpre;

    break;

    /**
     * Buscao dados do desconto
     * @todo criar metodo no model DescontoManual, getDadosDesconto, ou no __construct
     */
    case 'getDadosDesconto' :

      $iNumpre = $oParametros->iNumpre;
      $sDataHoje = date( "Y-m-d", db_getsession("DB_datausu") );

      $oDaoAbatimentoArreckey = new cl_abatimentoarreckey();
      $sWhereDesconto         = '     arreckey.k00_numpre                = ' . $iNumpre;
      $sWhereDesconto        .= ' and abatimento.k125_tipoabatimento     = ' . Abatimento::TIPO_DESCONTO;
      $sWhereDesconto        .= ' and abatimento.k125_abatimentosituacao = ' . Abatimento::SITUACAO_ATIVO;
      $sWhereDesconto        .= ' and arrecad.k00_numpre is not null       ';
      $sSqlDesconto           = $oDaoAbatimentoArreckey->sql_query_buscaAbatimento('*', null, $sWhereDesconto);
      $rsDesconto             = $oDaoAbatimentoArreckey->sql_record($sSqlDesconto);

      if ( $oDaoAbatimentoArreckey->numrows == 0 ) {
        throw new Exception("Descontos para o numpre {$iNumpre} não encontrados");
      }

      $aDescontos = db_utils::getCollectionByRecord($rsDesconto);
      $aRetorno   = array();

      foreach ($aDescontos as $oDesconto) {

        $oStdRetorno = new stdClass();
        $oStdRetorno->iAbatimento     = $oDesconto->k125_sequencial;
        $oStdRetorno->sDataLancamento = db_formatar($oDesconto->k125_datalanc, 'd');
        $oStdRetorno->iReceita        = $oDesconto->k00_receit;
        $oStdRetorno->sReceita        = $oDesconto->k02_descr;
        $oStdRetorno->nValor          = trim(db_formatar($oDesconto->k125_valor, 'f'));
        $oStdRetorno->iParcela        = $oDesconto->k00_numpar;

        $aRetorno[] = $oStdRetorno;
      }

      $oRetorno->aDescontos = $aRetorno;

    break;

    /**
     * Cancela desconto manual
     * - altera arrecad retornando valor do desconto
     * - altera situacao do abatimento para cancelado
     * - lanca historico 919 - cancelamento de desconto
     */
    case 'cancelaDesconto' :

      db_inicio_transacao();

      /**
       * Total dos descontos do numpre que foram cancelados
       */
      $nTotalCancelado = 0;

      /**
       * Percorre as os abatimentos enviados
       * - canela os descontos
       * - $nTotalDesconto: soma os valores que retornaram para arrecad
       */
      foreach( $oParametros->aDescontos as $oDadosDesconto ) {

        $oDesconto = new Desconto($oDadosDesconto->iAbatimento);
        $oDesconto->cancelar();

        $nTotalCancelado += $oDesconto->getValor();
      }

      db_fim_transacao(false);

      $oRetorno->sMensagem  = "Descontos selecionados cancelados.\n";
      $oRetorno->sMensagem .= "R$ ". db_formatar($nTotalCancelado, 'f', '0', 2) ." retornados para o numpre: {$oParametros->iNumpre}.";

    break;

    default :
      throw new Exception('Nenhum parâmetro informado.');
    break;

  }

} catch (Exception $eErro) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = str_replace('\n', "\n", $eErro->getMessage());

  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);