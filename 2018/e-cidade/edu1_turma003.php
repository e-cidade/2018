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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$iAnoEtapaCenso = null;

$clturma               = new cl_turma;
$clescola              = new cl_escola;
$clescolaestrutura     = new cl_escolaestrutura;
$clmatricula           = new cl_matricula;
$clregencia            = new cl_regencia;
$clalunotransfturma    = new cl_alunotransfturma;
$clregenteconselho     = new cl_regenteconselho;
$cltrocaserie          = new cl_trocaserie;
$clturmaturnoadicional = new cl_turmaturnoadicional;
$clparecerturma        = new cl_parecerturma;
$clregenciahorario     = new cl_regenciahorario;
$clregenciaperiodo     = new cl_regenciaperiodo;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clturmaturnoreferente = new cl_turmaturnoreferente;
$clturmalog            = new cl_turmalog;
$oDaoTurmaCensoEtapa   = new cl_turmacensoetapa();
$db_botao              = false;
$db_botao2             = true;
$db_opcao              = 33;
$db_opcao1             = 3;
$codigoescola          = db_getsession("DB_coddepto");

$oDaoDiarioClasseRegenciaHorario = new cl_diarioclasseregenciahorario();
$oDaoDiarioClasseAlunoFalta      = new cl_diarioclassealunofalta();
$oDaoDiarioClasse                = new cl_diarioclasse();

if( isset( $excluir ) ) {
  
  $db_opcao = 3;
  $db_opcao1= 3;
  $result1  = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo",""," ed60_i_turma = $ed57_i_codigo"));

  if ($clmatricula->numrows > 0) {
    
    db_msgbox("Turma $ed57_c_descr não pode ser excluída, pois possui matrículas vinculadas!");
    $db_opcao = 33;
  } else {
    
    try {

      db_inicio_transacao();

      /**
       * Exclui o vínculo da turma com o censo
       */
      $sWhereTurmaCensoEtapa = " ed132_turma = {$ed57_i_codigo}";
      $oDaoTurmaCensoEtapa->excluir( null, $sWhereTurmaCensoEtapa);

      if ($oDaoTurmaCensoEtapa->erro_status == 0) {
        
        $sMensagemErro = "Erro ao excluir vínculo da turma com o censo.\\nErro técnico : {$oDaoTurmaCensoEtapa->erro_msg}";
        throw new BusinessException($sMensagemErro);          
      }
  
      /**
       * Excluir dados das faltas dos alunos
       */
      /**
       * select na diarioclasseregenciahorario pelo codigo das regencias 
       *  - excluir diarioclassealunofalta
       *  - excluir diarioclasseregenciahorario
       *  - diarioclasse
       */
      $sWhereDiarioClasse  = "ed59_i_turma = {$ed57_i_codigo}";
      $sSqlDiarioClasse    = $oDaoDiarioClasseRegenciaHorario->sql_query(null, 
                                                                         "diarioclasseregenciahorario.*",
                                                                         null,
                                                                         $sWhereDiarioClasse
                                                                        );
                                                                          
      $rsDiarioClasse     = $oDaoDiarioClasseRegenciaHorario->sql_record($sSqlDiarioClasse);
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

      $clregenciahorario->excluir("",
                                  " ed58_i_regencia in (select ed59_i_codigo from 
                                                                           regencia where ed59_i_turma = $ed57_i_codigo)"
                                 );
      if ($clregenciahorario->erro_status == 0) {
      
        $sMensagemErro   = "Períodos da regência nao excluídos.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clregenciahorario->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clregenciaperiodo->excluir("",
                                  " ed78_i_regencia  in (select ed59_i_codigo from 
                                                                            regencia where ed59_i_turma = $ed57_i_codigo)"
                                 );
                                 
      if ($clregenciaperiodo->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir aulas dadas da regência.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clregenciaperiodo->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clregencia->excluir(""," ed59_i_turma = $ed57_i_codigo");
      if ($clregencia->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao Excluir regências da turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clregencia->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }
      
      $rsAlunoPossib = db_query("UPDATE alunopossib SET ed79_i_turmaant = null WHERE ed79_i_turmaant = $ed57_i_codigo");
      if (!$rsAlunoPossib) {
      
        $sMensagemErro   = "Erro ao alterar turma anterior dos alunos.\\n ";
        $sMensagemErro  .= "Erro Técnico : ".pg_last_error();
        throw new BusinessException($sMensagemErro);
      }

      $rsMatricula = db_query("UPDATE matricula SET ed60_i_turmaant = null WHERE ed60_i_turmaant = $ed57_i_codigo");
      if (!$rsMatricula) {
      
        $sMensagemErro   = "Erro ao alterar turma anterior das matricula dos alunos.\\n ";
        $sMensagemErro  .= "Erro Técnico : ".pg_last_error();
        throw new BusinessException($sMensagemErro);
      }

      $clalunotransfturma->excluir("","ed69_i_turmaorigem = $ed57_i_codigo or ed69_i_turmadestino = $ed57_i_codigo");
      if ($clalunotransfturma->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir transferencias de turma em qual a turma está envolvida.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clalunotransfturma->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clparecerturma->excluir(""," ed105_i_turma = $ed57_i_codigo");
      if ($clparecerturma->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir pareceres vinculados a turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clparecerturma->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clregenteconselho->excluir("","ed235_i_turma = $ed57_i_codigo");
      if ($clregenteconselho->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir conselheiro da turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clregenteconselho->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $cltrocaserie->excluir("","ed101_i_turmaorig = $ed57_i_codigo or ed101_i_turmadest = $ed57_i_codigo");
      if ($cltrocaserie->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir progressoes/avanços vínculados a turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$cltrocaserie->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clturmaturnoadicional->excluir("","ed246_i_turma = $ed57_i_codigo");
      if ($clturmaturnoadicional->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir turno da turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clturmaturnoadicional->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clturmaserieregimemat->excluir("","ed220_i_turma = $ed57_i_codigo");
      if ($clturmaserieregimemat->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir etapas da turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clturmaserieregimemat->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clturmalog->excluir("","ed287_i_turma = $ed57_i_codigo");
      if ($clturmalog->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir logs da turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clturmalog->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clturmaturnoreferente->excluir("", "ed336_turma = $ed57_i_codigo");
      if ($clturmaturnoreferente->erro_status == 0) {

        $sMensagemErro  = "Erro ao exlcuir vínculo com o turno referente a turma.";
        $sMensagemErro .= "Erro Técnico : {$clturmaturnoreferente->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $clturma->excluir($ed57_i_codigo);
      if ($clturma->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir turma.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$clturma->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      db_fim_transacao(false);
    } catch (BusinessException $eBusinnes) {
      
      $clturma->erro_status = "0";
      $clturma->erro_msg = $eBusinnes->getMessage();
      db_fim_transacao(true); 
    }
  }
} else if( isset( $chavepesquisa ) ) {

  $db_opcao  = 3;
  $db_opcao1 = 3;

  $sWhereTurmaCensoEtapa  = " ed132_turma = {$chavepesquisa}";
  $sSqlTurmaCensoEtapa    = $oDaoTurmaCensoEtapa->sql_query_file(null, "ed132_ano", null, $sWhereTurmaCensoEtapa);
  $rsTurmaCensoEtapa      = db_query( $sSqlTurmaCensoEtapa );

  if ( !$rsTurmaCensoEtapa ) {
    throw new DBException("Não foi possivel buscar o vinculo da turma com o censo.");
  }

  if ( pg_num_rows( $rsTurmaCensoEtapa ) == 0 ) {
    throw new DBException("Não há vinculos do censo com a turma.");
  }

  $iAnoEtapaCenso = db_utils::fieldsMemory( $rsTurmaCensoEtapa, 0 )->ed132_ano;

  $result    = $clturma->sql_record($clturma->sql_query_turma_etapa_censo( $chavepesquisa, $iAnoEtapaCenso ));

  db_fieldsmemory( $result, 0 );
  $db_botao  = true;
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
<script type="text/javascript" src="scripts/arrays.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_validaTipoTurma();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Exclusão de Turma</b></legend>
    <?include("forms/db_frmturma.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?php
if( isset( $excluir ) ) {

  if( $clturma->erro_status == "0" ) {
    $clturma->erro( true, false );
  } else {
    $clturma->erro( true, true );
  }
}

if( $db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}