<?
/*
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clgerfsal = new cl_gerfsal;
$clgerffx  = new cl_gerffx();
$clgerffer = new cl_gerffer;
$clgerfres = new cl_gerfres;
$clgerfcom = new cl_gerfcom;
$clgerfs13 = new cl_gerfs13;

$clrhipe = new cl_rhipe;
$clrhiperegist = new cl_rhiperegist;
$clrhipenumcgm = new cl_rhipenumcgm;
$clbasesr = new cl_basesr;
$clipe = new cl_ipe;
$clcfpess = new cl_cfpess;
$db_opcao = 1;
$db_botao = true;
if(isset($processar)){
  db_inicio_transacao();

  $anousu = $r36_anousu;
  if(trim($anousu) == ""){
    $anousu = db_anofolha();
  }

  $mesusu = $r36_mesusu;
  if(trim($mesusu) == ""){
    $mesusu = db_mesfolha();
  }

  $rsCfPess = $clcfpess->sql_record($clcfpess->sql_query_file( $anousu,
    $mesusu,
    db_getsession('DB_instit'),
    "r11_recpatrafasta"));
  if ( $clcfpess->numrows ==  0 ) {

    db_redireciona("db_erros.php?fechar=true&db_erro=Parâmetros do IPERGS não configurados para a competência : {$mesusu}/{$anousu}");
  } else {

    db_fieldsmemory($rsCfPess,0);  

    if ( $r11_recpatrafasta == 't') {
      $lRecPatrAfasta = true;
    } else {
      $lRecPatrAfasta = false;
    }
  }



  db_sel_instit(db_getsession("DB_instit"),"lower(trim(munic)) as d08_carnes");
  db_sel_cfpess($anousu,$mesusu,"r11_valor, r11_dtipe, r11_baseipe, r11_altfer");

  $minimo = $r11_valor;
  $tabela = $r11_dtipe;

  $sqlerro = false;

  $sql_in = $clbasesr->sql_query_file($anousu,$mesusu,$r11_baseipe,null,db_getsession('DB_instit'));
  //echo "<br>  base --> $r11_baseipe   sql -->$sql_in";exit;
  $res_in = $clbasesr->sql_record($sql_in);
  $rub  = '';
  $virg = '';
  for($x_in=0;$x_in<pg_numrows($res_in);$x_in++){
    db_fieldsmemory($res_in,$x_in);
    $rub .= $virg."'".$r09_rubric."'";
    $virg = ',';
  }

  $campos   = " rh14_sequencia,";
  $campos  .= " rh14_dtvinc ,  ";
  $campos  .= " rh14_estado ,  ";
  $campos  .= " rh14_dtalt  ,  ";
  $campos  .= " rh14_matipe ,  ";
  $campos  .= " rh14_valor  ,  ";
  $campos  .= " rh02_tbprev ,  ";
  $campos  .= " rh30_vinculo,  ";
  $campos  .= " rh01_regist ,  ";
  $campos  .= " z01_numcgm     ";

  $dbwhere  = " rh14_instit = ".db_getsession("DB_instit") ;
  $dbwhere .= " and rh14_dtvinc is not null ";
  $dbwhere .= " and rh14_dtalt <= '".$anousu."-".db_formatar($mesusu,'s','0',2,'e')."-".db_dias_mes($anousu,$mesusu)."'";
  $dbwhere .= " and (rh05_recis is null ";
  $dbwhere .= "     or ( extract(year from rh05_recis)= '$anousu'";
  $dbwhere .= "          and extract(month from rh05_recis)= '$mesusu'))";

  $result_clrhipe     = $clrhipe->sql_record($clrhipe->sql_query_ipe(null, $campos, "", $dbwhere, $anousu, $mesusu));
  $contador_inclusoes = 0;
  if($clrhipe->numrows > 0){

    $clipe->excluir($anousu,$mesusu,null,db_getsession('DB_instit'));

    if($clipe->erro_status == 0){
      $erro_msg = $clipe->erro_msg;
      $sqlerro=true;
    }

    if($sqlerro == false){

      $subpes = $anousu."/".$mesusu;
      $teste=0  ;


      for($I=0;$I < $clrhipe->numrows;$I++){

        db_fieldsmemory($result_clrhipe, $I);


        $prov       = 0;
        $nVlrProvFx = 0;

        if(trim($rh01_regist) != "" && ($rh14_valor == 0 || trim($rh14_valor) == "")){

          /////////////
          // Se for funcionário e o valor do rhipe for igual a zero
          /////////////

          $dbwhere  = " r20_anousu = $anousu and r20_mesusu = $mesusu";
          $dbwhere .= " and r20_instit = ".db_getsession("DB_instit")." ";
          $campos1 = "r20_valor as gprov";

          if ( $xtipo ==  1 ) {

            $dbwhere  = " r14_anousu = $anousu and r14_mesusu = $mesusu";
            $dbwhere .= " and r14_regist = ".$rh01_regist;
            $dbwhere .= " and r14_rubric in ($rub) ";
            $dbwhere .= " and r14_instit = ".db_getsession("DB_instit")." ";
            $campos1 = "(select sum(case when r14_pd in (1,3) then r14_valor else r14_valor *-1 end)) as r14_valor";

            $result_selecao = $clgerfsal->sql_record($clgerfsal->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
            if($clgerfsal->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $prov += $r14_valor;
            }

            $dbwhere  = " r48_anousu = $anousu and r48_mesusu = $mesusu";
            $dbwhere .= " and r48_regist = ".$rh01_regist;
            $dbwhere .= " and r48_rubric in ($rub) ";
            $dbwhere .= " and r48_instit = ".db_getsession("DB_instit")." ";
            $campos1  = "( select sum(case when r48_pd in (1,3) then r48_valor else r48_valor *-1 end ) ) as r48_valor";

            $result_selecao = $clgerfcom->sql_record($clgerfcom->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
            if($clgerfcom->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $prov += $r48_valor;
            }

            $dbwhere  = " r20_anousu = $anousu and r20_mesusu = $mesusu";
            $dbwhere .= " and r20_regist = ".$rh01_regist;
            $dbwhere .= " and r20_rubric in ($rub) ";
            $dbwhere .= " and r20_instit = ".db_getsession("DB_instit")." ";
            $campos1 = "(select sum(case when r20_pd in (1,3) then r20_valor else r20_valor *-1 end)) as r20_valor";

            $result_selecao = $clgerfres->sql_record($clgerfres->sql_query_file(null,null,null,null,null,$campos1,"",$dbwhere));

            if($clgerfres->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $prov += $r20_valor;
            }

            $dbwhere  = " r53_anousu = $anousu and r53_mesusu = $mesusu";
            $dbwhere .= " and r53_regist = ".$rh01_regist;
            $dbwhere .= " and r53_rubric in ($rub) ";
            $dbwhere .= " and r53_instit = ".db_getsession("DB_instit")." ";
            $campos1 = "(select sum(case when r53_pd in (1,3) then r53_valor else r53_valor *-1 end)) as r53_valor";

            $result_selecao = $clgerffx->sql_record($clgerffx->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));

            if($clgerffx->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $nVlrProvFx += $r53_valor;
            }
          }

        } else {

          /////////////
          // Se não for funcionário ou valor do rhipe for diferente de zero
          /////////////
          $prov = $rh14_valor;

        }




        $clipe->r36_regist = $rh01_regist;
        $clipe->r36_matric = $rh14_matipe;
        $clipe->r36_numcgm = $z01_numcgm;
        $clipe->r36_dtvinc = $rh14_dtvinc;
        $clipe->r36_estado = $rh14_estado;
        $clipe->r36_dtalt  = $rh14_dtalt;

        if( db_empty($prov) && ($rh14_estado == "21" || $rh14_estado == "22" )){

          if ( $lRecPatrAfasta && $nVlrProvFx > 0 && ($minimo < $nVlrProvFx)) {

            $clipe->r36_contr1 = 0;
            $clipe->r36_valorc = $nVlrProvFx;        		
          } else {

            $clipe->r36_contr1 = 0;
            $clipe->r36_valorc = $minimo;
          }
        } else if ( $prov < $minimo){

          $clipe->r36_contr1 = $prov;
          $clipe->r36_valorc = $minimo;
        } else {

          $clipe->r36_contr1 = $prov;
          $clipe->r36_valorc = $prov;
        }

        if($rh14_estado == "21" ) {
          continue;
        }

        if($rh14_estado == "22"){
          $clipe->r36_estado = 10;
        }

        //echo "<br> regist --> $rh01_regist    valor --> $prov";

        $clipe->incluir($anousu,$mesusu,$rh14_sequencia,db_getsession('DB_instit'));
        $contador_inclusoes ++;

        if($clipe->erro_status == 0){
          
          $erro_msg = $clipe->erro_msg;
          $sqlerro = true;
          break;
        }
      }
    }
  }

  if($sqlerro == false){
    $erro_msg = "$contador_inclusoes registros incluídos com sucesso.";
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
include("forms/db_frmipe.php");
db_menu();
?>
</body>
</html>
<?
if(isset($processar)){
  db_msgbox($erro_msg);
}
