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
include("classes/db_clientesmodulosprocusu_classe.php");
include("classes/db_db_usuclientes_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clclientesmodulosprocusu = new cl_clientesmodulosprocusu;
$cldb_usuclientes  = new cl_db_usuclientes;
$cldb_usuclientes->rotulo->label("at10_codigo");
$cldb_usuclientes->rotulo->label("at10_codcli");


if(isset($atualizar)){

    
   reset($HTTP_POST_VARS);

   $clclientesmodulosprocusu->excluir(null," at76_seqproc = $at76_sequen ");


   for($i=0;$i<count($HTTP_POST_VARS);$i++){
   
      if( substr(key($HTTP_POST_VARS),0,4) == 'usu_' ){
        $usuario = substr(key($HTTP_POST_VARS),4);
        $clclientesmodulosprocusu->at76_sequen = 0;
        $clclientesmodulosprocusu->at76_seqproc = $at76_sequen;
        $clclientesmodulosprocusu->at76_usuario = $usuario;
        $clclientesmodulosprocusu->incluir(0);
             
      }
      next($HTTP_POST_VARS);
   }


}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_incluir_cliente(){
  js_OpenJanelaIframe('','db_iframe_db_usucliente','func_db_usuclientesalt_usuario.php?cliente='+<?=$cliente?>,'Pesquisa',true);
}

function js_atualiza(codigo,nome){
  js_OpenJanelaIframe('','db_iframe_db_usucliente','func_db_usuclientesalt_usuario.php?pesquisa=true&cliente='+<?=$cliente?>+'&codusu='+codigo,'Pesquisa',true);   
}


</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name='form1' method='POST'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align='left'><strong>Procedimento:</strong>
    <input name='codproced' value='<?=$codproced?>' type='hidden'>
    <input name='cliente' value='<?=$cliente?>' type='hidden'>
    <input name='at76_sequen' value='<?=$at76_sequen?>' type='hidden'>
  </td>
  <td align='left'>
  <?=$codproced." - ".$nomeproced?>
  </td>
  <td>
     <input name='atualizar' value='Atualizar' type='submit'>
  </td>
  </tr> 
  <tr> 
    <td colspan='2'>
<?

$result = $cldb_usuclientes->sql_record($cldb_usuclientes->sql_query(null,"at10_usuario,at10_login,at10_nome","at10_nome"," at10_codcli = $cliente "));
echo "<table>";
for($i=0;$i<pg_numrows($result);$i+=2){
  db_fieldsmemory($result,$i);

  $res = $clclientesmodulosprocusu->sql_record($clclientesmodulosprocusu->sql_query_file(null,"*",null," at76_usuario = $at10_usuario"));
  if( $clclientesmodulosprocusu->numrows>0 ){
    $select = " checked  ";
  }else{
    $select = "" ;
  }

  echo "<tr>";
  echo "<td><input type='checkbox' $select name='usu_$at10_usuario' value='$at10_usuario'></td>";
  echo "<td><strong>$at10_login</strong></td>";
  echo "<td><strong>$at10_nome</strong></td>";

  if($i+1 <= pg_numrows($result)){
    db_fieldsmemory($result,$i+1);

  $res = $clclientesmodulosprocusu->sql_record($clclientesmodulosprocusu->sql_query_file(null,"*",null," at76_usuario = $at10_usuario"));
  if( $clclientesmodulosprocusu->numrows>0 ){
    $select = " checked ";
  }else{
    $select = "" ;
  }



    echo "<td><input type='checkbox' $select name='usu_$at10_usuario' value='$at10_usuario'></td>";
    echo "<td><strong>$at10_login</strong></td>";
    echo "<td><strong>$at10_nome</strong></td>";
  }
  
  echo "</tr>";
}
echo "</table>";
?>
     </td>
   </tr>
</table>
</form>
</body>
</html>