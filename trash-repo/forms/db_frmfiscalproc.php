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

//MODULO: fiscal
$clfiscalproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("y41_descr");
$clrotulo->label("db03_descr");
$clrotulo->label("y27_descr");
$clrotulo->label("y61_codpa");
$clrotulo->label("y61_codtipo");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty29_codtipo?>"><?=@$Ly29_codtipo?></td>
    <td><? db_input('y29_codtipo',20,$Iy29_codtipo,true,'text',3,"") ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty29_dias?>"><?=@$Ly29_dias?></td>
    <td><? db_input('y29_dias',10,$Iy29_dias,true,'text',$db_opcao,"") ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty29_descr?>"><?=@$Ly29_descr?></td>
    <td><? db_input('y29_descr',50,$Iy29_descr,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty29_coddepto?>"><? db_ancora(@$Ly29_coddepto,"js_pesquisay29_coddepto(true);",3); ?>
    </td>
    <td> 
    <?
     db_input('y29_coddepto',5,$Iy29_coddepto,true,'text',3," onchange='js_pesquisay29_coddepto(false);'");
     if($db_opcao == 1){
       echo "<script>document.form1.y29_coddepto.value='".db_getsession('DB_coddepto')."';</script>";
       echo "<script>js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave=".db_getsession('DB_coddepto')."&funcao_js=parent.js_mostradb_depart','Pesquisa',false);</script>";
     }
    ?>
    <? db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')   ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty29_descr_obs?>"><?=@$Ly29_descr_obs?></td>
    <td><? db_textarea('y29_descr_obs',15,100,$Iy29_descr_obs,true,'text',$db_opcao,"") ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty29_tipoandam?>">
      <? db_ancora(@$Ly29_tipoandam,"js_pesquisay29_tipoandam(true);",$db_opcao); ?>
      </td>
    <td> 
    <? db_input('y29_tipoandam',20,$Iy29_tipoandam,true,'text',$db_opcao," onchange='js_pesquisay29_tipoandam(false);'") ?>
    <? db_input('y41_descr',50,$Iy41_descr,true,'text',3,'')  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty29_tipofisc?>">
     <? db_ancora(@$Ly29_tipofisc,"js_pesquisa_tipofisc(true);",$db_opcao);  ?>
    </td>
    <td> 
     <? db_input('y29_tipofisc',20,$Iy29_tipofisc,true,'text',$db_opcao," onchange='js_pesquisa_tipofisc(false);'") ?>
     <? db_input('y27_descr',50,$Iy27_descr,true,'text',3,'')     ?>
    </td>
  </tr>
  <tr>
   <td nowrap>
       <b>Tipo de Procedimento : </b>
    </td>
    <td nowrap>
       <?
         $tipo = array("N"=>"Notifica��o","A"=>"Auto");
	 db_select("y29_tipoproced",$tipo,true,$db_opcao); 
        ?>
     </td>
   </tr>
   <!-- quando essa notifica��o gerar um auto, indica aqui qual o codigo do auto que ser� gerado
      -->
   <tr>
    <td nowrap title="<?=@$Ty61_codpa ?>">
     <? db_ancora(@$Ly61_codpa,"js_pesquisa_codpa(true);",$db_opcao);  ?>
    </td>
    <td> 
     <?
        if (isset($y61_codtipo) && $y61_codtipo !=""){
          $res = $clfiscalproc->sql_record($clfiscalproc->sql_query($y61_codtipo)); 
	  if ($clfiscalproc->numrows > 0 ){
            db_fieldsmemory($result,0);
            $descr_pa = $y29_descr;   
	  }  
	}  
        db_input('y61_codtipo',20,$Iy61_codpa,true,'text',$db_opcao,""); 
        db_input('descr_pa',50,'',true,'text',3,'');
	
       ?>
    </td>
   </tr>
 
    
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay29_coddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.y29_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.y29_coddepto.focus(); 
    document.form1.y29_coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.y29_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisay29_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_docum','func_db_documento.php?funcao_js=parent.js_mostradb_docum1|db03_docum|db03_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_docum','func_db_documento.php?pesquisa_chave='+document.form1.y29_docum.value+'&funcao_js=parent.js_mostradb_docum','Pesquisa',false);
  }
}
function js_mostradb_docum(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.y29_docum.focus(); 
    document.form1.y29_docum.value = ''; 
  }
}
function js_mostradb_docum1(chave1,chave2){
  document.form1.y29_docum.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_docum.hide();
}
function js_pesquisay29_tipoandam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipoandam','func_tipoandam.php?funcao_js=parent.js_mostratipoandam1|y41_codtipo|y41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipoandam','func_tipoandam.php?pesquisa_chave='+document.form1.y29_tipoandam.value+'&funcao_js=parent.js_mostratipoandam','Pesquisa',false);
  }
}
function js_mostratipoandam(chave,erro){
  document.form1.y41_descr.value = chave; 
  if(erro==true){ 
    document.form1.y29_tipoandam.focus(); 
    document.form1.y29_tipoandam.value = ''; 
  }
}
function js_mostratipoandam1(chave1,chave2){
  document.form1.y29_tipoandam.value = chave1;
  document.form1.y41_descr.value = chave2;
  db_iframe_tipoandam.hide();
}
function js_pesquisa_tipofisc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipo','func_tipofiscaliza.php?funcao_js=parent.js_mostratipo1|y27_codtipo|y27_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipo','func_tipofiscaliza.php?pesquisa_chave='+document.form1.y29_tipofisc.value+'&funcao_js=parent.js_mostratipo','Pesquisa',false);
  }
}
function js_mostratipo(chave,erro){
  document.form1.y27_descr.value = chave; 
  if(erro==true){ 
    document.form1.y29_tipofisc.focus(); 
    document.form1.y29_tipofisc.value = ''; 
  }
}
function js_mostratipo1(chave1,chave2){
  document.form1.y29_tipofisc.value = chave1;
  document.form1.y27_descr.value = chave2;
  db_iframe_tipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_fiscalproc','func_fiscalprocdepto.php?funcao_js=parent.js_preenchepesquisa|y29_codtipo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_fiscalproc.hide();
  <?
  if($db_opcao == 2 || $db_opcao == 22){
    echo " location.href = 'fis1_fiscalproc002.php?abas=1&chavepesquisa='+chave;";
  }elseif($db_opcao == 33 || $db_opcao == 3){
    echo " location.href = 'fis1_fiscalproc003.php?abas=1&chavepesquisa='+chave;";
  }
 ?>
}
function js_pesquisa_codpa(){
  js_OpenJanelaIframe('','db_iframe_fiscalproc','func_fiscalprocdepto.php?funcao_js=parent.js_mostrafiscalprocpa|y29_codtipo|y29_descr','Pesquisa',true);
}
function js_mostrafiscalprocpa(chave1,chave2){
  document.form1.y61_codtipo.value = chave1;
  document.form1.descr_pa.value = chave2;
  db_iframe_fiscalproc.hide();
}


</script>