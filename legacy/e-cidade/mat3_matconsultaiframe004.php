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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label("");
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script>
  function js_requi(codigo){
    js_OpenJanelaIframe('top.corpo','db_iframe_requi','mat3_consultarequi001.php?codigo='+codigo,'Consulta Requisição',true);
  }
  </script>
  <style>
  <?//$cor="#999999"?>
  .bordas{
      border: 2px solid #cccccc;
      border-top-color: #999999;
      border-right-color: #999999;
      border-left-color: #999999;
      border-bottom-color: #999999;
      background-color: #999999;
  }
  .bordas_corp{
      border: 1px solid #cccccc;
      border-top-color: #999999;
      border-right-color: #999999;
      border-left-color: #999999;
      border-bottom-color: #999999;
      background-color: #cccccc;
  }
  </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
  <table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
  <td  align="center" valign="top" > 
  <table border='0'>  
    <tr>
      <td colspan=6 align=center>
      <?php 
        if (!$lNovaConsulta) {
  	      echo "<input type='button' value='Voltar' onclick='parent.db_iframe_requisicao.hide();' >";
        }
      ?>
      </td>
    </tr>
  <?
  if (!isset($filtroquery)){
  if (isset($codmater)&&$codmater!="") {
    $where="";
    if (isset($db_where)&&$db_where!=""){
      if ($db_where=="D"){
	$depto_atual=db_getsession("DB_coddepto");
	$where.="  and m40_depto=$depto_atual ";
      }else{
	$where.=" and $db_where  "; 
      }
    }
    if (isset($db_inner)&&$db_inner!=""){
      $inner="  $db_inner  "; 
    }else{
      $inner="";
    }
    $sql= "select distinct m40_codigo,
		  m40_data,
		  m40_hora,
		  m40_depto,
		  descrdepto,
		  m40_login,
		  nome,
		  m40_obs 
	  from matrequi 
		inner join matrequiitem on m41_codmatrequi = m40_codigo 
		inner join db_depart on m40_depto = coddepto		
		inner join db_usuarios on m40_login = id_usuario 
		$inner
	where m41_codmatmater = $codmater $where" ;
  }
 
  }
//  db_lovrot($sql,15,"()","","");
//$repassa = array('dblov'=>'0');
  db_lovrot(@$sql,15,"()","",'ok');
 /* $result=pg_exec($sql);
  $numrows = pg_numrows($result);
  if($numrows>0){
    echo "<tr class='bordas'>
	      <td class='bordas' align='center'><b><small>Requisição</small></b></td>
	      <td class='bordas' align='center'><b><small>Data</small></b></td>
	      <td class='bordas' align='center'><b><small>Hora</small></b></td>
	      <td class='bordas' align='center'><b><small>Departamento</small></b></td>
	      <td class='bordas' align='center'><b><small>Usuário</small></b></td>
	      <td class='bordas' align='center'><b><small>Observação</small></b></td>
          </tr>";
  }else echo"<b>Nenhum registro encontrado...</b>";
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
     echo "
           <tr>	    
	     <td class='bordas_corp' align='center'><small>";
	     db_ancora($m40_codigo,"js_requi($m40_codigo);",1);
     echo "  </small></td>
	     <td class='bordas_corp' align='center'><small>".db_formatar($m40_data,'d')."</small></td>		    
	     <td class='bordas_corp' align='center'><small>$m40_hora</small></td>
	     <td class='bordas_corp' align='center'><small>$m40_depto-$descrdepto</small></td>
	     <td class='bordas_corp' align='center'><small>$nome</small></td>
	     <td class='bordas_corp' align='center'><small>".substr($m40_obs,0,20)."&nbsp;</small></td>
	   </tr>
	   ";
  }*/

?>     
</table>
</td>
</tr>
</table>
<script>
</script>
</body>
</html>