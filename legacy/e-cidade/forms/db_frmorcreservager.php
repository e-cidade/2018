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
$clorcreservager->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o80_descr");

?>
<script>
  function js_atualiza(){
     document.form1.submit();
  } 

</script>
<form name="form1" method="post" action="">
<center>
<br><br><br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To84_codres?>"> Orgão : 
    </td>
    <td> 
     <?  $res= $clorcorgao->sql_record($clorcorgao->sql_query_file(db_getsession('DB_anousu'),"","o40_orgao,o40_descr")); 
         db_selectrecord("orgao",$res,"",1,"","","","0","js_atualiza();");	
	
      ?>    
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To84_codres?>"> Unidade :   </td>
    <td> 
     <? if (isset($orgao)) 
        {
             $res= $clorcunidade->sql_record($clorcunidade->sql_query_file(db_getsession('DB_anousu'),
	                                      "$orgao","","o41_unidade,o41_descr")); 
	     db_selectrecord("unidade",$res,"",1,"","","","0","js_atualiza();");	
     
         } else {
           echo "<script> js_atualiza(); </script>"; 
	 }
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To84_codres?>"> Elemento :   </td>
    <td> 
     <? 
         if (isset($orgao) and $orgao=="0"){
              $res= $clorcelemento->sql_record($clorcelemento->sql_query_file("",db_getsession("DB_anousu"),"o56_codele,o56_descr","o56_descr","")); 
	      db_selectrecord("elemento",$res,"",1,"","","","0" );	  
         } else  if (isset($unidade) and $unidade =="0"){
	      $res= $clorcelemento->sql_record($clorcelemento->sql_query_file("",db_getsession("DB_anousu"),"o56_codele,o56_descr","o56_descr",
	                                                          "o56_codele in (select o58_codele from orcdotacao
				       	                          where o58_orgao=$orgao and o58_anousu = ".db_getsession("DB_anousu")." )")); 
	      db_selectrecord("elemento",$res,"",1,"","","","0" );	
	 } else {
              @$res= $clorcelemento->sql_record($clorcelemento->sql_query_file("",db_getsession("DB_anousu"),"o56_codele,o56_descr","o56_descr",
	                                                          "o56_codele in (select o58_codele from orcdotacao
				       	                          where o58_anousu = ".db_getsession("DB_anousu")." and o58_orgao=$orgao and o58_unidade=$unidade)")); 
	      @db_selectrecord("elemento",$res,"",1,"","","","0" );	
	 }  
      ?>
    </td>
  </tr>
  <? if ($db_opcao == "1") {   ?>
      <tr>
         <td nowrap title="<?=@$To84_codres?>"> Tipo  :   </td>
         <td> 
           <?
              $matr["M"]="Mensal ";
              $matr["B"]="Bimestral ";
              $matr["T"]="Trimestral";
              $matr["Q"]="Quadrimestral ";
              $matr["S"]="Semestral";
              db_select("tipo_reserva",$matr,"true",1);  
            ?>
         </td>
      </tr>
  <?   }   ?>  

  </table>
  </center>
  <br><br><br>
  <!---  
  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
   --->
  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Processar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
 
 <!---
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  ---> 
</form>

<script>

</script>