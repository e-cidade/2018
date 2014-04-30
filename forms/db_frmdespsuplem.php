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
$clorcsuplemlan->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o39_codproj");
$clrotulo->label("nome");

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To49_codsup?>">
       <? db_ancora(@$Lo39_codproj,"js_pesquisao39_codproj(true);",$db_opcao);   ?>
    </td>
    <td> 
       <? db_input('o39_codproj',4,'',true,'text',3,'') ?>
       <? db_input('o39_descr',60,'',true,'text',3,'')      ?>
    </td>
  </tr>

  <tr>
   <td nowrap colspan=2>* O Desprocessamento sempre implicará na abertura do Projeto</td>
  </tr> 
     
  <!--  -->
  <tr>
   <td colspan=2 align=center>
    <?
      if (isset($o39_codproj) && $o39_codproj !=""){
       $sql = " select o46_codsup,
                               o48_descr,
		                       sum(case when o47_valor > 0  then o47_valor else 0 end)  as suplementado,
		                       sum(case when o47_valor < 0  then o47_valor else 0 end) as reduzido	   
                     from orcsuplem
		                     inner join orcsuplemtipo on o48_tiposup = o46_tiposup
		                     inner join orcsuplemval  on o47_codsup=o46_codsup   
		                     inner join orcsuplemlan on o49_codsup = o46_codsup
		             where o46_codlei = $o39_codproj  and o49_codsup is not null
		             group by o46_codlei,o46_codsup,o48_descr
		             order by o46_codlei    
  	      ";
  	     // se este projeto é retificador, todas as suplementações devem ser desprocessadas  
  	    $sql_retificador= "select *
                                     from orcsuplemretif 
                                     where o48_projeto = $o39_codproj";
        $result = pg_exec($sql_retificador);                 
  	    if (pg_numrows($result)>0){
  	    	  $db_opcao=3;              
              $sql_marca = "  select o46_codsup,
					                               o48_descr,
							                       sum(case when o47_valor > 0  then o47_valor else 0 end)  as suplementado,
							                       sum(case when o47_valor < 0  then o47_valor else 0 end) as reduzido	   
					                     from orcsuplem
							                     inner join orcsuplemtipo on o48_tiposup = o46_tiposup
							                     inner join orcsuplemval  on o47_codsup=o46_codsup   
							                     inner join orcsuplemlan on o49_codsup = o46_codsup
							             where o46_codlei = $o39_codproj  and o49_codsup is not null
							             group by o46_codlei,o46_codsup,o48_descr
							             order by o46_codlei    
					  	            ";
  	    } else {
  	     	  $sql_marca="";
  	    }	        	      
        $cliframe_seleciona->campos  = "o46_codsup,o48_descr,reduzido,suplementado";
        $cliframe_seleciona->legenda="Suplementações Processadas";
        $cliframe_seleciona->sql=$sql;	   
        $cliframe_seleciona->sql_marca=$sql_marca;
        $cliframe_seleciona->iframe_height ="250";
        $cliframe_seleciona->iframe_width ="700";
        $cliframe_seleciona->iframe_nome ="suplementacoes"; 
        $cliframe_seleciona->chaves ="o46_codsup";
        $cliframe_seleciona->iframe_seleciona($db_opcao);    
      }
     ?>
   </td>
   </tr>
 
   </table>
  </center>

  <? if (isset($o39_codproj) && $o39_codproj !=""){  ?>
   <input name=Desprocessar type=submit value=Desprocessar onclick="js_gera_chaves();">
 <?  }  ?>

 </form>
<script>
function js_pesquisao39_codproj(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojetodesprocessa.php?funcao_js=parent.js_mostraprojeto|o39_codproj','Pesquisa',true);
  }
}
function js_mostraprojeto(chave,erro){
   <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave; ";
   ?>
   db_iframe_orcprojeto.hide();
}
</script>