<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%" height="95%">
  <tr align="center">
    <td nowrap  width="100%" height="100%"><br><br>
      <iframe name="iframe_solicitem" id="solicitem" marginwidth="0" marginheight="0" frameborder="0" src="com1_gerautautiframe001.php"  width="95%" height="400"></iframe>
      <?
        db_input('pc80_codproc',8,$Ipc80_codproc,true,'hidden',3);
        db_input('e54_codcom',8,0,true,'hidden',3);
        db_input('e54_codtipo',8,0,true,'hidden',3);
	      db_input('e54_praent',30,$Ie54_praent,true,'hidden',3,"");
		    db_input('e54_entpar',30,$Ie54_entpar,true,'hidden',3,"");
		    db_input('e54_conpag',30,$Ie54_conpag,true,'hidden',3,"");
		    db_input('e54_codout',30,$Ie54_codout,true,'hidden',3,"");
	  	  db_input('e54_contat',20,$Ie54_contat,true,'hidden',3,"");
  		  db_input('e54_telef',20,$Ie54_telef,true,'hidden',3,"");
      ?>
    </td>
  </tr>
  <tr align="center">
    <td nowrap height="10%">
      <?
      $botao = "Gerar autorização";
      $click = "js_enviarcampos();";
      $click2= "document.location.href='com1_selproc001.php'";
      if (isset($anul)) {
        $botao = "Anular autorização";
	      $click2= "document.location.href='com1_selproc001.php?anul=true'";
      }
      ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=$botao?>" <?=($db_botao==false?"disabled":"")?> onclick="<?=$click?>" >
      <input name="voltar" type="button" id="db_opcao" value="Selecionar PC" onclick="<?=$click2?>" >
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_enviarcampos(){
  vir = "";
  erro = 0;
  document.form1.<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>.disabled = true;
  x = iframe_solicitem.document.form1;
  x.valores.value = "";
  for (i=0;i<x.length;i++) {
    if (x.elements[i].type=='checkbox') {
      if (x.elements[i].checked==true) {
      	x.valores.value += vir+x.elements[i].name;
	      vir = ",";
      }
    }
  }
  if (x.valores.value!="") {
    obj=iframe_solicitem.document.createElement('input');
    obj.setAttribute('name','incluir');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','incluir');
    iframe_solicitem.document.form1.appendChild(obj);
<?
    if (isset($anul) && $anul == true){
?>
    var op = document.createElement("input");
    op.setAttribute("type","hidden");
    op.setAttribute("name","reservar");
<?
         $res_sol     = $clempautoriza->sql_record($clempautoriza->sql_query_solicita($e54_autori));
         $numrows_sol = $clempautoriza->numrows;
         if ($numrows_sol > 0){

					 $rsPcparam = $clpcparam->sql_record($clpcparam->sql_query(db_getsession('DB_instit'),"pc30_gerareserva"));
					 if($clpcparam->numrows > 0){
						 db_fieldsmemory($rsPcparam,0);
						 $lGeraReserva = $pc30_gerareserva;
					 }else{
						 $lGeraReserva = "t";
					 }

					 if ($lGeraReserva == "f"){
?>
						op.setAttribute("value","false");
<?
					 }else{
?>
						 if (confirm("Recriar reservas de solicitacao de compras?")){
								 op.setAttribute("value","true");
						 } else {
								 op.setAttribute("value","false");
						 }
<?
           }
				 } else {
?>
         op.setAttribute("value","true");
<?
         }
?>
    iframe_solicitem.document.form1.appendChild(op);
<?
    }
?>
    iframe_solicitem.document.form1.submit();
  }else{
    document.form1.<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>.disabled = false;
    alert('Usuário:\n\nNenhum item foi selecionado. \nAutorização não gerada.\n\nAdministrador:');
  }
}
</script>