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
 * Classe para importação do arquivo BCP do cadastro único
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 *
 */
final class BpcCadastroUnico extends ProcessarSituacaoCadastroUnico implements IProcessarSituacaoCadastroUnico {

  /**
   * Código do layout do arquivo
   * @var integer
   */
  private $iCodigoArquivo = 209;

  /**
   * Array com as linhas não processadas
   * @var array
   */
  private $aNaoProcessados = array();

  /**
   * Tipo da situação referente ao arquivo BPC
   * @var integer
   */
  protected $iTipoSituacao = 2;

  /**
   * Nome do arquivo a ser processado
   * @var string
   */
  private $sNomeArquivo;

  /**
   * Nome dos arquivos com as linhas não processadas
   * @var string
   */
  private $sNomeArquivoNaoProcessados = 'tmp/logsBpcNaoProcessados.csv';

  /**
   * Instancia de DBLayoutReader
   * @var DBLayoutReader
   */
  private $oLayoutReader;

  /**
   * Informa se em algum momento foi lançado um não processado
   * @var boolean
   */
  private $lTemNaoProcessado = false;

  /**
   * Construtor da classe. Recebe como parametro o nome do arquivo a ser processado
   */
  public function __construct($sArquivo) {

    $this->sNomeArquivo = $sArquivo;
    $this->fArquivoLog  = fopen("{$this->sNomeArquivoNaoProcessados}", 'w');
  }

  /**
   * Trata o processamento do arquivo
   */
  public function processarArquivo() {

    /**
     * Cria uma instancia de DBLayoutReader referente ao arquivo a ser processado e o layout cadastrado
     */
    $this->oLayoutReader = new DBLayoutReader($this->iCodigoArquivo, $this->sNomeArquivo, true, false);

    $_SESSION["DB_usaAccount"] = "1";

    /**
     * Remove da base todos os registros referentes a situação a ser processada. No caso, situacao 2 - BPC
     */
    $this->removerSituacao();
    $rsArquivo = fopen($this->sNomeArquivo, 'r');
    $iLinha    = 0;

    /**
     * Percorre o arquivo para tratamento das linhas
     */
    while (!feof($rsArquivo)) {

      $iLinha++;
      $sLinha  = fgets($rsArquivo);
      $oLinha  = $this->oLayoutReader->processarLinha($sLinha, 0, true, false, false);

      if (!$oLinha) {
        continue;
      }

      /**
       * Salva a primeira linha do arquivo por se o cabeçalho do mesmo, adicionando no arquivo de não processados
       * Ou se o nome da pessoa ou data de nascimento estiverem vazias
       */
      if ($iLinha == 1 || empty($oLinha->nome_pessoa) || empty($oLinha->data_nascimento)) {

        $this->escreveArquivoRegistrosNaoProcessados($sLinha);
        continue;
      }

      $oDataNascimento = new DBDate($oLinha->data_nascimento);
      $dtNascimento    = $oDataNascimento->convertTo(DBDate::DATA_EN);

      /**
       * Chama o método validar, responsavel por verificar se existe algum registro com os dados passados
       * Passamos o nome da pessoa da linha atual do arquivo, e a data de nascimento, já tratada, no formato do banco
       */
      $iCadastroUnico  = $this->validar($oLinha->nome_pessoa, $dtNascimento);

      /**
       * Caso tenha sido retornado o sequencial do cidadao na validacao, chama o metodo insereSituacao para inserir o
       * registro para o cidadao com tipo de situacao 2
       */
      if ($iCadastroUnico != null) {
        $this->insereSituacao($iCadastroUnico);
      } else {

        $this->escreveArquivoRegistrosNaoProcessados($sLinha);
        $this->lTemNaoProcessado = true;
      }

      unset($oLinha);
      unset($oDataNascimento);
    }

    fclose($this->fArquivoLog);
  }

  /**
   * Valida se existe um registro de cidadao com base no nome e data de nascimento vindos do arquivo processado
   * @param string $sNome
   * @param date   $dtNascimento
   * @return integer
   */
  public function validar($sNome, $dtNascimento) {

    $iCadastroUnico      = null;
    $oDaoCadastroUnico   = new cl_cidadaocadastrounico();
    $sWhereCadastroUnico = "trim(ov02_nome) = '".trim($sNome)."' and ov02_datanascimento = '{$dtNascimento}'";
    $sSqlCadastroUnico   = $oDaoCadastroUnico->sql_query(null, "as02_sequencial", null, $sWhereCadastroUnico);
    $rsCadastroUnico     = $oDaoCadastroUnico->sql_record($sSqlCadastroUnico);

    if ($oDaoCadastroUnico->numrows > 0) {
      $iCadastroUnico = db_utils::fieldsMemory($rsCadastroUnico, 0)->as02_sequencial;
    }

    return $iCadastroUnico;
  }

  /**
   * Retorna o nome do arquivo com as linhas nao processadas
   * @return string
   */
  public function getNaoProcessados() {

    if ($this->lTemNaoProcessado) {
      return $this->sNomeArquivoNaoProcessados;
    }
    return "";
  }
}