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
final class PadArquivoSigapReceitaDespesaExtra extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "ReceitaDesepsaExtraOrcamentaria";
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
    $oInstituicao  = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");
    $sWhere        = " c61_instit in ({$sListaInstit}) and c60_codsis=7 ";
    $rsReceitas    = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$this->sDataInicial, 
                                                $this->sDataFinal, 
                                                false,
                                                $sWhere, 
                                                '',
                                                false,
                                                'true'
                                                );
    if (PostgreSQLUtils::isTableExists("work_pl")) {
        db_query("drop table work_pl");
    }
    $iTotalLinhas  = pg_num_rows($rsReceitas);
    for ($i = 1; $i < $iTotalLinhas; $i++) {
      
      $oReceita   = db_utils::fieldsMemory($rsReceitas, $i);
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      
      $oReceitaRetorno = new stdClass();
      $oReceitaRetorno->rdeCodigoEntidade                 = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oReceitaRetorno->rdeMesAnoMovimento                = $sDiaMesAno;
      $oReceitaRetorno->rdeCodigoConta                    = str_pad($oReceita->estrutural, 20, 0, STR_PAD_RIGHT);

      $iTamanhoCampo = strlen($oInstituicao->codtrib);
      if ($iTamanhoCampo != 4) {
        
        $sMsg  = "Identificação do Orgão/Unidade da instituição ({$oInstituicao->codtrib}) está incorreto. \\n ";
        $sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
        $sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
        
        throw new Exception($sMsg);
      }
      
      $sOrgao                                             = substr($oInstituicao->codtrib, 0, 2);
      $sUnidade                                           = substr($oInstituicao->codtrib, 2, 2);
      $oReceitaRetorno->rdeCodigoOrgao                    = str_pad($sOrgao, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno->rdeCodigoUnidadeOrcamentaria      = str_pad($sUnidade, 2, "0", STR_PAD_LEFT);
      
      $oReceitaRetorno->rdeValorMovimentacao              = $this->corrigeValor($oReceita->saldo_final, 13);
      $sIdentificador  = "D";
      if (substr($oReceita->estrutural, 0 ,1) == 1) {
       $sIdentificador  = "R";   
      }
      
      $oReceitaRetorno->rdeIdentificadorDespesaReceita  = $sIdentificador; 
      $oReceitaRetorno->rdeClassificacao                = '07'; 
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
                        "rdeCodigoEntidade",
                        "rdeMesAnoMovimento",
                        "rdeCodigoConta",
                        "rdeCodigoOrgao",
                        "rdeCodigoUnidadeOrcamentaria",
                        "rdeValorMovimentacao",
                        "rdeIdentificadorDespesaReceita",
                        "rdeClassificacao"
                       );
    return $aElementos;  
  }
  
  private function corrigeValor($valor, $quant) {
  	
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