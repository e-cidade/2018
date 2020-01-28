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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_prontprofatend_ext_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

$clprontprofatend = new cl_prontprofatend_ext;

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd04_i_cbo");
$db_opcao=1;
$unidade=db_getsession("DB_coddepto");
$usuario=db_getsession("DB_id_usuario");
$todos="";

 $sql1 = "select z01_nome as profissional,db_usuacgm.id_usuario as sd24_i_codigo,z01_numcgm,sd03_i_codigo
                  from cgm 
                  inner join db_usuacgm on cgmlogin= z01_numcgm
                  inner join db_usuarios on db_usuarios.id_usuario= db_usuacgm.id_usuario
                  inner join medicos on medicos.sd03_i_cgm= cgm.z01_numcgm	
                  inner join unidademedicos on unidademedicos.sd04_i_medico= medicos.sd03_i_codigo
                  inner join unidades on unidades.sd02_i_codigo= unidademedicos.sd04_i_unidade		               
                  where sd02_i_codigo = $unidade and db_usuacgm.id_usuario= $usuario                                
                  ";
 $query1 = pg_query($sql1) or die(pg_errormessage());
 $linhas1 = pg_num_rows($query1);
if($linhas1>0){
db_fieldsmemory($query1,0);
} 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd24_i_codigo?>">
              <?=$Lsd24_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?db_input("sd24_i_codigo",11,@$Isd24_i_codigo,true,"text",4,"","chave_sd24_i_codigo");?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_v_nome?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan="2">
              <?db_input("z01_v_nome",40,@$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");?>
            </td>
          </tr>
         <tr>
           <td nowrap title="<?=@$Tsd03_i_codigo?>" align="right" >
            <?db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true);",$db_opcao);?>
           </td>
          <td align="left">
           <?db_input('sd03_i_codigo',10,@$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo(false);'")?>
          </td>
           <td>
           <?db_input('profissional',60,@$profissional,true,'text',3,'')?>
          </td>
         </tr>
        <tr>
         <td nowrap title="<?=@$Tsd04_i_cbo?>" align="right">         
           <?db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true);",$db_opcao);?>
         </td>
         <td align="left">
           <?db_input('sd24_i_profissional',10,@$Isd24_i_profissional,true,'hidden',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
           db_input('rh70_sequencial',10,@$Irh70_sequencial,true,'hidden',$db_opcao,"");
           db_input('rh70_estrutural',10,@$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
           ?>
         </td>
         <td>
          <?
          db_input('rh70_descr',60,@$Irh70_descr,true,'text',3,'');
          ?>
         </td>
        </tr>
          <tr> 
            <td colspan="3" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_prontuarios.hide();">
              <input name="todos" type="submit" id="todos" value="Todos" onClick="document.form2.todos2.value='ok'">
              <input name="todos2" type="hidden"  value="">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
    <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_prontuarios.php")==true){
             include("funcoes/db_func_prontuarios.php");
           }else{
           $campos = "prontuarios.*";
           }
        }
        $repassa = array();
        
        
        if(isset($todos2) && $todos2!=""){
          $sql = "select distinct sd24_i_codigo,sd24_i_numcgs,
                                  z01_v_nome,
                                  case when sd03_i_codigo is null
                                       then codigo_prof
                                       else sd03_i_codigo
                                  end
                                  as sd03_i_codigo ,
                                  case when z01_nome is null
                                       then nome_prof
                                       else z01_nome
                                  end
                                  as z01_nome ,
                                  z01_d_nasc
                  from prontuarios
                  left join unidades       on unidades.sd02_i_codigo           = prontuarios.sd24_i_unidade
                  inner join cgs_und       on cgs_und.z01_i_cgsund             = prontuarios.sd24_i_numcgs
                  left join db_depart      on db_depart.coddepto               = unidades.sd02_i_codigo
                  left join prontproced    on prontproced.sd29_i_prontuario    = prontuarios.sd24_i_codigo
                  left join especmedico    on especmedico.sd27_i_codigo        = prontproced.sd29_i_profissional
                  left join unidademedicos on unidademedicos.sd04_i_codigo     = especmedico.sd27_i_undmed
                  left join medicos        on medicos.sd03_i_codigo            = unidademedicos.sd04_i_medico
                  left join cgm            on cgm.z01_numcgm                   = medicos.sd03_i_cgm
                  left join (select cgm1.z01_nome as nome_prof,
                                    s104_i_profissional as codigo_prof,
                                    s104_i_prontuario
                                    from prontprofatend
                                    inner join especmedico on sd27_i_codigo = s104_i_profissional
                                    inner join rhcbo on rh70_sequencial = sd27_i_rhcbo
                                    inner join unidademedicos on sd04_i_codigo = sd27_i_undmed
                                    inner join medicos on sd04_i_medico = sd03_i_codigo
                  inner join cgm as cgm1 on cgm1.z01_numcgm = sd03_i_cgm ) as prontprofatend on prontprofatend.s104_i_prontuario = prontuarios.sd24_i_codigo
                  where prontuarios.sd24_c_digitada = 'N' ";

          $repassa = array("todos2"=>$todos2);
          //db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
       }else{
           if(isset($sd03_i_codigo) && (trim($sd03_i_codigo)!="") && (@$chave_sd24_i_codigo=="") && (@$chave_z01_v_nome=="")){
             $sql = $clprontprofatend->sql_query_ext(null, " sd24_i_codigo,z01_v_nome,sd24_i_numcgs,rh70_estrutural, rh70_descr ", 
                                                          null, "medicos.sd03_i_codigo = $sd03_i_codigo  ");
           }else if(isset($sd04_i_cbo) && (trim($sd04_i_cbo)!="")){
             $sql = "select sd24_i_codigo,z01_nome,z01_v_nome,sd24_i_numcgs,rh70_estrutural
                     from prontproced 
                     inner join prontuarios on sd24_i_codigo = sd29_i_prontuario 
                     left join especmedico on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional
                     left join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
                     left join unidades on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade	
                     left join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico
                     left join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm	
                     left join rhcbo on rhcbo.rh70_sequencial =  especmedico.sd27_i_rhcbo
                     inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
                     left join db_usuacgm on cgmlogin = medicos.sd03_i_cgm
    		         left join db_usuarios on db_usuarios.id_usuario = db_usuacgm.id_usuario	
                     left join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
                      where medicos.sd29_i_profissional = sd24_i_profissional
                      order by  sd04_i_unidade, rh70_estrutural";
             $repassa = array("sd04_i_cbo"=>$sd04_i_cbo);
           }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
                 $sql = $clprontuarios->sql_query("",$campos,"cgs_und.z01_v_nome, sd24_i_codigo","cgs_und.z01_v_nome like '$chave_z01_v_nome%' ");
                 $repassa = array("chave_z01_v_nome"=>$chave_z01_v_nome);                 
           }else if(isset($chave_sd24_i_codigo) && (trim($chave_sd24_i_codigo)!="") ){
                 $sql = $clprontuarios->sql_query($chave_sd24_i_codigo,$campos,"sd24_i_codigo");
           }
        } 
        //$repassa = array();
        //if(isset($chave_sd24_i_codigo)){
        //  $repassa = array("chave_sd24_i_codigo"=>$chave_sd24_i_codigo,"chave_sd24_i_codigo"=>$chave_sd24_i_codigo);
        //}
        if( isset( $sql ) ){
       	   db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clprontuarios->sql_record($clprontuarios->sql_query($pesquisa_chave));
          if($clprontuarios->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd24_i_codigo',false);</script>";
          }else{
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") nao Encontrado',true);</script>";
          }
        }else{
            echo "<script>".$funcao_js."('',false);</script>";
        }
      }
    ?>
     </td>
   </tr>
</table>
</body>
</html>

<script>
js_tabulacaoforms("form2","chave_sd24_i_codigo",true,1,"chave_sd24_i_codigo",true);

function js_pesquisasd03_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+<?=$unidade?>,'Pesquisa',true);
  }else{
     if(document.form2.sd03_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form2.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos&chave_sd06_i_unidade='+<?=$unidade?>,'Pesquisa',false);
     }else{
       document.form2.profissional.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form2.profissional.value = chave;
  if(erro==true){
    document.form2.sd03_i_codigo.focus();
    document.form2.sd03_i_codigo.value = '';
  }
  js_pesquisasd04_i_cbo(true);
}
function js_mostramedicos1(chave1,chave2){
  document.form2.sd03_i_codigo.value = chave1;
  document.form2.profissional.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
}


function js_pesquisasd04_i_cbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|rh70_sequencial&chave_sd04_i_unidade='+<?=$unidade?>+'&chave_sd04_i_medico='+document.form2.sd03_i_codigo.value,'Pesquisa',true);
  }else{
     if(document.form2.rh70_estrutural.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form2.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|rh70_estrutural&chave_sd04_i_unidade='+<?=$unidade?>+'&chave_sd04_i_medico='+document.form2.sd03_i_codigo.value,'Pesquisa',false);
        document.form2.rh70_estrutural.value = '';
        document.form2.rh70_descr.value = '';
     }else{
       document.form2.rh70_estrutural.value = '';
     }
  }
}
function js_mostrarhcbo(erro,chave1, chave2, chave3,chave4){
  document.form2.rh70_descr.value = chave1;
  document.form2.rh70_estrutural.value = chave2;
  document.form2.sd24_i_profissional.value = chave3;
  document.form2.rh70_sequencial.value = chave4;
  if(erro==true){
    document.form2.rh70_estrutural.focus(); 
    document.form2.rh70_estrutural.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3,chave4){
  document.form2.sd24_i_profissional.value = chave1;
  document.form2.rh70_estrutural.value = chave2;
  document.form2.rh70_descr.value = chave3;
  document.form2.rh70_sequencial.value = chave4;
  db_iframe_especmedico.hide();

  if(chave2=''){
    document.form2.rh70_estrutural.focus(); 
    document.form2.rh70_estrutural.value = ''; 
  }  
}
</script>