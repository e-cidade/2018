<?
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

//MODULO: educação
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_matricula_classe.php");
require_once ("classes/db_aluno_classe.php");
require_once ("classes/db_calendario_classe.php");
require_once ("classes/db_turmaac_classe.php");

$todas_escolas = 'n';
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clmatricula  = new cl_matricula;
$oDaoAluno    = new cl_aluno;
$clcalendario = new cl_calendario;
$clturmaac    = new cl_turmaac();
$clrotulo     = new rotulocampo;

$clmatricula->rotulo->label("ed60_i_codigo");
$clmatricula->rotulo->label("ed60_matricula");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed269_aluno");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted269_aluno?>">
      <?=$Led269_aluno?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed47_i_codigo",10,$Ied47_i_codigo,true,"text",4,"","chave_ed47_i_codigo");?>
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
      <td nowrap align="right" title=""><b>Trazer alunos de todas as escolas:</b></td>
      <td nowrap>
      <?
       $opcoes_todas_escolas=array("n"=>"Não","s"=>"Sim");
       db_select("todas_escolas",$opcoes_todas_escolas,true,4);?>
      </td>
    </tr>

    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matricula.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?

   /**
    * Buscamos o codigo do turno a qual foi vinculada a turma de atividade complementar
    */
   $iTurno      = null;
   $sSqlTurmaAc = $clturmaac->sql_query_file(null, "ed268_i_turno", null, "ed268_i_codigo = {$codigo_turma}");
   $rsTurmaAc   = $clturmaac->sql_record($sSqlTurmaAc);

   if ($clturmaac->numrows > 0) {
     $iTurno = db_utils::fieldsMemory($rsTurmaAc, 0)->ed268_i_turno;
   }

   $sCampos  = "aluno.ed47_i_codigo, ";
   $sCampos .= "aluno.ed47_v_nome, ";
   $sCampos .= "alunocurso.ed56_c_situacao, ";
   $sCampos .= "serie.ed11_c_descr as dl_serie, ";
   $sCampos .= "case ";
   $sCampos .= " when (alunocurso.ed56_c_situacao != '' ";
   $sCampos .= "       or trim(alunocurso.ed56_c_situacao) != 'CANDIDATO') then ";
   $sCampos .= "     (select ed57_c_descr ";
   $sCampos .= "        from matricula";
   $sCampos .= "             inner join turma on ed57_i_codigo = ed60_i_turma" ;
   $sCampos .= "       where ed47_i_codigo = ed60_i_aluno ";
   $sCampos .= "       order by ed60_i_codigo desc limit 1)  ";
   $sCampos .= " else null ";
   $sCampos .= "end as dl_turma, ";
   $sCampos .= "case when alunocurso.ed56_i_codigo is not null ";
   $sCampos .= " then ";
   $sCampos .= "  case when alunocurso.ed56_c_situacao = 'TRANSFERIDO REDE' ";
   $sCampos .= "   then ";
   $sCampos .= "    (select ed18_c_nome ";
   $sCampos .= "     from transfescolarede ";
   $sCampos .= "      inner join matricula on ed60_i_codigo = ed103_i_matricula ";
   $sCampos .= "      inner join turma on ed57_i_codigo = ed60_i_turma ";
   $sCampos .= "      inner join escola on ed18_i_codigo = ed57_i_escola ";
   $sCampos .= "     where ed60_i_aluno = ed56_i_aluno ";
   $sCampos .= "     and ed57_i_base = ed56_i_base ";
   $sCampos .= "     and ed57_i_calendario = ed56_i_calendario ";
   $sCampos .= "     order by ed103_d_data desc limit 1) ";
   $sCampos .= "   else ";
   $sCampos .= "    escola.ed18_c_nome ";
   $sCampos .= "   end ";
   $sCampos .= " else null ";
   $sCampos .= "end as dl_escola, ";
   $sCampos .= "cursoedu.ed29_c_descr as dl_curso, ";
   $sCampos .= "calendario.ed52_c_descr as dl_calendario ";
   $sWhere = "";
   if ($ed268_i_tipoatend == 5) {
     $sWhere = " exists(select * from alunonecessidade where ed214_i_aluno = ed47_i_codigo)";
   }
   if (isset($codigo_turma) && !empty($codigo_turma)) {

     if (trim($sWhere) != "") {
        $sWhere .= " and ";
     }
     $sWhere .= " not exists (select 1 ";
     $sWhere .= "               from turmaacmatricula ";
     $sWhere .= "              where ed269_aluno = aluno.ed47_i_codigo";
     $sWhere .= "                and ed269_i_turmaac = {$codigo_turma})";

    }

    $oTurno = TurnoRepository::getTurnoByCodigo($iTurno);
    $iReferencias = implode(', ', $oTurno->getTurnoReferente());

    /**
     * Verificamos se existe algum aluno com matricula, onde o turno da turma eh o mesmo turno da turma de atividade
     * complementar. Caso exista, nao apresentamos para vinculo
     */
    $sWhere .= " AND not exists (select 1 ";
    $sWhere .= "                 from matricula mat ";
    $sWhere .= "                      inner join turma tur on tur.ed57_i_codigo = mat.ed60_i_turma";
    $sWhere .= "                      inner join calendario cal on tur.ed57_i_calendario = cal.ed52_i_codigo";
    $sWhere .= "                      inner join turnoreferente on turnoreferente.ed231_i_turno = tur.ed57_i_turno";
    $sWhere .= "                where mat.ed60_i_aluno = aluno.ed47_i_codigo ";
    $sWhere .= "                  AND turnoreferente.ed231_i_referencia in ({$iReferencias})";
    $sWhere .= "                  AND cal.ed52_i_ano      = calendario.ed52_i_ano";
    $sWhere .= "                  AND mat.ed60_c_situacao = 'MATRICULADO' ";
    $sWhere .= "                  AND mat.ed60_c_ativa    = 'S')";

    if (!isset($pesquisa_chave)) {

      if (isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo) != "")) {

        if (trim($sWhere) != "") {
         $sWhere .= " and ";
        }
        $sWhere  .= " ed47_i_codigo = {$chave_ed47_i_codigo} ";
      }
      if (isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ){

        if (trim($sWhere) != "") {
          $sWhere .= " and ";
        }
        $sWhere .= " ed47_v_nome like '{$chave_ed47_v_nome}%'";
      }

      if (isset($todas_escolas) && $todas_escolas == "n") {

        if (trim($sWhere) != "") {
          $sWhere .= " and ";
        }
        $sWhere .= " escola.ed18_i_codigo = ".db_getsession("DB_coddepto");
      }

      $sSql = $oDaoAluno->sql_query_aluno_curso(null, $sCampos, "ed47_v_nome, ed47_i_codigo", $sWhere);

      $repassa = array();
      if (isset($chave_ed47_i_codigo)){
        $repassa = array("chave_ed47_i_codigo" => $chave_ed47_i_codigo, "chave_ed47_v_nome"=>$chave_ed47_v_nome);
      }

      db_lovrot($sSql,15,"()","", $funcao_js,"","NoMe",$repassa, false);
    } else {

      if ($pesquisa_chave!=null && $pesquisa_chave!="") {

        $sWhere .= " and ed47_i_codigo = {$pesquisa_chave} ";
        $sSql    = $oDaoAluno->sql_query_aluno_curso(null, $sCampos, null, $sWhere);
        $result  = $oDaoAluno->sql_record($sSql);

        if ($oDaoAluno->numrows != 0) {

           db_fieldsmemory($result,0);
           echo "<script>".$funcao_js."('$ed47_v_nome','$ed47_i_codigo','',false);</script>";
        } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','',true);</script>";
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
js_tabulacaoforms("form2","chave_ed60_i_codigo",true,1,"chave_ed60_i_codigo",true);
</script>