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
 * Prove dados para a geração do arquivo dos decretos no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.7 $
 */
final class PadArquivoSigapBalanceteVerificacao extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "BalanceteVerificacao";
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
    
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);    
    
    /**
     * Separamos a data do em ano, mes, dia
     */
    $this->sDataInicial = "$iAno-$iMes-01";
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sWhere       = " c61_instit in ($sListaInstit)";
    $rsBalancete  = @db_planocontassaldo_matriz(db_getsession("DB_anousu"),
                                                $this->sDataInicial,
                                                $this->sDataFinal,
                                                false,$sWhere,
                                                '',false,'false');
    if (pg_last_error() != "") {
      throw new Exception("Houve um erro ao processar Balancete de verificação!\nErro Retornado:".pg_last_error());                                            
    }
    if (PostgreSQLUtils::isTableExists("work_pl")) {
      db_query("drop table work_pl");
    }
    $iTotalLinhas  = pg_num_rows($rsBalancete);                                            
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oBalancete = db_utils::fieldsMemory($rsBalancete, $i);
      if (round($oBalancete->saldo_anterior, 2) + round($oBalancete->saldo_anterior_debito, 2)
           + round($oBalancete->saldo_anterior_credito,2) == 0) {
        continue;
      }
      
      $sDiaMesAno        =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oBalanceteRetorno = new stdClass();
      $oBalanceteRetorno->bveCodigoEntidade  = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oBalanceteRetorno->bveMesAnoMovimento = $sDiaMesAno;
      $oBalanceteRetorno->bveCodigoConta     = str_pad($oBalancete->estrutural, 20, 0,STR_PAD_RIGHT);
      
      $iTamanhoCampo = strlen($oInstituicao->codtrib);
      if ($iTamanhoCampo != 4) {
        
        $sMsg  = "Identificação do Orgão/Unidade da instituição ({$oInstituicao->codtrib}) está incorreto. \\n ";
        $sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
        $sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
        
        throw new Exception($sMsg);
      }
      
      $sOrgao                                             = substr($oInstituicao->codtrib, 0, 2);
      $sUnidade                                           = substr($oInstituicao->codtrib, 2, 2);
      $oBalanceteRetorno->bveCodigoOrgao                  = str_pad($sOrgao, 2, 0,STR_PAD_RIGHT);
      $oBalanceteRetorno->bveCodigoUnidadeOrcamentaria    = str_pad($sUnidade, 2, 0,STR_PAD_RIGHT);
      
      if ($oBalancete->sinal_anterior == 'D') {
        
        $oBalanceteRetorno->bveSaldoAnteriorContaDevedora = $this->corrigeValor($oBalancete->saldo_anterior, 13);
        $oBalanceteRetorno->bveSaldoAnteriorContaCredora  = $this->corrigeValor(0, 13);
      } else {
        
        $oBalanceteRetorno->bveSaldoAnteriorContaDevedora = $this->corrigeValor(0, 13);
        $oBalanceteRetorno->bveSaldoAnteriorContaCredora  = $this->corrigeValor($oBalancete->saldo_anterior, 13);
      }
      
      $oBalanceteRetorno->bveMovimentoContaDevedora =  $this->corrigeValor($oBalancete->saldo_anterior_debito, 13);
      $oBalanceteRetorno->bveMovimentoContaCredora  =  $this->corrigeValor($oBalancete->saldo_anterior_credito, 13);
      
      if ($oBalancete->sinal_final =='D') {
        
        $oBalanceteRetorno->bveSaldoAtualContaDevedora = $this->corrigeValor($oBalancete->saldo_final, 13);
        $oBalanceteRetorno->bveSaldoAtualContaCredora  = $this->corrigeValor(0, 13);
      } else {
        
        $oBalanceteRetorno->bveSaldoAtualContaDevedora = $this->corrigeValor(0, 13);
        $oBalanceteRetorno->bveSaldoAtualContaCredora  = $this->corrigeValor($oBalancete->saldo_final, 13);
      }
      
      $oBalanceteRetorno->bveDescricaoConta = substr(str_replace("\n", " ",$oBalancete->c60_descr), 0, 255);             
      $oBalanceteRetorno->bveTipoNivelConta = ($oBalancete->c61_reduz == 0?'S':'A');
      
      /**
       * Verificamos o nivel da conta
       */
      $sql     = "select fc_nivel_plano2005(rpad('{$oBalancete->estrutural}', 20, '0')) as nivel ";
      $rsNivel = pg_exec($sql);
      $iNivel  = db_utils::fieldsMemory($rsNivel, 0)->nivel;
      $oBalanceteRetorno->bveNumeroNivelConta   = $iNivel;
      /**
       * Verifica o sistema contabil da conta
       */
      $sSqlSistema  = "select c52_descrred ";
      $sSqlSistema .= "  from conplano  ";
      $sSqlSistema .= "      inner join consistema on c60_codsis = c52_codsis ";
      $sSqlSistema .= "where c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut = '{$oBalancete->estrutural}' ";
      $rsSistema    = db_query($sSqlSistema);
      $oBalanceteRetorno->bveSistemaContabil = "F";
      if (pg_num_rows($rsSistema) > 0) {
        $oBalanceteRetorno->bveSistemaContabil = db_utils::fieldsMemory($rsSistema, 0)->c52_descrred;
      }
      
      array_push($this->aDados, $oBalanceteRetorno);
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
                        "bveCodigoEntidade",
                        "bveMesAnoMovimento",
                        "bveCodigoConta",
                        "bveCodigoOrgao",
                        "bveCodigoUnidadeOrcamentaria",
                        "bveSaldoAnteriorContaDevedora",
                        "bveSaldoAnteriorContaCredora",
                        "bveMovimentoContaDevedora",
                        "bveMovimentoContaCredora",
                        "bveSaldoAtualContaDevedora",
                        "bveSaldoAtualContaCredora",
                        "bveDescricaoConta",
                        "bveTipoNivelConta",
                        "bveNumeroNivelConta",
                        "bveSistemaContabil"
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