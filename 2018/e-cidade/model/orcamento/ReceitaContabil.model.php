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
 * Model para controle de uma receita contabil
 *
 * @author matheus.felini
 * @package orcamento
 * @version $Revision: 1.41 $
 */
class ReceitaContabil {

  /**
   * Codigo
   * @var integer
   */
  protected $iCodigo;

  /**
   * Ano
   * @var int
   */
  protected $iAno;

  /**
   * Armazena um objeto do tipo ContaOrcamento
   * @var ContaOrcamento
   */
  protected $oContaOrcamento;

  /**
   * Tipo (orctiporec)
   * @var integer
   */
  protected $iTipoRecurso;

  /**
   * @var Recurso
   */
  protected $oRecurso;

  /**
   * Valor previsto
   * @var float
   */
  protected $nValor;
  /**
   * Receita configurada como lancada
   * @var boolean
   */
  protected $lReceitaLancada;

  /**
   * Instituicao a qual a receita pertence
   * @var Instituicao
   */
  protected $oInstituicao;

  /**
   * Caracteristica Peculiar / C. Aplicacao
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Data da Criacao
   * @var string
   */
  protected $dtCriacao;

  /**
   * Codigo da conta Contabil
   * @var integer
   */
  protected $iCodigoContaContabil;


  /**
   * Desdobramentos da Receita
   * @var array
   */
  private $aDesdobramentos = array();

  /**
   * Constroi um objeto do tipo Receita Contabil
   * @param integer $iCodigo
   * @param integer $iAno
   * @throws BusinessException
   * @return ReceitaContabil
   */
  public function __construct($iCodigo = null, $iAno = null) {

    $this->iCodigo = $iCodigo;
    $this->iAno    = $iAno;
    if (!empty($iCodigo)) {

      if (empty($iAno)) {
        $iAno = db_getsession('DB_anousu');
      }

      $oDaoOrcReceita   = new cl_orcreceita();
      //$sSqlBuscaReceita = $oDaoOrcReceita->sql_query_dados_receita($iAno, $iCodigo);
      $sSqlBuscaReceita = $oDaoOrcReceita->sql_query_file($iAno, $iCodigo);
      $rsBuscaReceita   = $oDaoOrcReceita->sql_record($sSqlBuscaReceita);
      if ($oDaoOrcReceita->erro_status == "0") {
        throw new BusinessException("Não foi possível buscar a receita {$iCodigo}/{$iAno}.");
      }

      $oDadoReceita                  = db_utils::fieldsMemory($rsBuscaReceita, 0);
      $this->iCodigo                 = $iCodigo;
      $this->iAno                    = $iAno;
      $this->iCodigoContaContabil    = $oDadoReceita->o70_codfon;
      $this->iTipoRecurso            = $oDadoReceita->o70_codigo;
      $this->nValor                  = $oDadoReceita->o70_valor;
      $this->lReceitaLancada         = $oDadoReceita->o70_reclan == "t" ? true : false;
      $this->oInstituicao            = InstituicaoRepository::getInstituicaoByCodigo($oDadoReceita->o70_instit);
      $this->sCaracteristicaPeculiar = $oDadoReceita->o70_concarpeculiar;
      $this->dtCriacao               = $oDadoReceita->o70_datacriacao;
      unset($oDadoReceita);
    }
    return true;
  }

  public function processaLancamentosReceita($nValorArrecadacao=null, $iId=null, $dtAutenticacao=null, $iAutent=null,
                                             $lEstorno=false, $iContaDebito=null, $sObservacaoHistorico=null,
                                             $iCodigoPlanilha=null, $iCodigoNumPre=null, $iCodigoNumPar=null,
                                             $lDesconto=false, $iCodigoRecurso = null,
                                             $sCaracteristaPeculiarRecibo=null,
                                             $iCodigoCgm = null) {


    $oDadoSqlGeral           = new stdClass();
    $oDadoSqlGeral->arrecada = $nValorArrecadacao;

    list($iAnoAutenticacao, $iMesAutenticacao, $iDiaAutenticacao) = explode("-", $dtAutenticacao);

    $aDesdobramentos     = $this->getDesdobramentos();
    $aReceitas           = array();

    if (count($aDesdobramentos) > 0 && $this->getTipoRecurso() == 1 && $oDadoSqlGeral->arrecada != 0.01) {

      $nValorSoma  = 0;
      $lMultiplica = false;
      if ($oDadoSqlGeral->arrecada < 0) {

        $lMultiplica             = true;
        $oDadoSqlGeral->arrecada = round($oDadoSqlGeral->arrecada * -1, 2);
      }
      $iPrimeiraReceita = 0;

      foreach ($aDesdobramentos as $oDadosDesdobramento ) {

        if ($iPrimeiraReceita == 0 ) {
          $iPrimeiraReceita = $oDadosDesdobramento->codigo_receita;
        }

        $nValorPercentual = db_formatar(round($oDadoSqlGeral->arrecada * ($oDadosDesdobramento->percentual_receita / 100 ), 2), 'p') + 0;
        if ($nValorPercentual == 0) {
          continue;
        }

        $nValorSoma = round($nValorSoma + $nValorPercentual, 2);

        if ($nValorSoma > $oDadoSqlGeral->arrecada) {
          // arredonda no ultimo desdobramento
          $nValorPercentual = round($nValorPercentual -round($nValorSoma - $oDadoSqlGeral->arrecada, 2), 2);
        }

        $oDadoReceitaDesdobrada                          = clone $oDadosDesdobramento;
        $oDadoReceitaDesdobrada->valor                   = $nValorPercentual;
        $oDadoReceitaDesdobrada->tem_desobramento        = true;
        $aReceitas[$oDadosDesdobramento->codigo_receita] = $oDadoReceitaDesdobrada;
      }

      //VAlor total arrecado é menor que o total.
      if ($nValorSoma < $oDadoSqlGeral->arrecada) {

        if (!isset($aReceitas[$iPrimeiraReceita])){
          $aReceitas[$iPrimeiraReceita]->codigo_receita = 0;
        }
        $nValorPercentual                    = round($aReceitas[$iPrimeiraReceita]->valor +
                                               round($oDadoSqlGeral->arrecada - $nValorSoma, 2), 2);
        $aReceitas[$iPrimeiraReceita]->valor = round($nValorPercentual, 2);
      }

      if ($lMultiplica) {

        foreach ($aReceitas as $iCodigoReceita => $oDadoReceita) {
          $aReceitas[$iCodigoReceita]->valor = round($oDadoReceita->valor * -1, 2);
        }
      }
    } else {

      $oDadoReceita                          = new stdClass();
      $oDadoReceita->codigo_receita          = $this->getCodigo();
      $oDadoReceita->caracteristica_peculiar = $this->getCaracteristicaPeculiar();
      $oDadoReceita->percentual_receita      = 0;
      $oDadoReceita->tem_desobramento        = false;
      $oDadoReceita->valor                   = round($oDadoSqlGeral->arrecada, 2);
      $aReceitas[$this->getCodigo()]         = $oDadoReceita;
    }

    $sCaracteristicaPeculiarPlanilhaRecibo = '';
    // passa aqui quando tem desdobramento (existe no arquivo orcreceitades)
    if (count($aReceitas) > 0) {

      foreach ($aReceitas as $iCodigoReceita => $oDadosReceita) {

        $codrec          = $oDadosReceita->codigo_receita;
        $valor           = $oDadosReceita->valor;
        $sCaracteristica = $oDadosReceita->caracteristica_peculiar;
        if ($valor == 0) {
          // quando entraria neste if ?
          // por exemplo:
          // arrecadar 0,01 cents de receita e esta receita ser desdobrada em 3,
          // ai só ocorrerá arrecadação na primeira
          continue;
        }

        $sChaveContaCredito = "ContaCredito{$iAnoAutenticacao}{$codrec}";
        $oDadoContaCredito  = DBRegistry::get($sChaveContaCredito);
        if ($oDadoContaCredito == null) {

          $oDaoOrcReceita        = new cl_orcreceita();
          $sSqlBuscaContaCredito = $oDaoOrcReceita->sql_query_dados_receita($iAnoAutenticacao,
                                                                            $codrec,
                                                                            "conplanoorcamento.*");
          $rsBuscaContaCredito   = $oDaoOrcReceita->sql_record($sSqlBuscaContaCredito);
          if ($oDaoOrcReceita->numrows == 0) {
            throw new BusinessException("Não localizou a conta credito da receita.");
          }

          $oDadoContaCredito   = db_utils::fieldsMemory($rsBuscaContaCredito, 0);
        }
        $sEstruturalCredito  = $oDadoContaCredito->c60_estrut;
        $sDescricaoCredito   = $oDadoContaCredito->c60_descr;
        $iCodigoContaCredito = $oDadoContaCredito->c60_codcon;


        $sWhereOrcamento            = "c21_anousu = {$iAnoAutenticacao} and c21_codcon = {$iCodigoContaCredito} ";
        $sWhereOrcamento           .= " and c21_instit = " . db_getsession("DB_instit");
        $oDaoConPlanoOrcamentoGrupo = new cl_conplanoorcamentogrupo();
        $sSqlBuscaGrupoPlano        = $oDaoConPlanoOrcamentoGrupo->sql_query_file(null, "*", null, $sWhereOrcamento);
        $rsBuscaGrupoPlano          = $oDaoConPlanoOrcamentoGrupo->sql_record($sSqlBuscaGrupoPlano);
        if ($oDaoConPlanoOrcamentoGrupo->numrows == "0") {

          $sMensagemErro  = "A conta {$iCodigoContaCredito} / {$sEstruturalCredito} / {$sDescricaoCredito}";
          $sMensagemErro .= "não está associada a nenhum grupo de receita.";
          throw new BusinessException($sMensagemErro);
        }


        /**
         *@Todo Colocar query em algum repository / Registry
         */
        $sChaveContaRegistry      = "contapcasp{$iCodigoContaCredito}{$iAnoAutenticacao}";
        $iCodigoContaCreditoPcasp = DBRegistry::get($sChaveContaRegistry);
        if (empty($iCodigoContaCreditoPcasp)) {

          $sSqlBuscaConta  = " select conplanoreduz.c61_reduz ";
          $sSqlBuscaConta .= "   from conplanoorcamento ";
          $sSqlBuscaConta .= "        inner join conplanoconplanoorcamento   on c72_conplanoorcamento = conplanoorcamento.c60_codcon ";
          $sSqlBuscaConta .= "                                              and c72_anousu            = conplanoorcamento.c60_anousu ";
          $sSqlBuscaConta .= "        inner join conplano                    on c72_conplano          = conplano.c60_codcon ";
          $sSqlBuscaConta .= "                                              and c72_anousu            = conplano.c60_anousu ";
          $sSqlBuscaConta .= "        inner join conplanoreduz               on c61_codcon = conplano.c60_codcon ";
          $sSqlBuscaConta .= "                                              and c61_anousu = conplano.c60_anousu ";
          $sSqlBuscaConta .= "  where conplanoorcamento.c60_codcon = {$iCodigoContaCredito} ";
          $sSqlBuscaConta .= "    and conplanoorcamento.c60_anousu = {$iAnoAutenticacao} ";
          $sSqlBuscaConta .= "    and conplanoreduz.c61_instit = " . db_getsession("DB_instit");
          $rsBuscaConta    = db_query($sSqlBuscaConta);

          if (pg_num_rows($rsBuscaConta) == 0) {

            $sErroMensagem  = "Impossível localizar o vínculo da conta orçamentaria de código {$iCodigoContaCredito} ";
            $sErroMensagem .= "com uma conta do plano de contas (PCASP) no ano de {$iAnoAutenticacao}.";
            throw new BusinessException($sErroMensagem);
          }

          $iCodigoContaCreditoPcasp = db_utils::fieldsMemory($rsBuscaConta, 0)->c61_reduz;
          DBRegistry::add($sChaveContaRegistry, $iCodigoContaCreditoPcasp);
        }
        if (!empty ($oDadoSqlGeral->cgm_estornado) || !empty ($oDadoSqlGeral->cgm_pago)) {
          $iCodigoCgm = $oDadoSqlGeral->cgm_pago != "" ? $oDadoSqlGeral->cgm_pago : $oDadoSqlGeral->cgm_estornado;
        }
        $iCodigoGrupoCorrente = "";
        if ($iId  != 0 && $iAutent != 0 ) {

          $oDaoCorgrupoCorrente = new cl_corgrupocorrente();
          $sSqlCorrente         = $oDaoCorgrupoCorrente->sql_query_file(null,
                                                                       "k105_sequencial",
                                                                        null,
                                                                       " k105_id     = {$iId}
                                                                         and k105_autent = {$iAutent}
                                                                         and k105_data   = '{$dtAutenticacao}'");

          $rsCorrenteGrupo = $oDaoCorgrupoCorrente->sql_record($sSqlCorrente);
          if ($oDaoCorgrupoCorrente->numrows > 0) {
            $iCodigoGrupoCorrente = db_utils::fieldsMemory($rsCorrenteGrupo,0)->k105_sequencial;
          }

          if (!empty($iCodigoPlanilha)) {

            $oDaoPlaCaixa   = new cl_placaixa();
            $sWherePlanilha = "k80_codpla = {$iCodigoPlanilha} and o70_codrec = {$codrec}";
            $sSqlBuscaCP    = $oDaoPlaCaixa->sql_query_receita(null,
                                                              'k81_concarpeculiar,
                                                               k81_numcgm',
                                                               null, $sWherePlanilha
                                                              );
            $rsBuscaCP      = $oDaoPlaCaixa->sql_record($sSqlBuscaCP);
            if ($oDaoPlaCaixa->numrows > 0) {

              $oStdDadosPlanilha                     = db_utils::fieldsMemory($rsBuscaCP, 0);
              $sCaracteristicaPeculiarPlanilhaRecibo = $oStdDadosPlanilha->k81_concarpeculiar;
              $iCodigoCgm                            = $oStdDadosPlanilha->k81_numcgm;
            }
          }

          if (!empty($iCodigoNumPre) && !empty($iCodigoNumPar)) {

            $sWhereCP  = "k130_numpre = {$iCodigoNumPre} and k130_numpar = {$iCodigoNumPar} ";
            $sWhereCP .= "and k02_codrec = {$codrec}";

            $oDaoReciboCP = new cl_reciboconcarpeculiar();
            $sSqlBuscaCP  = "select k130_concarpeculiar
                               from reciboconcarpeculiar
                                    inner join tabrec on tabrec.k02_codigo = reciboconcarpeculiar.k130_receit
                                    inner join taborc on taborc.k02_codigo = tabrec.k02_codigo
                              where {$sWhereCP}";

            $rsBuscaCP = $oDaoReciboCP->sql_record($sSqlBuscaCP);
            if ($oDaoReciboCP->numrows > 0) {
              $sCaracteristicaPeculiarPlanilhaRecibo = db_utils::fieldsMemory($rsBuscaCP, 0)->k130_concarpeculiar;
            }
          }
        }

        if ($sCaracteristicaPeculiarPlanilhaRecibo != "") {
          $sCaracteristica = $sCaracteristicaPeculiarPlanilhaRecibo;
        }

        $oLancamentoAuxiliar = new LancamentoAuxiliarArrecadacaoReceita();
        $oLancamentoAuxiliar->setCodigoCgm($iCodigoCgm);
        $oLancamentoAuxiliar->setCodigoContaCorrente($iContaDebito);
        $oLancamentoAuxiliar->setCodigoReceita($codrec);
        $oLancamentoAuxiliar->setHistorico(9100);
        $oLancamentoAuxiliar->setCodigoContaOrcamento($iCodigoContaCredito);
        $oLancamentoAuxiliar->setMesLancamento($iMesAutenticacao);
        $oLancamentoAuxiliar->setContaCredito($iCodigoContaCreditoPcasp);
        $oLancamentoAuxiliar->setContaDebito($iContaDebito);
        $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
        $oLancamentoAuxiliar->setValorTotal(round($valor, 2));
        $oLancamentoAuxiliar->setCodigoGrupoCorrente($iCodigoGrupoCorrente);

        $oLancamentoAuxiliar->setAutenticacao($iId);
        $oLancamentoAuxiliar->setDataAutenticacao($dtAutenticacao);
        $oLancamentoAuxiliar->setAutenticadora($iAutent);


        if (isset($sCaracteristica) && !empty($sCaracteristica)) {
          $oLancamentoAuxiliar->setCaracteristicaPeculiar($sCaracteristica);
        }
        if (isset($iCodigoRecurso) && !empty($iCodigoRecurso)) {
          $oLancamentoAuxiliar->setCodigoRecurso($iCodigoRecurso);
        }
        if (isset($sCaracteristaPeculiarRecibo) && !empty($sCaracteristaPeculiarRecibo)) {
          $oLancamentoAuxiliar->setCaracteristicaPeculiar($sCaracteristaPeculiarRecibo);
        }
        $iCodigoTipoDocumento = 100;
        if ($lEstorno) {

          $iCodigoTipoDocumento = 101;
          $oLancamentoAuxiliar->setEstorno(true);
          $oLancamentoAuxiliar->setContaCredito($iContaDebito);
          $oLancamentoAuxiliar->setContaDebito($iCodigoContaCreditoPcasp);
        }

        /**
         * Identificamos se a receita é uma receita de dedução
         */
        if (substr($this->getContaOrcamento()->getEstrutural(), 0, 1) == 9 && !empty($iCodigoPlanilha)) {

          $oLancamentoAuxiliar->setContaCredito($iContaDebito);
          $oLancamentoAuxiliar->setContaDebito($iCodigoContaCreditoPcasp);

          if (!$lEstorno) {

            $oLancamentoAuxiliar->setContaCredito($iCodigoContaCreditoPcasp);
            $oLancamentoAuxiliar->setContaDebito($iContaDebito);
          }

        }

        $oDocumentoContabil = SingletonRegraDocumentoContabil::getDocumento($iCodigoTipoDocumento);
        $oDocumentoContabil->setValorVariavel("[codigoreceita]", $codrec);
        $oDocumentoContabil->setValorVariavel("[anousureceita]", $iAnoAutenticacao);
        $oDocumentoContabil->setValorVariavel("[instituicaogrupoconta]", db_getsession('DB_instit'));
        $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
        $oLancamentoAuxiliar->setCodigoDocumento($iCodigoDocumentoExecutar);

        /**
         * Define os dados necessarios para salvar o contacorrentedetalhe
         */
        $oContaPlano = new ContaPlanoPCASP(null, db_getsession('DB_anousu'), $iContaDebito, db_getsession('DB_instit'));
        $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
        $oContaCorrenteDetalhe->setEstrutural($this->getContaOrcamento()->getEstrutural());
        $oContaCorrenteDetalhe->setRecurso(new Recurso($oContaPlano->getRecurso()));
        $oContaCorrenteDetalhe->setContaBancaria($oContaPlano->getContaBancaria());
        if (!empty($iCodigoCgm)) {
          $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($iCodigoCgm));
        }
        $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

        /**
         * Verificamos se o processamento é um estorno e desconto para sob escrevermos o código do documento
         */
        if ($lDesconto) {

          $oLancamentoAuxiliar->setContaCredito($iContaDebito);
          $oLancamentoAuxiliar->setContaDebito($iCodigoContaCreditoPcasp);
          $iCodigoDocumentoExecutar = 418;
          if ($lEstorno) {

            $oLancamentoAuxiliar->setContaCredito($iCodigoContaCreditoPcasp);
            $oLancamentoAuxiliar->setContaDebito($iContaDebito);
            $iCodigoDocumentoExecutar = 419;
          }
          $oLancamentoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumentoExecutar,
                                                                                     $iAnoAutenticacao);
          $oLancamentoContabil->executaLancamento($oLancamentoAuxiliar, $dtAutenticacao);
        } else {

          if (!empty($iCodigoNumPre) && !empty($iCodigoNumPar)) {

            $oDaoEmpPrestaRecibo     = new cl_empprestarecibo();
            $sSqlBuscaPestacaoContas = $oDaoEmpPrestaRecibo->sql_query_recibo_empenho(null,
                                                                                      "empempenho.e60_numemp",
                                                                                      null,
                                                                                      "   e170_numpre = {$iCodigoNumPre}
                                                                                      and e170_numpar = {$iCodigoNumPar}");
            $rsBuscaPrestacaoConta = $oDaoEmpPrestaRecibo->sql_record($sSqlBuscaPestacaoContas);
            if ($oDaoEmpPrestaRecibo->numrows > 0) {

              $oLancamentoAuxiliar->setNumeroEmpenho(db_utils::fieldsMemory($rsBuscaPrestacaoConta, 0)->e60_numemp);
              $oLancamentoAuxiliar->setArrecadacaoEmpenhoPrestacaoContas(true);
              $iCodigoDocumentoExecutar = 416;
              if ($lEstorno) {
                $iCodigoDocumentoExecutar = 417;
              }
            }
          }


          $oLancamentoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumentoExecutar,
                                                                                     $iAnoAutenticacao);
          $oLancamentoContabil->executaLancamento($oLancamentoAuxiliar, $dtAutenticacao);

        }
      }
    }
    return true;
  }

  /**
   * Retorna o codigo da receita
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo da receita
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o ano da receita
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o ano da receita
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Seta uma conta orcamento
   * @param ContaOrcamento $oContaOrcamento
   */
  public function setContaOrcamento(ContaOrcamento $oContaOrcamento) {

    $this->oContaOrcamento      = $oContaOrcamento;
    $this->iCodigoContaContabil = $oContaOrcamento->getCodigoConta();
  }

  /**
   * Retorna a conta do plano orcamentario associada a receita
   * @return ContaOrcamento
   */
  public function getContaOrcamento() {

    if (empty($this->oContaOrcamento)) {
      $this->oContaOrcamento = ContaOrcamentoRepository::getContaByCodigo($this->iCodigoContaContabil, $this->getAno());
    }
    return $this->oContaOrcamento;
  }

  /**
   * Retorna o tipo de receita
   * @return integer
   */
  public function getTipoRecurso() {
    return $this->iTipoRecurso;
  }

  /**
   * Seta o tipo de receita (orctiporec)
   * @param integer $iTipoRecurso
   */
  public function setTipoRecurso($iTipoRecurso) {
    $this->iTipoRecurso = $iTipoRecurso;
  }

  /**
   * Retorna o valor previsto da receita
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Seta o valor previsto para a receita
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna se a receita esta configurada como lancada
   */
  public function isReceitaLancada() {
    return $this->lReceitaLancada;
  }

  /**
   * Seta se a receita é lancada
   * @param boolean $lReceitaLancada
   */
  public function setReceitaLancada($lReceitaLancada) {
    $this->lReceitaLancada = $lReceitaLancada;
  }

  /**
   * Retorna a instituicao
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Seta a instituicao
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna a caracteristica peculiar
   * @return string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Seta a caracteristica peculiar / caracteristica de aplicacao
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Retorna a data da criacao
   * @return string
   */
  public function getDataCriacao() {
    return $this->dtCriacao;
  }

  /**
   * Seta a data da criacao
   * @param string $dtCriacao
   */
  public function setDataCriacao($dtCriacao) {
    $this->dtCriacao = $dtCriacao;
  }

  /**
   * @return Recurso
   */
  public function getRecurso() {

    if (empty($this->oRecurso) && !empty($this->iTipoRecurso)) {
      $this->oRecurso = RecursoRepository::getRecursoPorCodigo($this->iTipoRecurso);
    }
    return $this->oRecurso;
  }

  /**
   * Método para buscar o valor previsto de receita no ano, na instituicao
   * @param int $iAno
   * @param int $iInstituicao
   * @param string $sWhereAdicional - cláusula where que permita filtrar ainda mais o resultado
   * @return double valor Previsto Receia no Ano $iAno na instituicao $iInstituicao
   */
  public static function getValorPrevistoAno($iAno, $iInstituicao, $sWhereAdicional = null) {

    $oDaoOrcreceita  = new cl_orcreceita();
    $WhereOrcreceita = "o70_instit = {$iInstituicao} and o70_anousu = {$iAno}";
    if (!empty($sWhereAdicional)) {
      $WhereOrcreceita .= " and {$sWhereAdicional} ";
    }
    $sCampos         = "sum(o70_valor) as valor";
    $sSqlOrcreceita  = $oDaoOrcreceita->sql_query_receita($sCampos, null, $WhereOrcreceita);
    $rsSqlOrcreceita = $oDaoOrcreceita->sql_record($sSqlOrcreceita);

    $nValor = 0;
    if ($oDaoOrcreceita->numrows > 0) {
      $nValor = db_utils::fieldsMemory($rsSqlOrcreceita, 0)->valor;
    }
    return (float)$nValor;
  }


  /**
   * Retorna as Receitas que fazem parte do desdobramento da receita
   * @throws BusinessException
   * @return array
   */
  public  function getDesdobramentos() {

    if (count($this->aDesdobramentos) == 0) {

      $oDaoOrcReceita      = new cl_orcreceita();

      /*
      * verifica se a conta tem desdobramento
      */
      $sWhereDesdobramento  = "     o57_anousu = {$this->getAno()}";
      $sWhereDesdobramento .= " and o70_instit = {$this->getInstituicao()->getSequencial()}";
      $sWhereDesdobramento .= " and o57_fonte  = '{$this->getContaOrcamento()->getEstrutural()}' ";

      $rsBuscaDesdobramento = $oDaoOrcReceita->sql_record($oDaoOrcReceita->sql_query_fonte_desdobramento(null,
                                                                                                         null,
                                                                                                 "o70_codrec",
                                                                                                  "o57_fonte",
                                                                                          $sWhereDesdobramento));
      if ($oDaoOrcReceita->numrows > 0){

        $sEstruturalContaMae = db_le_mae_rec_sin($this->getContaOrcamento()->getEstrutural(), false);
        $sWhereEstrutural    = "     o57_anousu = {$this->getAno()}";
        $sWhereEstrutural   .= " and o70_instit = {$this->getInstituicao()->getSequencial()}";
        $sWhereEstrutural   .= " and o57_fonte like '{$sEstruturalContaMae}%' ";

        $sSqlBuscaEstruturalMae = $oDaoOrcReceita->sql_query_fonte_desdobramento(null,
                                                                                 null,
                                                                                 "o70_codrec,
                                                                                  o60_perc,
                                                                                  o70_concarpeculiar",
                                                                                 "o57_fonte",
                                                                                  $sWhereEstrutural
                                                                                );

        $rsBuscaEstruturalMae = db_query($sSqlBuscaEstruturalMae);
        if (!$rsBuscaEstruturalMae) {

          $sErroMensagem  = "Erro ao realizar pesquisas de desdobramentos para a receita {$this->getCodigo()} /";
          $sErroMensagem .= "{$this->getAno()} ({$this->sEstruturalReceita})";
          throw new BusinessException($sErroMensagem);
        }
        $iTotalLinhas = pg_num_rows($rsBuscaEstruturalMae);
        for ($iDesdobramento = 0; $iDesdobramento < $iTotalLinhas; $iDesdobramento++) {

          $oDesdobramento = new stdClass();
          $oDadosDesdobramento                     = db_utils::fieldsMemory($rsBuscaEstruturalMae, $iDesdobramento);
          $oDesdobramento->codigo_receita          = $oDadosDesdobramento->o70_codrec;
          $oDesdobramento->percentual_receita      = $oDadosDesdobramento->o60_perc;
          $oDesdobramento->caracteristica_peculiar = $oDadosDesdobramento->o70_concarpeculiar;
          $this->aDesdobramentos[]                 = $oDesdobramento;
        }
      }
    }
    return $this->aDesdobramentos;
  }

}
