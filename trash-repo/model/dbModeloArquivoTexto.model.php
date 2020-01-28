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
 * Classe que manipula modelos de arquivos .TXT. Esta classe permite utiliza��oo de vari�veis
 * em arquivos .TXT, que s�o interpretadas e possuem seus valores substitu�dos pelos valores
 * vindos do banco de dados. Para utiliz�-la � necess�rio informar o modelo TXT (arquivo com
 * as vari�veis e o SQL que traz os dados a serem substitu�dos pelas vari�veis encontradas no
 * arquivo.
 * OBS: Esta classe depende da db_utils.
 * @author Tony Farney Bruck Mendes Ribeiro
 */

  class DBModeloArquivoTexto {

    /**
     * String com o conte�do do arquivo TXT modelo.
     * @var string $sArquivoModelo
     */
    private $sArquivoModelo = '';
   
    /**
     * Array com SQL que obter� os dados para serem substitu�dos pelas vari�veis do arquivo TXT modelo.
     * Cada posi��o cont�m o SQL para gerar uma parte do arquivo. A posi��o 0 cont�m o SQL com os dados gerais,
     * Ou seja, com os dados que n�o ir�o se repetir. Depois, cada posi��o cont�m o SQL para cada trecho din�mico,
     * contido entre <n> </n> no arquivo modelo, onde n � um n�mero inteiro. Deve haver correspond�ncia entre n
     * e o �ndice do vetor que cont�m o SQL para o treco n.
     * @var string $sSql
     */
    private $aSql = array();

    /**
     * Vari�vel que recebe o arquivo gerado (string).
     * @var string $sArquivo
     */
    private $sArquivoGerado = '';

    /**
     * Vari�vel que possui o array com as linhas retornadas pelo m�todo executaSql() (�ltima execu��o do m�todo). 
     * Cada posi��o do array possui um objeto cujo atributos possuem os nomes dos campos buscados na query.
     * @var array(Object) $iNumLinhasSql
     */
    private $aDadosSql = array();

    /**
     * Vari�vel que possui o n�mero da linha que est� sendo atualmente processada de $aDadosSql.
     * Esta vari�vel somente � utilizda na fun��o de callback substituiVariavel().
     * @var integer $iLinhaAtual
     */
    private $iLinhaAtual = 0;

   /**
    * M�todo construtor da classe.
    * @param string $sCaminhoArquivoModelo Caminho para o arquivo modelo.
    * @param array(string) $aSql SQLs para processar o modelo. Na posi��o 0 esperasse o SQL da parte
    * est�tica e nas demais, dos trechos din�micos.
    */
   function  __construct($sCaminhoArquivoModelo, $aSql = array()) {
      
      if (empty($sCaminhoArquivoModelo)) {
        throw new Exception('O arquivo modelo deve ser informado obrigatoriamente.');
      }

      if (!file_exists($sCaminhoArquivoModelo)) {
        throw new Exception("'$sCaminhoArquivoModelo' n�o existe.'");
      }

      if (!is_file($sCaminhoArquivoModelo)) {
        throw new Exception("'$sCaminhoArquivoModelo' n�o � um arquivo.");
      }

      if (!is_readable($sCaminhoArquivoModelo)) {
        throw new Exception("'$sCaminhoArquivoModelo' n�o tem permiss�o de leitura.'");
      }

      $this->sArquivoModelo = file_get_contents($sCaminhoArquivoModelo);
      if ($this->sArquivoModelo === false) {
        throw new Exception("N�o foi poss�vel ler o conte�do de '$sCaminhoArquivoModelo'.");
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
     * M�todo que seta o array de SQLs necess�rios para processamento do modelo de arquivo texto.
     * @param array(string) $aSql SQLs para processar o modelo. Na posi��o 0 esperasse o SQL da parte
     * est�tica e nas demais, dos trechos din�micos.
     */
    public function setSql($aSql) {

      if (!is_array($aSql)) {
        $this->aSql = array($aSql);
      } else {
        $this->aSql = $aSql;
      }

    }
    
    /**
     * M�todo que retorna o arquivo gerado (string que comp�e o arquivo)
     * @return string Arquivo gerado. Se o arquivo ainda n�o foi gerado, retorna vazio.
     */
    public function getArquivo() {
      return $this->sArquivoGerado;
    }
    
    /**
     * M�todo que executa um comando SQL (query), atualizando o array de dados $aDadosSql.
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
     * Fun��o muito espec�fica, utilizada como callback para preg_replace_callback. Esta fun��o utiliza
     * o array com os dados retornados pela consunta ($aDadosSql), e o n�mero da linha atualmente
     * sendo processada ($iLinhaAtual)
     * @param array(string) $aPadroes Array com os padr�es referentes a vari�veis encontrados pela
     * fun��o preg_replace_callback(). Neste vetor, a primeira posi��o possui a defini��o
     * completa da vari�vel. Ex: ${NOME_VAR}{30}+. Na segunda posi��o espera-se apenas 
     * o nome da vari�vel. Ex: NOME_VAR. Na terceira posi��o (se existir), possuir� o tamanho
     * completo. Ex: {30}+. A quarta posi��o deve conter o n�mero que identifica
     * o tamanho m�ximo do campo. Ex: 30 A quinta posi��o (se existir) possui o 
     * modificador (+, -, =) que significa que deve ser feito padding para completar o tamanho
     * definido na quarta posi��o, ou pode estar vazia.
     * @return string Valor processado para a vari�vel passada.
     */

    private function substituiVariavel($aPadroes) {
      
      $sCampo = strtolower($aPadroes[1]);
      $sTroca = isset($this->aDadosSql[$this->iLinhaAtual]->$sCampo) ? 
                  $this->aDadosSql[$this->iLinhaAtual]->$sCampo : '';
      if (count($aPadroes) > 2) { // Tem que ter pelo menos 4 posicoes
        
        /* N�mero m�ximo de caracteres definido */
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
     * M�todo que gera o arquivo TXT a partir do modelo e das querys passados.
     * Ap�s gerar o arquivo, este m�todo seta o atributo $sArquivoGerado, com
     * o resultado do processamento.
     */
    public function gerarArquivo() {

      $sArqGerado            = $this->sArquivoModelo;
      
      $sPadraoVariavel       = '/\$\{([a-zA-Z0-9_]+)\}(\{([0-9]+)\}(\+|-|=)?)?/';
      $sPadraoAtrib          = ' +[a-z]+| +[a-z]+=[a-zA-Z0-9]+';
      $sPadraoTrechoDinamico = "/<([0-9])(($sPadraoAtrib)*) *>((.|\n)*)<\/\\1>/";

      /* Os dois arrays abaixo possuem os �ndices de acordo com a numera��o dos trechos din�micos no modelo. */
      $aTrechosDinamicos = array(); // Vai conter todos os textos de trechos din�micos exatamente como est�o no modelo
      $aTextosDinamicos  = array(); // Vai conter todos os textos din�micos j� processados

      $aPadroes          = array();
      preg_match_all($sPadraoTrechoDinamico, $sArqGerado, $aPadroes);
      
      /* Obtenho todos os textos dos trechos din�micos, trocando pelas tags de abertura e fechamento (<n></n>) */
      $iTam = count($aPadroes[0]); // $aPadroes[0] sempre vai existir, mesmo que n�o encontre trechos din�micos
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
      /* Substituo as vari�veis pelos seus valores vindos do banco ap�s executar o SQL da posi��o 0, que �
         global para o arquivo (mas suas vari�veis n�o s�o vistas dentro de trechos din�micos).
      */
      if (isset($this->aSql[0])) { // N�o obrigo existir um sql. Pode ser que se deseje imprimir o modelo sem vari�veis

        $this->executaSql($this->aSql[0]);
        $this->iLinhaAtual = 0;

      }
      $sArqGerado = preg_replace_callback($sPadraoVariavel, array(&$this, 'substituiVariavel'), $sArqGerado);
      
      /* Processo cada trecho din�mico, armazenando o texto gerado no array $aTextosDinamicos */
      foreach ($aTrechosDinamicos as $iInd => $oAtributos) {

        if (!isset($this->aSql[$iInd])) {
          throw new Exception("Trecho din�mico '$iInd' n�o possui SQL associado. N�o � poss�vel gerar o arquivo.");
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
      

      /* Coloco cada trecho din�mico j� processado em seu devido lugar no arquivo */
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
     * M�todo que disponibiliza o arquivo gerado para download em um di�logo
     * do navegador. Para isso, ele envia os headers necess�rios.
     * @param string $sNomeDownload Nome que o arquivo para download ir� possuir. Se n�o for
     * passado nenhum nome, assume-se por default o nome 'arquivo.txt'
     */
    public function downloadArquivo($sNomeDownload = 'arquivo.txt') {
      
      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="'.$sNomeDownload.'"');
      header('Content-Transfer-Encoding: binary');
      echo $this->getArquivo();
      
    }
    
    /**
     * M�todo que salva em disco o arquivo gerado.
     * @param string Caminho para salvar o arquivo.
     */
    public function salvarArquivo($sDestino) {

      if (empty($sDestino)) {
        throw new Exception('O caminho para salvar o arquivo deve ser informado obrigatoriamente.');
      }

      $pArquivoDestino = fopen($sDestino, 'w');
      if (!$pArquivoDestino) {
        throw new Exception("N�o foi poss�vel salvar o arquivo em '$sDestino'. Verique se o caminho est� correto.");
      }
      
      $lRetorno = fwrite($pArquivoDestino, $this->getArquivo());
      fclose($pArquivoDestino);
      if ($lRetorno === false) {
        throw new Exception("N�o foi poss�vel salvar o arquivo em '$sDestino'. Erro ao escrever no arquivo.");
      }

    }

  }