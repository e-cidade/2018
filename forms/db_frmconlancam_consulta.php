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
$clconlancamcompl->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c50_descr");
$clrotulo->label("c70_anousu");
//$clrotulo->label("c70_codlan");
$clrotulo->label("c69_valor");

?>
<script>
 function critica_form(){
      if (document.form1.codigo.value !="") {
         document.form1.submit();
      } else {
         alert('Informação faltando ');

      }  
 }  
 function js_emite(){
   obj = document.form1;
   dt1 = obj.data_ini_dia.value+'/'+obj.data_ini_mes.value+'/'+obj.data_ini_ano.value;
   dt2 = obj.data_fim_dia.value+'/'+obj.data_fim_mes.value+'/'+obj.data_fim_ano.value;
   jan = window.open('con1_conlancam_rel1001.php?sql=<?=@base64_encode($res_sql)?>&codigo='+document.form1.codigo.value+'&pesquisa=<?=@$pesquisa?>&dt1='+dt1+'&dt2='+dt2,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
 }  
</script>
<br>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap > 
    <? switch ($pesquisa){
          case 0: db_ancora("Codigo do Lançamento",'js_pesquisa();',1); break;
          case 1: db_ancora("Numero do Lote/Chave",'js_pesquisa();',1); break;
	  case 2: db_ancora("Codigo do Lançamento",'js_pesquisa();',1); break;
	  case 3: db_ancora("Codigo da Suplementação",'js_pesquisa();',1); break;
	  case 4: db_ancora("Codigo da Receita",'js_pesquisa();',1); break;
          case 5: db_ancora("Codigo da Dotação",'js_pesquisa();',1); break;
  	  case 6: db_ancora("Codigo do Empenho",'js_pesquisa();',1); break;
	  case 7: db_ancora("Codigo do Documento",'js_pesquisa();',1); break;
	  case 8: db_ancora("Codigo do CGM",'js_pesquisa();',1); break;
       }	
       
    ?></td>
    <td><?  db_input("codigo",12,"",true,'text',1);   ?>
    </td>
  </tr>

  <tr>
  <td nowrap>  Período   </td>
      <td><? db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
          à
	  <? db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  ?>

      </td>
  </tr>
  
  </table>
  </center>
  <input name="db_opcao" type="submit" id="db_opcao" value="Consultar" <?=($db_botao==false?"disabled":"")?>  onclick="critica_form(); ">
 
  <!--
  <input name="impressao" type="button" value="Impressão" onclick="js_emite();" >   
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  
  </form>
 -->
<script>
function js_pesquisa(){
  <?
   switch ($pesquisa){
     case 0:
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamlan','func_conlancamlan.php?funcao_js=parent.js_preenchepesquisa|c70_codlan','Pesquisa',true);";
         break;
     case 1:
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdig','func_conlancamdig.php?funcao_js=parent.js_preenchepesquisa|c78_chave','Pesquisa',true);";
         break;
     case 2:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamcompl','func_conlancamcompl.php?funcao_js=parent.js_preenchepesquisa|c72_codlan','Pesquisa',true);";
	 break;
     case 3:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamsup','func_conlancamsup.php?funcao_js=parent.js_preenchepesquisa|c79_codsup','Pesquisa',true);";
	 break;
     case 4:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamrec','func_conlancamrec.php?funcao_js=parent.js_preenchepesquisa|c74_codrec','Pesquisa',true);";
	 break;
     case 5:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdot','func_conlancamdot.php?funcao_js=parent.js_preenchepesquisa|c73_coddot','Pesquisa',true);";
	 break;
     case 6:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamemp','func_conlancamemp.php?funcao_js=parent.js_preenchepesquisa|c75_numemp','Pesquisa',true);";
	 break;
     case 7:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdoc','func_conlancamdoc.php?funcao_js=parent.js_preenchepesquisa|c53_coddoc','Pesquisa',true);";
	 break;
     case 8:	 
         echo "js_OpenJanelaIframe('top.corpo','db_iframe_conlancamcgm','func_conlancamcgm.php?funcao_js=parent.js_preenchepesquisa|z01_numcgm','Pesquisa',true);";
	 break;
   }  
  ?>
}
function js_preenchepesquisa(chave){
  <?
    switch ($pesquisa){
       case 0: echo "db_iframe_conlancamlan.hide();"; break;
       case 1: echo "db_iframe_conlancamdig.hide();"; break;
       case 2: echo "db_iframe_conlancamcompl.hide();"; break;
       case 3: echo "db_iframe_conlancamsup.hide();";  break;
       case 4: echo "db_iframe_conlancamrec.hide();"; break;
       case 5: echo "db_iframe_conlancamdot.hide();"; break;
       case 6: echo "db_iframe_conlancamemp.hide();"; break;
       case 7: echo "db_iframe_conlancamdoc.hide();"; break;
       case 8: echo "db_iframe_conlancamcgm.hide();"; break;
    }
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
</script>