<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clprontuarios = new cl_prontuarios;
$clunidades    = new cl_unidades_ext;
$clsau_config  = new cl_sau_config_ext;

$clprontuarios->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd04_i_cbo");
$clrotulo->label("sd24_c_digitada");
$clrotulo->label("sd24_d_cadastro");

//Sau_Config
$resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
$objSau_config = db_utils::fieldsMemory($resSau_config,0 );

$db_opcao = 1;
$unidade  = db_getsession("DB_coddepto");
$usuario  = db_getsession("DB_id_usuario");
$todos    = "";

 $sql1 = "select z01_nome as profissional,db_usuacgm.id_usuario as sd24_i_codigo,z01_numcgm,sd03_i_codigo
            from cgm
                 inner join db_usuacgm     on cgmlogin                     = z01_numcgm
                 inner join db_usuarios    on db_usuarios.id_usuario       = db_usuacgm.id_usuario
                 inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm
                 inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo
                 inner join unidades       on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade
           where sd02_i_codigo         = $unidade
             and db_usuacgm.id_usuario = $usuario ";

 $query1  = db_query($sql1) or die(pg_errormessage());
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
          <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
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
            <td width="4%" align="right" nowrap title="<?=$Tsd24_c_digitada?>">
              <?=$Lsd24_c_digitada?>
            </td>
            <td width="96%" align="left" nowrap colspan="2">
            	<?
			       $x = array("S"=>"SIM","N"=>"NÃO");
			       db_select('chave_sd24_c_digitada',$x,true,1);
            	?>
            </td>

          </tr>
          <tr>
             <td nowrap title="<?=@$Tsd24_d_cadastro?>"><?=@$Lsd24_d_cadastro?></td>
             <td>
             <?
             db_inputdata('sd24_d_cadastro',@$sd24_d_cadastro_dia,
                                            @$sd24_d_cadastro_mes,
                                            @$sd24_d_cadastro_ano,
                                            true,'text',"","chave_sd24_d_cadastro"
                         );
             ?>
             </td>
          </tr>

          <tr>
            <td colspan="3" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="pesquisar3" type="submit" id="pesquisarfaa" value="Pesquisar FAA's do usuário">
              <input name="limpar" type="reset" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_prontuarios.hide();">
             </td>
          </tr>
        </table>
        </form>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
    <?php

    	if(isset($chave_sd24_c_digitada)){

    		$pesquisa_chave = '';
    		if(isset($campos)==false){
    			include("funcoes/db_func_prontuarios.php");
    		}

    		$strWhere  = "select distinct unidades.sd02_i_codigo                                     ";
    		$strWhere .= "  from unidades                                                            ";
    		$strWhere .= "       inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
    		$strWhere .= "       inner join db_config on db_config.codigo   = db_depart.instit       ";
    		$strWhere .= "       inner join db_depusu on db_depusu.coddepto = db_depart.coddepto     ";
    		$strWhere .= $objSau_config->s103_i_departamentos == 1 ? "":"where db_depusu.id_usuario = ".db_getsession("DB_id_usuario");

        if(isset($pesquisar)){

           $sql = "select sd24_i_codigo,sd24_i_numcgs, z01_v_nome, z01_d_nasc
             				 from prontuarios
    			              	inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
    		            where prontuarios.sd24_c_digitada = '$chave_sd24_c_digitada'
    		            	and prontuarios.sd24_i_unidade in ($strWhere)
                 order by sd24_i_codigo desc";

           if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){

              $sql = $clprontuarios->sql_query("",$campos,"cgs_und.z01_v_nome, sd24_i_codigo","
                                                           cgs_und.z01_v_nome like '$chave_z01_v_nome%'
                                                             and prontuarios.sd24_i_unidade in ($strWhere) ");
           }else if(isset($chave_sd24_i_codigo) && (trim($chave_sd24_i_codigo)!="") ){

              $sql = $clprontuarios->sql_query(null,$campos,"sd24_i_codigo",
                                                            "sd24_c_digitada = '$chave_sd24_c_digitada'
                                                          and sd24_i_codigo = $chave_sd24_i_codigo
                                                          and prontuarios.sd24_i_unidade in ($strWhere)");
           }else if(isset($sd24_d_cadastro) && (trim($sd24_d_cadastro)!="") ){

              $sd24_d_cadastro = substr($sd24_d_cadastro,6,4)."/".
                                 substr($sd24_d_cadastro,3,2)."/".
                                 substr($sd24_d_cadastro,0,2);
              $sql = $clprontuarios->sql_query(null,$campos,"sd24_i_codigo desc",
                                                            "sd24_c_digitada = '$chave_sd24_c_digitada'
                                                         and sd24_d_cadastro = '$sd24_d_cadastro'
                                                         and prontuarios.sd24_i_unidade in ($strWhere)");
           }

        }else{

         if($sd24_d_cadastro!=""){

            $sql = "select sd24_i_codigo,sd24_i_numcgs, z01_v_nome, z01_d_nasc, nome
                      from prontuarios
                           inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
                           inner join db_usuarios on db_usuarios.id_usuario = prontuarios.sd24_i_login
                     where prontuarios.sd24_c_digitada = '$chave_sd24_c_digitada'
                       and prontuarios.sd24_i_login    = '$usuario'
                       and sd24_d_cadastro             = '$sd24_d_cadastro'
                       and cgs_und.z01_v_nome  like '$chave_z01_v_nome%'
                  order by sd24_i_codigo desc";
           }else{

             $sql = "select sd24_i_codigo,sd24_i_numcgs, z01_v_nome, z01_d_nasc, nome
                       from prontuarios
                            inner join cgs_und     on cgs_und.z01_i_cgsund   = prontuarios.sd24_i_numcgs
                            inner join db_usuarios on db_usuarios.id_usuario = prontuarios.sd24_i_login
                      where prontuarios.sd24_c_digitada = '$chave_sd24_c_digitada'
                        and prontuarios.sd24_i_login = '$usuario'
                        and cgs_und.z01_v_nome  like '$chave_z01_v_nome%'
                   order by sd24_i_codigo desc";
           }

        }

        if( isset( $sql ) ){
			$repassa = array(
                       "chave_sd24_i_codigo"=>@$chave_sd24_i_codigo, 
                       "chave_z01_v_nome"=>@$chave_z01_v_nome,
                       "chave_sd24_c_digitada"=>@$chave_sd24_c_digitada
                      );                 
    			db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",@$repassa);
    		}

    	}else{

    		if($pesquisa_chave!=null && $pesquisa_chave!="" && is_numeric($pesquisa_chave)){

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

function js_limpar(){
  document.form2.chave_sd04_i_codigo.value="";
  document.form2.chave_z01_nome.value="";

}
js_tabulacaoforms("form2","chave_sd24_i_codigo",true,1,"chave_sd24_i_codigo",true);

</script>