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
 header("location:digitaaidof.php");
}

// digitaaidof -> opcoesaidof -> aidof.php(relatorio)
// obs: tirei fora o pesquisagrafica.php

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_issbase_classe.php");
$clissbase = new cl_issbase;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$cgm= @$_SESSION["CGM"];
if($cgm!=""){ //  se tiver logado
	$sqlgra= "select z01_nome,z01_numcgm from cgm inner join graficas on y20_grafica = z01_numcgm where z01_numcgm=$cgm order by z01_nome ";
	//die($sqlgra);
	$resultgra= db_query($sqlgra);
	$linhagra=pg_num_rows($resultgra);
	if ($linhagra>0){
		msgbox("é grafica");
		echo"<script> location.href='aidof_grafica.php?cgm=$cgm';</script>";
	}else{
		msgbox("não é grafica");
	}
}



/*
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitaaidof.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
*/

mens_help();
db_mensagem("aidof_cab","aidof_rod");

$db_verificaip = db_verifica_ip();
if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),'');\"";
}else{
  $onsubmit = "";
}

//verifica se está logado

if(@$id_usuario!="" || @$_COOKIE["cookie_codigo_cgm"]!=""){
 if(@$id_usuario=="")$id_usuario = $_COOKIE["cookie_codigo_cgm"];
 $result  = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $id_usuario"));
 $linhas  = $clissbase->numrows;
 if($linhas!=0){
  db_fieldsmemory($result,0);
  //11 14
  if(strlen($z01_cgccpf)>11){
   //armazena em var
   $var_cnpj = $z01_cgccpf;
  }
 }
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
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?mens_div();?>
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
  <tr>
    <td height="60" align="<?=$DB_align1?>">
      <?=$DB_mens1?>
    </td>
  </tr>
  <tr>
    <td height="200" align="center" valign="middle">
    <form name="form1" method="post" <?=$onsubmit?> action="aidof_menu.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
          <tr>
            <td width="50%" height="30" align="right">
             Inscri&ccedil;&atilde;o Alvar&aacute;:&nbsp;
            </td>
            <td width="50%" height="30">
             <input name="inscricaow" type="text" class="digitacgccpf" value="<?=@$q02_inscr?>" size="8" maxlength="6">
            </td>
          </tr>
          <tr>
            <td width="50%" height="30" align="right">
             CNPJ:&nbsp;
            </td>
            <td width="50%" height="30">
             <input name="cgc" type="text" class="digitacgccpf" id="cgc" value="<?=@$var_cnpj?>" size="18" maxlength="18" 
                    onKeyPress="FormataCNPJ(this,event); return js_teclas(event);">
            </td>
          </tr>
          <tr>
            <td width="50%" height="30">&nbsp;</td>
            <td width="50%" height="30">
             <input class="botao" type="submit" name="pesquisa" value="Pesquisa" class="botaoconfirma">
             <input type="hidden" name="opcao" value="i" ><br><br>
             <a align="center" href="digitaaidof.php?outro">Pesquisar Outra Inscrição</a><br><br>
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
</center>
<?
db_logs("","",0,"Digita Codigo da Inscricao para solicitacao AIDOF.");
if(isset($erroscripts)){
  echo "<script>alert('".$erroscripts."');</script>";
}