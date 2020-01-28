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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_placaixa_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clplacaixa = new cl_placaixa;
$clplacaixa->rotulo->label("k80_codpla");
$clplacaixa->rotulo->label("k80_data");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container">
  <form name="form2" method="post" action="" >
    <fieldset>
      <legend class="bold">Filtros</legend>
      <table>
        <tr>
          <td  align="center" nowrap title="<?=$Tk80_codpla?>" colspan="2"><label for="chave_k80_codpla"><b>Planilha:</b></label></td>
          <td  align="center" nowrap colspan="2"><?php  db_input("k80_codpla",6,$Ik80_codpla,true,"text",4,"","chave_k80_codpla"); ?></td>
        </tr>
      </table>
    </fieldset>
    <p>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button"  id="limpar" value="Limpar" onclick="$('chave_k80_codpla').value = ''">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_placaixa.hide();">
    </p>
  </form>

  <fieldset>
    <legend><b>Resultados da Pesquisa</b></legend>
    <?php
    $where  = "";
    $iAnoSessao = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");


    if (isset($campos) == false) {

      $campos = "  placaixa.* ";
      if (file_exists("funcoes/db_func_placaixa.php") == true) {
        include(modification("funcoes/db_func_placaixa.php"));
      }
    }

    if(!isset($pesquisa_chave)){

      $sWhere = " and k80_dtaut is null ";

      /** [Extensao FiltroDespesa] Modificacao 1*/

      if(isset($chave_k80_codpla) && (trim($chave_k80_codpla)!="") ){
        $where .= "and k80_codpla = $chave_k80_codpla";
      }

      $sql = "
      select k80_codpla,
             max(k80_data) as k80_data,
             sum(k81_valor) as k81_valor,
             max(k80_dtaut) AS dl_Data_Autenticacao,
             max(k12_data) AS dl_Data_Estorno
        from (SELECT k80_codpla,
                     k80_data,
                     k80_dtaut,
                     case when k12_estorn is not null and k12_estorn is true then k81_valor
                       when k12_estorn is null then k81_valor
                     end as k81_valor,
                     k12_data
                FROM placaixa 
                     INNER JOIN db_config ON db_config.codigo = placaixa.k80_instit
                     LEFT JOIN placaixarec a ON k81_codpla = k80_codpla
                     LEFT JOIN corplacaixa ON k82_seqpla = k81_seqpla
                     LEFT JOIN corrente ON k82_id = k12_id
                                       AND k82_data = k12_data
                                       AND k82_autent = k12_autent
               WHERE k80_instit =  {$iInstituicaoSessao}
                 AND extract(YEAR FROM k80_data) = {$iAnoSessao}
                 and k80_dtaut is null
                     {$where} ) as x
               group by k80_codpla
       ORDER BY k80_codpla DESC";


      $repassa = array();
      if (isset($chave_k80_codpla)) {
        $repassa = array("chave_k80_codpla" => $chave_k80_codpla);
      }

      db_lovrot($sql, 15,"()","",$funcao_js,"","NoMe",$repassa, false);

    } else {

      if($pesquisa_chave!=null && $pesquisa_chave!=""){
        $sql = $clplacaixa->sql_query_rec(null,$campos,"k80_codpla desc"," k80_instit = ".db_getsession("DB_instit")."  and k80_dtaut is null and k80_codpla = $pesquisa_chave");
        $result = $clplacaixa->sql_record($sql);
        if($clplacaixa->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$k80_data',false);</script>";
        }else{
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
  </fieldset>
</div>


<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">

  <tr style="margin-top: 10px;">
    <td align="center" valign="top">

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
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
