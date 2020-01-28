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
 * Vinculo do servidor
 * 
 * @author Alberto <alberto@dbseller.com.br>
 */
class VinculoServidor {	

  /**
   * @todo { Verificar a existencia e necessidade do uso dessas contantes
   */
  const ATIVO              = 0;
  const PENSIONISTA        = 1;
  const TEMPO_CONTRIBUICAO = 2;
  const IDADE              = 3;
  const INVALIDEZ          = 4;
  const COMPULSORIA        = 5;   
  /**
   * }@todo
   */

  const VINCULO_ATIVO       = 'A';
  const VINCULO_PENSIONISTA = 'P';
  const VINCULO_INATIVO     = 'I';

  /**
   * Codigo do regime
   * @var Integer
   */
  private $iCodigo;

  /**
   * Descricao do regime
   * @var String
   */
  private $sDescricao;

  /**
   * Codigo do tipo do regime
   * @var Regime
   */
  private $oRegime;

  /**
   * Codigo do vinculo do regime
   * @var  String
   */
  private $sTipo;

  /**
   * Instituição 
   * @var Instituicao
   */
  private $oInstituicao;


  /**
   * Construtor da classe
   * 
   * @param Integer $iCodigoVinculoServidor
   */
  public function __construct($iCodigoVinculoServidor) {

    if(empty($iCodigoVinculoServidor)) {
      return;
    }

    $this->iCodigo = $iCodigoVinculoServidor;
  }

  /**
   * Retorna Codigo do regime.
   *
   * @return Integer
   */
  public function getCodigo() { 

    return $this->iCodigo;
  }

  /**
   * Define Codigo do regime.
   *
   * @param Integer $iCodigo 
   * @return void
   */
  public function setCodigo($iCodigo) { 

    $this->iCodigo = $iCodigo;
    return;
  }

  /**
   * Retorna Descricao do regime.
   *
   * @return String
   */
  public function getDescricao() { 

    return $this->sDescricao;
  }

  /**
   * Define Descricao do regime.
   *
   * @param String $sDescricao
   * @return void
   */
  public function setDescricao($sDescricao) { 

    $this->sDescricao = $sDescricao;
    return;
  }

  /**
   * Retorna Codigo do tipo do regime.
   *
   * @return Regime
   */
  public function getRegime() { 

    return $this->oRegime;
  }

  /**
   * Define Codigo do tipo do regime.
   *
   * @param Regime $oRegime
   * @return void
   */
  public function setRegime(Regime $oRegime) { 

    $this->oRegime = $oRegime;
    return;
  }

  /**
   * Retorna Codigo do vinculo do regime.
   *
   * @return  String
   */
  public function getTipo() { 

    return $this->sTipo;
  }

  /**
   * Define Codigo do vinculo do regime.
   *
   * @param  String $sTipo
   * @return void
   */
  public function setTipo($sTipo) { 

    $this->sTipo = $sTipo;
    return;
  }

  /**
   * Retorna Instituição.
   *
   * @return Instituicao
   */
  public function getInstituicao() { 

    return $this->oInstituicao;
  }

  /**
   * Define Instituição.
   *
   * @param Instituicao $oInstituicao
   *
   * @return void
   */
  public function setInstituicao(Instituicao $oInstituicao) { 

    $this->oInstituicao = $oInstituicao;
    return;
  }
}
