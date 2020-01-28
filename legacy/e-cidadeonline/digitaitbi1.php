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
if(isset($outro)){
 setcookie("cookie_codigo_cgm");
 header("location:digitacontribuinte.php");
}
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_cgm_classe.php");
include("classes/db_iptubase_classe.php");
$clcgm = new cl_cgm;
$cliptubase  = new cl_iptubase;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
/*$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitaitbi.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}*/
mens_help();
db_mensagem("itbi_cab","itbi_rod");
$db_verificaip = db_verifica_ip();
if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";
}else{
  $onsubmit = "";
}  
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center"><img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>
<center><br><br>
<?
  //verifica se está logado
  if($id_usuario!="" || $_COOKIE["cookie_codigo_cgm"]!=""){
   $usuario = $id_usuario==""?$_COOKIE["cookie_codigo_cgm"]:$id_usuario;
   $result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_cgccpf, cgm.z01_nome, cgm.z01_numcgm","","cgm.z01_numcgm = $usuario"));
   $linhas  = $clcgm->numrows;
   if($linhas!=0){
    db_fieldsmemory($result,0);
    //11 14
    if(strlen($z01_cgccpf)>11){
     $cgc = $z01_cgccpf;
     $cpf = "";
    }else{
     $cgc = "";
     $cpf = $z01_cgccpf;
    }
    
    // iptu
    $result2  = $cliptubase->sql_record($cliptubase->sql_query("","*","","cgm.z01_numcgm = $z01_numcgm"));
    $linhas2  = $cliptubase->numrows;
    ?>
    <table width="60%" border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" class="texto">
     <tr>
      <td width="100%" nowrap height="28" bgcolor="<?=$w01_corfundomenu?>">
       <table width="100%" border="0" cellpadding="1" cellspacing="0" class="texto">
        <tr><td>
         <img src="imagens/icone.gif" border="0">
        </td><td>
         CNPJ/CPF: <span class="bold3"><?=$z01_cgccpf?></span><br>
         Numcgm:&nbsp;&nbsp;&nbsp; <span class="bold3"><?=$z01_numcgm?></span><br>
        </td></tr>
       </table>
      </td>
     </tr>
     <tr bgcolor="#eaeaea">
      <td>
       <b>Minhas Matrículas</b>
      </td>
     </tr>
     <?
     for($i=0;$i<$linhas2;$i++){
      db_fieldsmemory($result2,$i);
     ?>
     <tr height="30">
      <td align="center">
       <b><a href="opcoesitbi.php?matricula1=<?=$j01_matric?>&codigo_cgm=<?=$z01_numcgm?>&cpf=<?=$cpf?>&cgc=<?=$cgc?>&opcao=mi&id_usuario=<?=$id_usuario?>"><?=$j01_matric?> - <?=$z01_ender?> <?=$z01_numero?></a></b>
      </td>
     </tr>
     <?}?>
     <tr height="30">
      <td align="center">
       <a href="digitaitbi.php?outro">Acessar Outro Contribuinte</a>
      </td>
     </tr>
    </table>
    <?
   }
  }else{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
  <tr>
    <td height="60" align="<?=$DB_align1?>">
      <?=$DB_mens1?>
    </td>
  </tr>
  <tr>
    <td height="200" align="center" valign="middle">
      <form name="form1" method="post" <?=$onsubmit?> action="opcoesitbi.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
          <tr>
            <td width="50%" height="30" align="right">Matr&iacute;cula
              Im&oacute;vel:&nbsp; </td>
            <td width="50%" height="30"> <input name="matricula1" type="text" id="matricula1" size="10" maxlength="10">
            </td>
          </tr>
          <tr>
            <td width="50%" height="30" align="right">CNPJ:&nbsp;
              </td>
            <td width="50%" height="30"> <input name="cgc" type="text" id="cgc" size="18" maxlength="18" onKeyDown="FormataCNPJ(this,event)">
            </td>
          </tr>
          <tr>
            <td width="50%" height="30" align="right">CPF:&nbsp;
              </td>
            <td width="50%" height="30"> <input name="cpf" type="text" id="cpf" size="14" maxlength="14" onKeyDown="FormataCPF(this,event)">
            </td>
          </tr>
          <tr>
            <td width="50%" height="30">&nbsp;</td>
            <td width="50%" height="30"> <input type="hidden" name="opcao" value="mi" >
              <input type="submit" class="botao" name="pesquisa" value="Pesquisa" class="botaoconfirma">
            </td>
          </tr>
        </table>
      </form>

    </td>
  </tr>
  <tr>
    <td height="60" align="<?=$DB_align2?>">
      <?=$DB_mens2?>
    </td>
  </tr>
</table>
<?}?>
</center>
<?
db_logs("","",0,"Digita Codigo da Matricula do ITBI.");
if(isset($erroscripts)){
  echo "<script>alert('".$erroscripts."');</script>";
}
?>
<script>
  document.getElementById('int_perc1').style.visibility='hidden';
</script>