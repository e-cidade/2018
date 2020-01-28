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
include("classes/db_selecao_classe.php");
include("classes/db_cfpess_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesrescisao_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_libpessoal.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clselecao = new cl_selecao;
$clcfpess = new cl_cfpess;
$clrhpessoal = new cl_rhpessoal;
$clrhpesrescisao = new cl_rhpesrescisao;
$db_opcao = 1;
$db_botao = true;

$dbopcao = false;
$rescindido = false;
$nexistfunc = true;
$nexistsele = false;

$anofolha = db_anofolha();
$mesfolha = db_mesfolha();

$r59_anousu = db_anofolha();
$r59_mesusu = db_mesfolha();

if (isset($regisi)) {
  $rei = $regisi;
}

if (isset($regisf)) {
  $ref = $regisf;
}

/**
 * Click no proximo, nao existe session com campomatriculas
 * criando somente para entrar entrar no else do bloco abaixo, e pular para proxima matricula sem efetuar rescisao
 */
if ( !empty($campomatriculas) ) {
	$_SESSION['campomatriculas'] = $campomatriculas;
}

if(!isset($_SESSION['campomatriculas'])){

  $whereestrut = " rh05_seqpes is null";
  $iInstituicao = db_getsession("DB_instit");
  if($selecao != 0){
    $sSql       = "select r44_where from selecao where r44_instit = $iInstituicao and r44_selec = ".$selecao;
    $result_sel = db_query($sSql);
    if(pg_numrows($result_sel) > 0){
      db_fieldsmemory($result_sel, 0, 1);
      $whereestrut .= " and ".$r44_where;
    }else{
      db_msgbox("Seleção ".$selecao." não encontrada. Verifique.");
      echo "<script>location.href = 'pes4_rhpesrescislote001.php';</script>";
    }
  }
  if ($tipo == "l"){
    if(isset($flt) && $flt != "") {
       $whereestrut .= " and r70_estrut in ('".str_replace(",","','",$flt)."') ";
    }elseif((isset($lti) && $lti != "" ) && (isset($ltf) && $ltf != "")){
       $whereestrut .= " and r70_estrut between '$lti' and '$ltf' ";
    }else if(isset($lti) && $lti != ""){
       $whereestrut .= " and r70_estrut >= '$lti' ";
    }else if(isset($ltf) && $ltf != ""){
       $whereestrut .= " and r70_estrut <= '$ltf' ";
    }
  }else if($tipo == "m"){
    if(isset($fre) && $fre != "") {
       $whereestrut .= " and rh02_regist in ('".str_replace(",","','",$fre)."') ";
    }elseif((isset($rei) && $rei != "" ) && (isset($ref) && $ref != "")){
       $whereestrut .= " and rh02_regist between '$rei' and '$ref' ";
    }else if(isset($rei) && $rei != ""){
       $whereestrut .= " and rh02_regist >= '$rei' ";
    }else if(isset($ref) && $ref != ""){
       $whereestrut .= " and rh02_regist <= '$ref' ";
    }
  }
   
  include("libs/db_sql.php");
  $clsql = new cl_gera_sql_folha;
  $clsql->usar_pes = true;
  $clsql->usar_pad = true;
  $clsql->usar_cgm = true;
  $clsql->usar_fun = true;
  $clsql->usar_lot = true;
  $clsql->usar_exe = true;
  $clsql->usar_org = true;
  $clsql->usar_atv = true;
  $clsql->usar_res = true;
  $clsql->usar_fgt = true;
  $clsql->usar_cad = true;
  $clsql->usar_tra = true;
  $clsql->usar_car = true;
  $clsql->usar_afa = true;
  $campomatriculas = "";
  $virgumatriculas = "";
  $sql = $clsql->gerador_sql("", $anofolha, $mesfolha, null, null, " rh01_regist ", "rh01_regist", $whereestrut);
  $result = $clsql->sql_record($sql);
  if($clsql->numrows_exec > 0){
    for($i=0; $i<$clsql->numrows_exec; $i++){
      db_fieldsmemory($result, $i);
      if($i > 0){
        $campomatriculas.= $virgumatriculas . $rh01_regist;
        $virgumatriculas = ",";
      }else{
        $r30_regist = $rh01_regist;
      }
    }
  }else{
    db_msgbox("Funcionário(s) não encontrado. Verifique.");
    echo "<script>location.href = 'pes4_rhpesrescislote001.php';</script>";
  }
}else{
  $arr_matriculas = split(",", $_SESSION['campomatriculas']);
  $r30_regist = array_shift($arr_matriculas);
  $campomatriculas = implode(",", $arr_matriculas);
  unset($_SESSION['campomatriculas']);
//echo "campomatriculas --> ".$campomatriculas;exit;
}
if($r30_regist != '' ){
  $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_ngeraferias(null,"*","","rh02_regist = $r30_regist and rh02_anousu = $anofolha and rh02_mesusu = $mesfolha"));
  if($clrhpesrescisao->numrows > 0){
    $rescindido = true;
  }else{
    $result_admissao = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($r30_regist,"z01_nome,z01_numcgm,rh01_admiss,rh02_codreg,rh02_seqpes"));
    if($clrhpessoal->numrows > 0){
      db_fieldsmemory($result_admissao, 0);
      $nexistfunc = false;
    }
  }
}else{
  echo "<script>location.href = 'pes4_rhpesrescislote001.php';</script>";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
        include("forms/db_frmrhpesrescis001.php");
        ?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($nexistsele == true){
  db_msgbox("Seleção ".$selecao." não encontrada. Verifique.");
  echo "<script>location.href = 'pes4_rhpesrescislote001.php';</script>";
}else if($nexistfunc == true ){
  db_msgbox("Funcionário ".$r30_regist." não encontrado. Verifique.");
  echo "<script>location.href = 'pes4_rhpesrescislote001.php';</script>";
}
if($rescindido == true){
  db_msgbox("Funcionário rescindiu contrato.");
  echo "
        <script>
          if(document.form1.proximo){
            document.form1.proximo.click();
          }else{
            document.form1.voltar.click();
          }
        </script>
       ";
}
?>
