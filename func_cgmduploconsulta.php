<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once('libs/db_utils.php');
require_once ('libs/db_app.utils.php');
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgmcorreto_classe.php");
$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo();
$clrotulo->label("z10_numcgm");
$clrotulo->label("z11_numcgm");
$clrotulo->label("z11_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt_cgmcorreto");
$clrotulo->label("DBtxt_cgmerrado");

$clcgmcorreto = new cl_cgmcorreto();

db_app::load('prototype.js');
?>

<html>
	<head>
	 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	 <link href="estilos.css" rel="stylesheet" type="text/css">
	 <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	 </head>
	
	 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	
		
		<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
		  <tr> 
		    <td  align="center" valign="top">
		      
		       <fieldset style="width: 44%"><legend><b>Dados CGM duplo processado</b></legend> 
		      <form  id="form2" name="form2" method="post" action="" >
		        <table  border="0" align="center" cellspacing="0">
		        
		          <tr> 
		            <td width="4%" align="left" nowrap title=" ">
		             <?=$LDBtxt_cgmcorreto ?>
		            </td>
		            <td width="96%"  nowrap> 
		              <?
		               $DBtxt_cgmcorreto = $oGet->z10_numcgm;
		              //db_input("k65_descricao",50,$Ik65_descricao,true,"text",4,"","chave_k65_descricao");
		                db_input("DBtxt_cgmcorreto",10,$oGet->z10_numcgm,true,"text",4,"","");
		              ?>
		            </td>
		            
		            <td   nowrap>
		              <?=$Lz01_nome ?>
		            </td>
		            <td>
		              <?
		                $DBtxt_z01_nome = $oGet->z01_nome;
		                db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","");
		              ?>
		            </td>
		          </tr>   
		          <tr> 
		            <td  align="right" nowrap title="">
		              <b>Data de processamento:</b>
		            </td>
		            <td  align="left" nowrap> 
		              <?
		                $z10_data = $oGet->z10_data;
		               db_input("z10_data",10,$oGet->z10_data,true,"text",4,"","");
		              ?>
		            </td>
		            
		            <td  alig="left" nowrap>
		              <b>Hora:</b>
		            </td>
		            <td>
		              <?
		                $z10_hora = $oGet->z10_hora;
		                db_input("z10_hora",10,$oGet->z10_hora,true,"text",4,"","");
		              ?>
		            </td>   
		          </tr>
		          
		          <tr>
		            <td>
		              <b>Usuário</b>
		            </td>
		          <td colspan="3">
		             <?
		              $usuario = $oGet->usuario;
		              db_input("usuario",40,$oGet->usuario,true,"text",4,"","");
		             ?>
		          </td>
		          </tr>
		          
		          <tr> 
		            <td colspan="4" align="center"> 
		              <input name="voltar" type="submit" id="voltar" value="voltar" onClick="parent.func_cgmduploconsulta.hide();"> 
		             </td>
		          </tr>
		          </table>
		           </form> 
		          </fieldset>
		         
		      </td>
		  </tr>
		  <tr>
		  </table>
		
		  <center>
		  <fieldset style="width: 42%"><legend><b>Dados CGM errado</b></legend>
		 <table border="0" align="center" cellspacing="0">
		  <tr>
		    <td>
		        <?
		     
		        $sWhere = "";
		        $sAnd = "";
		           	
		          $sWhere = " {$sAnd} z10_codigo = $oGet->z10_codigo";
		  
		          $sCampos         = "z11_numcgm as dl_cgmerrado,  ";
		          $sCampos        .= "z11_nome as dl_nomeerrado   ";
		
		          $sSqlCgmCorreto  = $clcgmcorreto->sql_query_cgmduploprocessado(null, $sCampos, null, $sWhere);
		        
		          db_lovrot($sSqlCgmCorreto,15,"()","",'');
		   
		      ?>
		   </td>
		   </tr>
		   
		  <tr>
		  <td>
		  <input id="teste" type="text">
		  </td>
		  </tr>
		   
		   </table>
		   </fieldset>
		  </center>
		 
	</body>
</html>
<script>
  window.onload = function(){
    
    $('form2').disable();
    $('voltar').enable();
    
  } 
</script>