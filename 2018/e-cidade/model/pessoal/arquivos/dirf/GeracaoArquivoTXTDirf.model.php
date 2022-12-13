<?php

/**
 *  Classe de geração do arquivo da DIRF para utilização entre 2012 e 2015
 *
 *  @package Pessoal
 *  @subpackage Arquivos/Dirf
 *  @author Rafael Nery <rafael.nery@dbseller.com.br>
 *  @version $Id: GeracaoArquivoTXTDirf.model.php,v 1.8 2017/01/09 11:46:26 dbiuri Exp $
 */
class GeracaoArquivoTXTDirf {

  /**
   *  @todo Criar setters para eles
   */
  private $oDirf;

  private $aRegistros = null;

  private $iAno;
  private $iValor;
  private $sCodigoArquivo;
  private $TipoDeclaracao;
  private $iNumeroRecibo;
  private $sNomeResponsavel;
  private $sCpfResponsavelCNPJ;
  private $sDDDResponsavel;
  private $sFoneResponsavel;
  private $sCpfResponsavel;
  private $iCcgmSaude;
  private $iNumeroANS;
  private $iCcgmSaude2;
  private $iNumeroANS2;
  private $lProcessaEmpenho;
  private $sCnpj;
  private $sAcima6000;
  private $aMatriculaSelecionadas;
  private $lGerarContabil;


  /**
  * tipo de registros de valores gerados.
  * os tipos usados por mes vao de 1 até 11.
  * os demais são valores unicos, em outro registro.
  */
  private $aSiglasTipoArquivo = array(
    1 => "RTRT",
    2 => "RTPO" ,
    3 => "RTPP",
    4 => "RTDP",
    5 => "RTPA",
    6 => "RTIRF",
    7 => "RIP65",
    8 => "RIDAC",
    9 => "RIIRP",
    10 => "RIAP",
    11 => "MOLA",
    12 => "RIMOG",
    13 => "SAUDE1",
    14 => "SAUDE2",
    15 => "RIO",
    16 => "RTRT"
  );



  private $oLayout;

  /**
   *  Contrutor da Classe, define os parametros necessarios para a geração
   */
  public function __construct($lGerarContabil = false, $aMatriculaSelecionadas = array(), $sAcima6000 = "S") {

    $this->sAcima6000             = $sAcima6000;
    $this->aMatriculaSelecionadas = $aMatriculaSelecionadas;
    $this->lGerarContabil         = $lGerarContabil;
  }

  /**
   *  Executa o processo de geração
   * @return String Arquivo Gerado
   * @throws \DBException
   */
  public function processar() {

    $sSqlDadosInstituicao = " select z01_cgccpf as cgc," . PHP_EOL;
    $sSqlDadosInstituicao.= "        z01_nome   as nomeinst" . PHP_EOL;
    $sSqlDadosInstituicao.= "   from orcunidade" . PHP_EOL;
    $sSqlDadosInstituicao.= "        inner join rhlotaexe on rh26_orgao   = o41_orgao" . PHP_EOL;
    $sSqlDadosInstituicao.= "                            and rh26_unidade = o41_unidade" . PHP_EOL;
    $sSqlDadosInstituicao.= "                            and o41_anousu   = rh26_anousu" . PHP_EOL;
    $sSqlDadosInstituicao.= "        inner join rhlota    on r70_codigo   = rh26_codigo" . PHP_EOL;
    $sSqlDadosInstituicao.= "        inner join cgm       on r70_numcgm   = z01_numcgm" . PHP_EOL;
    $sSqlDadosInstituicao.= "  where o41_cnpj   = '{$this->sCnpj}'" . PHP_EOL;
    $sSqlDadosInstituicao.= "    and z01_cgccpf = '{$this->sCnpj}'" . PHP_EOL;
    $rsDadosInstituicao   = db_query($sSqlDadosInstituicao);

    if (!$rsDadosInstituicao) {
      throw new DBException("Erro ao buscar os dados da Instituição.");
    }
    $iNumRowsDadosInstituicao = pg_num_rows($rsDadosInstituicao);

    if ($iNumRowsDadosInstituicao > 0) {
      $oDadosInstituicao  = db_utils::fieldsMemory($rsDadosInstituicao, 0);
    } else {
      $oDadosInstituicao  = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    }

    $this->oWriter->escreverLinhaHeaderArquivo();
    $this->oWriter->escreverLinhaResponsavel();
    $this->oWriter->escreverSecaoDeclarantePessoaJuridica($oDadosInstituicao->nomeinst);
    $this->oWriter->escreverSecaoRRA();
    $this->oWriter->escreverSecaoANS();
    $this->oWriter->escreverInformacoesComplementares();
    $this->oWriter->escreverFooterArquivo();

    return $sNomeArquivo = "dirf_{$this->iAno}_{$this->sCnpj}.txt";
  }

  /**
   *  Define a instancia da Dirf para processamento do TXT
   *
   *  @param Dirf $oDirf [description]
   */
  public function setDirf(Dirf $oDirf) {

    $this->oDirf = $oDirf;
    $sNomeArquivo  = "dirf_{$this->iAno}_{$this->sCnpj}.txt";
    $iCodigoLayout = $this->oDirf->getCodigoLayout();

    $aArquivosGerar = array("Dirf","DECPJ","RESPO","IDREC","BPFDEC","BPJDEC","RTRT","FIMDirf","PSE","RIO","OPSE","TPSE", "INFPC", "INFPA");
    $this->oWriter   = self::createWriter($this->iAno, new db_layouttxt($iCodigoLayout, "tmp/{$sNomeArquivo}", implode(" ", $aArquivosGerar)));
    $this->oWriter->setGeracaoArquivo($this);
  }

  /**
   * Retorna os registro necessários para o processamento do TXT
   * @return array
   * @throws \DBException
   */
  public function getRegistros() {

    if (!is_null($this->aRegistros)) {
      return $this->aRegistros;
    }

    $sMatriculaSelecionadas = $this->oDirf->getMatriculas();

    $oDaoDirf = new cl_rhdirfgeracao();

    $sSqlTipoReceitas = $oDaoDirf->sql_dados_geracao_arquivo(
      $this->lGerarContabil,
      $this->iAno,
      $this->sCnpj,
      $this->oDirf->getValorLimite(),
      $sMatriculaSelecionadas,
      $this->sAcima6000 == "S"
    );

    

    // kill($this->oDirf);
    $rsTipoReceitas    = db_query($sSqlTipoReceitas);

    if (!$rsTipoReceitas) {
      throw new DBException("Erro ao buscar os dados das receitas");
    }

    $iTotalLinhas      = pg_num_rows($rsTipoReceitas);
    $aLinhasDirf       = array();

    for ($i = 0; $i < $iTotalLinhas; $i++) {

      $oTipoReceita = db_utils::fieldsMemory($rsTipoReceitas, $i);

      if (!isset($aLinhasDirf[$oTipoReceita->rh98_tipoirrf])) {

        $oLinhaDirf = new stdClass();
        $oLinhaDirf->receita  = $oTipoReceita->rh98_tipoirrf;
        $oLinhaDirf->fisica   = array();
        $oLinhaDirf->juridica = array();

        $aLinhasDirf[$oTipoReceita->rh98_tipoirrf] = $oLinhaDirf;
      }

      $oPessoa = new stdClass();
      $oPessoa->nome           = $oTipoReceita->z01_nome;
      $oPessoa->codigo_geracao = $oTipoReceita->rh96_sequencial;
      $oPessoa->cgm            = $oTipoReceita->rh96_numcgm;
      $oPessoa->totalsaude1    = 0;
      $oPessoa->totalsaude2    = 0;
      $oPessoa->pagamentos     = array();
      $oPessoa->totaloutros    = 0;
      $oPessoa->informacao_complementar = $this->oDirf->getInformacoesComplementares($oPessoa->cgm);
      $oPessoa->previdencia_privada     = $this->oDirf->getInformacoesPrevidenciaPrivada($oPessoa->cgm);
      $oPessoa->pensionistas            = $this->oDirf->getDadosPensionista($oPessoa, $oTipoReceita->rh98_tipoirrf);
      $oPessoa->pensionistas_rra        = $this->oDirf->getDadosPensionista($oPessoa, '1889');
      $this->oDirf->calculaValoresMensaisTipo($oTipoReceita->rh95_sequencial, $oPessoa, $oTipoReceita->rh98_tipoirrf, $oTipoReceita->sem_retencao=='t', array(3,5, 19));

      if ($oTipoReceita->tipopessoa == 11) {

        if (!isset($aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->fisica[$oTipoReceita->rh96_numcgm])) {

          $oPessoa->portadormolestia = false;
          $oPessoa->deficientefisico = false;
          $oPessoa->datalaudo        = '';

          $sSqlMolestias  = "SELECT rh02_deficientefisico, ";
          $sSqlMolestias .= "       rh02_portadormolestia, ";
          $sSqlMolestias .= "       rh02_datalaudomolestia ";
          $sSqlMolestias .= "  from rhpessoal ";
          $sSqlMolestias .= "       inner join rhpessoalmov on rh01_regist = rh02_regist ";
          $sSqlMolestias .= " where rh02_anousu = " . DBPessoal::getAnoFolha();
          $sSqlMolestias .= "   and rh02_mesusu = " . DBPessoal::getMesFolha();
          $sSqlMolestias .= "   and rh01_numcgm = {$oTipoReceita->rh96_numcgm}";

          $rsMolestias   = db_query($sSqlMolestias);

          if (!$rsMolestias) {
            throw new DBException("Erro ao processar os dados de molestia grave para o cgm({$oTipoReceita->rh96_numcgm})");
          }

          if (pg_num_rows($rsMolestias) > 0) {

            $oDadosMolestia            = db_utils::fieldsMemory($rsMolestias, 0);
            $oPessoa->portadormolestia = $oDadosMolestia->rh02_portadormolestia == "t";
            $oPessoa->deficientefisico = $oDadosMolestia->rh02_deficientefisico == "t";

            if ($oDadosMolestia->rh02_datalaudomolestia != '') {
              $oPessoa->datalaudo = str_replace("-", "", $oDadosMolestia->rh02_datalaudomolestia);
            }
          }

          $oPessoa->cpf  = $oTipoReceita->z01_cgccpf;
          $aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->fisica[$oTipoReceita->rh96_numcgm] = $oPessoa;
        }

      } else if ($oTipoReceita->tipopessoa == 14) {

        if (!isset($aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->juridica[$oTipoReceita->rh96_numcgm])) {

          $oPessoa->cnpj = $oTipoReceita->z01_cgccpf;
          $aLinhasDirf[$oTipoReceita->rh98_tipoirrf]->juridica[$oTipoReceita->rh96_numcgm] = $oPessoa;
        }
      }
      unset($oTipoReceita);
    }

    $this->aRegistros = $aLinhasDirf;
    return $aLinhasDirf;
  }

  /**
   *  Retorna os beneficiarios de rendimentos separados por receita
   *
   *  @return array
   */
  public function getBeneficiariosRRAPorReceita() {


    $aReceitas      = array();
    foreach ($this->getRegistros() as $oRegistroDirf) {

      /**
       *  Zera os beneficiarios da receita
       */
      $aBeneficiarios = array();

      /**
       *  Precorre os registros processaos na dirf para buscar os possiveis beneficiarios do RRA
       */
      foreach ($oRegistroDirf->fisica as $iCodigoPessoa => $oDadosPessoa) {

        /**
         *  Toda pessoa encontrada na DIRF não aparecerá no RRA, a menos que os tipos de registros lancados para ela
         *  sejam do tipo 17,18,19,20,21,22,23
         */
        $lProcessarRRA      = false;

        /**
         *  Cria Objeto básico
         */
        $oDadosRRA          = new stdClass();
        $oDadosRRA->nome    = $oDadosPessoa->nome;
        $oDadosRRA->cpf     = $oDadosPessoa->cpf;
        $oDadosRRA->RTRT    = array();
        $oDadosRRA->RTPO    = array();
        $oDadosRRA->RTPA    = array();
        $oDadosRRA->RTIRF   = array();
        $oDadosRRA->DAJUD   = array();
        $oDadosRRA->QTMESES = array();
        $oDadosRRA->RIMOG   = array();

        /**
         *  Percorre os registros da pessoa
         */
        foreach ($oDadosPessoa->pagamentos as $iTipoRegistro => $aCompetencias) {

          /**
           *  Caso o registro não seja dos tipos do RRA, não perde tempo com isso =)
           */

          if (!in_array($iTipoRegistro, $this->getDirf()->getGruposRRA())) {
            continue;
          }

          $aGruposRRA = array_flip($this->getDirf()->getGruposRRA());
          /**
           *  Se encontrou ao menos 1 registro dos tipos do RRA, a pessoa aparecerá no TXT
           */
          $lProcessarRRA = true;

          $aValores = array();

          foreach ($aCompetencias as $oDadosCompetencia) {
            $aValores[(int)$oDadosCompetencia->rh98_mes] = $oDadosCompetencia->valor;
          }
          /**
           *  Separa cada tipo de registro para que seja melhor manipulado
           */
          $oDadosRRA->{$aGruposRRA[$iTipoRegistro]}  = $aValores;
          if (!empty($oDadosPessoa->pensionistas_rra)) {
            $oDadosRRA->RTPA  = $oDadosPessoa->pensionistas_rra;
          }

        }

        /**
         *  Adiciona o Beneficiario caso esteja ok
         */
        if ($lProcessarRRA) {
          $aBeneficiarios[] = $oDadosRRA;
        }
      }

      /**
       *  adiciona o beneficiarios a receita, se houver
       **/
      if (!empty($aBeneficiarios)) {
        $aReceitas[$oRegistroDirf->receita] = $aBeneficiarios;
      }
    }

    return $aReceitas;
  }



  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  public function setValor($iValor) {
    $this->iValor = $iValor;
  }

  public function setCodigoArquivo($sCodigoArquivo) {
    $this->sCodigoArquivo = $sCodigoArquivo;
  }

  public function setTipoDeclaracao($TipoDeclaracao) {
    $this->TipoDeclaracao = $TipoDeclaracao;
  }

  public function setNumeroRecibo($iNumeroRecibo) {
    $this->iNumeroRecibo = $iNumeroRecibo;
  }

  public function setNomeResponsavel($sNomeResponsavel) {
    $this->sNomeResponsavel = $sNomeResponsavel;
  }

  public function setCpfResponsavelCNPJ($sCpfResponsavelCNPJ) {
    $this->sCpfResponsavelCNPJ = $sCpfResponsavelCNPJ;
  }

  public function setDDDResponsavel($sDDDResponsavel) {
    $this->sDDDResponsavel = $sDDDResponsavel;
  }

  public function setFoneResponsavel($sFoneResponsavel) {
    $this->sFoneResponsavel = $sFoneResponsavel;
  }

  public function setCpfResponsavel($sCpfResponsavel) {
    $this->sCpfResponsavel = $sCpfResponsavel;
  }

  public function setCcgmSaude($iCcgmSaude) {
    $this->iCcgmSaude = $iCcgmSaude;
  }

  public function setNumeroANS($iNumeroANS) {
    $this->iNumeroANS = $iNumeroANS;
  }

  public function setCcgmSaude2($iCcgmSaude2) {
    $this->iCcgmSaude2 = $iCcgmSaude2;
  }

  public function setNumeroANS2($iNumeroANS2) {
    $this->iNumeroANS2 = $iNumeroANS2;
  }

  public function setProcessaEmpenho($lProcessaEmpenho) {
    $this->lProcessaEmpenho = $lProcessaEmpenho;
  }

  public function setCnpj ($sCnpj) {
    $this->sCnpj = $sCnpj;
  }

  public function getAno() {
    return $this->iAno;
  }

  public function getValor() {
    return $this->iValor;
  }

  public function getCodigoArquivo() {
    return $this->sCodigoArquivo;
  }

  public function getTipoDeclaracao() {
    return $this->TipoDeclaracao;
  }

  public function getNumeroRecibo() {
    return $this->iNumeroRecibo;
  }

  public function getNomeResponsavel() {
    return $this->sNomeResponsavel;
  }

  public function getCpfResponsavelCNPJ() {
    return $this->sCpfResponsavelCNPJ;
  }

  public function getDDDResponsavel() {
    return $this->sDDDResponsavel;
  }

  public function getFoneResponsavel() {
    return $this->sFoneResponsavel;
  }

  public function getCpfResponsavel() {
    return $this->sCpfResponsavel;
  }

  public function getCcgmSaude() {
    return $this->iCcgmSaude;
  }

  public function getNumeroANS() {
    return $this->iNumeroANS;
  }

  public function getCcgmSaude2() {
    return $this->iCcgmSaude2;
  }

  public function getNumeroANS2() {
    return $this->iNumeroANS2;
  }

  public function getProcessaEmpenho() {
    return $this->lProcessaEmpenho;
  }

  public function getCnpj() {
    return $this->sCnpj;
  }

  public function getMatriculaSelecionadas() {
    return $this->aMatriculaSelecionadas;
  }

  public function getAcima6000() {
    return $this->sAcima6000;
  }

  public function getGerarContabil() {
    return $this->lGerarContabil;
  }

  public static function createWriter($iAno, $oLayout) {

    if ($iAno >= 2015) {
      return new ArquivoDirf2015($oLayout);
    }
    return new ArquivoDirf2012($oLayout);
  }

  /**
   * @return \Dirf|\Dirf2012|Dirf2015
   */
  public function getDirf() {
    return $this->oDirf;
  }

  public function getSiglas() {
    return $this->aSiglasTipoArquivo;
  }
}
