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
 * Dependencias
 */
db_app::import('DBDate');
db_app::import('recibo');

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

/**
 * Refactor de geracao de boleto
 *
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 *
 * @version $Revision: 1.16 $
 */
class RefactorGeracaoBoleto {

  /**
   * variaveis o array $aDados
   */
  private $aDebitosSelecionados = array();

  /**
   * codigo do modelo de impressão
   */
  private $iCodigoModeloImpressao;


  /**
   * Array com string contendo informacoes do numpre como numpar e receita
   *
   * @var array
   * @access private
   */
  private $aDadosNumpre = array();

  private $ver_matric;
  private $ver_inscr;
  private $ver_numcgm;
  private $tipo_debito;
  private $k03_tipo;
  private $numpre_unica;
  private $totregistros;
  private $lNovoRecibo;
  private $forcarvencimento;
  private $processarDescontoRecibo;
  private $k00_dtoper;

  /**
   * Construtor
   *
   * @access public
   * @exception - sem transacao ativa
   * @return void
   */
  public function __construct() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Não existe Transação Ativa.");
    }
  }

  /**
   * Define as variaveis internas do refactor
   *
   * @param string $sVariavel - nome da propriedade
   * @param mixed $valor      - valor da propriedade
   * @access public
   * @return void
   */
  public function set($sVariavel, $valor) {

    if ( !property_exists($this, $sVariavel) ) {
      throw new Exception(__CLASS__ . ": Propriedade {$sVariavel} não encontrada.");
    }

    $this->{$sVariavel} = $valor;
  }

  /**
   * Adicionar debito
   * Adiciona ao array aDebitosSelecionados stdClass com numpre, numpar e receita
   * e adciona no array aDadosNumpre com chave CHECK0,1,2... com string contendo numpre, numpar e receita
   *
   * @param integer $iNumpre
   * @param integer $iNumpar
   * @param integer $iReceita
   * @access public
   * @return bool
   */
  public function adicionarDebito( $iNumpre, $iNumpar, $iReceita = 0 ) {

    $oDadosDebito                 = new stdClass();
    $oDadosDebito->iNumpre        = $iNumpre;
    $oDadosDebito->iNumpar        = $iNumpar;
    $oDadosDebito->iReceita       = $iReceita;
    $this->aDebitosSelecionados[] = $oDadosDebito;

    $sChave        = 'CHECK' . count($this->aDadosNumpre);
    $sDadosNumpre  = "N{$iNumpre}";
    $sDadosNumpre .= "P{$iNumpar}";
    $sDadosNumpre .= "R{$iReceita}";

    $this->aDadosNumpre[$sChave] = $sDadosNumpre;
    return true;
  }

  /**
   * processar
   *
   * @access public
   * @return void
   */
  public function processar() {

    $oRetorno = new stdClass();

    $oDaoNumpref     = db_utils::getDao('numpref');
    $oDaoParJuridico = db_utils::getDao('parjuridico');
    $oDaoArretipo    = db_utils::getDao('arretipo');
    $oDataSessao     = new DBDate( date("Y-m-d", db_getsession("DB_datausu")) );

    $sql_cgc = "select cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit");
    $rs_cgc = db_query($sql_cgc);

    $oConfig = new StdClass();
    $oConfig->db21_codcli = pg_result($rs_cgc,0,1);

    /**
     * Busca parâmetros do tributario
     * para validar reemissao de recibo
     */
    $sSqlParametros         = $oDaoNumpref->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit'));
    $rsSqlParametros        = $oDaoNumpref->sql_record($sSqlParametros);

    if ( $rsSqlParametros && pg_num_rows($rsSqlParametros) ) {

      $oParametrosTributario = db_utils::fieldsMemory($rsSqlParametros,0);

      if ($oParametrosTributario->k03_reemissaorecibo == 't') {
        $lConfReemissaoRecibo = true;
      } else {
        $lConfReemissaoRecibo = false;
      }

    } else {
      throw new Exception('Erro ao selecionar parâmetros do tributário.');
    }

    /**
     * Busca paraetros da tabela parjuridico
     */
    $sSqlParJuridico = "select * from parjuridico where v19_anousu = ".db_getsession("DB_anousu");
    $rsParJuridico   = $oDaoParJuridico->sql_record($sSqlParJuridico);

    if($rsParJuridico && pg_num_rows($rsParJuridico) > 0) {
      $oParJuridico = db_utils::fieldsMemory($rsParJuridico,0);
    } else {
      throw new Exception('Erro ao selecionar parâmetros do jurídico.');
    }

    /**
     * Valida data de vencimento
     */
    $tsDataOperacao = strtotime(date("Y-m-d", db_getsession("DB_datausu") ));
    $DB_DATACALC    = $tsDataOperacao;

    if (isset($this->k00_dtoper) ) {
      $dVencimento  = implode("-",array_reverse(explode("/",$this->k00_dtoper)));
    } else {
      $dVencimento  = date("Y-m-d", db_getsession("DB_datausu") );
    }
    $dtOperacao     = date("Y-m-d", db_getsession("DB_datausu") );

    $aRecibosComCustasEmitidos = array();
    /*
     * Caso atributo $this->geracarne for != "" eh uma emissao de carne
     *   Caso contrario eh uma emissao de recibo
     */
    $lEmissaoCarne  = false;
    $lEmissaoRecibo = false;

    if (isset($this->geracarne) && $this->geracarne != ""){
      $lEmissaoCarne = true;
    }else{
      $lEmissaoRecibo = true;
    }

    /**
     * ValidaNumpre  * Retornando mensagem cadastradas no arretipo
     */
    $sSqlArretipo           = $oDaoArretipo->sql_query($this->tipo_debito, "*","");
    $rsSqlArretipo          = $oDaoArretipo->sql_record($sSqlArretipo);
    $oArretipo              = db_utils::fieldsMemory($rsSqlArretipo, 0);

    $aTipoInicial[0]               = 18;
    $aTipoInicial[1]               = 12;
    $aTipoInicial[2]               = 13;

    $iTipoModeloRecibo             = 2;       //Cadtipomod
    $oRetorno->aSessoesRecibo      = array();
    $oRetorno->aSessoesCarne       = array();
    $oRetorno->recibos_emitidos    = array();

    $lForcaVencimento              = $this->forcarvencimento == "true" ? true : false;

    $oDaoProcessoForo              = db_utils::getDao('processoforo');
    $oDaoProcessoForoPartilha      = db_utils::getDao('processoforopartilha');
    $oDaoProcessoForoPartilhaCusta = db_utils::getDao('processoforopartilhacusta');

    $oRetorno->aSessoes            = array();

    $oDebitosFormulario            = $this->retornaDebitosSelecionados($this, $oArretipo->k00_tipoagrup);
    $aChecks                       = $oDebitosFormulario->aDadosChecks;
    $aDadosForm                    = $oDebitosFormulario->aOutrosDados;
    $aIniciais                     = $oDebitosFormulario->aIniciais;
    $aRecibopaga_numnov            = array();
    $sGeraCarne                    = $oDebitosFormulario->sGeraCarne;
    $lCarne                        = empty($sGeraCarne) ? true : false;

    $aNumpresFormulario = $oDebitosFormulario->aValidaNumpre;

    /**
     * Validacao se vai existir emissao de custas para o recibo
     */
    if ($lConfReemissaoRecibo) {

      foreach ($oDebitosFormulario->aValidaNumpre as $iNumpre) {

        $aNumpresOrigem = array();
        $aProcessosForo = $oDaoProcessoForoPartilhaCusta->getProcessoForoByNumprePacelamento($iNumpre, $oArretipo->k03_tipo);
        if (count($aProcessosForo) == 0 ) {

          $iTipoModeloRecibo  = 2;
          $iTipoModeloCarne   = 1;
        } elseif (count($aProcessosForo) == 1) {

          $iProcessoForo  = $aProcessosForo[0];

          $sSqlPartilha   = $oDaoProcessoForoPartilha->sql_query_file(null,"*",null,"v76_processoforo = {$iProcessoForo}");
          $rsPartilha     = $oDaoProcessoForoPartilha->sql_record($sSqlPartilha);

          if ($rsPartilha && pg_num_rows($rsPartilha) > 0) {

            $aPartilhas = db_utils::getCollectionByRecord($rsPartilha);
            foreach ($aPartilhas as $oProcessoForoPartilha) {

              $iTipoLancamento = $oProcessoForoPartilha->v76_tipolancamento;
              $dtLancamento    = $oProcessoForoPartilha->v76_dtpagamento;

              if ( $iTipoLancamento == 2 || $iTipoLancamento == 3 || ($iTipoLancamento == 1 && !empty($dtLancamento) ) ) {

                $iTipoModeloRecibo = 2;
                $iTipoModeloCarne  = 1;
                break;

              } else {

                $iTipoModeloRecibo = 19;
                $iTipoModeloCarne  = 20;
              }
            }
          } else {

            $iTipoModeloRecibo = 19;
            $iTipoModeloCarne  = 20;
          }
        } else {

          throw new Exception("Existe mais de um processo do foro encontrado para os débitos selecionados");
        }

      }

    } else {

      $iTipoModeloRecibo  = 2;
      $iTipoModeloCarne   = 1;
    }

    /*
     * validações para emissão de guia atravvez da nota
     * cadtipmod 21
     */
    if (!empty($this->iCodigoModeloImpressao)) {
      $iTipoModeloRecibo = $this->iCodigoModeloImpressao;
    }

    $oRetorno->iTipoModeloRecibo = $iTipoModeloRecibo;
    $oRetorno->iTipoModeloCarne  = $iTipoModeloCarne;

    $oRetorno->iMaximoParcelasGeral = $oDebitosFormulario->iMaxParc;
    $oRetorno->iMinimoParcelasGeral = $oDebitosFormulario->iMinParc;

    if ($oParJuridico->v19_partilha == "t" && $lEmissaoCarne && $oArretipo->k03_tipo == 18 ) {
      $iTipoModeloReciboQuery = "{$iTipoModeloCarne}, {$iTipoModeloRecibo}";
    } else if ($lEmissaoRecibo) {
      $iTipoModeloReciboQuery = "{$iTipoModeloRecibo}";
    } else if ($lEmissaoCarne) {
      $iTipoModeloReciboQuery = "{$iTipoModeloCarne}";
    }else {
      throw new Exception("Tipo de regra de emissão não encontrado");
    }

    $sDataHoje = date("Y-m-d", db_getsession("DB_datausu") );
    $iInstit   = db_getsession("DB_instit");

    $sSqlRegraEmissao  = "select *                                                                                                                      \n ";
    $sSqlRegraEmissao .= "  from (select min(k48_sequencial) as k48_sequencial,                                                                         \n ";
    $sSqlRegraEmissao .= "               k49_tipo,                                                                                                      \n ";
    $sSqlRegraEmissao .= "               k36_ip,                                                                                                        \n ";
    $sSqlRegraEmissao .= "               k48_parcini,                                                                                                   \n ";
    $sSqlRegraEmissao .= "               k48_parcfim,                                                                                                   \n ";
    $sSqlRegraEmissao .= "               k48_cadconvenio, k48_cadtipomod,                                                                               \n ";
    $sSqlRegraEmissao .= "               ar11_cadtipoconvenio, k03_tipo                                                                                 \n ";
    $sSqlRegraEmissao .= "          from modcarnepadrao                                                                                                 \n ";
    $sSqlRegraEmissao .= "               left  join modcarnepadraotipo on modcarnepadraotipo.k49_modcarnepadrao = modcarnepadrao.k48_sequencial         \n ";
    $sSqlRegraEmissao .= "               left  join modcarneexcessao   on modcarneexcessao.k36_modcarnepadrao   = modcarnepadrao.k48_sequencial         \n ";
    $sSqlRegraEmissao .= "               inner join cadconvenio        on cadconvenio.ar11_sequencial           = modcarnepadrao.k48_cadconvenio        \n ";
    $sSqlRegraEmissao .= "               left  join arretipo           on modcarnepadraotipo.k49_tipo           = arretipo.k00_tipo                     \n ";
    $sSqlRegraEmissao .= "         where '$sDataHoje' between  k48_dataini and k48_datafim                                                              \n ";
    $sSqlRegraEmissao .= "           and k48_instit     = {$iInstit}                                                                                    \n ";
    $sSqlRegraEmissao .= "           and ( case                                                                                                         \n ";
    $sSqlRegraEmissao .= "                   when modcarnepadraotipo.k49_modcarnepadrao is not null then                                                \n ";
    $sSqlRegraEmissao .= "                     modcarnepadraotipo.k49_tipo = {$this->tipo_debito}                                         \n ";
    $sSqlRegraEmissao .= "                   else true                                                                                                  \n ";
    $sSqlRegraEmissao .= "                 end )                                                                                                        \n ";
    $sSqlRegraEmissao .= "           and ( case                                                                                                         \n ";
    $sSqlRegraEmissao .= "                   when modcarneexcessao.k36_modcarnepadrao is not null then                                                  \n ";
    $sSqlRegraEmissao .= "                     modcarneexcessao.k36_ip = '".db_getsession('DB_ip')."'                                                   \n ";
    $sSqlRegraEmissao .= "                   else true                                                                                                  \n ";
    $sSqlRegraEmissao .= "                 end )                                                                                                        \n ";
    $sSqlRegraEmissao .= "           and (                                                                                                              \n ";
    $sSqlRegraEmissao .= "                {$oRetorno->iMaximoParcelasGeral} between k48_parcini and k48_parcfim                                         \n ";
    $sSqlRegraEmissao .= "                 or                                                                                                           \n ";
    $sSqlRegraEmissao .= "                {$oRetorno->iMinimoParcelasGeral} between k48_parcini and k48_parcfim                                         \n ";
    $sSqlRegraEmissao .= "               )                                                                                                              \n ";
    $sSqlRegraEmissao .= "           and k48_cadtipomod in ({$iTipoModeloReciboQuery})                                                                  \n ";
    $sSqlRegraEmissao .= "         group by k49_tipo, k36_ip, k48_parcini, k48_parcfim, k48_cadconvenio, ar11_cadtipoconvenio, k03_tipo, k48_cadtipomod \n ";
    $sSqlRegraEmissao .= "       ) as x                                                                    \n ";
    $rsSqlRegraEmissao = db_query($sSqlRegraEmissao);

    if ( !$rsSqlRegraEmissao ) {
      throw new Exception(pg_last_error());
    }

    $iRowsRegraEmissao = pg_numrows($rsSqlRegraEmissao);

    /**
     * Valida se existe alguma regra de emissao cadastrada no sistema
     */
    if ($iRowsRegraEmissao > 0) {

      $aRegrasEmissao           = db_utils::getCollectionByRecord($rsSqlRegraEmissao);
      $aRegrasEmissaoEspecifica = array();
      $aRegrasEmissaoGeral      = array();

      /**
       * Separa as regras de emissao se são regras gerais e regras especificas para tipo de débito
       */
      foreach ($aRegrasEmissao as $iIndiceRegra => $oRegraEmissao) {

        if ($oRegraEmissao->k49_tipo != "" || $oRegraEmissao->k36_ip != "") {
          $aRegrasEmissaoEspecifica[] = $oRegraEmissao;
        } else {
          $aRegrasEmissaoGeral[] = $oRegraEmissao;
        }
      }

      if (count($aRegrasEmissaoEspecifica) > 0 ) {
        $aRegrasEmissao = $aRegrasEmissaoEspecifica;
      } else {
        $aRegrasEmissao = $aRegrasEmissaoGeral;
      }

      /**
       * Percorre as regras selecionadas
       */
      $aDadosCompletos = array();

      foreach ($aRegrasEmissao as $iIndiceRegra => $oRegraEmissao) {

        /**
         * valida se tipo de convenio é refente cobrança
         */
        if ($oRegraEmissao->ar11_cadtipoconvenio == 7) {
          $lCobrancaRegistrada = true;
        } else {
          $lCobrancaRegistrada = false;
        }

        /**
         * Instancia novo recibo para ser gerado ou não
         */
        if ($lCobrancaRegistrada || $sGeraCarne == "") {
          $oRecibo = new recibo(2, null, 1);
        }

        $aRecibos[$oRegraEmissao->k48_sequencial] = "";

        /**
         * Cria array com os debitos selecionados para serem comparados posteriormente
         */
        foreach ($aChecks as $iInd => $aVal) {

          if ( ($aVal["Numpar"] >= $oRegraEmissao->k48_parcini) && ($aVal["Numpar"] <= $oRegraEmissao->k48_parcfim) ) {

            $aRecibos[$oRegraEmissao->k48_sequencial][]         =  "(k00_numpre in({$aVal['Numpre']}) and k00_numpar = {$aVal['Numpar']})";
            $aCompara[$oRegraEmissao->k48_sequencial][]         = $aVal['Numpre'].str_pad($aVal['Numpar'], 3, 0, STR_PAD_LEFT);

            $aNumparCompara[$oRegraEmissao->k48_sequencial][]   = $aVal['Numpar'];
            $aNumpreCompara[$oRegraEmissao->k48_sequencial][]   = $aVal['Numpre'];
            $aNumpres_emissao[$oRegraEmissao->k48_sequencial][] = array($aVal['Numpre'], $aVal['Numpar']);

          }
        }

        $aNumpresRecibo  = array();
        $aParcelasRecibo = array();

        /**
         * Percorre os debitos selecionados
         */
        foreach ($aChecks as $iIndice => $aValores) {

          $aNumpresRecibo[]   = $aValores["Numpre"];
          $aParcelasRecibo[]  = $aValores["Numpar"];

          /**
           * Valida se as parcelas da regra de emissao conferem com as parcelas do numpre
           */
          if ( ($aValores["Numpar"] >= $oRegraEmissao->k48_parcini) && ($aValores["Numpar"] <= $oRegraEmissao->k48_parcfim) ) {

            /**
             * Se for emissao de recibo, adiciona numpre ao recibo caso contrário apenas seta parâmetro para emissao de carne
             */
            if ( ( $lCobrancaRegistrada || empty($sGeraCarne)) &&  $oRegraEmissao->k48_cadtipomod == $iTipoModeloRecibo ) {

              /**
               * Valida o processamento do desconto por parcela
               */
              if ($this->processarDescontoRecibo == 'true') {

                $iTotalRegistros = $oDebitosFormulario->oReciboDesconto->iTotalRegistros;
                $nValorDesconto  = $this->reciboDesconto(
                  $aValores["Numpre"],
                  $aValores["Numpar"],
                  $oDebitosFormulario->oReciboDesconto->iTipoDebito,
                  $oDebitosFormulario->oReciboDesconto->iTipoDebito,
                  $oDebitosFormulario->oReciboDesconto->sWhereLoteador,
                  $oDebitosFormulario->oReciboDesconto->iTotalSelecionados,
                  $iTotalRegistros,
                  $this->ver_matric,
                  $this->ver_inscr
                );

              } else {
                $nValorDesconto  = 0;
              }

              $oRetorno->nValorDesconto = $nValorDesconto;

              /**
               * Adiciona numpre e numpar ao recibo
               */
              $oRecibo->setDescontoReciboWeb($aValores["Numpre"], $aValores["Numpar"], $nValorDesconto);
              $oRecibo->addNumpre($aValores["Numpre"], $aValores["Numpar"]);

              /**
               * Se o parametro estiver habilitado
               * lista os recibos válidos emtidos
               */
              if ($lConfReemissaoRecibo) {

                $sSqlRecibosEmitidos = " select distinct k00_numnov                                                   \n";
                $sSqlRecibosEmitidos.= "   from recibopaga                                                            \n";
                $sSqlRecibosEmitidos.= "  where k00_dtpaga >= '{$dtOperacao}'                                         \n";
                $sSqlRecibosEmitidos.= "    and k00_numpre =  {$aValores["Numpre"]}                                   \n";
                $sSqlRecibosEmitidos.= "    and k00_numpar =  {$aValores["Numpar"]}                                   \n";
                $sSqlRecibosEmitidos.= "    and not exists (select 1                                                  \n";
                $sSqlRecibosEmitidos.= "                      from cancrecibopaga                                     \n";
                $sSqlRecibosEmitidos.= "                     where cancrecibopaga.k134_numnov = recibopaga.k00_numnov)\n";

                $rsSqlRecibosEmitidos   = db_query($sSqlRecibosEmitidos);
                $aRecibosEmitidos       = db_utils::getCollectionByRecord($rsSqlRecibosEmitidos);
                foreach ($aRecibosEmitidos as $oReciboEmitido) {
                  $aRecibopaga_numnov[] = $oReciboEmitido->k00_numnov;
                }
              }
            } else {
              $aDadosCarne[$iIndiceRegra]["geracarne"]   = $sGeraCarne;
            }

            /**
             * Cria array de dados que serão gravados na sessao
             * Tambem cria um array das parcelas para serem utilizadas valores maximos e minimos da regra de emissao
             */
            $aDadosCarne[$iIndiceRegra]["numpres_emissao"][] = array($aValores["Numpre"], $aValores["Numpar"]);
            $aDadosCarne[$iIndiceRegra]["convenio"]          = $oRegraEmissao->ar11_cadtipoconvenio;
            $aDadosCarne[$iIndiceRegra][$iIndice]            = $aValores["valor"];
            $aParcelasSeparadas[$iIndiceRegra][]             = $aValores["Numpar"];

          }
        }

        /**
         * Valida os se existem recibos emitidos para o numpre e numpar selecionados
         * depois cria array de numpres e parcelas por numnov
         */
        $sSqlNumNov   = " select distinct array_to_string(array_accum(distinct k00_numnov), ',') as k00_numnov   ";
        $sSqlNumNov  .= "   from recibopaga                                                                     ";
        $sSqlNumNov  .= "  where k00_dtpaga = '".$dVencimento."'                                  ";
        $sSqlNumNov  .= "    and (".implode(" or ", $aRecibos[$oRegraEmissao->k48_sequencial]).")                   ";
        $sSqlNumNov  .= "    and not exists (select 1 from cancrecibopaga where cancrecibopaga.k134_numnov = recibopaga.k00_numnov) ";
        $rsSqlNumNov  = db_query($sSqlNumNov);
        $sNumNov      = db_utils::fieldsMemory($rsSqlNumNov, 0)->k00_numnov;

        $iDiferenca = array();

        foreach(explode(",", $sNumNov) as $sNumNovEmitido) {

          if ($sNumNovEmitido != ""  && $lConfReemissaoRecibo) {
            $aRecibopaga_numnov[] = $sNumNovEmitido;
          }
        }

        if ($sNumNov != "") {

          $sSqlNumpreNumpar  = " select k00_numnov, array_to_string(array_accum(distinct (k00_numpre||lpad(k00_numpar, 3, 0) )), '|') as numpre_numpar  ";
          $sSqlNumpreNumpar .= "   from recibopaga                                                                                                 ";
          $sSqlNumpreNumpar .= "  where k00_numnov in ({$sNumNov})                                                                                 ";
          $sSqlNumpreNumpar .= "    and not exists (select 1 from cancrecibopaga where cancrecibopaga.k134_numnov = recibopaga.k00_numnov)         ";
          $sSqlNumpreNumpar .= "  group by k00_numnov order by k00_numnov;                                                                         ";
          $rsSqlNumpreNumpar = db_query($sSqlNumpreNumpar);
          $aNumpreNumpar     = db_utils::getCollectionByRecord($rsSqlNumpreNumpar);

          foreach ($aNumpreNumpar as $oNumpreNumpar) {

            /**
             * Compara o array de debitos e parcelas selecionadas e debitos e parcelas emitidas
             * Caso exista caso para comparação apenas faz reemissao do recibo
             * Caso algum numpre ou numpar esteja faltanto ou sobrando emite um novo recibo.
             */
            $aComparaBanco = explode("|", $oNumpreNumpar->numpre_numpar);
            $iDiferenca[]  = count(array_diff($aCompara[$oRegraEmissao->k48_sequencial], $aComparaBanco) ) +
              count(array_diff($aComparaBanco, $aCompara[$oRegraEmissao->k48_sequencial]) );

          }
        }

        if ( (!in_array(0, $iDiferenca) && ($lCobrancaRegistrada || $sGeraCarne == "") && $this->lNovoRecibo) || ($sGeraCarne == "" && !$lConfReemissaoRecibo ) ) {

          /**
           * Valida se foram vinculados debitos ao recibo criado,
           * se verdadeiro tenta gerar o recibo
           */

          if (count($oRecibo->getDebitosRecibo() ) > 0) {

            try {

              /**
               * Valida vencimentos do recibo
               */
              if (!$lForcaVencimento) {

                /**
                 *
                 * Caso a data do sistema foi maior que as datas de vencimento, significa que parcelas estão vencidas e
                 * a data de vencimento será a data do sistema. Caso a data do sistema for menor que a data de vencimento
                 * a data de vencimento será a menor dentre as das parcelas selecionadas
                 */

                $sSqlVenc  = "select case                                                   ";
                $sSqlVenc .= "         when min(k00_dtvenc) <= '{$sDataHoje}'::date         ";
                $sSqlVenc .= "           then '{$sDataHoje}'::date                          ";
                $sSqlVenc .= "         else min(k00_dtvenc)                                 ";
                $sSqlVenc .= "       end as k00_dtvenc                                      ";
                $sSqlVenc .= "from arrecad                                                  ";
                $sSqlVenc .= "where k00_numpre in (".implode(", ", $aNumpresRecibo).")      ";
                $sSqlVenc .= "  and k00_numpar in (".implode(", ", $aParcelasRecibo).")     ";

                $rsVencimento = db_query($sSqlVenc);
                $dtDataVenc   = db_utils::fieldsMemory($rsVencimento, 0)->k00_dtvenc;

                if ( db_strtotime($dtDataVenc) > db_strtotime($dVencimento) || $dVencimento == "" ) {
                  $dVencimento = $dtDataVenc;
                }

                // @note: não usado pelo nfse (e-cidadeonline2)
                if ( ( $this->k03_tipo == 5 or $this->k03_tipo == 18 ) and $oConfig->db21_codcli == 19985 ) {

                  $db_datausu = date("Y-m-d",db_getsession("DB_datausu"));

                  $sUltimoDiaMenos5   = "select ultimo_dia - '5 day'::interval as ultimo_dia_menos_5 ";
                  $sUltimoDiaMenos5  .= "  from ( select ( substr(proximo_mes::text,1,7) || '-01'::text)::date - '1 day'::interval as ultimo_dia ";
                  $sUltimoDiaMenos5  .= "           from ( select '$db_datausu'::date + '1 month'::interval as proximo_mes ) as x ) as y ";
                  $rsUltimoDiaMenos5 = db_query($sUltimoDiaMenos5);
                  $oUltimoDiaMenos5  = db_utils::fieldsMemory($rsUltimoDiaMenos5,0);

                  $db_datausu_dia = substr($db_datausu,8,2);
                  $oUltimoDiaMenos5->dia = substr( $oUltimoDiaMenos5->ultimo_dia_menos_5,8,2);

                  if ( $db_datausu_dia > $oUltimoDiaMenos5->dia ) {
                    $iSomaDia = 2;
                  } else {
                    $iSomaDia = 1;
                  }

                  $sUltimoDia   = "select ( substr(proximo_mes::text,1,7) || '-01'::text)::date - '1 day'::interval as ultimo_dia ";
                  $sUltimoDia  .= "  from ( select '$db_datausu'::date + '$iSomaDia month'::interval as proximo_mes ) as x";
                  $rsUltimoDia = db_query($sUltimoDia);
                  $oUltimoDia  = db_utils::fieldsMemory($rsUltimoDia,0);
                  $db_datausu  = $oUltimoDia->ultimo_dia;

                  $db_datausu_mes = substr($db_datausu,5,2);
                  $db_datausu_dia = substr($db_datausu,8,2);
                  $db_datausu_ano = substr($db_datausu,0,4);
                  $dVencimento = $db_datausu;

                }

              }

              //@note: se vencimento é no ano seguinte, vencimento é ultimo dia do ano
              if (db_strtotime($dVencimento) > db_strtotime(db_getsession('DB_anousu')."-12-31")) {
                $dVencimento = db_getsession('DB_anousu')."-12-31";
              }

              /**
               * @note: gera Recibo
               */
              if ( !empty($oRegraEmissao->ar13_sequencial) ) {
                $iCodigoConvenioCobranca = $oRegraEmissao->ar13_sequencial;
              } else {
                $iCodigoConvenioCobranca = 0;
              }

              /*@note: Verifica se for de cobranca registrada*/
              $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->k48_cadconvenio);

              /*
                @note: Se cobranca registrada nao for por webservice e data de vencimento = data de operacao,
                entao data de vencimento + 1
              */
              if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->k48_cadconvenio) && (db_strtotime($dtOperacao) == db_strtotime($dVencimento))) {
                $dVencimento = date('Y-m-d', strtotime("+1 day",strtotime($dVencimento)));
              }

              $oRecibo->setNumBco($iCodigoConvenioCobranca);
              $oRecibo->setDataRecibo($dtOperacao);

              $oRecibo->setDataVencimentoRecibo($dVencimento);
              $oRecibo->setExercicioRecibo(substr($dVencimento, 0, 4) );
              $oRecibo->emiteRecibo($lConvenioCobrancaValido);

              $k03_numnov           = $oRecibo->getNumpreRecibo();
              $aRecibopaga_numnov[] = $k03_numnov;

              if (in_array($oRegraEmissao->k03_tipo, $aTipoInicial) && $oRegraEmissao->ar11_cadtipoconvenio == 7) {

                /**
                 * Valida se existe custas para o recibo gerado
                 */
                $sSqlValidaCustas     = $oDaoProcessoForoPartilhaCusta->sql_query_file("", "*", "", "v77_numnov ={$oRecibo->getNumpreRecibo()} ");
                $rsSqlValidaCustas    = $oDaoProcessoForoPartilhaCusta->sql_record($sSqlValidaCustas);
                $iNumRowsValidaCustas = $oDaoProcessoForoPartilhaCusta->numrows;

                /**
                 * Busca o processo do foro conforme tipo de débito
                 */
                if ($oRegraEmissao->k03_tipo == 13) {

                  $sSqlProcessosForo  = "select distinct processoforoinicial.v71_processoforo                ";
                  $sSqlProcessosForo .= "  from termo                                                        ";
                  $sSqlProcessosForo .= "       inner join termoini            on parcel  = v07_parcel       ";
                  $sSqlProcessosForo .= "       inner join processoforoinicial on inicial = v71_inicial      ";
                  $sSqlProcessosForo .= " where v07_numpre = {$aValores["Numpre"]}                           ";

                } elseif ($oRegraEmissao->k03_tipo == 12 || $oRegraEmissao->k03_tipo == 18) {

                  $sSqlProcessosForo  = "select distinct processoforoinicial.v71_processoforo                ";
                  $sSqlProcessosForo .= "  from inicialnumpre                                                ";
                  $sSqlProcessosForo .= "       inner join processoforoinicial on v71_inicial = v59_inicial  ";
                  $sSqlProcessosForo .= " where v59_inicial in (".implode(", ", $aIniciais).")               ";
                }

                $rsSqlProcessosForo = db_query($sSqlProcessosForo);
                $iProcessosForo     = pg_num_rows($rsSqlProcessosForo);

                $nValorTotalCustas = 0.00;

                /**
                 * Valida se existem processos do foro e se não existem custas vinculadas a ele.
                 */
                if ($iProcessosForo > 0 || $iNumRowsValidaCustas == 0) {

                  $aProcessosForo   = db_utils::getCollectionByRecord($rsSqlProcessosForo);

                  foreach ($aProcessosForo as $oProcessoForo) {

                    /**
                     * Verifica se existe lançamento manual ou isenção
                     * do processo do foro na tabela processoforopartilha
                     */
                    $sWherePartilhaProcesso    = " v76_processoforo = {$oProcessoForo->v71_processoforo} and ";
                    $sWherePartilhaProcesso   .= " ( v76_dtpagamento is not null or                          ";
                    $sWherePartilhaProcesso   .= "   v76_tipolancamento in(2, 3)                             ";
                    $sWherePartilhaProcesso   .= " )                                                         ";

                    $sSqlPartilhaProcesso      = $oDaoProcessoForoPartilha->sql_query("",
                      "v76_sequencial",
                      "",
                      $sWherePartilhaProcesso
                    );
                    $rsSqlPartilhaProcesso     = db_query($sSqlPartilhaProcesso);
                    $iNumRowsPartilhaProcesso  = pg_numrows($rsSqlPartilhaProcesso);

                    if ($rsSqlPartilhaProcesso && $iNumRowsPartilhaProcesso == 0) {

                      /**
                       * Busca as taxas referente ao codigo do convenio
                       */
                      $sSqlTaxas  = "   select taxa.*                                                                   ";
                      $sSqlTaxas .= "     from cadconvenio                                                              ";
                      $sSqlTaxas .= "          inner join cadconveniogrupotaxa on ar39_cadconvenio = ar11_sequencial    ";
                      $sSqlTaxas .= "          inner join grupotaxa            on ar37_sequencial  = ar39_grupotaxa     ";
                      $sSqlTaxas .= "          inner join taxa                 on ar36_grupotaxa   = ar37_sequencial    ";
                      $sSqlTaxas .= "    where ar11_cadtipoconvenio = {$oRegraEmissao->ar11_cadtipoconvenio};           ";

                      $rsSqlTaxas = db_query($sSqlTaxas);

                      $aTaxas     = db_utils::getCollectionByRecord($rsSqlTaxas);

                      /**
                       * Calcula o valor total de débitos no arrecad referentes ao processo do foro
                       */
                      $dtEmissao    = date("Y-m-d", db_getsession('DB_datausu') );
                      $tsVencimento = db_strtotime($dVencimento);
                      $iAno         = date("Y", $tsVencimento);
                      $dtVencimento = date("Y-m-d", $tsVencimento);

                      $nTotalDebito = $oDaoProcessoForoPartilhaCusta->getCustasProcesso(
                        $oRecibo->getNumpreRecibo(),
                        $oProcessoForo->v71_processoforo,
                        $dtVencimento,
                        $oArretipo->k03_tipo,
                        $this->tipo_debito,
                        $lCarne
                      );

                      $oDaoProcessoForoPartilha->v76_processoforo   = $oProcessoForo->v71_processoforo;
                      $oDaoProcessoForoPartilha->v76_tipolancamento = 1;
                      $oDaoProcessoForoPartilha->v76_dtpagamento    = null;
                      $oDaoProcessoForoPartilha->v76_obs            = null;
                      $oDaoProcessoForoPartilha->v76_obs            = null;
                      $oDaoProcessoForoPartilha->v76_valorpartilha  = $nTotalDebito;
                      $oDaoProcessoForoPartilha->v76_datapartilha   = $oDataSessao->getDate();
                      $oDaoProcessoForoPartilha->incluir(null);

                      if ($oDaoProcessoForoPartilha->erro_status == "0") {
                        throw new Exception($oDaoProcessoForoPartilha->erro_msg);
                      }

                      foreach ($aTaxas as $oTaxa) {

                        if ($oTaxa->ar36_perc == 0) {
                          $nValorCusta = $oTaxa->ar36_valor;
                        } else {

                          $oValor               = new DBNumber();
                          $nVlrPercentualDebito = $oValor->truncate($nTotalDebito * ($oTaxa->ar36_perc / 100), 2);

                          /**
                           * Verifica se valor do percentual do débito é maior que maximo ou minimo permitido
                           * caso ele ultrapasse um dos limites o valor da taxa será o limite
                           * caso contrario sera o resultado da operaçao
                           */
                          if ($nVlrPercentualDebito > $oTaxa->ar36_valormax) {
                            $nValorCusta = $oTaxa->ar36_valormax;
                          } elseif ($nVlrPercentualDebito < $oTaxa->ar36_valormin) {
                            $nValorCusta = $oTaxa->ar36_valormin;
                          } else {
                            $nValorCusta = $nVlrPercentualDebito;
                          }
                        }

                        $nValorTotalCustas                                       = $nValorCusta;
                        $oDaoProcessoForoPartilhaCusta->v77_taxa                 = $oTaxa->ar36_sequencial;
                        $oDaoProcessoForoPartilhaCusta->v77_processoforopartilha = $oDaoProcessoForoPartilha->v76_sequencial;
                        $oDaoProcessoForoPartilhaCusta->v77_valor                = $nValorCusta;
                        $oDaoProcessoForoPartilhaCusta->v77_numnov               = $oRecibo->getNumpreRecibo();
                        $oDaoProcessoForoPartilhaCusta->incluir("");

                        if ($oDaoProcessoForoPartilhaCusta->erro_status == "0") {
                          throw new Exception($oDaoProcessoForoPartilhaCusta->erro_msg);
                        }
                      }
                      /**
                       * Adiciona ao array o Recibo caso sejam emitidas custas para o mesmo
                       */
                      $oDadosEnvio                 = new stdClass();
                      $oDadosEnvio->iProcessoForo  = $oProcessoForo->v71_processoforo;
                      $oDadosEnvio->oRecibo        = $oRecibo;
                      $aRecibosComCustasEmitidos[] = $oDadosEnvio;
                      unset($oDadosEnvio);
                    }//

                  }//FOREACH

                }//Fim validação Processo Foro

                /**
                 *  Geração do Convenio de Cobrança
                 */
                db_app::import('convenio');

              }//Validação regra de emissao com as parcelas do numpre e numpar

                if (!isset($nValorTotalCustas) && empty($nValorTotalCustas)) {
                  $nValorTotalCustas = 0.00;
                }

                $nValorRecibo       = $oRecibo->getTotalRecibo() + $nValorTotalCustas;
                $sValorCodigoBarras = str_pad( number_format( $nValorRecibo, 2, "", "" ), 11, "0", STR_PAD_LEFT);

                $oConvenio = new convenio(
                  $oRegraEmissao->k48_cadconvenio,
                  $oRecibo->getNumpreRecibo(),
                  0,
                  $nValorRecibo,
                  $sValorCodigoBarras,
                  $oRecibo->getDataRecibo(),
                  $oArretipo->k00_tercdigrecnormal
                );

                /*@note: Se convenio cobranca adiciona a fila de remessa */

                if ($lConvenioCobrancaValido) {

                  if ( CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->k48_cadconvenio) ){
                    CobrancaRegistrada::registrarReciboWebservice($oRecibo->getNumpreRecibo(), $oRegraEmissao->k48_cadconvenio, $oRecibo->getTotalRecibo(), true);
                  } else {
                    CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->k48_cadconvenio);
                  }
                }

            } catch ( Exception $eException ) {
              throw new Exception($eException->getMessage());
            }
          }
        }

        /**
         * Mescla os dados do array com os dados especificos e os com dados que devem ficar em
         * todos os arrays
         */
        $aDadosCompletos[$iIndiceRegra]             = array_merge($aDadosCarne[$iIndiceRegra], $aDadosForm );
        $aDadosCompletos[$iIndiceRegra]['iParcIni'] = min($aParcelasSeparadas[$iIndiceRegra]);
        $aDadosCompletos[$iIndiceRegra]['iParcFim'] = max($aParcelasSeparadas[$iIndiceRegra]);

        /**
         * Define o nome das sessoes
         */
        if (($lCobrancaRegistrada || $sGeraCarne == "") && $oRegraEmissao->k48_cadtipomod == $iTipoModeloRecibo) {

          $aDadosCompletos[$iIndiceRegra]["iModeloRecibo"] = $iTipoModeloRecibo;
          db_putsession("RequestRecibo".$iIndiceRegra, $aDadosCompletos[$iIndiceRegra]);
          $oRetorno->aSessoesRecibo[] = "RequestRecibo".$iIndiceRegra;

        } elseif ( ( !$lCobrancaRegistrada && $sGeraCarne != "") && $oRegraEmissao->k48_cadtipomod == $iTipoModeloCarne && $this->lNovoRecibo ) {

          db_putsession("RequestCarne".$iIndiceRegra, $aDadosCompletos[$iIndiceRegra]);
          $oRetorno->aSessoesCarne[] = "RequestCarne".$iIndiceRegra;

        }

      } //FOREACH QUE PERCORRE REGRAS DE EMISSAO

      /**
       * @todo manter?
       * Integração Webservice com TJ RJ
       */
      if (count($aRecibosComCustasEmitidos) > 0 ) {

        include(modification('cai3_integracaotjemissao001.php'));
      }

    } else {

      throw new Exception("Erro: Nenhuma regra cadastrada para este tipo de débito ({$this->tipo_debito}). \nVerifique Parâmetros.");
    }

    $oRetorno->recibos_emitidos  = array_unique($aRecibopaga_numnov);

    return $oRetorno;
  }

  /**
   * Retorna os Débitos  selecionados no formulário da CGF
   * Com minimo e maximo de parcelas array com combinação de numpre, numpar e receita e string de retorno
   *
   * @param object   $oFormulario - Objeto contentdo os dados do Formulário da CGF
   * @param integer  $iTipoAgrupamento - Tipo de Agrupamento do Tipo de Débito
   */
  public function retornaDebitosSelecionados($oFormulario,$iTipoAgrupamento = null) {

    $aChecks            = array();
    $aParcelas          = array();
    $aDadosForm         = array();
    $sGeraCarne         = "";
    $iI                 = 0;
    $sRecibos           = '';
    $aInicial           = array();
    $iTotalSelecionados = 0;
    $aObjDebitos        = array();

    /**
     * Valida se é uma inicial do Foro
     */
    if (isset($oFormulario->inicial) ) {

      foreach ($oFormulario->aDadosNumpre as $sChave => $sValor) {

        if ( stripos(" ".$sChave, "CHECK") ) {

          $iTotalSelecionados++;
          $aInicial[]   = $sValor;

          $sSqlInicial  = " select distinct                                                              ";
          $sSqlInicial .= "        arrecad.k00_numpre,                                                   ";
          $sSqlInicial .= "         arrecad.k00_numpar                                                   ";
          $sSqlInicial .= "    from inicialnumpre                                                        ";
          $sSqlInicial .= "          inner join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre ";
          $sSqlInicial .= "  where v59_inicial in (".implode(", ", $aInicial).");                          ";
          $rsSqlInicial = db_query($sSqlInicial);
          $aIniciais  = db_utils::getCollectionByRecord($rsSqlInicial);

          foreach ($aIniciais as $oInicial) {

            $aParcelas[]          = $oInicial->k00_numpar;
            $sValores             = "N". $oInicial->k00_numpre."P".$oInicial->k00_numpar."R0";
            $aChecks["CHECK".$iI] = array("Numpre"=>$oInicial->k00_numpre, "Numpar"=>$oInicial->k00_numpar, "Receita"=>"0", "valor"=>$sValores);

            /**
             * Cria array com os numpres e numpar dos débitos
             */
            $oNumpreNumpar          = new stdClass();
            $oNumpreNumpar->iNumpre = $oInicial->k00_numpre;
            $oNumpreNumpar->iNumpar = $oInicial->k00_numpar;
            $aObjDebitos[]          = $oNumpreNumpar;


            $aNumpreValidacao[]   = $oInicial->k00_numpre;
            $aNumparValidacao[]   = $oInicial->k00_numpar;

            $iI++;
          }

        } else {

          /**
           * Formata string Gera Carne
           */
          if ($sChave == "geracarne") {
            $sGeraCarne = $sValor;
          } else {

            if (is_array($sValor) ) {
              $aDadosForm[$sChave] = $sValor[0];
            } else {
              $aDadosForm[$sChave] = $sValor;
            }
          }
        }
      }
    } else {

      foreach ($oFormulario->aDadosNumpre as $sChave => $sValor) {

        if ( stripos(" ".$sChave, "CHECK") ) {

          $aNumpre = split("N", $sValor);

          foreach ($aNumpre as $iIndiceNumpre => $sNumpres) {

            if ($sNumpres == "") {
              continue;
            }
            if($sNumpres != "") {
              $iTotalSelecionados++;
            }
            $aParcela               = split("P", $sNumpres);
            $iNumpre                = $aParcela[0];
            $aSliceParcela          = split("R", $aParcela[1]);
            $iNumpar                = (int)$aSliceParcela[0];
            $iReceita               = (int)$aSliceParcela[1];

            $aParcelas[]            = $iNumpar;
            $aChecks["CHECK".$iI]   = array("Numpre"=>$iNumpre, "Numpar"=>$iNumpar, "Receita"=>$iReceita, "valor"=>"N".$sNumpres);

            /**
             * Cria array com os numpres e numpar dos débitos
             */
            $oNumpreNumpar          = new stdClass();
            $oNumpreNumpar->iNumpre = $iNumpre;
            $oNumpreNumpar->iNumpar = $iNumpar;
            $aObjDebitos[]          = $oNumpreNumpar;

            $aNumpreValidacao[]     = $iNumpre;
            $aNumparValidacao[]     = $iNumpar;
            $sRecibos              .= " or (k00_numpre = {$iNumpre} and k00_numpar = {$iNumpar})" ;
            $iI++;
          }

        } else {

          /**
           * Formata string Gera Carne
           */
          if ($sChave == "geracarne") {
            $sGeraCarne = $sValor;
          } elseif ($sChave == "numpre_unica") {

            $aDadosForm[$sChave]    = $sValor;
            if(!empty($oFormulario->numpre_unica)){

              $aParcelas[]            = 0;
              $aChecks["CHECKU".$iI]   = array("Numpre"=>$oFormulario->numpre_unica, "Numpar"=>0, "Receita"=>0, "valor"=>"N0");

              $oNumpreNumpar          = new stdClass();
              $oNumpreNumpar->iNumpre = $oFormulario->numpre_unica;
              $oNumpreNumpar->iNumpar = 0;
              $aObjDebitos[]          = $oNumpreNumpar;

              $aNumpreValidacao[]     = $oFormulario->numpre_unica;
              $aNumparValidacao[]     = '0';
              $sRecibos              .= " or (k00_numpre = {$oFormulario->numpre_unica} and k00_numpar = {0})";
            }
          } else {

            if (is_array($sValor) ) {
              $aDadosForm[$sChave] = $sValor[0];
            } else {
              $aDadosForm[$sChave] = $sValor;
            }
          }
        }
      }
    }

    /**
     * Caso o agrupamento do débito seja do tipo 2 (Parcial)
     * Adiciona os débitos do arrecad ao recibo.
     */
    if ($iTipoAgrupamento == 2 && empty($oFormulario->geracarne)) {

      $oParametroConsulta = new stdClass();

      if(!empty($aDadosForm['ver_matric']) ) {

        $sTabela    = "arrematric";
        $sConsulta  = "k00_matric = {$aDadosForm['ver_matric']}";

      } elseif (!empty($aDadosForm['ver_inscr']) ) {

        $sTabela    = "arreinscr";
        $sConsulta  = "k00_inscr  = {$aDadosForm['ver_inscr']}";

      } elseif (!empty($aDadosForm['ver_numcgm']) ) {

        $sTabela    = "arrenumcgm";
        $sConsulta  = "k00_numcgm = {$aDadosForm['ver_numcgm']}";
      } else {
        return false;
      }

      $aDebitosAgrupados = retornaDebitosAgrupados($aObjDebitos, $oFormulario->tipo_debito, $sTabela, $sConsulta);

      foreach ($aDebitosAgrupados as $oDebitosAgrupados) {

        $iI++;
        $oDebitosAgrupados->iNumpre;

        $aChecks["CHECK".$iI]   = array("Numpre" => $oDebitosAgrupados->iNumpre,
          "Numpar" => $oDebitosAgrupados->iNumpar,
          "Receita"=> $oDebitosAgrupados->iReceit,
          "valor"  => "N".$oDebitosAgrupados->iNumpre.
          "P".$oDebitosAgrupados->iNumpar.
          "R".$oDebitosAgrupados->iReceit
        );

        /**
         * Cria array com os numpres e numpar dos débitos
         */
        $oNumpreNumpar          = new stdClass();
        $oNumpreNumpar->iNumpre = $oDebitosAgrupados->iNumpre;
        $oNumpreNumpar->iNumpar = $oDebitosAgrupados->iNumpre;
        $aObjDebitos[]          = $oNumpreNumpar;

        $aNumpreValidacao[]     = $oDebitosAgrupados->iNumpre;
        $aNumparValidacao[]     = $oDebitosAgrupados->iNumpar;
        $sRecibos              .= " or (k00_numpre = {$oDebitosAgrupados->iNumpre} and k00_numpar = {$oDebitosAgrupados->iNumpar})" ;
      }
    }


    /**
     * Valida loteador quando matricula não estiver setada
     */
    $lLoteador = false;

    if (!empty($oFormulario->ver_numcgm) and empty($oFormulario->ver_matric) ) {

      $sSqlLoteador = "  select *                                                                   ";
      $sSqlLoteador.= "    from loteam                                                              ";
      $sSqlLoteador.= "         left join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam   ";
      $sSqlLoteador.= "   where j120_cgm = {$oFormulario->ver_numcgm}                   ";

      $rsSqlLoteador = db_query($sSqlLoteador) or die($sSqlLoteador);
      if (pg_numrows($rsSqlLoteador) > 0) {
        $lLoteador = true;
      }

    }

    $sWhereLoteador = " and k40_forma <> 3";

    if ($lLoteador == true) {
      $sWhereLoteador = " and k40_forma = 3";
    }

    $oRetornoFuncao                = new stdClass();
    $oRetornoFuncao->aDadosChecks  = $aChecks;
    $oRetornoFuncao->aOutrosDados  = $aDadosForm;
    $oRetornoFuncao->aIniciais     = $aInicial;
    $oRetornoFuncao->iMaxParc      = max($aParcelas);
    $oRetornoFuncao->iMinParc      = min($aParcelas);
    $oRetornoFuncao->sGeraCarne    = $sGeraCarne;
    $oRetornoFuncao->aValidaNumpre = $aNumpreValidacao;

    /**
     * Variaveis para chamada da função
     * recibodesconto
     */
    $oRetornoFuncao->oReciboDesconto                     = new stdClass();
    $oRetornoFuncao->oReciboDesconto->iTipoDebito        = $oFormulario->tipo_debito;
    $oRetornoFuncao->oReciboDesconto->iTotalSelecionados = $iTotalSelecionados;
    $oRetornoFuncao->oReciboDesconto->sWhereLoteador     = $sWhereLoteador;
    $oRetornoFuncao->oReciboDesconto->iTotalRegistros    = $oFormulario->totregistros;

    return $oRetornoFuncao;
  }

  function recibodesconto($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros, $ver_matric, $ver_inscr) {

    $sql_cgc = "select cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit");
    $rs_cgc = db_query($sql_cgc);
    $oConfig->cgc         = pg_result($rs_cgc,0,0);
    $oConfig->db21_codcli = pg_result($rs_cgc,0,1);

    /* testa se está em dia com IPTU */
    $iTemDesconto = 1;

    if ( ( (int) @$ver_matric > 0 or (int) @$ver_inscr > 0 ) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj

    /*
    $sIptuAberto .= "         else ";
    $sIptuAberto .= "            case when min(arrecad.k00_dtvenc) < current_date then ";
    $sIptuAberto .= "                \'p\' ";
    $sIptuAberto .= "           else ";
    $sIptuAberto .= "               case when cadtipo.k03_parcelamento is true then ";
    $sIptuAberto .= "                 \'r\' ";
    $sIptuAberto .= "               else ";
    $sIptuAberto .= "                 \'n\' ";
    $sIptuAberto .= "           end ";
    $sIptuAberto .= "        end ";
     */
      $sIptuAberto  = "";
      $sIptuAberto .= " select count(distinct k00_numpar) from ( ";
      $sIptuAberto .= " select arrecad.k00_numpre, arrecad.k00_numpar, arrejustreg.k28_numpre, k27_dias, ";
      $sIptuAberto .= "        max(k27_data) as k27_data ";
      $sIptuAberto .= " from caixa.arrecad ";
      $sIptuAberto .= " inner join caixa.arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
      if ( (int) @$ver_matric > 0 ) {
        $sIptuAberto .= " inner join caixa.arrematric on arrecad.k00_numpre = arrematric.k00_numpre ";
      } else {
        $sIptuAberto .= " inner join caixa.arreinscr on arrecad.k00_numpre = arreinscr.k00_numpre ";
      }
      $sIptuAberto .= " left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data ";
      $sIptuAberto .= "             from ( select max(k28_sequencia) as k28_sequencia, ";
      $sIptuAberto .= "                           max(k28_arrejust) as k28_arrejust, ";
      $sIptuAberto .= "                           k28_numpar, ";
      $sIptuAberto .= "                           k28_numpre ";
      $sIptuAberto .= "                     from arrejustreg ";
      $sIptuAberto .= "                     group by k28_numpre, ";
      $sIptuAberto .= "                             k28_numpar ";
      $sIptuAberto .= "                 ) as subarrejust ";
      $sIptuAberto .= "                 inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust ";
      $sIptuAberto .= "           ) as arrejustreg on arrejustreg.k28_numpre = arrecad.k00_numpre ";
      $sIptuAberto .= "                           and arrejustreg.k28_numpar = arrecad.k00_numpar ";
      if ( (int) @$ver_matric > 0 ) {
        $sIptuAberto .= " where arrecad.k00_tipo = 1 and k00_matric = $ver_matric ";
      } else {
        $sIptuAberto .= " where arrecad.k00_tipo = 2 and k00_inscr = $ver_inscr ";
      }
      $sIptuAberto .= " group by arrecad.k00_numpre, arrecad.k00_numpar, arrejustreg.k28_numpre, k27_dias ";
      $sIptuAberto .= " ) as x ";
      $sIptuAberto .= " where case when k28_numpre is not null then case when ( k27_data + k27_dias >= current_date ) then false else true end else true end ";

      $rsIptuAberto = db_query($sIptuAberto) or die($sIptuAberto);
      if ( pg_numrows($rsIptuAberto) > 0 ) {
        $iQuantAberto = pg_result($rsIptuAberto,0,0);
        if ( (int) @$ver_matric > 0 ) {
          $iParcTesta = 2;
        } else {
          $iParcTesta = 0;
        }

        if ( $iQuantAberto > $iParcTesta ) {
          $iTemDesconto = 0;
        }
      }
    }


    // desconto
    global $k00_dtvenc, $k40_codigo, $k40_todasmarc, $cadtipoparc;

    $cadtipoparc = 0;

    $sqlvenc = "select k00_dtvenc
      from arrecad
      where k00_numpre = $numpre
      and k00_numpar = $numpar";
    $resultvenc = db_query($sqlvenc) or die($sqlvenc);
    if (pg_numrows($resultvenc) == 0) {
      return 0;
    }
    db_fieldsmemory($resultvenc, 0);

    $dDataUsu = date("Y-m-d",db_getsession("DB_datausu"));

    $sqltipoparc = "select k40_codigo,
      k40_todasmarc,
      cadtipoparc
      from tipoparc
      inner join cadtipoparc    on cadtipoparc     = k40_codigo
      inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
      where maxparc = 1
      and '{$dDataUsu}' >= k40_dtini
      and '{$dDataUsu}' <= k40_dtfim
      and k41_arretipo   = $tipo $whereloteador
      and '$k00_dtvenc' >= k41_vencini
      and '$k00_dtvenc' <= k41_vencfim ";
    if ( ( (int) @$ver_matric > 0 or (int) @$ver_inscr > 0 ) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj
      $sqltipoparc .= " and case when $iTemDesconto = 0 and k40_codigo = 3 then false else true end ";
    }

    $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
    if (pg_numrows($resulttipoparc) > 0) {
      db_fieldsmemory($resulttipoparc,0);
    } else {

      $sqltipoparc = "select k40_codigo,
        k40_todasmarc,
        cadtipoparc
        from tipoparc
        inner join cadtipoparc on cadtipoparc = k40_codigo
        inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
        where maxparc = 1
        and k41_arretipo = $tipo
        and '{$dDataUsu}' >= k40_dtini
        and '{$dDataUsu}' <= k40_dtfim
        $whereloteador
        and '$k00_dtvenc' >= k41_vencini
        and '$k00_dtvenc' <= k41_vencfim ";
      if ( ( (int) @$ver_matric > 0 or (int) @$ver_inscr > 0 ) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj
        $sqltipoparc .= " and case when $iTemDesconto = 0 and k40_codigo = 3 then false else true end ";
      }
      $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);

      if (pg_numrows($resulttipoparc) == 1) {
        db_fieldsmemory($resulttipoparc,0);
      } else {
        $k40_todasmarc = false;
      }
    }

    $sqltipoparcdeb = "select * from cadtipoparcdeb limit 1";
    $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
    $passar = false;

    if (pg_numrows($resulttipoparcdeb) == 0) {
      $passar = true;
    } else {

      $sqltipoparcdeb = "select k40_codigo, k40_todasmarc
        from cadtipoparcdeb
        inner join cadtipoparc on k40_codigo = k41_cadtipoparc
        where k41_cadtipoparc = $cadtipoparc and
        k41_arretipo = $tipo_debito $whereloteador and
        '$k00_dtvenc' >= k41_vencini and
        '$k00_dtvenc' <= k41_vencfim ";
      if ( ( (int) @$ver_matric > 0 or (int) @$ver_inscr > 0 ) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj
        $sqltipoparcdeb .= " and case when $iTemDesconto = 0 and k40_codigo = 3 then false else true end ";
      }
      $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
      if (pg_numrows($resulttipoparcdeb) > 0) {
        $passar = true;
      }
    }

    if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
      $desconto = 0;
    } else {
      $desconto = $k40_codigo;
    }

    if ( ( (int) @$ver_matric > 0 or (int) @$ver_inscr > 0 ) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj
      if ( $iTemDesconto == 1 and ( $tipo_debito == 25 or $tipo_debito == 5 or $tipo_debito == 6 or $tipo_debito == 21 or $tipo_debito == 30 or $tipo_debito == 34 ) ) {
        $desconto = 3;
      } else {
        $desconto = 2;
      }
    }

    return $desconto;

  }

}
