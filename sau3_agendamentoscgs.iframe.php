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

if(isset($z01_i_cgsund)){
	
  $oDaoAgendamentos = db_utils::getdao('agendamentos');
  $dDataAtual       = date('Y-m-d', db_getsession('DB_datausu'));

  $sSubAtendido     = 'select sd29_i_codigo from prontagendamento inner join prontproced ';
  $sSubAtendido    .= ' on s102_i_prontuario = sd29_i_prontuario where s102_i_agendamento = sd23_i_codigo ';

  $sSubAnulado      = ' select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo limit 1';

  $sSubAnulado2     = ' select * from agendaconsultaanula inner join db_usuarios as a on ';
  $sSubAnulado2    .= ' a.id_usuario = s114_i_login where s114_i_agendaconsulta = sd23_i_codigo limit 1 ';

  $sCampos          = " sd23_i_codigo as dl_Codigo_agenda, ";
  $sCampos         .= " sd23_d_agendamento as dl_Data_Agendamento, login as usuario, ";
  $sCampos         .= " sd101_c_descr as dl_ficha, sd23_d_consulta as dl_data_Consulta, sd23_c_hora, ";
  $sCampos         .= " sd03_i_codigo as dl_codigo, z01_nome as dl_profissional, ";
  $sCampos         .= " case when exists($sSubAtendido) then 'Atendido' ";
  $sCampos         .= "   else case when sd23_d_consulta >= '$dDataAtual' then 'Agendado'";
  $sCampos         .= "          else '' end";
  $sCampos         .= " end as dl_situacao, ";
  $sCampos         .= " (select s114_d_data from  ($sSubAnulado) as tmp) as data_anulacao, ";
  $sCampos         .= " (select s114_v_motivo from  ($sSubAnulado) as tmp2) as dl_motivo_anulacao, ";
  $sCampos         .= " (select login from  ($sSubAnulado2) as tmp3) as dl_usuario ";

  $sOrderBy         = ' sd23_d_consulta desc, sd23_c_hora desc ';

  $sSql             = $oDaoAgendamentos->sql_query_consulta_geral(null, $sCampos, $sOrderBy, 
                                                                  ' sd23_i_numcgs = '.$z01_i_cgsund
                                                                 );
//   echo $sSql;
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
          <fieldset style='width: 92%;'> <legend><b>Agendamentos do Paciente</b></legend> 
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
          </fieldset>
        </center>
      </td>
    </tr>
  </table>
</center>
</body>
</html>