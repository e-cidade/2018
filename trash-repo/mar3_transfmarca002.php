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
include("classes/db_transfmarca_classe.php");
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
$cltransfmarca = new cl_transfmarca;
$clcgm->rotulo->label();
$clmarca->rotulo->label();
$cllocalmarca->rotulo->label();
$cltransfmarca->rotulo->label();
if(isset($data_ini) && isset($data_fim)){
 if($escolha=="Todas"){
  $where = "";
  $opcao = "Todas";
 }else{
  $where = " ma02_i_marca = $valor and ";
  $opcao = "Por Marca n° $valor";
 }
 $where .= " ma02_d_data between '$data_ini' and '$data_fim' ";
 $titulo = " - Período: ".db_formatar($data_ini,'d')." até ".db_formatar($data_fim,'d')." - ".$opcao;
 $campos = "cgm1.z01_nome as ant,
            cgm4.z01_nome as novo,
            cgm3.z01_nome as atual,
            cgm3.z01_numcgm as cgmatual,
            protprocesso.p58_requer as req,
            protprocesso.p58_numcgm as cgmreq,
            protprocesso.p58_dtproc as dataproc,
            transfmarca.*";
 $sql = $cltransfmarca->sql_query("",$campos,"ma02_d_data,ma02_i_marca",$where);
 //die( ">>>>".$sql);
 $result = $cltransfmarca->sql_record($sql);
  ?>
  <table width="100%" border="1" cellspacing="0" cellpading="3" bordercolor="#004040">
  <tr>
   <td align="center"><b>Consulta de Transferências de Marcas <?=$titulo?></b></td>
  </tr>
   <?
   $cor1="#E796A4";
   $cor2="#97B5E6";
   $cor=$cor1;
   if($cltransfmarca->numrows!=0){
    for($i=0;$i<$cltransfmarca->numrows;$i++){
     db_fieldsmemory($result,$i);
     if($cor==$cor1){
      $cor=$cor2;
     }else{
      $cor=$cor1;
     }
     ?>
     <tr bgcolor="<?=$cor?>">
      <td>
       <table border="0" cellspacing="0" width="100%">
        <tr>
         <td><b><?=$Lma02_d_data?></b> <?=db_formatar($ma02_d_data,'d')?></td>
         <td><b><?=$Lma02_i_marca?></b> <?=$ma02_i_marca?></td>
         <td width="60%"><b>Proprietário Atual:</b> <?=$cgmatual?> <?=$atual?></td>
        </tr>
        <tr>
         <td colspan="3" height="2" bgcolor="#cccccc"></td>
        </tr>
        <tr>
         <td colspan="3">
          <b>Requerente:</b> <?=$cgmreq?> <?=$req?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <b><?=$Lma02_i_codproc?></b> <?=$ma02_i_codproc?> - <?=db_formatar($dataproc,'d')?><br>
          <b><?=$Lma02_i_propant?></b> <?=$ma02_i_propant?> <?=$ant?><br>
          <b><?=$Lma02_i_propnovo?></b> <?=$ma02_i_propnovo?> <?=$novo?><br>
          <b><?=$Lma02_t_obs?></b> <?=$ma02_t_obs?>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    <?}?>
    <table>
   <?}else{?>
    <tr>
      <td colspan="3" align="center">Nenhuma transferência para este período</td>
    </tr>
    </table>
   <?}
  }else{
   echo "<div align='center'><br>Digite o período, escolha a opção e clique em pesquisar</div>";
  }
?>
<br><br>
</body>
</html>