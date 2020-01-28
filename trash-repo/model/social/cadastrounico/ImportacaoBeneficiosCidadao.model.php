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
 * Realiza a importacao dos Beneficios do cidadao
 * atravez do arquivo do sibec "Situacao dos Beneficios"
 * @package social
 * @subpackage cadastrounico
 * @Version $Revision: 1.3 $
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 */
class ImportacaoBeneficiosCidadao {
  
  const CODIGO_LAYOUT  = 189;

  protected $sCaminhoArquivo;
  
  
  /**
   * Instancia da classe DBLayoutReader
   * @var DBLayoutReader
   */
  protected $oLayoutReader;
  
  
  /**
   * Total de Beneficios Processados
   */
  protected $iTotalBeneficios = 0;
  
  /**
   * LIsta de Beneficios
   * @var CidadaoBeneficio
   */
  protected $aBeneficios = array();
  
  protected $iAnoCompentencia;
  
  protected $iMesCompetencia;
  
  /**
   * Importa os dados do arquivo para a base de dados
   */
  function __construct($sCaminhoArquivo) {
    
    $this->validarArquivo($sCaminhoArquivo);
    $this->sNomeArquivo  = $sCaminhoArquivo;
    $this->oLayoutReader = new DBLayoutReader(ImportacaoBeneficiosCidadao::CODIGO_LAYOUT, $this->sNomeArquivo, false, false);
    $this->setCompetencia();
    if ($this->iAnoCompentencia == "" || $this->iMesCompetencia == "") {
      throw new FileException("Arquivo {$sCaminhoArquivo} inválido, não é um arquivo do SIBEC para importaçãoo do Benefícios.", 1);
    }
  }
  
  /**
   * Valida os dados do arquivo antes de iniciarmos o processamento
   */
  public function validarArquivo($sArquivo) {
    
    if (!file_exists($sArquivo)) {
      throw new FileException("Arquivo {$sArquivo} não existe.", 1);
    }
    if (!is_readable($sArquivo)) {
      throw new FileException("Arquivo {$sArquivo} sem permissão de leitura", 2);
    }
    $sNomeArquivo = strtolower(basename($sArquivo));
    $sExtensao   = substr($sNomeArquivo,  -3);  
    if ($sExtensao != "csv") {
      throw new FileException("Arquivo {$sArquivo} invalido, Deve ser um arquivo de extensão CSV.", 1);
    }
  }
  
  /**
   * Carrega os dados do arquivo dentro de cada grupo
   */
  public function processarArquivo () {
    
    $_SESSION["DB_usaAccount"]    = "1";
    $rArquivo                     = fopen($this->sNomeArquivo, 'r');
    $iLinha                       = 0;
    $this->iCodigoFamiliaAnterior = null;
    $this->removerBeneficiosDaCompetencia();
    
    /**
     * Persiste os Dados a cada Bloco de Beneficios 
     */
    $iTamanhoBloco = 100;
    db_inicio_transacao();
    while (!feof($rArquivo)) {

      $sLinha = fgets($rArquivo);
      if ($iLinha == 0) {
        
        $iLinha++;
        continue;
      }
      $iLinha++; 
      $oLinha = $this->oLayoutReader->processarLinha($sLinha, 0, true, false, false);
      if (!$oLinha) {
        continue;
      }
      
      /**
       * Linha é invalida quando o nis do beneficiario for vazia
       */
      if (trim($oLinha->nis_beneficiario) == '') {
        continue;
      }
      $iProgramaSocial   = substr($oLinha->codigo_programa, 0, 9);
      $oBeneficio        = new CidadaoBeneficio();
      list($iMes, $iAno) = explode("/",  $oLinha->mes_ano); 
      $oBeneficio->setAnoCompetencia($iAno);
      $oBeneficio->setMesCompetencia($iMes);
      
      if (trim($oLinha->dt_concessao) != "") {
        $oBeneficio->setDataConcessao(new DBDate($oLinha->dt_concessao));
      }
      if (trim($oLinha->dt_situacao) != '') {
        $oBeneficio->setDataSituacao(new DBDate($oLinha->dt_situacao)); 
      }
      
      $oBeneficio->setJustificativa(pg_escape_string(utf8_decode($oLinha->justificativa)));
      $oBeneficio->setMotivo(addslashes(utf8_decode($oLinha->motivo)));
      $oBeneficio->setNis($oLinha->nis_beneficiario);
      $oBeneficio->setProgramaSocial($iProgramaSocial);
      $oBeneficio->setSituacao($oLinha->situacao);
      $oBeneficio->setTipoBeneficio(strtoupper(db_removeAcentuacao(trim($oLinha->tipo_beneficio))));
      array_push($this->aBeneficios, $oBeneficio);         
      
      /**
       * Persistimos os dados a cada tamanho de bloco completado.
       */
      if ($iLinha >= $iTamanhoBloco) {
        
        $this->salvarBloco();
        $iLinha = 1;
      }
      
      $this->iTotalBeneficios++;
      
      /*
       * 
       */
    }
    
    $this->salvarBloco();
    fclose($rArquivo);
    db_fim_transacao(true);
    unset($_SESSION["DB_usaAccount"]);
  }
  
  /**
   * Persite o bloco de Dados no sistema
   */
  protected function salvarBloco() {
    
    foreach ($this->aBeneficios as $oBeneficio) {
      $oBeneficio->salvar();
    }
    db_fim_transacao(false);
    db_inicio_transacao();
    $this->aBeneficios = array();
  }
  
  /**
   * Remove os Beneficios da Competencia
   */
  protected function removerBeneficiosDaCompetencia() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Transação com o banco de dados não encontrada.");
    }
    $_SESSION["DB_usaAccount"]    = "1";
    $oDaoBeneficio   = db_utils::getDao("cidadaobeneficio");
    $sWhere          = "as08_mes = {$this->getMesCompetencia()} ";
    $sWhere         .= " and as08_ano = {$this->getAnoCompetencia()} ";
    $oDaoBeneficio->excluir(null, $sWhere);
    if ($oDaoBeneficio->erro_status == 0) {
      throw new Exception("Erro ao excluir Beneficios Existentes.");
    }
    
    unset($_SESSION["DB_usaAccount"]);
    if ($oDaoBeneficio->numrows > 0) {
      return true;
    }
    return false;
  }
  
  /**
   * Retorna o ano da Competencia do arquivo
   * @return integer
   */
  public function getAnoCompetencia() {
    return $this->iAnoCompentencia;
  }
  
  /**
   * Retorna o mes da Competencia do arquivo
   * @return integer
   */
  public function getMesCompetencia() {
    return $this->iMesCompetencia;
  }
  
  /**
   * Verifica se já existe algum beneficio na compentencia
   * @return boolean
   */
  public function hasBeneficiosNaCompetencia() {
    
    $oDaoBeneficio   = db_utils::getDao("cidadaobeneficio");
    $sWhere          = "as08_mes = {$this->getMesCompetencia()} ";
    $sWhere         .= " and as08_ano = {$this->getAnoCompetencia()} ";
    $sSqlBeneficios  = $oDaoBeneficio->sql_query_file(null, "1", "as08_sequencial limit 1", $sWhere);
    $rsBeneficios    = $oDaoBeneficio->sql_record($sSqlBeneficios);
    if ($oDaoBeneficio->numrows > 0) {
      return true;
    }
    return false;
  }
  
  /**
   * Verifica o mes de Competencia do Arquivo
   */
  protected function setCompetencia() {
    
    $rArquivo = fopen($this->sNomeArquivo, 'r');
    $sLinha   = '';
    $iLinha   = 0;
    while ($iLinha < 2) {
      
      $sLinha = fgets($rArquivo);
      $iLinha++;
    }
    $oLinha                 = $this->oLayoutReader->processarLinha($sLinha, 0, true, false, false);
    if (strpos($oLinha->mes_ano, "/") !== false) {
      
      list($iMes, $iAno)      = explode("/",  $oLinha->mes_ano);
      $this->iAnoCompentencia = $iAno;
      $this->iMesCompetencia  = $iMes;
    }
    fclose($rArquivo);
  }
}

?>