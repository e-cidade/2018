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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_marca(obj){ 
  
  var OBJ = document.form1;
  
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox'){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
    }
  }
  
  return false;
  
}
</script>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form name="form1" method="post" action="">		   
<table id="id_tabela" border="1" width="100%" >
<tr>
<td><a  title='Inverte Marcação' href='#' <?=($db_opcao==22||$db_opcao==3||$db_opcao==33?"":"onclick='return js_marca(this);return false;'")?> >M</a></td>
<td align="center"><small><b>Listas</b></small></td>
<td align="center"><small><b>Trecho</b></small></td>
<td align="center"><small><b>Rua</b></small></td>
</tr>  
<?
if($db_opcao!=1){
  $sql="select  j14_codigo,d10_codigo,d40_trecho,j14_nome from  editalproj inner join projmelhorias on d40_codigo=d10_codigo inner join ruas on d40_codlog=j14_codigo where d10_codedi=$d01_codedi order by j14_codigo";
  $result=pg_query($sql);
  $num=pg_numrows($result);
  for($x=0; $x<$num; $x++){
    db_fieldsmemory($result,$x);
    $ck = ($d10_codigo!=""?"checked":"");
    echo "<tr>
    <td id='codigo_$d10_codigo'><input type='checkbox' name='CHECK_$d10_codigo' $ck ".($db_opcao==22||$db_opcao==3||$db_opcao==33?"disabled":"")." ></td>
    <td><small>$d10_codigo</small></td>
    <td nowrap><small>$d40_trecho</small></td>
    <td nowrap><small>$j14_nome</small></td>
    </tr>";
  }  
}
if($db_opcao!=3){
  $sql="select * from
  (select j14_codigo,j14_nome,d40_codigo,d40_trecho,d10_codigo from projmelhorias left join editalproj on d40_codigo=d10_codigo inner join ruas on d40_codlog=j14_codigo)
  as x  where d10_codigo is null order by d40_codigo desc";
  $result=pg_query($sql);
  $num02=pg_numrows($result);
  for($x=0; $x<$num02; $x++){
    db_fieldsmemory($result,$x);
    $ck = ($d10_codigo!=""?"checked":"");
    echo "<tr>
    <td id='codigo_$d40_codigo'><input type='checkbox' name='CHECK_$d40_codigo' $ck ".($db_opcao==22||$db_opcao==3||$db_opcao==33?"disabled":"")." ></td>
    <td><small>&nbsp;$d40_codigo</small></td>
    <td nowrap><small>&nbsp;$d40_trecho</small></td>
    <td nowrap><small>&nbsp;$j14_nome</small></td>
    </tr>";
  }  
}  	 
?>  
</table>	
<?
if((isset($num02) && $num02==0) && (isset($num) && $num==0)){
  echo "
  <script>
  document.getElementById('id_tabela').style.visibility='hidden';
  </script>
  ";
  echo "<b>Nenhuma lista disponivel.</b>";
}
?>
</form>
</center>
</body>
</html>