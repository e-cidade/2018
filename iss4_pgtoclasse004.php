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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);

$sql = "select q12_classe,q12_descr from clasativ inner join classe on q82_classe=q12_classe group by q12_classe,q12_descr";
$result = pg_exec($sql);
if(pg_numrows($result) == 0 ){
  db_redireciona("db_erros.php?fechar=true&db_erro=Não Existe Atividade Para o Intervalo Digitado.&pagina_retorno=iss4_contativ001.php");
  exit;
}
$numrows = pg_numrows($result);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">



<script>
function js_marca() {
  
  var ID = document.getElementById('marca');
  //var BT = document.getElementById('btmarca');
  if(!ID)
    return false;
  var F = document.form1;
  if(ID.innerHTML == 'D') {
    var dis = false;
	ID.innerHTML = 'M';
  } else {
    var dis = true;
	ID.innerHTML = 'D';
  }
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox"){
        F.elements[i].checked = dis;
    }
  }
  js_verifica();
}

function js_verifica(){
  var marcas = false;
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
      marcas = true;
    }
  }
  if(marcas == false){
    parent.document.formaba.atividades.disabled = true;
  }else{
    parent.document.formaba.atividades.disabled = false;
  }
}
function js_relatorio(){
    var ativ = '';
    var virg = '';
    var F = document.form1;
    for(i = 0;i < F.elements.length;i++) {
        if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
           ativ = ativ+virg+F.elements[i].value;
           virg = '-';
        }
    }
    jan = window.open('iss4_pgtoclasse003.php?tipo='+document.form1.tipo.value+'&totais='+document.form1.totais.value+'&classes='+ativ+'&datai='+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value+'&dataf='+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value+'&opcao=analitico','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
}



</script>
<style type="text/css">
<!--
th {
     font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
}
td {
     font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" bgcolor="#CCCCCC" topmargin="0" marginwidth="0" marginheight="0">
<table  width="100%" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post" action="">
        <table height="200" border="0" width="100%" cellspacing="1" cellpadding="0" id="atividades">
	<tr align="top">
	<td width="43%" height="30" colspan="6" bordercolor="#FFFFCC"><div align="center"><font size="2">
	<strong>Período de : </strong>
	<?
	$datai_ano = date('Y');
	$datai_mes = '01';
	$datai_dia = '01';
	db_inputdata('datai',$datai_dia,$datai_mes,$datai_ano,true,'text',4);
	?>
	<strong>Até : </strong>
	<?
	$dataf_ano = date('Y');
	$dataf_mes = date('m');
	$dataf_dia = date('d');
	db_inputdata('dataf',$dataf_dia,$dataf_mes,$dataf_ano,true,'text',4);
	?>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<?
         $x = array("c"=>"Competência","p"=>"Pagamento");
         db_select('tipo',$x,'text',2);
         $y = array("m"=>"Valores por mes","t"=>"Somente totais");
         db_select('totais',$y,'text',2) 
	?>
	&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<input name="analitico" id="emite2" type="button" value="Imprimir" onClick="js_relatorio()">&nbsp;&nbsp;
	</tr>
          <tr height="20" bgcolor="#FFCC66">
            <th class="borda" align="center" style="font-size:12px" nowrap><a id="marca" href="#" style="color:black" onclick="js_marca();return false">D</a></th>      
	    <th class="borda" align="center" style="font-size:12px" nowrap>Classe</th>
            <th class="borda" align="left" style="font-size:12px" nowrap>Descrição</th>
          </tr>
	  <?
	    $cor = '#E4F471';
            for($i = 0;$i < $numrows;$i++) {
              db_fieldsmemory($result,$i);
	      if ($cor == '#E4F471'){
		 $cor = '#EFE029';
	      }elseif ($cor == '#EFE029'){
		 $cor = '#E4F471';
		
	      }
	  ?>
          <tr style="cursor: hand; height: 20px" bgcolor="<?=$cor?>">
            <td height="20px" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
              <input type="checkbox" value="<?=$q12_classe?>"  name="check<?=$i?>" checked onclick="js_verifica()">
            </td>
            <td height="20px" class="borda" style="font-size:11px" align="center" nowrap><?=$q12_classe?></td>
	    <td height="20px" class="borda" style="font-size:11px" align="left" nowrap><?=$q12_descr?></td>
          </tr>
	  <?
          }
	  ?>
        </table>   
      </form>
</table>
</body>
</html>