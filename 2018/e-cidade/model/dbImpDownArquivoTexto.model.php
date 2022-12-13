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
 * @fileoverview Classe que permite o download e impressão de arquivos texto, suportando dividir um único arquivo em vários,
 * pois avalia o separador de arquivos (FS - chr(28)).
 * @author Tony Farney Bruck Mendes Ribeiro
 */
require_once('./model/impressao.model.php');
  class DBImpDownArquivoTexto {

    /**
     * Array de string com o conteúdo de cada arquivo TXT.
     * @var array(string) $sArquivoModelo
     */
    private $aArquivos = array();
    
   /**
    * Método construtor da classe.
    * @construct
    */
    function  __construct() {
    }
    
    /**
     * Método que lê um arquivo e carrega ele para o array de arquivos. Se forem encontrados caracteres FS (chr(28)),
     * Estes servirão como delimitadores de arquivos, o que acarretará que em um único arquivo texto, podemos ter
     * vários arquivos.
     * @param string $sEnderecoArquivo Caminho para o arquivo a ser carregado.
     */
    public function carregarArquivo($sEnderecoArquivo) {

      if (empty($sEnderecoArquivo)) {
        throw new Exception('O arquivo a ser carregado deve ser informado obrigatoriamente.');
      }

      if (!file_exists($sEnderecoArquivo)) {
        throw new Exception("'$sEnderecoArquivo' não existe.'");
      }

      if (!is_file($sEnderecoArquivo)) {
        throw new Exception("'$sEnderecoArquivo' não é um arquivo.");
      }

      if (!is_readable($sEnderecoArquivo)) {
        throw new Exception("'$sEnderecoArquivo' não tem permissão de leitura.'");
      }

      $sArquivo = file_get_contents($sEnderecoArquivo);
      if ($sArquivo === false) {
        throw new Exception("Não foi possível ler o conteúdo de '$sEnderecoArquivo'.");
      }
      
      $aTmp = explode(chr(28), $sArquivo);
      foreach($aTmp as $sArq) {
        $this->aArquivos[] = $sArq;
      }

    }
    
    /**
     * Método que imprime os arquivos na impressora matricial.
     * @param integer $iNumArquivo Índice do arquivo no vetor de arquivos (começa em 0).
     */
    public function imprimirArquivo($iNumArquivo = -1) {

      $sIp        = db_getsession('DB_ip');
      $oImpressao = new impressao();
      $oImpressao->setIp($sIp);
      $oImpressao->setPorta(4444);
      
      if ($iNumArquivo > -1) { // Solicitada impressão de um arquivo específico
        
        if (!isset($this->aArquivos[$iNumArquivo])) {
          throw new Exception("Arquivo solicitado para impressão não foi carregado.");
        }

        $oImpressao->addComando(rtrim($this->aArquivos[$iNumArquivo], "\n"));
        try {
        	
          $oImpressao->rodarComandos(chr(12));
          
        } catch (Exception $oException) {
        	throw $oException;
        }
        
        return;

      }
      
      /* Imprime todos os arquivos carregados */
   //   foreach ($this->aArquivos as $sArquivo) {
        
      	$oImpressao->resetComandos();
        $oImpressao->addComando(implode(chr(12), $this->aArquivos));
        try {
        	
          $oImpressao->rodarComandos();
          
        } catch (Exception $oException) {
        	throw $oException;
        }
    
     // }

    }
    
    /**
     * Método que fornece para download um dos arquivos da lista.
     * @param integer $iNumArquivo Índice do arquivo no vetor de arquivos (começa em 0).
     * @param string $sNomeArquivo Nome que o arquivo para download irá possuir.
     */
    public function downloadArquivo($iNumArquivo, $sNomeArquivo = '') {
      
      if (!isset($this->aArquivos[$iNumArquivo])) {
        throw new Exception("Arquivo solicitado para download não foi carregado.");
      }
      if (empty($sNomeArquivo)) {
        $sNomeArquivo = "arquivo$iNumArquivo.txt";
      }

      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="'.$sNomeArquivo.'"');
      header('Content-Transfer-Encoding: binary');
      echo $this->aArquivos[$iNumArquivo];

    }
    
    /**
     * Método que retorna o número de arquivos carregados.
     * @return integer Número de arquivos carregados.
     */
    public function getNumArquivos() {
      return count($this->aArquivos);
    }

  }