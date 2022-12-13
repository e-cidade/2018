<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Exportar dados da secretaria para a escola</b></legend>
    <?
    if(isset($destino)){
     echo "<br><br>";
     ?>
     <b>Criado arquivo de exportação: </b>
     <a href="<?=$destino?>"><?=$destino?></a><br><br>
     <input type="button" value="Nova Exportação" name="nova" onclick="location.href='edu4_exportar_se.php'">
     <br><br>
     Para salvar arquivo, clique com o botão direito do mouse sobre
     o nome do arquivo. Após escolha <b>Salvar/Guardar Destino Como</b>.
     <?
    }else{
     ?>
     <form name="form1" method="post" action="">
     <table border="0">
      <tr>
       <td nowrap>
        <?db_ancora("<b>Escola:</b>","js_pesquisaed129_i_escola(true);",$db_opcao);?>
       </td>
       <td>
        <?db_input('ed129_i_escola',15,@$Ied129_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed129_i_escola(false);'")?>
        <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,'')?>
        <input type="button" value="Processar" name="processar" onclick="js_processar();">
       </td>
      </tr>
     </table>
     <br>
     <table id="tab_aviso" style="visibility:hidden;">
      <tr align="center">
       <td bgcolor="#DBDBDB" style="border:2px solid #000000;text-decoration:blink;">
        <table cellpadding="5" cellspacing="2">
         <tr align="center">
          <td bgcolor="#f3f3f3" style="border:2px solid #888888;text-decoration:blink;">
           <b><div id="id_escola"></div></b>
           <b>Iniciando exportação dos dados...Aguarde</b>
          </td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
     <br><br>
     </form>
     <?
    }
    if(isset($ed129_i_escola) && $ed129_i_escola!=""){
      ?>
      <script>
       document.getElementById("tab_aviso").style.visibility = "visible";
       document.getElementById("id_escola").innerHTML = <?=$ed129_i_escola?>;
      </script>
      <?
      set_time_limit(0);
      $caminho_dump = "/usr/local/pgsql/bin/pg_dump"; //webseller
      //$caminho_dump = "/usr/bin/pg_dump"; //bage
      //$caminho_dump = "/usr/bin/pg_dump"; //guaiba
      //$caminho_dump = "/usr/bin/pg_dump"; //tarefas/dbportal2_gua_20071105
      $base = db_base_ativa();
      $depto = $ed129_i_escola;
      $tempo = time();
      $destino_tar = "tmp/".$depto."_".$base."_".$tempo."_SE.tar";
      $destino = "tmp/".$depto."_".$base."_".$tempo."_SE.sql";
      system("echo '--Iniciando exportação de dados' > ".$destino);
      $sql = "select *
              from edutabelasdump
              where ed130_c_tipo = 'SE'
              order by ed130_i_sequencia asc
              ";
      $result = db_query($sql);
      $linhas = pg_num_rows($result);
      db_query("BEGIN");
      $erro_trigger = false;
      for($t=0;$t<$linhas;$t++){
       $dados = pg_fetch_array($result);
       $tabela = trim(strtolower($dados["ed130_c_tabela"]));
       $ed130_c_dumptrigger = trim($dados["ed130_c_dumptrigger"]);
       if($tabela=="ceplocalidades" || $tabela=="ceplogradouros" || $tabela=="db_acount" || $tabela=="db_acountkey"){
        $so_estrutura = "-s";
       }else{
        $so_estrutura = "";
       }
       if($ed130_c_dumptrigger=="N"){
        $result_desab = db_query("ALTER TABLE $tabela DISABLE TRIGGER ALL");
        if(!$result_desab){
         echo "ERRO desabilitando trigger da tabela $tabela";
         $erro_trigger = true;
        }
       }
       system($caminho_dump." -U postgres $so_estrutura -t $tabela $base >> ".$destino);
       if($ed130_c_dumptrigger=="N"){
        $result_hab = db_query("ALTER TABLE $tabela ENABLE TRIGGER ALL");
        if(!$result_hab){
         echo "ERRO habilitando trigger da tabela $tabela";
         $erro_trigger = true;
        }
       }
      }
      if($erro_trigger==true){
       db_query("ROLLBACK");
      }else{
       db_query("COMMIT");
      }
      echo "...Copiados dados das tabelas <br>";
      $sql1 = "select nomearq,nomesequencia,ed130_c_dumpseq,nomecam
               from db_syssequencia
                inner join db_sysarqcamp on db_sysarqcamp.codsequencia = db_syssequencia.codsequencia and db_syssequencia.codsequencia > 0
                inner join db_syscampo on db_syscampo.codcam = db_sysarqcamp.codcam
                inner join db_sysarqmod  on db_sysarqmod.codarq = db_sysarqcamp.codarq
                inner join db_sysarquivo  on db_sysarquivo.codarq = db_sysarqmod.codarq
                left join edutabelasdump on trim(lower(edutabelasdump.ed130_c_tabela)) = trim(lower(db_sysarquivo.nomearq))
               where db_sysarqmod.codmod = 1008004
               and ed130_c_tipo = 'SE'
               order by ed130_i_sequencia
               ";
      $result1 = db_query($sql1);
      $sql2 = "select * from escola_sequencias where ed129_i_escola = $depto";
      $result2 = db_query($sql2);
      $iniciosequencia = pg_result($result2,0,'ed129_i_inicio');
      $finalsequencia = pg_result($result2,0,'ed129_i_final');
      for($x=0; $x<pg_num_rows($result1);$x++){
       $nomesequencia = trim(pg_result($result1,$x,'nomesequencia'));
       $dump_seq = trim(pg_result($result1,$x,'ed130_c_dumpseq'));
       $nometabela = trim(pg_result($result1,$x,'nomearq'));
       $nomeprikey = trim(pg_result($result1,$x,'nomecam'));
       if(trim($dump_seq)=="S"){
        system($caminho_dump." $base -U postgres -t $nomesequencia >> ".$destino);
       }else{
        $sql3 = "select max($nomeprikey) as maximo
                 from $nometabela
                 where $nomeprikey >= $iniciosequencia
                 and $nomeprikey <= $finalsequencia
                ";
        $result3 = db_query($sql3);
        $maximo = pg_result($result3,0,'maximo');
        if($maximo==""){
         $start_seq = $iniciosequencia;
        }else{
         $start_seq = $maximo+1;
        }
        $create = "CREATE SEQUENCE $nomesequencia INCREMENT 1 MINVALUE $iniciosequencia MAXVALUE $finalsequencia CACHE 1 START $start_seq;";
        system("echo \"$create\" >> ".$destino);
       }
      }
      $up_time = "UPDATE escola_sequencias SET ed129_c_ulttransacao = 'SE', ed129_i_ultatualizse = ".$tempo." WHERE ed129_i_escola = $depto;";
      $index = "CREATE INDEX db_permherda_usuario_in on db_permherda(id_usuario);";
      system("echo \"$up_time\" >> ".$destino);
      system("echo \"$index\" >> ".$destino);
      echo "...Copiados dados das sequencias <br>";
      echo "...Copiando códigos fontes <br>";
      system("./edu4_exportar_fontes.sh");
      echo "...Copiados códigos fontes <br>";
      echo "...Compactando arquivo <br>";
      system("tar -cf $destino_tar $destino");
      system("tar --append --file=$destino_tar /tmp/_educodigofonte.tar.bz2");
      system("bzip2 $destino_tar");
      system("rm $destino");
      system("rm /tmp/_educodigofonte.tar.bz2");
      db_redireciona("edu4_exportar_se.php?destino=".$destino_tar.".bz2");
    }
    ?>
   </fieldset>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed129_i_escola",true,1,"ed129_i_escola",true);
function js_processar(){
 if(document.form1.ed129_i_escola.value==""){
  alert("Informe a escola para exportar os dados!");
  document.form1.ed129_i_escola.focus();
 }else{
  document.getElementById("tab_aviso").style.visibility = "visible";
  document.getElementById("id_escola").innerHTML = "ESCOLA: "+document.form1.ed129_i_escola.value+"-"+document.form1.ed18_c_nome.value;
  location.href = "edu4_exportar_se.php?ed129_i_escola="+document.form1.ed129_i_escola.value;
 }
}
function js_pesquisaed129_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escola','func_escola_sequencias.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome','Pesquisa Escolas Locais',true);
 }else{
  if(document.form1.ed129_i_escola.value != ''){
   js_OpenJanelaIframe('','db_iframe_escola','func_escola_sequencias.php?pesquisa_chave='+document.form1.ed129_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa Escolas Locais',false);
  }else{
   document.form1.ed18_c_nome.value = '';
  }
 }
}
function js_mostraescola(chave,erro){
 document.form1.ed18_c_nome.value = chave;
 if(erro==true){
  document.form1.ed129_i_escola.focus();
  document.form1.ed129_i_escola.value = '';
 }
}
function js_mostraescola1(chave1,chave2){
 document.form1.ed129_i_escola.value = chave1;
 document.form1.ed18_c_nome.value = chave2;
 db_iframe_escola.hide();
}
</script>