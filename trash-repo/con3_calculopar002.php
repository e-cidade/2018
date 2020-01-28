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
include("classes/db_edital_classe.php");
include("classes/db_editalrua_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contricalc_classe.php");
include("dbforms/db_funcoes.php");
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clrotulo = new rotulocampo;
$cledital = new cl_edital;
$cleditalrua = new cl_editalrua;
$clrotulo->label("d02_contri");
$clrotulo->label("d02_autori");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$db_opcao = 1;
$db_botao = true;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($HTTP_POST_VARS);

if(isset($confirmar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcontricalc->fc_calculocontr($d02_contri,$j01_matric,$parcelas,$privenc_ano,$privenc_mes,$privenc_dia,$provenc);
  $erro = $clcontricalc->erro_msg;
  db_fim_transacao($sqlerro);
}

if(isset($contri)){

  $result=$clcontrib->sql_record($clcontrib->sql_query($contri,"","d07_matric,z01_nome,d07_contri"));
  $numrows=$clcontrib->numrows;
  $d02_contri=$contri;
  $result01=$cleditalrua->sql_record($cleditalrua->sql_query($d02_contri,"d02_codedi,j14_nome","d02_codedi limit 1"));
  db_fieldsmemory($result01,0);
  $result02=$cledital->sql_record($cledital->sql_query_file($d02_codedi,"d01_privenc as privenc,d01_numtot as parcelas"));
  db_fieldsmemory($result02,0);
  $provenc=$privenc_dia;
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
  
  dia=document.form1.privenc_dia.value;
  mes=document.form1.privenc_mes.value;
  ano=document.form1.privenc_ano.value;
  
  parce=document.form1.parcelas;
  num= new Number(parce.value);
  if(isNaN(num) || num==""){
    alert("Numero de parcelas inválido.");  
    parce.focus();
    return false;
  }
  provenc=document.form1.provenc;
  num= new Number(provenc.value);
  if(isNaN(num) || num>31 || num==""){
    alert("Dia inválido.");  
    provenc.focus();
    return false;
  }
  
  obj=document.form1.matriculas;
  matric=document.form1.j01_matric.value;
  for(i=0; i<obj.options.length; i++){
   
    if(obj.options[i].value==matric && (obj.options[i].style.backgroundColor.substr(4,3) == "102" || obj.options[i].style.backgroundColor=="#669900")){
      if(!confirm("Deseja recalcular esta matricula?")){
        return false;
	break;
      }
    }  
  }  
  return  js_VerDaTa("privenc_dia",dia,mes,ano);  
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
  location.href="con3_calculopar001.php";
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
      <td nowrap title="Numero de parcelas">
      <b>Parcelas</b>
      </td>
      <td> 
<?
  db_input('parcelas',4,5,true,'text',1);
?>
      </td>
    </tr>
    <tr>
      <td nowrap title="Data do primeiro vencimento">
       <b>1° Vencimento</b>
      </td>  
      <td nowrap title="Data do primeiro vencimento">
     <?
     db_inputdata('privenc',@$privenc_dia,@$privenc_mes,@$privenc_ano,true,'text',$db_opcao,"");
   ?>  
      </td>
    </tr>  
    <tr>
      <td nowrap title="Dia dos próximos vencimentos">
        <b>Dia vencimento</b>
      </td>  
      <td nowrap title="Dia dos próximos vencimentos">
    <?
     db_input('provenc',4,0,'true','text',$db_opcao,"")
   ?>  
      </td>
    </tr>  
    <tr>
      <td colspan="2" align="center">
      <br>
	  <input name="confirmar" type="submit" id="confirmar" value="Confirmar"  onclick="return js_confirmar()">
	  <input name="volar" type="button" value="Voltar"  onclick="js_voltar();">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="left">
      <fieldset><Legend><b>Matrículas da contribuição</b></legend>
      <select name="matriculas" size="8" onchange="js_troca(this)">
       <?
         for($i=0; $i<$numrows; $i++){
	   db_fieldsmemory($result,$i);
           $resu=$clcontricalc->sql_record($clcontricalc->sql_query_file(null,"d09_contri",null," q09_contri = $d07_contri and q09_matric = $d07_matric"));
	   $cor="";
	   if($clcontricalc->numrows>0){
	     $cor="style='background-color:#669900; '";
	   }else{
	     if($i%2==0){
	       $cor="style='background-color:#D7CC06 ;'";
	       }else{
	       $cor="style='background-color:#F8EC07 ;'";
	      }  
	     
	   }   
             echo "<option $cor value='$d07_matric'>$d07_matric-$z01_nome</option>";
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
}
?>