<?php
/**
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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_proced_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$oRotulos    = new rotulocampo;
$oRotulos->label("v03_codigo");
$oRotulos->label("v03_descr");
$oRotulos->label("v03_dcomp");

$aTiposProcedencia = array(
  ""   => "Todas",
  "DI" => "Diversos",
  "DA" => "Dívida Ativa"
);

$sCamposBaseProcedencias  = " codigo as sequencial,                              ";
$sCamposBaseProcedencias .= " descricao_abreviada as dl_Descrição_Abrevidada,    ";
$sCamposBaseProcedencias .= " descricao,                                         ";
$sCamposBaseProcedencias .= " receita,                                           ";
$sCamposBaseProcedencias .= " instituicao as dl_Instituição,                     ";
$sCamposBaseProcedencias .= " tipo_debito as dl_Código_do_Tipo_de_Débito,        ";
$sCamposBaseProcedencias .= " case tipo_procedencia when 'DI' then 'Diversos'
                                                    else 'Dívida Ativa'
                              end as dl_Tipo_de_Procedência,                     ";
$sCamposBaseProcedencias .= " tipo_procedencia as db_tipo_procedencia            ";

$sSqlBaseProcedencias  = " select                                                            ";
$sSqlBaseProcedencias .= "   dv09_procdiver as codigo,                                       ";
$sSqlBaseProcedencias .= "   dv09_descra as descricao_abreviada,                             ";
$sSqlBaseProcedencias .= "   dv09_descr as descricao,                                        ";
$sSqlBaseProcedencias .= "   dv09_receit as receita,                                         ";
$sSqlBaseProcedencias .= "   dv09_hist as historico,                                         ";
$sSqlBaseProcedencias .= "   dv09_instit as instituicao,                                     ";
$sSqlBaseProcedencias .= "   k00_tipo as tipo_debito,                                        ";
$sSqlBaseProcedencias .= "   'DI' as tipo_procedencia                                        ";
$sSqlBaseProcedencias .= " from                                                              ";
$sSqlBaseProcedencias .= "   procdiver                                                       ";
$sSqlBaseProcedencias .= " inner join                                                        ";
$sSqlBaseProcedencias .= "   arretipo on arretipo.k00_tipo = procdiver.dv09_tipo             ";
$sSqlBaseProcedencias .= "                                                                   ";
$sSqlBaseProcedencias .= " union all                                                         ";
$sSqlBaseProcedencias .= "                                                                   ";
$sSqlBaseProcedencias .= " select                                                            ";
$sSqlBaseProcedencias .= "   v03_codigo as codigo,                                           ";
$sSqlBaseProcedencias .= "   v03_descr as descricao_abreviada,                               ";
$sSqlBaseProcedencias .= "   v03_dcomp as descricao,                                         ";
$sSqlBaseProcedencias .= "   v03_receit as receita,                                          ";
$sSqlBaseProcedencias .= "   k00_hist as historico,                                          ";
$sSqlBaseProcedencias .= "   v03_instit as instituicao,                                      ";
$sSqlBaseProcedencias .= "   k00_tipo as tipo_debito,                                        ";
$sSqlBaseProcedencias .= "   'DA' as tipo_procedencia                                        ";
$sSqlBaseProcedencias .= " from                                                              ";
$sSqlBaseProcedencias .= "   proced                                                          ";
$sSqlBaseProcedencias .= " inner join                                                        ";
$sSqlBaseProcedencias .= "   procedarretipo on procedarretipo.v06_proced = proced.v03_codigo ";
$sSqlBaseProcedencias .= " inner join                                                        ";
$sSqlBaseProcedencias .= "   arretipo on arretipo.k00_tipo = procedarretipo.v06_arretipo     ";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tv03_codigo?>">
              <?=$Lv03_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("codigo",4,$Iv03_codigo,true,"text",4,"","chave_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tv03_descr?>">
              <?=$Lv03_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("descricao_abreviada",20,$Iv03_descr,true,"text",4,"","chave_descricao_abreviada");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tv03_dcomp?>">
              <?=$Lv03_dcomp?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input("descricao",20,$Iv03_dcomp,true,"text",4,"","chave_descricao");
           ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?='Tipo de Procedência'?>">
              <strong>Procedência:</strong>
            </td>
            <td width="96%" align="left" nowrap>
              <? db_select("tipo_procedencia", $aTiposProcedencia, true, 4, ""); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_proced.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $aWhere   = array();
      $aWhere[] = "instituicao = " . db_getsession("DB_instit");

      if(!empty($sTiposDebitos)) {
        $aWhere[] = "tipo_debito in ({$sTiposDebitos})";
      }

      if(!isset($pesquisa_chave)) {

        if(isset($chave_codigo) && (trim($chave_codigo) != "") ) {
          $aWhere[] = "codigo = {$chave_}";
        }

        if(isset($chave_descricao_abreviada) && (trim($chave_descricao_abreviada) != "") ) {
          $aWhere[] = "descricao_abreviada ilike '{$chave_descricao_abreviada}%'";
        }
        
        if(isset($chave_descricao) && (trim($chave_descricao) != "") ) {
          $aWhere[] = "descricao ilike '{$chave_descricao}%'";
        }
        
        if(isset($tipo_procedencia) && (trim($tipo_procedencia) != "") ) {
          $aWhere[] = "tipo_procedencia = '{$tipo_procedencia}'";
        }

        $sql  = " SELECT {$sCamposBaseProcedencias} FROM ({$sSqlBaseProcedencias}) as procedencias ";

        if(!empty($aWhere)) {
          $sql .= " WHERE ". implode(' AND ', $aWhere);
        }

        $repassa = array();
        
        if(isset($chave_descricao)){
          $repassa["sequencial"] = $chave_codigo;
        }
        
        if(isset($chave_descricao)){
          $repassa["descricao"] = $chave_descricao;
        }
        
        if(isset($chave_descricao)){
          $repassa["tipo_procedencia"] = $tipo_procedencia;
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if($pesquisa_chave != null && $pesquisa_chave != "") {

          $aWhere[] = "codigo = {$pesquisa_chave}";

          $sSql = " SELECT {$sCamposBaseProcedencias} FROM ({$sSqlBaseProcedencias}) as procedencias ";
          
          if(!empty($aWhere)) {
            $sSql .= " WHERE ". implode(' AND ', $aWhere);
          }

          $result = db_query($sSql);

          if(is_resource($result) && pg_num_rows($result) > 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."($sequencial,'{$descricao}','{$db_tipo_procedencia}',false);</script>";
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
js_tabulacaoforms("form2","chave_descricao",true,1,"chave_descricao",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
