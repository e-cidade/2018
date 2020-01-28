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

define("TAREFA", true);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_clientes_classe.php");
include("classes/db_tarefaclientes_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clclientes = new cl_clientes;
$cltarefaclientes = new cl_tarefaclientes;
$clclientes->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_envia_dados(){

  deb = document.form1;
  parent.document.form1.itens_clientes_escolhidos.value = '';
  marcados = '';
  separador = '-';
  for(i=0;i< deb.length ;i++){
    if(deb.elements[i].type == "checkbox") {
      if(deb.elements[i].checked == true) {
        marcados += separador + deb.elements[i].name.substr(4) ;
      }
    }
  }
  marcados += separador;

  parent.document.form1.itens_clientes_escolhidos.value = marcados;

}

function js_marcadesmarca(){

  deb = document.form1;
  for(i=0;i< deb.length ;i++){
    if(deb.elements[i].type == "checkbox") {
      if(deb.elements[i].checked == true) {
        deb.elements[i].checked = false;
      }else{
        deb.elements[i].checked = true;
      }
    }
  }
  js_envia_dados();

}


</script>


</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
<center>
<form name='form1' method='post'>
<table border='1'>
<?

  $result = $clclientes->sql_record($clclientes->sql_query(null,'at01_codcli,at01_nomecli,at01_cidade','at01_nomecli',' at01_status is true '));
  //db_criatabela($result);
  if($clclientes->numrows>0){
    echo "<tr>";
    echo "<td>";
    echo "<input name='marca_todos' id='marca_todos' type='button' value='M' onclick='js_marcadesmarca()'>";
    echo "</td><td>";
    echo $Lat01_nomecli;
    echo "</td><td>";
    echo $Lat01_cidade;
    echo "</td>";
    echo "<td>&nbsp&nbsp&nbsp";
    echo "</td>";

    echo "<td>";
    echo "";
    echo "</td><td>";
    echo $Lat01_nomecli;
    echo "</td><td>";
    echo "$Lat01_cidade";
    echo "</td>\n";


 
    for($i=0;$i<$clclientes->numrows;$i++){
      db_fieldsmemory($result,$i);

      if($clientes_selecionados=='' || strpos("##".$clientes_selecionados,'-'.$at01_codcli.'-')>0){
        $marca = 'checked';
      }else{
        $marca = '';
      }

      echo "<tr>";
      echo "<td>";
      echo "<input name='cli_".$at01_codcli."' id='cli_".$at01_codcli."' type='checkbox' $marca onclick='js_envia_dados()'>";
      $res = $cltarefaclientes->sql_record($cltarefaclientes->sql_query(null,'at70_tarefa',''," at70_tarefa = $tarefa and at70_cliente = $at01_codcli "));
      if($cltarefaclientes->numrows>0){
        echo "</td><td bgcolor='red'>";
      }else{
        echo "</td><td>";
      }

      echo $at01_codcli."-".$at01_nomecli;
      echo "</td><td>";
      echo $at01_cidade;
      echo "</td>";
      echo "<td>&nbsp&nbsp&nbsp";
      echo "</td>";

      $i ++;
      if( $i == $clclientes->numrows ){
        break;
      }
      db_fieldsmemory($result,$i);

      if($clientes_selecionados=='' || strpos("##".$clientes_selecionados,'-'.$at01_codcli.'-')>0){
        $marca = 'checked';
      }else{
        $marca = '';
      }

      echo "<td>";
      echo "<input name='cli_".$at01_codcli."' id='cli_".$at01_codcli."' type='checkbox' $marca onclick='js_envia_dados()'>";

      $res = $cltarefaclientes->sql_record($cltarefaclientes->sql_query(null,'at70_tarefa',''," at70_tarefa = $tarefa and at70_cliente = $at01_codcli "));
      if($cltarefaclientes->numrows>0){
        echo "</td><td bgcolor='red'>";
      }else{
        echo "</td><td>";
      }
      echo $at01_codcli."-".$at01_nomecli;
      echo "</td><td>";
      echo $at01_cidade;
      echo "</td>";

      echo "</tr>\n";
    }
  
  }else{
    echo "Não existem clientes cadastrados.";
  }
  

?>
</table><strong>
Clientes em vermelho estão envolvidos na tarefa.
</strong>
</form>
</center>
</td>
</tr>
</table>
</body>
</html>
<script>
js_envia_dados();
</script>