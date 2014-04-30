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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_escola_classe.php");
include("classes/db_escolaproc_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censodistrito_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clescola = new cl_escola;
$clescolaproc = new cl_escolaproc;
$clcensouf = new cl_censouf;
$clcensomunic = new cl_censomunic;
$clcensodistrito = new cl_censodistrito;
if(isset($censouf)){
 ?>
 <script>
 M = parent.document.form1.ed82_i_censomunic;
 D = parent.document.form1.ed82_i_censodistrito;
 for(i=0;i<M.length;i++){
  M.options[i] = null;
  i--;
 }
 for(i=0;i<D.length;i++){
  D.options[i] = null;
  i--;
 }
 </script>
 <?
 if($censouf==""){
  ?>
  <script>
  parent.document.form1.elements["ed82_i_censomunic"].options[0] = new Option("Selecione o Estado","");
  parent.document.form1.elements["ed82_i_censodistrito"].options[0] = new Option("Selecione a Cidade","");
  </script>
  <?
 }else{
  ?>
  <script>
  parent.document.form1.elements["ed82_i_censomunic"].options[0] = new Option("","");
  parent.document.form1.elements["ed82_i_censodistrito"].options[0] = new Option("Selecione a Cidade","");
  </script>
  <?
  $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $censouf"));
  for($x=0;$x<$clcensomunic->numrows;$x++){
   db_fieldsmemory($result_munic,$x);
   ?>
   <script>
   parent.document.form1.elements["ed82_i_censomunic"].options[<?=($x+1)?>] = new Option("<?=$ed261_c_nome?>",<?=$ed261_i_codigo?>);
   </script>
   <?
  }
 }
}
if(isset($censomunic)){
 ?>
 <script>
 D = parent.document.form1.ed82_i_censodistrito;
 for(i=0;i<D.length;i++){
  D.options[i] = null;
  i--;
 }
 </script>
 <?
 if($censomunic==""){
  ?>
  <script>
  parent.document.form1.elements["ed82_i_censodistrito"].options[0] = new Option("Selecione a Cidade","");
  </script>
  <?
 }else{
  ?>
  <script>
  parent.document.form1.elements["ed82_i_censodistrito"].options[0] = new Option("","");
  </script>
  <?
  $result_distrito = $clcensodistrito->sql_record($clcensodistrito->sql_query("","ed262_i_codigo,ed262_c_nome","ed262_c_nome","ed262_i_censomunic = $censomunic"));
  for($x=0;$x<$clcensodistrito->numrows;$x++){
   db_fieldsmemory($result_distrito,$x);
   ?>
   <script>
   parent.document.form1.elements["ed82_i_censodistrito"].options[<?=($x+1)?>] = new Option("<?=$ed262_c_nome?>",<?=$ed262_i_codigo?>);
   </script>
   <?
  }
 }
}
?>