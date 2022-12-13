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

require_once("classes/db_obrastec_classe.php");
require_once("classes/db_obrastecnicos_classe.php");

$oDaoObrasTec      = new cl_obrastec;
$oDaoObrasTecnicos = new cl_obrastecnicos;

/**
 * Solicitação tecnico
 */   
$sqlObrasTecnicos = $oDaoObrasTecnicos->sql_query(null, "z01_nome,ob15_crea,ob15_numcgm", "", "ob20_codobra = $parametro");
$rsTecnicos       = $oDaoObrasTecnicos->sql_record($sqlObrasTecnicos); 

if($oDaoObrasTecnicos->numrows > 0){
  
  $oTecnicos = db_utils::fieldsMemory($rsTecnicos, 0);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <style>
    #elemento_principal {
      width: 100%;
    } 
    #elemento_principal tr td:first-child {
      width: 150px;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <br />
  <br />
	<fieldset style="width:95%; margin: 0 auto;">
	  <legend><B>Técnico Responsável: </B></legend>
	  <table id="elemento_principal">
	    <tr> 
	      <td nowrap>
	        <b><?php db_ancora('Numcgm:','js_mostracgm();',4); ?></b>
	      </td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oTecnicos->ob15_numcgm; ?></td>
	    </tr>
	    <tr> 
	      <td nowrap><strong>Nome:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oTecnicos->z01_nome; ?></td>
	    </tr>
	    <tr>
	      <td nowrap><strong>Crea:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oTecnicos->ob15_crea; ?></td>
	    </tr>
	  </table> 
	</fieldset>
  <?     

  /**
   * Se não existir técnico
   */   
} else {  
?>
  
  <br /><br />
  <center>
    <strong>Esta obra não possui técnico responsável. </strong>
  </center>
  <br /><br />
 
<?php 
}
?> 

<script>
function js_mostracgm(){
  
    func_nome.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=<?php echo $oTecnicos->ob15_numcgm; ?>';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
}
</script>
<?
	$func_nome                  = new janela('func_nome', '');
	$func_nome->posX            = 0;
	$func_nome->posY            = 0;
	$func_nome->largura         = 780;
	$func_nome->altura          = 430;
	$func_nome->titulo          = "Pesquisa";
	$func_nome->iniciarVisivel  = false;
	$func_nome->mostrar();
?>
</html>
</body>
</html>