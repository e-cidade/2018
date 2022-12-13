<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
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
abstract class IntegracaoBase {

  /**
   * Conex�o de origem dos dados (E-CIDADE)
   * @var resource
   */
  protected $rsConexaoOrigem;

  /**
   * Conex�o de destino dos dados (TRANSPAR�NCIA)
   * @var resource
   */
  protected $rsConexaoDestino;

  /**
   * Ano de inicio das integracao
   * @var integer
   */

  protected $iAnoInicioIntegracao = null;

  /**
   * Arquivo do log
   * @var string
   */
  protected $sArquivoLog;

  /**
   * Tipo do log
   * @var integer
   */
  protected $iTipoLog;

  protected $aInstituicoesTransparencia = array();

  /**
   * Define o arquivo a ser usado para log
   * @param string $sArquivoLog
   */
  public function setArquivoLog($sArquivoLog) {
    $this->sArquivoLog = $sArquivoLog;
  }

  /**
   * Define o tipo de log
   * @param integer $iTipoLog
   */
  public function setTipoLog($iTipoLog) {
    $this->iTipoLog = $iTipoLog;
  }

  /**
   * Define a conexao com o o banco de dados do ecidade
   *
   * @param $sConexaoOrigem resource da conex�o com o ecidade
   */
  public function setConexaoOrigem($sConexaoOrigem) {
    $this->rsConexaoOrigem = $sConexaoOrigem;
  }

  /**
   * Define a conex�o com o banco do ecidade-transparencia
   *
   * @param Resource $sConexaoDestino
   */
  public function setConexaoDestino($sConexaoDestino) {
    $this->rsConexaoDestino = $sConexaoDestino;
  }

  /**
   * Indica o ano que deve ser iniciado a integra��o
   *
   * @param integer $iAno ano de inicio
   */
  public function setAnoInicioExecucao($iAno) {
    $this->iAnoInicioIntegracao = $iAno;
  }

  /**
   * Insere um dado no table manageer, para posteriormente ser realizado a persistencia dos dados
   * @param  stdclass         $oDados dados que devem ser migradosq
   * @param tableDataManager $oTableManager instancia do table Manager
   * @throws Exception
   */
  protected function inserirDadosPortalTransparencia ($oDados, tableDataManager $oTableManager) {

    $oTableManager->setByLineOfDBUtils($oDados);
    try {

      $oTableManager->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }
  }

  /**
   * Persiste os dados no portal da transparencia
   * @param tableDataManager $oTableManager
   * @throws Exception
   */
  protected function persistirDadosPortalTransparencia(tableDataManager $oTableManager) {
    try {

      $oTableManager->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-1: {$eException->getMessage()}");
    }
  }

  /**
   * Retorna o codigo da instituicao do ecidade no portal da transparencia
   * @param integer $iCodigoInstituicao Codigo da instituicao do Ecidade
   * @return null|int
   */
  protected function getCodigoInstituicoesNoTransparencia($iCodigoInstituicao) {

    if (count($this->aInstituicoesTransparencia) == 0) {

      $sSqlQueryInstituicoes = "select * from instituicoes";
      $rsLicitacoes          = db_query($this->rsConexaoDestino, $sSqlQueryInstituicoes);
      $iTotalInstituicoes    = pg_num_rows($rsLicitacoes);
      for ($iInstituicao = 0; $iInstituicao < $iTotalInstituicoes; $iInstituicao++) {

        $oInstituicao                                               = db_utils::fieldsMemory($rsLicitacoes,
                                                                                             $iInstituicao);
        $this->aInstituicoesTransparencia[$oInstituicao->codinstit] = $oInstituicao->id;
      }
    }

    if (!isset($this->aInstituicoesTransparencia[$iCodigoInstituicao])) {
      return null;
    }
    
    return $this->aInstituicoesTransparencia[$iCodigoInstituicao];
  }
}
?>