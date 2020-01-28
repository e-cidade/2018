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
 * Linha Itinerário Logradouro
 * @author Trucolo <trucolo@dbseller.com.br>
 * @package transporteescolar
 * @version $Revision: 1.14 $
 */

class LinhaItinerarioLogradouro {

  /**
   * Código sequencial
   * @var integer
   */
  protected $iCodigo;

  /**
   * Instancia de LogradouroBairro
   * @var LogradouroBairro
   */
  protected $oLogradouroBairro;

  /**
   * Ordem
   * @var integer
   */
  protected $iOrdem = null;

  /**
   * Instância de LinhaItinerario
   * @var LinhaItinerario
   */
  protected $oLinhaItinerario;

  /**
   * POntos de parada do logradouro
   * @var ItinerarioPontoParada
   */
  protected $aPontosDeParada  = array();
  
  
  /**
   * Método construtor
   * @param string $iCodigo
   * @throws ParameterException
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('Parâmetro $iCodigo deve ser um inteiro.');
      }

      $oDaoLinhaItinerarioLogradouro = db_utils::getDao('itinerariologradouro');

      $sSqlLinhaItinerarioLogradouro = $oDaoLinhaItinerarioLogradouro->sql_query_file($iCodigo);
      $rsLinhaItinerarioLogradouro   = $oDaoLinhaItinerarioLogradouro->sql_record($sSqlLinhaItinerarioLogradouro);

      if ($oDaoLinhaItinerarioLogradouro->numrows == 0) {

        $oVariaveis         = new stdClass();
        $sMensagem          = 'educacao.transporteescolar.LinhaItinerarioLogradouro.itinerariologradouro_não_cadastrado';
        $oVariaveis->codigo = $iCodigo;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }

      $oLinhaItinerarioLogradouro = db_utils::fieldsMemory($rsLinhaItinerarioLogradouro, 0);
      $this->iCodigo              = $oLinhaItinerarioLogradouro->tre10_sequencial;
      $this->setLinhaItinerario(new LinhaItinerario($oLinhaItinerarioLogradouro->tre10_linhatransporteitinerario));
      $this->setLogradouroBairro(new LogradouroBairro($oLinhaItinerarioLogradouro->tre10_cadenderbairrocadenderrua));
      $this->setOrdem($oLinhaItinerarioLogradouro->tre10_ordem);
    }

  }

  /**
   * Retorna o código sequencial
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna uma instancia de LogradouroBairro
   * @return LogradouroBairro
   */
  public function getLogradouroBairro() {
    return $this->oLogradouroBairro;
  }

  /**
   * Seta uma instancia de LogradouroBairro
   * @param LogradouroBairro $oLogradouroBairro
   */
  public function setLogradouroBairro(LogradouroBairro $oLogradouroBairro) {
    $this->oLogradouroBairro = $oLogradouroBairro;
  }

  /**
   * Define uma instância de LinhaItinerario
   * @param LinhaItinerario LinhaItinerario
   */
  public function setLinhaItinerario(LinhaItinerario $oLinhaItinerario) {

    if (is_object($oLinhaItinerario)) {
      $this->oLinhaItinerario = $oLinhaItinerario;
    }
  }

  /**
   * Retorna uma instância de LinhaItinerario
   * @return LinhaItinerario LinhaItinerario
   */
  public function getLinhaItinerario() {
    return $this->oLinhaItinerario;
  }

  /**
   * Define a ordem
   * @param int
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Retorna a ordem
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Salva ou altera os dados.
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    if ($this->getLinhaItinerario() != '' && trim($this->getLinhaItinerario()->getCodigo()) == '') {
      throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerarioLogradouro.linhaitinerarionaoinformado'));
    }

    if ($this->getLogradouroBairro() != '' && trim($this->getLogradouroBairro()->getCodigo()) == '') {
      throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerarioLogradouro.bairrologradouronaoinformado'));
    }

    $oDaoLinhaItinerarioLogradouro = new cl_itinerariologradouro();

    /**
     * Verificamos se ha vinculo para o logradouro no itinerario, do mesmo tipo
     */
    $sWhereVerificaExistencia  = "    tre10_linhatransporteitinerario = {$this->getLinhaItinerario()->getCodigo()} ";
    $sWhereVerificaExistencia .= "and tre10_cadenderbairrocadenderrua = {$this->getLogradouroBairro()->getCodigo()} ";
    $sWhereVerificaExistencia .= "and tre09_tipo = {$this->getLinhaItinerario()->getTipo()}";
    if ($this->getCodigo() != null) {
      $sWhereVerificaExistencia .= " and tre10_sequencial <> {$this->getCodigo()}";
    }
    $sSqlVerificaExistencia    = $oDaoLinhaItinerarioLogradouro->sql_query(
                                                                            null,
                                                                            "tre10_sequencial",
                                                                            null,
                                                                            $sWhereVerificaExistencia
                                                                          );
    $rsVerificaExistencia = $oDaoLinhaItinerarioLogradouro->sql_record($sSqlVerificaExistencia);

    if ($oDaoLinhaItinerarioLogradouro->numrows > 0) {
      throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerarioLogradouro.vinculo_logradouro_existente'));
    }

    /**
     * Buscamos a ultima ordem existente para o itinerario. Caso tenha sida setada a ordem, buscamos se esta ordem ja
     * esta cadastrada.
     */

    if ($this->getOrdem() == null) {

      $iOrdem                          = 1;
      $sWhereLinhaItinerarioLogradouro = "tre10_linhatransporteitinerario = {$this->getLinhaItinerario()->getCodigo()}";
    	$sSqlLinhaItinerarioLogradouro = $oDaoLinhaItinerarioLogradouro->sql_query_file(
      	                                                                               null,
       	                                                                              "tre10_ordem",
        	                                                                             "tre10_ordem desc",
          	                                                                           $sWhereLinhaItinerarioLogradouro
            	                                                                       );
	    $rslLinhaItinerarioLogradouro = $oDaoLinhaItinerarioLogradouro->sql_record($sSqlLinhaItinerarioLogradouro);

  	  if ($oDaoLinhaItinerarioLogradouro->numrows > 0) {

    	  $iOrdem = db_utils::fieldsMemory($rslLinhaItinerarioLogradouro, 0)->tre10_ordem;
      	$iOrdem = $iOrdem + 1;
  	  }
      $this->setOrdem($iOrdem);
    }
    $oDaoLinhaItinerarioLogradouro->tre10_linhatransporteitinerario = $this->getLinhaItinerario()->getCodigo();
    $oDaoLinhaItinerarioLogradouro->tre10_cadenderbairrocadenderrua = $this->getLogradouroBairro()->getCodigo();
    $oDaoLinhaItinerarioLogradouro->tre10_ordem                     = $this->getOrdem();

    if ($this->iCodigo == null) {

      $oDaoLinhaItinerarioLogradouro->incluir(null);
      $this->iCodigo = $oDaoLinhaItinerarioLogradouro->tre10_sequencial;
    } else {

      $oDaoLinhaItinerarioLogradouro->tre10_sequencial = $this->getCodigo();
      $oDaoLinhaItinerarioLogradouro->alterar($this->getCodigo());
    }

    if ($oDaoLinhaItinerarioLogradouro->erro_status == 0) {

      $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioLogradouro.erro_persistir_dados';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoLinhaItinerarioLogradouro->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
  }

  /**
   * Remover
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    /**
     * Caso exista ponto de parada do logradouro, vinculado a um itinerario da linha de transporte, mostramos a mensagem
     * de que a exclusao nao foi permitida
     */
    if (count($this->getPontosDeParada()) > 0) {

      $sMensagem = 'educacao.transporteescolar.LinhaItinerarioLogradouro.ponto_parada_vinculado';
      $oVariaveis                   = new stdClass();
      $oVariaveis->sLinhaTransporte = $this->getLinhaItinerario()->getLinhaTransporte()->getNome();
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }

    if ($this->getCodigo() != null) {

      $oDaoLinhaItinerarioLogradouro = db_utils::getDao('itinerariologradouro');
      $oDaoLinhaItinerarioLogradouro->excluir($this->getCodigo());

      if ($oDaoLinhaItinerarioLogradouro->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioLogradouro.erro_remover_dados';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoLinhaItinerarioLogradouro->erro_msg;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }
    }
  }

  /**
   * Retorna um array de objetos de pontos de parada vinculados ao logradouro
   * @return ItinerarioPontoParada[]
   */
  public function getPontosDeParada() {

    if (count($this->aPontosDeParada) == 0 && !empty($this->iCodigo)) {
      
      $oDaoLinhaTransportePontoParada    = new cl_linhatransportepontoparada();
      $sWhere                            = "tre11_itinerariologradouro = {$this->getCodigo()}";
      $sCamposLinhaTransportePontoParada = 'tre11_sequencial';
      $sSqlLinhaTransportePontoParada    = $oDaoLinhaTransportePontoParada->sql_query_file(
                                                                                       null,
                                                                                       $sCamposLinhaTransportePontoParada,
                                                                                       'tre11_ordem',
                                                                                       $sWhere
                                                                                     );
      $rsLinhaTransportePontoParada = $oDaoLinhaTransportePontoParada->sql_record($sSqlLinhaTransportePontoParada);
      $iTotalLinhas                 = $oDaoLinhaTransportePontoParada->numrows;
  
      $aPontosParada = array();
      for ($iIndice = 0; $iIndice < $iTotalLinhas; $iIndice++) {
  
        $iCodigoPontoParada      = db_utils::fieldsMemory($rsLinhaTransportePontoParada, $iIndice)->tre11_sequencial;
        $this->aPontosDeParada[] = new ItinerarioPontoParada($iCodigoPontoParada);
      }
    }
    return $this->aPontosDeParada;
  }

  /**
   * Vincula um novo ponto de parada ao logradouro
   * @param PontoParada $oPontoParada
   * @throws DBException
   */
  public function adicionarPontoDeParada(PontoParada $oPontoParada) {

    if ($oPontoParada instanceof PontoParada) {

      //Validação para não vincular caso já exista este vínculo.
      $oDaoLinhaTransportePontoParada = new cl_linhatransportepontoparada();
      $sWhere = "tre11_itinerariologradouro = {$this->getCodigo()} and tre11_pontoparada = {$oPontoParada->getCodigo()}";
      $sqlLinhaTransportePontoParada  = $oDaoLinhaTransportePontoParada->sql_query_file(null,
                                                                                        'tre11_ordem',
                                                                                        'tre11_ordem desc',
                                                                                        $sWhere);
      $rsLinhaTransportePontoParada   = $oDaoLinhaTransportePontoParada->sql_record($sqlLinhaTransportePontoParada);

      if ($oDaoLinhaTransportePontoParada->numrows > 0) {

        $sMensagem = 'educacao.transporteescolar.LinhaItinerarioLogradouro.erro_vincularpontoparada';
        throw new BusinessException(_M($sMensagem));
      }

      /**
       * Busca qual a ultima ordem cadastrada para itinerariologradouro
       */
      $iOrdem = 1;
      $sWhere = "tre11_itinerariologradouro = {$this->getCodigo()}";
      $sqlLinhaTransportePontoParada  = $oDaoLinhaTransportePontoParada->sql_query_file(null,
                                                                                        'tre11_ordem',
                                                                                        'tre11_ordem desc',
                                                                                         $sWhere);
      $rsLinhaTransportePontoParada   = $oDaoLinhaTransportePontoParada->sql_record($sqlLinhaTransportePontoParada);

      if ($oDaoLinhaTransportePontoParada->numrows > 0) {

        $iOrdem = db_utils::fieldsMemory($rsLinhaTransportePontoParada, 0)->tre11_ordem;
        $iOrdem = $iOrdem + 1;
      }

      $oDaoLinhaTransportePontoParada->tre11_itinerariologradouro = $this->getCodigo();
      $oDaoLinhaTransportePontoParada->tre11_pontoparada          = $oPontoParada->getCodigo();
      $oDaoLinhaTransportePontoParada->tre11_ordem                = $iOrdem;
      $oDaoLinhaTransportePontoParada->incluir(null);

      if ($oDaoLinhaTransportePontoParada->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioLogradouro.erro_adicionarpontoparada';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoLinhaTransportePontoParada->erro_msg;
        throw new DBException(_M($sMensagem, $oVariaveis));
      }
      return true;
    }
  }

  /**
   * Método para remover vínculo de ponto de parada ao logradouro
   * @param PontoParada $oPontoParada
   * @throws DBException
   */
  public function removerPontoParada(PontoParada $oPontoParada) {

    if ($oPontoParada instanceof PontoParada) {

      $oDaoLinhaTransportePontoParada    = new cl_linhatransportepontoparada();
      $sWhereLinhaTransportePontoParada  = "    tre11_pontoparada = {$oPontoParada->getCodigo()} ";
      $sWhereLinhaTransportePontoParada .= "and tre11_itinerariologradouro = {$this->getCodigo()} ";
      $oDaoLinhaTransportePontoParada->excluir(null, $sWhereLinhaTransportePontoParada);

      if ($oDaoLinhaTransportePontoParada->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.LinhaItinerarioLogradouro.erro_removerpontoparada';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoLinhaTransportePontoParada->erro_msg;
        throw new DBException(_M($sMensagem, $oVariaveis));
      }
    }
    return true;
  }

  /**
   * Retorna um array de objetos de PontoParada
   */
  public function getPontosParadaPorLogradouro() {

    $oDaoLinhaTransportePontoParada    = new cl_pontoparada();
    $sWhereLinhaTransportePontoParada  = "tre04_cadenderbairrocadenderrua = {$this->getLogradouroBairro()->getCodigo()}";
    $sCamposLinhaTransportePontoParada = "distinct tre04_sequencial, tre04_nome";
    $sSqlLinhaTransportePontoParada    = $oDaoLinhaTransportePontoParada->sql_query(null,
                                                                                    $sCamposLinhaTransportePontoParada,
                                                                                    'tre04_sequencial',
                                                                                     $sWhereLinhaTransportePontoParada);
    $rsLinhaTransportePontoParada = $oDaoLinhaTransportePontoParada->sql_record($sSqlLinhaTransportePontoParada);

    $aPontosParada = array();
    for ($iIndice = 0; $iIndice < $oDaoLinhaTransportePontoParada->numrows; $iIndice++) {

      $oPontoParada    = db_utils::fieldsMemory($rsLinhaTransportePontoParada, $iIndice, false, false, true);
      $aPontosParada[] = $oPontoParada;
    }
    return $aPontosParada;
  }
}
?>