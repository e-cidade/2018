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

/**
 * 
 * @author I
 * @revision $Author: dbevandro $
 * @version $Revision: 1.41 $
 */
include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("libs/db_utils.php");
include ("classes/db_matparam_classe.php");
include ("classes/db_db_departorg_classe.php");
include ("classes/db_db_almox_classe.php");
include ("classes/db_db_almoxdepto_classe.php");
include ("classes/db_matestoque_classe.php");
include ("classes/db_matestoqueitem_classe.php");
include ("classes/db_matmater_classe.php");
include ("dbforms/db_funcoes.php");

$oParams = db_utils::postMemory($_GET);
//echo "<pre>";
//var_dump( $oParams );
//exit;

/**
 * 
 * Variáveis de configuração do Sql
 */
$sWhere                     = "";
$iCasasDecimais             = 9;
$sMovimentosNaoConsiderados = "0";
$sOrderBy                   = "1";
$sCampo                     = "m60_codmater";
$sCampoDescricao            = "m60_descr";

/**
 * Verifica se:
 * É relatório de Almoxarifado e Sintético
 * Se for altera o filtro do SQL para departamento  
 */ 

if ($oParams->tipo == 1 and $oParams->agruparporelemento == 2) {
  $oParams->agruparporelemento = 1;
}

if ($oParams->impressao == 1 && $oParams->tipo == 1) {
  $sCampo          = "m70_coddepto";
  $sCampoDescricao = "descrdepto";
} 
if ($oParams->orderby == 2) {
  $sOrderBy = "2";
}

if ( $oParams->agruparporelemento == 2 ) {
  $sOrderBy = "5, $sOrderBy";
};

if (isset($oParams->listacontas) && !empty($oParams->listacontas)) {
  $aCodContas  = explode(",", $oParams->listacontas);
}

$sCampos       = "distinct on ({$sCampo}) {$sCampo} as codigo, {$sCampoDescricao} as descricao,'0' as pc01_codsubgrupo,'' as pc04_descrsubgrupo, ";
$sCampos      .= " conplano.c60_estrut, conplano.c60_descr, conplanoreduz.c61_reduz ";
$sWhere        = "m71_servico is false ";
if (isset($oParams->listacontas) && !empty($oParams->listacontas)) {
  $sWhere     .= "and {$oParams->listacontas}";
}

$oDaoMatMater  = new cl_matestoque();
$sSqlMateriais = $oDaoMatMater->sql_query_item_grupo(null, $sCampos, "", "m71_servico is false ");

$sSqlMateriais = "select codigo, descricao, pc01_codsubgrupo, pc04_descrsubgrupo, case when y.o56_elemento is null then '9999999' else y.o56_elemento end as o56_elemento, orcelemento.o56_descr from ( select *, ( select substr(o56_elemento,1,7) from matestoque inner join matestoqueitem on m70_codigo = m71_codmatestoque inner join material.matestoqueitemoc on m71_codlanc = m73_codmatestoqueitem inner join matordemitem on m52_codlanc = m73_codmatordemitem inner join empempitem on m52_numemp = e62_numemp and m52_sequen = e62_sequen inner join empelemento on e62_numemp = e64_numemp inner join empempenho on e62_numemp = e60_numemp inner join orcelemento on o56_codele = e64_codele and o56_anousu = e60_anousu where m70_codmatmater = x.codigo group by substr(o56_elemento,1,7) order by count(*) desc limit 1 ) as o56_elemento from ( {$sSqlMateriais} ) as x ) as y left join orcelemento on orcelemento.o56_elemento = y.o56_elemento || '000000' and o56_anousu = " . db_getsession('DB_anousu') . " order by $sOrderBy ";

//$sSqlMateriais = "select * from ( $sSqlMateriais ) as x limit 10 ";

//die( $sSqlMateriais );

$rsMateriais   = $oDaoMatMater->sql_record($sSqlMateriais);
$sDataInicial  = implode("-", array_reverse(explode("/", $oParams->datainicial)));
$sDataFinal    = implode("-", array_reverse(explode("/", $oParams->datafinal)));
$where         = "";
$inner         = "";
$db_where      = "";
$db_inner      = "";
$depto_atual   = db_getsession("DB_coddepto");

if ($oParams->almoxarifado != 0) {
  $db_where = " and m70_coddepto = {$oParams->almoxarifado}";
}

/**
 * Criamos a consulta preparada para o saldo inicial
 */
$sSqlSaldoAnt  = "select sum(coalesce(case when m81_tipo = 1 then round(m82_quant,2) 																		";
$sSqlSaldoAnt .= "                when m81_tipo = 2 then round(m82_quant,2) *-1 end, 0)) as saldoInicial,								";
$sSqlSaldoAnt .= "       sum(coalesce(case when m81_tipo = 1 then round(m82_quant,2)*m89_valorunitario                  ";
$sSqlSaldoAnt .= "                when m81_tipo = 2 then round(m82_quant,2)*m89_precomedio *-1 end, 0)) as valorInicial ";
$sSqlSaldoAnt .= "  from (select m81_tipo,                                                                              ";
$sSqlSaldoAnt .= "               m82_quant,                                                                             ";
$sSqlSaldoAnt .= "               m89_valorunitario,                                                                     ";
$sSqlSaldoAnt .= "               m89_precomedio                                                                         ";
$sSqlSaldoAnt .= "          from matestoqueini                                                                          ";
$sSqlSaldoAnt .= "               inner join matestoquetipo     on m80_codtipo 			 = m81_codtipo                      ";
$sSqlSaldoAnt .= "                                            AND m80_codtipo <> 4                                      ";
$sSqlSaldoAnt .= "               inner join matestoqueinimei   on m82_matestoqueini  = m80_codigo                       ";
$sSqlSaldoAnt .= "               inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei             ";
$sSqlSaldoAnt .= "               inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                      ";
$sSqlSaldoAnt .= "               inner join matestoque         on m71_codmatestoque  = m70_codigo                       ";
$sSqlSaldoAnt .= "               inner join matmater           on m60_codmater       = m70_codmatmater                  ";
$sSqlSaldoAnt .= "               inner join db_depart 				 on m80_coddepto 			 = coddepto                         ";
$sSqlSaldoAnt .= "         where {$sCampo} = $1                                                                         ";
$sSqlSaldoAnt .= "           and m80_data < $2::date " . $db_where;
$sSqlSaldoAnt .= "           and instit  = " . db_getsession("DB_instit");
$sSqlSaldoAnt .= "           and m71_servico is false                                                                   ";
$sSqlSaldoAnt .= "         order by m80_data,                                                                           ";
$sSqlSaldoAnt .= "                  m80_hora,                                                                           ";
$sSqlSaldoAnt .= "                m80_codigo) as x                                                                      ";

$sSqlPrepared = pg_prepare("saldoini", $sSqlSaldoAnt);

$SqlSaldos  = "select m80_coddepto,                                                                                     ";
$SqlSaldos .= "       m81_entrada,                                                                                      ";
$SqlSaldos .= "       m81_tipo,                                                                                         ";
$SqlSaldos .= "       coalesce(round(m82_quant, 2),0) as quantidade,                                                    ";
$SqlSaldos .= "       round(m89_precomedio, 4) as preco_medio,                                                          ";
$SqlSaldos .= "       round(m89_valorunitario, 4) as valorunitario,                                                     ";
$SqlSaldos .= "       coalesce(m71_valor,0) as m71_valor,                                                               ";
$SqlSaldos .= "       coalesce(m71_quant, 0) as m71_quant,                                                              ";
$SqlSaldos .= "       m81_codtipo,                                                                                      ";
$SqlSaldos .= "       m70_coddepto, 																																										";
$SqlSaldos .= "       descrdepto, 											  																															";
$SqlSaldos .= "       m80_data                                                                                          ";
$SqlSaldos .= "       from (select                                                                                      ";
$SqlSaldos .= "             m80_codigo,                                                                                 ";
$SqlSaldos .= "             m80_coddepto,                                                                               ";
$SqlSaldos .= "             m81_descr,                                                                                  ";
$SqlSaldos .= "             m81_entrada,                                                                                ";
$SqlSaldos .= "             m81_tipo,                                                                                   ";
$SqlSaldos .= "             m82_quant,                                                                                  ";
$SqlSaldos .= "             m89_precomedio,                                                                             ";
$SqlSaldos .= "             m89_valorunitario,                                                                          ";
$SqlSaldos .= "             descrdepto,                                                                                 ";
$SqlSaldos .= "             m80_data,                                                                                   ";
$SqlSaldos .= "             m80_hora,                                                                                   ";
$SqlSaldos .= "             nome,                                                                                       ";
$SqlSaldos .= "             login,                                                                                      ";
$SqlSaldos .= "             m60_descr,                                                                                  ";
$SqlSaldos .= "             m71_valor,                                                                                  ";
$SqlSaldos .= "             m71_quant,                                                                                  ";
$SqlSaldos .= "             m70_coddepto,																																								";
$SqlSaldos .= "             m81_codtipo                                                                                 ";
$SqlSaldos .= "        from matestoqueini                                                                               ";
$SqlSaldos .= "             inner join matestoquetipo     on m80_codtipo        = m81_codtipo                           ";
$SqlSaldos .= "             inner join matestoqueinimei   on m82_matestoqueini  = m80_codigo                            ";
$SqlSaldos .= "             inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei                  ";
$SqlSaldos .= "             inner join db_usuarios        on m80_login          = id_usuario                            ";
$SqlSaldos .= "             inner join db_depart          on m80_coddepto       = coddepto                              ";
$SqlSaldos .= "             inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc                           ";
$SqlSaldos .= "             inner join matestoque         on m71_codmatestoque  = m70_codigo                            ";
$SqlSaldos .= "             inner join matmater           on m60_codmater 	    = m70_codmatmater                       ";
$SqlSaldos .= "             $db_inner                                                                                   ";
$SqlSaldos .= "       where instit            =   " . db_getsession("DB_instit");
$SqlSaldos .= "         and {$sCampo}   = $1" . $db_where;
$SqlSaldos .= "         and m80_data between $2 and $3                                                                  ";
$SqlSaldos .= "         and m71_servico is false                                                                        ";
$SqlSaldos .= ") as x order by m80_data,m80_hora                                                                        ";

$sSqlPreparedMov = pg_prepare("saldomov", $SqlSaldos);

$aSubtotalGrupo         = array ();
$aMateriais             = array ();
$aMateriaisSintetico    = array ();
$aTotalElemento         = array ();

$nTotalInicial   = 0;
$nTotalEntradas  = 0;
$nTotalSaidas    = 0;
$nTotalFinal     = 0;
$iTotalRegistros = 0;

for($i = 0; $i < $oDaoMatMater->numrows; $i ++) {
  $oMaterial                        = db_utils::fieldsMemory($rsMateriais, $i);

  $oMaterial->nSaldoInicial         = 0;
  $oMaterial->nValorInicial         = 0;
  $oMaterial->nValorInicialUnitario = 0;
  $oMaterial->nSaldoEntrada         = 0;
  $oMaterial->nValorEntrada         = 0;
  $oMaterial->nValorEntradaUnitario = 0;
  $oMaterial->nValorSaida           = 0;
  $oMaterial->nSaldoSaida           = 0;
  $oMaterial->nValorSaidaUnitario   = 0;
  $oMaterial->nValorUnit            = 0;
  $oMaterial->nValorUnitario        = 0;
  
  /**
   * Consultamos o saldo anterior
   */
  $rsPrepare = pg_execute("saldoini", array (    
                        $oMaterial->codigo, 
                        $sDataInicial 
  ));
  $iNumRowsSaldoIni   = pg_num_rows($rsPrepare);
  
  $nPrecoMedioInicial = 0;
  for($iIni = 0; $iIni < $iNumRowsSaldoIni; $iIni ++) {
    
    $oSaldoInicial = db_utils::fieldsMemory($rsPrepare, $iIni);
    $oMaterial->nSaldoInicial += $oSaldoInicial->saldoinicial;
    $oMaterial->nValorInicial  = $oSaldoInicial->valorinicial;
  }
  
  /**
   * Consultamos a movimentacao no periodo
   */
  $rsPeparare = pg_execute("saldomov", array (
    
                        $oMaterial->codigo, 
                        $sDataInicial, 
                        $sDataFinal 
  ));
  $iTotalMov = pg_num_rows($rsPeparare);

  for($iMov = 0; $iMov < $iTotalMov; $iMov ++) {
    
    $oSaldoMov = db_utils::fieldsMemory($rsPeparare, $iMov);
    
    $oMaterial->m70_coddepto = $oSaldoMov->m70_coddepto;
    $oMaterial->descrdepto   = $oSaldoMov->descrdepto;

    if ($oSaldoMov->m81_tipo == 1) {
      
      $oMaterial->nValorEntrada        += ($oSaldoMov->quantidade * $oSaldoMov->valorunitario);
      $oMaterial->nSaldoEntrada        += $oSaldoMov->quantidade;
      $oMaterial->nValorEntradaUnitario = $oSaldoMov->valorunitario;
    
    } else if ($oSaldoMov->m81_tipo == 2) {
      
      $oMaterial->nValorSaida        += $oSaldoMov->preco_medio * $oSaldoMov->quantidade;
      $oMaterial->nSaldoSaida        += $oSaldoMov->quantidade;
      $oMaterial->nValorSaidaUnitario = $oSaldoMov->preco_medio;
    
    }
    
    $oMaterial->nValorUnitario = $oSaldoMov->preco_medio;
    $oMaterial->nValorEntrada  = $oMaterial->nValorEntrada; //($oMaterial->nSaldoEntrada  * $oMaterial->nValorUnitario); 
    $oMaterial->nValorSaida    = $oMaterial->nValorSaida; //($oMaterial->nValorUnitario * $oMaterial->nSaldoSaida);
    unset($oSaldoMov);
  }
  
  if ($oMaterial->nSaldoEntrada == 0 and $oMaterial->nSaldoSaida == 0) {
    
    if ($oMaterial->nSaldoInicial > 0) {
      $oMaterial->nValorUnitario = $nPrecoMedioInicial;
    }
  } else {
  
  }
  
  $oMaterial->nSaldoFinal = ($oMaterial->nSaldoInicial + $oMaterial->nSaldoEntrada) - $oMaterial->nSaldoSaida;
  
  //Caso a quantidade do Saldo Final seja zero, obrigatoriamente o valor do material deverá ser zero.
  if($oMaterial->nSaldoFinal > 0) {
    $oMaterial->nValorFinal = ($oMaterial->nValorInicial + $oMaterial->nValorEntrada) - $oMaterial->nValorSaida;
  } else {
    $oMaterial->nValorFinal = 0;
  }
  
  if ($oMaterial->nSaldoInicial == 0 and ($oMaterial->nSaldoEntrada == 0 and $oMaterial->nSaldoSaida == 0) and $oParams->itenssemmovimento == "N") {
    continue;
  }
  
  if ($oMaterial->nSaldoFinal < 0 and $oParams->saldonegativo == "N") {
    continue;
  }
  
  if (isset($aSubtotalGrupo [$oMaterial->pc01_codsubgrupo])) {
    
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoInicial += $oMaterial->nValorInicial;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoFinal   += $oMaterial->nValorFinal;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoEntrada += $oMaterial->nValorEntrada;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoSaida   += $oMaterial->nValorSaida;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoPeriodo += ($oMaterial->nValorEntrada - $oMaterial->nValorSaida);
  
  } else {
    
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoInicial = $oMaterial->nValorInicial;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoFinal   = $oMaterial->nValorFinal;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoEntrada = $oMaterial->nValorEntrada;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->nSaldoSaida   = $oMaterial->nValorSaida;
    $aSubtotalGrupo [$oMaterial->pc01_codsubgrupo]->descricao     = $oMaterial->pc04_descrsubgrupo;
  
  }

  if (isset($aTotalElemento [$oMaterial->o56_elemento])) {
    $aTotalElemento [$oMaterial->o56_elemento]->nValorInicial  += $oMaterial->nValorInicial;
    $aTotalElemento [$oMaterial->o56_elemento]->nValorEntrada  += $oMaterial->nValorEntrada;
    $aTotalElemento [$oMaterial->o56_elemento]->nValorSaida    += $oMaterial->nValorSaida;
    $aTotalElemento [$oMaterial->o56_elemento]->nValorFinal    += $oMaterial->nValorFinal;
  } else {
    $aTotalElemento [$oMaterial->o56_elemento]->nValorInicial = $oMaterial->nValorInicial;
    $aTotalElemento [$oMaterial->o56_elemento]->nValorEntrada = $oMaterial->nValorEntrada;
    $aTotalElemento [$oMaterial->o56_elemento]->nValorSaida   = $oMaterial->nValorSaida;    
    $aTotalElemento [$oMaterial->o56_elemento]->nValorFinal   = $oMaterial->nValorFinal;
    $aTotalElemento [$oMaterial->o56_elemento]->descricao     = $oMaterial->o56_elemento . '000000 - ' . $oMaterial->o56_descr;
  }
  
  $nTotalInicial  += $oMaterial->nValorInicial;
  $nTotalEntradas += $oMaterial->nValorEntrada;
  $nTotalSaidas   += $oMaterial->nValorSaida;
  $nTotalFinal    += $oMaterial->nValorFinal;
  
  $iTotalRegistros ++;
  
  $aMateriais[] = $oMaterial;
  unset($oSaldoInicial);

}

/**
 * Se for selecionado um tipo de almoxarifado e o Tipo de Impressão for Sintética
 */

// ************************* ACHO QUE NÃO VAI MAIS PRECISAR DESTE TRECHO *************************
/*
if (($oParams->impressao == 1) && ($oParams->tipo == 1) && ($oParams->almoxarifado != 0)) {
  
  foreach ($aMateriais as $oMaterial) {
    
    $oAlmoxarifado = criaObjetoSintetico();
    
    if(empty($oMaterial->codigo)) {
      continue;
    }
    
    if(array_key_exists($oMaterial->codigo, $aMateriaisSintetico)) {
      
      $aMateriaisSintetico[$oMaterial->$codigo]->nSaldoInicial          +=  $oMaterial->nSaldoInicial;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorInicial          +=  $oMaterial->nValorInicial;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorInicialUnitario  +=  $oMaterial->nValorInicialUnitario;
      $aMateriaisSintetico[$oMaterial->$codigo]->nSaldoEntrada          +=  $oMaterial->nSaldoEntrada;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorEntrada          +=  $oMaterial->nValorEntrada;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorEntradaUnitario  +=  $oMaterial->nValorEntradaUnitario;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorSaida            +=  $oMaterial->nValorSaida;
      $aMateriaisSintetico[$oMaterial->$codigo]->nSaldoSaida            +=  $oMaterial->nSaldoSaida;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorSaidaUnitario    +=  $oMaterial->nValorSaidaUnitario;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorUnit             +=  $oMaterial->nValorUnit;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorUnitario         +=  $oMaterial->nValorUnitario;
      $aMateriaisSintetico[$oMaterial->$codigo]->nSaldoFinal            +=  $oMaterial->nSaldoFinal;
      $aMateriaisSintetico[$oMaterial->$codigo]->nValorFinal            +=  $oMaterial->nValorFinal;      
    } else {
      
      $oAlmoxarifado->nSaldoInicial         = $oMaterial->nSaldoInicial;
      $oAlmoxarifado->nValorInicial         = $oMaterial->nValorInicial;
      $oAlmoxarifado->nValorInicialUnitario = $oMaterial->nValorInicialUnitario;
      $oAlmoxarifado->nSaldoEntrada         = $oMaterial->nSaldoEntrada;
      $oAlmoxarifado->nValorEntrada         = $oMaterial->nValorEntrada;
      $oAlmoxarifado->nValorEntradaUnitario = $oMaterial->nValorEntradaUnitario;
      $oAlmoxarifado->nValorSaida           = $oMaterial->nValorSaida;
      $oAlmoxarifado->nSaldoSaida           = $oMaterial->nSaldoSaida;
      $oAlmoxarifado->nValorSaidaUnitario   = $oMaterial->nValorSaidaUnitario;
      $oAlmoxarifado->nValorUnit            = $oMaterial->nValorUnit;
      $oAlmoxarifado->nValorUnitario        = $oMaterial->nValorUnitario;
      $oAlmoxarifado->nSaldoFinal           = $oMaterial->nSaldoFinal;
      $oAlmoxarifado->nValorFinal           = $oMaterial->nValorFinal;
      $oAlmoxarifado->codigo                = $oMaterial->codigo;
      $oAlmoxarifado->descricao             = $oMaterial->descricao;
      $oAlmoxarifado->pc01_codsubgrupo      = $oMaterial->pc01_codsubgrupo;
      $oAlmoxarifado->pc04_descrsubgrupo    = $oMaterial->pc04_descrsubgrupo;
      $oAlmoxarifado->c60_estrut            = $oMaterial->c60_estrut;
      $oAlmoxarifado->c60_descr             = $oMaterial->c60_descr;
      $oAlmoxarifado->c61_reduz             = $oMaterial->c61_reduz;
      $oAlmoxarifado->descrdepto            = $oMaterial->descrdepto;
      
      $aMateriaisSintetico[$oMaterial->codigo] = $oAlmoxarifado;
      
    }
  }
}
*/

/**
 * Se for selecionado para imprimir o relatório como Conta
 * Não imprime quando a estrutura vem vazio
 */
if($oParams->impressao == 2) {
  
  foreach ($aMateriais as $oMaterial) {
    
    $oConta  = criaObjetoSintetico();
    
    if(empty($oMaterial->c60_estrut)) {
      continue;
    }
    
    if (array_key_exists($oMaterial->c60_estrut, $aMateriaisSintetico)) {
      
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nSaldoInicial          +=  $oMaterial->nSaldoInicial;        
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorInicial          +=  $oMaterial->nValorInicial;        
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorInicialUnitario  +=  $oMaterial->nValorInicialUnitario;
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nSaldoEntrada          +=  $oMaterial->nSaldoEntrada;        
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorEntrada          +=  $oMaterial->nValorEntrada;        
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorEntradaUnitario  +=  $oMaterial->nValorEntradaUnitario;
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorSaida            +=  $oMaterial->nValorSaida;          
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nSaldoSaida            +=  $oMaterial->nSaldoSaida;          
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorSaidaUnitario    +=  $oMaterial->nValorSaidaUnitario;  
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorUnit             +=  $oMaterial->nValorUnit;           
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorUnitario         +=  $oMaterial->nValorUnitario;       
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nSaldoFinal            +=  $oMaterial->nSaldoFinal;          
      $aMateriaisSintetico[$oMaterial->c60_estrut]->nValorFinal            +=  $oMaterial->nValorFinal;          
      
    } else {
      
     $oConta->nSaldoInicial         = $oMaterial->nSaldoInicial;
     $oConta->nValorInicial         = $oMaterial->nValorInicial;
     $oConta->nValorInicialUnitario = $oMaterial->nValorInicialUnitario;
     $oConta->nSaldoEntrada         = $oMaterial->nSaldoEntrada;
     $oConta->nValorEntrada         = $oMaterial->nValorEntrada;
     $oConta->nValorEntradaUnitario = $oMaterial->nValorEntradaUnitario;
     $oConta->nValorSaida           = $oMaterial->nValorSaida;
     $oConta->nSaldoSaida           = $oMaterial->nSaldoSaida;
     $oConta->nValorSaidaUnitario   = $oMaterial->nValorSaidaUnitario;
     $oConta->nValorUnit            = $oMaterial->nValorUnit;
     $oConta->nValorUnitario        = $oMaterial->nValorUnitario;
     $oConta->nSaldoFinal           = $oMaterial->nSaldoFinal;
     $oConta->nValorFinal           = $oMaterial->nValorFinal;
     if (isset($oMaterial->m60_codmater)) {
       $oConta->m60_codmater          = $oMaterial->m60_codmater;       
     }
     if (isset($oMaterial->m60_descr)) {
       $oConta->m60_descr             = $oMaterial->m60_descr;
     }          
     $oConta->pc01_codsubgrupo      = $oMaterial->pc01_codsubgrupo;   
     $oConta->pc04_descrsubgrupo    = $oMaterial->pc04_descrsubgrupo; 
     $oConta->c60_estrut            = $oMaterial->c60_estrut;        
     $oConta->c60_descr             = $oMaterial->c60_descr;         
     $oConta->c61_reduz             = $oMaterial->c61_reduz;
     if (isset($oMaterial->m70_coddepto)) {
       
       $oConta->m70_coddepto                   = $oMaterial->m70_coddepto;
       $oConta->descrdepto                     = $oMaterial->descrdepto;
     }
     $aMateriaisSintetico[$oMaterial->c60_estrut] = $oConta;
    }
  }
}

if ($oParams->impressao == 2 ) {
  unset($aMateriais);
  $aMateriais = $aMateriaisSintetico;
}


/** Variáveis de configuração para o PDF
 * 
 */
$sTipoRelatório              = "AlmoxarifadoAnalitico";
$aConfigPdf                  = array();
$aConfigPdf[1]['size']       = 10;
$aConfigPdf[1]['label']      = 'Item';
$aConfigPdf[2]['size']       = 50;
$aConfigPdf[21]['size']      = 10;
$aConfigPdf[21]['label']     = 'Elem';
$aConfigPdf[2]['label']      = 'Descrição';
$aConfigPdf[3]['sizeTitulo'] = 48;
$aConfigPdf[3]['subTitulo1']  = 20.5;
$aConfigPdf[3]['subTitulo2']  = 27.5;
$aConfigPdf[6]['sizeTitulo'] = 55;
$aConfigPdf[6]['subTitulo1'] = 18;
$aConfigPdf[6]['subTitulo2'] = 18;
$aConfigPdf[6]['subTitulo3'] = 19;
$setPosicaoX                 = 70 + ($oParams->agruparporelemento == 2?10:0); 

if ($oParams->impressao == 2) {
  
  $sTipoRelatório              = "Conta";
  $aConfigPdf[1]['size']       = 12;
  $aConfigPdf[1]['label']      = 'Reduz';
  $aConfigPdf[2]['size']       = 60;
  $aConfigPdf[2]['label']      = 'Conta';
  $aConfigPdf[3]['sizeTitulo'] = 50;
  $aConfigPdf[3]['subTitulo1']  = 25;
  $aConfigPdf[3]['subTitulo2']  = 25;
  $aConfigPdf[6]['sizeTitulo'] = 56;
  $aConfigPdf[6]['subTitulo1'] = 18;
  $aConfigPdf[6]['subTitulo2'] = 18;
  $aConfigPdf[6]['subTitulo3'] = 20;
  $setPosicaoX                 = 82;
} else if ($oParams->impressao == 1 && $oParams->tipo == 1) {

  $sTipoRelatório              = "AlmoxarifadoSintetico";
  $aConfigPdf[1]['size']       = 15;
  $aConfigPdf[1]['label']      = 'Codigo';
  $aConfigPdf[2]['size']       = 50;
  $aConfigPdf[2]['label']      = 'Departamento';
  $aConfigPdf[3]['sizeTitulo'] = 52;
  $aConfigPdf[3]['subTitulo1']  = 26;
  $aConfigPdf[3]['subTitulo2']  = 26;
  $aConfigPdf[6]['sizeTitulo'] = 58;
  $aConfigPdf[6]['subTitulo1'] = 19;
  $aConfigPdf[6]['subTitulo2'] = 19;
  $aConfigPdf[6]['subTitulo3'] = 20;
  $setPosicaoX                 = 75;
}

$head1 = "INVENTÁRIO  FÍSICO-CONTÁBIL";
$head2 = "Período: {$oParams->datainicial} a {$oParams->datafinal}";
$head3 = "Geral: Todos os almoxarifados";
if ($oParams->impressao == 2) {
  $head3 = "Geral: Todas as Contas Selecionadas";  
}

$head4 = "Exibe Saldos Negativos: " . ($oParams->saldonegativo == "N" ? "Não" : "Sim");
$head5 = "Exibe Itens Sem Movimento: " . ($oParams->itenssemmovimento == "N" ? "Não" : "Sim");

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false, 0);

if ($oParams->almoxarifado != 0) {

  $oDaoDepart = db_utils::getDao("db_depart");
  $sSql       = $oDaoDepart->sql_query_file($oParams->almoxarifado);
  $rsDepart   = $oDaoDepart->sql_record($sSql);
  $oDepto     = db_utils::fieldsMemory($rsDepart, 0);
  $head3      = "Almox:{$oParams->almoxarifado} - {$oDepto->descrdepto}";

}

$sFonte          = "arial";
$iAlt            = 4;
$lEscreveHeader  = true;
$i               = 1;
$iTotalRegistros = 0;

$cElementoAnterior = "";
if ( $oDaoMatMater->numrows > 0) {
  $oMaterial = db_utils::fieldsMemory($rsMateriais, 0);
  $cElementoAnterior = $oMaterial->o56_elemento;
}

foreach ( $aMateriais as $oMaterial ) {

  if ($oMaterial->nSaldoInicial == 0 and ($oMaterial->nSaldoEntrada == 0 and $oMaterial->nSaldoSaida == 0) and $oParams->itenssemmovimento == "N") {
    continue;
  }

  if ($pdf->GetY() > $pdf->h - 30 or $lEscreveHeader) {

    $pdf->AddPage("L");
    $pdf->SetFillColor(230);
    $pdf->SetFont($sFonte, "b", 9);
    $iAlturaHeader = $pdf->getY();
    
    /**
     * Titulo 
     */
    $pdf->cell($aConfigPdf[1]['size'], $iAlt * 2, $aConfigPdf[1]['label'],                           "TBR", 0, "C", 1);
    $pdf->cell($aConfigPdf[2]['size'], $iAlt * 2, $aConfigPdf[2]['label'],                               1, 0, "C", 1);
    if ( $oParams->agruparporelemento == 2 ) {
      $pdf->cell($aConfigPdf[21]['size'], $iAlt * 2, $aConfigPdf[21]['label'],                           1, 0, "C", 1);
    }
    $pdf->cell($aConfigPdf[3]['sizeTitulo'], $iAlt,     "Saldo Anterior a ",                             1, 0, "C", 1);
    $pdf->cell($aConfigPdf[3]['sizeTitulo'], $iAlt * 2, "Entradas no Período",                           1, 0, "C", 1);
    $pdf->cell($aConfigPdf[3]['sizeTitulo'], $iAlt * 2, "Saídas no Período",                             1, 0, "C", 1);
    $pdf->cell($aConfigPdf[6]['sizeTitulo'], $iAlt * 2, "Saldo em " . db_formatar($sDataFinal, "d"), "TBL", 1, "C", 1);
    
    /**
     * SubTitulo 
     */

    $pdf->SetXY($setPosicaoX, $iAlturaHeader + $iAlt);
    $pdf->cell($aConfigPdf[3]['sizeTitulo'], $iAlt, db_formatar($sDataInicial, "d"), "BLR", 1, "C", 1);

    $pdf->cell($aConfigPdf[1]['size'],      $iAlt, '',            "TBR", 0, '', 1);
    $pdf->cell($aConfigPdf[2]['size'],      $iAlt, '',                1, 0, '', 1);
    if ( $oParams->agruparporelemento == 2 ) {
      $pdf->cell($aConfigPdf[21]['size'],     $iAlt, '',                1, 0, '', 1);
    }
    $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, 'Quant',           1, 0, 'C', 1);
    $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, 'Vlr. Total',      1, 0, 'C', 1);
                                                                      
    $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, 'Quant',           1, 0, 'C', 1);
    $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, 'Vlr. Total',      1, 0, 'C', 1);
                                                                      
    $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, 'Quant',           1, 0, 'C', 1);
    $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, 'Vlr. Total',      1, 0, 'C', 1);
                                                                      
    $pdf->cell($aConfigPdf[6]['subTitulo1'], $iAlt, 'Quant',          1, 0, 'C', 1);
    $pdf->cell($aConfigPdf[6]['subTitulo2'], $iAlt, 'Vlr. Unit',      1, 0, 'C', 1);
    $pdf->cell($aConfigPdf[6]['subTitulo3'], $iAlt, 'Vlr. Total', "TBL", 1, 'C', 1);
    $pdf->SetFont($sFonte, '', 8);
    $lEscreveHeader = false;
    $pdf->SetFillColor(245);

  }

  $iFill = 0;
  if ($i % 2 == 0) {
    $iFill = 0;
  }

  $nValorUnitarioEntrada = $oMaterial->nValorUnitario;
  if ($oMaterial->nSaldoEntrada == 0) {
    $nValorUnitarioEntrada = 0;
  }
  $nValorUnitarioSaida = $oMaterial->nValorUnitario;
  if ($oMaterial->nSaldoSaida == 0) {
    $nValorUnitarioSaida = 0;
  }
  
  if ( $cElementoAnterior != $oMaterial->o56_elemento and $oParams->agruparporelemento == 2 ) {

    $pdf->Cell($aConfigPdf[1]['size'] + $aConfigPdf[2]['size'] + $aConfigPdf[21]['size'], $iAlt, "", "TBR", 0, "L", 1);

    $pdf->Cell($aConfigPdf[3]['subTitulo1'], $iAlt, '', 1, 0, 'L', 1);
    $pdf->Cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorInicial, "f"),    1, 0, 'R', 1);
    $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, '',                                  1, 0, 'R', 1);                   
    $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorEntrada, "f"),   1, 0, 'R', 1);
    $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, '',                                  1, 0, 'R', 1);                   
    $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorSaida, "f"),     1, 0, 'R', 1);
    $pdf->cell($aConfigPdf[6]['subTitulo1'], $iAlt, '',                                 1, 0, 'R', 1);
    $pdf->cell($aConfigPdf[6]['subTitulo2'], $iAlt, '',                                 1, 0, 'R', 1);
    $pdf->cell($aConfigPdf[6]['subTitulo3'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorFinal, "f"), 'TLB', 0, 'R', 1);
    $pdf->ln();

    $cElementoAnterior = $oMaterial->o56_elemento;

  }

  /**
   * Verifica o Tipo de Relatório impresso
   */
  switch ($sTipoRelatório) {
    
    case 'Conta':
      
      $sDescricao = "{$oMaterial->c60_estrut} - {$oMaterial->c60_descr}";
      $pdf->cell($aConfigPdf[1]['size'], $iAlt, $oMaterial->c61_reduz, "TBR", 0, "R", $iFill);
      $pdf->SetFont($sFonte, '', 6);
      $pdf->cell($aConfigPdf[2]['size'], $iAlt, substr($sDescricao, 0, 50), 1, 0, "L", $iFill);
      $pdf->SetFont($sFonte, '', 8);
      break;
    case 'AlmoxarifadoSintetico':
      
      $pdf->cell($aConfigPdf[1]['size'], $iAlt, $oMaterial->codigo, "TBR", 0, "R", $iFill);
      $pdf->SetFont($sFonte, '', 6);
      $pdf->cell($aConfigPdf[2]['size'], $iAlt, substr($oMaterial->descricao, 0, 32), 1, 0, "L", $iFill);
      $pdf->SetFont($sFonte, '', 8);
      break;
    default:

      $pdf->cell($aConfigPdf[1]['size'], $iAlt, $oMaterial->codigo, "TBR", 0, "R", $iFill);
      $pdf->SetFont($sFonte, '', 6);
      $pdf->cell($aConfigPdf[2]['size'], $iAlt, substr($oMaterial->descricao, 0, 32), 1, 0, "L", $iFill);
      if ( $oParams->agruparporelemento == 2 ) {
        $pdf->cell($aConfigPdf[21]['size'], $iAlt, $oMaterial->o56_elemento, 1, 0, "L", $iFill);
      }
      $pdf->SetFont($sFonte, '', 8);
      break;
    
  }
  
  $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, number_format($oMaterial->nSaldoInicial, 2, ',', '.'), 1, 0, 'R', $iFill);
  $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, number_format((float)$oMaterial->nValorInicial, 2, ',', '.'), 1, 0, 'R', $iFill);

  $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, number_format($oMaterial->nSaldoEntrada,  2, ',', '.'), 1, 0, 'R', $iFill);
  $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, number_format($oMaterial->nValorEntrada,  2, ',', '.'), 1, 0, 'R', $iFill);

  if ($oMaterial->nSaldoSaida == "") {
    $oMaterial->nSaldoSaida = 0;
  }
  $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, number_format($oMaterial->nSaldoSaida, 2, ',', '.'), 1, 0, 'R', $iFill);
  $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, number_format($oMaterial->nValorSaida, 2, ',', '.'), 1, 0, 'R', $iFill);

  $pdf->cell($aConfigPdf[6]['subTitulo1'], $iAlt, number_format(($oMaterial->nSaldoFinal), 2, ',', '.'), 1, 0, 'R', $iFill);
  $pdf->cell($aConfigPdf[6]['subTitulo2'], $iAlt, number_format(round($oMaterial->nValorUnitario, 4), 2, ',', '.'), 1, 0, 'R', $iFill);
  $pdf->cell($aConfigPdf[6]['subTitulo3'], $iAlt, number_format($oMaterial->nValorFinal, 2, ',', '.'), "TBL", 1, 'R', $iFill);

  $i ++;
  $iTotalRegistros ++;

}
  
if ( $oParams->agruparporelemento == 2 ) {

  $pdf->Cell($aConfigPdf[1]['size'] + $aConfigPdf[2]['size'] + $aConfigPdf[21]['size'], $iAlt, "", "TBR", 0, "L", 1);

  $pdf->Cell($aConfigPdf[3]['subTitulo1'], $iAlt, '', 1, 0, 'L', 1);
  $pdf->Cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorInicial, "f"),    1, 0, 'R', 1);
  $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, '',                                  1, 0, 'R', 1);                   
  $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorEntrada, "f"),   1, 0, 'R', 1);
  $pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, '',                                  1, 0, 'R', 1);                   
  $pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorSaida, "f"),     1, 0, 'R', 1);
  $pdf->cell($aConfigPdf[6]['subTitulo1'], $iAlt, '',                                 1, 0, 'R', 1);
  $pdf->cell($aConfigPdf[6]['subTitulo2'], $iAlt, '',                                 1, 0, 'R', 1);
  $pdf->cell($aConfigPdf[6]['subTitulo3'], $iAlt, db_formatar($aTotalElemento [$cElementoAnterior]->nValorFinal, "f"), 'TLB', 0, 'R', 1);
  $pdf->ln();

  $cElementoAnterior = $oMaterial->o56_elemento;

}

/**
* Imprime Totalizador
*/

$pdf->SetFont($sFonte, 'b', 8);
$pdf->Cell($aConfigPdf[1]['size'] + $aConfigPdf[2]['size'] + ($oParams->agruparporelemento == 2?$aConfigPdf[21]['size']:0), $iAlt, $iTotalRegistros . " Registros", "TBR", 0, "L", 1);
$pdf->Cell($aConfigPdf[3]['subTitulo1'], $iAlt, '', 1, 0, 'L', 1);
$pdf->Cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($nTotalInicial, "f"),    1, 0, 'R', 1);
$pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, '',                                  1, 0, 'R', 1);                   
$pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($nTotalEntradas, "f"),   1, 0, 'R', 1);
$pdf->cell($aConfigPdf[3]['subTitulo1'], $iAlt, '',                                  1, 0, 'R', 1);                   
$pdf->cell($aConfigPdf[3]['subTitulo2'], $iAlt, db_formatar($nTotalSaidas, "f"),     1, 0, 'R', 1);
$pdf->cell($aConfigPdf[6]['subTitulo1'], $iAlt, '',                                 1, 0, 'R', 1);
$pdf->cell($aConfigPdf[6]['subTitulo2'], $iAlt, '',                                 1, 0, 'R', 1);
$pdf->cell($aConfigPdf[6]['subTitulo3'], $iAlt, db_formatar($nTotalFinal, "f"), 'TLB', 0, 'R', 1);

$pdf->AddPage("L");

$nTotalGeral        = 0;
$nTotalGeralInicial = 0;
$nTotalGeralEntrada = 0;
$nTotalGeralSaida   = 0;
  
$pdf->SetFont($sFonte, "b", 9);
$pdf->cell(150, $iAlt, 'ELEMENTO'      , 1, 0, 'L', $iFill);
$pdf->cell(040, $iAlt, 'SALDO ANTERIOR', 1, 0, 'R', $iFill);
$pdf->cell(040, $iAlt, 'SALDO ENTRADA' , 1, 0, 'R', $iFill);
$pdf->cell(040, $iAlt, 'SALDO SAÍDA'   , 1, 0, 'R', $iFill);
$pdf->cell(040, $iAlt, 'SALDO FINAL'   , 1, 1, 'R', $iFill);
$pdf->SetFont($sFonte, "", 8);

foreach ( $aTotalElemento as $oTotalElemento ) {

  $pdf->cell(150, $iAlt, $oTotalElemento->descricao, 1, 0, 'L', $iFill);
  $pdf->cell(040, $iAlt, number_format((float)$oTotalElemento->nValorInicial, 2, ',', '.'), 1, 0, 'R', $iFill);    
  $pdf->cell(040, $iAlt, number_format((float)$oTotalElemento->nValorEntrada, 2, ',', '.'), 1, 0, 'R', $iFill);    
  $pdf->cell(040, $iAlt, number_format((float)$oTotalElemento->nValorSaida, 2, ',', '.')  , 1, 0, 'R', $iFill);    
  $pdf->cell(040, $iAlt, number_format((float)$oTotalElemento->nValorFinal, 2, ',', '.')  , 1, 1, 'R', $iFill);
  
  $nTotalGeralInicial += $oTotalElemento->nValorInicial;
  $nTotalGeralEntrada += $oTotalElemento->nValorEntrada;
  $nTotalGeralSaida   += $oTotalElemento->nValorSaida;
  $nTotalGeral        += $oTotalElemento->nValorFinal;
  
}
$pdf->SetFont($sFonte, "b", 9);
$pdf->cell(150, $iAlt, 'TOTAL GERAL', 1, 0, 'L', $iFill);
$pdf->cell(040, $iAlt, number_format((float) $nTotalGeralInicial, 2, ',', '.'), 1, 0, 'R', $iFill);
$pdf->cell(040, $iAlt, number_format((float) $nTotalGeralEntrada, 2, ',', '.'), 1, 0, 'R', $iFill);
$pdf->cell(040, $iAlt, number_format((float) $nTotalGeralSaida, 2, ',', '.')  , 1, 0, 'R', $iFill);
$pdf->cell(040, $iAlt, number_format((float) $nTotalGeral, 2, ',', '.')       , 1, 1, 'R', $iFill);
$pdf->ln();

$pdf->Output();

/**
*
* Cria um Objeto de referência quando a necessidade de imprimir um relatório Sintético
* @return stdClass
*/
function criaObjetoSintetico() {

  $oSintetico  = new stdClass();
  $oSintetico->m60_codmater          = 0;
  $oSintetico->m60_descr             = "";
  $oSintetico->pc01_codsubgrupo      = 0;
  $oSintetico->pc04_descrsubgrupo    = "";
  $oSintetico->c60_estrut            = 0;
  $oSintetico->c60_descr             = "";
  $oSintetico->c61_reduz             = 0;
  $oSintetico->m70_coddepto          = 0;
  $oSintetico->descrdepto            = "";
  $oSintetico->nSaldoInicial         = 0;
  $oSintetico->nValorInicial         = 0;
  $oSintetico->nValorInicialUnitario = 0;
  $oSintetico->nSaldoEntrada         = 0;
  $oSintetico->nValorEntrada         = 0;
  $oSintetico->nValorEntradaUnitario = 0;
  $oSintetico->nValorSaida           = 0;
  $oSintetico->nSaldoSaida           = 0;
  $oSintetico->nValorSaidaUnitario   = 0;
  $oSintetico->nValorUnit            = 0;
  $oSintetico->nValorUnitario        = 0;
  $oSintetico->nSaldoFinal           = 0;
  $oSintetico->nValorFinal           = 0;
  return $oSintetico;
}

?>