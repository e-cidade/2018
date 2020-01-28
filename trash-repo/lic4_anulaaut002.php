<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_empautoriza_classe.php");

$clpcorcam     = new cl_pcorcam;
$clpcparam     = new cl_pcparam;
$clpcproc      = new cl_pcproc;
$clempautoriza = new cl_empautoriza;

$clpcorcam->rotulo->label();
$clpcproc->rotulo->label();

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

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
     <?
        include("forms/db_frmlicanulaaut.php");
       ?>  
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<?
if(isset($e54_autori) && trim($e54_autori)!=""){	   
    $qry = "e54_autori=$e54_autori";
    echo "<script>iframe_solicitem.location.href = 'lic4_anulaaut003.php?$qry';</script>";

}
/*
if(isset($pc80_codproc) && trim($pc80_codproc)!="" && !isset($anul)){
  $result_itens  = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_geraut(null,null,"distinct pc11_codigo,pc81_codprocitem,pc22_orcamitem,pc01_codmater,pc01_descrmater,pc13_coddot,pc13_codigo,z01_numcgm,z01_nome,pc23_orcamforne,pc23_valor,pc23_quant,pc13_quant,pc13_anousu,pc11_codigo","z01_numcgm,pc13_coddot,pc01_codmater,pc81_codprocitem","pc81_codproc=$pc80_codproc and pc24_pontuacao=1"));
  $numrows_itens = $clpcorcamjulg->numrows;
}
if(isset($anul)){
  if(isset($e54_autori) && trim($e54_autori)!=""){
    $result_itens  = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct e54_autori,pc81_codproc,pc81_codprocitem,pc01_codmater,pc01_descrmater,e56_coddot,pc81_solicitem,z01_numcgm,z01_nome,e55_vltot,e55_quant","z01_numcgm,e56_coddot,pc01_codmater,pc81_codprocitem","e55_autori=$e54_autori "));
    $numrows_itens = $clempautitem->numrows;
  }else if(isset($pc80_codproc) && trim($pc80_codproc)!=""){
    $result_itens  = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct e54_autori,pc81_codproc,pc81_codprocitem,pc01_codmater,pc01_descrmater,e56_coddot,pc81_solicitem,z01_numcgm,z01_nome,e55_vltot,e55_quant","z01_numcgm,e56_coddot,pc01_codmater,pc81_codprocitem","pc81_codproc=$pc80_codproc and e54_anulad is null"));
    $numrows_itens = $clempautitem->numrows;
  }
}
if(isset($numrows_itens) && $numrows_itens>0){
echo "<script>
	document.form1.incluir.disabled=false;
      </script>";
}
*/
?>