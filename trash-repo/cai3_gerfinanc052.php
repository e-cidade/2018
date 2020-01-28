<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_app.utils.php");
include("model/arrecadacao/abatimento/Desconto.model.php");

$oGet  = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
 
 db_app::load('scripts.js');
 db_app::load('prototype.js');
 db_app::load('estilos.css');

?>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<?


  /**
   * Busca as Compensações.
   */

  if ( isset($oGet->numcgm) ) {
    $sInnerCredito = " inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = " and arrenumcgm.k00_numcgm = ".$oGet->numcgm; 
    $sTipoPesquisa = "C";
    $sChavePesquisa= $oGet->numcgm;

  } else if ( isset($oGet->matric) ) {
    $sInnerCredito = " inner join arrematric on arrematric.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = " and arrematric.k00_matric = ".$oGet->matric; 
    $sTipoPesquisa = "M";
    $sChavePesquisa= $oGet->matric;   
  } else if ( isset($oGet->inscr) ) {
    $sInnerCredito = " inner join arreinscr on arreinscr.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = " and arreinscr.k00_inscr = ".$oGet->inscr;         
    $sTipoPesquisa = "I";
    $sChavePesquisa= $oGet->inscr;          
  } else {
    $sInnerCredito = "";
    $sWhereCredito = " and abatimentorecibo.k127_numprerecibo = ".$oGet->numpre;   
  }
  
  $sSqlCreditosDisponiveis  = " select abatimento.*,                                                                            ";
  $sSqlCreditosDisponiveis .= "        recibo    .*,                                                                            ";
  $sSqlCreditosDisponiveis .= "        tabrec    .*,                                                                            ";
  $sSqlCreditosDisponiveis .= "        histcalc  .*,                                                                            ";
  $sSqlCreditosDisponiveis .= "        arretipo  .*                                                                             ";
  $sSqlCreditosDisponiveis .= "   from abatimentorecibo                                                                         "; 
  $sSqlCreditosDisponiveis .= "        inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento   ";
  $sSqlCreditosDisponiveis .= "        inner join recibo     on recibo.k00_numpre          = abatimentorecibo.k127_numprerecibo ";
  $sSqlCreditosDisponiveis .= "        inner join arretipo   on arretipo.k00_tipo          = recibo.k00_tipo                    ";
  $sSqlCreditosDisponiveis .= "        inner join tabrec     on tabrec.k02_codigo          = recibo.k00_receit                  ";
  $sSqlCreditosDisponiveis .= "        inner join histcalc   on histcalc.k01_codigo        = recibo.k00_hist                    ";
  $sSqlCreditosDisponiveis .= "        {$sInnerCredito}                                                                         ";
  $sSqlCreditosDisponiveis .= "  where abatimento.k125_tipoabatimento = 4                                                       "; 
  $sSqlCreditosDisponiveis .= "        {$sWhereCredito}                                                                         ";
    
  $rsCreditosDisponiveis    = db_query($sSqlCreditosDisponiveis);
  $iLinhasCreditos          = pg_num_rows($rsCreditosDisponiveis);
  $aDadosSaida = array();

  /**
   * BUSCA OS DESCONTOS CONCEDIDOS
   * ADICIONANDO A VARIAVEL $aDadosSaida[]
   */
  $aDescontos = !isset($sChavePesquisa) ? 
                array() : 
                Desconto::getDescontosPorOrigem($sTipoPesquisa,$sChavePesquisa);

  foreach ( $aDescontos as $oDescontos) {

    $oDados                  = new stdClass();
    $oDados->k01_codigo      = $oDescontos->getTipoAbatimento();
    $oDados->k125_sequencial = $oDescontos->getCodigo();
    $oDados->k00_tipo        = $oDescontos->getTipoDebito();
    $oDados->k00_descr       = !empty($oDados->k00_tipo) ? getDescricaoTipoDebito($oDescontos->getTipoDebito()) : "";
    $oDados->k125_datalanc   = $oDescontos->getDataLancamento()->getDate();
    $oDados->k00_hist        = Desconto::HISTORICO;
    $oDados->k01_descr       = 'DESCONTO';
    
    if ( $oDescontos->getSituacao() == Abatimento::SITUACAO_CANCELADO ) {  

      $oDados->k00_hist      = Desconto::HISTORICO_CANCELAMENTO;
      $oDados->k01_descr     = 'DESCONTO CANCELADO';
    }
    
    $oDados->k00_valor       = $oDescontos->getValor();
    $oDados->sTipo           = 'desconto';
    $aDadosSaida[]           = $oDados;
  }

  if ( $iLinhasCreditos > 0 ) {

    /**
     * Somente compensações
     */
    for ( $iInd=0; $iInd < $iLinhasCreditos; $iInd++ ) {
      
      $oCredito = db_utils::fieldsMemory($rsCreditosDisponiveis,$iInd);
      $oDados = new stdClass();
      $oDados->k01_codigo      = $oCredito->k01_codigo;
      $oDados->k125_datalanc   = $oCredito->k125_datalanc;
      $oDados->k125_sequencial = $oCredito->k125_sequencial;
      $oDados->k02_descr       = $oCredito->k02_descr;
      $oDados->k00_tipo        = $oCredito->k00_tipo;
      $oDados->k00_descr       = $oCredito->k00_descr;
      $oDados->k01_descr       = 'COMPENSAÇÃO';
      $oDados->k00_valor       = $oCredito->k00_valor;
      $oDados->sTipo           = 'compensação';

      $aDadosSaida[] = $oDados;
    }

}

 if ( count( $aDadosSaida ) > 0 ) {

    ?>
    <table border="1" cellspacing="0" cellpadding="3">
      <tr bgcolor="#FFCC66"> 
        <th nowrap>MI                  </th>
        <th nowrap>Tipo                </th>
        <th nowrap>Descrição Tipo      </th>
        <th nowrap>Tipo de Movimento   </th>        
        <th nowrap>Valor               </th>
        <th nowrap>Data Lançamento     </th>
      </tr>
    <?
    
    $sCor1   = "#EFE029";
    $sCor2   = "#E4F471";    
    $sCorRow = $sCor1;
  }
    foreach ($aDadosSaida as $oCredito ) {
      
      if ($sCorRow == $sCor1) {
        $sCorRow = $sCor2;
      } else { 
        $sCorRow = $sCor1;
      }      
    ?>
      <tr bgcolor="<?=$sCorRow?>"> 
        <td align="center" nowrap >
          <?php
            /**
             * Verifica se deve exibir as informações do desconto ou da compensação
             */
            
            if ( $oCredito->sTipo == 'desconto' ) {  
              db_ancora('MI',"js_consultaDesconto({$oCredito->k125_sequencial})",1,'');
            } else {
              db_ancora('MI',"js_consultaOrigemCredito({$oCredito->k125_sequencial})",1,'');
            }

          ?>
        </td>      
        <td align="center" nowrap ><?=$oCredito->k00_tipo  ?>&nbsp;</td>
        <td align="center" nowrap ><?=$oCredito->k00_descr ?>&nbsp;</td>        
        <td align="center" nowrap ><?=$oCredito->k01_descr ?>&nbsp;</td>
        <td align="right"  nowrap ><?=db_formatar($oCredito->k00_valor,'f')   ?></td>
        <td align="center" nowrap ><?=db_formatar($oCredito->k125_datalanc, 'd') ?>&nbsp;</td>
      </tr>      
    <?
    }

  ?>
</table>
</center>
</body>
</html>
<script type="text/javascript">
        
  function js_consultaOrigemCredito(iAbatimento) {
    
    var sUrl = 'func_compensacao.php?iAbatimento='+iAbatimento;
    js_OpenJanelaIframe('top.corpo','db_iframe_compensacao',sUrl,'Origem da Compensação',true);
    
  }

  function js_consultaDesconto(iAbatimento) {

    var sUrl = 'func_compensacaodesconto.php?iAbatimento='+iAbatimento;
    js_OpenJanelaIframe('top.corpo','db_iframe_desconto',sUrl,'Origem do Desconto',true);
  }

            
</script>
<?php
/**
 * 
 */
function getDescricaoTipoDebito( $iTipoDebito ) {

 static $aTiposDebito;
 
 if (empty($aTiposDebito[$iTipoDebito])) { 

  $oDaoArretipo = new cl_arretipo();
  $sSql         = $oDaoArretipo->sql_query_file($iTipoDebito);
  $rsSql        = db_query($sSql);

  if ( !$rsSql || pg_num_rows($rsSql) == 0 ) {
    $aTiposDebito[$iTipoDebito] = '';
    return $aTiposDebito[$iTipoDebito];
  }
  $aTiposDebito[$iTipoDebito] = db_utils::fieldsMemory($rsSql,0)->k00_descr;
 }
 return $aTiposDebito[$iTipoDebito];
}