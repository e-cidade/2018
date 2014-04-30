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
  
  $oDaoFarRetiradaitens = db_utils::getdao('far_retiradaitens');
  $sCampos  = "fa04_d_data,";
  $sCampos .= " case when fa04_tiporetirada = 1 and tipo = 1 then"; 
  $sCampos .= "     'Normal'::char(30) ";
  $sCampos .= " else case when fa04_tiporetirada = 2 and tipo = 1 then "; 
  $sCampos .= "        'Não padronizada'::char(30) ";
  $sCampos .= "      else case when fa23_i_cancelamento = 2 and tipo = 2 then "; 
  $sCampos .= "             'Devolução'::char(30) ";
  $sCampos .= "           else case when fa23_i_cancelamento = 1 and fa04_tiporetirada = 1 and tipo = 2 then "; 
  $sCampos .= "                  'Cancelamento'::char(30) ";
  $sCampos .= "                else ";
  $sCampos .= "                  'Cancelamento N. P.'::char(30) ";
  $sCampos .= "                end ";
  $sCampos .= "           end ";
  $sCampos .= "      end "; 
  $sCampos .= " end as dl_tipo_de_retirada, ";
  $sCampos .= " fa01_i_codigo, m60_descr as dl_medicamento, "; 
  $sCampos .= " fa06_f_quant, m77_lote as dl_lote, m77_dtvalidade as dl_validade, fa07_i_matrequi,";
  $sCampos .= " fa23_c_motivo as dl_motivo_da_devolucao, login as dl_usuario"; 
  $sSql     = $oDaoFarRetiradaitens->sql_query_historicoretiradasdevolucoes($z01_i_cgsund, 
                                                                            $sCampos, 
                                                                            'fa06_i_codigo desc, tipo asc'
                                                                           );
}
  
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
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
          <fieldset style='width: 92%;'> <legend><b>Histórico de retirada de medicamentos</b></legend> 
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