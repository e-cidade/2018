<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_divida_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$cldivida = new cl_divida;
$clrotulo->label("v01_exerc");
$clrotulo->label("v01_folha");
$clrotulo->label("v01_livro");
//$clrotulo->label("v01_dtoper");
$db_opcao = 1;
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function termo(qual,total){
  document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
}
function js_mostralivro(){	
  js_OpenJanelaIframe('top.corpo','db_iframe_mostralivro','div4_mostralivro.php','Livros',true);
}
function js_trocalivro(valor){
  if (valor=='nao'){
    document.form1.v01_livro.value = document.form1.v01_livroaux.value;
    document.form1.v01_folha.value = document.form1.v01_folhaaux.value; 
  }else if (valor=='sim'){
    document.form1.v01_livroaux.value = document.form1.v01_livro.value;
    document.form1.v01_livro.value = "";		 
    document.form1.v01_folhaaux.value = document.form1.v01_folha.value;
    document.form1.v01_folha.value = "";		 
  }
  document.form1.submit();
}
function  js_pesquisalivro(mostra){
  js_OpenJanelaIframe('top.corpo','db_iframe_mostralivro','div4_mostralivro.php?exerc='+document.form1.v01_exerc.value+'&funcao_js=parent.js_preenchelivro|v01_livro|v01_folha','Livros',true);
}
function js_preenchelivro(livro,folha){
  document.form1.v01_livro.value = livro;
  document.form1.v01_folha.value = folha;
  db_iframe_mostralivro.hide();
}
</script>
</head>
<body bgcolor=#CCCCCC onLoad="if(document.form1) document.form1.elements[0].focus()" >

<form class="container" name="form1" action="div4_processalivro001.php" method="post" target="" onSubmit="js_marca()">
  <fieldset>
    <legend>Processamento do Livro - Livro da Dívida</legend>	
	<?
	  if(!isset($processar)) {
	?>
	<table class="form-container">
	<tr>
	  <td>Complementar:</td>
	  <td>
	    <?
	      $arr=array("nao"=>"Não","sim"=>"Sim");
	      db_select("complementar",$arr,true,1,"onchange='js_trocalivro(this.value)'");
	    ?>
	  </td>
    </tr>
    <tr>
      <td><?=$Lv01_exerc?></td>
      <td>
		<?
		  $sSqlDivida = $cldivida->sql_query_file(null, "distinct v01_exerc",""," coalesce(v01_livro,0) = 0");
		  $result     = $cldivida->sql_record($sSqlDivida);
		  $numrows    = $cldivida->numrows;
		  $matriz     = array();
		  
		  if (isset($v01_exerc)){
		    $exerc_sel = $v01_exerc;
		  }
		  for ( $i = 0; $i < $numrows; $i++) {

		    db_fieldsmemory($result,$i);
		    $matriz[$v01_exerc] = $v01_exerc;
		  }
		  
		  if (isset($exerc_sel)) {
		    //echo "<script>document.form1.v01_exerc.value=$exerc_sel;</script>";         	 
		    $v01_exerc=$exerc_sel;
		  }
		  
		  arsort($matriz);
		  db_select("v01_exerc",$matriz,true,1);
		?>
	  </td>
	</tr>
	<?
	//<tr>
	//<td><b>Data para correção:<b/></td>
	//<td>
	?>
	<?
	//if(empty($v01_dtoper_dia)){
	//  $v01_dtoper_dia =  date("d",db_getsession("DB_datausu"));
	//  $v01_dtoper_mes =  date("m",db_getsession("DB_datausu"));
	//  $v01_dtoper_ano =  date("Y",db_getsession("DB_datausu"));
	//}	
	//db_inputdata('v01_dtoper',@$v01_dtoper_dia,@$v01_dtoper_mes,@$v01_dtoper_ano,true,'text',$db_opcao,"")
	?>
	<tr>
	  <td>Data inicial de inscrição:</td>
	  <td>
		<?
		  db_inputdata('dtini',@$dtini_dia,@$dtini_mes,@$dtini_ano,true,'text',$db_opcao,"");
		?>
		<b>&nbsp;À&nbsp;</b>
		<?
		  db_inputdata('dtfim',@$dtfim_dia,@$dtfim_mes,@$dtfim_ano,true,'text',$db_opcao,"");
		?>
	  </td>
	</tr>
	<tr>
	  <td>Numero da ultima página:</td>
	  <td>
		<?
		  if(empty($v01_folha)&&(!isset($complementar)||isset($complementar)&&$complementar!="sim")){
		    $v01_folha=1;
		  }
		  if (isset($complementar)&&$complementar=="sim"){
		    $opc = 3;
		  }else{
		    $opc = 1;
		  }
		  db_input('v01_folha',6,$Iv01_folha,true,'text',$opc);
		  db_input('v01_folhaaux',6,$Iv01_folha,true,'hidden',1);
		?>
	  </td>
	</tr>
	<tr>
	  <td>
		<?	   
		  if (isset($complementar)&&$complementar=="sim"){
		    $opc = 1;
		  }else{
		    $opc = 3;
		  }
		db_ancora("Numero do livro:","js_pesquisalivro(true);",$opc)
		?>
	  </td>
	  <td>
		<?
		  if(empty($processar)&&(!isset($complementar)||isset($complementar)&&$complementar!="sim")){	   
		    $result=$cldivida->sql_record($cldivida->sql_query_file(null,"max(v01_livro)+1 as v01_livro",""," v01_folha <> 0"));
		    db_fieldsmemory($result,0);        
		  } 	
		  db_input('v01_livro',8,$Iv01_livro,true,'text',3);
		  db_input('v01_livroaux',8,$Iv01_livro,true,'hidden',1);
		?>
	  </td>
	</tr>
	<tr>
	  <td>Opção de Seleção:</td>
	  <td>
		<?
		  $xxx = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
		  db_select('sele',$xxx,true,2);
		?>
	  </td>
	</tr>
	<tr>
	  <td colspan="2">
		<?
		  $aux = new cl_arquivo_auxiliar;
		  $aux->cabecalho = "<strong>PROCEDÊNCIAS</strong>";
		  $aux->codigo = "v03_codigo";
		  $aux->descr  = "v03_descr";
		  $aux->nomeobjeto = 'proced';
		  $aux->funcao_js = 'js_mostraproc';
		  $aux->funcao_js_hide = 'js_mostraproc1';
		  $aux->sql_exec  = "";
		  $aux->func_arquivo = "func_proced.php";
		  $aux->nomeiframe = "ifr_procedencia";
		  $aux->localjan = "top.corpo";
		  $aux->onclick = "";
		  $aux->db_opcao = 2;
		  $aux->tipo = 2;
		  $aux->top = 0;
		  $aux->linhas = 5;
		  $aux->vwhidth = 400;
		  $aux->funcao_gera_formulario();
		?>
	  </td>
	</tr>
	</table>
  </fieldset>
  <input name="processar" type='submit' value="Processar">
  <input name="mostra" type='button' value="Mostra Livros" onclick="js_mostralivro();" >

<?
}
?>
<table>
<tr>
<td  colspan='2' align='center'>
<?
if(isset($processar)){
  db_criatermometro("termometro", "Concluído");
}
?>
<!-- <input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=50> -->
</td>
</tr>
</table> 

</form>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_marca(){
  var F = document.getElementById("proced").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
}
</script>
<?

if(isset($processar)){
  $sqlerro=false;
  $dbwhere="v01_exerc=$v01_exerc and v01_livro=0 and v01_instit = ".db_getsession('DB_instit') ;

 //Verifica se já existe uma divida com o livro informado 
  $cldivida->sql_record($cldivida->sql_query(null,"v01_coddiv",null," v01_livro = $v01_livro limit 1"));
  if($cldivida->numrows > 0 && $complementar == 'nao'){
   db_msgbox('Aviso:\nLivro já existente!\nUtilize a opção complementar "Sim"');	
   $sqlerro=true;
   db_redireciona("div4_processalivro001.php");
  }

  if(isset($proced)){
    $procs='';
    $vir='';
    for($i=0; $i<count($proced); $i++){
      $procs .= $vir.$proced[$i];
      $vir=',';
    }
    if($sele=='S'){
      $dbwhere.=" and v01_proced in ($procs) and v01_instit = ".db_getsession('DB_instit') ;
    }else{
      $dbwhere.=" and v01_proced not in ($procs) and v01_instit = ".db_getsession('DB_instit') ;
    }
  }
  $dtini =  $dtini_ano."-".$dtini_mes."-".$dtini_dia;
  $dtfim =  $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;
  $data = "";
  if($dbwhere == ""){
    $and = "";
  }else{
    $and = " and ";
  }
  if(isset($dtini_dia) && $dtini_dia != "" && $dtfim_dia == ""){
    $data = " $and v01_dtinclusao >= '$dtini' ";
  }elseif(isset($dtini_dia) && $dtini_dia != "" && isset($dtfim_dia) && $dtfim_dia != ""){
    $data = " $and v01_dtinclusao >= '$dtini' and v01_dtinclusao <= '$dtfim' ";
  }

  // Apaga indice da tabela Divida pelo Nro do Livro 
  // para melhorar a performance da rotina
  $resdropindex = @db_query("DROP IF EXISTS INDEX divida_livro_in;");

  $result     = $cldivida->sql_record($cldivida->sql_query_file(null,"v01_coddiv","","$dbwhere $data"));
  $count_div  = pg_result(db_query("select count(*) as count_div from divida where v01_livro=".$v01_livro." and v01_folha=".$v01_folha." and  v01_instit = ".db_getsession('DB_instit')),0,0); 
  $numrows=$cldivida->numrows;
  if ($numrows != 0) {
    db_inicio_transacao();

    $cldivida->v01_livro=$v01_livro;
    if (!isset($v01_folha)) {
      $v01_folha = 2;
    }
    $tot_reg=$numrows-1;
    for($i=0; $i<$numrows; $i++){
      db_atutermometro($i, $numrows, "termometro");
      if($count_div >= 30){
        $v01_folha++;
		$count_div = 0;
      }
      $count_div++;
      db_fieldsmemory($result,$i);
      $cldivida->v01_folha  = $v01_folha;
      $cldivida->v01_coddiv = $v01_coddiv;
      $cldivida->alterar($v01_coddiv);
      if($cldivida->erro_status==0){
        $sqlerro=true;
        $erromsg=$cldivida->erro_msg;
        break;
      }
    }
    db_fim_transacao($sqlerro);

  } else {
    $sqlerro=true;
    $erromsg="Sem lançamentos a processar!";
  }

  // Recria indice da tabela Divida pelo Nro do Livro 
  // para melhorar a performance da rotina
  $rescreateindex   = @db_query("CREATE INDEX divida_livro_in ON divida(v01_livro);");
  $resanalyzedivida = @db_query("ANALYZE divida;");


}

if(isset($processar)){
  if($sqlerro==true){
    db_msgbox($erromsg);
    echo "<script>location.href='div4_processalivro001.php'</script>";
  }else{
    ?>
    <script>/*
    if(confirm("Deseja imprimir o relatório?")){
      jan = window.open('div4_processalivro002.php?v01_livro=<?=$v01_livro?>&v01_dtoper_ano=<?=$v01_dtoper_ano?>&v01_dtoper_mes=<?=$v01_dtoper_mes?>&v01_dtoper_dia=<?=$v01_dtoper_dia?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      location.href="div4_processalivro001.php";
    }*/
    alert("Livro processado com Sucesso!! \n Imprima o Livro na Rotina de Reemissão");
    location.href='div4_processalivro001.php';
    </script>
    <?
  }
}
?>
<script>

$("complementar").setAttribute("rel","ignore-css");
$("complementar").addClassName("field-size2");
$("v01_exerc").setAttribute("rel","ignore-css");
$("v01_exerc").addClassName("field-size2");
$("dtini").addClassName("field-size2");
$("dtfim").addClassName("field-size2");
$("v01_folha").addClassName("field-size2");
$("v01_livro").addClassName("field-size2");
$("fieldset_proced").addClassName("separator");
$("v03_codigo").addClassName("field-size2");
$("v03_descr").addClassName("field-size7");
$("proced").style.width = "100%";
$("v03_descr").style.width = "187px";
$$("#fieldset_proced table")[0].setAttribute("width", "100%");

</script>