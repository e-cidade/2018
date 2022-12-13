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
 * Itinerário Ponto Parada
 * @author Trucolo <trucolo@dbseller.com.br>
 * @package transporteescolar
 * @version $Revision: 1.8 $
 */

class ItinerarioPontoParada {

  /**
   * Código sequencial
   * @var integer
   */
  protected $iCodigo = null;

  /**
   * Instância de ItinerarioLogradouro
   * @var object ItinerarioLogradouro
   */
  protected $oLinhaItinerarioLogradouro;

  /**
   * Instância de PontoParada
   * @var object PontoParada
   */
  protected $oPontoParada;

  /**
   * Ordem
   * @var integer
   */
  protected $iOrdem;
  
  
  /**
   * Alunos que são pegos no pontos de parada
   * @var Aluno
   */
  protected $aVinculoAlunos = array();
  
  /**
   * propriedade para controle do lazy loading dos alunos
   * @var boolean
   */
  protected $lAlunosCarregados = false;

  /**
   * Instancia um ponto de parada no intinerario
   * Caso seja informado um codigo valido, a classe tera seus dados conforme o cadastro do ponto de parada
   * @param  integer $iCodigo
   * @throws BusinessException
   * @throws ParameterException
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('parametro $iCodigo deve ser integer');
      }

      $oDaoItinerarioPontoParada = new cl_linhatransportepontoparada();
      $sSqlItinerarioPontoParada = $oDaoItinerarioPontoParada->sql_query_file($iCodigo);
      $rsItinerarioPontoParada   = $oDaoItinerarioPontoParada->sql_record($sSqlItinerarioPontoParada);

      if ($oDaoItinerarioPontoParada->numrows == 0) {

        $oVariaveis         = new stdClass();
        $oVariaveis->codigo = $iCodigo;
        throw new BusinessException(_M('educacao.transporteescolar.ItinerarioPontoParada.nao_cadastrado', $oVariaveis));
      }

      $oItinerarioPontoParada = db_utils::fieldsMemory($rsItinerarioPontoParada, 0);
      $this->iCodigo          = $oItinerarioPontoParada->tre11_sequencial;
      $oLinhaItinerario       = new LinhaItinerarioLogradouro($oItinerarioPontoParada->tre11_itinerariologradouro);
      $this->setLinhaItinerarioLogradouro($oLinhaItinerario);
      $this->setPontoParada(new PontoParada($oItinerarioPontoParada->tre11_pontoparada));
      $this->setOrdem($oItinerarioPontoParada->tre11_ordem);
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
   * Retorna uma instância LinhaItinerarioLogradouro
   * @return object LinhaItinerarioLogradouro
   */
  public function getLinhaItinerarioLogradouro() {
    return $this->oLinhaItinerarioLogradouro;
  }

  /**
   * Define um objeto de LinhaItinerarioLogradouro
   * @param LinhaItinerarioLogradouro LinhaItinerarioLogradouro
   */
  public function setLinhaItinerarioLogradouro(LinhaItinerarioLogradouro $oLinhaItinerarioLogradouro) {
    $this->oLinhaItinerarioLogradouro = $oLinhaItinerarioLogradouro;
  }

  /**
   * Retorna uma instância de PontoParada
   * @return PontoParada
   */
  public function getPontoParada() {
    return $this->oPontoParada;
  }

  /**
   * Define o ponto de parada
   * @param PontoParada PontoParada
   */
  public function setPontoParada(PontoParada $oPontoParada) {
    $this->oPontoParada = $oPontoParada;
  }

  /**
   * Retorna a ordem
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Define a ordem
   * @param integer
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Persiste os dados do ponto de parada.
   * @throws DBException
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    if ($this->getLinhaItinerarioLogradouro() == null) {
      throw new BusinessException(_M('educacao.transporteescolar.ItinerarioPontoParada.logradouro_nao_informado'));
    }

    if ($this->getPontoParada() == '') {
      throw new BusinessException(_M('educacao.transporteescolar.ItinerarioPontoParada.pontoparada_nao_informado'));
    }

    $oDaoItinerarioPontoParada = new cl_linhatransportepontoparada();
    $oDaoItinerarioPontoParada->tre11_itinerariologradouro = $this->getLinhaItinerarioLogradouro()->getCodigo();
    $oDaoItinerarioPontoParada->tre11_pontoparada          = $this->getPontoParada()->getCodigo();
    $oDaoItinerarioPontoParada->tre11_ordem                = $this->getOrdem();

    if ($this->iCodigo == '') {

      $oDaoItinerarioPontoParada->incluir(null);
      $this->iCodigo = $oDaoItinerarioPontoParada->tre11_sequencial;
    } else {

      $oDaoItinerarioPontoParada->tre11_sequencial = $this->getCodigo();
      $oDaoItinerarioPontoParada->alterar($this->getCodigo());
    }

    if ($oDaoItinerarioPontoParada->erro_status == 0) {

      $sMensagem            = 'educacao.transporteescolar.ItinerarioPontoParada.erro_persitir_dados';
      $oVariaveis           = new stdClass();
      $oVariaveis->erro_dao = $oDaoItinerarioPontoParada->erro_msg;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
    
  }

  /**
   * Remove o ponto de parada do Itinerário
   * @throws DBException Nao existe transacao com o banco de dados
   * @throws BusinessException
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }
    
    $iTotalAlunos = count($this->getVinculoAlunos());
    if ($iTotalAlunos > 0) {
      
      $sMensagem                 = 'educacao.transporteescolar.ItinerarioPontoParada.existem_alunos_vinculados';
      $oVariaveis                = new stdClass();
      $oVariaveis->numero_alunos = $iTotalAlunos;
      throw new BusinessException(_M($sMensagem, $oVariaveis));
    }
    if ($this->getCodigo() != null) {

      $oDaoItinerarioPontoParada = new cl_linhatransportepontoparada();
      $oDaoItinerarioPontoParada->excluir($this->getCodigo());

      if ($oDaoItinerarioPontoParada->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.ItinerarioPontoParada.erro_remover_dados';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoItinerarioPontoParada->erro_msg;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }
    }
  }
   
  /**
   * Alunos que estão vinculados ao ponto de parada
   * @return ItinerarioVinculoAluno[]
   */
  public function getVinculoAlunos() {

    if (!$this->lAlunosCarregados  && !empty($this->iCodigo)) {

      $sWhere                = "tre12_linhatransportepontoparada = {$this->iCodigo}";
      $oDaoPontoParadaAluno  = new cl_linhatransportepontoparadaaluno();
      $sSqlAlunosPontoParada = $oDaoPontoParadaAluno->sql_query_file(null, "tre12_sequencial", null, $sWhere);
      $rsAlunosPontoParada   = $oDaoPontoParadaAluno->sql_record($sSqlAlunosPontoParada);
      if ($rsAlunosPontoParada && $oDaoPontoParadaAluno->numrows > 0) {
        
        for ($iAluno = 0; $iAluno < $oDaoPontoParadaAluno->numrows; $iAluno++) {
          
          $iVinculoAlunoPontoParada = db_utils::fieldsMemory($rsAlunosPontoParada, $iAluno)->tre12_sequencial;
          
          $this->aVinculoAlunos[$iVinculoAlunoPontoParada] = ItinerarioVinculoAlunoRepository::getItinerarioVinculoAlunoByCodigo($iVinculoAlunoPontoParada);
        }
      }
      $this->lAlunosCarregados = true;
    }

    return $this->aVinculoAlunos;
  }
}