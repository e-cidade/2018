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

 //MODULO: caixa
 $clsaltes->rotulo->label();

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
        <td nowrap title="<?=@$Tk13_conta?>"> <?=@$Lk13_conta?> </td>
        <td><? db_input('k13_conta',8,$Ik13_conta,true,'text',3);    ?>
    </td>
  </tr>

  <tr>
        <td nowrap title="<?=@$Tk13_descr?>">
	  <? 
	      if (isset($c01_reduz) && ($c01_reduz =="")){
 	           db_ancora(@$Lk13_descr,"js_contas();",1 );
	      } else {
                   db_ancora(@$Lk13_descr,"js_contas();",3 );
              } 
	       
	        ?></td>
        <td>
	 <?  
   	    if (isset($c01_reduz) && ($c01_reduz =="")){
	        db_input('c01_reduz',8,"",true,'text',1);	 
                echo "Conta não liberada no exercício ! ";

	    } else {
                db_input('c01_reduz',8,"",true,'text',3);	
		echo "Conta já liberada  no exercício ! ";
	    }  
            echo "<br>" ;
	    db_input('k13_descr',40,$Ik13_descr,true,'text',3,""); ?>
   </td>
  </tr>
  
  <tr>
        <td nowrap title="<?=@$Tk13_saldo?>"><?=@$Lk13_saldo?> </td>
        <td><? db_input('k13_saldo',15,$Ik13_saldo,true,'text',3,"") ?>
    </td>
  </tr>
  <tr>
        <td nowrap title="<?=@$Tk13_ident?>"><?=@$Lk13_ident?> </td>
        <td><? db_input('k13_ident',15,$Ik13_ident,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
        <td nowrap title="<?=@$Tk13_vlratu?>"><?=@$Lk13_vlratu?></td>
        <td><? db_input('k13_vlratu',15,$Ik13_vlratu,true,'text',3,"") ?>
    </td>
  </tr>
  <tr>
        <td nowrap title="<?=@$Tk13_datvlr?>"> <?=@$Lk13_datvlr?> </td>
        <td><? 
	
  	       @list($k13_datvlr_dia,$k13_datvlr_mes,$k13_datvlr_ano)= split("/",$k13_datvlr);
	      db_inputdata('k13_datvlr',@$k13_datvlr_dia,@$k13_datvlr_mes,@$k13_datvlr_ano,true,'text',3,""); 
	      
	      ?>
   </td>
  </tr>
 </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_preenche|k13_conta','Pesquisa',true);
}
function js_preenche(chave){
   db_iframe_saltes.hide();
   <?
//   if($db_opcao!=1){
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
//   }
   ?>
}
function js_contas(){
  js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes_contas001.php?funcao_js=parent.js_preenche_conta|c62_reduz|c60_descr','Pesquisa',true);
}
function js_preenche_conta(chave1,chave2){
    db_iframe_saltes.hide();
    document.form1.c01_reduz.value=chave1;
    document.form1.k13_descr.value=chave2;
}
</script>