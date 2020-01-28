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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_aidof_classe.php");
require_once("classes/db_aidofproc_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/fiscal/Aidof.model.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$claidof = new cl_aidof;
$claidofproc = new cl_aidofproc;
$db_opcao    = 22;
$db_botao    = false;

if ( isset($chavepesquisa) && !isset($oPost->db_opc) ) {

   $alt = false;
   $db_opcao = 2;
   $result = $claidof->sql_record($claidof->sql_query($chavepesquisa,"cgm.z01_nome as nomeinscr, a.z01_nome as z01_nome_grafica, *")); 
   db_fieldsmemory($result,0);
   $result_proc=$claidofproc->sql_record($claidofproc->sql_query(null,"*",null,"y02_aidof=$chavepesquisa"));

   if ($claidofproc->numrows>0){
    db_fieldsmemory($result_proc,0);
   }

   $result_ultalt=$claidof->sql_record($claidof->sql_query_file(null,"y08_codigo as codaidof","y08_codigo desc","y08_inscr=$y08_inscr"));
   db_fieldsmemory($result_ultalt,0);

   if ($y08_codigo==$codaidof){
     $db_opcao=2;
     $alt=false;
     $db_botao = true;
   }else{

     $db_opcao=3;
     $alt=true;
     $db_botao = false;
   }
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
<body bgcolor=#CCCCCC onLoad="a=1" >

  <?php
    include("forms/db_frmaidof.php");
  ?>

  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<?php
if( isset($db_opc) && $db_opc == "Alterar" ) {

  db_inicio_transacao();

  try {

    $oAidof = new Aidof($y08_codigo);

		$oAidof->setNota(new NotaFiscalISSQN($y08_nota));
    $oAidof->setEmpresa(new Empresa($y08_inscr));
    $oAidof->setProcesso($y02_codproc);
    $oAidof->setNumeroInicial($y08_notain);
    $oAidof->setNumeroFinal($y08_notafi);
    $oAidof->setGrafica(new Grafica($y08_numcgm));
    $oAidof->setQuantidadeSolicitada($y08_quantsol);
    $oAidof->setQuantidadeLiberada($y08_quantlib);
    $oAidof->setObservacoes($y08_obs);

    $oAidof->salvar();
    db_fim_transacao();

    $sMensagem = "Usu�rio: \\n\\nAltera��o efetuada com Sucesso\\nValores : ".$y08_codigo;
    db_msgbox($sMensagem);

    echo '<script>';
    echo "js_imprimir({$y08_codigo}, {$y08_inscr});";
    echo '</script>';

  } catch (Exception $oException) {

    db_msgbox($oException->getMessage());
    db_fim_transacao(true);
  }
  
}

if ( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>