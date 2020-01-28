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

//MODULO: saude
$clundmedhorario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed32_i_codigo");
$clrotulo->label("sd04_i_medico");
?>
<form name="form1" method="post" action="">
<center>
<table border="1" cellpadding="0" cellspacing="0" width="100%">
 <?
  $sql = "select distinct sd03_i_codigo,z01_numcgm,z01_nome,sd04_i_codigo
          from unidademedicos
           inner join medicos on sd03_i_codigo = sd04_i_medico
           inner join unidades on sd02_i_codigo = sd04_i_unidade
           inner join cgm on z01_numcgm = sd03_i_cgm
          where sd04_i_unidade = $sd04_i_unidade
          order by z01_nome asc
         ";
  $result = $clmedicos->sql_record($sql);
  if($clmedicos->numrows > 0){
 ?>
  <?$bg = "#E8E8E8";
    echo "<tr bgcolor='#b0b0b0'>";
    for($u=0; $u< $clmedicos->numrows; $u++){
     db_fieldsmemory($result,$u);
     echo "<td>".$sd03_i_codigo."</td><td><a href='#' onclick='js_selmedico($sd04_i_unidade,$sd03_i_codigo,\"$z01_nome\",$sd04_i_codigo)'>".$z01_nome."</a></td>";
      @$coluna = $coluna + 1;
      if ($coluna>1)
        {
         echo "<tr>";
         echo "<tr bgcolor='$bg'>";
         if($bg == "#E8E8E8"){
          $bg = "#B0B0B0";
         }else{
          $bg = "#E8E8E8";
         }
         $coluna = 0;
        }
    }
    }else{
     echo "<tr><td class='texto'>Médicos não cadastradas</td></tr>";
    }
  ?>
 </table>
</center>
</form>
<script>
function js_selmedico(unidade,medico,z01_nome,undmed){
 parent.mo_camada('a3');
 parent.document.formaba.a3.disabled = false;
 parent.iframe_a3.document.location.href='sau1_undmedhorario005.php?sd04_i_unidade='+unidade+'&sd04_i_medico='+medico+'&z01_nome='+z01_nome+'&sd30_i_undmed='+undmed;
}
function js_pesquisasd30_i_diasemana(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_diasemana','func_diasemana.php?funcao_js=parent.js_mostradiasemana1|ed32_i_codigo|ed32_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_diasemana.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_diasemana','func_diasemana.php?pesquisa_chave='+document.form1.sd30_i_diasemana.value+'&funcao_js=parent.js_mostradiasemana','Pesquisa',false);
     }else{
       document.form1.ed32_i_codigo.value = ''; 
     }
  }
}
function js_mostradiasemana(chave,erro){
  document.form1.ed32_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_diasemana.focus(); 
    document.form1.sd30_i_diasemana.value = ''; 
  }
}
function js_mostradiasemana1(chave1,chave2){
  document.form1.sd30_i_diasemana.value = chave1;
  document.form1.ed32_i_codigo.value = chave2;
  db_iframe_diasemana.hide();
}
function js_pesquisasd30_i_undmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostraunidademedicos1|sd04_i_codigo|sd04_i_medico','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_undmed.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd30_i_undmed.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
     }else{
       document.form1.sd04_i_medico.value = ''; 
     }
  }
}
function js_mostraunidademedicos(chave,erro){
  document.form1.sd04_i_medico.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_undmed.focus(); 
    document.form1.sd30_i_undmed.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd30_i_undmed.value = chave1;
  document.form1.sd04_i_medico.value = chave2;
  db_iframe_unidademedicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_undmedhorario','func_undmedhorario.php?funcao_js=parent.js_preenchepesquisa|sd30_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_undmedhorario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function mascara_hora(hora,x){
 var myhora = '';
 myhora = myhora + hora;
 if (myhora.length == 2){
  myhora = myhora + ':';
  document.form[x].value = myhora;
 }
 if (myhora.length == 5){
  verifica_hora(x);
 }
}

function verifica_hora(x){
 hrs = (document.form[x].value.substring(0,2));
 min = (document.form[x].value.substring(3,5));
 situacao = "";
// verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) )
  {
   alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
   document.form[x].value="";
   document.form[x].focus();
  }
}

</script>