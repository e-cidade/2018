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

/**
 * Classe para validaçao dos registro do arquivo de Retençoes de ISSQN
 *
 * @author     Roberto Carneiro <roberto@dbseller.com.br>
 * @package    tributario
 * @subpackage issqn
 *
 */
class ProcessaArquivoRetencao{

  /**
   * Constante com as mensagens de Validação
   */
  const MENSAGENS = 'tributario.issqn.ProcessaArquivoRetencao.';

  /**
   * Constantes com os erros de validação
   */
  const TOMADOR_SEM_CGM              = 'tomadorSemCGM';
  const PRESTADOR_SEM_CGM            = 'prestadorSemCGM';
  const PRESTADOR_SEM_INSCRICAOATIVA = 'prestadorSemInscricaoAtiva';

  /**
   * Array de registros do arquivo de retençao
   * @var array
   */
  private $aRegistrosRetencao = array();

  /**
   * Array de registros inconsistentes do arquivo de retençao
   * @var array
   */
  private $aRegistrosInconsistentes = array();

  /**
   * Metodo construtor
   * @throws Exception
   */
  public function __construct($iArquivoRetencao){

    if ( empty($iArquivoRetencao) )  {
      throw new BusinessException( _M( self::MENSAGENS . "erro_arquivo_nao_informado") );
    }

    $sWhere  = "q91_issarquivoretencao = {$iArquivoRetencao}";

    $oDaoIssArquivoRetencaoRegistro = new cl_issarquivoretencaoregistro;
    $sSqlIssArquivoRetencaoRegistro = $oDaoIssArquivoRetencaoRegistro->sql_query(null, "issarquivoretencaoregistro.*", "q91_sequencial", $sWhere);
    $rsIssArquivoRetencaoRegistro   = $oDaoIssArquivoRetencaoRegistro->sql_record($sSqlIssArquivoRetencaoRegistro);

    if ($oDaoIssArquivoRetencaoRegistro->numrows == 0 ) {
      throw new DBException( _M( self::MENSAGENS . 'erro_registro_nao_encontrado') );
    }

    $this->oIssArquivoRetencao = new IssArquivoRetencao($iArquivoRetencao);
    $this->aRegistrosRetencao  = db_utils::getCollectionByRecord($rsIssArquivoRetencaoRegistro);
  }

  /**
   * Validamos os registros do arquivo
   * @throws Exception
   */
  public function validarArquivo(){

    if (empty($this->aRegistrosRetencao)) {
      throw new Exception( _M( self::MENSAGENS . 'erro_validar_arquivo') );
    }

    /**
     * Verificamos os registros e caso ocorra inconsistencia populamos o array de Registros inconsistentes
     */
    foreach ($this->aRegistrosRetencao as $oRegistroRetencao) {

      /**
       * Validamos se existe CGM cadastrado para o cnpj TOMADOR
       */
      if (CgmFactory::getInstanceByCnpjCpf($oRegistroRetencao->q91_cnpjtomador) == false) {

        $this->aRegistrosInconsistentes[] = array( "sequencial_registro" => $oRegistroRetencao->q91_sequencialregistro,
                                                   "registro"            => $oRegistroRetencao->q91_cnpjtomador,
                                                   "erro"                => self::TOMADOR_SEM_CGM );
      }
    }
  }

  /**
   * Validamos se o arquivo selecionado ja fora processado, olhando se o mesmo esta na tabela issarquivoretencaodisarq
   * @throws BusinessException
   */
  public function validarProcessamento() {

    $sWhere = "q145_issarquivoretencao = {$this->oIssArquivoRetencao->getSequencial()}";
    $oDaoIssArquivoRetencaoDisArq = new cl_issarquivoretencaodisarq;
    $sSqlIssArquivoRetencaoDisArq = $oDaoIssArquivoRetencaoDisArq->sql_query_file(null, "*", null, $sWhere);
    $rsIssArquivoRetencaoDisArq   = $oDaoIssArquivoRetencaoDisArq->sql_record($sSqlIssArquivoRetencaoDisArq);

    if ($oDaoIssArquivoRetencaoDisArq->numrows > 0) {
      throw new BusinessException( _M( self::MENSAGENS . "erro_arquivo_processado") );
    }
  }

  /**
   * Busca código  do banco, da agência e da conta a ser vinculada na disarq
   * @param integer $iCodigoBanco
   */
  public function setBancoAgenciaConta($iCodigoBanco) {

    $sCampos = "k15_codigo, k15_codbco, k15_codage, k15_conta";

    $oDaoCadban = new cl_cadban;
    $sSqlCadban = $oDaoCadban->sql_query_file($iCodigoBanco, $sCampos);
    $rsCadban   = $oDaoCadban->sql_record($sSqlCadban);

    if ($rsCadban == false) {
      throw new DBException( _M( self::MENSAGENS . 'erro_banco_nao_encontrado') );
    }

    $oCadban = db_utils::fieldsMemory($rsCadban, 0);

    $this->oBancoAgenciaConta = $oCadban;
  }

  /**
   * Processamos os registros do arquivo, deixando seus débitos em aberto
   */
  public function processarRegistros() {

    if ( empty($this->aRegistrosRetencao) ) {
      throw new BusinessException( _M( self::MENSAGENS . 'erro_registro_nao_encontrado') );
    }

    if ( empty($this->oBancoAgenciaConta) ) {
      throw new BusinessException( _M( self::MENSAGENS . 'erro_banco_nao_encontrado') );
    }

    /**
     * Começamos a inserir os dados do arquivo na disarq
     */
    $oDaoDisArq                = new cl_disarq;
    $oDaoDisArq->id_usuario    = db_getsession("DB_id_usuario");
    $oDaoDisArq->k15_codbco    = $this->oBancoAgenciaConta->k15_codbco;
    $oDaoDisArq->k15_codage    = $this->oBancoAgenciaConta->k15_codage;
    $oDaoDisArq->arqret        = $this->oIssArquivoRetencao->getNomeArquivo();
    $oDaoDisArq->textoret      = "Object oid";
    $oDaoDisArq->dtretorno     = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoDisArq->dtarquivo     = $this->oIssArquivoRetencao->getData();
    $oDaoDisArq->k00_conta     = $this->oBancoAgenciaConta->k15_conta;
    $oDaoDisArq->autent        = "false";
    $oDaoDisArq->instit        = db_getsession('DB_instit');
    $oDaoDisArq->md5           = 'null';
    $oDaoDisArq->incluir(null);


    if ( $oDaoDisArq->erro_status == 0 ) {
      throw new DBException( _M( self::MENSAGENS . "erro_baixa" ) );
    }

    $oDaoIssArquivoRetencaoDisArq                          = new cl_issarquivoretencaodisarq;
    $oDaoIssArquivoRetencaoDisArq->q145_issarquivoretencao = $this->oIssArquivoRetencao->getSequencial();
    $oDaoIssArquivoRetencaoDisArq->q145_disarq             = $oDaoDisArq->codret;
    $oDaoIssArquivoRetencaoDisArq->incluir(null);

    if ( $oDaoIssArquivoRetencaoDisArq->erro_status == 0 ) {
      throw new DBException( _M( self::MENSAGENS . "erro_baixa" ) );
    }

    /**
     * Iniciamos o for para que possamos tratar cada registro do arquivo de vez
     */
    foreach ($this->aRegistrosRetencao as $iIndice => $oRegistroRetencao) {

      /**
       * Criamos um numpre para a árvore de débitos do registro
       */
      $rsNumpre = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
      $oNumpre  = db_utils::fieldsmemory($rsNumpre, 0);
      $iNumpre  = $oNumpre->k03_numpre;

      /**
       * Buscamos os dados do tomador
       */
      $oDaoCgm = new cl_cgm;
      $sCampos = "z01_numcgm, z01_nome, case when z01_telef is null then z01_telcel else z01_telef end as fone ";
      $sSlqCgm = $oDaoCgm->sql_query(null, $sCampos, null, "z01_cgccpf = '{$oRegistroRetencao->q91_cnpjtomador}'");
      $rsCgm   = $oDaoCgm->sql_record($sSlqCgm);

      if ($rsCgm == false) {
        throw new DBException( _M( self::MENSAGENS . 'erro_cgm_nao_encontrado') );
      }

      if ($oDaoCgm->numrows > 1) {
        throw new BusinessException( _M( self::MENSAGENS . 'erro_cgm_unico') );
      }

      $oCgmTomador = db_utils::fieldsMemory($rsCgm, 0);

      /**
       * Validamos se o CNPJ do prestador está devidamente cadastrado no sistema
       */
      $oDaoCgm = new cl_cgm;
      $sCampos = "z01_numcgm, z01_nome";
      $sSlqCgm = $oDaoCgm->sql_query(null, $sCampos, null, "z01_cgccpf = '{$oRegistroRetencao->q91_cpfcnpjprestador}'");
      $rsCgm   = $oDaoCgm->sql_record($sSlqCgm);

      $lPrestadorTemCgm = false;
      $oCgmPrestador    = null;

      /**
       * Se for encontrado cgm para o prestador, setamos a flag de controle para true e buscamos os dados
       * retornado na query
       */
      if ($rsCgm != false || $oDaoCgm->numrows > 0) {

        $lPrestadorTemCgm = true;
        $oCgmPrestador    = db_utils::fieldsMemory($rsCgm, 0);
      }

      $lPrestadorTemInscricao = false;
      $oIssBase               = null;

      /**
       * Se houver cgm para o prestador, verificamos se o mesmo tem inscrição cadastrada
       */
      if ( $lPrestadorTemCgm ) {

        /**
         * Buscamos a inscrição do prestador, para depois fazer o vínculo do mesmo com a issplanit
         */
        $sWhere      = "q02_numcgm = {$oCgmPrestador->z01_numcgm}";
        $oDaoIssBase = new cl_issbase;
        $sSqlIssBase = $oDaoIssBase->sql_query(null, "q02_inscr", "q02_inscr", $sWhere);
        $rsIssBase   = $oDaoIssBase->sql_record($sSqlIssBase);

        /**
         * Se for encontrada inscrição para o prestador, setamos a flag de controle para true e buscamos os dados
         * retornado na query
         */
        if ( $rsIssBase != false || $oDaoIssBase->numrows > 0 ) {

          $lPrestadorTemInscricao = true;
          $oIssBase               = db_utils::fieldsMemory($rsIssBase, 0);
        }
      }

      $lPrestadorTemAtividade = false;
      $oTabAtiv               = null;

      /**
       * Se houver inscrição para o prestador, verificamos se o mesmo tem atividade válida
       */
      if ( $lPrestadorTemInscricao ) {

        /**
         * Buscamos a atividade principal do prestador
         */
        $oDaoTabAtiv = new cl_tabativ;
        $sWhere      = " tabativ.q07_inscr = {$oIssBase->q02_inscr} and tabativbaixa.q11_inscr is null and q07_databx is null";
        $sSqlTabAtiv = $oDaoTabAtiv->sql_query_princ(null, null, 'q03_descr', null, $sWhere);
        $rsTabAtiv   = $oDaoTabAtiv->sql_record($sSqlTabAtiv);

        /**
         * Se for encontrada atividade para o prestador, setamos a flag de controle para true e buscamos os dados
         * retornado na query
         */
        if ($rsTabAtiv != false || $oDaoTabAtiv->numrows > 0) {

          $lPrestadorTemAtividade = true;
          $oTabAtiv               = db_utils::fieldsMemory($rsTabAtiv, 0);
        }
      }

      /**
       * Se o CNPJ do prestador informado no arquivo tiver inscrição devidamente cadastrada
       * no sistma, inserimos a planilha vinculando à inscrição
       */
      if ( $lPrestadorTemCgm && $lPrestadorTemInscricao && $lPrestadorTemAtividade ) {

        /**
         * Damos início a inserção da planilha
         * Será criada um planilha para cada registro do arquivo
         */
        $oDaoIssPlan                 = new cl_issplan;
        $oDaoIssPlan->q20_numcgm     = $oCgmTomador->z01_numcgm;
        $oDaoIssPlan->q20_ano        = $oRegistroRetencao->q91_anousu;
        $oDaoIssPlan->q20_mes        = $oRegistroRetencao->q91_mesusu;
        $oDaoIssPlan->q20_nomecontri = $oCgmTomador->z01_nome;
        $oDaoIssPlan->q20_fonecontri = $oCgmTomador->fone;
        $oDaoIssPlan->q20_numpre     = $iNumpre;
        $oDaoIssPlan->q20_numbco     = 0;
        $oDaoIssPlan->q20_situacao   = 1;
        $oDaoIssPlan->incluir(null);

        if ( $oDaoIssPlan->erro_status == 0) {
          throw new DBException(_M( self::MENSAGENS . 'erro_gerar_planilha') );
        }

        /**
         * Vinculamos a planilha ao registro
         */
        $oDaoIssArquivoRetencaoRegistroIssPlan                                  = new cl_issarquivoretencaoregistroissplan;
        $oDaoIssArquivoRetencaoRegistroIssPlan->q137_issplan                    = $oDaoIssPlan->q20_planilha;
        $oDaoIssArquivoRetencaoRegistroIssPlan->q137_issarquivoretencaoregistro = $oRegistroRetencao->q91_sequencial;
        $oDaoIssArquivoRetencaoRegistroIssPlan->incluir(null);

        if ($oDaoIssArquivoRetencaoRegistroIssPlan->erro_status == 0) {
          throw new DBException( _M( self::MENSAGENS . "erro_gerar_planilha" ) );
        }

        /**
         * Vinculamos a planilha com o numpre do debito
         */
        $oDaoIssPlanNumpre               = new cl_issplannumpre;
        $oDaoIssPlanNumpre->q32_planilha = $oDaoIssPlan->q20_planilha;
        $oDaoIssPlanNumpre->q32_numpre   = $iNumpre;
        $oDaoIssPlanNumpre->q32_dataop   = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoIssPlanNumpre->q32_horaop   = date("H:i", db_getsession("DB_datausu"));
        $oDaoIssPlanNumpre->q32_status   = 1 ;
        $oDaoIssPlanNumpre->incluir(null);

        if ( $oDaoIssPlanNumpre->erro_status == 0) {
          throw new DBException(_M( self::MENSAGENS . 'erro_gerar_planilha') );
        }


        $oDaoIssPlanit                   = new cl_issplanit;
        $oDaoIssPlanit->q21_planilha     = $oDaoIssPlan->q20_planilha;
        $oDaoIssPlanit->q21_cnpj         = $oRegistroRetencao->q91_cpfcnpjprestador;
        $oDaoIssPlanit->q21_nome         = $oCgmPrestador->z01_nome;
        $oDaoIssPlanit->q21_servico      = $oTabAtiv->q03_descr;
        $oDaoIssPlanit->q21_nota         = $oRegistroRetencao->q91_numeronotafiscal;
        $oDaoIssPlanit->q21_serie        = $oRegistroRetencao->q91_serienotafiscal;
        $oDaoIssPlanit->q21_valorser     = $oRegistroRetencao->q91_valorbasecalculo;
        $oDaoIssPlanit->q21_aliq         = $oRegistroRetencao->q91_aliquota;
        $oDaoIssPlanit->q21_valor        = $oRegistroRetencao->q91_valorprincipal;
        $oDaoIssPlanit->q21_valorimposto = $oRegistroRetencao->q91_valorprincipal;
        $oDaoIssPlanit->q21_dataop       = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoIssPlanit->q21_horaop       = date("H:i", db_getsession("DB_datausu"));
        $oDaoIssPlanit->q21_tipolanc     = 1;
        $oDaoIssPlanit->q21_situacao     = '0';
        $oDaoIssPlanit->q21_valordeducao = '0';
        $oDaoIssPlanit->q21_valorbase    = $oRegistroRetencao->q91_valorbasecalculo;
        $oDaoIssPlanit->q21_retido       = 't';
        $oDaoIssPlanit->q21_obs          = $oRegistroRetencao->q91_observacao;
        $oDaoIssPlanit->q21_datanota     = $oRegistroRetencao->q91_dataemissaonotafiscal;
        $oDaoIssPlanit->q21_status       = 1;
        $oDaoIssPlanit->incluir(null);

        if ( $oDaoIssPlanit->erro_status == 0) {
          throw new DBException(_M( self::MENSAGENS . 'erro_gerar_registros_planilha') );
        }

        /**
         * Vinculamos o registro da planilha(issplanit) com a inscrição do prestador
         */
        $oDaoIssPlanitInscr                = new cl_issplanitinscr;
        $oDaoIssPlanitInscr->q31_issplanit = $oDaoIssPlanit->q21_sequencial;
        $oDaoIssPlanitInscr->q31_inscr     = $oIssBase->q02_inscr;
        $oDaoIssPlanitInscr->incluir(null);

        if ( $oDaoIssPlanitInscr->erro_status == 0) {
          throw new DBException(_M( self::MENSAGENS . 'erro_gerar_registros_planilha_inscricao') );
        }

        /**
         * Vinculamos o planit ao numpre
         */
        $oDaoIssPlanNumpreIssPlanit = new cl_issplannumpreissplanit;
        $oDaoIssPlanNumpreIssPlanit->q77_issplanit     = $oDaoIssPlanit->q21_sequencial;
        $oDaoIssPlanNumpreIssPlanit->q77_issplannumpre = $oDaoIssPlanNumpre->q32_sequencial;
        $oDaoIssPlanNumpreIssPlanit->incluir(null);

        if ($oDaoIssPlanNumpreIssPlanit->erro_status == 0) {
          throw new DBException(_M( self::MENSAGENS . 'erro_gerar_registros_planilha_inscricao') );
        }
      }

      $oDaoIssVar             = new cl_issvar;
      $oDaoIssVar->q05_numpre = $iNumpre;
      $oDaoIssVar->q05_numpar = 1;
      $oDaoIssVar->q05_valor  = $oRegistroRetencao->q91_valorprincipal;
      $oDaoIssVar->q05_ano    = $oRegistroRetencao->q91_anousu;
      $oDaoIssVar->q05_mes    = $oRegistroRetencao->q91_mesusu;
      $oDaoIssVar->q05_histor = 'ISSQN retenção na fonte';
      $oDaoIssVar->q05_aliq   = $oRegistroRetencao->q91_aliquota;
      $oDaoIssVar->q05_bruto  = $oRegistroRetencao->q91_valorbasecalculo;
      $oDaoIssVar->q05_vlrinf = $oRegistroRetencao->q91_valorprincipal + $oRegistroRetencao->q91_valormulta + $oRegistroRetencao->q91_valorjuros;
      $oDaoIssVar->incluir(null);

      if ($oDaoIssVar->erro_status == 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_gerar_issvar') );
      }

      /**
       * Vinculamos o débito ao registro do arquivo
       */
      $oDaoIssArquivoRetencaoRegistroIssVar                                  = new cl_issarquivoretencaoregistroissvar;
      $oDaoIssArquivoRetencaoRegistroIssVar->q146_issvar                     = $oDaoIssVar->q05_codigo;
      $oDaoIssArquivoRetencaoRegistroIssVar->q146_issarquivoretencaoregistro = $oRegistroRetencao->q91_sequencial;
      $oDaoIssArquivoRetencaoRegistroIssVar->incluir(null);

      if ($oDaoIssArquivoRetencaoRegistroIssVar->erro_status == 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_gerar_issvar') );
      }

      /**
       * Buscamos os parâmetros configurados na configuraçao de planilhas
       */
      $oDaoConfPlan = new cl_db_confplan;
      $sSqlConfPlan = $oDaoConfPlan->sql_query();
      $rsConfPlan   = $oDaoConfPlan->sql_record($sSqlConfPlan);

      if ($oDaoConfPlan->numrows == 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_configuracao_nao_encontrada') );
      }

      $oConfPlan = db_utils::fieldsMemory($rsConfPlan, 0);

      if ($oRegistroRetencao->q91_mesusu == 12) {

        $oRegistroRetencao->q91_anousu = $oRegistroRetencao->q91_anousu + 1;
        $iMes = 1;
      } else {
        $iMes = $oRegistroRetencao->q91_mesusu + 1;
      }

      /**
       * Alteramos a data de processamento para utilizar o vencimento configurado na db_confplan
       */
      $sDataProcessamento = $oRegistroRetencao->q91_anousu . "-" . $iMes . "-" . $oConfPlan->w10_dia;
      $oData              = new DBDate( $sDataProcessamento );

      /**
       * Inserimos os débitos no arrecad
       */
      $oDaoArrecad             = new cl_arrecad;
      $oDaoArrecad->k00_numcgm = $oCgmTomador->z01_numcgm;
      $oDaoArrecad->k00_dtoper = $this->oIssArquivoRetencao->getData();
      $oDaoArrecad->k00_receit = $oConfPlan->w10_receit;
      $oDaoArrecad->k00_hist   = $oConfPlan->w10_hist;
      $oDaoArrecad->k00_valor  = $oRegistroRetencao->q91_valorprincipal;
      $oDaoArrecad->k00_dtvenc = $oData->getDate();
      $oDaoArrecad->k00_numpre = $iNumpre;
      $oDaoArrecad->k00_numpar = 1;
      $oDaoArrecad->k00_numtot = 1;
      $oDaoArrecad->k00_numdig = "0";
      $oDaoArrecad->k00_tipo   = $oConfPlan->w10_tipo;
      $oDaoArrecad->k00_tipojm = "0";
      $oDaoArrecad->incluir(null);

      if ($oDaoArrecad->erro_status == 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_gerar_arrecad' ) );
      }

      /**
       * Gerando numnov
       */
      $rsNumpre = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
      $oNumpre  = db_utils::fieldsmemory($rsNumpre, 0);
      $iNumnov  = $oNumpre->k03_numpre;

      $oDaoReciboWeb               = new cl_db_reciboweb;
      $oDaoReciboWeb->k99_codbco   = $this->oBancoAgenciaConta->k15_codbco;
      $oDaoReciboWeb->k99_codage   = $this->oBancoAgenciaConta->k15_codage;
      $oDaoReciboWeb->k99_numbco   = "0";
      $oDaoReciboWeb->k99_tipo     = 1;
      $oDaoReciboWeb->k99_origem   = 1;
      $oDaoReciboWeb->incluir($iNumpre,1,$iNumnov);

      if ($oDaoReciboWeb->erro_status = 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_recibo' ) );
      }

      /**
       * Geramos recibo_paga
       */
      $sDataEmissaoRecibo = date("Y-m-d", db_getsession("DB_datausu"));
      $sSqlGerarRecibo    = "select * from fc_recibo($iNumnov, '$sDataEmissaoRecibo', '$oRegistroRetencao->q91_datavencimento', ".db_getsession("DB_anousu").")";
      $rsRecibo           = db_query($sSqlGerarRecibo);
      $oRetornoRecibo     = db_utils::fieldsMemory($rsRecibo, 0);

      if (!isset($oRetornoRecibo->rlerro) || $oRetornoRecibo->rlerro != 'f') {

        if (isset( $oRetornoRecibo->rvmensagem ) && !empty( $oRetornoRecibo->rvmensagem )) {
          throw new Exception( $oRetornoRecibo->rvmensagem );
        }

        throw new Exception( _M( self::MENSAGENS . 'erro_recibo' ) );
      }

      /**
       * Inserimos o débito na Baixa de Banco
       */
      $oDaoDisbanco             = new cl_disbanco;
      $oDaoDisbanco->codret     = $oDaoDisArq->codret;
      $oDaoDisbanco->k15_codbco = $this->oBancoAgenciaConta->k15_codbco;
      $oDaoDisbanco->k15_codage = $this->oBancoAgenciaConta->k15_codage;
      $oDaoDisbanco->dtpago     = $this->oIssArquivoRetencao->getData();
      $oDaoDisbanco->dtarq      = $this->oIssArquivoRetencao->getData();
      $oDaoDisbanco->vlrjuros   = $oRegistroRetencao->q91_valorjuros;
      $oDaoDisbanco->vlrmulta   = $oRegistroRetencao->q91_valormulta;
      $oDaoDisbanco->vlrdesco   = "0";
      $oDaoDisbanco->cedente    = null;
      $oDaoDisbanco->vlrpago    = $oRegistroRetencao->q91_valorprincipal + $oRegistroRetencao->q91_valormulta + $oRegistroRetencao->q91_valorjuros;
      $oDaoDisbanco->vlrtot     = $oRegistroRetencao->q91_valorprincipal + $oRegistroRetencao->q91_valormulta + $oRegistroRetencao->q91_valorjuros;
      $oDaoDisbanco->vlrcalc    = "0";
      $oDaoDisbanco->classi     = 'false';
      $oDaoDisbanco->k00_numpre = $iNumnov;
      $oDaoDisbanco->k00_numpar = "0";
      $oDaoDisbanco->instit     = db_getsession('DB_instit');
      $oDaoDisbanco->convenio   = null;
      $oDaoDisbanco->dtcredito  = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoDisbanco->incluir(null);

      if ($oDaoDisbanco->erro_status == 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_baixa_banco' ) );
      }

      $oDaoIssArquivoRetencaoRegistroDisbanco                                 = new cl_issarquivoretencaoregistrodisbanco;
      $oDaoIssArquivoRetencaoRegistroDisbanco->q94_issarquivoretencaoregistro = $oRegistroRetencao->q91_sequencial;
      $oDaoIssArquivoRetencaoRegistroDisbanco->q94_disbanco                   = $oDaoDisbanco->idret;
      $oDaoIssArquivoRetencaoRegistroDisbanco->incluir(null);

      if ($oDaoIssArquivoRetencaoRegistroDisbanco->erro_status == 0) {
        throw new DBException( _M( self::MENSAGENS . 'erro_baixa_banco' ) );
      }
    }
  }



  /**
   * Busca BancoAgenciaConta
   * @return stdClass
   */
  public function getBancoAgenciaConta() {
    return $this->oBancoAgenciaConta;
  }

  /**
   * Altera IssArquivoRetencao
   * @param object IssArquivoRetencao
   */
  public function setIssArquivoRetencao( $oIssArquivoRetencao ) {
    $this->oIssArquivoRetencao = $oIssArquivoRetencao;
  }

  /**
   * Busca IssArquivoRetencao
   * @return object IssArquivoRetencao
   */
  public function getIssArquivoRetencao() {
    return $this->oIssArquivoRetencao;
  }

  /**
   * Altera o array $aRegistrosRetencao
   * @param array
   */
  public function setRegistroRetencao( $aRegistrosRetencao ) {
    $this->aRegistrosRetencao = $aRegistrosRetencao;
  }

  /**
   * Busca array de registros de retençoes
   * @return array
   */
  public function getRegistroRetencao() {
    return $this->aRegistrosRetencao;
  }

  /**
   * Altera o array de Registros inconsistentes
   * @param array
   */
  public function setRegistroInconsistente( $aRegistrosInconsistentes ) {
    $this->aRegistrosInconsistentes  = $aRegistrosInconsistentes;
  }

  /**
   * Busca o array de Registros inconsistentes
   *
   * @return array
   */
  public function getRegistroInconsistente () {
    return $this->aRegistrosInconsistentes;
  }
}