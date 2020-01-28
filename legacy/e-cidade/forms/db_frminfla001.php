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

//MODULO: contrib
$clinfla->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_codigo");
$clrotulo->label("i01_descr");
$clrotulo->label("i01_dm");
?>
<form name="form1" method="post" action="">
<center>
    <table border="0">
      <tr> 
        <td nowrap title="<?=@$Ti02_codigo?>"> 
          <?
         db_ancora(@$Li01_codigo,'js_pesquisainflan(true);',2);
         ?>
        </td>
        <td> 
          <?
          db_input('i02_codigo',5,$Ii02_codigo,true,'text',2," onchange='js_pesquisainflan(false);'")
          ?>
          <?
          db_input('i01_descr',40,$Ii01_descr,true,'text',3)
          ?>
        </td>
      </tr>
      <tr> 
        <td nowrap >
          <?
          db_input('i01_dm',4,$Ii01_dm,true,'hidden',3)
          ?>
        </td>
        <td><input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar" ></td>
      </tr>
    </table>
  </center>
</form>
<script>
function js_pesquisainflan(mostra){
  if(mostra==true){
    func_inflan.jan.location.href = 'func_inflan.php?funcao_js=parent.js_manutencao|0|1|3';
    func_inflan.mostraMsg();
    func_inflan.show();
    func_inflan.focus(); 
  }else{
    func_inflan.jan.location.href = 'func_inflan.php?pesquisa_chave='+document.form1.i02_codigo.value+'&funcao_js=parent.js_manutencao1';
  }
}
function js_manutencao(chave,chave1,chave2){
  document.form1.i02_codigo.value = chave;
  document.form1.i01_descr.value = chave1;
  document.form1.i01_dm.value = chave2;
  func_inflan.hide();   
}
function js_manutencao1(chave,chave1){
  document.form1.i01_descr.value = chave;
  document.form1.i01_dm.value = chave1;
}
</script>
<?
$inflan = new janela("func_inflan","");
$inflan ->posX=1;
$inflan ->posY=20;
$inflan ->largura=785;
$inflan ->altura=430;
$inflan ->titulo="Pesquisa Inflatores";
$inflan ->iniciarVisivel = false;
$inflan ->mostrar();
?>