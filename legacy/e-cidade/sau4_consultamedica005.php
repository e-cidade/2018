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

//MODULO:saude
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_prontproced_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clcgs = new cl_cgs;
$clcgs_und = new cl_cgs_und;
$clprontproced = new cl_prontproced;
$clprontuarios = new cl_prontuarios;
$clrotulo = new rotulocampo;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
$clprontuarios->rotulo->label();
$clrotulo->label("sd70_c_cid");
$clrotulo->label("sd70_c_nome");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
$clrotulo->label("sd03_i_codigo");
//$sql = $clprontuarios->sql_query("","*","sd24_i_codigo","sd24_i_codigo=$chave_sd24_i_codigo");
//echo $clprontuarios->sql_query("","*","sd24_i_codigo","sd24_i_numcgs=$chave_sd24_i_numcgs");
$result = $clprontuarios->sql_record($clprontuarios->sql_query("","*","sd24_i_codigo desc","sd24_i_numcgs=$chave_sd24_i_numcgs"));
?>
<form name="form1" method="post" action="">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<?
for($i=0;$i<$clprontuarios->numrows;$i++){
db_fieldsmemory($result,$i);
?>
 <tr valign="top">
  <td>
   <td title='<?=$Tsd24_i_codigo?>' nowrap>
     <?=$Lsd24_i_codigo?>
   </td>
   <td nowrap>
     <?db_input('sd24_i_codigo',7,$Isd24_i_codigo,true,'text',3);?>
    </td>
   </td>
   </tr>
   <tr valign="top">
  <td>
   <td  title='<?=$Tsd24_v_motivo?>' nowrap>
     <?=$Lsd24_v_motivo?>
   </td>
   <td  nowrap colspan="3">
     <?db_input('sd24_v_motivo',40,$Isd24_v_motivo,true,'text',3);?>
   </td>
  </td>
 </tr>


<tr valign="top">
 <td>
  <td title='<?=$Tsd24_v_pressao?>' nowrap>
     <?=$Lsd24_v_pressao?>
   </td>
   <td  nowrap>
     <?db_input('sd24_v_pressao',7,$Isd24_v_pressao,true,'text',3);?>
   <?=$Lsd24_f_temperatura?> <?db_input('sd24_v_temperatura',7,@$Isd24_f_temperatura,true,'text',3);?>
   <?=$Lsd24_f_peso?><?db_input('sd24_f_peso',7,$Isd24_f_peso,true,'text',3);?>
   </td>
  </td>
 </td>
</tr>

 <tr valign="top">
  <td>
   <td  title='<?=$Tsd24_t_diagnostico?>' nowrap>
     <?=$Lsd24_t_diagnostico?>
   </td>
   <td  nowrap>
     <?
         $sd24_t_diagnostico=!isset($sd24_t_diagnostico)?' ':$sd24_t_diagnostico;
         db_textarea('sd24_t_diagnostico',1,50,@$sd24_t_diagnostico,true,'text',3,"")
      ?>
   </td>
  </td>
 </tr>

<tr valign="top">
  <td>
   <td title='<?=$Tsd70_c_cid?>' nowrap>
     <?=$Lsd70_c_cid?>
   </td>
   <td nowrap>
     <?db_input('sd70_c_cid',7,$Isd70_c_cid,true,'text',3);?>
      <?db_input('sd70_c_nome',20,$Isd70_c_nome,true,'text',3);?>
   </td>
  </td>
 </tr>
<tr>
<td>
&nbsp;&nbsp;
</td>
</tr>
<?
//echo $clprontproced->sql_query("","*","sd29_i_codigo desc","sd29_i_prontuario=$sd24_i_codigo");
$sql=$clprontproced->sql_query("","sd03_i_codigo,z01_nome,rh70_estrutural,rh70_descr","sd29_i_codigo desc","sd29_i_prontuario=$sd24_i_codigo");
$query = pg_query($sql);
$linhas = pg_num_rows($query);
 if($linhas>0){
  db_lovrot($sql,15,"()","","","","NoMe",array("sd03_i_codigo"=>$sd03_i_codigo,"z01_nome"=>$z01_nome,"rh70_estrutural"=>$rh70_estrutural,"rh70_descr"=>$rh70_descr));
 }else{
  echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro Encontrado<br></b></font></td>
        </tr>
       </table>";
 } 
}
?>
</table>
</form>