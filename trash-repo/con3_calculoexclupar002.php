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
include("classes/db_contrib_classe.php");
include("classes/db_editalrua_classe.php");
include("classes/db_contricalc_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arreold_classe.php");
include("dbforms/db_funcoes.php");
$clcontrib = new cl_contrib;
$cleditalrua = new cl_editalrua;
$clcontricalc = new cl_contricalc;
$clrotulo = new rotulocampo;
$clarrecad = new cl_arrecad;
$clarreold = new cl_arreold;
$clrotulo->label("d02_contri");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$db_opcao = 1;
$db_botao = true;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($confirmar)){
  $sqlerro=false;
   $result=$clcontricalc->sql_record($clcontricalc->sql_query(null,"d09_numpre",null," d09_contri = $d02_contri and d09_matric = $j01_matric"));
   db_fieldsmemory($result,0); 
   $result=pg_query("select arrecad.k00_numpre from arrecant inner join arrecad on arrecad.k00_numpre=arrecant.k00_numpre where arrecant.k00_numpre=$d09_numpre");
   if(pg_numrows($result)>0){
     die("ja foi pago");
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
if($contri){
  $result=$clcontrib->sql_record($clcontrib->sql_query($contri,"","d07_matric,z01_nome,d07_contri"));
  $numrows=$clcontrib->numrows;
  $d02_contri=$contri;
  $result01=$cleditalrua->sql_record($cleditalrua->sql_query($d02_contri,"j14_nome"));
  db_fieldsmemory($result01,0);
  
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
}
function js_troca(obj){
  for(i=0; i<obj.options.length; i++){
    if(obj.options[i].value==obj.value){
      var arr=obj.options[i].text.split("-");
       document.form1.z01_nome_matric.value=arr[1];
       document.form1.j01_matric.value=arr[0];
    }
  }
}  
function js_voltar(){
  location.href="con3_calculoexclupar001.php";
}
  </script>


  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
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
      <br>
	  <input name="confirmar" type="submit" id="confirmar" value="Confirmar Exclusão"  onclick="return js_confirmar()">
	  <input name="volar" type="button" value="Voltar"  onclick="js_voltar();">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <fieldset><Legend><b>Matrículas calculadas da contribuição</b></legend>
      <select name="matriculas" size="8" onchange="js_troca(this)">
       <?
         $dd="";
	 $tem="nops";
         for($i=0; $i<$numrows; $i++){
	   db_fieldsmemory($result,$i);
           $resu=$clcontricalc->sql_record($clcontricalc->sql_query_file(null,"d09_contri",null,"d09_contri = $d07_contri and d09_matric = $d07_matric"));
	   $cor="";
	   $numcalc=$clcontricalc->numrows;
	   if($numcalc>0){
	     if($dd=="ok"){
	       $cor="style='background-color:#669900;'";
	       $dd="";
	     }else{
	       $dd="ok";
	       $cor="style='background-color:#459900;'";
	     }  
             echo "<option $cor value='$d07_matric'>$d07_matric-$z01_nome</option>";
	     $tem="ok";
	   }   
	 } 
       ?>
      </select><br>
      </fieldset> 
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
   if($tem=="nops"){
     db_redireciona("con3_calculoexclupar001.php");
   }
}
?>