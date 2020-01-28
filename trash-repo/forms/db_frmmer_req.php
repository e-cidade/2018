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

$clrotulo = new rotulocampo;
$clrotulo->label("me16_i_codigo");
$clrotulo->label("me17_i_codigo");
$clrotulo->label("me18_i_codigo");
$clrotulo->label("me17_i_item");
$clrotulo->label("me10_c_descr");
$clrotulo->label("me17_f_quant");

if (isset($opcao) && $opcao=="alterar") {
  $db_opcao = 2;
} else if (isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
  $db_opcao = 3;
} else {  
  $db_opcao = 1;
} 

?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
<br><br><br>
<table>
  <tr>
    <td>
    <fieldset><legend><b>Itens da Requisição</b></legend>
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td nowrap title="<?=@$Tme16_i_codigo?>"><b>Requisição: </b></td>
        <td> 
             <?
              db_input('me16_i_codigo',10,$Ime16_i_codigo,true,'text',3,"");
	          $me17_i_codigo=@$me17_i_codigo;
              db_input('me17_i_codigo',10,$Ime17_i_codigo,true,'hidden',3,"");
              db_input('me18_i_codigo',10,$Ime18_i_codigo,true,'hidden',3,"");
	     ?>
	  </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tme10_i_codigo?>">
         <?db_ancora("Itens","js_pesquisame10_i_codigo(true);",$db_opcao);?>
	    </td>
        <td> 
      <?
      db_input('me17_i_item',10,$Ime17_i_item,true,'text',$db_opcao,"onchange='js_pesquisame10_i_codigo(false);'");
      db_input('me10_c_descr',40,$Ime10_c_descr,true,'text',3,"");
      ?>
	  </td>
      </tr>
      <tr>
      <td title=<?=@$Tme17_f_quant?>>
	<?=@$Lme17_f_quant?>
	</td>
     <td>
	  <?db_input('me17_f_quant',10,$Ime17_f_quant,true,'text',$db_opcao,"");?>
	</td>
   </tr>
	<?
	if (isset($m41_codmatmater) && $m41_codmatmater!="") {
	  
	  $codmater=$m41_codmatmater;
	  $rsMaterial   = $clmatmater->sql_record($clmatmater->sql_query_file($m41_codmatmater)); 
	  $oMaterial    = db_utils::fieldsMemory($rsMaterial, 0);
      $sqlmatrequi  = $clmatrequi->sql_query($m40_codigo, "m40_depto");
	  $resmatrequi  = $clmatrequi->sql_record($sqlmatrequi);
	  db_fieldsmemory($resmatrequi, 0);
	  $campos = " sum(m70_valor)as vlrtot,sum(m70_quant)as quantot ";
	  $result_matestoque=$clmatestoque->sql_record(
	                                                $clmatestoque->sql_query_almox(null,
	                                                                               $campos,
	                                                                               null,
	                                                                               "m70_codmatmater=$codmater",
	                                                                               "",
	                                                                               "",
	                                                                               db_getsession("DB_coddepto")
	                                                                              )
	                                              );
	  if ($clmatestoque->numrows!=0) {
	    db_fieldsmemory($result_matestoque,0);  
	  }
	  ?>
	<tr>
        <td title='Quant. Disponível'><b>Quantidade Disponível:</b></td>
        <td>
	<?
	 if (isset($quantot)&&($quantot!="")) {
	   $quant_disp=$quantot;
	 } else {
	  $quant_disp='0';
	 }
	 db_input('quant_disp',10,$Im41_quant,true,'text',3,"");
	?>
	</td>
   </tr>
 <?}?>
	<tr>
    </table>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan=2 align=center>
	  <?
         if (!isset($opcao) && isset($db_opcao) && $db_opcao==3) {
            $db_botao=false;	  
	     }
	  ?>
      <input name="Incluir" type="submit" value="Incluir">
      <input name="Imprimir" type="button" value="Imprimir Requisição" onclick='js_imprime();'>
    </td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri= array( "m40_codigo"=>@$m40_codigo,
                       "m41_codigo"=>@$m41_codigo,
                       "m41_obs"=>@$m41_obs,
                       "m41_quant"=>@$m41_quant,
                       "m41_codmatmater"=>@$m41_codmatmater,
                       "m60_descr"=>@$m60_descr
                     );
     $cliframe_alterar_excluir->chavepri=$chavepri;
     if (isset($m40_codigo)&&@$m40_codigo!="") {
        $cliframe_alterar_excluir->sql = $clmatrequiitem->sql_query(null,'*',null,"m41_codmatrequi=$m40_codigo");
      }
      if(isset($me16_codigo)) {
      	
         $cliframe_alterar_excluir->sql_disabled = $clmer_requiitem_ext->sql_query(null,
                                                                                   '*',
                                                                                   null,
                                                                                   "me17_i_merrequi=$me16_codigo"
                                                                                  );
                                                                                  
      }else{
         $cliframe_alterar_excluir->sql_disabled = $clmer_requiitem_ext->sql_query(null,'*',null,"");	
      }
      $cliframe_alterar_excluir->campos  ="m41_codmatmater,m60_descr,m41_quant,m41_obs";
      $cliframe_alterar_excluir->legenda       ="ITENS REQUISITADOS";
      $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec    = "darkblue";
      $cliframe_alterar_excluir->textocorpo    = "black";
      $cliframe_alterar_excluir->fundocabec    = "#aacccc";
      $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
      $cliframe_alterar_excluir->iframe_width  = "710";
      $cliframe_alterar_excluir->iframe_height = "130";
      $lib=4;
      $cliframe_alterar_excluir->opcoes = @$lib;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);   
      db_input('db_opcao',10,'',true,'hidden',3);
    ?>
   </td>
  </tr>
</table>
</form>
<script>
function js_imprime() {
	
  var obj   = document.form1;
  var query = "";
  if (obj.m40_codigo.value!="") {
	  
    query  = "ini="  + obj.m40_codigo.value;
    query += "&fim=" + obj.m40_codigo.value;
    query += "&tObserva=181";
    jan = window.open('mat2_matrequi002.php?'+query,'',
    	              'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
    	             );
    jan.moveTo(0,0);
    
  }
}
//Ancora não retorna :s
function js_pesquisame10_i_codigo(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_mater',
    	                'func_mer_item.php?funcao_js=parent.js_mostra1|me10_i_codigo|me10_c_descr','Pesquisa',true,3
    	               );
    
  } else {
	  
    if (document.form1.me17_i_item.value != '') {
         
      js_OpenJanelaIframe('','db_iframe_mater',
    	                  'func_mer_item.php?pesquisa_chave='+document.form1.me17_i_item.value+
    	                  '&funcao_js=parent.js_mostra','Pesquisa',false
    	                 );
      
    } else {
      document.form1.me17_i_item.value = ""; 
     }
  }
}

function js_mostra(chave,erro) {
	
  document.form1.me10_c_descr.value = chave; 
  if (erro==true) {
	   
    document.form1.me17_i_item.focus(); 
    document.form1.me17_i_item.value = '';
     
  } else {
    document.form1.me10_c_descr.value = chave;
  }
}

function js_mostra1(chave1,chave2) {
	
  alert('chave1:'+chave1+' chave2:'+chave2);
  document.form1.me17_i_item.value  = chave1;
  document.form1.me10_c_descr.value = chave2;
  db_iframe_mater.hide();
  
}  
</script>