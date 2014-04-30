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
include("classes/db_contlot_classe.php");
$cliptubase = new cl_iptubase;
$clcontlot = new cl_contlot;
$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("d02_contri");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

   
if(isset($j01_matric) && $j01_matric!=""){
   $result01 = $cliptubase->sql_record($cliptubase->sql_query($j01_matric,"j01_idbql,z01_nome"));
   if($cliptubase->numrows>0){
     db_fieldsmemory($result01,0);
     $result02=$clcontlot->sql_record($clcontlot->sql_query("",$j01_idbql,"d05_contri,d02_codedi,d01_descr"));
     $numrows02=$clcontlot->numrows;
     if($numrows02>0){
         
     }else{  
       db_redireciona("con3_conscontri001.php?matric=false&j01_matric=$j01_matric"); 
     }
   }else{
     db_redireciona("con3_conscontri001.php?matric=false&j01_matric=$j01_matric"); 
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
<script>
function js_consultar(obj){
  if(document.form1.d02_contri.value==""){
    alert('Selecione uma contribuição.');
    return;
  }  
  js_OpenJanelaIframe('top.corpo','db_iframe','con3_conscontri011.php?contri='+document.form1.d02_contri.value+'&cod_matricula=<?=$j01_matric?>','Pesquisa',true);
}  
function js_trocacontri(obj){
    contri=document.form1.contribs.value;
    document.form1.d02_contri.value=contri;  
    obj=document.getElementById('edital_'+contri);
    matriz=obj.value.split("XX"); 
    str="<b>Edital numero "+matriz[0]+" "+matriz[1]+"</b>";
    document.getElementById('edital').innerHTML=str;    
}  
function js_voltar(){
  location.href="con3_conscontri001.php";  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<br>
    <center>
    <form name="form1" method="post" action="">
    <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td nowrap title="<?=@$Td02_contri?>">
      <?=$Ld02_contri?>
      </td>
      <td> 
  <?
  db_input('d02_contri',8,$Id02_contri,true,'text',3);
  ?>
      </td>
    </tr>
      <tr> 
        <td>     
        <?=$Lj01_matric?>
        </td>
        <td>
<?
  db_input('j01_matric',6,0,true,'text',3);
  db_input('z01_nome',40,0,true,'text',3,"","z01_nome");
?>
        </td>
      </tr>
      <tr>
	  <td valign="top" colspan="2" align="center">
	<br>
        <table border="1">
	  <tr>
	    <td id="edital" colspan="2">
	    </td>
	  </tr>
          <tr>
           <td align="center"><b>Contribuições</b></td>
           <td align="center"><b>Matrículas</b> </td>
   	  </tr>  
	  <tr>
	   <td valign="top"  align="center" >
            <select name="contribs" size="4" onclick="js_trocacontri(this)" >
            <?
            for($i=0; $i<$numrows02; $i++){
	     db_fieldsmemory($result02,$i);
	     if($i%2==0){
  	      $cor="style='background-color:#D7CC06 ;'";
  	     }else{
 	       $cor="style='background-color:#F8EC07 ;'";
	     }  
             echo "<option $cor value='$d05_contri'>$d05_contri</option>";
	   }   
           echo "</select>";
           ?>
  	    </td>
	    <td>
	      <select name="matriculas" size="2">
                <option style='background-color:#F8EC07 ;' value='<?=$j01_matric?>'><?=$j01_matric?>-<?=$z01_nome?></option>";
	      </select>  
	    </td>
	  </tr>  
	</table> 
        </td>	
      </tr>
      <tr>
        <td colspan="2"   height="25" align="center">
  <?
  $consultar="Consultar";
  db_input("consultar",6,0,true,'button',$db_opcao,"onClick='js_consultar();'");
  $voltar="Voltar";
  db_input("voltar",6,0,true,'button',$db_opcao,"onClick='js_voltar();'")
  ?>
	</td>
      </tr>
      <tr>
        <td>
            <?
            for($i=0; $i<$numrows02; $i++){
	     db_fieldsmemory($result02,$i);
	     $x="edital_".$d05_contri;
	     $$x=$d02_codedi."XX".$d01_descr;
             db_input("edital_$d05_contri",6,0,true,'hidden',1);
	   }   
           ?>
	</td>
      </tr>
    </table>
    </form>
    </center>
    </td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      if($numrows02==1){
	echo "<script>document.form1.d02_contri.value=$d02_contri;</script>";
      }
    ?>
</body>
</html>