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
require_once(modification("classes/db_assenta_classe.php"));

db_postmemory($_POST);
db_postmemory($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$classenta  = new cl_assenta;
$cltipoasse = new cl_tipoasse;
$clrotulo   = new rotulocampo;

$classenta->rotulo->label("h16_codigo");
$classenta->rotulo->label("h16_regist");

$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("h12_assent");

if ( isset($pesquisa_chave) ) {
  ob_start();
}
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  <body>

    <div class="container">

    <form name="form2" method="post" action="" onsubmit="return confirmarConsultaSemFiltro();">
    <fieldset>
      <legend>Filtros da pesquisa:</legend>
      <table class="form-container">
        <tr>
          <td title="Opção de assentamento">Assentamento de:</td>
          <td>
            <?
              $db_opcao_opc_assentamento = (isset($vinculo_portaria)||isset($iTipoFuncionamento)) ? 3 : 1;
              $aOpcaoAssentamento = array(1 => 'Efetividade', 2=>'Histórico Funcional');
              $sOpcaoAssentamento = (!isset($iTipoFuncionamento)) ? 2 : $iTipoFuncionamento;
              db_select("sOpcaoAssentamento",$aOpcaoAssentamento,true,$db_opcao_opc_assentamento, "style=max-width:130px");
            ?>
          </td>
        </tr>
      
        <tr>
          <td title="<?=$Th12_assent?>">
            Tipo do Assentamento:</td>
          <td>
            <?
            db_input("h12_assent",6,$Ih12_assent,true,"text",4,"","chave_h12_assent");
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Th16_regist?>">
            Matricula:
          </td>
          <td>
            <?
            db_input("h16_regist",6,$Ih16_regist,true,"text",4,"","chave_h16_regist");
            ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
          </td>
          <td>
            <?
            db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();" />
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_assenta.hide();" />
    </form>

    <?php

    $sWhere = "";
    if (isset($bloqueia_reajuste)) {
      $sWhere .= " and h12_tiporeajuste = 0";
    }

    if ( isset( $apenas_tipo_portaria) ) {
      $sWhere .= " and exists(select 1 from portariatipo where h12_codigo = h30_tipoasse ) ";
    }
    
    if ( isset( $vinculo_portaria) ) {
      $sWhere .= " and not exists(select 1 from portariaassenta where h33_assenta = h16_codigo ) ";
    }

    if(isset($sOpcaoAssentamento) && $sOpcaoAssentamento == 2) {
      $sWhere .= " and assentamentofuncional.rh193_assentamento_funcional is not null";
    } elseif(isset($sOpcaoAssentamento) && $sOpcaoAssentamento == 1) {

      $sWhere .= " and exists ( select true from tipoassedb_depart where rh184_db_depart = ".db_getsession("DB_coddepto")." and h16_assent = rh184_tipoasse ) ";
      $sWhere .= " and assentamentofuncional.rh193_assentamento_funcional is null";
      $sWhere .= " and h16_regist in (select rh02_regist
                                      from rhpessoalmov
                                     where rh02_anousu = ". DBPessoal::getAnoFolha() ."
                                       and rh02_mesusu = ". DBPessoal::getMesFolha() ."
                                       and rh02_seqpes in (select rh56_seqpes
                                                             from rhpeslocaltrab
                                                            where rh56_princ = 't'
                                                              and rh56_localtrab in (select rh185_rhlocaltrab
                                                                                       from db_departrhlocaltrab
                                                                                      where rh185_db_depart = ".db_getsession("DB_coddepto").")))";
    }

    if(!isset($pesquisa_chave)){

      if(isset($campos)==false){
        if(file_exists("funcoes/db_func_assenta.php")==true){
          include(modification("funcoes/db_func_assenta.php"));
        }else{
          $campos = "assenta.*";
        }

        if(isset($retorna_amparo_legal)) {
          $campos .= ",portariatipo.h30_amparolegal";
        }
        $campos  = preg_replace("/([,])\w+\./i", "$1", $campos);
        $campos .= " ,(case when assentamentofuncional.rh193_assentamento_funcional is null then 'Efetividade' else 'Histórico Funcional' end) as \"dl_Assentamento de\"";
      }

      if(isset($chave_h12_assent) && (trim($chave_h12_assent)!="") ){
        $sql = $classenta->sql_query_funcional("",$campos,"h16_regist"," h12_assent = '$chave_h12_assent' {$sWhere}");
      }else if(isset($chave_h16_regist) && (trim($chave_h16_regist)!="") ){
        $sql = $classenta->sql_query_funcional("",$campos,"h16_regist"," h16_regist = '$chave_h16_regist' {$sWhere}");
      }else if(isset($chave_z01_nome) && (trim($chave_z01_nome))!="" ){
        $sql = $classenta->sql_query_funcional("",$campos,"h16_regist"," z01_nome like '%$chave_z01_nome%' {$sWhere}");
      }else {
        $sql = $classenta->sql_query_funcional("",$campos,"h16_regist"," z01_nome like '%%' {$sWhere}");
      }

      $repassa = array();
      if(isset($chave_h16_regist) && (trim($chave_h16_regist)!="")  ){
        $repassa = array("chave_h16_regist"=>$chave_h16_regist,"chave_h16_regist"=>$chave_h16_regist);
      }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
        $repassa = array("chave_z01_nome"=>$chave_z01_nome,"chave_z01_nome"=>$chave_z01_nome);
      }else if(isset($chave_h12_assent) && (trim($chave_h12_assent)!="") ){
        $repassa = array("chave_h12_assent"=>$chave_h12_assent,"chave_h12_assent"=>$chave_h12_assent);
      }else if(isset($sOpcaoAssentamento) && (trim($sOpcaoAssentamento)!="") ){
        $repassa = array("sOpcaoAssentamento"=>$sOpcaoAssentamento,"sOpcaoAssentamento"=>$sOpcaoAssentamento);
      }

      if(isset($sql) && trim($sql) != ""){

        echo "<fieldset><legend>Resultado da Pesquisa</legend>";
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo "</fieldset>";
      }
    }else{

      ob_end_clean();

      if($pesquisa_chave!=null && $pesquisa_chave!=""){

        $sWhere .= " and  h16_codigo = {$pesquisa_chave}";
        $result = $classenta->sql_record(
          $classenta->sql_query($pesquisa_chave, "*", null, empty($sWhere) ? null : " 1=1 {$sWhere}")
        );

        if($classenta->numrows!=0){

          $oRetorno = db_utils::fieldsMemory($result, 0);
          $sRetorno = json_encode($oRetorno);
          if ( isset($retorna_objeto) ) {

            echo "<script>{$funcao_js}({$sRetorno}, false)</script>";
            exit(0);
          }
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$h16_regist',false);</script>";
        }else{
          dump("Erro");
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{
        echo "<script>".$funcao_js."('',false);</script>";
      }
      exit;
    }
?>
    </div>
</body>
</html>
<script>
  js_tabulacaoforms("form2","chave_z01_nome",true,1,"chave_z01_nome",true);

  function js_limpar() {
    window.location.href = window.location.href + ' ';
  }

  function confirmarConsultaSemFiltro() {

    if ( !$F('chave_h16_regist')  && !$F('chave_z01_nome') && !$F('chave_h12_assent') ) {
      return confirm("A pesquisa sem informação de filtros pode ser um processo demorado. \n\nDeseja coninuar?");
    }
    return true;
  }
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
