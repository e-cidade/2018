<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_bensmater_classe.php");
require_once("classes/db_bensimoveis_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_bensmaterialempempenho_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clbensmater = new cl_bensmater;
$clbensimoveis = new cl_bensimoveis;
$clempempenho = new cl_empempenho;
$clbensmatemp = new cl_bensmaterialempempenho;

if(isset($incluir)){

  $sqlerro=false;
  if($sqlerro==false){
    db_inicio_transacao();

    $clbensmater->t53_empen = $e60_numemp;
    $clbensmater->incluir($t53_codbem);
    if($clbensmater->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbensmater->erro_msg;
    //db_msgbox("$erro_msg");

   // db_msgbox($sqlerro);
    db_fim_transacao($sqlerro);
  }
    /*
     * Inclusao na tabela "bensmaterialempempenho", se o empenho for do sistema
     */
    if ($_POST['emp_sistema'] == 's') {
		  if ($sqlerro == false) {

		    db_inicio_transacao();
		    $clbensmatemp->t11_bensmaterial = $t53_codbem;
		    $clbensmatemp->t11_empempenho   = $e60_numemp;
		    $clbensmatemp->incluir(null);
		    if ($clbensmatemp->erro_status == 0) {
		      $sqlerro=true;
		    }
		    $erro_msg = $clbensmatemp->erro_msg;
		    db_fim_transacao($sqlerro);
		  }
    }

}else if(isset($alterar)){
  $sqlerro=false;
  if($sqlerro==false){
    db_inicio_transacao();

    $clbensmater->t53_empen = $e60_numemp;
    $clbensmater->alterar($t53_codbem);
    if($clbensmater->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbensmater->erro_msg;

    db_fim_transacao($sqlerro);
  }
    /*
     * Alteração na tabela "bensmaterialempempenho"
     * exclui o atual, e inclui novamente
     * se o empenho for do sistema
     */
  if( $sqlerro == false ){

    db_inicio_transacao();
  	if ($_POST['emp_sistema'] == 's') {

      $sSqlBensMatEmp  = $clbensmatemp->sql_query_file("","t11_sequencial","","t11_bensmaterial = {$t53_codbem} ");
      $rsSqlBensMatEmp = $clbensmatemp->sql_record($sSqlBensMatEmp);
      if ($clbensmatemp->numrows > 0) {

        db_fieldsmemory($rsSqlBensMatEmp,0);
        $clbensmatemp->excluir($t11_sequencial);
        if($clbensmatemp->erro_status==0){
				  $sqlerro=true;
				}
				$erro_msg = $clbensmatemp->erro_msg;

      }
      if ($sqlerro == false) {

        $clbensmatemp->t11_bensmaterial = $t53_codbem;
        $clbensmatemp->t11_empempenho   = $e60_numemp;
        $clbensmatemp->incluir(null);
        if($clbensmatemp->erro_status==0){
          $sqlerro=true;
        }
        $erro_msg = $clbensmatemp->erro_msg;
      }
  	} else {
	    /*
	     * Excluir na tabela "bensmaterialempempenho", se o empenho nao for do sistema
	     */
      $sSqlBensMatEmp  = $clbensmatemp->sql_query_file("","t11_sequencial","","t11_bensmaterial = {$t53_codbem} ");
      $rsSqlBensMatEmp = $clbensmatemp->sql_record($sSqlBensMatEmp);
      if ($clbensmatemp->numrows > 0) {

        db_fieldsmemory($rsSqlBensMatEmp,0);
        $clbensmatemp->excluir($t11_sequencial);
        if ($clbensmatemp->erro_status==0) {
          $sqlerro=true;
        }
        $erro_msg = $clbensmatemp->erro_msg;
      }
  	}
    db_fim_transacao($sqlerro);
  }

}else if(isset($excluir)){
  $sqlerro=false;
  if($sqlerro==false){
    db_inicio_transacao();

    /*
     * Excluir na tabela "bensmaterialempempenho"
     */
    $sSqlBensMatEmp  = $clbensmatemp->sql_query_file("","t11_sequencial","","t11_bensmaterial = {$t53_codbem} ");
    $rsSqlBensMatEmp = $clbensmatemp->sql_record($sSqlBensMatEmp);
    if($clbensmatemp->numrows > 0){

      db_fieldsmemory($rsSqlBensMatEmp,0);
      $clbensmatemp->excluir($t11_sequencial);
      if($clbensmatemp->erro_status==0){
        $sqlerro=true;
      }
      $erro_msg = $clbensmatemp->erro_msg;
    }
    $clbensmater->excluir($t53_codbem);
    if($clbensmater->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbensmater->erro_msg;
    db_fim_transacao($sqlerro);
  }
  $t53_ntfisc = "";
  $e60_numemp = "";
  $e60_codemp = "";
  $z01_nome = "";
  $t53_ordem = "";
  $t53_garant_dia = "";
  $t53_garant_mes = "";
  $t53_garant_ano = "";
}

$result = $clbensimoveis->sql_record($clbensimoveis->sql_query_file(null,null,"*","","t54_codbem = ".$t53_codbem));
if($clbensimoveis->numrows==0){
  $result1 = $clbensmater->sql_record($clbensmater->sql_query_file(null,"*","","t53_codbem = ".$t53_codbem));
  $numrows = $clbensmater->numrows;
  if($numrows>0){
    $result2 = $clbensmater->sql_record($clbensmater->sql_query_bensmater($t53_codbem));
    if($clbensmater->numrows>0){
      db_fieldsmemory($result2, 0);
      if(trim($e60_numemp)!=""){
        $result_codemp = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp,"e60_codemp||'/'||e60_anousu as e60_codemp"));
        if($clempempenho->numrows > 0){
          db_fieldsmemory($result_codemp,0);
        }
      }
    }
  }
}
if(isset($desabilita) && $desabilita==true || $clbensimoveis->numrows>0){
    echo "<center><b><h3>Bem cadastrado como Imóvel. <br> Cadastro de bens materiais desabilitado.</h3></b></center>";
    $db_botao = false;
    $db_opcao = 22;
}else if(isset($db_opcaoal) && $db_opcaoal == 33){
    $db_botao = false;
    $db_opcao = 33;
}else if($numrows > 0){
    $db_botao=true;
    $db_opcao=2;
}else{
    $db_botao = true;
    $db_opcao=1;
}

if (isset($importar) && $importar == true){
     $result = $clbensmater->sql_record($clbensmater->sql_query_file(null,"t53_ntfisc,e60_numemp,t53_ordem,t53_garant","t53_codbem","t53_codbem = ".$codbem));

     if ($clbensmater->numrows > 0){
          db_fieldsmemory($result,0);
          if(trim($e60_numemp)!=""){
              $result_codemp = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp,"e60_codemp||'/'||e60_anousu as e60_codemp"));
              if($clempempenho->numrows > 0){
                  db_fieldsmemory($result_codemp,0);
              }
          }
          $result2 = $clbensmater->sql_record($clbensmater->sql_query_bensmater($codbem,"z01_nome","t53_codbem"));
	  if ($clbensmater->numrows > 0){
	       db_fieldsmemory($result2,0);
	  }
     }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmbensmater.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
}
?>