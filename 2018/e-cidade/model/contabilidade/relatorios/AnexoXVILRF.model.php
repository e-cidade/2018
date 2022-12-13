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

require_once ("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");

/**
 * classe para controle dos valores do Anexo XVI do DEM.DOS IMP.E DESP.COM SAÚDE
 * @package    contabilidade
 * @subpackage relatorios
 * @author Rafael Lopes rafael.lopes@dbseller.com.br
 *
 */

class AnexoXVILRF extends RelatoriosLegaisBase  {


  /**
   * @param integer $iAnoUsu          ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio  85
   * @param integer $iCodigoPeriodo   Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   *
   * @return array - Colecao de stdClass
   */
  public function getDados() {


    $aLinhas          = array();
    $oDaoPeriodo      = db_utils::getDao("periodo");
    $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo        = db_query($sSqlDadosPeriodo);
    $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0);

    $sDataInicial     = "{$this->iAnoUsu}-01-01";
    $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
    $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";


    $sWhereReceita    = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa    = " o58_instit in ({$this->getInstituicoes()}) ";

    $rsReceita  = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $this->iAnoUsu, $sDataInicial, $sDataFinal);
    $rsDespesa  = db_dotacaosaldo( 8, 2, 3, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);
    //$rsDespesa  = db_dotacaosaldo( 7, 3, 4, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);



    $iTotalLinhasReceita = pg_num_rows($rsReceita);
    $iTotalLinhasDespesa = pg_num_rows($rsDespesa);

    $aLinhasTotalizadorasImpostoLiquido  = array(2, 3, 4, 5, 6, 7, 8, 9);
    $aLinhasTotalizadorasTransfConst     = array(11, 12, 13, 14, 15, 16, 17, 18, 19);
    $aLinhasTotalizadorasRecursoSus      = array(21, 22, 23, 24);
    $aLinhasTotalizadorasDespCorrente    = array(30, 31, 32);
    $aLinhasTotalizadorasDespCapital     = array(34, 35, 36);
    $aLinhasTotalizadorasTotal54         = array(50, 51, 52);

    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();

    for ($iLinha = 1; $iLinha <= count($aLinhasRelatorio); $iLinha++) {


      $aLinhasRelatorio[$iLinha]->setPeriodo($this->iCodigoPeriodo);
      $aColunasRelatorio  = $aLinhasRelatorio[$iLinha]->getCols($this->iCodigoPeriodo);

      $oLinha              = new stdClass();
      $oLinha->totalizar   = $aLinhasRelatorio[$iLinha]->isTotalizador();
      $oLinha->descricao   = $aLinhasRelatorio[$iLinha]->getDescricaoLinha();
      $oLinha->colunas     = $aColunasRelatorio;
      $oLinha->nivellinha  = $aLinhasRelatorio[$iLinha]->getNivel();
      $oLinha->desdobrar   = false;

      $aParametros         = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());

      if ($aParametros->desdobrarlinha && $aLinhasRelatorio[$iLinha]->desdobraLinha()) {
        $oLinha->desdobrar  = true;
      }

      $oLinha->insc_rp_np = 0;
      $oLinha->emp_atebim = 0;
      foreach ($aColunasRelatorio as $oColuna) {

        $oLinha->{$oColuna->o115_nomecoluna} = 0;
        if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {
          $oColuna->o116_formula = '';
        }
      }

      $nPercentual          = 0;
      $nPercentualLiquidado = 0;
      $nPercentualEmpenhado = 0;
      $nPercentualSus       = 0;

      $oLinha->percentual          = $nPercentual;
      $oLinha->percentualLiquidado = $nPercentualLiquidado;
      $oLinha->percentualEmpenhado = $nPercentualEmpenhado;

      if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {

        $aValoresColunasLinhas = $aLinhasRelatorio[$iLinha]->getValoresColunas(null,
                                                                               null,
                                                                               $this->getInstituicoes(),
                                                                               $this->iAnoUsu);


        foreach($aValoresColunasLinhas as $indice => $oValor) {

          foreach ($oValor->colunas as $iColunas => $oValorColuna){

            $oLinha->{$oValor->colunas[$iColunas]->o115_nomecoluna} += $oValor->colunas[$iColunas]->o117_valor;
          }

        }

        if ($iLinha >= 1 && $iLinha <= 19) {

          for ($iLinhaReceita = 0; $iLinhaReceita < $iTotalLinhasReceita; $iLinhaReceita++) {

            $oReceita   = db_utils::fieldsMemory($rsReceita, $iLinhaReceita);

            $oParametro = $aParametros;
            foreach ($oParametro->contas as $oConta) {

              $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oReceita, 1);

              if ($oVerificacao->match) {

                if ($oVerificacao->exclusao) {

                  $oReceita->saldo_inicial          *= -1;
                  $oReceita->saldo_inicial_prevadic *= -1;
                  $oReceita->saldo_arrecadado       *= -1;

                }
                $oLinha->prev_ini   += $oReceita->saldo_inicial;
                $oLinha->prev_atual += $oReceita->saldo_inicial_prevadic;
                $oLinha->rec_atebim += $oReceita->saldo_arrecadado;
              }
            }
          }

            if ($oLinha->prev_atual > 0) {
              $nPercentual = round((( $oLinha->rec_atebim / $oLinha->prev_atual ) * 100), 2);
            }
            $oLinha->percentual = $nPercentual;

        } else if ($iLinha >= 20 && $iLinha <= 28) {

          for ($iLinhaReceita = 0; $iLinhaReceita < $iTotalLinhasReceita; $iLinhaReceita++) {

            $oReceita   = db_utils::fieldsMemory($rsReceita, $iLinhaReceita);

            $oParametro = $aParametros;
            foreach ($oParametro->contas as $oConta) {

              $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oReceita, 1);

              if ($oVerificacao->match) {

                if ($oVerificacao->exclusao) {

                  $oReceita->saldo_inicial          *= -1;
                  $oReceita->saldo_inicial_prevadic *= -1;
                  $oReceita->saldo_arrecadado       *= -1;

                }
                $oLinha->prev_ini   += $oReceita->saldo_inicial;
                $oLinha->prev_atual += $oReceita->saldo_inicial_prevadic;
                $oLinha->rec_atebim += $oReceita->saldo_arrecadado;
              }
            }
          }

          if ($oLinha->rec_atebim > 0) {
            $nPercentual = @round(((  $oLinha->prev_atual / $oLinha->rec_atebim  ) * 100), 2);
          }

          $oLinha->percentual = $nPercentual;



        } else if ($iLinha >= 29 && $iLinha <= 69) {

          $iAnoInicial = (int)$this->iAnoUsu -2;
          $iAnoFinal   = $iAnoInicial - 3;
          switch ($iLinha) {

            case 50:
            case 54:
            case 58 :
              $oLinha->descricao = $oLinha->descricao . " " . ((int)$this->iAnoUsu -1);
            break;

            case 51 :
            case 55 :
            case 59 :
              $oLinha->descricao = $oLinha->descricao . " " . $iAnoInicial . " a " . $iAnoFinal;
            break;

            case 52:
            case 56 :
            case 60 :
              $oLinha->descricao = $oLinha->descricao . " Exercícios Anteriores a {$iAnoFinal}";
            break;
          }

          for ($iLinhaDespesa = 0; $iLinhaDespesa < $iTotalLinhasDespesa; $iLinhaDespesa++) {

            $oDespesa   = db_utils::fieldsMemory($rsDespesa, $iLinhaDespesa);

            $oParametro = $aParametros;
            foreach ($oParametro->contas as $oConta) {

              $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oDespesa, 2);

              if ($oVerificacao->match) {

                if ($oVerificacao->exclusao) {

                  $oDespesa->dot_ini             *= -1;
                  $oDespesa->atual               *= -1;
                  $oDespesa->empenhado           *= -1;
                  $oDespesa->empenhado_acumulado *= -1;
                  $oDespesa->anulado_acumulado   *= -1;
                  $oDespesa->liquidado           *= -1;
                }

                $oLinha->dot_ini    += $oDespesa->dot_ini;
                $oLinha->dot_atual  += (($oDespesa->dot_ini + $oDespesa->suplementado) - $oDespesa->reduzido) ;
                $oLinha->emp_atebim += $oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado;
                $oLinha->liq_atebim += $oDespesa->liquidado;
                $oLinha->insc_rp_np += $oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado - $oDespesa->liquidado;

                //@todo verificar coluna correta do resultset
                $oLinha->rp_inscrit += $oDespesa->ordinario;
                $oLinha->rp_cancel  += $oDespesa->ordinario;
                $oLinha->rp_pagos   += $oDespesa->ordinario;
                $oLinha->rp_apagar  += $oDespesa->ordinario;
                $oLinha->rp_prc_lim += $oDespesa->ordinario;

              }
            }
          }

          if ($iLinha >= 29 && $iLinha <= 36) {


            if ( !empty($oLinha->dot_atual) && $oLinha->dot_atual > 0 ) {
              $nPercentualLiquidado = (($oLinha->liq_atebim / $oLinha->dot_atual ) * 100);
            }

            if (!empty($oLinha->dot_atual) && $oLinha->dot_atual > 0 ){
              $nPercentualEmpenhado = ($oLinha->emp_atebim /  $oLinha->dot_atual) * 100;
            }

            $oLinha->percentualEmpenhado = $nPercentualEmpenhado;
            $oLinha->percentualLiquidado = $nPercentualLiquidado;
            if (in_array($this->iCodigoPeriodo, array(11, 13))) {

              if (!empty($oLinha->dot_atual)) {
                 $oLinha->percentualLiquidado = ((($oLinha->liq_atebim + $oLinha->insc_rp_np) / $oLinha->dot_atual));
              }
            }

          } else if ($iLinha > 37 && $iLinha <= 49 && $oLinha->totalizar != 1) {

            $oLinha->percentualEmpenhado = 0;
            $oLinha->percentualLiquidado = 0;
            if ($aLinhas[37]->emp_atebim > 0) {
              $oLinha->percentualEmpenhado = ($oLinha->emp_atebim / $aLinhas[37]->emp_atebim) * 100;
            }
            if ($aLinhas[37]->liq_atebim > 0) {
              $oLinha->percentualLiquidado = ($oLinha->liq_atebim / $aLinhas[37]->liq_atebim) * 100;
            }

            if (in_array($this->iCodigoPeriodo, array(11, 13))) {

              $iTotalIV = $aLinhas[37]->insc_rp_np + $aLinhas[37]->liq_atebim;
              if (!empty($iTotalIV)) {
                $oLinha->percentualLiquidado = (($oLinha->liq_atebim + $oLinha->liq_atebim) / $iTotalIV);
              }
            }
          }
        }
      }

      $aLinhas[$iLinha] = $oLinha;

      // totalizador da linha 1
      if (in_array($iLinha, $aLinhasTotalizadorasImpostoLiquido)) {
        $oLinhaAcumular = $aLinhas[1];
      }
      if (isset($oLinhaAcumular)) {

        $oLinhaAcumular->prev_ini   += $oLinha->prev_ini;
        $oLinhaAcumular->prev_atual += $oLinha->prev_atual;
        $oLinhaAcumular->rec_atebim += $oLinha->rec_atebim;
        $oLinhaAcumular->percentual = @round((($oLinhaAcumular->rec_atebim / $oLinhaAcumular->prev_atual) * 100), 2);
        unset($oLinhaAcumular);
       }

       // totalizador da linha 10
       if (in_array($iLinha, $aLinhasTotalizadorasTransfConst)) {
         $oLinhaAcumular = $aLinhas[10];
       }
       if (isset($oLinhaAcumular)) {

         $oLinhaAcumular->prev_ini   += $oLinha->prev_ini;
         $oLinhaAcumular->prev_atual += $oLinha->prev_atual;
         $oLinhaAcumular->rec_atebim += $oLinha->rec_atebim;
         $oLinhaAcumular->percentual = @round((($oLinhaAcumular->rec_atebim / $oLinhaAcumular->prev_atual) * 100), 2);
         unset($oLinhaAcumular);
       }

       // totalizador da linha 20
       if ($iLinha == 19) {

         $oLinhaAcumular             = $aLinhas[19];
         $oLinhaAcumular->prev_ini   = $aLinhas[1]->prev_ini   + $aLinhas[10]->prev_ini  ;
         $oLinhaAcumular->prev_atual = $aLinhas[1]->prev_atual + $aLinhas[10]->prev_atual;
         $oLinhaAcumular->rec_atebim = $aLinhas[1]->rec_atebim + $aLinhas[10]->rec_atebim;
         $oLinhaAcumular->percentual = @round((($oLinhaAcumular->rec_atebim / $oLinhaAcumular->prev_atual) * 100), 2);

//          echo "<pre>";
//          var_dump($oLinhaAcumular->rec_atebim);
         $aLinhas[19] = $oLinhaAcumular;
         unset($oLinhaAcumular);
       }

       // totalizador linha 21
       if (in_array($iLinha, $aLinhasTotalizadorasRecursoSus)) {
         $oLinhaAcumular = $aLinhas[20];
       }

       //Acumula os valores, caso a linha seja pertencente ao grupo do totalizador
       if (isset($oLinhaAcumular)) {

         $oLinhaAcumular->prev_ini   += $oLinha->prev_ini;
         $oLinhaAcumular->prev_atual += $oLinha->prev_atual;
         $oLinhaAcumular->rec_atebim += $oLinha->rec_atebim;

         if ($oLinha->rec_atebim >0) {
          $oLinhaAcumular->percentual = @round((( $oLinhaAcumular->rec_atebim/$oLinhaAcumular->prev_atual) * 100), 2);
         }
         unset($oLinhaAcumular);
       }

       /**
        * totalizador linha 29
        * soma linhas 26, 27 e 28
        */
       if ($iLinha == 28) {

         $aLinhaSomadas  = array(20,25,26,27);
         $oLinhaAcumular = $aLinhas[28];

         foreach( $aLinhaSomadas as $iLinhaSomada ) {

           $oLinhaAcumular->prev_ini   += $aLinhas[$iLinhaSomada]->prev_ini;
           $oLinhaAcumular->prev_atual += $aLinhas[$iLinhaSomada]->prev_atual;
           $oLinhaAcumular->rec_atebim += $aLinhas[$iLinhaSomada]->rec_atebim;
           $oLinhaAcumular->percentual  = @round((( $oLinhaAcumular->rec_atebim/$oLinhaAcumular->prev_atual) * 100), 2);

         }
         $aLinhas[28] = $oLinhaAcumular;
         unset($oLinhaAcumular);
       }

       // totalizador linha 30
       if (in_array($iLinha, $aLinhasTotalizadorasDespCorrente)) {
         $oLinhaAcumular = $aLinhas[29];
       }
       if (isset($oLinhaAcumular)) {

         $oLinhaAcumular->dot_ini              += $oLinha->dot_ini;
         $oLinhaAcumular->dot_atual            += $oLinha->dot_atual;
         $oLinhaAcumular->emp_atebim           += $oLinha->emp_atebim;
         $oLinhaAcumular->liq_atebim           += $oLinha->liq_atebim;
         $oLinhaAcumular->percentual           += $oLinha->percentual;
         $oLinhaAcumular->insc_rp_np           += $oLinha->insc_rp_np;
         $oLinhaAcumular->percentualEmpenhado   = @round((($oLinhaAcumular->emp_atebim / $oLinhaAcumular->dot_atual) * 100), 2);
         $oLinhaAcumular->percentualLiquidado   = @round((($oLinhaAcumular->liq_atebim / $oLinhaAcumular->dot_atual) * 100), 2);
         if (in_array($this->iCodigoPeriodo, array(11, 13))) {

           if (!empty($oLinha->dot_atual)) {
             $oLinhaAcumular->percentualLiquidado = ((($oLinha->liq_atebim + $oLinha->insc_rp_np) / $oLinha->dot_atual)) * 100;
           }
         }


         unset($oLinhaAcumular);
       }

       // totalizador linha 34
       if (in_array($iLinha, $aLinhasTotalizadorasDespCapital)) {
         $oLinhaAcumular = $aLinhas[33];
       }
       if (isset($oLinhaAcumular)) {

         $oLinhaAcumular->dot_ini              += $oLinha->dot_ini;
         $oLinhaAcumular->dot_atual            += $oLinha->dot_atual;
         $oLinhaAcumular->emp_atebim           += $oLinha->emp_atebim;
         $oLinhaAcumular->liq_atebim           += $oLinha->liq_atebim;
         $oLinhaAcumular->percentual           += $oLinha->percentual;
         $oLinhaAcumular->insc_rp_np           += $oLinha->insc_rp_np;
         $oLinhaAcumular->percentualEmpenhado   = @round((($oLinhaAcumular->emp_atebim / $oLinhaAcumular->dot_atual) * 100), 2);
         $oLinhaAcumular->percentualLiquidado   = @round((($oLinhaAcumular->liq_atebim / $oLinhaAcumular->dot_atual) * 100), 2);
         if (in_array($this->iCodigoPeriodo, array(11, 13))) {

           if (!empty($oLinha->dot_atual)) {
             $oLinhaAcumular->percentualLiquidado = ((($oLinha->liq_atebim + $oLinha->insc_rp_np) / $oLinha->dot_atual)) * 100;
           }
         }
         unset($oLinhaAcumular);
       }

       /**
        * totalizador III - linha 38
        * Soma as linhas 30 e 34
        */
       if ($iLinha == 37) {

         $aLinhaSomadas  = array(29, 33);
         $oLinhaAcumular = $aLinhas[37];

         foreach( $aLinhaSomadas as $iLinhaSomada ) {

           $oLinhaAcumular->dot_ini            += $aLinhas[$iLinhaSomada]->dot_ini   ;
           $oLinhaAcumular->dot_atual          += $aLinhas[$iLinhaSomada]->dot_atual ;
           $oLinhaAcumular->emp_atebim         += $aLinhas[$iLinhaSomada]->emp_atebim;
           $oLinhaAcumular->liq_atebim         += $aLinhas[$iLinhaSomada]->liq_atebim;
           $oLinhaAcumular->percentual         += $aLinhas[$iLinhaSomada]->percentual;
           $oLinhaAcumular->insc_rp_np         += $aLinhas[$iLinhaSomada]->insc_rp_np;
           $oLinhaAcumular->percentualEmpenhado = @round((($oLinhaAcumular->emp_atebim / $oLinhaAcumular->dot_atual) * 100), 2);
           $oLinhaAcumular->percentualLiquidado = @round((($oLinhaAcumular->liq_atebim / $oLinhaAcumular->dot_atual) * 100), 2);
           if (in_array($this->iCodigoPeriodo, array(11, 13))) {

             if (!empty($oLinha->dot_atual)) {
               $oLinhaAcumular->percentualLiquidado = ((($oLinha->liq_atebim + $oLinha->insc_rp_np) / $oLinha->dot_atual)) * 100;
             }
           }
         }
         $aLinhas[37] = $oLinhaAcumular;
         unset($oLinhaAcumular);
       }


       /**
        *  totalizador IV - linha 41
        *  Soma as linhas 42 até 44
        *  somente poderá ser contabilizado após ler a linha 44
        */
       if ($iLinha == 44) {


         $aLinhaSomadas  = array(41, 42, 43);
         $oLinhaAcumular = $aLinhas[40];

         foreach( $aLinhaSomadas as $iLinhaSomada ) {

           $oLinhaAcumular->dot_ini            += $aLinhas[$iLinhaSomada]->dot_ini   ;
           $oLinhaAcumular->dot_atual          += $aLinhas[$iLinhaSomada]->dot_atual ;
           $oLinhaAcumular->emp_atebim         += $aLinhas[$iLinhaSomada]->emp_atebim;
           $oLinhaAcumular->liq_atebim         += $aLinhas[$iLinhaSomada]->liq_atebim;
           $oLinhaAcumular->percentual         += $aLinhas[$iLinhaSomada]->percentual;
           $oLinhaAcumular->insc_rp_np         += $aLinhas[$iLinhaSomada]->insc_rp_np;
           $oLinhaAcumular->percentualEmpenhado = @round(($oLinhaAcumular->emp_atebim / $aLinhas[37]->emp_atebim)*100, 2);
           $oLinhaAcumular->percentualLiquidado = @round(($oLinhaAcumular->liq_atebim / $aLinhas[37]->liq_atebim)*100, 2);
           if (in_array($this->iCodigoPeriodo, array(11, 13))) {

             $iTotalIV = $aLinhas[37]->insc_rp_np + $aLinhas[37]->liq_atebim;
             if (!empty($iTotalIV)) {
               $oLinhaAcumular->percentualLiquidado = (($oLinhaAcumular->insc_rp_np + $oLinhaAcumular->liq_atebim) / $iTotalIV) * 100;
             }
           }
         }
         $aLinhas[40] = $oLinhaAcumular;

         unset($oLinhaAcumular);
       }

       // totalizador V -  linha 49
       if ($iLinha == 48) {

         $aLinhaSomadas  = array(38,39,40,44,45,46,47);
         $oLinhaAcumular = $aLinhas[48];
         foreach( $aLinhaSomadas as $iLinhaSomada ) {

           $oLinhaAcumular->dot_ini             += $aLinhas[$iLinhaSomada]->dot_ini;
           $oLinhaAcumular->dot_atual           += $aLinhas[$iLinhaSomada]->dot_atual;
           $oLinhaAcumular->emp_atebim          += $aLinhas[$iLinhaSomada]->emp_atebim;
           $oLinhaAcumular->liq_atebim          += $aLinhas[$iLinhaSomada]->liq_atebim;
           $oLinhaAcumular->insc_rp_np          += $aLinhas[$iLinhaSomada]->insc_rp_np;

           $oLinhaAcumular->percentualEmpenhado = @round(($oLinhaAcumular->emp_atebim /$aLinhas[37]->emp_atebim)*100, 2);
           $oLinhaAcumular->percentualLiquidado = @round(($oLinhaAcumular->liq_atebim /$aLinhas[37]->liq_atebim)*100, 2);
         }


         $oLinhaAcumular->total_pagar = $oLinhaAcumular->insc_rp_np + $oLinhaAcumular->liq_atebim;
         if (in_array($this->iCodigoPeriodo, array(11, 13))) {

           $iTotalIV = $aLinhas[37]->insc_rp_np + $aLinhas[37]->liq_atebim;
           if (!empty($iTotalIV)) {
             $oLinhaAcumular->percentualLiquidado = (($oLinhaAcumular->insc_rp_np + $oLinhaAcumular->liq_atebim) / $iTotalIV) * 100;
           }
         }
         $aLinhas[48] = $oLinhaAcumular;
         unset($oLinhaAcumular);
       }

       // totalizador VI - linha 50
       if ($iLinha == 49) {

         $oLinha                      = $aLinhas[49];
         $oLinha->dot_ini             = $aLinhas[37]->dot_ini    -   $aLinhas[48]->dot_ini   ;
         $oLinha->dot_atual           = $aLinhas[37]->dot_atual  -   $aLinhas[48]->dot_atual ;
         $oLinha->emp_atebim          = $aLinhas[37]->emp_atebim -   $aLinhas[48]->emp_atebim;
         $oLinha->liq_atebim          = $aLinhas[37]->liq_atebim -   $aLinhas[48]->liq_atebim;
         $oLinha->insc_rp_np          = $aLinhas[37]->insc_rp_np -   $aLinhas[48]->insc_rp_np;
         $oLinha->percentualEmpenhado = @round(($oLinha->emp_atebim /$aLinhas[37]->emp_atebim)*100, 2);
         $oLinha->percentualLiquidado = @round(($oLinha->liq_atebim /$aLinhas[37]->liq_atebim)*100, 2);
         $oLinha->total_pagar         = $oLinha->insc_rp_np + $oLinha->liq_atebim;
         if (in_array($this->iCodigoPeriodo, array(11, 13))) {
             $oLinha->percentualLiquidado = "-";
         }
         $aLinhas[49] = $oLinha;
         unset($oLinha);
       }

       // totalizador linha 58 VIII = 55+56+57
       if ($iLinha == 57) {

         $aLinhaSomadas  = array(54, 55, 56);
         $oLinha             = $aLinhas[57];
         foreach( $aLinhaSomadas as $iLinhaSomada ) {

           $oLinha->RP_sd_ini  += $aLinhas[$iLinhaSomada]->RP_sd_ini  ;
           $oLinha->Cust_exerc += $aLinhas[$iLinhaSomada]->Cust_exerc ;
           $oLinha->sd_naoapli += $aLinhas[$iLinhaSomada]->sd_naoapli ;
         }
         $aLinhas[57] = $oLinha;
       }

       // totalizador linha 62 IX = 59+60+61
       if ($iLinha == 61) {

         $oLinha             = $aLinhas[61];
         $aLinhaSomadas  = array(58, 59, 60);
         foreach( $aLinhaSomadas as $iLinhaSomada ) {

           $oLinha->RP_sd_ini  += $aLinhas[$iLinhaSomada]->RP_sd_ini ;
           $oLinha->Cust_exerc += $aLinhas[$iLinhaSomada]->Cust_exerc;
           $oLinha->sd_naoapli += $aLinhas[$iLinhaSomada]->sd_naoapli;
         }
         $aLinhas[61] = $oLinha;

       }

       // totalizador linha 70 TOTAL = 63+64+65+66+67+68+69
       if ($iLinha == 69) {

         $oLinha   = $aLinhas[69];

         for ($iLinhaSomada = 62; $iLinhaSomada <= 68;$iLinhaSomada++ ) {

           $oLinha->dot_ini             += $aLinhas[$iLinhaSomada]->dot_ini;
           $oLinha->dot_atual           += $aLinhas[$iLinhaSomada]->dot_atual;
           $oLinha->emp_atebim          += $aLinhas[$iLinhaSomada]->emp_atebim;
           $oLinha->liq_atebim          += $aLinhas[$iLinhaSomada]->liq_atebim;
           $oLinha->insc_rp_np          += $aLinhas[$iLinhaSomada]->insc_rp_np;
         }
         $aLinhas[69] = $oLinha;
         unset($oLinha);
       }
    }

    for ($iLinhaSomada = 62; $iLinhaSomada <= 69;$iLinhaSomada++ ) {

      $oLinha   = $aLinhas[$iLinhaSomada];
      $oLinha->percentualEmpenhado = @round(($oLinha->emp_atebim / $aLinhas[69]->emp_atebim) * 100, 2);//(I/total I) x 100
      $oLinha->percentualLiquidado = @round(($oLinha->liq_atebim / $aLinhas[69]->liq_atebim) * 100, 2);//(m/total m) x 100
      if (in_array($this->iCodigoPeriodo, array(11, 13))) {


        if (!empty($oLinha->dot_atual)) {
          $oLinha->percentualLiquidado = ((($oLinha->liq_atebim + $oLinha->insc_rp_np) / $oLinha->dot_atual) * 100);
        }
      }
    }

    // linha 71
    $oLinha          = new stdClass();
    $nTotalQuadroIII = $aLinhas[19]->rec_atebim;
    $oLinha->dot_in  = 0;
    if ($nTotalQuadroIII > 0) {
      $oLinha->dot_in = @round(($aLinhas[49]->emp_atebim / $nTotalQuadroIII) * 100, 2);
    }

    $aLinhas[70] = $oLinha;

    // linha 72 = (71 - 15 / 100 * bIII)
    $oLinha          = new stdClass();
    $nTotalQuadroVII = $aLinhas[70]->dot_in;
    $oLinha->linhaVIII = @round(((($nTotalQuadroVII - 15) /100) * $aLinhas[19]->rec_atebim));
    $aLinhas[71] = $oLinha;


    $oDaoEmpResto     = db_utils::getDao("empresto");
    $sSqlEmpResto =  $oDaoEmpResto->sql_rp_novo(db_getsession("DB_anousu"), "e60_instit in (" .$this->getInstituicoes(). ")", $sDataInicial, $sDataFinal, null, null, null);
    $sSqlExecucao = "select sum(e91_vlremp::numeric - e91_vlranu::numeric - e91_vlrliq::numeric) as inscritos,
                           sum (vlranuliqnaoproc::numeric) as cancelados,
                           sum (vlrpagnproc::numeric) as pagos,
                           sum ((e91_vlremp::numeric - e91_vlranu::numeric - e91_vlrliq::numeric) - (vlranuliqnaoproc::numeric) - (vlrpagnproc::numeric)) as a_pagar,
                           y.e60_anousu
                     from ($sSqlEmpResto)as y where y.e60_anousu <". db_getsession("DB_anousu")." and y.o58_codigo in ( 40) group by  y.e60_anousu order by e60_anousu desc";



    $rsExecucao = db_query($sSqlExecucao);
    $iTotalAnos = pg_num_rows($rsExecucao);


    //Linha 50
    $oDadosExecucaoReferencia  = db_utils::fieldsMemory($rsExecucao, 0);
    $aLinhas[50]->rp_inscrit  += $oDadosExecucaoReferencia->inscritos;
    $aLinhas[50]->rp_cancel   += $oDadosExecucaoReferencia->cancelados;
    $aLinhas[50]->rp_pagos    += $oDadosExecucaoReferencia->pagos;
    $aLinhas[50]->rp_apagar   += $oDadosExecucaoReferencia->a_pagar;


    //Linha 51
    for($iSequenciaAno = 1;$iSequenciaAno <= 4; $iSequenciaAno++) {

      $oDadosExecucaoReferencia = db_utils::fieldsMemory($rsExecucao, $iSequenciaAno);
      $aLinhas[51]->rp_inscrit += $oDadosExecucaoReferencia->inscritos;
      $aLinhas[51]->rp_cancel  += $oDadosExecucaoReferencia->cancelados;
      $aLinhas[51]->rp_pagos   += $oDadosExecucaoReferencia->pagos;
      $aLinhas[51]->rp_apagar  += $oDadosExecucaoReferencia->a_pagar;
    }

    //Linha 52
    $oLinha = new stdClass();
    for($iSequenciaAno = 5;$iSequenciaAno < $iTotalAnos; $iSequenciaAno++) {

      $oDadosExecucaoReferencia = @db_utils::fieldsMemory($rsExecucao, $iSequenciaAno);
      $aLinhas[52]->rp_inscrit += @$oDadosExecucaoReferencia->inscritos;
      $aLinhas[52]->rp_cancel  += @$oDadosExecucaoReferencia->cancelados;
      $aLinhas[52]->rp_pagos   += @$oDadosExecucaoReferencia->pagos;
      $aLinhas[52]->rp_apagar  += @$oDadosExecucaoReferencia->a_pagar;
    }

    $aLinhaSomadas  = array(50, 51, 52);
    $oLinha = $aLinhas[53];
    foreach( $aLinhaSomadas as $iLinhaSomada ) {

      $oLinha->rp_inscrit   += $aLinhas[$iLinhaSomada]->rp_inscrit ;
      $oLinha->rp_cancel    += $aLinhas[$iLinhaSomada]->rp_cancel  ;
      $oLinha->rp_pagos     += $aLinhas[$iLinhaSomada]->rp_pagos   ;
      $oLinha->rp_apagar    += $aLinhas[$iLinhaSomada]->rp_apagar  ;
      $oLinha->rp_prc_lim   += $aLinhas[$iLinhaSomada]->rp_prc_lim ;
    }
    $aLinhas[53] = $oLinha;
    return $aLinhas;
  }

  /**
   * retorna os dados necessários para o relatorio simplidicado
   *
   */
  public function getDadosSimplificado() {

    $aDadosSimplificado = $this->getDados();

    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->apurado_ate_bim   = $aDadosSimplificado[49]->emp_atebim;
    $oDadosSimplificado->percent_ate_bim   = $aDadosSimplificado[70]->dot_in;
    $oDadosSimplificado->inscritos_rp_nroc = $aDadosSimplificado[49]->insc_rp_np;
    $oDadosSimplificado->liquidadas        = $aDadosSimplificado[49]->liq_atebim;
    if (in_array($this->iCodigoPeriodo, array(11, 13))) {
      $oDadosSimplificado->apurado_ate_bim   = $aDadosSimplificado[49]->insc_rp_np + $aDadosSimplificado[49]->liq_atebim;
    }

    return $oDadosSimplificado;
  }
}
