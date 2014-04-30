<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oGet     = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("situacao");

$aRepassa  = array();
$oDaoSerie = new cl_serie();
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="Expires" CONTENT="0" />
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC >
  <div class='container' >
  <form name="form2" method="post" action="" >
    <table >
      <tr>
        <td align="right" nowrap title="<?=$Ted47_i_codigo?>">
          <?=$Led47_i_codigo?>
        </td>
        <td align="left" nowrap title="<?=$Ted47_v_nome?>">
          <?db_input("ed47_i_codigo",10,$Ied47_i_codigo,true,"text",4,"","chave_ed47_i_codigo");?>
          <?=$Led47_v_nome?>
          <?db_input("ed47_v_nome",40,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="<?=$Ted223_i_serie?>">
          <?=$Led223_i_serie?>
        </td>
        <td align="left" nowrap>
          <?
          $sCampoSerie = "ed11_i_codigo,ed11_c_descr||' - '||ed10_c_descr as serie,ed11_i_ensino,ed11_i_sequencia";
          $sSqlSerie   = $oDaoSerie->sql_query_equiv("", $sCampoSerie, " ed11_i_ensino,ed11_i_sequencia");
          $rsSerie     = $oDaoSerie ->sql_record($sSqlSerie);
          $iLinhas     = $oDaoSerie->numrows;
          
          $aDadosSerie = array('' => '');
          for ($i = 0; $i < $iLinhas; $i++) {
             
            $oDadosSerie   = db_utils::fieldsMemory($rsSerie, $i);
            $aDadosSerie[$oDadosSerie->ed11_i_codigo] = $oDadosSerie->serie;
          }
          db_select('chave_ed223_i_serie', $aDadosSerie, true,1,"onFocus=\"nextfield='pesquisar2'\" style='width:100%'", "");
          ?>
        </td>
      </tr>
    </table>
    <br />
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onFocus="nextfield='done'">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_dadosaluno.hide();">
    </form>
  </div>
  <div class='container'>
    <br />
    <?php 
    
    if ( !isset($oGet->pesquisa_chave) ) {
       
      $sSql  = " SELECT *                                                                                                   ";
      $sSql .= "   FROM (                                                                                                   ";
      $sSql .= "         SELECT distinct on (aluno.ed47_i_codigo) aluno.ed47_i_codigo,                                      ";
      $sSql .= "                aluno.ed47_v_nome,                                                                          ";
      $sSql .= "                alunocurso.ed56_c_situacao,                                                                 ";
      $sSql .= "                serie.ed11_c_descr as dl_serie,                                                             ";
      $sSql .= "                case                                                                                        ";
      $sSql .= "                  when (alunocurso.ed56_c_situacao != '' or trim(alunocurso.ed56_c_situacao) != 'CANDIDATO')";
      $sSql .= "                    then (select ed57_c_descr                                                               ";
      $sSql .= "                            from matricula                                                                  ";
      $sSql .= "                           inner join turma on ed57_i_codigo = ed60_i_turma                                 ";
      $sSql .= "                           where ed47_i_codigo = ed60_i_aluno                                               ";
      $sSql .= "                           order by ed60_i_codigo desc limit 1)                                             ";
      $sSql .= "                  else null                                                                                 ";
      $sSql .= "                end as dl_turma,                                                                            ";
      $sSql .= "                case when alunocurso.ed56_i_codigo is not null                                              ";
      $sSql .= "                  then                                                                                      ";
      $sSql .= "                    case                                                                                    ";
      $sSql .= "                      when alunocurso.ed56_c_situacao = 'TRANSFERIDO REDE'                                  ";
      $sSql .= "                        then (select ed18_c_nome                                                            ";
      $sSql .= "                                from transfescolarede                                                       ";
      $sSql .= "                                inner join matricula on ed60_i_codigo = ed103_i_matricula                   ";
      $sSql .= "                                inner join turma     on ed57_i_codigo = ed60_i_turma                        ";
      $sSql .= "                                inner join escola    on ed18_i_codigo = ed57_i_escola                       ";
      $sSql .= "                               where ed60_i_aluno      = ed56_i_aluno                                       ";
      $sSql .= "                                 and ed57_i_base       = ed56_i_base                                        ";
      $sSql .= "                                 and ed57_i_calendario = ed56_i_calendario                                  ";
      $sSql .= "                               order by ed103_d_data desc limit 1)                                          ";
      $sSql .= "                        else escola.ed18_c_nome                                                             ";
      $sSql .= "                      end                                                                                   ";
      $sSql .= "                  else null                                                                                 ";
      $sSql .= "                end as dl_escola,                                                                           ";
      $sSql .= "                cursoedu.ed29_c_descr as dl_curso,                                                          ";
      $sSql .= "                calendario.ed52_c_descr as dl_calendario                                                    ";
      $sSql .= "         FROM aluno                                                                                         ";
      $sSql .= "          left join alunocurso  on alunocurso.ed56_i_aluno        = aluno.ed47_i_codigo                     ";
      $sSql .= "          left join escola      on escola.ed18_i_codigo           = alunocurso.ed56_i_escola                ";
      $sSql .= "          left join calendario  on  calendario.ed52_i_codigo      = alunocurso.ed56_i_calendario            ";
      $sSql .= "          left join base        on  base.ed31_i_codigo            = alunocurso.ed56_i_base                  ";
      $sSql .= "          left join cursoedu    on  cursoedu.ed29_i_codigo        = base.ed31_i_curso                       ";
      $sSql .= "          left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo                ";
      $sSql .= "          left join serie       on  serie.ed11_i_codigo           = alunopossib.ed79_i_serie                ";
    
      if (isset($chave_ed47_i_codigo)) {
    
        $aRepassa = array("chave_ed47_i_codigo"=>$chave_ed47_i_codigo,
            "chave_ed47_v_nome"=>$chave_ed47_v_nome,
            "chave_ed223_i_serie"=>$chave_ed223_i_serie,
            "situacao"=>$situacao);
      }
      
      $aWhere = array();
      if (isset($situacao) && (trim($situacao) != "")) {

        $aWhere[] = " trim(ed56_c_situacao) = '{$oGet->situacao}' ";
      }
    
    	$sFiltraEscola = " ed18_i_codigo = " . db_getsession("DB_coddepto");
      if (isset($iEscola) && trim($iEscola) != '') {
        $sFiltraEscola = " ed18_i_codigo = {$iEscola} ";
      }
      $aWhere[] = $sFiltraEscola;
      $sOrder   = " ";
      if (isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
        $aWhere[] = "ed47_i_codigo = {$chave_ed47_i_codigo} ";
      } 
      if (isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ){
    
        $aWhere[] = " to_ascii(ed47_v_nome) like '".TiraAcento($chave_ed47_v_nome)."%' ";
      } 
      if (isset($chave_ed223_i_serie) && (trim($chave_ed223_i_serie)!="") ){
        $aWhere[] = " ed79_i_serie = {$chave_ed223_i_serie} ";
      }

      $sWhere  = "where " . implode(" and ", $aWhere) . ") as x ";
      $sSql   .= $sWhere;
      $sSql   .= " ORDER BY to_ascii(ed47_v_nome) ";
      db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa);
    
    } elseif ( isset($oGet->pesquisa_chave) && !empty($oGet->pesquisa_chave) ) {
      
      $sWhere    = "ed47_i_codigo = $oGet->pesquisa_chave";
      $oDaoAluno = new cl_aluno();
      $rsAluno   = $oDaoAluno->sql_record($oDaoAluno->sql_query_file("", "*", "", $sWhere));
      if( $oDaoAluno->numrows !=0 ) {

        db_fieldsmemory($rsAluno,0);
        echo "<script>".$funcao_js."('$ed47_i_codigo', '$ed47_v_nome', false);</script>";
      }else{
        echo "<script>".$funcao_js."('', 'Chave (".$pesquisa_chave.") não Encontrado', true);</script>";
      }
    } 
    ?>    
  </div>
</body>
</html>