<?
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

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
$oDaoDiarioAvaliacaoAlternativa  = new cl_diarioavaliacaoalternativa();

if(isset($codnobase)){
  
 $arr_descricao = explode(",",$descrdisciplina);
 $arr_regencia = explode(",",$codnobase);
 
 for ($r = 0; $r < count($arr_regencia); $r++) {
   
   $sSqlRegencia = $clregencia->sql_query("", "ed232_c_descr,ed59_c_encerrada", "", " ed59_i_codigo = $arr_regencia[$r]");
   $result11     = $clregencia->sql_record($sSqlRegencia);
   $sql_exc      = "SELECT DISTINCT ed95_i_codigo as coddiario, ed60_i_codigo, ed60_c_situacao
                      FROM diario
                           inner join aluno     on ed47_i_codigo = ed95_i_aluno
                           inner join matricula on ed60_i_aluno = ed47_i_codigo
                           inner join regencia  on ed59_i_codigo = ed95_i_regencia
                     WHERE ed95_i_regencia = $arr_regencia[$r]
                       AND ed59_i_turma = ed60_i_turma
                       AND ed95_c_encerrado = 'S'
                       AND ed60_c_situacao = 'MATRICULADO'";
   
   $result_exc = db_query($sql_exc);
   $linhas_exc = pg_num_rows($result_exc);
   
   if (pg_result($result11, 0, 0) == "S") {
     
     db_fieldsmemory($result11, 0);
     db_msgbox("Exclusão não permitida! Disciplina $arr_descricao[$r] já foi encerrada para todos alunos nesta turma.");
   } else if ($linhas_exc > 0) {
     db_msgbox("Exclusão não permitida! Existem aluno(s) com avaliações encerradas na disciplina $arr_descricao[$r].");
   } else {
     
     db_inicio_transacao();
     $sql_exc1 = "SELECT DISTINCT ed95_i_codigo as coddiario
                   FROM diario
                  WHERE ed95_i_regencia = $arr_regencia[$r]";
     $result_exc1 = db_query($sql_exc1);
     $linhas_exc1 = pg_num_rows($result_exc1);
     
     if ($linhas_exc1 > 0) {
       
       for ($z = 0; $z < $linhas_exc1; $z++) {
        
         db_fieldsmemory($result_exc1,$z);
         $clamparo->excluir(""," ed81_i_diario = $coddiario");
         if ($clamparo->erro_status == "0") {
           throw new BusinessException($clamparo->erro_msg);
         }
         
         $cldiariofinal->excluir(""," ed74_i_diario = $coddiario");
         if ($cldiariofinal->erro_status == "0") {
           throw new BusinessException($cldiariofinal->erro_msg);
         }
         
         $result5 = db_query("select ed73_i_codigo from diarioresultado where ed73_i_diario = $coddiario");
         $linhas5 = pg_num_rows($result5);
         
         for ($t = 0; $t < $linhas5; $t++) {
           
           db_fieldsmemory($result5,$t);
           $clparecerresult->excluir(""," ed63_i_diarioresultado = $ed73_i_codigo");
           if ($clparecerresult->erro_status == "0") {
             throw new BusinessException($clparecerresult->erro_msg);
           }
         }
         
         $cldiarioresultado->excluir(""," ed73_i_diario = $coddiario");
         if ($cldiarioresultado->erro_status == "0") {
           throw new BusinessException($cldiarioresultado->erro_msg);
         }
         
         $result6 = db_query("select ed72_i_codigo from diarioavaliacao where ed72_i_diario = $coddiario");
         $linhas6 = pg_num_rows($result6);
         
         for ($t = 0; $t < $linhas6; $t++) {
           
           db_fieldsmemory($result6,$t);
           $clpareceraval->excluir(""," ed93_i_diarioavaliacao = $ed72_i_codigo");
           if ($clpareceraval->erro_status == "0") {
             throw new BusinessException($clpareceraval->erro_msg);
           }
           
           $clabonofalta->excluir(""," ed80_i_diarioavaliacao = $ed72_i_codigo");
           if ($clabonofalta->erro_status == "0") {
             throw new BusinessException($clabonofalta->erro_msg);
           }
         }
         $cldiarioavaliacao->excluir(""," ed72_i_diario = $coddiario");
         if ($cldiarioavaliacao->erro_status == "0") {
           throw new BusinessException($cldiarioavaliacao->erro_msg);
         }
         
         $claprovconselho->excluir(""," ed253_i_diario = $coddiario");
         if ($claprovconselho->erro_status == "0") {
           throw new BusinessException($claprovconselho->erro_msg);
         }
         
         $cldiario->excluir(""," ed95_i_codigo = $coddiario");
         if ($cldiario->erro_status == "0") {
           throw new BusinessException($cldiario->erro_msg);
         }
         
         $oDaoDiarioAvaliacaoAlternativa->excluir(null, "ed136_diario = {$coddiario}");
         if ($oDaoDiarioAvaliacaoAlternativa->erro_status == "0") {
           throw new BusinessException($oDaoDiarioAvaliacaoAlternativa->erro_msg);
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
     $sWhereDiarioClasse .= "                            from regenciahorario ";
     $sWhereDiarioClasse .= "                           where ed58_i_regencia = {$arr_regencia[$r]})";
     $sSqlDiarioClasse    = $oDaoDiarioClasseRegenciaHorario->sql_query_file(null, 
                                                                             "*",
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
         if ($oDaoDiarioClasseAlunoFalta->erro_status == "0") {
           throw new BusinessException($oDaoDiarioClasseAlunoFalta->erro_msg);
         }
         
         /**
          * Excluir da diarioclasseregenciahorario
          */        
         $oDaoDiarioClasseRegenciaHorario->excluir($oDadosDiarioClasse->ed302_sequencial);
         if ($oDaoDiarioClasseRegenciaHorario->erro_status == "0") {
           throw new BusinessException($oDaoDiarioClasseRegenciaHorario->erro_msg);
         }
         unset($oDadosDiarioClasse);
       }
       
       $sDiarioClasseExcluir = implode(",", $aDiarioClasseExcluidos);
       $oDaoDiarioClasse->excluir(null, "ed300_sequencial in ({$sDiarioClasseExcluir})");
       if ($oDaoDiarioClasse->erro_status == "0") {
         throw new BusinessException($oDaoDiarioClasse->erro_msg);
       }
     }
     
     $clregenciahorario->excluir(""," ed58_i_regencia = $arr_regencia[$r]");
     if ($clregenciahorario->erro_status == "0") {
       throw new BusinessException($clregenciahorario->erro_msg);
     }
     
     $clregenciaperiodo->excluir(""," ed78_i_regencia = $arr_regencia[$r]");
     if ($clregenciaperiodo->erro_status == "0") {
       throw new BusinessException($clregenciaperiodo->erro_msg);
     }
     
     $clregencia->excluir($arr_regencia[$r]);
     if ($clregencia->erro_status == "0") {
       throw new BusinessException($clregencia->erro_msg);
     }
     
     db_fim_transacao();
   }
 }
 ?>
 <script>
   parent.location.href="edu1_regencia001.php?ed59_i_turma=<?=$ed59_i_turma?>&ed57_c_descr=<?=$ed57_c_descr?>&ed59_i_serie=<?=$ed59_i_serie?>&ed11_c_descr=<?=$ed11_c_descr?>&frequencia=<?=$frequencia?>";
 </script>
 <?
}
?>