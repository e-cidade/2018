<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pagordem_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empord_classe.php");
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_empageconfgera_classe.php");
require_once("classes/db_empagepag_classe.php");

$clempagetipo      = new cl_empagetipo;
$clpagordem        = new cl_pagordem;
$clempord          = new cl_empord;
$clempagemov       = new cl_empagemov;
$clempageconfgera  = new cl_empageconfgera;
$clempagepag       = new cl_empagepag;
$clrotulo          = new rotulocampo;

//echo ($HTTP_SERVER_VARS["QUERY_STRING"]);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;


$clrotulo->label("e82_codord");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e81_valor");
$clrotulo->label("e81_codmov");

$iInstit = db_getsession('DB_instit');

$sCampos  = " e81_codmov,                                 ";   
$sCampos .= " e83_codtipo as codtipo,                     ";
$sCampos .= " e83_descr,                                  ";
$sCampos .= " case                                        ";
$sCampos .= "   when e60_emiss is null                    ";
$sCampos .= "     then k17_data                           ";
$sCampos .= "   else e60_emiss                            ";
$sCampos .= " end,                                        ";
$sCampos .= " case                                        ";
$sCampos .= "   when e60_codemp is null                   ";
$sCampos .= "     then 'slip'                             ";
$sCampos .= "   else e60_codemp                           ";
$sCampos .= " end as e60_codemp,                          ";
$sCampos .= " case                                        ";
$sCampos .= "   when e82_codord is null                   ";
$sCampos .= "     then slip.k17_codigo                    ";
$sCampos .= "   else e82_codord                           ";
$sCampos .= " end as e82_codord,                          ";
$sCampos .= " case                                        ";
$sCampos .= "   when a.z01_numcgm is not null       ";
$sCampos .= "     then a.z01_numcgm                       ";
$sCampos .= "   when cgmslip.z01_numcgm is not null ";
$sCampos .= "     then cgmslip.z01_numcgm                 ";
$sCampos .= "   else cgm.z01_numcgm                       ";
$sCampos .= " end as z01_numcgm,                          ";
$sCampos .= " case                                        ";
$sCampos .= "   when trim(a.z01_nome) is not null         ";
$sCampos .= "     then a.z01_nome                         ";
$sCampos .= "   when trim(cgmslip.z01_nome) is not null   ";
$sCampos .= "     then cgmslip.z01_nome                   ";
$sCampos .= "   else cgm.z01_nome                         ";
$sCampos .= " end as z01_nome,                            ";
$sCampos .= " e81_valor                                   ";

$sOrdem  = " e83_codtipo, ";
$sOrdem .= " a.z01_nome,  ";
$sOrdem .= " cgm.z01_nome ";

$sWhere  = "e90_codgera = {$codarq} and ";
$sWhere .= "e75_codret is null      and ";
$sWhere .= "e80_instit  = {$iInstit}    ";
if (isset($lCancelado) && $lCancelado == '0') {
  
  $sWhere .= " and empageconfgera.e90_cancelado is false ";
}

$sql    = $clempageconfgera->sql_query_arqcanc(null, null, $sCampos, $sOrdem, $sWhere );
        
$result = $clempageconfgera->sql_record($sql);
$numrows= $clempageconfgera->numrows;
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
        <?if($numrows>0){?>
    <td class='bordas02' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas02'><small><b><?=$RLe81_codmov?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe60_codemp?></b></small></td>
          <td class='bordas02'><small><b>Ordem/Slip</b></small></td>
          <td class='bordas02'><small><b><?=$RLz01_nome?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe60_emiss?></b></small></td>
          <td class='bordas02'><small><b><?=$RLe81_valor?></b></small></td>
          <td class='bordas02'><small><b>Conta pagadora</b></small></td>
	    <?}else{
	    	$disabled = true;
	    ?>
	    	<td>
	    	<b>Arquivo não encontrado ou retorno já processado</b>	    	
	    	</td>	    	
	    <?}?>
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
          <td class='bordas' ><input <?=$ck?> value="<?=$e81_codmov?>"  name="CHECK_<?=$e81_codmov?>" type='checkbox'></td>
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
<?
  if(isset($disabled)){
  	echo "
    parent.document.form1.cancela.disabled = true;
    ";
  }
?>
</script>
