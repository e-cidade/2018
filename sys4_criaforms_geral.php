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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
height: 17px;
border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
<?
if(!isset($HTTP_POST_VARS["b_estrut"])) { ?>
  <form method="post" name="estrut">                
    <table border="0" cellpadding="0" cellspacing="0">
    <tr> 
    <td><strong> 
    <label for="ra1"></label>
    </strong><strong>
    <label for="ra2">Tabela:</label>
    </strong> <input type="radio" class="radio" name="tabmod" value="t" id="ra2" checked> 
    </td>
    </tr>
    <tr> 
    <td><input name="nometab" type="text" id="nometab" value="<?=@$nometabela?>"></td>
    </tr>
    <tr> 
    <td> <input id="id_estrut" type="submit" name="b_estrut" value="Criar Formul&aacute;rio" class="botao"> 
    </td>
    </tr>
    </table>
    </form>
    <?
} else {
  db_postmemory($HTTP_POST_VARS);
  // Tabelas
  $nometab = strtolower($nometab);
  if(trim($nometab) == "" && $tabmod == "t")
    $nometab = "%";
  if(trim($nometab) == "" && $tabmod == "m") {
    db_msgbox("Voce precisa informar um módulo");
    db_redireciona();
  }
  if($tabmod == "t")
    if($nometab == "%")
      $qr = " ";
    else
      $qr = "where nomearq = '$nometab'";
  else if($tabmod == "m")
    $qr = "where nomemod = '$nometab'";
  $sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod, a.rotulo
    from db_sysmodulo m
    inner join db_sysarqmod am on am.codmod = m.codmod
    inner join db_sysarquivo a on a.codarq = am.codarq
    $qr
    order by codmod";
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);
  $RecordsetTabMod = $result;
  if($numrows == 0) {
    if($tabmod == "t")
      db_msgbox("Não foi encontrada nenhuma tabela com o argumento $nometab");
    else if($tabmod == "m")
      db_msgbox("Não foi encontrada nenhum módulo com o nome de $nometab");
    db_redireciona();
  } else {
    $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
    $arquivo = $root."/forms/"."db_frm".trim($nometab).".php";
    $fd = fopen($arquivo,"w");
    fputs($fd,"<?\n");
    for($i = 0;$i < $numrows;$i++) {
      $varpk = ""; 
      $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
          from db_sysprikey p
          inner join db_sysarquivo a on a.codarq = p.codarq
          inner join db_syscampo c   on c.codcam = p.codcam
          where a.codarq = ".pg_result($result,$i,"codarq"));
      if(pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        $virgula = "";
        $virconc = "";
        for($p = 0;$p < $Npk;$p++) {
          $varpk .= "##".trim(pg_result($pk,$p,"nomecam"));
        } 
      }
      $campo = pg_exec("select c.*
          from db_syscampo c
          inner join db_sysarqcamp a   on a.codcam = c.codcam
          where codarq = ".pg_result($result,$i,"codarq").
          " order by a.seqarq");
      $Ncampos = pg_numrows($campo);
      if($Ncampos > 0) {
        fputs($fd,"//MODULO: ".trim(pg_result($result,$i,"nomemod"))."\n");        
        fputs($fd,'$cl'.trim(pg_result($result,$i,"nomearq")).'->rotulo->label();'."\n");

        // testar se existe chaves estrangeiras deste arquivo
        $forkey = pg_exec("select distinct f.codcam,b.nomecam as nomecerto,f.referen, q.nomearq, c.camiden, a.nomecam, a.tamanho
            from db_sysforkey f 
            inner join db_sysprikey c on c.codarq = f.referen 
            inner join db_syscampo a on a.codcam = c.camiden 
            inner join db_syscampo b on b.codcam = f.codcam 
            inner join db_sysarquivo q on q.codarq = f.referen 
            where f.codarq = ".pg_result($result,$i,"codarq")); 
          $Nforkey = pg_numrows($forkey);
        $campofk="";
        if($Nforkey > 0) { 
          fputs($fd,'$clrotulo = new rotulocampo;'."\n");
          for($fk=0;$fk<$Nforkey;$fk++){
            $campofk .= "#".trim(pg_result($forkey,$fk,'codcam'));
            fputs($fd,'$clrotulo->label("'.trim(pg_result($forkey,$fk,'nomecam')).'");'."\n");
          }
        }
        fputs($fd,'?>'."\n");
        fputs($fd,'<form name="form1" method="post" action="">'."\n");
        fputs($fd,'<center>'."\n");
        fputs($fd,'<table border="0">'."\n");
        for($j = 0;$j < $Ncampos;$j++) {
          fputs($fd,'  <tr>'."\n");
          //coluna label
          fputs($fd,'    <td nowrap title="<?=@$T'.trim(pg_result($campo,$j,"nomecam")).'?>">'."\n");
          $funcaojava = '""';
          if( strpos($campofk,trim(pg_result($campo,$j,"codcam"))) > 0 ){
            fputs($fd,'       <?'."\n");
            $funcaojava = '"js_pesquisa'.trim(pg_result($campo,$j,"nomecam")).'(true);"';
            fputs($fd,'       db_ancora(@$L'.trim(pg_result($campo,$j,"nomecam")).','.$funcaojava.',$db_opcao);'."\n");
            fputs($fd,'       ?>'."\n");
            $funcaojava = '" onchange=\'js_pesquisa'.trim(pg_result($campo,$j,"nomecam")).'(false);\'"';
          }else{
            fputs($fd,'       <?=@$L'.trim(pg_result($campo,$j,"nomecam")).'?>'."\n");
          }
          fputs($fd,'    </td>'."\n");
          fputs($fd,'    <td> '."\n");
          //$x = pg_result($campo,$j,"tipo");
          $xc = pg_result($campo,$j,"conteudo");
          if(substr($xc,0,4)!="date"){  
            if( (substr($xc,0,3)=="cha") || ( substr($xc,0,3)=="var") || (substr($xc,0,3)=="flo") ){
              if(strpos($varpk,trim(pg_result($campo,$j,"nomecam")) ) != 0 ){
                //chave primaria
                fputs($fd,"<?"."\n");

                if(pg_result($campo,$j,"sequencia")==0)
                  fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."3,".$funcaojava.")"."\n");
                else
                  fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");

                fputs($fd,"?>"."\n");
              }else{
                fputs($fd,"<?"."\n");
                fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
                fputs($fd,"?>"."\n");
              }
            }else if(substr($xc,0,3)=="boo"){
              fputs($fd,"<?"."\n");
              fputs($fd,'$x = array("f"=>"NAO","t"=>"SIM");'."\n");
              fputs($fd,"db_select('".trim(pg_result($campo,$j,"nomecam"))."',".'$x'.",true,$"."db_opcao,".$funcaojava.");"."\n");
              fputs($fd,"?>"."\n");
            }else if(substr($xc,0,3)=="tex"){
              fputs($fd,"<?"."\n");
              fputs($fd,"db_textarea('".trim(pg_result($campo,$j,"nomecam"))."'".',0,0,$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n"); 
              fputs($fd,"?>"."\n");
            }else{
              fputs($fd,"<?"."\n");
              fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
              fputs($fd,"?>"."\n");
            }
          }else{
            fputs($fd,"<?"."\n");
            fputs($fd,"db_inputdata('".trim(pg_result($campo,$j,"nomecam"))."',@$".trim(pg_result($campo,$j,"nomecam"))."_dia,@$".trim(pg_result($campo,$j,"nomecam"))."_mes,@$".trim(pg_result($campo,$j,"nomecam"))."_ano,true,'text',$"."db_opcao,".$funcaojava.")"."\n");
            fputs($fd,"?>"."\n");
          }
          if($funcaojava != '""'){
            // strpos($campofk,pg_result($campo,$j,"codcam")) > 0 ){
            fputs($fd,'       <?'."\n");
            for($fk=0;$fk<$Nforkey;$fk++){
              if( pg_result($forkey,$fk,'codcam') == pg_result($campo,$j,"codcam")){
                fputs($fd,"db_input('".trim(pg_result($forkey,$fk,"nomecam"))."'".','.trim(pg_result($forkey,$fk,"tamanho")).',$I'.trim(pg_result($forkey,$fk,"nomecam")).",true,'text',3,'')"."\n");
              }
            }
            fputs($fd,'       ?>'."\n");
          }

          fputs($fd,'    <td>'."\n");
          fputs($fd,'  <tr>'."\n");
          } 

          $arqarq =pg_exec("select *
              from db_sysarqarq a 
              inner join db_sysarquivo c on c.codarq = f.codarq 
              where a.codarq = ".pg_result($result,$i,"codarq")); 
            $Narqarq = pg_numrows($arqarq);
          if($Narqarq > 0) { 
            for($aa=0;$aa<$Narqarq;$qq++){ 
              $forkey = pg_exec("select * 
                  from db_sysforkey f 
                  inner join db_syscampo b on b.codcam = f.codcam 
                  where f.codarq = ".pg_result($Narqarq,$aa,"codepen")); 
                $campon = "";
              for($fk=0;$fk<pg_numrows($forkey);$fk++){
                $campon .= "#".trim(pg_result($forkey,$fk,"codcam"));
              }  

              $depcamp= pg_exec("select * 
                  from db_syscampo a
                  inner join db_sysarqcamp q on q.codarq = a.codarq 
                  where a.codarq = ".pg_result($Narqarq,$aa,"codepen")); 
                $tem = false;
              for($fk=0;$fkpg_numrows($depcamp);$fk++){
                if(strpos($campon,trim(pg_result($depcamp,$fk,'codcam')))==0){
                  //aqui


                  // testar se existe chaves estrangeiras deste arquivo
                  $forkey = pg_exec("select distinct f.codcam,b.nomecam as nomecerto,f.referen, q.nomearq, c.camiden, a.nomecam, a.tamanho
                      from db_sysforkey f 
                      inner join db_sysprikey c on c.codarq = f.referen 
                      inner join db_syscampo a on a.codcam = c.camiden 
                      inner join db_syscampo b on b.codcam = f.codcam 
                      inner join db_sysarquivo q on q.codarq = f.referen 
                      where f.codarq = ".pg_result($result,$i,"codarq")); 
                    $Nforkey = pg_numrows($forkey);
                  $campofk="";
                  fputs($fd,'?'."\n");
                  if($Nforkey > 0) { 
                    for($fk=0;$fk<$Nforkey;$fk++){
                      $campofk .= "#".trim(pg_result($forkey,$fk,'codcam'));
                      fputs($fd,'$clrotulo->label("'.trim(pg_result($forkey,$fk,'nomecam')).'");'."\n");
                    }
                  }
                  fputs($fd,'?>'."\n");
                  for($j = 0;$j < $Ncampos;$j++) {
                    fputs($fd,'  <tr>'."\n");
                    //coluna label
                    fputs($fd,'    <td nowrap title="<?=@$T'.trim(pg_result($campo,$j,"nomecam")).'?>">'."\n");
                    $funcaojava = '""';
                    if( strpos($campofk,trim(pg_result($campo,$j,"codcam"))) > 0 ){
                      fputs($fd,'       <?'."\n");
                      $funcaojava = '"js_pesquisa'.trim(pg_result($campo,$j,"nomecam")).'(true);"';
                      fputs($fd,'       db_ancora(@$L'.trim(pg_result($campo,$j,"nomecam")).','.$funcaojava.',$db_opcao);'."\n");
                      fputs($fd,'       ?>'."\n");
                      $funcaojava = '" onchange=\'js_pesquisa'.trim(pg_result($campo,$j,"nomecam")).'(false);\'"';
                    }else{
                      fputs($fd,'       <?=@$L'.trim(pg_result($campo,$j,"nomecam")).'?>'."\n");
                    }
                    fputs($fd,'    </td>'."\n");
                    fputs($fd,'    <td> '."\n");
                    //$x = pg_result($campo,$j,"tipo");
                    $xc = pg_result($campo,$j,"conteudo");
                    if(substr($xc,0,4)!="date"){  
                      if( (substr($xc,0,3)=="cha") || ( substr($xc,0,3)=="var") || (substr($xc,0,3)=="flo") ){
                        if(strpos($varpk,trim(pg_result($campo,$j,"nomecam")) ) != 0 ){
                          //chave primaria
                          fputs($fd,"<?"."\n");

                          if(pg_result($campo,$j,"sequencia")==0)
                            fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."3,".$funcaojava.")"."\n");
                          else
                            fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");

                          fputs($fd,"?>"."\n");
                        }else{
                          fputs($fd,"<?"."\n");
                          fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
                          fputs($fd,"?>"."\n");
                        }
                      }else if(substr($xc,0,3)=="boo"){
                        fputs($fd,"<?"."\n");
                        fputs($fd,'$x = array("f"=>"NAO","t"=>"SIM");'."\n");
                        fputs($fd,"db_select('".trim(pg_result($campo,$j,"nomecam"))."',".'$x'.",true,$"."db_opcao,".$funcaojava.");"."\n");
                        fputs($fd,"?>"."\n");
                      }else if(substr($xc,0,3)=="tex"){
                        fputs($fd,"<?"."\n");
                        fputs($fd,"db_textarea('".trim(pg_result($campo,$j,"nomecam"))."'".',0,0,$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n"); 
                        fputs($fd,"?>"."\n");
                      }else{
                        fputs($fd,"<?"."\n");
                        fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
                        fputs($fd,"?>"."\n");
                      }
                    }else{
                      fputs($fd,"<?"."\n");
                      fputs($fd,"db_inputdata('".trim(pg_result($campo,$j,"nomecam"))."',@$".trim(pg_result($campo,$j,"nomecam"))."_dia,@$".trim(pg_result($campo,$j,"nomecam"))."_mes,@$".trim(pg_result($campo,$j,"nomecam"))."_ano,true,'text',$"."db_opcao,".$funcaojava.")"."\n");
                      fputs($fd,"?>"."\n");
                    }
                    if($funcaojava != '""'){
                      // strpos($campofk,pg_result($campo,$j,"codcam")) > 0 ){
                      fputs($fd,'       <?'."\n");
                      for($fk=0;$fk<$Nforkey;$fk++){
                        if( pg_result($forkey,$fk,'codcam') == pg_result($campo,$j,"codcam")){
                          fputs($fd,"db_input('".trim(pg_result($forkey,$fk,"nomecam"))."'".','.trim(pg_result($forkey,$fk,"tamanho")).',$I'.trim(pg_result($forkey,$fk,"nomecam")).",true,'text',3,'')"."\n");
                        }
                      }
                      fputs($fd,'       ?>'."\n");
                    }

                    fputs($fd,'    <td>'."\n");
                    fputs($fd,'  <tr>'."\n");


                    //ate aqui			   
                    }              
                  }  
                  fputs($fd,'    <tr>'."\n");
                  fputs($fd,'      <td>'."\n");



                  fputs($fd,'      <td>'."\n");
                  fputs($fd,'    <tr>'."\n");

                }
              }

              fputs($fd,'  </table>'."\n");     
              fputs($fd,'  </center>'."\n");     
              fputs($fd,'<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >'."\n");
              fputs($fd,'<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >'."\n");
              fputs($fd,'</form>'."\n");    
              //
              // escreve os java scripts para controle dos iframe
              fputs($fd,'<script>'."\n");
              for($fk=0;$fk<$Nforkey;$fk++){	
                fputs($fd,'function js_pesquisa'.trim(pg_result($forkey,$fk,"nomecerto")).'(mostra){'."\n");
                  fputs($fd,'  if(mostra==true){'."\n");
                    fputs($fd,"    db_iframe.jan.location.href = 'func_".trim(pg_result($forkey,$fk,'nomearq')).".php?funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."1|0|1';"."\n");
                    fputs($fd,"    db_iframe.mostraMsg();"."\n");
                    fputs($fd,"    db_iframe.show();"."\n");
                    fputs($fd,"    db_iframe.focus();"."\n"); 
                    fputs($fd,"  }else{"."\n");
                    fputs($fd,"    db_iframe.jan.location.href = 'func_".trim(pg_result($forkey,$fk,'nomearq')).".php?pesquisa_chave='+document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value+'&funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."';"."\n");
                    fputs($fd,"  }"."\n");
                    fputs($fd,"}"."\n");
                    fputs($fd,"function js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."(chave,erro){"."\n");
                    fputs($fd,"  document.form1.".trim(pg_result($forkey,$fk,'nomecam')).".value = chave; "."\n"); 

                    fputs($fd,"  if(erro==true){ "."\n"); 
                    fputs($fd,"    document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".focus(); "."\n"); 
                    fputs($fd,"    document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value = ''; "."\n"); 
                    fputs($fd,"  }"."\n");

                    fputs($fd,"}"."\n");

                    fputs($fd,"function js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."1(chave1,chave2){"."\n");
                    fputs($fd,"  document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value = chave1;"."\n");
                    fputs($fd,"  document.form1.".trim(pg_result($forkey,$fk,'nomecam')).".value = chave2;"."\n");
                    fputs($fd,"  db_iframe.hide();"."\n");
                    fputs($fd,"}"."\n");
                  }
                  fputs($fd,"function js_pesquisa(){"."\n");
                  fputs($fd,"  db_iframe.jan.location.href = 'func_".trim(pg_result($result,$i,'nomearq')).".php?funcao_js=parent.js_preenchepesquisa|0';"."\n");
                  fputs($fd,"  db_iframe.mostraMsg();"."\n");
                  fputs($fd,"  db_iframe.show();"."\n");
                  fputs($fd,"  db_iframe.focus();"."\n");
                  fputs($fd,"}"."\n");
                  fputs($fd,"function js_preenchepesquisa(chave){"."\n");
                  fputs($fd,"  db_iframe.hide();"."\n");
                  fputs($fd,'  location.href = \'<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>\'+"?chavepesquisa="+chave;'."\n");
                  fputs($fd,"}"."\n");
                  fputs($fd,"</script>"."\n");
                  fputs($fd,"<?"."\n");
                  fputs($fd,"$"."func_iframe = new janela('db_iframe','');"."\n");
                  fputs($fd,"$"."func_iframe->posX=1;"."\n");
                  fputs($fd,"$"."func_iframe->posY=20;"."\n");
                  fputs($fd,"$"."func_iframe->largura=780;"."\n");
                  fputs($fd,"$"."func_iframe->altura=430;"."\n");
                  fputs($fd,"$"."func_iframe->titulo='Pesquisa';"."\n");
                  fputs($fd,"$"."func_iframe->iniciarVisivel = false;"."\n");
                  fputs($fd,"$"."func_iframe->mostrar();"."\n");
                  fputs($fd,"?>"."\n");

                  // fim dos java scripts
                }
              }
            } 
            fclose($fd);  






            echo "<h3>Formulario Gerado no Arquivo : $arquivo</h3>";
            echo "<a href=\"sys4_criaforms.php\">Retorna</a>\n";
          }
        }
          ?>
            </td>
            </tr>
            </table>
            <?
            db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
          ?>
            </body>
            </html>