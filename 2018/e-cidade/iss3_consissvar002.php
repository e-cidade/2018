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
include("classes/db_issvar_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_arrenumcgm_classe.php");
include("dbforms/db_classesgenericas.php");
$clcriaabas     = new cl_criaabas;
$clissvar = new cl_issvar;
$clissbase = new cl_issbase;
$clcgm = new cl_cgm;
$clarrenumcgm = new cl_arrenumcgm;
$clarrecad = new cl_arrecad;
$clarreinscr = new cl_arreinscr;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$db_botao=true;
$db_opcao=2;
function php_invalido($hide,$msg){
    echo "
     <script>
      parent.js_invalido('$hide','$msg');//hide=nome do iframe para fechar e msg a mensagem para mostrar pro usuario
     </script>
    "; 
  
}
if(isset($z01_cgccpf) && $z01_cgccpf!=""){
  $result04  = $clcgm->sql_record($clcgm->sql_query_file("","z01_numcgm","","z01_cgccpf = '$z01_cgccpf'"));
  $numrows04 = $clcgm->numrows;
  if($numrows04>1){
    php_invalido("z01_cgccpf","CNPJ $z01_cgccpf foi encontrado em dois cgm.\\n Contate suporte");
    exit;
  }else if($numrows04==1){
    db_fieldsmemory($result04,0);
  }else{
     $tipo="cgccpf";
  }
}
if(isset($z01_numcgm) && $z01_numcgm!=""){
  $result02 = $clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome,z01_cgccpf"));//pega o nome
  if($clcgm->numrows<1){
    php_invalido("z01_numcgm","NUMCGM $z01_numcgm inválido!");
    exit;
    
  }
  db_fieldsmemory($result02,0);
  $sql03 = $clissbase->sql_query("","issbase.q02_inscr","","q02_numcgm=$z01_numcgm");
  $result03=$clissbase->sql_record($sql03);
  $numrows03= $clissbase->numrows;
  if($numrows03==1){
    db_fieldsmemory($result03,0);
  }else if($numrows03>0){
    $varias_inscricoes=true;
  }else{
    $tipo="cgm";
    $valor=$z01_numcgm;
  }
}
if(isset($q02_inscr) && $q02_inscr!=""){
    $result08=$clissbase->sql_record($clissbase->sql_query($q02_inscr,"z01_nome,z01_cgccpf"));
    if($clissbase->numrows>0){
      $tipo="inscr";
      $valor=$q02_inscr;
      db_fieldsmemory($result08,0);
    }else{
      php_invalido("q02_inscr","Inscrição $q02_inscr inválida!");
      exit;
    }  
    $string02="";
    if(isset($mes_ini)){
    $string02.=" and q20_mes>=$mes_ini ";
    }
    if(isset($mes_fim)){
    $string02.=" and q20_mes<=$mes_fim ";
    }
    if(isset($ano_ini)){
    $string02.=" and q20_ano>=$ano_ini ";
    }
    if(isset($ano_fim)){
    $string02.=" and q20_ano<=$ano_fim ";
    }
    if($string02!=""){
    $string02="&string02=".base64_encode($string02);
    }
}
  
$string="";
if(isset($mes_ini)){
  $string.=" and q05_mes>=$mes_ini ";
}
if(isset($mes_fim)){
  $string.=" and q05_mes<=$mes_fim ";
}
if(isset($ano_ini)){
  $string.=" and q05_ano>=$ano_ini ";
}
if(isset($ano_fim)){
  $string.=" and q05_ano<=$ano_fim ";
}
if($string!=""){
  $string="&string=".base64_encode($string);
}
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("q02_inscr");
$clrotulo->label("q05_numpar");
$clrotulo->label("q05_numpre");
$clrotulo->label("DBtxtissplan");
$clrotulo->label("DBtxtissplanit");
$clrotulo->label("DBtxtissvar");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_voltar(){
   location.href="iss3_consissvar001.php";
}
function js_planit(codigo){
      js_OpenJanelaIframe('top.corpo','db_iframe_planit','iss3_consissvar005.php?q21_planilha='+codigo,'Pesquisa',true);
}
function js_label(liga,evt,codigo,numpre,parcela){
  evt= (evt)?evt:(window.event)?window.event:""; 

  
  if(liga){
    notas=iframe_issvar.document.getElementById('notas_'+codigo).value;
    if(notas!=""){
      matriz01= notas.split('#');
      for(i=0; i<matriz01.length; i++){
	matriz02= matriz01[i].split('-');
	
	novalinha  = document.getElementById('tab').insertRow(document.getElementById('tab').rows.length);
	novalinha.style.backgroundColor="#ccddcc";
	novalinha.id = "idx_"+document.getElementById('tab').rows.length;
	novacoluna = novalinha.insertCell(0);
	novacoluna.innerHTML = matriz02[0];
	novacoluna = novalinha.insertCell(1);
	novacoluna.innerHTML = matriz02[1];
      }
    }  
     document.getElementById('numpre').innerHTML=numpre;
     document.getElementById('parcela').innerHTML=parcela;
     //document.getElementById('tab').style.left=evt.clientX+100;
     //document.getElementById('tab').style.top=evt.clientY+40;
     document.getElementById('tab').style.left=250;
     document.getElementById('tab').style.top=100;
     document.getElementById('tab').style.visibility='visible';
  }else{
    var tab =document.getElementById('tab');
    var tam=(tab.rows.length-1);
    for(i=tam;i>2;i--){
        id=tab.rows[i].id;
        if(id.substr(0,3)=="idx"){
          tab.deleteRow(i);
        } 
    }
    document.getElementById('tab').style.visibility='hidden';
  }  
}
</script>
<style>
.cabec {
text-align: left;
font-size: 10;
font-weight: bold;
background-color:#aacccc ;       
border-color: darkblue;
}
.corpo {
  background-color:#ccddcc;       
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="395" width="765" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td valign="top" bgcolor="#cccccc" align="center">     
<?
###########QUANDO TIVER UMA OU MAIS INSCRIÇÕES PARA UM CGM###############################################################

if(isset($varias_inscricoes) && $varias_inscricoes==true){
?>
<script>
   function js_retorna_inscr(inscr){
     document.form1.q02_inscr.value=inscr;
     document.form1.submit();
   }
</script>
<form name="form1" method="post" action="iss3_consissvar002.php">
 <table>
   <tr>   
    <td title="<?=$Tz01_nome?>">
    <?=$Lz01_nome?>
    </td>
    <td> 
    <?
     $z01_numcgmx=$z01_numcgm; 
     db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',3,"",'z01_numcgmx');
     db_input('z01_nome',40,0,true,'text',3);
     db_input('q02_inscr',40,0,true,'hidden',1);
    ?>
   </td>
  </tr>
  <?
     if(isset($z01_cgccpf)){
  ?>
  <tr>   
    <td title="<?=$Tz01_cgccpf?>">
    <?=$Lz01_cgccpf?>
    </td>
    <td> 
    <?
     $z01_cgccpfx=$z01_cgccpf; 
     db_input('z01_cgccpf',20,$Iz01_numcgm,true,'text',3,"",'z01_cgccpfx');
    ?>
   </td>
  </tr>
  <?
     }
  ?>
  <tr>
    <td colspan="2" >
      <?
       db_lovrot($sql03,15,"()","","js_retorna_inscr|q02_inscr");
      ?> 
    </td>
  </tr>  
 </table>  
</form>


<?
###############################################################################################################
}else{
?>
<table width="760" height="390" border="0" align="center" cellpadding="0" cellspacing="2">
<form name="form1" method="post" action="iss3_consissvar002.php">
        <table border="1" id="tab"  class="cabec" style="position:absolute; z-index:1; top:0; left:600; visibility: hidden;">      
	    <tr id="id01">
	      <td colspan="2"><font color="darkblue"><?=$Lq05_numpre?></font><span id="numpre"></span><br> 
	    </tr>
	    <tr>
	      <td colspan="2"><font color="darkblue"><?=$Lq05_numpar?></font><span id="parcela"></span><br> 
	    </tr>
	    <tr id="id02">
	      <td><font color="darkblue">NOTA</font></td>
	      <td><font color="darkblue">VALOR</font></td>
	   </tr>
	  </table> 
<?
  if($tipo=="cgccpf"){
?>
  <tr>   
    <td title="<?=$Tz01_cgccpf?>" colspan="2" align="center">
      <?=$Lz01_cgccpf?>
    <?
     db_input('z01_cgccpf',40,0,true,'text',3);
    ?>
   </td>
  </tr>
<?
  }else{
?>
  <?
     if(isset($q02_inscr)){
  ?>
  <tr>   
    <td title="<?=$Tq02_inscr?>" colspan="2" align="center">
     <?=db_ancora($Lq02_inscr,"js_JanelaAutomatica('issbase','$q02_inscr')",2)?>
    <?
     $q02_inscr=$q02_inscr; 
     db_input('q02_inscr',10,$Iq02_inscr,true,'text',3,"",'q02_inscr');
     db_input('z01_nome',40,0,true,'text',3);
    ?>
   </td>
  </tr>
  <?
     }else if(isset($z01_numcgm)){
  ?>
  <tr>   
    <td title="<?=$Tz01_nome?>" colspan="2" align="center">
      <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','$z01_numcgm')",2)?>
    <?
     $z01_numcgmx=$z01_numcgm; 
     db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',3,"",'z01_numcgm');
     db_input('z01_nome',40,0,true,'text',3);
    ?>
   </td>
  </tr>
  <?
     }
  ?>
<?
 }
?>
</form>
  <tr> 
    <td colspan="2" align="left" >
      <table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
	  <td>
     <?
       if($tipo=="cgm"){
	   if($z01_cgccpf!=""){
             $clcriaabas->identifica = array("issvar"=>"$RLDBtxtissvar","issplanit"=>"$RLDBtxtissplanit");
             $clcriaabas->src = array("issvar"=>"iss3_consissvar003.php?tipo=$tipo&valor=$valor$string","issplanit"=>"iss3_consissvar006.php?z01_cgccpf=$z01_cgccpf");
	   }else{  
             $clcriaabas->identifica = array("issvar"=>"$RLDBtxtissvar");
             $clcriaabas->src = array("issvar"=>"iss3_consissvar003.php?tipo=$tipo&valor=$valor$string");
	   }  
       }else if($tipo=="inscr"){
	   if($z01_cgccpf!=""){
             $clcriaabas->identifica = array("issvar"=>"$RLDBtxtissvar","issplan"=>"$RLDBtxtissplan","issplanit"=>"$RLDBtxtissplanit");
             $clcriaabas->src = array("issvar"=>"iss3_consissvar003.php?tipo=$tipo&valor=$valor$string","issplan"=>"iss3_consissvar004.php?q02_inscr=$valor$string02","issplanit"=>"iss3_consissvar006.php?q02_inscr=$q02_inscr&z01_cgccpf=$z01_cgccpf");
	   }else{  
             $clcriaabas->identifica = array("issvar"=>"$RLDBtxtissvar","issplan"=>"$RLDBtxtissplan");
             $clcriaabas->src = array("issvar"=>"iss3_consissvar003.php?tipo=$tipo&valor=$valor$string","issplan"=>"iss3_consissvar004.php?q02_inscr=$valor$string02");
	   }  
       }else{	 
          $clcriaabas->identifica = array("issplanit"=>"$RLDBtxtissplanit");
          $clcriaabas->src = array("issplanit"=>"iss3_consissvar006.php?z01_cgccpf=$z01_cgccpf");
       } 
       $clcriaabas->sizecampo= array("issvar"=>"15");    
       $clcriaabas->iframe_width="740"; 
       $clcriaabas->iframe_height="300"; 
       $clcriaabas->abas_top=60;    
       $clcriaabas->abas_left=17;    
       $clcriaabas->scrolling="yes";    
       $clcriaabas->cria_abas();    
     ?> 
	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
<?
}
?>  
  </td>
  </tr>
</table>
</body>
</html>