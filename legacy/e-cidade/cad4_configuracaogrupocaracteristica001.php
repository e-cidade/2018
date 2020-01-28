<?php
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

	require_once ("libs/db_stdlib.php");
	require_once ("libs/db_conecta.php");
	require_once ("libs/db_sessoes.php");
	require_once ("libs/db_usuariosonline.php");
	require_once ("libs/db_app.utils.php");
	require_once ("libs/db_utils.php");
	require_once ("dbforms/db_funcoes.php");

	$oDaoConfiguracaoCar = new cl_configuracaogrupocaracteristicas();
	$oDaoCargrup         = new cl_cargrup();

	$oDaoConfiguracaoCar->rotulo->label();
	$oDaoCargrup->rotulo->label('j32_descr');

	$oPost = db_utils::postMemory($_POST);

	define("MENSAGENS", "tributario.cadastro.cad4_configuracaogrupocaracteristica.");

	if (isset($oPost->enviar)) {

		$oDaoConfiguracaoCar->db144_sequencial               = $oPost->db144_sequencial;
		$oDaoConfiguracaoCar->db144_tipoutilizacaoiptu       = $oPost->db144_tipoutilizacaoiptu;

		try {

			/**
 			 * Valida se os grupos informados estão de acordo com a regra
			 */
			$rsTipoUtilizacaoIPTU       = db_query( $oDaoCargrup->sql_query(null, '*', null, "j32_tipo in ('L', 'C') and j32_grupo = {$oPost->db144_tipoutilizacaoiptu}       ") );
			if ($rsTipoUtilizacaoIPTU && pg_num_rows($rsTipoUtilizacaoIPTU) == 0) {
				throw new BusinessException(_M( MENSAGENS . "grupo_invalido", (object)(array('sCampo' => 'Tipo Utilização IPTU', 'iGrupo' => $oPost->db144_tipoutilizacaoiptu)) ));
			}

			db_inicio_transacao();

			/**
			 * Se não tiver sequencial faz inclusão
			 */
			if ($oDaoConfiguracaoCar->db144_sequencial != "") {

				if (! $oDaoConfiguracaoCar->alterar($oDaoConfiguracaoCar->db144_sequencial) ) {
					throw new DBException($oDaoConfiguracaoCar->erro_sql);
				}
			} else {
				/**
				 * Case possua sequencial faz update
				 */
				if (!$oDaoConfiguracaoCar->incluir(null) ) {
					throw new DBException($oDaoConfiguracaoCar->erro_sql);
				}

				$db144_sequencial = $oDaoConfiguracaoCar->db144_sequencial;
			}

			db_msgbox(_M( MENSAGENS . "configuracao_salva"));

			db_fim_transacao();

		} catch (Exception $oErro) {

			db_msgbox($oErro->getMessage());
			db_fim_transacao(true);
		}

	}

		/**
		 * Traz o registro do banco.
		 */
		$rsConfiguracaoCar = db_query($oDaoConfiguracaoCar->sql_query(null));

		if ($rsConfiguracaoCar && pg_num_rows($rsConfiguracaoCar) > 0) {

			$oDadosConfiguracaoCar = db_utils::fieldsMemory($rsConfiguracaoCar, 0);

			$db144_sequencial               = $oDadosConfiguracaoCar->db144_sequencial;
			$db144_tipoutilizacaoiptu       = $oDadosConfiguracaoCar->db144_tipoutilizacaoiptu;

			/**
			 * Traz as descrições de cada coluna
			 */
			$j32_descr_tipoutilizacaoiptu       = db_utils::fieldsMemory( db_query( $oDaoCargrup->sql_query( $db144_tipoutilizacaoiptu       ) ), 0)->j32_descr;
		}

?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>

	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div class="container" style="width:700px !important;">

    <form method="post" name="form1">

    	<?php db_input('db144_sequencial', 0, 0, 0, 'hidden'); ?>

	    <fieldset>

	      <legend>Configuração de Grupos de Características</legend>

	      <table class="form-container">
	        <tr>
	          <td align="right" nowrap title="<?php echo $Tdb144_tipoutilizacaoiptu; ?>">
	            <?php
	            	db_ancora($Ldb144_tipoutilizacaoiptu, "js_pesquisaCargrup(true, 'L|C', 'tipoutilizacaoiptu', arguments[0])", 1);
	            ?>
            </td>
	          <td>
	          	<?php
	          		$sOnchange = 'onchange="js_pesquisaCargrup(false, \'L|C\', \'tipoutilizacaoiptu\', arguments[0])";';
								db_input('db144_tipoutilizacaoiptu', 10, $Idb144_tipoutilizacaoiptu, true, 'text', 1, $sOnchange);
              	db_input('j32_descr_tipoutilizacaoiptu', 40, $Ij32_descr, true, 'text', 3);
              ?>
	          </td>
	        </tr>

	 	    </table>

		  </fieldset>

		  <input type="submit" name="enviar" value="Salvar" onclick="return js_salvar();"/>

	  </form>

	</div>

		<?php
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>

<script type="text/javascript">


	/**
	 * Função genérica abrir o iframe de pesquisa
	 * @param lMostra  - boolean - define se vai abrir o iframe
	 * @param j32_tipo - string  - tipo do cargrup (L,I,C,O,LC...)
	 * @param sCampo   - string  - define qual campo da tela vai manipular
	 * @param oEvent   - Event   - recebe o evento para validação padrão do sistema.
	 */
	function js_pesquisaCargrup(lMostra, j32_tipo, sCampo, oEvent) {
		 var oCampo     = document.form1['db144_'+sCampo];
		 var sUrlFuncao = "func_cargrup_rel.php?&grupo=" + j32_tipo;

		 oCampo.onkeyup = oEvent;

		if ( lMostra == true ) {

				sUrlFuncao += "&funcao_js=parent.js_mostraRetornoIframe_" + sCampo + "|j32_descr|j32_grupo";
		    js_OpenJanelaIframe('top.corpo', 'db_iframe', sUrlFuncao, 'Pesquisa', true);
	  } else {

	    if ( oCampo.value != '' ) {

		    sUrlFuncao += '&funcao_js=parent.js_mostraRetornoDireto_' + sCampo + '&pesquisa_chave=' + oCampo.value
	      js_OpenJanelaIframe('top.corpo', 'db_iframe', sUrlFuncao, 'Pesquisa', false);
	    } else {
	      document.form1['j32_descr_' + sCampo].value = '';
	    }
	  }
	}

	/**
	 * Função genérica para mostrar o retorno da pesquisa nos campos
	 * @param - sCampo - string - define qual campo vai manipular
	 * @param - sValor - valor do campo de destino na ancora
	 * @param - sChave - valor do campo de origem na ancora
	 */
	function js_mostraRetorno(sCampo, sValor, sChave) {
		document.form1['j32_descr_' + sCampo].value = sValor;

		if (sChave === true) {
			document.form1['db144_' + sCampo].value   = '';
			sChave = null;
		}

		if (sChave && sChave != "") {
			document.form1['db144_' + sCampo].value   = sChave;
		}
	}

	 /**
		 * Funções de retorno para o tipo de utilizacao IPTU
		 */

	function js_mostraRetornoIframe_tipoutilizacaoiptu(j32_descr, j32_grupo) {
		db_iframe.hide();
		js_mostraRetorno('tipoutilizacaoiptu', j32_descr, j32_grupo);
	}

	function js_mostraRetornoDireto_tipoutilizacaoiptu(j32_descr, lErro) {
		js_mostraRetorno('tipoutilizacaoiptu', j32_descr, lErro);
	}


	function js_salvar() {

		var MENSAGENS = 'tributario.cadastro.cad4_configuracaogrupocaracteristica.';

		if ( $F('db144_tipoutilizacaoiptu') == "" ) {
			alert(_M(MENSAGENS + "nao_informado", {sCampo: "Tipo de Utilização IPTU"}));
			return false;
		}

		return true;
	}

</script>