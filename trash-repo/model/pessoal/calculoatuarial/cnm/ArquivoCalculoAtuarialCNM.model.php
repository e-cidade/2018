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
 * Classe que monta um Arquivo de Cálculo Atuarial
 * 
 * @package Calculo Atuarial
 * @author  Rafael Nery <rafael.nery@dbseller.com.br> 
 * @author  Renan Melo  <renan@dbseller.com.br> 
 */
class ArquivoCalculoAtuarialCNM {

  const ATIVOS                      = 0;
  const PENSIONISTAS                = 1;
  const INATIVOS_TEMPO_CONTRIBUICAO = 2;
  const INATIVOS_IDADE              = 3;
  const INATIVOS_INVALIDEZ          = 4;
  const INATIVOS_COMPULSORIA        = 5;

  /**
   * Registros a serem processados.
   * 
   * @var array
   * @access public
   */
  public $aRegistros = array(); 

  /**
   * Tipo de Arquivo que pode ser gerado, definido pelas constantes
   * 
   * @var integer
   * @access public
   */
  public $iTipoArquivo;  

  /**
   * Valida se foram adicionados registros no arquivo
   * @var boolean
   */
  private $lAdicionouRegistros = false;
  
  /**
   * Arquivo que será arquivo
   * @var pointer
   */
  private $pArquivo;
  
  /**
   * Caminho do Arquivo
   * @var string
   */
  private $sCaminho;
  
  /**
   * Construtor da Classe
   *
   * @param mixed $iTipo
   * @access public
   * @return void
   */
  public function __construct( $iTipo ) {

    switch ( $iTipo ) {

      case ArquivoCalculoAtuarialCNM::ATIVOS:
        $this->sNomeclatura = "ATIVOS";  
      break;

      case ArquivoCalculoAtuarialCNM::INATIVOS_INVALIDEZ:
        $this->sNomeclatura = "INATIVOS_INVALIDEZ";  
      break;

      case ArquivoCalculoAtuarialCNM::INATIVOS_TEMPO_CONTRIBUICAO:
        $this->sNomeclatura = "INATIVOS_TEMPO_CONTRIBUICAO";  
      break;

      case ArquivoCalculoAtuarialCNM::INATIVOS_IDADE:
        $this->sNomeclatura = "INATIVOS_IDADE";  
      break;

      case ArquivoCalculoAtuarialCNM::INATIVOS_COMPULSORIA:
        $this->sNomeclatura = "INATIVOS_COMPULSORIA";  
      break;

      case ArquivoCalculoAtuarialCNM::PENSIONISTAS:
        $this->sNomeclatura = "PENSIONISTAS";  
      break;

      default:
        throw new ParameterException("Arquivo de Cálculo Atuarial Inválido.");
      break;
    }

    $this->iTipoArquivo = $iTipo;
    $this->sCaminho = "tmp/_" . date("Ymdis"). rand() ."calculoAtuarialCNM_" . $this->sNomeclatura . ".csv";
    $this->pArquivo = fopen($this->sCaminho,'w');
  }


  /**
   * Adiciona registro ao Arquivo
   *
   * @param InformacaoCalculoAtuarial $oRegistro
   * @access public
   * @return void
   */
  public function lancarRegistro ( InformacaoCalculoAtuarial $oRegistro ) {
   
    switch ( $this->iTipoArquivo ) {

      case ArquivoCalculoAtuarialCNM::ATIVOS:

        if ( !( $oRegistro instanceOf InformacaoCalculoAtuarialAtivos ) ) {
          throw new ParameterException(" Os Registros Passados Devem ser de Servidores Ativos");
        }
      break;

      case ArquivoCalculoAtuarialCNM::INATIVOS_INVALIDEZ:
      case ArquivoCalculoAtuarialCNM::INATIVOS_TEMPO_CONTRIBUICAO:
      case ArquivoCalculoAtuarialCNM::INATIVOS_IDADE:
      case ArquivoCalculoAtuarialCNM::INATIVOS_COMPULSORIA:

        if ( !( $oRegistro instanceOf InformacaoCalculoAtuarialInativos ) ) {
          throw new ParameterException(" Os Registros Passados Devem ser de Servidores Inativos");
        }
      break;

      case ArquivoCalculoAtuarialCNM::PENSIONISTAS:

        if ( !( $oRegistro instanceOf InformacaoCalculoAtuarialPensionistas ) ) {
          throw new ParameterException(" Os Registros Passados Devem ser de Pensionista. ");
        }
      break;

    }
    
    $aLinha = array();
    foreach ($oRegistro->toArray() as $sConteudoColuna) {
    	
    	$aLinha[] = $sConteudoColuna != '' ? $sConteudoColuna : '0'; 
    	
    }
    
    if ( !fputcsv($this->pArquivo, $aLinha, ',', '"' ) ) {
    	throw new Exception("Erro Ao gravar o Registro no Arquivo");
    }
    $this->lAdicionouRegistros = true;
    return;
  }

  /**
   * Processa o Arquivo e Retorna o caminho do arquivo gerador
   *
   * @access public
   * @return string
   */
  public function processar() {

  	if ( !$this->lAdicionouRegistros ) {
  		return null;	
  	}
  	
   //foreach ( $this->aRegistros as $oRegistroServidor ) {
   //
   //  if ( !fputcsv($pArquivo, $oRegistroServidor->toArray(), ',', '"' ) ) {
   //    throw new Exception("Erro Ao gravar o Registro no Arquivo");
   //  }
   //}
    
    fclose($this->pArquivo);
    return $this->sCaminho;
  }


}