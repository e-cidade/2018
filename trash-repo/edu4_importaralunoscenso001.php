<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


/**
 * Rotina Importação Censo para Clientes Novos
 *
 * Secretaria > Procedimentos > Censo Escolar > Importação > Alunos (Para Clientes Novos)
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("model/dbLayoutReader.model.php");
require_once("model/dbLayoutLinha.model.php");

/**
 * Função que verifica se o arquivo de LOG possui mais de uma linha.
 *
 * @param  String $sLog  => Arquivo do LOG
 * @return boolean true  => Se existir mais de uma linha
 *                 false => Se existir uma linha
 *
 * @author Thiago A. de Lima - thiago.lima@dbseller.com.br
 */
function webChecaLog($sLog) {

  $iContador   = 0;
  $pArquivoLog = fopen($sLog, "r");

  $lVazio      = true;

  while (!feof($pArquivoLog)) {

    $sLinhaArquivo = fgets($pArquivoLog, 2000);
    if (trim($sLinhaArquivo) != "") {
      $iContador++;
    }

    if ($iContador > 1) {
      $lVazio = false;
      break;
    }
  }

  if (!$lVazio) {

    return true;
  } else {
    return false;
  }

}

db_postmemory($HTTP_POST_VARS);

$oDaoEscola = db_utils::getdao('escola');
$iEscola    = db_getsession("DB_coddepto");
$iAnoAtual  = date("Y",db_getsession("DB_datausu"));
$oPost      = db_utils::postMemory($_POST);
$oFile      = db_utils::postMemory($_FILES);

if (isset($oPost->importar)) {

  $lErro = false;

  try {

    db_inicio_transacao();
    if (isset($oPost->codigoinep_banco) && trim($oPost->codigoinep_banco) != "") {
      $iCodigoInepEscola = $oPost->codigoinep_banco;
    } else {
      $iCodigoInepEscola = null;
    }
    
    if (isset($oPost->ano_opcao)) {
      $iAnoEscolhido = $oPost->ano_opcao;
    }

    if ($oPost->ano_opcao == 2011) {

      require_once("model/educacao/importacaoAtualizacaoAluno2011.model.php");
      $oCenso = new importacaoAtualizacaoAluno2011($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola, 96);
    } elseif ($oPost->ano_opcao == 2010) {

      require_once("model/educacao/importacaoAtualizacaoAluno2010.model.php");
      $oCenso = new importacaoAtualizacaoAluno2010($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola, 98);
      $oCenso->lLayoutComPipe = false;
    } elseif ( $oPost->ano_opcao == 2012 || $oPost->ano_opcao == 2013 ) {
      
      db_app::import("educacao.censo.DadosCenso");
      db_app::import("educacao.censo.*");

      $iCodigoLayout = 184;
      
      if ( $oPost->ano_opcao == 2013 ) {
        $iCodigoLayout = 199;
      }
      
      $oCenso = new ImportacaoCenso2012($oFile->arquivo['tmp_name'], $iAnoEscolhido, $iCodigoInepEscola = null, $iCodigoLayout);
      $oCenso->lImportarAluno             = true;
      $oCenso->lIncluirAlunoNaoEncontrado = true;
      $oCenso->lImportarAlunoAtivo        = false;
    } else {

      db_msgbox("Arquivo do ano escolhido não existe!");
      db_fim_transacao(true);
      exit;
    }

    $oCenso->lIncluirAlunoNaoEncontrado = true;
    $oCenso->importarArquivo();
  } catch ( Exception $eException ) {

    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }

  db_fim_transacao($lErro);
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
		<tr>
			<td width="360" height="18">&nbsp;</td>
			<td width="263">&nbsp;</td>
			<td width="25">&nbsp;</td>
			<td width="140">&nbsp;</td>
		</tr>
	</table>
	<form name="form1" method="post" action="" enctype="multipart/form-data">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="100%" align="left" valign="top" bgcolor="#CCCCCC"><br>
					<center>
						<fieldset style="width: 95%">
							<legend>
								<b>Importação de informações do CENSO ESCOLAR -> ALUNO</b>
							</legend>
							<table border="0" align="left">
								<tr>
									<td><br /> <b>Ano das informações do arquivo:</b> <select
										name="ano_opcao">

											<option value="<?=$iAnoAtual?>"
											<?=@$ano_opcao == $iAnoAtual ? "selected" : ""?>>
												<?=$iAnoAtual?>
											</option>

											<option value="<?=$iAnoAtual-1?>"
											<?=@$ano_opcao == $iAnoAtual-1 ? "selected" : ""?>>
												<?=$iAnoAtual-1?>
											</option>

									</select>
									</td>
								</tr>
								<tr>
									<td><b>Arquivo de importação do Censo:</b> <?db_input('arquivo', 50, @$Iarquivo, true, 'file', 3, "");?>
										<br /> <br />
									</td>
								</tr>
							</table>
						</fieldset>
					</center>
				</td>
			</tr>
			<tr>
				<td align="center">
				  <br>
				  <input name="importar" type="submit" id="importar" value="Importar" />
				</td>
			</tr>
		</table>
	</form>
	<?php
	    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
</body>
</html>
<?php
if (isset($oPost->importar)) {

  if ($lErro) {
    db_msgbox(str_replace("\n","\\n",$sMsgErro));
  } else {

    if (webChecaLog($oCenso->sNomeArquivoLog)) {
    ?>
      <script>
        var sEndereco = 'edu4_importacaoatualizacaoaluno002.php?sArquivoErro=<?=$oCenso->sNomeArquivoLog?>';
        var oJanela   = window.open(sEndereco,'', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        oJanela.moveTo(0,0);
      </script>
    <?php
    } else {
      db_msgbox("Importação dos Dados Realizada com Sucesso.");
    }
  }
  db_redireciona("edu4_importaralunoscenso001.php");
}
?>