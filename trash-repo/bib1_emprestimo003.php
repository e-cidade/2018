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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_exemplar_classe.php");
db_postmemory($HTTP_POST_VARS);
$clexemplar = new cl_exemplar;
$result = $clexemplar->sql_record($clexemplar->sql_query("","bi23_codigo,bi06_titulo",""," bi23_codbarras = $bi23_codbarras"));
if($clexemplar->numrows==0){
 ?>
 <script>
 parent.document.form1.bi23_codbarras.value = "";
 parent.document.form1.bi23_codbarras.focus();
 </script>
 <?
 db_msgbox("Código de Barras $bi23_codbarras não encontrado.");
}else{
 db_fieldsmemory($result,0);
 ?>
 <script>
 parent.document.form1.bi23_codigo.value = <?=$bi23_codigo?>;
 parent.document.form1.bi06_titulo.value = "<?=$bi06_titulo?>";
 parent.$('db_lanca_emprestimo').onclick = parent.js_insSelectemprestimo;
 parent.$('db_lanca_emprestimo').click();
 parent.document.form1.bi23_codbarras.value = "";
 parent.document.form1.bi23_codbarras.focus();
 </script>
 <?
}
?>