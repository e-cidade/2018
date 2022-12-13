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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_varfix_classe.php"));
include(modification("classes/db_varfixproc_classe.php"));
include(modification("classes/db_varfixval_classe.php"));
include(modification("classes/db_varfixnotifica_classe.php"));
include(modification("classes/db_procfiscalvarfix_classe.php"));
include(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clvarfix         = new cl_varfix;
$clvarfixval      = new cl_varfixval;
$clvarfixproc     = new cl_varfixproc;
$clvarfixnotifica = new cl_varfixnotifica;
$clprocfiscalvarfix = new cl_procfiscalvarfix;

$db_opcao   = 22;
$db_botao=false;
$db_botao02=true;



if(isset($todosmeses) && $todosmeses == 't'){

for($i=$q34_mes;$i<13;$i++){
	  $rsConfirma = $clvarfixval->sql_record($clvarfixval->sql_query_file(null,"*",null," q34_codigo = $q33_codigo and q34_mes = $i and q34_ano = $q34_ano "));
	  $rows       = $clvarfixval->numrows;
	  if($rows > 0){
		  continue;
	  }
      $sqlerro=false;
	  db_inicio_transacao();
	  $clvarfixval->q34_numpar = $i;
	  $clvarfixval->q34_mes    = $i;
	  $clvarfixval->q34_ano    = $q34_ano;
	  $clvarfixval->q34_codigo = $q33_codigo;
	  $clvarfixval->incluir();
	  $erro_msg=$clvarfixval->erro_msg;
	  if($clvarfixval->erro_status==0){
		$sqlerro=true;
	  }
	  db_fim_transacao($sqlerro);
  }
}

if(isset($opcao)){// quando for alterar ou excluir traga os campos preenchidos
$result=$clvarfixval->sql_record($clvarfixval->sql_query_file(null,"*",null," q34_codigo = $q33_codigo and q34_mes = $q34_mes and q34_ano = $q34_ano "));
  db_fieldsmemory($result,0);
  $db_opcao   = 2;
  $db_botao=true;
}else if(isset($alterar)){

  $sqlerro=false;
  db_inicio_transacao();
  $db_opcao = 2;

  $clvarfixnotifica->excluir(null, "q37_varfix=$q33_codigo");
  $clvarfixnotifica->q37_varfix   = $q33_codigo;
  $clvarfixnotifica->q37_notifica = $q37_notifica;
  $clvarfixnotifica->incluir(null);

  $clvarfixproc->excluir(null, "q36_varfix=$q33_codigo");
  $clvarfixproc->q36_varfix   = $q33_codigo;
  $clvarfixproc->q36_processo = $q36_processo;
  $clvarfixproc->incluir(null);


  $clvarfix->alterar($q33_codigo);
  $erro_msg=$clvarfix->erro_msg;
  if($clvarfix->erro_status==0){
    $sqlerro=true;
  }

	 $sqlprocfiscalv = "select y113_sequencial from procfiscalvarfix where y113_varfix =  $q33_codigo ";
	 $resultprocfiscalv = db_query($sqlprocfiscalv);
	 $linhasprocfiscalv = pg_num_rows($resultprocfiscalv);
	 if($linhasprocfiscalv>0){
	 	 db_fieldsmemory($resultprocfiscalv,0);
	   $clprocfiscalvarfix->y113_sequencial =$y113_sequencial;
		 $clprocfiscalvarfix->excluir($y113_sequencial);
		 if($clprocfiscalvarfix->erro_status==0){
				$erro=$clprocfiscalvarfix->erro_msg;
	      $sqlerro = true;

	   }
	 }
	 if($procfiscal!=""){
			$clprocfiscalvarfix->y113_varfix     = $q33_codigo;
			$clprocfiscalvarfix->y113_procfiscal = $procfiscal;
			$clprocfiscalvarfix->incluir(null);
			if($clprocfiscalvarfix->erro_status==0){
	      $sqlerro=true;
				 $erro_msg = $clprocfiscalvarfix->erro_msg;
	    }
		}

  db_fim_transacao($sqlerro);

}else if(isset($chavepesquisa) && empty($inc) && empty($alt) && empty($exc) ){
   $result = $clvarfix->sql_record($clvarfix->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);

   $result = $clvarfixproc->sql_record($clvarfixproc->sql_query(null, "*", null, "q36_varfix=$q33_codigo")) ;

if ($clvarfixproc->numrows>0){
  db_fieldsmemory($result,0);
}

$result = $clvarfixnotifica->sql_record($clvarfixnotifica->sql_query(null, "*", null, "q37_varfix=$q33_codigo")) ;

if ($clvarfixnotifica->numrows>0){
    db_fieldsmemory($result,0);
}

$sqlprocfiscal = "select y113_procfiscal as procfiscal,z01_nome as nome
										 from procfiscalvarfix
										 inner join procfiscalcgm on y113_procfiscal = y101_procfiscal
										 inner join cgm on y101_numcgm=z01_numcgm
										 where y113_varfix = $chavepesquisa ";
	 $resultprocfiscal = db_query($sqlprocfiscal);
	 $linhasprocfiscal = pg_num_rows($resultprocfiscal);
	 if($linhasprocfiscal>0){
	 	db_fieldsmemory($resultprocfiscal,0);
	 }else{
	 	$nome="";
	 }

   $db_opcao = 2;
   $db_botao = true;
}else if(isset($inc)){
  $sqlerro=false;
  db_inicio_transacao();
  $rsConfirma = $clvarfixval->sql_record($clvarfixval->sql_query_file(null,"*",null," q34_codigo = $q33_codigo and q34_mes = $q34_mes and q34_ano = $q34_ano "));
  $rows       = $clvarfixval->numrows;
  if($rows > 0){
     $erro_msg = " Ja existe varfixval para esse mes!";
     db_msgbox("$erro_msg");
     $sqlerro=true;
  }
  $clvarfixval->q34_mes=$q34_mes;
  $clvarfixval->q34_ano=$q34_ano;
  $clvarfixval->q34_codigo=$q33_codigo;
  $clvarfixval->incluir();//$q33_codigo,$q34_mes,$q34_ano);
  $erro_msg=$clvarfixval->erro_msg;
  if($clvarfixval->erro_status==0){
    $sqlerro=true;
//	db_msgbox("$erro_msg");
  }
  db_fim_transacao($sqlerro);
  $db_opcao=2;
  $db_botao=true;
}else if(isset($alt)){
  $sqlerro=false;
  db_inicio_transacao();
   $clvarfixval->q34_mes=$q34_mes;
   $clvarfixval->q34_ano=$q34_ano;
   $clvarfixval->q34_codigo=$q33_codigo;
   $clvarfixval->alterar(null," q34_mes = $q34_mes and q34_codigo = $q33_codigo and q34_ano = $q34_ano" );
   $erro_msg=$clvarfixval->erro_msg;
   if($clvarfixval->erro_status==0){
      $sqlerro=true;

   }

  db_fim_transacao($sqlerro);
  $db_opcao=2;
  $db_botao=true;
}else if(isset($exc)){
  $sqlerro=false;
  db_inicio_transacao();
   $clvarfixval->q34_mes=$q34_mes;
   $clvarfixval->q34_ano=$q34_ano;
   $clvarfixval->q34_codigo=$q33_codigo;
   $clvarfixval->excluir(null," q34_codigo = $q33_codigo and q34_mes = $q34_mes and q34_ano = $q34_ano ");
   $erro_msg=$clvarfixval->erro_msg;
   if($clvarfixval->erro_status==0){
      $sqlerro=true;
   }
  db_fim_transacao($sqlerro);
  $db_opcao=2;
  $db_botao=true;
}

if(isset($opcao) && $opcao=="alterar"){
    $db_opcao02 = 2;
    $db_opcao04 = 2;
    $db_opcao05 = 3;
    $db_botao=true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao02 = 3;
    $db_opcao04 = 3;
    $db_opcao05 = 3;
    $db_botao=true;
}else{
    $db_opcao02 = 1;
    $db_opcao04 = 1;
    $db_opcao05 = 1;
    $db_botao=true;
}

if((isset($novo) && $novo=="ok")|| (isset($sqlerro) && $sqlerro==false)){
 $db_opcao=2;
 $db_botao=true;
 $q34_valor='';
 $q34_inflat='';
 unset($q34_ano);
 unset($q34_mes);
 $q34_dtval_dia='';
 $q34_dtval_mes='';
 $q34_dtval_ano='';
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
	include(modification("forms/db_frmvarfix.php"));
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
if(isset($alterar) || isset($inc) || isset($alt) || isset($exc)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clvarfix->erro_campo!=""){
      echo "<script> document.form1.".$clvarfix->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvarfix->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>