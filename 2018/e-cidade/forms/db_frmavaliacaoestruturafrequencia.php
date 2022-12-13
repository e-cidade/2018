<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

  //MODULO: escola
  $oDaoAvaliacaoEstruturaFrequencia = new cl_avaliacaoestruturafrequencia();
  $oDaoRegraArredondamento          = new cl_regraarredondamento();
  $oDaoAvaliacaoEstruturaFrequencia->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("db77_descr");
  $clrotulo->label("ed316_descricao");
  $clrotulo->label("ed316_sequencial");
  $clrotulo->label("ed18_i_codigo");
  $clrotulo->label("ed328_ano");

  $lBloquearAlteracao = false;
  $iAno               = db_getsession("DB_anousu");
  $sDisabled          = '';

  if (isset($ed316_sequencial) && !empty($ed316_sequencial)) {

    $oDaoRegencia = db_utils::getDao("regencia");

    $sWhereRegenciaTotal = "ed57_i_escola = {$iCodEscola} AND ed52_i_ano = {$iAno} ";
    $sSqlRegenciaTotal   = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaTotal);
    $rsRegenciaTotal     = $oDaoRegencia->sql_record($sSqlRegenciaTotal);
    $iTotalRegencias     = $oDaoRegencia->numrows;

    $sWhereRegenciaEncerrada  = "ed59_c_encerrada = 'S' and ed57_i_escola = {$iCodEscola} AND ed52_i_ano = {$iAno}";
    $sSqlRegenciaEncerrada    = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaEncerrada);
    $rsRegenciaEncerrada      = $oDaoRegencia->sql_record($sSqlRegenciaEncerrada);
    $iTotalRegenciasEncerrada = $oDaoRegencia->numrows;

    if ($iTotalRegencias > $iTotalRegenciasEncerrada && $iTotalRegenciasEncerrada != 0) {
      $lBloquearAlteracao = true;
    }

    if ($lBloquearAlteracao) {

      db_msgbox("Não é possível alterar a regra, pois existem turmas encerradas e em aberto na escola.");
      $db_opcao = 5;
      $db_botao = false;
    }
  }
  MsgAviso(db_getsession("DB_coddepto"),"escola");
?>
<form name="form1" method="post" action="">
  <div style="display: table">
    <fieldset>
      <legend class="bold">Estrutural da Frequência</legend>
      <center>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Ted328_sequencial?>">
               <?=@$Led328_sequencial?>
            </td>
            <td>
              <?
                db_input('ed328_sequencial', 10, $Ied328_sequencial, true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr style="display: none">
            <td nowrap title="<?=@$Ted328_escola?>">
              <?
                db_ancora(@$Led328_escola, "js_pesquisaed328_escola(true);", $db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed328_escola', 10, $Ied328_escola, true, 'text', $db_opcao, " onchange='js_pesquisaed328_escola(false);'");
              ?>
              <?
                db_input('ed18_i_codigo', 40, $Ied18_i_codigo, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted328_db_estrutura?>">
              <?
                db_ancora(@$Led328_db_estrutura, "js_pesquisaed328_db_estrutura(true);", $db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed328_db_estrutura', 10, $Ied328_db_estrutura, true, 'text', $db_opcao,
                         " onchange='js_pesquisaed328_db_estrutura(false);'")
              ?>
              <?
                db_input('db77_descr', 40, $Idb77_descr, true, 'text', 3, '');
                db_input('ed328_sequencial', 40, $Idb77_descr, true, 'hidden', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted328_ativo?>">
              <?=@$Led328_ativo?>
            </td>
            <td>
              <?
                $aAtivo = array("f"=>"NAO", "t"=>"SIM");
                db_select('ed328_ativo', $aAtivo, true, $db_opcao, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted328_arredondafrequencia?>">
              <?=@$Led328_arredondafrequencia?>
            </td>
            <td>
              <?
                $aArredonda = array("f"=>"NAO", "t"=>"SIM");
                db_select('ed328_arredondafrequencia', $aArredonda, true, $db_opcao, " onchange='js_verificaArredondar();'");
              ?>
            </td>
          </tr>
          <tr id="ctnRegraArredondamento" style="display: none">
            <td nowrap title="<?=@$Ted316_sequencial?>">
              <?
                db_ancora(@$Led316_sequencial, "js_pesquisaed316_regraarredondamento(true);", $db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed316_sequencial', 10, $Ied316_sequencial, true, 'text',$db_opcao,
                         " onchange='js_pesquisaed316_regraarredondamento(false);'");
                db_input('ed316_descricao', 40, $Ied316_descricao, true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted328_ano?>" >
              <?=@$Led328_ano?>
            </td>
            <td>
              <?
                $db_opcaoano = $db_opcao;
                if ($db_opcao == 2) {
                  $db_opcaoano = 33;
                }
                db_input('ed328_ano', 10, $Ied328_ano, true, 'text', $db_opcaoano);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted328_observacao?>" colspan="2">
              <fieldset>
                <legend><b><?=@$Led328_observacao?></b></legend>
                <?
                  db_textarea('ed328_observacao', 5, 74, $Ied328_observacao, true, 'text', $db_opcao, "");
                ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </center>
    </fieldset>
  </div>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
         id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22||$db_opcao==5?"Alterar":"Excluir"))?>"
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed328_db_estrutura(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframedb_estrutura',
                        'func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr',
                        'Pesquisa',
                        true
                       );
  } else {

    if (document.form1.ed328_db_estrutura.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframedb_estrutura',
                          'func_db_estrutura.php?pesquisa_chave='+document.form1.ed328_db_estrutura.value+'&funcao_js=parent.js_mostradb_estrutura',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.db77_descr.value = '';
    }
  }
}

function js_mostradb_estrutura(chave,erro) {

  document.form1.db77_descr.value = chave;
  if (erro == true) {

    document.form1.ed328_db_estrutura.focus();
    document.form1.ed328_db_estrutura.value = '';
  }
}

function js_mostradb_estrutura1(chave1,chave2) {

  document.form1.ed328_db_estrutura.value = chave1;
  document.form1.db77_descr.value         = chave2;
  db_iframedb_estrutura.hide();
}

function js_pesquisaed328_escola(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_escola',
                        'func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_i_codigo',
                        'Pesquisa',
                        true
                       );
  } else {
    if (document.form1.ed328_escola.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_escola',
                          'func_escola.php?pesquisa_chave='+document.form1.ed328_escola.value+'&funcao_js=parent.js_mostraescola',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.ed18_i_codigo.value = '';
    }
  }
}

function js_mostraescola(chave,erro) {

  document.form1.ed18_i_codigo.value = chave;
  if (erro == true) {

    document.form1.ed328_escola.focus();
    document.form1.ed328_escola.value = '';
  }
}

function js_mostraescola1(chave1,chave2) {

  document.form1.ed328_escola.value  = chave1;
  document.form1.ed18_i_codigo.value = chave2;
  db_iframe_escola.hide();
}

function js_pesquisaed316_regraarredondamento(mostra) {

	/*
	 * Parametro passado na função de pesquisa, para identificar que a pesquisa foi originada
	 * do formulário Estrutural Frequencia, e que deve mostrar apenas as regras ativas
	 */
	var sEstrutural = 'E';
	if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_regraarredondamento',
                        'func_regraarredondamento.php?pesquisa='+sEstrutural+'&funcao_js=parent.js_mostraregraarredondamento1|ed316_sequencial|ed316_descricao',
                        'Pesquisa',
                        true
                       );
	} else {

    if (document.form1.ed316_sequencial.value != "") {

    	js_OpenJanelaIframe(
        	                'CurrentWindow.corpo',
        	                'db_iframe_regraarredondamento',
        	                'func_regraarredondamento.php?pesquisa_chave='+document.form1.ed316_sequencial.value+
        	                                            '&funcao_js=parent.js_mostraregraarredondamento',
        	                'Pesquisa',
        	                false
        	               );
    } else {
      document.form1.ed316_descricao.value = '';
    }
	}
}

function js_mostraregraarredondamento(chave, erro) {

	document.form1.ed316_descricao.value = chave;
	if (erro == true) {

		document.form1.ed316_sequencial.focus();
		document.form1.ed316_sequencial.value = '';
	}
}

function js_mostraregraarredondamento1(chave1, chave2) {

	document.form1.ed316_sequencial.value = chave1;
	document.form1.ed316_descricao.value  = chave2;
	db_iframe_regraarredondamento.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_avaliacaoestruturafrequencia',
                      'func_avaliacaoestruturafrequencia.php?funcao_js=parent.js_preenchepesquisa|ed328_sequencial',
                      'Pesquisa',
                      true
                     );
}

function js_preenchepesquisa(chave) {

  db_iframe_avaliacaoestruturafrequencia.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_pesquisaregraarredondamento() {
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_regraarredondamento',
                      'func_regraarredondamento.php?funcao_js=parent.js_preenchepesquisaregraarredondamento|ed316_sequencial',
                      'Pesquisa',
                      true
                     );
}

function js_preenchepesquisaregraarredondamento(chave) {

	db_iframe_regraarredondamento.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_verificaArredondar() {

	var iArredondar = $F('ed328_arredondafrequencia');

	if (iArredondar == 't') {
		$('ctnRegraArredondamento').style.display = "table-row";
	} else {

		$('ctnRegraArredondamento').style.display = "none";
		document.form1.ed316_sequencial.value     = '';
  	document.form1.ed316_descricao.value      = '';
	}
}

function js_validarCampos() {

	var iArredondar  = $F('ed328_arredondafrequencia');
	var iCodigoRegra = $F('ed316_sequencial');

	if (iArredondar == 't') {

	  if (iCodigoRegra == '') {

  	  alert ('Opção Arredondar Média setada como Sim. Deve ser informado o código da regra de arredondamento.');
  	  document.form1.ed316_sequencial.focus();
  	  return false;
	  }
	}
}

js_verificaArredondar();
</script>
