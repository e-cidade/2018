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
$cldb_paragrafopadrao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb61_codparag?>">
       <?=@$Ldb61_codparag?>
    </td>
    <td> 
<?
db_input('db61_codparag',8,$Idb61_codparag,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb61_descr?>">
       <?=@$Ldb61_descr?>
    </td>
    <td> 
<?
db_input('db61_descr',40,$Idb61_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
 
		<?
		$db61_alinha = 0;
		db_input('db61_alinha',20,$Idb61_alinha,true,'hidden',3);
		?>
   
  <tr>
    <td nowrap title="<?=@$Tdb61_alinhamento?>">
       <?=@$Ldb61_alinhamento?>
    </td>
    <td> 
    <?
      $xw = array('J'=>"Justificado",'C'=>"Centralizado",'R'=>"Direita",'L'=>"Esquerda");
      db_select('db61_alinhamento',$xw,true,$db_opcao,"OnChange = js_alinha();")
    ?>
    </td>
  </tr>  
  
  <tr>
    <td nowrap title="<?=@$Tdb61_largura?>">
       <?=@$Ldb61_largura?>
    </td>
    <td> 
    <?
      db_input('db61_largura',10,$Idb61_largura,true,'text',$db_opcao,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb61_altura?>">
       <?=@$Ldb61_altura?>
    </td>
    <td> 
    <?
      db_input('db61_altura',10,$Idb61_altura,true,'text',$db_opcao,"");
    ?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tdb61_inicia?>">
       <?=@$Ldb61_inicia?>
    </td>
    <td> 
<?
$xy = array('0'=>"0",'5'=>"5 cm",'10'=>"10 cm",'15'=>"15 cm",'20'=>"20 cm",'25'=>"25 cm",'30'=>"30 cm",'35'=>"35 cm",'40'=>"40 cm",'45'=>"45 cm",'50'=>"50 cm",'55'=>"55 cm",'60'=>"60 cm",'65'=>"65 cm",'70'=>"70 cm",'75'=>"75 cm",'80'=>"80 cm");
db_select('db61_inicia',$xy,true,$db_opcao)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb61_espaco?>">
       <?=@$Ldb61_espaco?>
    </td>
    <td> 
<?
$x = array('1'=>"1 cm",'2'=>"2 cm",'3'=>"3 cm",'4'=>"4 cm");
db_select('db61_espaco',$x,true,$db_opcao)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb61_tipo?>">
       <?=@$Ldb61_tipo?>
    </td>
    <td> 
    <?
      $aTtipos = array('1'=>"Texto puro",'2'=>"Tabela Simples",'3'=>"Codigo PHP");
      db_select('db61_tipo',$aTtipos,true,$db_opcao,"")
    ?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tdb61_texto?>" valign="top" >
       <?=@$Ldb61_texto?>
    </td>
    <td> 
<?
db_textarea('db61_texto',30,100,$Idb61_texto,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_db_paragrafopadraopadrao.php?funcao_js=parent.js_preenchepesquisa|0';
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