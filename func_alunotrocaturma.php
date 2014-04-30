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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_aluno_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$iEscola   = db_getsession("DB_coddepto");
$oDaoAluno = db_utils::getDao("aluno");
$oDaoAluno->rotulo->label("ed47_i_codigo");
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed57_c_descr");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?
      db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js");
    ?> 
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
      <tr>
        <td height="63" align="center" valign="top">
          <table width="35%" border="0" align="center" cellspacing="0">
            <form name="form2" method="post" action="" >
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted47_i_codigo?>">
                  <?=$Led47_i_codigo?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input("ed47_i_codigo", 10, $Ied47_i_codigo, true,"text", 4, "", "chave_ed47_i_codigo");?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted47_v_nome?>">
                  <?=$Led47_v_nome?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input("ed47_v_nome",50,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted57_c_descr?>">
                  <?=$Led57_c_descr?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?
                    $aTurmas            = array();
                    $aTurmas[]          = '';
                    $sCamposTurmaAluno  = "distinct ed60_i_turma, ed57_c_descr";
                    $sWhereTurmaAluno   = "ed60_c_situacao = 'MATRICULADO' AND ed59_c_encerrada = 'N' AND ed57_i_escola = {$iEscola}  \n";
                    $sWhereTurmaAluno  .= " AND exists (select 1 from matricula mat                                                   \n";
                    $sWhereTurmaAluno  .= "                           inner join turma tur on tur.ed57_i_codigo = mat.ed60_i_turma    \n";
                    $sWhereTurmaAluno  .= "                           inner join regencia reg on reg.ed59_i_turma = tur.ed57_i_codigo \n";
                    $sWhereTurmaAluno  .= "                     where mat.ed60_matricula = matricula.ed60_matricula                   \n";
                    $sWhereTurmaAluno  .= "                       AND mat.ed60_c_situacao = 'TROCA DE TURMA'                          \n";
                    $sWhereTurmaAluno  .= "                       AND mat.ed60_i_codigo <> matricula.ed60_i_codigo                    \n";
                    $sWhereTurmaAluno  .= "                       AND reg.ed59_c_encerrada = 'N'                                      \n";
                    $sWhereTurmaAluno  .= "                       AND tur.ed57_i_escola = {$iEscola})                                 \n";
                    $sSqlTurmaAluno     = $oDaoAluno->sql_query_alunotrocaturma(null, $sCamposTurmaAluno, null, $sWhereTurmaAluno);
                    $rsTurmaAluno       = $oDaoAluno->sql_record($sSqlTurmaAluno);
                    $iLinhasTurmaAluno  = $oDaoAluno->numrows;
                    
                    if ($iLinhasTurmaAluno > 0) {
                      
                      for ($iContador = 0; $iContador < $iLinhasTurmaAluno; $iContador ++) {
                        
                        $oDadosTurma = db_utils::fieldsMemory($rsTurmaAluno, $iContador);
                        $aTurmas[$oDadosTurma->ed60_i_turma] = $oDadosTurma->ed57_c_descr;
                      }
                    }
                    db_select('iTurma', $aTurmas, 'iTurma', 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_alunotrocaturma.hide();">
                </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?
          $sWhere   = "ed60_c_situacao = 'MATRICULADO' AND ed59_c_encerrada = 'N' AND ed57_i_escola = {$iEscola}  \n";
          $sWhere  .= " AND exists (select 1 from matricula mat                                                   \n";
          $sWhere  .= "                           inner join turma tur on tur.ed57_i_codigo = mat.ed60_i_turma    \n";
          $sWhere  .= "                           inner join regencia reg on reg.ed59_i_turma = tur.ed57_i_codigo \n";
          $sWhere  .= "                     where mat.ed60_matricula = matricula.ed60_matricula                   \n";
          $sWhere  .= "                       AND mat.ed60_c_situacao = 'TROCA DE TURMA'                          \n";
          $sWhere  .= "                       AND mat.ed60_i_codigo <> matricula.ed60_i_codigo                    \n";
          $sWhere  .= "                       AND reg.ed59_c_encerrada = 'N'                                      \n";
          $sWhere  .= "                       AND tur.ed57_i_escola = {$iEscola})                                 \n";
          $sCampos  = "distinct ed47_i_codigo, ed47_v_nome, ed57_i_codigo, ed57_c_descr, ed60_i_codigo, ed221_i_serie";
          
          if (!isset($pesquisa_chave)) {
          
            if (isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo) != "") ) {
          
              $sWhere .= " AND ed47_i_codigo = $chave_ed47_i_codigo";
              $sSql    = $oDaoAluno->sql_query_alunotrocaturma("", $sCampos, "ed47_i_codigo", $sWhere);
            } else if (isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome) != "") ) {
          
              $sWhere .= " AND ed47_v_nome like '$chave_ed47_v_nome%'";
              $sSql    = $oDaoAluno->sql_query_alunotrocaturma("", $sCampos, "ed47_i_codigo", $sWhere);
            } else if (isset($iTurma) && $iTurma != '') {

              $sWhere .= " AND ed60_i_turma = {$iTurma}";
              $sSql    = $oDaoAluno->sql_query_alunotrocaturma("", $sCampos, "ed47_i_codigo", $sWhere);
            } else {
              $sSql    = $oDaoAluno->sql_query_alunotrocaturma("", $sCampos, "ed47_i_codigo", $sWhere);
            }
            
            $repassa = array();
            if (isset($chave_ed47_i_codigo)) {
              $repassa = array("chave_ed47_i_codigo" => $chave_ed47_i_codigo, "chave_ed47_v_nome" => $chave_ed47_v_nome);
            }
            
            db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
          } else {
          
            if ($pesquisa_chave != null && $pesquisa_chave != "") {
            
              $sWhere  .= " AND ed60_i_aluno = $pesquisa_chave";
              $sSql     = $oDaoAluno->sql_query_alunomatricula("", $sCampos, "ed47_i_codigo", $sWhere);
              $rsResult = $oDaoAluno->sql_record($sSql);
              
              if ($oDaoAluno->numrows != 0) {
            
                db_fieldsmemory($rsResult, 0);
                echo "<script>".$funcao_js."('$ed47_v_nome', '$ed47_i_codigo', '$ed60_matricula', '$ed57_i_codigo', '$ed221_i_serie', false);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
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
js_tabulacaoforms("form2", "chave_ed47_i_codigo", true, 1, "chave_ed47_i_codigo", true);
</script>