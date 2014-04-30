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
      <br>
	<input type='button' value='Voltar' onclick="parent.db_iframe_transferencias.hide();" >
      <br>
      <br>
      </td>
    </tr>
  <?
  if (!isset($filtroquery)){
  if (isset($codmater)&&$codmater!="") {
    $sql= "select distinct m71_codmatestoque,
		  m80_data,
		  m80_hora,
		  nome,
		  ,
		  nome,
		  m45_obs,
		  m42_codigo
	  from matestoqueitem 
		inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
		inner join matestoqueini a on a.m80_codigo = m82_matestoqueini
		inner join matestoquetransf on m83_matestoqueini = a.m80_codigo
		inner join matestoqueinil on m86_matestoqueini=a.m80_codigo
		inner join matestoqueinill on m87_matestoqueinil=m86_codigo
		inner join matestoqueini b on b.m80_codigo = m87_matestoqueinil
		left  join matestoqueitemlanc on m95_codlanc = m71_codlanc
	where m95_codlanc is null and m41_codmatmater = $codmater";
	die($sql);
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