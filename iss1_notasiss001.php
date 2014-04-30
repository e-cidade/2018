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
require_once("classes/db_notasiss_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/issqn/NotaFiscalISSQN.model.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$clnotasiss = new cl_notasiss;
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

  <?php 
    include("forms/db_frmnotasiss.php");
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); 
  ?>

</body>
</html>
<?php
if ( (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir" ) {

  db_inicio_transacao();

  try {

    /**
     * Valida se ja nao tem tipo de nota cadastrado, campo q09_nota 
     */
    $oDaoNotaIss    = db_utils::getDao('notasiss');
    $sWhereTipoNota = "q09_nota = '{$q09_nota}'";
    $sSqlTipoNota   = $oDaoNotaIss->sql_query_file(null, 'q09_nota', null, $sWhereTipoNota);
    $rsTipoNota     = db_query($sSqlTipoNota);

    if ( !$rsTipoNota ) {
      throw new DBException("Erro ao validar Inclusão de Nota de ISSQN");
    } 

    if ( pg_num_rows($rsTipoNota) > 0 ) {
      throw new BusinessException("Não foi possivel incluir.\\nTipo de nota: {$q09_nota} já cadastrado.");
    }

    /**
     * Inclui nota 
     */
    $oNota = new NotaFiscalISSQN();
    $oNota->setTipo($q09_nota);
    $oNota->setGrupo($q09_gruponotaiss);
    $oNota->setDescricao($q09_descr);
    $oNota->salvar();

    db_fim_transacao();
    db_msgbox("Usuário: \\n\\nInclusão efetuada com sucesso.\\n\\n Código do Tipo de Nota: " . $oNota->getCodigo() );
    db_redireciona();

  } catch(Exception $oException) {

    db_msgbox($oException->getMessage());
    db_fim_transacao(true);
  }

}
?>