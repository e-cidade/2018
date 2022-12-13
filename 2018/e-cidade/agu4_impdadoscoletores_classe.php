<?php

/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
 * Classe que lê o arquivo retornado pelo coletor eletronico
 * e gera dados nas tabelas
 * 
 * @author Alberto Ferri <alberto@dbseller.com.br>
 */

require_once(modification("libs/db_utils.php"));

class cl_importaDadosColetor {

  /**
   * caminho do arquivo
   * @var string
   */
  protected $arquivo = null;

  /**
   * tipo de acesso ao arquivo
   * @var string
   */
  protected $mode    = "r+";

  /**
   * ponteiro do arquivo
   * @var ponteiro
   */
  protected $cursor;

  /**
   * conteudo de uma linha de um arquivo
   * @var array
   */
  protected $linha = array();

  /**
   * numero de linhas do arquivo
   * @var integer
   */
  public $numLinhas = 0;

  /**
   * Status da classe quando instanciada / 1-Ok, 2-Erro
   * @var integer
   */
  public $iErroStatus = 1;

  /**
   * Mensagem de erro caso ocorra.
   * @var string
   */
  public $sErroMsg    = "";

  public $countExportaDados = 0;

  public $arrayExportacao = array();

  public $arrayArquivo = array();

  public $iCodExportacao;

  public $iSituacao;

  public $iUsuario;

  public $dData;

  public $sHora;

  public $sMotivo;

  public $iCodColetorExportaSituacao;

  public $iCodColetorExporta;

  protected $clACExporta;

  protected $clACExportaDados;

  protected $clReciboPaga;
  
  public $iCodColetorExportaDados;

  protected $clAguaLeitura;

  protected $clACExportaDadosLeitura;

  protected $clACESituacao;

  protected $clAguaLeituraCancela;

  protected $clAguaConsumoTipo;

  protected $clACEDadosFoto;
  
  protected $clDBReciboWeb;
  
  protected $clACEDadosReceita;
  
  protected $clReciboCodBar;

  public $iAno;

  public $iMes;

  public $iLinhaArquivo;

  public $iDBUsuario;

  public $dDtAtual;

  public $iCodLeitura;

  protected $clAguaCalc;

  protected $clAguaCalcVal;

  protected $clAguaConf;

  public $iCodConfExcesso;

  public $iAnoExportacao;

  public $iMesExportacao;

  public $iCodRecExcesso;

  public $iNumpre;

  public $iCodCalc;
  
  protected $iNumPreDados;
  
  protected $iCodBarrasDados;
  
  protected $iLinhaDigitavelDados;

  /**
   * verifica arquivos dentro de arquivo compactado
   * @param $zipFile
   * @return array
   */
  public function lerZIP($zipFile) {
     
    if (file_exists($zipFile)) {
      
      $objZip = new ZipArchive();
       
      if ($objZip->open($zipFile) === TRUE) {
        
        $numFiles = $objZip->numFiles;
         
        for($i = 0; $i <= $numFiles; $i++) {
           
          $fileName[] = $objZip->getNameIndex($i);
        }
         
        return $fileName;
         
      } else {
         
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Não foi possivel abrir o arquivo compactado.";
        
        return false;  
      }  
    } else {
       
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Arquivo não encontrado";
      
      return false;
    }
  }

  public function importaFoto($iCodImportacao, $iCodMatricula, $iCodColetorExportaDados, $arrayArquivos, $conn) {
    
    require_once(modification("classes/db_aguacoletorexportadadosfoto_classe.php"));

    $this->clACEDadosFoto = new cl_aguacoletorexportadadosfoto();
     
    for($i = 0; $i < count($arrayArquivos); $i++) {
       
      $codFoto = explode("-", $arrayArquivos[$i]);
       
      if ($codFoto[0] == $iCodMatricula) {
        
        $arquivo = "tmp/importacao_".$iCodImportacao."/".$arrayArquivos[$i];
        $geraOIDFoto = $this->db_geraArquivoOid($arquivo, $conn);
        
        $this->clACEDadosFoto->x53_usuario                 = db_getsession('DB_id_usuario');
        $this->clACEDadosFoto->x53_aguacoletorexportadados = $iCodColetorExportaDados;
        $this->clACEDadosFoto->x53_oidfoto                 = $geraOIDFoto;
        $this->clACEDadosFoto->x53_data                    = date("Y-m-d");
        $this->clACEDadosFoto->x53_hora                    = date("H:i");
        $this->clACEDadosFoto->incluir(null);
         
        if ($this->clACEDadosFoto->erro_status == "0") {
          
          $this->iErroStatus = 0;
          $this->sErroMsg    = "Inclusao na tabela aguacoletorexportadadosfoto não efetuada. Operação abortada.";
          $this->sErroMsg   .= "ERRO: {$this->clACEDadosFoto->erro_msg}";
          
          return false;
        }
      }
    }
  }

  public function db_geraArquivoOid ($arquivo, $conn) {

    $nomeArquivo        = $arquivo;
    $localRecebeArquivo = $arquivo;

    if ( trim($localRecebeArquivo) != "") {
      
      $arquivoGrava = fopen($localRecebeArquivo, "rb");
      
      if ($arquivoGrava == false) {
        
        echo "erro aruivograva";
        exit;
      }
      
      $dados = fread($arquivoGrava, filesize($localRecebeArquivo));
      
      if ($dados == false) {
        
        echo "erro fread";
        exit;
      }
      
      fclose($arquivoGrava);
      $oidgrava = pg_lo_create();
      
      if ($oidgrava == false) {
        
        echo "erro pg_lo_create";
        exit;
      }
       
      $objeto = pg_lo_open($conn, $oidgrava, "w");
      
      if ($objeto != false) {
        
        $erro = pg_lo_write($objeto, $dados);
        
        if ($erro == false) {
          
          echo "erro pg_lo_write";
          exit;
        }
        
        pg_lo_close($objeto);
      } else {
        $erro_msg = "Operação Cancelada!!";
        $sqlerro = true;
      }

      return $oidgrava;
    }
  }

  public function getArquivoTxt($arrayArquivos) {
     
    if (count($arrayArquivos) > 0) {
       
      $count = 0;
       
      for ($i = 0; $i < count($arrayArquivos); $i++) {
         
        $tipoArquivo = explode(".", $arrayArquivos[$i]);
        $extensao    = array_pop($tipoArquivo);
        
        if ($extensao == "txt") {
           
          $count ++;
          $nomeArquivoImportacao = $arrayArquivos[$i];
        }
      }
       
      if ($count != 1) {
         
        $this->iErroStatus = 0;
        $this->sErroMSg    = "O arquivo compactado é invalido";
        
        return false;

      } elseif($count == 1) {
        return $nomeArquivoImportacao;
      }
    }
  }

  public function descompactaZIP($zipFile, $dir) {
     
    $objZip = new ZipArchive();
     
    if ($objZip->open($zipFile)) {
       
      $return = $objZip->extractTo("tmp/importacao_$dir/");
       
      $objZip->close();
       
      if (!$return) {
         
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Não foi possivel abrir o arquivo compactado.";
        return false;
      }
       
    } else {

      $this->iErroStatus = 0;
      $this->sErroMsg    = "Não foi possivel abrir o arquivo compactado.";
      return false;
    }
  }

  /**
   * Recebe o caminho do arquivo e acessa o mesmo
   * @param $arquivo - caminho do arquivo
   *
   */
  public function leArquivoTXT($arquivo, $importacao) {
     
    $arquivo = "tmp/importacao_$importacao/$arquivo";

    if (!file_exists($arquivo)) {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Arquivo não encontrado";
      return false;
    }

    $this->arquivo = $arquivo;
    $this->cursor  = fopen($this->arquivo, $this->mode);
  }

  /**
   * Le conteudo do arquivo e coloca cada linha em um indice de um vetor
   * @return void
   */
  public function readFile() {

    if ($this->cursor) {
      
      while(!feof($this->cursor)) {
        
        $conteudoLinha = fgets($this->cursor, 500);
        
        if(trim($conteudoLinha) != '') {
          
          $this->linha[$this->numLinhas] = $conteudoLinha;
          $this->numLinhas++;
          $this->arrayArquivo[$this->numLinhas] = trim(substr($conteudoLinha, 0, 8));
        }
      }
      fclose($this->cursor);
    }
  }
  
  public function setNumpreDados($iNumPreDados) {
    $this->iNumPreDados = $iNumPreDados;  
  }
  
  public function setCodBarrasDados($iCodBarrasDados) {
    $this->iCodBarrasDados = $iCodBarrasDados;
  }
  
  public function setLinhaDigitavelDados($iLinhaDigitavelDados) {
    $this->iLinhaDigitavelDados = $iLinhaDigitavelDados;
  }
  
  /**
   * Modo de acesso ao arquivo
   * @param $mode
   */
  public function setMode($mode) {
    $this->mode = $mode;
  }

  /**
   * Retorna conteudo de uma linha do arquivo
   * @param $linha
   * @return String
   */
  public function getLinha($linha) {
    return trim($this->linha[$linha]);
  }

  /**
   * Codigo da Leitura Coletada
   * @param $linha
   * @return String
   */
  public function getCodLeitura($linha) {
    return trim(substr($this->linha[$linha], 0, 8));
  }

  /**
   * Código da rota da rua coletada
   * @param $linha
   * @return String
   */
  public function getCodRota($linha) {
    return trim(substr($this->linha[$linha], 8, 4));
  }

  /**
   * Código do Tipo de Logradouro
   * @param $linha
   * @return String
   */
  public function getTipoLogradouro($linha) {
    return trim(substr($this->linha[$linha], 12, 4));
  }

  /**
   * Nome do Logradouro
   * @param $linha
   * @return String
   */
  public function getNomeLogradouro($linha) {
    return trim(substr($this->linha[$linha], 16, 55));
  }

  /**
   * Codigo do leiturista que efetuou a leitura
   * @param $linha
   * @return String
   */
  public function getCodLeiturista($linha) {
    return trim(substr($this->linha[$linha], 71, 10));
  }

  /**
   * Codigo da Matricula da Leitura
   * @param $linha
   * @return String
   */
  public function getCodMatricula($linha) {
    return trim(substr($this->linha[$linha], 81, 10));
  }

  /**
   * Código do Hidrometro da Leitura
   * @param $linha
   * @return String
   */
  public function getCodHidrometro($linha) {
    return trim(substr($this->linha[$linha], 91, 20));
  }

  /**
   * Data Leitura
   * @param $linha
   * @return String
   */
  public function getDataLeituraAtual($linha) {
    return trim(substr($this->linha[$linha], 111, 10));
  }

  /**
   * Data da Leitura Anterior
   * @param $linha
   * @return String
   */
  public function getDataLeituraAnterior($linha) {
    return trim(substr($this->linha[$linha], 121, 10));
  }

  /**
   * Valor do Consumo da Leitura
   * @param $linha
   * @return String
   */
  public function getConsumoAtual($linha) {
    return trim(substr($this->linha[$linha], 131, 8));
  }

  /**
   * Dias entre as Leituras
   * @param $linha
   * @return String
   */
  public function getDiasEntreLeituras($linha) {
    return trim(substr($this->linha[$linha], 139, 4));
  }

  /**
   * Média Consumo por dia
   * @param $linha
   * @return String
   */
  public function getMediaConsumo($linha) {
    return trim(substr($this->linha[$linha], 143, 10));
  }

  /**
   * Data Vencimento da Conta
   * @param $linha
   * @return String
   */
  public function getDataVencimento($linha) {
    return trim(substr($this->linha[$linha], 153, 10));
  }

  /**
   * Valor acrescimo da Conta
   * @param $linha
   * @return String
   */
  public function getValorAcrescimo($linha) {
    return trim(substr($this->linha[$linha], 163, 10));
  }

  /**
   * Valor de desconto da Conta
   * @param $linha
   * @return String
   */
  public function getValorDesconto($linha) {
    return trim(substr($this->linha[$linha], 173, 10));
  }

  /**
   * Valor Total da Conta
   * @param $linha
   * @return String
   */
  public function getValorTotal($linha) {
    return trim(substr($this->linha[$linha], 183, 10));
  }

  /**
   * Mes que foi efetuada a leitura
   * @param $linha
   * @return String
   */
  public function getMesLeitura($linha) {
    return trim(substr($this->linha[$linha], 193, 2));
  }

  /**
   * Situacao da Leitura
   * @param $linha
   * @return String
   */
  public function getSituacaoLeitura($linha) {
    return trim(substr($this->linha[$linha], 195, 3));
  }

  /**
   * Valor da leitura
   * @param $linha
   * @return String
   */
  public function getLeitura($linha) {
    return trim(substr($this->linha[$linha], 198, 7));
  }

  /**
   * Valor de Consumo da Leitura
   * @param $linha
   * @return String
   */
  public function getConsumo($linha) {
    return trim(substr($this->linha[$linha], 205, 8));
  }

  /**
   * Valor Excesso da Leitura
   * @param $linha
   * @return String
   */
  public function getExcesso($linha) {
    return trim(substr($this->linha[$linha], 213, 6));
  }

  /**
   * Dias da ultima leitura
   * @param $linha
   * @return String
   */
  public function getDiasLeitura($linha) {
    return trim(substr($this->linha[$linha], 219, 10));
  }

  /**
   * Se a conta foi impressa
   * @param $linha
   * @return String
   */
  public function getContaImpressa($linha) {
    return trim(substr($this->linha[$linha], 229, 1));
  }

  /**
   * Observações da Leitura
   * @param $linha
   * @return String
   */
  public function getObsLeitura($linha) {
    return trim(substr($this->linha[$linha], 230, 70));
  }

  /**
   * Codigo da Linha Digitavel
   * @param $linha
   * @return String
   */
  public function getLinhaDigitavel($linha) {
    return trim(substr($this->linha[$linha], 300, 70));
  }

  /**
   * Codigo de Barras
   * @param $linha
   * @return String
   */
  public function getCodigoBarras($linha) {
    return trim(substr($this->linha[$linha], 370, 70));
  }

  /**
   * Valor do calculo do excesso
   * @param $linha
   * @return String
   */
  public function getValorExcessoCalc($linha) {
    return trim(substr($this->linha[$linha], 440, 10));
  }

  /**
   * Se leitura foi coletada ou não (0 - Não, 1 - Sim)
   * @param $linha
   * @return String
   */
  public function getLeituraColetada($linha) {
    return trim(substr($this->linha[$linha], 450, 1));
  }

  /**
   * Se virou a contagem do hidrometro (0 - Não, 1 - Sim)
   * @param $linha
   * @return String
   */
  public function getHidrometroVirou($linha) {
    return trim(substr($this->linha[$linha], 451, 1));
  }
  
  /**
   * Leitura Real Coletada (sem adequação)
   * @param $linha
   * @return String
   */
  public function getLeituraReal($linha) {
    return trim(substr($this->linha[$linha], 452, 7));
  }
  
  /**
   * Data Real da Leitura (Sem Adequação)
   * @param $linha
   * @return String
   */
  public function getDataLeituraReal($linha) {
    return trim(substr($this->linha[$linha], 459, 10));
  }

  /**
   * Funcao que retorna a qte de registros de uma exportacao
   * @param $iCodExportacao
   * @return integer
   */
  public function comparaRegistrosArquivo($iCodExportacao) {

    require_once(modification('classes/db_aguacoletorexportadados_classe.php'));

    $clAguaColetorExportaDados = new cl_aguacoletorexportadados();

    $sSql = $clAguaColetorExportaDados->sql_query_file(null, "x50_sequencial", null,
                                                       "x50_aguacoletorexporta = $iCodExportacao");
    
    $rsAguaColetorExportaDados = $clAguaColetorExportaDados->sql_record($sSql);

    for ($i = 0; $i < $clAguaColetorExportaDados->numrows; $i++) {
       
      $oAguaColetorExportaDaDos       = db_utils::fieldsMemory($rsAguaColetorExportaDados, $i);
      $this->arrayExportacao[$i]      = $oAguaColetorExportaDaDos->x50_sequencial;
      $this->countExportaDados++;
    }

    $erro      = 1;
    $arrayDiff = null;

    if ($this->countExportaDados == $this->numLinhas) {
      
      $arrayDiff = array_diff($this->arrayArquivo, $this->arrayExportacao);
      
      if ($arrayDiff != null) {
        $erro = 0;
      }
    } else {
      $erro = 0;
    }

    if ($erro == 0) {
    
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Número e valores de registros do arquivo não conferem com os registros dessa exportação.<br/>";
      return false;
    }
  }

  public function mudaSituacaoExportacao($iCodExportacao, $iCodSituacao = 2) {
    
    require_once(modification("classes/db_aguacoletorexporta_classe.php"));

    $this->clACExporta = new cl_aguacoletorexporta();

    $this->iCodExportacao = $iCodExportacao;
    $this->iSituacao      = $iCodSituacao;

    $rsACExporta = $this->clACExporta->sql_record($this->clACExporta->sql_query_file($this->iCodExportacao));

    if ($this->clACExporta->numrows > 0) {
      
      $oACExporta = db_utils::fieldsMemory($rsACExporta, 0);

      $this->clACExporta->x49_sequencial  = $oACExporta->x49_sequencial;
      $this->clACExporta->x49_aguacoletor = $oACExporta->x49_aguacoletor;
      $this->clACExporta->x49_instit      = $oACExporta->x49_instit;
      $this->clACExporta->x49_anousu      = $oACExporta->x49_anousu;
      $this->clACExporta->x49_mesusu      = $oACExporta->x49_mesusu;
      $this->clACExporta->x49_situacao    = $this->iSituacao;
      $this->clACExporta->alterar($this->clACExporta->x49_sequencial);

      if ($this->clACExporta->erro_status == "0") {
        
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Alteração na tabela aguacoletorexporta não efetuada. Operação abortada. ";
        $this->sErroMsg   .= "ERRO:{$this->clACExporta->erro_msg}<br/>";
        
        return false;
      }

      $this->iAnoExportacao = $oACExporta->x49_anousu;
      $this->iMesExportacao = $oACExporta->x49_mesusu;
    }
  }

  public function geraSituacaoExportacao($iCodExportacao, $iUsuario, $dData, $sHora, $sMotivo, $iCodSituacao = 2) {
     
    require_once(modification("classes/db_aguacoletorexportasituacao_classe.php"));
     
    $this->clACESituacao = new cl_aguacoletorexportasituacao();
     
    $this->iCodExportacao = $iCodExportacao;
    $this->iUsuario       = $iUsuario;
    $this->dData          = $dData;
    $this->sHora          = $sHora;
    $this->sMotivo        = $sMotivo;
    $this->iSituacao      = $iCodSituacao;
     
    $this->clACESituacao->x48_aguacoletorexporta = $this->iCodExportacao;
    $this->clACESituacao->x48_usuario            = $this->iUsuario;
    $this->clACESituacao->x48_data               = $this->dData;
    $this->clACESituacao->x48_hora               = $this->sHora;
    $this->clACESituacao->x48_motivo             = $this->sMotivo;
    $this->clACESituacao->x48_situacao           = $this->iSituacao;
    $this->clACESituacao->incluir(null);
     
    if ($this->clACESituacao->erro_status == "0") {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela aguacoletorexportasituação não efetuada. Operação abortada. ";
      $this->sErroMsg   .= "ERRO:{$this->clACESituacao->erro_msg}<br/>";
      return false;
    }
  }

  public function geraDadosImportacao($iCodColetorExportaDados, $iLinhaArquivo) {
     
    require_once(modification("classes/db_aguacoletorexportadados_classe.php"));
     
    $this->iCodColetorExportaDados = $iCodColetorExportaDados;
    $this->iLinhaArquivo           = $iLinhaArquivo;
    
    $this->clACExportaDados = new cl_aguacoletorexportadados();
     
    $sSql             = $this->clACExportaDados->sql_query_file($this->iCodColetorExportaDados);
    $rsACExportaDados = $this->clACExportaDados->sql_record($sSql);
     
    if ($this->clACExportaDados->numrows > 0) {
       
      $oACExportaDados = db_utils::fieldsMemory($rsACExportaDados, 0);
       
      $this->clACExportaDados->x50_aguacoletorexporta      = $oACExportaDados->x50_aguacoletorexporta;
      $this->clACExportaDados->x50_matric                  = $oACExportaDados->x50_matric;
      $this->clACExportaDados->x50_rota                    = $oACExportaDados->x50_rota;
      $this->clACExportaDados->x50_tipo                    = $oACExportaDados->x50_tipo;
      $this->clACExportaDados->x50_codlogradouro           = $oACExportaDados->x50_codlogradouro;
      $this->clACExportaDados->x50_codbairro               = $oACExportaDados->x50_codbairro;
      $this->clACExportaDados->x50_codhidrometro           = $oACExportaDados->x50_codhidrometro;
      $this->clACExportaDados->x50_zona                    = $oACExportaDados->x50_zona;
      $this->clACExportaDados->x50_ordem                   = $oACExportaDados->x50_ordem;
      $this->clACExportaDados->x50_responsavel             = $oACExportaDados->x50_responsavel;
      $this->clACExportaDados->x50_nomelogradouro          = $oACExportaDados->x50_nomelogradouro;
      $this->clACExportaDados->x50_numero                  = $oACExportaDados->x50_numero;
      $this->clACExportaDados->x50_letra                   = $oACExportaDados->x50_letra;
      $this->clACExportaDados->x50_complemento             = $oACExportaDados->x50_complemento;
      $this->clACExportaDados->x50_nomebairro              = $oACExportaDados->x50_nomebairro;
      $this->clACExportaDados->x50_cidade                  = $oACExportaDados->x50_cidade;
      $this->clACExportaDados->x50_estado                  = $oACExportaDados->x50_estado;
      $this->clACExportaDados->x50_quadra                  = $oACExportaDados->x50_quadra;
      $this->clACExportaDados->x50_economias               = $oACExportaDados->x50_economias;
      $this->clACExportaDados->x50_categorias              = $oACExportaDados->x50_categorias;
      $this->clACExportaDados->x50_areaconstruida          = $oACExportaDados->x50_areaconstruida;
      $this->clACExportaDados->x50_nrohidro                = $oACExportaDados->x50_nrohidro;
      $this->clACExportaDados->x50_numpre                  = $oACExportaDados->x50_numpre;
      $this->clACExportaDados->x50_natureza                = $oACExportaDados->x50_natureza;

      $this->clACExportaDados->x50_dtleituraanterior       = @$oACExportaDados->x50_dtleituraanteiror;
      
      $this->clACExportaDados->x50_consumopadrao           = $oACExportaDados->x50_consumopadrao;
      $this->clACExportaDados->x50_consumomaximo           = $oACExportaDados->x50_consumomaximo;
      $this->clACExportaDados->x50_imprimeconta            = $oACExportaDados->x50_imprimeconta;

      //dados arquivo
      $this->clACExportaDados->x50_aguacoletorexportadados = $this->getCodLeitura($this->iLinhaArquivo);
      $this->clACExportaDados->x50_dtleituraatual          = $this->getDataLeituraAtual($this->iLinhaArquivo);
      $this->clACExportaDados->x50_consumo                 = $this->getConsumo($this->iLinhaArquivo);
      $this->clACExportaDados->x50_diasleitura             = $this->getDiasLeitura($this->iLinhaArquivo);
      $this->clACExportaDados->x50_mediadiaria             = $this->getMediaConsumo($this->iLinhaArquivo);
      $this->clACExportaDados->x50_vencimento              = $this->getDataVencimento($this->iLinhaArquivo);
      $this->clACExportaDados->x50_valoracrescimo          = $this->getValorAcrescimo($this->iLinhaArquivo);
      $this->clACExportaDados->x50_valordesconto           = $this->getValorDesconto($this->iLinhaArquivo);
      $this->clACExportaDados->x50_valortotal              = $this->getValorTotal($this->iLinhaArquivo);
      $this->clACExportaDados->x50_observacao              = $this->getObsLeitura($this->iLinhaArquivo);
      $this->clACExportaDados->x50_linhadigitavel          = $this->getLinhaDigitavel($this->iLinhaArquivo);
      $this->clACExportaDados->x50_codigobarras            = $this->getCodigoBarras($this->iLinhaArquivo);
      $this->clACExportaDados->x50_valor_m3_excesso        = $this->getValorExcessoCalc($this->iLinhaArquivo);
      $this->clACExportaDados->x50_leituracoletada         = $this->getLeituraColetada($this->iLinhaArquivo);
      $this->clACExportaDados->x50_contaimpressa           = $this->getContaImpressa($this->iLinhaArquivo);
      $this->clACExportaDados->incluir(null);

      if ($this->clACExportaDados->erro_status == "0") {
        
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Inclusão na tabela aguacoletorexporta não efetuada. Operação abortada. ";
        $this->sErroMsg   .= "ERRO: {$this->clACExportaDados->erro_msg}.<br/>";
        return false;
      }
      
      $this->iNumPreDados = $oACExportaDados->x50_numpre;
      
      $this->setCodBarrasDados($this->getCodigoBarras($this->iLinhaArquivo));
      $this->setLinhaDigitavelDados($this->getLinhaDigitavel($this->iLinhaArquivo)); 
    }
  }

  public function getLeituraExportacao($iCodColetorExportaDados){
    
    require_once(modification("classes/db_aguacoletorexportadadosleitura_classe.php"));
    
    $this->clACExportaDadosLeitura = new cl_aguacoletorexportadadosleitura();
    $this->iCodColetorExportaDados = $iCodColetorExportaDados;
     
    $sSql = $this->clACExportaDadosLeitura->sql_query_file(null, "x51_agualeitura", null,
                                                           "x51_aguacoletorexportadados = $this->iCodColetorExportaDados");
    $rsACExportaDadosLeitura = $this->clACExportaDadosLeitura->sql_record($sSql);
     
    if ($this->clACExportaDadosLeitura->numrows > 0) {
      
      $oACExportaDadosLeitura = db_utils::fieldsMemory($rsACExportaDadosLeitura, 0);
      
      return $oACExportaDadosLeitura->x51_agualeitura;
    }
  }

  public function alteraLeitura($iCodLeitura) {
    
    require_once(modification("classes/db_agualeitura_classe.php"));
    
    $this->clAguaLeitura = new cl_agualeitura();
    $this->iCodLeitura   = $iCodLeitura;
    
    $rsAguaLeitura = $this->clAguaLeitura->sql_record($this->clAguaLeitura->sql_query_file($this->iCodLeitura));
     
    if ($this->clAguaLeitura->numrows > 0) {

      $oAguaLeitura = db_utils::fieldsMemory($rsAguaLeitura, 0);

      $this->clAguaLeitura->x21_codleitura    = $oAguaLeitura->x21_codleitura;
      $this->clAguaLeitura->x21_codhidrometro = $oAguaLeitura->x21_codhidrometro;
      $this->clAguaLeitura->x21_exerc         = $oAguaLeitura->x21_exerc;
      $this->clAguaLeitura->x21_mes           = $oAguaLeitura->x21_mes;

      $this->clAguaLeitura->x21_situacao  = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->getSituacaoLeitura($this->iLinhaArquivo)  : $oAguaLeitura->x21_situacao;
      $this->clAguaLeitura->x21_numcgm    = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->getCodLeiturista($this->iLinhaArquivo)    : $oAguaLeitura->x21_numcgm;
      $this->clAguaLeitura->x21_dtleitura = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->getDataLeituraAtual($this->iLinhaArquivo) : $oAguaLeitura->x21_dtleitura;
      $this->clAguaLeitura->x21_usuario   = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->iUsuario                                  : $oAguaLeitura->x21_usuario;
      $this->clAguaLeitura->x21_dtinc     = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->dData                                     : $oAguaLeitura->x21_dtinc;
      $this->clAguaLeitura->x21_leitura   = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->getLeitura($this->iLinhaArquivo)          : $oAguaLeitura->x21_leitura;
      
      //consumo = consumo total - excesso
      if (trim($this->getExcesso($this->iLinhaArquivo)) > 0) {
        $iConsumo = trim($this->getConsumo($this->iLinhaArquivo)) - trim($this->getExcesso($this->iLinhaArquivo));
      } else {
        $iConsumo = $this->getConsumo($this->iLinhaArquivo);
      } 
      
      $this->clAguaLeitura->x21_consumo  = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $iConsumo : $oAguaLeitura->x21_consumo;
      $this->clAguaLeitura->x21_excesso  = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? $this->getExcesso($this->iLinhaArquivo) : $oAguaLeitura->x21_excesso;
      $this->clAguaLeitura->x21_virou    = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? (($this->getHidrometroVirou($this->iLinhaArquivo) == "1") ? "true" : "false") : $oAguaLeitura->x21_virou;

      $this->clAguaLeitura->x21_tipo     = 3;
      $this->clAguaLeitura->x21_status   = ($this->getLeituraColetada($this->iLinhaArquivo) == "1") ? 1 : 3;
      
      if ($this->clAguaLeitura->x21_status == "3") {
        
        $oColetorExportaDadosLeitura = new cl_aguacoletorexportadadosleitura();
        $oColetorExportaDadosLeitura->excluir('', 'x51_agualeitura = ' . $iCodLeitura);
        
        if ($oColetorExportaDadosLeitura->erro_status == "0") {
        
          $this->iErroStatus = 0;
          $this->sErroMsg    = "Inclusão na tabela aguacoletorexportadadosleitura não efetuada. Operação abortada. ";
          $this->sErroMsg   .= "ERRO: {$oColetorExportaDadosLeitura->erro_msg}<br/>";
          return false;
          
        } else {
          
          $this->clAguaLeitura->excluir($this->clAguaLeitura->x21_codleitura);
          
        }

      } else {
        
        $this->clAguaLeitura->alterar($this->clAguaLeitura->x21_codleitura);
        
      } 
      
      if ($this->clAguaLeitura->erro_status == "0") {
        
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Alteração na tabela agualeitura não informada. Operação abortada. ";
        $this->sErroMsg   .= "ERRO: {$this->clAguaLeitura->erro_msg}.<br/>";
        
        return false;
      }
    }
  }

  public function cancelaLeitura($iCodLeitura) {
    
    require_once(modification("classes/db_agualeituracancela_classe.php"));
    
    $this->clAguaLeituraCancela = new cl_agualeituracancela();
     
    $this->iCodLeitura = $iCodLeitura;
     
    $this->clAguaLeituraCancela->x47_agualeitura = $this->iCodLeitura;
    $this->clAguaLeituraCancela->x47_usuario     = $this->iUsuario;
    $this->clAguaLeituraCancela->x47_data        = $this->dData;
    $this->clAguaLeituraCancela->x47_hora        = $this->sHora;
    $this->clAguaLeituraCancela->x47_motivo      = "Leitura cancelada automaticamente. Não foi coletada nenhuma \'Leitura\' pelo coletor.";
    $this->clAguaLeituraCancela->incluir(null);
     
    if ($this->clAguaLeituraCancela->erro_status == "0") {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusão na tabela agualeituracancela não efetuada. Operação Abortada. ";
      $this->sErroMsg   .= "ERRO: {$this->clAguaLeituraCancela->erro_msg}<br/>";
      return false;
    }
  }

  public function geraOperacaoExcesso($iCodConfExcesso, $iCodRecExcesso) {
    
    require_once(modification("classes/db_aguacalc_classe.php"));
    require_once(modification("classes/db_aguacalcval_classe.php"));
     
    $this->clAguaCalc      = new cl_aguacalc();
    $this->clAguaCalcVal   = new cl_aguacalcval();
     
    $this->iCodConfExcesso = $iCodConfExcesso;
    $this->iCodRecExcesso  = $iCodRecExcesso;
     
    $sSql = $this->clAguaCalc->sql_query_file(null, "x22_codcalc, x22_numpre", null,
                                              "    x22_exerc  = {$this->iAnoExportacao} " .
                                              "and x22_mes    = {$this->iMesExportacao} " .
                                              "and x22_matric = {$this->getCodMatricula($this->iLinhaArquivo)}");
    
    $rsAguaCalc   = $this->clAguaCalc->sql_record();
     
    if ($this->clAguaCalc->numrows > 0) {
       
      $oAguaCalc = db_utils::fieldsMemory($rsAguaCalc, 0);
       
      $this->atualizaAguaCalc($oAguaCalc->x22_codcalc);
       
      $this->clAguaCalcVal->excluir($oAguaCalc->x22_codcalc, $this->iCodConfExcesso);
       
      $this->clAguaCalcVal->x23_codcalc        = $oAguaCalc->x22_codcalc;
      $this->clAguaCalcVal->x23_codconsumotipo = $this->iCodConfExcesso;
      $this->clAguaCalcVal->x23_valor          = $this->getValorExcessoCalc($this->iLinhaArquivo);
      $this->clAguaCalcVal->incluir($this->clAguaCalcVal->x23_codcalc, $this->clAguaCalcVal->x23_codconsumotipo);
       
      if ($this->clAguaCalcVal->erro_status == "0") {
        
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Inclusão na tabela aguacalcval não efetuada. Operação abortada. ";
        $this->sErroMsg   .= "ERRO: {$this->clAguaCalcVal->erro_msg}<br/>";
        return false;
      }
       
      $this->geraCalculoGeral($oAguaCalc->x22_numpre);
    }
  }

  public function geraCalculoGeral($iNumpre) {
     
    $this->iNumpre = $iNumpre;
     
    $sPLCalculoGeral = "
    select fc_agua_calculogerafinanceiro({$this->iAnoExportacao}, 
    {$this->iMesExportacao},
    {$this->getCodMatricula($this->iLinhaArquivo)},
    {$this->iNumpre},
    {$this->iNumpre},
    {$this->getCodLeiturista($this->iLinhaArquivo)},
    {$this->iCodRecExcesso},
    {$this->iMesExportacao},
    {$this->getValorExcessoCalc($this->iLinhaArquivo)})";

    if (!db_query($sPLCalculoGeral)) {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Erro ao executar a procedure 'fc_agua_calculogerafinanceiro'. Operação abortada.<br/>";
      return false;
    }
  }
  
  public function geraReciboPaga($iCodColetorExportaDados) {
    
    require_once(modification("classes/db_aguacoletorexportadados_classe.php"));
    require_once(modification("classes/db_recibopaga_classe.php"));
    
    $this->clACExportaDados   = new cl_aguacoletorexportadados();
    $this->clReciboPaga       = new cl_recibopaga();
    
    $this->iCodColetorExportaDados = $iCodColetorExportaDados;
    
    $sSql             = $this->clACExportaDados->getSqlArrecadRecibo($this->iCodColetorExportaDados);
    $rsACExportaDados = $this->clACExportaDados->sql_record($sSql);
    
    if ($this->clACExportaDados->numrows > 0) {
      
      for ($i = 0; $i < $this->clACExportaDados->numrows; $i++) {
      
        $oACExportaDados = db_utils::fieldsMemory($rsACExportaDados, 0);
        
        $this->clReciboPaga->k00_numcgm   = $oACExportaDados->k00_numcgm;
        $this->clReciboPaga->k00_dtoper   = $oACExportaDados->k00_dtoper;
        $this->clReciboPaga->k00_receit   = $oACExportaDados->k00_receit;
        $this->clReciboPaga->k00_hist     = $oACExportaDados->k00_hist;
        $this->clReciboPaga->k00_valor    = $oACExportaDados->k00_valor;
        $this->clReciboPaga->k00_dtvenc   = $oACExportaDados->k00_dtvenc;
        $this->clReciboPaga->k00_numpre   = $oACExportaDados->k00_numpre;
        $this->clReciboPaga->k00_numpar   = $oACExportaDados->k00_numpar;
        $this->clReciboPaga->k00_numtot   = $oACExportaDados->k00_numtot;
        $this->clReciboPaga->k00_numdig   = $oACExportaDados->k00_numdig;
        $this->clReciboPaga->k00_conta    = $oACExportaDados->k00_conta;
        $this->clReciboPaga->k00_dtpaga   = $oACExportaDados->k00_dtpaga;
        $this->clReciboPaga->k00_numnov   = $oACExportaDados->k00_numnov;
        $this->clReciboPaga->incluir(null);
        
        if ($this->clReciboPaga->erro_status == "0") {
          
          $this->iErroStatus = 0;
          $this->sErroMsg    = "Inclusão na tabela recibopaga não efetuada. Operação Aboratada. ";
          $this->sErroMsg   .= "ERRO:{$this->clReciboPaga->erro_msg}";
          
          return false;
        } 
      } 
    }  
  }
  
  /**
   * Método para efetuar a inclusão de ocorrencia para adequação de leitura quando importada
   * pelo Coletor de Dados.
   * 
   * @param Integer $iMatricula        - Matricula da Leitura que está sendo adequada
   * @param Date    $dtDataLeitura     - Data da Leitura Adequada
   * @param Integer $iLeitura          - Valor da Leitura Adequada
   * @param Date    $dtDataLeturaReal  - Data Real da Coleta da leitura
   * @param Integer $iLeituraReal      - Valor Real da Leitura Coletada
   * 
   * @return boolean                   - Retorno de Erro ou sucesso
   */
  public function lancarOcorrencia ($iMatricula, $dtDataLeitura, $iLeitura, $dtDataLeturaReal, $iLeituraReal) {
      
    require_once(modification("classes/db_histocorrencia_classe.php"));
    require_once(modification("classes/db_histocorrenciamatric_classe.php"));
    
    $clhistocorrencia = new cl_histocorrencia;
    
    $dtDataLeturaReal = new DBDate($dtDataLeturaReal);
    $dtDataLeitura    = new DBDate($dtDataLeitura);
    
    $sOcorrencia  = "Ajustada leitura de {$iLeituraReal} ";
    $sOcorrencia .= "com data de {$dtDataLeturaReal->getDate('d/m/Y')} para ";
    $sOcorrencia .= "{$iLeitura} e data {$dtDataLeitura->getDate('d/m/Y')}";
    
    $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
    $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
    $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
    $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
    $clhistocorrencia->ar23_descricao    = "Adequação Leitura Maior 30 Dias";
    $clhistocorrencia->ar23_ocorrencia   = $sOcorrencia;
    $clhistocorrencia->ar23_tipo         = 2;
    $clhistocorrencia->ar23_hora         = date("H:i");
    $clhistocorrencia->ar23_data         = date("d")."/".date("m")."/".date("Y");
    
    $clhistocorrencia->incluir(null);
    
    if ( $clhistocorrencia->erro_status == 0 ) {
      
      $this->sErroMsg  = "Inclusao de ocorrencia não efetuada. Operação abortada. ";
      $this->sErroMsg .= "ERRO 01: {$clhistocorrencia->erro_msg}<br/>";
      return false;
    }
      
    $clhistocorrenciamatric = new cl_histocorrenciamatric;
    
    $clhistocorrenciamatric->ar25_matric         = $iMatricula;
    $clhistocorrenciamatric->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;
    
    $clhistocorrenciamatric->incluir(null);
    
    if ( $clhistocorrenciamatric->erro_status == 0 ) {
       
      $this->sErroMsg  = "Inclusao de Ocorrência não efetuada. Operação abortada. ";
      $this->sErroMsg .= "ERRO 02: {$clhistocorrencia->erro_msg}<br/>";
      return false;
    }  
    
    return true;
  }
  
  public function geraReciboWeb($iCodColetorExportaDados) {
    
    require_once(modification("classes/db_aguacoletorexportadadosreceita_classe.php"));
    require_once(modification("classes/db_db_reciboweb_classe.php"));
    
    $this->clACEDadosReceita = new cl_aguacoletorexportadadosreceita();
    $this->clDBReciboWeb     = new cl_db_reciboweb();
    
    $this->iCodColetorExportaDados = $iCodColetorExportaDados;
    
    $sCampos  = "distinct                     "; 
    $sCampos .= "x50_numpre  as k99_numpre_n, ";
    $sCampos .= "x52_numpre  as k99_numpre,   ";
    $sCampos .= "x52_numpar  as k99_numpar,   ";
    $sCampos .= "0::integer  as k99_codbco,   ";
    $sCampos .= "0::integer  as k99_codage,   ";
    $sCampos .= "0::integer  as k99_numbco,   ";
    $sCampos .= "0::integer  as k99_desconto, ";
    $sCampos .= "11::integer as k99_tipo,     ";
    $sCampos .= "5::integer  as k99_origem    ";
    
    $sSql = $this->clACEDadosReceita->sql_query_dados(null, $sCampos, null,
                                                      "x50_sequencial = $this->iCodColetorExportaDados");
    
    $rsACEDadosReceita = $this->clACEDadosReceita->sql_record($sSql);
    
    if ($this->clACEDadosReceita->numrows > 0) {
      
      for ($i = 0; $i < $this->clACEDadosReceita->numrows; $i++) {
      
        $oACEDadosReceita = db_utils::fieldsMemory($rsACEDadosReceita, $i);
        
        $this->clDBReciboWeb->k99_numpre    = $oACEDadosReceita->k99_numpre;
        $this->clDBReciboWeb->k99_numpar    = $oACEDadosReceita->k99_numpar;
        $this->clDBReciboWeb->k99_numpre_n  = $oACEDadosReceita->k99_numpre_n;
        $this->clDBReciboWeb->k99_codbco    = $oACEDadosReceita->k99_codbco;
        $this->clDBReciboWeb->k99_codage    = $oACEDadosReceita->k99_codage;
        $this->clDBReciboWeb->k99_numbco    = $oACEDadosReceita->k99_numbco;
        $this->clDBReciboWeb->k99_desconto  = $oACEDadosReceita->k99_desconto;
        $this->clDBReciboWeb->k99_tipo      = $oACEDadosReceita->k99_tipo;
        $this->clDBReciboWeb->k99_origem    = $oACEDadosReceita->k99_origem;
        $this->clDBReciboWeb->incluir($oACEDadosReceita->k99_numpre,
                                      $oACEDadosReceita->k99_numpar,
                                      $oACEDadosReceita->k99_numpre_n);
        
        if ($this->clDBReciboWeb->erro_status == "0") {
          
          $this->iErroStatus = 0;
          $this->sErroMsg    = "Inclusao na tabela db_reciboweb não efetuada. Operação abortada. ";
          $this->sErroMsg   .= "ERRO: {$this->clDBReciboWeb->erro_msg}<br/>";
          
          return false;
        }
      }
    }
  }

  public function geraReciboCodBar() {
    
    require_once(modification("classes/db_recibocodbar_classe.php"));
    
    $this->clReciboCodBar = new cl_recibocodbar();
    
    $this->clReciboCodBar->k00_numpre         = $this->iNumPreDados;
    $this->clReciboCodBar->k00_codbar         = $this->iCodBarrasDados;
    $this->clReciboCodBar->k00_linhadigitavel = $this->iLinhaDigitavelDados;
    $this->clReciboCodBar->incluir($this->iNumPreDados);
    
    if ($this->clReciboCodBar->erro_status == "0") {
      
      $this->iErroStatus = 0;
      $this->sErroMsg    = "Inclusao na tabela recibocodbar não efetuada. Operação abortada. ";
      $this->sErroMsg    = "ERRO: {$this->clReciboCodBar->erro_msg}<br/>";
      
      return false;
    }
  }
  
  public function atualizaAguaCalc($iCodCalc) {
     
    $this->iCodCalc = $iCodCalc;
    $rsAguaCalc     = $this->clAguaCalc->sql_record($this->clAguaCalc->sql_query_file($this->iCodCalc));
     
    if ($this->clAguaCalc->numrows > 0) {
       
      $oAguaCalc = db_utils::fieldsMemory($rsAguaCalc, 0);
       
      $this->clAguaCalc->x22_codcalc     = $oAguaCalc->x22_codcalc;
      $this->clAguaCalc->x22_codconsumo  = $oAguaCalc->x22_codconsumo;
      $this->clAguaCalc->x22_exerc       = $oAguaCalc->x22_exerc;
      $this->clAguaCalc->x22_mes         = $oAguaCalc->x22_mes;
      $this->clAguaCalc->x22_matric      = $oAguaCalc->x22_matric;
      $this->clAguaCalc->x22_area        = $oAguaCalc->x22_area;
      $this->clAguaCalc->x22_numpre      = $oAguaCalc->x22_numpre;
      $this->clAguaCalc->x22_manual      = $oAguaCalc->x22_manual;
      $this->clAguaCalc->x22_tipo        = 3;
      $this->clAguaCalc->x22_data        = date("Y-m-d");
      $this->clAguaCalc->x22_hora        = date("H:i");
      $this->clAguaCalc->x22_usuario     = db_getsession('DB_id_usuario');
      $this->clAguaCalc->alterar($this->clAguaCalc->x22_codcalc);
       
      if ($this->clAguaCalc->erro_status == "0") {
         
        $this->iErroStatus = 0;
        $this->sErroMsg    = "Alteração na tabela aguacalc não efetuada. Operação abortada. ";
        $this->sErroMsg   .= "ERRO:{$this->clAguaCalc->erro_msg}";
        return false;
      }
    }
  }
  
  public function getCodReceita($iCodConfExcesso) {
    
    require_once(modification("classes/db_aguaconsumotipo_classe.php"));
     
    $this->iCodConfExcesso   = $iCodConfExcesso;
    $this->clAguaConsumoTipo = new cl_aguaconsumotipo();
     
    $sSql              = $this->clAguaConsumoTipo->sql_query_file($this->iCodConfExcesso);
    $rsAguaConsumoTipo = $this->clAguaConsumoTipo->sql_record($sSql);
     
    if ($this->clAguaConsumoTipo->numrows > 0) {
       
      $oAguaConsumoTipo = db_utils::fieldsMemory($rsAguaConsumoTipo, 0);
      return $oAguaConsumoTipo->x25_receit; 
    }
  }

  public function getConfExcesso($iAno) {
     
    $this->iAno = $iAno;

    $this->sqlAguaConf = "select x18_consumoexcesso from aguaconf where x18_anousu = $this->iAno";

    $oConsumoExcesso = db_utils::fieldsMemory(db_query($this->sqlAguaConf) , 0);

    if ($oConsumoExcesso->x18_consumoexcesso == "") {
      $this->getConfExcesso($this->iAno - 1);
    } else {
      return $oConsumoExcesso->x18_consumoexcesso;
    }
  }
}