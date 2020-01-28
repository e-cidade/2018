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
require("libs/db_liborcamento.php");
include("classes/db_bens_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("classes/db_apolitem_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_departorg_classe.php");

$cldepartorg	 = new cl_db_departorg;
$clbens        = new cl_bens;
$clbensmater   = new cl_bensmater;
$clbensimoveis = new cl_bensimoveis;
$clbensbaix    = new cl_bensbaix;
$clapolitem    = new cl_apolitem;
$cldb_depart   = new cl_db_depart;

$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clbensmater->rotulo->label();
$clbensimoveis->rotulo->label();


$clbensbaix->rotulo->label();

$clrotulo->label("t70_descr");//situação do bem
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("t64_descr"); //descrição da classificação
$clrotulo->label("descrdepto");//departamento
$clrotulo->label("t81_codapo");//código da apolice
$clrotulo->label("t81_apolice");//descrição da apólice
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
db_postmemory($HTTP_POST_VARS);

if(isset($t52_bem) && $t52_bem!=""){
  $result = $clbens->sql_record($clbens->sql_query($t52_bem));
  if($clbens->numrows>0){   
    db_fieldsmemory($result,0);
  }else{
    db_redireciona("db_erros.php?fechar=true&db_erro=Bem $t52_bem não encontrado.");
  }
    $result_mater = $clbensmater->sql_record($clbensmater->sql_query_file($t52_bem));
    if($clbensmater->numrows>0){
      $bem_situac = "M";
      db_fieldsmemory($result_mater,0);
    }else{
      $result_imov = $clbensimoveis->sql_record($clbensimoveis->sql_query_file($t52_bem));
      if($clbensimoveis->numrows>0){
        $bem_situac = "I";
        db_fieldsmemory($result_imov,0);
      }else{
        $bemMI = "INDEFINIDO";
	$bem_situac = "NDA";
      }
    }
    $res_bensbaix = $clbensbaix->sql_record($clbensbaix->sql_query_file($t52_bem));
    if($clbensbaix->numrows>0){
      db_fieldsmemory($res_bensbaix,0);
      $baixado = "BEM BAIXADO";
    }else{
      $baixado = "BEM NÃO BAIXADO";
    }
  $r_apolitem = $clapolitem->sql_record($clapolitem->sql_query(null,$t52_bem));
  $numrows = $clapolitem->numrows;
  if($numrows>0){
    $item_apolice = "S";
  }else{
    $item_apolice = "N";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <table width="100%" height="100%" align="center">
	<tr>
	  <td valign="top"  align="center">
            <table border='1' cellspacing="0" cellpadding="0" align ="center" >   
              <tr>
                <td colspan='6' align='center' nowrap ><b> Dados atuais do bem </b></td>
              </tr>
<?
  if(isset($t52_bem) && trim($t52_bem) != ''){
     $result_bens = $clbens->sql_record($clbens->sql_query_file($t52_bem,'t52_descr,t52_depart,t52_dtaqu'));
     if($clbens->numrows>0){
       db_fieldsmemory($result_bens,0);
       
       $result_descrdepart = $cldb_depart->sql_record($cldb_depart->sql_query_file($t52_depart,'descrdepto'));
       db_fieldsmemory($result_descrdepart,0);
       $t52_dtaqu=db_formatar($t52_dtaqu,"d");
	      //<td align='center' title=$Tt70_descr ><b>$RLt70_descr </b></td>
	      //<td align='center'>$t70_descr </td>
     }
  }
?>
              <tr>
                <td nowrap title="<?=@$Tt52_bem?>">
                    <?=@$Lt52_bem?>
                </td>
                <td>
									<?
									    db_input('t52_bem',8,$It52_bem,true,'text',3,"")
									?>
									<?
									    db_input('t52_descr',40,$It52_descr,true,'text',3,"")
									?>
                </td>
              </tr>
              <? 
							$resPesqOrgaoUnidade = $cldepartorg->sql_record($cldepartorg->sql_query_orgunid($t52_depart,db_getsession('DB_anousu'),'o40_orgao,o40_descr,o41_unidade,o41_descr'));
							if($cldepartorg->numrows>0){
								db_fieldsmemory($resPesqOrgaoUnidade,0);
							}
							?>
              <tr>
                <td nowrap title="<?="Cód Órgão";?>">
                   <b>Órgão:</b>    
                </td>
                <td>
									<?
									    db_input('o40_orgao',8,$o40_orgao,true,'text',3,"")
									?>
									<?
									    db_input('o40_descr',40,$o40_descr,true,'text',3,"")
									?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?="Cód Unidade";?>">
                    <b>Unidade:</b>
                </td>
                <td>
									<?
									    db_input('o41_unidade',8,$o41_unidade,true,'text',3,"")
									?>
									<?
									    db_input('o41_descr',40,$o41_descr,true,'text',3,"")
									?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tt52_depart?>">
                    <?=@$Lt52_depart?>
                </td>
                <td>
								<?
								    db_input('t52_depart',8,$It52_depart,true,'text',3,"")
								?>
								<?
								    db_input('descrdepto',40,$Idescrdepto,true,'text',3,"")
								?>
                </td>
              </tr>
		<tr>
                <td nowrap title="<?=@$Tt64_class?>">
                    <?=@$Lt64_class?>
                </td>
                <td>
<?
    db_input('t64_class',10,$It64_class,true,'text',3,"")
?>
<?
    db_input('t64_descr',38,$It64_descr,true,'text',3,"")
?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tt52_dtaqu?>">
                    <?=@$Lt52_dtaqu?>
                </td>
                <td>
<?
    db_inputdata('t52_dtaqu',@$t52_dtaqu_dia,@$t52_dtaqu_mes,@$t52_dtaqu_ano,true,'text',3,"");
?>
                </td>
              </tr>
<?	      
if ($opcao_obs == "S"){
     if (trim(@$t52_obs) != ""){
?>
  <tr>
    <td width="200" colspan="1" align="left" title="Características adicionais do bem"><b>Características adicionais do bem:</b></td>
    <td align="left" title="">
<?
    db_input("t52_obs",80,$It52_obs,true,"text",3);
?>
    </td> 
  </tr>
<?
     }
}

   if (trim(@$t55_obs) != ""){
?>
              <tr>  
                <td nowrap valign="top" align="right" title='<?=$Tt55_obs?>'><?=$Lt55_obs?></td>
		<td>
<?
    db_textarea("t55_obs",5,80,$It55_obs,true,"text",3);
?>
		</td>
	      </tr>	
<?
   }
?>
            </table>
          </td>
        </tr>
        <tr>
	    <td align="center">
            <iframe name="dados_transf" id="dados_transf"  marginwidth="0" marginheight="0" frameborder="0" src="func_dadoshistbem001.php?t52_bem=<?=$t52_bem?>" width="750" height="300"></iframe>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>