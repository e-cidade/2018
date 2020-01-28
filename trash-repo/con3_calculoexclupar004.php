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
include("classes/db_editalrua_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arreold_classe.php");
include("classes/db_ruas_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contricalc_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$clruas = new cl_ruas;
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clarrecad = new cl_arrecad;
$clarreold = new cl_arreold;
$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label("d02_autori");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$db_opcao = 1;
$db_botao = true;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($confirmar)){
  $sqlerro=false;
   $result=$clcontricalc->sql_record($clcontricalc->sql_query(null,"d09_nupre",null,"d09_contri = $d02_contri and d09_matric = $j01_matric"));
   db_fieldsmemory($result,0); 
   $result=pg_query("select arrecad.k00_numpre from arrecant inner join arrecad on arrecad.k00_numpre=arrecant.k00_numpre where arrecant.k00_numpre=$d09_numpre");
   if(pg_numrows($result)>0){
     db_msgbox("ja foi pago");
   }else{
     db_inicio_transacao();
     $clcontricalc->excluir_arrecad($d09_numpre);
     if($clcontricalc->erro_status=="0"){
        $erro=$clcontricalc->erro_msg; 
        $sqlerro=true; 	
	return;
     }
     $clcontricalc->d09_contri=$d02_contri ;
     $clcontricalc->d09_matric=$j01_matric;
     $clcontricalc->excluir(null,"d09_contri = $d02_contri and d09_matric = $j01_matric");
     if($clcontricalc->erro_status==0){ 
       $falhou="ok";
       $sqlerro=true;
     }
     $erro=$clcontricalc->erro_msg; 
    db_fim_transacao($sqlerro);
    $j01_matric="";
    $z01_nome_matric="";
   }
 
}
if(isset($codigo)){
   $result=$clcontrib->sql_record($clcontrib->sql_query("","","distinct(d07_contri)","","editalrua.d02_autori='t' and editalrua.d02_codigo=$codigo"));
   $resultrua=$clruas->sql_record($clruas->sql_query($codigo,"j14_nome"));
   db_fieldsmemory($resultrua,0);
   $numrows=$clcontrib->numrows;
   
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_confirmar(){
  if(document.form1.j01_matric.value==""){
    alert("Selecione uma matricula.");
    return false;
  }  
  if(document.form1.d02_contri.value==""){
    alert("Selecione uma contribuição.");
    return false;
  }  
}
function js_troca(obj){
  document.form1.d02_contri.value=document.form1.contribs.value;  
  for(i=0; i<obj.options.length; i++){
    if(obj.options[i].value==obj.value){
      var arr=obj.options[i].text.split("-");
       document.form1.z01_nome_matric.value=arr[1];
       document.form1.j01_matric.value=arr[0];
    }
  }
}  
function js_trocacontri(obj){
    document.form1.submit();
}   
function js_voltar(){
  location.href="con3_calculoexclupar001.php";
}
  </script>


  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post" action="">
 <tr> 
 <td height="430" align="rigth" valign="top" bgcolor="#CCCCCC"> 
   <input name='contrisel' type="hidden">
  <center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Td02_contri?>">
      <?=$Ld02_contri?>
      </td>
      <td> 
  <?
  db_input('d02_contri',6,$Id02_contri,true,'text',3);
  db_input('j14_nome',50,$Ij14_nome,true,'text',3);
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
  db_input('z01_nome',50,0,true,'text',3,"","z01_nome_matric");
?>
          </td>
        </tr>
    <tr>
      <td colspan="2" align="center">
	  <input name="confirmar" type="submit" id="confirmar" value="Confirmar"  onclick="return js_confirmar()">
	  <input name="volar" type="button" value="Voltar"  onclick="js_voltar()">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="left">
      <br>
      <b>Contribuições da rua <?=@$j14_nome?></b>
      <select name="contribs" size="" onchange="js_trocacontri(this)">
       <?
         $pr="";
         $pri="";
         for($i=0; $i<$numrows; $i++){
	   db_fieldsmemory($result,$i);
           $resu=$clcontricalc->sql_record($clcontricalc->sql_query_file(null,"d09_contri",null,"d09_contri = $d07_contri"));
	   if($clcontricalc->numrows>0){
	     $pr=($pr==""?$d07_contri:$pr);
             echo "<option ".(isset($contribs)&& $contribs==$d07_contri?"selected":"")." value='$d07_contri'>$d07_contri</option>";
             if($pri==""){ 
  	       if($contribs==$d07_contri){
	        $pri="ok";	 
	       }
	     }  
	   } 
         } 
	 if(!isset($contribs)){
	   $contribs=$pr;
	 }else{
	   if($pri!="ok"){
  	     $contribs=$pr;
	     if($pr!=""){
	       echo "
	       <script>document.form1.d02_contri.value=$pr;</script>
	       ";
	     } 
	   }
	 }  
       ?>
      </select>
      <br>
	<select name="matriculas" size="10"  onClick="js_troca(this)">
       <?
         $resulta=$clcontrib->sql_record($clcontrib->sql_query("","","d07_matric,z01_nome","","d07_contri=$contribs"));
	   $num=$clcontrib->numrows;
	   for($x=0; $x<$num; $x++){
	     db_fieldsmemory($resulta,$x);
	     $resu=$clcontricalc->sql_record($clcontricalc->sql_query_file(null,"d09_contri",null,"d09_contri = $contribs and d09_matric = $d07_matric"));

	   $cor="";
	   if($clcontricalc->numrows>0){
	     $cor="style='background-color:#669900; '";
             echo "<option $cor value='$d07_matric'>$d07_matric-$z01_nome</option>";
	     
	   }
         } 
       ?>
      </select><br>
      </td>
    <tr>
  </table>
  </center>
 </td>
 </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($confirmar)){
   db_msgbox($erro); 
   if($pr==""){
      db_redireciona("con3_calculoexclupar001.php");
   }
    
}
?>