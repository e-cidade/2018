<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
   * Conexão de origem dos dados (E-CIDADE)
   * @var resource
   */
  protected $rsConexaoOrigem;

  /**
   * Conexão de destino dos dados (TRANSPARÊNCIA)
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
   * @param $sConexaoOrigem resource da conexão com o ecidade
   */
  public function setConexaoOrigem($sConexaoOrigem) {
    $this->rsConexaoOrigem = $sConexaoOrigem;
  }

  /**
   * Define a conexão com o banco do ecidade-transparencia
   *
   * @param Resource $sConexaoDestino
   */
  public function setConexaoDestino($sConexaoDestino) {
    $this->rsConexaoDestino = $sConexaoDestino;
  }

  /**
   * Indica o ano que deve ser iniciado a integração
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