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

//MODULO: caixa
include("dbforms/db_classesgenericas.php");
include("classes/db_orctiporec_classe.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clplacaixarec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k80_data");
$clrotulo->label("k13_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("k02_drecei");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_codigo");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("q02_inscr");
$clrotulo->label("j01_matric");

/*
 * definimos qual funcao sera usada para consultar a matricula.
 * se o campo db_config.db21_usasisagua for true, usamos a func_aguabase.
 * se for false, usamos a func_iptubase
 */
$oDaoDBConfig = db_utils::getDao("db_config");
$rsInstit     = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_file(db_getsession("DB_instit")));
$oInstit      = db_utils::fieldsMemory($rsInstit, 0);
$sFuncaoBusca = "js_pesquisa_matric";
if ($oInstit->db21_usasisagua == "t") {
  $sFuncaoBusca = "js_pesquisa_agua";
} 
if(isset($db_opcaoal)){
  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao=true;
}else{
  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) ){
    
     $k81_conta  = "";
     $k81_receita= "";
     $k81_valor  = "";
     $k13_descr  = "";
     $k02_descr  = "";
     $k81_seqpla = "";
     $k81_obs    = "";
     $c61_codigo = "";
     $k02_drecei = "";
     $k81_origem = "";
     $k81_numcgm = "";
     $j01_matric = "";
     $q02_inscr  = "";
     $j01_matric = "";
     $nomematric = "";
     $z01_nome   = "";
     $nomeinscr  = "";
     $recurso    = "";
     $c58_sequencial = "";
     $c58_descr      = "";

  }
	if (isset($incluir)){

     db_postmemory($_POST);

	}

}
?>
<form name="form1" method="post" action=""
	onsubmit="return js_verifica();">
<center>

<fieldset style="width: 50%; left: 50%;">
<legend><b>Receita</b></legend>

<table border="0" width="100%">
	<tr>
		<td nowrap title="<?=@$Tk81_seqpla?>"><?=@$Lk81_seqpla?></td>
		<td><?db_input('k81_seqpla',6,$Ik81_seqpla,true,'text',3,"")?></td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tk81_codpla?>"><? db_ancora(@$Lk81_codpla,"js_pesquisak81_codpla(true);",3);?></td>
		<td><?db_input('k81_codpla',6,$Ik81_codpla,true,'text',3," onchange='js_pesquisak81_codpla(false);'")?></td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tk81_receita?>"><?db_ancora(@$Lk81_receita,"js_pesquisak81_receita(true);",$db_opcao);?></td>
		<td><?db_input('k81_receita',5,$Ik81_receita,true,'text',2," onchange='js_pesquisak81_receita(false);'");
		      db_input('c61_codigo',5,$Ic61_codigo,true,'text',3,"onfocus=\"document.getElementById('k81_conta').focus()\" ",'recurso');
		      db_input('k02_drecei',40,$Ik02_drecei,true,'text',3,'');
		      db_input('estrutural',20,null,true,'hidden',2,"");
		     ?>
		</td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tk81_conta?>"><?db_ancora(@$Lk81_conta,"js_pesquisak81_conta(true);",$db_opcao);?></td>
		<td><?db_input('k81_conta',5,$Ik81_conta,true,'text',2," onchange='js_pesquisak81_conta(false);'");
		      db_input('c61_codigo',5,$Ic61_codigo,true,'text',3,"onfocus=\"document.getElementById('k81_codigo').focus()\"");
		      db_input('k13_descr',40,$Ik13_descr,true,'text',3,'');
		     ?>
		</td>
	</tr>
  <tr>
    <td nowrap title="<?=@$Tk81_origem?>"><?=$Lk81_origem?></td>
    <td><? db_select("k81_origem",getValoresPadroesCampo("k81_origem"),true,1,"onChange='toogleOrigem(this.value)'");?></td>
  </tr>
  <tr id='inputCgm' style=''>

		<td nowrap title="<?=@$Tk81_conta?>"><?db_ancora(@$Lk81_numcgm,"js_pesquisak81_numcgm(true);",$db_opcao);?></td>
		<td>
		    <?
				db_input('k81_numcgm',5,$Ik81_numcgm,true,'text',2," onchange='js_pesquisak81_numcgm(false);'");
				db_input('z01_nome',45,$Iz01_nome,true,'text',3);
				?>
    </td>
  </tr>
  <tr id='inputInscr' style='display:none'>

		<td nowrap title="<?=@$Tq02_inscr?>"><?db_ancora(@$Lq02_inscr,"js_pesquisa_inscr(true);",$db_opcao);?></td>
		<td>
	    <?
			db_input('q02_inscr',5,$Iq02_inscr,true,'text',2," onchange='js_pesquisa_inscr(false);'");
			db_input('nomeinscr',45,$Iz01_nome,true,'text',3);
			?>
    </td>
  </tr>
  <tr id='inputMatric' style='display:none'>

		<td nowrap title="<?=@$Tj01_matric?>"><?db_ancora(@$Lj01_matric,"{$sFuncaoBusca}(true);",$db_opcao);?></td>
		<td>
	    <?
			db_input('j01_matric',5,$Ij01_matric,true,'text',2," onchange='{$sFuncaoBusca}(false);'");
			db_input('nomematric',45,$Iz01_nome,true,'text',3);
			?>
    </td>
  </tr>
	<tr>
		<td nowrap title="<?=@$To15_codigo?>"><?echo $Lo15_codigo?></td>
		<td>
		 <?
		     $clorctiporec = new cl_orctiporec;
         $dbwhere = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
         $record = $clorctiporec->sql_record($clorctiporec->sql_query_file(null,"o15_codigo,o15_descr","o15_codigo",$dbwhere));
         db_selectrecord('k81_codigo', $record, true, $db_opcao,'','','');
		 ?>
		</td>
	</tr>
	
	
	
  <tr style=''>
    <td ><b><?db_ancora("C.Peculiar / C.Aplicação :","js_pesquisaPeculiar(true);",$db_opcao);?></b></td>
    <td>
        <?
        db_input('c58_sequencial',5,'',true,'text',2," onchange='js_pesquisaPeculiar(false);'");
        db_input('c58_descr',45,'',true,'text',3);
        ?>
    </td>
  </tr>	
	
	
	
	
	<tr>
		<td nowrap title="<?=@$Tk81_datareceb?>"><?echo $Lk81_datareceb?></td>
		<td><?
					if ($db_opcao==1) {
						
					  $k81_datareceb_dia = date("d",db_getsession("DB_datausu"));
					  $k81_datareceb_mes = date("m",db_getsession("DB_datausu"));
					  $k81_datareceb_ano = date("Y",db_getsession("DB_datausu"));
					}
					db_inputdata('k81_datareceb',@$k81_datareceb_dia,@$k81_datareceb_mes,@$k81_datareceb_ano,true,'text',$db_opcao,"")
					?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tk81_operbanco?>"><?=@$Lk81_operbanco?></td>
		<td><?db_input('k81_operbanco',15,$Ik81_operbanco,true,'text',$db_opcao,""); ?></td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tk81_valor?>"><?=@$Lk81_valor?></td>
		<td><?db_input('k81_valor',15,$Ik81_valor,true,'text',$db_opcao,"")?></td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tk81_obs?>"><?=@$Lk81_obs?></td>
		<td><?db_textarea("k81_obs",1,40,$Ik81_obs,"true","text",$db_opcao);?></td>
	</tr>
</table>

</fieldset>





<table border = '0' style="margin-top: 10px;">
	<tr>
		<td colspan="2" align="center"><input
			name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
			type="submit" id="db_opcao"
			value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
			<?=($db_botao==false?"disabled":"")?>> 
			<?php
        if ($db_opcao != 1 || isset($db_opcaoal)) {
        	 echo "<input name='novo' type='button' id='cancelar' value='Novo' onclick='js_cancelar();'>";
        }
			?>
		<input name="importar" type="button" id="importar" value="Importar"
			onclick="js_importar();">
		<input name="zeracampos" type="button" id="zerar" value="Zera Campos"
			onclick="js_limpa_campos(document.form1);"></td>
	</tr>
</table>




<table border="0" width="60%" style="margin-top: 10px;">
	<tr>
		<td valign="top" align="center"><?
	 $chavepri= array("k81_seqpla"=>@$k81_seqpla);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clplacaixarec->sql_query(null,"*",null," k81_codpla = $k81_codpla");
	// echo $clplacaixarec->sql_query(null,"*",null," k81_codpla = $k81_codpla");die();
	 $cliframe_alterar_excluir->campos  ="k81_seqpla,k81_codpla,k81_conta,k13_descr,k81_receita,k02_drecei,k81_valor,k81_obs";
	 $cliframe_alterar_excluir->legenda="Receitas Lançadas";
	 //$cliframe_alterar_excluir->iframe_height ="160";
	 //$cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	 ?></td>
	</tr>
	<tr>
		<td valign="top" align="center"><?
		if ($db_opcao!=3 && $db_opcao!=22 && $db_opcao!=11 && $db_opcao != 33 ){
		  ?> <input name="imp" value="Imprime" type="button"
			onclick="js_imprime()" <?=(@$k81_seqpla!=""?"disabled":"")?>> <?
}
?></td>
	</tr>

</table>


</center>
</form>
<script>

function js_importar(){

  js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_placaixa_imp','func_placaixa.php?funcao_js=parent.js_mostraimporta|k80_codpla','Pesquisa',true,'0');
}

function js_mostraimporta (codpla){
  db_iframe_placaixa_imp.hide();
  if(confirm('Importa Planilha ('+codpla+')?')==true){
    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","importar");
    opcao.setAttribute("value",codpla);
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
  
}
function js_imprime(){
  jan = window.open('cai2_emiteplanilha002.php?codpla='+document.form1.k81_codpla.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisak81_codpla(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_placaixa','func_placaixa.php?funcao_js=parent.js_mostraplacaixa1|k80_codpla|k80_data','Pesquisa',true,'0');
  }else{
     if(document.form1.k81_codpla.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_placaixa','func_placaixa.php?pesquisa_chave='+document.form1.k81_codpla.value+'&funcao_js=parent.js_mostraplacaixa','Pesquisa',false);
     }else{
       document.form1.k80_data.value = ''; 
     }
  }
}
function js_mostraplacaixa(chave,erro){
  document.form1.k80_data.value = chave; 
  if(erro==true){ 
    document.form1.k81_codpla.focus(); 
    document.form1.k81_codpla.value = ''; 
  }
}
function js_mostraplacaixa1(chave1,chave2){
  document.form1.k81_codpla.value = chave1;
  document.form1.k80_data.value = chave2;
  db_iframe_placaixa.hide();
}
function js_pesquisak81_conta(mostra){
  if(document.form1.recurso.value == ''){
    alert('Receita não selecionada.');
    return false;
  }
  if(document.form1.estrutural.value.substr(0,3) == '211' || document.form1.estrutural.value.substr(0,3) =='497' ){
    recurso = '0';
  }else{
    /*
        aqui, atribuindo recurso='0' quando seleciona uma receita aparecem contas de todos os recursos
    */ 
    //  recurso = document.form1.recurso.value;

    recurso ='0';
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_saltes','func_saltesrecurso.php?recurso='+recurso+'&funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr|c61_codigo&data_limite=<?=date("Y-m-d",db_getsession("DB_datausu"))?>','Pesquisa',true);
  }else{
     if(document.form1.k81_conta.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_saltes','func_saltesrecurso.php?pesquisa_chave='+document.form1.k81_conta.value+'&funcao_js=parent.js_mostrasaltes&data_limite=<?=date("Y-m-d",db_getsession("DB_datausu"))?>','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave1,chave2,chave3,erro){
  document.form1.k81_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  document.form1.c61_codigo.value = chave3;
  if( document.form1.estrutural.value.substr(0,3) == '211' ){
    document.form1.k81_codigo.value = document.form1.c61_codigo.value;
    document.form1.k81_codigo.onchange() ;
  }else{
    document.form1.k81_codigo.value = chave3;
    document.form1.k81_codigo.onchange() ;
  }

  if(erro==true){ 
    document.form1.k81_conta.focus(); 
    document.form1.k81_receita.focus(); 
    document.form1.k81_conta.value = ''; 
  } else {
    js_getCgmConta(chave1);
  }

}
function js_mostrasaltes1(chave1,chave2,chave3){
  document.form1.k81_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  document.form1.c61_codigo.value = chave3;
  if( document.form1.estrutural.value.substr(0,3) == '211' ){
    document.form1.k81_codigo.value = document.form1.c61_codigo.value;
    document.form1.k81_codigo.onchange() ;
  }else{
    document.form1.k81_codigo.value = chave3;
    document.form1.k81_codigo.onchange() ;
  }
  js_getCgmConta(chave1);


 
  db_iframe_saltes.hide();
}
function js_pesquisak81_receita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_tabrec','func_tabrec_recurso.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei|recurso|k02_estorc','Pesquisa',true,'0');
  }else{
     if(document.form1.k81_receita.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_tabrec','func_tabrec_recurso.php?pesquisa_chave='+document.form1.k81_receita.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave1,chave2,chave3,chave4,erro){
  // o codigo abaixo coloca a receita nos inputs
  document.form1.k81_receita.value = chave1;
  document.form1.k02_drecei.value = chave2;
  document.form1.recurso.value = chave3;
  document.form1.estrutural.value = chave4;

  // o codigo abaixo zera a conta bancaria
   /*
  document.form1.k81_conta.value = '';
  document.form1.k13_descr.value = '';
  document.form1.c61_codigo.value = '';
  document.form1.k81_codigo.value = 0;
  document.form1.k81_codigo.onchange() ;
  */
  if(erro==true){ 
    document.form1.k81_receita.focus(); 
    document.form1.k81_receita.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2,chave3,chave4){
  document.form1.k81_receita.value = chave1;
  document.form1.k02_drecei.value = chave2;
  document.form1.recurso.value = chave3;
  document.form1.estrutural.value = chave4;
  
  db_iframe_tabrec.hide();
 
}

function js_pesquisak81_numcgm(mostra){
  
  document.getElementById('db_opcao').disabled = true;
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostraz01_numcgm|z01_numcgm|z01_nome','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.k81_numcgm.value+'&funcao_js=parent.js_mostraz01_numcgm1','Pesquisa',false);
  }
}
function js_mostraz01_numcgm(chave1,chave2){
  document.getElementById('db_opcao').disabled = false;
  document.form1.k81_numcgm.value = chave1; 
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide(); 
}
function js_mostraz01_numcgm1(erro,chave){
  
  document.form1.z01_nome.value = chave;
   
  if(erro==true){ 
    document.form1.k81_numcgm.focus();
    document.form1.k81_numcgm.value = ''; 
    document.form1.z01_nome.value   = chave; 
  } else {
    document.getElementById('db_opcao').disabled = false;
  }
}

//////  Caracteristica Peculiar

function js_pesquisaPeculiar(mostra){
  

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_peculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraPeculiar|c58_sequencial|c58_descr','Pesquisa',true,'10');
  } else {
 
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_peculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.c58_sequencial.value+'&funcao_js=parent.js_mostraPeculiar1','Pesquisa',false);
  }
  
}
function js_mostraPeculiar(chave1,chave2){
  
  document.form1.c58_sequencial.value = chave1; 
  document.form1.c58_descr.value      = chave2;
  db_iframe_peculiar.hide(); 
}
function js_mostraPeculiar1(erro,chave){
  
    document.form1.c58_sequencial.focus();
    document.form1.c58_sequencial.value = ''; 
    document.form1.c58_descr.value      = erro; 
}






function js_pesquisa_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_inscr','func_issbase.php?pesquisa_chave='+$F('q02_inscr')+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1; 
  document.form1.nomeinscr.value  = chave2;
  db_iframe_inscr.hide(); 
}
function js_mostrainscr1(chave,erro){
  document.form1.nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.nomeinscr.value = chave; 
  }
}
function js_pesquisa_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_matric','func_iptubase.php?funcao_js=parent.js_mostramatric|j01_matric|z01_nome','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_matric','func_iptubase.php?pesquisa_chave='+$F('j01_matric')+'&funcao_js=parent.js_mostramatric1','Pesquisa',false);
  }
}
function js_mostramatric(chave1,chave2){
  document.form1.j01_matric.value = chave1; 
  document.form1.nomematric.value  = chave2;
  db_iframe_matric.hide(); 
}
function js_mostramatric1(chave,erro){
  document.form1.nomematric.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.nomematric.value = chave; 
  }
}
function js_pesquisa_agua(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_matric','func_aguabase.php?funcao_js=parent.js_mostramatric|x01_matric|z01_nome','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_placaixarec','db_iframe_matric','func_aguabase.php?pesquisa_chave='+$F('j01_matric')+'&funcao_js=parent.js_mostramatric1','Pesquisa',false);
  }
}
<?
if( isset($incluir) || isset($alterar) || isset($excluir) ){
     
   echo "document.form1.k81_receita.focus(); document.form1.k81_receita.select();";
}else{
//  echo "  document.form1.k81_conta.focus(); ";
//RAQUEL
  echo "  document.form1.k81_receita.focus(); ";
}
?>

function js_verifica(){
  

  if(document.form1.estrutural.value.substr(0,3) == '211'){
    if(document.form1.k81_codigo.value == 0){
      alert('O recurso deve ser informado.');
      return false;
    }
  }

  if ( document.form1.k81_numcgm.value == '' ) {
    alert('CGM do contribuinte não informado!');
    return false;
  }
  
  if (document.form1.c58_sequencial.value == "") {
  
   alert('Você deve selecionar uma C.Peculiar/Cod de Aplicação antes de incluir o lançamento.');
   return false;
  }
  return true;
  
}
function js_limpa_campos(form){
  for (var i = 0; i < form.elements.length; i++){
		if (form.elements[i].name != 'k81_codpla' && form.elements[i].name != 'k81_datareceb'){
     if (form.elements[i].type == "text" || form.elements[i].type == "hidden"){
		     form.elements[i].value = "";
	   }
	   if (form.elements[i].type == 'select-one'){
	      form.elements[i].selectedIndex = 0;
	    }
	    if (form.elements[i].type == "textarea"){
	         form.elements[i].value = "";
	     }
   //alert(form.elements[i].type);
  }
 }
}


function toogleOrigem(iTipo) {

  switch (iTipo) {

    case '1' :

      $('inputCgm').style.display    = '';
      $('inputMatric').style.display = 'none';
      $('inputInscr').style.display  = 'none';
      break;

   case '2' :

      $('inputInscr').style.display  = '';
      $('inputMatric').style.display = 'none';
      $('inputCgm').style.display    = 'none';
      break;

   case '3' :

      $('inputMatric').style.display = '';
      $('inputInscr').style.display  = 'none';
      $('inputCgm').style.display    = 'none';
      break;


  }
}
function js_getCgmConta(iReduz) {
     sJson    = '{"exec":"getCgmConta","iCodReduz":'+iReduz+'}';
     url      = 'cai4_placaixaRPC.php';
     oAjax    = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'sJson='+sJson, 
                               onComplete: js_retornoCgm
                              }
                             );
}
function js_retornoCgm(oAjax) {
    
     oCgm = eval("("+oAjax.responseText+")");
     $('k81_numcgm').value = oCgm.z01_numcgm;
     $('z01_nome').value   = oCgm.z01_nome;
     document.getElementById('db_opcao').disabled = false;

}  
<?
if (isset($opcao) || isset($importar)) {
  echo "\ntoogleOrigem('{$k81_origem}');\n";
}
?>
</script>