<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @version $Revision: 1.1 $
 */
final class PadArquivoSigapDecreto extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Decreto";
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
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sSqlDecreto  = " select *, ";
    $sSqlDecreto .= "        case when (  ";
    $sSqlDecreto .= "           select count(distinct(o58_instit)) ";
    $sSqlDecreto .= "           from orcsuplemval s ";
    $sSqlDecreto .= "                 inner join orcdotacao on o58_coddot=s.o47_coddot and o58_anousu=s.o47_anousu ";
    $sSqlDecreto .= "           where s.o47_codsup = x.codsup ";
    $sSqlDecreto .= "           group by o47_codsup ";                                                 
    $sSqlDecreto .= "         ) = 1  ";
    $sSqlDecreto .= "        then false  ";
    $sSqlDecreto .= "        else true end     ";   
    $sSqlDecreto .= "        as interinstit ";
    $sSqlDecreto .= "  from (select o45_numlei as num_lei, ";
    $sSqlDecreto .= "               o45_dataini as data_lei, ";
    $sSqlDecreto .= "               o39_numero as num_decreto, ";    
    $sSqlDecreto .= "               o39_data as data_decreto, ";
    $sSqlDecreto .= "               o46_codsup as codsup, ";
    $sSqlDecreto .= "               o46_tiposup as tipo_credito, ";
    $sSqlDecreto .= "               o46_obs     as sinopse, ";
    $sSqlDecreto .= "               round(sum(case when o47_valor > 0 then o47_valor else 0 end),2) as valor_credito, ";
    $sSqlDecreto .= "               round(sum(case when o47_valor < 0 then abs(o47_valor) else 0 end),2) as valor_reducao ";
    $sSqlDecreto .= "          from orcprojeto  ";
    $sSqlDecreto .= "               inner join orclei              on o45_codlei = o39_codlei ";
    $sSqlDecreto .= "               inner join orcsuplem as suplem on o46_codlei = orcprojeto.o39_codproj ";
    $sSqlDecreto .= "               inner join orcsuplemval        on o47_codsup = o46_codsup ";
    $sSqlDecreto .= "               inner join orcdotacao d        on o58_coddot = o47_coddot ";
    $sSqlDecreto .= "                                             and o58_anousu =".db_getsession("DB_anousu");
    $sSqlDecreto .= "                                             and o58_instit in ({$sListaInstit}) ";                                            
    $sSqlDecreto .= "               inner join orcsuplemlan on orcsuplemlan.o49_codsup= o46_codsup ";
    $sSqlDecreto .= "               left join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj ";
    $sSqlDecreto .= "         where o39_anousu=".db_getsession("DB_anousu");
    $sSqlDecreto .= "           and o49_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
    $sSqlDecreto .= "         group by o45_numlei,o45_dataini,o39_numero,o39_data,o46_codsup,o46_tiposup,o46_obs ";   
    $sSqlDecreto .= "         order by o45_numlei,o45_dataini,o39_numero,o39_data ";
    $sSqlDecreto .= "      ) as x ";
    $rsDecretos   = db_query($sSqlDecreto);
    $iTotalLinhas = pg_num_rows($rsDecretos);
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oDecreto = db_utils::fieldsMemory($rsDecretos, $i);
      switch ($oDecreto->tipo_credito) {
        
        case '1001':
         
          $iTipo   = '1';
          $iOrigem = '5';
          break;
        case '1002':
       
          $iTipo   = '1';
          $iOrigem = '3';
          break;
        case '1003':
         
          $iTipo   = '1';
          $iOrigem = '1';
          break;
        case '1004':
         
          $iTipo   = '1';
          $iOrigem = '2';
          break;
        case '1005':
         
          $iTipo   = '1';
          $iOrigem = '4';
          break;
        case '1006':
         
          $iTipo   = '2';
          $iOrigem = '5';
          break;
        case '1007':
        
          $iTipo   = '2';
          $iOrigem = '3';
          break;
        case'1008':

          $iTipo   = '2';
          $iOrigem = '1';
          break;
        case  '1009':
        
          $iTipo   = '2';
          $iOrigem = '2';
          break;
        case '1010':

          $iTipo   = '2';
          $iOrigem = '4';
          break;   
        case '1011':
         
          $iTipo   = '3';
          $iOrigem = '1';
          break;
        case'1012':
       
          $iTipo   = '2';
          $iOrigem = '1';
          break;
        case '1013':
       
          $iTipo   = '2';
          $iOrigem = '3';
          break;
        case  '1014':
       
          $iTipo   = '1';
          $iOrigem = '5';
          break;
        case '1015':
     
          $iTipo   = '1';
          $iOrigem = '5';
          break;
        case '1016':
      
          $iTipo   = '1';
          $iOrigem = '5';
          break;
      } 
      
      if ($oDecreto->interinstit == "t") {
        $iOrigem = 6;
      }
      $sDiaMesAno      =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oDecretoRetorno = new stdClass();
      $oDecretoRetorno->decCodigoEntidade        = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oDecretoRetorno->decMesAnoMovimento       = $sDiaMesAno;
      $oDecretoRetorno->decNumeroLei             = $oDecreto->num_lei;
      $oDecretoRetorno->decDataLei               = $oDecreto->data_lei;
      $oDecretoRetorno->decNumeroDecreto         = $oDecreto->num_decreto;
      $oDecretoRetorno->decDataDecreto           = $oDecreto->data_decreto;
      $oDecretoRetorno->decValorCreditoAdicional = $this->corrigeValor($oDecreto->valor_credito, 13);
      $oDecretoRetorno->decValorReducaoDotacao   = $this->corrigeValor($oDecreto->valor_reducao, 13);
      $oDecretoRetorno->decTipoCreditoAdicional  = $iTipo;
      $oDecretoRetorno->decOrigemRecurso         = $iOrigem;
      $oDecretoRetorno->decSinopse               = substr($oDecreto->sinopse,0, 255);
      if (trim($oDecreto->sinopse) == "") {
        $oDecretoRetorno->decSinopse = "Sinopse do decreto sem conteudo";
        
      }
      $this->aDados[] =  $oDecretoRetorno; 
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
                        "decCodigoEntidade",
                        "decMesAnoMovimento",
                        "decNumeroLei",
                        "decDataLei",
                        "decNumeroDecreto",
                        "decDataDecreto",
                        "decValorCreditoAdicional",
                        "decValorReducaoDotacao",
                        "decTipoCreditoAdicional",
                        "decOrigemRecurso",
                        "decSinopse",
                       );
    return $aElementos;  
  }
  
  private function corrigeValor ($valor, $quant) {
    
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