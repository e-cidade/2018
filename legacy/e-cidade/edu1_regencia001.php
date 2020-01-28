<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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

      db_msgbox("Em turmas de Progressão Parcial só é permitido uma disciplina cadastrada.");
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
      $sMensagemErro = "Exclusão não permitida! Disciplina já foi encerrada para todos alunos nesta turma.";
      throw new BusinessException($sMensagemErro);

    } else if ($cldiario->numrows > 0) {

      $clregencia->erro_status = "0";
      $sMensagemErro = "Exclusão não permitida! Existem aluno(s) com avaliações encerradas nesta disciplina.";
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

            $sMensagemErro = "Erro ao excluir amparos vinculados a regência.\\n Erro Técnico : {$clamparo->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }


          $cldiariofinal->excluir(""," ed74_i_diario = $coddiario");
          if ($cldiariofinal->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir resultados finais da regência.\\n Erro Técnico : {$cldiariofinal->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $clparecerresult->excluir(""," ed63_i_diarioresultado in (select ed73_i_codigo from diarioresultado
                                    where ed73_i_diario = $coddiario)");

          if ($clparecerresult->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir pareceres das avaliações vinculadas a regência.\\n";
            $sMensagemErro .= "Erro Técnico : {$clparecerresult->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          
          /**
           * Exclui as recuperações do aluno
           */
          $sCamposResultado = " array_to_string( array_accum(ed73_i_codigo), ',') as diarioresultado ";
          $sWhereResultado  = " ed73_i_diario = {$coddiario} ";
          $sSqlResultado    = $cldiarioresultado->sql_query_file(null, $sCamposResultado, null, $sWhereResultado);
          $rsResultado      = $cldiarioresultado->sql_record( $sSqlResultado );
          
          if ( $cldiarioresultado->numrows > 0 ) {
          	
            $sDiarioResultado      = db_utils::fieldsMemory($rsResultado, 0)->diarioresultado;
            $sWhereRecuperacao     = " ed116_diarioresultado in ($sDiarioResultado) ";
            $oDaoDiarioRecuperacao = new cl_diarioresultadorecuperacao();
            $oDaoDiarioRecuperacao->excluir( null, $sWhereRecuperacao );
            
            if ( $oDaoDiarioRecuperacao->erro_status == 0 ) {
            
              $sMensagemErro  = "Erro ao excluir dados da recuperação do aluno.\\n";
              $sMensagemErro .= "Erro Técnico : {$oDaoDiarioRecuperacao->erro_msg}";
              throw new BusinessException();
            }
          }
          
          $cldiarioresultado->excluir(""," ed73_i_diario = $coddiario");
          if ($cldiarioresultado->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir resultados vinculados a regência .";
            $sMensagemErro .= "\\n Erro Técnico : {$cldiarioresultado->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $clpareceraval->excluir(""," ed93_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao
                                   where ed72_i_diario = $coddiario)");

          if ($clpareceraval->erro_status == 0) {

            $sMensagemErro .= "Erro ao excluir pareceres vinculado a regência.\\n";
            $sMensagemErro .= "Erro Técnico : {$clpareceraval->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          $clabonofalta->excluir(""," ed80_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao
                                   where ed72_i_diario = $coddiario)");

          if ($clabonofalta->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir abonos vinculados a Regência.\\n Erro Técnico{$clabonofalta->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          
          $sCamposAvaliacao = " array_to_string( array_accum(ed72_i_codigo), ',') as diarioavaliacao ";
          $sWhereAvaliacao  = " ed72_i_diario = {$coddiario} ";
          $sSqlDiarioAvaliacao = $cldiarioavaliacao->sql_query_file(null, $sCamposAvaliacao, null, $sWhereAvaliacao);
          $rsDiarioAvaliacao   = $cldiarioavaliacao->sql_record($sSqlDiarioAvaliacao);
          
          if ($cldiarioavaliacao->numrows > 0) {
          	
            $sDiarioAvaliacao   = db_utils::fieldsMemory($rsDiarioAvaliacao, 0)->diarioavaliacao;
            $oDaoTransfAprov    = new cl_transfaprov();
            $sWhereExcluiTransf = "ed251_i_diarioorigem in ({$sDiarioAvaliacao}) or ed251_i_diariodestino in ({$sDiarioAvaliacao}) ";
            $oDaoTransfAprov->excluir(null, $sWhereExcluiTransf);
            if ( $oDaoTransfAprov->erro_status == 0 ) {
            
              $sMensagemErro  = "Erro ao excluir dados da transferencia do aluno.\\n";
              $sMensagemErro .= "Erro Técnico : {$oDaoTransfAprov->erro_msg}";
              throw new BusinessException();
            }
          }

          $cldiarioavaliacao->excluir(""," ed72_i_diario = $coddiario");
          if ($cldiarioavaliacao->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir avaliações vinculadas a regência.\\n";
            $sMensagemErro .= "Erro Técnico : {$cldiarioavaliacao->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          $claprovconselho->excluir(""," ed253_i_diario = $coddiario");
          if ($claprovconselho->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir aprovações pelo conselho vinculadas a regência.\\n";
            $sMensagemErro .= "Erro Técnico : {$claprovconselho->erro_msg}";
            throw new BusinessException();
          }
          

          $cldiario->excluir(""," ed95_i_codigo = $coddiario");
          if ($cldiario->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir diários de avaliação vinculados a regência.\\n ";
            $sMensagemErro .= "Erro Técnico : {$cldiario->erro_msg}";
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

            $sMensagemErro = "Erro ao excluir faltas do aluno.\\nErro técnico : {$oDaoDiarioClasseAlunoFalta->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          /**
           * Excluir da diarioclasseregenciahorario
           */
          $oDaoDiarioClasseRegenciaHorario->excluir($oDadosDiarioClasse->ed302_sequencial);
          if ($oDaoDiarioClasseRegenciaHorario->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir periodos de aula do aluno. \\n";
            $sMensagemErro .= "Erro técnico : {$oDaoDiarioClasseRegenciaHorario->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          unset($oDadosDiarioClasse);
        }
        $sDiarioClasseExcluir = implode(",", $aDiarioClasseExcluidos);
        $oDaoDiarioClasse->excluir(null, "ed300_sequencial in ({$sDiarioClasseExcluir})");
        if ($oDaoDiarioClasse->erro_status == 0) {

          $sMensagemErro  = "Erro ao excluir dados do diario de classe do professor.\\n";
          $sMensagemErro .= "Erro técnico : {$oDaoDiarioClasse->erro_msg}";
          throw new BusinessException($sMensagemErro);
        }

      }
      $clregenciahorario->excluir(""," ed58_i_regencia = $ed59_i_codigo");
      if ($clregenciahorario->erro_status == 0) {
        throw new BusinessException("Erro ao excluir periodos de aula da disciplina.\\n{$clregenciahorario->erro_msg}");
      }
      $clregenciaperiodo->excluir(""," ed78_i_regencia = $ed59_i_codigo");
      if ($clregenciaperiodo->erro_status == 0) {
        $sMensagemErro  = "Erro ao excluir quantidade de aulas dadas no período.\\n";
        $sMensagemErro .= "Erro Técnico{$clregenciaperiodo->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clregencia->excluir($ed59_i_codigo);
      if ($clregencia->erro_status == 0) {
        throw new BusinessException("Erro ao excluir disciplina da turma .\\n Erro Técnico{$clregencia->erro_msg}");
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
   * Código da disciplina global na base curricular
   */
  $iDisciplina   = null;

  /**
   * Variável para controle se há uma disciplina global
   */
  $lTemGlobal    = false;

  /**
   * Variável para controle se a disciplina global já foi inclusa como regência da turma
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
         * Seta uma frequência padrão, que será alterada de acordo com as validações
         * 1º - Caso o campo ed34_c_condicao esteja vazio, OU seja, há uma disciplina global no SQL $sSqlBase, ou exista
         *      uma disciplina global e código de $iDisciplina seja igual a disciplina percorrida, seta frequência como
         *      global FA
         *
         * 2º - Caso o campo ed34_c_condicao seja diferente de vazio E exista uma disciplina global, porém o código de
         *      $iDisciplina seja diferente da disciplina percorrida (ou seja, existe global porém a disciplina em questão
         *      não é esta global), seta a frequência como TRATADA 'A
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
      var msg  = "ATENÇÃO!\n\n Disciplina(s):\n <?=$nobase?>\nnão contém na base curricular.\n\n";
      msg += "Desta forma, ao atualizar as disciplinas pela base, a grade de horário / vínculo do professor com a turma";
      msg += " será alterado, removendo a(s) disciplina(s) em questão caso estas tenham sido vinculadas com regente.\n\n";
      msg += "Deseja excluir esta(s) disciplina(s) e alterar a grade de horário / vínculo da turma?";
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