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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cgs_classe.php");
require_once("classes/db_cgs_und_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlibwebseller.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcgs            = new cl_cgs();
$oDaoCgsCartaoSus = new cl_cgs_cartaosus();
$clcgs_und        = new cl_cgs_und();
$db_botao         = false;
$db_opcao         = 33;
$db_opcao1        = 3;

if ( isset($excluir) ) {

 db_inicio_transacao();

 validaVinculoRetirada( $z01_i_cgsund );
 validaVinculoEtnia( $z01_i_cgsund );
 validaVinculoAcompanhanteHiperdia( $z01_i_cgsund );
 validaVinculoControle( $z01_i_cgsund );
 validaVinculoDevolucao( $z01_i_cgsund );
 validaVinculoFechamentoTdfProcedimento( $z01_i_cgsund );
 validaVinculoRequisicao( $z01_i_cgsund );
 validaVinculoProntuarioMedico( $z01_i_cgsund );
 validaVinculoAgendaExames( $z01_i_cgsund );
 validaVinculoEncaminhamentos( $z01_i_cgsund );
 validaVinculoTriagemAvulsa( $z01_i_cgsund );
 validaVinculoAcompanhantes( $z01_i_cgsund );
 validaVinculoAjudaCustoPedido( $z01_i_cgsund );
 validaVinculoBeneficiadosAjudaCusto( $z01_i_cgsund );
 validaVinculoPassageiroVeiculo ( $z01_i_cgsund );
 validaVinculoPediodo( $z01_i_cgsund );
 validaVinculoAgendamentos( $z01_i_cgsund );
 validaVinculoFatorRisco( $z01_i_cgsund );
 validaVinculoEntrega( $z01_i_cgsund );
 validaVinculoProntuarios( $z01_i_cgsund );
 validaVinculoVacinaAplicada( $z01_i_cgsund );

 $db_opcao  = 3;
 $db_opcao1 = 3;
 $clcgs_und->excluir($z01_i_cgsund);
 $oDaoCgsCartaoSus->excluir('', "s115_i_cgs = {$z01_i_cgsund}");
 $clcgs->excluir($z01_i_cgsund);
 db_fim_transacao();
} else if( isset($chavepesquisa) ) {

 $db_opcao  = 3;
 $db_opcao1 = 3;
 $result    = $clcgs_und->sql_record($clcgs_und->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $db_botao = true;
}

/**
 * Valida se CGS possui alguma retirada vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoRetirada( $z01_i_cgsund ) {

  $oDaoRetirada   = new cl_far_retirada();
  $sWhereRetirada = "fa04_i_cgsund = {$z01_i_cgsund}";
  $sSqlRetirada   = $oDaoRetirada->sql_query_file("", "1", "", $sWhereRetirada);
  $rsRetirada     = db_query( $sSqlRetirada );

  if ( pg_num_rows( $rsRetirada ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui alguma retirada de medicamentos.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * Valida se CGS possui alguma etinia vinculada
 * @param  integer $z01_i_cgsund 
 */
function validaVinculoEtnia( $z01_i_cgsund ) {

  $oDaoEtnia   = new cl_cgs_undetnia();
  $sWhereEtnia = "s201_cgs_unid = {$z01_i_cgsund}";
  $sSqlEtnia   = $oDaoEtnia->sql_query_file("", "1", "", $sWhereEtnia); 
  $rsEtnia     = db_query( $sSqlEtnia );

  if ( pg_num_rows( $rsEtnia ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui alguma etnia vinculada.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * Valida se CGS possui algum acompanhante hiperdia vinculado
 * @param  integer $z01_i_cgsund
 */
function validaVinculoAcompanhanteHiperdia( $z01_i_cgsund ) {

  $oDaoAcompanhate    = new cl_far_cadacomppachiperdia();
  $sWhereAcompanhante = "fa50_i_cgsund = {$z01_i_cgsund}";
  $sSqlAcompanhante   = $oDaoAcompanhate->sql_query_file("", "1", "", $sWhereAcompanhante);
  $rsAcompanhante     = db_query( $sSqlAcompanhante );

  if ( pg_num_rows( $rsAcompanhante ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui algum hiperdia vinculado.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui algum medicamento controlado vinculado
 * @param  integer $z01_i_cgsund
 */
function validaVinculoControle( $z01_i_cgsund ) {

  $oDaoControle   = new cl_far_controle();
  $sWhereControle = "fa11_i_cgsund = {$z01_i_cgsund}";
  $sSqlControle   = $oDaoControle->sql_query_file("", "1", "", $sWhereControle);
  $rsControle     = db_query( $sSqlControle );

  if ( pg_num_rows( $rsControle ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui algum medicamento controlado vinculado.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui alguma devolução vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoDevolucao( $z01_i_cgsund ) {

  $oDaoDevolucao   = new cl_far_devolucao();
  $sWhereDevolucao = "fa22_i_cgsund = {$z01_i_cgsund}";
  $sSqlDevolucao   = $oDaoDevolucao->sql_query_file("", "1", "", $sWhereDevolucao);
  $rsDevolucao     = db_query( $sSqlDevolucao );

  if ( pg_num_rows( $rsDevolucao ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui alguma devolução vinculada.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui vinculo com o fechamento do BPA do TFD com os procedimentos
 * @param integer $z01_i_cgsund 
 */
function validaVinculoFechamentoTdfProcedimento( $z01_i_cgsund ) {

  $oDaoFechamento   = new cl_fechamentotfdprocedimento();
  $sWhereFechamento = "tf40_cgs_und = {$z01_i_cgsund}";
  $sSqlFechamento   = $oDaoFechamento->sql_query_file("", "1", "", $sWhereFechamento);
  $rsFechamento     = db_query( $sSqlFechamento );

  if ( pg_num_rows( $rsFechamento ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui vinculo do fechamento do BPA do TFD com os procedimentos.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui alguma requisição vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoRequisicao( $z01_i_cgsund ) {

  $oDaoRequisicao   = new cl_lab_requisicao();
  $sWhereRequisicao = "la22_i_cgs = {$z01_i_cgsund}";
  $sSqlRequisicao   = $oDaoRequisicao->sql_query_file("", "1", "", $sWhereRequisicao);
  $rsRequisicao     = db_query( $sSqlRequisicao );

  if ( pg_num_rows( $rsRequisicao ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui requisição de exames vinculada.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui algum prontuario médico vinculado
 * @param  integer $z01_i_cgsund
 */
function validaVinculoProntuarioMedico( $z01_i_cgsund ) {

  $oDaoProntuario   = new cl_prontuariomedico();
  $sWhereProntuario = "sd32_i_numcgs = {$z01_i_cgsund}";
  $sSqlProntuario   = $oDaoProntuario->sql_query_file("" , "1", "", $sWhereProntuario);
  $rsProntuario     = db_query( $sSqlProntuario );

  if ( pg_num_rows( $rsProntuario ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui prontuário médico vinculado.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui alguma agenda de exames vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoAgendaExames( $z01_i_cgsund ) {

  $oDaoAgendaExames   = new cl_sau_agendaexames();
  $sWhereAgendaExames = "s113_i_numcgs = {$z01_i_cgsund}";
  $sSqlAgendaExames   = $oDaoAgendaExames->sql_query_file("", "1", "", $sWhereAgendaExames);
  $rsAgendaExames     = db_query( $sSqlAgendaExames );

  if ( pg_num_rows( $rsAgendaExames ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui alguma agenda de exames vinculada.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui algum encaminhamento vinculado
 * @param  integer $z01_i_cgsund
 */
function validaVinculoEncaminhamentos( $z01_i_cgsund ) {

  $oDaoEncaminhamentos   = new cl_sau_encaminhamentos();
  $sWhereEncaminhamentos = "s142_i_cgsund = {$z01_i_cgsund}";
  $sSqlEncaminhamentos   = $oDaoEncaminhamentos->sql_query_file("", "1", "", $sWhereEncaminhamentos);
  $rsEncaminhamentos     = db_query( $sSqlEncaminhamentos );

  if ( pg_num_rows( $rsEncaminhamentos ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui algum encaminhamento vinculado.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui alguma triagem avulsa vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoTriagemAvulsa( $z01_i_cgsund ) {

  $oDaoTriagem   = new cl_sau_triagemavulsa();
  $sWhereTriagem = "s152_i_cgsund = {$z01_i_cgsund}";
  $sSqlTriagem   = $oDaoTriagem->sql_query_file("", "1", "", $sWhereTriagem);
  $rsTriagem     = db_query( $sSqlTriagem );

  if ( pg_num_rows( $rsTriagem ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui uma triagem avulsa vinculada.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui algum acompanante vinculado
 * @param  integer $z01_i_cgsund
 */
function validaVinculoAcompanhantes( $z01_i_cgsund ) {

  $oDaoAcompanhates    = new cl_tfd_acompanhantes();
  $sWhereAcompanhantes = "tf13_i_cgsund = {$z01_i_cgsund}";
  $sSqlAcompanhantes   = $oDaoAcompanhates->sql_query_file("", "1", "", $sWhereAcompanhantes);
  $rsAcompanhantes     = db_query( $sSqlAcompanhantes );

  if ( pg_num_rows( $rsAcompanhantes ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo é acompanhante de alguém vinculado ao TFD.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui alguma ajuda de custo vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoAjudaCustoPedido( $z01_i_cgsund ) {

  $oDaoAjudaCusto   = new cl_tfd_ajudacustopedido();
  $sWhereAjudaCusto = "tf14_i_cgsretirou = {$z01_i_cgsund}";
  $sSqlAjudaCusto   = $oDaoAjudaCusto->sql_query_file("", "1", "", $sWhereAjudaCusto);
  $rsAjudaCusto     = db_query( $sSqlAjudaCusto );

  if ( pg_num_rows( $rsAjudaCusto) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui alguma retirada de ajuda de custo vinculada no TFD.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui algum vinculo com a tabela tfd_beneficiadosajudacusto
 * @param  integer $z01_i_cgsund
 */
function validaVinculoBeneficiadosAjudaCusto( $z01_i_cgsund ) {

  $oDaoBeneficiados   = new cl_tfd_beneficiadosajudacusto();
  $sWhereBeneficiados = "tf15_i_cgsund = {$z01_i_cgsund}";
  $sSqlBeneficiados   = $oDaoBeneficiados->sql_query_file("", "1", "", $sWhereBeneficiados);
  $rsBeneficiados     = db_query( $sSqlBeneficiados );

  if ( pg_num_rows( $rsBeneficiados) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo é beneficiado por alguma ajuda de custo vinculada.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui vinculo com a tabela tfd_passageiroveiculo
 * @param  integer $z01_i_cgsund
 */
function validaVinculoPassageiroVeiculo ( $z01_i_cgsund ) {

  $oDaoPassageiro   = new cl_tfd_passageiroveiculo();
  $sWherePassageiro = "tf19_i_cgsund = {$z01_i_cgsund}";
  $sSqlPassageiro   = $oDaoPassageiro->sql_query_file("", "1", "", $sWherePassageiro);
  $rsPassageiro     = db_query( $sSqlPassageiro );

  if ( pg_num_rows( $rsPassageiro ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo é passageiro de algum veículo.");
    db_redireciona("sau1_cgs_und003.php"); 
  }
}

/**
 * Valida se CGS possui com a tabela que armazena todos os pedidos de TFD (tfd_pedidotfd)
 * @param  integer $z01_i_cgsund
 */
function validaVinculoPediodo( $z01_i_cgsund ) {

  $oDaoPeriodo   = new cl_tfd_pedidotfd();
  $sWherePeriodo = "tf01_i_cgsund = {$z01_i_cgsund}";
  $sSqlPeriodo   = $oDaoPeriodo->sql_query_file("", "1", "", $sWherePeriodo);
  $rsPeriodo     = db_query( $sSqlPeriodo );

  if ( pg_num_rows( $rsPeriodo ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo esta transferido fora do município.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * Valida se CGS possui agendamentos vinculados
 * @param  integer $z01_i_cgsund
 */
function validaVinculoAgendamentos( $z01_i_cgsund ) {

  $oDaoAgendamentos   = new cl_agendamentos();
  $sWhereAgendamentos = "sd23_i_numcgs = {$z01_i_cgsund}";
  $sSqlAgendamentos   = $oDaoAgendamentos->sql_query_file("", "1", "", $sWhereAgendamentos);
  $rsAgendamentos     = db_query( $sSqlAgendamentos );

  if ( pg_num_rows( $rsAgendamentos ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui agendamentos vinculados.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * Valida se CGM possui fator de risco vinculado
 * @param  integer $z01_i_cgsund
 */
function validaVinculoFatorRisco( $z01_i_cgsund ) {

  $oDaoFatorRisco   = new cl_cgsfatorderisco();
  $sWhereFatorRisco = "s106_i_cgs = {$z01_i_cgsund}";
  $sSqlFatorRisco   = $oDaoFatorRisco->sql_query_file("", "1", "", $sWhereFatorRisco);
  $rsFatorRsico     = pg_query( $sSqlFatorRisco );

  if ( pg_num_rows( $rsFatorRsico ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui fator de risco vinculado.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * Valida se CGS possui alguma entrega vinculada
 * @param  integer $z01_i_cgsund
 */
function validaVinculoEntrega( $z01_i_cgsund ) {

  $oDaoEntrega   = new cl_lab_entrega();
  $sWhereEntrega = "la31_i_cgs = {$z01_i_cgsund}";
  $sSqlEntrega   = $oDaoEntrega->sql_query_file("", "1", "", $sWhereEntrega);
  $rsEntrega     = db_query( $sSqlEntrega );

  if ( pg_num_rows( $rsEntrega ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui alguma entrega vinculada.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * Valida se CGS possui algum prontuario vinculado
 * @param integer $z01_i_cgsund
 */
function validaVinculoProntuarios( $z01_i_cgsund ) {

  $oDaoProntuarios   = new cl_prontuarios();
  $sWhereProntuarios = "sd24_i_numcgs = {$z01_i_cgsund}";
  $sSqlProntuarios   = $oDaoProntuarios->sql_query_file("", "1", "", $sWhereProntuarios);
  $rsProntuarios     = db_query( $sSqlProntuarios );

  if ( pg_num_rows( $rsProntuarios ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui prontuários vinculados.");
    db_redireciona("sau1_cgs_und003.php");
  }
}

/**
 * [validaVinculoVacinaAplicada description]
 * @param  integer $z01_i_cgsund
 */
function validaVinculoVacinaAplicada( $z01_i_cgsund ) {

  $oDaoVacinaAplicada   = new cl_vac_aplica();
  $sWhereVacinaAplicada = "vc16_i_cgs = {$z01_i_cgsund}";
  $sSqlVacinaAplicada   = $oDaoVacinaAplicada->sql_query_file("", "1", "", $sWhereVacinaAplicada);
  $rsVacinaAplicada     = db_query( $sSqlVacinaAplicada );

  if ( pg_num_rows( $rsVacinaAplicada ) > 0 ) {

    db_msgbox("Não foi possível excluir CGS, pois o mesmo possui vacinas aplicadas.");
    db_redireciona("sau1_cgs_und003.php");
  }
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
<script language='JavaScript' type='text/javascript' src='scripts/classes/saude/validaCNS.js'></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
    <?include("forms/db_frmcgs_und.php");?>
</body>
</html>
<?
if(isset($excluir)){
 if($clcgs->erro_status=="0"){
   $clcgs->erro(true,false);
 }else{
      if($clcgs_und->erro_status=="0"){
        $clcgs_und->erro(true,false);
      }else{
        $clcgs_und->erro(true,true);
      }
  }
}
if($db_opcao==33){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>