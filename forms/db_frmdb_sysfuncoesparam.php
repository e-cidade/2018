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

//MODULO: configuracoes
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_sysfuncoesparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomefuncao");
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
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
//     $db42_funcao = "";
     $db42_ordem = "";
     $db42_nome = "";
     $db42_tipo = "";
     $db42_tamanho = "";
     $db42_precisao = "";
     $db42_valor_default = "";
     $db42_descricao = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb42_sysfuncoesparam?>">
       <?=@$Ldb42_sysfuncoesparam?>
    </td>
    <td> 
<?
db_input('db42_sysfuncoesparam',10,$Idb42_sysfuncoesparam,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_funcao?>">
       <?
       db_ancora(@$Ldb42_funcao,"js_pesquisadb42_funcao(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('db42_funcao',5,$Idb42_funcao,true,'text',3," onchange='js_pesquisadb42_funcao(false);'")
?>
       <?
db_input('nomefuncao',50,$Inomefuncao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_ordem?>">
       <?=@$Ldb42_ordem?>
    </td>
    <td> 
<?
db_input('db42_ordem',5,$Idb42_ordem,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_nome?>">
       <?=@$Ldb42_nome?>
    </td>
    <td> 
<?
db_input('db42_nome',20,$Idb42_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
<?

 $sql = " select pg_type.typname,
                 case
                    when pg_class.relkind is null then 'primitivo'
                    else 'composto'
                 end as tipo
            from pg_type
                 left join pg_class on pg_class.oid = pg_type.typrelid
            where pg_type.typname !~ '^pg_'
              and pg_type.typname !~ '^_'
              and (pg_class.relkind is null 
               or pg_class.relkind = 'c')
            order by tipo, 
                  typname ";
  $result    = pg_query($sql);
  $numrows   = pg_numrows($result);
  $elementos = array(" Selecione o tipo do parametro ");
  for($i=0;$i<$numrows;$i++){
     db_fieldsmemory($result,$i);
     $elementos[$typname] = "Tipo ".trim($tipo)." - ".trim($typname);
   }
?>

  <tr>
    <td nowrap title="<?=@$Tdb42_tipo?>">
       <?=@$Ldb42_tipo?>
    </td>
    <td> 
<?
//db_input('db42_tipo',20,$Idb42_tipo,true,'text',$db_opcao,"")
db_select('db42_tipo',$elementos,'',$db_opcao,"","","");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_tamanho?>">
       <?=@$Ldb42_tamanho?>
    </td>
    <td> 
<?
db_input('db42_tamanho',10,$Idb42_tamanho,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_precisao?>">
       <?=@$Ldb42_precisao?>
    </td>
    <td> 
<?
db_input('db42_precisao',10,$Idb42_precisao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_valor_default?>">
       <?=@$Ldb42_valor_default?>
    </td>
    <td> 
<?
db_textarea('db42_valor_default',1,50,$Idb42_valor_default,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb42_descricao?>">
       <?=@$Ldb42_descricao?>
    </td>
    <td> 
<?
db_textarea('db42_descricao',5,50,$Idb42_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("db42_sysfuncoesparam"=>@$db42_sysfuncoesparam);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
//   echo $cldb_sysfuncoesparam->sql_query_file(null,"*",null," db42_funcao = $db42_funcao " );
	 $cliframe_alterar_excluir->sql     = $cldb_sysfuncoesparam->sql_query_file(null,"*",null," db42_funcao = $db42_funcao " );
	 $cliframe_alterar_excluir->campos  ="db42_sysfuncoesparam,db42_funcao,db42_ordem,db42_nome,db42_tipo,db42_tamanho,db42_precisao,db42_valor_default,db42_descricao";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisadb42_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_sysfuncoesparam','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_mostradb_sysfuncoes1|codfuncao|nomefuncao','Pesquisa',true,'0');
  }else{
     if(document.form1.db42_funcao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_sysfuncoesparam','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?pesquisa_chave='+document.form1.db42_funcao.value+'&funcao_js=parent.js_mostradb_sysfuncoes','Pesquisa',false);
     }else{
       document.form1.nomefuncao.value = ''; 
     }
  }
}
function js_mostradb_sysfuncoes(chave,erro){
  document.form1.nomefuncao.value = chave; 
  if(erro==true){ 
    document.form1.db42_funcao.focus(); 
    document.form1.db42_funcao.value = ''; 
  }
}
function js_mostradb_sysfuncoes1(chave1,chave2){
  document.form1.db42_funcao.value = chave1;
  document.form1.nomefuncao.value = chave2;
  db_iframe_db_sysfuncoes.hide();
}
</script>