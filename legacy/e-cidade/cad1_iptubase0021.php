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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptubase_classe.php");
include("classes/db_iptubaixa_classe.php");
$cliptubase = new cl_iptubase;
$cliptubaixa = new cl_iptubaixa;

$verilote          = false;
$verimatricula     = false;
$j18_utidadosdiver = false;

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(isset($testaentra)&& $testaentra=="true"){
  $result = $cliptubase->sql_record($cliptubase->sql_query($j01_matric,"z01_nome",""));
  if($cliptubase->numrows==0 || !isset($j01_matric) || isset($j01_matric) && $j01_matric==""){
    db_redireciona("cad1_iptubase002.php?invalido=true");   
  }
}
$sqlcfiptu = "select * from cfiptu where j18_anousu = ".db_getsession('DB_anousu');
//die($sqlcfiptu);
$rsparametro = db_query($sqlcfiptu);
$numrows     = pg_numrows($rsparametro);
if($numrows > 0){
  db_fieldsmemory($rsparametro,0);
}else{
  db_msgbox("Configure os parametros de calculo do iptu no modulo cadastro !!");
}

$rsBaixa = $cliptubaixa->sql_record($cliptubaixa->sql_query_file($j01_matric));
if($cliptubaixa->numrows > 0){
	$matriculaBaixada = 'true';
}else{
	$matriculaBaixada = 'false';
}

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
  <?
  if(isset($j01_matric)){
    $alterando=true;
  }else{
    $alterando=false;
  }
//db_msgbox("1");
  ?>
  function js_consultabaixa(matric){
   alert('Matricula baixada !');
   /* abre a tela de consulta do cadastro tecnico */
   iframe_lote.location.href = 'cad3_conscadastro_002.php?tiradiv=t&cod_matricula='+matric;
   /* desabilita as abas */
   document.formaba.lote.disabled = true;
   document.formaba.matricula.disabled = true;
   document.formaba.constr.disabled = true;
   document.formaba.escrit.disabled = true;
   document.formaba.imobiliaria.disabled = true;
   document.formaba.promitente.disabled = true;
   document.formaba.outros.disabled = true;
   document.formaba.dadosdiver.disabled = true;
   document.formaba.ender.disabled = true;
 }



 function js_novolote2(idmatricul,setor,quadra,bairro,loteam,zona,caract,setor1,bairro1,loteam1){
   document.formaba.matricula.disabled = true;
   iframe_lote.location.href="cad1_lotealt.php?idmatricu="+idmatricul+"&j34_setor="+setor+"&j34_quadra="+quadra+"&j34_bairro="+bairro+"&j34_loteam="+loteam+"&j34_zona="+zona+"&caracteristica="+caract+"&j30_descr="+setor1+"&j13_descr="+bairro1+"&j34_descr="+loteam1;
 }
/*function js_novolote2(idmatricul){
   document.formaba.matricula.disabled = true;
   iframe_lote.location.href="cad1_lotealt.php?idmatricu="+idmatricul;
 }*/


 function js_parentiframe(iframe,confere) {
  if (iframe=="alterando" && confere==true) {

  } else if (iframe=="lote" && confere==true) {

    document.formaba.lote.style.color = "#666666";
    document.formaba.lote.style.fontWeight = "normal";
    document.formaba.matricula.disabled = false;
    mo_camada('matricula',true,'Iframe2');
    
    iframe_iptubase.document.form1.j01_idbql.value=document.form1.idlote.value;
    
    if (document.form1.liberaconstrescr.value=="ok") {
      iframe_constrescr.document.form1.id_setor.value=document.form1.idsetor.value;
      iframe_constrescr.document.form1.id_quadra.value=document.form1.idquadra.value;
    }
    
    if (document.form1.liberaiptuconstr.value=="ok") {
      iframe_iptuconstr.document.form1.id_setor2.value=document.form1.idsetor.value;
      iframe_iptuconstr.document.form1.id_quadra2.value=document.form1.idquadra.value;
    }
    
    
    
  } else if (iframe=="matricula" && confere==true) {
    document.formaba.constr.disabled = false;
    document.formaba.escrit.disabled = false;
    document.formaba.imobiliaria.disabled = false;
    document.formaba.promitente.disabled = false;
    document.formaba.outros.disabled = false;
    document.formaba.dadosdiver.disabled = false;
    document.formaba.ender.disabled = false;
    
    
    iframe_lote.document.form1.idmatricu.value=document.form1.idmatricula.value;
    
    if (document.form1.liberadadosdiver.value=="ok") {
      iframe_dadosdiver.document.form1.j80_matric.value = document.form1.idmatricula.value;
    }
    
    if (document.form1.liberaiptuconstr.value=="ok") {
      iframe_iptuconstr.document.form1.j39_matric.value=document.form1.idmatricula.value;
      iframe_iptuconstr.document.form1.z01_nome.value=document.form1.nomematricula.value;
      iframe_iptuconstr.document.form1.id_setor2.value=document.form1.idsetor.value;
      iframe_iptuconstr.document.form1.id_quadra2.value=document.form1.idquadra.value;
    }
    if (document.form1.liberaconstrescr.value=="ok") {

      iframe_constrescr.document.form1.id_setor.value=document.form1.idsetor.value;
      iframe_constrescr.document.form1.id_quadra.value=document.form1.idquadra.value;
    }
    
    iframe_imobil.document.form1.j44_matric.value=document.form1.idmatricula.value;
    iframe_imobil.document.form1.z01_nomematri.value=document.form1.nomematricula.value;
    
    iframe_promitente.document.form1.j41_matric.value=document.form1.idmatricula.value;
    iframe_promitente.document.form1.z01_nomematri.value=document.form1.nomematricula.value;
    
    iframe_propri.document.form1.j42_matric.value=document.form1.idmatricula.value;
    iframe_propri.document.form1.z01_nomematri.value=document.form1.nomematricula.value;
    
    
    iframe_iptuender.document.form1.j43_matric.value=document.form1.idmatricula.value;
    iframe_iptuender.document.form1.z01_nome.value=document.form1.nomematricula.value;
    
    mo_camada('constr',true,'Iframe3');
  }
}
function mo_camada(idtabela,mostra,camada) {
  var tabela = document.getElementById(idtabela);
  var divs = document.getElementsByTagName("DIV");
  var tab  = document.getElementsByTagName("TABLE");
  var aba = eval('document.formaba.'+idtabela+'.name');
  var input = eval('document.formaba.'+idtabela);
  var alvo = document.getElementById(camada);
  for (var j = 0; j < divs.length; j++) {
    if (mostra) {
      if (alvo.id == divs[j].id) {
        divs[j].style.visibility = "visible" ;
        divs[j].style.zIndex = 99;
        divs[j].style.width = screen.availWidth;
        divs[j].style.height = screen.availHeight;
        
        if (divs[j].id == "Iframe3") {
          var tt = document.getElementById(divs[j].id+"_iframe").src;
          if (tt=="") {
            document.getElementById(divs[j].id+"_iframe").src = "cad1_iptuconstralt.php?alterando=true&id_setor=&j01_matric=<?=$j01_matric?>";
            document.form1.liberaiptuconstr.value="ok";
            
          }
        }
        
        if (divs[j].id == "Iframe4") {
          var tt = document.getElementById(divs[j].id+"_iframe").src;
          if (tt=="") {
            document.getElementById(divs[j].id+"_iframe").src = "cad1_constrescralt.php?alterando=true&id_setor=&j01_matric=<?=$j01_matric?>";
            document.form1.liberaconstrescr.value="ok";
          }
        }
        
      } else {
        if (divs[j].className == 'tabela') {
          divs[j].style.visibility = "hidden";
          divs[j].style.zIndex = 98;
          divs[j].style.width = screen.availWidth;
          divs[j].style.height = screen.availHeight;
          
        }
      }
    } else {
      if (alvo.id == divs[j].id) {
        divs[j].stlert(dadosveri[1]);
        divs[j].style.width = screen.availWidth;
        divs[j].style.height = screen.availHeight;
        
      }
    }
  }
  for (var x = 0; x < tab.length; x++) {
    if (tab[x].className == 'bordas') {
      for (y=0; y < document.forms['formaba'].length; y++) {
        tab[x].style.border = "1px outset #cccccc";
        tab[x].style.borderBottomColor = "#000000";
        document.formaba.lote.style.color = "#666666";
        document.formaba.lote.style.fontWeight = "normal";
        
        document.formaba.matricula.style.color = "#666666";
        document.formaba.matricula.style.fontWeight = "normal";
        document.formaba.constr.style.color = "#666666";
        document.formaba.constr.style.fontWeight = "normal";
        document.formaba.escrit.style.color = "#666666";
        document.formaba.escrit.style.fontWeight = "normal";
        document.formaba.imobiliaria.style.color = "#666666";
        document.formaba.imobiliaria.style.fontWeight = "normal";
        document.formaba.promitente.style.color = "#666666";
        document.formaba.promitente.style.fontWeight = "normal";
        document.formaba.outros.style.color = "#666666";
        document.formaba.outros.style.fontWeight = "normal";
        document.formaba.ender.style.color = "#666666";
        document.formaba.ender.style.fontWeight = "normal";
        document.formaba.isencao.style.color = "#666666";
        document.formaba.isencao.style.fontWeight = "normal";
        document.formaba.dadosdiver.style.color = "#666666";
        document.formaba.dadosdiver.style.fontWeight = "normal";
      }
      if (aba == tab[x].id) {
        tab[x].style.border = "3px outset #999999";
        tab[x].style.borderBottomWidth = "0px";
        tab[x].style.borderRightWidth = "1px";
        tab[x].style.borderLeftColor =  "#000000";
        tab[x].style.borderTopColor =  "#3c3c3c";
        tab[x].style.borderRightColor =  "#000000";
        tab[x].style.borderRightStyle =  "inset";
      }
      input.style.color = "black";
      input.style.fontWeight = "bold";
    }
  }
  
}

function js_veripros(nome) {
  cgm_iptubase = iframe_iptubase.document.form1.j01_numcgm.value;
  cgm_propri = iframe_propri.document.form1.j42_numcgm.value;
  cgm_promitente = iframe_promitente.document.form1.j41_numcgm.value;
  
  cgm_selpropri = iframe_propri.document.form1.cgmpropri.value;
  cgm_selpromitente = iframe_promitente.document.form1.cgmpromi.value;
  
  proprimatriz="";
  promimatriz="";
  proprimatriz=cgm_selpropri.split("#");
  promimatriz=cgm_selpromitente.split("#");
  if (nome=="iptubase") {
    for (var i=0; i<proprimatriz.length; i++) {
      if (cgm_iptubase==proprimatriz[i]) {
        alert("Nome cadastrado como Outros Proprietários! Verifique!");
        return false;
        break;
      }
    }
    for (var i=0; i<promimatriz.length; i++) {
      if (cgm_iptubase==promimatriz[i]) {
        alert("Nome cadastrado como Promitente ou Possuidor! Verifique!");
        return false;
        break;
      }
    }
  } else if (nome=="propri") {
    for (var x=0; x<promimatriz.length; x++) {
      if (promimatriz[x]==cgm_propri) {
        alert("Nome cadastrado como Promitente ou Possuidor! Verifique!");
        return false;
        break;
      }
      
    }
    if (cgm_iptubase==cgm_propri) {
      alert("Nome já cadastrado como proprietário principal! Verifique!");
      return false;
    }
  }
  
  return true;
  
}

function js_pripromi(){
  //função para não dar erro em promitente
}

</script>
<style>
  a {text-decoration:none;
  }
  a:hover {text-decoration:none;
   color: #666666;
 }
 a:visited {text-decoration:none;
   color: #999999;
 }
 a:active {
  color: black;
  font-weight: bold; 
}  
.nomes {background-color: transparent;
  border:none;
  text-align: center;
  font-size: 11px;
  color: #666666;
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
.bordas{border: 1px outset #cccccc;
  border-bottom-color: #000000;
}
.bordasi{border: 0px outset #cccccc;
}
.novamat{border: 2px outset #cccccc;
 border-right-color: darkblue;
 border-bottom-color: darkblue;
 background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad=" js_trocacordeselect();">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table valign="top" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr> 
      <form name="formaba" method="post" id="formaba" >
        <td height="" align="left" valign="top" bgcolor="#CCCCCC">
         <table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
           <tr>
             <td>
              <table class="bordas" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " id="lote"  cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td nowrap>
                    <input readonly name="lote" class="nomes" style="font-weight:bold; color:black" type="text" value="Lote" title="Cadastro de Lote" size="4" maxlength="7" onClick="mo_camada('lote',true,'Iframe1');"> 
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="matricula" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td  id="link_matric" nowrap>
                    <input <?=($alterando==false?"disabled":"")?> readonly name="matricula" type="text" value="Matrícula" size="10" maxlength="10"  class="nomes"  title="Matrícula do Proprietario"  onClick="mo_camada('matricula',true,'Iframe2');">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="constr" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td nowrap id="link_constr">
                    <input <?=($alterando==false?"disabled":"")?>  readonly type="text" value="Construções" size="12" maxlength="12"  class="nomes"  name="constr" title="Construções" onClick="mo_camada('constr',true,'Iframe3');">
                    <input type="hidden" name="ve_constr" value="false">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="escrit" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td id="link_constrescr" nowrap>
                    <input <?=($alterando==false?"disabled":"")?> readonly type="text" value="Escrituradas" size="12" maxlength="12"  class="nomes"  name="escrit" title="Construções Escrituradas" onClick="mo_camada('escrit',true,'Iframe4');">
                    <input type="hidden" name="ve_constrescr" value="false">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="imobiliaria" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td id="link_imobil" nowrap>
                    <input <?=($alterando==false?"disabled":"")?> readonly type="text" value="Imobiliária" size="11" maxlength="11"  class="nomes"  name="imobiliaria" title="Manutenção de Imobiliária"  onClick="mo_camada('imobiliaria',true,'Iframe5');">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="promitente" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td id="link_promit" nowrap>
                    <input <?=($alterando==false?"disabled":"")?> readonly type="text" value="Promitente" size="10" maxlength="10"  class="nomes"  name="promitente" title="Manutenção de Promitente Comprador" onClick="mo_camada('promitente',true,'Iframe6');">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="outros" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td id="link_propri" nowrap >
                    <input <?=($alterando==false?"disabled":"")?> readonly type="text" value="Outros Propr" size="12" maxlength="12"  class="nomes"  name="outros"  title="Outros Proprietários"  onClick="mo_camada('outros',true,'Iframe7');">
                  </td>
                </tr>
              </table>
            </td>
            <td>

              <table border="0" class="bordas" id="ender" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td id="link_entreg" nowrap>
                    <input <?=($alterando==false?"disabled":"")?> readonly type="text" value="End.Entrega" size="11" maxlength="11"  class="nomes"  name="ender" title="Manutenção de Endereços de Entrega" onClick="mo_camada('ender',true,'Iframe8');">
                  </td>
                </tr>
              </table>

            </td>

            <td>
              <table border="0" class="bordas" id="isencao" cellpadding="3" cellspacing="0" width="12%">
                <tr>
                  <td id="link_isencao" nowrap>
                    <input <?=($alterando==false?"disabled":"")?> readonly type="text" value="Isenção" size="11" maxlength="11" class="nomes" name="isencao" title="Isenção" onClick="mo_camada('isencao', true, 'Iframe10')" />
                  </td>
                </tr>
              </table>
            </td>            

            <td>
              <!-- criado por robson -->
              <table border="0" class="bordas" id="dadosdiver" cellpadding="<?=($j18_utidadosdiver=='t'?"3":"0")?>" cellspacing="0" width="12%">
               <tr>
                 <td id="link_dadosdiver" nowrap>
                   <input <?=($alterando==false?"disabled":"")?> readonly type="<?=($j18_utidadosdiver=='t'?"text":"hidden")?>" value="Dados diversos" size="11" maxlength="11"  class="nomes"  name="dadosdiver" title="Manutenção de dados diversos" onClick="mo_camada('dadosdiver',true,'Iframe9');">
                 </td>
               </tr>
             </table>
           </td>
         </tr>
       </table>
     </td>
   </form>
 </tr>
 <tr>
  <form name="form1" method="post" id="form1" >
    <td nowrap>  
      <input name="idlote"           type="hidden" value="<?=@$idlote?>" > 
      <input name="idmatricula"      type="hidden" value="" /> 
      <input name="nomematricula"    type="hidden" value="" /> 
      <input name="idsetor"          type="hidden" value="" /> 
      <input name="idquadra"         type="hidden" value="" /> 
      <input name="liberaconstrescr" type="hidden" value="" /> 
      <input name="liberaiptuconstr" type="hidden" value="" /> 
      <input name="liberadadosdiver" type="hidden" value="" /> 
    </td>
    <td>
     <div class="tabela" id="Iframe2" style="position:absolute; left:0px; top:47px; z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
       <iframe id="Iframe2_iframe" id="iptubase" frameborder="0" name="iframe_iptubase"  src="cad1_iptubasealt.php?alterando=true&j01_matric=<?=$j01_matric?>" scrolling="no" height=100% width="100%"></iframe> 
     </div>

     <div class="tabela" id="Iframe3" style="position:absolute; left:0px; top:47px; z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
      <iframe id="Iframe3_iframe" name="iframe_iptuconstr" frameborder="0"   scrolling="no"  height="100%" width="100%"></iframe>
    </div>

    <div class="tabela" id="Iframe4" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
     <iframe id="Iframe4_iframe" name="iframe_constrescr" frameborder="0"   scrolling="no"  height=100% width="100%"></iframe>
   </div>

   <div class="tabela" id="Iframe5" style="position:absolute; left:0px; top:47px; z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
    <iframe id="Iframe5_iframe" name="iframe_imobil" frameborder="0"  src="cad1_imobilalt.php?alterando=true&j01_matric=<?=$j01_matric?>" scrolling="no"  height="100%" width="100%"></iframe>
  </div>

  <div class="tabela" id="Iframe6" style="position:absolute; left:0px; top:47px; z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
    <iframe id="Iframe6_iframe" name="iframe_promitente"  frameborder="0" src="cad1_promitentealt.php?alterando=true&j01_matric=<?=$j01_matric?>" scrolling="no"  height="100%" width="100%"></iframe>
  </div>

  <div class="tabela" id="Iframe7" style="position:absolute; left:0px; top:47px; z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
    <iframe id="Iframe7_iframe" name="iframe_propri" frameborder="0"  src="cad1_proprialt.php?alterando=true&j01_matric=<?=$j01_matric?>" scrolling="no" height="100%" width="100%"></iframe>
  </div> 
  <div class="tabela" id="Iframe8" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: <?=($alterando==false?"hidden":"visible")?>;">
    <iframe id="Iframe8_iframe" name="iframe_iptuender" frameborder="0"  src="cad1_iptuenderalt.php?alterando=true&j01_matric=<?=$j01_matric?>"  scrolling="no" height="100%" width="100%"></iframe>
  </div>

  <!-- criado por robson -->
  <div class="tabela" id="Iframe9" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: hidden;">
   <iframe name="iframe_dadosdiver"  frameborder="0"  class="bordasi"  leftmargin="0" topmargin="0"  src="cad1_iptudiversosalt002.php?alterando=true&j80_matric=<?=$j01_matric?>"  scrolling="no" height="100%" width="100%"></iframe>
 </div>

 <div class="tabela" id="Iframe10" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: hidden;">
 <iframe name="iframe_isencao"  frameborder="0"  class="bordasi"  leftmargin="0" topmargin="0"  src="cad4_iptuisen002.php?alterando=true&j46_matric=<?=$j01_matric?>"  scrolling="no" height="100%" width="100%"></iframe>
</div>

<div class="tabela" id="Iframe1" style="position:absolute; left:0px; top:47px; z-index:99; visibility: visible;">
  <iframe name="iframe_lote" frameborder="0"  src="cad1_lotealt.php?alterando=true&j01_matric=<?=$j01_matric?>" height="100%" scrolling="no"   width="100%"></iframe>
</div>
<div id="load"  style="position:absolute; left:300px; top:167px; z-index:99;visibility: visible;">
  <b> Processando.&nbsp;Aguarde...</b>
</div>

</td>
</form>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>
 mo_camada('lote',true,'Iframe1');
</script>
<?
if($matriculaBaixada == 'true'){
  echo "<script> js_consultabaixa(".$j01_matric."); </script>";
//	exit;
}
?>