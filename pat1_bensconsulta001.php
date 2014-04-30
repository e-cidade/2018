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
include("classes/db_db_estruturanivel_classe.php");

$clbens = new cl_bens;
$clbensmater = new cl_bensmater;
$clbensimoveis = new cl_bensimoveis;
$clbensbaix = new cl_bensbaix;
$clapolitem = new cl_apolitem;

$clrotulo = new rotulocampo;
$clbens->rotulo->label();
db_postmemory($HTTP_POST_VARS);
$t52_bem = 5;
  if(isset($t52_bem) && $t52_bem!=""){
    $result = $clbens->sql_record($clbens->sql_query($t52_bem));
    $numrows = $clbens->numrows;
  if($numrows>0){
    db_fieldsmemory($result,0);
   
    $clbensmater->sql_record($clbensmater->sql_query_file($t52_bem));
    if($clbensmater->numrows>0){
      $bemMIB = "BEM MATERIAL";
      $bem = "M";
    }else{
      $clbensimoveis->sql_record($clbensimoveis->sql_query_file($t52_bem));
      if($clbensimoveis->numrows>0){
        $bemMIB = "BEM IMÓVEL";
	$bem = "I";
      }else{
        $bemMIB = "BEM INDEFINIDO";
      }
    }
    $clbensbaix->sql_record($clbensbaix->sql_query_file($t52_bem));
    if($clbensbaix->numrows>0){
      $bemB = "BEM BAIXADO";
    }else{
      $bemB = "BEM NÃO BAIXADO";
    }
  }
  $r_apolitem = $clapolitem->sql_record($clapolitem->sql_query(null,$t52_bem));
}		  
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_bem?>"> <? db_ancora(@$Lt52_bem,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_bem",8,$t52_bem,true,"text",4,""); 
         db_input("t52_descr",40,"$t52_descr",true,"text",3);  
      ?>
    </td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_codcla?>"> <? db_ancora(@$Lt52_codcla,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_codcla",8,$t52_codcla,true,"text",4,""); 
         db_input("t64_descr",40,"$t64_descr",true,"text",3);  
        ?>
    </td>
  </tr>
  
 <?
   if($clbensmater->numrows > 0 || $clbensimoveis->numrows > 0){
 ?>
  <tr> 
    <td  align="left" nowrap title="<?=($bem=="M"?"Código do Material":"Código do Imóvel")?>"> <? db_ancora(@$Lt52_codmat,"",3);?>  </td>
    <td align="left" nowrap>
      <?
        db_input("t52_codmat",8,$t52_codmat,true,"text",4,""); 
        db_input("bemMIB",40,"$bemMIB",true,"text",3);  
      ?>
    </td>
  </tr>

<?
  if($bem == "M"){  
?>
  <tr> 
    <td  align="left" nowrap title="Nota Fiscal"> <? db_ancora("<b>Nota Fiscal:</b>","",3);?>  </td>
    <td align="left" nowrap>
      <?
        db_input("t53_ntfisc",40,$t53_ntfisc,true,"text",3,""); 
      ?>
    </td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="N~umero do Empenho"> <? db_ancora("<b>Número:</b>","",3);?>  </td>
    <td align="left" nowrap>
      <?
        db_input("t53_empen",8,$t53_ntfisc,true,"text",3,""); 
        db_input("z01_nome",40,$z01_nome,true,"text",3,""); 
      ?>
    </td>
  </tr>
<?
  }else if($bem == "I"){  
?>

<?
  }  
?>



  
  <?}?>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_numcgm?>"> <? db_ancora(@$Lt52_numcgm,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_numcgm",8,$t52_numcgm,true,"text",4,""); 
         db_input("z01_nome",40,"$z01_nome",true,"text",3);  
        ?>
    </td>
  </tr>
  <?
    $data = split("-",$t52_dtaqu);
    $t52_dtaqu_dia = $data[2];
    $t52_dtaqu_mes = $data[1];
    $t52_dtaqu_ano = $data[0];
  ?>
  <tr>
    <td nowrap title="<?=@$Tt52_dtaqu?>">
      <?=@$Lt52_dtaqu?>
    </td>
    <td>
    <?
      db_inputdata('data',@$t52_dtaqu_dia,@$t52_dtaqu_mes,@$t52_dtaqu_ano,true,'text',4,"");
    ?>                                                                                                                                                                                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=@$Lt52_ident?>                                                                                                                                                                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?
    if($t52_ident == "f"){
      $x = "NÃO";
    }else{
      $x = "SIM";
    }
    db_input("x",4,$It52_ident,true,"text",3,""); 
    ?>
    </td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_depart?>"> <? db_ancora(@$Lt52_depart,"",3);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_depart",8,$It52_depart,true,"text",4,""); 
         db_input("descrdepto",40,"$descrdepto",true,"text",3);  
      ?>
    </td>
  </tr>
<?
  if($clapolitem->numrows > 0){
    $a = 4;
    db_fieldsmemory($r_apolitem,0);
  }else{
    $a = 3;
    $t80_contato = "BEM NÃO SEGURADO";
    $t80_segura = "";
    $t81_apolice = "BEM NÃO INCLUÍDO EM NENHUMA APÓLICE";
    $t81_codapo = "";
  }
?>
  <tr> 
    <td  align="left" nowrap title="Código da seguradora"> <? db_ancora("<b>Seguradora</b>:","",3);?>  </td>
    <td align="left" nowrap>
  <?
        db_input("t80_segura",8,$t80_segura,true,"text",$a,"");
        db_input("t80_contato",40,$t80_contato,true,"text",3); 
  ?>
    </td>
  </tr>

  <tr>
    <td  align="left" nowrap title="Código da apólice"> <? db_ancora("<b>Apólice</b>:","",3);?>  </td>
    <td align="left" nowrap>
  <?
        db_input("t81_codapo",8,$t81_codapo,true,"text",$a,"");
        db_input("t81_apolice",40,$t81_apolice,true,"text",3); 
  ?>
    </td>
  </tr>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="pesquisa" type="button"  value="Pesquisa">
  </td>
  </tr>
  </table>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>