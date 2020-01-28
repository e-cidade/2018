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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_transfescolarede_classe.php"));
require_once(modification("classes/db_matricula_classe.php"));
require_once(modification("classes/db_matriculamov_classe.php"));
require_once(modification("classes/db_escoladiretor_classe.php"));
require_once(modification("classes/db_obstransferencia_classe.php"));
require_once(modification("classes/db_alunocurso_classe.php"));
require_once(modification("classes/db_diario_classe.php"));
require_once(modification("classes/db_regencia_classe.php"));
require_once(modification("classes/db_turma_classe.php"));
require_once(modification("classes/db_atestvaga_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_jsplibwebseller.php"));

db_postmemory($HTTP_POST_VARS);

$ed103_d_data_dia     = date("d", db_getsession("DB_datausu"));
$ed103_d_data_mes     = date("m", db_getsession("DB_datausu"));
$ed103_d_data_ano     = date("Y", db_getsession("DB_datausu"));
$oDaoTransfEscolaRede = db_utils::getdao('transfescolarede');
$oDaoMatricula        = db_utils::getdao('matricula');
$oDaoMatriculaMov     = db_utils::getdao('matriculamov');
$oDaoEscolaDiretor    = db_utils::getdao('escoladiretor');
$oDaoObsTransferencia = db_utils::getdao('obstransferencia');
$oDaoAlunoCurso       = db_utils::getdao('alunocurso');
$oDaoDiario           = db_utils::getdao('diario');
$oDaoRegencia         = db_utils::getdao('regencia');
$oDaoTurma            = db_utils::getdao('turma');
$oDaoAtestVaga        = db_utils::getdao('atestvaga');
$db_opcao             = 1;
$db_botao             = true;
$ed103_i_escolaorigem = db_getsession("DB_coddepto");
$ed18_c_nome          = db_getsession("DB_nomedepto");
$iEscola              = db_getsession("DB_coddepto");
$oPost                = db_utils::postMemory($_POST);

if (isset($incluir)) {

  try {

    db_inicio_transacao();

    $oDaoTransfEscolaRede->ed103_c_situacao  = "A";
    $oDaoTransfEscolaRede->ed103_i_matricula = $matricula;
    $oDaoTransfEscolaRede->ed103_i_usuario   = db_getsession("DB_id_usuario");
    $oDaoTransfEscolaRede->incluir($ed103_i_codigo);

    if ($oDaoTransfEscolaRede->erro_status == '0') {
      throw new Exception("Erro na alteração na tabela Transfescolarede. Erro da classe: "
                          .$oDaoTransfEscolaRede->erro_msg
                         );
    }//fecha o erro_status

    $sCampos       = "ed60_i_turma,turma.ed57_i_turno as turnoturma,ed60_c_situacao as sitatual";
    $sSqlMatricula = $oDaoMatricula->sql_query("", $sCampos, "", " ed60_i_codigo = $matricula");
    $rsResult      = $oDaoMatricula->sql_record($sSqlMatricula);

    if ($oDaoMatricula->numrows > 0) {
      $oDados = db_utils::fieldsmemory($rsResult, 0);
    }

    if ($concluida == "N") {

      $sWhere           = " ed59_i_turma = ".$oDados->ed60_i_turma;
      $sSqlRegencia     = $oDaoRegencia->sql_query("", "ed59_i_codigo as regturma", "", $sWhere);
      $rsResultRegencia = $oDaoRegencia->sql_record($sSqlRegencia);

      for ($iCont = 0; $iCont < $oDaoRegencia->numrows; $iCont++) {

        $sWhereDiario   = " ed95_i_aluno = $ed47_i_codigo";
        $sWhereDiario  .= " AND ed95_i_regencia = ".db_utils::fieldsmemory($rsResultRegencia,$iCont)->regturma;
        $sSqlDiario     = $oDaoDiario->sql_query("", "ed95_i_codigo", "", $sWhereDiario);
        $rsResultDiario = $oDaoDiario->sql_record($sSqlDiario);

        if ($oDaoDiario->numrows > 0) {

          $oDaoDiario->ed95_c_encerrado = 'S';
          $oDaoDiario->ed95_i_codigo    = db_utils::fieldsmemory($rsResultDiario,0)->ed95_i_codigo;
          $oDaoDiario->alterar($oDaoDiario->ed95_i_codigo);

          if ($oDaoDiario->erro_status == '0') {
            throw new Exception("Erro na alteração na tabela Diario. Erro da classe: ".$oDaoDiario->erro_msg);
          }//fecha o erro_status

        }

      }//fecha o for

      $dDataModif                         = substr($ed103_d_data,6,4)."-".substr($ed103_d_data,3,2)."-".
                                            substr($ed103_d_data,0,2);
      $dDataModifAnt                      = substr($datamodif,6,4)."-".substr($datamodif,3,2)."-".substr($datamodif,0,2);
      $oDaoMatricula->ed60_c_situacao     = 'TRANSFERIDO REDE';
      $oDaoMatricula->ed60_d_datamodif    = $dDataModif;
      $oDaoMatricula->ed60_d_datasaida    = ($dDataModif==""?"null":$dDataModif);
      $oDaoMatricula->ed60_d_datamodifant = $dDataModifAnt;
      $oDaoMatricula->ed60_i_codigo       = $matricula;
      $oDaoMatricula->alterar($matricula);

      if ($oDaoMatricula->erro_status == '0') {
        throw new Exception("Erro na alteração na tabela Matricula. Erro da classe: ".$oDaoMatricula->erro_msg);
      }//fecha o erro_status

      $sDescr                                 = "ALUNO DA TURMA ".trim($turma)." TRANSFERIDO PARA ESCOLA ";
      $sDescr                                .= trim($nomeescola).",CONFORME ATESTADO DE VAGA N°";
      $sDescr                                .= $ed103_i_atestvaga." DE". $dataatestado;
      $dDataEvento                            = substr($ed103_d_data,6,4)."-".
                                                substr($ed103_d_data,3,2)."-".substr($ed103_d_data,0,2);
      $oDaoMatriculaMov->ed229_i_matricula    = $matricula;
      $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
      $oDaoMatriculaMov->ed229_c_procedimento = "TRANSFERÊNCIA ENTRE ESCOLAS DA REDE";
      $oDaoMatriculaMov->ed229_t_descr        = $sDescr;
      $oDaoMatriculaMov->ed229_d_dataevento   = $dDataEvento;
      $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
      $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoMatriculaMov->incluir(null);

      if ($oDaoMatriculaMov->erro_status == '0') {
        throw new Exception("Erro na alteração na tabela MatriculaMov. Erro da classe: ".$oDaoMatriculaMov->erro_msg);
      }//fecha o erro_status

      LimpaResultadofinal($matricula);

    }//fecha o if $concluida == "N"

    $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query("", "ed56_i_codigo", "", "ed56_i_aluno = $ed47_i_codigo");
    $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);

    if ($oDaoAlunoCurso->numrows > 0) {

      $oDaoAlunoCurso->ed56_i_escola      = $codigoescola;
      $oDaoAlunoCurso->ed56_c_situacao    = 'TRANSFERIDO REDE';
      $oDaoAlunoCurso->ed56_c_situacaoant = $oDados->sitatual;
      $oDaoAlunoCurso->ed56_i_aluno       = $ed47_i_codigo;
      $oDaoAlunoCurso->ed56_i_codigo      = db_utils::fieldsmemory($rsResultAlunoCurso,0)->ed56_i_codigo;
      $oDaoAlunoCurso->alterar($oDaoAlunoCurso->ed56_i_codigo);

    }

    $sWhere            = " ed102_i_aluno = $ed47_i_codigo AND not exists ";
    $sWhere           .= "(select * from transfescolarede where ed103_i_atestvaga = ed102_i_codigo)";
    $sSqlAtestVaga     = $oDaoAtestVaga->sql_query("", "ed102_i_codigo", "", $sWhere);
    $rsResultAtestVaga = $oDaoAtestVaga->sql_record($sSqlAtestVaga);

    if ($oDaoAtestVaga->numrows > 0) {

      for ($iCont = 0; $iCont < $oDaoAtestVaga->numrows; $iCont++) {

        $oDados = db_utils::fieldsmemory($rsResultAtestVaga, $iCont);
        $oDaoAtestVaga->excluir(null,"ed102_i_codigo = ".$oDados->ed102_i_codigo);

        if ($oDaoAtestVaga->erro_status == '0') {
          throw new Exception("Erro na alteração na tabela AtestVaga. Erro da classe: ".$oDaoAtestVaga->erro_msg);
        }//fecha o erro_status

      }//fecha o for

    }//fecha o if $oDaoAtestVaga->numrows > 0

    if ($oDaoTransfEscolaRede->erro_status=="0") {
      $erro_transf = true;
    } else {
      $erro_transf = false;
    }

    $sSqlObsTransferencia     = $oDaoObsTransferencia->sql_query("", "*", "", " ed283_i_escola = $iEscola");
    $rsResultObsTransferencia = $oDaoObsTransferencia->sql_record($sSqlObsTransferencia);

    if ($oDaoObsTransferencia->numrows > 0) {

      $oDadosObs                                  = db_utils::fieldsmemory($rsResultObsTransferencia, 0);
      $oDaoObsTransferencia->ed283_i_escola       = $iEscola;
      $oDaoObsTransferencia->ed283_t_mensagem     = $obs;
      $oDaoObsTransferencia->ed283_c_bolsafamilia = $ed283_c_bolsafamilia;
      $oDaoObsTransferencia->ed283_i_codigo       = $oDadosObs->ed283_i_codigo;
      $oDaoObsTransferencia->alterar($oDaoObsTransferencia->ed283_i_codigo);

      if ($oDaoObsTransferencia->erro_status == '0') {

        throw new Exception("Erro na alteração na tabela ObsTransferencia. Erro da classe: ".
                            $oDaoObsTransferencia->erro_msg
                           );

      }//fecha o erro_status

    } else {

      if ($obs != "") {

        $oDaoObsTransferencia->ed283_i_escola       = $iEscola;
        $oDaoObsTransferencia->ed283_c_bolsafamilia = $ed283_c_bolsafamilia;
        $oDaoObsTransferencia->ed283_t_mensagem     = $obs;
        $oDaoObsTransferencia->incluir(null);

        if ($oDaoObsTransferencia->erro_status == '0') {

          throw new Exception("Erro na alteração na tabela ObsTransferencia. Erro da classe: ".
                              $oDaoObsTransferencia->erro_msg
                             );

        }//fecha o erro_status

      }//fecha o if obs!=""

    }//fecha o else


    db_fim_transacao();
    //db_msgbox('Transferência efetuada com sucesso!');
    //db_redireciona('edu1_transfescolarede001.php');
    //exit;

  } catch (Exception $oE) {

    db_fim_transacao(true);
    db_msgbox(str_replace("'", "\'", $oE->getMessage()));
    db_redireciona('edu1_transfescolarede001.php');

  }

}//fecha o incluir
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <? MsgAviso(db_getsession("DB_coddepto"), "escola"); ?>
        <br>
        <center>
        <fieldset style="width:95%"><legend><b>Transferência entre escolas da rede municipal</b></legend>
          <? include(modification("forms/db_frmtransfescolarede.php")); ?>
        </fieldset>
        </center>
      </td>
    </tr>
  </table>
  <? db_menu(db_getsession("DB_id_usuario"),
             db_getsession("DB_modulo"),
             db_getsession("DB_anousu"),
             db_getsession("DB_instit")
            );
  ?>
</body>
</html>
<script>
  js_tabulacaoforms("form1","ed103_i_atestvaga",true,1,"ed103_i_atestvaga",true);
</script>
<?
if (isset($incluir)) {

  if ($oDaoTransfEscolaRede->erro_status == "0") {

    $oDaoTransfEscolaRede->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    echo "<script> document.form1.db_opcao.style.visibility='visible';</script>  ";

    if ($oDaoTransfEscolaRede->erro_campo != "") {

      echo "<script> document.form1.".$oDaoTransfEscolaRede->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoTransfEscolaRede->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoTransfEscolaRede->erro(true, false);
    $alunos = $oDaoTransfEscolaRede->ed103_i_codigo;
    ?>
    <script>

      var oParametros = {
        'alunos'       : <?=$alunos?>,
        'tipo'         : 'TR',
        'diretor'      : '<?=$oPost->diretor?>',
        'bolsafamilia' : <?=$ed283_c_bolsafamilia?>,
        'obs'          : '<?=$obs?>'
      };

      var oGuiaTransferencia = new EmissaoRelatorio('edu2_guiatransf002.php', oParametros);
          oGuiaTransferencia.open();
    </script>
    <?
    db_redireciona("edu1_transfescolarede001.php");

  }

}
?>