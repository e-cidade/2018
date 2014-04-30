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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empage_classe.php");
include("classes/db_empageconf_classe.php");
include("classes/db_errobanco_classe.php");
$clempage  = new cl_empage;
$clempageconf  = new cl_empageconf;
$clerrobanco  = new cl_errobanco;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
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
$clrotulo->label("e86_cheque");
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
  if(campo.checked==true){
    soma = new Number(total+valor);
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
    <td>
      <b>Total debitado:</b><?=trim(db_formatar($valor,'f'))?>&nbsp;&nbsp;&nbsp;<b>Total movimentos:</b><span id='vlrmov'></span><br>
      <b>Movimentos:</b><?=$movs?> 
    </td>
  </tr>
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1" method="post" action="">
      <center>
<?
  db_input("arquivo",10,'',true,'text',1);
  db_input("remreto",10,'',true,'text',1);
  db_input("movimentos",10,'',true,'text',1);
  db_input("lotsdemovs",10,'',true,'text',1);
  db_input("codigomovs",10,'',true,'text',1);
  db_input("dataretmov",10,'',true,'text',1);
  db_input("valoresmov",10,'',true,'text',1);
  db_input("codlotsmov",10,'',true,'text',1);
  db_input("codHeaderL",10,'',true,'text',1);
  db_input("codtraillL",10,'',true,'text',1);
  if(isset($geracampo)){
    $arr_movimentos = split(",",$movimentos);
    $arr_retlotsmov = split(",",$lotsdemovs);
    $arr_codigomovs = split(",",$codigomovs);
    $arr_dataretmov = split(",",$dataretmov);
    $arr_valoresmov = split(",",$valoresmov);
    $arr_codilotmov = split(",",$codlotsmov);
    $loteant = "";
    for($i=0;$i<$numrows;$i++){
      $impri = false;
      if($i==0){
        echo "<table  class='bordas'>";
      }
      if($loteant!=$olote){
	$olote = $loteant;
	$impri = true;
        echo "
        <tr>
          <td colspan='3' class='bordas02'>Lote</td>
          <td colspan='6' class='bordas02'>Retorno lote</td>
        </tr>
        ";
      }
      $dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and rpad(e81_codmov,15,'0') = ".$arr_movimentos[$i]." and e81_codgera = ".$arquivo;
      $result_arq  = $clempage->sql_record($clempage->sql_query_rel_arqretorno(null,"e81_codmov,e60_codemp,e82_codord,e86_codmov,z01_numcgm,z01_nome,e81_valor","",$dbwhere));
      $numrows_arq = $clempage->numrows;
      if($numrows_arq>0){
	db_fieldsmemory($result_arq,0);
      }
      if($i==0){
	echo "
        <tr>
          <td class='bordas' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas'><small><b>$RLe82_codord</b></small></td>
          <td class='bordas'><small><b>$RLe60_codemp</b></small></td>
          <td class='bordas'><small><b>$RLz01_nome</b></small></td>
          <td class='bordas'><small><b>$RLe81_valor</b></small></td>
          <td class='bordas'><small><b>Valor agenda</b></small></td>
          <td class='bordas'><small><b>Valor processo</b></small></td>
          <td class='bordas'><small><b>Data processo</b></small></td>
          <td class='bordas'><small><b>Retorno</b></small></td>
	</tr>
        ";
      }
      echo "
      <tr>
	<td class='bordas' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	<td class='bordas'><small><b>$e82_codord</b></small></td>
	<td class='bordas'><small><b>$e60_codemp</b></small></td>
	<td class='bordas'><small><b>$z01_nome</b></small></td>
	<td class='bordas'><small><b>$e81_valor</b></small></td>
	<td class='bordas'><small><b>Valor agenda</b></small></td>
	<td class='bordas'><small><b>Valor processo</b></small></td>
	<td class='bordas'><small><b>Data processo</b></small></td>
	<td class='bordas'><small><b>Retorno</b></small></td>
      </tr>
      ";
    }
  }
?>

        <?
	/*
	  $vlrmov = 0;
	  for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);
	    $disab01 = false;
	    $disab02 = false;
         
            $vlrmov += $e81_valor;
	  
            if($e86_codmov== ''){
	      $disab02 = true;
            }
     
	   $result01=$clerrobanco->sql_record($clerrobanco->sql_query_file(null,"e92_descrerro,e92_sequencia",'',"e92_coderro = '".$arr_retorno[$e81_codmov]."'"));
	   db_fieldsmemory($result01,0,true);
	   if($arr_retorno[$e81_codmov]=='00'){
	     $disab01 = true;
	     
	   }
	?>
        <tr>
          <td class='bordas' >
	    <input value="<?=$e81_codmov?>" <?=($disab01==true||$disab02==true?"disabled":"")?>  name="CHECK_<?=$e81_codmov?>" type='checkbox' onclick="js_calcula(this);"  >
            <?=($disab02==true?"<span style=\"color:darkblue;\">**</span>":"")?><?=($disab01==true?"<span style=\"color:darkblue;\">*</span>":"")?>
	  </td>	    
          <td class='bordas' align='center'><small><?=$e81_codmov?></small></td>
          <td class='bordas' align='center'><small id="e60_numemp_<?=$e82_codord?>"> <?=$e60_codemp?></small></td>
          <td class='bordas' align='center'><small><?=$e82_codord?></small></td>
          <td class='bordas' align='left'><small label='Numcgm:<?=$z01_numcgm?>'><?=$z01_nome?>  </small></td>
           <?
	     $x= "valor_$e81_codmov";
  	     $$x = $e81_valor;
             db_input("valor_$e81_codmov",10,'',true,'hidden',1);

	     $x= "ret_$e81_codmov";
  	     $$x = $e92_sequencia;
             db_input("ret_$e81_codmov",10,'',true,'hidden',1);
            ?>       
	  
          <td class='bordas' align='right'><small><?=number_format($e81_valor,"2",".","")?></small></td>
          <td class='bordas' align='right'><small><?=number_format($arr_retval[$e81_codmov],"2",".","")?></small></td>
          <td class='bordas' align='center'><small><?=$e92_descrerro?></small></td>
	</tr>
        <?
	  }
	  */
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
  document.getElementById('vlrmov').innerHTML = '<?=trim(db_formatar($vlrmov,'f'))?>';
function js_atualizaiframe(){
  xform  = top.corpo.document.form1;
  xformi = document.form1;
  if(xform){
    xformi.movimentos.value = xform.movimentos.value;
    xformi.valoresmov.value = xform.valoresmov.value;
    xformi.dataretmov.value = xform.dataretmov.value;
    xformi.codigomovs.value = xform.codigomovs.value;
    xformi.codHeaderL.value = xform.codHeaderL.value;
    xformi.codtraillL.value = xform.codtraillL.value;
    xformi.codlotsmov.value = xform.codlotsmov.value;
    xformi.lotsdemovs.value = xform.lotsdemovs.value;
    <?
    if(!isset($geracampo)){
      echo "obj=document.createElement('input');";
      echo "obj.setAttribute('name','geracampo');";
      echo "obj.setAttribute('type','hidden');";
      echo "obj.setAttribute('value','$arquivo');";
      echo "document.form1.appendChild(obj);";
      echo "xformi.submit();";
    }
    ?>
  }
}
<?
if(isset($arquivo) && trim($arquivo)!=""){
  echo "
    js_atualizaiframe();
  ";
}
?>
</script>