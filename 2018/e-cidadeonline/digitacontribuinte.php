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
//include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_cgm_classe.php");
include("classes/db_issbase_classe.php");
$clcgm = new cl_cgm;
$clissbase = new cl_issbase;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
/*$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitacontribuinte.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}*/
//mens_help();
$dblink="index.php";
db_mensagem("contribuinte_cab","contribuinte_rod");
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
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<div id='int_perc1' align="left" style="position:absolute;top:30%;left:35%; float:left; width:200; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:100%; background-color:#eaeaea;" align="center"><img src="imagens/processando.gif" align="center"> Processando...</div>
   </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>
<body leftmargin="0" text="<?=$w01_cortexto?>" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<center>
<br>
<?=$DB_mens1?>
<br><br>
<?
  //verifica se está logado
  if(@$id_usuario!="" ){
   @$usuario = $id_usuario;
   @$result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_cgccpf, cgm.z01_nome, cgm.z01_numcgm","","cgm.z01_numcgm = $usuario"));
   @$linhas  = $clcgm->numrows;
   if(@$linhas!=0){
    db_fieldsmemory($result,0);
    //11 14
    if(strlen($z01_cgccpf)>11){
     $cgc = $z01_cgccpf;
     $cpf = "";
    }else{
     $cgc = "";
     $cpf = $z01_cgccpf;
    }
    
    //$result2 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $usuario and q02_dtbaix is null"));
    $sql2=$clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $usuario and q02_dtbaix is null");
    $result2 = db_query($sql2);
    //echo"$sql2";
    $linhas2 = pg_num_rows($result2);
    if($linhas2>0){
      db_fieldsmemory($result2,0);
    }
    //echo "inc= $q02_inscr";
    ?>
    <table width="60%" border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" class="texto">
     <tr>
      <td width="100%" nowrap height="28" bgcolor="<?=$w01_corfundomenu?>">
       <table width="100%" border="0" cellpadding="1" cellspacing="0" class="texto">
        <tr><td>
         <img src="imagens/icone.gif" border="0">
        </td><td>
         CNPJ/CPF: <span class="bold3"><?=$z01_cgccpf?></span><br>
         Nº Contribuinte / CGM: <span class="bold3"><?=$z01_numcgm?></span><br>
        </td></tr>
       </table>
      </td>
     </tr>
     <tr height="100">
      <td align="center">
      
       <a href="opcoesdebitospendentes.php?inscricao=<?=@$q02_inscr?>&codigo_cgm=<?=$z01_numcgm?>&cpf=<?=$cpf?>&cgc=<?=$cgc?>&opcao=n&id_usuario=<?=$id_usuario?>">Acessar Meus Dados</a><br><br>
       <?//"opcoesdebitospendentes.php?".base64_encode("inscricao=$q02_inscr&cgc=$z01_cgccpf&opcao=i&id_usuario=".@$id_usuario);?>
       
       <a href="digitacontribuinte.php?outro">Acessar Outro Contribuinte</a>
      </td>
     </tr>
    </table>
    <?
   }
  }else{
 ?>
 <form name="form1" method="post" action="opcoesdebitospendentes.php" <?=$onsubmit?>>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
   <?if(@$w13_permconscgm=="t"){?>
   <tr>
    <td width="50%" height="30" align="right">Nº Contribuinte / CGM:&nbsp;</td>
    <td width="50%" height="30"><input name="codigo_cgm" type="text" class="digitacgccpf" id="codigo_cgm" size="10" maxlength="10"></td>
   </tr>
   <?}?>
   <tr>
    <td width="50%" height="30" align="right">CNPJ:&nbsp;</td>
    <td width="50%" height="30"><input name="cgc" type="text" class="digitacgccpf" id="cgc" 
        onChange='js_teclas(event);' 
        onKeyPress="FormataCNPJ(this,event); return js_teclas(event);" size="18" maxlength="18"></td>
   </tr>
   <tr>
    <td width="50%" height="30" align="right">CPF:&nbsp;</td>
    <td width="50%" height="30"><input name="cpf" type="text" class="digitacgccpf" id="cpf" 
        onChange='js_teclas(event);'
        onKeyPress="FormataCPF(this,event); return js_teclas(event);" size="14" maxlength="14"></td>
   </tr>
   <tr>
    <td width="50%" height="30">&nbsp;</td>
    <td width="50%" height="30">
     <input type="hidden" name="opcao" value="n">
     <input class="botao" type="submit" name="pesquisa" value="Pesquisa" class="botaoconfirma">
    </td>
   </tr>
  </table>
 </form>
 <?}?>
<?
if(isset($funcao)){
  echo "<script>alert('Código identificador dever ser preenchido')</script>";
}
db_logs("","",0,"Digita Codigo do Contribuinte.");
if(isset($erroscripts)){
  echo "<script>
         alert('".$erroscripts."');
         location='digitacontribuinte.php?outro';
        </script>";
}
?>
</center>
<script>
  document.getElementById('int_perc1').style.visibility='hidden';
</script>