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
include("classes/db_edital_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contricalc_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$cledital = new cl_edital;
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

if (isset($confirmar)) {
  $erros="";
  if (isset($d02_contri) && $d02_contri!="") {
    $resultau = $cleditalrua->sql_record($cleditalrua->sql_query("","d02_contri,d02_autori","","d02_contri=$d02_contri"));
    if ($cleditalrua->numrows>0) {
      $clcontrib->sql_record($clcontrib->sql_query("","","d07_contri","","d07_contri=$d02_contri"));

      if ($clcontrib->numrows>0) {
        db_fieldsmemory($resultau,0);
        if ($d02_autori=='t') {
          $resu=$cleditalrua->sql_record($cleditalrua->sql_query("","d02_contri","","d02_autori='t' and d02_contri=$d02_contri"));
          $numr=$cleditalrua->numrows;
          if ($numr>0) {
            $result=$clcontrib->sql_record($clcontrib->sql_query_file("","","d07_matric","","d07_contri=$d02_contri"));
            $numrows=$clcontrib->numrows;
            $virg="";
            $matricus="";
            $sqlerro=false;
            db_inicio_transacao();
            for ($i=0; $i<$numrows; $i++) {
              db_fieldsmemory($result,$i);
              $result03=$clcontricalc->sql_record($clcontricalc->sql_query_file("","d09_numpre","","d09_contri = $d02_contri and d09_matric = $d07_matric"));
              $numrows03=$clcontricalc->numrows;
              if ($numrows03==0) {
                $clcontricalc->fc_calculocontr($d02_contri,$d07_matric,$parcelas,$privenc_ano,$privenc_mes,$privenc_dia,$provenc);
                if ($clcontricalc->erro_status=="0") {
                  $sqlerro=true;
                  break;
                }
              } else {
                $matricus.=$virg.$d07_matric;
                $virg=", ";
              }
            }
            db_fim_transacao($sqlerro);
          }
        } else {
          $erros.="Contribuição $d02_contri não tem permissão de calculo.\\n";
        }
      } else {
        $erros.="Não foram processadas as matrículas para esta contribuição $d02_contri.\\n";
      }
    } else {
      $erros.="Contribuição $d02_contri não existe.\\n";
    }
  }
  
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
  if(document.form1.d02_contri.value==""){
    alert("Selecione uma contribuição.");
    return false;
  }
  
  dia=document.form1.privenc_dia.value;
  mes=document.form1.privenc_mes.value;
  ano=document.form1.privenc_ano.value;
  
  parce=document.form1.parcelas;
  num= new Number(parce.value);
  if(isNaN(num) || num==""){
    alert("Numero de parcelas inválido.");  
    parce.focus();
    return false;
  }
  provenc=document.form1.provenc;
  num= new Number(provenc.value);
  if(isNaN(num) || num>31 || num==""){
    alert("Dia inválido.");  
    provenc.focus();
    return false;
  }
  return  js_VerDaTa("privenc_dia",dia,mes,ano);  
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
          db_ancora(@$Ld02_contri,"js_contri(true);",1);
        ?>
        </td>	
        <td>	
      <?
      db_input('d02_contri',4,$Id02_contri,true,'text',1," onchange='js_contri(false);'");
      db_input('j14_nome',40,$Ij14_nome,true,'text',3);
         ?>
        </td>
      </tr>
    <tr>
      <td nowrap title="Numero de parcelas">
      <b>Parcelas</b>
      </td>
      <td> 
<?
  if(!isset($confirmar) && isset($d02_contri)){ 
    $result01=$cleditalrua->sql_record($cleditalrua->sql_query_file($d02_contri,"d02_codedi,d02_autori","d02_codedi limit 1"));
    db_fieldsmemory($result01,0);
    if($d02_autori=="f"){
       $msgerro="Contribuição não autorizada para calculo.";  
       $db_opcao=3;
       $parcelas='';
       $provenc='';
       $privenc_dia='';
       $privenc_mes='';
       $privenc_ano='';
    }else{
      $result02=$cledital->sql_record($cledital->sql_query_file($d02_codedi,"d01_privenc as privenc,d01_numtot as parcelas"));
      db_fieldsmemory($result02,0);
      $provenc=$privenc_dia ;
    }  
  }else{
    $db_opcao=3;
  }  
  db_input('parcelas',4,5,true,'text',$db_opcao);
?>
      </td>
    </tr>
    <tr>
      <td nowrap title="Data do primeiro vencimento">
       <b>1° Vencimento</b>
      </td>  
      <td nowrap title="Data do primeiro vencimento">
    <?
     db_inputdata('privenc',@$privenc_dia,@$privenc_mes,@$privenc_ano,true,'text',$db_opcao,"");
   ?>  
      </td>
    </tr>  
    <tr>
      <td nowrap title="Dia dos próximos vencimentos">
        <b>Dia vencimento</b>
      </td>  
      <td nowrap title="Dia dos próximos vencimentos">
    <?
     db_input('provenc',4,0,'true','text',$db_opcao,"")
   ?>  
      </td>
    </tr>  
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
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalruaalt.php?funcao_js=parent.js_mostracontri1|d02_contri|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalruaalt.php?pesquisa_chave='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    document.form1.d02_contri.focus(); 
    document.form1.d02_contri.value=""; 
    document.form1.j14_nome.value=""; 
  }else{  
    document.form1.j14_nome.value = chave;
    document.form1.submit();
  }
}
function js_mostracontri1(chave1,chave2){
  document.form1.d02_contri.value = chave1;
  document.form1.j14_nome.value = chave2;
  document.form1.submit();
  db_iframe.hide();
}
</script>
<?
if(isset($confirmar)){
   if(isset($erros) && $erros!=""){
       db_msgbox($erros);
   }else if(isset($clcontricalc->erro_status) && $clcontricalc->erro_status=="0"){
     $clcontricalc->erro(true,false);
   }else{
      if(isset($matricus) && $matricus!=""){
        db_msgbox("As matriculas $matricus não foram calculadas pois já haviam sido.");
      }else if(isset($clcontricalc->erro_status) && $clcontricalc->erro_status=="1"){
         $clcontricalc->erro(true,false);
      }	 
   }  
}  
if(isset($msgerro)){
  db_msgbox($msgerro);
}
?>