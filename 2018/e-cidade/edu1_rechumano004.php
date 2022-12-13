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
include("classes/db_aluno_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censodistrito_classe.php");
include("classes/db_censoorgreg_classe.php");
include("classes/db_censolinguaindig_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clescola = new cl_escola;
$claluno = new cl_aluno;
$clcensouf = new cl_censouf;
$clcensomunic = new cl_censomunic;
$clcensodistrito = new cl_censodistrito;
$clcensoorgreg = new cl_censoorgreg;
$clcensolinguaindig = new cl_censolinguaindig;
if($campo=="end"){
 $campomunic = "ed20_i_censomunicend";
 $campouf = "ed20_i_censoufender";
}elseif($campo=="nat"){
 $campomunic = "ed20_i_censomunicnat";
 $campouf = "ed20_i_censoufnat";
}elseif($campo=="cert"){
 $campomunic = "ed20_i_censomuniccert";
 $campouf = "ed20_i_censoufcert";
}elseif($campo=="ender"){
 $campomunic = "ed20_i_censomunicender";
 $campouf = "ed20_i_censoufender";
}
if(isset($censouf1)){
 ?>
 <script>
 M = parent.document.form1.<?=$campomunic?>;
 for(i=0;i<M.length;i++){
  M.options[i] = null;
  i--;
 }
 </script>
 <?
 if($censouf1==""){
  ?>
  <script>
  parent.document.form1.elements["<?=$campomunic?>"].options[0] = new Option("Selecione o Estado"," ");
  </script>
  <?
 }else{
  ?>
  <script>
  parent.document.form1.elements["<?=$campomunic?>"].options[0] = new Option(""," ");
  </script>
  <?
  $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $censouf1"));
  for($x=0;$x<$clcensomunic->numrows;$x++){
   db_fieldsmemory($result_munic,$x);
   ?>
   <script>
   parent.document.form1.elements["<?=$campomunic?>"].options[<?=($x+1)?>] = new Option("<?=$ed261_c_nome?>",<?=$ed261_i_codigo?>);
   </script>
   <?
  }
 }
}
if(isset($nacionalidade1)){
 $result = $clrechumano->sql_record($clrechumano->sql_query_file("","ed20_i_codigo",""," ed20_i_codigo = $nacionalidade1 AND ed20_i_censomuniccert is null AND ed20_i_censoufcert is null AND ed20_c_certidaotipo = '' AND ed20_c_certidaonum = '' AND ed20_c_certidaolivro = '' AND ed20_c_certidaofolha = '' AND ed20_c_certidaocart = '' AND ed20_c_certidaodata is null AND ed20_i_censoufident is null AND ed20_i_censoorgemiss is null "));
 if($clrechumano->numrows==0){
  db_msgbox("Quando o aluno tiver nacionalidade Estrangeira, os campos referentes a Certidão e Identidade não devem ser informados (Aba Documentos).");
  ?>
  <script>
   parent.document.form1.ed20_i_nacionalidade.value = 1;
  </script>
  <?
 }
}
?>