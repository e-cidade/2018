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

require_once("model/pessoal/relatorios/RelatorioFolhaPagamento.model.php");

/**
 * @fileoverview Classe para relatório da folha analitico/sintetico
 *
 * @author  Rafael Lopes rafael.lopes@dbseller.com.br
 *          Rafael Nery  rafael.nery@dbseller.com.br
 *
 * @package Pessoal
 * @revision $Author: dbjeferson.belmiro $
 * @version $Revision: 1.9 $
 */
class RelatorioFolhaSinteticoAnalitico extends RelatorioFolhaPagamento  {
  
  protected $aRetorno   = array();
  protected $aDadosBase = array();
  protected $lAfastados = true;

  public function __construct(){
    parent::__construct();
  }
    /**
     * metodo para retornarmos os dados basicos do relatorio
     * tratamos junto o where dos afastados
     *
     * @return $this
     */
  public function getDadosBase(){
    
    $oDadosRetorno    = new stdClass();
    $sWhere           = " rh05_seqpes IS NULL ";
    $oDaoAfasta       = db_utils::getDao('afasta', true);

    /**
     * Where utilizado para trazer os afastamentos que estão de acordo com a competência
     */
    $sWhereAfastamentos  = "     r45_anousu = {$this->iAno}                                                                            ";
    $sWhereAfastamentos .= " and r45_mesusu = {$this->iMes}                                                                            ";
    $sWhereAfastamentos .= " and (( extract(month from r45_dtafas) = {$this->iMes} and extract(year from r45_dtafas) = {$this->iAno} ) ";
    $sWhereAfastamentos .= " or ( r45_dtreto is null or r45_dtreto >= '{$this->iAno}-{$this->iMes}-01' ))                              ";

    if (!$this->lAfastados) {
      
      $sWhere .= " and rh01_regist not in (select r45_regist                                   
                                             from afasta 
                                            where r45_anousu = {$this->iAno}
                                              and r45_mesusu = {$this->iMes} 
                                              and(r45_regist is not null 
                                                   and (    r45_dtreto is null 
                                                         or r45_dtreto >= '{$this->iAno}-{$this->iMes}-01')
                                                       ) 
                                    )";
                                       
    }

    
    $aSQLBase = $this->retornaSQLBaseRelatorio($sWhere);

    foreach ($aSQLBase as $sTabelaPonto => $sSqlBase){
      
      $rsDados = db_query($sSqlBase);
      
      if ( $rsDados && pg_num_rows($rsDados) > 0 ) {
        
        while ($oDados = pg_fetch_object($rsDados) ) {

          /**
           * Busca os afastamentos
           */
          $sSqlAfastamentos = $oDaoAfasta->sql_query_file( null, 
                                                           "r45_dtafas, r45_dtreto,                             "
                                                           . "( select r66_descr                                "
                                                           . "    from codmovsefip                              "
                                                           . "   where r66_anousu = r45_anousu                  "
                                                           . "     and r66_mesusu = r45_mesusu                  "
                                                           . "     and r66_codigo = r45_codafa ) as afastamento ",
                                                           "r45_dtafas desc",
                                                           "{$sWhereAfastamentos} and r45_regist = $oDados->matricula_servidor" );
          $rsAfastamentos   = $oDaoAfasta->sql_record( $sSqlAfastamentos );

          $aAfastamentos = array();
          if ($oDaoAfasta->numrows > 0) {
            $aAfastamentos  = db_utils::getCollectionByRecord($rsAfastamentos, true);
          }

          $oDadosServidor = new stdClass();
          $oDadosServidor->matricula_servidor                  = $oDados->matricula_servidor;
          $oDadosServidor->nome_servidor                       = $oDados->nome_servidor     ;
          $oDadosServidor->codigo_cargo                        = $oDados->codigo_cargo      ;
          $oDadosServidor->descr_cargo                         = $oDados->descr_cargo       ;
          $oDadosServidor->codigo_lotacao                      = $oDados->codigo_lotacao    ;
          $oDadosServidor->estrutural_lotacao                  = $oDados->estrutural_lotacao;
          $oDadosServidor->descr_lotacao                       = $oDados->descr_lotacao     ;
          $oDadosServidor->codigo_funcao                       = $oDados->codigo_funcao     ;
          $oDadosServidor->descr_funcao                        = $oDados->descr_funcao      ;
          $oDadosServidor->aAfastamentos                       = $aAfastamentos;
                                                              
          $oDadosRubricas = new stdClass();                   
          $oDadosRubricas->rubrica                             = $oDados->rubrica           ;
          $oDadosRubricas->valor_rubrica                       = $oDados->valor_rubrica     ;
          $oDadosRubricas->quant_rubrica                       = $oDados->quant_rubrica     ;
          $oDadosRubricas->provento_desconto                   = $oDados->provento_desconto ;
          $oDadosRubricas->descr_rubrica                       = $oDados->descr_rubrica     ;
          
          $oDadosRetorno->aDadosServidor[$oDados->matricula_servidor]                                  = $oDadosServidor ;
          $oDadosRetorno->aDadosRubricas[$sTabelaPonto][$oDados->matricula_servidor][$oDados->rubrica] = $oDadosRubricas ;
          
          $oRubricas = new stdClass();
          $oRubricas->rubrica                                  = $oDados->rubrica;
          $oRubricas->descr_rubrica                            = $oDados->descr_rubrica;
                    
          $oDadosRetorno->aRubricas     [$oDados->rubrica]     = $oRubricas;
          asort($oDadosRetorno->aRubricas);
        }
      } else {
        
        $oDadosRetorno->aDadosServidor   = array();
        $oDadosRetorno->aDadosRubricas   = array();
        $oDadosRetorno->aRubricas        = array();
      }
    }
    return $oDadosRetorno;
  }
  
  
  /**
   * metodo set para definirmos se filtramos ou nao pelos
   * servidores afastados
   *
   * @param bollean $lAfastados
   * @return $this
   */
  public function setAfastados($lAfastados){
  	
  	$this->lAfastados = $lAfastados;
  	return $this;
  }
  
}