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
include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

include("classes/db_db_syscampo_classe.php");
$cldb_syscampo = new cl_db_syscampo;
$cldb_syscampo->rotulocl->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descricao");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cods = str_replace("XX",",",$segundo);
$db_opcao=3;
$db_opcao_radio = 5;


if(isset($atualizar)){
  $sqlerro=false;
  db_inicio_transacao();
  $arr_atucamp =array();

  $arr_dados = $HTTP_POST_VARS;
  reset($arr_dados);
  
  while($dad = key($arr_dados)){
   if(substr($dad,0,3)=="ver"){
     $campo = substr($dad,4);
     $val   = "an_".$campo;
     if($$val=="f"){
       $$val="false";
     }else if($$val=="f"){
       $$val="true";
     }  
     $cldb_syscampo->$campo = $$val;
   }
   next($arr_dados);
  }
  
  
  $arr_chaves = split("#",$chaves);
  $num_chaves = sizeof($arr_chaves);
  for($i=0; $i<$num_chaves; $i++ ){
    $cldb_syscampo->codcam = $arr_chaves[$i];
    $cldb_syscampo->alterar($arr_chaves[$i]);
    if($cldb_syscampo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $cldb_syscampo->erro_msg;
  }
db_fim_transacao($sqlerro);
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
<form name='form1' method='POST'>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="380" align="left" valign="top" bgcolor="#CCCCCC"> 
<?
    $result = $cldb_syscampo->sql_record($cldb_syscampo->sql_query_file($principal));
    db_fieldsmemory($result,0); 
   $an_codcam      = $codcam        ;
   $an_nomecam     = $nomecam       ; 
   $an_conteudo    = $conteudo    ;
   $an_descricao   = $descricao   ;
   $an_valorinicial= $valorinicial;
   $an_rotulo      = $rotulo      ;
   $an_tamanho     = $tamanho     ;
   $an_nulo        = $nulo        ;
   $an_maiusculo   = $maiusculo   ;
   $an_autocompl   = $autocompl   ;
   $an_aceitatipo  = $aceitatipo  ;
   $an_tipoobj     = $tipoobj     ;
   $an_rotulorel   = $rotulorel   ;

//   echo $an_autocompl; 
?>    
      <table border='1' cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap title="<?=@$Tcodcam?>" colspan='6' align='center'>
           <?=@$Lcodcam?>
            <?
                 db_input('an_codcam',7,$Icodcam,true,'text',3)
            ?>
           <?=@$Lnomecam?>
            <?
                 db_input('an_nomecam',20,$Inomecam,true,'text',3)
            ?>
          </td>
        </tr>
	
        <tr>
          <td nowrap title="<?=@$Trotulo?>">
            <?
                 db_input('ver_rotulo',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lrotulo?>
          </td>
          <td> 
            <?
                 db_input('an_rotulo',30,$Irotulo,true,'text',$db_opcao,"")
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
               db_select('an_conteudo',$x,true,$db_opcao);
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
               $x = array("t"=>"Sim","f"=>"Não");
               db_select('an_autocompl',$x,true,$db_opcao);
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
                 db_input('an_rotulorel',30,$Irotulorel,true,'text',$db_opcao,"")
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
                 db_input('an_valorinicial',7,$Ivalorinicial,true,'text',$db_opcao,"")
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
               $x = array("t"=>"Sim","f"=>"Não");
               db_select('an_nulo',$x,true,$db_opcao);
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
               db_select('an_aceitatipo',$x,true,$db_opcao);
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
                 db_input('an_tamanho',7,$Itamanho,true,'text',$db_opcao,"")
            ?>
          </td>
          <td nowrap title="<?=@$Tmaiusculo?>">
            <?
                 db_input('ver_maiusculo',7,$Iautocompl,true,'checkbox',$db_opcao,"")
            ?>
           <?=@$Lmaiusculo?>
          </td>
          <td> 
            <?
               $x = array("t"=>"Sim","f"=>"Não");
               db_select('an_maiusculo',$x,true,$db_opcao);
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
               db_select('an_tipoobj',$x,true,$db_opcao);
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
                 db_textarea('an_descricao',0,40,$Idescricao,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
	<tr>
	  <td colspan='6' align='center'>
	    <input name='atualizar' type='submit' value='Atualizar' onclick='return  js_erificar();'>
	  </td>
	</tr>
	<tr>
	  <td colspan='6'>
<?	  
      $cliframe_seleciona->legenda="CAMPOS";
      $cliframe_seleciona->sql= $cldb_syscampo->sql_query_file(null,"*","codcam","codcam in ($cods)");
      $cliframe_seleciona->campos  = "codcam,nomecam,conteudo,descricao,valorinicial,rotulo,rotulorel,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj";
      $cliframe_seleciona->iframe_height ="160";
      $cliframe_seleciona->iframe_width ="740";
      $cliframe_seleciona->iframe_nome ="camp";
      $cliframe_seleciona->chaves ="codcam";
      $cliframe_seleciona->checked  =true;
      $cliframe_seleciona->iframe_seleciona(1);    
?>
	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
  function js_erificar(){
    js_gera_chaves();

    obj=camp.document.getElementsByTagName("INPUT")
    var marcado=false;
    erro_msg= 'Selecione os campos que deseja atualizar!';
    for(i=0; i<obj.length; i++){
      if(obj[i].type=='checkbox'){
	if(obj[i].checked==true){
	  marcado=true;
	  break;
	}
      }
    }
    
    if(marcado==true){
      obj=document.getElementsByTagName("INPUT")
      var marcado=false;
      erro_msg= 'Selecione uma das propriedaes para atualizar!';
      for(i=0; i<obj.length; i++){
	if(obj[i].type=='checkbox' && obj[i].name.substring(0,3)=="ver"){
	  if(obj[i].checked==true){
	    marcado=true;
	    break;
	  }
	}
      }
    } 
    if(marcado==true){
      return true;
    }else{
      alert(erro_msg);
      return false;
    }  
  }
</script>
<?
if(isset($atualizar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else{
    db_msgbox($erro_msg);
    echo "
            <script>
	      parent.location.href='con3_db_syscampo001.php?codcam=$an_codcam&pesquisar=true';
            </script>
    ";
  }  
}
?>