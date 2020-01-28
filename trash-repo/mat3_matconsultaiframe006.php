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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
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
  function js_devolucao(codigo){
    js_OpenJanelaIframe('top.corpo','db_iframe_devolucao','mat3_consultadevolucao001.php?codigo='+codigo,'Consulta Devolução',true);
  }
  function js_atendrequi(codigo){
    js_OpenJanelaIframe('top.corpo','db_iframe_atendrequi','mat3_consultaatendrequi001.php?codigo='+codigo,'Consulta Atendimento da Requisição',true);
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
	        echo "<input type='button' value='Voltar' onclick='parent.db_iframe_devolucoes.hide();' >";
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
	$where.="  and m45_depto=$depto_atual ";
      }else{
	$where.=" and $db_where  "; 
      }
    }
    if (isset($db_inner)&&$db_inner!=""){
      $inner="  $db_inner  "; 
    }else{
      $inner="";
    }
    $sql= "select distinct m45_codigo,
		  m45_data,
		  m45_hora,
		  m45_depto,
		  descrdepto,
		  m45_login,
		  nome,
		  m45_obs,
		  m42_codigo
	  from matestoquedev 
		inner join matestoquedevitem on m46_codmatestoquedev = m45_codigo 
		inner join matrequiitem on m46_codmatrequiitem = m41_codigo
		inner join db_depart on m45_depto = coddepto 
		inner join db_usuarios on m45_login = id_usuario 
		inner join atendrequi on m45_codatendrequi = m42_codigo
		$inner
	where m41_codmatmater = $codmater $where";
  //db_lovrot($sql,15,"()","","js_devolucao|m45_codigo");
  }
  }
  $repassa = array('dblov'=>'0');
  db_lovrot(@$sql,15,"()","","","","NoMe",$repassa);
	      
  /*$result=pg_exec($sql);
  $numrows = pg_numrows($result);
  if($numrows>0){
    echo "<tr class='bordas'>
	      <td class='bordas' align='center'><b><small>Devolução</small></b></td>
	      <td class='bordas' align='center'><b><small>Data</small></b></td>
	      <td class='bordas' align='center'><b><small>Hora</small></b></td>
	      <td class='bordas' align='center'><b><small>Departamento</small></b></td>
	      <td class='bordas' align='center'><b><small>Usuário</small></b></td>
	      <td class='bordas' align='center'><b><small>Observação</small></b></td>
	      <td class='bordas' align='center'><b><small>Atendimento</small></b></td>
          </tr>";
  }else echo"<b>Nenhum registro encontrado...</b>";
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
     echo "
           <tr>	    
	     <td class='bordas_corp' align='center'><small>";
	     db_ancora($m45_codigo,"js_devolucao($m45_codigo);",1);
     echo "  </small></td>
	     <td class='bordas_corp' align='center'><small>".db_formatar($m45_data,'d')."</small></td>		    
	     <td class='bordas_corp' align='center'><small>$m45_hora</small></td>
	     <td class='bordas_corp' align='center'><small>$m45_depto-$descrdepto</small></td>
	     <td class='bordas_corp' align='center'><small>$nome</small></td>
	     <td class='bordas_corp' align='center'><small>".substr($m45_obs,0,20)."&nbsp;</small></td>
	     <td class='bordas_corp' align='center'><small>";
	     db_ancora($m45_codatendrequi,"js_atendrequi($m45_codatendrequi);",1);
     echo "  </small></td>
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