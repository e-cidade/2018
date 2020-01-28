<?php
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
 
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_caracter_classe.php");
require_once("classes/db_obrasconstr_classe.php");
require_once("classes/db_obrasender_classe.php");
require_once("classes/db_obrashabite_classe.php");
require_once("classes/db_obrasalvara_classe.php");
require_once("classes/db_obras_classe.php");
require_once("classes/db_obrastec_classe.php");
require_once("classes/db_obrastecnicos_classe.php");

$clobrasconstr   = new cl_obrasconstr();
$clobrasender    = new cl_obrasender();
$clobrashabite   = new cl_obrashabite();
$oDaoCaracter    = new cl_caracter();

$oGet            = db_utils::postMemory($_GET);

/**
 * Sql tabela obrascontr
 */   
$sSqlObrasConstr = $clobrasconstr->sql_query_caracteristicasConstrucao($oGet->parametro);
$rsObrasConstr   = $clobrasconstr->sql_record($sSqlObrasConstr);

/**
 * Verifica se existe dados na tabela obrasconstr
 */   
if($clobrasconstr->numrows > 0) { 

  $oObrasConstr = db_utils::fieldsMemory($rsObrasConstr, 0);

  /**
   * Sql da tabela obrasender
   */   
  $sqlObrasEnder= $clobrasender->sql_query(null, "*", "", "ob07_codconstr = $oObrasConstr->ob08_codconstr"); 
  $rsObrasEnder = $clobrasender->sql_record($sqlObrasEnder);

  /**
   * Verifica se existe dados na tabela obrasender
   */   
  if($clobrasender->numrows > 0) {
    $oObrasEnder = db_utils::fieldsMemory($rsObrasEnder,0);
  }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <br />
  <br />
  <fieldset style="width:95%; margin: 0 auto;">
    <legend><B>Dados da Construção: </B></legend>
	  <table width="95%" border="0">
	    <tr> 
	      <td nowrap><strong>Construção:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasConstr->ob08_codconstr; ?></td>
	      <td nowrap><strong>Área:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasConstr->ob08_area?></td>
	    </tr>
	  
	    <tr>
	      <td nowrap><strong>Ocupação:</strong></td>
	      <td nowrap bgcolor="#FFFFFF" >
	        <?php 
	         echo $oObrasConstr->ob08_ocupacao . " - " . $oObrasConstr->ocupacao; 
	        ?>
	      </td>
	      
	      <td nowrap ><strong>Tipo de Contrução:</strong></td>
	      <td align="left" nowrap bgcolor="#FFFFFF">
	        <?php 
	         echo $oObrasConstr->ob08_tipoconstr . " - " . $oObrasConstr->construcao;  
	        ?>
	      </td>
	    </tr> 
	  
	    <tr> 
	      <td nowrap><strong>Tipo de Lançamento:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"> 
	        <?php echo $oObrasConstr->ob08_tipolanc . " - " . $oObrasConstr->tipo_lancamento; ?>
	      </td>
	
	      <td nowrap><strong>Área atual:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"> <?php echo db_formatar($oObrasEnder->ob07_areaatual,"p"); ?></td>
	
	    </tr>
	  
		  <tr> 
		    <td nowrap><strong>Cod. Rua/Avenida:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->ob07_lograd; ?></td>
		    <td colspan=2 nowrap bgcolor="#FFFFFF"> <?php echo $oObrasEnder->j14_nome; ?></td>
		  </tr>
		  
		  <tr> 
		    <td nowrap><strong>Número:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->ob07_numero; ?></td>
		    <td nowrap><strong>Complemento:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->ob07_compl; ?></td>
		  </tr>
		  
		  <tr> 
		    <td nowrap><strong>Bairro:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->ob07_bairro; ?></td>
		    <td colspan=2 nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->j13_descr; ?></td>
		  </tr>
		 
		  <tr> 
		    <td nowrap><strong>Unidade:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->ob07_unidades; ?></td>
		    <td nowrap><strong>Pavimento:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnder->ob07_pavimentos; ?></td>
		  </tr>
		  
		  <tr> 
		    <td nowrap><strong>Data inicio:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"><?php echo db_formatar($oObrasEnder->ob07_inicio, "d"); ?></td>
		    <td nowrap><strong>Data final:</strong></td>
		    <td nowrap bgcolor="#FFFFFF"> <?php echo db_formatar($oObrasEnder->ob07_fim, "d")?>&nbsp; </td>
		  </tr>
		</table>
	</fieldset> 
<?php 
  /**
   * Se não existir construção
   */   
} else {  
?>
  
  <br /><br />
  <center>
    <strong>Nenhuma construção cadastrada.</strong>
  </center>
  <br /><br />

  <?php } ?> 

</body>
</html>