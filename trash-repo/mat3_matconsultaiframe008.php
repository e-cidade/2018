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
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
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
	        echo "<input type='button' value='Voltar' onclick='parent.db_iframe_pontopedidos.hide();' >";
        }
      ?>
      </td>
    </tr>
  <?
  if (!isset($filtroquery)){
  if (isset($codmater)&&$codmater!="") {
    $where="";
	$depto_atual=db_getsession("DB_coddepto");
    if (isset($db_where)&&$db_where!=""){
      if ($db_where=="D"){
	$where.="  and m91_depto=$depto_atual ";
      }else{
	$where.=" and $db_where  "; 
      }
    }
    if (isset($db_inner)&&$db_inner!=""){
      $inner="  $db_inner  "; 
    }else{
      $inner="";
    }

    if (isset($flag_almox) && trim(@$flag_almox)=="true"){
         $where .= " and m91_depto = $depto_atual"; 
    }
    $sql= "select distinct m91_depto,
		  descrdepto,
      m64_estoqueminimo,
      m64_estoquemaximo,
      m64_pontopedido
	  from matmaterestoque
    inner join db_almox  on db_almox.m91_codigo = matmaterestoque.m64_almox
		inner join db_depart on db_depart.coddepto  = db_almox.m91_depto
		$inner
	where m64_matmater = $codmater $where";
  }
  }
  $resultado = @pg_query($sql);
  $numrows   = @pg_numrows($resultado);

  if ($numrows == 0){
       $sql= "select distinct m91_depto,
		                          descrdepto,
                              m64_estoqueminimo,
                              m64_estoquemaximo,
                              m64_pontopedido
              from matmaterestoque
                   inner join db_almox  on db_almox.m91_codigo = matmaterestoque.m64_almox
                	 inner join db_depart on db_depart.coddepto  = db_almox.m91_depto
                   $inner
              where m64_matmater = $codmater";
  }

  $repassa = array('dblov'=>'0');
  db_lovrot(@$sql,15,"()","","","","NoMe",$repassa);
?>     
</table>
 
</td>
</tr>
</table>
</body>
</html>