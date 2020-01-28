<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
//$unidade= db_getsession("DB_depto");
if(!isset($data_ini)){
$data_ini=date("d-m-Y",db_getsession("DB_datausu"));
$data_ini_dia=date("d",db_getsession("DB_datausu"));
$data_ini_mes=date("m",db_getsession("DB_datausu"));
$data_ini_ano=date("Y",db_getsession("DB_datausu"));
}
$dHoje = date("Y-m-d",db_getsession("DB_datausu"));
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
              <?
                 db_input("sd24_i_codigo",11,$Isd24_i_codigo,true,"text",4,"","chave_sd24_i_codigo");
                 ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Inicio <?db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',4,"",'chave_data_ini');?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd24_i_ano.'|'.Tsd24_i_mes.'|'.Tsd24_i_seq?>">
              <?=$Lsd24_i_ano.'|'.$Lsd24_i_mes.'|'.$Lsd24_i_seq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                 db_input("sd24_i_ano",4,$Isd24_i_ano,true,"text",4,"","chave_sd24_i_ano");
                 db_input("sd24_i_mes",2,$Isd24_i_mes,true,"text",4,"","chave_sd24_i_mes");
                 db_input("sd24_i_seq",5,$Isd24_i_seq,true,"text",4,"","chave_sd24_i_seq");
                 ?>&nbsp;Fim <?db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',4,"",'chave_data_fim');?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_v_nome?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("z01_v_nome",40,$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
                 ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">&nbsp&nbsp&nbsp&nbsp
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">&nbsp&nbsp&nbsp&nbsp
              <input name="emite" type="button" id="emite" value="Emite Lista" onClick="js_emitelista()">&nbsp&nbsp&nbsp&nbsp
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_triagem.hide();">
             </td>
          </tr>

        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = '';
      if(!isset($pesquisa_chave)){   	
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_triagem.php")==true){           	
             include("funcoes/db_func_triagem.php");
           }else{
           	
           $campos = "prontuarios.*";
           }
        }
        if(isset($chave_sd24_i_codigo) && (trim($chave_sd24_i_codigo)!="") ){        	
            $sql = $clprontuarios->sql_query($chave_sd24_i_codigo,$campos,"sd24_i_codigo", "sd24_i_codigo=$chave_sd24_i_codigo and 
            		                                                       sd24_i_unidade = ".DB_getsession("DB_coddepto")." $sWhere");
              
        }else if(isset($chave_sd24_i_ano) && (trim($chave_sd24_i_ano)!="") &&
                 isset($chave_sd24_i_mes) && (trim($chave_sd24_i_mes)!="") &&
                 isset($chave_sd24_i_seq) && (trim($chave_sd24_i_seq)!="")
                ){
                	
             $sql = $clprontuarios->sql_query("",$campos,"sd24_i_codigo"," sd24_i_ano = $chave_sd24_i_ano and
                                                                            sd24_i_mes = $chave_sd24_i_mes and
                                                                            sd24_i_seq = $chave_sd24_i_seq and
                                                                            sd24_i_unidade = ".DB_getsession("DB_coddepto")." $sWhere");
        }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
             $sql = $clprontuarios->sql_query("",$campos,"cgs_und.z01_v_nome, sd24_i_codigo","cgs_und.z01_v_nome like '$chave_z01_v_nome%' and 
             		                                                                         sd24_i_unidade = ".DB_getsession("DB_coddepto"));

        }else if(isset($chave_data_ini)&&($chave_data_ini!="")){  
           if((isset($chave_data_fim))&&($chave_data_fim!="")){
               $sql=$clprontuarios->sql_query("",$campos,"sd24_i_codigo"," sd24_d_cadastro between '$chave_data_ini' and '$chave_data_fim' 
                                                           and sd24_i_unidade = ".DB_getsession("DB_coddepto")." $sWhere");
           }else{
               $sql=$clprontuarios->sql_query("",$campos,"sd24_i_codigo"," sd24_d_cadastro>='$chave_data_ini' 
                                                           and sd24_i_unidade = ".DB_getsession("DB_coddepto")." $sWhere");
           }
        } else{
                $sql = "select distinct sd24_i_codigo,sd24_i_ano,sd24_i_mes,z01_v_nome,sd24_i_numcgs
                    from prontuarios 
                    inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
                    left join especmedico on especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional
                    left join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
                    left join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico
                    left join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm	
                    left join rhcbo on rhcbo.rh70_sequencial =  especmedico.sd27_i_rhcbo
                    left join unidades on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade	
                    left join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
                    left join sau_triagemavulsa on s152_i_cgsund = cgs_und.z01_i_cgsund and s152_d_dataconsulta = '$dHoje'
                    where ( sd24_v_motivo is null or sd24_v_motivo = '' )
                     and sd24_c_digitada = 'N'
                     and sd24_i_unidade = ".DB_getsession("DB_coddepto")."
                     and s152_i_codigo is null
                     and sd24_d_cadastro = '$dHoje'
                      $sWhere 
                    order by sd24_i_codigo";

         //$query_pront = @pg_query($sql) or die(pg_errormessage());
         //$linhas = @pg_num_rows($query_pront);
        }

        $repassa = array();
        if(isset($chave_sd24_i_codigo)){
          $repassa = array("chave_sd24_i_codigo"=>$chave_sd24_i_codigo,"chave_sd24_i_codigo"=>$chave_sd24_i_codigo);
        }
        db_lovrot(@$sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clprontuarios->sql_record($clprontuarios->sql_query($pesquisa_chave));
          if($clprontuarios->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd24_i_codigo',false);</script>";
          }else{
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
  js_tabulacaoforms("form2","chave_sd24_i_codigo",true,1,"chave_sd24_i_codigo",true);

  function js_emitelista(){
    jan = window.open('sau2_triagem001.php?unidade=<?=DB_getsession("DB_coddepto")?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }  
  
 function js_limpar(){
 document.form2.chave_sd24_i_codigo.value="";
 document.form2.chave_sd24_i_ano.value="";
 document.form2.chave_sd24_i_mes.value="";
 document.form2.chave_sd24_i_seq.value="";
 document.form2.chave_z01_v_nome.value="";	
 } 
</script>