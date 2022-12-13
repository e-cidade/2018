<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_issplan_classe.php");
include("classes/db_issplanit_classe.php");
include("classes/db_issplaninscr_classe.php");
include("classes/db_issplanitinscr_classe.php");
include("classes/db_issplanitop_classe.php");
include("classes/db_issplanitold_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_libtributario.php");

$clissplanitold    = new cl_issplanitold;
$cl_issplan        = new cl_issplan;
$cl_issplanit      = new cl_issplanit;
$cl_issplaninscr   = new cl_issplaninscr;
$cl_issplanitinscr = new cl_issplanitinscr;
$cl_issplanitop    = new cl_issplanitop;
		
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($_POST);
db_postmemory($_GET);

if( isset($planilha) ) {
  // retorna o nomecontri e o fonecontri
  $recordIssplan = $cl_issplan->sql_record($cl_issplan->sql_query_file(null, "q20_nomecontri, q20_fonecontri", null, "q20_planilha = '{$planilha}'"));
			
	if ( $cl_issplan->numrows > 0) {
	
 	  $sSql = pg_fetch_assoc($recordIssplan);
		
		$q20_nomecontri = $sSql["q20_nomecontri"]; 
		$q20_fonecontri = $sSql["q20_fonecontri"];
		
    $nomecontri     = $sSql["q20_nomecontri"]; 
		$fonecontri     = $sSql["q20_fonecontri"];
	} 
}

//Máximo de s lançadas
$datahj= date("Y-m-d");
$ip = db_verifica_ip();

$ficha = db_getcadbancobranca(31,$ip,$datahj,db_getsession("DB_instit"),5);
if($ficha == true){
  //  ficha de compensação
  $int_max = 54;

}else if($ficha == false){
  // febrabam
  $int_max = 74;
}else{
  $int_max = 74;
}

$sqlconf = "select * from configdbpref";
$resultconf = db_query($sqlconf);
db_fieldsmemory($resultconf,0);
//busca dados para armazemar em cookies
if(@$_COOKIE["cookie_codigo_cgm"]==""){
  // cgm
 	if(@$codigo_cgm!=""){
		
 	  $result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","cgm.z01_numcgm = $codigo_cgm"));
 	 	$s2 = $clcgm->numrows;
 	}
 	db_fieldsmemory($result,0);
 	@setcookie("cookie_codigo_cgm",$z01_numcgm);
 	@setcookie("cookie_nome_cgm",$z01_nome);
 	@$cookie_codigo_cgm = $z01_numcgm;
}else{
 	@$cookie_codigo_cgm = $_COOKIE["cookie_codigo_cgm"];
}

$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaissqn.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
  echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}

$db_verifica_ip = db_verifica_ip();
mens_help();
$dblink="digitaissqn.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
postmemory($HTTP_POST_VARS);

//  tira a formatação do cnpj
$cgccpf = str_replace(".","",$cgc);
$cgccpf = str_replace("/","",$cgccpf);
$cgccpf = str_replace("-","",$cgccpf);


$clquery = new cl_query;

$sqlVerificaCgcPref = "
				select nomeinst from db_config 
				inner join cgm on z01_cgccpf = cgc  
				where prefeitura is true and  cgc = '$cgccpf' and numcgm <> z01_numcgm";
$resultVerificaCgcPref = db_query($sqlVerificaCgcPref);
$linhasVerificaCgcPref = pg_num_rows($resultVerificaCgcPref);
if($linhasVerificaCgcPref > 0){
	// não deixar acessar 
	db_msgbox("CNPJ Prefeitura, sendo utilizado para mais de um cgm.");
	db_redireciona("digitaissqn.php?");
	exit;
}
// ##########  Se for a primeira vez que entrei ######################################
if(isset($primeiravez)){
  $cgccpf = str_replace(".","",$cgc);
  $cgccpf = str_replace("/","",$cgccpf);
  $cgccpf = str_replace("-","",$cgccpf);


  //#################### se foi preenchido a inscrição #################################
  if ($inscricaow!=""){
  		$sql = "select * from issbase inner join cgm on z01_numcgm = q02_numcgm where q02_inscr=$inscricaow and z01_cgccpf = '$cgccpf'";
  		$result = db_query($sql);
  		if(pg_numrows($result)!=0){  // cnpj e inscriçõs corretos
   			db_fieldsmemory($result,0);
  		}else{ // cnpj ou inscrição invalido
   			redireciona("digitaissqn.php?".base64_encode('erroscripts=1Acesso a Rotina Inválido, verifique os dados digitados!'));
  		}
  }
  //#################### se não foi preenchido a inscrição #################################
  if ($inscricaow==""){
  		$sql1 = "select z01_numcgm,z01_cgccpf,z01_nome from cgm where z01_cgccpf = '$cgccpf'";
  		$result1 = db_query($sql1);
  		if(pg_numrows($result1)!=0){  // cnpj correto... buscar inscrição
   			db_fieldsmemory($result1,0);
   			$sql2= "select * from issbase where q02_numcgm = '$z01_numcgm' and  q02_dtbaix is null";
   			$result2 = db_query($sql2);
   			if(pg_numrows($result2)!=0){// cnpj do municipio
   			  db_fieldsmemory($result2,0);
   			  $inscricaow = $q02_inscr;
   		 		//echo "insc = $inscricaow";
   			}else{
   			  	
   			  //$inscricaow= "f";
   			}
  		}else{ // cnpj invalido
  		  redireciona("digitaissqn.php?".base64_encode('erroscripts=Acesso a rotina inválido! Verifique os dados digitados!'));
  		  	
  		}
  }

   
  // vê se ja existe alguma planilha para este mes e ano selecionado
   
  $sIssplan = " select * from issplan where q20_ano = $ano and q20_mes=$mes and q20_numcgm= $z01_numcgm order by q20_mes";
  $result3 = db_query($sIssplan);
  if(pg_numrows($result3)!=0){
    db_fieldsmemory($result3,0);
    // tem planilha
    redireciona("planilha.php?nomecontri=".$q20_nomecontri."&mostra="."5"."&fonecontri=".$q20_fonecontri."&inscricaow=".$inscricaow."&mesx=".$mesx."&mes=".$mes."&ano=".$ano."&numcgm=".$z01_numcgm."&nomes=".$z01_nome);
  }

}


?>
<html>
    <head>
        <title>
            <?=$w01_titulo?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script language="JavaScript" src="scripts/scripts.js"></script>
        <script language="JavaScript" src="scripts/db_script.js"></script>
        <script>
            
            function js_excluir(x, y){
                var obj = document.form1;
                obj.pkq21_sequencial.value = x;
                obj.pkq31_sequencial.value = y;
                
            }
            
            function js_alterar(seq, ins, seq2, plan, cnpj, nome, ser, nota, serie, valor, ali, pag, tipo, sit, ded, base, reti, obs, imp, datanota){

                var obj1 = document.form1;
                obj1.pkq21_sequencial.value = seq2;
                obj1.pkq31_sequencial.value = seq;
                obj1.inscricao.value = ins;
                obj1.planilha.value = plan;
                obj1.cnpj1.value = cnpj;
                obj1.nomerazao.value = nome;
                obj1.sprestado.value = ser;
                obj1.numnota.value = nota;
                obj1.numserie.value = serie;
                obj1.valservico.value = valor;
                obj1.aliquota.value = ali;
                obj1.total.value = pag;
                obj1.q21_tipolanc.value = tipo;
                obj1.q21_situacao.value = sit;
                obj1.q21_valordeducao.value = ded;
                obj1.q21_valorbase.value = base;
                obj1.q21_datanota.value = datanota;
								
                if (reti == 't') {
                    obj1.q21_retido.checked = true;
                }
                if (tipo == 2) {
                    document.getElementById("canc").style.visibility = 'visible';
                    if (sit == 1) {
                        // prestado e cancelado
                        obj1.q21_situacao.checked = true;
                        document.getElementById("q21_valordeducao").style.background = '#EEEEE2';
                        obj1.q21_valordeducao.readOnly = true;
                        document.getElementById("nomerazao").style.background = '#EEEEE2';
                        obj1.nomerazao.readOnly = true;
                        document.getElementById("cnpj1").style.background = '#EEEEE2';
                        obj1.cnpj1.readOnly = true;
                        document.getElementById("valservico3").style.background = '#EEEEE2';
                        obj1.valservico.readOnly = true;
                        document.getElementById("sprestado3").style.background = '#EEEEE2';
                        obj1.sprestado.readOnly = true;
                        obj1.q21_retido.disabled = true;
                    }
                }
                obj1.q21_obs.value = obs;
                obj1.q21_valorimposto.value = imp;
                obj1.grava.value = "Alterar nota acima";
                obj1.grava.style.visibility = 'visible';
                
            }
            
            function js_verificacnpj(cnpj){
                pesquisanota.location.href = 'pesquisa_cgm.php?cnpj=' + cnpj;
            }
            
            function js_veri(){
                //valor bruto
                if (document.form1.valservico.value.indexOf(",") != -1) {
                    var vals = new Number(document.form1.valservico.value.replace(",", "."));
                    document.form1.valservico.value = vals.toFixed(2);
                }
                else {
                    var vals = new Number(document.form1.valservico.value);
                    document.form1.valservico.value = vals.toFixed(2);
                }
                if (isNaN(vals)) {
                    alert("Verifique o valor!  (ex: 1500.00 com ponto somente no centavos )");
                    document.form1.valservico.value = "";
                    document.form1.total.value = "";
                    document.form1.valservico.focus();
                    return false;
                }
                //deducao
                if (document.form1.q21_valordeducao.value == "") {
                    document.form1.q21_valordeducao.value = 0;
                }
                if (document.form1.q21_valordeducao.value.indexOf(",") != -1) {
                    var valsded = new Number(document.form1.q21_valordeducao.value.replace(",", "."));
                    document.form1.q21_valordeducao.value = valsded.toFixed(2);
                }
                else {
                    var valsded = new Number(document.form1.q21_valordeducao.value);
                    document.form1.q21_valordeducao.value = valsded.toFixed(2);
                }
                if (isNaN(valsded)) {
                    alert("Verifique o valor!  (ex: 1500.00 com ponto somente no centavos )");
                    document.form1.q21_valordeducao.value = "";
                    document.form1.total.value = "";
                    document.form1.q21_valordeducao.focus();
                    return false;
                }
                var aliquota = new Number(document.form1.aliquota.value.replace(",", "."));
                
                // base de calculo
                document.form1.q21_valorbase.value = document.form1.valservico.value - document.form1.q21_valordeducao.value;
                var base = new Number(document.form1.q21_valorbase.value);
                document.form1.q21_valorbase.value = base.toFixed(2);
                
                //valor do imposto
                vals = new Number((document.form1.q21_valorbase.value * (aliquota / 100)));
                document.form1.q21_valorimposto.value = vals.toFixed(2);
                
                var obj = document.form1;
                if (obj.q21_tipolanc.value == 1) {
                    if (obj.q21_retido.checked == false) {
                        obj.total.value = 0;
                    }
                    else {
                        obj.total.value = obj.q21_valorimposto.value;
                    }
                }
                else {
                    if (obj.q21_retido.checked == false) {
                        obj.total.value = obj.q21_valorimposto.value;
                    }
                    else {
                        obj.total.value = 0;
                    }
                }
            }
            
            function maiusculo(obj){
                var maiusc = new String(obj.value);
                obj.value = maiusc.toUpperCase();
            }
            
            function js_cnpj(obj){
                var retorno = js_verificaCGCCPF(obj, '');
                if (retorno == false) 
                    obj.focus();
                else 
                    document.submit();
            }
            
            function js_vericampos(){
                var alerta = "";
                var chi = document.createElement("INPUT");
                chi.setAttribute("type", "hidden");
                chi.setAttribute("name", "guarda");
                
				jcnpj = document.form1.cnpj1;
				jcnpj.value = jcnpj.value.replace(/[. \- \/ \\]/g,"");
				
                jinscricao = document.form1.inscricao.value;
                jnomerazao = document.form1.nomerazao.value;
                jsprestado = document.form1.sprestado.value;
                jnumnota = document.form1.numnota.value;
                jnumserie = document.form1.numserie.value;
                jvalservico = document.form1.valservico.value;
                jaliquota = document.form1.aliquota.value;
                
                if (jcnpj.value == "") {
                    alerta += "CNPJ\n";
                }
                if (jnomerazao == "") {
                    alerta += "Nome/Razão Social\n";
                }
                if (jsprestado == "") {
                    alerta += "Serviço Prestado\n";
                }
                if (jvalservico == "") {
                    alerta += "Serviço Prestado\n";
                }
                if (jnumnota == "") {
                    alerta += "Numero da Nota\n";
                }
                
                if (jaliquota == "") {
                    alerta += "Valor da Alíquota\n";
                }
                var expr = /[^0-9]+/;
                if (jinscricao.match(expr) != null) {
                    alerta += "Inscrição Inválida";
                }
				if (jcnpj.value.length > 11) {
                   var retorna = js_verificaCGCCPF(jcnpj,'');					
				} else{
                   var retorna = js_verificaCGCCPF('',jcnpj);
				}

                if (retorna == false) {
                    //alert('falso');
                    //	return false;
                }
                
                /*if(alerta == ""){
                 document.form1.appendChild(chi);
                 document.form1.submit();
                 } */
                if (alerta != "") {
                    alert("Verifique os seguintes campos:\n" + alerta);
                    return false;
                }
                else 
                    if (retorna == true) {
                    
                        if (document.form1.grava.value == 'Lança Registro') {
                            document.form1.gravadados.value = "inclui";
                        }
                        else {
                            document.form1.gravadados.value = "altera";
                        }
                        document.form1.submit();
                        return true;
                    }
            }
            
            function js_proximo(){
                document.form1.passaproximo.value = "sim";
                document.form1.submit();
            }
            
            function abre(){
                window.open('relatoriopdf.php?planilha=<?=@$planilha?>', 'Ralatorio', 'toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
                return false;
            }
            
            function js_buscainscr(ins){
            
                if (document.form1.inscricao.value == "") {
                    alert('Preencha uma inscrição antes de efetuar a busca.');
                }
                else {
                
                    if (document.form1.inscricao.value == ins) {
                        alert('Você não deve preencher aqui a sua própria inscrição!\nEste campo deve ser preenchido com a inscrição da empresa da qual o imposto foi retido.\nEsta regra vale somente para empresas do município.\nCaso a empresa da qual o imposto foi retido não seja do município este campo ficará em branco.');
                        document.form1.inscricao.value = '';
                        document.form1.inscricao.focus();
                        
                    }
                    else {
                        pesquisainscr.location.href = 'pesquisainscricao.php?inscr=' + document.form1.inscricao.value;
                        
                    }
                    
                }
            }
            
            function js_buscaop(codord){
                if (codord == "") {
                    alert('Preencha o código da ordem.');
                }
                else {
                    pesquisaordem.location.href = 'pesquisaordem.php?codord=' + codord + '&ano=' + document.form1.ano.value + '&mes=' + document.form1.mes.value + '&planilha=' + document.form1.planilha.value;
                }
            }
            
            function js_preenchedados(d1, d2, d3){
                document.form1.cnpj1.value = d1;
                document.form1.nomerazao.value = d2;
                document.form1.sprestado.value = d3;
                document.form1.numnota.focus();
            }
            
            function js_erropesquisa(inscr){
                alert('Inscrição:' + inscr + ' não encontrada');
                document.form1.inscricao.value = '';
                document.form1.inscricao.focus();
            }
            
            function js_erropesquisaordem(msg){
                alert(msg);
                document.form1.codord.value = '';
                document.form1.codord.focus();
            }
            
            function js_limpa(){
            
                var obj1 = document.form1;
                obj1.inscricao.value = '';
                obj1.cnpj1.value = '';
                obj1.nomerazao.value = '';
                obj1.sprestado.value = '';
                obj1.numnota.value = '';
                obj1.numserie.value = '';
                obj1.valservico.value = '';
                obj1.q21_valorimposto.value = '';
                obj1.total.value = '';
                obj1.q21_obs.value = '';
                obj1.q21_datanota_dia.value = '';
                obj1.q21_datanota_mes.value = '';
                obj1.q21_datanota_ano.value = '';
								obj1.q21_datanota.value = '';
	              document.getElementById("nomecontri").value = '';
                document.getElementById("fonecontri").value = '';
                
            }
            
            function js_verificanota(cnpj, nota, serie){
                pesquisanota.location.href = 'pesquisanota.php?cnpj=' + cnpj + '&nota=' + nota + '&serie=' + serie;
            }
            
            function js_notaexiste(){
                document.form1.numnota.value = '';
                document.form1.numserie.value = '';
                document.form1.numnota.focus();
            }
            
            function js_tomado(){
                if (document.form1.q21_tipolanc.value == 1) {
                    document.form1.q21_retido.checked = true;
                    var obj = document.form1;
                    if (obj.q21_situacao.checked == true) {
                        alert('Para alterar o tipo de serviço desmarque a opção cancelado.');
                        document.form1.q21_tipolanc.value = 2;
                    }
                    else {
                        document.getElementById("canc").style.visibility = 'hidden';
                    }
                }
                else {
                    document.form1.q21_retido.checked = false;
                    document.getElementById("canc").style.visibility = 'visible';
                }
                js_veri();
            }
            
            function js_cancelado(cor){
                //alert(cor);
                var obj = document.form1;
                if (obj.q21_situacao.checked == false) {
                    document.getElementById("q21_valordeducao").style.background = cor;
                    obj.q21_valordeducao.readOnly = false;
                    document.getElementById("nomerazao").style.background = cor;
                    obj.nomerazao.readOnly = false;
                    document.getElementById("cnpj1").style.background = cor;
                    obj.cnpj1.readOnly = false;
                    document.getElementById("valservico3").style.background = cor;
                    obj.valservico.readOnly = false;
                    document.getElementById("sprestado3").style.background = cor;
                    obj.sprestado.readOnly = false;
                    obj.q21_retido.disabled = false;
                }
                else {
                    document.getElementById("q21_valordeducao").style.background = '#EEEEE2';
                    obj.q21_valordeducao.readOnly = true;
                    document.getElementById("nomerazao").style.background = '#EEEEE2';
                    obj.nomerazao.readOnly = true;
                    document.getElementById("cnpj1").style.background = '#EEEEE2';
                    obj.cnpj1.readOnly = true;
                    document.getElementById("valservico3").style.background = '#EEEEE2';
                    obj.valservico.readOnly = true;
                    document.getElementById("sprestado3").style.background = '#EEEEE2';
                    obj.sprestado.readOnly = true;
                    obj.q21_retido.disabled = true;
                    
                }
            }
            
            function js_datanota(){
                if (document.form1.q21_datanota_ano.value != "") {
                    var mes = document.form1.mes.value;
                    if (mes < 10) {
                        var mes = '0' + document.form1.mes.value;
                    }
                    var mes1 = document.form1.q21_datanota_mes.value;
                    var ano1 = document.form1.q21_datanota_ano.value;
                    var ano = document.form1.ano.value;
                    //alert(' mes= '+mes+'   mes1= '+mes1+'  ano='+ano+'  ano1='+ano1);
                    if (mes != mes1 || ano != ano1) {
                        alert('Data da nota fora do período da competência.');
                        document.form1.q21_datanota_dia.value = "";
                        document.form1.q21_datanota_mes.value = "";
                        document.form1.q21_datanota_ano.value = "";
                    }
                    
                }
            }
            
            
            
        </script>
        <?

// ################# função ######################################
function monta_tabela($sql, $array_formata = array()){
  $result = db_query($sql);
  $lin = pg_num_rows($result);

  if ($lin > $int_max){
    msgbox("Voce deve criar outra planilha");
    echo"<script> document.form1.grava.style.visibility='hidden';
             </script>"; 	
  }

  if($lin==0) {
    return;
  }
  $col = pg_num_fields($result);

 	// Para publicar variaveis do ResultSet
 	for ($c = 0; $c < $col; $c++) {
 	  $campo = pg_field_name($result, $c);
 	  global $$campo;
 	}

 	//echo "colunas = $col, linhas = $lin<br>";
 	for ($i = 0; $i < $lin; $i++) {
 	  db_fieldsmemory($result, $i);

 	  //echo "linha = $i<br>";
 	  echo "<tr class=\"titulo2\">";
 	   

 	   
 	  // Para montar a Tabela de acordo com formatação
 	  foreach($array_formata as $campo => $conteudo) {

 	    //echo $campo . "=" .$$campo."<br>";

 	    if (!empty($conteudo)) {
 	      //echo "= ". $$campo. "<br>";
 	      $formata = $conteudo;
 	      $valor_campo = $$campo;
 	      $eval_expr = "\$eval_var=".$formata;
 	       
 	      eval($eval_expr);
 	       
 	      echo "<td>".$eval_var."</td>";
 	    } else {
 	      //echo "= ". $$campo. "<br>";
 	      echo "<td>".$$campo."</td>";
 	    }

 	  }//linha
 	  echo"
	 	<td width= \"120px\">
																													
				  <input class=\"botao\" name=\"alterar\" type=\"button\" value=\"Alterar\" onClick=\"js_alterar('$q31_sequencial','$q31_inscr','$q21_sequencial','$q21_planilha','$q21_cnpj','$q21_nome','$q21_servico','$q21_nota','$q21_serie','$q21_valorser','$q21_aliq','$q21_valor','$q21_tipolanc','$q21_situacao','$q21_valordeducao','$q21_valorbase','$q21_retido','$q21_obs','$q21_valorimposto','".db_formatar($q21_datanota,"d")."')\">
					
				  <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"js_excluir('$q21_sequencial','$q31_sequencial')\">
   			      
				  					  
				</td>";

 	  echo "</tr>";
 	}
}//######################## termina a função #######################################

if ((isset ($numcgm)) and ($numcgm!="")){
  $sql="select z01_cgccpf, z01_nome,z01_numcgm from cgm where z01_numcgm = $numcgm";
  $result = db_query($sql);
  db_fieldsmemory($result,0);

}

?>
        <style type="text/css">
            <?db_estilosite();?>
        </style>
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad=""
        <? mens_OnHelp() ?>
>
        <form name="form1" method="post" action="opcoesissqn.php?cgc=$cgc">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
                <input name="gravadados" type="hidden" value="<?=@$gravadados?>">
								<input name="passaproximo" type="hidden" value="<?=@$passaproximo?>">
								<input name="cpf" type="hidden" value="<?=@$cpf?>">
								<input name="mes" type="hidden" value="<?=@$mes?>">
								<input name="ano" type="hidden" value="<?=$ano?>">
								<input name="cgc" type="hidden" value="<?=@$cgc?>">
								<input name="numcgm" type="hidden" value="<?=@$numcgm?>">
								<input name="inscricaow" type="hidden" value="<?=@$inscricaow?>">
								
								<input name="nomecontri" type="hidden" value="<?=@$nomecontri?>">
								<input name="fonecontri" type="hidden" value="<?=@$fonecontri?>">
								
								<input name="modificando" type="hidden" value="<?=@$modificando?>">
								<input name="plani" type="hidden" value="<?=@$plani?>">
								<input name="z01_nome" type="hidden" value="<?=@$z01_nome?>">
								<input name="z01_numcgm" type="hidden" value="<?@$z01_numcgm?>">
								<input name="ttt" type="hidden">
								<input name="planilha" type="hidden" value="<?=@$planilha?>">
								<input name="nova" type="hidden" value="<?=@$nova?>">
								<input name="pkq21_sequencial" type="hidden" value="">
								<input name="pkq31_sequencial" type="hidden" value="">
                <tr>
                    <td width="100%" colspan="5">
                        <!-- ############ aki começa a montar a tabela do declarante ############################################# -->
                        <table width="100%" border="0" class="texto">
                            <tr>
                                <td colspan="3" align="center" style="border: 1px solid">
                                    <b>DADOS DO
                                        DECLARANTE</b>
                                </td>
                            </tr>
                            <tr>
                            <td width="19%" colspan="0" nowrap>
                                <small>
                                    <b>Nome ou Raz&atilde;o
                                        Social:</b>
                                    <font color="<?=$w01_corfontesite?>">
                                        <?=$z01_nome?>
                                    </font>
                                </small>
                            </td>
                            <?
				//echo "inscrição = $inscricaow";
				if ($inscricaow =="" || $inscricaow=="f"){

				  echo"
            		<td width='19%' <small>
	             	<font color='$w01_corfontesite'> Empresa de fora do município</font>
	              	</small>
					";
				  	
				}else{
				  echo"
            		<td width='19%' <small><b>Inscrição:</b>
	             	<font color='$w01_corfontesite'> $inscricaow</font>
	              	</small>";
				}
				?>
                            </td>
                            <td width="19%" nowrap>
                                <small>
                                <b>Competência:</b>
                                <font color="<?=$w01_corfontesite?>">
                                    <?=db_mes($mes)?>
                                    de 
                                    <?=$ano?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3">
                                <small>
                                    <b>Contato:</b>
                                </small>
                                <input type="text" id="nomecontri" maxlength="40" name="nomecontri" value="<?=@$nomecontri?>" size="14">
                                <small>
                                    <b>Fone:</b>
                                </small>
                                <input type="text" id="fonecontri" maxlength="15" name="fonecontri" value="<?=@$fonecontri?>" size="14">
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <!-- tabela dos dados do prestado ou tomado -->
                        <table width="100%" border="0" class="texto">
                            <tr>
                                <td colspan="4" align="center" style="border: 1px solid">
                                    <strong>DADOS
                                        DOS SERVIÇOS (PRESTADOS/TOMADOS)</strong>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b>
                                        <small>
                                            Tipo de serviço:
                                        </small>
                                    </b>
                                </td>
                                <td align="left" colspan="2">
                                    <select name="q21_tipolanc" onChange="js_tomado();">
                                        <option value="1">Tomado</option>
                                        <?if($w13_libissprestado=="t"){?>
                                        <option value="2">Prestado</option>
                                        <?}?>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;&nbsp; 
                                    <?
				//verifica se é a prefeitura
				$where = "";
				//echo"numcgm = $numcgm   ...... cgc = $cgc ........  cnpj $cgccpf";
				if((isset($cgc))and ($cgc!="")){

          $cgc2 = str_replace(".","",$cgc);
          $cgc2 = str_replace("/","",$cgc2);
          $cgc2 = str_replace("-","",$cgc2);

				  $where .= " and cgc = '".$cgc2."'";

				}elseif((isset($numcgm)) and ($numcgm!="")){
				  $where .= " and numcgm = $numcgm ";
				}
				$sqlpref    = "select nomeinst from db_config where prefeitura is true  $where ";
				$resultpref = db_query($sqlpref);
				$linhaspref = pg_num_rows($resultpref);
				if($linhaspref>0){
				  echo " <b><small>&nbsp;&nbsp;&nbsp;&nbsp; Código da ordem de pagamento :</small></b>
                          </td>
                          <td colspan='2'><input name='codord' type='text' size= '15' value=''>
                                          <input name= 'buscaop' type= 'button' value='Busca dados da ordem' class='botao' onclick = 'js_buscaop(document.form1.codord.value)'; >
                          ";
				}else{
				  echo "&nbsp;</td><td colspan='2' >&nbsp;";
				}

				?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>
                                        <small>
                                            Inscri&ccedil;&atilde;o:
                                        </small>
                                    </b>
                                </td>
                                <td>
                                    
                                    <?if ($inscricaow==""){
					  $ins="0";
					}else{
					  $ins="$inscricaow";
					}
                    echo "<input name='inscricao' type='text' value='".@$inscricao."' size='7' maxlength='6' onchange='js_buscainscr($ins)'>";
					echo"<input name='buscadados' class='botao' type='button'  value='Busca Dados' onclick='js_buscainscr($ins)' >";
					?>
                                </td>
                                <td align="left" colspan="2">
                                    <font size="1">
                                        * Este campo deve ser
                                        preenchido somente em casos em que a empresa da qual o imposto foi
                                        retido seja do município. Caso você não tenha esta informação deixe
                                        o campo em branco.
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td valign="bottom" width="140">
                                    <b>
                                        <small>
                                            Nota:
                                        </small>
                                    </b>
                                </td>
                                <td align="left" width="180">
                                    <input name="numnota" type="text" id="numnota3" size="18">
                                </td>
                                <td align="left" width="140px">
                                    <b>
                                        <small>
                                            Série:
                                        </small>
                                    </b>
                                </td>
                                <td align="left">
                                    <input name="numserie" type="text" id="numserie4" size="10" maxlength="5" onchange="js_verificanota(document.form1.cnpj1.value,document.form1.numnota.value, document.form1.numserie.value)">&nbsp;&nbsp;&nbsp;&nbsp; <span id='canc' style="visibility:hidden"><input name="q21_situacao" type="checkbox" onChange="js_cancelado('<?=$w01_corfundoinput?>');">
                                        <label value="1">
                                            <b>
                                                <small>
                                                    Cancelado
                                                </small>
                                            </b>
                                        </label>
                                    </span>
                                    &nbsp;&nbsp;&nbsp;&nbsp; <b>
                                        <small>
                                            Data da nota:
                                        </small>
                                    </b>
                                    <? 
				//if(@$q21_datanota!=""){
				//  $q21_datanota_dia = substr($q21_datanota,8,2);
				// $q21_datanota_mes = substr($q21_datanota,5,2);
				//  $q21_datanota_ano = substr($q21_datanota,0,4);
				//}
					
				db_inputdata("q21_datanota",@$q21_datanota_dia,@$q21_datanota_mes,@$q21_datanota_ano ,true, 'text', 1);
				//db_inputdata("q21_datanota",@$q21_datanota_dia,@$q21_datanota_mes,@$q21_datanota_ano ,true, 'text', 1,"onChange='js_datanota()';","","","parent.js_datanota()");
				?>
				                     </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b>
                                        <small>
                                            CPF ou CNPJ:
                                        </small>
                                    </b>
                                </td>
                                <td align="left">
                                    <input name="cnpj1" type="text" value="<?=@$cnpj1?>" id="cnpj1" size="18" maxlength="18" 
                                           onBlur="js_verificacnpj(document.form1.cnpj1.value);"
                                           onChange="js_teclas(event);"
                                           onKeyPress="FormataCPFeCNPJ(this,event); return js_teclas(event);">
                                </td>
                                <td align="left">
                                    <b>
                                        <small>
                                            Nome ou Razão Social:
                                        </small>
                                    </b>
                                </td>
                                <td align="left">
                                    <input name="nomerazao" id="nomerazao" value="<?=@$nomerazao?>" type="text" size="40" maxlength="60" onKeyUp="maiusculo(this)">
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b>
                                        <small>
                                            Valor Bruto:
                                        </small>
                                    </b>
                                </td>
                                <td align="left">
                                    <input name="valservico" type="text" id="valservico3" onBlur="return js_veri();" size="18">
                                </td>
                                <td align="left">
                                    <b>
                                        <small>
                                            Dedução:
                                        </small>
                                    </b>
                                </td>
                                <td align="left">
                                    <input name="q21_valordeducao" type="text" id="q21_valordeducao" size="10" onChange="return js_veri();">&nbsp;&nbsp;&nbsp;&nbsp; <b>
                                        <small>
                                            Base cálculo:
                                        </small>
                                    </b>
                                    <input name="q21_valorbase" type="text" id="q21_valorbase" size="10" readonly class="readonly"> &nbsp;&nbsp;&nbsp;&nbsp;<b>
                                        <small>
                                            Aliquota:
                                        </small>
                                    </b>
                                    <?

					$numcgm = isset($numcgm)?$numcgm:@$z01_numcgm;

					// ############################# aliquota ######################################################
					// q81_cadcalc = 3 ... 3 = issqn variavel
					$sql_base = "select distinct q81_valexe from tipcalc where q81_cadcalc = 3 and q81_usaretido is true";
					$query = db_query($sql_base);
					$s = pg_num_rows($query);
					if($w13_aliqissretido=="f"){?>
                                    <select name="aliquota" onChange="return js_veri();">
                                        <?
					for($xx=0;$xx<$s;$xx++){
					  db_fieldsmemory($query,$xx);
					  ?>
                                        <option value="<?=$q81_valexe?>">
                                            <?=$q81_valexe?>
                                            %</option>
                                        <?}?>
                                    </select>
                                    <?
}else{// não entra aki
  db_fieldsmemory($query,0);
  ?>
                                    <select name="aliquota" onBlur="return js_veri();" onselect="return js_veri();">
                                        <option value="0">0%</option>
                                        <option value="0.5">0.5%</option>
                                        <option value="1"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="1"){echo "selected";}?>
> 1% </option>
                                        <option value="1.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="1.5"){echo "selected";}?>
> 1.5% </option>
                                        <option value="2"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="2"){echo "selected";}?>
> 2% </option>
                                        <option value="2.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="2.5"){echo "selected";}?>
> 2.5% </option>
                                        <option value="3"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="3"){echo "selected";}?>
> 3% </option>
                                        <option value="3.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="3.5"){echo "selected";}?>
> 3.5% </option>
                                        <option value="4"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="4"){echo "selected";}?>
> 4% </option>
                                        <option value="4.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="4.5"){echo "selected";}?>
> 4.5% </option>
                                        <option value="5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="5"){echo "selected";}?>
> 5% </option>
                                        <option value="5.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="5.5"){echo "selected";}?>
> 5.5% </option>
                                        <option value="6"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="6"){echo "selected";}?>
> 6% </option>
                                        <option value="6.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="6.5"){echo "selected";}?>
> 6.5% </option>
                                        <option value="7"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="7"){echo "selected";}?>
> 7% </option>
                                        <option value="7.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="7.5"){echo "selected";}?>
> 7.5% </option>
                                        <option value="8"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="8"){echo "selected";}?>
> 8% </option>
                                        <option value="8.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="8.5"){echo "selected";}?>
> 8.5% </option>
                                        <option value="9"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="9"){echo "selected";}?>
> 9% </option>
                                        <option value="9.5"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="9.5"){echo "selected";}?>
> 9.5% </option>
                                        <option value="10"
                                            <?if(isset($q81_valexe)&&$q81_valexe=="10"){echo "selected";}?>
> 10% </option>
                                    </select>
                                    <?}?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b>
                                        <small>
                                            Valor do Imposto:
                                        </small>
                                    </b>
                                </td>
                                <td align="left">
                                    <input name="q21_valorimposto" type="text" size="18" readonly class="readonly">
                                </td>
                                <td align="left">
                                    <input name="q21_retido" type="checkbox" onChange="return js_veri();" checked>
                                    <label value="1">
                                        <b>
                                            <small>
                                                Imposto
                                                Retido
                                            </small>
                                        </b>
                                    </label>
                                </td>
                                <td align="left">
                                    <b>
                                        <small>
                                            Valor a Pagar:
                                        </small>
                                    </b>
                                    <input name="total" type="text" size="10" readonly class="readonly">
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b>
                                        <small>
                                            Servi&ccedil;o Prestado:
                                        </small>
                                    </b>
                                </td>
                                <td align="left" colspan="3">
                                    <input name="sprestado" value="<?=@$sprestado?>" type="text" id="sprestado3" size="73" onKeyUp="maiusculo(this)" maxlength="40">
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b>
                                        <small>
                                            Observação:
                                        </small>
                                    </b>
                                </td>
                                <td align="left" colspan="3">
                                    <textarea rows="2" cols="70" name="q21_obs"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" colspan="4">
                                    <input name="grava" class="botao" type="button" value="Lança Registro" style="visibility: visible" onclick="js_vericampos()">&nbsp;&nbsp; <input name="limpa" class="botao" type="button" value="Limpa Campos" onclick=" if (confirm('Confirma a limpeza dos campos?')){js_limpa()}"> &nbsp;&nbsp;<input name="proximo" class="botao" type="button" value="Próxima Etapa" onclick="js_proximo();" style="visibility: hidden">
                                </td>
                            </tr>
                        </table>
                        <?
		
		
		// ######### botão proximo ###############

		if (isset($passaproximo)&& $passaproximo=="sim"){


			// verifica se a matrícula já está cadastrada, caso já possuir dados cadastrados faz a alteração dos registros
 		  $cl_issplan->sql_record($cl_issplan->sql_query_file(null, "q20_planilha", null, "q20_planilha = {$planilha}"));
			
			if ( $cl_issplan->numrows > 0) {
				
				// seta os campos que serão alterados
				$cl_issplan->q20_nomecontri = $nomecontri;
				$cl_issplan->q20_fonecontri = $fonecontri;
				$cl_issplan->q20_planilha   = $planilha;
	      $cl_issplan->alterar($planilha);
				
			}

			//die("select * from issplan where q20_ano = $ano and q20_mes=$mes and q20_numcgm= $z01_numcgm order by q20_mes");
		  $result3 = db_query("select * from issplan where q20_ano = $ano and q20_mes=$mes and q20_numcgm= $z01_numcgm order by q20_mes");
          if(pg_numrows($result3)!=0){
            db_fieldsmemory($result3,0);
            // tem planilha
						//die($q20_nomecontri);
            redireciona("planilha.php?nomecontri=".$q20_nomecontri."&fonecontri=".$q20_fonecontri."&inscricaow=".$inscricaow."&mesx=".$mesx."&mes=".$mes."&ano=".$ano."&numcgm=".$z01_numcgm."&nomes=".$z01_nome."&mostra=5");
          }

		}



		// ############################# incluir no banco ##########################
		$dtatual= date("Y-m-d");
		$hora = date("H:i");
		//echo "data = $dtatual hora = $hora";



		if (isset($gravadados) && $gravadados=="inclui" and (!isset($excluir)) ){
		  //die("incluir"); 
		  $sqlerro = false;
		  echo"<script>
            var nova1 = Number(document.form1.nova.value);
                document.form1.nova.value = nova1+(1);	
	            document.form1.proximo.style.visibility='visible';
        </script>"; 
    // se tiver incluindo uma nota de uma planilha alterada.. deve alterar o campo situação para alterado(2)
    // se situação  for == emitido ou reemitido ...mudar para alterado
    $sqlerro = false;
    db_inicio_transacao();
   
    if($nova==""){ // se for a primeira nota a ser lançada

      $cl_issplan-> q20_numcgm = $z01_numcgm;
      $cl_issplan-> q20_ano = $ano;
      $cl_issplan-> q20_mes = $mes;
      $cl_issplan-> q20_nomecontri = @$nomecontri;
      $cl_issplan-> q20_fonecontri = @$fonecontri;
      $cl_issplan-> q20_numpre = 0;
      $cl_issplan-> q20_numbco = 0;
      $cl_issplan-> q20_situacao = 1;
      $cl_issplan->incluir(null);

      if ($cl_issplan->erro_status == 0) {
        $sqlerro = true;
        // echo"entrei no erro do issplan.....";
        //die($cl_issplan->erro_sql);
        $erro_msg = $cl_issplan->erro_msg;
      }

      if ($sqlerro==false)	{

        if ($inscricaow != "" ){
          if($inscricaow != "f"){

            $cl_issplaninscr-> q24_planilha = $cl_issplan->q20_planilha;
            $cl_issplaninscr-> q24_inscr = $inscricaow;
            $cl_issplaninscr-> incluir(null);

            if ($cl_issplaninscr->erro_status == 0) {
              $sqlerro = true;
              echo"entrei no erro do issplaninscr.....";
              //die($cl_issplaninscr->erro_sql);
              $erro_msg = $cl_issplaninscr->erro_msg;
            }
          }
        }
      }
       
      //guardar o numero da planilha
      $planilha = $cl_issplan->q20_planilha;
      echo"<script>document.form1.planilha.value= $planilha; </script>";

      
    }


    if (isset($planilha)){
      
      $result4 = db_query("select * from issplan where q20_planilha = $planilha");
      db_fieldsmemory($result4,0);

      //verifica se tem o campo codord preenchido
      if(isset($codord) and ($codord!="")){
        // verificar se tem ordem de pagto... se tiver tem q ser a mesma
        $sqlpla = "select q20_planilha,q96_pagordem from issplanitop
              inner join issplanit on q96_issplanit = q21_sequencial 
              inner join issplan on q20_planilha = q21_planilha 
              where q20_planilha = $planilha ";
        $resultpla = db_query($sqlpla);
        $linhaspla = pg_num_rows($resultpla);
        if($linhaspla > 0){
          db_fieldsmemory($resultpla,0);
          if($q96_pagordem != $codord){
            // não pode incluir
            db_msgbox("Ja existe uma ordem de pagamento ($q96_pagordem) para esta planilha");
            $sqlerro = true;
            //$erro_msg ="Ja existe uma ordem de pagamento ($q96_pagordem) para esta planilha";
          }else{
            // se for igual pode incluir
          }

        }else{
          
          if($nova!=""){
          // se a planilha não tem op... não pode inclui, pois ela ja tem notas sem op... so pode incluir sem op
          db_msgbox("Esta planilha ja possui notas lançadas sem ordem de compra, para incluir por ordem deve criar uma nova planilha.");
          $sqlerro = true;
          }
        }
      }

    }
    ##################### tirar depois ########### 
    //$sqlerro = true;
     
    // se situação for igual a emitir ou reemitir
    if(isset($q20_situacao)){
	    if (($q20_situacao==3 )||( $q20_situacao==4)){
	      $cl_issplan->q20_situacao = 2;
	      $cl_issplan->q20_planilha = $planilha;
	      //die($q20_planilha);
	      $cl_issplan->alterar($q20_planilha);
	      if ($cl_issplan->erro_status == 0) {
	        $sqlerro = true;
	        //echo"entrei no erro do issplan.....  $q20_planilha";
	        //die($cl_issplan->erro_sql);
	        $erro_msg = $cl_issplan->erro_msg;
	      }
	    }
    }
    if($sqlerro==false)	{
       
      // insere as notas na issplanit
      if($q21_datanota_ano!=""){
        $q21_datanota = $q21_datanota_ano."-".$q21_datanota_mes."-".$q21_datanota_dia;
      }

      if($q21_tipolanc==1){ //tomado
        $q21_situacao    ="0";
      }else{
        if(isset($q21_situacao)){ // prestado e cancelado
          $q21_situacao    = 1;
          $cnpj1           = "0";
          $nomerazao       = "nota cancelada";
          $sprestado       = "nota cancelada";
          $valservico      = "0";
          $aliquota        = "0";
          $total           = "0";
          $q21_valordeducao= "0";
          $q21_valorbase   = "0";
          $q21_valorimposto= "0";
        }else{
          $q21_situacao    ="0";
        }
      }
      if(isset($q21_retido)){
        $q21_retido = "true";
      }else{
        $q21_retido = "false";
      }

      $cl_issplanit->q21_planilha    = $planilha;
      $cl_issplanit->q21_cnpj        = $cnpj1;
      $cl_issplanit->q21_nome        = $nomerazao;
      $cl_issplanit->q21_servico     = $sprestado;
      $cl_issplanit->q21_nota        = $numnota;
      $cl_issplanit->q21_serie       = $numserie;
      $cl_issplanit->q21_valorser    = $valservico;
      $cl_issplanit->q21_aliq        = $aliquota;
      $cl_issplanit->q21_valor       = $total;
      $cl_issplanit->q21_valorimposto= $q21_valorimposto;
      $cl_issplanit->q21_dataop      = $dtatual;
      $cl_issplanit->q21_horaop      = $hora;
      $cl_issplanit->q21_tipolanc    = $q21_tipolanc;
      $cl_issplanit->q21_situacao    = $q21_situacao;
      $cl_issplanit->q21_valordeducao= $q21_valordeducao;
      $cl_issplanit->q21_valorbase   = $q21_valorbase;
      $cl_issplanit->q21_retido      = $q21_retido;
      $cl_issplanit->q21_obs         = @$q21_obs;
      $cl_issplanit->q21_datanota    = @$q21_datanota;
			$cl_issplanit->q21_status = 1;
      $cl_issplanit->incluir(null);

      if ($cl_issplanit->erro_status == 0) {
        $sqlerro = true;
        //echo" 111 - entrei no erro do issplanit.....";
        //die($cl_issplanit->erro_sql);
        $erro_msg = $cl_issplanit->erro_msg;
      }
    }
    if($sqlerro==false)	{
		if(isset($codord) and ($codord!="")){
        // grava na issplanitop
        $cl_issplanitop->q96_issplanit = $cl_issplanit->q21_sequencial;
        $cl_issplanitop->q96_pagordem  = $codord;
        $cl_issplanitop->incluir(null);
        if ($cl_issplanitop->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $cl_issplanitop->erro_msg;
        }
      }
    }
    
    if($sqlerro==false)	{
      if ($inscricao!=""){
        $cl_issplanitinscr->q31_issplanit = $cl_issplanit-> q21_sequencial;
        $cl_issplanitinscr->q31_inscr = $inscricao;
        $cl_issplanitinscr->incluir(null);

        if ($cl_issplanitinscr->erro_status == 0) {
          $sqlerro = true;
          //echo"entrei no erro do issplanitinscr.....";
          //die($cl_issplanitinscr->erro_sql);
          $erro_msg = $cl_issplanitinscr->erro_msg;
        }

      }
    }
    db_fim_transacao($sqlerro);


		}
		//################### excluir #####################################
		if (isset($excluir)){
			
			$sqlerro = false;
     
		  $sqlexc = "select q21_planilha,q20_situacao from issplanit inner join issplan on q20_planilha=q21_planilha where q21_sequencial= $pkq21_sequencial";
		  $resultexc = db_query($sqlexc);
		  db_fieldsmemory($resultexc,0);

     //TEMOS QUE VERIFICAR SE É A ULTIMA NOTA EXCLUIDA... SE FOR TEMOS Q ANULAR A PLANILHA
     $sqlnum = "select  q21_planilha,q21_sequencial from issplanit 
		            where q21_planilha = $q21_planilha and q21_status = 1";
		 $resultnum = db_query($sqlnum);
		 $linhasnum = pg_num_rows($resultnum);
		 //echo "<br> $sqlnum";
     $cancelaexclusao = 0;
		 if($linhasnum == 1){
  			 db_fieldsmemory($resultnum,0);
			 	 	 // se tiver somente uma nota deve anular a planilha.
			
				
				 echo "
				 <script>
				 var conf = confirm('Se excluir a última nota terá que anular a planilha. Deseja anular a planilha?');
				 if(conf){
				 	 location.href = 'anulaplanilha.php?planilha=$q21_planilha&mes=$mes&ano=$ano&numcgm=$z01_numcgm&inscricaow=$inscricaow&q21_sequencial=$q21_sequencial&ultima=sim';
				 }else{";
						$cancelaexclusao = 1;
				echo "
				 }
				 </script>
				 ";
			 }
		
		
		  if($cancelaexclusao != 1){
			  db_inicio_transacao();
			  echo"<script> document.form1.proximo.style.visibility='visible';
	         </script>"; 
				// não da pra ser por metodo... porque ele tenta alterar os campos em ranco da tela... ele não sabe q tem q alterar só o status.	 
				$sqlaltera = " update issplanit set q21_status= 3 where q21_sequencial = $pkq21_sequencial";
				$resultealtera = db_query($sqlaltera);
				if($resultealtera == false){
				  $sqlerro = true;
				  //die($sqlaltera);
				  $erro_msg = " Erro na exclusão na nota ";
				  db_msgbox($erro_msg);
				}	 
			
	    // se situação for igual a emitir ou reemitir
	      if (($q20_situacao==3 )||( $q20_situacao==4)){
	        $cl_issplan->q20_situacao = 2;
	        $cl_issplan->q20_planilha = $q21_planilha;
	        //die($q20_planilha);
	        $cl_issplan->alterar($q21_planilha);
					if ($cl_issplan->erro_status == 0) {
	          $sqlerro = true;
	          $erro_msg = $cl_issplan->erro_msg;
				    db_msgbox($erro_msg);
	        }
	      }
	      db_fim_transacao($sqlerro);
			}
    }
		//################### alterar #####################################


		if (isset($gravadados) && $gravadados=="altera" and (!isset($excluir))){
			 
      db_inicio_transacao();
		  $sqlerro=false;
		  echo"<script> document.form1.proximo.style.visibility='visible';
             </script>"; 	
		  $result4 = db_query("select * from issplan where q20_planilha = $planilha");
		  db_fieldsmemory($result4,0);

		  if (($q20_situacao==3 )||( $q20_situacao==4)){
		    $cl_issplan->q20_situacao = 2;
		    $cl_issplan->q20_planilha = $planilha;
		    //die($q20_planilha);
		    $cl_issplan->alterar($q20_planilha);
		    if ($cl_issplan->erro_status == 0) {
		      $sqlerro = true;
		      //echo"entrei no erro do issplan.....  $q20_planilha";
		      //die($cl_issplan->erro_sql);
		      $erro_msg = $cl_issplan->erro_msg;
		    }
		  }

		  //******************

		  if($q21_tipolanc==1){ //tomado
		    $q21_situacao    ="0";
		  }else{

		    if(isset($q21_situacao)){ // prestado e cancelado
		      $q21_situacao    = 1;
		      $cnpj1           = "0";
		      $nomerazao       = "nota cancelada";
		      $sprestado       = "nota cancelada";
		      $valservico      = "0";
		      $aliquota        = "0";
		      $total           = "0";
		      $q21_valordeducao= "0";
		      $q21_valorbase   = "0";
		      $q21_valorimposto= "0";
		    }else{
		      $q21_situacao    ="0";
		    }

		  }
		  if(isset($q21_retido)){
		    $q21_retido = "true";
		  }else{
		    $q21_retido = "false";
		  }
			
			// inluir uma nova nota somente quando for alterado o valor
			
			$sql_valorissplanit = "select q21_valorimposto as valornotaorigem from issplanit where q21_sequencial = $pkq21_sequencial ";
			$result_valorissplanit = db_query($sql_valorissplanit);
			$linhasvalorissplanit = pg_num_rows($result_valorissplanit);
			if($linhasvalorissplanit > 0){
				db_fieldsmemory($result_valorissplanit,0);
			}else{
				$sqlerro = true;
				$erro_msg = " Erro na alteração na nota ";
		    db_msgbox($erro_msg);
			}
	
						
		 if($sqlerro==false){
			  $cl_issplanit->q21_planilha    = $planilha;
			  $cl_issplanit->q21_cnpj        = $cnpj1;
			  $cl_issplanit->q21_nome        = $nomerazao;
			  $cl_issplanit->q21_servico     = $sprestado;
			  $cl_issplanit->q21_nota        = $numnota;
			  $cl_issplanit->q21_serie       = $numserie;
			  $cl_issplanit->q21_valorser    = $valservico;
			  $cl_issplanit->q21_aliq        = $aliquota;
			  $cl_issplanit->q21_valor       = $total;
			  $cl_issplanit->q21_valorimposto= $q21_valorimposto;
			  $cl_issplanit->q21_dataop      = $dtatual;
			  $cl_issplanit->q21_horaop      = $hora;
			  $cl_issplanit->q21_tipolanc    = $q21_tipolanc;
			  $cl_issplanit->q21_situacao    = $q21_situacao;
			  $cl_issplanit->q21_valordeducao= $q21_valordeducao;
			  $cl_issplanit->q21_valorbase   = $q21_valorbase;
			  $cl_issplanit->q21_retido      = $q21_retido;
			  $cl_issplanit->q21_obs         = $q21_obs;
				$cl_issplanit->q21_status      = 1;
			  if($q21_datanota_ano!=""){
			    $q21_datanota = $q21_datanota_ano."-".$q21_datanota_mes."-".$q21_datanota_dia;
			    $cl_issplanit->q21_datanota  = $q21_datanota;
			  }
				if($valornotaorigem != $q21_valorimposto){
					
					$cl_issplanit->incluir(null);
				}else{
					
					$cl_issplanit->q21_sequencial = $pkq21_sequencial;
					$cl_issplanit->alterar($pkq21_sequencial);
				}
							  
			  if ($cl_issplanit->erro_status == 0) {
			    $sqlerro = true;
			    echo"222- entrei no erro do issplanit.....  $q20_planilha";
			    $erro_msg = $cl_issplanit->erro_msg;
			    db_msgbox($erro_msg);
			    //die($cl_issplanit->erro_sql);
	
			  }
			}
			// só inclui nova se for alterado o valor da nota
			if($valornotaorigem != $q21_valorimposto){
				// alterar o status da nota para desativado por alteração
        //usei update por no metodo iria alterar todos os campos e eu quero alterar so status.
			  if($sqlerro==false){
	        $sqlaltera = " update issplanit set q21_status= 2 where q21_sequencial = $pkq21_sequencial";
				  $resultealtera = db_query($sqlaltera);
				  if($resultealtera == false){
					  $sqlerro = true;
					  //die($sqlaltera);
			      $erro_msg = " Erro na alteração na nota ";
			      db_msgbox($erro_msg);
			  	}
			  }
				if($sqlerro == false)	{
					// grava na issplanitold
					$clissplanitold->q73_issplanitorigem  = $pkq21_sequencial;
					$clissplanitold->q73_issplanitdestino = $cl_issplanit->q21_sequencial;
					$clissplanitold->q73_data             = date("Y-m-d");
					$clissplanitold->q73_hora             = date("H:i");
					$clissplanitold->q73_ip               = $ip;
					$clissplanitold->incluir(null);
				  if ($clissplanitold->erro_status == 0) {
			      $sqlerro = true;
			      $erro_msg = $clissplanitold->erro_msg;
			      db_msgbox($erro_msg);
			    }
			  }
			}
		  
    if($sqlerro==false){
      if($inscricao!=""){
      	
				$cl_issplanitinscr->q31_issplanit = $cl_issplanit->q21_sequencial;
        $cl_issplanitinscr->excluir(null,"q31_issplanit = $cl_issplanit->q21_sequencial ");
				if ($cl_issplanitinscr->erro_status == 0) {
          $sqlerro = true;
          //echo"333- entrei no erro do issplanitinscr.....  ";
          $erro_msg = $cl_issplanitinscr->erro_msg;
          db_msgbox($erro_msg);
          
        }
				
        $cl_issplanitinscr->q31_issplanit = $cl_issplanit->q21_sequencial;
        $cl_issplanitinscr->q31_inscr     = $inscricao;
        $cl_issplanitinscr->incluir(null);
        if ($cl_issplanitinscr->erro_status == 0) {
          $sqlerro = true;
          //echo"entrei no erro do issplanit.....  ";
          $erro_msg = $cl_issplanitinscr->erro_msg;
          db_msgbox($erro_msg);
          //die($cl_issplanitinscr->erro_sql);

        }
      }
    }
			
			db_fim_transacao($sqlerro);
		}


		if (isset($limpa)){
		  echo"<script> document.form1.proximo.style.visibility='visible';</script>";
		}
		// ............ monta tabela ...............


		//echo "planilha ............ $planilha";

?>
                        <tr>
                            <td colspan="5">
                                <br>
                                <table width="98%" class="tab">
                                    <tr bgcolor="#CCCCCC">
                                        <th>
                                            TIPO SER.
                                        </th>
                                        <th>
                                            INSCRIÇÃO
                                        </th>
                                        <th>
                                            CNPJ
                                        </th>
                                        <th>
                                            NOME
                                        </th>
                                        <th>
                                            NOTA
                                        </th>
                                        <th>
                                            SERIE
                                        </th>
                                        <th>
                                            DATA 
                                        </th>
                                        <th>
                                            VALOR
                                        </th>
                                        <th>
                                            ALI.
                                        </th>
                                        <th>
                                        VALOR TOTAL
                                        </thd>
                                        <th>
                                        </th>
                                    </tr>
                                    <?
//if($q21_tipolanc==1){$q21_tipolanc= "tomado";	}
/*
 $parametro = array(
 'tipolanc'   => "",
 'q31_inscr'		   => "",
 'q21_cnpj'       => "db_cgccpf(\$q21_cnpj);",
 'q21_nome'       => "",
 'q21_nota'       => "",
 'q21_serie'      => "",
 'q21_datanota'   => "db_formatar(\$q21_datanota, 'd');",
 'q21_valorbase'   => "db_formatar(\$q21_valorbase, 'f');",
 'q21_aliq'       => "\$q21_aliq . '%';",
 'q21_valor'      => "db_formatar(\$q21_valor, 'f');"

 );
 */
if (isset($planilha)){
  	
  if ($planilha!="" || $planilha !=0){
    $sql = "select *,
										case when q21_tipolanc = 1 then 'Tomado' 
												else 'Prestado' 
										end as tipolanc
									from issplanit 
									left join issplanitinscr on q21_sequencial=q31_issplanit 
									where q21_planilha= $planilha and q21_status = 1
									order by q21_tipolanc,q21_datanota";
    //monta_tabela($sql,$parametro);
//die($sql);

    $resultx = db_query($sql);
    $linhax = pg_num_rows($resultx);
    // db_msgbox("nnnnnnnnnnn".$int_max." lin=".$linhax);
    	
    if($linhax>0){
      if ($linhax > $int_max){
        db_msgbox("Voce deve criar outra planilha");
        echo"<script> document.form1.grava.style.visibility='hidden';
					    </script>"; 	
      }
      for ($x = 0; $x < $linhax; $x++) {
        db_fieldsmemory($resultx,$x);
        echo "
											<tr>
											<td align= 'center'>$tipolanc</td>
											<td align= 'center'>$q31_inscr</td>
											<td align= 'left'>".db_cgccpf($q21_cnpj)."</td>
											<td align= 'left'>$q21_nome</td>
											<td align= 'center'>$q21_nota</td>
											<td align= 'center'>$q21_serie</td>
								";
        if($q21_datanota!=""){
          echo "<td align= 'center'>".db_formatar($q21_datanota,"d")."</td>";
        }else{
          echo "<td align= 'center'></td>";
        }

								echo "
											<td align= 'right'>".db_formatar($q21_valorbase, 'f')."</td>
											<td align= 'center'>$q21_aliq%</td>
											<td align= 'right'>".db_formatar($q21_valor, 'f')."</td>
											<td width= '150px'>
							      	  <input class=\"botao\" name=\"alterar teste\" type=\"button\" value=\"Alterar\" onClick=\"js_alterar('$q31_sequencial','$q31_inscr','$q21_sequencial','$q21_planilha','$q21_cnpj','$q21_nome','$q21_servico','$q21_nota','$q21_serie','$q21_valorser','$q21_aliq','$q21_valor','$q21_tipolanc','$q21_situacao','$q21_valordeducao','$q21_valorbase','$q21_retido','$q21_obs','$q21_valorimposto', '".db_formatar($q21_datanota,"d")."')\">
										  	<input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"js_excluir('$q21_sequencial','$q31_sequencial')\">
											</td>
											</tr>

";

      }
    }
  }
  
  echo "<script> document.form1.proximo.style.visibility='visible'; </script>" ;
  
}
if(isset($z01_numcgm)and $z01_numcgm!=""){
   echo "<script> document.form1.z01_numcgm.value = $z01_numcgm;</script>" ;
}

?>
                                </table>
                            </td>
                        </tr>
                        </table>
                        <iframe name="pesquisainscr" style="visibility:hidden">
                        </iframe>
                        <iframe name="pesquisaordem" style="visibility:hidden">
                        </iframe>
                        <iframe name="pesquisanota" style="visibility:hidden">
                        </iframe>
                    </form>
                    </body>
                </html>