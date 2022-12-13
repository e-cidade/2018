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
include("classes/db_imobil_classe.php");
include("classes/db_iptubase_classe.php");
require_once("classes/db_configdbpref_classe.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$climobil       = new cl_imobil;
$cliptubase     = new cl_iptubase;
$clconfigdbpref = new cl_configdbpref;

$iInstit = db_getsession ("DB_instit");
$iLogin  = db_getsession ("DB_login");

//$sqlMenupref = " select distinct m_publico,
//                                 m_arquivo,
//                                 m_descricao
//                            from db_menupref 
//					       where m_arquivo = 'digitamatricula.php'
//					    order by m_descricao ";
////die($sqlMenupref);
//$result    = db_query($sqlMenupref);
//$iMenupref = pg_numrows($result);
//
//if ($iMenupref > 0) {
//db_fieldsmemory($result,0);	
//}
//
//if($m_publico != 't'){
//  if(!session_is_registered("DB_acesso"))
//    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
//}

if(isset($outro)){
  setcookie("cookie_codigo_cgm");
  header("location:certidaoimovel003.php");
}


$rsParametro = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file($iInstit,"w13_exigecpfcnpjmatricula"));
$oRetorno    = db_utils::fieldsMemory($rsParametro,0);

if ($oRetorno->w13_exigecpfcnpjmatricula == "t") {
   db_mensagem("imovel_cab","imovel_rod");
} else {
   db_mensagem("certidaomatric_cab","certidaomatric_rod");
}

$onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<? db_estilosite(); ?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script>
 function js_valida(){
   var parmdbpref = "<?=$oRetorno->w13_exigecpfcnpjmatricula;?>";
   var matimovel  = document.getElementById('matricula1').value;
   
   if(parmdbpref == "t"){
     var cnpj       = document.getElementById('cgc').value;
     var cpf        = document.getElementById('cpf').value;
     var iTamCnpj   = js_tamanho(cnpj);
     var iTamCpf    = js_tamanho(cpf);   

     if(matimovel == ""){
       alert('Preencha o campo MATRICULA DO IMÓVEL!'); 
       return false;     
     } else if(cnpj == "" && cpf == ""){
       alert('Preencha um dos campo CNPJ/CPF!'); 
       return false;  
     } else {   
       if(cnpj != "" && iTamCnpj < 14){
         alert('CNPJ Inconsistente!');
         var retorno = false; 
       }
        
       if(cpf != "" && iTamCpf < 11){
         alert('CPF Inconsistente!');
         var retorno = false; 
       }
     
       if(retorno === false){
         return false;
       }
     }   
   } else {
     if(matimovel == ""){
       alert('Preencha o campo MATRICULA DO IMÓVEL!'); 
       return false;     
     }   
   }
 }
 
 function js_carregando(bool){
   var bolean = bool;
   
   if (bolean === true) {
     document.getElementById('int_perc1').style.visibility='hidden';
     document.getElementById('int_perc2').style.visibility='hidden';
   } else {
     document.getElementById('int_perc1').style.visibility='visible';
     document.getElementById('int_perc2').style.visibility='visible';
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" 
      onLoad="js_carregando(true);" <?mens_OnHelp()?>>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2;padding:5px;
                                        margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center">
   <img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
</div>
<br /> <br /> <br /> <center>
<?
//verifica se está logado
if ((isset($id_usuario) && trim($id_usuario) != "") ) {
   $usuario = $id_usuario;

   //é imobilária
   if(is_numeric($usuario)){
      $result = $climobil->sql_record($climobil->sql_query("",
                                                           "iptubase.j01_matric, a.z01_nome as z01_nome,a.z01_cgccpf as z01_cgccpf",
                                                           "",
                                                           "imobil.j44_numcgm = $usuario"));
      $imobil = $climobil->numrows;
   
      //iptubase
      $sqlconf = "select db21_regracgmiptu, db21_regracgmiss from db_config where codigo = ".$iInstit;
      $resconf = db_query($sqlconf);
      db_fieldsmemory($resconf, 0);
		
      // exibe os imovéis do usuário
      $cliptubase    = new cl_iptubase;
      $sqlpromitente = $cliptubase ->sqlmatriculas_nome_numero($usuario, $db21_regracgmiptu);

      $result2  = db_query($sqlpromitente);
      $iptubase = pg_num_rows($result2);
      $sqlcgm		= "select z01_cgccpf from cgm where z01_numcgm = $usuario ";
      $resultcgm = db_query($sqlcgm);
      $linhascgm = pg_num_rows($resultcgm);
   
   }
   if(isset($linhascgm) and $linhascgm > 0){
     db_fieldsmemory($resultcgm,0);
   }
}
	
if((@$imobil==0 && @$iptubase==0) || @$usuario=="") {
?>
   <form name="form1" method="post" <?=$onsubmit?> action="opcoescertidao.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
     <tr>
     	<td colspan=2 align="center"><?=$DB_mens1?></td>
     </tr>	
     <tr>
      <td width="50%" height="30" align="right">
        Matr&iacute;cula do Im&oacute;vel:&nbsp;
      </td>
      <td width="50%" height="30">
       <input name="matricula1" type="text" class="digitacgccpf" id="matricula1" size="10" maxlength="10">
      </td>
     </tr>    
<?
 /*
 *  Se o parametro de configuração do prefeitura on-line no dbportal, na tabela configdbpref, campo chamado w13_exigecpfcnpjmatricula
 *  estiver como true é para exibir os campos cnpj e cpf para o usuário do dbpref  
 */
  $rsParametro = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file($iInstit,"w13_exigecpfcnpjmatricula"));
  $oRetorno    = db_utils::fieldsMemory($rsParametro,0);
  
if ($oRetorno->w13_exigecpfcnpjmatricula == "t") {  
?>
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
<?
}    
?>   
     <tr>
      <td width="50%" height="30">&nbsp;</td>
      <td width="50%" height="30">
         <input  class="botao" type="submit" name="pesquisa" value="Pesquisa" class="botaoconfirma" onClick="return js_valida();">
         <input type="hidden" name="opcao" value="m" >
      </td>
     </tr>
    </table>
  </form>  
<?
} else {
?>
   <a href="certidaoimovel003.php?outro">:: Pesquisar Outro Imóvel ::</a><br><br>
   <table width="350" border="0" cellspacing="0" cellpadding="3" class="texto">
<?  
  //busca clientes do escritório 
  for ( $x=0; $x<$imobil; $x++ ) {
    if ( $x==0 ) {
	?>
     <tr height="20" bgcolor="#eaeaea">
	   <td colspan="3">
		 <b>
		  Matrículas que tenho acesso
	    </b>
	  </td>
	</tr>
	<?
      }
	  db_fieldsmemory($result,$x);
	  if($imobil==1 && $iptubase==0){
		$redireciona = "opcoescertidao.php?".base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=$id_usuario");
		db_redireciona($redireciona);
	  }
	  $sUrlMatric = base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);
	  $sUrlNome   = base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);
      echo "<tr height=\"20\">
              <td>
                <img src=\"imagens/seta.gif\" border=\"0\">
              </td>
              <td align=\"right\">
                <a href=\"opcoescertidao.php?".$sUrlMatric."\">
                  <b>".$j01_matric."</b>
                </a>
              </td>
              <td>
                <a href=\"opcoescertidao.php?".$sUrlNome."\">"
                  .$z01_nome.
               "</a>
              </td>
            </tr>";
    echo "<tr height=\"1\" bgcolor=\"#cccccc\">
            <td colspan=\"3\"></td>
          </tr>";
   }

   // verifica se o usuário não está logado
   //$iLogin = db_getsession ( 'DB_login' );

if( !isset($iLogin) ) {
// condição faz com que entre novamente na tela para o usuário informar o número da matrícula
?>	
   <script>
     document.cookie = 'cookie_codigo_cgm=;';
	 location.href = 'certidaoimovel003.php';
   </script>
<?
}
   //busca dados do issbase
   for($x=0;$x<$iptubase;$x++){ 
	 if($x==0){
?>
	     <tr height="20" bgcolor="<?=$w01_corfundomenu?>"> 
	     <td colspan="3"> <b> Minhas Matrículas </b> </td> 
	     </tr>
<?
     }
	 db_fieldsmemory($result2,$x);
	
	if($imobil==0 && $iptubase==1){
	  $sUrl = base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);
      $redireciona = "opcoescertidao.php?".$sUrl;
    
	  if(!isset($DB_LOGADO)){
        @include($redireciona);
      } else {
        db_redireciona($redireciona);
      }
    
	}
?>
<tr height="20">                                                                                                                                                                                             
  <td>                                                                                                                                                                                                         
    <img src="imagens/seta.gif" border="0">                                                                                                                                                                
  </td>                                                                                                                                                                                                        
  <td align="right">
<?
  $sUrlMatric = base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);
  echo " <a class=\"links\" href=\"opcoescertidao.php?".$sUrlMatric."\"> 
           <b>".$j01_matric."</b> 
         </a> ";                          
?>
  </td>                                                                                                                                                                                                        
  <td>                                                                                                                                                                                                          

<?
  $sUrlNome = base64_encode("matricula1=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=".@$id_usuario);
  echo " <a class=\"links\" href=\"opcoescertidao.php?".$sUrlNome."\"> 
           ".$z01_nome." - ".$proprietario."
         </a> ";
?>
  </td>                                                                                                                                                                                                        
</tr>                                             
<tr height="1" bgcolor="<?=$w01_corfundomenu?>">  
  <td colspan="3"></td>                                                                                                                                                                                                        
</tr>
<?  
   }
?>
</table>
</center>
<?
}
  db_logs("","",0,"Tela da Certidoo por Matricula.");
  if(isset($erroscripts)){
    echo "<script>alert('".$erroscripts."');</script>";
  }
?>
<script>
   js_carregando(false);
</script>
</body>
</html>