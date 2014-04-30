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

require_once 'model/social/cadastrounico/IProcessarSituacaoCadastroUnico.interface.php';
require_once 'model/dbLayoutReader.model.php';
require_once 'model/dbLayoutLinha.model.php';
/**
 *
 * @author Trucolo <trucolo@dbseller.com.br>
 * @package social
 * @subpackage cadastrounico
 */
final class RevisaoCadastralCadastroUnico extends ProcessarSituacaoCadastroUnico implements IProcessarSituacaoCadastroUnico {

  /**
   * Código do layout (PK db_layouttxt)
   * @var integer
   */
  protected $iCodArquivo = 210;

  /**
   * Tipo da Situacao
   * @var integer
   */
  protected $iTipoSituacao = 3;

  /**
   * Nome do arquivo de log
   * @var string
   */
  protected $sNomeArquivoNaoProcessado = "tmp/logsRevisaoCadastralNaoProcessado.csv";

  /**
   * Informa se em algum momento foi lançado um não processado
   * @var boolean
   */
  private $lTemNaoProcessado = false;

  /**
   * Nome do arquivo que esta sendo processado
   * @var string
   */
  private $sNomeArquivo;

  /**
   * Nome do arquivo
   * @param string $sArquivo
   */
  public function __construct($sArquivo) {

    $this->sNomeArquivo = $sArquivo;
    $this->fArquivoLog  = fopen("{$this->sNomeArquivoNaoProcessado}", 'w');
    $this->removerSituacao();
  }

  /**
   * Abre o arquivo e percorre as linhas do mesmo realizando o processamento da informação
   */
  public function processarArquivo() {

    $oLayout = new DBLayoutReader($this->iCodArquivo, $this->sNomeArquivo,true, false);
    $_SESSION["DB_usaAccount"] = "1";
    $rArquivo                  = fopen($this->sNomeArquivo, "r");
    $iLinha                    = 0;

    while (!feof($rArquivo)) {

      $iLinha++;
      $sLinhaAtual = fgets($rArquivo);
      $oLinhaAtual = $oLayout->processarLinha($sLinhaAtual, 0, true, false, false);

      if (!$oLinhaAtual) {
        continue;
      }

      /**
       * Escreve o cabeçalho do arquivo de log
       */
      if ($iLinha == 1) {

        $this->escreveArquivoRegistrosNaoProcessados($sLinhaAtual);
        continue;
      }

      $iNisPessoaArquivo = $oLinhaAtual->nis_pessoa;

      /**
       * Validamos se o Nis do arquivo não esta vazio
       */
      if (empty($iNisPessoaArquivo)) {

        $this->escreveArquivoRegistrosNaoProcessados($sLinhaAtual);
        continue;
      }

      if (!$this->validar($iNisPessoaArquivo)) {

        $this->escreveArquivoRegistrosNaoProcessados($sLinhaAtual);
        $this->lTemNaoProcessado = true;
      }

      unset($oLinhaAtual);
    }

    fclose($this->fArquivoLog);
  }

  /**
   * Verifica se o nis encontrado no arquivo, esta presente no sistema;
   * Se sim vincula a situação, se não retorna false
   * @param integer $iNisArquivo
   * @return boolean
   */
  protected function validar($iNisArquivo) {

    $sWhere = "as02_nis = '{$iNisArquivo}'";

    $oDaoCidadaoCadUnico = new cl_cidadaocadastrounico();
    $sSqlCidadaoCadUnico = $oDaoCidadaoCadUnico->sql_query_file(null, "as02_sequencial", null, $sWhere);
    $rsCidadaoCadUnico   = $oDaoCidadaoCadUnico->sql_record($sSqlCidadaoCadUnico);

    if ($oDaoCidadaoCadUnico->numrows > 0) {

      $this->insereSituacao(db_utils::fieldsMemory($rsCidadaoCadUnico, 0)->as02_sequencial);
      return true;
    }

    return false;
  }

  /**
   * Retorna o caminho do arquivo com uma lista dos registros não processados
   * @see IProcessarSituacaoCadastroUnico::getNaoProcessados()
   */
  public function getNaoProcessados() {

    if ($this->lTemNaoProcessado) {
      return $this->sNomeArquivoNaoProcessado;
    }
    return "";
  }
}