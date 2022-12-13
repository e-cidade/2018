<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
 * Condicionante para Parecer Técnico
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class ParecerTecnico {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Tipo de Licença
   * @var TipoLicenca
   */
  private $oTipoLicenca = null;

  /**
   * Empreendimento do Parecer
   * @var Empreendimento
   */
  private $oEmpreendimento = null;

  /**
   * Processo do Parecer
   * @var integer
   */
  private $iProtProcesso = null;

  /**
   * Empreendimento do Parecer
   * @var integer
   */
  private $iParecerAnterior = null;

  /**
   * Data de Emissão
   * @var date
   */
  private $dDataEmissao = null;

  /**
   * Data de Vencimento
   * @var date
   */
  private $dDataVencimento = null;

  /**
   * Data de geração
   * @var date
   */
  private $dDataGeracao = null;

  /**
   * Favorável
   * @var boolean
   */
  private $lFavoravel = null;

  /**
   * Obrservações do Parecer
   * @var string
   */
  private $sObservacao = null;

  /**
   * Arquivo do Parecer Técnico
   * @var int
   */
  private $iArquivo = null;


  public function __construct( $iSequencial = null ) {

    $oDaoParecerTecnico = new cl_parecertecnico();
    $rsParecerTecnico   = null;

    if (!is_null($iSequencial)) {

      $sSql             = $oDaoParecerTecnico->sql_query($iSequencial);
      $rsParecerTecnico = $oDaoParecerTecnico->sql_record($sSql);
    }

    if (!is_null($rsParecerTecnico)) {

      $oDados = db_utils::fieldsMemory($rsParecerTecnico, 0);

      $this->iSequencial      = $oDados->am08_sequencial;
      $this->iProtProcesso    = $oDados->am08_protprocesso;
      $this->lFavoravel       = $oDados->am08_favoravel;
      $this->sObservacao      = $oDados->am08_observacao;
      $this->iArquivo         = $oDados->am08_arquivo;

      if( $oDados->am08_favoravel == 't'){

        $this->iParecerAnterior = $oDados->am08_pareceranterior;
        $this->dDataEmissao     = $oDados->am08_dataemissao;
        $this->dDataGeracao     = $oDados->am08_datageracao;
        $this->dDataVencimento  = $oDados->am08_datavencimento;
        $this->oTipoLicenca     = new TipoLicenca($oDados->am08_tipolicenca);
        $this->oEmpreendimento  = new Empreendimento($oDados->am08_empreendimento);
      }
    }
  }

  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Altera o Empreendimento
   * @param Empreendimento
   */
  public function setEmpreendimento ($oEmpreendimento) {
    $this->oEmpreendimento = $oEmpreendimento;
  }

  /**
   * Busca o Empreendimento
   * @return $oEmpreendimento
   */
  public function getEmpreendimento () {
    return $this->oEmpreendimento;
  }

  /**
   * Altera o processo
   * @param integer
   */
  public function setProtProcesso ($iProtProcesso) {
    $this->iProtProcesso = $iProtProcesso;
  }

  /**
   * Busca o processo
   * @return $iProtProcesso
   */
  public function getProtProcesso () {
    return $this->iProtProcesso;
  }

  /**
   * Altera o Parecer Anterior
   * @param integer
   */
  public function setParecerAnterior ($iParecerAnterior) {
    $this->iParecerAnterior = $iParecerAnterior;
  }

  /**
   * Busca a Parcela Anterior
   * @return $iParecerAnterior
   */
  public function getParecerAnterior () {
    return $this->iParecerAnterior;
  }

  /**
   * Altera a Data de Emissao
   * @param date
   */
  public function setDataEmissao ($dDataEmissao) {
    $this->dDataEmissao = $dDataEmissao;
  }

  /**
   * Busca a Data de Emissão
   * @return date
   */
  public function getDataEmissao () {
    return $this->dDataEmissao;
  }

  /**
   * Altera a Data de Vencimento
   * @param date
   */
  public function setDataVencimento ($dDataVencimento) {
    $this->dDataVencimento = $dDataVencimento;
  }

  /**
   * Busca a Data de Vencimento
   * @return date
   */
  public function getDataVencimento () {
    return $this->dDataVencimento;
  }

  /**
   * Altera o Tipo de Licença
   * @param TipoLicenca
   */
  public function setTipoLicenca ($oTipoLicenca) {
    $this->oTipoLicenca = $oTipoLicenca;
  }

  /**
   * Busca O Tipo de Licença
   * @return TipoLicenca
   */
  public function getTipoLicenca () {
    return $this->oTipoLicenca;
  }

  /**
   * Altera a Data de Geração
   * @param date
   */
  public function setDataGeracao ($dDataGeracao) {
    $this->dDataGeracao = $dDataGeracao;
  }

  /**
   * Busca a Data de Geração
   * @return date
   */
  public function getDataGeracao () {
    return $this->dDataGeracao;
  }

  /**
   * Altera o Favorável
   * @param boolean
   */
  public function setFavoravel ($lFavoravel) {
    $this->lFavoravel = $lFavoravel;
  }

  /**
   * Busca o Favorável
   * @return boolean
   */
  public function getFavoravel () {
    return $this->lFavoravel;
  }

  /**
   * Altera o Observações
   * @param string
   */
  public function setObservacao ($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Busca as Observações
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Altera o arquivo do Parecer Técnico
   * @param int
   */
  public function setArquivo ($iArquivo) {
    $this->iArquivo = $iArquivo;
  }

  /**
   * Busca o arquivo do Parecer Técnico
   * @return $iArquivo
   */
  public function getArquivo () {
    return $this->iArquivo;
  }

  /**
   * Salva os dados do Parecer no banco de dados
   * @throws Exception
   */
  public function incluir(){

    try {

      $oDaoParecerTecnico = new cl_parecertecnico();
      $oDaoParecerTecnico->am08_empreendimento  = $this->oEmpreendimento->getSequencial();
      $oDaoParecerTecnico->am08_protprocesso    = $this->iProtProcesso;
      $oDaoParecerTecnico->am08_favoravel       = $this->lFavoravel;
      $oDaoParecerTecnico->am08_observacao      = $this->sObservacao;
      $oDaoParecerTecnico->am08_arquivo         = $this->iArquivo;

      if( $this->lFavoravel == 'true' ){

        $oDaoParecerTecnico->am08_tipolicenca     = $this->oTipoLicenca->getSequencial();
        $oDaoParecerTecnico->am08_dataemissao     = $this->dDataEmissao;
        $oDaoParecerTecnico->am08_datavencimento  = $this->dDataVencimento;
        $oDaoParecerTecnico->am08_datageracao     = $this->dDataGeracao;
        $oDaoParecerTecnico->am08_pareceranterior = $this->iParecerAnterior;
      }else{
        $oDaoParecerTecnico->am08_tipolicenca     = 1;
      }

      $oDaoParecerTecnico->incluir();
      $this->iSequencial = $oDaoParecerTecnico->am08_sequencial;

    } catch (Exception $oErro) {
      throw $oErro;
    }
  }

  /**
   * Este serve para criar o vículo entre o Parecer Técnico e as
   * Condicionantes cadastradas, enviadas array de parâmetro
   * @param array
   * @throws Exception
   */
  public function setCondicionantes($aCondicionantes) {

    try {

        foreach ($aCondicionantes as $key => $oCondicionante) {

          if (!empty($oCondicionante->iSequencial)) {

            $oParecerTecnicoCondicionante = new ParecerTecnicoCondicionante();
            $oParecerTecnicoCondicionante->setCondicionante(new Condicionante($oCondicionante->iSequencial));
            $oParecerTecnicoCondicionante->setParecerTecnico($this);
            $oParecerTecnicoCondicionante->incluir();
          }
        }
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }

  /**
   * Verifica se já houve um parecer emitido
   *
   * @param  int $iTipoEmissao
   * @throws Exception
   */
  public function verificaParecerAnterior($iTipoEmissao) {

    try {

      $oDaoParecerTecnico = db_utils::getDao('parecertecnico');

      $sWhere   = "     am08_empreendimento = " . $this->oEmpreendimento->getSequencial();
      $sWhere  .= " and am08_tipolicenca    = " . $this->oTipoLicenca->getSequencial() ;
      $sWhere  .= " and am08_favoravel      = 't'";

      $sSql     = $oDaoParecerTecnico->sql_query_file(null, "*", "am08_sequencial", $sWhere);
      $rsRecord = $oDaoParecerTecnico->sql_record($sSql);
      $aParacer = db_utils::getCollectionByRecord($rsRecord);

      if ($iTipoEmissao == TIPO_EMISSAO_NOVA) {

        if (!empty($aParacer)) {
          throw new Exception( _M( MENSAGENS . 'erro_licenca_existente' ) );
        }
      }

      if ($iTipoEmissao == TIPO_EMISSAO_PRORROGACAO || $iTipoEmissao == TIPO_EMISSAO_RENOVACAO) {

        if (empty($aParacer)) {
          throw new Exception( _M( MENSAGENS . 'erro_licenca_inexistente' ) );
        }

        if ( $iTipoEmissao == TIPO_EMISSAO_PRORROGACAO ) {
          $this->setParecerAnterior($aParacer[0]->am08_sequencial);
        }
      }
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }

  public function excluir( $iCodigoParecerTecnico ) {

    $oDaoParecerTecnicoCondicionante = new cl_parecertecnicocondicionante();
    $sWhere                          = "am12_parecertecnico = {$iCodigoParecerTecnico}";
    $oDaoParecerTecnicoCondicionante->excluir(null, $sWhere);

    $oDaoParecerTecnico = new cl_parecertecnico();

    $oDaoParecerTecnico->excluir( $iCodigoParecerTecnico );

    if ($oDaoParecerTecnico->erro_status == "0" ) {
      return false;
    }

    return true;
  }

  /**
   * Buscamos o codigo da licença do parecer anterior, caso exista
   *
   * @return int/null
   */
  public function getCodigoLicencaAnterior() {

    try {

      $oDaoParecerTecnico = db_utils::getDao("parecertecnico");
      $sSql               = $oDaoParecerTecnico->sql_query_codigo_licenca($this->iSequencial);
      $rsCodigoLicenca    = $oDaoParecerTecnico->sql_record($sSql);
      $oCodigoLicenca     = db_utils::fieldsMemory($rsCodigoLicenca, 0);

      if (empty($oCodigoLicenca->am13_sequencial)) {
        return null;
      }

      return $oCodigoLicenca->am13_sequencial;
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }

  public function getTipoEmissao($iCodigoEmpreendimento, $iTipoLicenca, $iCodigoParecerTecnico) {

    try {

      $aTiposEmissao = array(
          TIPO_EMISSAO_NOVA        => utf8_encode("Nova"),
          TIPO_EMISSAO_PRORROGACAO => utf8_encode("Prorrogação"),
          TIPO_EMISSAO_RENOVACAO   => utf8_encode("Renovação")
        );

      if ($iTipoLicenca == TIPO_LICENCA_PREVIA || $iTipoLicenca == TIPO_LICENCA_INSTALACAO) {
        unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
      }

      if ($iTipoLicenca == TIPO_LICENCA_OPERACAO) {
        unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
      }

      $sWhere    = "am08_empreendimento = {$iCodigoEmpreendimento}";
      $sWhere   .= " and am08_tipolicenca = {$iTipoLicenca}";

      $oDaoParecerTecnico = new cl_parecertecnico();

      $sSql      = $oDaoParecerTecnico->sql_query_file(null, "*", null, $sWhere);
      $rsRecord  = $oDaoParecerTecnico->sql_record($sSql);
      $aLicencas = db_utils::getCollectionByRecord($rsRecord);

      if ( empty($aLicencas) ) {

        if (isset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO])) {
          unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
        }

        if (isset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO])) {
          unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
        }
      } else {

        if ($aLicencas[0]->am08_sequencial == $iCodigoParecerTecnico) {

          if (isset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO])) {
            unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
          }

          if (isset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO])) {
            unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
          }
        } else {
          unset($aTiposEmissao[TIPO_EMISSAO_NOVA]);
        }
      }

      return $aTiposEmissao;
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }
}