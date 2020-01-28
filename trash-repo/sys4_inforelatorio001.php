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
include("libs/db_utils.php");
include("model/dbGeradorRelatorio.model.php");
include("model/dbPropriedadeRelatorio.php");
include("model/dbVariaveisRelatorio.php");
include("model/dbFiltroRelatorio.php");
include("model/dbColunaRelatorio.php");
include("model/dbOrdemRelatorio.model.php");
include("classes/db_db_relatorio_classe.php");
include("classes/db_db_syscampo_classe.php");


$oGet = db_utils::postMemory($_GET);

$cldb_relatorio    = new cl_db_relatorio();
$cldb_syscampo 	   = new cl_db_syscampo();
$oGeradorRelatorio = new dbGeradorRelatorio($oGet->codrel);


$rsConsultaRelatorio = $cldb_relatorio->sql_record($cldb_relatorio->sql_query($oGet->codrel));
$oConsultaRelatorio  = db_utils::fieldsMemory($rsConsultaRelatorio,0);

$codRel  	  = $oConsultaRelatorio->db63_sequencial;
$nomeRel 	  = $oConsultaRelatorio->db63_nomerelatorio;
$tipoRel 	  = $oConsultaRelatorio->db13_sequencial;
$descrTipo  = $oConsultaRelatorio->db13_descricao;
$grupoRel   = $oConsultaRelatorio->db14_sequencial;
$descrGrupo = $oConsultaRelatorio->db14_descricao;
$dataRel	  = $oConsultaRelatorio->db63_data;

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table align="center">
    <tr>
      <td>
  		<fieldset>
  		  <legend>
  		  	<b>Informações Relatório</b>
   		  </legend>
  		  <table>
			<tr>
			  <td>
			    <b>Código Relatório:</b>
			  </td>
			  <td>
			  	<?
				  db_input("codRel",44,"",true,"text",3,"");
			  	?>
			  </td>   		  
   		    </tr>  		  
			<tr>
			  <td>
			    <b>Nome Relatório:</b>
			  </td>
			  <td>
			  	<?
				  db_input("nomeRel",44,"",true,"text",3,"");
			  	?>
			  </td>   		  
   		    </tr>
			<tr>
			  <td>
			    <b>Tipo:</b>
			  </td>
			  <td>
			  	<?
				  db_input("tipoRel"  ,5,"",true,"text",3,"");  	
			  	  db_input("descrTipo",35,"",true,"text",3,"");
			  	?>
			  </td>   		  
   		    </tr>
			<tr>
			  <td>
			    <b>Grupo:</b>
			  </td>
			  <td>
			  	<?
				  db_input("grupoRel"  ,5,"",true,"text",3,"");
				  db_input("descrGrupo",35,"",true,"text",3,"");
			  	?>
			  </td>   		  
   		    </tr>   		       		    
			<tr>
			  <td>
			    <b>Ultima Alteração:</b>
			  </td>
			  <td>
			  	<?
			  	  
			  	  $sDia = substr($dataRel,8,2);
			  	  $sMes = substr($dataRel,5,2);
			  	  $sAno = substr($dataRel,0,4);
			  	  
				  db_inputdata("dateRel",$sDia,$sMes,$sAno,true,"text",3,"");
				  
			  	?>
			  </td>   		  
   		    </tr>
   		  </table>
        </fieldset>
      </td>
    </tr>
	<?
	  if ($oConsultaRelatorio->db63_db_tiporelatorio == 2 or true ) {
	?>
	<tr>
	  <td>
	    <fieldset>
	      <legend align="center">
	      	<b>Variáveis Documento</b>
	      </legend>
	      <table cellspacing="0" style="border:2px inset white;" >
		    <tr>
		  	  <th class="table_header" width="200px"><b>Descrição Campo</b></th>
		      <th class="table_header" width="200px"><b>Nome Variável</b></th>
		      <th class="table_header" width="12px" ><b>&nbsp;</b></th>
		    </tr>
		  <tbody id="relatoriosSalvos" style=" height:200px; overflow:scroll; overflow-x:hidden; background-color:white"  >
		  <?
			
		  $aColunas = $oGeradorRelatorio->getColunas();

			foreach ( $aColunas as $sInd => $oColuna ) {

				if ( $oColuna->getAlias() != '' ) {
          $sDescricao = $oColuna->getAlias(); 
        } else {
        	$sDescricao = '&nbsp;';
        }
          
	      echo "<tr>";
	      echo "<td class='linhagrid' style='text-align:left'>".$sDescricao."</td>";
	      echo "<td class='linhagrid'>".$oColuna->getNome()."</td>";
	      echo "</tr>";
	      
			}
			
	    $aVariaveis = $oGeradorRelatorio->getVariaveis();

      foreach ( $aVariaveis as $sIndVar => $oVariavel ) {

        if ( $oVariavel->getLabel() != '' ) {
          $sDescricao = $oVariavel->getLabel(); 
        } else {
          $sDescricao = '&nbsp;';
        }
        
        echo "<tr>";
        echo "<td class='linhagrid' style='text-align:left'>".$sDescricao."</td>";
        echo "<td class='linhagrid'>".$oVariavel->getNome()."</td>";
        echo "</tr>";
        
      }			
			
			$aVariaveisPadrao = array();
	    $aVariaveisPadrao['$db_codigoinst']  = "Codigo da Instituição";
	    $aVariaveisPadrao['$db_nomeinst']  = "Nome da Instituição";
	    $aVariaveisPadrao['$db_logo']      = "Logotipo da Instituição";
	    $aVariaveisPadrao['$db_enderinst'] = "Endereço da Instituição";
	    $aVariaveisPadrao['$db_municinst'] = "Município da Instituiçao";
	    $aVariaveisPadrao['$db_ufinst']    = "UF da Instituiçao";
	    $aVariaveisPadrao['$db_foneinst']  = "Fone da Instituiçao";
	    $aVariaveisPadrao['$db_emailinst'] = "Email da Instituiçao";
	    $aVariaveisPadrao['$db_siteinst']  = "Site da Instituiçao";

	    $aVariaveisPadrao['$db_data_atual_extenso']   = "Data atual por extenso";

	    $aVariaveisPadrao['$db_base']   = "Nome da base de dados";

	    $aVariaveisPadrao['$db_id_usuario'] = "id do usuário";
	    $aVariaveisPadrao['$db_login']   = "Login do usuário";
	    $aVariaveisPadrao['$db_nomeusu']   = "Nome do usuário";
	    $aVariaveisPadrao['$db_anousu']    = "Exercício que está acessado";
	    $aVariaveisPadrao['$db_datausu']   = "Data da sessão";
	    $aVariaveisPadrao['$db_horausu']   = "Hora da sessão";

	    $aVariaveisPadrao['$db_coddepto']   = "Codigo do departamento";

	    $aVariaveisPadrao['$db_matriculafolhausu']   = "Matricula da folha de pagamento";
	    $aVariaveisPadrao['$db_cargofolhausu']   = "Cargo da folha de pagamento";

	    foreach ( $aVariaveisPadrao as $sNomeVar => $sDescricao ) {
        echo "<tr>";
        echo "<td class='linhagrid' style='text-align:left'>{$sDescricao}</td>";
        echo "<td class='linhagrid'>{$sNomeVar}</td>";
        echo "</tr>";
      }	    
	    
			echo "<tr><td style='height:100%;'>&nbsp;</td></tr>";
			
		  ?>
		  </tbody>
	    </fieldset>
	  </td>
	</tr>
	<?
	  }
	?>	
  </table>
</body>
</html>