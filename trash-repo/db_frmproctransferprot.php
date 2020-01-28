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

//MODULO: protocolo
$clproctransfer->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
?>
<form name="form1" id="cria" method="post" action="" onsubmit="return valida(this);">
<center>
<table border="0">
  <tr>
    <td nowrap title="Usuário">
      <b>Usuário:</b> 
    </td>
    <td> 
     <?
       $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
       echo pg_result(pg_exec($sql),0,"nome");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Usuário">
      <b>Departamento:</b> 
    </td>
    <td> 
     <?
       $sql = "select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto");
       echo pg_result(pg_exec($sql),0,"descrdepto");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp62_codtran?>">
       <?=@$Lp62_codtran?>
    </td>
    <td> 
<?
db_input('p62_codtran',0,$Ip62_codtran,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp62_dttran?>">
       <?=@$Lp62_dttran?>
    </td>
    <td> 
<?
db_inputdata('p62_dttran',@$p62_dttran_dia,@$p62_dttran_mes,@$p62_dttran_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
   <td colspan=3>
     <iframe name="campos" scrolling=no  width="400" height="75" frameborder=0 src="db_frmtransdepto.php"></iframe>
   </td>
  </tr>
    <tr>
       <td colspan=5>
          <table style="border:1px solid #999999" width="400" cellspacing =0> 
   <?
    $sql = "select  x.* from (
    		    select  p58_codproc,
                    z01_nome,
                    p51_descr,
                    p58_id_usuario,
                    coddepto,
                    descrdepto  
             from   protprocesso inner join tipoproc 
                    on p58_codigo = p51_codigo  
                    inner join andpadrao on p58_codigo = p53_codigo and p53_ordem = 1
                    inner join db_depart on p53_coddepto = coddepto 
                    inner join cgm on p58_numcgm = z01_numcgm
                    left join arqproc on arqproc.p68_codproc = protprocesso.p58_codproc
             where  (p58_id_usuario = ".db_getsession("DB_id_usuario")."
             or     p58_coddepto = ".db_getsession("DB_coddepto").") and p68_codproc is null) as x
	     left join proctransferproc on p63_codproc = p58_codproc where p63_codproc is null";
//             and    p58_codproc not in(select p63_codproc from proctransferproc)"; 
//    echo $sql;exit;          
    $rs = pg_exec($sql);
    $numrows = pg_num_rows($rs);
    if ($numrows > 0 ){
       
       echo "<tr><td colspan = 4 align='center'><b>Processosa Existentes</b></td></tr><tr> 
                <td bgcolor='#999999'></td>
                <td align='center' bgcolor='#999999'><b>Processo<b></td>
                <td align='center' bgcolor='#999999'><b>Requerente<b></td>
                <td align='center' bgcolor='#999999'><b>Tipo</b></td>
                <td align='center' bgcolor='#999999'><b></b>Depto Padrão</td>
             </tr>";      
       for ($i = 0;$i < $numrows;$i++){
           db_fieldsmemory($rs,$i);
           $class = $p58_id_usuario == db_getsession("DB_id_usuario")?"class='dono'":null;
           echo "<tr>
                    <td $class>
                      <input type='checkbox' name='processos[]' value='".$p58_codproc."'>
                    </td>
                    <td $class>".$p58_codproc."</td>
                    <td $class>".$z01_nome."</td>
                    <td $class>".$p51_descr."</td>
                    <td $class><a style='cursor:pointer' onclick=\"Envia_setor('$coddepto','$descrdepto');\">".$descrdepto."</a></td>
                 </tr>";
        }
    }else{
       $db_botao = false;        
       echo "<tr><td bgcolor='#999999'><b>Não existem processos</b></td></tr>";
    }

   ?>
     </table>
     </td>
     </tr>
    </table>
  </center>
<input name="db_opcao" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="cria_obj();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap62_id_usorec(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.p62_id_usorec.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.p62_id_usorec.focus(); 
    document.form1.p62_id_usorec.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.p62_id_usorec.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisap62_coddeptorec(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_depart.php?pesquisa_chave='+document.form1.p62_coddeptorec.value+'&funcao_js=parent.js_mostradb_depart';
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.p62_coddeptorec.focus(); 
    document.form1.p62_coddeptorec.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.p62_coddeptorec.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_proctransfer.php?funcao_js=parent.js_preenchepesquisa|0';
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