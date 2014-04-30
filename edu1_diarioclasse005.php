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

class cl_criaabasrefresh {
 var $identifica = null;
 var $abas_top  = "44";
 var $abas_left  = "0";
 var $src       = null;
 var $title     = null;
 var $cortexto  = null;
 var $corfundo  = null;
 var $cordisabled  = null;
 var $sizecampo = null;
 var $disabled = null;
 var $iframe_width = '100%';
 var $iframe_height = '100%';
 var $scrolling = "yes";
 function cria_abas(){
  ?>
  <script>
   function mo_camada(idtabela){
    var camada="div_"+idtabela;
    var tabela = document.getElementById(idtabela);
    var divs = document.getElementsByTagName('DIV');
    var tab  = document.getElementsByTagName('TABLE');
    var aba = eval('document.formaba.'+idtabela+'.name');
    var input = eval('document.formaba.'+idtabela);
    var alvo = document.getElementById(camada);
    for (var j = 0; j < divs.length; j++){
     if(alvo.id == divs[j].id){
      divs[j].style.visibility = 'visible' ;
      divs[j].style.zIndex = 99;
      divs[j].style.width  = (screen.availWidth-10);
      divs[j].style.height = (screen.availHeight-184);
     }else{
      if(divs[j].className == 'tabela'){
       divs[j].style.visibility = 'hidden';
       divs[j].style.zIndex = 98;
       divs[j].style.width = (screen.availWidth-10);
       divs[j].style.height= (screen.availHeight-184);
      }
     }
    }
    for(var x = 0; x < tab.length; x++){
     if(tab[x].className == 'bordas'){
      for(y=0; y < document.forms['formaba'].length; y++){
       tab[x].style.border = '1px outset #cccccc';
       tab[x].style.borderBottomColor = '#000000';
       <?
       reset($this->identifica);
       for($w=0; $w<sizeof($this->identifica); $w++){
        $chave=key($this->identifica);
        ?>
        document.formaba.<?=$chave?>.style.fontWeight = 'normal';
        if(document.formaba.<?=$chave?>.disabled==true){
         document.formaba.<?=$chave?>.style.color ='<?=(isset($this->cordisabled)&&$this->cordisabled!=""?$this->cordisabled:"black")?>';
        }else{
         document.formaba.<?=$chave?>.style.color ='black';
        }
        <?
        next($this->identifica);
       }
       ?>
      }
      if(aba == tab[x].id){
       tab[x].style.border = '3px outset #999999';
       tab[x].style.borderBottomWidth = '0px';
       tab[x].style.borderRightWidth = '1px';
       tab[x].style.borderLeftColor =  '#000000';
       tab[x].style.borderTopColor =  '#3c3c3c';
       tab[x].style.borderRightColor =  '#000000';
       tab[x].style.borderRightStyle =  'inset';
      }
      input.style.color = 'black';
      input.style.fontWeight = 'bold';
     }
    }
   }
  </script>
  <style>
   a {
       text-decoration:none;
     }
   a:hover {
     text-decoration:none;
     color: #666666;
   }
   a:visited {
     text-decoration:none;
     color: #999999;
   }
   a:active {
      color: black;
      font-weight: bold;
   }
   .nomes {
      border:none;
      text-align: center;
      font-size: 11px;
      font-weight:normal;
      cursor: hand;
   }
   .nova {background-color: transparent;
      border:none;
      text-align: center;
      font-size: 11px;
      color: darkblue;
      font-weight:bold;
      cursor: hand;
      height:14px;
   }
   .bordas{
      border: 1px outset #cccccc;
      border-bottom-color: #000000;
   }
   .bordasi{
      border: 0px outset #cccccc;
   }
   .novamat{
      border: 2px outset #cccccc;
      border-right-color: darkblue;
      border-bottom-color: darkblue;
      background-color: #999999;
   }
  </style>
  <table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0" >
   <form name="formaba" method="post" id="formaba" >
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
      <tr>
      <?
      reset($this->identifica);
      for($w=0; $w<sizeof($this->identifica); $w++){
       $chave=key($this->identifica);
       $cortexto=(isset($this->cortexto[$chave])&&$this->cortexto[$chave]!=""?$this->cortexto[$chave]:'black');
       $corfundo=(isset($this->corfundo[$chave])&&$this->corfundo[$chave]!=""?$this->corfundo[$chave]:'#cccccc');
       $sizecampo=(isset($this->sizecampo[$chave])&&$this->sizecampo[$chave]!=""?$this->sizecampo[$chave]:'10');
       $disabled=(isset($this->disabled[$chave])&&$this->disabled[$chave]=="true"?'disabled':'');
       $src=(isset($this->src[$chave])&&$this->src[$chave]!=""?$this->src[$chave]:'');
       ?>
       <td>
        <table class="bordas" id="<?=$chave?>" border="0" style="cursor:hand; border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " cellpadding="3" cellspacing="0" >
         <tr>
          <td nowrap>
           <input readonly <?=$disabled?>  name="<?=$chave?>" class="nomes"  style="cursor:hand;font-weight:bold; color:<?=$cortexto?>; background-color:<?=$corfundo?>;" type="text"  value="<?=$this->identifica[$chave]?>" title="<?=$this->title[$chave]?>" size="<?=$sizecampo?>"  onClick="iframe_<?=$chave?>.location.href='<?=$src?>';mo_camada('<?=$chave?>');">
          </td>
         </tr>
        </table>
       </td>
       <?
       next($this->identifica);
      }
      ?>
      </tr>
     </table>
    </td>
   </tr>
   </form>
   <form name="form_iframes" method="post" id="form_iframes" >
   <tr>
    <td align="center">
     <?
     reset($this->identifica);
     for($w=0; $w<sizeof($this->identifica); $w++){
      $chave=key($this->identifica);
      $src=(isset($this->src[$chave]) && $this->src[$chave]!=null?"src=\"".$this->src[$chave]."\"":"");
      ?>
      <div class="tabela" id="div_<?=$chave?>" style="position:absolute;left:<?=$this->abas_left?>px; top:<?=$this->abas_top?>px;visibility: visible;">
       <iframe  id='<?=$chave?>' name="iframe_<?=$chave?>" class="bordasi" <?=$src?> frameborder="0" marginwidth="0" leftmargin="0" topmargin="0" height="<?=$this->iframe_height?>" width="<?=$this->iframe_width?>" scrolling="<?=$this->scrolling?>">
       </iframe>
      </div>
      <?
      next($this->identifica);
     }
     ?>
     </div>
    </td>
   </tr>
   </form>
  </table>
  <?
  reset($this->identifica);
  $chave=key($this->identifica);
  echo "<script>mo_camada('$chave');</script>";
 }
}
require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_regencia_classe.php");

$clcriaabas = new cl_criaabasrefresh;
$clregencia = new cl_regencia;
$db_opcao = 1;
$result = $clregencia->sql_record($clregencia->sql_query("","*","","ed59_i_codigo = $regencia"));
db_fieldsmemory($result,0);
$sql3 = "SELECT ed41_i_codigo,
                ed09_c_abrev,
                ed09_c_descr,
                case
                 when ed41_i_codigo>0 then 'A' end as tipo,
                ed37_c_tipo,
                ed37_i_menorvalor,
                ed37_i_maiorvalor,
                ed37_i_variacao,
                ed37_c_minimoaprov,
                ed41_i_formaavaliacao,
                ed41_i_sequencia
         FROM procavaliacao
          inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
          inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao
         WHERE ed41_i_procedimento = $ed220_i_procedimento
         UNION
         SELECT ed43_i_codigo,
                ed42_c_abrev,
                ed42_c_descr,
                case
                 when ed43_i_codigo>0 then 'R' end as tipo,
                ed37_c_tipo,
                ed37_i_menorvalor,
                ed37_i_maiorvalor,
                ed37_i_variacao,
                ed37_c_minimoaprov,
                ed43_i_formaavaliacao,
                ed43_i_sequencia
         FROM procresultado
          inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
          inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao
         WHERE ed43_i_procedimento = $ed220_i_procedimento
         ORDER BY ed41_i_sequencia
        ";
$result3 = pg_query($sql3);
$linhas3 = pg_num_rows($result3);
$nabas = $linhas3+2;
$ident["G"] = "Geral";
$tamcampo["G"] = 3;
$pagina["G"] = "edu1_regenciaperiodo001.php?regencia=$regencia&nabas=$nabas&iTrocaTurma=$iTrocaTurma";
for($x=0;$x<$linhas3;$x++){
 db_fieldsmemory($result3,$x);
 $num = $x+2;
 if(trim($tipo)=="A"){
  $ident["A$ed41_i_codigo"] = $ed09_c_abrev;
  $tamcampo["A$ed41_i_codigo"] = 4;
  $pagina["A$ed41_i_codigo"] = "edu1_diarioavaliacao001.php?regencia=$regencia&ed41_i_codigo=$ed41_i_codigo&iTrocaTurma=$iTrocaTurma";
 }else{
  if($ed59_c_freqglob!="F"){
   $ident["R$ed41_i_codigo"] = $ed09_c_abrev;
   $tamcampo["R$ed41_i_codigo"] = 4;
   $pagina["R$ed41_i_codigo"] = "edu1_diarioresultado001.php?regencia=$regencia&ed43_i_codigo=$ed41_i_codigo&iTrocaTurma=$iTrocaTurma";
  }
 }
}
$ident["RF"] = "Resultado Final";
$tamcampo["RF"] = 11;
$pagina["RF"] = "edu1_diariofinal001.php?regencia=$regencia&iTrocaTurma=$iTrocaTurma";
$ident["AM"] = "Amparo";
$tamcampo["AM"] = 4;
$pagina["AM"] = "edu1_amparo001.php?regencia=$regencia&iTrocaTurma=$iTrocaTurma";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onAbort="alert('ai')">
<form name="formaba">
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <?
   $clcriaabas->abas_top      = "30";
   $clcriaabas->identifica    = $ident;
   $clcriaabas->sizecampo     = $tamcampo;
   $clcriaabas->src           = $pagina;
   $clcriaabas->iframe_height = "2000";
   $clcriaabas->iframe_width  = "100%";
   $clcriaabas->scrolling     = "no";
   $clcriaabas->cria_abas();
   ?>
  </td>
  <td valign="top" bgcolor="#CCCCCC" align="center" height="20">
   <table valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0">
    <tr><td height="5"></td></tr>
    <tr>
     <td>
      <input type="button" id="voltar" name="voltar" value="Fechar" title="Fechar" onclick="parent.top.corpo.document.getElementById('tab_aguarde').style.visibility = 'visible';parent.dados.location.href='edu1_diarioclasse004.php?turma=<?=$ed57_i_codigo?>&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>&codserieregencia=<?=$ed59_i_serie?>';parent.db_iframe_avaliacoes<?=$regencia?>.hide();">
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
 parent.db_iframe_avaliacoes<?=$regencia?>.liberarJanBTFechar('false');
 parent.db_iframe_avaliacoes<?=$regencia?>.liberarJanBTMinimizar('false');
 parent.db_iframe_avaliacoes<?=$regencia?>.liberarJanBTMaximizar('false');
</script>