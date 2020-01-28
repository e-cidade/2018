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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_liborcamento.php");
include ("classes/db_conrelinfo_classe.php");
include ("classes/db_conrelvalor_classe.php");
include ("classes/db_orcparamrel_classe.php");
include ("classes/db_orcparamseq_classe.php");
include ("classes/db_orcparamelemento_classe.php");
include ("classes/db_orcparamrecurso_classe.php");
include ("classes/db_orcparamsubfunc_classe.php");
include ("classes/db_orcparamnivel_classe.php");
include ("classes/db_orcparamfunc_classe.php");
include ("model/linhaRelatorioContabil.model.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clconrelinfo  = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;
$clorcparamrel = new cl_orcparamrel;
$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;
$clorcparamrecurso  = new cl_orcparamrecurso;
$clorcparamsubfunc  = new cl_orcparamsubfunc;
$clorcparamfunc     = new cl_orcparamfunc;

$clrotulo = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('o42_descrrel');

$iAnoPesquisa = db_getsession("DB_anousu");

if (isset($iAnoInicial) && !empty($iAnoInicial)) {
  
  $iAnoPesquisa = $iAnoInicial;
}


$db_opcao = 1;
$db_botao = true;

if (!isset($filtrar_seq)){
     $filtrar_seq = "C";
}

$res = $clorcparamrel->sql_record($clorcparamrel->sql_query($c83_codrel));
if ($clorcparamrel->numrows > 0) {
	db_fieldsmemory($res, 0);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function atualiza_nivel($rel,$linha,$valor){	
    $msg = "0| Registro Atualizado !";
    $clorcparamnivel = new cl_orcparamnivel;
	
    $res = $clorcparamnivel->sql_record($clorcparamnivel->sql_query_file(db_getsession("DB_anousu"),$rel,$linha));
	
    $clorcparamnivel->o44_codparrel     = $rel; 
    $clorcparamnivel->o44_sequencia     = $linha;
    $clorcparamnivel->o44_anousu        = db_getsession("DB_anousu");
  
    $clorcparamnivel->o44_nivel         = $valor;
    
    if ($clorcparamnivel->numrows > 0){    	
	  $clorcparamnivel->o44_nivelexclusao = "" ;  
    	  $clorcparamnivel->alterar(db_getsession("DB_anousu"),$rel,$linha);
	} else {
	  $clorcparamnivel->o44_nivelexclusao = '0' ;  
	  $clorcparamnivel->incluir(db_getsession("DB_anousu"),$rel,$linha);
	}	
	$erro = $clorcparamnivel->erro_msg;
	if ($clorcparamnivel->erro_status==0){
    		$msg= "1| Falha ao atualizar Nivel".$erro;
  	}
  		
  	return $msg;    		
}
function atualiza_nivel_exclusao($rel,$linha,$valor){	
    $msg = "0| Registro Atualizado !";
    $clorcparamnivel = new cl_orcparamnivel;
	
    $res = $clorcparamnivel->sql_record($clorcparamnivel->sql_query_file(db_getsession("DB_anousu"),$rel,$linha));
	
    $clorcparamnivel->o44_codparrel     = $rel; 
    $clorcparamnivel->o44_sequencia     = $linha;
    $clorcparamnivel->o44_anousu        = db_getsession("DB_anousu");
  
    $clorcparamnivel->o44_nivelexclusao = $valor;
    
    if ($clorcparamnivel->numrows > 0){    	
	  $clorcparamnivel->o44_nivel = "" ;  
    	  $clorcparamnivel->alterar(db_getsession("DB_anousu"),$rel,$linha);
	} else {
	  $clorcparamnivel->o44_nivel = '0' ;  
	  $clorcparamnivel->incluir(db_getsession("DB_anousu"),$rel,$linha);
	}	
	$erro = $clorcparamnivel->erro_msg;
	if ($clorcparamnivel->erro_status==0){
    		$msg= "1| Falha ao atualizar Nivel".$erro;
  	}
  		
  	return $msg;    		
}

include("dbforms/Sajax.php");  // inclusão da biblioteda ajax
sajax_init();// Inicializar o sajax
$sajax_debug_mode = 0;// para Debugar o sajax = 0 desligado 1 = ligado
sajax_export("atualiza_nivel");// função exportada !
sajax_export("atualiza_nivel_exclusao");// função exportada !
sajax_handle_client_request();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_filtrarSeq(){
  document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc" style="overflow:hidden">

<form name="form1" method="post" action="" >
<?
$dbwhere   = "o69_codparamrel = $c83_codrel"; 
$lista_seq = "";
$virgula   = "";

$res_elemento = $clorcparamelemento->sql_record($clorcparamelemento->sql_query_file(db_getsession("DB_anousu"),$c83_codrel,null,null,db_getsession("DB_instit"),"distinct o44_codparrel,o44_sequencia as seq"));     
$numrows      = $clorcparamelemento->numrows;
if ($numrows > 0){
//     db_criatabela($res_elemento);
     for($i=0; $i < $numrows; $i++){
          db_fieldsmemory($res_elemento,$i);
          $lista_seq .= $virgula.$seq;
          $virgula    = ",";
     }
}
if ($filtrar_seq == "C" && strlen(trim(@$lista_seq)) == 0){
     $filtrar_seq = "S";
}
if ($filtrar_seq == "C" && strlen(trim(@$lista_seq)) > 0){
     $dbwhere .= " and o69_codseq in ($lista_seq)";
}

if ($filtrar_seq == "S" && strlen(trim(@$lista_seq)) > 0){
     $dbwhere .= " and o69_codseq not in ($lista_seq)";
}
?>
<table  border=0  width=100% height=96%>   
<tr>
 <td>
    <table  align="center" border=0>
     <tr><td align=left width=20%><? db_ancora(@$Lc83_codrel,"js_pesquisac60_codcla(true);",3);?></td>
         <td><? db_input('c83_codrel',5,$Ic83_codrel,true,'text',3,"")?>
             <? db_input('o42_descrrel',60,$Io42_descrrel,true,'text',3,"")?></td>
         <td>&nbsp;&nbsp;
           <?
              $matriz = array("C"=>"COM PARÂMETROS CONFIGURADOS","S"=>"SEM PARÂMETROS CONFIGURADOS","T"=>"TODAS AS SEQüÊNCIAS");
              db_select("filtrar_seq",$matriz,true,4,"onChange='js_filtrarSeq();'");
           ?>
         </td>    
    </tr>
    </table>
 </td>
</tr>

<tr>
  <td colspan=8 height=20px>
  <div style="width:100%"> 
     <table border=0 width=98%>
     <tr>
         <td width=5%  style="border:1px dotted"><b>COD</b> </td>
         <td width=23% style="border:1px dotted"><b>Linha</b></td>
         <td width=10% style="border:1px dotted"><b>Adição de Parametros</b></td>
         <td width=10% style="border:1px dotted"><b>Nível/Comparar</b></td>
         <!--<td width=12% style="border:1px dotted"><b>Exclusão de Parametros</b></td>-->
         <!--<td width=12% style="border:1px dotted"><b>Nível/Exclusao</b></td>-->
         <td width=10% style="border:1px dotted"><b>Função</b></td>
         <td width=10% style="border:1px dotted"><b>SubFunção</b></td>
         <td width=10% style="border:1px dotted"><b>Recurso</b> </td>
         <td width=10% style="border:1px dotted"><b>Colunas</b> </td>
     </tr>
     </table>
  </div> 
  </td>
</tr>
<tr>
 <td colspan=8 valign=top height=100%>
 <div style="width:100%">
 <table border=0 width=98% style='' cellspacing="0">
 <tbody style='height:500;overflow: scroll;overflow-x:hidden;background-color: white;border:2px inset white'>

 <? 
/*
echo ($clorcparamseq->sql_query($c83_codrel, null, "o69_codparamrel,
                                                                                   o69_codseq,
                                                                                   o69_grupo,
                                                                                   o69_grupoexclusao,
                                                                                   o69_descr,
                                                                                   o69_librec,
                                                                                   o69_libsubfunc,
                                                                                   o69_libfunc,
                                                                                   o44_nivel as o69_nivel,
                                                                                   o44_nivelexclusao as o69_nivelexclusao,
                                                                                   o69_libnivel","o69_codseq","$dbwhere"));
exit;
*/
$record = $clorcparamseq->sql_record($clorcparamseq->sql_query($c83_codrel, null, "distinct o69_codparamrel,
                                                                                   o69_codseq,
                                                                                   o69_grupo,
                                                                                   o69_grupoexclusao,
                                                                                   o69_descr,
                                                                                   o69_librec,
                                                                                   o69_libsubfunc,
                                                                                   o69_libfunc,
                                                                                   o44_nivel as o69_nivel,
                                                                                   o44_nivelexclusao as o69_nivelexclusao,
                                                                                   o69_libnivel","o69_codseq","$dbwhere"));
if ($clorcparamseq->numrows > 0 )
 for ($x=0; $x< pg_numrows($record);$x++){
     db_fieldsmemory($record,$x);
     // Permissao de menu para alterar parametro de relatorio, modulo 209 (Contabilidade)
     $flag_permissao = db_permissaomenu(db_getsession("DB_anousu"),209,228050);

     if ($flag_permissao == "true"){
          $lb_texto = "Editar";
     } else {
          $lb_texto = "Visualizar";
     }
     
     ?>
      <tr style='height:1em'>
        <td width=5%  class='linhagrid'style='text-align:right'><b><?=$o69_codseq?></b></td>
        <td width=23% class='linhagrid'style='text-align:left'><b><? echo strtoupper($o69_descr) ?></b></td>
        <td width=10% valign=top class='linhagrid' style='text-align:left'>
           <div style="cellpadding:0px"><? db_ancora($lb_texto,"js_editar_elemento($c83_codrel,$o69_codseq,'$o69_grupo');",1); ?></div>
           <?
	     $sql ="select * 
                    from orcparamelemento
		        inner join conplano on c60_codcon = o44_codele and c60_anousu=o44_anousu
                    where 
		       	c60_anousu = ".db_getsession("DB_anousu")." and
                        o44_codparrel = $o69_codparamrel and
			o44_sequencia = $o69_codseq and o44_instit = ".db_getsession("DB_instit").
      " and ( o44_exclusao = 'f' or o44_exclusao is null )
	           ";
             $result=$clorcparamelemento->sql_record(analiseQueryPlanoOrcamento($sql));
             if ($clorcparamelemento->numrows >0) {
	           $size = $clorcparamelemento->numrows;
	 	   if ($size > 5){
                       $size=5;
	   	   }  
	           ?><select multiple size=<?=$size ?> style="font-size: 6pt;"> <?			
			for ($i = 0; $i < $clorcparamelemento->numrows; $i ++) {
			   db_fieldsmemory($result,$i);
                           ?><option value=<?=$o44_codele?> title="<?=$c60_descr ?> "><?=$c60_estrut?></option><?  
		   
                        }
		   ?></select><?
	     } 
           ?>                         
	</td>
	
	<td width=10% class='linhagrid' style='text-align:left'>&nbsp;
	   <? if (isset($o69_libnivel) && ($o69_libnivel=='t')) { ?>
	      	   <input type=text size =3  name=nivel  value="<?=$o69_nivel ?>" maxlength=2 onChange=js_updateNivel(<?=$c83_codrel ?>,<?=$o69_codseq?>,this.value); >             
	   <? } ?>              
	</td>

<!--        <td  width=12% valign=top class='linhagrid'>&nbsp;
	   <? 
/*     
     if (isset($o69_grupoexclusao) && $o69_grupoexclusao!='' && $o69_grupoexclusao!='0'){ ?>
	         <div><?  db_ancora($lb_texto,"js_editar_exclusao_elemento($c83_codrel,$o69_codseq,'$o69_grupoexclusao');",1); ?>  </div>
                 <?
	          $sql ="select * 
	                 from orcparamelemento
		            inner join conplano on c60_codcon = o44_codele and c60_anousu=o44_anousu
                         where 
		            c60_anousu = ".db_getsession("DB_anousu")." and
                            o44_codparrel = $o69_codparamrel and
			    o44_sequencia = $o69_codseq and
			    o44_exclusao='t'
	                ";
                  $result=$clorcparamelemento->sql_record($sql);
                  if ($clorcparamelemento->numrows >0) {
	             $size = $clorcparamelemento->numrows;
  	   	     if ($size > 5){
                         $size=5;
		     }  
	             ?> <select multiple size=<?=$size ?> style="font-size: 6pt;" > <?
	             for ($i=0;$i < $clorcparamelemento->numrows;$i++){
	  	        db_fieldsmemory($result,$i);
                        ?><option value=<?=$o44_codele?> title="<?=$c60_descr ?> "><?=$c60_estrut?></option>  <?  
		     }
		     ?></select><?
	          }              
	      } // end if
*/        
	  ?> 
	</td>	-->
	
<!--	<td width=12% class='linhagrid'>&nbsp; -->
	   <? 
//        if (isset($o69_libnivel) && ($o69_libnivel=='t')) { ?>
<!--   	   <input type=text size =3  name=nivelexclusao value="<?=$o69_nivelexclusao ?>" maxlength=2 onChange=js_updateNivelExclusao(<?=$c83_codrel ?>,<?=$o69_codseq?>,this.value); > -->
	   <? 
//        }
     ?>              
<!--	</td> -->

<!-- Funcao -->
        <td width=10% class='linhagrid' style='text-align:left'>&nbsp;
        <?
           if (isset($o69_libfunc) && $o69_libfunc!="" && $o69_libfunc=="t"){
        ?>
            <div> 
              <?
                 db_ancora($lb_texto,"js_editar_func($c83_codrel,$o69_codseq);",1);
              ?>
            </div>  
        <?
            $res_func = $clorcparamfunc->sql_record($clorcparamfunc->sql_query($o69_codseq,db_getsession("DB_anousu"),$c83_codrel,null)); 
            if ($clorcparamfunc->numrows > 0){
        ?>
	            <select multiple size=<?=$clorcparamfunc->numrows+1?> style="font-size: 6pt;">
        <?
                 for($i=0; $i < $clorcparamfunc->numrows; $i++){
		                  db_fieldsmemory($res_func,$i);
        ?>
                <option value=<?=$o45_func?> title="<?=$o52_descr?>"><?=$o45_func?></option>   
        <?
                 }
        ?>
              </select>
        <? 
            }
           }
        ?>
        </td>
        
<!-- SubFuncao -->
        <td width=10% class='linhagrid' style='text-align:left'>&nbsp;
	   <? if (isset($o69_libsubfunc) && $o69_libsubfunc!='' && $o69_libsubfunc=='t'){ ?>
                 <div><?  db_ancora($lb_texto,"js_editar_subfunc($c83_codrel,$o69_codseq);",1); ?>  </div>
   	         <?
	         $sql ="select * 
	                from orcparamsubfunc
		           inner join orcsubfuncao on o53_subfuncao = o44_subfunc
                        where 
		           o44_anousu = ".db_getsession("DB_anousu")." and
                           o44_codparrel = $o69_codparamrel and
			   o44_sequencia = $o69_codseq
	               ";
                 $result=$clorcparamsubfunc->sql_record($sql);
                 if ($clorcparamsubfunc->numrows >0) {
	            ?><select multiple size=<?=$clorcparamsubfunc->numrows+1 ?> style="font-size: 6pt;" > <?
	              for ($i=0;$i < $clorcparamsubfunc->numrows;$i++){
		         db_fieldsmemory($result,$i);
                         ?><option value=<?=$o44_subfunc?>  title="<?=$o53_descr?>"><?=$o44_subfunc ?></option>  <?  
		   
                      }
		      ?>
		      </select><?
	         } 
	      }	 
           ?>            
	</td>

<!-- Recursos -->
        <td width=10% class='linhagrid' style='text-align:left'>&nbsp;
	   <? if (isset($o69_librec) && $o69_librec!='' && $o69_librec=='t'){ ?> 
                <div><?  db_ancora($lb_texto,"js_editar_recurso($c83_codrel,$o69_codseq);",1); ?>  </div>
	         <?
	          $sql ="select o44_codrec,
	                        o15_descr as title_descr,
	                         substr(o15_descr,1,10) as o15_descr 
	                 from orcparamrecurso
		             inner join orctiporec on o15_codigo = o44_codrec
                         where 
		            o44_anousu = ".db_getsession("DB_anousu")." and
                            o44_codparrel = $o69_codparamrel and
		 	    o44_sequencia = $o69_codseq
	                ";
                  $result=$clorcparamrecurso->sql_record($sql);
                  if ($clorcparamrecurso->numrows >0) {
	             ?> <select multiple size=<?=$clorcparamrecurso->numrows+1 ?> style="font-size: 6pt;" > <?
	               for ($i=0;$i < $clorcparamrecurso->numrows;$i++){
		          db_fieldsmemory($result,$i);
                          ?><option value=<?=$o44_codrec?> title="<?=$title_descr ?>"><?=$o44_codrec?></option>  <?  
		   
                       }
		     ?> </select><?
	          }            
	     } 
	   ?>	   
	   </td>  
       <td width=10% class='linhagrid' style='text-align:left' valign="top">
        <div>
          
	   <?
	   
  	     $oLinhaRelatorio = new linhaRelatorioContabil($c83_codrel, $o69_codseq);
  	     $aColunas        = $oLinhaRelatorio->getCols(null);
  	     
  	     if (count($aColunas) > 0) {
   	       
  	       db_ancora("Edição Manual","js_editar_colunas($c83_codrel, $o69_codseq, $iAnoPesquisa);return false;",1);
  	       $avalores  = $oLinhaRelatorio->getValoresColunas(null, null, null, $iAnoPesquisa);
  	       if (count($avalores) > 0) {
  	          echo "<img src='imagens/action_ok.png' border='0'>"; 
  	       }
  	       
  	     }
 	    ?> &nbsp;</div>
       </td>   
      </tr>      
     <?
 }
?>
<tr style="height: auto;"><td colspan="9">&nbsp;</td></tr>
</tbody>
</table>
</div>


</td>
</tr>
</table> 

</form>
</body>
<html>
<script>
function js_editar_elemento(codrel,linha,grupo_de_contas){
    parent.iframe_parametro.location.href = 'func_seleciona_plano.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&grupo='+grupo_de_contas+'&flag_permissao=<?=$flag_permissao?>';      
}  
function js_editar_exclusao_elemento(codrel,linha,grupo_de_contas){
    parent.iframe_parametro.location.href = 'func_seleciona_exclusao_plano.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&grupo='+grupo_de_contas+'&flag_permissao=<?=$flag_permissao?>';
}  
function js_editar_recurso(codrel,linha){
    parent.iframe_parametro.location.href = 'func_seleciona_recursos.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&flag_permissao=<?=$flag_permissao?>';
}  
function js_editar_subfunc(codrel,linha){
    parent.iframe_parametro.location.href = 'func_seleciona_subfunc.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&flag_permissao=<?=$flag_permissao?>';
}  
function js_editar_func(codrel,linha){
    parent.iframe_parametro.location.href = 'func_seleciona_func.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&flag_permissao=<?=$flag_permissao?>';
}  
function js_refresh(){
   atualiza_hp();    
}
function atualiza_hp(){
   document.form1.submit();
}

<? sajax_show_javascript();   /* imprime a função do sajax */ ?>
function js_updateNivel(relatorio,linha,valor){	 
	 x_atualiza_nivel(relatorio,linha,valor,mensagem);	 
}
function js_updateNivelExclusao(relatorio,linha,valor){	 
	 x_atualiza_nivel_exclusao(relatorio,linha,valor,mensagem);	 
}
function mensagem(retorno){	
	if (retorno.substr(0,1)!='0') {
	  alert(retorno);
   }
}	

function js_editar_colunas(iCodRel, iLinha, iAnoPesquisa) {
    
    var iPeriodo = top.corpo.iframe_relatorio.document.getElementById('o116_periodo').value;
    if (iPeriodo == '0') {
    
      alert('Para Configurar os valores das colunas, escolha um periodo');
      return false;
      
    }
    
    var url  = 'con4_orcrelatorioscolunas.php?iCodRel='+iCodRel+'&iLinha='+iLinha+'&iPeriodo='+iPeriodo;
        url += '&iAnoPesquisa='+iAnoPesquisa;
    js_OpenJanelaIframe('',
                       'db_iframe_colunas',
                        url ,
                       'Valores para as Colunas',
                       true,
                       0);
}	
</script>