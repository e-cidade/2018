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
include("classes/db_pessoal_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpessoal = new cl_pessoal;
$clcgm     = new cl_cgm;
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_numcgm");
$clpessoal->rotulo->label("r01_mesusu");
$clpessoal->rotulo->label("r01_regist");
$clpessoal->rotulo->label("r01_regist");

$sqlanomes = "select max(cast(r11_anousu as text)||lpad(cast(r11_mesusu as text),2,'0')) from cfpess";
$resultanomes = db_query($sqlanomes);
db_fieldsmemory($resultanomes,0);
$ano = substr($max,0,4);
$mes = substr($max,4,2);
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
            <td width="4%" align="right" nowrap title="<?=$Tr01_regist?>">
              <?=$Lr01_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r01_regist",6,$Ir01_regist,true,"text",4,"","chave_r01_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
              <?=$Lz01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	         db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
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
           $campos = "pessoal.*";
        }
        if(isset($chave_r01_regist) && (trim($chave_r01_regist)!="") ){
	         $sql = "
		 	select r01_regist,z01_numcgm,z01_nome,r37_descr,r01_recis
			from pessoal
		      			inner join cgm on cgm.z01_numcgm = pessoal.r01_numcgm
		           		inner join funcao on funcao.r37_anousu = pessoal.r01_anousu
			                		and funcao.r37_mesusu = pessoal.r01_mesusu
				          		and funcao.r37_funcao = pessoal.r01_funcao
					inner join lotacao on lotacao.r13_anousu = pessoal.r01_anousu
							and lotacao.r13_mesusu = pessoal.r01_mesusu
							and lotacao.r13_codigo = pessoal.r01_lotac
					left join cargo on cargo.r65_anousu = pessoal.r01_anousu
							and cargo.r65_mesusu = pessoal.r01_mesusu
							and cargo.r65_cargo = pessoal.r01_cargo
			where r01_anousu = $ano 
			  and r01_mesusu = $mes 
			  and r01_regist = $chave_r01_regist
			order by r01_regist";
        }elseif(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = "
		 	select r01_regist,z01_numcgm,z01_nome,r37_descr,r01_recis
			from pessoal
		      			inner join cgm on cgm.z01_numcgm = pessoal.r01_numcgm
		           		inner join funcao on funcao.r37_anousu = pessoal.r01_anousu
			                		and funcao.r37_mesusu = pessoal.r01_mesusu
				          		and funcao.r37_funcao = pessoal.r01_funcao
					inner join lotacao on lotacao.r13_anousu = pessoal.r01_anousu
							and lotacao.r13_mesusu = pessoal.r01_mesusu
							and lotacao.r13_codigo = pessoal.r01_lotac
					left join cargo on cargo.r65_anousu = pessoal.r01_anousu
							and cargo.r65_mesusu = pessoal.r01_mesusu
							and cargo.r65_cargo = pessoal.r01_cargo
			where r01_anousu = $ano 
			  and r01_mesusu = $mes
			  and z01_nome like '%$chave_z01_nome%'
			order by r01_regist";
        }elseif(isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!="") ){
	         $sql = "
		 	select r01_regist,z01_numcgm,z01_nome,r37_descr,r01_recis
			from pessoal
		      			inner join cgm on cgm.z01_numcgm = pessoal.r01_numcgm
		           		inner join funcao on funcao.r37_anousu = pessoal.r01_anousu
			                		and funcao.r37_mesusu = pessoal.r01_mesusu
				          		and funcao.r37_funcao = pessoal.r01_funcao
					inner join lotacao on lotacao.r13_anousu = pessoal.r01_anousu
							and lotacao.r13_mesusu = pessoal.r01_mesusu
							and lotacao.r13_codigo = pessoal.r01_lotac
					left join cargo on cargo.r65_anousu = pessoal.r01_anousu
							and cargo.r65_mesusu = pessoal.r01_mesusu
							and cargo.r65_cargo = pessoal.r01_cargo
			where r01_anousu = $ano 
			  and r01_mesusu = $mes
			  and z01_numcgm = $chave_z01_numcgm
			order by r01_regist";
        }else{
	         $sql = "
		 	select r01_regist,z01_numcgm,z01_nome,r37_descr,r01_recis
			from pessoal
		      			inner join cgm on cgm.z01_numcgm = pessoal.r01_numcgm
		           		inner join funcao on funcao.r37_anousu = pessoal.r01_anousu
			                		and funcao.r37_mesusu = pessoal.r01_mesusu
				          		and funcao.r37_funcao = pessoal.r01_funcao
					inner join lotacao on lotacao.r13_anousu = pessoal.r01_anousu
							and lotacao.r13_mesusu = pessoal.r01_mesusu
							and lotacao.r13_codigo = pessoal.r01_lotac
					left join cargo on cargo.r65_anousu = pessoal.r01_anousu
							and cargo.r65_mesusu = pessoal.r01_mesusu
							and cargo.r65_cargo = pessoal.r01_cargo
			where r01_anousu = $ano 
			  and r01_mesusu = $mes
			order by r01_regist";
        }
//	echo $sql; exit;
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
	$sql = "
		 	select r01_regist,z01_numcgm,z01_nome,r37_descr,r01_recis
			from pessoal
		      			inner join cgm on cgm.z01_numcgm = pessoal.r01_numcgm
		           		inner join funcao on funcao.r37_anousu = pessoal.r01_anousu
			                		and funcao.r37_mesusu = pessoal.r01_mesusu
				          		and funcao.r37_funcao = pessoal.r01_funcao
					inner join lotacao on lotacao.r13_anousu = pessoal.r01_anousu
							and lotacao.r13_mesusu = pessoal.r01_mesusu
							and lotacao.r13_codigo = pessoal.r01_lotac
					left join cargo on cargo.r65_anousu = pessoal.r01_anousu
							and cargo.r65_mesusu = pessoal.r01_mesusu
							and cargo.r65_cargo = pessoal.r01_cargo
			where r01_anousu = $ano 
			  and r01_mesusu = $mes
			  and r01_regist = $pesquisa_chave
			order by r01_regist";
        $result = db_query($sql);
        if(pg_numrows($result)!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$z01_nome',false);</script>";
        }else{
	       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
document.form2.chave_r01_regist.focus();
document.form2.chave_r01_regist.select();
  </script>
  <?
}
?>