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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");

include("classes/db_confsite_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_iptubase_classe.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
//postmemory($HTTP_POST_VARS,2);

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$clissbase  = new cl_issbase;
$cliptubase = new cl_iptubase;

db_mensagem("certidao_cab","certidao_rod");

$db_verificaip = db_verifica_ip();
$instit        = db_getsession("DB_instit");
$bForm         = true;
$numcgm        = "";

$sqlconf = "select db21_regracgmiptu, db21_regracgmiss from db_config where codigo = {$instit}";
$resconf = db_query($sqlconf);
$iconf   = pg_num_rows($resconf);

if ($iconf > 0) {
  db_fieldsmemory($resconf, 0);	
}

$sQueryCndPorNome   = "select * from configdbpref where w13_instit = {$instit}";
$resQueryCndPorNome = db_query($sQueryCndPorNome);
$iQueryCndPorNome   = pg_num_rows($resQueryCndPorNome);

if ($iQueryCndPorNome > 0) {
  db_fieldsmemory($resQueryCndPorNome,0);	
}

if( isset($id_usuario) and $id_usuario !=""){
   $numcgm = $id_usuario;
    
   // issbase
   $result1 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr","","cgm.z01_numcgm = $numcgm"));
   $linhas1 = $clissbase->numrows;
   
   // iptu
   $sqlpromitente = $cliptubase ->sqlmatriculas_nome_numero($numcgm,$db21_regracgmiptu);
   $result2 = db_query($sqlpromitente);
   $linhas2 = pg_num_rows($result2);
   
   if (isset($linhas1) && $linhas1 > 0) {
      db_fieldsmemory($result1,0);    	
   }
}

if (isset($oPost->pesquisa) && $oPost->pesquisa == "Pesquisa") {
  if (isset($oPost->cgc) && $oPost->cgc != "") {
  	$cgccpf = $oPost->cgc;
  } 
  if (isset($oPost->cpf) && $oPost->cpf != "") {
  	$cgccpf = $oPost->cpf;
  }
    $cgccpf    = formata_cgccpf($cgccpf);
	$sqlcgm    = "select * from cgm where trim(z01_cgccpf)= '$cgccpf' ";
	//die($sqlcgm);
	$resultcgm = db_query($sqlcgm);
	$linhascgm = pg_num_rows($resultcgm);
	
	if($linhascgm == 1){
		db_fieldsmemory($resultcgm,0);
		$numcgm = $z01_numcgm;
         //die($clissbase->sql_query("","issbase.q02_inscr","","cgm.z01_numcgm = $numcgm"));
		 $result1 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr","","cgm.z01_numcgm = $numcgm"));
		 $linhas1 = $clissbase->numrows;
		 //die($cliptubase ->sqlmatriculas_nome_numero($numcgm,$db21_regracgmiptu));
		 $sqlpromitente = $cliptubase ->sqlmatriculas_nome_numero($numcgm,$db21_regracgmiptu);
		 $result2 = db_query($sqlpromitente);
	     $linhas2 = pg_num_rows($result2);

		 if (isset($z01_numcgm) && $z01_numcgm != "") {
		    @setcookie("cookie_codigo_cgm",$z01_numcgm);
		 }
		 
		 if (isset($z01_nome) && $z01_nome != "") {
 		    @setcookie("cookie_nome_cgm",$z01_nome);
		 }		 
		 
	} else if ($linhascgm > 1){
		msgbox("Inconsistencia de dados, procure a Prefeitura.");
	} else {
		msgbox("CPF ou CNPJ inválido");
	}
}

if (isset($numcgm) && $numcgm != "") {
   if(isset($w13_libcertpos) && $w13_libcertpos == 't'){
     $bForm = false;
   }
}

if (isset($w13_libcertpos) && $w13_libcertpos == "t") {
   if(isset($linhas2) && $linhas2 != 0){
      $bForm = false;
   }

   if(isset($linhas1) && $linhas1 != 0){
      $bForm = false;
   }
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?db_estilosite();?>
</style>
<script>
 function js_valida(){
   var cnpj     = document.getElementById('cgc').value;
   var cpf      = document.getElementById('cpf').value;
   var iTamCnpj = js_tamanho(cnpj);
   var iTamCpf  = js_tamanho(cpf);
   
   if(cnpj == "" && cpf == ""){
     alert('Preencha um dos campo CNPJ/CPF!'); 
     return false;  
   } else {   
     if(cnpj != "" && iTamCnpj != 14){
       alert('CNPJ Inconsistente!');
       var retorno = false; 
     } else if(cpf != "" && iTamCpf != 11){
       alert('CPF Inconsistente!');
       var retorno = false; 
     }
     
     if(retorno === false){
       return false;
     }
   }
 }
 
 function js_tamanho(Campo){
   var vr = Campo;
       vr = vr.replace(".", "");
       vr = vr.replace(".", "");       
       vr = vr.replace("-", "");
       vr = vr.replace("/", "");
   var iTamCampo = vr.length 
   return iTamCampo;
 }
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<br><br><br>
<?
if ($bForm == true) {
?>
<form name="certidaonome" id="certidaonome" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
 	 <tr>
  	   <td colspan="2" align="center">
  	     <?=$DB_mens1?>
  	   </td>
  	 </tr>
 	 <tr>
  	   <td colspan="2" align="center">&nbsp;</td>
  	 </tr>   	 
     <tr>
      <td width="50%" height="30" align="right">
       CNPJ:&nbsp;
      </td>
      <td width="50%" height="30">
       <input name="cgc" type="text" class="digitacgccpf" id="cgc" size="18" maxlength="18" 
              onKeyPress="FormataCNPJ(this,event); return js_teclas(event);">
      </td>
     </tr>
     <tr>
      <td width="50%" height="30" align="right">
       CPF:&nbsp;
      </td>
      <td width="50%" height="30">
       <input name="cpf" type="text" class="digitacgccpf" id="cpf" size="14" maxlength="14" 
              onKeyPress="FormataCPF(this,event); return js_teclas(event);">
      </td>
     </tr>
    <tr>
      <td width="50%" height="30">&nbsp;</td>
      <td width="50%" height="30">
         <input  class="botao" type="submit" name="pesquisa" value="Pesquisa" class="botaoconfirma" onClick="return js_valida();">
      </td>
     </tr>
     <tr>
      <td colspan="3" align="<?=$DB_align2?>">
       <?=$DB_mens2?>
      </td>
     </tr>       
</table>
</form>
<?
} else if ($bForm == false) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
 	 <tr>
  	   <td colspan="2" align="center">
  	     <?=$DB_mens1?>
  	   </td>
  	 </tr>
 	 <tr>
  	   <td colspan="2" align="center">&nbsp;</td>
  	 </tr>
<?
if (isset($numcgm) && $numcgm != "") {
   if(isset($w13_libcertpos) && $w13_libcertpos == 't'){
     $bForm = false;
   	 echo "
		<tr> 
		 <td height=\"28\" align=\"center\">
		    <a class=\"links\" href=\"cai3_certidao.php?numcgm=".$numcgm."\">
		    	<img src=\"imagens/folder4.gif\" border=\"0\">Emite Certid&atilde;o por Nome
		   	</a>
		 </td>
		</tr>   	 
   	 ";
   }
}
if (isset($w13_libcertpos) && $w13_libcertpos == "t") {
   if(isset($linhas2) && $linhas2 != 0){
      $bForm = false;
      for($x=0;$x<$linhas2;$x++){
         @db_fieldsmemory($result2,$x);
         echo "
           <tr align=\"center\"> 
             <td height=\"28\">
               <a class=\"links\" href=\"cai3_certidao.php?matricula=".$j01_matric."\">
                 <img src=\"imagens/folder4.gif\" border=\"0\">Emite Certid&atilde;o da Matrícula ".$j01_matric."
               </a>
             </td>
           </tr>        
         ";
      }
   }
   if(isset($linhas1) && $linhas1 != 0){
      $bForm = false;
      for($i=0;$i<$linhas1;$i++){
         @db_fieldsmemory($result1,$i);
         echo "
           <tr align=\"center\"> 
             <td height=\"28\"> 
               <a class=\"links\" href=\"cai3_certidao.php?inscricao=".$q02_inscr."\">
                 <img src=\"imagens/folder4.gif\" border=\"0\">Emite Certid&atilde;o da Inscrição ".$q02_inscr."
               </a>
             </td>
           </tr>        
         ";
      }
   }   
}
?>
</table>
<?
}
?>
</body>
</html>
<?
db_logs("","",0,"Tela da Certidoo por Nome.");

function formata_cgccpf($sCgcCpf){
  $cgccpf = $sCgcCpf;  
  if (isset($cgccpf) && $cgccpf != ""){
  	$cgccpf = ereg_replace("[./-]","",$cgccpf);
  }
  return $cgccpf;
}
?>