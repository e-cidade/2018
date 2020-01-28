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
require_once("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_tpcardapioturma_classe.php");
include("classes/db_mer_cardapioescola_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_tpcardapioturma = new cl_mer_tpcardapioturma;
$clmer_cardapioescola = new cl_mer_cardapioescola;
$clmer_cardapio = new cl_mer_cardapio;
$clmer_cardapiodata = new cl_mer_cardapiodata;
$clmer_cardapiodia = new cl_mer_cardapiodia;
$clmatricula = new cl_matricula;
$db_opcao = 1;
$db_botao = true;
if (isset($alterar) || isset($incluir)) {  
	
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_tpcardapioturma->excluir("","me28_i_cardapioescola in (select me32_i_codigo from mer_cardapioescola where me32_i_tipocardapio = $me32_i_tipocardapio)");
  if (isset($checkserie)) {
  	  	
    for ($t=0;$t<count($checkserie);$t++) {
    	    	
      $aSerie = explode("|",$checkserie[$t]);
      $clmer_tpcardapioturma->me28_i_serie = $aSerie[0];
      $clmer_tpcardapioturma->me28_i_cardapioescola = $aSerie[1];
      $clmer_tpcardapioturma->incluir(null);
      
    }
    
  }
  db_fim_transacao();
  if ($clmer_tpcardapioturma->erro_status=="0") {
    $clmer_tpcardapioturma->erro(true,false);
  } else{
  
  	?>
  	<script>
  	parent.iframe_a2.location.href=' mer1_mer_cardapioescola001.php?me32_i_tipocardapio=<?=$me32_i_tipocardapio?>&me27_c_nome=<?=$me27_c_nome?>';    
  	</script>
    <?

  }
  
}
$dataatual = date("Y-m-d",db_getsession("DB_datausu"));
$horaatual = date("H:i");
$result_verif1 = $clmer_cardapiodata->sql_record($clmer_cardapiodata->sql_query("",
                                                                                "*",
                                                                                "",
                                                                                "me01_i_tipocardapio = $me32_i_tipocardapio"
                                                                               ));
                                                                               
$sWhere         = " me01_i_tipocardapio = $me32_i_tipocardapio AND (me12_d_data < '$dataatual' OR (me12_d_data = '$dataatual' "; 
$sWhere        .= " AND me03_c_fim < '$horaatual')) AND not exists ";
$sWhere        .= "                                       (select * from mer_cardapiodata inner join mer_cardapiodiaescola on me37_i_codigo = me13_i_cardapiodiaescola";
$sWhere        .= "                                                where me12_i_codigo = me37_i_cardapiodia)";
$result_verif2  = $clmer_cardapiodia->sql_record($clmer_cardapiodia->sql_query("",
                                                                               "*",
                                                                               "",
                                                                               $sWhere
                                                                              ));
if ($clmer_cardapiodata->numrows>0 || $clmer_cardapiodia->numrows>0) {
  $db_botao   = false;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
      <fieldset style="width:95%"><legend><b>Etapas do Cardápio   <?=$me32_i_tipocardapio?> - <?=$me27_c_nome?>  </b></legend>
      <table border="0" align="left" width="100%">
        <tr>
          <td>
            <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
              <tr>
                <td align="center">
                  <input style="height:12px;" type="checkbox" id="MT" value="" onclick="js_marcatudo();"> Marcar Tudo
                  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
                         type="submit" id="db_opcao" value="Salvar" <?=($db_botao==false?"disabled":"")?> >
                </td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="1" cellspacing="0" cellpadding="0">
                  <?
                  $rsCardapioEscola = $clmer_cardapioescola->sql_record($clmer_cardapioescola->sql_query("","me32_i_codigo,ed18_i_codigo,ed18_c_nome,me27_i_ano","me32_i_ordem"," me32_i_tipocardapio = $me32_i_tipocardapio"));
                  for ($t=0;$t<$clmer_cardapioescola->numrows;$t++) {
                	
                    $oCardapioEscola = db_utils::fieldsMemory($rsCardapioEscola, $t);         	
                    ?><tr><td colspan="2" style="color:#DEB887;font-weight:bold;background:#444444"><?=$oCardapioEscola->ed18_i_codigo?> - <?=$oCardapioEscola->ed18_c_nome?></td></tr><?
                    $rsMatricula = $clmatricula->sql_record(
                                    $clmatricula->sql_query("",
                                                            "count(*) as qtde,
                                                             cursoedu.ed29_i_codigo,
                                                             cursoedu.ed29_c_descr,
                                                             serie.ed11_i_codigo,
                                                             serie.ed11_c_descr,
                                                             serie.ed11_i_sequencia",                          
                                                            "ensino.ed10_i_codigo,serie.ed11_i_sequencia",
                                                            "turma.ed57_i_escola = ($oCardapioEscola->ed18_i_codigo) 
                                                             AND calendario.ed52_i_ano = ($oCardapioEscola->me27_i_ano) 
                                                             AND ed60_c_situacao = 'MATRICULADO' 
                                                             GROUP BY serie.ed11_i_codigo,
                                                                      serie.ed11_i_sequencia,
                                                                      serie.ed11_c_descr,
                                                                      ensino.ed10_i_codigo,
                                                                      cursoedu.ed29_i_codigo,
                                                                      cursoedu.ed29_c_descr "
                                                           ));
                    if ($clmatricula->numrows>0) {
                      
                      $pri_curso = "";
                      $pri_serie = "";
                      $linhas_matricula1 = $clmatricula->numrows;
                      for ($i=0;$i<$linhas_matricula1;$i++) {
                        
                        $oMatricula = db_utils::fieldsMemory($rsMatricula, $i);
                        if ($pri_curso!=$oMatricula->ed29_i_codigo) {

                          ?><tr><td colspan="2" style="font-weight:bold;background:#DBDBDB">Curso: <?=$oMatricula->ed29_c_descr?></td></tr><?
                          ?><tr style="background:#DBDBDB"><td>Etapa</td><td>Qtde. Alunos</td></tr><?
                          $pri_curso = $oMatricula->ed29_i_codigo;

                        }
                        $rsTpCardapioTurma = $clmer_tpcardapioturma->sql_record($clmer_tpcardapioturma->sql_query("","me28_i_codigo as nada",""," me28_i_cardapioescola = ($oCardapioEscola->me32_i_codigo) AND me28_i_serie = {$oMatricula->ed11_i_codigo}"));
                        if ($clmer_tpcardapioturma->numrows>0) {
                          $sChecked = "checked"; 
                        } else {
                          $sChecked = "";
                        }
                        $rsMatriculaTurma = $clmatricula->sql_record(
                                             $clmatricula->sql_query("",
                                                                     "count(*) as qtde,
                                                                      turma.ed57_i_codigo,
                                                                      turma.ed57_c_descr",                          
                                                                     "turma.ed57_c_descr",
                                                                     "turma.ed57_i_escola = ($oCardapioEscola->ed18_i_codigo) 
                                                                      AND calendario.ed52_i_ano = ($oCardapioEscola->me27_i_ano)
                                                                      AND matriculaserie.ed221_i_serie = ($oMatricula->ed11_i_codigo)
                                                                      AND ed60_c_situacao = 'MATRICULADO' 
                                                                      GROUP BY turma.ed57_i_codigo,
                                                                               turma.ed57_c_descr"
                                                                     ));
                        $linhas_matricula2 = $clmatricula->numrows;
                        ?>
                        <tr style="background:#f3f3f3;">
                          <td width="30%">
                            <input type="checkbox" name="checkserie[]" id="checkserie" <?=$sChecked?> value="<?=$oMatricula->ed11_i_codigo?>|<?=$oCardapioEscola->me32_i_codigo?>"> 
                            <a href="javascript:js_matriculas(<?=$oMatricula->ed11_i_codigo?>,<?=$oCardapioEscola->ed18_i_codigo?>,<?=$oCardapioEscola->me27_i_ano?>)"><?=$oMatricula->ed11_c_descr?></a> 	
                          </td>
                          <td>
                            <?=$oMatricula->qtde?>
                          </td>
                        </tr>
                        <tr id="etapa<?=$oMatricula->ed11_i_codigo?>" style="visibility:hidden;position:absolute;">
                          <td colspan="2">
                            <table width="100%" cellspacing="0" cellpadding="1" border="1">
                              <?
                              for ($tt=0;$tt<$linhas_matricula2;$tt++) {
                              	
                              	$oMatriculaTurma = db_utils::fieldsMemory($rsMatriculaTurma, $tt);
                              	?>
                              	<tr>
                              	  <td width="30%">
                              	    Turma: <?=$oMatriculaTurma->ed57_c_descr?>
                              	  </td>
                              	  <td>
                                    <?=$oMatriculaTurma->qtde?>
                              	  </td>
                                </tr>
                              	<?
                              	
                              }
                              ?>
                            </table>
                          </td>
                        </tr>
                        <?
                        
                      } 	
                       	
                    } else {
                    	
                      echo "<tr><td colspan='2'>Nenhuma matrícula ativa para esta escola.</td></tr>";
                    	
                    }
                     	
                  }
                  ?>
                  </table>
                </td>
              </tr>
            </table>
          </td> 
        </tr>    
      </table>
      </fieldset>
      </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>

function js_marcatudo(codigo) {
	
  tam = document.form1.checkserie.length;
  if (tam==undefined) {

    if (document.getElementById("MT").checked==true) {
      document.form1.checkserie.checked=true;
    } else {
      document.form1.checkserie.checked=false;
    }
	  
  } else {

    for (i=0;i<tam;i++) {
	      
	  if (document.getElementById("MT").checked==true) {
	    document.form1.checkserie[i].checked=true;
	  } else {
	    document.form1.checkserie[i].checked=false;
	  }
	         
    }

  }
}

function js_marcaensino(codigo) {
	
  tam = document.form1.checkserie.length;
  arr_entrada = document.form1.codentrada.value.split("|");
  arr_turma = document.form1.codturma.value.split("|");
  for(t=0;t<arr_entrada.length;t++){
	  
	if (codigo==arr_entrada[t]) {
		
	  codturma = arr_turma[t].split(",");
	  for (x=0;x<codturma.length;x++) {
		  
	    for (i=0;i<tam;i++) {
		    
	      if (document.form1.checkserie[i].value==codturma[x]){
		      
	        if (document.getElementById("MTE"+codigo).checked==true){
	          document.form1.checkserie[i].checked = true;
	        } else {
	          document.form1.checkserie[i].checked = false;
	        }
	        
	      }
	      
	    }
	    
	  }
	  
	}
	
  }
  
}

function js_matriculas(serie,escola,ano){
  
  js_OpenJanelaIframe('','db_iframe_matriculas','func_etapacardapio?serie='+serie+'&escola='+escola+'&ano='+ano,'Turmas',true);
  location.href = "#topo";
  
}
function js_MostraTurma(etapa){
  document.getElementById("etapa"+etapa).style.visibility = "visible";
}
</script>