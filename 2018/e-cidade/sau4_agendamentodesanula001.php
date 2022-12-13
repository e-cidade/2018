<?php

/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("classes/db_undmedhorario_ext_classe.php"));
require_once(modification("classes/db_agendamentos_ext_classe.php"));
require_once(modification("classes/db_agendaconsultaanula_classe.php"));
require_once(modification("classes/db_agendaconsultadesanula_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
/*Require plugin SMSAgendamento - SMSAgendamentoConsulta - NÃO APAGAR*/

db_postmemory($HTTP_POST_VARS);

$sd02_i_codigo   = db_getsession("DB_coddepto");

$oDaoagendamentos  = new cl_agendamentos_ext;
$oDaoagendaconsultaanula  = new cl_agendaconsultaanula;
$oDaoagendaconsultadesanula  = new cl_agendaconsultadesanula;
$oDaoundmedhorario = new cl_undmedhorario_ext;

db_app::load("prototype.js");

$oDaoagendaconsultadesanula->rotulo->label();

$iUsuario = db_getsession('DB_id_usuario');

if(isset($confirmar)) {

  $cod_undmedhorario = $lado_de;
 
  $aDataConsulta = explode('/', $sd23_d_consulta);
	$rsTotalAgendado = $oDaoundmedhorario->sql_record( "select fc_totalagendado('$sd23_d_consulta_ano/$sd23_d_consulta_mes/$sd23_d_consulta_dia', $cod_undmedhorario);" );
	$oDadosTotalAgendado = db_utils::fieldsMemory($rsTotalAgendado, 0);

	if($oDadosTotalAgendado->fc_totalagendado == '') {
		db_msgbox("Profissional não possui agenda para a data $sd23_d_consulta");
	} else {

    if($oDadosTotalAgendado->fc_totalagendado != '') {

			$aDadosTotalAgendado = explode(',', $oDadosTotalAgendado->fc_totalagendado);
			
      $iNumAgendamentosDesanular = count($select_agendamento);

			//Verifica fichas disponiveis
			if($aDadosTotalAgendado[6] >= $iNumAgendamentosDesanular) {

        if($iNumAgendamentosDesanular > 0) {
   
          $iCont = 0;
          
          /* bloco que seta os campos de inclusao na tabela agendaconsultadesanulamento que sao estaticos na rotina */
          $oDaoagendaconsultadesanula->s151_i_logindesanulamento = $iUsuario;
          $oDaoagendaconsultadesanula->s151_c_horadesanulamento = date('H:i');
          $oDaoagendaconsultadesanula->s151_d_datadesanulamento = date('Y-m-d');
          $oDaoagendaconsultadesanula->s151_c_motivodesanulamento = $s151_c_motivodesanulamento;
            
          db_inicio_transacao();
          for($iCont = 0; $iCont < $iNumAgendamentosDesanular; $iCont++) {

            /* bloco que pega os dados do anulamento para grava-los na tabela agendaconsultadesanula */
            $sSql = $oDaoagendaconsultaanula->sql_query_file(null, '*', null, ' s114_i_agendaconsulta = '.$select_agendamento[$iCont]);
            $rsDadosAnulacao = $oDaoagendaconsultaanula->sql_record($sSql);
            $oDadosAnulacao = db_utils::fieldsmemory($rsDadosAnulacao, 0);

            /* bloco que seta os campos de inclusao na tabela agendaconsultadesanulamento que sao variaveis na rotina */
            $oDaoagendaconsultadesanula->s151_i_codigoanulamento = $oDadosAnulacao->s114_i_codigo;
            $oDaoagendaconsultadesanula->s151_d_dataanulamento = $oDadosAnulacao->s114_d_data;
            $oDaoagendaconsultadesanula->s151_c_motivoanulamento = $oDadosAnulacao->s114_v_motivo;
            $oDaoagendaconsultadesanula->s151_i_situacaoanulamento = $oDadosAnulacao->s114_i_situacao;
            $oDaoagendaconsultadesanula->s151_i_loginanulamento = $oDadosAnulacao->s114_i_login;
            $oDaoagendaconsultadesanula->s151_c_horaanulamento = $oDadosAnulacao->s114_c_hora;
            $oDaoagendaconsultadesanula->s151_i_agendamento = $select_agendamento[$iCont];
            
            $oDaoagendaconsultadesanula->incluir(null); // insere na tabela agendaconsultadesanula
            $oDaoagendaconsultaanula->excluir(null, ' s114_i_agendaconsulta = '.$select_agendamento[$iCont]);  // remove o anulamento da tabela agendaconsultaanula
    
            if($oDaoagendaconsultadesanula->erro_status == '0') {

              $oDaoagendaconsultadesanula->erro(true,false);
              db_fim_transacao(true);
              $iCont = -10;
              break;

            }
            if($oDaoagendaconsultaanula->erro_status == '0') {

              $oDaoagendaconsultaanula->erro(true,false);
              db_fim_transacao(true);
              $iCont = -10;
              break;

            }
            
            /*Inclusão código Plugin SMS Agendamento de Consulta - NÃO APAGAR*/

          }

          if($iCont >= 0) {

            db_fim_transacao();
           	db_msgbox('Agendamento(s) desanulado(s) com sucesso.');				
        	  db_redireciona("sau4_agendamentodesanula001.php");

          }

        } else {
          db_msgbox('Deve ser selecionado ao menos um agendamento para desanular.');
        }

      } else {
        db_msgbox('Nao existe quantidade disponivel para desanular os agendamentos anulados.');
      }

    } // fim do if($oDadosTotalAgendado->fc_totalagendado != '')

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
        require_once(modification("forms/db_frmagendamentodesanula.php"));
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