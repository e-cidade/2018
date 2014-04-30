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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($HTTP_POST_VARS["excluir"])){
  db_postmemory($HTTP_POST_VARS);
  // excluir itens cadastrados para este arquivo
  db_query("begin");
  // excluir items das permissoes deste arquivo

  $sql = " delete 
           from db_permissao 
           where db_processa.codarq = $codarq and
	         db_permissao.id_item = db_processa.id_item";
  $result = db_query($sql);
 
  $sql = " delete 
           from db_menu 
           where db_processa.codarq = $codarq and
	         ( db_menu.id_item = db_processa.id_item or
		   db_menu.id_item_filho = db_processa.id_item ) ";
  $result = db_query($sql);
  

  $sql = "delete 
          from db_itensfilho
          where db_processa.codarq = $codarq 
	    and db_itensfilho.id_item = db_processa.id_item ";
	
  $result = db_query($sql);
  
  $sql = " delete 
           from db_itensmenu 
           where db_processa.codarq = $codarq and
	         db_itensmenu.id_item = db_processa.id_item "; 
  $result = db_query($sql);
  
  $sql = " delete 
           from db_processa 
           where db_processa.codarq = $codarq ";

  $result = db_query($sql);
    
  db_query("commit");

}



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
//
if(!isset($excluir)){
  
  $sql = "select nomemod, nomearq, a.codmod, r.tipotabela, r.descricao 
        from db_sysarqmod a
	     inner join db_sysmodulo m on m.codmod = a.codmod
	     inner join db_sysarquivo r on r.codarq = a.codarq
	where a.codarq = $codarq";
   //
   $result = db_query($sql);
   //
   db_fieldsmemory($result,0);
   //
   $nomearqc = ucfirst($nomearq);
   $nomearq  = substr($nomemod,0,3)."1_".trim($nomearq);
   $sql = "select id_item 
	   from db_itensmenu 
	   where funcao like '".$nomearq."0%.php' and itemativo = '1'";

   $result = db_query($sql);
   if(pg_numrows($result)!=0){
      ?>
      <form name="form1" method="POST" >
     <table width="100%"><tr><td  align="center"><b>Item Já cadastrado...</b>&nbsp;&nbsp;&nbsp;&nbsp;<input name="excluir" value="Excluir" type="submit">
     </td></tr></table>
     <input name="arquivo" value="<?=$codarq?>" type="hidden">
     </form>
     <?
   }else{
     //
     db_query('begin');

     $sql = "select nextval('db_itensmenu_id_item_seq') as primeiro";
     $result = db_query($sql);
     //
     db_fieldsmemory($result,0);
     //

     if($tipotabela == "0"){


	$sql = "insert into db_itensmenu (id_item,
					  descricao,
					  help,
					  funcao,
					  itemativo,
					  manutencao,
					  desctec)
				  values ($primeiro,
					  'Cadastro de ".trim($nomearqc)."',
					  'Cadastro de ".trim($nomearqc)."',
					  '',
					  '1',
					  1,
					  'Cadastro de ".trim($nomearqc)."')";
					  
	$result = db_query($sql);

	// incluir processa

	$sql = "insert into db_processa  (codarq,
					  id_item)
				  values ($codarq,
					  $primeiro)";
					  
	$result = db_query($sql);
	// inclusao
	$sql = "select nextval('db_itensmenu_id_item_seq') as segundo";
	//
	$result = db_query($sql);
	//
	db_fieldsmemory($result,0);
	//
	$sql = "insert into db_itensmenu (id_item,
					  descricao,
					  help,
					  funcao,
					  itemativo,
					  manutencao,
					  desctec)
				  values ($segundo,
					  'Inclusão',
					  'Inclusão de ".trim($nomearqc)."',
					  '".strtolower($nomearq)."001.php',
					  '1',
					  1,
					  'Inclusão de ".trim($nomearqc)."')";
					  
	$result = db_query($sql);
        // cadastra item filho
	$sql = "select codfilho 
	        from db_arquivos
		where arqfilho = '".$nomearq."001.php'";
        
        $result = db_query($sql);
	if(pg_numrows($result)==0){
	  
          $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
          $result = db_query($sql);
          $codfilho = pg_result($result,0,0);
          $sql = "insert into db_arquivos values($codfilho,
                                           '".$nomearq."001.php',
			  		   'Inclusão:  ".$descricao."')";
		   
          $result = db_query($sql);
	}else{
	  $codfilho = pg_result($result,0,'codfilho');
	}
        $sql = "insert into db_itensfilho values($segundo,
                                          ".$codfilho.")";
		   
        $result = db_query($sql);
	
	//
	$sql = "insert into db_processa  (codarq,
					  id_item)
				  values ($codarq,
					  $segundo)";
					  
	$result = db_query($sql);
	// alteracao
	$sql = "select nextval('db_itensmenu_id_item_seq') as terceiro";
	//
	$result = db_query($sql);
	//
	db_fieldsmemory($result,0);
	//
	$sql = "insert into db_itensmenu (id_item,
					  descricao,
					  help,
					  funcao,
					  itemativo,
					  manutencao,
					  desctec)
				  values ($terceiro,
					  'Alteração',
					  'Alteração de ".trim($nomearqc)."',
					  '".strtolower($nomearq)."002.php',
					  '1',
					  1,
					  'Alteração de ".trim($nomearqc)."')";
					  
	$result = db_query($sql);
        // cadastra item filho
	$sql = "select codfilho 
	        from db_arquivos
		where arqfilho = '".$nomearq."002.php'";
        
        $result = db_query($sql);
	if(pg_numrows($result)==0){
	
          $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
          $result = db_query($sql);
          $codfilho = pg_result($result,0,0);
          $sql = "insert into db_arquivos values(".$codfilho.",
                                         '".$nomearq."002.php',
					 'Inclusão:  ".$descricao."')";
		   
          $result = db_query($sql);
	}else{
	  $codfilho = pg_result($result,0,'codfilho');
	}
        $sql = "insert into db_itensfilho values($terceiro,
                                          ".$codfilho.")";
		   
        $result = db_query($sql);
	
	$sql = "insert into db_processa  (codarq,
					  id_item)
				  values ($codarq,
					  $terceiro)";
					  
	$result = db_query($sql);



	// exclusao
	$sql = "select nextval('db_itensmenu_id_item_seq') as quarto";
	$result = db_query($sql);
	//
	db_fieldsmemory($result,0);
	//
	$sql = "insert into db_itensmenu (id_item,
					  descricao,
					  help,
					  funcao,
					  itemativo,
					  manutencao,
					  desctec)
				  values ($quarto,
					  'Exclusão',
					  'Exclusão de ".trim($nomearqc)."',
					  '".strtolower($nomearq)."003.php',
					  '1',
					  1,
					  'Exclusão de ".trim($nomearqc)."')";
					  
	$result = db_query($sql);
	// 
        // cadastra item filho
	$sql = "select codfilho 
	        from db_arquivos
		where arqfilho = '".$nomearq."003.php'";
        
        $result = db_query($sql);
	if(pg_numrows($result)==0){
	
          $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
          $result = db_query($sql);
          $codfilho = pg_result($result,0,0);
          $sql = "insert into db_arquivos values(".$codfilho.",
                                         '".$nomearq."003.php',
					 'Inclusão:  ".$descricao."')";
		   
          $result = db_query($sql);
	}else{
	  $codfilho = pg_result($result,0,'codfilho');
	}
        $sql = "insert into db_itensfilho values($quarto,
                                          ".$codfilho.")";
		   
        $result = db_query($sql);
	
        $sql = "insert into db_processa  (codarq,
					  id_item)
				  values ($codarq,
					  $quarto)";
					  
	$result = db_query($sql);

				       
	// organiza item de menu
	
	if($itemmenu!=""){
	
	  $sql = "insert into db_menu (id_item,
				       id_item_filho,
				       menusequencia,
				       modulo)
				values($itemmenu,
				       $primeiro,
				       1,
				       $modulomenu)";

	  $result = db_query($sql);
       
	} 
	
	$sql = "insert into db_menu (id_item,
				     id_item_filho,
				     menusequencia,
				     modulo)
			      values($primeiro,
				     $segundo,
				     1,
				     $modulomenu)";

	$result = db_query($sql);

	$sql = "insert into db_menu (id_item,
				     id_item_filho,
				     menusequencia,
				     modulo)
			      values($primeiro,
				     $terceiro,
				     1,
				     $modulomenu)";

	$result = db_query($sql);
       
	$sql = "insert into db_menu (id_item,
				     id_item_filho,
				     menusequencia,
				     modulo)
			      values($primeiro,
				     $quarto,
				     1,
				     $modulomenu)";

	$result = db_query($sql);
	 
	// libera permisso de menu
	$sql = "insert into db_permissao (id_usuario,
					  id_item,
					  permissaoativa,
					  anousu,
					  id_instit,
					  id_modulo)
				   values(".db_getsession("DB_id_usuario").",
					  $primeiro,
					  '1',
					  ".db_getsession("DB_anousu").",
					  ".db_getsession("DB_instit").",
					  $modulomenu)"; 

	$result = db_query($sql);


	$sql = "insert into db_permissao (id_usuario,
					  id_item,
					  permissaoativa,
					  anousu,
					  id_instit,
					  id_modulo)
				   values(".db_getsession("DB_id_usuario").",
					  $segundo,
					  '1',
					  ".db_getsession("DB_anousu").",
					  ".db_getsession("DB_instit").",
					  $modulomenu)"; 

	$result = db_query($sql);

	$sql = "insert into db_permissao (id_usuario,
					  id_item,
					  permissaoativa,
					  anousu,
					  id_instit,
					  id_modulo)
				   values(".db_getsession("DB_id_usuario").",
					  $terceiro,
					  '1',
					  ".db_getsession("DB_anousu").",
					  ".db_getsession("DB_instit").",
					  $modulomenu)"; 

	$result = db_query($sql);

	$sql = "insert into db_permissao (id_usuario,
					  id_item,
					  permissaoativa,
					  anousu,
					  id_instit,
					  id_modulo)
				   values(".db_getsession("DB_id_usuario").",
					  $quarto,
					  '1',
					  ".db_getsession("DB_anousu").",
					  ".db_getsession("DB_instit").",
					  $modulomenu)"; 

	$result = db_query($sql);


	// 
	?>
	<table width="100%"><tr><td align="center"><h3>Item Cadastrado...</h3></td></tr></table>
	<?
      }else if($tipotabela=="1"){

	$sql = "insert into db_itensmenu (id_item,
					  descricao,
					  help,
					  funcao,
					  itemativo,
					  manutencao,
					  desctec)
				  values ($primeiro,
					  'Manuteção de ".trim($nomearqc)."',
					  'Manuteção de ".trim($nomearqc)."',
					  '".strtolower($nomearq)."002.php',
					  '1',
					  1,
					  'Manutenção de ".trim($nomearqc)."')";
					  
	$result = db_query($sql);

	
 	if($itemmenu!=""){
	
	  $sql = "insert into db_menu (id_item,
				       id_item_filho,
				       menusequencia,
				       modulo)
				values($itemmenu,
				       $primeiro,
				       1,
				       $modulomenu)";

	  $result = db_query($sql);
       
	}
	// incluir processa
       	$sql = "select codfilho 
	        from db_arquivos
		where arqfilho = '".$nomearq."002.php'";
        
        $result = db_query($sql);
	if(pg_numrows($result)==0){
	
          $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
          $result = db_query($sql);
          $codfilho = pg_result($result,0,0);
          $sql = "insert into db_arquivos values(".$codfilho.",
                                         '".$nomearq."002.php',
					 'Inclusão:  ".$descricao."')";
		   
          $result = db_query($sql);
	}else{
	  $codfilho = pg_result($result,0,'codfilho');
	}
        $sql = "insert into db_itensfilho values($primeiro,
                                          ".$codfilho.")";
		  
	$result = db_query($sql);
	
	$sql = "insert into db_processa  (codarq,
					  id_item)
				  values ($codarq,
					  $primeiro)";
					  
	$result = db_query($sql);

        $sql = "insert into db_permissao (id_usuario,
					  id_item,
					  permissaoativa,
					  anousu,
					  id_instit,
					  id_modulo)
				   values(".db_getsession("DB_id_usuario").",
					  $primeiro,
					  '1',
					  ".db_getsession("DB_anousu").",
					  ".db_getsession("DB_instit").",
					  $modulomenu)"; 

	$result = db_query($sql);

	// 
	?>
	<table width="100%"><tr><td align="center"><h3>Item Cadastrado...</h3></td></tr></table>
	<?
        //	
      }else{
	// 
	?>
	<table width="100%"><tr><td title="Esta tabela não possui menu." align="center"><h3>Tabela Dependente. Sem menus.</h3></td></tr></table>
	<?
      }

      /// inclusao de funcao e forms como arquivos auxiliares
      $sql = "select codfilho 
	        from db_arquivos
		where arqfilho = 'db_func_".strtolower($nomearqc).".php'";
        
      $result = db_query($sql);
      if(pg_numrows($result)==0){
	
        $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
        $result = db_query($sql);
        $codfilho = pg_result($result,0,0);
        $sql = "insert into db_arquivos values(".$codfilho.",
                                         'db_func_".strtolower($nomearqc).".php',
					 'Arquivo com os campos para a função da tabela :  ".$nomearqc."')";
		   
        $result = db_query($sql);
      }else{
        $codfilho = pg_result($result,0,'codfilho');
      }
      if($tipotabela=="0"){
        $sql = "insert into db_itensfilho values($segundo,
                                               ".$codfilho.")";
        $result = db_query($sql);
        $sql = "insert into db_itensfilho values($terceiro,
                                          ".$codfilho.")";
        $result = db_query($sql);
        $sql = "insert into db_itensfilho values($quarto,
                                            ".$codfilho.")";
      }else{
        $sql = "insert into db_itensfilho values($primeiro,
                                            ".$codfilho.")";
      }
      $result = db_query($sql);


      $sql = "select codfilho 
	        from db_arquivos
		where arqfilho = 'func_".strtolower($nomearqc).".php'";
        
      $result = db_query($sql);
      if(pg_numrows($result)==0){
	
        $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
        $result = db_query($sql);
        $codfilho = pg_result($result,0,0);
        $sql = "insert into db_arquivos values(".$codfilho.",
                                         'func_".strtolower($nomearqc).".php',
					 'Função de consulta aos dados da tabela :  ".$nomearqc."')";
		   
        $result = db_query($sql);
      }else{
        $codfilho = pg_result($result,0,'codfilho');
      }
      if($tipotabela=="0"){
        $sql = "insert into db_itensfilho values($segundo,
                                               ".$codfilho.")";
        $result = db_query($sql);
        $sql = "insert into db_itensfilho values($terceiro,
                                          ".$codfilho.")";
        $result = db_query($sql);
        $sql = "insert into db_itensfilho values($quarto,
                                            ".$codfilho.")";
      }else{
        $sql = "insert into db_itensfilho values($primeiro,
                                            ".$codfilho.")";
      }
      $result = db_query($sql);

      /// inclusao do formulario criado
      $sql = "select codfilho 
              from db_arquivos
              where arqfilho = 'db_frm".strtolower($nomearqc).".php'";
      $result = db_query($sql);
      if(pg_numrows($result)==0){
	
        $sql = "select nextval('db_arquivos_codfilho_seq') as codfilho" ;
	
        $result = db_query($sql);
        $codfilho = pg_result($result,0,0);
        $sql = "insert into db_arquivos values(".$codfilho.",
                                         'db_frm".strtolower($nomearqc).".php',
					 'Formulario utilizado para a tabela :  ".$nomearqc."')";
		   
        $result = db_query($sql);
      }else{
        $codfilho = pg_result($result,0,'codfilho');
      }
      if($tipotabela=="0"){
        $sql = "insert into db_itensfilho values($segundo,
                                               ".$codfilho.")";
        $result = db_query($sql);
        $sql = "insert into db_itensfilho values($terceiro,
                                          ".$codfilho.")";
        $result = db_query($sql);
        $sql = "insert into db_itensfilho values($quarto,
                                            ".$codfilho.")";
      }else{
        $sql = "insert into db_itensfilho values($primeiro,
                                            ".$codfilho.")";
      }
 	   
      $result = db_query($sql);
     
      db_query('commit');

   }
}
?>
</body>
</html>