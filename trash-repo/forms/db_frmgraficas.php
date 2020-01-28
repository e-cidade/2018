<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: fiscal
$clgraficas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
?>
<center>
  <form name="form1" method="post" action="">
    <fieldset style="width:602px;margin-top:30px;">
      <legend>Gráficas:</legend>
    
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Ty20_grafica?>">
             <?
             db_ancora(@$Ly20_grafica,"js_pesquisay20_grafica(true);",$db_opcao);
             ?>
          </td>
          <td> 
            <?php db_input('y20_grafica',10,$Iy20_grafica,true,'text',$db_opcao," onchange='js_pesquisay20_grafica(false);'") ?>
            <?php db_input('z01_nome',40,$Iz01_nome,true,'text',3,'') ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Ty20_datalimiteimpressao?>">
             <?=@$Ly20_datalimiteimpressao?>
          </td>
          <td> 
            <?php db_inputdata('y20_datalimiteimpressao',@$y20_datalimiteimpressao_dia,@$y20_datalimiteimpressao_mes,@$y20_datalimiteimpressao_ano,true,'text',$db_opcao,"") ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <br />

    <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <?php if ( $db_opcao != 1 ) : ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php endif; ?>
  </form>
</center>
<script>
function js_pesquisay20_grafica(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.y20_grafica.value+'&funcao_js=parent.js_mostracgm';
  }
}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y20_grafica.focus(); 
    document.form1.y20_grafica.value = ''; 
  }
}

function js_mostracgm1(chave1,chave2){
  document.form1.y20_grafica.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}

function js_pesquisay20_id_usuario(mostra){

  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.y20_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}

function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y20_id_usuario.focus(); 
    document.form1.y20_id_usuario.value = ''; 
  }
}

function js_mostradb_usuarios1(chave1,chave2){

  document.form1.y20_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}

function js_pesquisa(){

  db_iframe.jan.location.href = 'func_graficas.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}

function js_preenchepesquisa(chave){

  db_iframe.hide();
  location.href = '<?php echo basename($_SERVER["PHP_SELF"]); ?>' + "?chavepesquisa=" + chave;
}
</script>

<?php
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>

<?php 
if( $db_opcao == 22 || $db_opcao == 33 ) {
  echo "<script>js_pesquisa();</script>";
}
?>