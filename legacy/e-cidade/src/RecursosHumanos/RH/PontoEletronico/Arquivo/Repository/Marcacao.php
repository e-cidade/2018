<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository;

use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Marcacao as MarcacaoRegistro;

/**
 * Classe responsável pela manutenção aos dados de pontoeletronicomarcacao
 *
 * Class Marcacao
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Marcacao {

  /**
   * @var \cl_pontoeletronicoarquivodata
   */
  private $oDaoData;

  /**
   * @var \cl_pontoeletronicoarquivodata
   */
  private $oDaoDataRegistro;

  /**
   * @var string
   */
  private $sPISNaoEncontrado;

  /**
   * @var null|int
   */
  private $iOrdem = null;

  /**
   * Marcacao constructor.
   */
  public function __construct() {
    $this->oDaoData         = new \cl_pontoeletronicoarquivodata();
    $this->oDaoDataRegistro = new \cl_pontoeletronicoarquivodataregistro();
  }

  /**
   * @return string
   */
  public function getPISNaoEncontrado() {
    return $this->sPISNaoEncontrado;
  }

  /**
   * @param int $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Responsável por persistir os dados da tabela pontoeletronicoarquivodata
   * @param MarcacaoRegistro $oMarcacaoRegistro
   * @throws \DBException
   */
  public function addArquivoData(MarcacaoRegistro $oMarcacaoRegistro) {

    $this->oDaoData->rh197_sequencial              = null;
    $this->oDaoData->rh197_pontoeletronicoarquivo  = $oMarcacaoRegistro->getCabecalho()->getCodigo();
    $this->oDaoData->rh197_data                    = $oMarcacaoRegistro->getDataVinculo()->getDate();
    $this->oDaoData->rh197_pis                     = $oMarcacaoRegistro->getPIS();

    if($oMarcacaoRegistro->getMatricula() === null && $oMarcacaoRegistro->getPIS() != '') {

      $oServidor                       = \ServidorRepository::getServidorByPIS($oMarcacaoRegistro->getPIS());
      $this->oDaoData->rh197_matricula = $oServidor instanceof \Servidor ? $oServidor->getMatricula() : null;
      $sWhereVerificaNovaData          = "rh197_pis = '{$oMarcacaoRegistro->getPIS()}'";
    }

    if($oMarcacaoRegistro->getMatricula() != null) {

      $oServidor                       = \ServidorRepository::getInstanciaByCodigo($oMarcacaoRegistro->getMatricula());
      $this->oDaoData->rh197_matricula = $oServidor->getMatricula();
      $sWhereVerificaNovaData          = "rh197_matricula = {$oServidor->getMatricula()}";
    }

    if($oServidor == null) {

      $this->sPISNaoEncontrado = $oMarcacaoRegistro->getPIS();
      return false;
    }

    $rsVerificaNovaData = db_query(
      "SELECT rh197_sequencial 
         FROM pontoeletronicoarquivodata
        WHERE rh197_data = '{$oMarcacaoRegistro->getDataVinculo()->getDate()}' 
          AND ($sWhereVerificaNovaData)"
    );

    if(!$rsVerificaNovaData) {
      throw new \DBException("Ocorreu um erro ao verificar a(s) data(s) da(s) marcação(ões) do ponto.");
    }

    if(pg_num_rows($rsVerificaNovaData) == 0) {
      $this->oDaoData->incluir(null);
    } else {
      $this->oDaoData->rh197_sequencial = \db_utils::fieldsMemory($rsVerificaNovaData, 0)->rh197_sequencial;
    }

    if($this->oDaoData->erro_status == '0') {
      throw new \DBException($this->oDaoData->erro_msg);
    }
    
    return true;
  }

  /**
   * Persiste os dados da tabela pontoeletronicomarcacao
   * @param MarcacaoRegistro $oMarcacaoRegistro
   * @param bool $lSalvarArquivoData
   * @return MarcacaoRegistro
   * @throws \DBException
   */
  public function add(MarcacaoRegistro $oMarcacaoRegistro, $lSalvarArquivoData = true) {

    if($lSalvarArquivoData) {
      if($this->addArquivoData($oMarcacaoRegistro) === false) {
        return;
      }
    }

    $iOrdem = $this->iOrdem;

    if(is_null($iOrdem)) {

      $rsOrdemRegistro = db_query(
        "SELECT coalesce(max(rh198_ordem),0) as ordem 
         FROM pontoeletronicoarquivodataregistro 
        WHERE rh198_pontoeletronicoarquivodata = {$this->oDaoData->rh197_sequencial}"
      );

      if(!$rsOrdemRegistro || pg_num_rows($rsOrdemRegistro) == 0) {
        throw new \DBException("Ocorreu um erro ao buscar a ordem da marcação.");
      }

      $iOrdem = \db_utils::fieldsMemory($rsOrdemRegistro, 0)->ordem + 1;
    }

    $sAcao = $oMarcacaoRegistro->getCodigo() != null ? 'alterar' :  'incluir';

    $this->oDaoDataRegistro->rh198_sequencial                 = $oMarcacaoRegistro->getCodigo();
    $this->oDaoDataRegistro->rh198_pontoeletronicoarquivodata = $this->oDaoData->rh197_sequencial;
    $this->oDaoDataRegistro->rh198_registro                   = $oMarcacaoRegistro->getHora();
    $this->oDaoDataRegistro->rh198_ordem                      = $iOrdem;
    $this->oDaoDataRegistro->rh198_data                       = $oMarcacaoRegistro->getData()->getDate();
    $this->oDaoDataRegistro->rh198_registro_manual            = $oMarcacaoRegistro->isManual() ? 't' : 'f';
    $this->oDaoDataRegistro->{$sAcao}($oMarcacaoRegistro->getCodigo());

    if($this->oDaoDataRegistro->erro_status == '0') {
      throw new \DBException($this->oDaoDataRegistro->erro_msg);
    }

    $oMarcacaoRegistro->setCodigo($this->oDaoDataRegistro->rh198_sequencial);

    return $oMarcacaoRegistro;
  }
}