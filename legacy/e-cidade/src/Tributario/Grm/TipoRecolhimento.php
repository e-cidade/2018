<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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
namespace Ecidade\Tributario\Grm;

use ECidade\Configuracao\Workflow\Workflow;

/**
 * Class TipoRecolhimento
 * @package Ecidade\Tributario\Grm
 */
class TipoRecolhimento {

  /**
   * Emissão apenas para pessoas físicas
   */
  const TIPO_EMISSAO_FISICA = 1;

  /**
   * Emissão do tipo apenas para pessoas Juridicas
   */
  const TIPO_EMISSAO_JURIDICA = 2;

  /**
   * Permite a emissão por Ambas pessoas
   */
  const TIPO_EMISSAO_AMBOS = 3;
  
  /**
   * Código do tipo
   * @var integer
   */
  protected $codigo;

  /**
   * Codigo do recolhimento
   * @var string
   */
  protected $codigoRecolhimento;

  /**
   * Nome do tipo
   * @var string
   */
  protected $nome;

  /**
   * Instruções para a emissão do recibo
   * @var string
   */
  protected $instrucoes;

  /**
   * Obriga informar numero de Referencia
   * @var bool 
   */
  protected $obrigaNumeroReferencia = false;


  /**
   * Tipo de pessoal que pode emitir o recolhimento
   * @var integer
   */
  protected $tipoPessoa = 2;

  /**
   * Titulo reduzido do Recolhimento
   * @var string
   */
  protected $tituloReduzido;

  /**
   * Especie de ingresso do Titulo
   * @var string
   */
  protected $especieIngresso = 1;
  
  /**
   * Informa desconto
   * @var bool
   */
  protected $informaDesconto = false;


  /**
   * Infroma multa
   * @var boolean
   */
   protected $informaMulta = false;

  /**
   * Infroma Juros
   * @var boolean
   */
  protected $informaJuros = false;

  /**
   * Infroma outros acrescimos
   * @var boolean
   */
  protected $informaOutrosAcrescimos = false;

  /**
   * Informa outras deduções
   * @var boolean
   */
  protected $informaOutrasDeducoes = false;

  /**
   * @var Workflow
   */
  protected $workflow;
  
  protected $plainData = null;

  /**
   * @var \DBAttDinamico
   */
  protected $AtributoDinamico;
  
  /**
   * TipoRecolhimento constructor.
   */
  public function __construct() {
    
  }

  
  /**
   * @return int
   */
  public function getCodigo() {

    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {

    $this->codigo = $codigo;
  }

  /**
   * @return string
   */
  public function getNome() {

    return $this->nome;
  }

  /**
   * @param string $nome
   */
  public function setNome($nome) {

    $this->nome = $nome;
  }

  /**
   * @return bool
   */
  public function obrigaNumeroReferencia() {

    return $this->obrigaNumeroReferencia;
  }

  /**
   * @param bool $obrigaNumeroReferencia
   */
  public function setObrigaNumeroReferencia($obrigaNumeroReferencia) {
    $this->obrigaNumeroReferencia = $obrigaNumeroReferencia;
  }

  /**
   * @return integer
   */
  public function getTipoPessoa() {
    return $this->tipoPessoa;
  }

  /**
   * @param bool $tipoPessoal
   */
  public function setTipoPessoa($tipoPessoal) {
    $this->tipoPessoa = $tipoPessoal;
  }

  /**
   * @return string
   */
  public function getCodigoRecolhimento() {
    return $this->codigoRecolhimento;
  }

  /**
   * @param string $codigoRecolhimento
   */
  public function setCodigoRecolhimento($codigoRecolhimento) {
    $this->codigoRecolhimento = $codigoRecolhimento;
  }

  /**
   * @return string
   */
  public function getTituloReduzido() {

    return $this->tituloReduzido;
  }

  /**
   * @param string $tituloReduzido
   */
  public function setTituloReduzido($tituloReduzido) {

    $this->tituloReduzido = $tituloReduzido;
  }

  /**
   * @return string
   */
  public function getEspecieIngresso() {

    return $this->especieIngresso;
  }

  /**
   * @param string $especieIngresso
   */
  public function setEspecieIngresso($especieIngresso) {

    $this->especieIngresso = $especieIngresso;
  }

  /**
   * @return bool
   */
  public function informaDesconto() {

    return $this->informaDesconto;
  }

  /**
   * @param bool $informaDesconto
   */
  public function setInformaDesconto($informaDesconto) {

    $this->informaDesconto = $informaDesconto;
  }

  /**
   * @return bool
   */
  public function informaMulta() {

    return $this->informaMulta;
  }

  /**
   * @param bool $informaMulta
   */
  public function setInformaMulta($informaMulta) {
    $this->informaMulta = $informaMulta;
  }

  /**
   * @return bool
   */
  public function informaJuros() {
    return $this->informaJuros;
  }

  /**
   * @param bool $informaJuros
   */
  public function setInformaJuros($informaJuros) {
    $this->informaJuros = $informaJuros;
  }

  /**
   * @return bool
   */
  public function informaOutrosAcrescimos() {
    return $this->informaOutrosAcrescimos;
  }

  /**
   * @param bool $informaOutrosAcrescimos
   */
  public function setInformaOutrosAcrescimos($informaOutrosAcrescimos) {
    $this->informaOutrosAcrescimos = $informaOutrosAcrescimos;
  }
  
  /**
   * @return \ECidade\Configuracao\Workflow\Workflow
   */
  public function getWorkflow() {
    
    if (!empty($this->plainData) && empty($this->workflow)) {
      
      if ($this->plainData->k172_workflow != '') {
       
        $oWorkFlowRepository = new \ECidade\Configuracao\Workflow\Repository\Workflow();
        $this->workflow = $oWorkFlowRepository->getById($this->plainData->k172_workflow);
      }
    }
    return $this->workflow;
  }
  
  /**
   * @param \ECidade\Configuracao\Workflow\Workflow $workflow
   */
  public function setWorkflow($workflow) {  
    $this->workflow = $workflow;
  }

  /**
   * Seta os dados planos da classe. Utilizado para lazy loading das dependencias
   * @param \stdClass $data
   */
  public function setData(\stdClass $data) {
    $this->plainData = $data; 
  }

  /**
   * @return string
   */
  public function getInstrucoes() {

    return $this->instrucoes;
  }

  /**
   * @param string $instrucoes
   */
  public function setInstrucoes($instrucoes) {
    $this->instrucoes = $instrucoes;
  }

  /**
   * Define se o tipo de reclhimento informa outras deduções
   * @return bool
   */
  public function informaOutrasDeducoes() {
    return $this->informaOutrasDeducoes;
  }

  /**
   * @param bool $informaOutrasDeducoes
   */
  public function setInformaOutrasDeducoes($informaOutrasDeducoes) {
    $this->informaOutrasDeducoes = $informaOutrasDeducoes;
  }

  /**
   * REtorna a coleção de Atributos Dinamicos
   * @return \DBAttDinamico
   */
  public function getAtributoDinamico() {
    return $this->AtributoDinamico;
  }

  /**
   * @param \DBAttDinamico $AtributoDinamico
   */
  public function setAtributoDinamico($AtributoDinamico) {
    $this->AtributoDinamico = $AtributoDinamico;
  }
  
  
  
}