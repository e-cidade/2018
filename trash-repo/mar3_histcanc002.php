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
include("classes/db_cgm_classe.php");
include("classes/db_marca_classe.php");
include("classes/db_localmarca_classe.php");
include("classes/db_cancmarca_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
$clcgm = new cl_cgm;
$clmarca = new cl_marca;
$cllocalmarca = new cl_localmarca;
$clcancmarca = new cl_cancmarca;
$clcgm->rotulo->label();
$clmarca->rotulo->label();
$cllocalmarca->rotulo->label();
$clcancmarca->rotulo->label();
if(isset($data_ini) && isset($data_fim)){
 if($escolha=="Cancelamentos"){
  $where = "ma03_c_tipo = 'C' and ";
 }elseif($escolha=="Reativações"){
  $where = "ma03_c_tipo = 'R' and ";
 }else{
  $where = "";
 }
 $where .= "ma03_d_data between '$data_ini' and '$data_fim'";
 $titulo = " - Período: ".db_formatar($data_ini,'d')." até ".db_formatar($data_fim,'d')." - Tipo: $escolha";
 $campos = "a.z01_nome as atual,
            a.z01_numcgm as cgmatual,
            protprocesso.p58_requer as req,
            protprocesso.p58_numcgm as cgmreq,
            protprocesso.p58_dtproc as dataproc,
            cancmarca.*";
 $sql = $clcancmarca->sql_query("",$campos,"ma03_d_data,ma03_i_marca",$where);
 $result = $clcancmarca->sql_record($sql);
  ?>
  <table width="100%" border="1" cellspacing="0" cellpading="3" bordercolor="#004040">
  <tr>
   <td align="center"><b>Consulta de Cancelamentos de Marcas <?=$titulo?></b></td>
  </tr>
  <tr>
   <td colspan="5" height="1" bgcolor="#cccccc"></td>
  </tr>
   <?
   $cor1="#E796A4";
   $cor2="#97B5E6";
   $cor=$cor1;
   $lcor1="#F7738D";
   $lcor2="#78A0EB";
   $lcor=$lcor1;
   if($clcancmarca->numrows!=0){
    for($i=0;$i<$clcancmarca->numrows;$i++){
     db_fieldsmemory($result,$i);
     if($cor==$cor1){
      $cor=$cor2;
      $lcor=$lcor2;
     }else{
      $cor=$cor1;
      $lcor=$lcor1;
     }
     ?>
     <tr bgcolor="<?=$cor?>">
      <td>
       <table border="0" cellspacing="0" width="100%">
        <tr>
         <td><b><?=$Lma03_d_data?></b> <?=db_formatar($ma03_d_data,'d')?></td>
         <td><b><?=$Lma03_i_marca?></b> <?=$ma03_i_marca?></td>
         <td width="60%"><b>Proprietário Atual:</b> <?=$cgmatual?> <?=$atual?></td>
        </tr>
        <tr>
         <td colspan="3" height="1" bgcolor="<?=$lcor?>"></td>
        </tr>
        <tr>
         <td colspan="3">
          <b>Requerente:</b> <?=$cgmreq?> <?=$req?><br>
          <b><?=$Lma03_i_codproc?></b> <?=$ma03_i_codproc?> - <?=db_formatar($dataproc,'d')?><br>
          <b><?=$Lma03_t_obs?></b> <?=$ma03_t_obs?><br>
          <b>Tipo:</b> <?=$ma03_c_tipo=="C"?"Cancelamento":"Reativação"?>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td colspan="5" height="1" bgcolor="#cccccc"></td>
     </tr>
    <?}?>
    <table>
   <?}else{?>
    <tr>
     <td colspan="3" align="center">Nenhum cancelamento para este período</td>
    </tr>
    </table>
   <?}
  }else{
   echo "<div align='center'><br>Digite o período, selecione o tipo e clique em Pesquisar</div>";
  }
?>
<br><br>
</body>
</html>