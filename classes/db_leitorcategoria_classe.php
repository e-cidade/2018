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

//MODULO: biblioteca
//CLASSE DA ENTIDADE leitorcategoria
class cl_leitorcategoria { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $bi07_codigo = 0; 
   var $bi07_nome = null; 
   var $bi07_qtdlivros = 0; 
   var $bi07_tempo = 0; 
   var $bi07_biblioteca = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi07_codigo = int8 = Código 
                 bi07_nome = char(50) = Nome da Categoria 
                 bi07_qtdlivros = int8 = Quantidade de Livros 
                 bi07_tempo = int8 = Tempo de Empréstimo (dias) 
                 bi07_biblioteca = int8 = Biblioteca 
                 ";
   //funcao construtor da classe 
   function cl_leitorcategoria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("leitorcategoria"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->bi07_codigo = ($this->bi07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi07_codigo"]:$this->bi07_codigo);
       $this->bi07_nome = ($this->bi07_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["bi07_nome"]:$this->bi07_nome);
       $this->bi07_qtdlivros = ($this->bi07_qtdlivros == ""?@$GLOBALS["HTTP_POST_VARS"]["bi07_qtdlivros"]:$this->bi07_qtdlivros);
       $this->bi07_tempo = ($this->bi07_tempo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi07_tempo"]:$this->bi07_tempo);
       $this->bi07_biblioteca = ($this->bi07_biblioteca == ""?@$GLOBALS["HTTP_POST_VARS"]["bi07_biblioteca"]:$this->bi07_biblioteca);
     }else{
       $this->bi07_codigo = ($this->bi07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi07_codigo"]:$this->bi07_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi07_codigo){ 
      $this->atualizacampos();
     if($this->bi07_nome == null ){ 
       $this->erro_sql = " Campo Nome da Categoria nao Informado.";
       $this->erro_campo = "bi07_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi07_qtdlivros == null ){ 
       $this->erro_sql = " Campo Quantidade de Livros nao Informado.";
       $this->erro_campo = "bi07_qtdlivros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi07_tempo == null ){ 
       $this->erro_sql = " Campo Tempo de Empréstimo (dias) nao Informado.";
       $this->erro_campo = "bi07_tempo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi07_biblioteca == null ){ 
       $this->erro_sql = " Campo Biblioteca nao Informado.";
       $this->erro_campo = "bi07_biblioteca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi07_codigo == "" || $bi07_codigo == null ){
       $result = db_query("select nextval('leitorcategoria_bi07_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: leitorcategoria_bi07_codigo_seq do campo: bi07_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi07_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from leitorcategoria_bi07_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi07_codigo)){
         $this->erro_sql = " Campo bi07_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi07_codigo = $bi07_codigo; 
       }
     }
     if(($this->bi07_codigo == null) || ($this->bi07_codigo == "") ){ 
       $this->erro_sql = " Campo bi07_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into leitorcategoria(
                                       bi07_codigo 
                                      ,bi07_nome 
                                      ,bi07_qtdlivros 
                                      ,bi07_tempo 
                                      ,bi07_biblioteca 
                       )
                values (
                                $this->bi07_codigo 
                               ,'$this->bi07_nome' 
                               ,$this->bi07_qtdlivros 
                               ,$this->bi07_tempo 
                               ,$this->bi07_biblioteca 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Categoria do Leitor ($this->bi07_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Categoria do Leitor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Categoria do Leitor ($this->bi07_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi07_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi07_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008105,'$this->bi07_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1008015,1008105,'','".AddSlashes(pg_result($resaco,0,'bi07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008015,1008106,'','".AddSlashes(pg_result($resaco,0,'bi07_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008015,1008108,'','".AddSlashes(pg_result($resaco,0,'bi07_qtdlivros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008015,1008107,'','".AddSlashes(pg_result($resaco,0,'bi07_tempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008015,1008931,'','".AddSlashes(pg_result($resaco,0,'bi07_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bi07_codigo=null) { 
      $this->atualizacampos();
     $sql = " update leitorcategoria set ";
     $virgula = "";
     if(trim($this->bi07_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi07_codigo"])){ 
       $sql  .= $virgula." bi07_codigo = $this->bi07_codigo ";
       $virgula = ",";
       if(trim($this->bi07_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi07_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi07_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi07_nome"])){ 
       $sql  .= $virgula." bi07_nome = '$this->bi07_nome' ";
       $virgula = ",";
       if(trim($this->bi07_nome) == null ){ 
         $this->erro_sql = " Campo Nome da Categoria nao Informado.";
         $this->erro_campo = "bi07_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi07_qtdlivros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi07_qtdlivros"])){ 
       $sql  .= $virgula." bi07_qtdlivros = $this->bi07_qtdlivros ";
       $virgula = ",";
       if(trim($this->bi07_qtdlivros) == null ){ 
         $this->erro_sql = " Campo Quantidade de Livros nao Informado.";
         $this->erro_campo = "bi07_qtdlivros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi07_tempo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi07_tempo"])){ 
       $sql  .= $virgula." bi07_tempo = $this->bi07_tempo ";
       $virgula = ",";
       if(trim($this->bi07_tempo) == null ){ 
         $this->erro_sql = " Campo Tempo de Empréstimo (dias) nao Informado.";
         $this->erro_campo = "bi07_tempo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi07_biblioteca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi07_biblioteca"])){ 
       $sql  .= $virgula." bi07_biblioteca = $this->bi07_biblioteca ";
       $virgula = ",";
       if(trim($this->bi07_biblioteca) == null ){ 
         $this->erro_sql = " Campo Biblioteca nao Informado.";
         $this->erro_campo = "bi07_biblioteca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi07_codigo!=null){
       $sql .= " bi07_codigo = $this->bi07_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi07_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008105,'$this->bi07_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi07_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1008015,1008105,'".AddSlashes(pg_result($resaco,$conresaco,'bi07_codigo'))."','$this->bi07_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi07_nome"]))
           $resac = db_query("insert into db_acount values($acount,1008015,1008106,'".AddSlashes(pg_result($resaco,$conresaco,'bi07_nome'))."','$this->bi07_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi07_qtdlivros"]))
           $resac = db_query("insert into db_acount values($acount,1008015,1008108,'".AddSlashes(pg_result($resaco,$conresaco,'bi07_qtdlivros'))."','$this->bi07_qtdlivros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi07_tempo"]))
           $resac = db_query("insert into db_acount values($acount,1008015,1008107,'".AddSlashes(pg_result($resaco,$conresaco,'bi07_tempo'))."','$this->bi07_tempo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi07_biblioteca"]))
           $resac = db_query("insert into db_acount values($acount,1008015,1008931,'".AddSlashes(pg_result($resaco,$conresaco,'bi07_biblioteca'))."','$this->bi07_biblioteca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Categoria do Leitor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi07_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Categoria do Leitor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bi07_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi07_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008105,'$bi07_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1008015,1008105,'','".AddSlashes(pg_result($resaco,$iresaco,'bi07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008015,1008106,'','".AddSlashes(pg_result($resaco,$iresaco,'bi07_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008015,1008108,'','".AddSlashes(pg_result($resaco,$iresaco,'bi07_qtdlivros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008015,1008107,'','".AddSlashes(pg_result($resaco,$iresaco,'bi07_tempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008015,1008931,'','".AddSlashes(pg_result($resaco,$iresaco,'bi07_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from leitorcategoria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi07_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi07_codigo = $bi07_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Categoria do Leitor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi07_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Categoria do Leitor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:leitorcategoria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi07_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from leitorcategoria ";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = leitorcategoria.bi07_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($bi07_codigo!=null ){
         $sql2 .= " where leitorcategoria.bi07_codigo = $bi07_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $bi07_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from leitorcategoria ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi07_codigo!=null ){
         $sql2 .= " where leitorcategoria.bi07_codigo = $bi07_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>