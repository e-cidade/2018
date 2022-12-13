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

//MODULO: patrim
$clbenstransfconf->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("t95_codtran");
$clrotulo->label("t95_codbem");
$clrotulo->label("t95_situac");
$clrotulo->label("t95_histor");
$clrotulo->label("t30_descr");
$clrotulo->label("t52_descr");
$clrotulo->label("t70_descr");
$clrotulo->label("t52_ident");

    $id = db_getsession("DB_id_usuario");
    $usu = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($id,"nome",null,""));
    if($cldb_usuarios->numrows > 0){
      $resultado =  db_fieldsmemory($usu,0);
      $t96_id_usuario = $id;
    }
  ?>

<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Confirmação de Transferência</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tt96_data?>">
          <?=@$Lt96_data?>
        </td>
        <td> 
          <?
            $t96_data_dia=date("d",db_getsession("DB_datausu"));
            $t96_data_mes=date("m",db_getsession("DB_datausu"));
            $t96_data_ano=date("Y",db_getsession("DB_datausu"));
            db_inputdata('t96_data',@$t96_data_dia,@$t96_data_mes,@$t96_data_ano,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tnome?>">
          <?=(@$Lnome)?>
        </td>
        <td> 
          <?
            db_input('t96_id_usuario',8,$It96_id_usuario,true,'text',3," onchange='js_pesquisat96_id_usuario(false);'")
          ?>
          <?
            db_input('nome',40,$Inome,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt96_codtran?>">
          <?
            db_ancora(@$Lt96_codtran,"js_pesquisat96_codtran(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t96_codtran',8,$It96_codtran,true,'text',$db_opcao," onchange='js_pesquisat96_codtran(false);'")
          ?>
          <?
            db_input('nome',40,$Inome,true,'text',3,'','nome_transf')
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Receber":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <?
    if ( isset($t96_codtran) && $t96_codtran != "" ) {
      
      $sCamposBensTransfCodigo = "distinct t95_codtran,t95_codbem,t52_descr,t95_situac,t70_descr,t95_histor,t31_divisao,t30_descr,t52_ident";
      $sSqlBensTransfCodigo    = $clbenstransfcodigo->sql_query_div($t96_codtran,null,$sCamposBensTransfCodigo);
      $result  = $clbenstransfcodigo->sql_record($sSqlBensTransfCodigo);
      $numrows = $clbenstransfcodigo->numrows;
      //die ($sSqlBensTransfCodigo);
        
      if ($numrows > 0) {
        echo "
          <table border='1' cellspacing='0' cellpadding='0'>
            <tr><td></td></tr>
            <tr class='bordas'>		  
              <td class='bordas' align='center'><b><small>  $Lt95_codtran	</small></b></td>
              <td class='bordas' align='center'><b><small>  $Lt95_codbem 	</small></b></td>
              <td class='bordas' align='center'><b><small>  $Lt52_ident   </small></b></td>
              <td class='bordas' align='center'><b><small>  $Lt52_descr 	</small></b></td>
              <td class='bordas' align='center'><b><small>  $Lt70_descr		</small></b></td>
              <td class='bordas' align='center'><b><small>  $Lt95_histor 	</small></b></td>	      
              <td class='bordas' align='center'><b><small>  $Lt30_descr  	</small></b></td>
            </tr>";
      } else {
        echo"<b>Nenhum registro encontrado...</b>";
      }
  
       for($i=0; $i<$numrows; $i++){
  	    db_fieldsmemory($result,$i);	    	       
  	    echo "<tr>
                <td	 class='bordas_corp' align='center'><small>	$t95_codtran			&nbsp;</small></td>
                <td	 class='bordas_corp' align='center'><small>	$t95_codbem 			&nbsp;</small></td>
                <td  class='bordas_corp' align='center'><small> $t52_ident        &nbsp;</small></td>
                <td	 class='bordas_corp' align='center'><small>	$t52_descr 				&nbsp;</small></td>
                <td	 class='bordas_corp' align='center'><small>	$t70_descr 				&nbsp;</small></td>
                <td	 class='bordas_corp' align='center'><small>	$t95_histor 			&nbsp;</small></td>
                <td	 class='bordas_corp' align='center'><small>
             	  <select name='t31_divisao_$t95_codbem'>
  	             <option value=''>Nenhuma</option>";
  	             
        $sSqlDepartDiv = $cldepartdiv->sql_query_file(null,"t30_codigo,t30_descr",null,"t30_depto=".db_getsession("DB_coddepto"));
  	    $result1       = $cldepartdiv->sql_record($sSqlDepartDiv);
        for($y=0; $y < $cldepartdiv->numrows; $y++) {
          db_fieldsmemory($result1,$y);
   	        ?>
   	          <option value=<?=@$t30_codigo?> <?=(isset($t31_divisao)&&$t31_divisao==$t30_codigo?"selected":"") ?> > <?=@$t30_descr?> </option>
   	        <?
  	    }
        echo " </select>";          
     
     	  echo "&nbsp;</small></td>
  		        </tr>"; 
       }
       echo   "</table>";
    }
  ?>
</form>
<script>
function js_pesquisat96_codtran(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_benstransf','func_benstransf001.php?funcao_js=parent.js_mostrabenstransf1|t93_codtran|nome','Pesquisa',true);
  }else{
     if(document.form1.t96_codtran.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_benstransf','func_benstransf001.php?pesquisa_chave='+document.form1.t96_codtran.value+'&funcao_js=parent.js_mostrabenstransf','Pesquisa',false);
     }else{
       document.form1.nome_transf.value = ''; 
     }
  }
}
function js_mostrabenstransf(chave,erro){
  document.form1.nome_transf.value = chave; 
  if(erro==true){ 
    document.form1.t96_codtran.focus(); 
    document.form1.t96_codtran.value = ''; 
  }else{
  	document.form1.submit();
  }
}
function js_mostrabenstransf1(chave1,chave2){
  document.form1.t96_codtran.value = chave1;
  document.form1.nome_transf.value = chave2;  
  db_iframe_benstransf.hide();
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_benstransfconf','func_benstransfconf.php?funcao_js=parent.js_preenchepesquisa|t96_codtran&t93=false','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_benstransfconf.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

</script>
<script>

$("t96_data").addClassName("field-size2");
$("t96_id_usuario").addClassName("field-size2");
$("nome").addClassName("field-size7");
$("t96_codtran").addClassName("field-size2");
$("nome_transf").addClassName("field-size7");

</script>