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

//MODULO: caixa
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcaitransflanc->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("k13_descr");
$clrotulo->label("k13_instit");

$clrotulo->label("k02_descr");

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
     $k93_sequen     = "";
     $k93_instit     = "";
     $k93_debito     = "";
     $k93_credito    = "";
     $k93_finalidade = "";
     $k13_descr      = "";
     $k02_descr      = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk93_transf?>"> <?=@$Lk93_transf?> </td>
    <td>
    <? 
    	db_input('k93_transf',10,$Ik93_transf,true,'text',3,"");
        db_input('k93_sequen',10,$Ik93_sequen,true,'hidden',3,"");
    ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk93_instit?>"><?=@$Lk93_instit?></td>
    <td><? 
         // seleciona as instituições que estejam na caitransf e caitransflanc
         $res = pg_query("select codigo,nomeinst
 	                  from db_config
    	                  where codigo in  (
  	                    select k91_instit
                   	    from caitransf where k91_transf    = $k93_transf
	                    union
	                    select k92_instit
	                    from caitransfdest where k92_transf= $k93_transf
	                  ) order by codigo desc ");
	 $db_matriz = array();
	 if (pg_numrows($res)>0){
            for ($x=0;$x<pg_numrows($res);$x++){
                 db_fieldsmemory($res,$x);
		 $db_matriz[$codigo]=$nomeinst;
	    }  
	 }  
	 db_select('k93_instit',$db_matriz,'true',$db_opcao,' onchange="js_limpa_instit();" ');


	 
	?>    
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tk93_debito?>"><? db_ancora(@$Lk93_debito,"js_pesquisak93_debito(true);",$db_opcao);?></td>
    <td><? db_input('k93_debito',8,$Ik93_debito,true,'text',$db_opcao," onchange='js_pesquisak93_debito(false);'") ?> 
        <? db_input('k13_descr',40,$Ik13_descr,true,'text',3,'') ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk93_credito?>"><? db_ancora(@$Lk93_credito,"js_pesquisak93_credito(true);",$db_opcao);?> </td>
    <td><? db_input('k93_credito',8,$Ik93_credito,true,'text',$db_opcao," onchange='js_pesquisak93_credito(false);'") ?>
        <? db_input('k02_descr',40,$Ik02_descr,true,'text',3,'') ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk93_finalidade?>"><?=@$Lk93_finalidade?></td>
    <td><?db_textarea('k93_finalidade',2,48,$Ik93_finalidade,true,'text',$db_opcao,"")?></td>
  </tr>  
  <tr>
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
     $where = "";
     if($db_opcao==1||$db_opcao==11) {
	     $chavepri = array("k93_sequen"=>@$k93_sequen,"k93_transf"=>@$k93_transf);
     } else {
	     $chavepri = array("k93_transf"=>@$k93_transf);
		 $where = "k93_sequen <> $k93_sequen and ";
     }
     $cliframe_alterar_excluir->chavepri=$chavepri;
     $cliframe_alterar_excluir->sql     = $clcaitransflanc->sql_query(null,
                                            "k93_sequen,k93_transf,k93_instit,
				             k93_debito,k93_credito,k93_finalidade  ",
				            "k93_sequen",
				            $where." k93_transf=$k93_transf");
     $cliframe_alterar_excluir->campos  ="k93_sequen,k93_instit,k93_debito,k93_credito,k93_finalidade";
     $cliframe_alterar_excluir->legenda="LANÇAMENTOS";
     $cliframe_alterar_excluir->iframe_height ="200";
     $cliframe_alterar_excluir->iframe_width ="820";
     $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>

function js_limpa_instit(){
  obj=document.form1;
  obj.k93_debito.value='';
  obj.k93_credito.value='';
  obj.k13_descr.value='';
  obj.k02_descr.value='';
}  


function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisak93_debito(mostra){
  db_instit = document.form1.k93_instit.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_lanc','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&funcao_js=parent.js_mostrasaltes1|c62_reduz|c60_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k93_debito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_lanc','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&pesquisa_chave='+document.form1.k93_debito.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.k93_debito.focus(); 
    document.form1.k93_debito.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k93_debito.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
function js_pesquisak93_credito(mostra){
  db_instit = document.form1.k93_instit.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_lanc','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&funcao_js=parent.js_mostratabrec1|c62_reduz|c60_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k93_credito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_lanc','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&pesquisa_chave='+document.form1.k93_credito.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);

     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.k93_credito.focus(); 
    document.form1.k93_credito.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.k93_credito.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
</script>