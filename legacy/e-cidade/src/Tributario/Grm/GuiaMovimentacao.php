<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 08/03/17
 * Time: 15:23
 */

namespace ECidade\Tributario\Grm;


use ECidade\Configuracao\Workflow\Atividade;

class GuiaMovimentacao {

  /**
   * @var Recibo
   */
  protected $guia;

  /**
   * @var \processoProtocolo
   */
  protected $processo;

  /**
   * @var \UsuarioSistema;
   */
  protected $usuario;

  /**
   * @var \DBDate
   */
  protected $data;

  /**
   * @var Atividade
   *
   */
  protected $atividade;

  /**
   * @var bool
   */
  protected $concluido = false;

  /**
   * Codigo da Atividade
   * @var integer
   */
  protected $codigo;

  /**
   * @var null
   */
  protected $observacao = null;

  /**
   * Código do grupo de valores dinãmicos
   * @var integer
   */
  protected $grupoAtributos; 
  

  /**
   * @return \ECidade\Tributario\Grm\Recibo
   */
  public function getGuia() {

    return $this->guia;
  }

  /**
   * @param \ECidade\Tributario\Grm\Recibo $guia
   */
  public function setGuia($guia) {

    $this->guia = $guia;
  }

  /**
   * @return \processoProtocolo
   */
  public function getProcesso() {

    return $this->processo;
  }

  /**
   * @param \processoProtocolo $processo
   */
  public function setProcesso($processo) {

    $this->processo = $processo;
  }

  /**
   * @return \UsuarioSistema
   */
  public function getUsuario() {

    return $this->usuario;
  }

  /**
   * @param \UsuarioSistema $usuario
   */
  public function setUsuario($usuario) {

    $this->usuario = $usuario;
  }

  /**
   * @return \DBDate
   */
  public function getData() {

    return $this->data;
  }

  /**
   * @param \DBDate $data
   */
  public function setData($data) {

    $this->data = $data;
  }

  /**
   * @return \ECidade\Configuracao\Workflow\Atividade
   */
  public function getAtividade() {

    return $this->atividade;
  }

  /**
   * @param \ECidade\Configuracao\Workflow\Atividade $atividade
   */
  public function setAtividade($atividade) {

    $this->atividade = $atividade;
  }

  /**
   * @return bool
   */
  public function isConcluido() {

    return $this->concluido;
  }

  /**
   * @param bool $concluido
   */
  public function setConcluido($concluido) {

    $this->concluido = $concluido;
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
   * @return null
   */
  public function getObservacao() {

    return $this->observacao;
  }

  /**
   * @param null $observacao
   */
  public function setObservacao($observacao) {
    $this->observacao = $observacao;
  }

  /**
   * @return int
   */
  public function getGrupoAtributos() {

    return $this->grupoAtributos;
  }

  /**
   * @param int $grupoAtributos
   */
  public function setGrupoAtributos($grupoAtributos) {

    $this->grupoAtributos = $grupoAtributos;
  }
  
  

  /**
   * Conclui a atividade ou o workflow
   */
  private function concluirAtividade() {

    $oDepartamento = $this->atividade->getDepartamento();
    if (!$this->processo->getProximoDeptoAndamentoPadrao()) {

      $this->processo->arquivar($this->observacao, $this->usuario->getCodigo(), $oDepartamento->getCodigo());
      return true;
    }
    $iCodigoTransferencia = $this->processo->transferirPorAndamentoPadrao($this->usuario->getCodigo(), $oDepartamento->getCodigo());
    $iProximoDepto        = $this->processo->getProximoDeptoAndamentoPadrao();
    $this->processo->receber($iCodigoTransferencia, $iProximoDepto, $this->usuario->getCodigo(), $this->observacao);

    $oDaoAtividadeTransferencia = new \cl_proctransferworkflowativexec();
    $oDaoAtividadeTransferencia->p46_proctransfer = $iCodigoTransferencia;
    $oDaoAtividadeTransferencia->p46_workflowativexec = $this->codigo;
    $oDaoAtividadeTransferencia->incluir(null);

    if ($oDaoAtividadeTransferencia->erro_status == 0) {
      throw new \Exception($oDaoAtividadeTransferencia->erro_msg);
    }
  }

  /**
   * Realiza a movimentação da guia
   * @throws \BusinessException
   */
  public function movimentar() {
    
    
    $oDaoWorkflowAtividade                     = new \cl_workflowativexec();
    $oDaoWorkflowAtividade->db113_dtexecucao   = $this->data->getDate();
    $oDaoWorkflowAtividade->db113_id_usuario   = $this->usuario->getCodigo();
    $oDaoWorkflowAtividade->db113_obs          = $this->observacao;
    $oDaoWorkflowAtividade->db113_workflowativ = $this->atividade->getCodigo();
    $oDaoWorkflowAtividade->db113_concluido    = $this->concluido ? 'true' : 'false';
    $oDaoWorkflowAtividade->incluir(null);
    if ($oDaoWorkflowAtividade->erro_status == 0) {
      throw new \BusinessException('Erro ao salvar dados da atividade');
    }
    $this->codigo = $oDaoWorkflowAtividade->db113_sequencial;
    
    if (!empty($this->grupoAtributos)) {
      
      
      $oDaoWorfflowAtributos = new \cl_workflowativexecucaoatributovalor();
      $oDaoWorfflowAtributos->db111_workflowativexec         = $this->codigo;
      $oDaoWorfflowAtributos->db111_cadattdinamicovalorgrupo = $this->grupoAtributos;
      $oDaoWorfflowAtributos->incluir(null);
      if ($oDaoWorfflowAtributos->erro_status== 0) {
        throw new \BusinessException('Erro ao salvar dados de atributos da atividade.');
      }
      $this->observacao .= "\n".$this->criarObservacoesDosAtributos();
    }

    if (!$this->isConcluido()) {

      $oDepartamento = $this->atividade->getDepartamento();
      $this->processo->adicionarDespachoDepartamento($this->observacao, $this->getUsuario()->getCodigo(), $oDepartamento);
    } else {
      $this->concluirAtividade();
    }
  }
  
  protected function criarObservacoesDosAtributos() {
    
    $oDaoWorkflowAtividadeAtributo = new \cl_workflowativexecucaoatributovalor();
    $campos        = "db109_descricao, db110_valor, db109_tipo";
    $sSqlAtributos = $oDaoWorkflowAtividadeAtributo->sql_query_atributos(null, $campos, '', 'db111_workflowativexec='.$this->getCodigo());
    $rsAtributos   = db_query($sSqlAtributos);
    if (!$rsAtributos) {
      throw new \DBException('Erro ao pesquisar atributos');
    }
    
    $observacoes = \db_utils::makeCollectionFromRecord($rsAtributos, function($dados) {      
      
      switch ($dados->db109_tipo) {
        
        case '4':
          $valor = db_formatar($dados->db110_valor, 'f');
          break;
        case '5':
          $valor = $dados->db110_valor == 't' ? 'Sim':'Não';
        break;
         
        default:
         $valor = $dados->db110_valor;
         break;
          
      }
      $observacao = "{$dados->db109_descricao}: $valor"; 
        
      return $observacao;
    });
    
    return implode("\n", $observacoes);
  }
}
