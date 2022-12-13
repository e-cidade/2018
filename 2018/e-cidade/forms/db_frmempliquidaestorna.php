<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: empenho
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_seleciona = new cl_iframe_seleciona;


$clrotulo = new rotulocampo;
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("o56_elemento");
$clrotulo->label("e60_coddot");
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();
$clempnotaele->rotulo->label();

$jafoipago="false";

  if(isset($e60_vlremp)){

    //os dados do empenho já foram pegos no emp1_empliquida001.php
    //$e60_valor
    //$e60_vlrliq
    //$e60_vlranu
    //$e60_vlrpag

    //=======================================================================>
    //retorna valores das ordens do empenho
      $result = $clpagordem->sql_record($clpagordem->sql_query_file(null,"e50_codord as codord","","e50_numemp=$e60_numemp"));
      $numrows = $clpagordem->numrows;
      //rotina que verifica se existe ordem para o empenho... se existir, no valor disponivel para estornar sera descontado
      //os valores de todas as ordens existentes
      if($numrows > 0){
	  $cods='';
	  $vir='';
	  for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i);
	    $cods .= $vir.$codord;
	    $vir=',';
	  }
	  $result02 = $clpagordemele->sql_record($clpagordemele->sql_query_file(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu ","","e53_codord in($cods)"));
	  db_fieldsmemory($result02,0,true);
      }else{
	$tot_valor  = '0.00';
	$tot_vlranu = '0.00';
	$tot_vlrpag = '0.00';
      }
    //===============================================================================>

    //==================================================================================>
        //rotina que procura pega os valores das notas...
	  $result = $clempnota->sql_record($clempnota->sql_query_file(null,"e69_numemp","","e69_numemp = $e60_numemp "));
	  $numrows_nota = $clempnota->numrows;

	  if($numrows_nota>0){
	    //rotina que traz os valores do empnotaele...
	    $result  = $clempnotaele->sql_record($clempnotaele->sql_query(null,null,"sum(e70_valor) as tot_valorn, sum(e70_vlrliq) as tot_vlrliqn, sum(e70_vlranu) as tot_vlranun","","e69_numemp=$e60_numemp"));
	    db_fieldsmemory($result,0,true);
	  }else{
	      $tot_valorn  = '0.00';
	      $tot_vlrliqn = '0.00';
	      $tot_vlranun = '0.00';
	    }
     //=======================================================================================>


     //=======================================================================================>
    //rotina que procura pega o total de ordens com  notas
	    $sql = $clempnotaele->sql_query_ordem(null,null," sum(e70_valor) as tot_valornord, sum(e70_vlrliq) as tot_vlrliqnord, sum(e70_vlranu) as    tot_vlranunord","","e69_numemp=$e60_numemp  and e70_vlrliq <> 0  and ((e71_codnota is  not  null  and e71_anulado='f') ) ");
	    $result = $clempnotaele->sql_record($sql);
	    db_fieldsmemory($result,0,true);
	    $tot_valornord = $tot_vlrliqnord - $tot_vlranunord;

	    $notas_vlrliq_semordem   = $tot_valor  - $tot_valornord;
            $notas_vlranu_semordem   = $tot_vlranu - $tot_vlranunord;
   //=====================================================>



     //existe dois calculos diferentes.... quando o empenho já tiver um valor pago ele irá o mostrar o valor total
    // disponivel para anular... caso contrario irá mostrar os valores disponiveis com nota e sem notm notaa


    /// Se o empenho já tiver um valor pago entaum seta $jafoipago para se orientar a baixo..
      if(isset($e60_vlrpag) && $e60_vlrpag>0){
     	   $jafoipago ="true";
      }





	 // disponivel = liquidados -  notas liquidadas sem ordem - total da ordem - anulado
	  //$vlrdis =  $e60_vlrliq - $notas_vlrliq_semordem - ($tot_valor-$tot_vlranu) - $tot_nota ;
	 // echo "$vlrdis =   ($tot_valor-$tot_vlranu ) - $tot_valornord ";

	  $vlrdis =   $e60_vlrliq - ($tot_valor-$tot_vlranu );


	  /*     total de notas - total de notas com ordens		*/
	  $vlrdis_nota = $tot_vlrliqn -   $tot_vlrliqnord ;

	  $vlrdis = $vlrdis - $vlrdis_nota;

	  if($vlrdis<0){
	    $vlrdis = '0.00';
	  }

          $vlrliq_estornar = '0.00';

      //quando já tiver sido pago, será usado somente o vlrdis..
      if(isset($jafoipago) && $jafoipago==true){

	 $vlrdis_pag = ($vlrdis+$vlrdis_nota) - ($e60_vlrpag-$tot_vlrpag);
         $vlrdis = $vlrdis_pag;
         /*echo "if($vlrdis_pag<$vlrdis_nota){";
         if($vlrdis_pag<$vlrdis_nota){
	   $vlrdis='0.00';
	 }else{
	   $vlrdis = $vlrdis_pag - $vlrdis_nota;
	 }*/
      }



      $vlrdis_nota = number_format($vlrdis_nota,2,".","");
      $vlrliq_estornar_nota = "0.00";

      $vlrdis = number_format($vlrdis,2,".","");
      $vlrliq_estornar = "0.00";


  //rotina que verifica os saldos disponiveis....
  if(($vlrdis==0||$vlrdis=='')&&($vlrdis_nota==0||$vlrdis_nota=='')){
      $db_opcao=33;
    }

  }

if(empty($e60_numemp)){
  $db_opcao_inf = 3;
}else{
  $db_opcao_inf = $db_opcao;
}



   //quando for resto a pagar sera atualizado a variavel $restoapagar
  if(isset($e60_anousu) && $e60_anousu <  db_getsession("DB_anousu")){
 	$restoapagar=true;
  }
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 1px solid #cccccc;
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
<table border='0' cellspacing='0' cellpadding='0' width='100%'>
  <tr>
  <td colspan='2' align='center' valign='top'>
<?
db_input('dados',6,0,true,'hidden',3)
?>

<table border="0" cellspacing='0' cellpadding='0'>
  <tr>
    <td nowrap title="<?=@$Te60_numemp?>">
       <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho','".@$e60_numemp."')",$db_opcao_inf)?>
    </td>
    <td>
<?
db_input('e60_numemp',13,$Ie60_numemp,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
    <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','".@$e60_numcgm."')",$db_opcao_inf)?>
    </td>
    <td>
<?
db_input('e60_numcgm',10,$Ie60_numcgm,true,'text',3)
?>
       <?
db_input('z01_nome',38,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
      <td nowrap title="<?=@$Te60_coddot?>">
         <?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao','".@$e60_coddot."','".db_getsession("DB_anousu")."')",$db_opcao_inf)?>
      </td>
      <td>
          <? db_input('e60_coddot',8,$Ie60_coddot,true,'text',3); ?>
      </td>
  </tr>
     <?    /* busca dados da dotação  */
     if((isset($e60_coddot))){
          $instit=db_getsession("DB_instit");
          $clorcdotacao->sql_record($clorcdotacao->sql_query_file("","","*","","o58_coddot=$e60_coddot and o58_instit=$instit"));
          if($clorcdotacao->numrows >0){
             $result= db_dotacaosaldo(8,2,2,"true","o58_coddot=$e60_coddot", $e60_anousu) ;
             db_fieldsmemory($result,0);
	     $atual=number_format($atual,2,",",".");
	     $reservado=number_format($reservado,2,",",".");
             $atudo=number_format($atual_menos_reservado,2,",",".");
	   }else{
	     $nops=" Dotação $e60_coddot  não encontrada ";
	   }

      }
     ?>
          <tr>
             <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
	     <td nowrap >
	       <? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?>
	       <? db_input('o40_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
	     <td nowrap >
	       <? db_input('o58_unidade',8,"",true,'text',3,"");  ?>
	       <? db_input('o41_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
	     <td nowrap >
	       <? db_input('o58_funcao',8,"",true,'text',3,"");  ?>
	       <? db_input('o52_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
	     <td nowrap >
	       <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>
	       <? db_input('o53_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
	     <td nowrap >
	       <? db_input('o58_programa',8,"",true,'text',3,"");  ?>
	       <? db_input('o54_descr',40,"",true,'text',3,"");  ?>
             </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
	     <td nowrap >
	       <? db_input('o58_projativ',8,"",true,'text',3,"");  ?>
	       <? db_input('o55_descr',40,"",true,'text',3,"");  ?>
	     </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$To56_elemento ?>" ><?=@$Lo56_elemento ?> </td>
	     <td nowrap >
	       <? db_input('o58_elemento',8,"",true,'text',3,"");  ?>
	       <? db_input('o56_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
	     <td nowrap >
	       <? db_input('o58_codigo',8,"",true,'text',3,"");  ?>
	       <? db_input('o15_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
	  <tr>
	   <td align='center' colspan='2'>
               <input name="confirmar" type="submit" id="db_opcao" value="Confirmar" onclick="return js_verificar('botao');" <?=($db_botao==false?"disabled":"")?> >
	       <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
	   </td>
	 </tr>
 <tr>
   <td colspan='2' align='center'>
<?  if(isset($e60_numemp)){
     $sql = $clempnotaele->sql_query(null,null,"e69_numero,nome,e69_dtnota,e70_codnota,sum(e70_valor) as e70_valor, sum(e70_vlrliq) as e70_vlrliq, sum(e70_vlranu) as    e70_vlranu","","e69_numemp=$e60_numemp  and e70_vlrliq <> 0  group by e70_codnota,nome,e69_dtnota,e69_numero ");
      $clempnotaele->sql_record($sql);
      $numrows_nota = $clempnotaele->numrows;
      if($numrows_nota >0){

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


	  $cliframe_seleciona->campos  = "e70_codnota,e69_numero,e69_dtnota,e70_valor,e70_vlrliq,e70_vlranu";
	  $cliframe_seleciona->sql = $sql;

          $sql_disabled = $clempnotaele->sql_query_ordem(null,null,"e70_codnota","","e69_numemp=$e60_numemp   and ((e71_codnota is  not  null  and e71_anulado='f') ) ");
	  $cliframe_seleciona->sql_disabled = $sql_disabled ;
	  $cliframe_seleciona->input_hidden = true;
	  $cliframe_seleciona->chaves ="e70_codnota";
	  $cliframe_seleciona->iframe_seleciona($db_opcao);
      }

     //rotina que monta campos com elementos e seus valores...
     if(isset($sql_disabled)){
       $sql = $clempnotaele->sql_query(null,null,"e70_codnota,e70_codele,e70_valor,e70_vlrliq,e70_vlranu","","e69_numemp=$e60_numemp  and e70_codnota not in ($sql_disabled)  ");
       $result = $clempnotaele->sql_record($sql);
       $numrows = $clempnotaele->numrows;
       if($numrows>0){
	 for($i=0; $i<$numrows; $i++ ){
	   db_fieldsmemory($result,$i);
	   echo "\n<input id='nota_$e70_codnota' name='nota_$e70_codnota' type='hidden' value='".$e70_codele."_".$e70_valor."'>";
	 }
       }
     }
   }


?>
   </td>
 </tr>
    </table>
     </td>
     <td valign='bottom'>
       <table cellspacing='0' cellpadding='0' class='bordas'>
<?
if(isset($restoapagar) && $restoapagar==true){
?>
	<tr class='bordas'>
	  <td  colspan='2' align='center'>
   	     <b style='color:red'>RESTO À PAGAR</b>
	    <!--<b>RESTO À PAGAR</b>-->
	  </td>
	</tr>
<?
  }
?>
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
	<tr>
	  <td class='bordas' nowrap title="<?=@$Te60_vlrliq?>">
	     <?=@$Le60_vlrliq?>
	  </td>
	  <td class='bordas'>
      <?
	db_input('e60_vlrliq',15,$Ie60_vlrliq,true,'text',3,'')
      ?>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="<?=@$Te60_vlrpag?>">
	     <?=@$Le60_vlrpag?>
	  </td>
	  <td class='bordas'>
      <?
	db_input('e60_vlrpag',15,$Ie60_vlrpag,true,'text',3,'')
      ?>
	  </td>
	</tr>
<?
 if(isset($e60_numemp)){
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap>
	    <b><small>NOTA</small></b>
	  </td>
	</tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te70_valor?>">
	       <?=@$Le70_valor?>
	    </td>
	    <td class='bordas'>
	<?
	  db_input('tot_valorn',15,$Ie70_valor,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te70_vlrliq?>">
	       <?=@$Le70_vlrliq?>
	    </td>
	    <td class='bordas'>
	<?
	  db_input('tot_vlrliqn',15,$Ie70_vlrliq,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te70_vlranu?>">
	       <?=@$Le70_vlranu?>
	    </td>
	    <td class='bordas'>
	<?
	  db_input('tot_vlranun',15,$Ie70_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>

<?

}
?>
<?
 if(isset($e60_numemp)){
   $result  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e60_numemp"));
   if($clpagordemele->numrows>0){
     db_fieldsmemory($result,0,true);
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>ORDEM</small></b>
	  </td>
	</tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	       <?=@$Le53_valor?>
	    </td>
	    <td class='bordas'>
	<?
	  db_input('tot_valor',15,$Ie60_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te53_vlrpag?>">
	       <?=@$Le53_vlrpag?>
	    </td>
	    <td class='bordas'>
	<?
	  db_input('tot_vlrpag',15,$Ie53_vlrpag,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te53_vlranu?>">
	       <?=@$Le53_vlranu?>
	    </td>
	    <td class='bordas'>
	<?
	  db_input('tot_vlranu',15,$Ie53_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>

<?
  }
}
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>SALDO</small></b>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="Valor que deseja anular">
	     <b>Total disponível:</b>
	  </td>
	  <td class='bordas'>
      <?
	db_input('vlrdis',15,0,true,'text',3);
      ?>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="Valor que deseja anular">
	     <b>Valor à estornar:</b>
	  </td>
	  <td class='bordas'>
      <?
	db_input('vlrliq_estornar',15,4,true,'text',$db_opcao,"onchange='js_verificar(\"campo\");'");
      ?>
	  </td>
	</tr>
      </table>
     </td>
     </tr>
     <tr>
  <td colspan='5' align='left'>
    <iframe name="elementos" id="elementos" src="forms/db_frmempliquidaestorna_elementos.php?jafoipago=<?=$jafoipago?>&db_opcao=<?=$db_opcao?>&e60_numemp=<?=@$e60_numemp?>" width="760" height="80" marginwidth="0" marginheight="0" frameborder="0">
    </iframe>
  </td>
 </tr>
 </table>
  </center>
</form>
<script>
<?
if(isset($e60_numemp)){
  if(($vlrdis==0||$vlrdis=='')&&($vlrdis_nota==0||$vlrdis_nota=='')){
      echo " document.form1.confirmar.disabled=true;\n";
     if(empty($confirmar)){
         echo "alert(\"Não existe valor liquidado disponível para ser estornado!\");\n";
     }
  }
?>



//============================================================================================
//função responsavel de pegar o valor da nota cliacada e colocar no campo para estornar


cont = new Number(0);
function js_calcula(campo){
     obj = document.form1.elements;
     soma_total  = new Number();


        if(campo.checked==true){
   	  if(cont == '0' ){
	    elementos.js_coloca('0.00',true,false);
	    document.form1.vlrliq_estornar.value = "0.00";
            document.form1.vlrliq_estornar.readOnly=true;
	    document.form1.vlrliq_estornar.style.backgroundColor = '#DEB887';
	    document.form1.vlrliq_estornar.value = '0.00';
          }
    	  cont++;
	}else{
	     cont--;
	    if(cont == '0' ){
	      document.form1.vlrliq_estornar.readOnly=false;
              document.form1.vlrliq_estornar.style.backgroundColor = 'white';
	      elementos.js_coloca('0.00',true,false);
   	      document.form1.vlrliq_estornar.value = '0.00';
	      return true;
	    }
        }


  //atualiza os valores

     eleval = '';
     vir='';
    nota = campo.value;
    soma_total =  new Number();
     for(w=0; w<obj.length; w++){
       nome = obj[w].name;


       if(nome == "nota_"+campo.value ){
	   eles = obj[w].value;
           arr_eles = eles.split("_");
	   elemento = arr_eles[0];
	   valor    = new Number(arr_eles[1]);
           eleval += vir+nota+"-"+elemento+"-"+valor;
           vir='#';
           soma_total += valor;
       }
     }

	  if(campo.checked==true){
              elementos.js_coloca_notas(eleval,true);
	  }else{
              elementos.js_coloca_notas(eleval,false);
	  }

	  //document.form1.vlrliq_estornar_nota.value =  soma_total;

//          elementos.js_coloca(soma_total,true);
//    elementos.js_calcular();
}



<?
  if(isset($jafoipago) && $jafoipago==true){
     if($vlrdis <  $vlrdis_nota){
       $saldon ='0.00';
     }else{
       $saldon = number_format(($vlrdis-$vlrdis_nota),2,".","");
     }
  }else{
     $saldon = number_format(($vlrdis),2,".","");

  }
?>


      function js_verificar(tipo){
        erro=false;

	  if(tipo=='botao'){
	    if(document.form1.vlrliq_estornar.value == '' || document.form1.vlrliq_estornar.value == 0 ){
	      alert('Informe o valor à ser estornado!');
	      return false;
	    }
	  }


	saldon = new Number('<?=$saldon?>');
	vlrliq_estornar= new Number(document.form1.vlrliq_estornar.value);
	if(isNaN(vlrliq_estornar)){
	  erro=true;
	}

	if(tipo=="campo"){
	  if(vlrliq_estornar > saldon ){
	    if(saldon==0){
	      alert('Não  há valor à estornar sem nota!');
	    }else{
	      alert('O valor máximo que poderia estornar sem nota é '+saldon+'.');
	    }
	   erro= true;
	  }
	}
	if(tipo == 'botao' && elementos.document.form1.verificador.value!="ok"){
	  erro=true;
	  erro_msg = elementos.document.form1.verificador.value;
	}

	if(erro==false){
  	  val = vlrliq_estornar.toFixed(2);
	  document.form1.vlrliq_estornar.value=val
	  if(tipo=='campo'){
	    elementos.js_coloca(val,false);
	  }else{
	    elementos.js_calcular();
	  }
<?if($numrows_nota>0){?>
	  js_gera_chaves();
<?}?>
	  return true;
	}else{
          document.form1.vlrliq_estornar.focus();
          document.form1.vlrliq_estornar.value="<?=$saldon?>";
	  elementos.js_coloca("<?=$saldon?>",false);
	  return false;
	}

      }
<?
}
?>

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp&empenhofolha=f','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?e60_numemp='+chave";
  ?>
}
</script>
<?/*
     //=====================================================>
     //rotina que calcula o total disponivel================>
     //=====================================================>

       $total_disponivel = $e60_vlrliq-$e60_vlrpag;

     //=====================================================>

     //=====================================================>
     //rotina que calcula o total disponivel sem nota ======>
     //=====================================================>

       $vlrdis =  $total_disponivel  ;

     //=====================================================>

*/?>