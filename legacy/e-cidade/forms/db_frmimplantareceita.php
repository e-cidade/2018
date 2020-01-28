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

//MODULO: contabilidade
$clorcreceita->rotulo->label();
$clorcreceitaval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o70_codrec");
$clrotulo->label("o70_codrec");


$rr =$cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit")));
if ($cldb_config->numrows > 0 ){
   db_fieldsmemory($rr,0);
}  
$dt = split('-',$dtcont);
$dtcont_dia = $dt[2];
$dtcont_mes = $dt[1];
$dtcont_ano = $dt[0];

// -- config
if (!isset($semestre))
  $semestre=1;



?>
<br>
<form name="form1" method="post" action="">

<table border=1 align=left>
<tr>

     <td><b>Data da Contabilidade </b></td>
     <td><b><? db_inputdata('dtcont',$dtcont_dia,$dtcont_mes,$dtcont_ano,false,'text',3) ?></b></td>          
     
     <td><b>Periodo</b></td>
     <td><?
            $matriz = array('1'=>"Primeiro Semestre",2=>"Segundo Semestre");
            db_select ("semestre",$matriz,false,1);  
	    
         ?>
     </td>          
</tr>

</table>  
  <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
  <input name="processar" type="submit" value="Processar Lançamentos">

<br>

<center>
<table cellpadding="2" cellspacing="0" style="border:1px solid;position:absolute;left:05px;top:70px" width=100% border=1 align=left>
  <thead style="background-color:#c0c0c0;height:15px">
    <tr>
      <th style="border-bottom:1px solid;">ESTRUTURAL</th>
      <th style="border-bottom:1px solid;">DESCRIÇÃO</th>      
      <? if ($semestre==1) { ?>
            <th style="border-bottom:1px solid;">PREVISÃO</th>
      		<th style="border-bottom:1px solid;">JANEIRO</th>
      		<th style="border-bottom:1px solid;">FEVEREIRO</th>
      		<th style="border-bottom:1px solid;">MARÇO</th>
      		<th style="border-bottom:1px solid;">ABRIL</th>
      		<th style="border-bottom:1px solid;">MAIO</th>
      		<th style="border-bottom:1px solid;">JUNHO</th>
      <?  }else {?>
      		<th style="border-bottom:1px solid;">JULHO</th>
      		<th style="border-bottom:1px solid;">AGOSTO</th>
      		<th style="border-bottom:1px solid;">SETEMBRO</th>
      		<th style="border-bottom:1px solid;">OUTUBRO</th>
      		<th style="border-bottom:1px solid;">NOVEMBRO</th>
      		<th style="border-bottom:1px solid;">DEZEMBRO</th>      	
      <?  }?>
      <td nowrap width=45px>&nbsp;</td>
    </tr>
  </thead>
  
  <tfoot style="background-color:#c0c0c0;height:15px">
    <tr>
       <th style="border-bottom:1px solid;">ESTRUTURAL</th>
       <th style="border-bottom:1px solid;">DESCRIÇÃO</th>             
       <? if ($semestre==1) { ?>
       	    <th style="border-bottom:1px solid;">PREVISÃO</th>
      		<th style="border-bottom:1px solid;">JANEIRO</th>
      		<th style="border-bottom:1px solid;">FEVEREIRO</th>
      		<th style="border-bottom:1px solid;">MARÇO</th>
      		<th style="border-bottom:1px solid;">ABRIL</th>
      		<th style="border-bottom:1px solid;">MAIO</th>
      		<th style="border-bottom:1px solid;">JUNHO</th>
      <?  }else {?>
      		<th style="border-bottom:1px solid;">JULHO</th>
      		<th style="border-bottom:1px solid;">AGOSTO</th>
      		<th style="border-bottom:1px solid;">SETEMBRO</th>
      		<th style="border-bottom:1px solid;">OUTUBRO</th>
      		<th style="border-bottom:1px solid;">NOVEMBRO</th>
      		<th style="border-bottom:1px solid;">DEZEMBRO</th>      	
      <?  }?>
      <td nowrap width=45px>&nbsp;</td>
       
           
    </tr>
  </tfoot>
  
  <tbody style="max-height:600px;overflow:scroll;">
  <?   

   if (db_getsession("DB_anousu") >= $dtcont_ano ){
     
	 echo "<tr><td colspan=8 height=150px  align=center><b><font size=+1>Não é permitido alterar dados nesse exercicio !</b><td></tr>";
	 
        // db_redireciona("db_erros?db_erro='Não é permitido alterações de dados nesse exercicio !';  "); 
   } else {
     if (isset($pesquisar)){
      $res=$clorcreceita->sql_record($clorcreceita->sql_query_migra(db_getsession("DB_anousu"),db_getsession("DB_instit")));
      if ($clorcreceita->numrows >0){
      for ($linha=0;$linha < $clorcreceita->numrows;$linha++){
   	    db_fieldsmemory($res,$linha);
   	    ?> 	  
   	    <tr>
            <!-- <td><?=$o70_codrec ?></td>-->
            <td><?=$o57_fonte ?></td>
            <td nowrap><?=substr($o57_descr,0,70) ?></td>
            <? if ($semestre==1) { ?>
                      <td><? db_input("o70_valor",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,0,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("jan",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,1,this.value);","","","text-align:right") ?></td>            
                      <td><? db_input("fev",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,2,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("mar",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,3,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("abr",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,4,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("mai",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,5,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("jun",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,6,this.value);","","","text-align:right")    ?></td>
            <?  } else { ?>
                      <td><? db_input("jul",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,7,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("ago",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,8,this.value);","","","text-align:right")    ?></td>
                      <td><? db_input("set",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,9,this.value);","","","text-align:right")    ?></td>
	              <td><? db_input("out",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,10,this.value);","","","text-align:right")    ?></td>
         	      <td><? db_input("nov",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,11,this.value);","","","text-align:right")    ?></td>
               	      <td><? db_input("dez",12,4,false,'text',1,"onchange=js_atuOrcreceitaval($o70_codrec,12,this.value);","","","text-align:right")    ?></td>
            <? } ?>
            <td nowrap width=45px>&nbsp;&nbsp;</td>
          </tr>   	  	  
   	  <?
   	  }	   	
      }
     }
   }
  ?>
    
  </tbody>
</table>
 
</form>
<script>

function js_recarregar(semestre){
	 location.href='con4_implantarec001.php?semestre='+semestre;
}


function js_mostraconta(chave1,chave2){
    document.form1.c62_reduz.value = chave1;
    document.form1.c60_descr.value = chave2;
    db_iframe_conplanoreduz.hide();

}  

function js_pesquisao71_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_codrec|o70_codrec|o57_descr','Pesquisa',true);
   }else{
       if(document.form1.o71_codrec.value != ''){ 
           js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o70_codrec.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
       }else{
           document.form1.o57_descr.value = ''; 
       }
   }
}
function js_codrec(chave1,chave2){
 //   document.form1.o71_codrec.value = chave1;
 //   document.form1.o57_descr.value = chave2;
    db_iframe_orcreceita.hide();
    <? echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?o71_codrec='+chave1+'&o57_descr='+chave2"; ?>
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_preenchepesquisa|o70_codrec|o57_descr','Pesquisa',true);
 //  js_OpenJanelaIframe('top.corpo','db_iframe_conplanoexe','func_conplanoexe.php?funcao_js=parent.js_preenchepesquisa|c61_reduz','Pesquisa',true);

}
function js_preenchepesquisa(chave){
    db_iframe_conplanoexe.hide();
    <?
    if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
}
</script>