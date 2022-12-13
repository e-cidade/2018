<?php

/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("agu4_impdadoscoletores_classe.php"));

$oPost      = db_utils::postMemory($_POST);
$oFiles     = db_utils::postMemory($_FILES);
$iDBUsuario = db_getsession('DB_id_usuario');
$dData      = date("d/m/Y");
$sHora      = date("H:i");

db_inicio_transacao();

?>

<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <form name="form1">
      <?php

        if (empty($oFiles->arquivo_importacao)) {

          db_msgbox("Não foi possível processar o arquivo de importação.");
          exit;
        }

        $arquivo_importacao = $oFiles->arquivo_importacao['tmp_name'];

        db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
                db_getsession("DB_anousu")    , db_getsession("DB_instit"));

        $clImportaDadosColetor = new cl_importaDadosColetor();

        $arrayArquivos = $clImportaDadosColetor->lerZIP($arquivo_importacao);
        
        $arquivoTXT    = $clImportaDadosColetor->getArquivoTxt($arrayArquivos);
        
        if ( $clImportaDadosColetor->iErroStatus == 0 ) {
        
          db_msgbox($clImportaDadosColetor->sErroMsg);
          exit;
        }

        $clImportaDadosColetor->descompactaZIP($arquivo_importacao, $oPost->x49_sequencial);
        
        if ($clImportaDadosColetor->iErroStatus == 0) {
        
          db_msgbox($clImportaDadosColetor->sErroMsg);
          exit;
        }

        $clImportaDadosColetor->leArquivoTXT($arquivoTXT, $oPost->x49_sequencial);

        if ($clImportaDadosColetor->iErroStatus == 1) {

          $clImportaDadosColetor->readFile();
          $clImportaDadosColetor->comparaRegistrosArquivo($oPost->x49_sequencial);

          if ($clImportaDadosColetor->iErroStatus == 1) {

            $clImportaDadosColetor->mudaSituacaoExportacao($oPost->x49_sequencial);
            
            if ($clImportaDadosColetor->iErroStatus == 0) {
            
              db_msgbox($clImportaDadosColetor->sErroMsg);
              exit;
            }
            
            $sMotivo = "Importação de dados do coletor";
            
            $clImportaDadosColetor->geraSituacaoExportacao($oPost->x49_sequencial, $iDBUsuario, $dData, $sHora, $sMotivo);
            
            if ($clImportaDadosColetor->iErroStatus == 0) {
            
              db_msgbox($clImportaDadosColetor->sErroMsg);
              exit;
            }
            
            $iConfExcessoTipo = $clImportaDadosColetor->getConfExcesso(date("Y"));
            $iCodRecExcesso   = $clImportaDadosColetor->getCodReceita($iConfExcessoTipo);
            
            db_criatermometro('termometro', 'Concluido...', 'blue', 1);
            flush();
            
            for ($i = 0; $i < $clImportaDadosColetor->numLinhas; $i ++) {
            
              db_atutermometro($i, $clImportaDadosColetor->numLinhas, "termometro", 1, "Processando Matricula " . $clImportaDadosColetor->getCodMatricula($i). " (" . ($i + 1) . "/ $clImportaDadosColetor->numLinhas) ...   ");
            
              $clImportaDadosColetor->geraDadosImportacao($clImportaDadosColetor->getCodLeitura($i), $i);
            
              if ($clImportaDadosColetor->iErroStatus == 0) {
            
                db_msgbox($clImportaDadosColetor->sErroMsg);
                exit;
              }
            
              $clImportaDadosColetor->alteraLeitura($clImportaDadosColetor->getLeituraExportacao($clImportaDadosColetor->getCodLeitura($i)));
            
              if ($clImportaDadosColetor->iErroStatus == 0) {
            
                db_msgbox($clImportaDadosColetor->sErroMsg);
                exit;
              }
            
              if ($clImportaDadosColetor->getValorExcessoCalc($i) > 0) {
            
                $clImportaDadosColetor->geraOperacaoExcesso($iConfExcessoTipo, $iCodRecExcesso);
            
                if ($clImportaDadosColetor->iErroStatus == 0) {
            
                  db_msgbox($clImportaDadosColetor->sErroMsg);
                  exit();
                }
              }
            
              // lança ocorrencia quando leitura for adequada
              if ($clImportaDadosColetor->getLeituraReal($i) && $clImportaDadosColetor->getDataLeituraReal($i)) {
            
                if ( !$clImportaDadosColetor->lancarOcorrencia($clImportaDadosColetor->getCodMatricula($i)    ,
                                                               $clImportaDadosColetor->getDataLeituraAtual($i),
                                                               $clImportaDadosColetor->getLeitura($i)         ,
                                                               $clImportaDadosColetor->getDataLeituraReal($i) ,
                                                               $clImportaDadosColetor->getLeituraReal($i)) ) { 
                  
                  db_msgbox($clImportaDadosColetor->sErroMsg);
                  exit;
                }
              }
            
              if ($clImportaDadosColetor->getLeituraColetada($i) == "1") {
                
                $clImportaDadosColetor->geraReciboWeb($clImportaDadosColetor->getCodLeitura($i));
                
                if ($clImportaDadosColetor->iErroStatus == 0) {
            
                  db_msgbox($clImportaDadosColetor->sErroMsg);
                  exit;
                }
                
                $clImportaDadosColetor->geraReciboPaga($clImportaDadosColetor->getCodLeitura($i));
            
                if ($clImportaDadosColetor->iErroStatus == 0) {
            
                  db_msgbox($clImportaDadosColetor->sErroMsg);
                  exit;
                }      
                
                $clImportaDadosColetor->geraReciboCodBar();
                
                if ($clImportaDadosColetor->iErroStatus == 0) {
            
                  db_msgbox($clImportaDadosColetor->sErroMsg);
                  exit;
                }
              }
            
              $clImportaDadosColetor->importaFoto($oPost->x49_sequencial,
                                                  $clImportaDadosColetor->getCodMatricula($i),
                                                  $clImportaDadosColetor->getCodLeitura($i),
                                                  $arrayArquivos,
                                                  $conn);
            
              if($clImportaDadosColetor->iErroStatus == 0) {
            
                db_msgbox($clImportaDadosColetor->sErroMsg);
                exit;
              }
            }
          }
        }

        if ($clImportaDadosColetor->iErroStatus == 0) {

          db_fim_transacao(true);
          db_msgbox($clImportaDadosColetor->sErroMsg);
          db_redireciona("agu4_impdadoscoletores_001.php");
        
        } else {
          
          db_fim_transacao();
          db_msgbox("Importação realizada com sucesso.");
          db_redireciona("agu4_impdadoscoletores_001.php");
        }
      ?>
    </form>
  </body>
</html>