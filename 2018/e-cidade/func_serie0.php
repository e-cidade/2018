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
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_serie_classe.php");
include("classes/db_ensino_classe.php");
include("classes/db_regimemat_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clserie = new cl_serie;
$clensino = new cl_ensino;
$clregimemat = new cl_regimemat;
$clrotulo = new rotulocampo;
$clrotulo->label("ed11_i_ensino");
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
   <table width="55%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted11_i_ensino?>">
      <?=$Led11_i_ensino?>
     </td>
     <td width="96%" align="left" nowrap>
      <?
      $result0 = $clensino->sql_record($clensino->sql_query("","ed10_i_codigo,ed10_c_descr","ed10_c_abrev",""));
      ?>
      <select name="chave_ed11_i_ensino" id="chave_ed11_i_ensino">
       <option value="">TODOS</option>
       <?
       for($x=0;$x<$clensino->numrows;$x++){
        db_fieldsmemory($result0,$x);
        ?>
        <option value="<?=$ed10_i_codigo?>" <?=$ed10_i_codigo==@$chave_ed11_i_ensino?"selected":""?> > <?=$ed10_c_descr?></option>
        <?
       }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_serie.hide();">
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
    $condicao = "";
    if(isset($chave_ed11_i_ensino) && (trim($chave_ed11_i_ensino)!="") ){
     $condicao .= " AND ed11_i_ensino = $chave_ed11_i_ensino";
    }
    $sql = "SELECT distinct ed11_i_codigo,
                   ed11_c_descr,
                   ed11_c_abrev,
                   ed10_c_descr,
                   ed10_c_abrev,
                   (select array(select case when ed219_c_nome != '' then ed218_c_nome||' / '||ed219_c_nome else ed218_c_nome end
                                 from serieregimemat
                                  inner join regimemat on ed218_i_codigo = ed223_i_regimemat
                                  left join regimematdiv on ed219_i_codigo = ed223_i_regimematdiv
                                  where ed223_i_serie = ed11_i_codigo)
                   ) as ed223_i_regimemat,
                   ed11_i_sequencia,
                   ed11_i_ensino
            FROM serie
             left join serieregimemat on ed223_i_serie = ed11_i_codigo
             inner join ensino on ed10_i_codigo = ed11_i_ensino
            WHERE ed11_i_codigo > 0
            $condicao
            ORDER BY ed11_i_ensino,ed11_i_sequencia
           ";
    $repassa = array();
    if(isset($chave_ed11_i_ensino)){
     $repassa = array("chave_ed11_i_ensino"=>$chave_ed11_i_ensino);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }
  ?>
  </td>
 </tr>
</table>
</body>
</html>