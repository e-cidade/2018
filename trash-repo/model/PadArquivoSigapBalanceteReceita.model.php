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
 * Prove dados para a geração do arquivo do balancete da receita no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapBalanceteReceita extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "BalanceteReceita";
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
    $sWhere               =  " o70_instit in ({$sListaInstit})";
    $sSqlDadosBalancete  = db_receitasaldo(11, 1, 3, true, $sWhere, $iAno, $this->sDataInicial, $this->sDataFinal, true);
    
    $sSqlBalancete  = "select case  when {$iAno} <= 2007 then ";
    $sSqlBalancete .= "         substr(o57_fonte,2,14) "; 
    $sSqlBalancete .= "       else  ";
    $sSqlBalancete .= "         case  when fc_conplano_grupo({$iAno}, substr(o57_fonte,1,1) || '%', 9000 ) is false then "; 
    $sSqlBalancete .= "           substr(o57_fonte, 2, 14) "; 
    $sSqlBalancete .= "         else  ";
    $sSqlBalancete .= "          substr(o57_fonte,1,15) "; 
    $sSqlBalancete .= "         end  ";
    $sSqlBalancete .= "       end as o57_fonte, ";
    $sSqlBalancete .= "       o57_descr, ";
    $sSqlBalancete .= "       saldo_inicial, ";
    $sSqlBalancete .= "       saldo_arrecadado_acumulado, ";  
    $sSqlBalancete .= "       o15_codtri as o70_codigo, ";
    $sSqlBalancete .= "       x.o70_codrec,           ";
    $sSqlBalancete .= "       coalesce(o70_instit,0) as o70_instit, ";
    $sSqlBalancete .= "       fc_nivel_plano2005(rpad(x.o57_fonte,20,0)) as nivel, ";
    $sSqlBalancete .= "       saldo_prevadic_acum ";
    $sSqlBalancete .= "  from ({$sSqlDadosBalancete}) as x  ";
    $sSqlBalancete .= "       left join orcreceita on orcreceita.o70_codrec = x.o70_codrec and o70_anousu={$iAno} ";
    $sSqlBalancete .= "       left join orctiporec on orcreceita.o70_codigo = o15_codigo ";
    $sSqlBalancete .= " order by o57_fonte asc ";
    $rsBalancete    = db_query($sSqlBalancete);
    $iTotalLinhas = pg_num_rows($rsBalancete);
    if (PostgreSQLUtils::isTableExists("work_receita")) {
      db_query("drop table work_receita");
    }
    
    for ($i = 1; $i < $iTotalLinhas; $i++) {
      
      $oReceita            = db_utils::fieldsMemory($rsBalancete, $i);
      $sSqlCaracteristica  = "select o70_concarpeculiar  ";
      $sSqlCaracteristica .= "  from orcreceita ";
      $sSqlCaracteristica .= " where o70_anousu = {$iAno} and "; 
      $sSqlCaracteristica .= "       o70_codrec = {$oReceita->o70_codrec}";
      $rsCaracteristica    = @pg_query($sSqlCaracteristica);
      $iCaracteristicaPeculiar = db_utils::fieldsMemory($rsCaracteristica, 0)->o70_concarpeculiar;
      
      if ($iAno > 2007) {

        if (substr($oReceita->o57_fonte, 0, 1) == "9") {
          if ($iCaracteristicaPeculiar == 0 and 1==2) {
                $iCaracteristicaPeculiar = 101;
          }
        } else {
          $oReceita->nivel  = $oReceita->nivel - 1;
        }
      } else {
        $oReceita->nivel = $oReceita->nivel - 1;
      }
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      
      $oReceitaRetorno = new stdClass();
      
      $oReceitaRetorno->breCodigoEntidade                 = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oReceitaRetorno->breMesAnoMovimento                = $sDiaMesAno;
      
      if (strlen($oReceita->o57_fonte) < 15) {
        $oReceita->o57_fonte = str_pad($oReceita->o57_fonte, 15, "0", STR_PAD_RIGHT);
      }
      $oReceitaRetorno->breContaReceita                   = str_pad($oReceita->o57_fonte, 20, 0, STR_PAD_RIGHT);

      $iTamanhoCampo = strlen($oInstituicao->codtrib);
      if ($iTamanhoCampo != 4) {
        
        $sMsg  = "Identificação do Orgão/Unidade da instituição ({$oInstituicao->codtrib}) está incorreto. \\n ";
        $sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
        $sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
        
        throw new Exception($sMsg);
      }
      
      $sOrgao                                             = substr($oInstituicao->codtrib, 0, 2);
      $sUnidade                                           = substr($oInstituicao->codtrib, 2, 2);
      $oReceitaRetorno->breCodigoOrgao                    = str_pad($sOrgao, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno->breCodigoUnidadeOrcamentaria      = str_pad($sUnidade, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno->breValorPrevisaoAtualizada        = str_pad(number_format(abs($oReceita->saldo_prevadic_acum),2,".",""), 13,'0', STR_PAD_LEFT);
      
      $nSaldoInicial = $oReceita->saldo_inicial;
      /**
       * Verificamos o sinal do saldo inicial;
       */
      if ($nSaldoInicial < 0 ){
        $nSaldoInicial = str_pad(number_format(abs($nSaldoInicial),2,".",""), 13,'0', STR_PAD_LEFT);
      } else  {
        $nSaldoInicial = str_pad(number_format(abs($nSaldoInicial),2,".",""), 13,'0', STR_PAD_LEFT);
      }
      $oReceitaRetorno->breValorReceitaOrcada  = $nSaldoInicial;
      /**
       * Verificamos so saldo do saldo acumulado da receita
       */
      if ($oReceita->saldo_arrecadado_acumulado < 0){
        $nArrecadadoAcumulado = str_pad(number_format(abs($oReceita->saldo_arrecadado_acumulado) 
                                                           ,2,".",""), 13,'0', STR_PAD_LEFT);
      } else {   
        $nArrecadadoAcumulado = str_pad(number_format(abs($oReceita->saldo_arrecadado_acumulado), 
                                                            2,".",""), 13,'0', STR_PAD_LEFT);
      }
      $oReceitaRetorno->breValorReceitaRealizada   = $nArrecadadoAcumulado;
      $oReceitaRetorno->breCodigoRecursoVinculado  = str_pad($oReceita->o70_codigo, 6, "0", STR_PAD_LEFT); 
      $oReceitaRetorno->breDescricaoContaReceita   = substr($oReceita->o57_descr, 0, 255); 
      $oReceitaRetorno->breNivelContaReceita       = ($oReceita->o70_codrec == 0?'S':'A');
      
      $oReceitaRetorno->breNumeroNivelContaReceita = str_pad($oReceita->nivel, 2, "0", STR_PAD_LEFT);
      $oReceitaRetorno->breCaracteristicaPeculiar  = str_pad($iCaracteristicaPeculiar, 3, "0", STR_PAD_LEFT);
      
      $this->aDados[]     = $oReceitaRetorno; 
      $array_teste[$i][0] = $oReceita->o57_fonte;
      $array_teste[$i][1] = ($oReceita->o70_codrec==0?'S':'A');
      $array_teste[$i][2] = $oReceita->nivel;
      $array_teste[$i][3] = abs($oReceita->saldo_inicial);
    }
    /**
     * Valida os valores, e se as contas possuem estruturais corretos
     */
    $maxnivelanalitico = 0;
    $maxnivelsintetico = 0;
    for ($x = 1; $x <= sizeof($array_teste); $x++) {
      
      if ($array_teste[$x][1] == "A") {
         if ($array_teste[$x][2] > $maxnivelanalitico) {
           $maxnivelanalitico = $array_teste[$x][2];
         }
       }
       
       if ($array_teste[$x][1] == "S") {
         if ($array_teste[$x][2] > $maxnivelsintetico) {
           $maxnivelsintetico = $array_teste[$x][2];
         }
       }
    }
    
    $numerro=0;
    $array_erro = array();
    for ($nivel_atual = $maxnivelsintetico; $nivel_atual > 0; $nivel_atual--) {

       for ($x=1; $x <= sizeof($array_teste); $x++) {

         if (@$array_teste[$x][1] == "S" && $array_teste[$x][2] == $nivel_atual) {

           $estrutural_sintetico = $array_teste[$x][0];
           $soma_sintetico = $array_teste[$x][3];
           $soma_analitico = 0;
           for ($y = $x + 1; $y < sizeof($array_teste); $y++) {

             if ($array_teste[$y][1] == "S" and $array_teste[$y][2] <= $nivel_atual) {
               break;
             } elseif ($array_teste[$y][1] == "A" and $array_teste[$y][2] > $nivel_atual) {
               $soma_analitico += $array_teste[$y][3];
               $array_teste[$numerro]["contas"][] = $array_teste[$y][0];
             } elseif ($array_teste[$y][1] == "A" and $array_teste[$y][2] <= $nivel_atual and 1==2) {
                    //         echo "provavel erro " . $array_teste[$y][0] . "<br>";
               $array_erro[$numerro][0] = $array_teste[$y][0];
               $array_erro[$numerro][1] = 1;
               $numerro++;
               break;
              }
            }
            if (round($soma_sintetico,2) != round($soma_analitico,2)) {
                  //       die("xxx: " . $array_teste[$x][0] . " - sint: $soma_sintetico - anal: $soma_analitico");
              $array_erro[$numerro][0] = $estrutural_sintetico;
              $array_erro[$numerro][1] = 2;
              $array_erro[$numerro]["soma"] = "Sintética:{$soma_sintetico} Analíticas:{$soma_analitico}";
              $numerro++;
            }

          }
        }
      }
    if (sizeof($array_erro) > 0) {
      
      $this->addLog("\n-----------------------------\nBalancete Receita:\n  PROVAVEIS ERROS NOS ESTRUTURAIS:\n");
      for ($x=0; $x < sizeof($array_erro); $x++) {
        $this->addLog("Estrutural: ".$array_erro[$x][0] . " Valor:{$array_erro[$x]["soma"]}\n");
        for ($i = 0; $i < count($array_teste[$x]["contas"]);$i++) {
           //$this->addLog("    Conta:{$array_teste[$x]["contas"][$i]}\n");
        }
      }
    }
    /**
     * verifica contas sem reduzido e estao na orcreceita
     */
     $sqlorcreceita = "select orcreceita.*, c60_estrut
                         from orcreceita 
                              left join conplano on o70_codfon = c60_codcon and o70_anousu = c60_anousu
                              left join conplanoreduz on o70_codfon = c61_codcon and o70_anousu = c61_anousu and o70_instit = c61_instit 
                        where o70_anousu = {$iAno} and c61_reduz is null";
          $resultorcreceita = pg_query($sqlorcreceita) or die($sqlorcreceita);
          if (pg_num_rows($resultorcreceita) > 0) {

            $this->addLog("\nERRO - RECEITAS DO ORCAMENTO SEM REDUZIDO NO PLANO DE CONTAS:\n");

            for ($x=0;$x < pg_numrows($resultorcreceita); $x++) {
              
              $o70_codrec = pg_result($resultorcreceita,$x,"o70_codrec");
              $o70_codfon = pg_result($resultorcreceita,$x,"o70_codfon");
              $c60_estrut = pg_result($resultorcreceita,$x,"c60_estrut");

              $this->addLog("REDUZIDO ORCAMENTO: $o70_codrec - CODCON: $o70_codfon - ESTRUTURAL: $c60_estrut\n");

            }
            
            $this->addLog("\n".str_repeat("-", 30)."\n");
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
                         "breCodigoEntidade",
                         "breMesAnoMovimento",
                         "breContaReceita",    
                         "breCodigoOrgao",
                         "breCodigoUnidadeOrcamentaria",
                         "breValorReceitaOrcada",
                         "breValorReceitaRealizada",
                         "breCodigoRecursoVinculado",
                         "breDescricaoContaReceita",
                         "breNivelContaReceita",
                         "breNumeroNivelContaReceita",
                         "breCaracteristicaPeculiar",
                         "breValorPrevisaoAtualizada"
                       );
                       
    return $aElementos; 
  }
}
?>