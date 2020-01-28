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

require_once ('model/PadArquivoSigap.model.php');
/**
 * Prove dados para a geração do arquivo d3 Receita no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapReceita extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Receita";
    $this->aDados       = array();
  }
  
  /**
   * Gera os dados para utilizacao posterior. Metodo geralmente usado 
   * em conjuto com a classe PadArquivoEscritorXML
   * @return true;
   */
  public function gerarDados() {
    
    if (empty($this->sDataInicial)) {
      throw new Exception("Data inicial nao informada!");
    }
    
    if (empty($this->sDataFinal)) {
      throw new Exception("Data final não informada!");
    }
    /**
     * Separamos a data do em ano, mes, dia
     */
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");
    $oReceitaMes = new cl_receita_saldo_mes;
    $oReceitaMes->dtini               = $this->sDataInicial;
    $oReceitaMes->dtfim               = $this->sDataFinal;
    $oReceitaMes->instit              = $sListaInstit;
    $oReceitaMes->lPrevisaoCronograma = true;
    $oReceitaMes->sql_record();
    if (PostgreSQLUtils::isTableExists("work_receita")) {
      db_query("drop table work_receita");
    }
    $iTotalLinhas = pg_num_rows($oReceitaMes->result);
    for ($i = 1; $i < $iTotalLinhas; $i++) {
      
      $oReceita            = db_utils::fieldsMemory($oReceitaMes->result, $i);
      $sSqlCaracteristica  = "select o70_concarpeculiar  ";
      $sSqlCaracteristica .= "  from orcreceita ";
      $sSqlCaracteristica .= " where o70_anousu = {$iAno} and "; 
      $sSqlCaracteristica .= "       o70_codrec = {$oReceita->o70_codrec}";
      $rsCaracteristica    = @pg_query($sSqlCaracteristica);
      $iCaracteristicaPeculiar = db_utils::fieldsMemory($rsCaracteristica, 0)->o70_concarpeculiar;
      
      /**
       * Acertamos o estrutural, caso seje receita, devemos retirar o primeiro caracter;
       */
      if ($oReceita->o70_anousu > 2007) {
        
        if (db_conplano_grupo($oReceita->o70_anousu,substr($oReceita->o57_fonte,0,1)."%", 9000) == false) {
          $oReceita->o57_fonte = str_pad(substr($oReceita->o57_fonte,1,14), 20, '0', STR_PAD_RIGHT); // recompisoção
        } else {
          $oReceita->o57_fonte  = str_pad(substr($oReceita->o57_fonte,0,15), 20, '0', STR_PAD_RIGHT); // recompisoção
        }
      } else {
        $oReceita->o57_fonte = str_pad(substr($oReceita->o57_fonte,1,14), 20, '0', STR_PAD_RIGHT); // recompisoção
      }
      /**
       * AJuste no mes de dezemro, quando conta de Deducao
       */
      if (db_conplano_grupo($oReceita->o70_anousu, substr($oReceita->o57_fonte,0,2)."%",9000) == true) {  // 49

        if ($oReceita->dezembro <> 0) {
          $oReceita->dezembro = abs($oReceita->dezembro) *-1;
        }
        
        if ($oReceita->prev_dez <> 0) {
          $oReceita->prev_dez = abs($oReceita->prev_dez) *-1;
        }
      }
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno = new stdClass();
      $oReceitaRetorno->recCodigoEntidade                 = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oReceitaRetorno->recMesAnoMovimento                = $sDiaMesAno;
      $oReceitaRetorno->recCodigoContaReceita             = str_pad($oReceita->o57_fonte, 20, 0, STR_PAD_RIGHT);
      $oReceitaRetorno->recDescricaoContaReceita          = substr($oReceita->o57_descr, 0, 255);
      
      $iTamanhoCampo = strlen($oInstituicao->codtrib);
      if ($iTamanhoCampo != 4) {
        
        $sMsg  = "Identificação do Orgão/Unidade da instituição ({$oInstituicao->codtrib}) está incorreto. \\n ";
        $sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
        $sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
        
        throw new Exception($sMsg);
      }
      
      $sOrgao                                             = substr($oInstituicao->codtrib, 0, 2);
      $sUnidade                                           = substr($oInstituicao->codtrib, 2, 2);
      $oReceitaRetorno->recCodigoOrgao                    = str_pad($sOrgao, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno->recCodigoUnidadeOrcamentaria      = str_pad($sUnidade, 2, "0", STR_PAD_LEFT);
      
      $oReceitaRetorno->recCaracteristicaPeculiar         = str_pad($iCaracteristicaPeculiar, 3, "0", STR_PAD_LEFT);
      $oReceitaRetorno->recRealizadaJaneiro               = $this->corrigeValor($oReceita->janeiro, 13);
      $oReceitaRetorno->recRealizadaFevereiro             = $this->corrigeValor($oReceita->fevereiro, 13);
      $oReceitaRetorno->recRealizadaMarco                 = $this->corrigeValor($oReceita->marco, 13);
      $oReceitaRetorno->recRealizadaAbril                 = $this->corrigeValor($oReceita->abril, 13);
      $oReceitaRetorno->recRealizadaMaio                  = $this->corrigeValor($oReceita->maio, 13);
      $oReceitaRetorno->recRealizadaJunho                 = $this->corrigeValor($oReceita->junho, 13);
      $oReceitaRetorno->recRealizadaJulho                 = $this->corrigeValor($oReceita->julho, 13);
      $oReceitaRetorno->recRealizadaAgosto                = $this->corrigeValor($oReceita->agosto, 13);
      $oReceitaRetorno->recRealizadaSetembro              = $this->corrigeValor($oReceita->setembro, 13);
      $oReceitaRetorno->recRealizadaOutubro               = $this->corrigeValor($oReceita->outubro, 13);
      $oReceitaRetorno->recRealizadaNovembro              = $this->corrigeValor($oReceita->novembro, 13);
      $oReceitaRetorno->recRealizadaDezembro              = $this->corrigeValor($oReceita->dezembro, 13);
      /**
       * metas das Arrecadaçoes 
       */
      $oReceitaRetorno->recMetaArrecadacao1oBimestre = $this->corrigeValor($oReceita->prev_jan+$oReceita->prev_fev, 12);
      $oReceitaRetorno->recMetaArrecadacao2oBimestre = $this->corrigeValor($oReceita->prev_mar+$oReceita->prev_abr, 12);
      $oReceitaRetorno->recMetaArrecadacao3oBimestre = $this->corrigeValor($oReceita->prev_mai+$oReceita->prev_jun, 12);
      $oReceitaRetorno->recMetaArrecadacao4oBimestre = $this->corrigeValor($oReceita->prev_jul+$oReceita->prev_ago, 12);
      $oReceitaRetorno->recMetaArrecadacao5oBimestre = $this->corrigeValor($oReceita->prev_set+$oReceita->prev_out, 12);
      $oReceitaRetorno->recMetaArrecadacao6oBimestre = $this->corrigeValor($oReceita->prev_nov+$oReceita->prev_dez, 12);
      
      $this->aDados[] =  $oReceitaRetorno; 
    }
    
    return true;
  }
  
  /**
   * Publica quais elementos/Campos estão disponiveis para 
   * o uso no momento da geração do arquivo
   *
   * @return array com elementos disponibilizados para a geração dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                         "recCodigoEntidade",
                         "recMesAnoMovimento",
                         "recCodigoContaReceita",
                         "recDescricaoContaReceita",
                         "recCodigoOrgao",
                         "recCodigoUnidadeOrcamentaria",
                         "recRealizadaJaneiro",
                         "recRealizadaFevereiro",
                         "recRealizadaMarco",
                         "recRealizadaAbril",
                         "recRealizadaMaio",
                         "recRealizadaJunho",
                         "recRealizadaJulho",
                         "recRealizadaAgosto",
                         "recRealizadaSetembro",
                         "recRealizadaOutubro",
                         "recRealizadaNovembro",
                         "recRealizadaDezembro",
                         "recMetaArrecadacao1oBimestre",
                         "recMetaArrecadacao2oBimestre",
                         "recMetaArrecadacao3oBimestre",
                         "recMetaArrecadacao4oBimestre",
                         "recMetaArrecadacao5oBimestre",
                         "recMetaArrecadacao6oBimestre",
                         "recCaracteristicaPeculiar"
                       );
                       
    return $aElementos;  
  }
  
  private function corrigeValor ($valor, $quant) {
    
    if (empty($valor)) {
      $valor = 0;
    }
  	
    if ($valor < 0) {
      
      $valor *= -1;
      $valor  = "-".str_pad(number_format($valor, 2, ".",""),  $quant-1, '0', STR_PAD_LEFT);
    } else {
      $valor  = str_pad(number_format($valor, 2, ".",""), $quant, '0', STR_PAD_LEFT);
    }
    
    return $valor;
  }
}
?>