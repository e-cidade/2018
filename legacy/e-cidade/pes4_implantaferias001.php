<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cadferia_classe.php");
include("classes/db_cfpess_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesrescisao_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_libpessoal.php");
db_postmemory($HTTP_POST_VARS);
$clcadferia      = new cl_cadferia;
$clcfpess        = new cl_cfpess;
$clrhpessoal     = new cl_rhpessoal;
$clrhpesrescisao = new cl_rhpesrescisao;

$dbopcao = false;
$rescindido = false;
$nexistfunc = true;
$priperiodo = true;

$r30_anousu = db_anofolha();
$r30_mesusu = db_mesfolha();

$db_opcao = 1;

if ( !isset($r30_regist) ) {
  $db_opcao = 3;
}
if(isset($enviar) || isset($alterar)){

  $r30_perai = $r30_perai_ano."-".$r30_perai_mes."-".$r30_perai_dia;
  $r30_peraf = $r30_peraf_ano."-".$r30_peraf_mes."-".$r30_peraf_dia;

  $r30_per1i = $r30_per1i_ano."-".$r30_per1i_mes."-".$r30_per1i_dia;
  $r30_per1f = $r30_per1f_ano."-".$r30_per1f_mes."-".$r30_per1f_dia;

  $r30_per2i = $r30_per2i_ano."-".$r30_per2i_mes."-".$r30_per2i_dia;
  $r30_per2f = $r30_per2f_ano."-".$r30_per2f_mes."-".$r30_per2f_dia;

  /*
  $sql = $clcadferia->sql_query_file(
                                     null,
                                     " r30_perai ",
                                     "",
                                     "
                                           r30_anousu = ".$r30_anousu." 
                                       and r30_mesusu = ".$r30_mesusu."
                                       and r30_regist = ".$r30_regist."
                                       and r30_perai  = '".$r30_perai_ant."'"
                                    );

  $result_cadferia = $clcadferia->sql_record($sql);
  */

  db_inicio_transacao();

  $r30_dias1 = 0;
  $r30_dias2 = 0;
  $r30_abono = 0;
  if($r30_ndias == 30){
     if($r30_tip1 == '01'){
       $r30_dias1 = 30;
     }else if($r30_tip1 == '02'){
       $r30_dias1 = 20;
     }else if($r30_tip1 == '03'){
       $r30_dias1 = 15;
     }else if($r30_tip1 == '04'){
       $r30_dias1 = 10;
     }else if($r30_tip1 == '05'){
       $r30_dias1 = 20;
       $r30_abono = 10;
     }else if($r30_tip1 == '06'){
       $r30_dias1 = 15;
       $r30_abono = 15;
     }else if($r30_tip1 == '07'){
       $r30_dias1 = 10;
       $r30_abono = 20;
     }else if($r30_tip1 == '07'){
       $r30_abono = 30;
     }else if($r30_tip1 == '12'){
       $r30_dias1 = $r30_diasgozados1;
       $r30_dias2 = $r30_diasgozados2;
     }
  }else{
     if($r30_tip1 == '01'){
       $r30_dias1 = $r30_ndias;
     }else if($r30_tip1 == '02'){
       $r30_abono = $r30_ndias;
     }else if($r30_tip1 == '12'){
       $r30_dias1 = $r30_diasgozados1;
       $r30_dias2 = $r30_diasgozados2;       
     }
  }
       
  if(trim($r30_per2i_dia) != "" && trim($r30_per2i_mes) != "" && trim($r30_per2i_ano) != ""){
    $r30_dias2 = $r30_ndias - $r30_abono - $r30_dias1;
  }else{
    $r30_tip2 = "";
    $r30_per2i_dia = "";
    $r30_per2i_mes = "";
    $r30_per2i_ano = "";
    $r30_per2f_dia = "";
    $r30_per2f_mes = "";
    $r30_per2f_ano = "";
    $r30_proc2 = "";
    $r30_tip2 = "";
    $r30_dias2 = 0;
  }

  $matriz1 = array();
  $matriz2 = array();
  $matriz1[1] = "r30_anousu";
  $matriz1[2] = "r30_mesusu";
  $matriz1[3] = "r30_regist";
  $matriz1[4] = "r30_numcgm";
  $matriz1[5] = "r30_perai";
  $matriz1[6] = "r30_peraf";
  $matriz1[7] = "r30_ndias";
  $matriz1[8] = "r30_abono";
  $matriz1[9] = "r30_faltas";
  $matriz1[10] = "r30_ponto";
  $matriz1[11] = "r30_per1i";
  $matriz1[12] = "r30_per1f";
  $matriz1[13] = "r30_proc1";
  $matriz1[14] = "r30_tip1";
  $matriz1[15] = "r30_dias1";
  $matriz1[16] = "r30_per2i";
  $matriz1[17] = "r30_per2f";
  $matriz1[18] = "r30_proc2";
  $matriz1[19] = "r30_tip2";
  $matriz1[20] = "r30_dias2";

  $r30_proc1_reverso = $r30_proc1;
  $r30_proc2_reverso = $r30_proc2;

  if(isset($r30_proc1) && !empty($r30_proc1)) {
    list($r30_proc1_mes, $r30_proc1_ano) = explode("/", $r30_proc1);
    $r30_proc1_reverso = $r30_proc1_ano ."/". $r30_proc1_mes;
  }

  if(isset($r30_proc2) && !empty($r30_proc2)) {
    list($r30_proc2_mes, $r30_proc2_ano) = explode("/", $r30_proc2);
    $r30_proc2_reverso = $r30_proc2_ano ."/". $r30_proc2_mes;
  }

  $matriz2[1] = $r30_anousu + 0;
  $matriz2[2] = $r30_mesusu + 0;
  $matriz2[3] = $r30_regist + 0;
  $matriz2[4] = $z01_numcgm + 0;
  $matriz2[5] = db_nulldata($r30_perai); 
  $matriz2[6] = db_nulldata($r30_peraf);
  $matriz2[7] = $r30_ndias + 0;
  $matriz2[8] = $r30_abono + 0;
  $matriz2[9] = $r30_faltas + 0;
  $matriz2[10] = $r30_ponto;
  $matriz2[11] = db_nulldata($r30_per1i);
  $matriz2[12] = db_nulldata($r30_per1f);
  $matriz2[13] = $r30_proc1_reverso;
  $matriz2[14] = $r30_tip1;
  $matriz2[15] = $r30_dias1 + 0;
  $matriz2[16] = db_nulldata($r30_per2i);
  $matriz2[17] = db_nulldata($r30_per2f);
  $matriz2[18] = $r30_proc2_reverso;
  $matriz2[19] = $r30_tip2;
  $matriz2[20] = $r30_dias2 + 0;

  if(isset($alterar)){
    $erro_msg = "Alteração efetuada com sucesso.";
    $result_insert = db_update("cadferia", $matriz1, $matriz2, " where r30_anousu = ".$r30_anousu." and r30_mesusu = ".$r30_mesusu." and r30_regist = ".$r30_regist." and r30_perai = '".$r30_perai_ant."'");
  }else{
    $erro_msg = "Implantação efetuada com sucesso.";
    $result_insert = db_insert("cadferia", $matriz1, $matriz2, false);
  }
  db_fim_transacao();
}else if(isset($excluir)){

  $erro_msg = "Exclusão efetuada com sucesso.";
  $r30_perai = $r30_perai_ano."-".$r30_perai_mes."-".$r30_perai_dia;
  $dbwhere = "    r30_anousu = ".$r30_anousu." 
              and r30_mesusu = ".$r30_mesusu."
              and r30_regist = ".$r30_regist."
              and r30_perai  = '".$r30_perai_ant."'
             ";
  db_inicio_transacao();
  $result_insert = db_delete("cadferia", " where ".$dbwhere);
  db_fim_transacao();
}else if(isset($opcao)){
  if($opcao == "alterar"){
    $db_opcao = 2;
  }else{
    $db_opcao = 3;
    $dbopcao  = true;
  }
  $arr_perai = split("/",$r30_perai);
  $r30_perai = $arr_perai[2]."-".$arr_perai[1]."-".$arr_perai[0];
  $result_dados_ferias = $clcadferia->sql_record($clcadferia->sql_query_pesquisa(null," cadferia.*, rh05_recis, z01_nome, z01_numcgm ",""," r30_anousu = ".$r30_anousu." and r30_mesusu = ".$r30_mesusu." and r30_regist = ".$r30_regist." and r30_perai = '".$r30_perai."'"));
  if($clcadferia->numrows > 0){
    db_fieldsmemory($result_dados_ferias, 0);
    if(trim($rh05_recis) != ""){
      $rescindido = true;
    }else{
      $nexistfunc = false;
    }
  }
}else if(isset($r30_regist)){
  $db_opcao = 1;
  $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_ngeraferias(null,"rh05_seqpes","","rh02_regist = $r30_regist and rh02_anousu = ".$r30_anousu." and rh02_mesusu = ".$r30_mesusu));
  if($clrhpesrescisao->numrows > 0){
    $rescindido = true;
  }else{

    $result_admissao = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($r30_regist,"z01_nome,z01_numcgm,rh01_admiss"));
    if($clrhpessoal->numrows > 0){
      db_fieldsmemory($result_admissao, 0);
      $nexistfunc = false;
    }

    $sql = $clcadferia->sql_query_file(
                                       null,
                                       " r30_ndias, r30_dias1, r30_dias2, r30_abono, r30_perai,
                                         r30_peraf, r30_per1i, r30_per2i, r30_per1f, r30_per2f,
                                         r30_proc1, r30_proc2, r30_faltas, r30_tip1 ",
                                       " r30_regist,r30_perai desc ",
                                       "
                                             r30_anousu = ".$r30_anousu." 
                                         and r30_mesusu = ".$r30_mesusu."
                                         and r30_regist = ".$r30_regist
                                      );
    $result_cadferia = $clcadferia->sql_record($sql);
    if($clcadferia->numrows > 0){
      db_fieldsmemory($result_cadferia, 0);
      $saldo = $r30_ndias - ($r30_dias1 + $r30_dias2 + $r30_abono);
      if($saldo == 0){
        $r30_perai = mktime(0,0,0,$r30_peraf_mes,($r30_peraf_dia + 1),$r30_peraf_ano);
        $r30_perai_dia = db_subdata($r30_perai,"d","t");
        $r30_perai_mes = db_subdata($r30_perai,"m","t"); 
        $r30_perai_ano = db_subdata($r30_perai,"a","t");

        $r30_peraf = mktime(0,0,0,$r30_perai_mes,($r30_perai_dia - 1),($r30_perai_ano + 1));
        $r30_peraf_dia = db_subdata($r30_peraf,"d","t");
        $r30_peraf_mes = db_subdata($r30_peraf,"m","t");
        $r30_peraf_ano = db_subdata($r30_peraf,"a","t");

        $r30_ndias = 30;
        $saldo = 30;
        $r30_abono = 0;
        unset(
              $r30_dias1, $r30_dias2,
              $r30_proc1, $r30_proc2,
              $r30_faltas, $r30_tip1,
              $r30_per1i_dia, $r30_per1i_mes, $r30_per1i_ano, 
              $r30_per1f_dia, $r30_per1f_mes, $r30_per1f_ano,
              $r30_per2i_dia, $r30_per2i_mes, $r30_per2i_ano, 
              $r30_per2f_dia, $r30_per2f_mes, $r30_per2f_ano
             );
      }
    }else{
      if(isset($rh01_admiss)){
        $r30_perai = $rh01_admiss;
        $r30_perai_dia = $rh01_admiss_dia;
        $r30_perai_mes = $rh01_admiss_mes; 
        $r30_perai_ano = $rh01_admiss_ano;
          
        $r30_peraf = mktime(0,0,0,$r30_perai_mes,($r30_perai_dia - 1),($r30_perai_ano + 1));
        $r30_peraf_dia = db_subdata($r30_peraf,"d");
        $r30_peraf_mes = db_subdata($r30_peraf,"m");
        $r30_peraf_ano = db_subdata($r30_peraf,"a");
        $r30_ndias = 30;
        $saldo = 30;
      }
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body>
<?php 
  include("forms/db_frmimplantaferias.php");
  db_menu();
?>
</body>
</html>

<?php
if(isset($r30_regist)){
  if($nexistfunc == true && !isset($enviar) && !isset($alterar) && !isset($excluir)){
    db_msgbox("Funcionário ".$r30_regist." não encontrado. Verifique.");
    echo "<script>location.href = 'pes4_implantaferias001.php';</script>";
  }
}

if(isset($enviar) || isset($alterar) || isset($excluir)){
  if($result_insert == true){
    db_msgbox($erro_msg);
    echo "<script>location.href = 'pes4_implantaferias001.php?r30_regist=$r30_regist";
  }else{
    // db_msgbox("Erro ao implantar no cadferia. Contate o suporte.");
    db_msgbox($clcadferia->erro_msg);
  }
}

if(isset($r30_regist) && trim($r30_regist) != ""){
  echo "
        <script>
          js_tabulacaoforms('form1','r30_perai_dia',true,1,'r30_perai_dia',true);
        </script>
       ";
}else if($dbopcao == true){
  echo "
        <script>
          js_tabulacaoforms('form1','excluir',true,1,'excluir',true);
        </script>
       ";
}else{
  echo "
        <script>
          js_tabulacaoforms('form1','r30_regist',true,1,'r30_regist',true);
        </script>
       ";
}
