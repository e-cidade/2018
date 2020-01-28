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
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("model/dbLayoutLinha.model.php"));
require_once(modification("model/educacao/censo/DadosCenso.model.php"));
require_once(modification("model/educacao/censo/DadosCensoDocente.model.php"));
require_once(modification("model/educacao/censo/DadosCensoEscola.model.php"));
require_once(modification("model/educacao/censo/DadosCensoAluno.model.php"));
require_once(modification("model/Avaliacao.model.php"));
require_once(modification("model/AvaliacaoGrupo.model.php"));
require_once(modification("model/AvaliacaoPergunta.model.php"));

define("MENSAGEM_ATUALIZA_CADASTROCENSO","educacao.escola.edu4_atualizacadastrocenso001.");
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

db_postmemory($_POST);

$oDaoEscola = db_utils::getdao('escola');
$iEscola    = db_getsession("DB_coddepto");
$iAnoAtual  = date("Y");
$oPost      = db_utils::postMemory($_POST);
$oFile      = db_utils::postMemory($_FILES);

if (isset($oPost->importar)) {

  $lErro = false;
  db_inicio_transacao();

  try {

    if (isset($oPost->codigoinep_banco)) {
	    $iCodigoInepEscola = $oPost->codigoinep_banco;
  	}

  	if (isset($oPost->ano_opcao)) {
  	  $iAnoEscolhido = $oPost->ano_opcao;
  	}

    $aErrosArquivo = array(
      1 => 'Tamanho máximo do arquivo excedido conforme diretiva do php.ini.',
      2 => 'Tamanho máximo do arquivo excedido conforme especificado no formulário HTML.',
      3 => 'Arquivo corrompido.',
      4 => 'Arquivo não foi informado.',
      6 => 'Diretório temporario não encontrado.',
      7 => 'Diretório sem permissão de escrita.',
      8 => 'Upload do arquivo interrompido.'
    );

    if ($oFile->arquivo['error'] != 0 ) {

      $oErro        = new stdClass();
      $oErro->sErro = $aErrosArquivo[$oFile->arquivo['error']];
      throw new Exception( _M( MENSAGEM_ATUALIZA_CADASTROCENSO . "erro_arquivo", $oErro) );
    }

  	switch ($oPost->ano_opcao) {

  	  case 2011:

    	  require_once(modification("model/educacao/importacaoCenso2011.model.php"));
    	  $oCenso = new importacaoCenso2011($oFile->arquivo['tmp_name'],
    	                                    $iAnoEscolhido,
                                          $iCodigoInepEscola,
                                          96
    	                                   );
        break;
  	  case 2012:

        require_once(modification("model/educacao/censo/ImportacaoCenso2012.model.php"));
        $oCenso = new importacaoCenso2012($oFile->arquivo['tmp_name'],
                                          $iAnoEscolhido,
                                          $iCodigoInepEscola,
                                          184
                                         );
        break;

      /**
       * Utilizada a classe de importacao de 2012 para 2013, pois os dados sao importados da mesma forma, sem necessidade
       * de alteracoes
       */
      case 2013:

      	require_once(modification("model/educacao/censo/ImportacaoCenso2012.model.php"));
      	$oCenso = new importacaoCenso2012($oFile->arquivo['tmp_name'],
														        			$iAnoEscolhido,
														        			$iCodigoInepEscola,
														        			199
														        	   );
      case 2014:

        require_once(modification("model/educacao/censo/ImportacaoCenso2012.model.php"));
        $oCenso = new importacaoCenso2012($oFile->arquivo['tmp_name'],
                                          $iAnoEscolhido,
                                          $iCodigoInepEscola,
                                          219
                                         );
        	break;
	  	case 2010:

	  	  require_once(modification("model/educacao/importacaoCenso2010.model.php"));
	  	  $oCenso = new importacaoCenso2010($oFile->arquivo['tmp_name'],
	  	                                    $iAnoEscolhido,
                                          $iCodigoInepEscola,
                                          98
	  	                                   );
	  	  break;
      case 2015:

        $oCenso = new importacaoCenso2015($oFile->arquivo['tmp_name'],
                                          $iAnoEscolhido,
                                          $iCodigoInepEscola,
                                          226
                                        );
        $oCenso->setCodigoEscola( $iEscola );
        break;
  	  default:

  	    db_msgbox("Arquivo do ano escolhido não existe!");
  	    db_redireciona("edu4_atualizacadastrocenso001.php");

  	}

  	if (isset($oPost->escolacenso)) {
  	  $oCenso->lImportarEscola = true;
  	}

  	if (isset($oPost->turma)) {
  	  $oCenso->lImportarTurma = true;
  	}

  	if (isset($oPost->docente)) {
  	  $oCenso->lImportarDocente = true;
  	}

  	if (isset($oPost->aluno)) {
  	  $oCenso->lImportarAluno = true;
  	}

  	/**
  	 * Verifica parâmetro da Secretaria para ver se :
  	 *    1 - Importar todos registros do arquivo ou
  	 *    2 - Importar apenas registros ativos na escola
  	 */
  	$oDaoSecParam = db_utils::getDao("sec_parametros");
  	$sSqlSecParam = $oDaoSecParam->sql_query_file(1);
  	$rsSecParam   = $oDaoSecParam->sql_record($sSqlSecParam);

  	if ($oDaoSecParam->numrows == 1) {

  	  $iImportCenso = db_utils::fieldsMemory($rsSecParam, 0)->ed290_importcenso;
  	  $oCenso->lImportarAlunoAtivo = $iImportCenso == 1 ? false : true;
  	}

	  $oCenso->lIncluirAlunoNaoEncontrado = false;
  	$oCenso->importarArquivo();

  } catch ( Exception $eException ) {

  	$lErro    = true;
  	$sMsgErro = $eException->getMessage();
  }

  db_fim_transacao( $lErro );
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
 <body>
  <div class="container">
   <form name="form1" enctype="multipart/form-data" method="post" action="" >
     <fieldset>
       <legend>
         Importação de informações do CENSO ESCOLAR -> ESCOLA / TURMA / DOCENTE / ALUNO
       </legend>
         <?php
         $sSqlEscola       = $oDaoEscola->sql_query("","ed18_c_codigoinep","","ed18_i_codigo = $iEscola");
         $rsEscola         = $oDaoEscola->sql_record($sSqlEscola);
         $iCodigoInepBanco = db_utils::fieldsMemory($rsEscola,0)->ed18_c_codigoinep;
         ?>
       <table class="form-container">
          <tr>
           <td>
             Ano das informações do arquivo:
           </td>
           <td>
             <?php
             $ano_opcao = isset( $ano_opcao ) ? $ano_opcao : "";
             ?>
             <select name="ano_opcao" >
               <option value="<?=$iAnoAtual?>" <?=$ano_opcao==$iAnoAtual?"selected":""?>><?=$iAnoAtual?></option>
               <option value="<?=$iAnoAtual-1?>" <?=$ano_opcao==$iAnoAtual-1?"selected":""?>><?=$iAnoAtual-1?></option>
             </select>
           </td>
          </tr>
          <tr>
            <td>
              Código INEP:
            </td>
            <td>
              <input type="text" name="codigoinep_banco" value="<?=$iCodigoInepBanco?>" size="8" readonly style="background:#deb887">
            </td>
          </tr>
          <tr>
            <td>
              Dados para importar:
            </td>
            <td>
              <input  id="escolacenso" type="checkbox" name="escolacenso" value="escolacenso" <?=!isset($iEscolaCenso)?"":"checked"?>>
              <label for="escolacenso">Escola</label>
              <input type="checkbox" id="turma" name="turma" value="turma" <?=!isset($iTurma)?"":"checked"?>>
              <label for="turma">Turmas</label>
              <input type="checkbox" id="docente" name="docente" value="docente" <?=!isset($iDocente)?"":"checked"?>>
              <label for="docente">Docentes</label>
              <input type="checkbox" id="aluno" name="aluno" value="aluno" <?=!isset($iAluno)?"":"checked"?>>
              <label for="aluno">Alunos</label>
            </td>
          </tr>
          <tr>
            <td>
              Arquivo de importação do Censo:
            </td>
            <td>
              <?php
              db_input( 'arquivo', 50, '', true, 'file', 3 );
              ?>
            </td>
          </tr>
         <tr>
           <td colspan="2">
             <?php
             if (trim($iCodigoInepBanco) == "") : ?>
               <div style="color:red; margin-top:8px;">
                 * Código INEP desta escola não informado no sistema. Operação Não Permitida.
                 <br>
                 <a href='edu1_escolaabas002.php'>Informar Código INEP</a>
               </div>
             <?php endif ?>
           </td>
         </tr>
       </table>
     </fieldset>
     <input type="submit" name="importar" value="Importar" onclick="js_criaObj();"
       <?=$iCodigoInepBanco == "" || isset($importar)?"disabled":""?>/>
   </form>
  </div>
  <?php
   db_menu();
  ?>
 </body>
</html>

<script type="text/javascript">
(function() {

  if ( $('msgbox') ) {
    js_removeObj($('msgbox'));
  }

  function js_criaObj() {
    js_divCarregando("Aguarde, importando dados...", "msgbox");
  }
})();

</script>

<?php
if( isset( $oPost->importar ) ) {

  if ($lErro) {
    db_msgbox(str_replace("\n","\\n",$sMsgErro));
  } else {

  	if (webChecaLog($oCenso->sNomeArquivoLog)) {

      ?>
      <script type="text/javascript">

        // Remove loading da página no e-Cidade v3
        if ($('msgbox') !== null) {
          js_removeObj( "msgbox" );
        }

        $sEndereco = 'edu4_atualizacadastrocenso002.php?sArquivoErro=<?=$oCenso->sNomeArquivoLog?>';
        jan = window.open($sEndereco,'',
                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        jan.moveTo(0,0);
      </script>
    <?php
  	} else {
  	  db_msgbox("Importação dos Dados Realizada com Sucesso.");
  	}
  }

  db_redireciona("edu4_atualizacadastrocenso001.php");
}