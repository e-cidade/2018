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

@$id = $_SESSION["id"];

if(isset($processar)){

  if($cpf_cnpj!=""){
    if(($cpf_cnpj=="00000000000")||($cpf_cnpj=="00000000000000")){
      db_msgbox("Informe Um CPF/CNPJ válido");
    }else{
      $tam = strlen($cpf_cnpj);
      if($tam==14){
        $pessoa ='J';
      }else{
        $pessoa ='F';
      }

      $sqlcpf= "select  z01_cgccpf, q55_usuario, z01_sequencial as cgm, q55_sequencial as dbprefempresa
			from dbprefcgm 
			inner join dbprefempresa on z01_sequencial =q55_dbprefcgm 
			where z01_cgccpf = '".$cpf_cnpj."'";
      //die($sqlcpf);
      $resultcpf= db_query($sqlcpf);
      $linhascpf= pg_num_rows($resultcpf);
      if($linhascpf>0){
        db_fieldsmemory($resultcpf,0);

        if($q55_usuario == $id){
          //alterar
          session_register("dbprefcgm");
          session_register("dbprefempresa");
          $_SESSION["dbprefcgm"] = $cgm;
          $_SESSION["dbprefempresa"] = $dbprefempresa;
          echo"<script> location.href='cadempresaaba01.php?pessoa=$pessoa&cpf_cnpj=$cpf_cnpj&opcao=2';</script>";
        }else{
          db_msgbox("Empresa ja cadastrada por outro usuário");
        }
      }else{
        session_register("dbprefcgm");
        session_register("dbprefempresa");
        $_SESSION["dbprefcgm"] = "";
        $_SESSION["dbprefempresa"] = "";
        echo"<script> location.href='cadempresaaba01.php?pessoa=$pessoa&cpf_cnpj=$cpf_cnpj&opcao=1';</script>";
      }
    }
  }else{
    db_msgbox("Informe o CPF/CNPJ.");
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
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	bgcolor="<?=$w01_corbody?>">
<center><br>
<br>
<br>
<br>
<form name="form1" method="post" action="">
<table width="350px" border="0" cellspacing="0" cellpadding="0"
	class="texto">
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="titulo">CADASTRO DE
		EMPRESAS/PROFISSIONAIS AUTÔNOMOS</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<!--  
	<tr>
		<td>Pessoa:</td>
		<td><select name="pessoa">
			<option value="F">Física</option>
			<option value="J">Juridica</option>
		</select></td>
	</tr>-->
	<tr>
		<td>CPF/CNPJ:</td>
		<td>
    <input name="cpf_cnpj" type="text" value="" size="18" maxlength="18"
           onChange='js_teclas(event);'
           onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);'> 
    <input class="botao" type="submit" name="processar" value="Processar"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input class="botao" type="button"
			name="lista" value="Lista registros cadastrados"
			onclick="js_lista();"></td>
	</tr>
</table>
</form>
</center>
</body>
</html>

<script>
function js_valida(obj){ 
  if (!js_verificaCGCCPF(obj)){
    obj.value = '';
    obj.focus();  
  }
}
function js_lista(){
var id = <?=$id?>;
js_OpenJanelaIframe('',
                    'db_iframe_dbprefcgm',
                    'func_dbprefcgm.php?id='+id+'&funcao_js=parent.js_mostradbprefcgm|z01_cgccpf','Pesquisa',true);
}

function js_mostradbprefcgm(cpf){
    db_iframe_dbprefcgm.hide();
	document.form1.cpf_cnpj.value=cpf;
	document.form1.processar.click();
}

</script>