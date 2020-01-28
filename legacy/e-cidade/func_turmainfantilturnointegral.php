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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

/**
 * Trazer:
 * -> somente turmas que sejam de Ensino infantil (Ensino tem  que estar vinculado na tabela ensinoinfantil);
 * -> somente turmas ativas (turma não pode estar encerrada);
 * -> somente turmas que tenhas turno integral (2 refencias de turno); 
 * -> somente turmas que tenham ao menos um aluno (MATRICULADO)
 */
 
$oPost = db_utils::postMemory( $_POST );
$oGet  = db_utils::postMemory( $_GET );

$oDaoTurma      = new cl_turma();
$oDaoCalendario = new cl_calendario();

$oDaoTurma->rotulo->label();
$oDaoCalendario->rotulo->label();

$iEscola = db_getsession("DB_coddepto");

$aWhere   = array();
$aWhere[] = " ed57_i_escola = {$iEscola} ";
$aWhere[] = " ed52_c_passivo = 'N' ";

// filtra somente turmas ativas (turma não pode estar encerrada);
$sTurmaAtiva  = " ( select exists ( select 1 ";
$sTurmaAtiva .= "                     from regencia   ";
$sTurmaAtiva .= "                    where regencia.ed59_i_turma = turma.ed57_i_codigo ";
$sTurmaAtiva .= "                      and ed59_c_encerrada = 'N' and ed59_c_condicao = 'OB' ) )" ;
$aWhere[]     = $sTurmaAtiva;

// filtra somente turmas de ensino infantil
$sTurmaEnsinoInfantil  = " cursoedu.ed29_i_codigo in (SELECT ed29_i_codigo as curso_infantil ";
$sTurmaEnsinoInfantil .= "                              FROM cursoedu";
$sTurmaEnsinoInfantil .= "                             INNER JOIN ensinoinfantil on ed117_ensino = ed29_i_ensino )";
$aWhere[]              = $sTurmaEnsinoInfantil;

//filtar turma de turno integral
$sTurmaTurnoIntegral = " ( select exists ( select count(*) FROM turnoreferente where ed231_i_turno = turma.ed57_i_turno having count(*) > 1 ) )";
$aWhere[]            = $sTurmaTurnoIntegral;

// filtra somente turmas que tenham ao menos um aluno (MATRICULADO)
$sAlunoMatriculado   = " ( select exists (select 1 from matricula where ed60_i_turma = turma.ed57_i_codigo and ed60_c_situacao = 'MATRICULADO') ) ";
$aWhere[]            = $sAlunoMatriculado;
?>

<html>
  
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  
  <body>
    
    <div class="container">
      
      <form name="form1" method="post" action="" >
        <table class='form-container'>
          <tr title="<?=$Ted57_i_codigo?>">
            <td nowrap='nowrap' >
              <?=$Led57_i_codigo?>
            </td>
            <td>
              <?db_input("ed57_i_codigo", 10, $Ied57_i_codigo, true, "text", 4, "", "chave_ed57_i_codigo");?>
            </td>
          </tr>
          
          <tr title="<?=$Ted57_c_descr?>">
            <td nowrap='nowrap' >
              <?=$Led57_c_descr?>
            </td>
            <td>
              <?db_input("ed57_c_descr", 30, $Ied57_c_descr, true, "text", 4, "", "chave_ed57_c_descr");?>
            </td>
          </tr>
          <tr title="<?=$Ted57_i_calendario?>">
            <td nowrap='nowrap' >
              <?=$Led57_i_calendario?>
            </td>
            <td>
              <?
                $sWhere  = "  ed52_c_passivo = 'N' AND ed38_i_escola = $iEscola ";
                $sCampos = "ed52_i_codigo,ed52_c_descr";
                
                $sSqlCalendario = $oDaoCalendario->sql_query_calescola("", $sCampos, "ed52_i_ano desc", $sWhere);
                $rsCalendario   = $oDaoCalendario->sql_record( $sSqlCalendario );

                if ( $rsCalendario && $oDaoCalendario->numrows == 0 ) {

                 $aCalendarios = array(''=>'NENHUM REGISTRO');
                 db_select('ed57_i_calendario', $aCalendarios, true, 1, "");
                } else {
                 db_selectrecord("ed57_i_calendario", $rsCalendario, "", "", "", "chave_ed57_i_calendario", "", "", "", 1);
                }
              ?>
            </td>
          </tr>
          
        </table>
        <br />
        <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turmainfantilturnointegral.hide();">
      </form>
    </div>
    <div class="container">
        <?php
          
          if ( !isset( $oGet->pesquisa_chave ) ) {
            
            $sCampos  = " turma.ed57_i_codigo, ";
            $sCampos .= " turma.ed57_i_codigoinep, ";
            $sCampos .= " turma.ed57_c_descr, ";
            $sCampos .= " fc_nomeetapaturma(ed57_i_codigo) as ed57_i_serie, ";
            $sCampos .= " calendario.ed52_c_descr as ed57_i_calendario, ";
            $sCampos .= " cursoedu.ed29_c_descr as ed31_i_curso, ";
            $sCampos .= " turno.ed15_c_nome as ed57_i_turno, ";
            $sCampos .= " sala.ed16_c_descr as ed57_i_sala ";
            
            if ( isset( $oPost->chave_ed57_i_codigo )  && !empty( $oPost->chave_ed57_i_codigo ) ) {
              $aWhere[] = " ed57_i_codigo = $oPost->chave_ed57_i_codigo";
            }
                    
            if ( isset( $oPost->chave_ed57_c_descr )  && !empty( $oPost->chave_ed57_c_descr ) ) {
              
              $sDescricaoTurma = trim($oPost->chave_ed57_c_descr);
              $aWhere[] = " trim(ed57_c_descr) ilike '$sDescricaoTurma%'";
            }
            
            if ( isset( $oPost->chave_ed57_i_calendario )  && !empty( $oPost->chave_ed57_i_calendario ) ) {
              $aWhere[] = " ed57_i_calendario = $oPost->chave_ed57_i_calendario";
            }
            
            $sOrdem = " ed57_c_descr";
            $sWhere = implode(" and ", $aWhere);
            $sSql   = $oDaoTurma->sql_query( null, "$sCampos", $sOrdem, $sWhere);
                       
            db_lovrot($sSql, 15, "()", "", $oGet->funcao_js);
                    
          } else {
            
            if ( !empty( $oGet->pesquisa_chave) ) {
              
              $sOrdem   = " ed57_c_descr, ed11_i_ensino, ed11_i_sequencia ";
              $aWhere[] = " ed57_i_codigo = $oGet->pesquisa_chave ";
              $sWhere   = implode(" and ", $aWhere);
              $rsTurma  = $oDaoTurma->sql_record( $oDaoTurma->sql_query( null, "*", $sOrdem, $sWhere) );

              if ( $oDaoTurma->numrows != 0 ) {

                db_fieldsmemory($rsTurma,0);
                echo "<script>".$oGet->funcao_js."('$ed57_c_descr','$ed52_c_descr','$ed29_c_descr','$ed11_c_descr','$ed15_c_nome',false);</script>";
              } else {
                echo "<script>".$oGet->funcao_js."('Chave(".$oGet->pesquisa_chave.") não Encontrado','','','','','','',true);</script>";
              }
            } else {
              echo "<script>".$oGet->funcao_js."('',false);</script>";
            }
          }
          
        ?>
        
    </div>
    
  </body>
</html>

