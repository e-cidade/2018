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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cancdebitos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcancdebitos = new cl_cancdebitos;
$clcancdebitos->rotulo->label("k20_codigo");
$clcancdebitos->rotulo->label("k20_data");
$clcancdebitos->rotulo->label("k20_descr");
$instit = db_getsession("DB_instit");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
             <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk20_codigo?>">
              <?=$Lk20_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                db_input("k20_codigo",10,$Ik20_codigo,true,"text",4,"","chave_k20_codigo");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk20_data?>">
              <?=$Lk20_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_inputdata("k20_data",null,null,null, true, 'text',1,"","chave_k20_data" );
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Origem do débito">
              <b>Origem:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?db_input("origem",10,1,true,"text",4,"","chave_origem");?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk20_descr?>">
              <?=$Lk20_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?db_input("k20_descr",30,$Ik20_descr,true,"text",4,"","chave_k20_descr");?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cancdebitos.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      if(!isset($pesquisa_chave)){

        $sql = "";

        if (isset($pesquisar)) {

          $sql = "select distinct
                         k20_codigo,
                         to_char(k20_data,'dd/mm/yyyy') as k20_data,
                         k20_descr,
                         nome,
                         case
                           when arreinscr.k00_inscr is null and arrematric.k00_matric is null
                             then 'CGM: '||cgm.z01_numcgm
                           when arreinscr.k00_inscr is null
                             then 'M: '||arrematric.k00_matric
                           when arrematric.k00_matric is null
                             then 'I: '||arreinscr.k00_inscr
                           else
                             'CGM: '||cgm.z01_numcgm
                         end as origem
                   from cancdebitos
                        inner join cancdebitosreg on cancdebitosreg.k21_codigo = cancdebitos.k20_codigo
                        inner join arrecad        on arrecad.k00_numpre        = cancdebitosreg.k21_numpre
                                                 and cancdebitosreg.k21_numpar = arrecad.k00_numpar
                                                 and cancdebitosreg.k21_receit = arrecad.k00_receit
                        left join  arreinscr on arrecad.k00_numpre = arreinscr.k00_numpre
                        left join  arrematric on arrecad.k00_numpre = arrematric.k00_numpre
                        inner join cgm            on cgm.z01_numcgm            = arrecad.k00_numcgm
                        inner join db_usuarios on db_usuarios.id_usuario = cancdebitos.k20_usuario";
        if(isset($chave_k20_codigo) && (trim($chave_k20_codigo)!="") ){
          $sql .= " where k20_codigo = $chave_k20_codigo and k20_instit = $instit";
        }else if(isset($chave_k20_data) && (trim($chave_k20_data)!="") ){
          $chave_k20_data = "$chave_k20_data_ano-$chave_k20_data_mes-$chave_k20_data_dia";
          $sql .= " where k20_data = '$chave_k20_data%'::date and k20_instit = $instit";
        }else if(isset($chave_k20_descr) && (trim($chave_k20_descr)!="") ){
          $sql .= " where k20_descr = '$chave_k20_descr' and k20_instit = $instit";
        }else if(isset($chave_origem) && (trim($chave_origem)!="") ){
          $sql .= " where (arrematric.k00_matric like '%$chave_origem%'
                     or    arreinscr.k00_inscr   like '%$chave_origem%'
                     or    cgm.z01_numcgm        like '%$chave_origem%')
                    and k20_instit = $instit";
        }else{
          $sql .= " where k20_instit = $instit";
        }

        $sql .= " order by k20_codigo, k20_data";
      }

      db_lovrot($sql,15,"()","",$funcao_js);

      /**
       * Inserimos o value para que este nao seja o value retornado do Dicionario de Dados
       */
      echo "<script>document.getElementsByName('origem')[0].value = 'Origem';</script>";

    }else{

      if($pesquisa_chave!=null && $pesquisa_chave!=""){
        $result = $clcancdebitos->sql_record($clcancdebitos->sql_query("","",""," k20_codigo = $pesquisa_chave and k20_instit = $instit"));
        if($clcancdebitos->numrows!=0){

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$k20_data',false);</script>";
        }else{
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{

          echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
   </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>