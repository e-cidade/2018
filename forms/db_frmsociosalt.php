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

//MODULO: issqn
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsocios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if(empty($excluir) && empty($alterar) && isset($opcao) && $opcao!=""){
  $result24=$clsocios->sql_record($clsocios->sql_query($q95_cgmpri,$q95_numcgm,'z01_nome,q95_perc'));
  db_fieldsmemory($result24,0);
  $result25=$clcgm->sql_record($clcgm->sql_query_file($q95_numcgm,'z01_nome as z01_nome_socio'));
  db_fieldsmemory($result25,0);
}
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif((isset($opcao) && $opcao=="excluir" ) || (isset($db_opcao) && $db_opcao==3)){
    $db_opcao = 3;
}else{  
    $db_opcao = 1;
} 
    $sql = $clsocios->sql_query_socios($q95_cgmpri,"","sum(q95_perc) as somaval ");
    $result_testaval=pg_exec($sql);
    if (pg_numrows($result_testaval)!=0){
      db_fieldsmemory($result_testaval,0);
      
    }else $somaval=0;
?>
<form name="form1" method="post" action="iss1_socios004.php" >
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <td height="140" align="center" valign="top">
<center>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Socios</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq95_cgmpri?>">
       <?=$Lq95_cgmpri?>
    </td>
    <td> 
				<?
				  db_input('somaval',20,"",true,'hidden',3);
				  db_input('q95_cgmpri',6,$Iq95_cgmpri,true,'text',3);
				?>
       <?
       $z01_nome = stripslashes($z01_nome);
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq95_numcgm?>">
       <?
					if($db_opcao==2){
					  $str_01=3;
					}else{  
					  $str_01=$db_opcao;
					}
          db_ancora(@$Lq95_numcgm,"js_pesquisaq95_numcgm(true);",$str_01);
       ?>
       <input type='hidden' id='fisico_juridico' style="width: 50px;" />
    </td>
    <td> 
				<?
				  db_input('q95_numcgm',6,$Iq95_numcgm,true,'text',$str_01," onchange='js_pesquisaq95_numcgm(false);'")
				?>
       <?
         db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '', 'z01_nome_socio');
       ?>
    </td>
    
  <tr>
    <td nowrap title="<?=@$Tq95_tipo?>">
       <?=@$Lq95_tipo?>
    </td>
    <td> 
      <?
        $aTipo = array('0' => "Selecione...", '1' => "Sócio", '2' => "Responsável MEI", '3' => "Responsável");
       // ksort($aTipo);
        db_select('q95_tipo', $aTipo, true, $db_opcao,"onchange='js_mostraValr_capital();'");
      ?>
    </td>
  </tr>      
    
    
  <tr id='valor_capital' style="display: none;">
    <td nowrap title="<?=@$Tq95_perc?>">
       <?=@$Lq95_perc?>
    </td>
    <td> 
			<?
			db_input('q95_perc',15,$Iq95_perc,true,'text',$db_opcao,"");
			?>
    </td>
  </tr>
  <? 
    $sAcaoClick = "";
    if ($db_opcao == 33 || $db_opcao == 3) {
    	
    	$sAcaoClick = "";
    } else {
    	$sAcaoClick = " onclick='return js_verificatipo();'";
    }
    //echo $sAcaoClick;die();
  ?>
  
  
  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"	
        type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
           <?=($db_botao==false?"disabled":"")?> <?=$sAcaoClick ?> >
     <!--  <input name="cancelar" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?//=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> > -->
    </td>
  </tr>
  </table>
</fieldset>  
  </center>
  </td>
  </tr>
  
  <tr>
   <td colspan="2"> &nbsp;</td>
  </tr>
  
  <tr>
   <td valign="top">
   <?
    $chavepri= array("q95_cgmpri"=>$q95_cgmpri,"q95_numcgm"=>@$q95_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
//    $cliframe_alterar_excluir->sql     =$clsocios->sql_query_socios($q95_cgmpri,"","q95_numcgm,soc.z01_nome as DBtxtnomesocio,q95_perc,q95_cgmpri");
//    $cliframe_alterar_excluir->campos  ="q95_numcgm,DBtxtnomesocio,q95_perc";
//  não estava aparecendo as informações com o alias para a coluno
//  Cristian Tales.
    $sWhereSocios  = "     q95_cgmpri = $q95_cgmpri ";
    
    $sCampoQ95Tipo  = "  case                                      ";
    $sCampoQ95Tipo .= "   when q95_tipo = 1 then 'Sócio'           ";
    $sCampoQ95Tipo .= "   when q95_tipo = 2 then 'Responsável MEI' ";
    $sCampoQ95Tipo .= "   else 'Responsável'                       ";
    $sCampoQ95Tipo .= "  end    as tipo                            ";
    
    $cliframe_alterar_excluir->sql        = $clsocios->sql_query_socios(null, null, "q95_numcgm,soc.z01_nome,q95_perc,q95_cgmpri,$sCampoQ95Tipo", null, $sWhereSocios);
    $cliframe_alterar_excluir->campos     = "q95_numcgm,z01_nome,q95_perc, tipo ";
    $cliframe_alterar_excluir->legenda    = "SÓCIOS CADASTRADOS";
    $cliframe_alterar_excluir->msg_vazio  = "Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec = "darkblue";
    $cliframe_alterar_excluir->textocorpo = "black";
    $cliframe_alterar_excluir->fundocabec = "#aacccc";
    $cliframe_alterar_excluir->fundocorpo = "#ccddcc";
    $cliframe_alterar_excluir->formulario = false;
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
  </tr>
  <tr>
  <td align='right'>
  <?
  $somaval=db_formatar(@$somaval,'f');
  ?>
  <b>Valor total do capital: 
    <?=@$somaval?>
  </b>
  </td>
  </tr>
</table>  
</form>
<script>

// função verifica se q95_cgmpri e q95_numcgm são diferentes
function jc_VerificaCgmCpfIgual(){

  var iEmpresa = $F('q95_cgmpri');
  var iSocio   = $F('q95_numcgm');
  if (iEmpresa == iSocio) {
    alert('Não será possível fazer a inclusão do cgm da própria inscrição como sócio');
    $('q95_numcgm').value = '';
    $('q95_numcgm').focus();
    return false;
  } else {
    return true;
  }
  
}

// função que valida o tipo de pessoa, fisica ou juridica, se for fisica, não habilitara a opção sócio no select q95_tipo
function js_tipoPessoa() {

  var iTipoPessoa = top.corpo.iframe_issbase.document.form1.z01_cgccpf.value;
      iTipoPessoa = iTipoPessoa.length;
  
  if (iTipoPessoa <= 11 || iTipoPessoa == "" || iTipoPessoa == null) {
  
    $("q95_tipo").options.length = 0;
    $("q95_tipo").options[0]     = new Option('Selecione...', '');
    $("q95_tipo").options[1]     = new Option('Responsável MEI', '2');
    $("q95_tipo").options[2]     = new Option('Responsável', '3');
    $('q95_perc').value = '';
    $('valor_capital').hide();
  } else {   
  
    $("q95_tipo").options.length = 0;
    $("q95_tipo").options[0]     = new Option('Selecione...', '');
    $("q95_tipo").options[1]     = new Option('Sócio', '1');
    $("q95_tipo").options[2]     = new Option('Responsável MEI', '2');
    $("q95_tipo").options[3]     = new Option('Responsável', '3');  
  }
}


// função que disponibiliza o campo q95_tipo se o tipo de socio for 1 : socio
function js_mostraValr_capital(){

  var iTipo = $F('q95_tipo');
  if (iTipo == 1 || iTipo == '1') {
    $('valor_capital').show();
    jc_VerificaCgmCpfIgual();
  } else {
    $('valor_capital').hide();
    $('q95_perc').value = '';
  }
}

function js_verificatipo(){
  
  var iTipo = $F('q95_tipo');
  if (iTipo != 1) {
    $('q95_perc').value = 0;
  }
  if (iTipo == 0 || iTipo == '0') {
    alert('Selecione o tipo de sócio.');
    
    return false;
  } else {
    return true;
  }

}


function js_cancelar() {

 <?
   if (isset($q95_cgmpri)) {
     echo "location.href=\"iss1_socios004.php?q95_cgmpri={$q95_cgmpri}&z01_nome={$z01_nome}\";\n";
   }
 ?>
}

function js_pesquisaq95_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_socios','db_iframe_cgm','func_nome.php?filtro=3&testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|z01_ender|z01_cgccpf','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_socios','db_iframe_cgm','func_nome.php?filtro=3&testanome=true&pesquisa_chave='+document.form1.q95_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,0);
  }
}

function js_mostracgm(erro,chave, chave2){

  if (chave2 == '') {
    alert('Contribuinte com o CGM desatualizado');
    document.form1.fisico_juridico.value = ''; 
    document.form1.q95_numcgm.value ='' ;
    document.form1.z01_nome_socio.value = 'Contribuinte com o CGM desatualizado';
    js_tipoPessoa();
    return false;    
  }
 
  document.form1.z01_nome_socio.value  = chave;
  document.form1.fisico_juridico.value = chave2;
  js_tipoPessoa(); 
  if(erro==true){ 
    document.form1.q95_numcgm.focus(); 
    document.form1.q95_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2,chave3,chave4){
	if (chave3  == ''|| chave4 == ''){
		alert('Contribuinte com o CGM desatualizado');
      document.form1.fisico_juridico.value = ''; 
    	document.form1.q95_numcgm.value ='' ;
      document.form1.z01_nome_socio.value = 'Contribuinte com o CGM desatualizado';

	}else{
	   document.form1.fisico_juridico.value = chave4; 
    	document.form1.q95_numcgm.value = chave1;
      document.form1.z01_nome_socio.value = chave2;
	}
	js_tipoPessoa();
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_socios','db_iframe_socios','func_socios.php?funcao_js=parent.js_preenchepesquisa|q95_numcgm|1','Pesquisa',true,0);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_socios.hide();
  <?
	  if($db_opcao!=1){
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
	  }
  ?>
}  
js_mostraValr_capital();
</script>