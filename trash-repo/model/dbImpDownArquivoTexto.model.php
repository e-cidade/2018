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
 * @fileoverview Classe que permite o download e impress�o de arquivos texto, suportando dividir um �nico arquivo em v�rios,
 * pois avalia o separador de arquivos (FS - chr(28)).
 * @author Tony Farney Bruck Mendes Ribeiro
 */
require_once('./model/impressao.model.php');
  class DBImpDownArquivoTexto {

    /**
     * Array de string com o conte�do de cada arquivo TXT.
     * @var array(string) $sArquivoModelo
     */
    private $aArquivos = array();
    
   /**
    * M�todo construtor da classe.
    * @construct
    */
    function  __construct() {
    }
    
    /**
     * M�todo que l� um arquivo e carrega ele para o array de arquivos. Se forem encontrados caracteres FS (chr(28)),
     * Estes servir�o como delimitadores de arquivos, o que acarretar� que em um �nico arquivo texto, podemos ter
     * v�rios arquivos.
     * @param string $sEnderecoArquivo Caminho para o arquivo a ser carregado.
     */
    public function carregarArquivo($sEnderecoArquivo) {

      if (empty($sEnderecoArquivo)) {
        throw new Exception('O arquivo a ser carregado deve ser informado obrigatoriamente.');
      }

      if (!file_exists($sEnderecoArquivo)) {
        throw new Exception("'$sEnderecoArquivo' n�o existe.'");
      }

      if (!is_file($sEnderecoArquivo)) {
        throw new Exception("'$sEnderecoArquivo' n�o � um arquivo.");
      }

      if (!is_readable($sEnderecoArquivo)) {
        throw new Exception("'$sEnderecoArquivo' n�o tem permiss�o de leitura.'");
      }

      $sArquivo = file_get_contents($sEnderecoArquivo);
      if ($sArquivo === false) {
        throw new Exception("N�o foi poss�vel ler o conte�do de '$sEnderecoArquivo'.");
      }
      
      $aTmp = explode(chr(28), $sArquivo);
      foreach($aTmp as $sArq) {
        $this->aArquivos[] = $sArq;
      }

    }
    
    /**
     * M�todo que imprime os arquivos na impressora matricial.
     * @param integer $iNumArquivo �ndice do arquivo no vetor de arquivos (come�a em 0).
     */
    public function imprimirArquivo($iNumArquivo = -1) {

      $sIp        = db_getsession('DB_ip');
      $oImpressao = new impressao();
      $oImpressao->setIp($sIp);
      $oImpressao->setPorta(4444);
      
      if ($iNumArquivo > -1) { // Solicitada impress�o de um arquivo espec�fico
        
        if (!isset($this->aArquivos[$iNumArquivo])) {
          throw new Exception("Arquivo solicitado para impress�o n�o foi carregado.");
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
     * M�todo que fornece para download um dos arquivos da lista.
     * @param integer $iNumArquivo �ndice do arquivo no vetor de arquivos (come�a em 0).
     * @param string $sNomeArquivo Nome que o arquivo para download ir� possuir.
     */
    public function downloadArquivo($iNumArquivo, $sNomeArquivo = '') {
      
      if (!isset($this->aArquivos[$iNumArquivo])) {
        throw new Exception("Arquivo solicitado para download n�o foi carregado.");
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
     * M�todo que retorna o n�mero de arquivos carregados.
     * @return integer N�mero de arquivos carregados.
     */
    public function getNumArquivos() {
      return count($this->aArquivos);
    }

  }