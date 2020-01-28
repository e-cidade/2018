<?php

/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

/*Require plugin SMSAgendamento - SMSAgendamentoConsulta - NÃO APAGAR*/

db_postmemory($HTTP_POST_VARS);

$sd02_i_codigo            = db_getsession("DB_coddepto");
$oDaoagendamentos         = new cl_agendamentos_ext;
$oDaoagendaconsultaanula  = new cl_agendaconsultaanula;
$oDaoundmedhorario        = new cl_undmedhorario_ext;

db_app::load("prototype.js");

$oDaoagendaconsultaanula->rotulo->label();

$iUsuario = db_getsession('DB_id_usuario');

if( isset( $confirmar ) ) {

  $iNumAgendamentosAnular = count($select_agendamento);

	if( $iNumAgendamentosAnular > 0 ) {
 
    $sMotivo = $s114_v_motivo;

    $oDaoagendaconsultaanula->s114_v_motivo = $s114_v_motivo;
    $oDaoagendaconsultaanula->s114_i_login  = $iUsuario;
    $oDaoagendaconsultaanula->s114_c_hora   = date('H:i');
    $oDaoagendaconsultaanula->s114_d_data   = date('Y-m-d');
    
    db_inicio_transacao();
    for( $iCont = 0; $iCont < $iNumAgendamentosAnular; $iCont++ ) {

      $oDaoagendaconsultaanula->s114_i_agendaconsulta = $select_agendamento[$iCont];

      if( $oDaoagendamentos->excluir_prontuario_agendamento( $select_agendamento[$iCont] ) ) {
        $oDaoagendaconsultaanula->s114_v_motivo .= " POSSUÍA FAA: $oDaoagendamentos->sd23_i_codigo";
      }

      if($oDaoagendamentos->erro_status == '0') {

        $oDaoagendamentos->erro( true, false );
        db_fim_transacao(true);
        $iCont = -10;
        break;
      }

      $oDaoagendaconsultaanula->incluir(null);
      if($oDaoagendaconsultaanula->erro_status == '0') {

        $oDaoagendaconsultaanula->erro( true, false );
        db_fim_transacao(true);
        $iCont = -10;
        break;
      }
      
      /*Inclusão código Plugin SMS Cancelamento de Consulta - NÃO APAGAR*/

      $oDaoagendaconsultaanula->s114_v_motivo = $sMotivo;
    }

		if( $iCont >= 0 ) {
      
      db_fim_transacao();
    	db_msgbox('Agendamento(s) anulado(s) com sucesso.');				
  	  db_redireciona("sau4_agendamentoanula001.php");
    }
  }
}

$db_opcao = 1;
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
<table align="center" width="65%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
        <?php
          require_once(modification("forms/db_frmagendamentoanula.php"));
        ?>
    </center>
    </td>
  </tr>
</table>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>
<script>
  js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);
</script>