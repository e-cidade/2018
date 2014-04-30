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
$clorcdotacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o54_anousu");
$clrotulo->label("o55_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o56_descr");
$clrotulo->label("o15_descr");
$clrotulo->label("o61_codigo");
$clrotulo->label("DB_txtdotacao");


?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To58_anousu?>">
       <?
       echo $Lo58_anousu;
       ?>
    </td>
    <td> 
<?
$o58_instit = db_getsession('DB_instit');
db_input('o58_instit',4,$Io58_instit,true,'hidden',3,"")
?>
<?
$o58_anousu = db_getsession('DB_anousu');
db_input('o58_anousu',4,$Io58_anousu,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Percentual de correção">
       <?
       echo "<strong>Percentual:</strong>";
       ?>
    </td>
    <td> 

<?
db_input('percentual',4,0,true,'text',2,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To58_orgao?>">
       <?
       echo $Lo58_orgao;
       ?>
    </td>
    <td> 
<?
$matr = array();
$clorcorgao = new cl_orcorgao;
$result = $clorcorgao->sql_record($clorcorgao->sql_query(null,null,"*","o40_orgao"," o40_anousu = ".db_getsession("DB_anousu")." and o40_instit = ".db_getsession("DB_instit")));
for($i=0;$i<$clorcorgao->numrows;$i++){
  db_fieldsmemory($result,$i);
  $matr[$o40_orgao] = $o40_orgao;
}
db_select('o58_orgao',$matr,true,'text',2,"")
?>
    </td>
  </tr>



  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>