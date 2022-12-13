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

//MODULO: configuracoes
$cldb_paragrafo->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb02_idparag?>">
       <?=@$Ldb02_idparag?>
    </td>
    <td> 
<?
db_input('db02_idparag',8,$Idb02_idparag,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb02_descr?>">
       <?=@$Ldb02_descr?>
    </td>
    <td> 
<?
db_input('db02_descr',40,$Idb02_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb02_alinha?>">
       <?=@$Ldb02_alinha?>
    </td>
    <td> 
<?
$xw = array('0'=>"0",'5'=>"5 cm",'10'=>"10 cm",'15'=>"15 cm",'20'=>"20 cm",'25'=>"25 cm",'30'=>"30 cm",'35'=>"35 cm",'40'=>"40 cm",'45'=>"45 cm",'50'=>"50 cm",'55'=>"55 cm",'60'=>"60 cm",'65'=>"65 cm",'70'=>"70 cm",'75'=>"75 cm",'80'=>"80 cm");
db_select('db02_alinha',$xw,true,$db_opcao)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb02_inicia?>">
       <?=@$Ldb02_inicia?>
    </td>
    <td> 
<?
$xy = array('0'=>"0",'5'=>"5 cm",'10'=>"10 cm",'15'=>"15 cm",'20'=>"20 cm",'25'=>"25 cm",'30'=>"30 cm",'35'=>"35 cm",'40'=>"40 cm",'45'=>"45 cm",'50'=>"50 cm",'55'=>"55 cm",'60'=>"60 cm",'65'=>"65 cm",'70'=>"70 cm",'75'=>"75 cm",'80'=>"80 cm");
db_select('db02_inicia',$xy,true,$db_opcao)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb02_espaca?>">
       <?=@$Ldb02_espaca?>
    </td>
    <td> 
<?
$x = array('1'=>"1 cm",'2'=>"2 cm",'3'=>"3 cm",'4'=>"4 cm");
db_select('db02_espaca',$x,true,$db_opcao)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb02_texto?>" valign="top" >
       <?=@$Ldb02_texto?>
    </td>
    <td> 
<?
db_textarea('db02_texto',10,50,$Idb02_texto,true,'text',$db_opcao,"")
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
  db_iframe.jan.location.href = 'func_db_paragrafo.php?funcao_js=parent.js_preenchepesquisa|0';
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