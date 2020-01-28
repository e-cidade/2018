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
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_levanta_classe.php");
$clissbase = new cl_issbase;
$clcgm = new cl_cgm;
$cllevanta = new cl_levanta;
$clrotulo = new rotulocampo;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$db_botao=true;
$db_opcao=2;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
$clrotulo->label("y60_codlev");
$clrotulo->label("y60_contato");
  if(isset($y60_codlev)){
    $cllevanta->sql_record($cllevanta->sql_query_file($y60_codlev));
    if($cllevanta->numrows>0){
      db_redireciona("fis4_levanta004.php?db_opcao=2&y60_codlev=$y60_codlev");
    }else{
      $msgerro='Código de levantamento inválido.';
    }
  }else if(isset($q02_inscr) && $q02_inscr!=""){
    $result=$clissbase->sql_record($clissbase->sql_query($q02_inscr,'z01_nome'));   
    if($clissbase->numrows>0){
      db_fieldsmemory($result,0);
      db_redireciona("fis4_levanta004.php?db_opcao=2&q02_inscr$z01_nuncgm&z01_nome=$z01_nome");
    }else{
        $msgerro='Inscrição inválida.';
    }
  }else if(isset($z01_numcgm) && $z01_numcgm!=''){
    $sql01=$clissbase->sql_query_file('','q02_inscr,q02_fanta','',"q02_numcgm=$z01_numcgm");
    $result=$clissbase->sql_record($sql01);
    $numrows=$clissbase->numrows;
    if($numrows==0){   //quando não tiver inscrição para o z01_numcgm informado
	$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
	$numrows01=$clcgm->numrows;
	if($numrows01==0){
	  $msgerro='Numcgm inválido.';
	}else if($numrows01==1){  //quando o z01_numcgm for válido
	  db_redireciona("fis4_levanta004.php?db_opcao=2&z01_numcgm=$z01_nuncgm&z01_nome=$z01_nome");
	} 	
    }else if($numrows==1){  // uma inscrição para o z01_numcgm
          db_fieldsmemory($result,0);
  	  $result01=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
          db_fieldsmemory($result01,0);
	  db_redireciona("fis4_levanta004.php?db_opcao=2&q02_inscr$q02_inscr&z01_nome=$z01_nome");
    }else if($numrows>1){   // varias inscrições para o z01_numcgm
	 $result=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
	 db_fieldsmemory($result,0);
	 $varias_inscr=true;
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.q02_inscr.focus();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<script>
function js_testacamp(){
  var inscr = document.form1.q02_inscr.value;
  var numcgm = document.form1.z01_numcgm.value;
  var codlev = document.form1.y60_codlev.value;
  if(inscr=="" && numcgm=="" && codlev==''){
    alert("Informe um campo para pesquisa!");   
    return false;  
    
  }
  
return true;  
}   
</script>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
  <form name="form1" method="post" action=""  onSubmit="return js_verifica_campos_digitados();" >
   <table border="0" cellspacing="0" cellpadding="0">
   <br>
   <br>
<?
if(isset($varias_inscr) || isset($filtroquery)){//quando tiver várias inscrições para um cgm
?>
  <tr>
    <td>
      <?=$Lz01_numcgm?>
    </td>
    <td>
    <?
    if(empty($z01_numcgmx)){
      $z01_numcgmx=$z01_numcgm;
    }  
  db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3,"","z01_numcgmx");
  db_input('q02_inscr',6,0,true,'hidden',3);
      ?>
    </td>
  </tr>  
  <tr>  
    <td> 
      <?=$Lz01_nome?>
    </td>
    <td>
    <?
  db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td colspan='2'>
<?
   if(empty($sql01)){
     $sql01="";
   }
   db_lovrot($sql01,15,"()","","js_retorna|q02_inscr");

?>
       </td>   	 
     </tr>	 
     <tr>
       <td  colspan="2" align="center">
          <input type="submit" name="entrar" value="Entrar">
          <input type="button" name="voltar" value="Voltar" onclick="js_voltar();">
       </td>
     </tr>  
<script>     
  function js_retorna(isncr){
    document.form1.q02_inscr.value=isncr;
    document.form1.submit();
  }
  function js_voltar(){
    location.href="<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>";
  }
</script>     
<?
}else{
?>   
     <tr>   
       <td title="<?=$Tq02_inscr?>">
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
      db_input('z01_nome',40,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>
     <tr>   
      <td title="<?=$Tz01_numcgm?>">
      <?
       db_ancora($Lz01_nome,' js_cgm(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
  <tr>
    <td nowrap title="<?=@$Ty60_codlev?>">
       <?
       db_ancora(@$Ly60_codlev,"js_pesquisay60_codlev(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y60_codlev',5,$Iy60_codlev,true,'text',$db_opcao," onchange='js_pesquisay60_codlev(false);'")
?>
       <?
db_input('y60_contato',40,$Iy60_contato,true,'text',3,'')
       ?>
    </td>
  </tr>
     <tr>
       <td colspan="2" align="center">
     <br>
	   <input type="submit" name="entrar" value="Entrar" onclick="return js_testacamp()" >
       </td>   	 
     </tr>	 
<?
}
?>   
    </table> 	 
  </form>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisay60_codlev(mostra){
  var codlev=document.form1.y60_codlev.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_levanta','func_levanta.php?funcao_js=parent.js_mostralevanta1|y60_codlev|y60_contato','Pesquisa',true);
  }else{
    if(codlev==''){
      document.form1.y60_contato.value='';
    }else{  
      js_OpenJanelaIframe('top.corpo','db_iframe_levanta','func_levanta.php?pesquisa_chave='+codlev+'&funcao_js=parent.js_mostralevanta','Pesquisa',false);
    }      
  }
}
function js_mostralevanta(chave,erro){
  document.form1.y60_contato.value = chave; 
  if(erro==true){ 
    document.form1.y60_codlev.focus(); 
    document.form1.y60_codlev.value = ''; 
  }
}
function js_mostralevanta1(chave1,chave2){
  document.form1.y60_codlev.value = chave1;
  document.form1.y60_contato.value = chave2;
  db_iframe_levanta.hide();
}
function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    if(inscr!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nomeinscr.value = "";
    } 
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe_inscr.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}


function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_numcgm','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    if(cgm!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_numcgm','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
    }else{
      document.form1.z01_nomecgm.value = '';
    }  
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe_numcgm.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
</script>
<?
if(isset($msgerro)){
  db_msgbox($msgerro);
}
?>