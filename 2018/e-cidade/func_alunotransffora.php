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

//MODULO: educação
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$claluno  = new cl_aluno;
$clrotulo = new rotulocampo;

$claluno->rotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  <body>
    <table class="container">
      <tr>
        <td>
          <form name="form2" method="post" action="" class="form-container" >
            <fieldset>
              <legend>Filtros</legend>
              <table class="subtable">
                <tr>
                  <td nowrap title="<?=$Ted47_i_codigo?>">
                    <?=$Led47_i_codigo?>
                  </td>
                  <td nowrap>
                    <?db_input( "ed47_i_codigo", 10, $Ied47_i_codigo, true, "text", 4, "", "chave_ed47_i_codigo" );?>
                  </td>
                </tr>
                <tr>
                  <td owrap title="<?=$Ted47_v_nome?>">
                    <?=$Led47_v_nome?>
                  </td>
                  <td nowrap>
                    <?db_input( "ed47_v_nome", 40, $Ied47_v_nome, true, "text", 4, "", "chave_ed47_v_nome" );?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
            <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_aluno.hide();">
          </form>
        </td>
      </tr>
    </table>
    <table class="container">
      <tr>
        <td>
         <?php
           $escola = db_getsession("DB_coddepto");
           $sWhere = "";
           
           if ( !isset( $pesquisa_chave ) ) {
             
             $sql  = "SELECT *                                                                                              ";
             $sql .= "  FROM (                                                                                              ";
             $sql .= "         SELECT distinct on (aluno.ed47_i_codigo) aluno.ed47_i_codigo,                                ";
             $sql .= "                aluno.ed47_v_nome,                                                                    ";
             $sql .= "                alunocurso.ed56_c_situacao,                                                           ";
             $sql .= "                serie.ed11_i_codigo as dl_codigo,                                                     ";
             $sql .= "                serie.ed11_c_descr as dl_etapa,                                                       ";
             $sql .= "                case                                                                                  ";
             $sql .= "                     when (    alunocurso.ed56_c_situacao       != ''                                 ";
             $sql .= "                            or trim(alunocurso.ed56_c_situacao) != 'CANDIDATO' )                      "; 
             $sql .= "                     then ( select ed57_c_descr                                                       ";
             $sql .= "                              from matricula                                                          ";
             $sql .= "                                   inner join turma on ed57_i_codigo = ed60_i_turma                   "; 
             $sql .= "                             where ed47_i_codigo = ed60_i_aluno                                       ";
             $sql .= "                             order by ed60_i_codigo desc                                              ";
             $sql .= "                             limit 1 )                                                                ";
             $sql .= "                     else null                                                                        ";
             $sql .= "                 end as dl_turma,                                                                     ";
             $sql .= "                case                                                                                  ";
             $sql .= "                     when alunocurso.ed56_i_codigo is not null                                        ";
             $sql .= "                     then escola.ed18_i_codigo                                                        ";
             $sql .= "                     else null                                                                        ";
             $sql .= "                 end as dl_codigoescola,                                                              ";
             $sql .= "                case                                                                                  ";
             $sql .= "                     when alunocurso.ed56_i_codigo is not null                                        ";
             $sql .= "                     then escola.ed18_c_nome                                                          ";
             $sql .= "                     else null                                                                        ";
             $sql .= "                 end as dl_escola,                                                                    ";
             $sql .= "                cursoedu.ed29_i_codigo as dl_codigocurso,                                             ";
             $sql .= "                cursoedu.ed29_c_descr as dl_curso,                                                    ";
             $sql .= "                calendario.ed52_c_descr as dl_calendario                                              ";
             $sql .= "           FROM aluno                                                                                 ";
             $sql .= "                left join alunocurso  on alunocurso.ed56_i_aluno       = aluno.ed47_i_codigo          ";
             $sql .= "                left join escola      on escola.ed18_i_codigo          = alunocurso.ed56_i_escola     ";
             $sql .= "                left join calendario  on calendario.ed52_i_codigo      = alunocurso.ed56_i_calendario ";
             $sql .= "                left join base        on base.ed31_i_codigo            = alunocurso.ed56_i_base       ";
             $sql .= "                left join cursoedu    on cursoedu.ed29_i_codigo        = base.ed31_i_curso            ";
             $sql .= "                left join alunopossib on alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo     ";
             $sql .= "                left join serie       on serie.ed11_i_codigo           = alunopossib.ed79_i_serie     ";
             $sql .= "                left join matricula   on matricula.ed60_i_aluno        = aluno.ed47_i_codigo          ";
             
             $sWhere  = " WHERE ed56_i_escola    = {$escola}          ";
             $sWhere .= "   AND ed56_c_situacao != 'TRANSFERIDO REDE' ";
             $sWhere .= "   AND ed56_c_situacao != 'TRANSFERIDO FORA' ";
             $sWhere .= "   AND ed56_c_situacao != 'CANDIDATO'        ";
             $sWhere .= "   AND ed56_c_situacao != 'FALECIDO'         ";
             $sWhere .= "   AND ed56_c_situacao != 'ENCERRADO'        ";
             $sWhere .= "   AND ed60_c_situacao  = 'MATRICULADO'      ";
             $sWhere .= "   AND ed60_c_concluida = 'N'                ";
             $sWhere .= "   AND ed60_c_ativa     = 'S'                ";
             
             $repassa = array();
             
             if ( isset( $chave_ed47_i_codigo ) ) {
               $repassa = array( "chave_ed47_i_codigo" => $chave_ed47_i_codigo, "chave_ed47_v_nome" => $chave_ed47_v_nome );
             }
             
             if ( isset( $chave_ed47_i_codigo ) && ( trim( $chave_ed47_i_codigo ) != "" ) ) {
               $sWhere .= " AND ed47_i_codigo = {$chave_ed47_i_codigo}";
             } else if ( isset( $chave_ed47_v_nome ) && ( trim( $chave_ed47_v_nome ) != "" ) ) {
               $sWhere .= " AND ed47_v_nome like '{$chave_ed47_v_nome}%'";
             }
             
             $sWhere .= ") as x ORDER BY ed47_v_nome";
             $sql    .= $sWhere;
             
             db_lovrot( @$sql, 12, "()", "", $funcao_js, "", "NoMe", $repassa );
           } else {

             if ( $pesquisa_chave != null && $pesquisa_chave != "" ) {

               $result = $claluno->sql_record( $claluno->sql_query_pesq( "", "*", "", $where." AND ed47_i_codigo = {$pesquisa_chave}" ) );
               
               if ( $claluno->numrows != 0 ) {

                 db_fieldsmemory( $result, 0 );
                 echo "<script>".$funcao_js."('$ed47_i_codigo',false);</script>";
               } else {
                 echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
js_tabulacaoforms("form2","chave_ed47_v_nome",true,1,"chave_ed47_v_nome",true);
$('chave_ed47_i_codigo').className = 'field-size2';
$('chave_ed47_v_nome').className   = 'field-size7';
</script>