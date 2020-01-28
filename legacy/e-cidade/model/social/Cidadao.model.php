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
 * Classe para controle do Cidadao
 * @package Assistencia
 * @author Iuri guntchnigg
 *
 */
class Cidadao {

  /**
   * Códiugo do cidadao
   * @var integer Ci
   */
  protected $iCodigo;

  /**
   * Nome do Cidadao
   * @var string
   */
  protected $sNome;

  /**
   * Carteira de identidade/RG do Cidadao
   * @var
   */
  protected $sIdentidade;

  /**
   * CPF/CNPJ do Cidadao
   * @var string
   */
  protected $sCpfCnpj;

  /**
   * Endereço do cidadão
   * @var string
   */
  protected $sEndereco;

  /**
   * Número de moraria do cidadao
   * @var string
   */
  protected $sNumero;

  /**
   * Complemento do endereço
   * @var string
   */
  protected $sComplemento;

  /**
   * bairro do cidadao
   * @string
   */
  protected $sBairro;

  /**
   * municpio do residencia do cidadao
   * @var string
   */
  protected $sMunicipio;

  /**
   * UF de Moradia do Cidadao
   * @var string
   */
  protected $sUF;

  /**
   * Cep  de moradia
   * @var string
   */
  protected $sCEP;

  /**
   * Sitaucao do Cadastro do cidadao
   * @var string
   */
  protected $iSituacaoCidadao;

  /**
   * Cadastro ativo
   * @var boolean
   */
  protected $lAtivo;

  /**
   * data da ultima manutencao do cadastro.
   * @var string
   */
  protected $sDataManutencao;

  /**
   * Telefones que o cidadao possui para contado.
   * @var array
   */
  protected $aTelefones = array();

  /**
   * Código sequencial interno @var ov02_seq
   */
  protected $iSequencial = 1;

  /**
   * Vinculo com a tabela CGM (o cadastro foi migrado para o CGM)
   * @var cgmBase
   */
  protected $oCgm;

  /**
   * Códigfo da avalidao do Cidadao
   * @var integer
   */
  const CODIGO_AVALICAO = 3000004;

  /**
   * Código do Grupo de Respostas do cidadao
   * @var integer
   */
  protected $iCodigoGrupoRespostas;
  /**
   * Avaliacao do Cidadao
   * @var Avaliacao
   */
  protected $oAvaliacao;

  /**
   * Tipos de contato o para retorno do Cidadao
   * @var array
   */
  protected $aTiposRetorno = array();

  /**
   * Código do Tipo de familiar.
   * Ex: tio, tia, pai, mae
   * tabela: tipofamiliar
   * @var integer
   */
  protected $iCodigoTipoFamilia;

  /**
   * Descricao do Tipo de familiar.
   * Ex: tio, tia, pai, mae
   * tabela: tipofamiliar
   * @var integer
   */
  protected $sTipoFamilia;

  /**
   * Código do cadastro da avaliacao
   */
  protected $iCodigoLancamentoAvaliacao;

  /**
   * Código sequencial do cadastro unico no sistema
   * @var integer
   */
  protected $iSequencialCadastroUnico;

  /**
   * Familia do Cidadao
   * @var Familia
   */
  protected $oFamilia = null;

  /**
   * Data de nascimento do cidadao
   * @var string
   */
  protected $dtNascimento;

  /**
   * Sexo do cidadao
   * @var string
   */
  protected $sSexo;

  /**
   * lista de emails do cidadao
   * @var CidadaoEmail
   */
  protected $aEmails = array();

  /**
   * Controle para modificacao de emails
   * @var boolean
   */
  protected $lModificacaoEmail = false;

  /**
   * Pai do Cidadao
   * @var CidadaoFiliacao
   */
  private $oFiliacaoPai;

  /**
   * Mae do Cidadao
   * @var CidadaoFiliacao
   */
  private $oFiliacaoMae;

  /**
   * Cria uma nova instancia do cidadão
   * @param integer $iCodigo codigo do cidadao ov02_sequencial;
   * @param integer $iOrdem
   */
  public function __construct($iCodigo = null, $iOrdem = null) {

    if (!empty($iCodigo)) {

      $oDaoCidadao      = new cl_cidadao();
      $sSqlDadosCidadao = $oDaoCidadao->sql_query_cadastrounico($iCodigo,
                                                                $iOrdem,
                                                                 "cidadao.*, as02_sequencial",
                                                                 "ov02_seq desc limit 1");
      $rsDadosCidadao   = $oDaoCidadao->sql_record($sSqlDadosCidadao);
      if ($oDaoCidadao->numrows > 0) {

        $oDadosCidadao          = db_utils::fieldsMemory($rsDadosCidadao, 0);
        $this->iCodigo          = $oDadosCidadao->ov02_sequencial;
        $this->iSequencial      = $oDadosCidadao->ov02_seq;
        $this->sDataManutencao  = db_formatar($oDadosCidadao->ov02_data, 'd');
        $this->setAtivo($oDadosCidadao->ov02_ativo == 't'?true:false);
        $this->setBairro($oDadosCidadao->ov02_bairro);
        $this->setCEP($oDadosCidadao->ov02_cep);
        $this->setComplemento($oDadosCidadao->ov02_compl);
        $this->setCpfCnpj($oDadosCidadao->ov02_cnpjcpf);
        $this->setEndereco($oDadosCidadao->ov02_endereco);
        $this->setIdentidade($oDadosCidadao->ov02_ident);
        $this->setMunicipio($oDadosCidadao->ov02_munic);
        $this->setNome($oDadosCidadao->ov02_nome);
        $this->setNumero($oDadosCidadao->ov02_numero);
        $this->setUF($oDadosCidadao->ov02_uf);
        $this->iSequencialCadastroUnico  = $oDadosCidadao->as02_sequencial;
        $this->setSituacaoCidadao($oDadosCidadao->ov02_situacaocidadao);
        $this->dtNascimento = db_formatar($oDadosCidadao->ov02_datanascimento, "d");
        $this->sSexo        = $oDadosCidadao->ov02_sexo;
      }
    }
  }

  /**
   * Retorna o codigo do cadastro do cidadao
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a Situacao do cadastro do cidadao
   * @return string
   */
  public function getSituacaoCidadao() {
    return $this->iSituacaoCidadao;
  }

  /**
   * define sitaucao do cadastro do cidadao.
   * @param string $iSituacaoCidadao
   */
  public function setSituacaoCidadao($iSituacaoCidadao) {
    $this->iSituacaoCidadao = $iSituacaoCidadao;
  }

  /**
   * Verifica se o cadastro do cidadao está ativo.
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * Ativa/desativa o cadastro do cidadao
   * @param boolean $lAtivo
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * Retorna o bairro de Moradia
   * @return string
   */
  public function getBairro() {
    return $this->sBairro;
  }

  /**
   * Define o Bairro de Moradia do Cidadão
   * @param string $sBairro Bairro de Moradia
   */
  public function setBairro($sBairro) {
    $this->sBairro = $sBairro;
  }

  /**
   * Retorna o CEP
   * @return string
   */
  public function getCEP() {
    return $this->sCEP;
  }

  /**
   * Define o CEP
   * @param string $sCEP
   */
  public function setCEP($sCEP) {
    $this->sCEP = $sCEP;
  }

  /**
   * Retorna o complemento do endereco
   * @return string
   */
  public function getComplemento() {
    return $this->sComplemento;
  }

  /**
   * Define o complemento do endereço do Cidadao
   * EX. Apto 111, casa dos fundos,
   * @param string $sComplemento
   */
  public function setComplemento($sComplemento) {
    $this->sComplemento = $sComplemento;
  }

  /**
   * @return string
   */
  public function getCpfCnpj() {
    return $this->sCpfCnpj;
  }

  /**
   * Define o CPF/CNPJ do Cidadao
   * @param string $sCpfCnpj
   */
  public function setCpfCnpj($sCpfCnpj) {
    $this->sCpfCnpj = $sCpfCnpj;
  }

  /**
   * Retorna a data que foi realizado a ultima atualizacao nos dados.
   * @return string no formato 'dd/mm/yyyy'
   */
  public function getDataManutencao() {
    return $this->sDataManutencao;
  }

  /**
   * Retorna o endereço de moradia
   * @return string
   */
  public function getEndereco() {
    return $this->sEndereco;
  }

  /**
   * Define o endereco de moradia
   * @param string $sEndereco
   */
  public function setEndereco($sEndereco) {
    $this->sEndereco = $sEndereco;
  }

  /**
   * Retorna o número de identidade/RG
   * @return string
   */
  public function getIdentidade() {
    return $this->sIdentidade;
  }

  /**
   * Define o número de identidade/RG
   * @param string $sIdentidade Número de Identidade/RG
   */
  public function setIdentidade($sIdentidade) {
    $this->sIdentidade = $sIdentidade;
  }

  /**
   * retorna o município de moradia do cidadao.
   * @return string
   */
  public function getMunicipio() {
    return $this->sMunicipio;
  }

  /**
   * Define o município de moradia do cidadao.
   * @param string $sMunicipio Municipio de moradia
   */
  public function setMunicipio($sMunicipio) {
    $this->sMunicipio = $sMunicipio;
  }

  /**
   * Retorna o nome do cidadao
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Define o nome do cidadao
   * @param string $sNome Nome do cidadao
   */
  public function setNome($sNome) {
    $this->sNome = mb_strtoupper(trim($sNome));
  }

  /**
   * Retorna o Número do endereço do cidadao
   * @return string
   */
  public function getNumero() {
    return $this->sNumero;
  }

  /**
   * Define o Número do endereço do cidadao
   * @param string $sNumero Nuúmero da casa/Apartamento
   */
  public function setNumero($sNumero) {
    $this->sNumero = $sNumero;
  }

  /**
   * Retorna a UF de Moradia do Cidadao
   * @return string
   */
  public function getUF() {
    return $this->sUF;
  }

  /**
   * Define a UF do Cidadao
   * @param string $sUF UF do Cidadao
   */
  public function setUF($sUF) {
    $this->sUF = $sUF;
  }

  /**
   * Retornoa o vinculo com o CGM.
   * @return CgmBase
   */
  public function getCgm() {

    if (empty($this->oCgm) && !empty($this->iCodigo)) {

      $oDaoCidadaoCgm  = db_utils::getDao("cidadaocgm");
      $sWhere          = "ov03_cidadao = {$this->iCodigo}";
      $sWhere         .= " and ov03_seq = {$this->iSequencial}";
      $sSqlVinculoCgm  = $oDaoCidadaoCgm->sql_query_file(null, "ov03_numcgm", null, $sWhere);
      $rsVinculoCgm    = $oDaoCidadaoCgm->sql_record($sSqlVinculoCgm);
      if ($oDaoCidadaoCgm->numrows > 0) {
        $this->oCgm = CgmFactory::getInstanceByCgm(db_utils::fieldsMemory($rsVinculoCgm, 0)->ov03_numcgm);
      }
    }
    return $this->oCgm;
  }

  /**
   * Retorna a avaliacao realizada pela Familia
   * @return Avaliacao
   */
  public function getAvaliacao() {

    if (empty($this->oAvaliacao)) {
      $this->buildAvaliacao();
    }
    return $this->oAvaliacao;
  }

  /**
   * Retorna a avaliacao realizada pela cidadao
   * @return Avaliacao
   */
  protected function buildAvaliacao() {

    $oDaoAvaliacao     = db_utils::getDao('cidadaoavaliacao');
    if (empty($this->oAvaliacao)) {

      if ($this->iCodigo != "") {

        $sWhere            = "as01_cidadao = {$this->iCodigo}";
        $sSqlDadosAvalicao = $oDaoAvaliacao->sql_query_file(null, "*", null, $sWhere);

        $rsAvaliacao       = $oDaoAvaliacao->sql_record($sSqlDadosAvalicao);
        $this->oAvaliacao                 = new Avaliacao(Cidadao::CODIGO_AVALICAO);
        if ($oDaoAvaliacao->numrows > 0) {

          $oDadosLancamentoAvalicao         = db_utils::fieldsMemory($rsAvaliacao, 0);
          $this->iCodigoGrupoRespostas      = $oDadosLancamentoAvalicao->as01_avaliacaogruporesposta;
          $this->iCodigoLancamentoAvaliacao = $oDadosLancamentoAvalicao->as01_sequencial;
          $this->oAvaliacao->setAvaliacaoGrupo($this->iCodigoGrupoRespostas);
        }
      }
    }

    /**
     * Caso nao exista o grupo de respostas para o cidadao,
     * devemos criar um.
     */
    if (empty($this->iCodigoGrupoRespostas)) {

      $this->oAvaliacao            = new Avaliacao(Cidadao::CODIGO_AVALICAO);
      $this->iCodigoGrupoRespostas = $this->oAvaliacao->setAvaliacaoGrupo()->getAvaliacaoGrupo();
      $this->oAvaliacao->setAvaliacaoGrupo($this->iCodigoGrupoRespostas);
      if (empty($this->iCodigoLancamentoAvaliacao) && $this->iCodigo != "") {

        $oDaoAvaliacaoCidadao                              = db_utils::getDao("cidadaoavaliacao");
        $oDaoAvaliacaoCidadao->as01_avaliacaogruporesposta = $this->getCodigoGrupoResposta();
        $oDaoAvaliacaoCidadao->as01_cidadao                = $this->getCodigo();
        $oDaoAvaliacaoCidadao->as01_cidadao_seq            = $this->getSequencialInterno();
        $oDaoAvaliacaoCidadao->incluir(null);
        if ($oDaoAvaliacaoCidadao->erro_status == 0) {
          throw new BusinessException("Erro ao salvar dados da avaliacao do cidadao.\n{$oDaoAvaliacaoCidadao->erro_msg}");
        }
        $this->iCodigoLancamentoAvaliacao = $oDaoAvaliacaoCidadao->as01_sequencial;
      }
    }
    return $this->oAvaliacao;
  }

  /**
   * define o codigo tipo de familiar.
   * Ex: tio, tia, pai, mae
   * tabela: tipofamiliar
   * @param integer $iCodigoTipoFamilia
   */
  public function setCodigoTipoFamilia($iCodigoTipoFamilia) {

    $this->iCodigoTipoFamilia = $iCodigoTipoFamilia;
  }
  /**
   * retorna o codigo Tipo de familiar.
   * Ex: tio, tia, pai, mae
   * tabela: tipofamiliar
   * @param integer $iCodigoTipoFamilia
   */
  public function getCodigoTipoFamilia() {

    return $this->iCodigoTipoFamilia;
  }

  /**
   * define a descricao do tipo de familiar.
   * Ex: tio, tia, pai, mae
   * tabela: tipofamiliar
   * @param integer $iCodigoTipoFamilia
   */
  public function setTipoFamilia($sTipoFamilia) {

  	$this->sTipoFamilia = $sTipoFamilia;
  }
  /**
   * retorna  a descricao do Tipo de familiar.
   * Ex: tio, tia, pai, mae
   * tabela: tipofamiliar
   * @param integer $iCodigoTipoFamilia
   */
  public function getTipoFamilia() {

  	return $this->sTipoFamilia;
  }


  /**
   * Retorna o codigo sequencial interno do cidadao.
   * default 1
   * @return integer
   */
  public function getSequencialInterno() {
    return $this->iSequencial;
  }

  /**
   * Salva os dados do Cidadao no sistema.
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação Ativa.');
    }

    $sIdentidade                        = $this->getIdentidade();
    if (trim($sIdentidade) == "") {
      $sIdentidade = "00000000000";
    }
    $oDaoCidadao                          = db_utils::getDao("cidadao");
    $oDaoCidadao->ov02_ativo              = $this->isAtivo()?'true':'false';
    $oDaoCidadao->ov02_bairro             = $this->getBairro();
    $oDaoCidadao->ov02_cep                = $this->getCEP();
    $oDaoCidadao->ov02_cnpjcpf            = $this->getCpfCnpj();
    $oDaoCidadao->ov02_compl              = $this->getComplemento();
    $oDaoCidadao->ov02_data               = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoCidadao->ov02_endereco           = $this->getEndereco();
    $oDaoCidadao->ov02_ident              = $sIdentidade;
    $oDaoCidadao->ov02_munic              = $this->getMunicipio();
    $oDaoCidadao->ov02_uf                 = $this->getUF();
    $oDaoCidadao->ov02_nome               = $this->getNome();
    $oDaoCidadao->ov02_numero             = $this->getNumero();
    $oDaoCidadao->ov02_situacaocidadao    = $this->getSituacaoCidadao();
    $oDaoCidadao->ov02_seq                = $this->iSequencial;
    $oDaoCidadao->ov02_datanascimento     = $this->dtNascimento;
    $oDaoCidadao->ov02_sexo               = $this->sSexo;

    if (!empty($this->iCodigo) && !empty($this->iSequencial)) {

      $daoCidadao = new cl_cidadao();
      $buscaCidadao = $daoCidadao->sql_query_file($this->iCodigo, $this->iSequencial);
      $resBuscaCidadao = db_query($buscaCidadao);
      if (!$resBuscaCidadao) {
        throw new Exception("Ocorreu um erro ao buscar os dados do cidadão.");
      }

      if (pg_num_rows($resBuscaCidadao) === 0) {
        $oDaoCidadao->incluir($this->iCodigo, $this->iSequencial);
        if ($oDaoCidadao->erro_status === "0") {
          throw new Exception("Ocorreu um erro ao salvar os dados do cidadão. {$oDaoCidadao->erro_msg}");
        }
      }
    }

    if (empty($this->iCodigo)) {

      $oDaoCidadao->incluir(null, $oDaoCidadao->ov02_seq);
      $this->iCodigo = $oDaoCidadao->ov02_sequencial;
    } else {

      $oDaoCidadao->ov02_sequencial = $this->iCodigo;
      $oDaoCidadao->ov02_seq        = $this->iSequencial;
      $oDaoCidadao->alterar($this->iCodigo, $this->iSequencial);
    }
    /**
     * caso houve erro na inclusao, ou alteração dos dados do cidadao,
     * lançamos uma excessão para o usuário
     */
    if ($oDaoCidadao->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados do Cidadao {$this->iCodigo} - {$this->sNome}.\n\n{$oDaoCidadao->erro_msg}");
    }

    /**
     * Salvamos os telefones do Cidadao
     */
    $sWhereTelefone = " ov07_cidadao = {$this->getCodigo()}";
    $sWhereTelefone.= " and ov07_seq = {$this->getSequencialInterno()}";

    $oDaoCidadaoTelefone = new cl_cidadaotelefone();
    $oDaoCidadaoTelefone->excluir(null, $sWhereTelefone);
    if ($oDaoCidadaoTelefone->erro_status == 0) {
      throw new BusinessException("Erro ao alterar os dados de telefone. \n{$oDaoCidadaoTelefone->erro_msg}");
    }
    foreach ($this->aTelefones as $oTelefone) {
      $oTelefone->salvar($this->getCodigo(), $this->iSequencial);
    }
    /**
     * Salvamos as formas de retorno que o cidadao solicitou.
     */
    $sWhereRetorno      = "ov04_cidadao  = {$this->getCodigo()} ";
    $sWhereRetorno     .= " and ov04_seq = {$this->iSequencial} ";
    $oDaoRetornoCidadao = db_utils::getDao("cidadaotiporetorno");

    $oDaoRetornoCidadao->excluir(null, $sWhereRetorno);
    if ($oDaoRetornoCidadao->erro_status == 0) {
      throw new BusinessException("Erro ao alterar os dados do cidadao. \n{$oDaoRetornoCidadao->erro_msg}");
    }

    /**
     * Salvamos todas as formas de retorno que o cidadoa solicitou.
     */
    foreach ($this->getFormasDeRetorno() as $oTipoRetorno) {

      $oDaoRetornoCidadao->ov04_cidadao     = $this->getCodigo();
      $oDaoRetornoCidadao->ov04_seq         = $this->iSequencial;
      $oDaoRetornoCidadao->ov04_tiporetorno = $oTipoRetorno->getCodigoRetorno();
      $oDaoRetornoCidadao->incluir(null);
      if ($oDaoRetornoCidadao->erro_status == 0) {
       throw new BusinessException("Erro ao alterar os dados do cidadao. \n{$oDaoRetornoCidadao->erro_msg}");
      }
    }

    /**
     * Persistir os emails, caso houve alteração nos mesmos
     */
    if ($this->lModificacaoEmail) {

      $sWhereEmail = " ov08_cidadao = {$this->getCodigo()}";
      $sWhereEmail.= " and ov08_seq = {$this->getSequencialInterno()}";

      $oDaoCidadaoEmail = new cl_cidadaoemail();
      $oDaoCidadaoEmail->excluir(null, $sWhereEmail);
      if ($oDaoCidadaoEmail->erro_status == 0) {
        throw new BusinessException("Erro ao alterar os dados de email. \n{$oDaoCidadaoEmail->erro_msg}");
      }

      foreach ($this->getEmails() as $oEmail) {

        $oDaoCidadaoEmail->ov08_cidadao   = $this->getCodigo();
        $oDaoCidadaoEmail->ov08_seq       = $this->getSequencialInterno();
        $oDaoCidadaoEmail->ov08_email     = $oEmail->getEmail();
        $oDaoCidadaoEmail->ov08_principal = $oEmail->isPrincipal()?"true":"false";
        $oDaoCidadaoEmail->incluir(null);
        if ($oDaoCidadaoEmail->erro_status == 0) {
          throw new BusinessException("Erro ao alterar os dados de telefone. \n{$oDaoCidadaoEmail->erro_msg}");
        }
      }
    }
    $this->salvarFiliacao();
  }

  /**
   * persiste os dados de filiacao do cidadao
   * @throws DBException
   */
  protected function salvarFiliacao() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação Ativa.');
    }

    /**
     * limpamos a filiacao do cidadao
     */
    $sWhereFiliacao        = "ov29_cidadao  = {$this->getCodigo()} ";
    $sWhereFiliacao       .= " and ov29_cidadao_seq = {$this->iSequencial} ";
    $oDaoCidadaoFiliacao   = new cl_cidadaofiliacao();
    $oDaoCidadaoFiliacao->excluir(null, $sWhereFiliacao);
    if ($oDaoCidadaoFiliacao->erro_status == 0) {
      throw new BusinessException('Erro ao atualizar dados da filiacao do cidadao');
    }

    $aFiliacao = array();
    if ($this->getPai() != null) {
      $aFiliacao[] = $this->getPai();
    }

    if ($this->getMae() != null) {
      $aFiliacao[] = $this->getMae();
    }

    $oDaoCidadaoFiliacao->ov29_cidadao     = $this->getCodigo();
    $oDaoCidadaoFiliacao->ov29_cidadao_seq = $this->getSequencialInterno();

    foreach ($aFiliacao as $oFiliacao) {

      $oDaoCidadaoFiliacao->ov29_cidadaovinculo     = $oFiliacao->getCidadao()->getCodigo();
      $oDaoCidadaoFiliacao->ov29_cidadaovinculo_seq = $oFiliacao->getCidadao()->getSequencialInterno();
      $oDaoCidadaoFiliacao->ov29_tipofamiliar       = $oFiliacao->getTipoFiliacao()->getCodigo();
      $oDaoCidadaoFiliacao->incluir(null);
      if ($oDaoCidadaoFiliacao->erro_status == 0) {
        throw new BusinessException('Erro ao atualizar dados da filiacao do cidadao');
      }
    }
  }


  /**
   * Adiciona um Telefone ao Cadastro do Cidadao
   * @param string $sNumeroTelefone Número do Telefone
   * @param integer $iTipoTelefone Tipo do telefone
   * @param boolean $lPrincial  Se o telefone é o contato principal
   * @param string $sDDD DDD do telefone
   * @param string $sRamal ramal do telefone
   * @throws ParameterException, BusinessException
   */
  public function adicionarTelefone($sNumeroTelefone, $iTipoTelefone, $lPrincipal = true, $sDDD = '', $sRamal = '', $sObservacoes = '') {

    if (empty($sNumeroTelefone)) {
      throw new ParameterException("Número do Telefone não informado.");
    }
    if (empty($iTipoTelefone)) {
      throw new ParameterException("Tipo do telefone não informado.");
    }

    /**
     * Carregamos os telefones já existentes para o cidadao.
     */
    $this->getTelefones();
    $oTelefone = new CidadaoTelefone();
    $oTelefone->setNumeroTelefone($sNumeroTelefone);
    $oTelefone->setCodigoTipoTelefone($iTipoTelefone);
    $oTelefone->setDDD($sDDD);
    $oTelefone->setRamal($sRamal);
    $oTelefone->setObservacao($sObservacoes);
    $oTelefone->setTelefonePrincipal($lPrincipal);
    $this->aTelefones[] = $oTelefone;
  }

  /**
   * Retorna a lista de atelefones do Cidadao
   * @return CidadaoTelefone[]
   */
  public function getTelefones() {

    /**
     * caso os telefones nao foram carregados, verificamos se já existem algum telefone lançado.
     */
    if (count($this->aTelefones) == 0 && $this->getCodigo() != null) {

      $oDaoCidadaoTelefone  = db_utils::getDao("cidadaotelefone");
      $sWhereTelefones      = "ov07_cidadao  = {$this->getCodigo()} ";
      $sWhereTelefones     .= " and ov07_seq = {$this->iSequencial} ";
      $sSqlTelefones        = $oDaoCidadaoTelefone->sql_query(null,
                                                              "ov07_sequencial",
                                                              "ov07_sequencial",
                                                              $sWhereTelefones
                                                             );
      $rsTelefones = $oDaoCidadaoTelefone->sql_record($sSqlTelefones);
      $aTelefones  = db_utils::getCollectionByRecord($rsTelefones);
      foreach ($aTelefones as $oDadosTelefone) {
        $this->aTelefones[] = new CidadaoTelefone($oDadosTelefone->ov07_sequencial);
      }
      unset($oDadosTelefone);
      unset($aTelefones);
    }
    return $this->aTelefones;
  }

  /**
   * Retorna a forma de retorno do Cidadao
   * @return array
   */
  public function getFormasDeRetorno() {

    if (count($this->aTiposRetorno) == 0 && $this->getCodigo() != "") {

      $oDaoRetornoCidadao = db_utils::getDao("cidadaotiporetorno");
      $sWhereRetorno      = "ov04_cidadao  = {$this->getCodigo()} ";
      $sWhereRetorno     .= " and ov04_seq = {$this->iSequencial} ";
      $sSqlTiposRetorno   = $oDaoRetornoCidadao->sql_query_file(null,
                                                                "ov04_tiporetorno",
                                                                "ov04_sequencial",
                                                                $sWhereRetorno
                                                                );

      $rsTiposDeRetorno = $oDaoRetornoCidadao->sql_record($sSqlTiposRetorno);
      $aTiposRetorno    = db_utils::getCollectionByRecord($rsTiposDeRetorno);
      foreach ($aTiposRetorno as $oTipoRetorno) {
        $this->aTiposRetorno[] = new FormaRetorno($oTipoRetorno->ov04_tiporetorno);
      }
      if (count($aTiposRetorno) == 0) {
        $this->aTiposRetorno[] = new FormaRetorno(1);
      }
      unset($aTiposRetorno);
      unset($oTipoRetorno);
    }
    return $this->aTiposRetorno;
  }

  /**
   * Adiciona uma forma de retorno ao Cidadao
   * @param FormaRetorno $oFormaDeRetorno instancia da classe FormaRetorno
   */
  public function adicionarFormaDeRetorno(FormaRetorno $oFormaDeRetorno) {

    $aFormasDeRetorno = $this->getFormasDeRetorno();
    foreach ($aFormasDeRetorno as $oFormaDeRetornoCadastradas) {

      if ($oFormaDeRetornoCadastradas->getCodigoRetorno() == $oFormaDeRetorno->getCodigoRetorno()) {
        throw new BusinessException("Forma de Retorno {$oFormaDeRetorno->getDescricao()} já adicionada para esse Cidadao.");
      }
    }
    $this->aTiposRetorno[] = $oFormaDeRetorno;
  }

  /**
   * Retorna a Renda mensal do Cidadao
   */
  public function getRendaMensal() {

    $nRendaMensal = 0 ;
    $aRespostas   = $this->getAvaliacao()->getRespostasDaPerguntaPoCodigo(3000122);
    foreach ($aRespostas as $oResposta) {

      if ($oResposta->codigoresposta == 3000446 && $oResposta->textoresposta != "") {

        $nValorResposta  = number_format($oResposta->textoresposta, 2, ".", "");
        $nRendaMensal += $nValorResposta;
      }
    }
    return $nRendaMensal;
  }

  /**
   * Retorna o telefone principal do cidadao
   * @return CidadaoTelefone
   */
  public function getTelefonePrincipal () {

  	foreach ($this->getTelefones() as $oTelefone) {

  		if ($oTelefone->isTelefonePrincipal()) {
  			return $oTelefone;
  		}
  	}
  }

  /**
   * Retorna o código do grupo de respostas
   * @return integer
   */
  public function getCodigoGrupoResposta() {
    return $this->iCodigoGrupoRespostas;
  }

  /**
   * Retorna o codigo de lancamento da avaliacao
   * @return integer
   */
  public function getCodigoLancamentoAvaliacao() {
    return $this->iCodigoLancamentoAvaliacao;
  }

  /**
   * Verifica se o Cidadao possui cadastro unico Cadastrado
   * @return boolean
   */
  public function hasCadastroUnico() {
    return !empty($this->iSequencialCadastroUnico);
  }

  /**
   * Retorna o codigo de cadastro no ecidade para o cadastro unico.
   * @return integer
   */
  public function getSequencialCadastroUnico() {
    return $this->iSequencialCadastroUnico;
  }

  /**
   *retorna a familia que o cidadao pertence
   * @return Familia
   */
  public function getFamilia() {

    if ($this->oFamilia == null) {

      $oDaoCidadaoComposicaoFamiliar = db_utils::getDao("cidadaocomposicaofamiliar");

      $sWhere             = "as03_cidadao     = {$this->getCodigo()}";
      $sWhere            .= " and as03_cidadao_seq = 1";
      $sSqlCodigoFamilia  = $oDaoCidadaoComposicaoFamiliar->sql_query_file(null, 'as03_cidadaofamilia', null, $sWhere);
      $rsCodigoFamilia    = $oDaoCidadaoComposicaoFamiliar->sql_record($sSqlCodigoFamilia);
      if ($oDaoCidadaoComposicaoFamiliar->numrows > 0) {
        $this->oFamilia = FamiliaRepository::getFamiliaByCodigo(db_utils::fieldsMemory($rsCodigoFamilia, 0)->as03_cidadaofamilia);
      }
    }

    return $this->oFamilia;
  }

  /**
   * seta a data de nascimento
   * @param string $dtNascimento
   */
  public function setDataNascimento ($dtNascimento) {
    $this->dtNascimento = $dtNascimento;
  }
  /**
   * retorna a data de nascimento
   * @return string
   */
  public function getDataNascimento() {
    return $this->dtNascimento;
  }

  /**
   * seta o sexo
   * @param string $sSexo
   */
  public function setSexo ($sSexo) {
    $this->sSexo = $sSexo;
  }
  /**
   * Retorna o sexo do aluno
   * @return string
   */
  public function getSexo () {
    return $this->sSexo ;
  }

  /**
   * Retorna os cursos realizados pelo cidadao. Recebe um boolean como parametro que valida se devem ser listados
   * todos os cursos ou somente os em andamento
   * @param boolean $lEmAndamento
   * @return array
   */
  public function getCursosCidadao($lEmAndamento = true) {

    $aCursos                   = array();
    $oDaoCursoSocialCidadao    = new cl_cursosocialcidadao();
    $sWhereCursoSocialCidadao  = "as22_cidadao = {$this->getCodigo()} and as22_cidadao_seq = {$this->getSequencialInterno()}";

    if ($lEmAndamento) {

      $sDataAtual                = date("Y-m-d");
      $sWhereCursoSocialCidadao .= " and as19_fim <= {$sDataAtual}";
    }

    $sSqlCursoSocialCidadao = $oDaoCursoSocialCidadao->sql_query(
                                                                  null,
                                                                  "as19_sequencial",
                                                                  "as19_sequencial",
                                                                  $sWhereCursoSocialCidadao
                                                                );
    $rsCursoSocialCidadao     = $oDaoCursoSocialCidadao->sql_record($sSqlCursoSocialCidadao);
    $iTotalCursoSocialCidadao = $oDaoCursoSocialCidadao->numrows;

    if ($iTotalCursoSocialCidadao > 0) {

      for ($iContador = 0; $iContador < $iTotalCursoSocialCidadao; $iContador++) {

        $iCursoSocial = db_utils::fieldsMemory($rsCursoSocialCidadao, $iContador)->as19_sequencial;
        $oCursoSocial = CursoSocialRepository::getCursoSocialByCodigo($iCursoSocial);
        $aCursos[]    = $oCursoSocial;
      }
    }

    return $aCursos;
  }

  /**
   * Retorna a lista de emails do cidadao
   * @return CidadaoEmail[]
   */
  public function getEmails() {

    if (count($this->aEmails)  == 0 && $this->getCodigo() != null) {

      $sWhere  = "ov08_cidadao = {$this->getCodigo()}";
      $sWhere .= " and ov08_seq     = {$this->getSequencialInterno()}";

      $oDaoCidadaoEmail = new cl_cidadaoemail();
      $sSqlCidadaoEmail = $oDaoCidadaoEmail->sql_query_file(null,
                                                            "ov08_Email, ov08_principal",
                                                            'ov08_sequencial',
                                                            $sWhere
                                                            );
      $rsEmails  = $oDaoCidadaoEmail->sql_record($sSqlCidadaoEmail);
      if ($rsEmails && $oDaoCidadaoEmail->numrows > 0) {

        for ($iEmail = 0; $iEmail < $oDaoCidadaoEmail->numrows; $iEmail++) {

          $oDadosEmail = db_utils::fieldsMemory($rsEmails, $iEmail);
          array_push($this->aEmails, new CidadaoEmail(
                                                       $oDadosEmail->ov08_email,
                                                       $oDadosEmail->ov08_principal == 't' ? true : false
                                               )
                    );
        }
      }
    }
    return $this->aEmails;
  }

  /**
   * Adiciona um email a lista de emails do cidadao
   * @param string $sEmail endereco email
   * @param boolean $lPrincipal email principal
   * @throws BusinessException email já cadastrado
   */
  public function adicionarEmail($sEmail, $lPrincipal = false) {

    $aEmail = $this->aEmails;
    foreach ($aEmail as $oEmail) {

      if ($sEmail == $oEmail->getEmail()) {
        return;
      }

      if ($oEmail->isPrincipal() && $lPrincipal) {
        throw new BusinessException(_M('social.social.Cidadao.email_principal_ja_informado'));
      }
    }

    array_push($this->aEmails, new CidadaoEmail($sEmail, $lPrincipal));
    $this->lModificacaoEmail = true;
  }

  /**
   * Remove um email do Cidadao
   * @param string $sEmail email a ser Removido
   */
  public function removerEmail($sEmail = null) {

    foreach ($this->getEmails() as $iIndice => $oEmail) {

      if ($oEmail->getEmail() == $sEmail || empty($sEmail)) {

        array_splice($this->aEmails, 0, 1);
        unset($oEmail);
      }
    }

    $this->lModificacaoEmail = true;
  }

  /**
   * Define o pai do cidadao
   * @param Cidadao $oCidadao
   */
  public function setPai(Cidadao $oCidadao = null) {

    $oDadosCidadao = null;
    if ($oCidadao != null) {
      $oDadosCidadao = new CidadaoFiliacao($oCidadao, TipoFamiliarRepository::getTipoFamiliarByCodigo(5));
    }
    $this->oFiliacaoPai = $oDadosCidadao;
  }

  /**
   * Define a mae do Cidadao
   * @param Cidadao $oCidadao
   */
  public function setMae(Cidadao $oCidadao = null) {

    $oDadosCidadao = null;
    if ($oCidadao != null) {
      $oDadosCidadao = new CidadaoFiliacao($oCidadao, TipoFamiliarRepository::getTipoFamiliarByCodigo(4));
    }
    $this->oFiliacaoMae = $oDadosCidadao;
  }

  /**
   * Retorna o pai do cidadao
   * @return CidadaoFiliacao
   */
  public function getPai() {
    if (empty($this->oFiliacaoPai)) {
      $this->getFiliacao(5);
    }
    return $this->oFiliacaoPai;
  }

  /**
   * Retorna a mae do Cidadao
   * @return CidadaoFiliacao
   */
  public function getMae() {

    if (empty($this->oFiliacaoMae)) {
      $this->getFiliacao(4);
    }
    return $this->oFiliacaoMae;
  }

  /**
   * retorna a filiacao atravez do tipo
   * Usado internamente na classe para construir os dados de filiacao
   * @param unknown $iTipo tipo da filiacao
   */
  protected function getFiliacao($iTipo) {

    if ($this->iCodigo != null) {


      $sWhereFiliacao  = "ov29_cidadao  = {$this->getCodigo()} ";
      $sWhereFiliacao .= " and ov29_cidadao_seq  = {$this->iSequencial} ";
      $sWhereFiliacao .= " and ov29_tipofamiliar = {$iTipo} ";

      $oDaoCidadaoFiliacao      = new cl_cidadaofiliacao();
      $sSqlQueryCidadaoFiliacao = $oDaoCidadaoFiliacao->sql_query_file(null,
                                                                       'ov29_cidadaovinculo,
                                                                        ov29_cidadaovinculo_seq',
                                                                       null,
                                                                       $sWhereFiliacao
                                                                      );
      $rsFiliacao = $oDaoCidadaoFiliacao->sql_record($sSqlQueryCidadaoFiliacao);
      if ($rsFiliacao && $oDaoCidadaoFiliacao->numrows > 0) {
        switch ($iTipo) {

          case 4:

            $this->setMae(new Cidadao(db_utils::fieldsMemory($rsFiliacao, 0)->ov29_cidadaovinculo));
            break;

          case 5:

            $this->setPai(new Cidadao(db_utils::fieldsMemory($rsFiliacao, 0)->ov29_cidadaovinculo));
            break;
        }
      }
    }
  }

  /**
   * @param $nome
   * @param $documento
   *
   * @return bool|Cidadao
   */
  public static function getPorDocumentoENome($nome, $documento) {
    return self::processarBusca($documento, $nome);
  }

  /**
   * @param $documento
   *
   * @return bool|Cidadao
   */
  public static function getPorDocumento($documento) {
    return self::processarBusca($documento);
  }

  /**
   * @param $nome
   * @param $documento
   *
   * @return bool|Cidadao
   * @throws DBException
   * @throws ParameterException
   */
  private static function processarBusca($documento, $nome = null) {

    $documento = trim($documento);
    if (empty($documento)) {
      throw new ParameterException("Documento não informado.");
    }

    $where = array(
      "cidadao.ov02_cnpjcpf = '{$documento}'",
    );

    if (!empty($nome)) {

      $nome = strtoupper(trim($nome));
      $where[] = "trim(cidadao.ov02_nome) = '{$nome}'";
    }

    $where = implode(' and ', $where);
    $daoCidadao = new cl_cidadao();
    $buscaCidadao = $daoCidadao->sql_query_file(null, null, 'ov02_sequencial,ov02_seq', 'ov02_seq desc limit 1', $where);
    $resCidadao   = db_query($buscaCidadao);
    if (!$resCidadao) {
      throw new DBException("Ocorreu um erro ao buscar o cidadão com documento: {$documento}.");
    }

    if (pg_num_rows($resCidadao) === 1) {

      $stdCidadao = db_utils::fieldsMemory($resCidadao, 0);
      return new Cidadao($stdCidadao->ov02_sequencial, $stdCidadao->ov02_seq);
    }
    return false;
  }

  public function __clone() {
    $this->iSequencial += 1;
  }
}
