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
include("classes/db_iptuisen_classe.php");
include("classes/db_isenexe_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_iptutaxa_classe.php");
include("classes/db_isentaxa_classe.php");
include("classes/db_isenproc_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao=1;
$db_opcao=1;
$alterando=false;
$pesq=true;  

$clisenproc = new cl_isenproc;
$clisenexe = new cl_isenexe;


$clisentaxa = new cl_isentaxa;

$cliptutaxa = new cl_iptutaxa;
$cliptutaxa->rotulo->label();

$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();

$cliptuisen = new cl_iptuisen;
$cliptuisen->rotulo->label();

$rotulocampo = new rotulocampo;
$rotulocampo->label("z01_nome");
$rotulocampo->label("j45_descr");
$rotulocampo->label("j34_area");
$rotulocampo->label("p58_codproc");
$rotulocampo->label("p58_requer");

$result = $cliptubase->sql_record($cliptubase->sql_query("","cgm.z01_nome as z01_nomematri","","j01_matric=$j46_matric"));
if($cliptubase->numrows==0){
  db_redireciona("cad4_iptuisen001.php?invalido=true");
}else{
  @db_fieldsmemory($result,0);
}


if(isset($incluir) || isset($alterar)){
  $data = date("Y-m-d",db_getsession("DB_datausu"));
  $dat = split("-",$data);
  $cliptuisen->j46_dtinc=$data;
  $cliptuisen->j46_dtinc_dia=$dat[2];
  $cliptuisen->j55_dtinc_mes=$dat[1];
}

if(isset($j46_codigo)&&$j46_codigo=="nova"){ 
   $result = $cliptubase->sql_record($cliptubase->sql_query($j46_matric,"z01_nome",""));
   @db_fieldsmemory($result,0);
   $j46_codigo="";
}else if(isset($incluir)){
  $j46_dtinc_ano =  $cliptuisen->j55_dtinc_ano=$dat[2];
  db_inicio_transacao();
    $trans_erro = false;
    $cliptuisen->incluir($j46_codigo);
    $erro_msg=$cliptuisen->erro_msg;
    if($cliptuisen->erro_status=="0"){
      $trans_erro = true;
    }else{  
      if (isset($p58_codproc)&&$p58_codproc!=""){
        $clisenproc->j61_codigo=$cliptuisen->j46_codigo;
        $clisenproc->j61_codproc=$p58_codproc;
        $clisenproc->incluir();
        if($clisenproc->erro_status=="0"){
	  $trans_erro = true;
	  $erro_msg=$clisenproc->erro_msg;
	}
      }
      if($trans_erro==false){
      for($ano=$j46_dtini_ano;$ano<=$j46_dtfim_ano;$ano++){
        $j47_codigo=$cliptuisen->j46_codigo;
        $clisenexe->j47_codigo=$j47_codigo;
        $clisenexe->anousu=$ano;
        $clisenexe->incluir($j47_codigo,$ano);
        if($clisenexe->erro_status=="0"){
          $trans_erro = true;
	  $erro_msg=$clisenproc->erro_msg;
  	  break;
        }
      }
      }
      if($trans_erro==false){
       	$taxa = split("X",$dadostaxa);
	for($r=0; $r<sizeof($taxa); $r++){
	  if($taxa[$r]!=""){
      	    $dad = split("yy",$taxa[$r]);//dad[0]->valor percentual dad[1] receita
 	    $clisentaxa->j56_codigo=$cliptuisen->j46_codigo;  
   	    $clisentaxa->j56_receit=$dad[1];
 	    $clisentaxa->j56_perc=$dad[0];
	    $clisentaxa->incluir($cliptuisen->j46_codigo,$dad[1]);
            if($clisentaxa->erro_status=="0"){
              $trans_erro = true;
	      $erro_msg=$clisentaxa->erro_msg;
              break;
            }
	  }  
        }
      } 
    }  
  db_fim_transacao($trans_erro);
}else if(isset($excluir)){
 db_inicio_transacao();
    $clisenexe->j47_codigo=$j46_codigo;
    $clisenexe->excluir($j46_codigo);
    $clisentaxa->j56_codigo=$j46_codigo;
    $clisentaxa->excluir($j46_codigo);
    $result_proc = $clisenproc->sql_record($clisenproc->sql_query(null,"p58_codproc,p58_requer",null,"j61_codigo=$j46_codigo"));
    if ($clisenproc->numrows!=0){
      $clisenproc->excluir(null,"j61_codigo=$j46_codigo and j61_codproc=$p58_codproc");
    }
    $cliptuisen->excluir($j46_codigo);
  db_fim_transacao();
}else if(isset($alterar)){
    db_inicio_transacao();
    $clisenexe->j47_codigo=$j46_codigo;
    $clisenexe->excluir($j46_codigo);
    
    $j46_dtinc_ano =  $cliptuisen->j55_dtinc_ano=$dat[2];
      for($ano=$j46_dtini_ano;$ano<=$j46_dtfim_ano;$ano++){
        $clisenexe->j47_codigo=$j46_codigo;
        $clisenexe->anousu=$ano;
        $clisenexe->incluir($j46_codigo,$ano);
        if($clisenexe->erro_status=="0"){
          $trans_erro = true;
  	  break;
        }
      }
    $clisentaxa->j56_codigo=$j46_codigo;
    $clisentaxa->excluir($j46_codigo);
	$taxa = split("X",$dadostaxa);
	
	for($r=0; $r<sizeof($taxa); $r++){
	  if($taxa[$r]!=""){
      	    $dad = split("yy",$taxa[$r]);//dad[0]->valor percentual dad[1] receita
 	    $clisentaxa->j56_codigo=$cliptuisen->j46_codigo;  
   	    $clisentaxa->j56_receit=$dad[1];
 	    $clisentaxa->j56_perc=$dad[0];
	    $clisentaxa->incluir($j46_codigo,$dad[1]);
            if($clisentaxa->erro_status=="0"){
              $trans_erro = true;
              break;
            }
	  }  
        }
    
    $cliptuisen->alterar($j46_codigo);
    $clisenproc->j61_codproc = $p58_codproc;
    $clisenproc->j61_codigo = $j46_codigo;
    if ($p58_codproc == "") {
      $clisenproc->excluir(null,"j61_codigo=$j46_codigo");
    } else {
      $result_proc = $clisenproc->sql_record($clisenproc->sql_query_file(null,"*",null,"j61_codigo=$j46_codigo"));
      if ($clisenproc->numrows > 0) {
        $clisenproc->alterar_where("j61_codproc=$p58_codproc and j61_codigo=$j46_codigo");
        if($clisenproc->erro_status=="0"){
	  $sqlerro=true;
	}
      } else {
        $clisenproc->incluir();
        if($clisenproc->erro_status=="0"){
	  $sqlerro=true;
	}
      }
    }
  db_fim_transacao();
}else if(isset($j46_matric) && isset($j46_codigo)){ 
    $sql=$cliptuisen->sql_query("$j46_codigo","iptuisen.*,j45_descr,j56_receit,j56_perc","","");
    $result = $cliptuisen->sql_record($sql);
    @db_fieldsmemory($result,0);
    $db_opcao="2"; 
    $recoloca="ok";//libera para recolocar os valores de iptu taxa
    $codigo=$j46_codigo;
    $result_proc = $clisenproc->sql_record($clisenproc->sql_query(null,"p58_codproc,p58_requer",null,"j61_codigo=$j46_codigo"));
    if ($clisenproc->numrows!=0){
      db_fieldsmemory($result_proc,0);
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
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}

.bordas {
         border: 1;
         border-color: solid #999999;
         background-color: solid #999999;
				  
}
-->
</style>
<script>
<?if(isset($j46_matric)){?>
  function js_trocaid(valor){
    if(valor!=""){
      location.href="cad4_iptuisen002.php?j46_matric=<?=$j46_matric?>&j46_codigo="+valor;
    } 
  }
<?}?>
function js_carreg(){
   document.form1.j46_tipo.focus();  
   js_trocacordeselect();
}

function js_pegare(){
 var obj=   document.getElementsByTagName("INPUT");
 var val="";
 var valor="";
 var x="";
 var expr = new RegExp("[^0-9\.]+");
 if(document.form1.j45_descr.value == '' || document.form1.j46_tipo.value == ''){
   alert('Tipo de isenção não encontrado no cadastro de isenções');   
   document.form1.j46_tipo.value = '';
   document.form1.j46_tipo.focus();  
   return false;
 }
 for(var i=0; i<obj.length; i++){
   var matri= obj[i].name.split("xx");
   if(obj[i].id=="receit"){
     valor =obj[i].value; 
     if(obj[i].value.match(expr)) {
       alert(matri[0]+" deve ser preenchido somente com números decimais!");
       obj[i].select();
       return false;
     }
     if(obj[i].value=="") {
       alert(matri[0]+" deve ser preenchido!");
       obj[i].select();
       return false;
     }
     val+=x+obj[i].value+"yy"+matri[1];//valor percentual yy numero da receita 
     x="X";
   }
 }  
  document.form1.dadostaxa.value=val;
  return true;
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_carreg();"   >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0" align="center">
<form name="form1" method="post" action=""  onSubmit="return js_verifica_campos_digitados();"  >
  <tr align="center">
    <td valign="top" bgcolor="#CCCCCC" align="center">
    <input name="dadostaxa" type="hidden" value="">
    <table border="0" align="center">
      <tr>
        <td nowrap title="<?=@$Tj46_matric?>">
	<a href='' onclick='js_mostrabic_matricula();return false;'><?=@$Lj46_matric?></a>
        </td>
        <td> 
<?
db_input('j46_matric',10,$Ij46_matric,true,'text',3," onchange='js_pesquisaj46_matric(false);'");         
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'','z01_nomematri');
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj46_codigo?>">
         <?=@$Lj46_codigo?>
        </td>
        <td> 
<? 
db_input('j46_codigo',4,"",true,'text',3,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj46_tipo?>">
<?
db_ancora(@$Lj46_tipo,"js_pesquisaj46_tipo(true);document.form1.j45_descr.value='';",$db_opcao);
?>
        </td>
        <td> 
<?
db_input('j46_tipo',4,$Ij46_tipo,true,'text',$db_opcao,"onchange='js_pesquisaj46_tipo(false);js_limpanome();'");
//document.form1.j45_descr.value='';'");
db_input('j45_descr',40,$Ij45_descr,true,'text',3,'');
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj46_dtini?>">
        <?=@$Lj46_dtini?>
        </td>
        <td> 
<?
db_inputdata('j46_dtini',@$j46_dtini_dia,@$j46_dtini_mes,@$j46_dtini_ano,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj46_dtfim?>">
         <?=@$Lj46_dtfim?>
        </td>
        <td> 
<?
db_inputdata('j46_dtfim',@$j46_dtfim_dia,@$j46_dtfim_mes,@$j46_dtfim_ano,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj46_perc?>">
        <?=@$Lj46_perc?>
        </td>
        <td> 
<?
db_input('j46_perc',10,$Ij46_perc,true,'text',$db_opcao,"onChange='js_validapercentual(this);'")
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Área total do lote">
	<strong>Área do lote:</strong>
        </td>
        <td colspan="2">
<?
$sql_areatot = "select j34_area from iptubase inner join lote on j34_idbql = j01_idbql where j01_matric = $j46_matric;";
$result_areatot = $cliptubase->sql_record($sql_areatot);
if($cliptubase->numrows>0){
  db_fieldsmemory($result_areatot,0);
}
db_input('j34_area',10,$Ij34_area,true,'text',3,"");
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Área a isentar">
	<strong>Área isenta:</strong>
        </td>
        <td>
<?
db_input('j46_arealo',10,$Ij46_arealo,true,'text',$db_opcao,"onchange = 'js_preenchedif(this.name,this.value,document.form1.j34_area.value);'");
?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<strong>Diferença:</strong>
<?
db_input('j46_dif',10,$Ij46_arealo,true,'text',3,"");
?>
        </td>
      </tr>
      <tr>
        <td> 
<?
$j46_idusu=db_getsession("DB_id_usuario");

db_input('j46_idusu',4,$Ij46_idusu,true,'hidden',$db_opcao,"")
?> 
        </td>
      </tr>
  <tr>
    <td nowrap title="<?=@$Tp58_codproc?>">
       <?
       db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p58_codproc',10,$Ip58_codproc,true,'text',$db_opcao," onchange='js_pesquisap58_codproc(false);'")
?>
       <?
db_input('p58_requer',40,$Ip58_requer,true,'text',3,'')
       ?>
    </td>
  </tr>
      <tr>
        <td nowrap title="<?=@$Tj46_hist?>">
        <?=@$Lj46_hist?>
        </td>
        <td> 
<?
db_textarea('j46_hist',5,52,$Ij46_hist,true,'text',$db_opcao,"")
?>
        </td>
      </tr>
    </table>
    <input name="incluir" type="submit" id="incluir" value="Incluir" onclick="return js_pegare()"  <?=($db_opcao!=1?"disabled":"")?> >
    <input name="alterar" type="submit" id="alterar" value="Alterar" onclick="return js_pegare()"  <?=($db_opcao!=2?"disabled":"")?> >
    <input name="excluir" type="submit" id="excluir" value="Excluir" onclick="return js_pegare()"  <?=($db_opcao!=2?"disabled":"")?>>
    <input name="nova" type="button" id="nova" value="Nova Isenção" onclick="js_trocaid('nova')">
    <input name="voltar" type="button" id="volta" value="Voltar" onclick="js_volta()">
    </td>
    <td align="left" width="40%" valign="top" bgcolor="#CCCCCC">
      <table border=0>
        <tr>
      
<?
if(isset($j46_matric)){

  if(!isset($excluir)){

    $result = $cliptuisen->sql_record($cliptuisen->sql_query_file("","j46_codigo as codigo","","j46_matric=$j46_matric"));
    if($cliptuisen->numrows>0){
      db_fieldsmemory($result,0);
    }  
  }
  $num=$cliptuisen->numrows;
  if($num!=0){
  echo  "<td>";
?>
           <table border="0" cellpadding="0" cellspacing="0">
              <tr><td><b>Isenções já Cadastradas</b></td></tr>
              <tr>
                <td align="center">
<?                               
    echo "<select name='selcod' onchange='js_trocaid(this.value)'  size='".($num>4?5:($num+1))."'>";
    echo "<option value='nova' ".(!isset($j46_matric)?"selected":"").">Nova</option>";
    if(isset($recoloca) && $recoloca!=""){
      $idcod=$j46_codigo;
    }else{
      $idcod=""; 
    }
    //$testasel=true;
    for($i=0;$i<$num;$i++){
      db_fieldsmemory($result,$i);             
      if($codigo!=$idcod){
        echo "<option  value='".$codigo."' ".($codigo==$idcod?"selected":"").">".$codigo."</option>";
      }
    }
?>
                </td>
              </tr>
            </table>
        </td>   
<?
  }
} 
?>
      </tr>  
<?
if(@$j46_tipo==""||isset($incluir)){ 
  $j46_codigo="";
}
?>
      <tr>
        <td align="center" >
           <table border='1' class="bordas">
<?
$data       = date("Y",db_getsession("DB_datausu"));

$sSqlTaxas  = " select distinct  ";
$sSqlTaxas .= "        k02_descr, ";
$sSqlTaxas .= "        k02_codigo as j19_receit ";
$sSqlTaxas .= "   from iptucadtaxa  ";
$sSqlTaxas .= "        inner join iptucadtaxaexe on iptucadtaxa.j07_iptucadtaxa = iptucadtaxaexe.j08_iptucadtaxa ";
$sSqlTaxas .= "                                 and iptucadtaxaexe.j08_anousu   = ".db_getsession('DB_anousu'); 
$sSqlTaxas .= "        inner join tabrec         on tabrec.k02_codigo           = iptucadtaxaexe.j08_tabrec ";
$sSqlTaxas .= "  order by k02_codigo ";
$result     = $cliptutaxa->sql_record($sSqlTaxas);
$numrows    = $cliptutaxa->numrows;

echo "<tr><td valign='top' align='center'><b>Taxa de IPTU</b></td><td valign='top'><b>Valor(%)</b></td></tr>";
for($n=0; $n<$numrows; $n++){
  db_fieldsmemory($result,$n);
  if(isset($recoloca) && $recoloca!=""){ 
    $resulta = $clisentaxa->sql_record($clisentaxa->sql_query_file($j46_codigo,$j19_receit,"j56_perc"));
    if($clisentaxa->numrows>0){
      db_fieldsmemory($resulta,0);
    }else{
      $j56_perc=0;  
    }  
  }
    
  echo "<tr><td>$j19_receit - $k02_descr </td><td><input id='receit' name='".$k02_descr."xx".$j19_receit."' type='text' size='1' onChange=\"js_validapercentual(this);\"  onKeyUp=\"js_ValidaCampos(this,1,'$k02_descr','f','f',event);\" value='".@$j56_perc."'  ".($db_opcao==3?"disabled":"")."  ></td></tr>";  
}  
?>	      
          </table>
	</td>
        </tr>	
     </table>
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_validapercentual(obj){
  valor = new Number(obj.value);
	if(valor > 100 ){
		alert('Percentual nao pode ser maior que 100 !');
		obj.value = '0';
		obj.focus();
		obj.select();
	}  	
}


function js_limpanome(){
//  alert('aki');
  document.form1.j45_descr.value='';  
}

function js_mostrabic_matricula(){
  js_OpenJanelaIframe('','db_iframe_cadastro','cad3_conscadastro_002.php?cod_matricula=<?=@$j46_matric?>','Pesquisa',true);
}
function js_pesquisap58_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1; 
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_cgm.hide();
}
function js_volta(){
  location.href = 'cad4_iptuisen001.php ';                      
}
function js_pesquisaj46_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_tipoisen.php?funcao_js=parent.js_mostratipoisen1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_tipoisen.php?pesquisa_chave='+document.form1.j46_tipo.value+'&funcao_js=parent.js_mostratipoisen','Pesquisa',false);
  }
}
function js_mostratipoisen(chave,erro){
  document.form1.j45_descr.value = chave; 
  if(erro==true){ 
    document.form1.j46_tipo.focus(); 
    document.form1.j46_tipo.value = ''; 
  }
}
function js_mostratipoisen1(chave1,chave2){
  document.form1.j46_tipo.value = chave1;
  document.form1.j45_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','','func_iptuisen.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_pesquisaj46_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','','func_iptubase.php?pesquisa_chave='+document.form1.j46_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j46_matric.focus(); 
    document.form1.j46_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j46_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','','func_iptuisen.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','','func_nome.php?funcao_js=parent.js_mostra1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','','func_nome.php?pesquisa_chave='+document.form1.j46_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
  }
}
function js_mostra1(chave1,chave2){
  document.form1.j46_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j46_numcgm.focus();
    document.form1.j46_numcgm.value="";
  }
}
function js_preenchedif(nome,valor1,valor2){
  valor1 = parseInt(valor1);
  valor2 = parseInt(valor2);
  if(valor1>valor2){
    alert("A área a isentar deve ser menor que a área total do lote.");
    eval('document.form1.'+nome+'.value = "";');
    eval('document.form1.'+nome+'.focus();');
    document.form1.j46_dif.value = "";
  }else{
    if(valor1 != "" || valor1==0){
      document.form1.j46_dif.value = valor2 - valor1;
    }else{
      document.form1.j46_dif.value = "";
    }
  } 
}
</script>
<?
if(isset($incluir)||isset($excluir)||isset($alterar)){
  if($cliptuisen->erro_status=="0"){
    db_msgbox($erro_msg);
    //$cliptuisen->erro(true,false);
    if($cliptuisen->erro_campo!=""){
      echo "<script> document.form1.".$cliptuisen->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuisen->erro_campo.".focus();</script>";
    }
  }else{
    $cliptuisen->erro(true,false);
    db_redireciona("cad4_iptuisen002.php?j46_matric=$j46_matric&j46_codigo=nova");
  }
}
?>