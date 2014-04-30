<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
*  ROTINA DE INCLUSAO DE BAIXA DE MATRICULA
*  26-02-2007
*/
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubaixa_classe.php");
include("classes/db_arrematric_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_iptubaixaproc_classe.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cliptubase = new cl_iptubase;
$clarrematric = new cl_arrematric;
$cliptubaixa = new cl_iptubaixa;
$cliptubaixaproc = new cl_iptubaixaproc;
$cldb_config                 = new cl_db_config;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){

  $lControlaAjuizado = 0;
  $iInstitSessao = db_getsession('DB_instit');
  $result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
  db_fieldsmemory($result, 0);
  if ($db21_codcli == 19985) {
    $lControlaAjuizado = 1;
  }

	$result_deb = $clarrematric->sql_record($clarrematric->sql_query_info(null,null,"distinct cadtipo.k03_tipo,cadtipo.k03_descr",null,"arrematric.k00_matric = $j02_matric"));
	$numrows_deb = $clarrematric->numrows;
	$descr = '\n';
	$debitos =true;
	if($numrows_deb>0 and $lControlaAjuizado == 1){
    $lTemInicialAberto = 0;
		for($w=0;$w<$numrows_deb;$w++){
			db_fieldsmemory($result_deb,$w);
			$descr .=  "*".@$k03_descr.'\n';

      if ( $k03_tipo == 13 or $k03_tipo == 18 ) {
        $lTemInicialAberto = 1;
      }

		}
    if ( $lTemInicialAberto == 1 ) {
     	echo "<script>
            alert('Existe débito ajuizado em aberto para esta matrícula - procedimento não pode ser executado!');
            </script>";
    }
  } else {

    $sqlerro = false;
    $rsVerificaBaixa = $cliptubaixa->sql_record($cliptubaixa->sql_query_file($j02_matric,"*",null,""));
    if ($cliptubaixa->numrows > 0) {
      db_msgbox("Matrícula ja baixada !");
      db_redireciona('cad1_iptubaixa001.php');
      exit;
    }
    if (!$sqlerro) {
      db_inicio_transacao();
      // update na iptubase setando j01_baixa com a data da baixa 
      $sqlUpdate = " update iptubase set j01_baixa = '".$j02_dtbaixa_ano."-".$j02_dtbaixa_mes."-".$j02_dtbaixa_dia."' where j01_matric = $j02_matric ";
      $rsUpdate  = $cliptubase->sql_record($sqlUpdate);

      // inclui os dados da baixa
      $cliptubaixa->incluir($j02_matric);
      if($cliptubaixa->erro_status == 0){
        $sqlerro = true;
        $cliptubaixa->erro_msg = $cliptubaixa->erro_msg;
      }
      // inclui na iptubaixaproc se estiver preenchido o codigo processo 
      if(isset($j03_codproc) && $j03_codproc != ''){
        $cliptubaixaproc->incluir($j02_matric);
        if($cliptubaixaproc->erro_status == 0){
          $sqlerro = true;
          $cliptubaixa->erro_msg = $cliptubaixaproc->erro_msg;
        }
      }
      db_fim_transacao();
    }
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmiptubaixa.php");
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
js_tabulacaoforms("form1","j02_dtbaixa",true,1,"j02_dtbaixa",true);
</script>
<?
if(isset($incluir)){
  if($cliptubaixa->erro_status=="0"){
    $cliptubaixa->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cliptubaixa->erro_campo!=""){
      echo "<script> document.form1.".$cliptubaixa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptubaixa->erro_campo.".focus();</script>";
    }
  }else{
    $cliptubaixa->erro(true,true);
  }
}
?>