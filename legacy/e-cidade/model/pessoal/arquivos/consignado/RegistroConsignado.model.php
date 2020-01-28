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
class RegistroConsignado {

  const MOTIVO_FALECIMENTO        = 1;
  const MOTIVO_SERVIDOR_INVALIDO  = 2;
  const MOTIVO_TIPO_CONTRATO      = 3;
  const MOTIVO_MARGEM_EXCEDIDO    = 4;
  const MOTIVO_OUTROS_MOTIVOS     = 5;
  const MOTIVO_SERVIDOR_DESLIGADO = 6;
  const MOTIVO_SERVIDOR_AFASTADO  = 7;
  const MOTIVO_EXCLUIDO           = 8;
  const MOTIVO_SALDO_INSUFICIENTE = 9;

  /**
   * Sequencial do Registro.
   * @var integer $iSequencial
   */
  private $iSequencial = null;

  /**
   * Sequencial do Movimento servidor
   * @var integer $iCodigoMovimento
   */
  private $iCodigoMovimento = null;

  /**
   * Arquivo que o registro pertence
   * @var ArquivoConsignado $oArquivoConsignado
   */
  private $oArquivoConsignado;

  /**
   * Matricula Servidor
   * @var integer $iMatricula
   */ 
  private $iMatricula;

  /**
   * Nome Servidor
   * @var string $sNome
   */ 
  private $sNome;

  /**
   * Motivo pelo qual o registro não foi descontado
   * @var integer $iMotivo
   */
  private $iMotivo;

  /**
   * Rubrica que deve ser realizado o desconto
   * @var Rubrica $oRubrica;
   */     
  private $oRubrica;

  /**
   * Instituição do Servidor
   * @var Instituicao $oInstituicao
   */
  private $oInstituicao;

  /**
   * Valor que deve ser descontado
   * @var Double $nValorDescontar
   */
  private $nValorDescontar;

  /**
   * Valor que foi descontado
   * @var Double $nValorDescontado
   */
  private $nValorDescontado;

  /**
   * pServiParcela que está sendo descontada
   * @var integer
   */
  private $iParcela;

  /**
   * @var Servidor
   */
  private $oServidor;

  /**
   * Total de parcelas que deve ser descontada.
   * @var integer
   */
   private $iTotalParcelas;


  public function __construct() {}

  public function setCodigo($iCodigo) {
    $this->iSequencial = $iCodigo;
  }

  public function setCodigoMovimento($iCodigoMovimento) {
    $this->iCodigoMovimento = $iCodigoMovimento;
  }

  public function setArquivoConsignado(ArquivoConsignado $oArquivoConsignado) {
    $this->oArquivoConsignado = $oArquivoConsignado;
  }

  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  public function setMotivo($iMotivo) {
    $this->iMotivo = $iMotivo;
  }

  public function setRubrica(Rubrica $oRubrica) {
    $this->oRubrica = $oRubrica;
  }

  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  public function setValorDescontar($nValorDescontar) {
    $this->nValorDescontar = $nValorDescontar;
  }

  public function setValorDescontado($nValorDescontado) {
    $this->nValorDescontado = $nValorDescontado;
  } 

  public function setParcela($iParcela) {
    $this->iParcela = $iParcela;
  }

  public function setTotalParcelas($iTotalParcelas) {
    $this->iTotalParcelas = $iTotalParcelas;
  }

  public function getCodigo() {
    return $this->iSequencial;
  }

  public function getCodigoMovimento() {
    return $this->iCodigoMovimento;
  }

  public function getArquivoConsignado() {
    return $this->oArquivoConsignado;
  }

  public function getMatricula() {
    return $this->iMatricula;
  }

  public function getNome() {
    return $this->sNome;
  }

  public function getMotivo() {
    return $this->iMotivo;
  }

  public function getRubrica() {
    return $this->oRubrica;
  }

  public function getInstituicao() {
    return $this->oInstituicao;
  }

  public function getValorDescontar() {
    return $this->nValorDescontar;
  }

  public function getValorDescontado() {
    return $this->nValorDescontado;
  }

  public function getParcela() {
    return $this->iParcela;
  }

  public function getTotalParcelas() {
    return $this->iTotalParcelas;
  }

  public function setServidor(Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  public function getServidor() {
    return $this->oServidor;
  }
}
