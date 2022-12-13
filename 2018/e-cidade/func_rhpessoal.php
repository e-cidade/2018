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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));

db_postmemory($_POST);
db_postmemory($_GET);

$chave_z01_nome = isset($chave_z01_nome) ? stripslashes($chave_z01_nome) : '';
$db_opcao       = 1;

if (!isset($sQuery)) {
  $sQuery = null;
}

if(isset($filtro_lotacao) && $filtro_lotacao){

  $rsSqlUsaFiltroLotacao = db_query("select count(*) as qtde_usuarios_permissao from db_usuariosrhlota");
  
  if(is_resource($rsSqlUsaFiltroLotacao) && db_utils::fieldsMemory($rsSqlUsaFiltroLotacao, 0)->qtde_usuarios_permissao > 0){
    $lotelotacao = true;
  }
}

if (!isset($lotelotacao)) {
  $lotelotacao = false;
}

if ($sQuery || isset($sAtivos)) {
  $db_opcao = 3;
  $selecao  = 'A';
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhpessoal = new cl_rhpessoal;
$clrotulo    = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_nome");

if (isset($valor_testa_rescisao)) {
  $chave_rh01_regist = $valor_testa_rescisao;
  $retorno           = db_alerta_dados_func($testarescisao, $valor_testa_rescisao, db_anofolha(), db_mesfolha());
  if ($retorno != ""){
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css" />
<link href="estilos.css" rel="stylesheet" type="text/css" />

<style>
 form.container div {
   width      : 100%;
   text-align : left;
   padding    : 1px;
 }

form.container label { 
  width: 150px !important;
  float: left;
}

form.container div { 
  font-weight: bold;
}

#container_adicao_filtros {
  margin : 5px auto 0;
}

/**
 * @todo Rever os campos a seguir
 */
.field-size8 {
  width: 302px;
}

.field-size2 {
  width: 122px;
}

#chave_rh01_regist,
#chave_rh01_numcgm,
#chave_z01_cgccpf,
#selecao,
#regime {
  width: 122px !important;
}

#chave_z01_nome {
  width: 427px;
}

</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
    function js_recebe_click(value) {

      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','funcao_js');
      obj.setAttribute('id','funcao_js');
      obj.setAttribute('value','<?=$funcao_js?>');
      document.form2.appendChild(obj);

      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','valor_testa_rescisao');
      obj.setAttribute('id','valor_testa_rescisao');
      obj.setAttribute('value',value);
      document.form2.appendChild(obj);

      document.form2.submit();
    }
  </script>
  <?
}
?>
</head>
<body>

  <form name="form2" method="post" action="" class="container" >
    <fieldset id="fieldset_principal">
      <legend>Pesquisa de Servidores</legend> 
        <div> 
         <label for="chave_rh01_regist" title="<?=$Trh01_regist?>"><?=$Lrh01_regist?></label>
         <? db_input("rh01_regist",10,$Irh01_regist,true,"text",4,"","chave_rh01_regist"); ?>
        </div>
        <div> 
         <label title="<?=$Trh01_numcgm?>"><?=$Lrh01_numcgm?></label>
          <? db_input("rh01_numcgm",10,$Irh01_numcgm,true,"text",4,'class="field-size2"',"chave_rh01_numcgm"); ?>
        </div>
        <div> 
          <label title="<?=$Tz01_cgccpf?>"><?=$Lz01_cgccpf?></label>
          <? db_input("z01_cgccpf",14,1,true,"text",4,"","chave_z01_cgccpf"); ?>
        </div>
      <div> 
        <label title="<?=$Tz01_nome?>"><?=$Lz01_nome?></label>
        <? db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome"); ?>
      </div>
      <div>
        <label><strong>Seleção Por:</strong></label>
        <?
          $aSelecao = array(
              "T" => "Todos",
              "A" => "Ativos",
              "R" => "Rescindidos"
            );
          db_select("selecao", $aSelecao, true, $db_opcao, "");
        ?>
      </div>
      <div style="margin-top: 10px ">
        <a class="DBAncora" href="#" id="adicionar_filtro">Configurar Filtro Personalizado... </a>
      </div>
   </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"  onclick="return js_valida(arguments[0]);"> 
    <input name="limpar"    type="button" id="limpar"     value="Limpar">
    <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_rhpessoal.hide();">
  </form>
<?
  $chave_z01_nome = addslashes($chave_z01_nome);

  $sFiltraInstit = " rh01_instit = " . db_getsession('DB_instit');
  if (isset($_GET['lTodos'])) {
    $sFiltraInstit = " 1 = 1 ";
  }

  $aWhere = array();

  $aWhere[] = "((rh02_instit is null and $sFiltraInstit) or $sFiltraInstit)";

  if(isset($mInstituicoes) && !empty($mInstituicoes)) {
    if(is_array($mInstituicoes)) {
      $aWhere[] = "rh01_instit IN (". implode(", ", $mInstituicoes) .")";
    }
    if(!is_array($mInstituicoes)) {
      $aWhere[] = "rh01_instit IN (". $mInstituicoes .")";
    }
  }

  if (isset($selecao)) {
    switch ($selecao) {
      case "A":
          $aWhere[] = "rh05_seqpes is null";
        break;
      case "R":
          $aWhere[] = "rh05_seqpes is not null";
        break;
    }
  }

  if ($lotelotacao) {
    
    try {
  
      $oDaoCfpess           = new cl_cfpess();
      $oDaoDbUsuarioLotacao = new cl_db_usuariosrhlota();
      $aEstruturais         = array();
      $aResultados          = array();
      $iInstit              = db_getsession('DB_instit');
      $iAno                 = DBPessoal::getAnoFolha();
      $iMes                 = DBPessoal::getMesFolha();
      $iCodigoUsuario       = db_getsession('DB_id_usuario');

      $sSqlMascaraLotacao = $oDaoCfpess->sql_query($iAno, $iMes, $iInstit, "db77_estrut");
      $rsMascaraLotacao   = db_query($sSqlMascaraLotacao);

      if (!$rsMascaraLotacao) {
        throw new DBException("Erro ao buscar a mascara da lotação.");
      }

      if (pg_num_rows($rsMascaraLotacao) == 0) {
        throw new BusinessException("Nenhuma lotação configurada para esta competência. Por favor verificar manutenção de parâmetros.");
      }

      $sMascara = db_utils::fieldsMemory($rsMascaraLotacao,0)->db77_estrut;

      $sSqlLotacoesUsuario = $oDaoDbUsuarioLotacao->sql_query(null, "r70_estrut", null, "rh157_usuario = {$iCodigoUsuario}");
      $rsLotacoesUsuario   = db_query($sSqlLotacoesUsuario);

      if (!$rsLotacoesUsuario) {
        throw new DBException("Erro ao buscar lotações do usuário.");
      }

      if (pg_num_rows($rsLotacoesUsuario) == 0) {
        throw new BusinessException("Nenhuma lotação vinculada à este usuário.");
      }

      $aEstruturais = db_utils::getCollectionByRecord($rsLotacoesUsuario);

      foreach ($aEstruturais as $oEstrutural) {  
        $aResultados[] = trim(str_replace( ".",'', DBEstrutura::removerEstruturalVazio( DBEstrutura::mascararString($sMascara, $oEstrutural->r70_estrut) ) ) ); 
      }  

      $aWhere[] = "r70_estrut ~ '^(".implode('|',$aResultados).")'";

    } catch (Exception $oErro) {

      db_msgbox($oErro->getMessage());
      return true;
    }

  }

  if (isset( $rh37_funcao ) && !empty($rh37_funcao)) {
    $aWhere[] = "rh37_funcao = $rh37_funcao";
  }

  if (isset($rh30_regime) && !empty($rh30_regime)) {
    $aWhere[] = "rh30_regime = $rh30_regime";
  }

  if (isset($r70_codigo) and !empty($r70_codigo)) {
    $aWhere[] = "r70_codigo = $r70_codigo";
  }

  // Validação dos campos de pesquisa de servidores
  if (isset($chave_rh01_regist) && !empty($chave_rh01_regist)) {
    $aWhere[] = "rh01_regist = $chave_rh01_regist";
  }

  if (isset($chave_rh01_numcgm) && !empty($chave_rh01_numcgm)) {
    $aWhere[] = "rh01_numcgm = $chave_rh01_numcgm";
  }

  if (isset($chave_z01_cgccpf) && !empty($chave_z01_cgccpf)) {
    $aWhere[] = "z01_cgccpf like '$chave_z01_cgccpf%'";
  }

  if (isset($chave_z01_nome) && !empty($chave_z01_nome)) {
    $aWhere[] = "z01_nome like '$chave_z01_nome%'";
  }

  if (isset($lFormularioAfastamento) && !isset($filtro_lotacao)) {

    $aWhere[] = "exists (select 1
                           from db_departrhlocaltrab
                                inner join rhpeslocaltrab on rhpeslocaltrab.rh56_localtrab = db_departrhlocaltrab.rh185_rhlocaltrab
                          where db_departrhlocaltrab.rh185_db_depart = " . db_getsession('DB_coddepto') . "
                            and rhpeslocaltrab.rh56_seqpes     = rh02_seqpes)";
  }


  if (isset($contratosEmergenciais) && !empty($contratosEmergenciais) && $contratosEmergenciais == 1) {
    $aWhere[] = "rh163_sequencial is not null";
  }

  if (!empty($condition) && $condition  == 'somenteAtivos') {
      $aWhere[] = " rhregime.rh30_vinculo = 'A'";
  }


  if (!isset($pesquisa_chave)) {
    if (isset($campos) == false) {
      if (file_exists("funcoes/db_func_rhpessoal.php") == true) {
        include(modification("funcoes/db_func_rhpessoal.php"));
      } else {
        $campos = "rhpessoal.*";
      }
    }

    $repassa = array(
      "chave_z01_nome"    => @$chave_z01_nome,
      "chave_rh01_regist" => @$chave_rh01_regist,
      "chave_rh01_numcgm" => @$chave_rh01_numcgm,
      "rh01_instit"       => @$instit
    );

    if (count($_POST) > 0) {
      $sWhere = implode(' and ', $aWhere);
      $sql = $clrhpessoal->sql_query_func_rhpessoal(
        "", $campos, "z01_nome", $sWhere, $sQuery);
 
      if (isset($sql) && !empty($sql)) {
        echo "<div class='container'>";
        echo "  <fieldset>";
        echo "    <legend>Resultado da Pesquisa</legend>";
          db_lovrot($sql, 15, "()", "",
            (isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|rh01_regist" : $funcao_js),
            "" ,"NoMe", $repassa);
        echo "  </fieldset>";
        echo "</div>";
      }
    }
  } else {  // com chave pesquisa
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
      $aWhere[] = "rh01_regist = $pesquisa_chave";
      $sWhere = implode(' and ', $aWhere);

      $sSql = $clrhpessoal->sql_query_func_rhpessoal(
        null, "*", "rh01_regist", $sWhere, $sQuery);
      $result = $clrhpessoal->sql_record($sSql);

      if ($clrhpessoal->numrows != 0) {
        db_fieldsmemory($result, 0);
        if (isset($testarescisao)) {
          $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());
          if($retorno != ""){

            if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && isset($rotina_suplementar)) {
              $oDaoPessoalMov = new cl_rhpessoalmov();
              if ($oDaoPessoalMov->isRescindido($pesquisa_chave)) {
                db_msgbox('Não é possível cadastrar rescisão, pois todas as folhas disponíveis estão fechadas.');
              }
            } else {
              db_msgbox($retorno);
            }
            
          }
        }
        echo "<script>" . $funcao_js . "('$z01_nome', false,'" . @db_formatar($rh01_admiss, 'd') . "', false);</script>";
      } else {
        echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado', true);</script>";
      }
    } else {
      echo "<script>" . $funcao_js . "('', false);</script>";
    }
  }
?>
     </td>
   </tr>
</table>

</body>
</html>
<?
if (!isset($pesquisa_chave)) {
  ?>
  <script>

  function js_valida(event) {
    document.getElementById('chave_z01_nome').onkeyup = event;
    document.getElementById('chave_rh01_regist').onkeyup = event;
    document.getElementById('chave_rh01_numcgm').onkeyup = event;
    document.getElementById('chave_z01_cgccpf').onkeyup = event;
    return true;
  }
  </script>
  <?
}
?>

<div id="container_adicao_filtros" class="container" style="display:none">
  <fieldset>
    <legend>Filtros Disponíveis:</legend>
    <div id="ContainerComponentes"></div>
  </fieldset>
  <input type="button" value="Salvar" id="adicionar_componente" />
</div>

<script>
(function() {
  var oBotaoLimpar     = document.getElementById('limpar');
  oBotaoLimpar.onclick = function() {

    var aElementos = document.querySelectorAll('form fieldset input');   

    for (var iIndiceInput = 0; iIndiceInput < aElementos.length; iIndiceInput++) {
      var oInput   = aElementos[iIndiceInput];
      oInput.value = '';
    }

    document.form2.selecao.value = '';   
  }

  require_once("scripts/arrays.js");
  require_once("scripts/numbers.js");
  require_once("scripts/widgets/windowAux.widget.js");
  require_once("scripts/widgets/dbcomboBox.widget.js");
  require_once("scripts/classes/configuracao/DBViewPreferenciaUsuario.classe.js");
  require_once("scripts/classes/DBViewFormularioFolha/FiltroDinamicoPesquisaServidores.classe.js");

  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores.rh37_descr   = "<?php echo isset($rh37_descr)  ? $rh37_descr  : ""; ?>";
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores.rh37_funcao  = "<?php echo isset($rh37_funcao) ? $rh37_funcao : ""; ?>";
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores.r70_codigo   = "<?php echo isset($r70_descr)   ? $r70_codigo  : ""; ?>";
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores.r70_descr    = "<?php echo isset($r70_codigo)  ? $r70_descr   : ""; ?>";
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores.rh30_regime = "<?php echo isset($rh30_regime)? $rh30_regime: ""; ?>";

  var oPreferencias = new DBViewPreferenciaUsuario();
  var aComponentes  = oPreferencias.oDados.oFiltrosPersonalizados["func_rhpessoal.php"];

  if (aComponentes) {
    for(var iComponente = 0; iComponente < aComponentes.length; iComponente++) {
      makeComponent(aComponentes[iComponente]);
    }
  }

  montaGridComponentes(aComponentes);

  var oAncoraAdicionarFiltro = $('adicionar_filtro'); 
  var oJanela                = new windowAux(null, 'Adicionar Filtros', 300, 250);

  oAncoraAdicionarFiltro.onclick = function() {

    montaGridComponentes(aComponentes);

    $('container_adicao_filtros').style.display = '';

    oJanela.setContent($('container_adicao_filtros'));
    oJanela.show();

    $('adicionar_componente').onclick = function() {
     
      var aValoresSelecionados = oGridFiltros.getSelection();

      $$('.filtros_dinamicos').each(function(oElemento) {
        oElemento.parentNode.removeChild(oElemento); // Remove o elemento
      });

      /**
       * Percorre as opções selecionadas e cria os elementos conforme implementação.
       */
      var aSelecionados = [];
      for (var iIndiceSelecionado = 0; iIndiceSelecionado < aValoresSelecionados.length; iIndiceSelecionado++) {
        var sValorSelecionado = aValoresSelecionados[iIndiceSelecionado][0];
        aSelecionados.push(sValorSelecionado);
        makeComponent(sValorSelecionado);
      };

      aComponentes = aSelecionados;
      oPreferencias.oDados.oFiltrosPersonalizados["func_rhpessoal.php"] = aSelecionados;
      oPreferencias.salvar();
      oJanela.hide();
    };
  };
})();

function montaGridComponentes(aComponentes) {

  /**
   * Instancia o componente DBGrid
   */
  oGridFiltros              = new DBGrid('gridFiltros');
  oGridFiltros.nameInstance = "oGridFiltros";
  oGridFiltros.setHeight('80px');
  oGridFiltros.setCheckbox(0);
  oGridFiltros.setHeader(new Array('Codigo','Filtro'));
  oGridFiltros.setCellWidth(new Array('0', '100'));
  oGridFiltros.setCellAlign(new Array('left', 'left'));
  oGridFiltros.aHeaders[1].lDisplayed = false;
  oGridFiltros.show($('ContainerComponentes'));
  oGridFiltros.clearAll(true);

  aDadosFiltros = [
      {sFuncao: "makeComboRegime",   descricao: "Regime"},
      {sFuncao: "makeLookUpLotacao", descricao: "Lotação"},
      {sFuncao: "makeLookUpCargo",   descricao: "Cargo"}
    ];

  aDadosFiltros.each (function(aDadosFiltro) {
      oGridFiltros.addRow([
          aDadosFiltro.sFuncao,
          aDadosFiltro.descricao
        ],
        true, false, (aComponentes) ? aComponentes.in_array(aDadosFiltro.sFuncao) : false);
    });

  oGridFiltros.renderRows();
}

function makeComponent(sName) {
  var oElemento         = document.createElement("div");
  oElemento.id          = "div" + sName;
  oElemento.className  += "filtros_dinamicos";
  $('fieldset_principal').insertBefore( oElemento, $('adicionar_filtro').parentNode); 
  DBViewFormularioFolha.FiltroDinamicoPesquisaServidores[sName](oElemento);
}
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
