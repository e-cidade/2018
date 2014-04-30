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
include("classes/db_marcaloc_classe.php");
include("classes/db_cancmarca_classe.php");
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
$clmarcaloc = new cl_marcaloc;
$clcancmarca = new cl_cancmarca;
$cltransfmarca = new cl_transfmarca;
$clcgm->rotulo->label();
$clmarca->rotulo->label();
$clmarcaloc->rotulo->label();
$clcancmarca->rotulo->label();
$cltransfmarca->rotulo->label();
if(isset($cgm)){
 if($cgm==""){
  $where = "ma01_c_ativo = 'N'";
  $titulo = " - Todas";
 }else{
  $where = "ma01_c_ativo = 'N' and ma01_i_cgm = $cgm";
  $titulo = " - Por Proprietário";
 }
 $campos = "marca.*,localmarca.*,cgm.z01_nome";
 $result = $clmarca->sql_record($clmarca->sql_query("",$campos,"z01_nome",$where));
 ?>
 <table width="100%" border="1" cellspacing="0" cellpading="2">
 <tr>
  <td colspan="3" align="center"><b>Consulta de Marcas Canceladas: <?=$titulo?></b></td>
 </tr>
 <?
 $cor1="#E796A4";
 $cor2="#97B5E6";
 $cor=$cor1;
 if($clmarca->numrows!=0){
  for($i=0;$i<$clmarca->numrows;$i++){
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
         <td><b>Dados da Marca:</b></td>
         <td align="right"><b>Situação:</b>
         <?
         if($ma01_c_ativo=="S"){
          echo "ATIVA";
         }else{
          echo "CANCELADA";
         }
         ?>
         </td>
        </tr>
       </table>
      </td>
      <td><b>Transferências:</b></td>
      <td><b>Imagem:</b></td>
     </tr>
     <tr bgcolor="<?=$cor?>">
      <td>
       <table cellspacing="0" border="0">
        <tr>
         <td width="36%"><?=$Lma01_i_codigo?></td>
         <td colspan="2"><?=$ma01_i_codigo?></td>
        </tr>
        <tr>
         <td><?=$Lz01_nome?></td>
         <td colspan="2"><?=$z01_nome?></td>
        </tr>
        <?
         $campos1 = "localmarca.*";
         $result_loc = $clmarcaloc->sql_record($clmarcaloc->sql_query("",$campos1,"ma04_c_descr"," ma05_i_marca = $ma01_i_codigo"));
        ?>
        <tr>
         <td valign="top"><b>Localidades:</b></td>
         <td colspan="2">
          <?
          for($w=0;$w<$clmarcaloc->numrows;$w++){
           db_fieldsmemory($result_loc,$w);
           echo $ma04_c_descr." - ".$ma04_c_subdistrito."<br>";
          }
          ?>
         </td>
        </tr>
        <tr>
         <td><?=$Lma01_d_data?> <?=db_formatar($ma01_d_data,'d')?></td>
         <td><?=$Lma01_i_livro?> <?=$ma01_i_livro?></td>
         <td><?=$Lma01_i_folha?> <?=$ma01_i_folha?></td>
        </tr>
        <tr>
         <td><?=$Lma01_c_figura1?> <?=$ma01_c_figura1?></td>
         <td><?=$Lma01_c_objeto1?> <?=$ma01_c_objeto1?></td>
         <td><?=$Lma01_c_letra1?> <?=$ma01_c_letra1?></td>
        </tr>
        <tr>
         <td><?=$Lma01_c_figura2?> <?=$ma01_c_figura2?></td>
         <td><?=$Lma01_c_objeto2?> <?=$ma01_c_objeto2?></td>
         <td><?=$Lma01_c_letra2?> <?=$ma01_c_letra2?></td>
        </tr>
        <tr>
         <td><?=$Lma01_c_figura3?> <?=$ma01_c_figura3?></td>
         <td><?=$Lma01_c_objeto3?> <?=$ma01_c_objeto3?></td>
         <td><?=$Lma01_c_letra3?> <?=$ma01_c_letra3?></td>
        </tr>
        <tr>
         <td colspan="2"></td>
         <td><?=$Lma01_c_letra4?> <?=$ma01_c_letra4?></td>
        </tr>
       </table>
      </td>
      <td valign="top" rowspan="3" >
       <?
       $campos = "transfmarca.*,cgm1.z01_nome as ant,cgm4.z01_nome as novo,cgm2.z01_nome as req";
       $result_trans = $cltransfmarca->sql_record($cltransfmarca->sql_query("",$campos,"ma02_d_data","ma02_i_marca = $ma01_i_codigo"));
       if($cltransfmarca->numrows!=0){
        for($z=0;$z<$cltransfmarca->numrows;$z++){
         db_fieldsmemory($result_trans,$z);?>
         <b>Requerente:</b><br> <?=$req?><br>
         <?=$Lma02_d_data?> <?=db_formatar($ma02_d_data,'d')?><br>
         <?=$Lma02_i_propant?> <?=$ma02_i_propant?><br>
         <?=$ant?><br>
         <?=$Lma02_i_propnovo?> <?=$ma02_i_propnovo?><br>
         <?=$novo?><br>
         <?=$Lma02_i_codproc?> <?=$ma02_i_codproc?><br>
         <?=$Lma02_t_obs?> <?=$ma02_t_obs?><p>
         <?
        }
       }else{
        echo "Marca sem transferências";
       }?>
      </td>
      <td rowspan="3" align="center" width="105">
       <?
       if($ma01_o_imagem!=0){
        $arquivo = "tmp/".$ma01_c_nomeimagem;
        pg_exec("begin");
        pg_loexport($ma01_o_imagem,$arquivo);
        pg_exec("end");
       }else{
        $arquivo = "imagens/semmarca.jpg";
       }?>
       <img src="<?=$arquivo?>" border="0" width="100" height="100">
      </td>
     </tr>
     <tr bgcolor="<?=$cor?>">
       <td><b>Cancelamentos:</b></td>
     </tr>
     <tr bgcolor="<?=$cor?>">
      <td>
       <table border="0" cellspacing="0" width="100%">
        <tr>
        <?
        $campos = "cgm.z01_nome,cgm.z01_numcgm,cancmarca.*";
        $sql = $clcancmarca->sql_query("",$campos,"ma03_d_data","ma01_i_codigo = $ma01_i_codigo");
        $result_canc = $clcancmarca->sql_record($sql);
        if($clcancmarca->numrows!=0){
         for($t=0;$t<$clcancmarca->numrows;$t++){
          db_fieldsmemory($result_canc,$t);?>
          <td valign="top">
           <b>Requerente:</b> <?=$z01_numcgm?> <?=$z01_nome?><br>
           <?=$Lma03_d_data?> <?=db_formatar($ma03_d_data,'d')?>&nbsp;&nbsp;&nbsp;
           <?=$Lma03_i_codproc?> <?=$ma03_i_codproc?>&nbsp;&nbsp;&nbsp;
           <b>Tipo:</b> <?=$ma03_c_tipo=="C"?"Cancelamento":"Reativação"?><br>
           <?=$Lma03_t_obs?> <?=$ma03_t_obs?>
           <?if($clcancmarca->numrows>1){?><br><br><?}?>
          </td>
          <?
          if(($t%2)!=0){
           echo "</tr><tr><td height='10'></td></tr>";
          }
         }?>
        <?}else{?>
         <td>Marca sem cancelamentos</td>
        <?}?>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td colspan="3" bgcolor="#CCCCCC" height="5"></td>
     </tr>
    <?}?>
    <table>
   <?}else{?>
    <tr>
     <td colspan="3" align="center">Nenhum registro de marca cancelada</td>
    </tr>
    </table>
   <?}
  }else{
   echo "<div align='center'><br>Escolha a opção e clique em pesquisar</div>";
  }
?>
<br><br>
</body>
</html>