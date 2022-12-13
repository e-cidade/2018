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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

/**
 * Traz somente os professores da escola
 */
$oGet          = db_utils::postMemory($_GET);
$oRotulo       = new rotulocampo;
$oDaoRecHumano = new cl_rechumano();

$oRotulo->label("ed284_i_rhpessoal");
$oRotulo->label("ed285_i_cgm");
$oRotulo->label("z01_nome");
$oRotulo->label("z01_nome");

$iEscola = db_getsession("DB_coddepto");
$aWhere  = array();

if (isset($oGet->lProfessor) && $oGet->lProfessor == 'true') {
  $aWhere[] = " ed01_c_docencia = 'S' ";
}

if ( isset($oGet->lFiltraProfissionalAtivo) ) {
  $aWhere[] = " ed75_i_saidaescola is null ";
}


$iEscola = !isset($oGet->iEscola) ? $iEscola : $oGet->iEscola;
$aWhere[] = " ed75_i_escola = {$iEscola} ";
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
      <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="ed285_i_cgm">CGM:</label>
            </td>
            <td>
              <?php
              db_input("ed285_i_cgm", 10, $Ied285_i_cgm, true, "text", 4,
                       "onFocus=\"nextfield='pesquisar2'\"","chave_ed285_i_cgm");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap">
              <label for="z01_nome">Nome:</label>
            </td>
            <td>
              <?php
              db_input("z01_nome", 50, $Iz01_nome, true, "text", 4,
                      "onFocus=\"nextfield='pesquisar2'\"","chave_z01_nome");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="submit" id="limpar"     value="Limpar" onClick="form1.reset();">
      <input name="fechar"    type="button" id="fechar"     value="Fechar" onClick="form1.close();">
    </form>
  </div>
  <div class="container">
    <?php
    if (!isset($pesquisa_chave)) {

      $campos  = "rechumano.ed20_i_codigo, \n";
      $campos .= "case when ed20_i_tiposervidor = 1 \n";
      $campos .= "     then cgmrh.z01_nome \n";
      $campos .= "     else cgmcgm.z01_nome \n";
      $campos .= " end as z01_nome, \n";
      $campos .= "case when ed20_i_tiposervidor = 1 \n";
      $campos .= "     then cgmrh.z01_numcgm \n";
      $campos .= "     else cgmcgm.z01_numcgm \n";
      $campos .= " end as z01_numcgm, \n";
      $campos .= "case when ed20_i_tiposervidor = 1 \n";
      $campos .= "     then rechumanopessoal.ed284_i_rhpessoal \n";
      $campos .= "     else rechumanocgm.ed285_i_cgm \n";
      $campos .= " end as dl_identificacao, \n";
      $campos .= "case when ed20_i_tiposervidor = 1 \n";
      $campos .= "     then cgmrh.z01_cgccpf \n";
      $campos .= "     else cgmcgm.z01_cgccpf \n";
      $campos .= " end as dl_cpf, \n";
      $campos .= "(select ativrh.ed01_c_descr \n";
      $campos .= "   from rechumanoativ as ativ \n";
      $campos .= "  inner join atividaderh as ativrh on ativrh.ed01_i_codigo = ativ.ed22_i_atividade \n";
      $campos .= "  where ativ.ed22_i_rechumanoescola = ed75_i_codigo \n";
      $campos .= "  order by ed01_c_regencia desc limit 1) as dl_atividade, \n";
      $campos .= "case when ed20_i_tiposervidor = 1 \n";
      $campos .= "     then regimerh.rh30_descr \n";
      $campos .= "     else regimecgm.rh30_descr \n";
      $campos .= " end as dl_regime, \n";
      $campos .= "case when ed20_i_tiposervidor = 1 \n";
      $campos .= "     then 'SIM' \n";
      $campos .= "     else 'NÃO' \n";
      $campos .= " end as ed20_i_tiposervidor, \n";
      $campos .= "rechumanoescola.ed75_i_codigo";

      if( isset( $chave_ed285_i_cgm ) && ( trim( $chave_ed285_i_cgm ) != "" ) ) {
       $aWhere[]  = " ( rechumanocgm.ed285_i_cgm = {$chave_ed285_i_cgm} OR rhpessoal.rh01_numcgm = {$chave_ed285_i_cgm} ) ";
      }

      if( isset( $chave_z01_nome ) && ( trim( $chave_z01_nome ) != "" ) ) {
       $aWhere[]  = " (cgmrh.z01_nome like '$chave_z01_nome%' OR cgmcgm.z01_nome like '$chave_z01_nome%') ";
      }

      if( isset( $atividaderh ) && ( trim( $atividaderh ) != "" ) ) {
       $aWhere[]  = " rechumanoativ.ed22_i_atividade = $atividaderh";
      }

      if( isset( $grupo ) && ( trim( $grupo ) != "" ) ) {
       $aWhere[]  = " ensino.ed12_i_ensino = $grupo";
      }

      if( isset( $subgrupo ) && ( trim( $subgrupo ) != "" ) ) {
       $aWhere[]  = " relacaotrabalho.ed23_i_disciplina = $subgrupo";
      }

      $sWhere  = implode(" and ", $aWhere);
      $sql     = $oDaoRecHumano->sql_query_escola(""," distinct ".$campos,"z01_nome",$sWhere);

      $sCamposAusencia  = " ed20_i_codigo, z01_nome, z01_numcgm, dl_identificacao, dl_cpf, dl_atividade, ";
      $sCamposAusencia .= "dl_regime, ed20_i_tiposervidor, ed75_i_codigo as db_rechumano ";

      $sWhereAusencia  = " ed20_i_codigo not in (select ed321_rechumano  ";
      $sWhereAusencia .= "                              from docenteausencia ";
      $sWhereAusencia .= "                             where ed321_inicio is not null ";
      $sWhereAusencia .= "                             and ed321_escola = {$iEscola}";
      $sWhereAusencia .= "                               and ed321_final is null)       ";

      $sSqlNovo = "select {$sCamposAusencia} from ({$sql}) as x where {$sWhereAusencia}";
      $repassa  = array();

      db_lovrot( $sSqlNovo, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
    } else {

      if (!empty($pesquisa_chave)) {

        $sCampos   = " distinct ed20_i_codigo, rh37_descr, ";
        $sCampos  .= " case                                ";
        $sCampos  .= "   when ed20_i_tiposervidor = 1      ";
        $sCampos  .= "     then cgmrh.z01_nome             ";
        $sCampos  .= "   else cgmcgm.z01_nome              ";
        $sCampos  .= " end as z01_nome,                    ";
        $sCampos  .= " case                                ";
        $sCampos  .= "   when ed20_i_tiposervidor = 1      ";
        $sCampos  .= "     then cgmrh.z01_numcgm           ";
        $sCampos  .= "   else cgmcgm.z01_numcgm            ";
        $sCampos  .= " end as z01_numcgm,                  ";
        $sCampos  .= " ed75_i_codigo                       ";

        $aWhere[]  = " ed20_i_codigo = {$pesquisa_chave} ";
        $sWhere    = implode(" and", $aWhere);
        $sSql      = $oDaoRecHumano->sql_query_escola("", $sCampos, "z01_nome", $sWhere);


        $sCamposAusencia = " ed20_i_codigo, rh37_descr, z01_nome, z01_numcgm";

        $sWhereAusencia  = " ed20_i_codigo not in (select ed321_rechumano          ";
        $sWhereAusencia .= "                         from docenteausencia          ";
        $sWhereAusencia .= "                        where ed321_inicio is not null ";
        $sWhereAusencia .= "                          and ed321_escola = {$iEscola}";
        $sWhereAusencia .= "                          and ed321_final is null)     ";

        $sSqlNovo  = "select {$sCamposAusencia} from ({$sSql}) as x where {$sWhereAusencia}";
        $rsDocente = $oDaoRecHumano->sql_record($sSql);

        if( $rsDocente && $oDaoRecHumano->numrows != 0 ) {

          db_fieldsmemory($rsDocente,0);
          echo "<script>".$funcao_js."('$z01_nome', '$z01_numcgm',false, '{$ed75_i_codigo}');</script>";
        } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      } else {
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
  </div>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();

$('chave_ed285_i_cgm').addClassName( 'field-size2' );
$('chave_z01_nome').addClassName( 'field-size7' );
</script>
