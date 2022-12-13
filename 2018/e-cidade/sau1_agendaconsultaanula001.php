<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once(modification("classes/db_agendaconsultaanula_classe.php"));
require_once(modification("classes/db_agendamentos_classe.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

/*Require plugin SMSAgendamento - SMSAgendamentoConsulta - NÃO APAGAR*/

db_postmemory($HTTP_POST_VARS);

$oDaoAgendaConsultaAnula = new cl_agendaconsultaanula();
$oDaoagendamentos        = new cl_agendamentos();

$db_opcao = 1;
$db_botao = true;

$s114_d_data_ano  = date('Y');
$s114_d_data_mes  = date('m');
$s114_d_data_dia  = date('d');

if (isset($incluir)) {  

  db_inicio_transacao();
  
  if ($oDaoagendamentos->excluir_prontuario_agendamento($s114_i_agendaconsulta)) {
    $oDaoAgendaConsultaAnula->s114_v_motivo = $s114_v_motivo." POSSUÍA FAA: $oDaoagendamentos->sd23_i_codigo ";
  }

  if ($oDaoagendamentos->erro_status == '0') {

    $oDaoagendamentos->erro(true, false);
    db_fim_transacao(true);
  } else {

    $oDaoAgendaConsultaAnula->s114_i_login = db_getsession('DB_id_usuario');
    $oDaoAgendaConsultaAnula->s114_c_hora  = date('H:i');
    $oDaoAgendaConsultaAnula->s114_d_data  = date('Y-m-d');
    $oDaoAgendaConsultaAnula->incluir(null);
    
    /*Inclusão código Plugin SMS Cancelamento de Consulta - NÃO APAGAR*/
    
  }

  db_fim_transacao($oDaoAgendaConsultaAnula->erro_status == '0');
}
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
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
      <fieldset><legend><b>Anulação</b></legend>  
        <?php
        require_once(modification("forms/db_frmagendaconsultaanula.php"));
        ?>
      </fieldset>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "s114_v_motivo", true, 1, "s114_v_motivo", true);
</script>
<?php
if (isset($incluir)) {

  if ($oDaoAgendaConsultaAnula->erro_status == "0") {

    $oDaoAgendaConsultaAnula->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($oDaoAgendaConsultaAnula->erro_campo!="") {

      echo "<script> document.form1.".$oDaoAgendaConsultaAnula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAgendaConsultaAnula->erro_campo.".focus();</script>";
    }
  } else {
  ?>
    <script>
      if ( parent.document.getElementById('framecalendario') != undefined ) {
        parent.document.getElementById('framecalendario').contentDocument.location.reload(true);
      }

      if ( parent.document.getElementById('frameagendados') != undefined ) {
        parent.document.getElementById('frameagendados').contentDocument.location.reload(true);
      }

      iIdJanela = '<?=@$iIdJanela?>';
      if (iIdJanela == undefined || iIdJanela == '') {

        parent.db_iframe_agendamento.hide();

        parent.document.form1.sd23_i_codigo.value    = '';
        parent.document.form1.z01_i_cgsund.value     = '';
        parent.document.form1.z01_v_nome.value       = '';
        parent.document.form1.z01_v_nome.value       = '';
        parent.document.form1.s115_c_cartaosus.value = '';
        parent.document.form1.z01_v_telcel.value     = '';
        parent.document.form1.anula.disabled         = true;
        parent.document.form1.faa.disabled           = true;
        parent.document.form1.prontuario.disabled    = true;
        parent.document.form1.comprovante.disabled   = true;
        parent.document.form1.nova.disabled          = true;
      } else {
        eval('parent.db_iframe_agendamento'+iIdJanela+'.hide()');
      }

      alert('Agendamento anulado!');
    </script><?php
  }
}