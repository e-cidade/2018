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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_jsplibwebseller.php");

if ( !isset( $ed60_d_datamatricula_dia ) ) {
  
  $ed60_d_datamatricula_dia = substr( $datamat, 0, 2 );
  $ed60_d_datamatricula_mes = substr( $datamat, 3, 2 );
  $ed60_d_datamatricula_ano = substr( $datamat, 6, 4 );
}

db_postmemory($HTTP_POST_VARS);

$clmatricula                 = new cl_matricula;
$clmatriculamov              = new cl_matriculamov;
$cledu_parametros            = new cl_edu_parametros;
$clmatriculaserie            = new cl_matriculaserie;
$clcalendario                = new cl_calendario;
$clturma                     = new cl_turma;
$clturmaserieregimemat       = new cl_turmaserieregimemat;
$claluno                     = new cl_aluno;
$clbase                      = new cl_base;
$clserie                     = new cl_serie;
$clserieequiv                = new cl_serieequiv;
$clalunopossib               = new cl_alunopossib;
$clalunocurso                = new cl_alunocurso;
$clhistoricomps              = new cl_historicomps;
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();
$oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();
$clrotulo                    = new rotulocampo;

$db_opcao = 1;
$db_botao = true;
$escola   = db_getsession("DB_coddepto");

$clrotulo->label("ed60_d_datamatricula");
$result_parametros = $cledu_parametros->sql_record( $cledu_parametros->sql_query( "", "*", "", "ed233_i_escola = {$escola}" ) );

if ( $cledu_parametros->numrows > 0 ) {
  db_fieldsmemory( $result_parametros, 0 );	
} else {
  
  echo "Erro! Parâmetros não informados";
  exit;	
}

if ( isset( $incluir ) ) {
  
  $msg_mat        = "";
  $lErroTransacao = false;
  db_inicio_transacao();
  
  for ( $i = 0; $i < count( $codigoaluno ); $i++ ) {
    
    $sCamposTurma  = "ed57_i_escola, ed57_i_base, ed57_i_calendario, ed57_i_turno, ed57_c_descr";
    $sCamposTurma .= ", fc_codetapaturma(ed57_i_codigo) as etapasturma";
    $sSqlTurma     = $clturma->sql_query( "", $sCamposTurma, "", "ed57_i_codigo = {$turma}" );
    $result_tur    = $clturma->sql_record( $sSqlTurma );
    db_fieldsmemory( $result_tur, 0 );
    
    $erro_mat          = false;
    $sCamposMatricula  = "ed60_i_codigo as jatem, ed47_v_nome as nometem, turma.ed57_c_descr as turmatem";
    $sCamposMatricula .= ", calendario.ed52_c_descr as caltem";
    $sWhereMatricula   = "ed60_i_aluno = {$codigoaluno[$i]} AND turma.ed57_i_calendario = {$ed57_i_calendario}";
    $sSqlMatricula     = $clmatricula->sql_query( "", $sCamposMatricula, "", $sWhereMatricula );
    $result_verif      = $clmatricula->sql_record( $sSqlMatricula );
    
    if ( $clmatricula->numrows > 0 ) {
      
      db_fieldsmemory( $result_verif, 0 );
      $msg_mat .= "ATENÇÃO:\\n\\nAluno(a) {$nometem} já está matriculado(a) na turma {$turmatem} no calendário {$caltem}!\\n\\n";
      $erro_mat = true;
    } else if (    VerUltimoRegHistorico( $codigoaluno[$i], $etapaorigem[$i], $etapasturma ) == true 
                && $ed233_c_consistirmat == 'S' ) {
      
      $msg_mat  .= $msgequiv;// $msgequiv -> variável global da função VerUltimoRegHistorico
      $erro_mat  = true;
      unset( $msgequiv );
    }
    
    if ( $erro_mat == false ) {
      
      $sCamposAlunoPossib = "ed56_i_codigo,ed79_i_codigo,ed79_c_resulant,ed79_i_turmaant";
      $sSqlAlunoPossib    = $clalunopossib->sql_query( "", $sCamposAlunoPossib, "", "ed56_i_aluno = {$codigoaluno[$i]}" );
      $result1            = $clalunopossib->sql_record( $sSqlAlunoPossib );
      db_fieldsmemory( $result1, 0 );
      
      $ed79_i_turmaant = $ed79_i_turmaant == "0" ? "" : $ed79_i_turmaant;
      
      $sSqlMatricula2 = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", "ed60_i_turma = {$turma}" );
      $result2        = $clmatricula->sql_record( $sSqlMatricula2 );
      db_fieldsmemory( $result2, 0 );
      
      $max = $max == "" ? "" : ( $max + 1 );
      
      $sSqlAlunoCurso = $clalunocurso->sql_query_file( "", "ed56_c_situacao as sitanterior", "", "ed56_i_aluno = {$codigoaluno[$i]}" );
      $result3        = $clalunocurso->sql_record( $sSqlAlunoCurso );
      
      $sitanterior     = pg_result( $result3, 0, 0 );
      $sitmatricula    = trim( $sitanterior ) == "CANDIDATO" ? "MATRICULAR" : "REMATRICULAR";
      $sitmatricula1   = trim( $sitanterior ) == "CANDIDATO" ? "MATRICULADO" : "REMATRICULADO";
      $tipomatricula   = trim( $sitanterior ) == "CANDIDATO" ? "N" : "R";
      $ed79_i_turmaant = $ed79_i_turmaant == "" ? "null" : $ed79_i_turmaant;
      
      $clmatricula->ed60_i_numaluno     = $max;
      $clmatricula->ed60_i_aluno        = $codigoaluno[$i];
      $clmatricula->ed60_i_turma        = $turma;
      $clmatricula->ed60_c_situacao     = "MATRICULADO";
      $clmatricula->ed60_c_concluida    = "N";
      $clmatricula->ed60_t_obs          = "";
      $clmatricula->ed60_i_turmaant     = $ed79_i_turmaant;
      $clmatricula->ed60_c_rfanterior   = $ed79_c_resulant;
      $clmatricula->ed60_d_datamodif    = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
      $clmatricula->ed60_d_datamodifant = null;
      $clmatricula->ed60_d_datasaida    = "null";
      $clmatricula->ed60_c_ativa        = "S";
      $clmatricula->ed60_c_tipo         = $tipomatricula;
      $clmatricula->ed60_c_parecer      = "N";
      $clmatricula->ed60_matricula      = null;
      $clmatricula->incluir(null);
      
      $ultima = $clmatricula->ed60_i_codigo;
      $clmatriculamov->ed229_i_matricula    = $ultima;
      $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
      $clmatriculamov->ed229_c_procedimento = "$sitmatricula ALUNO";
      $clmatriculamov->ed229_t_descr        = "ALUNO {$sitmatricula1} NA TURMA {$ed57_c_descr}. SITUAÇÃO ANTERIOR: ".trim( $sitanterior );
      $clmatriculamov->ed229_d_dataevento   = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
      $clmatriculamov->ed229_c_horaevento   = date("H:i");
      $clmatriculamov->ed229_d_data         = date( "Y-m-d", db_getsession("DB_datausu") );
      $clmatriculamov->incluir(null);
      
      $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query( "", "ed223_i_serie as codetapaturma", "", "ed220_i_turma = {$turma}" );
      $result_etapa            = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );
      
      for ( $r = 0; $r < $clturmaserieregimemat->numrows; $r++ ) {
       
        db_fieldsmemory( $result_etapa, $r );
        
        if ( $codetapaturma == $etapaorigem[$i] ) {
          $origem = "S";
        } else {
          $origem = "N";
        }
        
        $clmatriculaserie->ed221_i_matricula = $ultima;
        $clmatriculaserie->ed221_i_serie     = $codetapaturma;
        $clmatriculaserie->ed221_c_origem    = $origem;
        $clmatriculaserie->incluir(null);
      }
      
      $clalunocurso->ed56_c_situacao   = "MATRICULADO";
      $clalunocurso->ed56_i_calendario = $ed57_i_calendario;
      $clalunocurso->ed56_i_base       = $ed57_i_base;
      $clalunocurso->ed56_i_escola     = $ed57_i_escola;
      $clalunocurso->ed56_i_codigo     = $ed56_i_codigo;
      $clalunocurso->alterar( $ed56_i_codigo );
      
      $clalunopossib->ed79_i_serie  = $etapaorigem[$i];
      $clalunopossib->ed79_i_turno  = $ed57_i_turno;
      $clalunopossib->ed79_i_codigo = $ed79_i_codigo;
      $clalunopossib->alterar( $ed79_i_codigo );
      
      $sql2 = "UPDATE historico SET
                      ed61_i_escola = {$ed57_i_escola}
                WHERE ed61_i_aluno = {$codigoaluno[$i]}";
      $query2 = db_query( $sql2 );
      
      /**
       * Busca os turnos referentes vinculados a turma
       */
      $sWhereTurmaTurnoReferente = "ed336_turma = {$turma}";
      $sSqlTurmaTurnoReferente   = $oDaoTurmaTurnoReferente->sql_query_file(
                                                                              null,
                                                                              "ed336_codigo",
                                                                              null,
                                                                              $sWhereTurmaTurnoReferente
                                                                           );
      $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );
      $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );
      
      if ( $rsTurmaTurnoReferente && $iTotalTurmaTurnoReferente > 0 ) {
      
        /**
         * Percorre os turnos vinculados e inclui um novo registro na tabela matriculaturnoreferente, vinculando a
         * matrícula ao turno da turma
         */
        for ( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {
      
          $iTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;
      
          $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iTurmaTurnoReferente;
          $oDaoMatriculaTurnoReferente->ed337_matricula           = $ultima;
          $oDaoMatriculaTurnoReferente->incluir( null );
      
          if ( $oDaoMatriculaTurnoReferente->erro_status == "0" ) {
      
            $lErroTransacao  = true;
            $sMensagem       = "Erro ao salvar dados do vínculo da matrícula com o turno:\n";
            $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;
            db_msgbox( $sMensagem );
          }
        }
      }
    }
  }
  
  db_fim_transacao( $lErroTransacao );
  
  if ( $msg_mat != "" ) {
    db_msgbox( $msg_mat );
  } else {
    
    if ( $clmatricula->erro_status != "0" ) {
      $clmatricula->erro( true, false );
    }
  }
  ?>
  <script>
    parent.location.href = "edu1_matricula001.php?chavepesquisa=<?=$turma?>";
    parent.db_iframe_matric.hide();
  </script>
  <?
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
  <div class="container">
    <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
    <br>
    <form name="form1" method="post" action="" class="form-container">
      <fieldset style="width:95%">
        <legend>Matricular Alunos</legend>
        <b>Informe a etapa de origem para cada aluno:</b><br>
        <table>
        <?php
          $escola    = db_getsession("DB_coddepto");
          $sSqlTurma = $clturma->sql_query( "", "ed52_d_inicio,ed52_d_fim", "", "ed57_i_codigo = {$turma}" );
          $result    = $clturma->sql_record( $sSqlTurma );
          db_fieldsmemory( $result, 0 );
          
          $data   = @$ed60_d_datamatricula_ano."-".@$ed60_d_datamatricula_mes."-".@$ed60_d_datamatricula_dia;
          $inicio = $ed52_d_inicio;
          $fim    = $ed52_d_fim;
          
          $sSqlTurmaSerieRegimeMat2 = $clturmaserieregimemat->sql_query(
                                                                         "",
                                                                         "ed223_i_serie, ed11_c_descr as descretapa",
                                                                         "ed223_i_ordenacao",
                                                                         "ed220_i_turma = {$turma}"
                                                                       );
          $result_etp = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat2 );
          db_fieldsmemory( $result_etp, 0 );
          
          $campos_sql   = "DISTINCT ed47_i_codigo, ed47_v_nome, ed56_c_situacao, ed11_i_codigo, ed11_c_descr, ed10_c_abrev";
          $sWhereAluno  = "ed56_i_aluno in ({$codalunos}) AND ed56_i_escola = {$escola}";
          $sSqlAluno    = $claluno->sql_query_matricula( "", $campos_sql, "ed47_v_nome", $sWhereAluno );
          $result       = $claluno->sql_record( $sSqlAluno );
          $linhas_aluno = $claluno->numrows;
          
          for ( $t = 0; $t < $claluno->numrows; $t++ ) {

            db_fieldsmemory( $result, $t );
            
            if ( $ed56_c_situacao == "APROVADO" ) {
              $sitdescr = "APROVADO (PARA {$ed11_c_descr} - {$ed10_c_abrev})";
            } else if ( $ed56_c_situacao == "REPETENTE" ) {
              $sitdescr = "REPETENTE (NA {$ed11_c_descr} - {$ed10_c_abrev})";
            } else if ( $ed56_c_situacao == "CANDIDATO" ) {
              $sitdescr = "CANDIDATO (NA {$ed11_c_descr} - {$ed10_c_abrev})";
            } else if ( $ed56_c_situacao == "APROVADO PARCIAL" ) {
              $sitdescr = "APROVADO PARCIALMENTE (NA {$ed11_c_descr} - {$ed10_c_abrev})";
            }
          ?>
          <tr>
            <td>
              <b><?=$ed47_i_codigo." - ".$ed47_v_nome?></b>
            </td>
            <td>
              <b><?="&nbsp;&nbsp;&nbsp;---> ".$sitdescr?></b>
            </td>
            <td>
              &nbsp;&nbsp;&nbsp;
              <select name="etapaorigem[]" id="etapaorigem">
                <option value=""></option>
                <?
                $temequiv       = false;
                $sSqlSerieEquiv = $clserieequiv->sql_query( "", "ed234_i_serieequiv", "", "ed234_i_serie = {$ed11_i_codigo}");
                $result_equiv   = $clserieequiv->sql_record( $sSqlSerieEquiv );
                
                for ( $r = 0; $r < $clturmaserieregimemat->numrows; $r++ ) {
            
                  db_fieldsmemory( $result_etp, $r );
                  
                  $selected = "";
                  $disabled = "disabled";
                  
                  if ( $clserieequiv->numrows > 0 ) {
            
                    for ( $w = 0; $w < $clserieequiv->numrows; $w++ ) {
            
                      db_fieldsmemory( $result_equiv, $w );
                      
                      if ( $ed234_i_serieequiv == $ed223_i_serie ) {
            
                        $selected = "selected";
                        $disabled = "";
                        break;
                      }
                    }
                  }
                  
                  if ( $ed11_i_codigo == $ed223_i_serie ) {
            
                    $selected = "selected";
                    $disabled = "";
                  }
                  
                  if ( $disabled == "" ) {
                    $temequiv = true;
                  }
                  ?>
                  <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
                  <?
                }
                ?>
              </select>
              <?=$temequiv == false ? "Etapa {$ed11_c_descr} não tem registros de etapas equivalentes" : ""?>
              <input name="codigoaluno[]" type="hidden" value="<?=$ed47_i_codigo?>">
            </td>
          </tr>
          <?
          }
          ?>
          <tr>
            <td colspan="3">
            <?php
              echo @$Led60_d_datamatricula;
              db_inputdata( 
                            'ed60_d_datamatricula', 
                            @$ed60_d_datamatricula_dia,
                            @$ed60_d_datamatricula_mes,
                            @$ed60_d_datamatricula_ano,
                            true,
                            'text',
                            $db_opcao
                          );
            ?>
            </td>
          </tr>
          <tr>
           <td colspan="3">
            <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterarnada" : "excluir" ) )?>" 
                   type="submit" 
                   id="db_opcao" 
                   value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>" 
                   <?=( $db_botao == false ? "disabled" : "")?> 
                   <?=( $db_opcao == 1 ? "onclick=\"return js_selecionar('$data','$inicio','$fim',$linhas_aluno)\"" : "" )?>  >
            <input name="turma" type="hidden" value="<?=$turma?>">
           </td>
          </tr>
        </table>
      </fieldset>
    </form>
  </div>
</body>
</html>
<?
if ( isset( $incluir ) ) {

  if ( $clmatricula->erro_status == "0" ) {

    $clmatricula->erro( true, false );
    $db_botao = true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ( $clmatricula->erro_campo != "" ) {

      echo "<script> document.form1.".$clmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatricula->erro_campo.".focus();</script>";
    }
  }
}
?>
<script>
function js_selecionar( data, inicio, fim, linhasaluno ) {
  
  if ( document.form1.ed60_d_datamatricula.value == "" ) {
    
    alert( "Informe a data para matricular o aluno!" );
    document.form1.ed60_d_datamatricula.focus();
    document.form1.ed60_d_datamatricula.style.backgroundColor = '#99A9AE';
    return false;
  } else {
    
    datamat  = document.form1.ed60_d_datamatricula_ano.value + "-" + document.form1.ed60_d_datamatricula_mes.value;
    datamat += "-" + document.form1.ed60_d_datamatricula_dia.value;
    dataini  = inicio;
    datafim  = fim;
    check    = js_validata( datamat, dataini, datafim );
    
    if ( check == false ) {
      
      data_ini = dataini.substr(8,2) + "/" + dataini.substr(5,2) + "/" + dataini.substr(0,4);
      data_fim = datafim.substr(8,2) + "/" + datafim.substr(5,2) + "/" + datafim.substr(0,4);
      
      alert( "Data da matrícula fora do periodo do calendario ( " + data_ini + " a " + data_fim + " )." );
      document.form1.ed60_d_datamatricula.focus();
      document.form1.ed60_d_datamatricula.style.backgroundColor = '#99A9AE';
      return false;
    }
  }
  
  selec = false;
  if ( linhasaluno == 1 ) {
    
    if ( document.form1.etapaorigem.value == "" ) {
      selec = true;
    }
  } else {
    
    for ( var i = 0; i < linhasaluno; i++ ) {
      
      if ( document.form1.etapaorigem[i].value == "" ) {
        
        selec = true;
        break;
      }
    }
  }
  
  if ( selec == true ) {
    
    alert("Informe a etapa de origem para todos os alunos!");
    return false;
  }
  
  return true;
}
</script>