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

include("classes/db_db_syscampo_classe.php");
include("classes/db_db_sysarqcamp_classe.php");
include("classes/db_db_syscampodep_classe.php");
$cldb_syscampo = new cl_db_syscampo;
$cldb_syscampodep = new cl_db_syscampodep;
$cldb_sysarqcamp = new cl_db_sysarqcamp;


$clrotulo = new rotulocampo;
$clrotulo->label("nomecam");
$clrotulo->label("nomearq");
$clrotulo->label("codcam");
$clrotulo->label("rotulo");
$clrotulo->label("rotulorel");

$db_opcao = 1;
$db_botao = true;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
function gera($codigo,$tipo,$ni=1){
        global $codcam,$codcampai,$cldb_syscampodep,$cldb_syscampo,$rotulorel,$rotulo,$nomecam,$cldb_sysarqcamp,$nomearq;
        //rotina que pega os dados do campo  
          $result07 = $cldb_syscampo->sql_record($cldb_syscampo->sql_query($codigo));
          db_fieldsmemory($result07,0) ; 
        //	
	//rotina que indica a q tabela pertence o campo  
          $result08 = $cldb_sysarqcamp->sql_record($cldb_sysarqcamp->sql_query(null,$codigo,null,"nomearq"));
	  if($cldb_sysarqcamp->numrows==0){
	    $nomearq = "Sem tabela";
	  }else{  
            db_fieldsmemory($result08,0); 
	  }  
	//
	 $esp='&nbsp;';
	 for($i=0;$i<$ni;$i++){
	   $esp.='&nbsp;&nbsp;&nbsp;&nbsp;';   
	 }
	echo "<tr>
	        <td><input type='radio' name='pri' value='$codigo' ".($ni==0?"checked":"")." onclick='js_desab(\"$codigo\");'></td>
	        <td><input type='checkbox' name='sec_$codigo' value='$codigo' ".($ni==0?"disabled":"")."  ></td>
		<td>$esp$codigo</td>
		<td>$nomecam</td>
		<td>$rotulo</td>
		<td>$rotulorel</td>
		<td>$tipo</td>
		<td>$nomearq</td>
	      </tr>
	     ";
}

  static $passou=null;
  static $g=0;
  $arr_pai = array();
  $arr_filho = array();
  $arv=array();
  $pai =array();
function verifica($campo,$anterior=null){
  global $pai,$arv,$arr_filho,$g,$principal,$codcam,$codcampai,$cldb_syscampodep,$cldb_syscampo,$rotulorel,$rotulo,$nomecam,$cldb_sysarqcamp,$nomearq,$passou;
    //rotina que procura pai
    $result = $cldb_syscampodep->sql_record($cldb_syscampodep->sql_query_file(null,"codcampai","","codcam=$campo and codcampai<>$campo"));
    $numrows = $cldb_syscampodep->numrows;
    if($numrows==0 && $campo!=null){
      $principal = $campo;
    }
    for($x=0; $x<$numrows; $x++){
      db_fieldsmemory($result,$x);
      if($codcampai != $anterior){
        if(empty($pai[$codcampai])){
	  $pai[$codcampai]=array();
	}
        $pai[$codcampai][ count($pai[$codcampai]) ]=$campo;
        verifica($codcampai,$campo);
      }
    }

    //rotina que procura filho
    $result02 = $cldb_syscampodep->sql_record($cldb_syscampodep->sql_query_file(null,"codcam","","codcampai=$campo"));
    $numrows02 =$cldb_syscampodep->numrows;
    for($i=0; $i<$numrows02; $i++){
      db_fieldsmemory($result02,$i);
      if($codcam != $anterior){
        if(empty($pai[$campo])){
	  $pai[$campo]=array();
	}
        $pai[$campo][ count($pai[$campo]) ]=$codcam;
	verifica($codcam,$campo);
      }
    }
}
  $arr_n=array();
  $nivel=0;

  
function ordena($pri,$ni){
   global $pai;
   if(isset($pai[$pri])){
     reset($pai[$pri]);
     while($cod = current($pai[$pri])){
       if(isset($pai[$cod])){
         gera($cod,"Pai",$ni);
	 ordena($cod,($ni+1));
       }else{
         gera($cod,"Filho",$ni);
       }	     
       next($pai[$pri]);
     }
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
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
function js_desab(cod){
    obj=document.getElementsByTagName("INPUT")
    var marcado=false;
    for(i=0; i<obj.length; i++){
      if(obj[i].type=='checkbox'){
	nome = obj[i].name.substring(4);
	if(nome==cod){
	  obj[i].checked=false;
	  obj[i].disabled=true;
	}else{
	  obj[i].disabled=false;
	}
      }
    }

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<?

  if(isset($codcam) && $codcam!="" ){
    
        echo "<table border='1' cellspacing='2' cellpadding='0' style='border-style:outset'> ";
 	echo "<tr >
		<td style='border-style:outset' align='center'><b>Pri</b></td>
		<td style='border-style:outset' align='center'><b><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>Sec</a></b></td>
		<td style='border-style:outset' align='center'><b>$RLcodcam</b></td>
		<td style='border-style:outset' align='center'><b>$RLnomecam</b></td>
		<td style='border-style:outset' align='center'><b>$RLrotulo</b></td>
		<td style='border-style:outset' align='center'><b>$RLrotulorel</b></td>
		<td style='border-style:outset' align='center'><b>Tipo</b></td>
		<td style='border-style:outset' align='center'><b>$RLnomearq</b></td>
	      </tr>
	";         

         verifica($codcam);
         gera($principal,"Principal",0);
	 ordena($principal,1);

      echo "</table>";
  }      
?>    
    </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>