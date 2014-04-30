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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');

if (isset($z01_i_cgsund)) {
	
	$oDaoProntProced = db_utils::getdao('prontproced');
  $sCampos         = ' sd24_i_codigo, s102_i_agendamento as dl_Codigo_agenda, sd29_d_data as dl_data_Consulta, ';
  $sCampos        .= ' sd29_c_hora, coddepto as dl_codigo, ';
  $sCampos        .= ' descrdepto as dl_ups, sd03_i_codigo as dl_codigo, z01_nome as dl_profissional, '; 
  $sCampos        .= ' rh70_estrutural as dl_codigo, rh70_descr as dl_especialidade, ';
  $sCampos        .= ' login as dl_usuario, sd29_d_cadastro as dl_data, sd29_c_cadastro as dl_hora ';
  $sOrderBy        = ' sd29_d_data desc, sd29_c_hora desc, sd24_i_codigo desc';
  $sSql            = $oDaoProntProced->sql_query_consulta_geral(null, $sCampos, $sOrderBy, 
                                                                 ' sd24_i_numcgs = '.$z01_i_cgsund
                                                                );
}
  
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br>
  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
          <fieldset style='width: 92%;'> <legend><b>Prontuarios do Paciente</b></legend> 
            <table border="0" width="90%">
              <tr>
                <td>
                  <?
                    db_input('z01_i_cgsund', 10, '', true, 'hidden', 3, '');
                    if ($sSql != "") {
                    	
                    	global $cor1;
                      global $cor2;
                      $cor1 = "#FFFAF0";
                      $cor2 = "#FFFAF0"; 
                      db_lovrot($sSql,$iLinhas,"()","","");
                      
                    }
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </center>
      </td>
    </tr>
  </table>
</center>
</body>
</html>