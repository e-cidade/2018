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

/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe para controle das integrações do Ecidade com o Protal da Transparencia
 * @package Integracao
 * @subpackage Transparencia
 */
class IntegracaoPortalTransparencia {

  /**
   * Itens para Integracao do Ecidade
   * @var  IItemIntegracao[]
   */
  private $oItensParaIntegracao = array();

  /**
   * Ano de Inicio da Integracao
   * @var integer
   */
  private $iAnoInicioIntegracao = null;

  /**
   * Conexao de origem
   * @var resource
   */
  private $rsConexaoOrigem = null;

  /**
   * Conexão de Destino
   * @var resource
   */
  private $rsConexaoDestino = null;

  /**
   * Arquivo de destino do log
   * @var string
   */
  private $sArquivoLog = null;

  /**
   *  Define o tipo de log que deve ser gerado :
   *  0 - Imprime log na tela e no arquivo
   *  1 - Imprime log somente da tela
   *  2 - Imprime log somente no arquivo
   *  @var integer
   */
  private $iTipoLog = 0;  

  /**
   * Realiza o log das acoes em Tela e no arquivo
   */
  CONST LOG_ARQUIVO_TELA = 0;


  /**
   * Log sera criado no arquivo
   */
  CONST LOG_ARQUIVO = 1;

  /**
   * Logo Sera Feito APenas na Tela
   */
  CONST LOG_TELA   = 1;

  /**
   * Define o parametro do log
   * @param integer $iTipoLog
   */
  public function setParamLog($iTipoLog) {
    $this->iTipoLog = $iTipoLog;
  }

  /**
   * Define o arquivo de log
   * @param string $sArquivoLog
   */
  public function setArquivoLog($sArquivoLog) {
    $this->sArquivoLog = $sArquivoLog;
  }

  /**
   * Define o Ano de Inicio de integracao do portal
   * @param integer $iAnoInicioIntegracao
   */
  public function setAnoInicioIntegracao($iAnoInicioIntegracao) {
    $this->iAnoInicioIntegracao = $iAnoInicioIntegracao;
  }

  /**
   * Retorna o Ano de inicio da integracao
   * @return int
   */
  public function getAnoInicioIntegracao() {
    return $this->iAnoInicioIntegracao;
  }

  /**
   * Adiciona um item para ser executada a integracao
   * @param IItemIntegracao $oItemIntegracao Instancia do item de Integracao
   * @return boolean
   */
  public function adicionarIntegracao(IItemIntegracao $oItemIntegracao) {

    foreach ($this->oItensParaIntegracao as $oItemIntegracaoAdicionado) {
      if (get_class($oItemIntegracaoAdicionado) == get_class($oItemIntegracao)) {
        return false;
      }
    }
    $this->oItensParaIntegracao[] = $oItemIntegracao;
    return true;
  }

  /**
   * Realiza a escrita de log
   * @param string $sLog
   * @param string $sArquivo
   * @param int    $iTipo
   * @param bool   $lLogDataHora
   * @param bool   $lQuebraAntes
   * @return array
   */
  public static function log($sLog = "", $sArquivo = "", $iTipo = 0, $lLogDataHora = true, $lQuebraAntes = true) {

    $aDataHora    = getdate();
    $sQuebraAntes = $lQuebraAntes ? "\n" : "";

    if ($lLogDataHora) {
      $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s",
                            $sQuebraAntes,
                            $aDataHora ["mday"],
                            $aDataHora ["mon"],
                            $aDataHora ["year"],
                            $aDataHora ["hours"],
                            $aDataHora ["minutes"],
                            $aDataHora ["seconds"], $sLog);
    } else {
      $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
    }

    /**
     * Caso o log seja output na Tela
     */
    switch ($iTipo) {

      case self::LOG_TELA:
      case self::LOG_ARQUIVO_TELA:

        echo $sOutputLog;
        break;

      case self::LOG_ARQUIVO:
      case self::LOG_ARQUIVO_TELA:

        if ($iTipo == self::LOG_ARQUIVO_TELA or $iTipo == self::LOG_ARQUIVO) {

          if (empty($sArquivo)) {
            return true;
          }
          $rsArquivoLog = fopen($sArquivo, "a+");
          if (!$rsArquivoLog) {
            return false;
          }

          fwrite($rsArquivoLog, $sOutputLog);
          fclose($rsArquivoLog);
        }
        BREAK;
    }
    return $aDataHora;
  }

  /**
   * Realizada o Log do total de linhas do processamento do Item Corrente
   * @param integer $iLinhaAtual  Item o qual está sendo processado Atualmente
   * @param integer $iTotalLinhas Total de linhas que devem ser processadas
   * @param integer $iTipoLog     Forma que o log sera executado do log
   */
  public static function logarProcessamento($iLinhaAtual, $iTotalLinhas, $sArquivoLog, $iTipoLog) {

    $nPercentual = round((($iLinhaAtual + 1) / $iTotalLinhas) * 100, 2);
    $nMemScript  = (float)round( (memory_get_usage()/1024 ) / 1024,2);
    $sMemScript  = $nMemScript ." Mb";

    $sMensagem   = "".($iLinhaAtual+1)." de {$iTotalLinhas} Processando ".str_pad($nPercentual,5,' ',STR_PAD_LEFT)." %";
    $sMensagem  .= " Total de memoria utilizada : {$sMemScript} ";

    $sMensagemFormatada = str_pad($sMensagem, 100, " ",STR_PAD_RIGHT);
    self::log("{$sMensagemFormatada}\r", $sArquivoLog, $iTipoLog, true, false);
  }

  /**
   * Escreve o Titulo do processamento
   * @param string $sTitulo
   * @param string $sArquivoLog
   * @param int    $iParamLog
   */
  public static function escreverTitulo ($sTitulo = "", $sArquivoLog = "", $iParamLog = 0) {

    self::log("",$sArquivoLog, $iParamLog);
    self::log("//".str_pad($sTitulo, 85, "-", STR_PAD_BOTH)."//", $sArquivoLog, $iParamLog);
    self::log("", $sArquivoLog, $iParamLog);
    self::log("", $sArquivoLog, $iParamLog);
  }


  /**
   * Mostrado o Total de Registros processados para o item da integracao
   * @param $iLinhas
   * @param $sArquivoLog
   * @param $iParamLog
   */
  public static function escreverRegistrosProcessados($iLinhas, $sArquivoLog, $iParamLog) {

    self::log("Total de Registros Encontrados : {$iLinhas}", $sArquivoLog, $iParamLog);
    self::log("\n",$sArquivoLog,1);
  }

  public function executar() {

    foreach ($this->oItensParaIntegracao as $oIntegracao) {

      $oIntegracao->setAnoInicioExecucao($this->getAnoInicioIntegracao());
      $oIntegracao->setConexaoDestino($this->rsConexaoDestino);
      $oIntegracao->setConexaoOrigem($this->rsConexaoOrigem);
      $oIntegracao->setArquivoLog($this->sArquivoLog);
      $oIntegracao->setTipoLog($this->iTipoLog);
      
      $oIntegracao->executar();
    }
  }

  /**
   * Define o resource de conexao com o banco de Dados do Portal da transparencia
   * @param resource $rsConexaoDestino
   */
  public function setConexaoDestino($rsConexaoDestino) {
    $this->rsConexaoDestino = $rsConexaoDestino;
  }

  /**
   * Define o resource de conexao com o banco de Dados do Ecidade
   * @param resource $rsConexaoOrigem
   */
  public function setConexaoOrigem($rsConexaoOrigem) {
    $this->rsConexaoOrigem = $rsConexaoOrigem;
  }
}