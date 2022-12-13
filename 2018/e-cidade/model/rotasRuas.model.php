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
 * Manipula rotas e ruas
 * @package Agua
 *
 */
class rotasRuas {
  
  /**
   * Codigo da rota
   * @var inteiro
   */
  protected $iCodRota;
  
  /**
   * Codigo da rua
   * @var inteiro
   */
  protected $iCodRua;
  
  /**
   * Nome do Logradouro
   * @var string
   */
  protected $sNomeRua;
  
  /**
   * Numero de Ruas da Rota
   * @var inteiro
   */
  protected $iNumRowsRUas;
  
  /**
   * Numero inicial enviado do formulario
   * @var integer
   */
  public $iFrmNroInicial;
  
  /**
   * Numero Final enviado do formulario
   * @var integer
   */
  public $iFrmNroFinal;
  
  /**
   * Orientação da numerão para a rua
   * @var integer
   */
  public $cFrmOrientacao;
  
  
  /**
   * Lista das ruas da rota definida
   * @var array
   */
  protected $aRuas = array(); 
  
  /**
   * Define Codigo da Rora
   * @param $iCodRota Código da rota
   */
  public function setCodRota($iCodRota) {
   
    $this->iCodRota = $iCodRota;
    
  }
  
  /**
   * Retorna Codigo da Rota
   * @return inteiro
   */
  public function getRota() {
    
    return $this->iCodRota;
    
  }
  
  /**
   * Define Codigo da Rua
   * @param $iCodRua
   */
  public function setCodRua($iCodRua) {
    
    $this->iCodRua = $iCodRua;
    
  }
  
  /**
   * Retorna Codigo da Rua
   * @return inteiro
   */
  public function getCodRua() {
    
    return $this->iCodRua;
    
  }
  
  /**
   * Define nome da rua
   * @param $sNomeRua
   */
  public function setNomeRua($sNomeRua) {
    
    $this->sNomeRua = $sNomeRua;
    
  }
  
  /**
   * Retorna o Nome da Rua
   * @return String
   */
  public function getNomeRua() {
    
    return $this->sNomeRua;
  }
  
  /**
   * Retorna ruas da rota
   * @return array
   */
  public function ruasRota($iCodRota) {
    
    if($iCodRota == null) {

      throw new Exception("Rota não informada");
      
    }
    
    $this->iCodRota = $iCodRota;
    
    require_once("classes/db_aguarotarua_classe.php");
    
    $oDaoAguaRotaRua = new cl_aguarotarua();
      
		$sCampos  = "x07_codrotarua, x07_codrota, x07_codrua,                                  ";
		$sCampos .= "j14_nome, x07_nroini, x07_nrofim, x07_orientacao,                         ";
		$sCampos .= "(select count(*)                                                          ";
		$sCampos .= "   from aguabase                                                          ";
		$sCampos .= "        left join aguabasebaixa on x08_matric = x01_matric                ";
		$sCampos .= "  where x01_codrua = x07_codrua                                           ";
		$sCampos .= "    and x08_matric is null                                                ";
		$sCampos .= "    and x01_orientacao = x07_orientacao                                   ";
		$sCampos .= "    and x01_numero BETWEEN x07_nroini and x07_nrofim                      ";
		$sCampos .= "    and fc_agua_hidrometroinstalado(x01_matric)) as x99_quantidade        ";

	  $sWhere   = "x07_codrota = $this->iCodRota";
    $sOrderBy = "x07_codrota, x07_codrua, j14_nome";

    $sSqlAguaRotaRua = $oDaoAguaRotaRua->sql_query(null, $sCampos, $sOrderBy, $sWhere);
   
    $rsAguaRotaRua   = $oDaoAguaRotaRua->sql_record($sSqlAguaRotaRua);
    
    if($oDaoAguaRotaRua->numrows > 0) {
      
      $this->iNumRowsRUas = $oDaoAguaRotaRua->numrows;
      
      for($i = 0; $i < $oDaoAguaRotaRua->numrows; $i++) {
        
        $oRuas       = db_utils::fieldsMemory($rsAguaRotaRua, $i, '', false);
        
        $this->iCodRota = $oRuas->x07_codrota;
        $this->iCodRua  = $oRuas->x07_codrua;
        $this->sNomeRua = $oRuas->j14_nome;
        
        $this->aLinhas[] = $oRuas;
      }
      
      return $this->aLinhas;
    }
    
  }
  
  /**
   * 
   * @param $iCodRua
   * @return array
   */
  public function rotaRuasNumeracao($iCodRua, $iCodRota, $iFrmNroInicial, $iFrmNroFinal, $cFrmOrientacao) {
    
    if($iCodRota == '') {
      
      throw new Exception("Código da Rota não informado.");
    }
    if($iCodRua == '') {
      
      throw new Exception("Código da Rua não informado.");
    }
    
    require_once("classes/db_aguarotarua_classe.php");

    $this->iCodRua        = $iCodRua;
    $this->iCodRota       = $iCodRota;
    $this->iFrmNroInicial = $iFrmNroInicial;
    $this->iFrmNroFinal   = $iFrmNroFinal;
    $this->cFrmOrientacao = $cFrmOrientacao;

    $oDaoAguaRotaRua = new cl_aguarotarua();

    $sCampos = "x07_nroini, x07_nrofim, x07_orientacao"; 
    $sWhere  = "x07_codrua = $this->iCodRua and x07_orientacao = '$this->cFrmOrientacao' and x07_codrota <> $this->iCodRota ";

    
    $sSqlAguaRotaRua = $oDaoAguaRotaRua->sql_query(null, $sCampos, null, $sWhere);
    $rsAguaRotaRua   = $oDaoAguaRotaRua->sql_record($sSqlAguaRotaRua);
    
    for($i = 0; $i < $oDaoAguaRotaRua->numrows; $i++) {
      
      $oAguaRotaRua = db_utils::fieldsMemory($rsAguaRotaRua, $i);
      
      $iNumeroInicial = $oAguaRotaRua->x07_nroini;
      $iNumeroFinal   = $oAguaRotaRua->x07_nrofim;
      
      if(($this->iFrmNroInicial >= $iNumeroInicial and $this->iFrmNroInicial <= $iNumeroFinal) ||
         ($this->iFrmNroFinal   >= $iNumeroInicial and $this->iFrmNroFinal   <= $iNumeroFinal) ||
         ($this->iFrmNroInicial <= $iNumeroInicial and $this->iFrmNroFinal   >= $iNumeroFinal)) 
      {
        
        return 1;
        
      }else {
       
        return 0;
        
      }
      
    }    
    
  }
  
 /**
   * retorna quais orientações a rua possui da rota informada
   * @param $iCodRua, $iCodRota, $iFrmNro
   * @return array
   */
  public function rotaRuasOrientacao($iCodRua, $iCodRota, $iFrmNro) {
    
    if($iCodRota == '') {
      
      throw new Exception("Código da Rota não informado.");
    }
    if($iCodRua == '') {
      
      throw new Exception("Código da Rua não informado.");
    }
    if($iFrmNro == '') {
      
      throw new Exception("Numero não definido.");
    }  
      
    require_once("classes/db_aguarotarua_classe.php");

    $this->iCodRua   = $iCodRua;
    $this->iCodRota  = $iCodRota;
    $this->iFrmNro   = $iFrmNro;

    $oDaoAguaRotaOrientacao = new cl_aguarotarua();

    $sCampos = "x07_nroini, x07_nrofim, x07_orientacao "; 
    $sWhere  = "x07_codrua = $this->iCodRua and x07_codrota = $this->iCodRota";
    
    $sSqlAguaRotaOrientacao = $oDaoAguaRotaOrientacao->sql_query(null, $sCampos, null, $sWhere);

    $rsAguaRotaOrientacao   = $oDaoAguaRotaOrientacao->sql_record($sSqlAguaRotaOrientacao);

    $iIndice = 0;
    for($i = 0; $i < $oDaoAguaRotaOrientacao->numrows; $i++) {
      
      $oAguaRotaOrientacao = db_utils::fieldsMemory($rsAguaRotaOrientacao, $i);
      
      $sOrientacao    = $oAguaRotaOrientacao->x07_orientacao;
      $iNumeroInicial = $oAguaRotaOrientacao->x07_nroini;
      $iNumeroFinal   = $oAguaRotaOrientacao->x07_nrofim;
      
      
      if ($this->iFrmNro >= $iNumeroInicial and $this->iFrmNro <= $iNumeroFinal) {
        
        if ($sOrientacao == "D") {
          $sDescricaoOrientacao = "DIREITA";
        }else if ($sOrientacao == "E") {
          $sDescricaoOrientacao = "ESQUERDA";
        }else if ($sOrientacao == "S") {
          $sDescricaoOrientacao = "SUL";
        } else {
          $sDescricaoOrientacao = "";
        }
        
        $oOrientacoes[$iIndice]['value']     = $sOrientacao;
        $oOrientacoes[$iIndice]['descricao'] = $sDescricaoOrientacao;
        $iIndice = $iIndice + 1;
      }
      
    }

    return $oOrientacoes;
    
  }  
  
}
?>