<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
//MODULO: empenho
$clpagordem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("e60_coddot");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$db_opcao_desab=1;
$desabilita =  false;
if(isset($e50_numemp)){
  
   //rotina que traz os dados do empenho
     $result = $clempempenho->sql_record($clempempenho->sql_query_file($e50_numemp)); 
     db_fieldsmemory($result,0);
   //fim  



   //rotina que irá somar os valores de todas as ordens 
       $result  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"e60_numemp,sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e50_numemp group by e60_numemp ")); 
       if($clpagordemele->numrows>0){
	 db_fieldsmemory($result,0);
       }else{
	   $tot_vlrpag   = '0.00';
	   $tot_vlranu   = '0.00';
	   $tot_valor    = '0.00';
       }  
   //fim  
   
   //pega valores se tiver ordem lanaçada
        $tem_elemento = false;
       if(isset($e50_codord)){
	 $result02  = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord,null,"sum(e53_valor) as total_valor, sum(e53_vlrpag) as total_vlrpag, sum(e53_vlranu) as total_vlranu  ")); 
	 if($clpagordemele->numrows>0){
	   db_fieldsmemory($result02,0);
	     $total_valor  = number_format($total_valor ,"2",".","");
	     $total_vlrpag = number_format($total_vlrpag,"2",".","");
	     $total_vlranu = number_format($total_vlranu,"2",".","");
   	     $tem_elemento = true;
	 }	 
       }
       if($tem_elemento==false){
	     $total_valor  = '0.00';
	     $total_vlrpag = '0.00';
	     $total_vlranu = '0.00';
       } 
  //fim
  
     //tot_xxx total de todas as ordens
     //total_xx total de só uma orde


     
    //rotina que irá pegar os valores das notas liquidadas... 
       if($db_opcao==3){
         $sql = $clempnotaele->sql_query_ordem(null,null,"sum(e70_valor) as tot_valor_nota, sum(e70_vlrliq) as tot_vlrliq_nota, sum(e70_vlranu) as tot_vlranu_nota","","e60_numemp=$e50_numemp and e71_codord =$e50_codord  and e70_vlrliq <> 0 and ((e71_codnota is not  null  and e71_anulado='f') ) group by e71_codord "); 
       }else{	
         $sql = $clempnotaele->sql_query_ordem(null,null,"sum(e70_valor) as tot_valor_nota, sum(e70_vlrliq) as tot_vlrliq_nota, sum(e70_vlranu) as tot_vlranu_nota","","e60_numemp=$e50_numemp and e70_vlrliq <> 0 and ((e71_codnota is  null) or (e71_codnota is not null and e71_anulado='t') ) group by e71_codord "); 
       }	  
       $result  = $clempnotaele->sql_record($sql);
       if($clempnotaele->numrows>0){
	 db_fieldsmemory($result,0);
       }else{
	   $tot_vlrliq_nota   = '0.00';
	   $tot_vlranu_nota   = '0.00';
	   $tot_valor_nota    = '0.00';
       }  
   //fim  

   //rotina que retorna o valor disponivel
	if($db_opcao==2){

		//valores sem notas...
		$vlrdis = ($e60_vlrliq-$e60_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag) - ($tot_valor_nota-$tot_vlranu_nota)  ;
		
	  //valores sem notas...
          $vlrdis = ($e60_vlrliq-$e60_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag)  ;
          $vlrdis = ($e60_vlrliq-$e60_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag)  ;


	  //valores com notas..
          $saldo_nota =  number_format(($tot_valor_nota-$tot_vlranu_nota),"2",".","");
          
	  
	  if($saldo_nota>$vlrdis){
	    $saldo = '0.00';
	  }else{
	    $saldo = $vlrdis-$saldo_nota;
	  } 
	  $vlrpag = '0.00';


	  
	  

	}elseif($db_opcao==3){

	  $vlrdis = ($total_valor-$total_vlrpag-$total_vlranu);
           
	  $saldo_nota = ($tot_valor_nota-$tot_vlranu_nota) ;
	  $saldo = ($total_valor-$total_vlranu) - $saldo_nota;
	  $vlrpag = '0.00';
	}else{  

              
	  
	  
	  //valores sem notas...
          $vlrdis = ($e60_vlrliq-$e60_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag)  ;


	  //valores com notas..
          $saldo_nota =  number_format(($tot_valor_nota-$tot_vlranu_nota),"2",".","");
          
	  
	  if($saldo_nota>$vlrdis){
	    $saldo = '0.00';
	  }else{
	    $saldo = $vlrdis-$saldo_nota;
	  } 
	  $vlrpag = '0.00';



	} 

	$vlrdis = number_format($vlrdis,"2",".","");
    //fim
 
     
    //rotina que verifica se o valor disponivel eh maior que zero
    
      if(($vlrdis==0||$vlrdis=='')&& $db_opcao!=2 && !isset($incluirimp)){
	$db_botao=false;
	$desabilita =  true;
	$db_opcao_desab = 33;
	      if(empty($alterar) && empty($anular) && empty($incluir) && empty($operan) ){
		$mens_erro="Não existe saldo dísponivel!";
	      }  
      }  
    //-----------------------------------------------------------------------------------------//  
}    

if(isset($e50_numemp) || isset($e50_codord) ){
  $db_opcao_ancora = 1;  
}else{
  $db_opcao_ancora = 3;  
}
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="">
<center>
<table border="0" cellpadding='0' cellspacing='0'>
  <tr>
    <td nowrap title="<?=@$Te50_codord?>">
       <?=@$Le50_codord?>
    </td>
    <td> 
<?
db_input('e50_codord',6,$Ie50_codord,true,'text',3)
?>
    </td>
    <td>&nbsp;</td>
    <td nowrap title="<?=@$Te50_numemp?>">
       <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho','".@$e50_numemp."')",$db_opcao_ancora)?>        
    </td>
    <td> 
<?
db_input('e50_numemp',13,$Ie50_numemp,true,'text',3);
?>
    </td>
            <td nowrap title="<?=@$Te60_coddot?>">
              <?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao','".@$e60_coddot."')",$db_opcao_ancora)?>        
            </td>
            <td>
               <? db_input('e60_coddot',8,$Ie60_coddot,true,'text',3); ?>
            </td>
            <td  align="left" nowrap title="Credor de empenho diferente do credor de pagamento">
	      <?db_ancora("<b>Credor</b>","js_pesquisa_cgm(true);",$db_opcao);?>
	    </td>
	    <td align="left" nowrap title="Credor de empenho diferente do credor de pagamento">
	      <?
		 db_input("z01_numcgm2",6,$Iz01_numcgm,true,"text",$db_opcao,"onchange='js_pesquisa_cgm(false);'","z01_numcgm2",($db_opcao!=3?"#E6E4F1":""));
		 db_input("z01_nome2",40,"",true,"text",3);  
	      ?>
	    </td>

  </tr>
</table>  
<table cellpadding='0' cellspacing='0' border='0'>  
  <tr>
    <td align='center'  valign='top'>
      <table border='0' cellpadding='1' cellspacing='0' class='bordas02'>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>ORDEM</small></b>
	  </td>
	</tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	       <?=@$Le53_valor?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('total_valor',15,$Ie60_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te53_vlrpag?>">
	       <?=@$Le53_vlrpag?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('total_vlrpag',15,$Ie53_vlrpag,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te53_vlranu?>">
	       <?=@$Le53_vlranu?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('total_vlranu',15,$Ie53_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>
      </table>	  
    </td>
    <td>&nbsp;</td>
    <td align='center' >
      <table border='0' cellpadding='1' cellspacing='0' class='bordas02'>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>EMPENHO</small></b>
	  </td>
	</tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te60_vlremp?>">
	       <?=@$Le60_vlremp?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('e60_vlremp',15,$Ie60_vlremp,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	       <?=@$Le60_vlranu?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('e60_vlranu',15,$Ie60_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te60_vlrliq?>">
	       <?=@$Le60_vlrliq?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('e60_vlrliq',15,$Ie60_vlrliq,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="<?=@$Te60_vlrpag?>">
	       <?=@$Le60_vlrpag?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('e60_vlrpag',15,$Ie60_vlrpag,true,'text',3,'')
	?>
	    </td>
	  </tr>
      </table>	  
    </td>	  
    <td align='left' valign='top'>
          <iframe name="ordens" id="elementos" src="forms/db_frmpagordem_ordens.php?e60_numemp=<?=@$e50_numemp?>" width="380" height="100" marginwidth="0" marginheight="0" frameborder="0">
          </iframe>
    </td>	  
  </tr>	 


  <tr>
    <td colspan='4' >   	  
      <table cellpadding='0' cellspacing='0'><tr><td>
       <table cellpadding='0' cellspacing='0' border='0'>
	  <tr >
	    <td nowrap title="<?=@$Te50_obs?>" valign='top'>
	       <?=@$Le50_obs?>
	    </td>
	   </tr> 
	   <tr>
	    <td colspan='7' align='center'> 
	<?
	if($db_opcao==1){

	  $result_parametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"),"e30_opimportaresumo, e30_trazobsultop",null,""));
	  
	  if ($clempparametro->numrows == 0){
	    $e30_trazobsultop = 1;
	  } else {
	    db_fieldsmemory($result_parametro,0);
	  }

          if ($e30_trazobsultop == 3){
	          $result_observ = $clpagordem->sql_record($clpagordem->sql_query_file(null,"e50_obs"," e50_codord desc limit 1 ","e50_id_usuario = " . db_getsession("DB_id_usuario")));
	          if($clpagordem->numrows>0){
	              db_fieldsmemory($result_observ,0);
	          }
	  } elseif ($e30_trazobsultop == 2) {
	          $result_observ = $clpagordem->sql_record($clpagordem->sql_query_file(null,"e50_obs"," e50_codord desc limit 1 "));
	          if($clpagordem->numrows>0){
	              db_fieldsmemory($result_observ,0);
	          }
	  } elseif ($e30_trazobsultop == 1) {
	          $e50_obs = "";
	  }

	  if ($clempparametro->numrows > 0){
               if (isset($e30_opimportaresumo)&&$e30_opimportaresumo!=""){
                    if ($e30_opimportaresumo=="t"){
		         $e50_obs .= "\n".$e60_resumo;
                    }	        
               }
	  }
	}
	db_textarea('e50_obs',6,70,$Ie50_obs,true,'text',$db_opcao_desab,"onblur='js_mudacampo(this.name);'")
	?>
	<?
	db_input('dados',6,0,true,'hidden',3);
	?>
	    </td>
	  </tr>
       </table>  
      </td><td>
       <table cellpadding='0' cellspacing='0' border='0'>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>VALORES</small></b>
	  </td>
	</tr>

	  <tr class='bordas'>
	    <td class='bordas' nowrap title="Valor que deseja anular">
		<?
		if($db_opcao==3){
	          echo " <b>Disponível anular:</b>";
                }else{
	          echo " <b>Valor disponível:</b>";
	        }
		?>	
	    </td>
	    <td class='bordas'> 
      	     <?db_input('vlrdis',10,0,true,'text',3);?>
	    </td>
	  </tr>  
	   <tr class='bordas'>
	    <td class='bordas' nowrap title="Valor à pagar">
   <?if($db_opcao==2){	    
         echo " <b>Valor acrescentar:</b>";
   }else if($db_opcao==3){ 	    
          echo "<b>Valor a anular: </b>";
    }else{	    
          echo "<b>Valor da ordem:</b>";
    }?>	    
	    </td>
	    <td class='bordas'> 
             <?db_input('vlrpag',10,4,true,'text',$db_opcao_desab,"onchange='js_verificar(\"campo\");'");?>
	    </td>
	  </tr>  
      </table> 	  
      </td></tr></table>
    </td>   	  
  </tr>	  
</table>  
<table align='left' cellpadding='0' cellspacing='0'>  
   <tr>
    <td  align='center'>
       <input name="<?=($db_opcao==1||$db_opcao==11?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"anular"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1||$db_opcao==11?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Anular"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verif();">
       <?if($db_opcao!=3 && $db_opcao!=33){?>
       <input name="<?=(($db_opcao==1||$db_opcao==11)?"incluirimp":"alterarimp")?>" type="submit" id="db_opcaoimp" value="<?=(($db_opcao==1||$db_opcao==11)?"Incluir e imprimir":"Alterar e imprimir")?>" <?=($db_botao==false?"disabled":"")?> onclick="js_verif();return js_imprimeincalt(this.name);">
       <?}?>
       <input name="pesquisar" type="button" id="pesquisar" value="<?=($db_opcao==11||$db_opcao==1?"Pesquisar empenhos":"Pesquisar ordens")?>"  <?=($db_opcao==11||$db_opcao==1?"onclick=\"js_pesquisa_emp();\"":"onclick=\"js_pesquisa_ordem();\"")?>  >
       <input name="imprimir" type="button" id="imprimir" value="<?=($db_opcao==11||$db_opcao==1?"Imprimir":"Imprimir")?>"  <?=($db_opcao==11||$db_opcao==1?"onclick=\"js_imprimir();\"":"onclick=\"js_imprimir();\"")?>  >
       <?
       echo "<script>
             function js_retornaliq(){
	       top.corpo.location.href = 'emp1_empliquida001.php';
	     }
	     </script>";
       if(isset($emite_automatico) || isset($retornaliq)){
         echo "<input name=\"retornaliq\" type=\"text\" size=\"2\" style=\"visibility:hidden\" id=\"retornaliq\" value=\"retornaliq\" >";
         echo "<input name=\"retornaliqbutton\" type=\"button\" id=\"retornaliqbutton\" value=\"Retorna Liquidação\" onclick=\"js_retornaliq();\">";
       }
       ?>
    </td>
     <td align='center'>
  <?
    if(isset($e50_numemp)){
     
        if($db_opcao==3){
           $sql = $clempnotaele->sql_query_ordem(null,null," nome,e69_dtnota,e70_codnota,e71_anulado,sum(e70_valor) as e70_valor, sum(e70_vlrliq) as e70_vlrliq, sum(e70_vlranu) as    e70_vlranu","","e69_numemp=$e50_numemp  and e71_codord=$e50_codord and e70_vlrliq <> 0  and ((e71_codnota is  not  null  and e71_anulado='f') ) group  by nome,e70_codnota,e71_anulado,e69_dtnota "); 
        }else{  
           $sql = $clempnotaele->sql_query_ordem(null,null," nome,e69_dtnota,e70_codnota,e71_anulado,sum(e70_valor) as e70_valor, sum(e70_vlrliq) as e70_vlrliq, sum(e70_vlranu) as    e70_vlranu","","e69_numemp=$e50_numemp  and e70_vlrliq <> 0  and ((e71_codnota is  null) or (e71_codnota is not null and e71_anulado='t') ) group  by nome,e70_codnota,e71_anulado,e69_dtnota "); 
        }	 
	$clempnotaele->sql_record($sql); 
	$numrows_nota = $clempnotaele->numrows;
	if($numrows_nota>0){
	    
	    $cliframe_seleciona->textocabec ="black";
	    $cliframe_seleciona->textocorpo ="black";
	    $cliframe_seleciona->fundocabec ="#999999";
	    $cliframe_seleciona->fundocorpo ="#cccccc";
	    $cliframe_seleciona->iframe_height ="80";
	    $cliframe_seleciona->iframe_width ="450";
	    $cliframe_seleciona->iframe_nome ="notas";
	    $cliframe_seleciona->fieldset =false;

	    $cliframe_seleciona->marcador = false;
	    $cliframe_seleciona->dbscript = "onclick=\\\"parent.js_calcula(this);\\\"";
	    
	    
	    $cliframe_seleciona->campos  = "e70_codnota,nome,e69_dtnota,e70_valor,e70_vlrliq,e70_vlranu";
	    $cliframe_seleciona->sql = $sql;
	    $cliframe_seleciona->input_hidden = true;
	    $cliframe_seleciona->chaves ="e70_codnota";
	    $cliframe_seleciona->iframe_seleciona(1);    
	} 
       
       //rotina que monta campos com elementos e seus valores...
       if($db_opcao==3){
         $sql = $clempnotaele->sql_query_ordem(null,null,"e70_codnota,e70_codele,e70_valor,e70_vlrliq,e70_vlranu","","e69_numemp=$e50_numemp and e70_vlrliq<>0  and ((e71_codnota is  not  null and e71_anulado='f') )"); 
       }else{
         $sql = $clempnotaele->sql_query_ordem(null,null,"e70_codnota,e70_codele,e70_valor,e70_vlrliq,e70_vlranu","","e69_numemp=$e50_numemp and e70_vlrliq<>0  and ((e71_codnota is  null) or (e71_codnota is not null and e71_anulado='t') )"); 
       }	 
       $result = $clempnotaele->sql_record($sql); 
       $numrows = $clempnotaele->numrows;
       $total_nota = 0;
       if($numrows>0){
	 for($i=0; $i<$numrows; $i++ ){
	   db_fieldsmemory($result,$i);
	   echo "\n<input id='nota_$e70_codnota' name='nota_$e70_codnota' type='hidden' value='".$e70_codele."_".$e70_valor."'>";
	   $total_nota += $e70_valor;
	 }
       }  
     }        

      
     
  ?>
     </td>
  </tr>
  <tr>
    <td colspan='2' align='left'>
      <iframe name="elementos" id="elementos" src="forms/db_frmpagordem_elementos.php?desabilita=<?=$desabilita?>&db_opcao=<?=$db_opcao?>&e50_codord=<?=@$e50_codord?>&e60_numemp=<?=@$e50_numemp?>" width="760" height="130" marginwidth="0" marginheight="0" frameborder="0">
      </iframe>
    </td>
  </tr>
</table>
  </center>
</form>
<script>
function js_mudacampo(nomecampomaisum){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='textarea' && x.elements[i].name==nomecampomaisum){
      x.elements[i+3].select();
      break;
    }
  }
}
function js_imprimeincalt(opcao){

  //return js_verif();
  //alert(1);
  obj=document.createElement('input');
  obj.setAttribute('name',opcao);
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',opcao);
  document.form1.appendChild(obj);

  document.form1.submit();

}
function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pagordem','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,0);
  }else{
     if(document.form1.z01_numcgm2.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pagordem','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm2.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome2.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome2.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm2.value = ''; 
    document.form1.z01_numcgm2.focus(); 
  }
}
function js_mostracgm1(chave1,chave2){
   document.form1.z01_numcgm2.value = chave1;  
   document.form1.z01_nome2.value = chave2;
   db_iframe_cgm.hide();
}
<?
     $campo = "vlrpag";
?>

function js_verif(){
  //alert(2);
  if(document.form1.vlrpag.value=='' || document.form1.vlrpag.value==0){
    <?if($db_opcao != 2){?>
      alert("Informe o valor da ordem!");
      return false;
    <?}?>
  }
  
  <?
   if(isset($agendado)) {
     
    echo "if (!confirm('Esta Ordem de Pagamento consta na agenda $e81_codage.\\n realmente confirmar a anulação ?'))  {\n";
    echo "  return false;\n";
    echo "}\n"; 
     
   }
     if($db_opcao==3||$db_opcao==2){
        echo "return js_verifica_receita();";
     }else if(isset($numrows_nota) && $numrows_nota>0){
        echo "  return js_gera_chaves();";
     }
  ?> 
}  
<?if(isset($e50_codord) && ($db_opcao==2||$db_opcao==3)){?>


function js_verifica_receita(){
   vlrpag = document.form1.vlrpag.value;
   js_OpenJanelaIframe('top.corpo.iframe_pagordem','db_iframe_veri','emp1_pagordem007.php?db_opcao=<?=$db_opcao?>&e50_codord=<?=$e50_codord?>&vlranu='+vlrpag,'Pesquisa',false);
   return false;
}
function js_confere(pode){
  <?
    if($db_opcao==2){
      $nomec="alterar";
    }else{
      $nomec="anular";
    }  
  ?>
  if(pode==true){
  //só irar gerar as chaves da notas quando tiver notas...hehee  
  <?if(isset($numrows_nota) && $numrows_nota>0){?>
      js_gera_chaves();
   <?}?>   
      document.form1.<?=$nomec?>.onclick='';
      document.form1.<?=$nomec?>.click();
  }else{
    if(confirm("O valor digitado é maior que o total dos valores da receita! \n Se continuar, os registros da receita serão apagados! ")){
      var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","apagarec");
      opcao.setAttribute("value","true");
      document.form1.appendChild(opcao);

      document.form1.<?=$nomec?>.onclick='';
      document.form1.<?=$nomec?>.click();
    }
  }
}
<?}?>
  <?
  if(isset($mens_erro)){
   echo "alert('$mens_erro');\n";
  }


  
  if(isset($vlrdis)){
  ?>
//função responsavel de pegar o valor da nota cliacada e colocar no campo para estornar	    
cont =0;
function js_calcula(campo){
        if(campo.checked==true){
   	  if(cont == '0' ){
	    document.form1.vlrpag.value = "0.00";
            document.form1.vlrpag.readOnly=true;
	    document.form1.vlrpag.style.backgroundColor = '#DEB887';
	    document.form1.vlrpag.value = '0.00';
	    document.form1.<?=$campo?>.value = '0.00';
            elementos.js_zeracampos(true);  
	    <?if($db_opcao==1){?>
            elementos.js_tranca(); 
	    <?}?>
          }
    	  cont++;
	}else{ 
	     cont--;
	    if(cont == '0' ){
	      document.form1.vlrpag.readOnly=false;
              document.form1.vlrpag.style.backgroundColor = 'white';
   	      document.form1.vlrpag.value = '0.00';
   	      document.form1.<?=$campo?>.value = '0.00';
              elementos.js_zeracampos(true);  
	      <?if($db_opcao==1){?>
                elementos.js_libera(); 
	      <?}?>	
	      return true;
	    }   
        }
  
  //atualiza os valores  
 
     eleval = '';
     vir='';
     virg='';

     obj = document.form1.elements; 
     soma_total  = new Number();
     nota = campo.value;

     for(w=0; w<obj.length; w++){
       nome = obj[w].name;
       if(nome == "nota_"+campo.value ){
	   eles = obj[w].value;
           arr_eles = eles.split("_");
	   elemento = arr_eles[0];
	   valor    = new Number(arr_eles[1]);
	   
           vlrlimite = new Number(eval("elementos.document.form1.saldo_nota_"+elemento+".value"));
	   if(valor >vlrlimite){
	     alert('Valor não permitido para o elemento '+elemento+'!');
 	            campo.checked = false;
		    cont--;
		    if(cont == '0' ){
		      document.form1.vlrpag.readOnly=false;
		      document.form1.vlrpag.style.backgroundColor = 'white';
		      document.form1.vlrpag.value = '0.00';
		      elementos.js_zeracampos(true);  
		    }   
		    return false;
	   }

           eleval += vir+nota+"-"+elemento+"-"+valor;
           vir='#';
	   soma_total +=valor;
       } 	
     }



     
	  if(campo.checked==true){
                 x = new Number(document.form1.vlrdis.value);
     		 y = new Number(document.form1.vlrpag.value);
                 vlrdis = new Number ( x.toFixed(2)  - y.toFixed(2) ); 
		 soma_total =new Number(soma_total);
                 if((soma_total.toFixed(2) > vlrdis.toFixed(2)) && (soma_total > vlrdis ))  {
		   
	            alert('Valor não permitido!');
 	            campo.checked = false;
		    cont--;
		    if(cont == '0' ){
		      document.form1.vlrpag.readOnly=false;
		      document.form1.vlrpag.style.backgroundColor = 'white';
		      document.form1.vlrpag.value = '0.00';
		      elementos.js_zeracampos(true);  
		    }   
		    return true;
	          }
	  }  
	  
     antigo = new Number(document.form1.vlrpag.value); 
     if(campo.checked==true){
        elementos.js_coloca_notas(eleval,true);
        tot  = new Number(soma_total + antigo);
        document.form1.vlrpag.value =  tot.toFixed(2);
     }else{
        elementos.js_coloca_notas(eleval,false);
        document.form1.vlrpag.value =  antigo -soma_total;
     }  


}



      function js_verificar(tipo){
        erro=false; 

	  if(tipo=='botao'){
	    if(document.form1.vlrpag.value == '' || document.form1.vlrpag.value == 0 ){
	      alert('Informe o valor!');
	      return false;	    
	    }
            for(i=0;i<elementos.document.form1.length;i++){
              if(elementos.document.form1.elements[i].name.substr(0,9)=="generico_"){
                valor = Number(elementos.document.form1.elements[i].value);
                if(valor==0){
                  alert("Informe o valor s/ nota!");
                  return false;
                }
              }
            }
	  }  

	vlrpag= new Number(document.form1.vlrpag.value);
	if(isNaN(vlrpag)){
	  erro=true;
	}
        
     <?if($db_opcao==3){ 
       echo "
           if(vlrpag > $saldo){
	     alert('O máximo que se pode anular sem nota é $saldo  menos o que já foi pago desse valor!');
	     vlrdis=0;
	     erro=true;
	   }
      	  ";
       }
    ?> 

       vlrdis = new Number(document.form1.vlrdis.value);
        if(vlrpag> <?=$saldo?> && erro!=true){
	  alert("Já existe nota para este valor!");
	  erro=true;
	}
	
	if(vlrpag > vlrdis){
	 erro= true;
	}
       

	if(erro==false){
	  val = vlrpag.toFixed(2);
	  document.form1.vlrpag.value=val
	  if(tipo=='campo'){
	    elementos.js_coloca(val);
	  }
	  return true;
	}else{
          document.form1.vlrpag.focus();
          document.form1.vlrpag.value=vlrdis.toFixed(2);
	  elementos.js_coloca(vlrdis);
	  return false;
	}
	
	}
<?
  }
  
?>
function js_pesquisa_emp(){
  js_OpenJanelaIframe('top.corpo.iframe_pagordem','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true,0);
}
function js_pesquisa_ordem(){
 <? if(isset($procedimento) && $procedimento=="anulacao"){?>//variavel procedimento, é para indicar quando esta anulando, dae abre uma func que tras só as ordens com saldo
     js_OpenJanelaIframe('top.corpo.iframe_pagordem','db_iframe_pagordem','func_pagordem_anula.php?funcao_js=parent.js_preenchepesquisa|e50_codord','Pesquisa',true,0);
 <? }else{?>
     js_OpenJanelaIframe('top.corpo.iframe_pagordem','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_preenchepesquisa|e50_codord','Pesquisa',true,0);
 <? }?>
}
function js_preenchepesquisa(chave){
  <?
      if($db_opcao==1 || $db_opcao==11){
  ?>
     db_iframe_empempenho.hide();
  <?
     }else{  
  ?>  
     db_iframe_pagordem.hide();
  <?
     }
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&z01_numcgm2='+document.form1.z01_numcgm2.value";
  ?>
}
function js_imprimir(){
//  alert('parou');
  jan = window.open('emp2_emiteordem002.php?codordem='+document.form1.e50_codord.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>