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

session_start();
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_aidof_classe.php");
$clarreinscr = new cl_arreinscr;
$clissbase   = new cl_issbase;
$claidof     = new cl_aidof;

$sqlmenu="SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE trim(m_arquivo) = 'digitaaidof.php'
                   ORDER BY m_descricao
                   ";
$result = db_query($sqlmenu);

                  
db_fieldsmemory($result,0);
//if($m_publico != 't'){
 // if(!session_is_registered("DB_acesso"))
//    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
//}

if(@$_COOKIE["cookie_codigo_cgm"]==""){
 // issbase
 if($inscricaow!=""){
  $result  = $clissbase->sql_record($clissbase->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","issbase.q02_inscr = $inscricaow"));
  $linhas1 = $clissbase->numrows;
  db_fieldsmemory($result,0);
  setcookie("cookie_codigo_cgm",$z01_numcgm);
  setcookie("cookie_nome_cgm",$z01_nome);
 }
}
$db_verifica_ip = db_verifica_ip();
//mens_help();
$dblink="digitaaidof.php";
db_logs("","",0,"Digita Aidof.");
postmemory($HTTP_POST_VARS);
$clquery = new cl_query;
$inscricaow!=""?"":$inscricaow = 0 ;
if ( !empty($cgc) ){
  $cgccpf = $cgc;
}else{
  if (!empty($cpf) ){
    $cgccpf = $cpf;
  }else{
    $cgccpf = "";
  }
}
$cgccpf = str_replace(".","",$cgccpf);
$cgccpf = str_replace("/","",$cgccpf);
$cgccpf = str_replace("-","",$cgccpf);  

if($cgccpf != "" ) {
// die($clissbase->sql_query("","*","","cgm.z01_cgccpf = '$cgccpf' and issbase.q02_inscr  = $inscricaow"));
 $result  = $clissbase->sql_record($clissbase->sql_query("","*","","cgm.z01_cgccpf = '$cgccpf' and issbase.q02_inscr  = $inscricaow"));
}else{
 $result  = $clissbase->sql_record($clissbase->sql_query("","*","","issbase.q02_inscr = $inscricaow"));
}

if($clissbase->numrows != 0){
  db_fieldsmemory($result,0);
  if($q02_dtbaix!=""){
  	db_msgbox("Contribuinte com inscrição baixada.");
	db_redireciona("digitaaidof.php");
	exit;
  }
}else{
  db_redireciona("digitaaidof.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
}  
if(!isset($DB_LOGADO) && $m_publico !='t'){
  $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricaow)";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    db_redireciona("digitaaidof.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
    exit;
  }
  $result = pg_result($result,0,0);
  if($result=="0"){
    db_redireciona("digitaaidof.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
    exit;
  }
} 
$result  = $clissbase->sql_record($clissbase->sql_query("","*","","issbase.q02_inscr  = $inscricaow"));
if($clissbase->numrows != 0){
   db_fieldsmemory($result,0);
}

?>

<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bg>
	<form name="form1" method="post" action="opcoesaidof.php" >
		<br><br><br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="texto" >
				<td width="30%"></td>
				<td width="30%" bgcolor="<?=$w01_corfundomenu?>"><b><?=$z01_nome ?></b></td>
				<td width="30%"></td>
			</tr>
			<tr class="texto" >
				<td></td>
				<td bgcolor="<?=$w01_corfundomenu?>">Inscrição: <?=$q02_inscr ?></td>
				<td></td>
			</tr>
			<tr class="texto" >
				<td></td>
				<td bgcolor="<?=$w01_corfundomenu?>">CNPJ: <?=$z01_cgccpf ?></td>
				<td></td>
			</tr>
			<tr ><td colspan="3">&nbsp;</td></tr>
			<tr ><td colspan="3">&nbsp;</td></tr>
			
			<tr class="texto">
				<td></td>
				<? echo"<td><img src='imagens/seta.gif' border='0'><a href='opcoesaidof.php?inscricaow=$q02_inscr'>Solicita AIDOF</a></td>"; ?>
				<td></td>
			</tr>
			<tr ><td colspan="2">&nbsp;</td></tr>
			<tr class="texto">
				<td></td>
				<? echo"<td><img src='imagens/seta.gif' border='0'><a href='aidof_consulta.php?inscricaow=$q02_inscr'>Consulta AIDOF</a></td>"; ?>
				<td></td>
			</tr>
		</table>
	</form>
</body>
</html>