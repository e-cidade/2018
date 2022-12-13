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
$cldb_versaocpd->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db30_codversao");
$clrotulo->label("db30_codrelease");
      
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
     $db33_codver = "";
     $db33_obs = "";
     $db33_obscpd = "";
     $db33_data = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?

db_input('db33_codcpd',6,$Idb33_codcpd,true,'hidden',3,"")
?>
  <tr>
    <td nowrap title="<?=@$Tdb30_codversao?>">
       <?=@$Ldb30_codversao?>
    </td>
    <td> 
<?
db_input('db30_codversao',6,$Idb30_codversao,true,'text',3);
?>/
       <?
db_input('db30_codrelease',6,$Idb30_codrelease,true,'text',3);
       if(@$db33_codcpd > 0){
       ?>
       
       <td rowspan="19" valign="top">
          <table border="0" cellpadding="0" cellspacing="0">
             <tr><td align="center"><b>Anexos</b></td></tr>
	     <?
	     $result = $cldb_versaocpdarq->sql_record($cldb_versaocpdarq->sql_query_file(null,"*","","db34_codcpd=".@$db33_codcpd));
	     ?>
             <tr>
                 <td align="center">
		  <select name='selid' onchange='js_novo(<?=$db33_codcpd?>,1,0)'  size=3>
                   <?for($x=0;$x<$cldb_versaocpdarq->numrows;$x++){
		     db_fieldsmemory($result,$x);
		   ?> 		   
                    <option value='<?=$db34_codarq?>'><?=$db34_descr?></option>
                   <?
	            }
		   ?>
		 </td>
	      </tr>
              <tr>
                 <td align="center" >
                   <br><br>
		 
                   <input type="button" name="novo" value="Manutenção" onclick="js_novo('<?=$db33_codcpd?>',1,0);">
                 </td>
              </tr>
	   </table>
	 </td>
	<?
	}
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb33_obs?>">
       <?=@$Ldb33_obs?>
       
									
    </td>
    <td> 
<?
db_textarea('db33_obs',4,70,$Idb33_obs,true,'text',$db_opcao,"")
?>

    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb33_obscpd?>">
       <?=@$Ldb33_obscpd?>
    </td>
    <td> 
<?
db_textarea('db33_obscpd',4,70,$Idb33_obscpd,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb33_data?>">
       <?=@$Ldb33_data?>
    </td>
    <td> 
<?
if(!isset($db33_data_dia)){
   $db33_data_dia     = date('d',db_getsession("DB_datausu") );
   $db33_data_mes  = date('m',db_getsession("DB_datausu") );
   $db33_data_ano   = date('Y',db_getsession("DB_datausu") );
}
db_inputdata('db33_data',@$db33_data_dia,@$db33_data_mes,@$db33_data_ano,true,'text',$db_opcao,"")
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
	 $chavepri= array("db33_codcpd"=>@$db33_codcpd);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $db33_codver=$db30_codver;
	 $cliframe_alterar_excluir->sql     = $cldb_versaocpd->sql_query(null,"*","","db33_codver=$db30_codver");
	 $cliframe_alterar_excluir->campos  ="db30_codversao,db30_codrelease,db33_obs,db33_obscpd,db33_data";
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
function js_pesquisadb33_codver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_versaocpd','db_iframe_db_versao','func_db_versao.php?funcao_js=parent.js_mostradb_versao1|db30_codver|db30_codversao','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.db33_codver.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_versaocpd','db_iframe_db_versao','func_db_versao.php?pesquisa_chave='+document.form1.db33_codver.value+'&funcao_js=parent.js_mostradb_versao','Pesquisa',false);
     }else{
       document.form1.db30_codversao.value = ''; 
     }
  }
}
function js_mostradb_versao(chave,erro){
  document.form1.db30_codversao.value = chave; 
  if(erro==true){ 
    document.form1.db33_codver.focus(); 
    document.form1.db33_codver.value = ''; 
  }
}
function js_mostradb_versao1(chave1,chave2){
  document.form1.db33_codver.value = chave1;
  document.form1.db30_codversao.value = chave2;
  db_iframe_db_versao.hide();
}
function js_novo(codcpd,db_opcao,codarq){
  if(db_opcao == 1){
    js_OpenJanelaIframe('top.corpo.iframe_db_versaocpd','db_iframe_novo','con1_db_versaocpdarq001.php?db_opcao='+db_opcao+'&codcpd='+codcpd+'&codarq='+codarq,'ANEXOS',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_db_versaocpd','db_iframe_novo','con1_db_versaocpdarq002.php?db_opcao='+db_opcao+'&codcpd='+codcpd+'&codarq='+codarq,'ANEXOS',true,0);
  }
  
}    

</script>