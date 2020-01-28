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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

class SigfisArquivoConcilia extends SigfisArquivoBase implements iPadArquivoTXTBase {

  protected $iCodigoLayout     = 201;
  protected $sNomeArquivo      = 'Concilia';

  public function gerarDados() {


    /**
     * Busca os dados da db_config
     */
    $oDadoConfig    = db_stdClass::getDadosInstit();

    $this->addLog("=====Arquivo".$this->getNomeArquivo()." Erros:\n");

    $aCompetencia = explode('-',$this->dtDataFinal);
    $sCompetencia = $aCompetencia[0].$aCompetencia[1];
    $iAnoUsu      = $aCompetencia[0];

	  $this->setCodigoLayout(201);
    if( $iAnoSessao < 2013 ){
  	  $this->setCodigoLayout(191);
    }

    $sSqlContasConciliacao = "select distinct on (k68_contabancaria)
                                     k68_contabancaria,
                                     max(k68_data) as k68_data,
                                     competencia,
                                     c61_reduz,
                                     c61_instit,
                                     c60_estrut
                                from ( select k68_contabancaria,
                                              extract(year from k68_data)::varchar || lpad(extract(month from k68_data)::varchar,2,'0') as competencia,
                                              k68_data,
                                              c61_reduz,
                                              c61_instit,
                                              c60_estrut, c61_anousu, c60_anousu, c56_anousu
                                         from concilia
                                           inner join contabancaria         on contabancaria.db83_sequencial = concilia.k68_contabancaria
                                           inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
                                           inner join conplano              on conplano.c60_codcon = conplanocontabancaria.c56_codcon
                                                                           and conplano.c60_anousu = conplanocontabancaria.c56_anousu
                                           inner join conplanoreduz         on conplanoreduz.c61_codcon = conplano.c60_codcon
                                                                           and conplanoreduz.c61_anousu = conplano.c60_anousu
                                         ) as x
                               where competencia  = '{$sCompetencia}'
                                 and x.c60_anousu = {$iAnoUsu}
                                 and x.c61_anousu = {$iAnoUsu}
                                 and x.c56_anousu = {$iAnoUsu}
                                 and x.c61_instit = ".db_getsession('DB_instit')."
                               group by k68_contabancaria,
                                        competencia,
                                        c61_reduz,
                                        c60_estrut,
                                        c61_instit";
//    die($sSqlContasConciliacao);
    $rsContasConciliacao     = db_query($sSqlContasConciliacao) ;
    $iTotalLinhasConciliacao = pg_num_rows($rsContasConciliacao);

    if ( !$rsContasConciliacao || $iTotalLinhasConciliacao == 0) {
      throw new BusinessException("Sem contas conciliadas para a competência selecionada.");
    }

    $aProcessados = array();

    for ($iRowConciliacao = 0; $iRowConciliacao < $iTotalLinhasConciliacao; $iRowConciliacao++) {

      $oStdContaConciliacao = db_utils::fieldsMemory($rsContasConciliacao, $iRowConciliacao);

      $oDaoConcilia        = db_utils::getDao("concilia");
      $sWhereConcilia      = "     k68_contabancaria = {$oStdContaConciliacao->k68_contabancaria} ";
      $sWhereConcilia     .= " and k68_data = '{$oStdContaConciliacao->k68_data}'";
      $sSqlBuscaSequencial = $oDaoConcilia->sql_query_file(null, "k68_sequencial", null, $sWhereConcilia);
      $rsBuscaSequencial   = $oDaoConcilia->sql_record($sSqlBuscaSequencial);

      if ($oDaoConcilia->erro_status == "0") {
        throw new BusinessException("Sequencial da conciliação para a conta bancária {$oStdContaConciliacao->k68_contabancaria} não localizada.");
      }

      $iSequencialConciliacao   = db_utils::fieldsMemory($rsBuscaSequencial, 0)->k68_sequencial;
      $oDaoConciliaPendCorrente = db_utils::getDao("conciliapendcorrente");
      $sqlPendenciascaixa       = $oDaoConciliaPendCorrente->sql_query_pendencias_sigfis(db_getsession("DB_instit"),
                                                                                         $oStdContaConciliacao->k68_contabancaria,
                                                                                         $iSequencialConciliacao);

      if (in_array("{$iDiaSequencial}{$oStdPendenciaCaixa->k89_id}{$oStdPendenciaCaixa->k89_autent}", $aProcessados)) {
    //    echo "Continuando : {$iDiaSequencial} - {$oStdPendenciaCaixa->k89_id} - {$oStdPendenciaCaixa->k89_autent} - {$iRowConciliacao}\n";
    //    continue;
      }

      $rsPendenciaCaixa     = db_query($sqlPendenciascaixa);
      $iTotalPendenciaCaixa = pg_num_rows($rsPendenciaCaixa);

      if ( !$rsPendenciaCaixa) {
        throw new BusinessException("Pendencias do caixa não localizadas.");
      }

      /**
       * Laço para percorrer as pendencias do caixa
       */
      for ($iRowPendenciaCaixa = 0; $iRowPendenciaCaixa < $iTotalPendenciaCaixa; $iRowPendenciaCaixa++) {

        $oStdPendenciaCaixa       = db_utils::fieldsMemory($rsPendenciaCaixa, $iRowPendenciaCaixa);
        list($iAno, $iMes, $iDia) = explode("-", $oStdPendenciaCaixa->data_conciliacao);
        list($iCodigoTabelaConciliacao, $sDescricaoTabelaConciliacao) = $this->getDadosTabelaConciliacao(true, $oStdPendenciaCaixa->tipomovimentacao);

        list($iAnoSequencial, $iMesSequencial, $iDiaSequencial) = explode("-", $oStdPendenciaCaixa->k89_data);

        $oStdLinhaArquivo = new stdClass();
        $oStdLinhaArquivo->dt_anocriacao            = $iAno;
        $oStdLinhaArquivo->cd_unidade               = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT); 
        $oStdLinhaArquivo->cd_contacontabil         = $oStdContaConciliacao->c60_estrut;
        $oStdLinhaArquivo->dt_anomes                = "{$iAno}{$iMes}";
        $oStdLinhaArquivo->rv_tce                   = "0";
        $oStdLinhaArquivo->cd_conciliacao           = $iCodigoTabelaConciliacao;
        $oStdLinhaArquivo->de_conciliacao           = $sDescricaoTabelaConciliacao;
        $oStdLinhaArquivo->dt_movconciliado         = "{$iDia}{$iMes}{$iAno}";
        $oStdLinhaArquivo->vl_movconciliado         = number_format(round($oStdPendenciaCaixa->vl_movconciliado,2), 2, '', '');//str_replace(".", "", round($oStdPendenciaCaixa->vl_movconciliado, 2));
        /**
         * Concatenando "0" para caixa ao final do sequencial para nao repetir
         *  o sequencial com as pendencias do extrato
         */
        $oStdLinhaArquivo->nu_sequencialconciliacao = "{$iDiaSequencial}{$oStdPendenciaCaixa->k89_id}{$oStdPendenciaCaixa->k89_autent}";
        $aProcessados[] = $oStdLinhaArquivo->nu_sequencialconciliacao;
        // $oStdLinhaArquivo->nu_sequencialconciliacao = ($iRowConciliacao+1).($iRowPendenciaCaixa+1)."0";
//         echo "C : $oStdLinhaArquivo->nu_sequencialconciliacao -- $oStdLinhaArquivo->vl_movconciliado\n";

       if( $iAnoSessao < 2013 ){
          $oStdLinhaArquivo->codigolinha              = 576;
        }else{
          $oStdLinhaArquivo->Cd_Conta_Corrente        = str_pad(' ', 30, ' ', STR_PAD_LEFT);
          $oStdLinhaArquivo->codigolinha              = 666;
        }
        $this->aDados[]                             = $oStdLinhaArquivo;
        unset($oStdLinhaArquivo);

      }


      $oDaoConciliaPendExtrato  = db_utils::getDao("conciliapendextrato");
      $sCamposPendenciaExtrato  = " k86_sequencial,                ";
      $sCamposPendenciaExtrato .= " k68_data  as data_conciliacao, ";
      $sCamposPendenciaExtrato .= " k86_valor as vl_movconciliado, ";
      $sCamposPendenciaExtrato .= " k86_tipo  as tipomovimentacao  ";
      $sWherePendenciaExtrato   = "k88_concilia = {$iSequencialConciliacao}";
      $sSqlPendeciaExtrato      = $oDaoConciliaPendExtrato->sql_query_extrato_sigfis(null,
                                                                                    $sCamposPendenciaExtrato,
                                                                                    "tipomovimentacao, k86_data",
                                                                                    $sWherePendenciaExtrato);
      if ( $oStdContaConciliacao->k68_contabancaria == 758 ){                                                                               
       // die($sSqlPendeciaExtrato);
      }
      $rsPendenciaExtrato  = db_query($sSqlPendeciaExtrato);
      $iTotalLinhasExtrato = pg_num_rows($rsPendenciaExtrato);

      for ($iRowPendenciaExtrato = 0; $iRowPendenciaExtrato < $iTotalLinhasExtrato; $iRowPendenciaExtrato++) {

        $oStdPendenciaExtrato       = db_utils::fieldsMemory($rsPendenciaExtrato, $iRowPendenciaExtrato);
        list($iAno, $iMes, $iDia)   = explode("-", $oStdPendenciaExtrato->data_conciliacao);
        list($iCodigoTabelaConciliacao,$sDescricaoTabelaConciliacao) = $this->getDadosTabelaConciliacao(false,
                                                                              $oStdPendenciaExtrato->tipomovimentacao);
        

        if ( $oStdPendenciaExtrato->k86_sequencial == 26849 ) {
          //echo $sSqlPendeciaExtrato."\n";
        }

        $oStdLinhaArquivo = new stdClass();
        $oStdLinhaArquivo->dt_anocriacao            = $iAno;
        $oStdLinhaArquivo->cd_unidade               = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
        $oStdLinhaArquivo->cd_contacontabil         = $oStdContaConciliacao->c60_estrut;
        $oStdLinhaArquivo->dt_anomes                = "{$iAno}{$iMes}";
        $oStdLinhaArquivo->rv_tce                   = "0";
        $oStdLinhaArquivo->cd_conciliacao           = $iCodigoTabelaConciliacao;
        $oStdLinhaArquivo->de_conciliacao           = $sDescricaoTabelaConciliacao;
        $oStdLinhaArquivo->dt_movconciliado         = "{$iDia}{$iMes}{$iAno}";
        $oStdLinhaArquivo->vl_movconciliado         = number_format(round($oStdPendenciaExtrato->vl_movconciliado,2), 2, '', '');// str_replace(".", "", round($oStdPendenciaExtrato->vl_movconciliado, 2));

        /**
         * Concatenando "1" para caixa ao final do sequencial para nao repetir
         *  o sequencial com as pendencias do caixa
         */
        $oStdLinhaArquivo->nu_sequencialconciliacao = $oStdPendenciaExtrato->k86_sequencial; //($iRowConciliacao+1).($iRowPendenciaExtrato+1)."1";
//         echo "E : $oStdLinhaArquivo->nu_sequencialconciliacao -- $oStdLinhaArquivo->vl_movconciliado\n";

         if( $iAnoSessao < 2013 ){             
          $oStdLinhaArquivo->codigolinha              = 576;
        }else{
          $oStdLinhaArquivo->Cd_Conta_Corrente        = str_pad(' ', 15, ' ', STR_PAD_LEFT);
          $oStdLinhaArquivo->codigolinha              = 666;
        }

        $this->aDados[]                             = $oStdLinhaArquivo;
        unset($oStdLinhaArquivo);

      }

      /**
       * Linha com o saldo do extrato
       */

      $oDaoExtratoSaldo     = db_utils::getDao("extratosaldo");
      $sCamposExtratoSaldo  = "coalesce(k97_saldofinal, 0) as saldoextrato";
      $sOrderByExtratoSaldo = "k97_dtsaldofinal desc limit 1";
      $sWhereExtratoSaldo   = "    k97_contabancaria = {$oStdContaConciliacao->k68_contabancaria} ";
      $sWhereExtratoSaldo  .= "and k97_dtsaldofinal <= '{$oStdContaConciliacao->k68_data}' ";
      $sSqlExtratoSaldo     = $oDaoExtratoSaldo->sql_query_file(null,
                                                                $sCamposExtratoSaldo,
                                                                $sOrderByExtratoSaldo,
                                                                $sWhereExtratoSaldo);
      $rsSaldoExtrato = db_query($sSqlExtratoSaldo);
      if ( !$rsSaldoExtrato) {
        throw new BusinessException("Não foi possível localizar o saldo final para a conta {$oStdContaConciliacao->k68_contabancaria}.");
      }

      $oStdSaldoExtrato = db_utils::fieldsMemory($rsSaldoExtrato, 0);
      list($iAno, $iMes, $iDia) = explode("-", $oStdContaConciliacao->k68_data);

      $oStdLinhaArquivo = new stdClass();
      $oStdLinhaArquivo->dt_anocriacao            = $iAno;
      $oStdLinhaArquivo->cd_unidade               = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);;
      $oStdLinhaArquivo->cd_contacontabil         = $oStdContaConciliacao->c60_estrut;
      $oStdLinhaArquivo->dt_anomes                = "{$iAno}{$iMes}";
      $oStdLinhaArquivo->rv_tce                   = "0";
      $oStdLinhaArquivo->cd_conciliacao           = "3";
      $oStdLinhaArquivo->de_conciliacao           = "Saldo conforme extrato bancário";
      $oStdLinhaArquivo->dt_movconciliado         = "{$iDia}{$iMes}{$iAno}";
      $oStdLinhaArquivo->vl_movconciliado         = number_format(round($oStdSaldoExtrato->saldoextrato,2), 2, '', '');//str_replace(".", "", round($oStdSaldoExtrato->saldoextrato, 2));
      /**
       * Concatenando "3" para caixa ao final do sequencial para nao repetir
       *  o sequencial com as pendencias do caixa e do extraro
      */
      $oStdLinhaArquivo->nu_sequencialconciliacao = ($iRowConciliacao+1)."03";

       if( $iAnoSessao < 2013 ){               
        $oStdLinhaArquivo->codigolinha              = 576;
      }else{
        $oStdLinhaArquivo->Cd_Conta_Corrente        = str_pad(' ', 15, ' ', STR_PAD_LEFT);
        $oStdLinhaArquivo->codigolinha              = 666;
      }
      $this->aDados[]                             = $oStdLinhaArquivo;
      unset($oStdLinhaArquivo);

    }
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");

    return $this->aDados;

  }

  public function getDadosTabelaConciliacao ($lCaixa, $sTipoMovimentacao) {

    //@todo - revisar este hash de valores
    $aHashTipoMovimentacao[true]["C"]  = "1";
    $aHashTipoMovimentacao[true]["D"]  = "2";
    $aHashTipoMovimentacao[false]["C"] = "4";
    $aHashTipoMovimentacao[false]["D"] = "5";

    $aTiposMovimentacoes = array( "1" => array("1", "Entrada não considerada pelo banco"),
                                  "2" => array("2", "Entrada não considerada pela contabilidade"),
                                  "3" => array("3", "Saldo conforme extrato bancário"),
                                  "4" => array("4", "Saida não considerada pelo banco"),
                                  "5" => array("5", "Saída não considerada pela contabilidade") );

    return $aTiposMovimentacoes[$aHashTipoMovimentacao[$lCaixa][$sTipoMovimentacao]];

  }

}