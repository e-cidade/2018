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

  require_once("fpdf151/pdf.php");
  require_once("libs/db_sql.php");
  require_once("libs/db_utils.php");
  require_once("classes/db_folha_classe.php");
  
  $clrotulo = new rotulocampo;
  $clfolha  = new cl_folha;
  
  $clrotulo->label('r06_codigo');
  $clrotulo->label('r06_descr');
  $clrotulo->label('r06_elemen');
  $clrotulo->label('r06_pd');
  
  $iNumRows = 0;
  $sOrdem   = "";
  $sCampo   = "";
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  
  $head2 = "RELATÓRIO DE FUNCIONÁRIOS SEM CONTA";
  $head3 = "PERÍODO : " . $mes . " / " . $ano;
  
  if ($tipo == 'g') {
    
    $sOrdem = "";
    $sCampo = ", '' as quebra_rel";
    
  } elseif ($tipo == 'o') {
    
    $sOrdem = 'order by r70_estrut';
    $sCampo = ', r70_descr as quebra_rel';
    
  } elseif ($tipo == 'u') {
  
    $sOrdem = 'order by o41_descr';
    $sCampo = ', o41_descr as quebra_rel';
    
  } elseif ($tipo == 't') {
    
    $sOrdem = 'order by rh55_descr';
    $sCampo = ', rh55_descr as quebra_rel';
    
  } elseif ($tipo == 'r') {
    
    $sOrdem = "order by  quebra_rel";
    $sCampo = ", case when rh25_recurso is null then 'sem recurso' else o15_descr end as quebra_rel";
    
  }

  $sSql  = "select DISTINCT                                                                                       ";
  $sSql .= "       r38_regist, z01_nome    , rh25_projativ   ,                                                    ";
  $sSql .= "       rh26_orgao, rh26_unidade, r38_liq as valor,                                                    ";
  $sSql .= "       r70_estrut  {$sCampo}                                                                          ";
  $sSql .= "  from folha                                                                                          ";
  $sSql .= "       inner join cgm             on cgm.z01_numcgm                = folha.r38_numcgm                 ";
  $sSql .= "       inner join rhpessoalmov    on rhpessoalmov.rh02_regist      = folha.r38_regist                 ";
  $sSql .= "                                 and rhpessoalmov.rh02_anousu      = {$ano}                           ";
  $sSql .= "                                 and rhpessoalmov.rh02_instit      = " . db_getsession("DB_instit")    ;
  $sSql .= "                                 and rhpessoalmov.rh02_mesusu      = {$mes}                           ";
  $sSql .= "       inner join rhlota          on rhlota.r70_codigo             = rhpessoalmov.rh02_lota           ";
  $sSql .= "                                 and rhlota.r70_instit             = rhpessoalmov.rh02_instit         ";
  $sSql .= "       left  join rhlotaexe       on rhlotaexe.rh26_codigo         = rhlota.r70_codigo                ";
  $sSql .= "                                 and rhlotaexe.rh26_anousu         = {$ano}                           ";
  $sSql .= "       left  join rhlotavinc      on rhlotavinc.rh25_codigo        = rhlota.r70_codigo                ";
  $sSql .= "                                 and rhlotavinc.rh25_anousu        = {$ano}                           ";
  $sSql .= "       left  join orcunidade      on orcunidade.o41_anousu         = {$ano}                           ";
  $sSql .= "                                 and orcunidade.o41_orgao          = rhlotaexe.rh26_orgao             ";
  $sSql .= "                                 and orcunidade.o41_unidade        = rhlotaexe.rh26_unidade           ";
  $sSql .= "       left  join orctiporec      on orctiporec.o15_codigo         = rhlotavinc.rh25_recurso          ";
  $sSql .= "       left  join rhpeslocaltrab  on rhpeslocaltrab.rh56_seqpes    = rhpessoalmov.rh02_seqpes         ";
  $sSql .= "                                 and rhpeslocaltrab.rh56_princ     = 't'                              ";
  $sSql .= "       left  join rhlocaltrab     on rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo          ";
  $sSql .= "                                 and rhlocaltrab.rh55_instit       = " . db_getsession("DB_instit")    ;
  $sSql .= "  where trim(folha.r38_banco) = ''                                                                    ";
  $sSql .= "  {$sOrdem}                                                                                           ";
  //die($sSql);
  $rsResult = $clfolha->sql_record($sSql);
  $iNumRows = $clfolha->numrows;

  if ($iNumRows == 0) {
    
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários sem conta no período de ' . $mes . ' / ' . $ano);
  }

  $sNomeArquivo = 'tmp/relatorio_servidores_sem_conta_' . date('Y-m-d_H:i') . '_' . db_getsession('DB_login');
  
  if ($formato == 'pdf') {
  
    $oPdf = new PDF(); 
    $oPdf->Open(); 
    $oPdf->AliasNbPages();
    
    $func   = 0;
    $func_c = 0;
    $tot_c  = 0;
    $total  = 0;
    $troca  = 1;
    $alt    = 4;
    $rec    = '';
    
    $oPdf->setfillcolor(235);
    $oPdf->setfont('arial','b',8);
    
    for ($i = 0; $i < $iNumRows; $i++) {
      
      $oDadosRelatorio = db_utils::fieldsmemory($rsResult, $i);
      
      if ($rec != $oDadosRelatorio->quebra_rel && $trocap == 's') {
        
        $troca = 1;
        
        $oPdf->ln(1);
        
        $oPdf->cell(30, $alt, 'Total da Quebra  :  '  , "T", 0, "L", 0);
        $oPdf->cell(45, $alt, $func_c                 , "T", 0, "L", 0);
        $oPdf->cell(40, $alt, ''                      , "T", 0, "L", 0);
        $oPdf->cell(20, $alt, db_formatar($tot_c, 'f'), "T", 1, "R", 0);
        
        $func_c = 0;
        $tot_c  = 0;
      }
     
      if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0) {
        
        $oPdf->addpage();
        $oPdf->setfont('arial', 'b', 8);
        
        $oPdf->cell(15, $alt, 'MATRÍC.'   , 1, 0, "C", 1);
        $oPdf->cell(60, $alt, 'NOME'      , 1 ,0, "C", 1);
        $oPdf->cell(20, $alt, 'UNID.ORÇ.' , 1, 0, "C", 1);
        $oPdf->cell(20, $alt, 'PROJ.ATIV.', 1, 0, "C", 1);
        $oPdf->cell(20, $alt, 'VALOR'     , 1, 1, "C", 1);
        $troca = 0;
      }
     
      if ($rec != $oDadosRelatorio->quebra_rel) {
        
        if ($trocap != 's' && $tot_c != 0) {
          
          $oPdf->ln(1);
          $oPdf->cell(30, $alt, 'Total da Quebra  :  '  , "T", 0, "L", 0);
          $oPdf->cell(45, $alt, $func_c                 , "T", 0, "L", 0);
          $oPdf->cell(40, $alt, ''                      , "T", 0, "L", 0);
          $oPdf->cell(20, $alt, db_formatar($tot_c, 'f'), "T", 1, "R", 0);
          $func_c = 0;
          $tot_c  = 0;
        }
        
        $oPdf->setfont('arial', 'b', 9);
        
        $oPdf->ln(4);
        $oPdf->cell(50, $alt, $oDadosRelatorio->quebra_rel, 0, 1, "L", 1);
        
        $oPdf->ln(2);
        $rec = $oDadosRelatorio->quebra_rel;
      }
      
      $oPdf->setfont('arial', '', 7);
      
      $oPdf->cell(15, $alt, $oDadosRelatorio->r38_regist                        , 0, 0, "C", 0);
      $oPdf->cell(60, $alt, $oDadosRelatorio->z01_nome                          , 0, 0, "L", 0);
      $oPdf->cell(20, $alt, db_formatar($oDadosRelatorio->rh26_orgao, 'orgao') 
      		                . db_formatar($oDadosRelatorio->rh26_unidade, 'orgao'), 0, 0, "C", 0);
      $oPdf->cell(20, $alt, $oDadosRelatorio->rh25_projativ                     , 0, 0, "C", 0);
      $oPdf->cell(20, $alt, db_formatar($oDadosRelatorio->valor, 'f')           , 0, 1, "R", 0);
      
      $func   += 1;
      $func_c += 1;
      $tot_c  += $oDadosRelatorio->valor;
      $total  += $oDadosRelatorio->valor;
      
    }
    
    if ($tipo != 'g') { 
    
      $oPdf->ln(1);
      $oPdf->cell(30, $alt, 'Total da Quebra  :  '  , "T", 0, "L", 0);
      $oPdf->cell(45, $alt, $func_c                 , "T", 0, "L", 0);
      $oPdf->cell(40, $alt, ''                      , "T", 0, "L", 0);
      $oPdf->cell(20, $alt, db_formatar($tot_c, 'f'), "T", 1, "R", 0);
    }
    
    $oPdf->ln(3);
    $oPdf->cell(30, $alt, 'Total da Geral  :  '   , "T", 0, "L", 0);
    $oPdf->cell(45, $alt, $func                   , "T", 0, "L", 0);
    $oPdf->cell(40, $alt, ''                      , "T", 0, "L", 0);
    $oPdf->cell(20, $alt, db_formatar($total, 'f'), "T", 1, "R", 0);
    
    $sArquivo = $sNomeArquivo.'.pdf';
    
    $oPdf->Output();
    
    
  } else {
    
    $aDadosRelatorio = db_utils::getCollectionByRecord($rsResult, true );
    
    $aLinhas    = array();
    $oCabecalho = new stdClass();
    
    $sArquivo   = $sNomeArquivo.'.csv';
    $fArquivo = fopen($sArquivo, "w");
    
    $oCabecalho->matricula = "Matricula";
    $oCabecalho->nome      = "Nome";
    $oCabecalho->unidorc   = "Unid.orç.";
    $oCabecalho->projativ  = "Proj.Ativ.";
    $oCabecalho->valor     = "Valor";
    $oCabecalho->descrTipo = "Descr. Tipo";
    
    $aLinhas[] = $oCabecalho;
    
    foreach ($aDadosRelatorio as $oRegistro) {
      
      $oConteudo = new stdClass();
      
      $oConteudo->r38_regist        = $oRegistro->r38_regist;
      $oConteudo->z01_nome          = $oRegistro->z01_nome;
      $oConteudo->rh26_orgaoUnidade = db_formatar($oRegistro->rh26_orgao, 'orgao') . db_formatar($oRegistro->rh26_unidade, 'orgao');
      $oConteudo->rh25_projativ     = $oRegistro->rh25_projativ;
      $oConteudo->valor             = db_formatar($oRegistro->valor, 'f');
      $oConteudo->quebra_rel        = $oRegistro->quebra_rel;
      
      $aLinhas[] = $oConteudo;

    }
    
    foreach ($aLinhas as $oLinha) {
      
      fputcsv($fArquivo, (array)$oLinha, ";");
    }
    
    fclose($fArquivo);
    
    echo "
    
    <script>
    window.opener.js_detectaarquivo('{$sArquivo}');
    </script>";
  }

?>