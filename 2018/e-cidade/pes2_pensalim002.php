<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once 'fpdf151/pdf.php';
require_once 'libs/db_sql.php';
require_once 'classes/db_selecao_classe.php';

$oPost = db_utils::postMemory($_GET);

$iInstituicao = db_getsession('DB_instit');
$iMesFolha    = DBPessoal::getMesFolha();
$iAnoFolha    = DBPessoal::getAnoFolha();
$sWhere       = '';

/**
 * Define cabeçalho
 */
$head2 = "Resumo de Pensões Alimentícias";
$head4 = "Período: {$oPost->mes}/{$oPost->ano}";

switch ($oPost->tipo) {
  case 's':
    $head6      = 'Salário';
    $sValor     = 'r52_valor + r52_valfer';
    $iTipoFolha = FolhaPagamento::TIPO_FOLHA_SALARIO;
    break;

  case 'c':
    $head6      = 'Complementar';
    $sValor     = 'r52_valcom';
    $iTipoFolha = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;
    break;

  case '3':
    $head6  = '13º. Salário';
    $sValor = 'r52_val13'; 
    break;

  case 'r':
    $head6  = 'Rescisão';
    $sValor = 'r52_valres'; 
    break;

  case 'u':
    $head6      = 'Suplementar';
    $sValor     = 'r52_valor + r52_valfer';
    $iTipoFolha = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
    break;
}

if (!empty($oPost->selecao)) {

  $oDaoSelecao = new cl_selecao;

  $sSql     = $oDaoSelecao->sql_query_file($oPost->selecao, $iInstituicao);
  $rsResult = $oDaoSelecao->sql_record($sSql);

  if ($oDaoSelecao->numrows) {

    $oSelecao = db_utils::fieldsMemory($rsResult, 0);
    $sWhere   = "AND {$oSelecao->r44_where}";

    $head8    = "Seleção {$oPost->selecao} - {$oSelecao->r44_descr}";
  }
}

if ($oPost->ordem == 'n') {
  $sOrder = 'rh01_regist';
} else {
  if ($oPost->func == 's') {
    $sOrder = 'z01_nome, codigo_banco, codigo_agencia';
  } else {
    $sOrder = 'codigo_banco, codigo_agencia, nome_beneficiario';
  }
}

$sGroup = 'descricao_banco, codigo_banco, codigo_agencia, r52_dvagencia, conta, r52_dvconta, cgm_beneficiario, nome_beneficiario, rh01_regist, x.z01_nome, x.w01_work05';

if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && isset($iTipoFolha)) {

  $sSql = "
    SELECT *
      FROM (
        SELECT CASE WHEN trim(r52_codbco) = '' OR r52_codbco IS NULL THEN '000'
                    ELSE r52_codbco
               END                     AS codigo_banco,
               CASE WHEN db90_descr IS NOT NULL THEN db90_descr
                    ELSE 'SEM BANCO'
               END                     AS descricao_banco,
               to_char(to_number(CASE WHEN trim(r52_codage) = '' THEN '0'
                    ELSE r52_codage
               END, '99999'), '99999') AS codigo_agencia,
               CASE WHEN r52_dvagencia IS NULL THEN ''
                    ELSE r52_dvagencia
               END                     AS r52_dvagencia,
               r52_conta               AS conta,
               CASE WHEN r52_dvconta IS NULL THEN ''
                    ELSE r52_dvconta
               END                     AS r52_dvconta,
               r52_numcgm              AS cgm_beneficiario,
               cgm.z01_nome            AS nome_beneficiario,
                 a.z01_nome,
               rh01_regist,
               (
                 SELECT sum(rh145_valor)
                   FROM rhhistoricopensao
                        INNER JOIN rhfolhapagamento  ON rh145_rhfolhapagamento = rh141_sequencial

                  WHERE rh141_anousu    = r52_anousu
                    AND rh141_mesusu    = r52_mesusu
                    AND rh141_instit    = rh02_instit
                    AND rh141_tipofolha = {$iTipoFolha}
                    AND rh145_pensao    = r52_sequencial
               )                       AS w01_work05
          FROM pensao
               INNER JOIN cgm          ON r52_numcgm              = z01_numcgm
               INNER JOIN rhpessoal    ON rh01_regist             = r52_regist
               INNER JOIN rhpessoalmov ON rh01_regist             = rh02_regist
                                      AND rh02_anousu             = {$iAnoFolha}
                                      AND rh02_mesusu             = {$iMesFolha}
                                      AND rh02_instit             = {$iInstituicao}
               INNER JOIN rhlota       ON r70_codigo              = rh02_lota
                                      AND r70_instit              = rh02_instit
               INNER JOIN cgm AS a     ON a.z01_numcgm            = rh01_numcgm
               LEFT  JOIN db_bancos    ON r52_codbco::varchar(10) = db90_codban
         WHERE r52_anousu = {$oPost->ano}
           AND r52_mesusu = {$oPost->mes}
               {$sWhere}
      ) AS x
     WHERE w01_work05 > 0
     GROUP BY {$sGroup}
     ORDER BY {$sOrder}
  ";
} else {

  $sSql = "
    SELECT *
      FROM (
        SELECT CASE WHEN trim(r52_codbco) = '' OR r52_codbco IS NULL THEN '000'
                    ELSE r52_codbco
               END                     AS codigo_banco,
               CASE WHEN db90_descr IS NOT NULL THEN db90_descr
                    ELSE 'SEM BANCO'
               END                     AS descricao_banco,
               to_char(to_number(CASE WHEN trim(r52_codage) = '' THEN '0'
                    ELSE r52_codage
               END, '99999'), '99999') AS codigo_agencia,
               CASE WHEN r52_dvagencia IS NULL THEN ''
                    ELSE r52_dvagencia
               END                     AS r52_dvagencia,
               r52_conta               AS conta,
               CASE WHEN r52_dvconta IS NULL THEN ''
                    ELSE r52_dvconta
               END                     AS r52_dvconta,
               r52_numcgm              AS cgm_beneficiario,
               cgm.z01_nome            AS nome_beneficiario,
                 a.z01_nome,
               rh01_regist,
               {$sValor}               AS w01_work05
          FROM pensao
            INNER JOIN cgm          ON   r52_numcgm              =  z01_numcgm
            INNER JOIN rhpessoal    ON  rh01_regist              =  r52_regist
            INNER JOIN rhpessoalmov ON  rh01_regist              = rh02_regist
                                   AND  rh02_anousu              = {$iAnoFolha}
                                   AND  rh02_mesusu              = {$iMesFolha}
                                   AND  rh02_instit              = {$iInstituicao}
            INNER JOIN rhlota       ON   r70_codigo              = rh02_lota
                                   AND   r70_instit              = rh02_instit
            INNER JOIN cgm AS a     ON a.z01_numcgm              = rh01_numcgm
            LEFT  JOIN db_bancos    ON   r52_codbco::varchar(10) = db90_codban
         WHERE r52_anousu = {$oPost->ano}
           AND r52_mesusu = {$oPost->mes}
           AND {$sValor}  > 0
               {$sWhere}
      ) AS x
     GROUP BY {$sGroup}
     ORDER BY {$sOrder}
  ";
}

$rsResult = db_query($sSql);
if (!pg_num_rows($rsResult)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nao existem lancamentos no periodo de {$oPost->mes}/{$oPost->ano}");
}

$oPDF = new PDF(); 
$oPDF->Open(); 
$oPDF->AliasNbPages(); 
$oPDF->setfillcolor(235);
$oPDF->setfont('arial', 'b', 8);

$alt     = 5;
$total   = 0;
$total_g = 0;

if($oPost->func != 's'){
  
  if($oPost->tipoquebra == 'a'){
    $quebra = substr($codigo_banco,0,3).$codigo_agencia;
  }else{  
    $quebra = substr($codigo_banco,0,3);
  }
  $troca = 0;

  for($x = 0; $x < pg_numrows($rsResult);$x++){
     
     db_fieldsmemory($rsResult,$x);

     if ($quebra != substr($codigo_banco,0,3).$codigo_agencia && $oPost->tipoquebra == 'a') {

        $oPDF->setfont('arial','b',8);
        $oPDF->cell(122,$alt,'Total da Agência',"T",0,"C",0);
        $oPDF->cell(40,$alt,'',"T",0,"C",0);
        $oPDF->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
        $oPDF->sety(300);
        $total = 0;
        $quebra = substr($codigo_banco,0,3).$codigo_agencia;
     }

     if ($quebra != substr($codigo_banco,0,3) && $tipoquebra != 'a') {

        $oPDF->setfont('arial','b',8);
        $oPDF->cell(122,$alt,'Total do Banco',"T",0,"C",0);
        $oPDF->cell(40,$alt,'',"T",0,"C",0);
        $oPDF->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
        $oPDF->sety(300);
        $total = 0;
        $quebra = substr($codigo_banco,0,3);
     }

     if ($oPDF->gety() > $oPDF->h - 30 || $troca == 0) {

        $oPDF->addpage();
        $oPDF->setfont('arial','b',8);
        if ($tipoquebra == 'a') {
          $oPDF->cell(80,$alt,$descricao_banco.' - Agência: '.$codigo_agencia,0,1,"L",0);
        } else {
          $oPDF->cell(80,$alt,$descricao_banco,0,1,"L",0);
        }
        $oPDF->ln(3);
        $oPDF->cell(122,$alt,'Nome do Beneficiário',1,0,"C",1);
        $oPDF->cell(20,$alt,'Agência',1,0,"C",1);
        $oPDF->cell(20,$alt,'Conta',1,0,"C",1);
        $oPDF->cell(30,$alt,'Valor',1,1,"C",1);
        $troca = 1;
     }

     $oPDF->setfont('arial','',7);
     $oPDF->cell(122, $alt, $nome_beneficiario,0,0,"l",0);
     $oPDF->cell(20, $alt, $codigo_agencia.$r52_dvagencia, 0, 0, "R", 0);
     $oPDF->cell(20, $alt, $conta.$r52_dvconta, 0, 0, "R", 0);
     $oPDF->cell(30, $alt, db_formatar($w01_work05,'f'), 0, 1, "R", 0);
     $total   += $w01_work05;
     $total_g += $w01_work05;
  }

  $oPDF->setfont('arial','b',8);
  if ($oPost->tipoquebra == 'a') {
    $oPDF->cell(122,$alt,'Total da Agência',"T",0,"C",0);
  } else {
    $oPDF->cell(122,$alt,'Total do Banco',"T",0,"C",0);
  }
  $oPDF->cell(40,$alt,'',"T",0,"C",0);
  $oPDF->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

  $oPDF->ln(5);
  $oPDF->cell(122,$alt,'Total do Geral',"T",0,"C",0);
  $oPDF->cell(40,$alt,'',"T",0,"C",0);
  $oPDF->cell(30,$alt,db_formatar($total_g,'f'),"T",1,"R",0);
}else{

  $troca = 0;

  for ($x = 0; $x < pg_numrows($rsResult);$x++) {
     db_fieldsmemory($rsResult,$x);
     if ($oPDF->gety() > $oPDF->h - 30 || $troca == 0) {

        $oPDF->addpage('L');
        $oPDF->setfont('arial','b',8);
        $oPDF->ln(3);
        $oPDF->cell(15,$alt,'Matr',1,0,"C",1);
        $oPDF->cell(80,$alt,'Nome do Funcionário',1,0,"C",1);
        $oPDF->cell(15,$alt,'CGM',1,0,"C",1);
        $oPDF->cell(80,$alt,'Nome do Beneficiário',1,0,"C",1);
        $oPDF->cell(10,$alt,'Banco',1,0,"C",1);
        $oPDF->cell(20,$alt,'Agência',1,0,"C",1);
        $oPDF->cell(20,$alt,'Conta',1,0,"C",1);
        $oPDF->cell(30,$alt,'Valor',1,1,"C",1);
        $troca = 1;
     }

     $oPDF->setfont('arial','',7);
     $oPDF->cell(15,$alt,$rh01_regist,0,0,"l",0);
     $oPDF->cell(80,$alt,$z01_nome,0,0,"l",0);
     $oPDF->cell(15,$alt,$cgm_beneficiario,0,0,"l",0);
     $oPDF->cell(80,$alt,$nome_beneficiario,0,0,"l",0);
     $oPDF->cell(10,$alt,$codigo_banco,0,0,"l",0);
     $oPDF->cell(20,$alt,$codigo_agencia.$r52_dvagencia,0,0,"R",0);
     $oPDF->cell(20,$alt,$conta.$r52_dvconta,0,0,"R",0);
     $oPDF->cell(30,$alt,db_formatar($w01_work05,'f'),0,1,"R",0);
     $total += $w01_work05;
     $total_g += $w01_work05;
  }

  $oPDF->ln(5);
  $oPDF->cell(200,$alt,'TOTAL GERAL',"T",0,"C",0);
  $oPDF->cell(40,$alt,'',"T",0,"C",0);
  $oPDF->cell(30,$alt,db_formatar($total_g,'f'),"T",1,"R",0);
}

$sName = 'tmp/pensaoAlimenticia' . date('YmdHms') . '.pdf';
$oPDF->Output($sName, false);
