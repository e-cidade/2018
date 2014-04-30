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

//MODULO: inflatores
$clinflan->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ti01_codigo?>">
       <?=@$Li01_codigo?>
    </td>
    <td> 
       <?
	if ( $db_opcao == 2 )
   	   $opcaotipo = 3;
	else
	   $opcaotipo = $db_opcao;
	db_input('i01_codigo',5,$Ii01_codigo,true,'text',$opcaotipo,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_descr?>">
       <?=@$Li01_descr?>
    </td>
    <td> 
<?
db_input('i01_descr',40,$Ii01_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_dm?>">
       <?=@$Li01_dm?>
    </td>
    <td> 
    <?
      $x = array("1"=>"DIÁRIO","0"=>"MENSAL");
      db_select('i01_dm',$x,true,4,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_tipo?>">
       <?=@$Li01_tipo?>
    </td>
    <td> 
<?
   $rec = array("1"=>"Divide valor pelo índice da data base e multiplica pelo índice da data atual","2"=>"Aplica índice mês a mês sobre o valor","3"=>"Aplica índice do mês do vencimento sobre o valor","9"=>"Sem correção ( Moeda Corrente Nacional )"); 
   db_select("i01_tipo",$rec,true,4,"");

//db_input('i01_tipo',1,$Ii01_tipo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_inflan.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>