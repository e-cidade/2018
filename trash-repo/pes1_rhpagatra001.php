<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_rhpagatra_classe.php");
include("classes/db_rhpagocor_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesjustica_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clrhpagatra = new cl_rhpagatra;
$clrhpagocor = new cl_rhpagocor;
$clrhpessoal = new cl_rhpessoal;
$clrhpesjustica = new cl_rhpesjustica;
$db_opcao = 1;
$db_botao = true;

db_sel_cfpess(db_anofolha(),db_mesfolha(),"r11_databaseatra");

if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
  if($rh58_valor != "" && $rh58_valor > 0){
    $clrhpagocor->rh58_seq = $rh57_seq;
    $clrhpagocor->incluir($rh58_codigo);
    if($clrhpagocor->erro_status != 0){
      $result_rhpagatra_dados = $clrhpagatra->sql_record($clrhpagatra->sql_query_file($rh57_seq));
      db_fieldsmemory($result_rhpagatra_dados, 0);
      $clrhpagatra->rh57_ano = $rh57_ano; 
      $clrhpagatra->rh57_mes = $rh57_mes; 
      $clrhpagatra->rh57_regist = $rh57_regist; 
      $clrhpagatra->rh57_valorini = $rh57_valorini; 
      $clrhpagatra->rh57_tipoatra = $rh57_tipoatra; 
      $clrhpagatra->rh57_seq = $rh57_seq;
      $soma_ou_subtrai = "+";
      if($rh59_tipo == "D"){
        $soma_ou_subtrai = "-";
      }
      $clrhpagatra->rh57_saldo= "(rh57_saldo ".$soma_ou_subtrai." ".$rh58_valor.")";
      $clrhpagatra->alterar($rh57_seq);
      if($clrhpagatra->erro_status == 0){
        $sqlerro = true;
	$clrhpagocor->erro_status = 0;
	$clrhpagocor->erro_msg   = $clrhpagatra->erro_msg;
      }
    }

  }else{
    $sqlerro = true;
    $clrhpagocor->erro_status = 0;
    $clrhpagocor->erro_msg    = "Valor deve ser superior a 0 (zero). Inclusão abortada.";
    $clrhpagocor->erro_campo  = "rh58_valor";
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  db_inicio_transacao();
  $opcao    = "alterar";
  $db_opcao = 2;
  $clrhpagocor->rh58_seq = $rh57_seq;
  $clrhpagocor->alterar($rh58_codigo);
  db_fim_transacao();
}else if(isset($excluir)){
  db_inicio_transacao();
  $opcao    = "excluir";
  $db_opcao = 3;
  $clrhpagocor->excluir($rh58_codigo);
  db_fim_transacao();
}else if(isset($rh57_regist)){
  if(isset($opcao) && isset($rh58_codigo)){
    if($opcao == "alterar"){
      $db_opcao = 2;
    }else{
      $db_opcao = 3;
    }
    $result_dadosmovi = $clrhpagocor->sql_record($clrhpagocor->sql_query($rh58_codigo));
    if($clrhpagocor->numrows > 0){
      db_fieldsmemory($result_dadosmovi, 0);
    }
  }
  if(isset($rh57_seq)){
    $dbhaving = " rh57_regist = $rh57_regist and rh57_seq = $rh57_seq ";
    $sql = $clrhpagatra->sql_query_tipoatras(null,
                                            "rh57_seq, 
                                             rh57_ano, 
                                             rh57_mes, 
                                             rh57_regist, 
                                             rh57_valorini,
     					     rh57_saldo,
                                             rh57_tipoatra, 
                                             rh60_descr",
                                            "rh57_ano,rh57_mes",
                                            $dbhaving
                                           );
//    die($sql); 
    $result_dadosatraso = $clrhpagatra->sql_record($sql);
    if($clrhpagatra->numrows > 0){
      db_fieldsmemory($result_dadosatraso, 0);
      $rh57_valorini = trim(db_formatar($rh57_valorini,"f"));
      $valsaldo      = trim(db_formatar($rh57_saldo,"f"));
    }
  }
  $result_matricula = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh57_regist,"z01_nome"));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_matricula, 0);
  }
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
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
      include("forms/db_frmrhpagatra.php");
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
<script>
<?if(!isset($rh57_regist) || !isset($rh57_seq)){?>
js_tabulacaoforms("form1","rh57_regist",true,1,"rh57_regist",true);
<?}else if($db_opcao == 3){?>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
<?}else{?>
js_tabulacaoforms("form1","rh58_tipoocor",true,1,"rh58_tipoocor",true);
<?}?>
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clrhpagocor->erro_status=="0"){
    $clrhpagocor->erro(true,false);
    if($clrhpagocor->erro_campo!=""){
      echo "<script> document.form1.".$clrhpagocor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhpagocor->erro_campo.".focus();</script>";
    }
  }else{
    echo "
          <script>
	    location.href = 'pes1_rhpagatra001.php?rh57_regist=".$rh57_regist."';
	  </script>
	 ";
  }
}
?>