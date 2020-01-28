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
 * LinhaTransporte
 * @package transporteescolar
 * @author Trucolo <trucolo@dbseller.com.br>
 * @version $Revision: 1.6 $
 */

class LinhaTransporte {

  /**
   * Codigo sequencial da Linha de Transporte
   * @var integer
   */
  protected $iCodigo;

  /**
   * Nome da Linha de Transporte
   * @var string
   */
  protected $sNome;

  /**
   * Abreviatura da Linha de Transporte
   * @var string
   */
  protected $sAbreviatura;

  /**
   * Array com inst�ncias de itiner�rios
   * @var array
   */
  protected $aItinerarios = array();

  /**
   * M�todo construtor da classe.
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('Par�metro $iCodigo deve ser um inteiro.');
      }

      $oDaoLinhaTransporte = db_utils::getDao('linhatransporte');
      $sSqlLinhaTransporte = $oDaoLinhaTransporte->sql_query_file($iCodigo);
      $rsLinhaTransporte   = $oDaoLinhaTransporte->sql_record($sSqlLinhaTransporte);

      if ($oDaoLinhaTransporte->numrows > 0) {

        $oLinhaTransporte   = db_utils::fieldsMemory($rsLinhaTransporte, 0);
        $this->iCodigo      = $oLinhaTransporte->tre06_sequencial;
        $this->setNome($oLinhaTransporte->tre06_nome);
        $this->setAbreviatura($oLinhaTransporte->tre06_abreviatura);

      }
    }
  }

  /**
   * Retorna o c�digo sequencial da Linha de Transporte
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o nome da Linha de Transporte
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Retorna a abreviatura da Linha de Transporte
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Define o nome da Linha de Transporte
   * @param string $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Define a abreviatura da Linha de Transporte
   * @param string $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Retorna o itiner�rio da Linha de Transporte
   * @return LinhaItinerario[]
   */
  public function getItinerarios() {

    if (count($this->aItinerarios) == 0 && $this->getCodigo() != '') {

      $oDaoLinhaItinerario   = db_utils::getDao('linhatransporteitinerario');
      $sWhereLinhaItinerario = "tre09_linhatransporte = {$this->getCodigo()}";
      $sSqlLinhaItinerario   = $oDaoLinhaItinerario->sql_query_file (null,
                                                                     "tre09_sequencial",
                                                                     "tre09_sequencial",
                                                                     $sWhereLinhaItinerario);
      $rsLinhaItinerario     = $oDaoLinhaItinerario->sql_record($sSqlLinhaItinerario);

      $iQuantidadeLinhaItinerario = $oDaoLinhaItinerario->numrows;

      for ($iIndice = 0; $iIndice < $iQuantidadeLinhaItinerario; $iIndice++) {

        $iLinhaItinerario     = db_utils::fieldsMemory($rsLinhaItinerario, $iIndice)->tre09_sequencial;
        $oLinhaItinerario     = new LinhaItinerario($iLinhaItinerario);
        $this->aItinerarios[] = $oLinhaItinerario;
      }
    }
    return $this->aItinerarios;
  }

  /**
   * Salva ou altera a Linha de Transporte
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('N�o existe transa��o com o banco de dados.');
    }

    if (trim($this->sNome) == '') {

      $sMensagem = 'educacao.transporteescolar.LinhaTransporte.nome_linhatransporte_nao_informado';
      throw new BusinessException(_M($sMensagem));
    }

    $oDaoLinhaTransporte = new cl_linhatransporte();
    
    /**
     * Verificamos se ha uma linha de transporte cadastrado com o mesmo nome informado
     */
    $sWhereLinhaTransporte = "tre06_nome = trim('{$this->getNome()}')";
    $sSqlLinhaTransporte   = $oDaoLinhaTransporte->sql_query_file(null, "tre06_sequencial", null, $sWhereLinhaTransporte);
    $rsLinhaTransporte     = $oDaoLinhaTransporte->sql_record($sSqlLinhaTransporte);
    
    if ($oDaoLinhaTransporte->numrows > 0) {
      
      $sMensagem = 'educacao.transporteescolar.LinhaTransporte.nome_existente';
      throw new BusinessException(_M($sMensagem));
    }
    
    $oDaoLinhaTransporte->tre06_nome        = $this->getNome();
    $oDaoLinhaTransporte->tre06_abreviatura = $this->getAbreviatura();
    
    if (empty($this->iCodigo)) {

      $oDaoLinhaTransporte->incluir(null);
      $this->iCodigo = $oDaoLinhaTransporte->tre06_sequencial;
    } else {

      $oDaoLinhaTransporte->tre06_sequencial = $this->getCodigo();
      $oDaoLinhaTransporte->alterar($this->getCodigo());
    }

    if ($oDaoLinhaTransporte->erro_status == 0) {

      $oVariaveis            = new stdClass();
      $oVariaveis->sMensagem = $oDaoLinhaTransporte->erro_msg;
      throw new BusinessException(_M('educacao.transporteescolar.LinhaTransporte.erro_salvar_linhatransporte',
                                     $oVariaveis));
    }
  }
  
  /**
   * Remove uma linha de transporte.
   * 1� - Percorre os itinerarios da linha de transporte, verificando se existem vinculos de logradouros ou horarios,
   *      com o itinerario da linha, nao permitindo a exclusao
   * 2� - Exclui os registros de linhatransporteitinerario
   * 3� - Exclui o registro de linhatransporte
   * @throws BusinessException
   */
  public function remover() {
    
    foreach ($this->getItinerarios() as $oLinhaItinerario) {
      
      if (count($oLinhaItinerario->getLogradouros()) > 0) {
        
        $sMensagem = 'educacao.transporteescolar.LinhaTransporte.logradouro_vinculado';
        throw new BusinessException(_M($sMensagem));
      }
      
      if (count($oLinhaItinerario->getHorarios()) > 0) {
        
        $sMensagem = 'educacao.transporteescolar.LinhaTransporte.horario_vinculado';
        throw new BusinessException(_M($sMensagem));
      }
    }
    
    $oDaoLinhaTransporteItinerario   = new cl_linhatransporteitinerario();
    $sWhereLinhaTransporteItinerario = "tre09_linhatransporte = {$this->getCodigo()}";
    $oDaoLinhaTransporteItinerario->excluir(null, $sWhereLinhaTransporteItinerario);
    
    if ($oDaoLinhaTransporteItinerario->erro_status == 0) {
      
      $oVariaveis        = new stdClass();
      $oVariaveis->sErro = $oDaoLinhaTransporteItinerario->erro_msg;
      $sMensagem         = 'educacao.transporteescolar.LinhaTransporte.erro_remover_itinerario';
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
    
    $oDaoLinhaTransporte = new cl_linhatransporte();
    $oDaoLinhaTransporte->excluir($this->getCodigo());
    
    if ($oDaoLinhaTransporte->erro_status == 0) {
    
      $oVariaveis        = new stdClass();
      $oVariaveis->sErro = $oDaoLinhaTransporte->erro_msg;
      $sMensagem         = 'educacao.transporteescolar.LinhaTransporte.erro_remover_linha_transporte';
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
  }
}
?>