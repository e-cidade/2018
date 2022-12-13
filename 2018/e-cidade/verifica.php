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
include("dbforms/db_funcoes.php");
include("classes/db_iptubase_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$ex=false;

if(isset($excluir)){
    echo "
      <script>
        function js_mandamatric(){
	  parent.js_selexclui('$j01_matric','$valormatr');
	  location.href='verifica.php?liber=ok';
	}

      js_mandamatric();
	
      </script>
     "; 
  
}else if(isset($adicionar)){
  $result = $cliptubase->sql_record($cliptubase->sql_query($j01_matric,"j01_matric","","j01_matric=$j01_matric"));
  $nurows=$cliptubase->numrows;
  @db_fieldsmemory($result,0);
  
  if($nurows!=0){
    echo "
      <script>
        function js_mandamatric(){
	  
	  parent.selmatric('$j01_matric');
	}

      js_mandamatric();
	
      </script>
    
    
    
    ";
  }else{
    echo "
      <script>
         parent.alert(\"Matrícula Inválida!\");
	
      </script>
    
    
    
    
    ";
    
  }
  $j01_matric="";
}else if(isset($matricu)){
  
$j01_matric=$matricu;  
$ex=true;  


}
	      
?>

<script>
function js_excluirmatri(matricu,valormatr){
  document.form1.matricu.value=matricu;
  document.form1.valormatr.value=valormatr;
 document.form1.submit(); 
}
function js_liberasel(){
   parent.document.form1.selmassafalida.disabled=true; 
   return true;
}
</script>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<body>
<form name="form1" method="post" >
  <table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
    <input type="hidden" name="matricu" value="<?=@$matricu?>">
    <input type="hidden" name="valormatr" value="<?=@$valormatr?>">
    <?
    if($ex){
    ?>    
<?
 db_input('j01_matric',9,$Ij01_matric,true,'text',3,'')
?>
    </td>
  </tr>
  <tr>
    <td align="left" height="100" valign="top">
      <input name="excluir" type="submit" value="Excluir" id="excluir" onclick="return js_liberasel()" >
    <?
    }else{
    ?>
<?
 db_input('j01_matric',9,$Ij01_matric,true,'text',1,'')
?>
    </td>
  </tr>
  <tr>
    <td align="left" height="100" valign="top">
	<input name="adicionar" type="submit" value="Adicionar" <?=($db_botao==false?"disabled":"")?> >            
    <?
    }
    ?>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?
if(isset($matricu)||isset($liber)){
    echo "
      <script>
        function js_libera(){
	  parent.document.form1.selmassafalida.disabled=false; 
	}
        js_libera();
      </script>
     "; 
}
?>