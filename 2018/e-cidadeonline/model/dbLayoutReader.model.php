<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
 * O model DBLayoutReader converte um arquivo texto em um array de objetos apartir do cadastro de layout
 * @package configuracao
 * @author Felipe Nunes Ribeiro 
 * @revision $Author: dbanderson $
 * @version $Revision: 1.3 $
 */
class DBLayoutReader {
	
	/**
	 * Linha do arquivo
	 *
	 * @var array
	 */
  private $aLinhas             = array();
  
  /**
   * Propriedades de cada campo da linha
   *
   * @var array
   */
  private $aPropriedadesCampos = array(); 
  
  protected $sCaminhoArquivo   = false;
  /**
   * No construtor já será gerado os abjetos apartir do layout informado
   *
   * @param integer $iLayoutTxt  Código do Cadastro de Layout
   * @param string  $sArquivo    Caminho do Arquivo
   * @param boolean $lUsaSeparador  true para utilizar separador (se houver), ou seja,
   * fazer o explode pelo separador nas linhas. false para não utilizar separador.
   * @param boolean $lProcessarArquivo  true para processar o arquivo diretamente default true
   */
  function __construct ($iLayoutTxt='',$sArquivo='', $lUsaSeparador = false, $lProcessarArquivo = true, $lUsaChr = false) {
  	
  	if ( trim($iLayoutTxt) == '' ) {
  		throw new Exception('Código do Layout não informado!');
  	}
  	
    if ( trim($sArquivo) == '' ) {
      throw new Exception('Caminho do arquivo não informado!');
    }  	
  	
    
    $clLayoutTxt = db_utils::getDao("db_layouttxt");
    $this->sCaminhoArquivo = $sArquivo;
    /**
     *  Consulta as propriedades de cada campo das linhas do layout informado 
     */
    $sCamposDadosLayout   = " ( select trim(db52_default)                     ";
    $sCamposDadosLayout  .= "			from db_layoutcampos                  			";      					
    $sCamposDadosLayout  .= "		 where db52_layoutlinha = db51_codigo					";         				  
    $sCamposDadosLayout  .= "			 and db52_ident is true ) as identificador, ";
    $sCamposDadosLayout  .= "	db52_nome       as nome_campo,         					";
    $sCamposDadosLayout  .= " db52_descr      as descricao_campo,             ";                	 					
    $sCamposDadosLayout  .= "	db52_posicao    as posicao_campo,    						";           					
    $sCamposDadosLayout  .= "	db52_tamanho    as tamanho_campo, 							";               				
    $sCamposDadosLayout  .= "	db51_separador  as separador_campo,							";
    $sCamposDadosLayout  .= " db52_obs        as observacao_campo,             ";              					
    $sCamposDadosLayout  .= "	db52_ident      as campo_identificador      		";
                                                                                                                
    $sOrderByDadosLayout  = " db51_tipolinha, ";
    $sOrderByDadosLayout .= " identificador,  ";
    $sOrderByDadosLayout .= " db52_posicao    ";
    
    $sSqlDadosLayout = $clLayoutTxt->sql_query_campos($iLayoutTxt,
                                                      $sCamposDadosLayout,
                                                      $sOrderByDadosLayout);
  	$rsDadosLayout   = $clLayoutTxt->sql_record($sSqlDadosLayout);
    $iLinhasLayout   = $clLayoutTxt->numrows; 
    
  	if ( $iLinhasLayout > 0 ) {
  		
      /**
      * Para o caso de utilizar separadores para determinar os campos dentro das linhas
      * preciso saber o índice do vetor explodido no separador. Como os dados vêm ordenados
      * pelo tipo de linha e pela posição dentro do tipo de linha, então, a ordem
      * para cada campo dentro de cada tipo de linha já está correta. O índice começa em 0
      * porque os arrays no PHP começam em 0 também.
      * Ex.: 
      * linha: 123|456|78
      * separador: |
      * array explodido: {123, 456, 78}
      * valor da posição 1: 456
      */
      $iIndice             = 0;
      /**
      * Variável que identifica a mudança de um tipo de linha para outro
      */ 
      $sIdentificadorAtual = '';
  		for ( $iInd=0; $iInd < $iLinhasLayout; $iInd++ ) {

  			$oDadosLayout = db_utils::fieldsMemory($rsDadosLayout,$iInd);

  			/**
        * Verifico se mudou o tipo de linha. Se mudou, zero o índice do campo.
        */
        if ($sIdentificadorAtual != $oDadosLayout->identificador) {

          $iIndice             = 0;
          $sIdentificadorAtual = $oDadosLayout->identificador;

        }

  			/**
  			 *  Cria um array contendo as propriedades necessárias para 
  			 *  a localização do campo dentro da linha do arquivo
  			 */
  			$this->aPropriedadesCampos[$oDadosLayout->identificador]
  			                          [$oDadosLayout->nome_campo]   = array($oDadosLayout->posicao_campo,
  			                                                                $oDadosLayout->tamanho_campo,
                                                                        $oDadosLayout->separador_campo,
                                                                        $iIndice, 
                                                                        $oDadosLayout->nome_campo,
                                                                        $oDadosLayout->campo_identificador=='t'?true:false,
                                                                        $oDadosLayout->descricao_campo, 
                                                                        $oDadosLayout->observacao_campo
                                                                       ); 
  			$iTamanhoIdent = strlen($oDadosLayout->identificador);
  			$iIndice++;
  		}
  	}
  	if ($lProcessarArquivo) {
  	  $this->processarArquivo($iTamanhoIdent, $lUsaSeparador,false,$lUsaChr);
  	}
  }
  /**
   * Retorna o array de objetos com os campos apartir de uma linha informada
   *
   * @param  integer $iInd // Indice da linha desejada
   * @return array         // Array de objeto com os campos
   */
  public function getLineByInd($iInd) {

    return $this->aLinhas[$iInd];
  }
  
  /**
   * Retorna o array de objetos com tadas as linhas do arquivo 
   *
   * @return array         // Array de objeto com os campos
   */  
  public function getLines() {
    return $this->aLinhas;
  }  
  /**
   * Pesquisa o identificador de linha 
   */
  public function getLinhaIdentificadora($sLinha) {
    
    foreach ($this->aPropriedadesCampos as $sIdentificador => $aCampos) {
      
      if (empty($sIdentificador)) {
        continue;
      }
      foreach ($aCampos as $sNomeCampo => $aCampo) {
        
        if ($aCampo[5] ) {
          
          $sValorIdentificadorLinha = substr($sLinha, $aCampo[0]-1, $aCampo[1]);
          if ($sValorIdentificadorLinha == $sIdentificador) {
            return $sIdentificador;
          }
        }
      }
    }
    return false;
  }
  /**
   * Processa as linhas do TXT
   */
  public function processarArquivo($iTamanhoIdent = 0, $lUsaSeparador = false, $lLocalizarIdentificadorLinha = false, 
                                   $lUsaChr = false) {
    
    $aArquivo = file($this->sCaminhoArquivo);   
    foreach ($aArquivo as $sLinha) {                                     

      $this->aLinhas[] = $this->processarLinha($sLinha, $iTamanhoIdent, $lUsaSeparador,$lLocalizarIdentificadorLinha, 
                                               $lUsaChr);
    }
  }
  
  public function processarLinha($sLinhaArquivo, $iTamanhoIdent = 0, $lUsaSeparador = false,
                                 $lLocalizarIdentificadorLinha = false, $lUsaChr = false) {
    
   /**
     *  Abre o aquivo em um array por linha
     */
    
    $sIdentLinha = 0;
    $sLinhaNova  = $this->getLinhaIdentificadora($sLinhaArquivo);
    if ( $lLocalizarIdentificadorLinha && $sLinhaNova ) {
      $sIdentLinha = $sLinhaNova;        
    } else if (!$lLocalizarIdentificadorLinha) {
      $sIdentLinha = substr($sLinhaArquivo, 0, $iTamanhoIdent);  
    }

    if (isset($this->aPropriedadesCampos[$sIdentLinha])) {
      try {
        return new DBLayoutLinha($sLinhaArquivo,$this->aPropriedadesCampos[$sIdentLinha], $lUsaSeparador, $lUsaChr);
      } catch ( Exception $eException ) {
        throw new Exception($eException->getMessage());  
      }
    }
  }
}

?>