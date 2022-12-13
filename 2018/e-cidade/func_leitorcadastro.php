<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_leitor_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_aluno_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clleitor = new cl_leitor;
$clcgm = new cl_cgm;
$claluno = new cl_aluno;
$clcgm->rotulo->label("z01_cgccpf");
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_numcgm");
$claluno->rotulo->label("ed47_i_codigo");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
      <b>Código do Aluno:</b>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed47_i_codigo",11,@$Ied47_i_codigo,true,"text",1,"","chave_ed47_i_codigo","");?>
     </td>
    </tr>	
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tz01_cgccpf?>">
      <?=$Lz01_cgccpf?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("z01_cgccpf",11,@$Iz01_cgccpf,true,"text",1,"","chave_z01_cgccpf","");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
      <?=$Lz01_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("z01_nome",40,@$Iz01_nome,true,"text",1,"","chave_z01_nome","");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_leitorcadastro.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   if(isset($chave_z01_cgccpf) && (trim($chave_z01_cgccpf)!="") ){
    $sql1 = " ed47_v_cpf = '$chave_z01_cgccpf'";
    $sql2 = " z01_cgccpf = '$chave_z01_cgccpf'";
    $sql3 = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end = '$chave_z01_cgccpf'";
   }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
    $sql1 = " ed47_v_nome like '$chave_z01_nome%'";
    $sql2 = " z01_nome like '$chave_z01_nome%'";
    $sql3 = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end like '$chave_z01_nome%'";
   }else if(isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
    $sql1 = " ed47_i_codigo = '$chave_ed47_i_codigo'"; 
	$sql2 = "";
    $sql3 = "";
   }else{
    $sql1 = "";
    $sql2 = "";
    $sql3 = "";
   }
   /*
    * $sql = "SELECT ed47_i_codigo as dl_codigo,ed47_v_nome as dl_nome,ed47_v_cpf as dl_cpf,'ALUNO' as dl_tipo
    *       FROM aluno
    *        inner join alunocurso on alunocurso.ed56_i_aluno = ed47_i_codigo
    *       WHERE $sql1
    *       AND ed56_i_escola = $escola
    *   alterado em 2007/09/28 - Cristian - Pois Charqueada quer módulo biblioteca separada e tem
    *   q fazer o cadastro do leitor como público, daí liberamos o cadastro em "aluno"
    */
	if(isset($chave_ed47_i_codigo)&& (trim($chave_ed47_i_codigo)!="")){
	 $sql = "SELECT ed47_i_codigo as dl_codigo,ed47_v_nome as dl_nome,ed47_v_cpf as dl_cpf,'ALUNO' as dl_tipo
             FROM aluno
             WHERE $sql1";
	}else{
       $sql = "
           SELECT ed47_i_codigo as dl_codigo,ed47_v_nome as dl_nome,ed47_v_cpf as dl_cpf,'ALUNO' as dl_tipo
           FROM aluno
           WHERE $sql1

           UNION

           SELECT ed20_i_codigo as dl_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as dl_nome,case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as dl_cpf,'FUNCIONARIO' as dl_tipo
           FROM rechumano
            left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
            left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
            left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
            left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
            left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
            inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo
           WHERE $sql3
           AND ed75_i_escola = $escola

           UNION
           
           SELECT distinct on (trim(z01_nome)) z01_numcgm as dl_codigo,z01_nome as dl_nome,z01_cgccpf as dl_cpf,'PUBLICO' as dl_tipo
           FROM cgm
           WHERE $sql2
           AND exists( select * from db_cgmcpf where db_cgmcpf.z01_numcgm = cgm.z01_numcgm)
           AND not exists(SELECT *
                          FROM rechumano
                           left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
                           left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
                           left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
                           left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
                           left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
                          WHERE cgmrh.z01_numcgm = cgm.z01_numcgm
                          OR cgmcgm.z01_numcgm = cgm.z01_numcgm
                          )
           ORDER BY dl_nome
          ";
		  }
   $repassa = array();
   if(isset($chave_z01_cgccpf)){
    $repassa = array("chave_z01_cgccpf"=>$chave_z01_cgccpf,"chave_z01_nome"=>$chave_z01_nome);
   }
   if(isset($chave_z01_cgccpf) && (trim($chave_z01_cgccpf)!="") ){
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else if(isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }
   ?>
   </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_z01_nome",true,1,"chave_z01_nome",true);
</script>