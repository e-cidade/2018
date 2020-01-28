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

$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");

if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{  
  $db_opcao = 1;
} 

if (isset($flag_almox) && trim(@$flag_almox) != ""){
     if ($flag_almox == "false"){
          $lib      = 4;
          $db_botao = false;
     } else {
          $lib = $db_opcao;
     }
}

if ($db_opcao != 1 && $lib != 4){
     $res_db_almox = $cldb_almox->sql_record($cldb_almox->sql_query_file(null,"m91_codigo as codigo",null,"m91_depto = ".db_getsession("DB_coddepto")));
     if($cldb_almox->numrows > 0){
         db_fieldsmemory($res_db_almox,0);

         if($codigo != $m64_almox){
             $db_botao = false;
             db_msgbox("Material pertence a outro deposito. Nao pode ser alterado e excluido.");
             echo "<script>
               parent.iframe_matmaterestoque.location.href='mat1_matmaterestoque001.php?m64_matmater=".@$m64_matmater."';\n
             </script>";
         }
     }
}
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
<br><br>
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
	    <td nowrap align="right" title="<?=@$Tm64_matmater?>"><?=@$Lm64_matmater?></td>
      <td>
        <?
           $result_descr=$clmatmater->sql_record($clmatmater->sql_query_file($m64_matmater,"m60_descr"));
           if ($clmatmater->numrows>0){
                db_fieldsmemory($result_descr,0);
           }

           db_input("flag_almox",    10,0,true,"hidden",3);
           db_input("m64_sequencial",10,0,true,"hidden",3);
           db_input("m64_matmater",  10,0,true,"text",  3);
           db_input("m64_almox",      6,0,true,"hidden",3);
           db_input("m60_descr",     40,0,true,"text",  3);
        ?>
      </td>
    </tr>
    <tr>
	    <td nowrap align="right" title="<?=@$Tm64_estoqueminimo?>"><?=@$Lm64_estoqueminimo?></td>
      <td>
        <?
           db_input("m64_estoqueminimo",15,@$Im64_estoqueminimo,true,"text",$db_opcao);
        ?>
      </td>
    </tr>
    <tr>
	    <td nowrap align="right" title="<?=@$Tm64_estoquemaximo?>"><?=@$Lm64_estoquemaximo?></td>
      <td>
        <?
           db_input("m64_estoquemaximo",15,@$Im64_estoquemaximo,true,"text",$db_opcao);
        ?>
      </td>
    </tr>
    <tr>
	    <td nowrap align="right" title="<?=@$Tm64_pontopedido?>"><?=@$Lm64_pontopedido?></td>
      <td>
        <?
           db_input("m64_pontopedido",15,@$Im64_pontopedido,true,"text",$db_opcao);
        ?>
      </td>
    </tr>
     <tr>
      <td nowrap align="right" title="<?=@$Tm64_localizacao ?>"><?=@$Lm64_localizacao?></td>
      <td>
        <?
           db_input("m64_localizacao",15,@$Im64_localizacao,true,"text",$db_opcao);
        ?>
      </td>
    </tr>
	  <tr>
	    <td colspan="2" align="center">
	      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onClick="return js_verifica_dados();">
        <?
           if($db_opcao != 1){
        ?>
           <input name="novo" type="submit" id="novo" value="Novo">
        <?
           }
        ?>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2"> 
      <?
     $chavepri= array("m64_sequencial"=>@$m64_sequencial,"m64_matmater"=>@$m64_matmater);

     $cliframe_alterar_excluir->chavepri=$chavepri;

     if (isset($m64_matmater) && trim(@$m64_matmater) != ""){  
          $cliframe_alterar_excluir->sql = $clmatmaterestoque->sql_query(null,"*","m91_depto","m64_matmater=$m64_matmater");
     }

      $cliframe_alterar_excluir->campos  = "coddepto,m64_estoqueminimo,m64_estoquemaximo,m64_pontopedido,m64_localizacao";
      $cliframe_alterar_excluir->legenda = " Material Estoque ";
      $cliframe_alterar_excluir->msg_vazio ="Nao foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="darkblue";
      $cliframe_alterar_excluir->textocorpo ="black";
      $cliframe_alterar_excluir->fundocabec ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
      $cliframe_alterar_excluir->iframe_width ="710";
      $cliframe_alterar_excluir->iframe_height ="130";

      $cliframe_alterar_excluir->opcoes = @$lib;   
      $cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);   
    ?>
   </td>
 </tr>
  </table>
</form>     
<script>
function js_verifica_dados(){
  var obj = eval("document.form1");

  if (obj.m64_estoqueminimo.value == ""){
       alert("Preencher estoque minimo.");
       obj.m64_estoqueminimo.focus();
       obj.m64_estoqueminimo.select();
       return false;
  }
  
  if (obj.m64_estoquemaximo.value == ""){
       alert("Preencher estoque maximo.");
       obj.m64_estoquemaximo.focus();
       obj.m64_estoquemaximo.select();
       return false;
  }
  
  if (obj.m64_pontopedido.value == ""){
       alert("Preencher ponto de pedido.");
       obj.m64_pontopedido.focus();
       obj.m64_pontopedido.select();
       return false;
  }
}
</script>