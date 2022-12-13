<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcgm = new cl_cgm;
if(isset($campos)==false){
  $campos = "
    cgm.z01_numcgm, z01_nome, z01_ender, z01_munic, z01_uf, z01_cep, z01_email
  ";
}

$sAndNumCgm = "";
if(@$numcgm != ""){
  $sAndNumCgm = " and cgm.z01_numcgm <> ".$numcgm;
}

if(@$z01_cgc != ""){
  $clnome = new cl_cgm;
  $sql = $clnome->sql_query("",$campos,""," z01_cgccpf = '".$z01_cgc."' and z01_cgccpf <> '00000000000000' $sAndNumCgm");
  $result = $clnome->sql_record($sql);
  if($clnome->numrows > 0){
    db_fieldsmemory($result,0);
    echo "<script>parent.alert('AVISO! \\n CNPJ já cadastrado no CGM \\n NUMCGM : $z01_numcgm\\nNOME : $z01_nome ')</script>";
    echo "<script>parent.document.form1.z01_cgc.value=''</script>";
    echo "<script>parent.document.form1.z01_cgc.focus()</script>";
    exit;
  }else{
    exit;
  }
} 
if(@$z01_cpf != ""){
  $clnome = new cl_cgm;
  $sql = $clnome->sql_query("",$campos,""," z01_cgccpf = '".$z01_cpf."' and z01_cgccpf <> '00000000000' $sAndNumCgm");
  $result = $clnome->sql_record($sql);
  if($clnome->numrows > 0){
    db_fieldsmemory($result,0);
    echo "<script>parent.alert('AVISO! \\n CPF já cadastrado no CGM \\n NUMCGM : $z01_numcgm')</script>";
    echo "<script>parent.document.form1.z01_cpf.value=''</script>";
    echo "<script>parent.document.form1.z01_cpf.focus()</script>";
    exit;
  }else{
    exit;
  }
} 
if(@$nome != ""){
  $clnome = new cl_cgm;
  $sql = $clnome->sql_query("",$campos,""," z01_nome = '$nome' $sAndNumCgm");
  $result = $clnome->sql_record($sql);
  if($clnome->numrows > 0){
    db_fieldsmemory($result,0);
    echo "<script>parent.alert('AVISO! \\n Nome já cadastrado no CGM \\n NUMCGM : $z01_numcgm')</script>";
    exit;
  }else{
    exit;
  }
}
?>