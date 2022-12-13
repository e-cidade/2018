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


require_once("classes/db_issbase_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oInstit = db_stdClass::getDadosInstit();
$head1     = "";
$head2     = "";
$sqlparag  = " select db02_texto ";
$sqlparag .= "   from db_documento ";
$sqlparag .= "        inner join db_docparag  on db03_docum   = db04_docum ";
$sqlparag .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
$sqlparag .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
$sqlparag .= " where db03_tipodoc = 1017 ";
$sqlparag .= "   and db03_instit = ".db_getsession("DB_instit")." ";
$sqlparag .= " order by db04_ordem ";
$resparag = db_query($sqlparag);

if (pg_numrows($resparag) == 0) {
  $head3 = "SECRETARIA DA FAZENDA";
} else {
  db_fieldsmemory($resparag, 0);
  $head3 = $db02_texto;
}

$head4 = "Relatório dos Pagamentos Efetuados";
    
$aWherePagamento    = array();
$sWhereNumpreNormal = "";
$sWhereNumprePgto   = "";
$sInnerPagamento = "";

  
if (isset($numcgm)) {
    
  $sSqlEnderCgm  = " select *                    "; 
  $sSqlEnderCgm .= "   from cgm                  "; 
  $sSqlEnderCgm .= "  where z01_numcgm = $numcgm ";
    
  $oEnderCgm     =  db_utils::fieldsMemory(db_query($sSqlEnderCgm),0);
    
  $nome  = 'CGM         : '.$oEnderCgm->z01_numcgm.' - '.$oEnderCgm->z01_nome;
  $ender = $oEnderCgm->z01_ender.', '.$oEnderCgm->z01_numero.' '.$oEnderCgm->z01_compl;
    
  $aWherePagamento[] = " arrenumcgm.k00_numcgm = ".$numcgm;

} else if(isset($matric)) {

  $sSqlEnderMatric  = " select * "; 
  $sSqlEnderMatric .= "   from proprietario         "; 
  $sSqlEnderMatric .= "  where j01_matric = $matric "; 
                          
  $oEnderMatric     = db_utils::fieldsMemory(db_query($sSqlEnderMatric),0);
    
  $sSqlEnvol        = " select rvnome                                                                   "; 
  $sSqlEnvol       .= "   from fc_busca_envolvidos(true, {$oInstit->db21_regracgmiptu}, 'M', {$matric}) ";
    
  $oEnvolvidos      = db_utils::fieldsMemory(db_query($sSqlEnvol),0);
    
    
  $nome  = 'Matrícula : '.$matric.' - '  . $oEnvolvidos->rvnome;
  $ender = $oEnderMatric->j14_tipo.' ' . $oEnderMatric->j14_nome
           .' -  ZONA : ' . $oEnderMatric->j37_zona
           .'  SETOR : '  . $oEnderMatric->j34_setor
           .'  QUADRA : ' . $oEnderMatric->j34_quadra
           .'  LOTE : '   . $oEnderMatric->j34_lote
           .'  PQL : '.$oEnderMatric->pql_localizacao;    
    
    
  $aWherePagamento[] = " arrematric.k00_matric = ".$matric;
  
} else if(isset($inscr)) {

    
  $sSqlEnderInscr = " select * 
                        from empresa 
                       where q02_inscr = $inscr ";
  
  $oEnderInscr    = db_utils::fieldsMemory(db_query($sSqlEnderInscr),0);
    
  $sSqlEnvol      = " select rvnome 
                        from fc_busca_envolvidos(true, {$oInstit->db21_regracgmiss}, 'I', {$inscr}) ";
                          
  $oEnvolvidos    = db_utils::fieldsMemory(db_query($sSqlEnvol),0);                          
    
    
  $nome = "Inscrição : {$inscr} - {$oEnvolvidos->rvnome}";
    
  if (trim($oEnderInscr->z01_nomefanta) != "") {
    $nome .= " - Nome fantasia: ".trim($oEnderInscr->z01_nomefanta);
  }
    
  $ender = $oEnderInscr->j14_tipo   . ' '
          .$oEnderInscr->z01_ender  . ', '
          .$oEnderInscr->z01_numero . ' '
          .$oEnderInscr->z01_compl;    
    
    
  $aWherePagamento[] = " arreinscr.k00_inscr   = ".$inscr;    

} else {

  $sSqlEnderNumpre  = " select cgm.*                                                                                 ";
  $sSqlEnderNumpre .= "   from ( select arrecad.k00_numcgm                                                           ";
  $sSqlEnderNumpre .= "            from arrecad                                                                      ";
  $sSqlEnderNumpre .= "                 inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre         ";
  $sSqlEnderNumpre .= "                                       and arreinstit.k00_instit = ".db_getsession('DB_instit');
  $sSqlEnderNumpre .= "            where arrecad.k00_numpre = $numpre                                                ";
  $sSqlEnderNumpre .= "                                                                                              ";
  $sSqlEnderNumpre .= "          union all                                                                           ";
  $sSqlEnderNumpre .= "                                                                                              ";
  $sSqlEnderNumpre .= "          select arrecant.k00_numcgm                                                          ";
  $sSqlEnderNumpre .= "            from arrecant                                                                     ";
  $sSqlEnderNumpre .= "                 inner join arreinstit  on arreinstit.k00_numpre = arrecant.k00_numpre        ";
  $sSqlEnderNumpre .= "                                       and arreinstit.k00_instit = ".db_getsession('DB_instit');
  $sSqlEnderNumpre .= "            where arrecant.k00_numpre = $numpre                                               ";
  $sSqlEnderNumpre .= "                                                                                              ";
  $sSqlEnderNumpre .= "        ) as x                                                                                ";
  $sSqlEnderNumpre .= "        inner join cgm on z01_numcgm = x.k00_numcgm                                           ";
                        
  $oEnderNumpre = db_utils::fieldsMemory(db_query($sSqlEnderNumpre),0);
    
  $nome  = 'CGM         : '.$oEnderNumpre->z01_numcgm.' - '.$oEnderNumpre->z01_nome.'       Cód. Arrecadação : '.$numpre;
  $ender = $oEnderNumpre->z01_ender.', '.$oEnderNumpre->z01_numero.' '.$oEnderNumpre->z01_compl;    
    
  $sWhereNumpreNormal = " and arrepaga.k00_numpre   = ".$numpre;
   
  $sWhereNumprePgto   = " and (  arrepaga.k00_numpre = {$numpre} "; 
  $sWhereNumprePgto  .= "     or arreckey.k00_numpre = {$numpre} ";
  $sWhereNumprePgto  .= "     )                                  ";  
    
} 
  
if ( isset($datainicial) ) {
  $head5 = 'Período : '.db_formatar($datainicial,'d').' a '.db_formatar($datafinal,'d');
  $aWherePagamento[] = " arrepaga.k00_dtpaga between '$datainicial' and '$datafinal' ";
} 

  
if (isset($receita)) {
  $head6             = 'Receita : '.$receita;
  $aWherePagamento[] = " arrepaga.k00_receit = ".$receita;
} else if (isset($k02_codigo)) {
  $head6             = 'Receita : '.$k02_codigo;
  $aWherePagamento[] = " arrepaga.k00_receit = ".$k02_codigo;
}
  
  
if ( isset($conta) ) {
  $aWherePagamento[] = " arrepaga.k00_conta = ".$conta;
  $head7             = 'Conta : '.$conta;
} 

if (isset($v70_sequencial)) {
    
    $sInnerPagamento .= " inner join ( select distinct "; 
    $sInnerPagamento .= "                      case ";
    $sInnerPagamento .= "                       when termo.v07_numpre is null"; 
    $sInnerPagamento .= "                         then inicialnumpre.v59_numpre"; 
    $sInnerPagamento .= "                       else termo.v07_numpre ";
    $sInnerPagamento .= "                     end as numpre, ";
    $sInnerPagamento .= "                     processoforoinicial.v71_processoforo ";    
    $sInnerPagamento .= "                from processoforoinicial"; 
    $sInnerPagamento .= "                left join termoini      on inicial          = v71_inicial"; 
    $sInnerPagamento .= "                left join termo         on termo.v07_parcel = termoini.parcel"; 
    $sInnerPagamento .= "                left join inicialnumpre on inicialnumpre.v59_inicial = v71_inicial"; 
    $sInnerPagamento .= "               where processoforoinicial.v71_processoforo = {$v70_sequencial}"; 
    $sInnerPagamento .= "            ) as processoforo on processoforo.numpre = arrepaga.k00_numpre ";
        
	$aWherePagamento[] = " processoforo.v71_processoforo = ".$v70_sequencial;
}
   
$sWherePagamento   = implode(" and ", $aWherePagamento);
  
  
if (trim($sWherePagamento) != '') {
  $sWherePagamento = " and ".$sWherePagamento;
}
  
  
$sSqlPagamentos  = " select distinct                                                                                                     ";
$sSqlPagamentos .= "        arrepaga.k00_numcgm,                                                                                         ";
$sSqlPagamentos .= "        arrepaga.k00_numpre,                                                                                         ";
$sSqlPagamentos .= "        arrepaga.k00_numpar,                                                                                         ";
$sSqlPagamentos .= "        arrepaga.k00_numtot,                                                                                         ";
$sSqlPagamentos .= "        case when arrecant.k00_dtvenc is null then arrepaga.k00_dtvenc else arrecant.k00_dtvenc end as k00_dtvenc,   ";
$sSqlPagamentos .= "        case when arrecant.k00_dtoper is null then arrepaga.k00_dtoper else arrecant.k00_dtoper end as k00_dtoper,   ";
$sSqlPagamentos .= "        arrepaga.k00_receit,                                                                                         ";
$sSqlPagamentos .= "        k02_drecei,                                                                                                  ";
$sSqlPagamentos .= "        arrepaga.k00_hist,                                                                                           ";
$sSqlPagamentos .= "        k01_descr,                                                                                                   ";
//$sSqlPagamentos .= "        sum(arrepaga.k00_valor) as k00_valor, ";
$sSqlPagamentos .= "        arrepaga.k00_valor,                                                                                          ";
$sSqlPagamentos .= "        arrepaga.k00_conta,                                                                                          ";
$sSqlPagamentos .= "        arrepaga.k00_dtpaga,                                                                                         ";
$sSqlPagamentos .= "        arrecant.k00_tipo,                                                                                           ";
$sSqlPagamentos .= "        coalesce(disbanco.dtpago,k00_dtpaga) as efetpagto,                                                           ";
$sSqlPagamentos .= "        'NORMAL'                             as tipopagamento,                                                       ";
$sSqlPagamentos .= "        0                                    as abatimento,                                                          ";
$sSqlPagamentos .= "        arrematric.k00_matric,                                                                                       ";
$sSqlPagamentos .= "        coalesce( arrematric.k00_perc,0) as percmatric,                                                              ";
$sSqlPagamentos .= "        arreinscr.k00_inscr,                                                                                         ";
$sSqlPagamentos .= "        coalesce( arreinscr.k00_perc,0)  as percinscr,                                                               ";
$sSqlPagamentos .= "        case                                                                                                         ";
$sSqlPagamentos .= "          when exists ( select *                                                                                     "; 
$sSqlPagamentos .= "                          from divida                                                                                ";        
$sSqlPagamentos .= "                        where divida.v01_numpre = arrepaga.k00_numpre ) then true                                    "; 
$sSqlPagamentos .= "          else false                                                                                                 ";
$sSqlPagamentos .= "        end as divida                                                                                                ";
$sSqlPagamentos .= "     from arrepaga                                                                                                   ";
$sSqlPagamentos .= "        {$sInnerPagamento}                                                                                           ";
$sSqlPagamentos .= "        inner join arrenumcgm    on arrenumcgm.k00_numpre = arrepaga.k00_numpre                                      ";
$sSqlPagamentos .= "        left  join arrematric    on arrematric.k00_numpre = arrepaga.k00_numpre                                      ";
$sSqlPagamentos .= "        left  join arreinscr     on arreinscr.k00_numpre  = arrepaga.k00_numpre                                      ";
$sSqlPagamentos .= "        left  join arrecant      on arrecant.k00_numpre   = arrepaga.k00_numpre                                      ";
$sSqlPagamentos .= "                                and arrecant.k00_numpar   = arrepaga.k00_numpar                                      ";
$sSqlPagamentos .= "                                and arrecant.k00_receit   = arrepaga.k00_receit                                      ";
$sSqlPagamentos .= "                                and arrecant.k00_hist    <> 918                                                      ";
$sSqlPagamentos .= "        inner join arreinstit    on arreinstit.k00_numpre = arrepaga.k00_numpre                                      "; 
$sSqlPagamentos .= "                                and arreinstit.k00_instit = ".db_getsession('DB_instit')                              ;
$sSqlPagamentos .= "        inner join tabrec        on tabrec.k02_codigo     = arrepaga.k00_receit                                      ";
$sSqlPagamentos .= "        inner join tabrecjm      on tabrecjm.k02_codjm    = tabrec.k02_codjm                                         ";
$sSqlPagamentos .= "        inner join histcalc      on histcalc.k01_codigo   = arrepaga.k00_hist                                        ";
$sSqlPagamentos .= "        left  join arreidret     on arreidret.k00_numpre  = arrepaga.k00_numpre                                      ";
$sSqlPagamentos .= "                                and arreidret.k00_numpar  = arrepaga.k00_numpar                                      ";
$sSqlPagamentos .= "        left  join disbanco      on disbanco.idret        = arreidret.idret                                          ";
$sSqlPagamentos .= "  where not exists ( select 1                                                                                        ";
$sSqlPagamentos .= "                       from abatimentorecibo                                                                         "; 
$sSqlPagamentos .= "                            inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento   ";  
$sSqlPagamentos .= "                      where abatimentorecibo.k127_numprerecibo = arrepaga.k00_numpre                                 ";
$sSqlPagamentos .= "                        and abatimento.k125_tipoabatimento     = 1                                                   ";
$sSqlPagamentos .= "                       limit 1 )                                                                                     ";
$sSqlPagamentos .= "        {$sWhereNumpreNormal}                                                                                        ";
$sSqlPagamentos .= "        {$sWherePagamento}                                                                                           ";                                          
$sSqlPagamentos .= " group by arrepaga.k00_numcgm,                                                                                       ";
$sSqlPagamentos .= "        arrepaga.k00_numpre,                                                                                         ";
$sSqlPagamentos .= "        arrepaga.k00_numpar,                                                                                         ";
$sSqlPagamentos .= "        arrepaga.k00_numtot,                                                                                         ";
$sSqlPagamentos .= "        arrepaga.k00_hist,                                                                                           ";
$sSqlPagamentos .= "        arrepaga.k00_receit,                                                                                         ";
$sSqlPagamentos .= "        k02_drecei,                                                                                                  ";
$sSqlPagamentos .= "        k01_descr,                                                                                                   ";
$sSqlPagamentos .= "        arrepaga.k00_conta,                                                                                          "; 
$sSqlPagamentos .= "        arrepaga.k00_dtpaga,                                                                                         "; 
$sSqlPagamentos .= "        arrecant.k00_tipo,                                                                                           ";
$sSqlPagamentos .= "        arrepaga.k00_dtoper,                                                                                         ";
$sSqlPagamentos .= "        arrecant.k00_dtoper,                                                                                         ";
$sSqlPagamentos .= "        disbanco.dtpago,                                                                                             ";
$sSqlPagamentos .= "        k00_dtpaga,                                                                                                  ";
$sSqlPagamentos .= "        arrepaga.k00_dtvenc,                                                                                         ";  
$sSqlPagamentos .= "        arrecant.k00_dtvenc,                                                                                         ";
$sSqlPagamentos .= "        arrematric.k00_matric,                                                                                       ";
$sSqlPagamentos .= "        arrematric.k00_perc,                                                                                         ";
$sSqlPagamentos .= "        arreinscr.k00_inscr,                                                                                         ";
$sSqlPagamentos .= "        arreinscr.k00_perc,                                                                                          ";
$sSqlPagamentos .= "        arrepaga.k00_valor                                                                                           ";
$sSqlPagamentos .= " union all                                                                                                           ";
$sSqlPagamentos .= " select distinct                                                                                                     ";
$sSqlPagamentos .= "        case when arrecad.k00_numcgm is not null then arrecad.k00_numcgm else arrecant.k00_numcgm end as k00_numcgm, ";
$sSqlPagamentos .= "        arreckey.k00_numpre,                                                                                         ";
$sSqlPagamentos .= "        arreckey.k00_numpar,                                                                                         ";
$sSqlPagamentos .= "        case when arrecad.k00_numtot is not null then arrecad.k00_numtot else arrecant.k00_numtot end as k00_numtot, ";
$sSqlPagamentos .= "        case when arrecad.k00_dtvenc is not null then arrecad.k00_dtvenc else arrecant.k00_dtvenc end as k00_dtvenc, ";
$sSqlPagamentos .= "        case when arrecad.k00_dtoper is not null then arrecad.k00_dtoper else arrecant.k00_dtoper end as k00_dtoper, ";
$sSqlPagamentos .= "        arreckey.k00_receit,                                                                                         ";
$sSqlPagamentos .= "        tabrec.k02_drecei,                                                                                           ";
$sSqlPagamentos .= "        arreckey.k00_hist,                                                                                           ";
$sSqlPagamentos .= "        histcalc.k01_descr,                                                                                          ";
$sSqlPagamentos .= "        ( abatimentoarreckey.k128_valorabatido +                                                                     ";
$sSqlPagamentos .= "          abatimentoarreckey.k128_correcao     +                                                                     ";
$sSqlPagamentos .= "          abatimentoarreckey.k128_juros        +                                                                     ";
$sSqlPagamentos .= "          abatimentoarreckey.k128_multa  ) as valorabatido,                                                          ";
$sSqlPagamentos .= "        arrepaga.k00_conta,                                                                                          ";
$sSqlPagamentos .= "        arrepaga.k00_dtpaga,                                                                                         ";
$sSqlPagamentos .= "        case when arrecad.k00_tipo is not null then arrecad.k00_tipo else arrecant.k00_tipo end as k00_tipo,         ";
$sSqlPagamentos .= "        coalesce(disbanco.dtpago,k00_dtpaga) as efetpagto,                                                           ";
$sSqlPagamentos .= "        'PARCIAL'                            as tipopagamento,                                                       ";
$sSqlPagamentos .= "        abatimento.k125_sequencial           as abatimento,                                                          ";
$sSqlPagamentos .= "        arrematric.k00_matric,                                                                                       ";
$sSqlPagamentos .= "        coalesce( arrematric.k00_perc,0) as percmatric,                                                              ";
$sSqlPagamentos .= "        arreinscr.k00_inscr,                                                                                         ";
$sSqlPagamentos .= "        coalesce( arreinscr.k00_perc,0)  as percinscr,                                                               ";
$sSqlPagamentos .= "        false as divida                                                                                              ";
$sSqlPagamentos .= "   from abatimentorecibo                                                                                             ";
$sSqlPagamentos .= "        inner join abatimento         on abatimento.k125_sequencial         = abatimentorecibo.k127_abatimento       ";
$sSqlPagamentos .= "        inner join abatimentodisbanco on abatimentodisbanco.k132_abatimento = abatimento.k125_sequencial             ";
$sSqlPagamentos .= "        inner join disbanco           on disbanco.idret                     = abatimentodisbanco.k132_idret          ";
$sSqlPagamentos .= "        inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial             ";
$sSqlPagamentos .= "        inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey       ";
$sSqlPagamentos .= "        inner join tabrec             on tabrec.k02_codigo                  = arreckey.k00_receit                    ";
$sSqlPagamentos .= "        inner join histcalc           on histcalc.k01_codigo                = arreckey.k00_hist                      ";
$sSqlPagamentos .= "        inner join arrenumcgm         on arrenumcgm.k00_numpre              = abatimentorecibo.k127_numprerecibo     ";
$sSqlPagamentos .= "        left  join arrematric         on arrematric.k00_numpre              = abatimentorecibo.k127_numprerecibo     ";
$sSqlPagamentos .= "        left  join arreinscr          on arreinscr.k00_numpre               = abatimentorecibo.k127_numprerecibo     ";
$sSqlPagamentos .= "        left  join arrepaga           on arrepaga.k00_numpre                = abatimentorecibo.k127_numprerecibo     ";
$sSqlPagamentos .= "        left  join arreidret          on arreidret.k00_numpre               = arreckey.k00_numpre                    ";
$sSqlPagamentos .= "                                     and arreidret.k00_numpar               = arreckey.k00_numpar                    ";
$sSqlPagamentos .= "        inner join arreinstit         on arreinstit.k00_numpre              = arrepaga.k00_numpre                    "; 
$sSqlPagamentos .= "                                     and arreinstit.k00_instit              = ".db_getsession('DB_instit')            ;                             
$sSqlPagamentos .= "        left  join arrecant           on arrecant.k00_numpre                = arreckey.k00_numpre                    ";
$sSqlPagamentos .= "                                     and arrecant.k00_numpar                = arreckey.k00_numpar                    ";
$sSqlPagamentos .= "                                     and arrecant.k00_receit                = arreckey.k00_receit                    ";
$sSqlPagamentos .= "        left  join arrecad            on arrecad.k00_numpre                 = arreckey.k00_numpre                    ";
$sSqlPagamentos .= "                                     and arrecad.k00_numpar                 = arreckey.k00_numpar                    ";
$sSqlPagamentos .= "                                     and arrecad.k00_receit                 = arreckey.k00_receit                    ";
$sSqlPagamentos .= "        {$sInnerPagamento}                                                                                           ";
$sSqlPagamentos .= "  where abatimento.k125_tipoabatimento = 1                                                                           ";
$sSqlPagamentos .= "        {$sWhereNumprePgto}                                                                                          ";
$sSqlPagamentos .= "        {$sWherePagamento}                                                                                           ";
$sSqlPagamentos .= " order by efetpagto,                                                                                                 ";
$sSqlPagamentos .= "        k00_numpre,                                                                                                  ";
$sSqlPagamentos .= "        k00_numpar                                                                                                   ";

$rsPagamentos    = db_query($sSqlPagamentos);
$aPagamentos     = db_utils::getColectionByRecord($rsPagamentos);   
    

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");
$pdf->SetFillColor(220);
$pdf->setxy(5,35);
$X = 5;
$Y = 38;

$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,21,'',"TB",0,'C');

$pdf->Text($X     ,$Y + 12,"Endereço :");
$pdf->Text($X     ,$Y +  8,$nome);
$pdf->Text($X + 16,$Y + 12,$ender);

$pdf->setxy(5,60);

$pdf->SetFont('Arial','B',7);

$pdf->cell(10,04,"Tipo"       ,1,0,"C",0);
$pdf->cell(11,04,"Numpre"     ,1,0,"C",0);
$pdf->cell( 8,04,"Parc"       ,1,0,"C",0);
$pdf->cell( 8,04,"Tot."       ,1,0,"C",0);
$pdf->cell(15,04,"Matric"     ,1,0,"C",0);
$pdf->cell(15,04,"Inscr"      ,1,0,"C",0);
$pdf->cell(15,04,"Exerc"      ,1,0,"C",0);
$pdf->cell(15,04,"Venc"       ,1,0,"C",0);
$pdf->cell(12,04,"DT.Lanc."   ,1,0,"C",0);
$pdf->cell( 8,04,"Hist."      ,1,0,"C",0);
$pdf->cell(50,04,"Descrição"  ,1,0,"C",0);
$pdf->cell( 8,04,"Rec."       ,1,0,"C",0);
$pdf->cell(50,04,"Descrição"  ,1,0,"C",0);
$pdf->cell(20,04,"Valor"      ,1,0,"C",0);
$pdf->cell(10,04,"Conta"      ,1,0,"C",0);
$pdf->cell(12,04,"DT.Pag."    ,1,0,"C",0);
$pdf->cell(15,04,"DT.EfetPag.",1,1,"C",0);

$pdf->SetFont('arial','',6);

$tottotal = 0;

$aParcelamentos = array();
$aTotal         = array();

foreach ($aPagamentos as $oPagamento) {
	
  if ($pdf->GetY() > ( $pdf->h - 30 )) {
    
     $linha = 0;
     
     $pdf->AddPage("L");
     $pdf->setxy(5,35);
     
     $X = 5;
     $Y = 38;
     
     $pdf->SetFont('Arial','B',8);
     $pdf->Cell(0,21,'',"TB",0,'C');
     
     $pdf->Text($X     ,$Y + 12,"Endereço : ");
     $pdf->Text($X     ,$Y +  8,$nome);
     $pdf->Text($X + 16,$Y + 12,$ender);
     
     $pdf->SetXY(5,60);
     $pdf->SetFont('Arial','B',7);
     
     $pdf->cell(10,04,"Tipo"       ,1,0,"C",0);
     $pdf->cell(11,04,"Numpre"     ,1,0,"C",0);
     $pdf->cell( 8,04,"Parc"       ,1,0,"C",0);
     $pdf->cell( 8,04,"Tot."       ,1,0,"C",0);
     $pdf->cell(15,04,"Matric"     ,1,0,"C",0);
     $pdf->cell(15,04,"Inscr"      ,1,0,"C",0);
     $pdf->cell(15,04,"Exerc"      ,1,0,"C",0);
     $pdf->cell(15,04,"Venc"       ,1,0,"C",0);
     $pdf->cell(12,04,"DT.Lanc."   ,1,0,"C",0);
     $pdf->cell( 8,04,"Hist."      ,1,0,"C",0);
     $pdf->cell(50,04,"Descrição"  ,1,0,"C",0);
     $pdf->cell( 8,04,"Rec."       ,1,0,"C",0);
     $pdf->cell(50,04,"Descrição"  ,1,0,"C",0);
     $pdf->cell(20,04,"Valor"      ,1,0,"C",0);
     $pdf->cell(10,04,"Conta"      ,1,0,"C",0);
     $pdf->cell(12,04,"DT.Pag."    ,1,0,"C",0);
     $pdf->cell(15,04,"DT.EfetPag.",1,1,"C",0);
          
     $pdf->SetFont('arial','',6);
  }
  
  if ( !in_array( $oPagamento->k00_numpre, $aParcelamentos ) ) {

    $sBuscaParcelamentos = "select v07_parcel from divida.termo where v07_numpre = " . $oPagamento->k00_numpre;
    $rsBuscaParcelamentos = db_query($sBuscaParcelamentos);
    if ( pg_numrows($rsBuscaParcelamentos) > 0 ) {
      $oBuscaPagamentos = db_utils::getColectionByRecord($rsBuscaParcelamentos);
      $aParcelamentos[] = $oPagamento->k00_numpre;
    }

  }

  $pdf->setx(5);
  $pdf->Cell(10,04,$oPagamento->tipopagamento,0,0,"C",0);
  $pdf->Cell(11,04,$oPagamento->k00_numpre ,0,0,"C",0);
  $pdf->Cell( 8,04,$oPagamento->k00_numpar ,0,0,"C",0);
  $pdf->Cell( 8,04,$oPagamento->k00_numtot ,0,0,"C",0);
  
  $pdf->Cell(15,04,@$oPagamento->k00_matric,0,0,"C",0);
  $pdf->Cell(15,04,@$oPagamento->k00_inscr ,0,0,"C",0);
  $pdf->Cell(15,04,(@$oPagamento->v01_exerc == ""?substr($oPagamento->k00_dtoper,0,4):$oPagamento->v01_exerc),0,0,"C",0);
  $pdf->Cell(15,04,db_formatar($oPagamento->k00_dtvenc,'d'),0,0,"C",0);
  $pdf->Cell(12,04,db_formatar($oPagamento->k00_dtoper,'d'),0,0,"C",0);
  $pdf->Cell( 8,04,$oPagamento->k00_hist   ,0,0,"C",0);
  $pdf->Cell(50,04,$oPagamento->k01_descr  ,0,0,"L",0);
  $pdf->Cell( 8,04,$oPagamento->k00_receit ,0,0,"C",0);
  $pdf->Cell(50,04,$oPagamento->k02_drecei ,0,0,"L",0);
  
  if ((float)$oPagamento->percmatric <> 0) {
    (float)$perc = (float)$oPagamento->percmatric; 
  } else if ((float)$oPagamento->percinscr <> 0) {
    (float)$perc = (float)$oPagamento->percinscr; 
  } else {
    (float)$perc = (float)100; 
  }

  $iCountMatric = @pg_result(db_query("select coalesce(count(*),0) from arrematric where k00_numpre = $oPagamento->k00_numpre"),0,0);
  $iCountInscr  = @pg_result(db_query("select coalesce(count(*),0) from arreinscr  where k00_numpre = $oPagamento->k00_numpre"),0,0);
  
  $oPagamento->k00_valor = $oPagamento->k00_valor *-1;
  
  if ( ($iCountMatric > 1 || $iCountInscr > 1) && !isset($numcgm)) {
  	if($oPagamento->k00_inscr != '') {
  		//Se houver mais de uma matricula na divida paga, irá dividir o percentual de k00_inscr 
  		$perc = $perc + ($oPagamento->percinscr / $iCountMatric);
  	}
    (float)$oPagamento->k00_valor =  (float)(( $oPagamento->k00_valor*$perc) / 100) ;
  }
  
  $pdf->Cell(20,04,db_formatar(  $oPagamento->k00_valor,'f'),0,0,"R",0);
  $pdf->Cell(10,04,$oPagamento->k00_conta                   ,0,0,"C",0);
  $pdf->Cell(12,04,db_formatar($oPagamento->k00_dtpaga,'d') ,0,0,"C",0);
  $pdf->Cell(15,04,db_formatar($oPagamento->efetpagto ,'d') ,0,1,"C",0);
  
  
  /**
   * Caso a consulta tenha sido pelo CGM, agrupamos por numpre_numpar_receit_hist (e inscr caso exista)
   * Senão, apenas soma os valores
   */
  if (isset($numcgm)) {
  	
    $iIndice = "{$oPagamento->k00_numpre}_{$oPagamento->k00_numpar}_{$oPagamento->k00_receit}_{$oPagamento->k00_hist}";
    
    if ( !empty( $oPagamento->k00_inscr ) ) {
    	$iIndice .= "_{$oPagamento->k00_inscr}";
    }
    
    if ( !array_key_exists( $iIndice, $aTotal ) ) {
      $aTotal[$iIndice] = $oPagamento->k00_valor;
    }
  } else {
  	$aTotal[0] += $oPagamento->k00_valor;
  }  
}

$tottotal = array_sum($aTotal);

$pdf->setx(5);
$pdf->SetFont('arial','B',6);
$pdf->cell(226,4,'TOTAL PAGO',1,0,"L",0);
$pdf->cell(20,04,db_formatar(($tottotal),'f'),1,0,"R",0);
$pdf->cell(35,4,'',1,1,"C",0);
$pdf->Ln(3);

if ( sizeof($aParcelamentos) > 0 ) {

  $pdf->SetFont('Arial','B',6);
  $pdf->cell(100, 04, "Origem dos parcelamentos",1,1,"L");
  $pdf->cell(20,04, "Numpre"             ,1,0,"L",0);
  $pdf->cell(20,04, "Parcelamento"       ,1,0,"L",0);
  $pdf->cell(60,04, "Anos"               ,1,0,"L",0);
  $pdf->ln();
  $pdf->SetFont('Arial','',6);

  for ( $iParcelamento = 0; $iParcelamento < sizeof($aParcelamentos); $iParcelamento++ ) {
    $iNumpreParcelamento = $aParcelamentos[$iParcelamento];

    $sAnos  = "";
    $sAnos .= " select termo.v07_parcel, array_to_string( array_accum(distinct v01_exerc),', ' ) as v01_exerc from divida.termo inner join divida.termodiv on termodiv.parcel = termo.v07_parcel inner join divida.divida on divida.v01_coddiv = termodiv.coddiv where termo.v07_numpre = $iNumpreParcelamento group by termo.v07_parcel ";
    $sAnos .= " union ";
    $sAnos .= " select termo.v07_parcel, array_to_string( array_accum(distinct v01_exerc),', ' ) as v01_exerc from divida.termo inner join divida.termoini on termoini.parcel = termo.v07_parcel inner join juridico.inicialcert on termoini.inicial = inicialcert.v51_inicial inner join divida.certdiv on certdiv.v14_certid = inicialcert.v51_certidao inner join divida.divida on divida.v01_coddiv = certdiv.v14_coddiv where termo.v07_numpre = $iNumpreParcelamento group by termo.v07_parcel ";
    $sAnos  = " select * from ( $sAnos ) as x where trim(v01_exerc) <> ''";
    $rsAnos = db_query($sAnos);
    if ( pg_numrows( $rsAnos ) > 0 ) {
      $oAnos = db_utils::fieldsMemory(db_query($sAnos),0);
      $pdf->cell(20,04, $iNumpreParcelamento ,0,0,"L",0);
      $pdf->cell(20,04, $oAnos->v07_parcel   ,0,0,"L",0);
      $pdf->cell(60,04, $oAnos->v01_exerc    ,0,0,"L",0);
      $pdf->ln();

    }

  }

}

/*
 * Se estiver setada a variável v70_sequencial significa que foi informado o processo do foro na consulta
 * Neste caso buscamos os dados das custas geradas para o processo do foro 
 */


if ( isset($v70_sequencial) ) {
  $oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");

  $nTotalTaxas = 0;
  
  $sCampos  = " v70_codforo,             ";
  $sCampos .= " ar37_sequencial,         "; 
  $sCampos .= " ar37_descricao,          ";
  $sCampos .= " v76_sequencial,          ";
  $sCampos .= " v76_tipolancamento,      ";
  $sCampos .= " v76_dtpagamento,         ";
  $sCampos .= " v76_obs,                 ";
  $sCampos .= " v77_sequencial,          ";
  $sCampos .= " v77_taxa,                ";
  $sCampos .= " v77_valor,               ";
  $sCampos .= " v77_numnov,              ";
  $sCampos .= " v77_processoforopartilha,";
  $sCampos .= " ar36_sequencial,         ";
  $sCampos .= " ar36_descricao,          ";
  $sCampos .= " ar36_receita             ";

  $sWhereProcessoForoPartilhCusta = "v76_processoforo = {$v70_sequencial} and v76_dtpagamento is not null";
  $sSqlProcessoForoPartilhCusta   = $oDaoProcessoForoPartilhaCusta->sql_query_recibo(null, " distinct {$sCampos}", "", $sWhereProcessoForoPartilhCusta);
  $rsProcessoForoPartilhaCusta    = $oDaoProcessoForoPartilhaCusta->sql_record($sSqlProcessoForoPartilhCusta);
if ($oDaoProcessoForoPartilhaCusta->numrows > 0) {
    //db_criatabela($rsProcessoForoPartilhaCusta);
    $oDadosProcessoPartilhaCusta = db_utils::getColectionByRecord($rsProcessoForoPartilhaCusta);
    switch ($oDadosProcessoPartilhaCusta[0]->v76_tipolancamento) {
      case 1: $sTipoLancamento = "Automático";
      break;
      case 2: $sTipoLancamento = "Manual";
      break;
      case 3: $sTipoLancamento = "Isento";
      break;      
    }
    
    $pdf->setx(5);
    $pdf->SetFont('Arial','B',6);
    $pdf->cell(73, 4,  "Processo Nº: {$oDadosProcessoPartilhaCusta[0]->v70_codforo}",1,1,"L");
    
    $pdf->setx(5);
    $pdf->cell(73, 4,  "Partilha: {$oDadosProcessoPartilhaCusta[0]->ar37_descricao}", 1,1,"L"); 
    
    $pdf->setx(5);
    $pdf->SetFont('Arial','B',6);
    $pdf->cell(23, 4,  "Lancamento",1,0,"L");
    $pdf->SetFont('Arial','',6);
    $pdf->cell(50, 4,  $sTipoLancamento,1,1,"L");
    
    $pdf->setx(5);
    $pdf->SetFont('Arial','B',6);      
    $pdf->cell(23, 4,  "Data de Pagamento",1,0,"L");
    $pdf->SetFont('Arial','',6);
    $pdf->cell(50, 4,  db_formatar($oDadosProcessoPartilhaCusta[0]->v76_dtpagamento,"d"),1,1,"L");  

    foreach($oDadosProcessoPartilhaCusta as $oTaxas) {
      
      $pdf->setx(5);
      $pdf->cell(50, 4,  $oTaxas->ar36_descricao,"L",0,"L");
      $pdf->cell(5, 4,  "R$",0,0,"L");
      $pdf->cell(18, 4,  db_formatar($oTaxas->v77_valor,"f"),"R",1,"R");
      $nTotalTaxas += $oTaxas->v77_valor;
    }

    $pdf->setx(5);
    $pdf->SetFont('Arial','B',6);
    $pdf->cell(50, 4,  "TOTAL DAS CUSTAS:","LT",0,"L");
    $pdf->SetFont('Arial','',6);
    $pdf->cell(5, 4,  "R$","TB",0,"L");
    $pdf->cell(18, 4,  db_formatar($nTotalTaxas,"f"),"TBR",1,"R");
    
    $pdf->setx(5);
    $pdf->SetFont('Arial','B',6);
    $pdf->cell(73, 4,  "Observação : ",1,0,"L");
    
    $pdf->setY($pdf->GetY()+4);
    $pdf->setx(5);    
    $pdf->SetFont('Arial','',6);
    $pdf->MultiCell(73, 4, $oDadosProcessoPartilhaCusta[0]->v76_obs, 1, "left", false); 

  } else {

// Exibe o processo do foro para aqueles que nao tem custas.

   $sSqlProcessoForo = " select * from processoforo where v70_sequencial = {$v70_sequencial} ";
   $oProcessoForo = db_utils::fieldsMemory(db_query($sSqlProcessoForo),0);

     if (isset($oProcessoForo->v70_codforo)) {

              $pdf->setx(5);
              $pdf->SetFont('Arial','B',6);
              $pdf->cell(73, 4,  "Processo Nº: {$oProcessoForo->v70_codforo}",1,1,"L");
          } else {

          }


  }
  
  
}

$pdf->Output();
?>