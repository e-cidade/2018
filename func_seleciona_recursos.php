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

include("classes/db_orcparamseq_classe.php");
include("classes/db_orcparamelemento_classe.php");
include("classes/db_orcparamrecurso_classe.php");
include("classes/db_orcparamsubfunc_classe.php");
include("classes/db_orcparamfunc_classe.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;
$clorcparamrecurso  = new cl_orcparamrecurso;
$clorcparamsubfunc = new cl_orcparamsubfunc;
$clorcparamfunc    = new cl_orcparamfunc;


$clorcparamseq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");

$clorcparamseq = new cl_orcparamseq;

?>
<script>
function js_voltar(){
  document.location.href="con2_conrelparametros.php?c83_codrel=<?=$o69_codparamrel?>";
}
</script>
<?

if (isset($processar) && $processar=='processar'){
   db_inicio_transacao();
   $erro = false;

   // apaga registros
   $instit = db_getsession("DB_instit");
   $clorcparamrecurso->sql_record("select * from orcparamrecurso 
                                   where o44_anousu = ".db_getsession("DB_anousu")." and
				         o44_codparrel=$c83_codrel and 
				         o44_sequencia=$c69_codseq 
				  ");
   if ($clorcparamrecurso->numrows > 0) {
	$clorcparamrecurso->excluir(db_getsession("DB_anousu"),$c83_codrel,$c69_codseq);
	if ($clorcparamrecurso->erro_status == 0) {
		$erro = true;
		$msg = $clorcparamrecurso->erro_msg;
	}
   }
   $matriz = explode("#", $chaves); //gera matriz com as chaves


   for ($i = 0; $i < sizeof($matriz); $i ++) {
	// o teste abaixo e necessario porque quando desmerca todos os itens na tela, o expode acima gera 1 vazio
	if ($matriz[$i] != "") {
         	// db_msgbox($matriz[$i]);
		$clorcparamrecurso->o44_instit=db_getsession("DB_instit");
		$clorcparamrecurso->incluir(db_getsession("DB_anousu"), $c83_codrel, $c69_codseq, $matriz[$i]);
		if ($clorcparamrecurso->erro_status == 0) {
			$erro = true;
			$msg = $clorcparamrecurso->erro_msg;
		}
	}
   }
   db_fim_transacao($erro);
   if ($erro == true) {
	db_msgbox($msg);
   }
   echo "<script>
            js_voltar();
            parent.js_refresh(); 
   </script>";
}  
// ---------------------------------------------------------------------------------

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body>

<form name=form1 action=""  method="POST">

<input type="hidden" name="lista" value="">
 <input type="hidden" name="c83_codrel" value="<?=$o69_codparamrel ?>">
 <input type="hidden" name="c69_codseq" value="<?=$o69_codseq ?>">

<table border=1 align=center>
 <tr>
   <td colspan=1>
     <? $s = "select o69_descr,o69_libsubfunc,o69_libfunc,o69_verificaano
              from orcparamseq 
	      where o69_codparamrel = $o69_codparamrel
	             and o69_codseq = $o69_codseq 
              ";
	$r = pg_exec($s);
	if (pg_numrows($r)>0){
            db_fieldsmemory($r,0);
	    echo  "<b>Parametro: $o69_descr </b>";	
	}  

  // Verifica se existe função
  $res_func     = $clorcparamfunc->sql_record($clorcparamfunc->sql_query_file($o69_codseq,
                                                                              db_getsession("DB_anousu"),
                                                                              $o69_codparamrel,
                                                                              null,"o45_func","o45_sequencia"));
  $numrows      = $clorcparamfunc->numrows;
  $tem_funcao   = 0;

  if ($numrows > 0){
       $tem_funcao = 1;
  }

	// verifica se existe uma subfunção
	$tem_subfuncao = 0;
	$rr = $clorcparamsubfunc->sql_record($clorcparamsubfunc->sql_query_file(db_getsession("DB_anousu"),$o69_codparamrel,$o69_codseq));
	if ($clorcparamsubfunc->numrows >0){
            $tem_subfuncao = 1; 
	}  

     ?>
   </td>
 </tr>

 <tr>
   <td colspan="1" nowrap align="center">
      <input type="button" value="Gravar Parametros" onclick="js_processar();" <?=($flag_permissao=="false"?"disabled":"")?>>
      <input type="button" value="Voltar" onclick="js_voltar();">
   </td>
 </tr>
</table>

<? 
   $sWhereAno    = '';
   if ($o69_verificaano == 't') {
     $sWhereAno  = " where o58_anousu =".db_getsession("DB_anousu");
   }
                $cliframe_seleciona = new cl_iframe_seleciona();
                if (isset($o69_libfunc) && $o69_libfunc=='t' && $tem_funcao==1 && $tem_subfuncao==0){
                     $sql = "select distinct(o15_codigo),o15_descr
                             from orctiporec
                                  inner join orcdotacao on o58_codigo = orctiporec.o15_codigo and o58_anousu=".db_getsession("DB_anousu")."
                                  inner join orcparamfunc on o58_funcao = orcparamfunc.o45_func and
                                                             o45_anousu = ".db_getsession("DB_anousu")." and
                                                             o45_codparrel = $o69_codparamrel and
                                                             o45_sequencia = $o69_codseq {$sWhereAno}";
                } elseif (isset($o69_libsubfunc) && $o69_libsubfunc=='t' && $tem_subfuncao==1 && $tem_funcao==0){
                   // pega somente os recursos das subfunções relacionadas
		   $sql = "select distinct(o15_codigo),o15_descr
		           from orctiporec
			      inner join orcdotacao on o58_codigo = orctiporec.o15_codigo and o58_anousu=".db_getsession("DB_anousu")."
			      inner join orcparamsubfunc on o58_subfuncao = orcparamsubfunc.o44_subfunc and 
			                                    o44_anousu = ".db_getsession("DB_anousu")." and
			 		                    o44_codparrel = $o69_codparamrel and
			 	 			    o44_sequencia = $o69_codseq
                  {$sWhereAno}

		         ";
			
               } elseif (isset($o69_libsubfunc) && $o69_libsubfunc=='t' && $tem_subfuncao==1 && $tem_funcao==1){
		   $sql = "select distinct(o15_codigo),o15_descr
		           from orctiporec
			      inner join orcdotacao on o58_codigo = orctiporec.o15_codigo and o58_anousu=".db_getsession("DB_anousu")."
			      inner join orcparamsubfunc on o58_subfuncao = orcparamsubfunc.o44_subfunc and 
			                                    o44_anousu = ".db_getsession("DB_anousu")." and
			 		                    o44_codparrel = $o69_codparamrel and
			 	 			    o44_sequencia = $o69_codseq
                  {$sWhereAno}

		         ";
               } else {  
		  
	           $sql = "select * 
	                     from orctiporec 
	                    where o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."' 
	                 order by o15_codigo ";
			  
                }
		
		$sql_marca = "
                              select o44_codrec as o15_codigo
			      from orcparamrecurso 
			      where 
                                  o44_anousu = ".db_getsession("DB_anousu")." and
                                  o44_codparrel = $o69_codparamrel and
                                  o44_sequencia = $o69_codseq
		             ";

		$cliframe_seleciona->campos = "o15_codigo,o15_descr";
		$cliframe_seleciona->legenda = "Recursos";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->sql_marca = $sql_marca;
		$cliframe_seleciona->iframe_height = "375";
		$cliframe_seleciona->iframe_width = "100%";
		$cliframe_seleciona->iframe_nome = "iframe_nome";
		$cliframe_seleciona->chaves = "o15_codigo";
		$cliframe_seleciona->iframe_seleciona(1);

//    echo $sql."<br>";

?>



</form>




<script>

function js_processar(){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','processar');  
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','processar');
  document.form1.appendChild(obj);
  // submete o formulario
  document.form1.submit();
}





</script>
</html>