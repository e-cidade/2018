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

include("classes/db_db_syscampo_classe.php");
include("classes/db_db_sysarqcamp_classe.php");
include("classes/db_db_syscampodep_classe.php");
$cldb_syscampo = new cl_db_syscampo;
$cldb_syscampodep = new cl_db_syscampodep;
$cldb_sysarqcamp = new cl_db_sysarqcamp;

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

$clrotulo = new rotulocampo;
$clrotulo->label("descricao");
$clrotulo->label("nomearq");
$clrotulo->label("codcam");
$clrotulo->label("rotulo");
$clrotulo->label("rotulorel");

$db_opcao = 3;
$db_botao = true;

//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
echo ($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$cldb_syscampo->rotulo->label();

$cods = str_replace("XX",",",$secs);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
  function js_verificar(){
    document.form1.submit();
  }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<form name='form1' >
<table width="755" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="380" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<?
    $result = $cldb_syscampo->sql_record($cldb_syscampo->sql_query_file($pri));
    db_fieldsmemory($result,0); 
?>    
      <table border='1' cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap title="<?=@$Tcodcam?>" colspan='6' align='center'>
           <?=@$Lcodcam?>
            <?
                 db_input('codcam',7,$Icodcam,true,'text',3)
            ?>
           <?=@$Lnomecam?>
            <?
                 db_input('nomecam',20,$Inomecam,true,'text',3)
            ?>
          </td>
        </tr>
	
        <tr>
          <td nowrap title="<?=@$Trotulo?>">
            <?
                 db_input('autocompl',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lrotulo?>
          </td>
          <td> 
            <?
                 db_input('rotulo',30,$Irotulo,true,'text',$db_opcao,"")
            ?>
          </td>
          <td nowrap title="<?=@$Tconteudo?>">
            <?
                 db_input('ver_conteudo',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lconteudo?>
          </td>
          <td> 
            <?
               $x = array("varchar"=>"Varchar","text"=>"Text","oid"=>"Oid","int4"=>"Int4","int8"=>"Int8","float4"=>"Float4","float8"=>"Float8","bool"=>"Lógico","char"=>"Char","date"=>"Data");
               db_select('conteudo',$x,true,$db_opcao);
            ?>
          </td>
          <td nowrap title="<?=@$Tautocompl?>">
            <?
                 db_input('ver_autocompl',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lautocompl?>
          </td>
          <td align='left' width='13%'> 
            <?
                 db_input('autocompl',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
          </td>
        </tr>
	  
	
        <tr>
          <td nowrap title="<?=@$Trotulorel?>">
            <?
                 db_input('ver_rotulorel',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lrotulorel?>
          </td>
          <td> 
            <?
                 db_input('rotulorel',30,$Irotulorel,true,'text',$db_opcao,"")
            ?>
          </td>
          <td nowrap title="<?=@$Tvalorinicial?>">
            <?
                 db_input('ver_valorinicial',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lvalorinicial?>
          </td>
          <td> 
            <?
                 db_input('valorinicial',7,$Ivalorinicial,true,'text',$db_opcao,"")
            ?>
          </td>
          <td nowrap title="<?=@$Tnulo?>">
            <?
                 db_input('ver_nulo',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lnulo?>
          </td>
          <td> 
            <?
                 db_input('nulo',7,$Inulo,true,'checkbox',$db_opcao,"")
            ?>
          </td>
        </tr>
	
        
	<tr>
          <td nowrap title="<?=@$Taceitatipo?>">
            <?
                 db_input('ver_aceitatipo',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Laceitatipo?>
          </td>
          <td> 
            <?
               $x = array("0"=>"Não Valida Campo","1"=>"Somente Números","2"=>"Somente Letras","3"=>"Números e letras","4"=>"Números Casa Dec.","5"=>"Verdadeiro/Falso");
               db_select('aceitatipo',$x,true,$db_opcao);
            ?>
          </td>
          <td nowrap title="<?=@$Ttamanho?>">
            <?
                 db_input('ver_tamanho',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Ltamanho?>
          </td>
          <td> 
            <?
                 db_input('tamanho',7,$Itamanho,true,'text',$db_opcao,"")
            ?>
          </td>
          <td nowrap title="<?=@$Tmaiusculo?>">
            <?
                 db_input('ver_maisculo',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lmaiusculo?>
          </td>
          <td> 
            <?
                 db_input('maiusculo',7,$Imaiusculo,true,'checkbox',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ttipoobj?>" valign='top' >
            <?
                 db_input('ver_tipoobj',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Ltipoobj?>
          </td>
          <td valign='top'> 
            <?
               $x = array("text"=>"Input Text","checkbox"=>"Input Checkbox","radiobutton"=>"Input Radio Button","image"=>"Input Imagem","textarea"=>"TextArea","select"=>"Select","multiple"=>"Select Multiple");
               db_select('tipoobj',$x,true,$db_opcao);
            ?>
          </td>
          <td nowrap title="<?=@$Tdescricao?>" valign='top'>
            <?
                 db_input('ver_descricao',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Ldescricao?>
          </td>
          <td colspan='5' valign='top'> 
            <?
                 db_textarea('descricao',0,40,$Idescricao,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
	<tr>
	  <td colspan='6' align='center'>
	    <input name='inlcuir' type='button' value='Atualizar' onclick='js_verificar();'>
	  </td>
	</tr>
	<tr>
	  <td colspan='6'>
<?	  
/*
      $cliframe_seleciona->legenda="CAMPOS";
      $cliframe_seleciona->sql= $cldb_syscampo->sql_query_file(null,"*","codcam","codcam in ($cods)");
      $cliframe_seleciona->campos  = "codcam,nomecam";
      $cliframe_seleciona->iframe_height ="160";
      $cliframe_seleciona->iframe_width ="700";
      $cliframe_seleciona->iframe_nome ="camp";
      $cliframe_seleciona->chaves ="codcam";
      $cliframe_seleciona->iframe_seleciona(1);    
*/      
?>
	  </td>
	</tr>
      </table>
    </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>