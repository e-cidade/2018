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

//MODULO: orcamento

$clrotulo = new rotulocampo;
$clrotulo->label("o70_anousu");
$clrotulo->label("o70_anousu");
$clrotulo->label("o70_anousu");
$clrotulo->label("o70_anousu");
$clrotulo->label("o47_codsup");
$clrotulo->label("o85_valor");

if (isset($o85_codrec) and !$o85_codrec== "")  {
        $campos = "orcreceita.o70_codrec,
	           fc_estruturalreceita(".db_getsession("DB_anousu").",orcreceita.o70_codrec) as o50_estrutreceita,
		   o57_descr,
		   orcreceita.o70_codigo,
		   o15_descr,
		   orcreceita.o70_valor";
        $res=$clorcreceita->sql_record($clorcreceita->sql_query(db_getsession("DB_anousu"),$o85_codrec,$campos));
	// echo "$clorcreceita->erro_banco";
        if ($clorcreceita->numrows >0 )  {
	    db_fieldsmemory($res,0,true);
	    $o06_sequencial = '';
        }
}	
?>
<script>
// --------------------
function valida_dados(){

   var op= document.createElement("input");
       op.setAttribute("type","hidden");
       op.setAttribute("name","<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>");
       op.setAttribute("value","");
       document.form1.appendChild(op);  
       document.form1.submit();

}

</script>
<form name="form1" method="post" action="">
<center>
<table border=0 >
<tr>
<td valign=top>
 <fieldset><legend><b>Receitas</b></legend>
 <table>
   <tr>
     <td nowrap title="<?=@$To85_codrec?>"> <? db_ancora(@$Lo85_codrec,"js_pesquisao85_codrec();",$op); ?> </td>
     <td><? db_input('o85_codrec',8,$Io85_codrec,true,'text',$op,""); ?> </td>
     <td><input type="submit" name="pesquisa_rec"  value="pesquisar"> </td>
  </tr>
  <?
   if ($oDadosProjeto->o138_sequencial != "") {
   ?>
   <tr>
     <td nowrap title="<?=@$To85_codrec?>"> <? db_ancora("<b>Projeção Receita:</b>","js_pesquisa_estimativa(true);",$op); ?> </td>
     <td><? db_input('o06_sequencial',8,$Io85_codrec,true,'text',3,""); ?> </td>
  </tr>  
   <?
   }
   ?>
  <tr>
     <td nowrap >Estrutural : </td>
     <td><? db_input('o50_estrutreceita',26,"",true,'text',3,"");  ?> </td>
     <td colspan=1><? db_input('o57_descr',50,"",true,'text',3,"");  ?> </td>     
  </tr>	
  <tr>
     <td nowrap> Recurso: </td>
     <td><? db_input('o70_codigo',8,"",true,'text',3,"");  ?> </td>
     <td colspan=1><? db_input('o15_descr',50,"",true,'text',3,"");  ?> </td>     
  </tr>
  
  <tr>
    <td><b>Valor do Lançamento</b></td>
    <td><? db_input('o85_valor',10,$Io85_valor,true,'text',1,'','','','text-align:right');  ?> </td>

    <td><input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="button" id="db_opcao" 
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?>" onclick='valida_dados();'" >
     </td>

  </tr>
 </table>
 </legend>

</td>
<!-- segunda coluna -->
<td valign=top>
 <fieldset><legend><b>Projeto</b></legend>
 <table width=200px>
   <tr><td><b>Projeto</b></td><td><? db_input("o39_codproj",6,'',true,'text',3); ?></td></tr>
   <tr><td><b>Suplementação</b> </td><td><? db_input("o46_codsup",6,'',true,'text',3); ?></td></tr>
 </table>
 </fieldset>

 <fieldset><legend><b>Saldos</b></legend>
 <table width=200px>
    <tr><td><b>Total Receitas</b></td><td><? db_input("soma_receitas",10,'',true,'text',3,'','','','text-align:right'); ?></td></tr>

 </table>
 </fieldset>

</td>
</tr>
</table>

<center>
        

<? 
  $sSqlReceitas = $clorcsuplemrec->sql_query_file(null,null,
                   "o85_codrec,o85_anousu,o85_valor,fc_estruturalreceita(o85_anousu::integer,o85_codrec::integer) as o50_estrutreceita, 1 as tipo",
       null,"o85_codsup =$o46_codsup");
  $sSqlReceitas .= " union all ";
  $sSqlReceitas .= " select o137_sequencial, ";
  $sSqlReceitas .= "        o06_anousu, ";
  $sSqlReceitas .= "        o137_valor,";
  $sSqlReceitas .= "        fc_estruturalreceitappa(o06_anousu::integer,o06_sequencial, c61_instit) as o50_estrutreceita,";     
  $sSqlReceitas .= "        2 as tipo";     
  $sSqlReceitas .= "  from  orcsuplemreceitappa";     
  $sSqlReceitas .= "        inner join ppaestimativareceita on o06_sequencial = o137_ppaestimativareceita";     
  $sSqlReceitas .= "        inner join conplanoreduz       on o06_codrec     = c61_codcon ";     
  $sSqlReceitas .= "                                      and o06_anousu     = c61_anousu";     
  $sSqlReceitas .= "  where  c61_instit = ".db_getsession("DB_instit");     
  $sSqlReceitas .= "    and  o137_orcsuplem = {$o46_codsup} ";
  $chavepri= array("o85_anousu"=>$anousu,"o85_codrec"=>@$o85_codrec, "tipo"=>@$tipo);
  $cliframe_alterar_excluir->chavepri=$chavepri;       
  $cliframe_alterar_excluir->sql = $sSqlReceitas;
  $cliframe_alterar_excluir->campos ="o85_codrec, o85_anousu, o50_estrutreceita ,o85_valor, tipo";
  $cliframe_alterar_excluir->legenda="lista";
  $cliframe_alterar_excluir->iframe_height ="200";
  $cliframe_alterar_excluir->opcoes = 3;
  $cliframe_alterar_excluir->iframe_alterar_excluir(1);

?>

</form>
<script>
function js_pesquisao85_codrec(){
   js_OpenJanelaIframe('','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostracodrec|o70_codrec','Pesquisa',true,0);
}
function js_mostracodrec(chave1) {
  
  if ($('o06_sequencial')) {
    $('o06_sequencial').value   = '';
  }
  db_iframe_orcreceita.hide();
  document.form1.o85_codrec.value=chave1;
  document.form1.pesquisa_rec.click();
}

function js_pesquisa_estimativa(mostra) {

  if (mostra) {
    js_OpenJanelaIframe('',
                        'db_iframe_ppaestimativareceita',
                        'func_ppareceitasuplementacao.php?funcao_js=parent.js_mostraestimativa1|o06_sequencial',
                        'Pesquisar Estimativas de Receita',true,0);
  }
}
function js_mostraestimativa1(chave1) {
  
  document.form1.o06_sequencial.value = chave1;
  db_iframe_ppaestimativareceita.hide();
  getDadosReceitaPPA(chave1);
}
function getDadosReceitaPPA(iReceita) {

  var oParam         = new Object();
  oParam.iEstimativa = iReceita;
  oParam.exec        = 'getDadosReceitaPPA';
  js_divCarregando('Aguarde, pesquisando dados...', 'msgBox');
  var oAjax          = new Ajax.Request(
                                        'orc4_suplementacoes.RPC.php',
                                        {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParam),
                                        onComplete: js_retornoGetDadosReceita
                                        } 
                                       );
}

function js_retornoGetDadosReceita(oAjax) {
  
  js_removeObj('msgBox');
  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    $('o85_codrec').value   = '';
    $('o50_estrutreceita').value    = oRetorno.dadosreceita.estrutural.urlDecode();
    $('o57_descr').value    = oRetorno.dadosreceita.o57_descr.urlDecode();
    $('o70_codigo').value   = oRetorno.dadosreceita.c61_codigo;
    $('o15_descr').value   = oRetorno.dadosreceita.o15_descr;
    
  } else {
  
    alert('Estimativa informada não possui estrutura válida.')
    $('o07_sequencial').value = '';
  }
}
</script>