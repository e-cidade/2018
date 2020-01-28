<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Linha Itiner�rio Hor�rio
 * @author Trucolo <trucolo@dbseller.com.br>
 * @package transporteescolar
 * @version $Revision: 1.7 $
 */

class LinhaItinerarioHorario {

  /**
   * C�digo sequencial
   * @var integer
   */
  protected $iCodigo;

  /**
   * Hora Sa�da
   * @var string
   */
  protected $sHoraSaida = '';

  /**
   * Hora Chegada
   * @var string
   */
  protected $sHoraChegada = '';

  /**
   * Inst�ncia de LinhaItinerario
   * @var LinhaItinerario LinhaItinerario
   */
  protected $oLinhaItinerario;

  protected $aVeiculosTransporte = array();

  /**
   * Tipo do itinerario
   * 1 - Ida
   * 2 - Retorno
   * @var integer
   */
  protected $iTipoItinerario;

  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('Par�metro $iCodigo deve ser um inteiro.');
      }

      $oDaoLinhaItinerarioHorario = new cl_linhatransportehorario();
      $sSqlLinhaItinerarioHorario = $oDaoLinhaItinerarioHorario->sql_query($iCodigo);
      $rsLinhaItinerarioHorario   = $oDaoLinhaItinerarioHorario->sql_record($sSqlLinhaItinerarioHorario);

      if ($oDaoLinhaItinerarioHorario->numrows == 0) {

        $oVariaveis         = new stdClass();
        $sMensagem          = 'educacao.transporteescolar.LinhaItinerarioHorario.linhaitinerariohorario_nao_cadastrado';
        $oVariaveis->codigo = $iCodigo;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }

      $oLinhaItinerarioHorario = db_utils::fieldsMemory($rsLinhaItinerarioHorario, 0);
      $this->iCodigo           = $oLinhaItinerarioHorario->tre07_sequencial;
      $this->setLinhaItinerario(new LinhaItinerario($oLinhaItinerarioHorario->tre07_linhatransporteitinerario));
      $this->setHoraSaida($oLinhaItinerarioHorario->tre07_horasaida);
      $this->setHoraChegada($oLinhaItinerarioHorario->tre07_horachegada);
      $this->setTipoItinerario($oLinhaItinerarioHorario->tre09_tipo);
    }
  }

  /**
   * Retorna o C�digo sequencial
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a hora de sa�da
   * @return string
   */
  public function getHoraSaida() {
    return $this->sHoraSaida;
  }

  /**
   * Define a hora de sa�da
   * @param string
   */
  public function setHoraSaida($sHoraSaida) {
    $this->sHoraSaida = $sHoraSaida;
  }

  /**
   * Retorna a hora de chegada
   * @return string
   */
  public function getHoraChegada() {
    return $this->sHoraChegada;
  }

  /**
   * Define a hora de chegada
   * @param string
   */
  public function setHoraChegada($sHoraChegada) {
    $this->sHoraChegada = $sHoraChegada;
  }

  /**
   * Define uma inst�ncia de LinhaItinerario
   * @param LinhaItinerario LinhaItinerario
   */
  public function setLinhaItinerario(LinhaItinerario $oLinhaItinerario) {

    if (is_object($oLinhaItinerario)) {
      $this->oLinhaItinerario = $oLinhaItinerario;
    }
  }

  /**
   * Retorna uma inst�ncia de LinhaItinerario
   * @return LinhaItinerario LinhaItinerario
   */
  public function getLinhaItinerario() {
    return $this->oLinhaItinerario;
  }

  /**
   * Retorno o tipo de itinerario
   * @return integer
   */
  public function getTipoItinerario() {
    return $this->iTipoItinerario;
  }

  /**
   * Seta o tipo de itinerario
   * @param integer $iTipoItinerario
   */
  public function setTipoItinerario($iTipoItinerario) {
    $this->iTipoItinerario = $iTipoItinerario;
  }

  /**
   * Salva e Altera
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M('educacao.transporteescolar.LinhaItinerarioHorario.sem_transacao'));
    }

    if (trim($this->sHoraSaida) == '') {
      throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerarioHorario.hora_nao_informada'));
    }

    if ($this->oLinhaItinerario == null) {
      throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerarioHorario.linhatransporteitinerario_nao_informada'));
    }

    $oDaoLinhaTransporteHorario = new cl_linhatransportehorario();

    /**
     * Buscamos a ultima hora de chegada para o itinerario, comparando os valores para que nao seja cadastrada uma hora
     * de partida menor que a ultima chegada
     */
    $sWhereLinhaTransporteHorario   = "tre07_linhatransporteitinerario = {$this->getLinhaItinerario()->getCodigo()} ";
    $sWhereLinhaTransporteHorario  .= "order by 1 desc limit 1";
    $sCamposLinhaTransporteHorario  = "tre07_horachegada";
    $sSqlLinhaTransporteHorario     = $oDaoLinhaTransporteHorario->sql_query(
  	                                                                          null,
                                                                              $sCamposLinhaTransporteHorario,
                                                                              null,
                                                                              $sWhereLinhaTransporteHorario
                                                                            );
    $rsLinhaTransporteHorario = $oDaoLinhaTransporteHorario->sql_record($sSqlLinhaTransporteHorario);

    if ($oDaoLinhaTransporteHorario->numrows > 0) {

      $sHoraChegada = db_utils::fieldsMemory($rsLinhaTransporteHorario, 0)->tre07_horachegada;

      if ($this->getHoraSaida() <= $sHoraChegada) {
        throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerarioHorario.hora_partida_menor_ultima_chegada'));
      }
    }

    $oDaoLinhaTransporteHorario->tre07_linhatransporteitinerario = $this->getLinhaItinerario()->getCodigo();
    $oDaoLinhaTransporteHorario->tre07_horasaida                 = $this->getHoraSaida();
    $oDaoLinhaTransporteHorario->tre07_horachegada               = $this->getHoraChegada();

    if ($this->iCodigo == '') {

      $oDaoLinhaTransporteHorario->incluir(null);
      $this->iCodigo = $oDaoLinhaTransporteHorario->tre07_sequencial;
    } else {

      $oDaoLinhaTransporteHorario->tre07_sequencial = $this->getCodigo();
      $oDaoLinhaTransporteHorario->alterar($this->getCodigo());
    }

    if ($oDaoLinhaTransporteHorario->erro_status == 0) {

      $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioHorario.erro_persitir_dados_itinerariohorario';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoLinhaTransporteHorario->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }

  }

  /**
   * Remove
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M('educacao.transporteescolar.LinhaItinerarioHorario.sem_transacao'));
    }

    /**
     * Verificamos se existem veiculos de transporte municipal vinculados ao horario, nao permitindo a exclusao
     */
    if (count($this->getTransportes()) > 0) {
      
      $sMensagem = 'educacao.transporteescolar.LinhaItinerarioHorario.transporte_vinculado_horario';
      throw new BusinessException(_M($sMensagem));
    }
    
    $oDaoLinhaTransporteHorario = db_utils::getDao('linhatransportehorario');
    $oDaoLinhaTransporteHorario->excluir($this->getCodigo());

    if ($oDaoLinhaTransporteHorario->erro_status == 0) {

      $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioHorario.erro_remover_dados_itinerariohorario';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoLinhaTransporteHorario->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
  }

  /**
   * Adiciona ao array uma instancia de VeiculoTransporte, caso nao exista
   * @param VeiculoTransporte $oVeiculoTransporte
   */
  public function adicionarTransporte(VeiculoTransporte $oVeiculoTransporte) {

    if (!in_array($oVeiculoTransporte, $this->aVeiculosTransporte)) {
      $this->aVeiculosTransporte[] = $oVeiculoTransporte;
    }
  }

  /**
   * Remove do array uma instancia de VeiculoTransporte, caso exista
   * @param VeiculoTransporte $oVeiculoTransporte
   */
  public function removerTransporte(VeiculoTransporte $oVeiculoTransporte) {

    if (in_array($oVeiculoTransporte, $this->aVeiculosTransporte)) {

      $iIndice = array_search($oVeiculoTransporte, $this->aVeiculosTransporte);
      unset($this->aVeiculosTransporte[$iIndice]);
    }
  }

  /**
   * Busca todos ve�culos vinculados ao hor�rio.
   * @return array VeiculoTransporte
   */
  public function getTransportes() {

    $oDaoLinhaTransporteHorarioVeiculo = new cl_linhatransportehorarioveiculo();
    $sWhere                            = "tre08_linhatransportehorario = {$this->getCodigo()}";
    $sSql                              = $oDaoLinhaTransporteHorarioVeiculo->sql_query(null,
                                                                                       'tre01_sequencial',
                                                                                       'tre01_sequencial',
                                                                                       $sWhere);
    $rsLinhaTransporteHorarioVeiculo   = $oDaoLinhaTransporteHorarioVeiculo->sql_record($sSql);

    for ($iIndice = 0; $iIndice < $oDaoLinhaTransporteHorarioVeiculo->numrows; $iIndice++) {

      $iVeiculoTransporteMunicipal = db_utils::fieldsMemory($rsLinhaTransporteHorarioVeiculo, $iIndice)->tre01_sequencial;
      $this->adicionarTransporte(new VeiculoTransporte($iVeiculoTransporteMunicipal));
    }
    return $this->aVeiculosTransporte;
  }

  /**
   * Salva o v�nculo entre ve�culos e hor�rios
   */
  public function salvarVeiculo() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M('educacao.transporteescolar.LinhaItinerarioHorario.sem_transacao'));
    }
    
    $oDaoLinhaTransporteHorarioVeiculo   = new cl_linhatransportehorarioveiculo();
    $sWhereLinhaTransporteHorarioVeiculo = "tre08_linhatransportehorario = {$this->getCodigo()}";
    $oDaoLinhaTransporteHorarioVeiculo->excluir(null, $sWhereLinhaTransporteHorarioVeiculo);
    
    if ($oDaoLinhaTransporteHorarioVeiculo->erro_status == 0) {
      
      $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioHorario.erro_excluir_vinculo_veiculo';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoLinhaTransporteHorarioVeiculo->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }

    foreach ($this->getTransportes() as $oVeiculoTransporte) {

      $oDaoLinhaTransporteHorarioVeiculo->tre08_linhatransportehorario     = $this->getCodigo();
      $oDaoLinhaTransporteHorarioVeiculo->tre08_veiculotransportemunicipal = $oVeiculoTransporte->getCodigo();
      $oDaoLinhaTransporteHorarioVeiculo->incluir(null);
      
      if ($oDaoLinhaTransporteHorarioVeiculo->erro_status == 0) {
      
        $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioHorario.erro_incluir_vinculo_veiculo';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoLinhaTransporteHorarioVeiculo->erro_msg;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }
    }
  }
}
?>