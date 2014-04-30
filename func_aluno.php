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

//MODULO: educação
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_jsplibwebseller.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$claluno  = new cl_aluno;
$clserie  = new cl_serie;
$clrotulo = new rotulocampo;

$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed223_i_serie");

$repassa = array();
?>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  </head>
<script>
nextfield = "campo1"; // nome do primeiro campo
netscape  = "";
ver       = navigator.appVersion;
len       = ver.length;

for ( var iln = 0; iln < len; iln++ ) {
  
  if ( ver.charAt( iln ) == "(" ) {
    break;
  }
}

netscape = ( ver.charAt( iln + 1 ).toUpperCase() != "C" );

function keyDown( DnEvents ) {
  
  k = ( netscape ) ? DnEvents.which : window.event.keyCode;
  
  if ( k == 13 ) { // pressiona tecla enter
    
    if ( nextfield == 'done' ) {
      return true; // envia quando termina os campos
    } else {
      
      eval(" document.getElementById('"+nextfield+"').focus()" );
      return false;
    }
  }
}

document.onkeydown = keyDown;
if ( netscape ) {
  document.captureEvents(Event.KEYDOWN|Event.KEYUP);
}
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
     <td height="63" align="center" valign="top">
       <form name="form2" method="post" action="" >
         <table width="55%" border="0" align="center" cellspacing="0">
           <?php
             if ( isset( $iEscola ) && $iEscola != '') {
             
               db_input( 'iEscola', '10',  '', true, 'hidden', 3, '', 'iEscola' );
               db_input( 'sAlunos', '300', '', true, 'hidden', 3, '', 'sAlunos' );
             }
           ?>
           <tr>
             <td width="4%" align="right" nowrap title="<?=$Ted47_i_codigo?>">
               <?=$Led47_i_codigo?>
             </td>
             <td width="96%" align="left" nowrap title="<?=$Ted47_v_nome?>">
               <?php
                 db_input( "ed47_i_codigo", 10, $Ied47_i_codigo, true, "text", 4, "onFocus=\"nextfield='pesquisar2'\"", "chave_ed47_i_codigo" );
                 echo $Led47_v_nome;
                 db_input( "ed47_v_nome", 40, $Ied47_v_nome, true, "text", 4, "onFocus=\"nextfield='pesquisar2'\"", "chave_ed47_v_nome" );
               ?>
             </td>
           </tr>
           <tr>
             <td width="4%" align="right" nowrap title="<?=$Ted223_i_serie?>">
               <?=$Led223_i_serie?>
             </td>
             <td width="96%" align="left" nowrap>
               <?php
                 $sCamposSerie = "ed11_i_codigo,ed11_c_descr||' - '||ed10_c_descr,ed11_i_ensino,ed11_i_sequencia";
                 $sSqlSerie    = $clserie->sql_query_equiv( "", $sCamposSerie, " ed11_i_ensino, ed11_i_sequencia", "" );
                 $result_serie = $clserie->sql_record( $sSqlSerie );
                 
                 if ( $clserie->numrows == 0 ) {

                   $x = array( '' => 'NENHUM REGISTRO' );
                   db_select( 'ed223_i_serie', $x, true, 1, "onFocus=\"nextfield='pesquisar2'\"" );
                 } else {
                   db_selectrecord( "ed223_i_serie", $result_serie, "", "", "onFocus=\"nextfield='pesquisar2'\"", "chave_ed223_i_serie", "", "  ", "", 1) ;
                 }
                 ?>
                 <b>Situação:</b>
                 <?
                 $x = array(
                             ''                 => '',
                             'APROVADO'         => 'APROVADO',
                             'CANCELADO'        => 'CANCELADO',
                             'CANDIDATO'        => 'CANDIDATO',
                             'CONCLUÍDO'        => 'CONCLUÍDO',
                             'EVADIDO'          => 'EVADIDO',
                             'FALECIDO'         => 'FALECIDO',
                             'MATRICULADO'      => 'MATRICULADO',
                             'REPETENTE'        => 'REPETENTE',
                             'TRANSFERIDO FORA' => 'TRANSFERIDO FORA',
                             'TRANSFERIDO REDE' => 'TRANSFERIDO REDE'
                           );
                 db_select( 'situacao', $x, true, 1, "onFocus=\"nextfield='pesquisar2'\"" );
               ?>
             </td>
           </tr>
           <tr>
             <td colspan="2" align="center">
               <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onFocus="nextfield='done'">
               <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
               <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_aluno.hide();">
             </td>
           </tr>
         </table>
       </form>
     </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php
          $escola = db_getsession("DB_coddepto");
          $sWhere = ' WHERE ';
          
          if ( isset( $iEscola ) && trim( $iEscola ) != '' ) {
    	    
          	 $situacao = 'MATRICULADO';
          
             $sSqlExists  = " select matric.ed60_i_aluno                              ";
             $sSqlExists .= "   from matricula as matric                              ";
             $sSqlExists .= "        inner join turma on ed60_i_turma = ed57_i_codigo ";
             $sSqlExists .= "  where ed60_c_situacao     = '{$situacao}'              ";
             $sSqlExists .= "    and ed60_c_ativa        = 'S'                        ";
             $sSqlExists .= "    and ed57_i_escola       = {$iEscola}                 ";
             $sSqlExists .= "    and matric.ed60_i_aluno = aluno.ed47_i_codigo        ";
             $sSqlExists .= "    and matric.ed60_i_aluno not in({$sAlunos})           ";
             
             $sWhere .= " exists ({$sSqlExists}) and ";
          }
          
          $sSqlExistsProgressao = '';
          if ( !empty( $listar_apenas_progressao_parcial ) ) {
         
            $sSqlExistsProgressao .= " exists (select 1 ";
            $sSqlExistsProgressao .= "           from progressaoparcialaluno";
            $sSqlExistsProgressao .= "                inner join situacaoeducacao on ed114_situacaoeducacao = ed109_sequencial";
            $sSqlExistsProgressao .= "          where ed114_aluno = aluno.ed47_i_codigo";
            
            if ( !empty( $listar_situacao_progressao_parcial ) ) {
              $sSqlExistsProgressao .= "   and ed114_situacaoeducacao in({$listar_situacao_progressao_parcial}) ";
            }
         
            if ( !empty( $progressao_parcial_ativa ) ) {
              $sSqlExistsProgressao .= "   and ed109_ativo is {$progressao_parcial_ativa}";
            }
            
            $sSqlExistsProgressao .= ")";
          }
          
          /**
           * Caso tenha sido passado como parâmetro lMatriculaEscola, busca somente alunos que possuam matrícula ativa
           * na escola logada
           */
          $sSqlMatriculaEscola = "";
          if ( isset( $lMatriculaEscola ) ) {
            
            $sSqlMatriculaEscola .= " exists ( select 1 ";
            $sSqlMatriculaEscola .= "            from matricula "; 
            $sSqlMatriculaEscola .= "                 INNER JOIN turma ON turma.ed57_i_codigo = matricula.ed60_i_turma ";
            $sSqlMatriculaEscola .= "           WHERE matricula.ed60_i_aluno = aluno.ed47_i_codigo ";
            $sSqlMatriculaEscola .= "             AND turma.ed57_i_escola        = {$escola} ";
            $sSqlMatriculaEscola .= "             AND matricula.ed60_c_situacao  = 'MATRICULADO' ";
            $sSqlMatriculaEscola .= "             AND matricula.ed60_c_concluida = 'N' ";
            $sSqlMatriculaEscola .= "             AND matricula.ed60_c_ativa     = 'S' ";
            $sSqlMatriculaEscola .= "             AND matricula.ed60_c_tipo      = 'N' )";
          }
          
          if ( !isset( $pesquisa_chave ) && ( !isset( $pesquisa_chave2 ) ) ) {
            
            $sql  = "SELECT * ";
            $sql .= " FROM ( ";
            $sql .= "         SELECT distinct on (aluno.ed47_i_codigo) aluno.ed47_i_codigo, ";
            $sql .= "                aluno.ed47_v_nome, ";
            $sql .= "                alunocurso.ed56_c_situacao, ";
            $sql .= "                serie.ed11_c_descr as dl_serie, ";
            $sql .= "                case ";
            $sql .= "                     when (alunocurso.ed56_c_situacao != '' or trim(alunocurso.ed56_c_situacao) != 'CANDIDATO') "; 
            $sql .= "                     then ";
            $sql .= "                          (select ed57_c_descr "; 
            $sql .= "                             from matricula  ";
            $sql .= "                                  inner join turma on ed57_i_codigo = ed60_i_turma "; 
            $sql .= "                            where ed47_i_codigo = ed60_i_aluno  ";
            $sql .= "                            order by ed60_i_codigo desc limit 1) ";
            $sql .= "                     else null ";
            $sql .= "                 end as dl_turma, ";
            $sql .= "                case  ";
            $sql .= "                     when alunocurso.ed56_i_codigo is not null ";
            $sql .= "                     then ";
            $sql .= "                          case "; 
            $sql .= "                               when alunocurso.ed56_c_situacao = 'TRANSFERIDO REDE' ";
            $sql .= "                               then ";
            $sql .= "                                    (select ed18_c_nome ";
            $sql .= "                                       from transfescolarede ";
            $sql .= "                                            inner join matricula on ed60_i_codigo = ed103_i_matricula ";
            $sql .= "                                            inner join turma     on ed57_i_codigo = ed60_i_turma ";
            $sql .= "                                            inner join escola    on ed18_i_codigo = ed57_i_escola ";
            $sql .= "                                      where ed60_i_aluno      = ed56_i_aluno ";
            $sql .= "                                        and ed57_i_base       = ed56_i_base ";
            $sql .= "                                        and ed57_i_calendario = ed56_i_calendario ";
            $sql .= "                                      order by ed103_d_data desc limit 1) ";
            $sql .= "                               else escola.ed18_c_nome ";
            $sql .= "                           end ";
            $sql .= "                      else null ";
            $sql .= "                  end as dl_escola, ";
            $sql .= "                cursoedu.ed29_c_descr as dl_curso, ";
            $sql .= "                calendario.ed52_c_descr as dl_calendario ";
            $sql .= "           FROM aluno ";
            $sql .= "                left join alunocurso  on alunocurso.ed56_i_aluno        = aluno.ed47_i_codigo ";
            $sql .= "                left join escola      on escola.ed18_i_codigo           = alunocurso.ed56_i_escola ";
            $sql .= "                left join calendario  on  calendario.ed52_i_codigo      = alunocurso.ed56_i_calendario ";
            $sql .= "                left join base        on  base.ed31_i_codigo            = alunocurso.ed56_i_base ";
            $sql .= "                left join cursoedu    on  cursoedu.ed29_i_codigo        = base.ed31_i_curso ";
            $sql .= "                left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo ";
            $sql .= "                left join serie       on  serie.ed11_i_codigo           = alunopossib.ed79_i_serie ";
       
            if ( isset( $lPesquisaTransportePublico ) ) {
            
              $dtCalendario = date('Y',db_getsession('DB_datausu'));
              
              $sWhere .= " trim(ed47_c_transporte) = '{$iTransporte}'      and ";
              $sWhere .= " ed47_i_transpublico     = {$iUtilizaTransporte} and ";
              $sWhere .= " trim(ed56_c_situacao)   = '{$situacao}'         and ";
              $sWhere .= " ed52_i_ano = {$dtCalendario}                    and ";
            }
            
            if ( isset( $chave_ed47_i_codigo ) ) {
            
             $repassa = array("chave_ed47_i_codigo" => $chave_ed47_i_codigo,
                              "chave_ed47_v_nome"   => $chave_ed47_v_nome,
                              "chave_ed223_i_serie" => $chave_ed223_i_serie,
                              "situacao"            => $situacao);
            }
            
            $sFiltraEscola = "";
            if ( isset( $iEscola ) && trim( $iEscola ) != '' ) {
              $sFiltraEscola = "and ed18_i_codigo = {$iEscola} ";
            }
            
            $sWhere .= $sSqlExistsProgressao;
            if ( !empty( $sSqlExistsProgressao ) ) {
               $sWhere .= " and";
            }
            
            $sWhere .= $sSqlMatriculaEscola;
            if ( !empty( $sSqlMatriculaEscola ) ) {
              $sWhere .= " and";
            }
            
            $lBuscaDados = false;
            
            if ( isset( $chave_ed47_i_codigo ) && ( trim( $chave_ed47_i_codigo ) != "" ) ) {
            
              $lBuscaDados  = true;
              $sql         .= " {$sWhere} ed47_i_codigo = $chave_ed47_i_codigo {$sFiltraEscola}) as x ORDER BY to_ascii(ed47_v_nome)";
            } else if ( isset( $chave_ed47_v_nome ) && ( trim( $chave_ed47_v_nome ) != "" ) ) {
            
              $lBuscaDados  = true;
              $sql         .= " {$sWhere} to_ascii(ed47_v_nome) like '".TiraAcento( $chave_ed47_v_nome )."%'";
              $sql         .= " {$sFiltraEscola} ) as x ORDER BY to_ascii(ed47_v_nome)";
            } else if ( isset( $chave_ed223_i_serie ) && ( trim( $chave_ed223_i_serie ) != "" ) ) {
            
              $lBuscaDados  = true;
              $sql         .= " {$sWhere} ed79_i_serie = {$chave_ed223_i_serie} {$sFiltraEscola}) as x ORDER BY to_ascii(ed47_v_nome)";
            } else if ( isset( $situacao ) && ( trim( $situacao ) != "" ) ) {
            
              $lBuscaDados  = true;
              $sql         .= " {$sWhere} trim(ed56_c_situacao) = '{$situacao}' {$sFiltraEscola}) as x ORDER BY to_ascii(ed47_v_nome)";
            }
            
            if ( $lBuscaDados ) {
             db_lovrot( @$sql, 12, "()", "", $funcao_js, "", "NoMe", $repassa );
            }
          } else {
         
            if ( !empty( $sSqlExistsProgressao ) ) {
              $sSqlExistsProgressao = " and {$sSqlExistsProgressao}";
            }
            
            if ( !empty( $sSqlMatriculaEscola ) ) {
              $sSqlMatriculaEscola = " and {$sSqlMatriculaEscola}";
            }
            
            if ( isset( $pesquisa_chave ) && $pesquisa_chave != null && $pesquisa_chave != "" ) {
         
              $sWhere = "ed47_i_codigo = {$pesquisa_chave} {$sSqlExistsProgressao} {$sSqlMatriculaEscola}";
              $result = $claluno->sql_record( $claluno->sql_query_file( "", "*", "", $sWhere ) );
              
              if ( $claluno->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$ed47_i_codigo', '$ed47_v_nome');</script>";
              } else {
                echo "<script>".$funcao_js."(null);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
            }
            
            if ( isset( $pesquisa_chave2 ) && $pesquisa_chave2 != null && $pesquisa_chave2 != "" ) {
            
              if ( !empty( $sSqlExistsProgressao ) ) {
                $sSqlExistsProgressao = " and {$sSqlExistsProgressao}";
              }
              
              if ( !empty( $sSqlMatriculaEscola ) ) {
                $sSqlMatriculaEscola = " and {$sSqlMatriculaEscola}";
              }
              
              $sWhere = "ed47_i_codigo = {$pesquisa_chave2} {$sSqlExistsProgressao} {$sSqlMatriculaEscola}";
              
              if ( isset( $lPesquisaTransportePublico ) ) {
                $sWhere .= " and trim(ed47_c_transporte) = '{$iTransporte}' and ed47_i_transpublico = {$iUtilizaTransporte}";
              }
              
              $result = $claluno->sql_record( $claluno->sql_query_file( "", "*", "", $sWhere ) );
              
              if ( $claluno->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$ed47_v_nome',false, '$ed47_i_codigo');</script>";
              } else {
                echo "<script>".$funcao_js."(null,true);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
            }
          }
        ?>
      </td>
    </tr>
  </table>
</body>
</html>
<script>
js_tabulacaoforms( "form2", "chave_ed47_i_codigo", true, 1, "chave_ed47_i_codigo", true );

<?php 
if ( isset( $iEscola ) && trim( $iEscola ) != '' ) { ?>

  var oOption   = document.getElementById('situacao');
  oOption.value = 'MATRICULADO';
  
  for (var iIndice = 0; iIndice < oOption.options.length; iIndice++) {
  	
  	if (oOption.options[iIndice].value != 'MATRICULADO') {
  		oOption.options[iIndice].disabled = true;
  	}
  }

<?php } ?>

</script>