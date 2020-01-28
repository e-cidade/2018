<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

class EvolucaoDividaAtiva {

  /**
   * Constante com o caminho do arquivo json de mensagens
   */
  const MENSAGENS = "tributario.divida.EvolucaoDividaAtiva.";

  /**
   * Data de início da carga
   * @var DBDate
   */
  private $oDataInicio;

  /**
   * Data de fim da carga
   * @var DBDate
   */
  private $oDataFim;

  /**
   * Data do sistema
   * @var DBDate
   */
  private $oDataSistema;

  /**
   * Array com os valores agrupados por conta pcasp
   * @var array
   */
  private $aRegistrosRelatorio = array();

  /**
   * Método Construtor
   *
   * @param string $sDataGeracao
   */
  public function __construct($sDataInicio = null, $sDataFim = null, $oDataAtual = null) {

    if (empty($sDataInicio)) {
      throw new BusinessException( _M( self::MENSAGENS . "erro_data_inicio_obrigatorio" ) );
    }

    if (empty($sDataFim)) {
      throw new BusinessException( _M( self::MENSAGENS . "erro_data_fim_obrigatorio" ) );
    }

    if (empty($oDataAtual)) {
      throw new BusinessException( _M( self::MENSAGENS . "erro_data_sistema_obrigatorio" ) );
    }

    $this->oDataInicio  = new DBDate( $sDataInicio );
    $this->oDataFim     = new DBDate( $sDataFim );
    $this->oDataSistema = new DBDate( $oDataAtual );
  }

  /**
   * Validamos se há inconsistências entre as datas informadas e as datas dos registros
   * @return boolean
   */
  public function validar() {

    $oMensagem = new StdClass();
    /**
     * Validamos a integridade das datas informadas
     */
    if (DBDate::calculaIntervaloEntreDatas( $this->oDataFim, $this->oDataInicio, 'd') <= 0 ) {
      throw new DBException( _M( self::MENSAGENS . "intervalo_invalido" ) );
    }

    if ( DBDate::calculaIntervaloEntreDatas( $this->oDataInicio, $this->oDataSistema, 'd') >= 0 ) {

      $oMensagem->sCampo = "Data Inicial";
      throw new DBException( _M( self::MENSAGENS . "emissao_hoje", $oMensagem ) );
    }

    if ( DBDate::calculaIntervaloEntreDatas( $this->oDataFim, $this->oDataSistema, 'd') >= 0 ) {

      $oMensagem->sCampo = "Data Final";
      throw new DBException( _M( self::MENSAGENS . "emissao_hoje", $oMensagem ) );
    }

    /**
     * Buscamos todas as datas dos registros usando o período informado
     */
    $sWhere                  = " v30_datageracao between '{$this->oDataInicio->getDate()}' and '{$this->oDataFim->getDate()}'";

    $oDaoEvolucaoDividaAtiva = new cl_evolucaodividaativa;
    $sSqlEvolucaoDividaAtiva = $oDaoEvolucaoDividaAtiva->sql_query_file( null , "distinct v30_datageracao", null, $sWhere );
    $rsEvolucaoDividaAtiva   = $oDaoEvolucaoDividaAtiva->sql_record( $sSqlEvolucaoDividaAtiva );

    if ($oDaoEvolucaoDividaAtiva->numrows == 0) {
      throw new DBException( _M( self::MENSAGENS . "dados_nao_encontrados" ) );
    }

    $aDataDividaAtiva = db_utils::getCollectionByRecord($rsEvolucaoDividaAtiva);

    $aDataDebito      = array();

    foreach ($aDataDividaAtiva as $oEvolucaoDividaAtiva) {

      $oData         = new DBDate($oEvolucaoDividaAtiva->v30_datageracao);
      $aDataDebito[] = $oData->getDate(DBDate::DATA_PTBR);
    }

    /**
     * Criamos um array de todas as datas no intervalo das datas informadas pelo usuário
     */
    $sSqlGeraData    = "select to_date (generate_series::varchar, 'YYYY-MM-DD') as intervalo
                        from (select * from generate_series('{$this->oDataInicio->getDate()}'::timestamp,'{$this->oDataFim->getDate()}'::timestamp ,'1 day')) as x;";

    $rsGeraData      = pg_query($sSqlGeraData);
    $aDatas          = db_utils::getCollectionByRecord($rsGeraData);
    $aDatasIntervalo = array();

    foreach ($aDatas as $oDataIntervalo) {

      $oData             = new DBDate($oDataIntervalo->intervalo);
      $aDatasIntervalo[] = $oData->getDate(DBDate::DATA_PTBR);
    }

    /**
     * Comparamos as datas dos registros no banco com as datas do intervalo do período informado
     */
    $aDatasInconsistentes = array_diff($aDatasIntervalo, $aDataDebito);

    if (!empty($aDatasInconsistentes)) {

      /**
       * Criamos um objeto com uma string das datas inconsistentes, para adicionarmos à mensagem de erro
       */
      $oMensagem->sDatas = "";

      foreach ($aDatasInconsistentes as $sDataInconsistente) {

        if ( !empty($oMensagem->sDatas) ) {
          $oMensagem->sDatas .= ", ";
        }

        $oMensagem->sDatas .= $sDataInconsistente;
      }

      $oMensagem->sDatas = $oMensagem->sDatas . '.';
      throw new BusinessException( _M( self::MENSAGENS . "datas_inconsistentes", $oMensagem ) );
    }

    return true;
  }

  /**
   * Efetua processamento dos dados para emissão do relatório
   * @return void
   */
  public function processar() {

    $oDaoEvolucaoDividaAtiva = new cl_evolucaodividaativa;

    $sSqlPeriodo = $oDaoEvolucaoDividaAtiva->getValoresPorPeriodoReceitaOrcamentaria( $this->oDataInicio,
                                                                                      $this->oDataFim,
                                                                                      'evolucaodividaativa.*, orcreceita.o70_codrec,
                                                                                       o57_fonte, orcfontes.o57_descr' );

    $rsEvolucaoDividaAtiva = $oDaoEvolucaoDividaAtiva->sql_record( $sSqlPeriodo );
    $aValoresPorPeriodo    = db_utils::getCollectionByRecord( $rsEvolucaoDividaAtiva );

    /**
     * Buscamos intervalo entre datas para utilizar no controle de quantidade
     * de registros, por receita taborc (o70_codrec)
     */
    $iIntervalo = DBDate::calculaIntervaloEntreDatas($this->oDataFim, $this->oDataInicio, 'd');

    $iReceitaAtual = 0;
    $iIndice       = 0;

    /**
     * Iteramos o array de valores por periodo ordenados por codrec, receita, data
     */
    foreach ( $aValoresPorPeriodo as $oValorPorPeriodo ) {

      if ( $iReceitaAtual != $oValorPorPeriodo->o70_codrec ) {

        $iReceitaAtual                             = $oValorPorPeriodo->o70_codrec;
        $this->aRegistrosRelatorio[$iReceitaAtual] = array(
                                                        'iCondigo'      => $iReceitaAtual,
                                                        'sEstrutural'   => $oValorPorPeriodo->o57_fonte,
                                                        'sDescricao'    => $oValorPorPeriodo->o57_descr,
                                                        'nSaldoInicial' => 0,
                                                        'nInscricao'    => 0,
                                                        'nPagamento'    => 0,
                                                        'nCancelamento' => 0,
                                                        'nDesconto'     => 0,
                                                        'nAtualizacao'  => 0,
                                                        'nSaldoFinal'   => 0
                                                      );
      }

      if ( $iIndice == 0 ) {

        $this->aRegistrosRelatorio[$iReceitaAtual]['nSaldoInicial'] += $oValorPorPeriodo->v30_valorhistorico + $oValorPorPeriodo->v30_valorcorrecao;

        /**
         * Guardamos o primeiro valor histórico e correção da receita para que possamos comparar com os valores
         * finais, e assim saberemos os valores de Inscrição e Atualização para o Relatório
         */
        $nHistoricoInicial = $oValorPorPeriodo->v30_valorhistorico;
        $nCorrecaoInicial  = $oValorPorPeriodo->v30_valorcorrecao;

        //Cancelados e prescritos
        $nCanceladoCorrecaoAnterior    = $oValorPorPeriodo->v30_valorcancelado - $oValorPorPeriodo->v30_valorcanceladohistorico;
        $nCanceladoHistoricoAnterior   = $oValorPorPeriodo->v30_valorcanceladohistorico;

        //Pagamentos normais
        $nPagoCorrecaoAnterior         = $oValorPorPeriodo->v30_valorpago - $oValorPorPeriodo->v30_valorpagohistorico;
        $nPagoHistoricoAnterior        = $oValorPorPeriodo->v30_valorpagohistorico;

        //Pagamentos parciais
        $nPagoCorrecaoParcialAnterior  = $oValorPorPeriodo->v30_valorpagoparcial;
        $nPagoHistoricoParcialAnterior = $oValorPorPeriodo->v30_valorpagoparcialhistorico;
        $nHistoricoAnterior            = $oValorPorPeriodo->v30_valorhistorico;
        $nCorrecaoAnterior             = $oValorPorPeriodo->v30_valorcorrecao;

        //Descontos
        $nDescontoAnterior             = abs($oValorPorPeriodo->v30_valordesconto);

        /**
         * Com estas variáveis controlamos os valores que serão inseridos no relatório
         */
        $nPagoCorrecaoRelatorio        = 0;
        $nPagoHistoricoRelatorio       = 0;
        $nCanceladoHistoricoRelatorio  = 0;
        $nCanceladoCorrecaoRelatorio   = 0;
        $nDescontoRelatorio            = 0;
      }

      /**
       * Somamos as diferenças entre os CANCELAMENTOS para sabermos o valor total de débitos cancelados no período informado
       */
      if ($nCanceladoHistoricoAnterior != $oValorPorPeriodo->v30_valorcanceladohistorico ||
          $nCanceladoCorrecaoAnterior  != ( $oValorPorPeriodo->v30_valorcancelado - $oValorPorPeriodo->v30_valorcanceladohistorico ) ) {

        $nCanceladoHistoricoRelatorio += $oValorPorPeriodo->v30_valorcanceladohistorico - $nCanceladoHistoricoAnterior;
        $nCanceladoCorrecaoRelatorio  += ( $oValorPorPeriodo->v30_valorcancelado - $oValorPorPeriodo->v30_valorcanceladohistorico ) - $nCanceladoCorrecaoAnterior;

        $nCanceladoCorrecaoAnterior    = $oValorPorPeriodo->v30_valorcancelado - $oValorPorPeriodo->v30_valorcanceladohistorico;
        $nCanceladoHistoricoAnterior   = $oValorPorPeriodo->v30_valorcanceladohistorico;
      }

      /**
       * Somamos as diferenças entre os PAGAMENTOS NORMAIS para sabermos o valor total de débitos pagos no período informado
       */
      if ($nPagoHistoricoAnterior != $oValorPorPeriodo->v30_valorpagohistorico ||
          $nPagoCorrecaoAnterior  != ( $oValorPorPeriodo->v30_valorpago - $oValorPorPeriodo->v30_valorpagohistorico ) ) {

        $nPagoHistoricoRelatorio += $oValorPorPeriodo->v30_valorpagohistorico - $nPagoHistoricoAnterior;
        $nPagoCorrecaoRelatorio  += ( $oValorPorPeriodo->v30_valorpago - $oValorPorPeriodo->v30_valorpagohistorico ) - $nPagoCorrecaoAnterior;

        $nPagoCorrecaoAnterior    = $oValorPorPeriodo->v30_valorpago - $oValorPorPeriodo->v30_valorpagohistorico;
        $nPagoHistoricoAnterior   = $oValorPorPeriodo->v30_valorpagohistorico;
      }

      /**
       * Somamos as diferenças entre os PAGAMENTOS PARCIAIS para sabermos o valor total de débitos pagos no período informado
       */
      if ($nPagoHistoricoParcialAnterior != $oValorPorPeriodo->v30_valorpagoparcialhistorico ||
          $nPagoCorrecaoParcialAnterior  != $oValorPorPeriodo->v30_valorpagoparcial) {

        $nPagoHistoricoRelatorio += $oValorPorPeriodo->v30_valorpagoparcialhistorico - $nPagoHistoricoParcialAnterior;
        $nPagoCorrecaoRelatorio  += $oValorPorPeriodo->v30_valorpagoparcial - $nPagoCorrecaoParcialAnterior;

        $nPagoHistoricoParcialAnterior = $oValorPorPeriodo->v30_valorpagoparcialhistorico;
        $nPagoCorrecaoParcialAnterior  = $oValorPorPeriodo->v30_valorpagoparcial;
      }

      if ( $nDescontoAnterior != abs($oValorPorPeriodo->v30_valordesconto) ) {

        $nDescontoRelatorio += abs($oValorPorPeriodo->v30_valordesconto) - $nDescontoAnterior;
        $nDescontoAnterior   = abs($oValorPorPeriodo->v30_valordesconto);
      }

      if ( $iIndice == $iIntervalo ) {

        $this->aRegistrosRelatorio[$iReceitaAtual]['nSaldoFinal']   += $oValorPorPeriodo->v30_valorhistorico + $oValorPorPeriodo->v30_valorcorrecao;
        $this->aRegistrosRelatorio[$iReceitaAtual]['nPagamento']    += ( $nPagoCorrecaoRelatorio + $nPagoHistoricoRelatorio ) - $nDescontoRelatorio;
        $this->aRegistrosRelatorio[$iReceitaAtual]['nCancelamento'] += $nCanceladoHistoricoRelatorio + $nCanceladoCorrecaoRelatorio;
        $this->aRegistrosRelatorio[$iReceitaAtual]['nDesconto']     += $nDescontoRelatorio;

        $nDescontoHistorico = 0;
        $nDescontoCorrecao  = 0;

        if ( empty($nHistoricoInicial) && empty($nPagoHistoricoRelatorio) && empty($nCanceladoHistoricoRelatorio) ) {
          $nDescontoCorrecao = $nDescontoRelatorio;
        } else {
          $nDescontoHistorico = $nDescontoRelatorio;
        }

        /**
         * Descontamos os valores de pagamentos, cancelamentos e descontos deste período e descontamos do histórico inicial
         */
        $nHistoricoDescontado = $nHistoricoInicial - ($nPagoHistoricoRelatorio - $nDescontoHistorico) - $nCanceladoHistoricoRelatorio - $nDescontoHistorico;

        /**
         * Verificamos se o histórico inicial(com os descontos citados acima) é menor que o valor histórico atual
         */
        if ( $nHistoricoDescontado < $oValorPorPeriodo->v30_valorhistorico) {

          /**
           * Caso seja menor, é porque tivemos inscrições de débitos neste período
           * Então buscamos esta diferença e colocamos no relatório
           */
          $nInscricao = $oValorPorPeriodo->v30_valorhistorico - $nHistoricoDescontado;
          $this->aRegistrosRelatorio[$iReceitaAtual]['nInscricao'] = $nInscricao;
        }

        /**
         * Descontamos os valores de pagamentos, cancelamentos e descontos deste período e descontamos do histórico inicial
         */
        $nCorrecaoDescontado = $nCorrecaoInicial - ($nPagoCorrecaoRelatorio - $nDescontoCorrecao) - $nCanceladoCorrecaoRelatorio - $nDescontoCorrecao;

        /**
         * Verificamos se o histórico inicial(com os descontos citados acima) é menor que o valor histórico atual
         */
        if ( $nCorrecaoDescontado < $oValorPorPeriodo->v30_valorcorrecao) {

          /**
           * Caso seja menor, é porque tivemos inscrições de débitos neste período
           * Então buscamos esta diferença e colocamos no relatório
           */
          $nAtualizacao = $oValorPorPeriodo->v30_valorcorrecao - $nCorrecaoDescontado;
          $this->aRegistrosRelatorio[$iReceitaAtual]['nAtualizacao'] = $nAtualizacao;
        }

        $iIndice = 0;
      } else {
        $iIndice++;
      }
    }
  }

  /**
   * Emitimos o relatório após o processamento executado
   * @return string
   */
  public function emitirRelatorio() {

    $aHeaders = array( "Código", "Estrutural", "Descrição", "Saldo Inicial","Inscrições", "Pagamentos", "Cancelamentos", "Descontos", "Atualização", "Saldo Final" );
    $aWidth   = array( 5, 12, 34, 7, 7, 7, 7, 7, 7, 7 );
    $aAlign   = array( PDFDocument::ALIGN_CENTER,
                       PDFDocument::ALIGN_CENTER,
                       PDFDocument::ALIGN_LEFT,
                       PDFDocument::ALIGN_RIGHT,
                       PDFDocument::ALIGN_RIGHT,
                       PDFDocument::ALIGN_RIGHT,
                       PDFDocument::ALIGN_RIGHT,
                       PDFDocument::ALIGN_RIGHT,
                       PDFDocument::ALIGN_RIGHT,
                       PDFDocument::ALIGN_RIGHT );

    $oPdfTable = new PDFTable(PDFDocument::PRINT_LANDSCAPE);
    $oPdfTable->setTotalByPage(true);
    $oPdfTable->setPercentWidth(true);
    $oPdfTable->setHeaders($aHeaders);
    $oPdfTable->setColumnsWidth($aWidth);
    $oPdfTable->setColumnsAlign($aAlign);

    foreach ($this->aRegistrosRelatorio as $this->aRegistroRelatorio) {

      $oPdfTable->addLineInformation(
        array(
          $this->aRegistroRelatorio['iCondigo'],
          db_formatar($this->aRegistroRelatorio['sEstrutural'], 'receita'),
          $this->aRegistroRelatorio['sDescricao'],
          db_formatar(round($this->aRegistroRelatorio['nSaldoInicial'], 2), 'v'),
          db_formatar(round($this->aRegistroRelatorio['nInscricao'], 2),  'v'),
          db_formatar(round($this->aRegistroRelatorio['nPagamento'], 2),  'v'),
          db_formatar(round($this->aRegistroRelatorio['nCancelamento'], 2),  'v'),
          db_formatar(round($this->aRegistroRelatorio['nDesconto'], 2),  'v'),
          db_formatar(round($this->aRegistroRelatorio['nAtualizacao'], 2),  'v'),
          db_formatar(round($this->aRegistroRelatorio['nSaldoFinal'], 2),   'v')
        )
      );
    }

    $oPdfDocument = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $oPdfDocument->SetFillColor(235);
    $oPdfDocument->addHeaderDescription("Relatório de Evolução da Dívida Ativa");
    $oPdfDocument->addHeaderDescription("");
    $oPdfDocument->addHeaderDescription("Período: De {$this->oDataInicio->getDate(DBDate::DATA_PTBR)} até {$this->oDataFim->getDate(DBDate::DATA_PTBR)}");
    $oPdfDocument->open();

    $oPdfTable->printOut($oPdfDocument, false);

    $sDiretorioTmp     = dirname(__DIR__) . "/../tmp/";
    $sArquivoRelatorio = $oPdfDocument->savePDF( "evolucao_divida_ativa_".time(), $sDiretorioTmp );

    if( !file_exists($sArquivoRelatorio) ){
      throw new Exception(_M( self::MENSAGENS . "erro_gerar_relatorio" ));
    }

    return $sArquivoRelatorio;
  }
}