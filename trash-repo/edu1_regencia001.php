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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($_POST);

$oGet = db_utils::postMemory($_GET);

$clregencia                      = new cl_regencia;
$clturma                         = new cl_turma;
$clbasediscglob                  = new cl_basediscglob;
$clbasemps                       = new cl_basemps;
$cldiario                        = new cl_diario;
$clamparo                        = new cl_amparo;
$clpareceraval                   = new cl_pareceraval;
$clparecerresult                 = new cl_parecerresult;
$clabonofalta                    = new cl_abonofalta;
$cldiarioavaliacao               = new cl_diarioavaliacao;
$cldiarioresultado               = new cl_diarioresultado;
$cldiariofinal                   = new cl_diariofinal;
$clregenciahorario               = new cl_regenciahorario;
$clregenciaperiodo               = new cl_regenciaperiodo;
$claprovconselho                 = new cl_aprovconselho;
$oDaoDiarioClasseRegenciaHorario = new cl_diarioclasseregenciahorario();
$oDaoDiarioClasseAlunoFalta      = new cl_diarioclassealunofalta();
$oDaoDiarioClasse                = new cl_diarioclasse();
$oDaoBaseDiscGlob                = new cl_basediscglob();

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;

$sSqlDadosTurma  = $clturma->sql_query_file($ed59_i_turma);
$rsDadosTurma    = $clturma->sql_record($sSqlDadosTurma);
$oDadosTurma     = db_utils::fieldsMemory($rsDadosTurma, 0);
$oGet->tipoturma = $oDadosTurma->ed57_i_tipoturma;
$hoje            = date("Y-m-d",db_getsession("DB_datausu"));
$sWhere          = " ed59_i_turma = $oGet->ed59_i_turma AND ed59_i_serie = $oGet->ed59_i_serie";
$result1         = $clregencia->sql_record($clregencia->sql_query("",
                                                                    "ed59_i_disciplina as discjacad,ed57_i_turno",
                                                                    "",
                                                                    $sWhere
                                                                   )
                                            );
if ($clregencia->numrows > 0) {

  $sep      = "";
  $disc_cad = "";

  for ($c = 0; $c < $clregencia->numrows; $c++) {

    db_fieldsmemory($result1,$c);
    $disc_cad .= $sep.$discjacad;
    $sep       = ",";

  }
} else {
  $disc_cad = 0;
}

if (isset($incluir)) {

  $max              = 0;
  $sWhere           = " ed59_i_turma = $oGet->ed59_i_turma AND ed59_i_serie = $oGet->ed59_i_serie";
  $sSqlMaxOrdem     = $clregencia->sql_query_file("", "coalesce(max(ed59_i_ordenacao)) as max","", $sWhere);

  $rsMaxOrdem       = $claprovconselho->sql_record($sSqlMaxOrdem);
  $iNumRowsMaxOrdem = $claprovconselho->numrows;

  $lInclui = true;

  if ($oGet->tipoturma == 6 ) {

    $sSqlValidaTipoTurma = $clregencia->sql_query_file("", "1","",$sWhere);
    $rsValidaTipoTurma   = $clregencia->sql_record($sSqlValidaTipoTurma);

    if ($clregencia->numrows >= 1) {

      db_msgbox("Em turmas de Progress�o Parcial s� � permitido uma disciplina cadastrada.");
      $lInclui = false;
    }
  }

  if ($lInclui) {

    if ($iNumRowsMaxOrdem > 0) {
      $max = db_utils::fieldsMemory($rsMaxOrdem, 0)->max;
    }
    db_inicio_transacao();
    $clregencia->ed59_c_ultatualiz      = "SI";
    $clregencia->ed59_c_encerrada       = "N";
    $clregencia->ed59_d_dataatualiz     = $hoje;
    $clregencia->ed59_i_ordenacao       = ($max+1);
    $clregencia->ed59_lancarhistorico   = 'true';
    
    if ($ed59_c_condicao == 'OP') {
      $clregencia->ed59_lancarhistorico = $ed59_lancarhistorico == 't'? 'true':'false';
    } 
    
    $clregencia->incluir($ed59_i_codigo);
    db_fim_transacao();

  }
  $ed59_i_disciplina = '';
  $ed232_c_descr     = '';
}

if (isset($alterar)) {

  $db_opcao = 2;
  db_inicio_transacao();
  
  $clregencia->ed59_lancarhistorico   = 'true';
  if ($ed59_c_condicao == 'OP') {
    $clregencia->ed59_lancarhistorico = $ed59_lancarhistorico == 't'? 'true':'false';
  }
  $clregencia->alterar($ed59_i_codigo);
  db_fim_transacao();

}

if (isset($excluir)) {

  db_inicio_transacao();

  try {
    $db_opcao   = 3;
    $result11   = $clregencia->sql_record($clregencia->sql_query("",
                                                                 "ed59_c_encerrada",
                                                                 "",
                                                                 " ed59_i_codigo = $ed59_i_codigo"
                                                                )
                                         );
    $sCampos    = "DISTINCT ed95_i_codigo as coddiario,ed60_i_codigo,ed60_c_situacao";
    $sWhere     = " ed95_i_regencia = $ed59_i_codigo AND ed59_i_turma = ed60_i_turma ";
    $sWhere    .= " AND ed95_c_encerrado = 'S' AND ed60_c_situacao = 'MATRICULADO'";
    $result_exc = $cldiario->sql_record($cldiario->sql_query_matric("",
                                                                    $sCampos,
                                                                    "",
                                                                    $sWhere
                                                                   )
                                       );

    if (pg_result($result11,0,0) == "S") {

      $clregencia->erro_status = "0";
      $sMensagemErro = "Exclus�o n�o permitida! Disciplina j� foi encerrada para todos alunos nesta turma.";
      throw new BusinessException($sMensagemErro);

    } else if ($cldiario->numrows > 0) {

      $clregencia->erro_status = "0";
      $sMensagemErro = "Exclus�o n�o permitida! Existem aluno(s) com avalia��es encerradas nesta disciplina.";
      throw new BusinessException($sMensagemErro);

    } else {

      $result_exc = $cldiario->sql_record($cldiario->sql_query_file("",
                                                                    "DISTINCT ed95_i_codigo as coddiario",
                                                                    "",
                                                                    " ed95_i_regencia = $ed59_i_codigo"
                                                                   )
                                         );
      $linhas_exc = $cldiario->numrows;

      if ($linhas_exc > 0) {

        for ($z = 0; $z < $linhas_exc; $z++) {

          db_fieldsmemory($result_exc,$z);
          $clamparo->excluir(""," ed81_i_diario = $coddiario");
          if ($clamparo->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir amparos vinculados a reg�ncia.\\n Erro T�cnico : {$clamparo->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }


          $cldiariofinal->excluir(""," ed74_i_diario = $coddiario");
          if ($cldiariofinal->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir resultados finais da reg�ncia.\\n Erro T�cnico : {$cldiariofinal->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $clparecerresult->excluir(""," ed63_i_diarioresultado in (select ed73_i_codigo from diarioresultado
                                    where ed73_i_diario = $coddiario)");

          if ($clparecerresult->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir pareceres das avalia��es vinculadas a reg�ncia.\\n";
            $sMensagemErro .= "Erro T�cnico : {$clparecerresult->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $cldiarioresultado->excluir(""," ed73_i_diario = $coddiario");
          if ($cldiarioresultado->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir resultados vinculados a reg�ncia .";
            $sMensagemErro .= "\\n Erro T�cnico : {$cldiarioresultado->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $clpareceraval->excluir(""," ed93_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao
                                   where ed72_i_diario = $coddiario)");

          if ($clpareceraval->erro_status == 0) {

            $sMensagemErro .= "Erro ao excluir pareceres vinculado a reg�ncia.\\n";
            $sMensagemErro .= "Erro T�cnico : {$clpareceraval->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          $clabonofalta->excluir(""," ed80_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao
                                   where ed72_i_diario = $coddiario)");

          if ($clabonofalta->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir abonos vinculados a Reg�ncia.\\n Erro T�cnico{$clabonofalta->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $cldiarioavaliacao->excluir(""," ed72_i_diario = $coddiario");
          if ($cldiarioavaliacao->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir avalia��es vinculadas a reg�ncia.\\n";
            $sMensagemErro .= "Erro T�cnico : {$cldiarioavaliacao->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $claprovconselho->excluir(""," ed253_i_diario = $coddiario");
          if ($claprovconselho->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir aprova��es pelo conselho vinculadas a reg�ncia.\\n";
            $sMensagemErro .= "Erro T�cnico : {$claprovconselho->erro_msg}";
            throw new BusinessException();
          }

          $cldiario->excluir(""," ed95_i_codigo = $coddiario");
          if ($cldiario->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir di�rios de avalia��o vinculados a reg�ncia.\\n ";
            $sMensagemErro .= "Erro T�cnico : {$cldiario->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }


        }
      }

      /**
       * select na diarioclasseregenciahorario pelo codigo das regencias
       *  - excluir diarioclassealunofalta
       *  - excluir diarioclasseregenciahorario
       *  - diarioclasse
       */
      $sWhereDiarioClasse  = "ed302_regenciahorario in (select ed58_i_codigo ";
      $sWhereDiarioClasse .= "                           from regenciahorario ";
      $sWhereDiarioClasse .= "                           where ed58_i_regencia = {$ed59_i_codigo})";
      $sSqlDiarioClasse    = $oDaoDiarioClasseRegenciaHorario->sql_query_file(null,
                                                                           "*",
                                                                           null,
                                                                           $sWhereDiarioClasse
                                                                          );

      $rsDiarioClasse = $oDaoDiarioClasseRegenciaHorario->sql_record($sSqlDiarioClasse);
      $iTotalLinhasDiario = $oDaoDiarioClasseRegenciaHorario->numrows;
      if ($iTotalLinhasDiario > 0) {

        $aDiarioClasseExcluidos = array();
        for ($iDiario = 0; $iDiario < $iTotalLinhasDiario; $iDiario++) {

          $oDadosDiarioClasse       = db_utils::fieldsMemory($rsDiarioClasse, $iDiario);
          $aDiarioClasseExcluidos[] = $oDadosDiarioClasse->ed302_diarioclasse;
          /**
           * Excluir diarioalunofalta
           *
           */
          $sWhereExcluirDiarioClasseAlunoFalta = "ed301_diarioclasseregenciahorario = {$oDadosDiarioClasse->ed302_sequencial}";
          $oDaoDiarioClasseAlunoFalta->excluir(null, $sWhereExcluirDiarioClasseAlunoFalta );
          if ($oDaoDiarioClasseAlunoFalta->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir faltas do aluno.\\nErro t�cnico : {$oDaoDiarioClasseAlunoFalta->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          /**
           * Excluir da diarioclasseregenciahorario
           */
          $oDaoDiarioClasseRegenciaHorario->excluir($oDadosDiarioClasse->ed302_sequencial);
          if ($oDaoDiarioClasseRegenciaHorario->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir periodos de aula do aluno. \\n";
            $sMensagemErro .= "Erro t�cnico : {$oDaoDiarioClasseRegenciaHorario->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          unset($oDadosDiarioClasse);
        }
        $sDiarioClasseExcluir = implode(",", $aDiarioClasseExcluidos);
        $oDaoDiarioClasse->excluir(null, "ed300_sequencial in ({$sDiarioClasseExcluir})");
        if ($oDaoDiarioClasse->erro_status == 0) {

          $sMensagemErro  = "Erro ao excluir dados do diario de classe do professor.\\n";
          $sMensagemErro .= "Erro t�cnico : {$oDaoDiarioClasse->erro_msg}";
          throw new BusinessException($sMensagemErro);
        }

      }
      $clregenciahorario->excluir(""," ed58_i_regencia = $ed59_i_codigo");
      if ($clregenciahorario->erro_status == 0) {
        throw new BusinessException("Erro ao excluir periodos de aula da disciplina.\\n{$clregenciahorario->erro_msg}");
      }
      $clregenciaperiodo->excluir(""," ed78_i_regencia = $ed59_i_codigo");
      if ($clregenciaperiodo->erro_status == 0) {
        $sMensagemErro  = "Erro ao excluir quantidade de aulas dadas no per�odo.\\n";
        $sMensagemErro .= "Erro T�cnico{$clregenciaperiodo->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clregencia->excluir($ed59_i_codigo);
      if ($clregencia->erro_status == 0) {
        throw new BusinessException("Erro ao excluir disciplina da turma .\\n Erro T�cnico{$clregencia->erro_msg}");
      }
      db_fim_transacao(false);

    }
  } catch (BusinessException $eBusinness) {

    $clregencia->erro_status = "0";
    $clregencia->erro_msg    = $eBusinness->getMessage();
    db_fim_transacao(true);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Disciplinas da Turma <?=@$oGet->ed57_c_descr?> - Etapa <?=$oGet->ed11_c_descr?></b></legend>
    <?include("forms/db_frmregencia.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed59_i_disciplina",true,1,"ed59_i_disciplina",true);
</script>
 <iframe name="iframe_nobase" src="" frameborder="0" width="200" height="200" style=""></iframe>
<?

if (isset($atualizar)) {

  /**
   * C�digo da disciplina global na base curricular
   */
  $iDisciplina   = null;

  /**
   * Vari�vel para controle se h� uma disciplina global
   */
  $lTemGlobal    = false;

  /**
   * Vari�vel para controle se a disciplina global j� foi inclusa como reg�ncia da turma
   */
	$lPassouGlobal = false;

	$lErro         = false;
	$sMsgErro      = "Ocorreu erro durante o processamento!\\n";

	/**
   * Busca a disciplina global da base, caso exista
	 */
	$sSqlDisciplinaGlobal = $oDaoBaseDiscGlob->sql_query_file(null, "ed89_i_disciplina", null, "ed89_i_codigo = {$base}");
	$rsDisciplinaGlobal   = $oDaoBaseDiscGlob->sql_record($sSqlDisciplinaGlobal);

	if ($oDaoBaseDiscGlob->numrows > 0) {

    $lTemGlobal  = true;
    $iDisciplina = db_utils::fieldsMemory($rsDisciplinaGlobal, 0)->ed89_i_disciplina;
  }

	$sSqlBase = "SELECT ed34_i_disciplina,
	                    ed34_i_qtdperiodo,
	                    ed34_i_ordenacao,
	                    ed34_c_condicao,
	                    ed34_lancarhistorico
                 FROM basemps
                WHERE ed34_i_base = {$base}
                  AND ed34_i_serie = {$oGet->ed59_i_serie}
                UNION
               SELECT ed89_i_disciplina, ed89_i_qtdperiodos, null, '', 't'
                 FROM basediscglob
                WHERE ed89_i_codigo = {$base}";

  $result = $clbasemps->sql_record($sSqlBase);
  $iLinhasBaseMps = $clbasemps->numrows;

  if ($iLinhasBaseMps > 0) {

    $disc_inc    = "";
    $sep         = "";

    db_inicio_transacao();

    for ($w = 0; $w < $iLinhasBaseMps; $w++) {

      $oDadosBaseMps = db_utils::fieldsMemory($result, $w);

      $disc_inc .= $sep.$oDadosBaseMps->ed34_i_disciplina;
      $sep       = ",";

      $clregencia->ed59_i_qtdperiodo    = $oDadosBaseMps->ed34_i_qtdperiodo;
      $clregencia->ed59_c_condicao      = $oDadosBaseMps->ed34_c_condicao;
      $clregencia->ed59_c_encerrada     = "N";
      $clregencia->ed59_i_ordenacao     = $oDadosBaseMps->ed34_i_ordenacao;
      $clregencia->ed59_i_disciplina    = $oDadosBaseMps->ed34_i_disciplina;
      $clregencia->ed59_i_serie         = $oGet->ed59_i_serie;
      $clregencia->ed59_i_turma         = $oGet->ed59_i_turma;
      $clregencia->ed59_c_freqglob      = $lTemGlobal ? "A" : "I";
      $clregencia->ed59_lancarhistorico = $oDadosBaseMps->ed34_lancarhistorico == 't' ? 'true' : 'false';

      $oDaoRegencia  = new cl_regencia();
      $sWhere        = "     ed59_i_disciplina = {$oDadosBaseMps->ed34_i_disciplina} ";
      $sWhere       .= " AND ed59_i_turma = {$oGet->ed59_i_turma}       ";
      $sWhere       .= " AND ed59_i_serie = {$oGet->ed59_i_serie}       ";

      $sCampos         = "ed59_i_codigo, ed59_c_freqglob, ed59_c_condicao, ed59_lancarhistorico";
      $result2         = $oDaoRegencia->sql_record($oDaoRegencia->sql_query_file("", $sCampos, "", $sWhere));
      $iExisteRegencia = $oDaoRegencia->numrows;

      if ( $iExisteRegencia > 0) {

        $oDadosRegencia = db_utils::fieldsMemory($result2, 0);

        /**
         * Seta uma frequ�ncia padr�o, que ser� alterada de acordo com as valida��es
         * 1� - Caso o campo ed34_c_condicao esteja vazio, OU seja, h� uma disciplina global no SQL $sSqlBase, ou exista
         *      uma disciplina global e c�digo de $iDisciplina seja igual a disciplina percorrida, seta frequ�ncia como
         *      global FA
         *
         * 2� - Caso o campo ed34_c_condicao seja diferente de vazio E exista uma disciplina global, por�m o c�digo de
         *      $iDisciplina seja diferente da disciplina percorrida (ou seja, existe global por�m a disciplina em quest�o
         *      n�o � esta global), seta a frequ�ncia como TRATADA 'A
         */
        $sFrequencia = 'I';
        if ($oDadosBaseMps->ed34_c_condicao == '' || ($lTemGlobal && $iDisciplina == $oDadosBaseMps->ed34_i_disciplina)) {
          $sFrequencia = 'FA';
        }

        if ($oDadosBaseMps->ed34_c_condicao != '' && ($lTemGlobal && $iDisciplina != $oDadosBaseMps->ed34_i_disciplina)) {
          $sFrequencia = 'A';
        }

        $clregencia->ed59_c_freqglob      = $sFrequencia;
        $clregencia->ed59_i_codigo        = $oDadosRegencia->ed59_i_codigo;
        $clregencia->ed59_lancarhistorico = $oDadosRegencia->ed59_lancarhistorico;
        $clregencia->alterar($oDadosRegencia->ed59_i_codigo);

        if ($clregencia->erro_status == "0" ) {
          throw new BusinessException($clregencia->erro_msg);
        }
      } else {

        if (empty($oDadosBaseMps->ed34_c_condicao) && !$lPassouGlobal) {

          $lPassouGlobal = true;
          continue;
        }

        $clregencia->incluir(null);
        if ($clregencia->erro_status == "0") {
          throw new BusinessException($clregencia->erro_msg);
        }
      }
    }

    db_fim_transacao($lErro);

    if ($lErro == true) {

    	db_msgbox($sMsgErro);
    	db_redireciona("edu1_regencia001.php?ed59_i_turma=$oGet->ed59_i_turma"
    			                              ."&ed57_c_descr=".addslashes($oGet->ed57_c_descr)
    			                              ."&ed59_i_serie=$oGet->ed59_i_serie"
    			                              ."&ed11_c_descr=$oGet->ed11_c_descr"
    			                              ."&tipoturma=$oGet->tipoturma");
    }

    $sWhere          = " ed59_i_turma = $oGet->ed59_i_turma ";
    $sWhere         .= " AND ed59_i_serie = $oGet->ed59_i_serie ";
    $sWhere         .= " AND ed59_i_disciplina not in ($disc_inc)";
    $result3         = $clregencia->sql_record($clregencia->sql_query("",
                                                                      "ed59_i_codigo,ed232_c_descr",
                                                                      "",
                                                                      $sWhere
                                                                     )
                                              );
    $nobase          = "";
    $codnobase       = "";
    $descrdisciplina = "";
    $sepnobase       = "";
    $iLinhasRegencia = $clregencia->numrows;
    for ($r = 0; $r < $iLinhasRegencia; $r++) {

      db_fieldsmemory($result3,$r);
      $nobase          .= " - ".$ed232_c_descr."\\n";
      $codnobase       .= $sepnobase.$ed59_i_codigo;
      $descrdisciplina .= $sepnobase.$ed232_c_descr;
      $sepnobase        = ",";

    }

    if ($nobase != "") {

     $lConfirmaExclusao = false;
     ?>
      <script>
      var msg  = "ATEN��O!\n\n Disciplina(s):\n <?=$nobase?>\nn�o cont�m na base curricular.\n\n";
      msg += "Desta forma, ao atualizar as disciplinas pela base, a grade de hor�rio / v�nculo do professor com a turma";
      msg += " ser� alterado, removendo a(s) disciplina(s) em quest�o caso estas tenham sido vinculadas com regente.\n\n";
      msg += "Deseja excluir esta(s) disciplina(s) e alterar a grade de hor�rio / v�nculo da turma?";
       if (confirm(msg)) {

         <?
           $lConfirmaExclusao = true;
         ?>
         iframe_nobase.location.href="edu1_regencia002.php?ed59_i_turma=<?=$oGet->ed59_i_turma?>"+
                                                         "&ed57_c_descr=<?=$oGet->ed57_c_descr?>&ed59_i_serie=<?=$oGet->ed59_i_serie?>"+
                                                         "&ed11_c_descr=<?=$oGet->ed11_c_descr?>&frequencia=<?=$frequencia?>"+
                                                         "&codnobase=<?=$codnobase?>&descrdisciplina=<?=$descrdisciplina?>"+
                                                         "&tipoturma=<?=$oGet->tipoturma?>";

         var sEtapa = "<?=$oGet->ed11_c_descr?>";
         var sTurma = "<?=$oGet->ed57_c_descr?>";
         top.corpo.iframe_a3.location.href = 'edu1_regenciahorario001.php?ed59_i_turma='+<?=$oGet->ed59_i_turma?>
                                                                        +'&ed57_c_descr='+sTurma
                                                                        +'&ed57_i_turno='+<?=$ed57_i_turno?>
                                                                        +'&ed59_i_serie='+<?=$oGet->ed59_i_serie?>
                                                                        +'&ed11_c_descr='+sEtapa;
       }
      </script>
     <?

      if ($lConfirmaExclusao) {

        db_inicio_transacao();

        $aCodigoRegenciaHorario = explode(",", $codnobase);
        $iTotalRegenciaHorario  = count($aCodigoRegenciaHorario);

        for ($iContador = 0; $iContador < $iTotalRegenciaHorario; $iContador++) {

          $oDaoRegenciaHorario                = new cl_regenciahorario();
          $oDaoRegenciaHorario->ed58_ativo    = 'false';
          $oDaoRegenciaHorario->ed58_i_codigo = $aCodigoRegenciaHorario[$iContador];
          $oDaoRegenciaHorario->alterar($aCodigoRegenciaHorario[$iContador]);

          if ($oDaoRegenciaHorario->erro_status == "0") {
            throw new DBException($oDaoRegenciaHorario->erro_msg);
          }
        }

        db_fim_transacao();
        db_msgbox("Disciplinas atualizadas com sucesso!");
      }
    } else {

      db_msgbox("Disciplinas atualizadas com sucesso!");
      db_redireciona("edu1_regencia001.php?ed59_i_turma=$oGet->ed59_i_turma"
                                        ."&ed57_c_descr=".addslashes($oGet->ed57_c_descr)
                                        ."&ed59_i_serie=$oGet->ed59_i_serie"
                                        ."&ed11_c_descr=$oGet->ed11_c_descr"
      		                              ."&tipoturma=$oGet->tipoturma");
    }
  } else {

    db_msgbox("Nenhuma disciplina cadastrada na base curricular!");
    db_redireciona("edu1_regencia001.php?ed59_i_turma=$oGet->ed59_i_turma"
    		                              ."&ed57_c_descr=".addslashes($oGet->ed57_c_descr)
    		                              ."&ed59_i_serie=$oGet->ed59_i_serie"
    		                              ."&ed11_c_descr=$oGet->ed11_c_descr"
    		                              ."&tipoturma=$oGet->tipoturma");

  }
}


if (isset($incluir)) {

  if ($clregencia->erro_status == "0") {

    $clregencia->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clregencia->erro_campo != "") {

      echo "<script> document.form1.".$clregencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clregencia->erro_campo.".focus();</script>";

    }
  } else {
    $clregencia->erro(true,true);
  }
}

if (isset($alterar)) {

  if ($clregencia->erro_status == "0") {

    $clregencia->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clregencia->erro_campo != "") {

      echo "<script> document.form1.".$clregencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clregencia->erro_campo.".focus();</script>";

    }
  } else {
    $clregencia->erro(true,true);
  }
}

if (isset($excluir)) {

  if ($clregencia->erro_status == "0") {
    $clregencia->erro(true,false);
  } else {
   ?>
   <script>
     top.corpo.iframe_a3.location.href="edu1_regenciahorario001.php?ed59_i_turma=<?=$oGet->ed59_i_turma?>"+
                                       "&ed59_i_serie=<?=$oGet->ed59_i_serie?>&ed57_c_descr=<?=$oGet->ed57_c_descr?>"+
                                       "&ed57_i_turno=<?=$ed57_i_turno?>&ed11_c_descr=<?=$oGet->ed11_c_descr?>";
   </script>
   <?
   $clregencia->erro(true,true);
  }
}

if (isset($cancelar)) {
  echo "<script>location.href='{$clregencia->pagina_retorno}'</script>";
}
?>