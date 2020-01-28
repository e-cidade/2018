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

include("classes/db_pagordem_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empageconf_classe.php");
include("classes/db_empagepag_classe.php");

include("classes/db_slip_classe.php");
include("classes/db_slipnum_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;
$clempageconf  = new cl_empageconf;

$clslip       = new cl_slip;
$clslipnum    = new cl_slipnum;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_SERVER_VARS,2);

$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("k17_codigo");
$clrotulo->label("k17_valor");
$clrotulo->label("k17_data");
$clrotulo->label("k17_debito");
$clrotulo->label("k17_credito");
$clrotulo->label("e40_descr");
$clrotulo->label("c60_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");

$dbwhere = " e86_codmov is null and (e81_codage is null  or e81_codage=$e80_codage) and k17_dtaut is null and k17_dtanu is null and c60_anousu =".db_getsession("DB_anousu") . " and k17_instit = " . db_getsession("DB_instit");

$dbwhere1 = "";
if((isset($slip1) && trim($slip1)!="") || (isset($slip2) && trim($slip2)!="")){
  if((isset($slip1) && trim($slip1)!="") && (isset($slip2) && trim($slip2)!="")){
    $dbwhere1 = " and s.k17_codigo between $slip1 and $slip2 ";
  }else{
    if((isset($slip1) && trim($slip1)!="")){
      $dbwhere1 .= " and s.k17_codigo>=$slip1 ";
    }else if((isset($slip2) && trim($slip2)!="")){
      $dbwhere1 = " and s.k17_codigo<=$slip2 ";
    }
  }
}
$sql  = $clslip->sql_query_tipo(null,"s.*,e40_descr,c60_descr,z01_nome,e89_codigo,e89_codmov",'s.k17_codigo',$dbwhere.$dbwhere1);
//echo $sql;
$result = $clslip->sql_record($sql); 
$numrows= $clslip->numrows;
//db_msgbox($numrows);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
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
function js_padrao(val){

   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     nome = OBJ.elements[i].name;
     tipo = OBJ.elements[i].type;
     if( tipo.substr(0,6) == 'select' && nome.substr(0,11)=='e83_codtipo'){
       ord = nome.substr(12);
       checa = eval("document.form1.CHECK_"+ord+".checked");
       if(checa==false){
	 continue;
       } 
      for(q=0; q<OBJ.elements[i].options.length; q++){
	if(OBJ.elements[i].options[q].value==val){
	   OBJ.elements[i].options[q].selected=true;
	   break;
	 }
      }
    }
   }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
<form name="form1" method="post" action="">
    <center>
      <table  class='bordas'>
        <tr>
          <td class='bordas02' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_codigo?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_credito?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_debito?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLc60_descr?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLe40_descr?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_data?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_valor?></b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLe83_codtipo?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLz01_nome?></b></small></td>
	</tr>
        <?
	   $nords =  '';
	   $nvirg ='';
	  for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);

        if( substr($k17_data,6,4) != db_getsession("DB_anousu")){
          continue;
        }

	    $result0  = $clempageconf->sql_record($clempageconf->sql_query_file(null,"e86_codmov",""," e80_instit = " . db_getsession("DB_instit") . " and e86_codmov=$e89_codmov"));
	     
	    $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_codtipo as codtipo,e83_descr","e83_descr"," k13_conta=$k17_credito "));
	    $numrows05 = $clempagetipo->numrows;
	    $arr = Array();
	    $x= "e83_codtipo_$k17_codigo";
	    $xd = "descr_$k17_codigo";
	    if($numrows05>0){
	      db_fieldsmemory($result05,0);
	      $$x = $codtipo;
              $$xd = $e83_descr;
	    }else{
	      $$x = '';
              $$xd = '';
	    }

	    

	    flush();
        
         
	   //coloca o valor com campo
	    $x= "valor_$k17_codigo";
	    $$x = number_format($k17_valor,"2",".","");
            db_input("valor_$k17_codigo",6,'',true,'hidden',1,1);
           //------------ 

	   $xeque = '';
	   if(isset($ordens) && $ordens == 's'){
	     if($e89_codigo == ""){
	       continue;
	     }
	   }else if(isset($ordens) && $ordens=="n"){
	     if($e89_codigo != ""){
	       continue;
	     }
	   }
           if($e89_codigo != ""){
	     $xeque = 'checked';

             //rotina que verifica quais movimentos eh para trazer.. se todos,selecionados e naum selecionados
               
	   } 
	   
	?>
        <tr>
          <td class='bordas' align='right'><input value="<?=$k17_codigo?>"  name="CHECK_<?=$k17_codigo?>" <?=$xeque?> type='checkbox'></td>
          <td class='bordas' align='right'><small > <?=$k17_codigo?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_credito?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_debito?></small></td>
          <td class='bordas' align='left' nowrap ><small > <?=(substr($c60_descr,0,20))?></small></td>
          <td class='bordas' align='right'><small > <?=$e40_descr?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_data?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_valor?></small></td>
          <td class='bordas' align='right'><small>
	  <?
	  db_input("e83_codtipo_$k17_codigo","2","",true,"hidden",3);
	  db_input("descr_$k17_codigo","30","",true,"text",3);
	  ?></small></td>
          <td class='bordas' align='left' nowrap><small >&nbsp; <?=$z01_nome?></small></td>
	</tr>
        <?
	  }
	?>
      </table>
    </center>
    </form>
    </td>
  </tr>
</table>
</body>
</html>