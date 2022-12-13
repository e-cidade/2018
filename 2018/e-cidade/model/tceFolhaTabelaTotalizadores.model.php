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


require_once ('model/tceEstruturaBasica.php');

class tceFolhaTabelaTotalizadores extends tceEstruturaBasica {
  
  const  NOME_ARQUIVO   = 'TCE_4960.TXT';
  const  CODIGO_ARQUIVO = 37;
  
  public  $iInstit       = "";
  public  $sInstituicoes = "";
  public  $sDataIni      = "";
  public  $sDataFim      = "";
  public  $sCodRemessa   = "";

  protected $oDadosArquivo = null;

  private $oLeiaute = null;
  /**
   * 
   */
  function __construct($iInstit,$sCodRemessa,$sDataIni,$sDataFim,$oData, $oLeiaute = null, $sInstituicoes) {
  	
    try {
      parent::__construct(self::CODIGO_ARQUIVO,self::NOME_ARQUIVO);
    } catch (Exception $e) {
    	throw $e->getMessage();    	
    }
    $this->oDadosArquivo = $oData;
    $this->iInstit       = $iInstit;
    $this->sInstituicoes = $sInstituicoes;
    $this->sDataIni      = $sDataIni;
    $this->sDataFim      = $sDataFim;
    $this->sCodRemessa   = $sCodRemessa;
    if ($oLeiaute != null) {
      $this->oLeiaute = $oLeiaute;
    }
    
    
  }
  
  function getNomeArquivo(){
    return self::NOME_ARQUIVO;
  }
  
  function geraArquivo() {

    // db_criatermometro('terTCE4960', 'Arquivo TCE4960...', 'blue', 1);
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, 
                                                                 $this->sDataIni, 
                                                                 $this->sDataFim, 
                                                                 $this->sCodRemessa), 1);
    $rsFolhaRubricas = db_query($this->sqlTabelaTotalizadores());
    $iNumRows        = pg_num_rows($rsFolhaRubricas);
    $iTotalRegistros = 0;
    
    
    for($i = 0; $i < $iNumRows; $i ++) {
      
      // db_atutermometro($i, $iNumRows, "terTCE4960");
      $oFolhaRubricas = db_utils::fieldsMemory($rsFolhaRubricas, $i);
      $this->oTxtLayout->setByLineOfDBUtils($oFolhaRubricas, 3);
      $iTotalRegistros ++;
    
    }
    
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
    unset($rsFolhaRubricas);
  
  }
  
  /**
   * Funcao para montar uma string sql para busca do cadastro de rubricas
   *
   * @return string                  string sql com o cadastro de rubricas
   */
  function sqlTabelaTotalizadores() {

    $sSqlFolhaRubricas  = " select rh27_instit::char||rh27_rubric  as rubrica, ";
    $sSqlFolhaRubricas .= "        0            as codigovantagemdescontototalizador, ";
    $sSqlFolhaRubricas .= "        cast('{$this->sDataFim}' as date) as dataatualizacao, ";
    $sSqlFolhaRubricas .= "        rh27_descr   as nomevantagemdescontototalizador, ";
    $sSqlFolhaRubricas .= "        rh137_descricao as baselegal ";
    $sSqlFolhaRubricas .= "   from rhrubricas ";
    $sSqlFolhaRubricas .= "   left join rhfundamentacaolegal on rh27_rhfundamentacaolegal = rh137_sequencial ";
    $sSqlFolhaRubricas .= "  where rh27_instit in ({$this->sInstituicoes}) ";
    
    return $sSqlFolhaRubricas;
  
  }



}