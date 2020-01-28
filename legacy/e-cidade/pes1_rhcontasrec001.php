<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_rhcontasrec_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoRhcontasrec = new cl_rhcontasrec;
$db_opcao    = 1;
$db_botao    = true;
$sPosScripts = "";

$sMensagens = "recursoshumanos.pessoal.pes1_rhcontasrec.";

if (isset($incluir)) {

  db_inicio_transacao();

  try {

    $oDaoRhcontasrec->rh41_anousu = db_getsession("DB_anousu");
    $oDaoRhcontasrec->rh41_instit = db_getsession("DB_instit");

    $sSqlValidaUnique = $oDaoRhcontasrec->sql_query(null, $rh41_codigo, db_getsession("DB_instit"), db_getsession("DB_anousu"));
    $oDaoRhcontasrec->sql_record( $sSqlValidaUnique );

    if ($oDaoRhcontasrec->numrows > 0) {
      throw new BusinessException( _M($sMensagens . "recurso_duplicado") );
    }

    $oDaoRhcontasrec->incluir($rh41_conta, $rh41_codigo, db_getsession("DB_instit"), db_getsession("DB_anousu"));

    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoRhcontasrec->erro_msg . '");' . "\n";

    if ($oDaoRhcontasrec->erro_status == '0') {

      $db_botao = true;
      $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

      if ($oDaoRhcontasrec->erro_campo != "") {
        $sPosScripts .= "document.form1.{$oDaoRhcontasrec->erro_campo}.classList.add('form-error');\n";
        $sPosScripts .= "document.form1.{$oDaoRhcontasrec->erro_campo}.focus();\n";
      }
    } else {
      $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }

  } catch (Exception $e) {
    db_fim_transacao(false);
    
    $sPosScripts .= 'alert("' . $e->getMessage() . '");' . "\n";
    $oDaoRhcontasrec->erro_status = 0;
  }

}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh41_codigo", true, 1, "rh41_codigo", true);';

include("forms/db_frmrhcontasrec.php");
?>