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
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
<script>
function js_rel(opcao){
  obj = document.form1;
  query = "1=1";
  if(obj.ano1.value >= obj.ano2.value){
    alert("O segundo ano deve ser maior que o primeiro!");
    return false;
  }else{
    query += "&ano1="+obj.ano1.value+"&ano2="+obj.ano2.value;
  }

  if(obj.valor.value!=''){
     query += "&perc="+obj.perc.value+"&valor="+obj.valor.value;  
  }
  query += "&ordem="+obj.ordem.value;
  query += "&order="+obj.order.value;
  query += "&imprimirsemdif="+obj.imprimirsemdif.value;

  if (opcao == 1) {
		jan = window.open('cad2_comparaiptu002.php?'+query,'relatpropri','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	} else if (opcao == 2) {
		jan = window.open('cad2_comparaiptu003.php?'+query,'relatpropri','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	}
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form method="post" name="form1" target="relatpropri" action="cad2_relpromitentes002.php">
<table width="60%" border='0'>
  <tr>
  <td><br></td>
  <td><br></td>
  </tr>
  <tr>
  
    <td width='40%' align='right'><b>Entre:</b></td> 
    <td>
<?
  $result =  pg_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
  $numrows = pg_numrows($result);
  $arr = array();
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
    $arr[$j18_anousu] = $j18_anousu;
  }
 db_select("ano1",$arr,true,1);
 echo "<b>à</b>";
 db_select("ano2",$arr,true,1);
?>


    </td>
  </tr>
  <tr>
    <td width='40%' align='right'><b>Percentual de diferença:</b></td> 
    <td nowrap width="100%"> 
      <select name="perc">
        <option value="mame">Maior ou menor</option>
        <option value="ma">Maior</option>
	<option value="me">Menor</option>
      </select>
 <b> que </b>
       <?
db_input('valor',7,'4',true,'text',1);
       ?>
    </td>
  </tr>
  <tr>
    <td width='40%' align='right'><b>Ordem:</b></td> 
    <td nowrap width="100%"> 
      <select name="ordem">
        <option value="percentual_imposto">Percentual Imposto</option>
        <option value="percentual_taxas">Percentual Taxas</option>
        <option value="j01_matric">Matrículas</option>
        <option value="valor1">Valor 1</option>
        <option value="valor2">Valor 2</option>
      </select>
    </td>
  </tr>
  <tr>
    <td width='40%' align='right'><b>Modo:</b></td> 
    <td nowrap width="100%"> 
      <select name="order">
        <option value="asc">Ascendente</option>
        <option value="desc">Descendente</option>
      </select>
    </td>
  </tr>


  <tr>
    <td width='40%' align='right'><b>Imprimir registros sem diferenca:</b></td> 
    <td nowrap width="100%"> 
      <select name="imprimirsemdif">
        <option value="nao">Nao</option>
        <option value="sim">Sim</option>
      </select>
    </td>
  </tr>


  
  <tr>
      <td align="center" colspan='2'>
        <br>
        <input name="relatorio" type="button" value="Gerar relatório"				onClick="return js_rel(1)">
        <input name="txt"				type="button" value="Gerar TXT comparativo" onClick="return js_rel(2)">
     </td>
  </tr>
  </table>
</form>
</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>