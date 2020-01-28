<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("model/dbLayoutReader.model.php");
require_once("model/dbLayoutLinha.model.php");
require_once("model/educacao/censo/DadosCenso.model.php");
require_once("model/educacao/censo/DadosCensoDocente.model.php");
require_once("model/educacao/censo/DadosCensoEscola.model.php");
require_once("model/educacao/censo/DadosCensoAluno.model.php");
require_once("model/Avaliacao.model.php");
require_once("model/AvaliacaoGrupo.model.php");
require_once("model/AvaliacaoPergunta.model.php");

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
  db_inicio_transacao();
	
  try {  				   
	
    if (isset($oPost->codigoinep_banco)) {
	    $iCodigoInepEscola = $oPost->codigoinep_banco;
  	}	
  	
  	if (isset($oPost->ano_opcao)) {
  	  $iAnoEscolhido = $oPost->ano_opcao;
  	}
  	
  	switch ($oPost->ano_opcao) {
  	  
  	  case 2011: 
  
    	  require_once("model/educacao/importacaoCenso2011.model.php");
    	  $oCenso = new importacaoCenso2011($oFile->arquivo['tmp_name'],
    	                                    $iAnoEscolhido, $iCodigoInepEscola, 96 
    	                                   );	
        break;
  	  case 2012:
          
        require_once("model/educacao/censo/ImportacaoCenso2012.model.php");
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
        
        	require_once("model/educacao/censo/ImportacaoCenso2012.model.php");
        	$oCenso = new importacaoCenso2012($oFile->arquivo['tmp_name'],
															        			$iAnoEscolhido,
															        			$iCodigoInepEscola,
															        			199
															        	   );
        	break;
	  	case 2010:
	  			  
	  	  require_once("model/educacao/importacaoCenso2010.model.php");
	  	  $oCenso = new importacaoCenso2010($oFile->arquivo['tmp_name'],
	  	                                    $iAnoEscolhido, $iCodigoInepEscola, 98 
	  	                                   );	
	  	  break;	
  	  default:
  		
  	    db_msgbox("Arquivo do ano escolhido não existe!");
  	    exit;
  	  	
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
  	  $oCenso->lImportarAlunoAtivo = $iImportCenso == 1 ? false : true ;
  	}
  	
	  $oCenso->lIncluirAlunoNaoEncontrado = false; 
  	$oCenso->importarArquivo();
					
  } catch ( Exception $eException ) {
  	
  	$lErro    = true;
  	$sMsgErro = $eException->getMessage();
	
  }	
  //$lErro = true;
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
 <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <center>
   <form name="form1" enctype="multipart/form-data" method="post" action="" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
       <br><br>
       <center>
       <fieldset style="width:95%">
       <legend><b>Importação de informações do CENSO ESCOLAR -> ESCOLA / TURMA / DOCENTE / ALUNO</b></legend>
        <?
         $sSqlEscola       = $oDaoEscola->sql_query("","ed18_c_codigoinep","","ed18_i_codigo = $iEscola");
         $rsEscola         = $oDaoEscola->sql_record($sSqlEscola);         
         $iCodigoInepBanco = db_utils::fieldsMemory($rsEscola,0)->ed18_c_codigoinep;        
        ?>
         <table border="0" align="left">
          <tr>
           <td>
             <b>Ano das informações do arquivo:</b>
             <select name="ano_opcao" onchange="js_trocaano(this.value)">
              <option value="<?=$iAnoAtual?>" <?=@$ano_opcao==$iAnoAtual?"selected":""?>><?=$iAnoAtual?></option>
              <option value="<?=$iAnoAtual-1?>" <?=@$ano_opcao==$iAnoAtual-1?"selected":""?>><?=$iAnoAtual-1?></option>        
             </select><br>       
             <b>Código INEP:</b> <input type="text" name="codigoinep_banco" value="<?=$iCodigoInepBanco?>" size="8" 
                                        readonly style="background:#deb887">
           </td>
          </tr>
          <tr>
           <td>
            <input type="checkbox" name="escolacenso" 
                  value="escolacenso" <?=!isset($iEscolaCenso)?"":"checked"?>> <b>Escola</b>
            <input type="checkbox" name="turma" value="turma" <?=!isset($iTurma)?"":"checked"?>> <b>Turmas</b>
            <input type="checkbox" name="docente" value="docente" <?=!isset($iDocente)?"":"checked"?>> <b>Docentes</b>
            <input type="checkbox" name="aluno" value="aluno" <?=!isset($iAluno)?"":"checked"?>> <b>Alunos</b>
           </td>
          </tr>
          <tr>
           <td>
            <b>Arquivo de importação do Censo:</b>
            <?db_input('arquivo',50,@$Iarquivo,true,'file',3,"");?>          
           </td>
          </tr>
         </table>
        </fieldset>
       </center>
      </td>
     </tr>
     <tr>
      <td align="center">
       <?
        if (trim($iCodigoInepBanco) == "") {
      	
          echo "<font color=red><b>* Código INEP desta escola não informado no sistema. Operação Não Permitida.</b></font>";
          echo "&nbsp;&nbsp;<a href='edu1_escolaabas002.php'>Informar Código INEP</a>";
          echo "<br>";
        
        }
       ?>
        <input type="submit" name="importar" value="Importar" onClick="js_criaObj();"
         <?=$iCodigoInepBanco == "" || isset($importar)?"disabled":""?>/>
       
      </td>
     </tr>
    </table> 	
   </form>
  </center>
  <?
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
   db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
 </body>
</html>

<script>

  function js_criaObj() {
    js_divCarregando("Aguarde, importando dados...", "msgbox");
  }
 
</script>

<?
if (isset($oPost->importar)) {
  if ($lErro) {
    db_msgbox(str_replace("\n","\\n",$sMsgErro));	 
  } else {
  	
  	if (webChecaLog($oCenso->sNomeArquivoLog)) {
	?>
	  <script>
	   $sEndereco = 'edu4_atualizacadastrocenso002.php?sArquivoErro=<?=$oCenso->sNomeArquivoLog?>';	 
       jan = window.open($sEndereco,'',
    	                 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
       jan.moveTo(0,0);
      </script>
  <?
  	} else {
  	  db_msgbox("Importação dos Dados Realizada com Sucesso.");
  	}
  }
  
  db_redireciona("edu4_atualizacadastrocenso001.php");

}	
?>