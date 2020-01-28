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
include("classes/db_editalrua_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contricalc_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label("d02_autori");
$clrotulo->label("j39_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$db_opcao = 1;
$db_botao = true;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$testan=false;
if(isset($confirmar)){
  $erros="";
  if(isset($d02_contri) && $d02_contri!=""){ 
    $resultau = $cleditalrua->sql_record($cleditalrua->sql_query("","d02_contri,d02_autori","","d02_contri=$d02_contri"));
    if($cleditalrua->numrows>0){
      $clcontricalc->sql_record($clcontricalc->sql_query("","d09_contri","","d09_contri=$d02_contri"));
      if($clcontricalc->numrows>0){
          db_redireciona("con3_calculoexclupar002.php?contri=$d02_contri");
	  exit;
      }else{
          $erros.="Não foi calculado esta contribuição $d02_contri.\\n";
      }
    }else{
          $erros.="Contribuição $d02_contri não existe.\\n";
    }	
  }
  if(isset($j01_matric) && $j01_matric!=""){
    $result=$clcontrib->sql_record($clcontrib->sql_query("",$j01_matric,"editalrua.d02_autori,d07_contri,z01_nome"));
    $num=$clcontrib->numrows;
    if($num>0){
        $existe=false;
        for($e=0; $e<$num; $e++){
   	  db_fieldsmemory($result,$e);
          $clcontricalc->sql_record($clcontricalc->sql_query("","d09_contri","","d09_contri=$d07_contri"));
          if($clcontricalc->numrows>0){
            db_redireciona("con3_calculoexclupar003.php?matric=$j01_matric");
	    exit;
	  }
	}  
       $erros.="Não foi encontrado contribuição calculada para esta matricula $j01_matric.\\n";
    }else{
         $erros.="Não foi encontrado contribuição para esta matricula $j01_matric.\\n";
    }
  }
  if(isset($j39_codigo) && $j39_codigo!=""){  
      $result09=$cleditalrua->sql_record($cleditalrua->sql_query_file("","d02_contri","","d02_codigo=$j39_codigo"));
      $numrows09=$cleditalrua->numrows;
      if($numrows09>0){
    	  $xx="";
   	  $conts="";
          $temcont=false;      	
	  for($w=0; $w<$numrows09; $w++){
	    db_fieldsmemory($result09,$w);
            $resultas=$clcontrib->sql_record($clcontrib->sql_query_file($d02_contri,"","distinct(d07_contri)"));
	    $conts .=$xx.$d02_contri;
	    $xx=",";
	    if($clcontrib->numrows>0){
	      $temcont=true;      	
	    }  
	  }
	  if(!$temcont){
	    if($numrows09==1){
              $erros.="Rua incluída na contribuição $d02_contri que não foram processadas as matrículas.\\n";
  	    }else{
              $erros.="Rua incluída nas contribuições $conts que não foram processadas as matrículas.\\n";
	    }
          }else{ 
      	      $temcalc=false;
              for($r=0; $r<$numrows09; $r++){
    	        db_fieldsmemory($result09,$r);
                $resultos=$clcontricalc->sql_record($clcontricalc->sql_query_file(null,"d09_contri",null, " d09_contri = $d02_contri "));
                //$conts.=$xx.$d02_contri;
                //$xx=",";
   	        if($clcontricalc->numrows>0){
	          $temcalc=true;
	        }
	      } 
	      if(!$temcalc){
                if($numrows09>1){
    	          $erros.="Rua incluída nas contribuições $conts que não foram calculada.\\n";
	        }else{
    	          $erros.="Rua incluída na contribuição $conts que não foi calculada.\\n";
	        }  
	     }else{
	       db_redireciona("con3_calculoexclupar004.php?codigo=$j39_codigo&pri=true");
	       exit;
	     }  
	 }    
      }else{
	 $erros.="Não existe contribuição para esta rua. ";	
      } 
  }
  $testan=true;

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_confirmar(){
  return true;
}    
  </script>


  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
  <form name="form1" method="post" action="">
  <center>
  <table border="0">
      <tr>
        <td nowrap title="<?=@$Td02_contri?>">
        <?
          db_ancora(@$Ld02_contri,"js_contri(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('d02_contri',6,$Id02_contri,true,'text',$db_opcao," onchange='js_contri(false);'");
      db_input('j14_nome',40,$Ij14_nome,true,'text',3,'','j14_nome_contri');
         ?>
        </td>
      </tr>
        <tr> 
          <td>     
<?
  db_ancora($Lj01_matric,' js_matri(true); ',1);
?>
          </td>
	  <td>
<?
  db_input('j01_matric',6,0,true,'text',1,"onchange='js_matri(false)'");
  db_input('z01_nome',40,0,true,'text',3,"","z01_nome_matric");
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj39_codigo?>">
<?
  db_ancora(@$Lj39_codigo,"js_pesquisaj39_codigo(true);",$db_opcao);
?>
          </td>
          <td> 
<?
  db_input('j39_codigo',6,$Ij39_codigo,true,'text',$db_opcao," onchange='js_pesquisaj39_codigo(false);'");
  db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
?>
          <td>
        <tr>
    <tr>
      <td colspan="2" align="center">
      <br>
	  <input name="confirmar" type="submit" id="confirmar" value="Confirmar" onclick="return js_confirmar()">
      </td>
    </tr>
  </table>
  </center>
</form>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostra|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1';
  }
}
function js_mostra(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nome_matric.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome_matric.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rua','func_editalruaalt.php?funcao_js=parent.js_mostracontri1|d02_contri|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_rua','func_editalruaalt.php?pesquisa_chave='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    document.form1.d02_contri.focus(); 
    document.form1.d02_contri.value=""; 
    document.form1.j14_nome_contri.value=""; 
  }else{
      document.form1.j14_nome_contri.value = chave;
  }  
}
function js_mostracontri1(chave1,chave2){
  document.form1.d02_contri.value = chave1;
  document.form1.j14_nome_contri.value = chave2;
  db_iframe_rua.hide();
}






function js_pesquisaj39_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j39_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j39_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j39_codigo.focus(); 
    document.form1.j39_codigo.value = ''; 
  }
}
</script>
<?
 if($testan==true){
   db_msgbox($erros);  
 }
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>