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
 * Prove dados para a geração do arquivo do balancete de verificacao mensal, dos ano anterior
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.2 $
 */
final class PadArquivoSigapBalanceteMovimentacaoAnterior extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "BalanceteVerificacaoMovimentacaoAnterior";
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
    $iAnoUsu      = db_getsession("DB_anousu");
    //$this->sDataInicial = "{$iAnoUsu}-01-01";
    //$this->sDataFinal   = "{$iAnoUsu}-12-31";
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $rsBalancete   = $this->db_planocontassaldo_matriz_mes($iAnoUsu
                                                         , $this->sDataInicial, $this->sDataFinal, false,'', '', false);
    if (PostgreSQLUtils::isTableExists("work_pl")) {
      db_query("drop table work_pl");
    }
    $iTotalLinhas  = pg_num_rows($rsBalancete);
    $aMeses = array( 1 => "janeiro",
                     2 => "fevereiro", 
                     3 => "marco",
                     4 => "abril", 
                     5 => "maio", 
                     6 => "junho", 
                     7 => "julho", 
                     8 => "agosto", 
                     9 => "setembro", 
                    10 => "outubro", 
                    11 => "novembro", 
                    12 => "dezembro", 
                   );
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oBalancete = db_utils::fieldsMemory($rsBalancete, $i);
      $sDiaMesAno        =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oBalanceteRetorno = new stdClass();
      $oBalanceteRetorno->bbaCodigoEntidade   = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oBalanceteRetorno->bbaMesAnoMovimento = $sDiaMesAno;
      $oBalanceteRetorno->bbaCodigoConta     = str_pad($oBalancete->estrutural, 20, 0,STR_PAD_RIGHT);
      $oBalanceteRetorno->bbaCodigoOrgaoUnidadeOrcamentaria = str_pad($oInstituicao->codtrib, 4, "0",STR_PAD_LEFT);
      foreach ($aMeses as $iMes => $descricao) {
        
        $oBalanceteRetorno->{"bbaDebito".ucfirst($descricao)} = $oBalancete->{"debito_{$descricao}"};
        $oBalanceteRetorno->{"bbaCredito".ucfirst($descricao)} = $oBalancete->{"credito_{$descricao}"};
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
                        "bbaCodigoEntidade",
                        "bbaMesAnoMovimento",
                        "bbaCodigoConta",
                        "bbaCodigoOrgaoUnidadeOrcamentaria",
                        "bbaDebitoJaneiro",
                        "bbaCreditoJaneiro",
                        "bbaDebitoFevereiro",
                        "bbaCreditoFevereiro",
                        "bbaDebitoMarco",
                        "bbaCreditoMarco",
                        "bbaDebitoAbril",
                        "bbaCreditoAbril",
                        "bbaDebitoMaio",
                        "bbaCreditoMaio",
                        "bbaDebitoJunho",
                        "bbaCreditoJunho",  
                        "bbaDebitoJulho",
                        "bbaCreditoJulho",
                        "bbaDebitoAgosto",
                        "bbaCreditoAgosto",
                        "bbaDebitoSetembro",
                        "bbaCreditoSetembro",
                        "bbaDebitoOutubro",
                        "bbaCreditoOutubro",
                        "bbaDebitoNovembro",
                        "bbaCreditoNovembro",
                        "bbaDebitoDezembro",
                        "bbaCreditoDezembro",                    
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
  
  public function db_planocontassaldo_matriz_mes($iAnoUsu, $sDataIni, $sDataFinal, 
                                                 $lRetornaSql = false, 
                                                 $where='', 
                                                 $sEstruturalInicial ='',
                                                 $lAcumularReduzido = true,
                                                 $lEncerramento  =false) {

    $sCondicao = '';                                               
    if (!empty($sWhere)) {
      $sCondicao = " and {$sWhere}";
    }
    $pesq_estrut = "";
    if (!empty($sEstruturalInicial)) {
      $sCondicao .= "  and p.c60_estrut like '{$sEstruturalInicial}%' ";    
    }
    $aMeses = array( 1 => "janeiro",
                     2 => "fevereiro", 
                     3 => "marco",
                     4 => "abril", 
                     5 => "maio", 
                     6 => "junho", 
                     7 => "julho", 
                     8 => "agosto", 
                     9 => "setembro", 
                    10 => "outubro", 
                    11 => "novembro", 
                    12 => "dezembro", 
                   );
    $sSqlPrincipal = " select estrut_mae,"; 
    $sSqlPrincipal .= "        estrut, ";
    $sSqlPrincipal .= "        c61_reduz,";
    $sSqlPrincipal .= "        c61_codcon,";
    $sSqlPrincipal .= "        c61_codigo,";
    $sSqlPrincipal .= "        c60_descr,";
    $sSqlPrincipal .= "        c60_finali,";
    $sSqlPrincipal .= "        c61_instit,";
    $sSqlPrincipal .= "        round(substr(retornojaneiro,3,13)::float8,2)::float8 as saldo_anterior,";
    /**
     * Montamos os valores debito/credito por mes
     */
    foreach ($aMeses as $iMes => $descricao) {
      
      $sSqlPrincipal .= "        round(substr(retorno{$descricao},16,13)::float8,2)::float8 as debito_{$descricao},";
      $sSqlPrincipal .= "        round(substr(retorno{$descricao},29,13)::float8,2)::float8 as credito_{$descricao},";
      
    }
    $sSqlPrincipal .= "        round(substr(retornodezembro,42,13)::float8,2)::float8 as saldo_final,";
    $sSqlPrincipal .= "        substr(retornojaneiro,55,1)::varchar(1) as sinal_anterior,";
    $sSqlPrincipal .= "        substr(retornodezembro,56,1)::varchar(1) as sinal_final";
    $sSqlPrincipal .= "  from  (select p.c60_estrut as estrut_mae,";
    $sSqlPrincipal .= "                p.c60_estrut as estrut,";
    $sSqlPrincipal .= "                c61_reduz,";
    $sSqlPrincipal .= "                c61_codcon,";
    $sSqlPrincipal .= "                c61_codigo,";
    $sSqlPrincipal .= "                p.c60_descr,";
    $sSqlPrincipal .= "                p.c60_finali,";
    $sSqlPrincipal .= "                r.c61_instit,";
    /**
     * Criamos um fc_planosaldo para cada mes
     */
    $sEncerramento    = $lEncerramento?'true':'false';
    foreach ($aMeses as $iMes => $descricao) {
      
      $sSqlPrincipal .= "fc_planosaldosigned({$iAnoUsu},c61_reduz,";
      $sSqlPrincipal .= "                   '{$iAnoUsu}-{$iMes}-01'::date,";
      $sSqlPrincipal .= "                   ('{$iAnoUsu}-{$iMes}-'||fc_ultimodiames({$iAnoUsu},{$iMes}))::date,
                                             $sEncerramento) as retorno".strtolower($descricao); 
      
      if ($iMes != 12) {
        $sSqlPrincipal .= ", ";                                             
      }
    }
    $sSqlPrincipal .= "           from conplanoexe e";
    $sSqlPrincipal .= "                inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz"; 
    $sSqlPrincipal .= "                inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu";
    $sSqlPrincipal .= "                left outer join consistema on c60_codsis = c52_codsis";
    $sSqlPrincipal .= "          where c62_anousu = $iAnoUsu $sCondicao) as x";  
    
    /**
     * Criamos uma tabela temporaria, para podermos somar os valroes das contas sinteticas
     */
    $sSqlTabelaTemporaria  = "create temporary table work_pl ( ";
    $sSqlTabelaTemporaria .= "       estrut_mae varchar(15), "; 
    $sSqlTabelaTemporaria .= "       estrut varchar(15), ";
    $sSqlTabelaTemporaria .= "       c61_reduz integer, ";
    $sSqlTabelaTemporaria .= "       c61_codcon integer, ";
    $sSqlTabelaTemporaria .= "       c61_codigo integer, ";
    $sSqlTabelaTemporaria .= "       c60_descr varchar(50), ";
    $sSqlTabelaTemporaria .= "       c60_finali text, ";
    $sSqlTabelaTemporaria .= "       c61_instit integer, ";
    $sSqlTabelaTemporaria .= "       saldo_anterior float8, ";
    foreach ($aMeses as $iMes => $descricao) {
      
      $sSqlTabelaTemporaria .= " debito_{$descricao}  float8,";
      $sSqlTabelaTemporaria .= " credito_{$descricao} float8,";
    }
    $sSqlTabelaTemporaria .= "saldo_final float8, ";
    $sSqlTabelaTemporaria .= "sinal_anterior varchar(1), ";
    $sSqlTabelaTemporaria .= "sinal_final varchar(1)) ";
    
    db_query($sSqlTabelaTemporaria);
    //   pg_exec("create temporary table work_plano as $sql");
    db_query("create index work_pl_estrut on work_pl(estrut)");
    db_query("create index work_pl_estrutmae on work_pl(estrut_mae)");
    
    $tot_anterior    = 0;
    $tot_debito_1bin = 0;
    $tot_debito_2bin = 0;
    $tot_debito_3bin = 0;
    $tot_debito_4bin = 0;
    $tot_debito_5bin = 0;
    $tot_debito_6bin = 0;
    
    $tot_credito_1bin = 0;
    $tot_credito_2bin = 0;
    $tot_credito_3bin = 0;
    $tot_credito_4bin = 0;
    $tot_credito_5bin = 0;
    $tot_credito_6bin = 0;
    
    $tot_saldo_final     = 0;
    $work_planomae    = array();
    $work_planoestrut = array();
    $work_plano = array();
    $seq = 0;
    
    for ($i = 0; $i < pg_num_rows($rsDados); $i++) {

      $oLinha = db_utils::fieldsmemory($rsDados, $i);
      
      if ($oLinha->sinal_anterior == "C") {
        $oLinha->saldo_anterior *= -1;
      }
      if ($oLinha->sinal_final == "C") {        
       $oLinha->saldo_final *= -1;
      }
      $oSomatorio            = new stdClass();
      $oSomatorio->tot_anterior  = $oLinha->saldo_anterior;
      /**
       * criamos as propriedades ppara janeiro/Dezembro
       */
      foreach ($aMeses as $iMes => $descricao) {
        
        $oSomatorio->{"tot_debito_{$descricao}"}  = $oLinha->{"debito_{$descricao}"};
        $oSomatorio->{"tot_credito_{$descricao}"} = $oLinha->{"credito_{$descricao}"};  
      }
      $tot_saldo_final      = $oLinha->saldo_final;   
      
      if ($lAcumularReduzido) {
        $key = array_search("$oLinha->estrut_mae",$work_planomae);
      } else {
        $key = false;
      }
      
      if (!$key) {  // não achou  
        
        $work_planomae[$seq]    = $oLinha->estrut_mae;  
        $work_planoestrut[$seq] = $oLinha->estrut;
        
        $work_plano[$seq] =  array( 0 => "$oLinha->c61_reduz",
                                    1 => "$oLinha->c61_codcon",
                                    2 => "$oLinha->c61_codigo",
                                    3 => "$oLinha->c60_descr",
                                    4 => "$oLinha->c60_finali",
                                    5 => "$oLinha->c61_instit",
                                    6 => "$oLinha->saldo_anterior",
                                    );
                                    
        $iInicio = 7;
        foreach ($aMeses as $iMes => $descricao) {
          
           $work_plano[$seq][$iInicio] = $oLinha->{"debito_{$descricao}"};
           $iInicio++; 
           $work_plano[$seq][$iInicio] = $oLinha->{"credito_{$descricao}"};
           $iInicio++;
        }
        $work_plano[$seq][30]   = "$oLinha->saldo_final";
        $work_plano[$seq][31] = "$oLinha->sinal_anterior";
        $work_plano[$seq][32] = "$oLinha->sinal_final";
        $seq = $seq+1;
        
      } else {
        
        $work_plano[$key][6] += $oLinha->saldo_anterior;
        $iInicio              = 7;
        /**
         * soma debito/credito nos meses
         */
        foreach ($aMeses as $iMes => $descricao) {
          
           $work_plano[$seq][$iInicio] += $oLinha->{"debito_{$descricao}"};
           $iInicio++; 
           $work_plano[$seq][$iInicio] += $oLinha->{"credito_{$descricao}"};
           $iInicio++;
        }
        $work_plano[$key][30] += $oLinha->saldo_final;
      }
      $estrutural = $oLinha->estrut;
      for($ii = 1;$ii < 10;$ii++) {
        
        $estrutural = db_le_mae_conplano($estrutural);
        $nivel = db_le_mae_conplano($estrutural,true);
        
        $key = array_search("$estrutural",$work_planomae);
        if ($key === false ) {  // não achou  
          // busca no banco e inclui
          $res = pg_query("select c60_descr,c60_finali,c60_codcon from conplano where c60_anousu={$iAnoUsu}
                          and c60_estrut = '$estrutural'");
          if($res == false || pg_num_rows($res) == 0) {
            throw new Exception("Está faltando cadastrar esse estrutural na contabilidade.\nNível : $nivel  Estrutural : $estrutural");
          }
          $oConta = db_utils::fieldsmemory($res,0);   
          
          $work_planomae[$seq]= $estrutural;  
          $work_planoestrut[$seq]= '';
          $work_plano[$seq] = array(0  => 0,
                                    1  => 0,
                                    2  => $oConta->c60_codcon, 
                                    3  => $oConta->c60_descr,
                                    4  => $oConta->c60_finali,
                                    5  => 0 ,
                                    6  => $oLinha->saldo_anterior,
                                    30 => $oLinha->saldo_final,
                                    31 => $oLinha->sinal_anterior,
                                    32 => $oLinha->sinal_final);
          $iInicio = 7;                           
          foreach ($aMeses as $iMes => $descricao) {
            
            @ $work_plano[$seq][$iInicio] = $oLinha->{"debito_{$descricao}"};
             $iInicio++; 
            @ $work_plano[$seq][$iInicio] = $oLinha->{"credito_{$descricao}"};
             $iInicio++;
          }                          
        $seq ++;
        } else {
          
          $work_plano[$key][6] += $oLinha->saldo_anterior;
          $iInicio              = 7;
          /**
           * soma debito/credito nos meses
           */
          foreach ($aMeses as $iMes => $descricao) {
            
             @$work_plano[$seq][$iInicio] += $oLinha->{"debito_{$descricao}"};
             $iInicio++; 
             @$work_plano[$seq][$iInicio] += $oLinha->{"credito_{$descricao}"};
             $iInicio++;
          }
          $work_plano[$key][30] += $oLinha->saldo_final;
        }
        if ($nivel == 1) {
          break;
        }
      }
    }
    for ($i = 0; $i < sizeof($work_planomae); $i++) {
      
      $mae            = $work_planomae[$i];
      $estrut         = $work_planoestrut[$i];
      $c61_reduz      = $work_plano[$i][0];
      $c61_codcon     = $work_plano[$i][1];
      $c61_codigo     = $work_plano[$i][2];
      $c60_descr      = $work_plano[$i][3]; 
      $c60_finali     = $work_plano[$i][4]; 
      $c61_instit     = $work_plano[$i][5]; 
      $saldo_anterior = $work_plano[$i][6];  
      $saldo_final     = $work_plano[$i][30];
      $sinal_anterior  = $work_plano[$i][31];
      $sinal_final     = $work_plano[$i][32];
      
      $sql = "insert into work_pl 
      values ('$mae',
      '$estrut',
      $c61_reduz, 
      $c61_codcon,
      $c61_codigo,
      '$c60_descr',
      '$c60_finali',
      $c61_instit,
      $saldo_anterior,";
      $iInicio = 7;
      foreach ($aMeses as $iMes => $descricao) {

        $sql .= "{$work_plano[$i][$iInicio]},";
        $iInicio ++;
        $sql .= "{$work_plano[$i][$iInicio]},";
      }
      $sql .= "$saldo_final,
      '$sinal_anterior',
      '$sinal_final')
      
      ";
      pg_exec($sql);     
    } 
    
    $sSqlRetorno  = "select case when c61_reduz = 0 then"; 
    $sSqlRetorno .= "         estrut_mae"; 
    $sSqlRetorno .= "       else ";
    $sSqlRetorno .= "        estrut ";
    $sSqlRetorno .= "      end as estrutural,";
    $sSqlRetorno .= "      c61_reduz,";
    $sSqlRetorno .= "      c61_codcon,";
    $sSqlRetorno .= "      c61_codigo,";
    $sSqlRetorno .= "      c60_descr,";
    $sSqlRetorno .= "      c60_finali,";
    $sSqlRetorno .= "      c61_instit,";
    $sSqlRetorno .= "       saldo_anterior as saldo_anterior, ";
    foreach ($aMeses as $iMes => $descricao) {
      
      $sSqlRetorno .= " debito_{$descricao},";
      $sSqlRetorno .= " credito_{$descricao},";
    }
    $sSqlRetorno .= " saldo_final as saldo_final,";
    $sSqlRetorno .= " case when saldo_anterior < 0 then  'C'";
    $sSqlRetorno .= "      when saldo_anterior > 0 then 'D'";
    $sSqlRetorno .= "      else ' ' ";
    $sSqlRetorno .= "      end as  sinal_anterior,";
    $sSqlRetorno .= "  case when saldo_final < 0 then 'C'";
    $sSqlRetorno .= "       when saldo_final> 0 then 'D'";
    $sSqlRetorno .= "       else ' '";
    $sSqlRetorno .= "       end as  sinal_final";
    $sSqlRetorno .= "  from work_pl ";
    $sSqlRetorno .= "  order by estrut_mae,estrut ";
    
    if($lRetornaSql == false) {

      $result_final = pg_exec($sSqlRetorno);
      return $result_final;
    }else{
      return $sql;
    }   
  }
}

?>