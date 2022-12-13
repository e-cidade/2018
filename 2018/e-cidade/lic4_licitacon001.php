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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$sPessoas = PessoasLicitaCon::NOME_ARQUIVO;
$sMembroCons = MembroConsLicitaCon::NOME_ARQUIVO;
$sComissao = ComissaoLicitaCon::NOME_ARQUIVO;
$sMemComissao = MemComissaoLicitaCon::NOME_ARQUIVO;
$sLicitacao = LicitacaoLicitaCon::NOME_ARQUIVO;
$sLicitante = LicitanteLicitaCon::NOME_ARQUIVO;
$sDoctacaoLic = DotacaoLicLicitaCon::NOME_ARQUIVO;
$sEventoLic = EventoLicLicitaCon::NOME_ARQUIVO;
$sDocumentoLic = DocumentoLicLicitaCon::NOME_ARQUIVO;
$sLote = LoteLicitaCon::NOME_ARQUIVO;
$sItem = ItemLicitaCon::NOME_ARQUIVO;
$sProposta = PropostaLicitaCon::NOME_ARQUIVO;
$sLoteProp = LotePropLicitaCon::NOME_ARQUIVO;
$sItemProp = ItemPropLicitaCon::NOME_ARQUIVO;
$sContrato = ContratoLicitaCon::NOME_ARQUIVO;
$sDotacaoCon = DotacaoConLicitaCon::NOME_ARQUIVO;
$sEventoCon = EventoConLicitaCon::NOME_ARQUIVO;
$sDocumentoCon = DocumentoConLicitaCon::NOME_ARQUIVO;
$sResponsavelCon = ResponsavelConLicitaCon::NOME_ARQUIVO;
$sLoteCon = LoteConLicitaCon::NOME_ARQUIVO;
$sItemCon = ItemConLicitaCon::NOME_ARQUIVO;
$sAlteracao = AlteracaoLicitaCon::NOME_ARQUIVO;

?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js?45"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
	<body class="body-default">
		<div class="container">
			<form name="form1" method="post">
				<fieldset>
					<legend>Geração de Arquivos do LicitaCon</legend>
					<fieldset class="separator">
						<legend>Licitação</legend>
						<table cellspacing="0" cellpadding="0">
						  <tr>
						    <td>
						    	<input id="<?php echo $sPessoas; ?>" name="<?php echo $sPessoas; ?>" type="checkbox"/>
					    	</td>
						    <td>
						    	<label for="<?php echo $sPessoas; ?>" for="<?php echo $sPessoas; ?>"><?php echo $sPessoas; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sMembroCons; ?>" name="<?php echo $sMembroCons; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sMembroCons; ?>"><?php echo $sMembroCons; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sComissao; ?>" name="<?php echo $sComissao; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sComissao; ?>"><?php echo $sComissao; ?></label>
					    	</td>
						  </tr>
						  <tr>
						    <td>
						    	<input id="<?php echo $sMemComissao; ?>" name="<?php echo $sMemComissao; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sMemComissao; ?>"><?php echo $sMemComissao; ?></label>
					    	</td>
						    <td>
					    		<input id="<?php echo $sLicitacao; ?>" name="<?php echo $sLicitacao; ?>" type="checkbox">
				    		</td>
						    <td>
						    	<label for="<?php echo $sLicitacao; ?>"><?php echo $sLicitacao; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sLicitante; ?>" name="<?php echo $sLicitante; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sLicitante; ?>"><?php echo $sLicitante; ?></label>
					    	</td>
						  </tr>
						  <tr>
						    <td>
						    	<input id="<?php echo $sDoctacaoLic; ?>" name="<?php echo $sDoctacaoLic; ?>" type="checkbox">
				    		</td>
						    <td>
						    	<label for="<?php echo $sDoctacaoLic; ?>"><?php echo $sDoctacaoLic; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sEventoLic; ?>" name="<?php echo $sEventoLic; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sEventoLic; ?>"><?php echo $sEventoLic; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sDocumentoLic; ?>" name="<?php echo $sDocumentoLic; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sDocumentoLic; ?>"><?php echo $sDocumentoLic; ?></label>
					    	</td>
						  </tr>
						  <tr>
						    <td>
						    	<input id="<?php echo $sLote; ?>" name="<?php echo $sLote; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sLote; ?>"><?php echo $sLote; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sItem; ?>" name="<?php echo $sItem; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sItem; ?>"><?php echo $sItem; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sProposta; ?>" name="<?php echo $sProposta; ?>" type="checkbox">
					    	</td>
						    <td>
					    		<label for="<?php echo $sProposta; ?>"><?php echo $sProposta; ?></label>
			    			</td>
						  </tr>
						  <tr>
						    <td>
						    	<input id="<?php echo $sLoteProp; ?>" name="<?php echo $sLoteProp; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sLoteProp; ?>"><?php echo $sLoteProp; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sItemProp; ?>" name="<?php echo $sItemProp; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sItemProp; ?>"><?php echo $sItemProp; ?></label>
					    	</td>
						  </tr>
						</table>
					</fieldset>

					<fieldset class="separator">
						<legend>Contratos</legend>
						<table cellspacing="0" cellpadding="0">
						  <tr>
						    <td>
						    	<input id="<?php echo $sContrato; ?>" name="<?php echo $sContrato; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sContrato; ?>"><?php echo $sContrato; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sDotacaoCon; ?>" name="<?php echo $sDotacaoCon; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sDotacaoCon; ?>"><?php echo $sDotacaoCon; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sEventoCon; ?>" name="<?php echo $sEventoCon; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sEventoCon; ?>"><?php echo $sEventoCon; ?></label>
					    	</td>
						  </tr>
						  <tr>
						    <td>
						    	<input id="<?php echo $sDocumentoCon; ?>" name="<?php echo $sDocumentoCon; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sDocumentoCon; ?>"><?php echo $sDocumentoCon; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sResponsavelCon; ?>" name="<?php echo $sResponsavelCon; ?>" type="checkbox">
					    	</td>
					    	<td>
					    		<label for="<?php echo $sResponsavelCon; ?>"><?php echo $sResponsavelCon; ?></label>
				    		</td>
						    <td>
						    	<input id="<?php echo $sLoteCon; ?>" name="<?php echo $sLoteCon; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sLoteCon; ?>"><?php echo $sLoteCon; ?></label>
					    	</td>
						  </tr>
						  <tr>
						    <td>
						    	<input id="<?php echo $sItemCon; ?>" name="<?php echo $sItemCon; ?>" type="checkbox">
				    		</td>
						    <td>
						    	<label for="<?php echo $sItemCon; ?>"><?php echo $sItemCon; ?></label>
					    	</td>
						    <td>
						    	<input id="<?php echo $sAlteracao; ?>" name="<?php echo $sAlteracao; ?>" type="checkbox">
					    	</td>
						    <td>
						    	<label for="<?php echo $sAlteracao; ?>"><?php echo $sAlteracao; ?></label>
					    	</td>
						  </tr>
						</table>
					</fieldset>
				</fieldset>
				<input type="button" value="Marcar/Desmarcar Todos" id="todos"/>
				<input type="button" value="Gerar" id="emitir"/>
			</form>
		</div>
		<?php db_menu(); ?>
		<script type="text/javascript">

			(function(exports) {

				var oDownloader = new DBDownload(),
						lTodos = false;

	      oDownloader.addGroups(1, "Arquivo Compactado");
	      oDownloader.addGroups(2, "Arquivos");

				$("todos").addEventListener("click", function() {

					lTodos = !lTodos;

					$$("input:checkbox").each(function(e) {
				    e.checked = lTodos;
					});
				})

				$("emitir").addEventListener("click", function() {

					var aRelatorios = $$("input:checkbox:checked").map(function(oCheck) {
					    return oCheck.name;
						});

					if (!aRelatorios.length) {
						return alert("Selecione ao menos um arquivo para geração.");
					}

					oDownloader.clear();

				  var oParam = {
				  	exec : "gerarArquivos",
				  	aArquivos : aRelatorios
				  }

				  new AjaxRequest("lic4_licitacon.RPC.php", oParam, function (oRetorno, lErro) {

				      if (lErro) {

				        alert(oRetorno.mensagem.urlDecode());
				        return false;
				      }

				      oDownloader.addFile(oRetorno.oArquivoCompactado.path, oRetorno.oArquivoCompactado.name, 1);

				      oRetorno.aArquivos.each(function(oArquivo) {
				      	oDownloader.addFile(oArquivo.path, oArquivo.name, 2);
				      });

				      oDownloader.show();
				    }).setMessage("Aguarde, gerando arquivos...")
				      .execute();

				})

			})(this);

		</script>
	</body>
</html>