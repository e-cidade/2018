<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Classe que manipula modelos de arquivos .TXT. Esta classe permite utilizaçãoo de variáveis
 * em arquivos .TXT, que são interpretadas e possuem seus valores substituídos pelos valores
 * vindos do banco de dados. Para utilizá-la é necessário informar o modelo TXT (arquivo com
 * as variáveis e o SQL que traz os dados a serem substituídos pelas variáveis encontradas no
 * arquivo.
 * OBS: Esta classe depende da db_utils.
 * @author Tony Farney Bruck Mendes Ribeiro
 */

  class DBModeloArquivoTexto {

    /**
     * String com o conteúdo do arquivo TXT modelo.
     * @var string $sArquivoModelo
     */
    private $sArquivoModelo = '';
   
    /**
     * Array com SQL que obterá os dados para serem substituídos pelas variáveis do arquivo TXT modelo.
     * Cada posição contém o SQL para gerar uma parte do arquivo. A posição 0 contém o SQL com os dados gerais,
     * Ou seja, com os dados que não irão se repetir. Depois, cada posição contém o SQL para cada trecho dinâmico,
     * contido entre <n> </n> no arquivo modelo, onde n é um número inteiro. Deve haver correspondência entre n
     * e o índice do vetor que contém o SQL para o treco n.
     * @var string $sSql
     */
    private $aSql = array();

    /**
     * Variável que recebe o arquivo gerado (string).
     * @var string $sArquivo
     */
    private $sArquivoGerado = '';

    /**
     * Variável que possui o array com as linhas retornadas pelo método executaSql() (última execução do método). 
     * Cada posição do array possui um objeto cujo atributos possuem os nomes dos campos buscados na query.
     * @var array(Object) $iNumLinhasSql
     */
    private $aDadosSql = array();

    /**
     * Variável que possui o número da linha que está sendo atualmente processada de $aDadosSql.
     * Esta variável somente é utilizda na função de callback substituiVariavel().
     * @var integer $iLinhaAtual
     */
    private $iLinhaAtual = 0;

   /**
    * Método construtor da classe.
    * @param string $sCaminhoArquivoModelo Caminho para o arquivo modelo.
    * @param array(string) $aSql SQLs para processar o modelo. Na posição 0 esperasse o SQL da parte
    * estática e nas demais, dos trechos dinâmicos.
    */
   function  __construct($sCaminhoArquivoModelo, $aSql = array()) {
      
      if (empty($sCaminhoArquivoModelo)) {
        throw new Exception('O arquivo modelo deve ser informado obrigatoriamente.');
      }

      if (!file_exists($sCaminhoArquivoModelo)) {
        throw new Exception("'$sCaminhoArquivoModelo' não existe.'");
      }

      if (!is_file($sCaminhoArquivoModelo)) {
        throw new Exception("'$sCaminhoArquivoModelo' não é um arquivo.");
      }

      if (!is_readable($sCaminhoArquivoModelo)) {
        throw new Exception("'$sCaminhoArquivoModelo' não tem permissão de leitura.'");
      }

      $this->sArquivoModelo = file_get_contents($sCaminhoArquivoModelo);
      if ($this->sArquivoModelo === false) {
        throw new Exception("Não foi possível ler o conteúdo de '$sCaminhoArquivoModelo'.");
      }
      
      if (!empty($aSql)) {
        
        if (!is_array($aSql)) {
          $this->aSql = array($aSql);
        } else {
          $this->aSql = $aSql;
        }

      }

    }
    
    /**
     * Método que seta o array de SQLs necessários para processamento do modelo de arquivo texto.
     * @param array(string) $aSql SQLs para processar o modelo. Na posição 0 esperasse o SQL da parte
     * estática e nas demais, dos trechos dinâmicos.
     */
    public function setSql($aSql) {

      if (!is_array($aSql)) {
        $this->aSql = array($aSql);
      } else {
        $this->aSql = $aSql;
      }

    }
    
    /**
     * Método que retorna o arquivo gerado (string que compõe o arquivo)
     * @return string Arquivo gerado. Se o arquivo ainda não foi gerado, retorna vazio.
     */
    public function getArquivo() {
      return $this->sArquivoGerado;
    }
    
    /**
     * Método que executa um comando SQL (query), atualizando o array de dados $aDadosSql.
     * @param $sSql SQL a ser executado.
     */
    private function executaSql($sSql) {

      if (($rs = pg_query($sSql)) !== false) {	
        $this->aDadosSql = db_utils::getColectionByRecord($rs);
      } else {
        $this->aDadosSql = array();
      }

    }
    
    /**
     * Função muito específica, utilizada como callback para preg_replace_callback. Esta função utiliza
     * o array com os dados retornados pela consunta ($aDadosSql), e o número da linha atualmente
     * sendo processada ($iLinhaAtual)
     * @param array(string) $aPadroes Array com os padrões referentes a variáveis encontrados pela
     * função preg_replace_callback(). Neste vetor, a primeira posição possui a definição
     * completa da variável. Ex: ${NOME_VAR}{30}+. Na segunda posição espera-se apenas 
     * o nome da variável. Ex: NOME_VAR. Na terceira posição (se existir), possuirá o tamanho
     * completo. Ex: {30}+. A quarta posição deve conter o número que identifica
     * o tamanho máximo do campo. Ex: 30 A quinta posição (se existir) possui o 
     * modificador (+, -, =) que significa que deve ser feito padding para completar o tamanho
     * definido na quarta posição, ou pode estar vazia.
     * @return string Valor processado para a variável passada.
     */

    private function substituiVariavel($aPadroes) {
      
      $sCampo = strtolower($aPadroes[1]);
      $sTroca = isset($this->aDadosSql[$this->iLinhaAtual]->$sCampo) ? 
                  $this->aDadosSql[$this->iLinhaAtual]->$sCampo : '';
      if (count($aPadroes) > 2) { // Tem que ter pelo menos 4 posicoes
        
        /* Número máximo de caracteres definido */
        $sTroca = substr($sTroca, 0, $aPadroes[3]);
        
        if (count($aPadroes) >  4) { 
        
          /* Verifico o tipo de padding que foi definido para fazer */
          if ($aPadroes[4] == '+') { // Padding no final
            $sTroca = str_pad($sTroca, $aPadroes[3], ' ', STR_PAD_RIGHT);
          } elseif ($aPadroes[4] == '-') {
            $sTroca = str_pad($sTroca, $aPadroes[3], ' ', STR_PAD_LEFT);
          } elseif ($aPadroes[4] == '=') {
            $sTroca = str_pad($sTroca, $aPadroes[3], ' ', STR_PAD_BOTH);
          }

        }

      }

      return $sTroca;

    }

    /**
     * Método que gera o arquivo TXT a partir do modelo e das querys passados.
     * Após gerar o arquivo, este método seta o atributo $sArquivoGerado, com
     * o resultado do processamento.
     */
    public function gerarArquivo() {

      $sArqGerado            = $this->sArquivoModelo;
      
      $sPadraoVariavel       = '/\$\{([a-zA-Z0-9_]+)\}(\{([0-9]+)\}(\+|-|=)?)?/';
      $sPadraoAtrib          = ' +[a-z]+| +[a-z]+=[a-zA-Z0-9]+';
      $sPadraoTrechoDinamico = "/<([0-9])(($sPadraoAtrib)*) *>((.|\n)*)<\/\\1>/";

      /* Os dois arrays abaixo possuem os índices de acordo com a numeração dos trechos dinâmicos no modelo. */
      $aTrechosDinamicos = array(); // Vai conter todos os textos de trechos dinâmicos exatamente como estão no modelo
      $aTextosDinamicos  = array(); // Vai conter todos os textos dinâmicos já processados

      $aPadroes          = array();
      preg_match_all($sPadraoTrechoDinamico, $sArqGerado, $aPadroes);
      
      /* Obtenho todos os textos dos trechos dinâmicos, trocando pelas tags de abertura e fechamento (<n></n>) */
      $iTam = count($aPadroes[0]); // $aPadroes[0] sempre vai existir, mesmo que não encontre trechos dinâmicos
      for ($iCont = 0; $iCont < $iTam; $iCont++) {

        $sArqGerado = str_replace($aPadroes[0][$iCont], 
                                  '<'.$aPadroes[1][$iCont].'></'.
                                  $aPadroes[1][$iCont].'>', 
                                  $sArqGerado
                                 );
        /* Atributos doas trechos dinamicos
         * Cria um objeto para cada trecho dinamico com o valor de cada atributo.
         */
        
        $oAtributos  = new stdclass();
        $oAtributos->sTexto     = $aPadroes[4][$iCont];
        $oAtributos->iLimite    = null;
        $oAtributos->lPreencher = false;
        
        //Verifica os atributos passados na tag
        $aAtributoSep   = explode(" ",trim($aPadroes[2][$iCont]));
        $iCountAtibutos = count($aAtributoSep);
        for ($iInd = 0; $iInd < $iCountAtibutos; $iInd++) {

          $aAtributoNomeVal = explode("=",$aAtributoSep[$iInd]);
          if ($aAtributoNomeVal[0] == 'limite') {
            if (isset($aAtributoNomeVal[1]) && $aAtributoNomeVal[1] != null) {
              $oAtributos->iLimite = $aAtributoNomeVal[1];
            }
          } elseif ($aAtributoNomeVal[0] == 'preencher') {
            $oAtributos->lPreencher = true;
          }

        }
        $aTrechosDinamicos[(int)$aPadroes[1][$iCont]] = $oAtributos;

      }
      /* Substituo as variáveis pelos seus valores vindos do banco após executar o SQL da posição 0, que é
         global para o arquivo (mas suas variáveis não são vistas dentro de trechos dinâmicos).
      */
      if (isset($this->aSql[0])) { // Não obrigo existir um sql. Pode ser que se deseje imprimir o modelo sem variáveis

        $this->executaSql($this->aSql[0]);
        $this->iLinhaAtual = 0;

      }
      $sArqGerado = preg_replace_callback($sPadraoVariavel, array(&$this, 'substituiVariavel'), $sArqGerado);
      
      /* Processo cada trecho dinâmico, armazenando o texto gerado no array $aTextosDinamicos */
      foreach ($aTrechosDinamicos as $iInd => $oAtributos) {

        if (!isset($this->aSql[$iInd])) {
          throw new Exception("Trecho dinâmico '$iInd' não possui SQL associado. Não é possível gerar o arquivo.");
        }

        $aTextosDinamicos[$iInd] = '';
        $this->executaSql($this->aSql[$iInd]);
        $iLinhas = count($this->aDadosSql);
        if ($oAtributos->iLimite != null) {
          if ($iLinhas > $oAtributos->iLimite) {
            $iLinhas = $oAtributos->iLimite;
          }
        }
        for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
          
          $this->iLinhaAtual        = $iCont;
          $aTextosDinamicos[$iInd] .= preg_replace_callback($sPadraoVariavel, 
                                                            array(&$this, 'substituiVariavel'),
                                                            $oAtributos->sTexto
                                                           );
          

        }

        if ($oAtributos->iLimite != null && $oAtributos->lPreencher == true) {

          $this->iLinhaAtual = $iCont;
          for ($iInd2=0; $iInd2 < $oAtributos->iLimite-$iLinhas; $iInd2++) {
            $aTextosDinamicos[$iInd] .= preg_replace_callback($sPadraoVariavel, 
                                                            array(&$this, 'substituiVariavel'),
                                                            $oAtributos->sTexto
                                                           );
          }

        }

      } // End foreach
      

      /* Coloco cada trecho dinâmico já processado em seu devido lugar no arquivo */
      foreach ($aTextosDinamicos as $iInd => $sTexto) {
        
        $sArqGerado = str_replace('<'.$iInd.'></'.$iInd.'>', $sTexto, $sArqGerado);

      }

      /* Substitui as tag's fixas */
      $sArqGerado = str_replace('<condensar>', chr(15), $sArqGerado);
      $sArqGerado = str_replace('</condensar>', chr(18), $sArqGerado);
      $sArqGerado = str_replace('<fim>', chr(12), $sArqGerado);

      $this->sArquivoGerado = $sArqGerado;

    }
    
    /**
     * Método que disponibiliza o arquivo gerado para download em um diálogo
     * do navegador. Para isso, ele envia os headers necessários.
     * @param string $sNomeDownload Nome que o arquivo para download irá possuir. Se não for
     * passado nenhum nome, assume-se por default o nome 'arquivo.txt'
     */
    public function downloadArquivo($sNomeDownload = 'arquivo.txt') {
      
      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="'.$sNomeDownload.'"');
      header('Content-Transfer-Encoding: binary');
      echo $this->getArquivo();
      
    }
    
    /**
     * Método que salva em disco o arquivo gerado.
     * @param string Caminho para salvar o arquivo.
     */
    public function salvarArquivo($sDestino) {

      if (empty($sDestino)) {
        throw new Exception('O caminho para salvar o arquivo deve ser informado obrigatoriamente.');
      }

      $pArquivoDestino = fopen($sDestino, 'w');
      if (!$pArquivoDestino) {
        throw new Exception("Não foi possível salvar o arquivo em '$sDestino'. Verique se o caminho está correto.");
      }
      
      $lRetorno = fwrite($pArquivoDestino, $this->getArquivo());
      fclose($pArquivoDestino);
      if ($lRetorno === false) {
        throw new Exception("Não foi possível salvar o arquivo em '$sDestino'. Erro ao escrever no arquivo.");
      }

    }

  }