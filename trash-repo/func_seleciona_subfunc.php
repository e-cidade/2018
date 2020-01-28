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
include("classes/db_orcparamsubfunc_classe.php");
include("classes/db_orcparamfunc_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento  = new cl_orcparamelemento;
$clorcparamsubfunc = new cl_orcparamsubfunc;
$clorcparamfunc    = new cl_orcparamfunc;


$clorcparamseq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");

$clorcparamseq = new cl_orcparamseq;

?>
<script>
function js_voltar()
{
  document.location.href="con2_conrelparametros.php?c83_codrel=<?=$o69_codparamrel?>";
}
</script>
<?

if (isset($processar) && $processar=='processar') {
  db_inicio_transacao();
  $erro = false;
  
  // apaga registros
  $instit = db_getsession("DB_instit");
  $sql_orcparamsubfunc = "select * from orcparamsubfunc
                          where o44_anousu    = ".db_getsession("DB_anousu")." and
                                o44_codparrel = $c83_codrel and
                                o44_sequencia = $c69_codseq";
  
  $clorcparamsubfunc->sql_record($sql_orcparamsubfunc);
  
  if ($clorcparamsubfunc->numrows > 0) {
    $clorcparamsubfunc->excluir(db_getsession("DB_anousu"),$c83_codrel,$c69_codseq);
    if ($clorcparamsubfunc->erro_status == 0) {
      $erro = true;
      $msg = $clorcparamsubfunc->erro_msg;
    }
  }
  $matriz = explode("#", $chaves);
  //gera matriz com as chaves
  
  $subfunc = ""; 
  for ($i = 0; $i < sizeof($matriz); $i ++) {
    // o teste abaixo e necessario porque quando desmerca todos os itens na tela, o expode acima gera 1 vazio
    if (strlen(trim($matriz[$i])) > 0) {
      // db_msgbox($matriz[$i]);
      if ($subfunc != $matriz[$i]){
           $clorcparamsubfunc->o44_instit=db_getsession("DB_instit");
           $clorcparamsubfunc->o44_subfunc = $matriz[$i];
           $clorcparamsubfunc->incluir(db_getsession("DB_anousu"), $c83_codrel, $c69_codseq);
           if ($clorcparamsubfunc->erro_status == 0) {
                $erro = true;
                $msg = $clorcparamsubfunc->erro_msg;
           }

           $subfunc = $matriz[$i];
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
     <? $s = "select o69_descr,o69_verificaano 
              from orcparamseq 
	      where o69_codparamrel = $o69_codparamrel
	             and o69_codseq = $o69_codseq 
              ";
	$r = pg_exec($s);
	if (pg_numrows($r)>0){
            db_fieldsmemory($r,0);
	    echo  "<b>Parametro: $o69_descr </b>";	
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
     $sWhereAno  = " and o58_anousu =".db_getsession("DB_anousu");
   }
   $res_func     = $clorcparamfunc->sql_record($clorcparamfunc->sql_query_file($o69_codseq,
                                                                               db_getsession("DB_anousu"),
                                                                               $o69_codparamrel,
                                                                               null,"o45_func","o45_sequencia"));
   $numrows      = $clorcparamfunc->numrows;
   $dbwhere_func = "";
   if ($numrows > 0){
        $dbwhere_func = "and o52_funcao in (";
        $virgula      = "";

        for($i=0; $i < $numrows; $i++){
             db_fieldsmemory($res_func,$i);

             $dbwhere_func .= $virgula.$o45_func;
             $virgula       = ", ";
        }

        $dbwhere_func .= ")";
   }


               $cliframe_seleciona = new cl_iframe_seleciona();
               $sql = "select   distinct o58_funcao,
                                o52_descr,
      	                        o58_subfuncao,
 	                        o53_descr
		       from orcdotacao
		           inner join orcfuncao       on o52_funcao = o58_funcao
			         inner join orcsubfuncao    on o53_subfuncao=o58_subfuncao
 		       where o58_funcao > 0 {$dbwhere_func} {$sWhereAno}"; 

		$sql_marca = "
                             select   distinct o58_funcao,
                                      o52_descr,
      	                              o58_subfuncao,
 	                              o53_descr
		             from orcdotacao
		                 inner join orcfuncao on o52_funcao=o58_funcao
			         inner join orcsubfuncao on o53_subfuncao=o58_subfuncao
				 inner join orcparamsubfunc on o44_anousu = ".db_getsession("DB_anousu")." and
				                               o44_codparrel = $o69_codparamrel and
							       o44_sequencia = $o69_codseq and
							       o44_subfunc = orcsubfuncao.o53_subfuncao
				 
 		             where o58_funcao > 0 {$dbwhere_func} {$sWhereAno}";

		$cliframe_seleciona->campos = "o58_funcao,o52_descr,o58_subfuncao,o53_descr";
		$cliframe_seleciona->legenda = "Recursos";
		$cliframe_seleciona->sql = $sql;
		$cliframe_seleciona->sql_marca = $sql_marca;
		$cliframe_seleciona->iframe_height = "375";
		$cliframe_seleciona->iframe_width = "100%";
		$cliframe_seleciona->iframe_nome = "iframe_nome";
		$cliframe_seleciona->chaves = "o58_subfuncao";
		$cliframe_seleciona->iframe_seleciona(1);

//    echo $sql."<br>".$sql_marca."<br>";

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