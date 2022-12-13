<?
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptubase_classe.php");

$oGet = db_utils::postMemory($_GET);

$cliptubase = new cl_iptubase;
$clrotulo   = new rotulocampo;

$cliptubase->rotulo->label();

$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j39_numero");
$clrotulo->label("j39_compl");
$clrotulo->label("j34_area");
$clrotulo->label("j40_refant");
$clrotulo->label("j36_testad");
$clrotulo->label("j32_descr");

$clrotulo->label("j04_matricregimo");
$clrotulo->label("j04_quadraregimo");
$clrotulo->label("j04_loteregimo");


if ( isset($oGet->matric) && trim($oGet->matric) != "" ) {
	
  $sCampos  = "z01_nome, j01_matric, j40_refant, j15_numero, j39_compl, j34_setor, j34_quadra, j34_lote, j34_area";
  $sCampos .= ", j36_testad, j04_matricregimo, j04_quadraregimo, j04_loteregimo";
  $sCampos .= ", case                                                ";
  $sCampos .= "    when j39_numero is null then                      ";
  $sCampos .= "         (case                                        ";
  $sCampos .= "              when j15_numero is null then 0          ";
  $sCampos .= "              else j15_numero                         ";
  $sCampos .= "         end)                                         ";
  $sCampos .= "    else j39_numero                                   ";
  $sCampos .= "  end as j39_numero                                   ";
  $sCampos .= ", case                                                ";
  $sCampos .= "     when ruase.j14_codigo is null then ruas.j14_nome ";
  $sCampos .= "     else ruase.j14_nome                              ";
  $sCampos .= "  end as j14_nome                                     ";
  
  $rsConsultaDadosMatric = $cliptubase->sql_record($cliptubase->sql_query_regmovel($oGet->matric, $sCampos));

  if ( $cliptubase->numrows > 0 ) {
  	 db_fieldsmemory($rsConsultaDadosMatric,0);
  }
	
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <center> 
  	<form name="form1" method="post" action="" >
  	  <table>
	    <tr>
	      <td align="center"> 
	        <b>I.T.B.I. URBANO</b>
	      </td>
	    </tr>
	    <tr>
	      <td>
		    <fieldset>
		      <legend>
		        <b>Identificação do Imóvel</b>
		      </legend>
		      <table>
		        <tr> 
		 	      <td>
		 	        <fieldset>
		 	          <legend>
		 	            <b>Localização na Prefeitura</b>
		 	          </legend>
		 	          <table>
		 	            <tr width="45%">
		 	              <td>
		 	                <b>Proprietário</b>
		 	              </td>
		 	              <td colspan="3">
		 	                <?
					          db_input("z01_nome",80,$Iz01_nome,true,"text",3,"");
		 	                ?>
		 	              </td>
		 	            </tr>
		 	            <tr>
		 	              <td>
		 	                <b>Matrícula</b>
		 	              </td>
		 	              <td>
		 	                <?
					          db_input("j01_matric",10,$Ij01_matric,true,"text",3,""); 	                
		 	                ?>
		 	              </td>
		 	              <td align="right">
		 	                <b>Ref. Anterior</b>
		 	              </td>
		 	              <td align="right">
		 	                <?
					          db_input("j40_refant",10,$Ij40_refant,true,"text",3,""); 	                
		 	                ?>
		 	              </td>
		 	            </tr>
		 	            <tr>
		 	              <td>
		 	                <b>Logradouro</b>
		 	              </td>
		 	              <td colspan="3">
		 	                <?
					          db_input("j14_nome",80,$Ij14_nome,true,"text",3,""); 	                
		 	                ?>
		 	              </td>
		 	            </tr>
		 	            <tr>
		 	              <td>
		 	                <b>Número</b>
		 	              </td>
		 	              <td>
		 	                <?
					          db_input("j39_numero",10,$Ij39_numero,true,"text",3,""); 	                
		 	                ?>
		 	              </td>
		 	              <td align="right">
		 	                <b>Complemento</b>
		 	              </td>
		 	              <td align="right">
		 	                <?
							  db_input("j39_compl",10,$Ij39_compl,true,"text",3,"");	 	                
		 	                ?>
		 	              </td>
		 	            </tr> 	          
		 	            <tr>
		 	              <td>
		 	                <b>Setor/Quadra/Lote</b>
		 	              </td>
		 	              <td colspan="3">
		 	                <?
							  db_input("j34_setor" ,10,$Ij34_setor,true,"text",3,"");
							  db_input("j34_quadra",10,$Ij34_quadra,true,"text",3,"");
							  db_input("j34_lote"  ,10,$Ij34_lote,true,"text",3,""); 	                 
		 	                ?>
		 	              </td>
		 	            </tr> 	            
		 	            <tr>
		 	              <td>
		 	                <b>Área</b>
		 	              </td>
		 	              <td>
		 	                <?
							  db_input("j34_area",10,$Ij34_area,true,"text",3,""); 	                
		 	                ?>
		 	                <b>m²</b>
		 	              </td>
		 	              <td align="right">
		 	                <b>Frente</b>
		 	              </td>
		 	              <td align="right">
		 	                <?
							  db_input("j36_testad",10,$Ij36_testad,true,"text",3,""); 	                
		 	                ?>
		 	                <b>m²</b>
		 	              </td>
		 	            </tr> 	          
		 	          </table>
		 	        </fieldset>
		 	      </td>
		 	    </tr>
		 	    <tr>
		 	      <td>
		 	        <fieldset>
		 	          <legend>
		 	            <b>Localização no Registro de Imóveis</b>
		 	          </legend>
		 	          <table>
		 	            <tr>
		 	              <td width="45%">
		 	                <b>Matrícula</b>
		 	              </td>
		 	              <td>
		 	                <?
							  db_input("j04_matricregimo",10,$Ij04_matricregimo,true,"text",3,""); 	                 
		 	                ?>
		 	              </td>
		 	              <td>
		 	                <b>Quadra</b>
		 	              </td>
		 	              <td>
		 	                <?
							  db_input("j04_quadraregimo",10,$Ij04_quadraregimo,true,"text",3,""); 	                
		 	                ?>
		 	              </td>
		 	              <td align="right">
		 	                <b>Lote</b>
		 	              </td>
		 	              <td align="right">
		 	                <?
							  db_input("j04_loteregimo",10,$Ij04_loteregimo,true,"text",3,""); 	                
		 	                ?>
		 	              </td> 	              
		 	            </tr>
		 	            <tr>
		 	              <td>
		 	                <b>Setor/Bairro</b>
		 	              </td>
		 	              <td colspan="5">
		 	                <?
							  db_input("j32_descr",80,$Ij32_descr,true,"text",3,""); 	                
		 	                ?>
		 	              </td>
		 	            </tr> 	            
		 	          </table>
		 	        </fieldset>
	 	      	  </td>
	   	        </tr>    
	   	      </table>
	        </fieldset>
	      </td>
	    </tr>
	    <tr align="center">
	      <td>
	        <b>O imóvel corresponde ao desejado para solicitar avaliação ?</b>
	      </td>
	    </tr>
	    <tr align="center">
	      <td>
	        <input type="button" name="confirmar" value="Confirmar" onClick="js_confirmar();">
	        <input type="button" name="voltar"    value="Voltar"    onClick="js_voltar();">
	      </td>
	    </tr>
	  </table>      
	</form>
  </center>
</body>
<script>
  function js_voltar(){
    location.href = "func_matricitbi.php?funcao_js=<?=$oGet->funcao_anterior?>&valida=true";
  }
  
  function js_confirmar(){  
  
    var sQuery  = "funcao_js=<?=$oGet->funcao_anterior?>";
    	sQuery += "&chave_j01_matric="+document.form1.j01_matric.value;
    	sQuery += "&valida=false";
    
    location.href = "func_matricitbi.php?"+sQuery;
  }
</script>
</html>