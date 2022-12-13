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
include("classes/db_empagepag_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;

//echo ($HTTP_SERVER_VARS["QUERY_STRING"]);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;


$clrotulo = new rotulocampo;
$clrotulo->label("e82_codord");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e81_valor");
$clrotulo->label("e81_codmov");

if(isset($movs)){
  $arr_movs = split("XX",$movs);
  $arr_m = array();
  for($i=0; $i<count($arr_movs); $i++){
    $arr_m[$arr_movs[$i]] = $arr_movs[$i];
  }
}

$dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e81_codage=$e80_codage and e81_cancelado is null and (e86_codmov is null or (e86_codmov is not null and e86_correto='f')) ";

if(isset($e83_codtipo) && $e83_codtipo != '' ){
  $dbwhere .=" and e83_codtipo=$e83_codtipo ";
}
if(isset($e82_codord) && $e82_codord != '' && isset($e82_codord02) && $e82_codord02 != '' ){
  $dbwhere .=" and e82_codord >=$e82_codord and e82_codord <= $e82_codord02 ";
}else if(  (empty($e82_codord) || ( isset($e82_codord) &&  $e82_codord == '')  ) && isset($e82_codord02) && $e80_codord02 != '' ){
  $dbwhere .=" and e82_codord <= $e82_codord02 ";
}else if(isset($e82_codord) && $e82_codord != '' ){
  $dbwhere .=" and e82_codord=$e82_codord ";
}


if(isset($e60_codemp) && $e60_codemp != '' ){
  $dbwhere .=" and e60_codemp = $e60_codemp ";
}
if(isset($e60_numemp) && $e60_numemp != '' ){
  $dbwhere .=" and e60_numemp = $e60_numemp ";
}
if(isset($z01_numcgm) && $z01_numcgm != '' ){
  $dbwhere .=" and z01_numcgm = $z01_numcgm ";
}

$sql    = $clempagemov->sql_query_emp(null,"e81_codmov,e83_codtipo as codtipo,e83_descr,e60_emiss,e60_codemp,e82_codord,z01_numcgm,z01_nome,e81_valor","","$dbwhere");
$result = $clpagordem->sql_record($sql); 
$numrows= $clpagordem->numrows; 
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
   soma=new Number();
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            

       if(OBJ.elements[i].checked==true){
         valor = new Number(eval("document.form1.valor_"+OBJ.elements[i].value+".value"));
	 soma = new Number(soma+valor);
       }
     }
   }
   parent.document.form1.total.value = soma.toFixed(2); 
   return false;
}

function js_calcula(campo){
  total = new Number(parent.document.form1.total.value);
  valor = new Number(eval("document.form1.valor_"+campo.value+".value"));
  mov = campo.value;
  if(campo.checked==true){
    soma   = new Number(total+valor);
    
  }else{
    soma = new Number(total-valor);
  }
  parent.document.form1.total.value = soma.toFixed(2);
  if(soma == 0){
	  parent.document.form1.cheques.options[0].selected = true;
  }
  parent.js_liberar_botao(true);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
<form name="form1" method="post" action="">
           <?
             db_input("cgm",10,'',true,'hidden',1);
            ?>       
    <center>
      <table  class='bordas'>
        <tr>
    <td class='bordas02' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas02'><small><b><?=$RLe81_codmov?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe60_codemp?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe82_codord?></b></small></td>
          <td class='bordas02'><small><b><?=$RLz01_nome?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe60_emiss?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe81_valor?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe83_codtipo?></b></small></td>
	</tr>
        <?
	 $tot='0.00'; 
	  for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);
	    $ck = '';
	    
	    if (isset($movs) && array_key_exists($e81_codmov,$arr_m)) {
               $ck = ' checked ';
	       $tot+=$e81_valor;
	    }
	?>
        <tr>
          <td class='bordas' ><input <?=$ck?> value="<?=$e81_codmov?>"  name="CHECK_<?=$e81_codmov?>" type='checkbox' onclick="js_calcula(this);"  ></td>
          <td class='bordas' align='center'><small><?=$e81_codmov?></small></td>
          <td class='bordas' align='center'><small id="e60_numemp_<?=$e82_codord?>"> <?=$e60_codemp?></small></td>
          <td class='bordas' align='center'><small><?=$e82_codord?></small></td>
          <td class='bordas' align='right'><small><?=$z01_nome?>  </small></td>
           <?
	     $x= "z01_numcgm_$e81_codmov";
  	     $$x = $z01_numcgm;
             db_input("z01_numcgm_$e81_codmov",10,'',true,'hidden',1);
            ?>       
          <td class='bordas' align='center'><small><?=$e60_emiss?>  </small></td>
          <td class='bordas' align='right'><small><?=number_format($e81_valor,"2",".","")?></small></td>
           <?
	     $x= "valor_$e81_codmov";
  	     $$x = $e81_valor;
             db_input("valor_$e81_codmov",10,'',true,'hidden',1);
            ?>       
	  
          <td class='bordas' align='left'><small><?=$e83_descr?></small></td>
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
<script>
  parent.document.form1.total.value = '<?=$tot?>'; 
  parent.document.form1.codtipo.value = '<?=$codtipo?>';
</script>