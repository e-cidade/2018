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

include("classes/db_orcimpactorecmov_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcimpactorecmov     = new cl_orcimpactorecmov;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <table> 
	<tr>
	  <td valign="top"  align="center">  
	  <?
		if(isset($db_opcaoal)){
		   $db_opcao=33;
		    $db_botao=false;
		}else if(isset($opcao) && $opcao=="alterar"){
		    $db_botao=true;
		    $db_opcao = 2;
               
		    
		}else if(isset($opcao) && $opcao=="excluir"){
		    $db_opcao = 3;
		    $db_botao=true;
		}else{  
		    $db_opcao = 1;
		    $db_botao=true;
		} 
		if(isset($opcao)){
		    
		    echo "<script>";
		    echo "
			top.corpo.iframe_orcimpactorecmov.document.form1.o69_codperiodo.value = $o69_codperiodo;
			top.corpo.iframe_orcimpactorecmov.document.form1.o69_proces.value = $o69_proces;

			obj=top.corpo.iframe_orcimpactorecmov.document.createElement('input');
			obj.setAttribute('name','opcao');
			obj.setAttribute('type','hidden');
			obj.setAttribute('value','$opcao');
			top.corpo.iframe_orcimpactorecmov.document.form1.appendChild(obj);
			top.corpo.iframe_orcimpactorecmov.document.form1.submit();
                        parent.mo_camada('orcimpactorecmov');
		         ";
		    echo "</script>";
		}   
		    
	       $chavepri= array("o69_codperiodo"=>@$o69_codperiodo,"o69_proces"=>@$o69_proces);


               $cliframe_alterar_excluir->chavepri=$chavepri;
	       $cliframe_alterar_excluir->sql     = $clorcimpactorecmov->sql_query_compl(null,"o69_sequen,o69_codperiodo,o69_exercicio,o57_fonte,o69_valor,o69_proces,o15_codigo,o15_descr","o69_sequen","o69_codperiodo =$o69_codperiodo");
	       
               $cliframe_alterar_excluir->campos  ="o69_sequen,o69_codperiodo,o69_exercicio,o57_fonte,o69_valor,o69_proces,o15_codigo,o15_descr";
	       $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	       $cliframe_alterar_excluir->iframe_height ="340";
	       $cliframe_alterar_excluir->iframe_width ="700";
	       $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	  ?>
	  </td>
	 </tr>
      </table>    
    </center>
    </td>
  </tr>
</table>
</body>
</html>