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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$iAnoEtapaCenso = null;
$oPost          = db_utils::postMemory($_POST);

$clturma                     = new cl_turma;
$clturmaturnoadicional       = new cl_turmaturnoadicional;
$clescola                    = new cl_escola;
$clescolaestrutura           = new cl_escolaestrutura;
$clregencia                  = new cl_regencia;
$clregenciahorario           = new cl_regenciahorario;
$clmatricula                 = new cl_matricula;
$clturmaserieregimemat       = new cl_turmaserieregimemat;
$oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();

$db_opcao     = 22;
$db_opcao1    = 3;
$db_botao     = false;
$db_botao2    = true;
$codigoescola = db_getsession("DB_coddepto");

$aMapaTurnoReferente = array(1 => 'MANHÃ', 2 => 'TARDE', 3 => 'NOITE');

/**
 * Responsável por excluir os vínculos das tabelas matriculaturnoreferente e turmaturnoreferente
 * @param array   $aTurnoAnterior - Contêm as informações dos turnos que a turma possuia vínculo
 * @param integer $iTurma         - Códiga da turma
 */
function excluirVinculosTurno( $aTurnoAnterior, $iTurma ) {

  $oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();
  $oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();

  foreach( $aTurnoAnterior as $oTurnoRefente ) {

    $oDaoMatriculaTurnoReferente->excluir( null, "ed337_turmaturnoreferente = {$oTurnoRefente->ed336_codigo}" );

    if ( $oDaoMatriculaTurnoReferente->erro_status == "0" ) {

      $lErroTransacao = true;
      $sMensagem      = "Erro ao excluir os registros da tabela matriculaturnoreferente:\n";
      $sMensagem     .= $oDaoMatriculaTurnoReferente->erro_msg;
      db_msgbox( $sMensagem );
    }
  }

  $oDaoTurmaTurnoReferente->excluir( null, "ed336_turma = {$iTurma}" );
  if ( $oDaoTurmaTurnoReferente->erro_status == "0" ) {

    $lErroTransacao = true;
    $sMensagem      = "Erro ao excluir os registros da tabela turmaturnoreferente:\n";
    $sMensagem     .= $oDaoTurmaTurnoReferente->erro_msg;
    db_msgbox( $sMensagem );
  }
}

function buscaAnoEtapaCenso( $iTurma ) {

  $oDaoTurmaCensoEtapa    = new cl_turmacensoetapa();
  $sWhereTurmaCensoEtapa  = " ed132_turma = {$iTurma}";
  $sSqlTurmaCensoEtapa    = $oDaoTurmaCensoEtapa->sql_query_file(null, "ed132_ano", null, $sWhereTurmaCensoEtapa);
  $rsTurmaCensoEtapa      = db_query( $sSqlTurmaCensoEtapa );

  if ( !$rsTurmaCensoEtapa ) {
    throw new DBException("Não foi possivel buscar o vinculo da turma com o censo.");
  }

  if ( pg_num_rows( $rsTurmaCensoEtapa ) == 0 ) {
    throw new DBException("Não há vinculos do censo com a turma.");
  }

  return db_utils::fieldsMemory( $rsTurmaCensoEtapa, 0 )->ed132_ano;
}

if (isset($alterar)) {

  $db_opcao          = 2;
  $db_opcao1         = 3;
  $lErroTransacao    = false;
  $aTurnosReferentes = explode( ", " , $ed336_turnoreferente );
  $iTotalTurnos      = count( $aTurnosReferentes );
  $aNovosTurnos      = array();
  
  $oTurma              = TurmaRepository::getTurmaByCodigo($ed57_i_codigo);
  $aTurnoAnterior      = $oTurma->getTurnoReferente();

  //Quando turma tem um ensino vinculado ao ensinoinfantil
  $lTurmaInfantil      = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->isInfantil();
  $lTurmaTurnoIntegral = $oTurma->getTurno()->isIntegral() ? true : false;

  $iAnoEtapaCenso = buscaAnoEtapaCenso( $ed57_i_codigo );

  db_inicio_transacao();

  /**
   * verificamos se foi alterado o turno, para desvincular na regenciahorarios
   */
  $oDaoTurma           = new cl_turma();
  $oDaoRegencia        = new cl_regencia();
  $oDaoRegenciaHorario = new cl_regenciahorario();

  $iTurnoSelecionado   = $ed57_i_turno;
  $iTurma              = $ed57_i_codigo;
  $iTurnoReferente     = 1;

  $sSqlTurma   = $oDaoTurma->sql_query_file($iTurma, "ed57_i_turno", null, null);
  $rsTurma     = $oDaoTurma->sql_record($sSqlTurma);
  $iTurnoAtual = db_utils::fieldsMemory($rsTurma, 0)->ed57_i_turno;

  $lAlterarSomenteVagas = false;

  /*
   * se os turnos forem diferentes, desvinculamos na regenciahorarios
   */
  if ($iTurnoAtual != $iTurnoSelecionado) {

    $sCampo       = "array_to_string(array_accum(ed59_i_codigo),',' ) as ed59_i_codigo";
    $sSqlRegencia = $oDaoRegencia->sql_query_file(null, $sCampo, null, "ed59_i_turma = {$iTurma}");
    $rsRegencia   = $oDaoRegencia->sql_record($sSqlRegencia);
    $sRegencias   = db_utils::fieldsMemory($rsRegencia, 0)->ed59_i_codigo;

    $sUpdateregenciahorario = "update regenciahorario set ed58_ativo = false where ed58_i_regencia in ({$sRegencias})";

    if (!db_query($sUpdateregenciahorario)) {

      $sErro = "ERRO : erro ao desvincular regenciahorario";
      db_msgbox($sErro);
      db_fim_transacao(true);
    }

    /**
     * Procedimento para turma de turno integral e infantil, que tenha selecionado apenas 1 turno
     * 1º Exclui todos os vínculos das matrículas com o turno ( matriculaturnoreferente )
     * 2º Exclui o vínculo da turma com os turnos ( turmaturnoreferente )
     * 3º Percorre o mapa dos turnos e verifica qual o turno referente selecionado
     * 4º Inclui um novo registro na tabela turmaturnoreferente
     *
     * O vínculo das matrículas será feito ao percorrer os alunos matriculados na turma
     */
    if ( $iTotalTurnos == 1 ) {

      excluirVinculosTurno( $aTurnoAnterior, $iTurma );

      $IndiceCorreto = null;
      foreach( $aMapaTurnoReferente as $iIndice => $sValor ) {

        if ( $ed336_turnoreferente == $sValor ) {
          $IndiceCorreto = $iIndice;
        }
      }

      $oDaoTurmaTurnoReferente->ed336_turma          = $iTurma;
      $oDaoTurmaTurnoReferente->ed336_turnoreferente = $IndiceCorreto;
      $oDaoTurmaTurnoReferente->ed336_vagas          = $vagasTurma;
      $oDaoTurmaTurnoReferente->incluir( null );

      $aNovosTurnos[ $IndiceCorreto ] = $oDaoTurmaTurnoReferente->ed336_codigo;
    }

    /**
     * Procedimento para turma de turno normal e que selecionou 2 turnos
     * 1º Guarda o turno referente anterior da turma
     * 2º Exclui todos os vínculos das matrículas com o turno ( matriculaturnoreferente )
     * 3º Exclui o vínculo da turma com os turnos ( turmaturnoreferente )
     * 4º Verifica o número de vagas a ser salvo de acordo com cada turno
     * 5º Inclui os vínculos na tabela turmaturnoreferente
     *
     * O vínculo das matrículas será feito ao percorrer os alunos matriculados na turma
     */
    if ( !$lTurmaTurnoIntegral && $iTotalTurnos == 2 ) {

      $iTotalTurnos = count( $aTurnosReferentes );

      excluirVinculosTurno( $aTurnoAnterior, $iTurma );

      for ( $iContador = 0; $iContador < $iTotalTurnos; $iContador++ ) {

        if ( $iTurnoReferente = array_search( $aTurnosReferentes[ $iContador ], $aMapaTurnoReferente ) ) {

          $iVagas = 0;

          switch ( $iTurnoReferente ) {

            case 1:

              $iVagas = isset($vagasmanha) ? $vagasmanha : $vagasTurma;
              break;

            case 2:

              $iVagas = isset($vagastarde) ? $vagastarde : $vagasTurma;
              break;

            case 3:

              $iVagas = $vagasnoite;
              break;
          }

          $oDaoTurmaTurnoReferente->ed336_turma          = $iTurma;
          $oDaoTurmaTurnoReferente->ed336_turnoreferente = $iTurnoReferente;
          $oDaoTurmaTurnoReferente->ed336_vagas          = $iVagas;
          $oDaoTurmaTurnoReferente->incluir( null );

          $aNovosTurnos[ $iTurnoReferente ] = $oDaoTurmaTurnoReferente->ed336_codigo;

          if ( $oDaoTurmaTurnoReferente->erro_status == 0 ) {

            $lErroTransacao  = true;
            $sMensagem       = "Erro ao incluir vínculo da turma com o turno.\n";
            $sMensagem      .= $oDaoTurmaTurnoReferente->erro_msg;
            db_msgbox( $sMensagem );
          }
        }
      }
    }
  } else {

    $lAlterarSomenteVagas = true;

    $iVagas = 0;
    if ( isset($vagasTurma) ) {
      $iVagas = $vagasTurma;
    } elseif ( isset($vagasmanha) ) {
      $iVagas = $vagasmanha;
    }elseif ( isset($vagastarde) ) {
      $iVagas = $vagastarde;
    } elseif ( isset($vagasnoite) ) {
      $iVagas = $vagasnoite;
    }

    $sSqlAlteraVagas = " update turmaturnoreferente set ed336_vagas = {$iVagas} where ed336_turma = {$iTurma} ";
    $rsAlteraVagas   = db_query($sSqlAlteraVagas);

    if ( !$rsAlteraVagas ) {

      $lErroTransacao  = true;
      $sMensagem       = "Erro ao alterar número de vagas da turma.";
      db_msgbox( $sMensagem );
    }
  }

  if ( $ed57_i_tipoturma == 2 && isset($ed57_censoprogramamaiseducacao) ) {
  	$ed57_censoprogramamaiseducacao = '';
  }

  $clturma->ed57_censoprogramamaiseducacao = $ed57_censoprogramamaiseducacao;
  $clturma->ed57_c_descr                   = trim($ed57_c_descr);
  $clturma->alterar($ed57_i_codigo);

  if ( !$lAlterarSomenteVagas ) {

    foreach ($oTurma->getAlunosMatriculados() as $oMatricula ) {

      $oSituacaoAluno = $oMatricula->getAluno()->getSituacao();
      $oSituacaoAluno->setTurno(TurnoRepository::getTurnoByCodigo($iTurnoSelecionado));
      $oSituacaoAluno->salvar();

      /**
       * Percorre o array com os turnos vinculados a turma, e vincula a matrícula a estes turnos
       */
      foreach ( $aNovosTurnos as $iTurnoReferente => $iCodigoTabela ) {

        if ( $lTurmaInfantil && !array_key_exists( $iTurnoReferente, $aTurnoAnterior ) && $iTotalTurnos > 1 ) {
          continue;
        }

        $oDaoMatriculaTurnoReferente->ed337_codigo              = null;
        $oDaoMatriculaTurnoReferente->ed337_matricula           = $oMatricula->getCodigo();
        $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iCodigoTabela;
        $oDaoMatriculaTurnoReferente->incluir( null );

        if ( $oDaoMatriculaTurnoReferente->erro_status == 0 ) {

          $lErroTransacao  = true;
          $sMensagem       = "Erro ao incluir vínculo da matrícula com o turno.\n";
          $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;
          db_msgbox( $sMensagem );
        }
      }
    }
  }

  db_fim_transacao( $lErroTransacao );
  $db_botao = true;

} else if (isset($chavepesquisa)) {
	
  $db_opcao  = 2;
  $db_opcao1 = 3;

  $iAnoEtapaCenso = buscaAnoEtapaCenso( $chavepesquisa );

  $result    = $clturma->sql_record($clturma->sql_query_turma_etapa_censo($chavepesquisa, $iAnoEtapaCenso));
  db_fieldsmemory($result,0);

  $sCampos  = " array_to_string(array_accum (case ";
  $sCampos .= "                          when ed336_turnoreferente = 1 ";
  $sCampos .= "                            then 'MANHÃ' ";
  $sCampos .= "                          when ed336_turnoreferente = 2 ";
  $sCampos .= "                            then 'TARDE' ";
  $sCampos .= "                          else 'NOITE' ";
  $sCampos .= "                        end  ), ";
  $sCampos .= "           ', ') as ed336_turnoreferente ";

  $sWhereTurma        = "ed336_turma = {$chavepesquisa}";
  $sSqlTurnoReferente = $oDaoTurmaTurnoReferente->sql_query_file( null, $sCampos, null, $sWhereTurma );
  $rsTurnoReferente   = $oDaoTurmaTurnoReferente->sql_record($sSqlTurnoReferente);
  db_fieldsmemory($rsTurnoReferente, 0); 

  $db_botao = true;
  $result1  = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) ",""," ed60_i_turma = $ed57_i_codigo"));
  db_fieldsmemory($result1,0);
  
  $ed57_i_nummatr = $count;
  $result1        = $clturmaturnoadicional->sql_record($clturmaturnoadicional->sql_query("",
                                                                       "ed246_i_turno,ed15_c_nome as ed15_c_nomeadd",
                                                                       "",
                                                                       " ed246_i_turma = $ed57_i_codigo"
                                                                      )
                                             );
 if ($clturmaturnoadicional->numrows > 0) {
   db_fieldsmemory($result1,0);
 }

  $sDisciplinaGlobal = $ed31_c_contrfreq == 'G' ? 'S' : 'N';

 ?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a3.disabled = false;
   parent.document.formaba.a4.disabled = false;
   parent.document.formaba.a5.disabled = false;
   parent.document.formaba.a6.disabled = false;


    var sParametros  = 'iBase=<?=$ed31_i_codigo?>&sBase=<?=$ed31_c_descr?>&iCurso=<?=$ed31_i_curso?>';
    sParametros += '&iTurma=<?=$ed57_i_codigo?>&sTurma=<?=$ed57_c_descr?>&iTipoTurma=<?=$ed57_i_tipoturma?>';
    sParametros += '&cadastroBase=N&sDisciplinaGlobal=<?=$sDisciplinaGlobal?>';

    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'edu1_disciplinaetapa001.php?' + sParametros;
   
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = 'edu1_regenciahorarioabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                        '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_turno=<?=$ed57_i_turno?>';

    var sHRefAbaAluno  = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
        sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';

   if (<?=$ed57_i_tipoturma?> == 6) {
     
     sHRefAbaAluno  = 'edu1_alunoturmaprogressao001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
     sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
   }
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = sHRefAbaAluno;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href = 'edu1_parecerturma001.php?ed105_i_turma=<?=$ed57_i_codigo?>'+
                                       '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href = 'edu1_outrosprofissionaisturma001.php?iTurma=<?=$ed57_i_codigo?>' ;
  </script>
 <?
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/arrays.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_validaTipoTurma();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Turma</b></legend>
    <?include(modification("forms/db_frmturma.php"));?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed57_c_descr",true,1,"ed57_c_descr",true);
</script>
<?php
if (isset($alterar)) {
  
  if ($clturma->erro_status == "0") {
  	
    $clturma->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clturma->erro_campo != "") {
    	
      echo "<script> document.form1.".$clturma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturma->erro_campo.".focus();</script>";
    }
  } else {
  	
    $clturma->erro(true,false);
    $result = $clturma->sql_record($clturma->sql_query("",
                                                       "ed57_i_turno as turnoant,ed15_c_nome as descrant",
                                                       "",
                                                       " ed57_i_codigo = $ed57_i_codigo"
                                                      )
                                  );
    db_fieldsmemory($result,0);
    
    if ($turnoant != $ed57_i_turno) {
    	
      $result1 = $clregencia->sql_record($clregencia->sql_query("",
                                                                "ed59_i_codigo as regencia",
                                                                "",
                                                                " ed59_i_turma = $ed57_i_codigo"
                                                               )
                                        );
                                        
      for ($c = 0; $c < $clregencia->numrows; $c++) {
      	
        db_fieldsmemory($result1,$c);
        $sSqlRegenciaHorario = $clregenciahorario->sql_query_file(null, 
                                                                  "ed58_i_codigo", 
                                                                  null, 
                                                                  "ed588_i_regencia={$regencia}"
                                                                 );
        $rsRegenciaHorario    = $clregenciahorario->sql_record($sSqlRegenciaHorario);
        $iTotalLinhasRegencia = $clregenciahorario->numrows; 
        if ($iTotalLinhasRegencia > 0) {
          
          for ($iDiario = 0; $iDiario < $iTotalLinhasRegencia; $iDiario++) {
            
            $clregenciahorario->ed58_i_codigo = db_utils::fieldsMemory($rsRegenciaHorario, $iDiario)->ed58_i_codigo; 
            $clregenciahorario->ed58_ativo    = "false";
            $clregenciahorario->alterar($clregenciahorario->ed58_i_codigo);
          }
        }
        
        /**
         * Cancelamos os dados da regencia para false
         */
        
      }
    }
    $sCampos = "ed246_i_codigo as codconf,ed246_i_turno as turnoaddant";
    $result2 = $clturmaturnoadicional->sql_record($clturmaturnoadicional->sql_query_file("",
                                                                       $sCampos,
                                                                       "",
                                                                       " ed246_i_turma = $ed57_i_codigo"
                                                                      )
                                        );
    $linhas2 = $clturmaturnoadicional->numrows;
    if ($linhas2 > 0) {
      $turnoaddant = pg_result($result2,0,'turnoaddant');
    }
    
    if ($ed246_i_turno == "") {
    	
      if ($clturmaturnoadicional->numrows > 0) {
      	
        db_fieldsmemory($result2,0);
        $clturmaturnoadicional->excluir($codconf);
        $exclusaoadd = true;
      }
    } else {
    	
      if ($clturmaturnoadicional->numrows > 0) {
      	
        db_fieldsmemory($result2,0);
        $clturmaturnoadicional->ed246_i_turma  = $ed57_i_codigo;
        $clturmaturnoadicional->ed246_i_codigo = $codconf;
        $clturmaturnoadicional->alterar($codconf);
      } else {
      	
        $clturmaturnoadicional->ed246_i_turma = $ed57_i_codigo;
        $clturmaturnoadicional->incluir(null);
      }
    }
  
    if ($linhas2 > 0) {

      if (($ed246_i_turno != "" && $ed246_i_turno != $turnoaddant) || isset($exclusaoadd)) {

        $sWhere   = " ed59_i_turma = $ed57_i_codigo AND ed17_i_turno = $turnoaddant and ed58_ativo is true  ";
        $result1  = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                                 "ed58_i_codigo as codreghora",
                                                                                 "",
                                                                                 $sWhere
                                                                                )
                                                 );
        $linhas11 = $clregenciahorario->numrows;
        for ($c = 0; $c < $linhas11; $c++) {

          db_fieldsmemory($result1,$c);
          $clregenciahorario->ed58_i_codigo = $codreghora;
          $clregenciahorario->ed58_ativo    = "false";
          $clregenciahorario->alterar($codreghora);
        }
      }
    }
    ?>
    <script>
    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a3.disabled = false;
    parent.document.formaba.a4.disabled = false;
    parent.document.formaba.a5.disabled = false;
    parent.document.formaba.a6.disabled = false;

    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = 'edu1_regenciahorarioabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                        '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_turno=<?=$ed57_i_turno?>';

    var sHRefAbaAluno  = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
        sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
    
    if (<?=$ed57_i_tipoturma?> == 6) {
      
      sHRefAbaAluno  = 'edu1_alunoturmaprogressao001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
      sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
    }

    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = sHRefAbaAluno;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href = 'edu1_parecerturma001.php?ed105_i_turma=<?=$ed57_i_codigo?>'+
                                        '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href = 'edu1_outrosprofissionaisturma001.php?iTurma=<?=$ed57_i_codigo?>' ;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href = 'edu1_turma002.php?chavepesquisa=<?=$ed57_i_codigo?>';
    </script>
  <?php
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>