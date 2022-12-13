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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_listadump
class cl_db_listadump { 
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
   var $db54_sequencial = 0; 
   var $db54_tabela = null; 
   var $db54_sqlapaga = null; 
   var $db54_sqlantes = null; 
   var $db54_sqldepois = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db54_sequencial = int4 = Ordem 
                 db54_tabela = varchar(40) = Tabela 
                 db54_sqlapaga = text = SQL Apaga 
                 db54_sqlantes = text = SQL Antes 
                 db54_sqldepois = text = SQL Depois 
                 ";
   //funcao construtor da classe 
   function cl_db_listadump() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_listadump"); 
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
       $this->db54_sequencial = ($this->db54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db54_sequencial"]:$this->db54_sequencial);
       $this->db54_tabela = ($this->db54_tabela == ""?@$GLOBALS["HTTP_POST_VARS"]["db54_tabela"]:$this->db54_tabela);
       $this->db54_sqlapaga = ($this->db54_sqlapaga == ""?@$GLOBALS["HTTP_POST_VARS"]["db54_sqlapaga"]:$this->db54_sqlapaga);
       $this->db54_sqlantes = ($this->db54_sqlantes == ""?@$GLOBALS["HTTP_POST_VARS"]["db54_sqlantes"]:$this->db54_sqlantes);
       $this->db54_sqldepois = ($this->db54_sqldepois == ""?@$GLOBALS["HTTP_POST_VARS"]["db54_sqldepois"]:$this->db54_sqldepois);
     }else{
       $this->db54_sequencial = ($this->db54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db54_sequencial"]:$this->db54_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db54_sequencial){ 
      $this->atualizacampos();
     if($this->db54_tabela == null ){ 
       $this->erro_sql = " Campo Tabela nao Informado.";
       $this->erro_campo = "db54_tabela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db54_sqlapaga == null ){ 
       $this->erro_sql = " Campo SQL Apaga nao Informado.";
       $this->erro_campo = "db54_sqlapaga";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db54_sqlantes == null ){ 
       $this->erro_sql = " Campo SQL Antes nao Informado.";
       $this->erro_campo = "db54_sqlantes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db54_sqldepois == null ){ 
       $this->erro_sql = " Campo SQL Depois nao Informado.";
       $this->erro_campo = "db54_sqldepois";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db54_sequencial == "" || $db54_sequencial == null ){
       $result = db_query("select nextval('db_listadump_db54_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_listadump_db54_sequencial_seq do campo: db54_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db54_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_listadump_db54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db54_sequencial)){
         $this->erro_sql = " Campo db54_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db54_sequencial = $db54_sequencial; 
       }
     }
     if(($this->db54_sequencial == null) || ($this->db54_sequencial == "") ){ 
       $this->erro_sql = " Campo db54_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_listadump(
                                       db54_sequencial 
                                      ,db54_tabela 
                                      ,db54_sqlapaga 
                                      ,db54_sqlantes 
                                      ,db54_sqldepois 
                       )
                values (
                                $this->db54_sequencial 
                               ,'$this->db54_tabela' 
                               ,'$this->db54_sqlapaga' 
                               ,'$this->db54_sqlantes' 
                               ,'$this->db54_sqldepois' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "db_listadump ($this->db54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "db_listadump já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "db_listadump ($this->db54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db54_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db54_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9124,'$this->db54_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1562,9124,'','".AddSlashes(pg_result($resaco,0,'db54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1562,9125,'','".AddSlashes(pg_result($resaco,0,'db54_tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1562,9126,'','".AddSlashes(pg_result($resaco,0,'db54_sqlapaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1562,9127,'','".AddSlashes(pg_result($resaco,0,'db54_sqlantes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1562,9128,'','".AddSlashes(pg_result($resaco,0,'db54_sqldepois'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db54_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_listadump set ";
     $virgula = "";
     if(trim($this->db54_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db54_sequencial"])){ 
       $sql  .= $virgula." db54_sequencial = $this->db54_sequencial ";
       $virgula = ",";
       if(trim($this->db54_sequencial) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "db54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db54_tabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db54_tabela"])){ 
       $sql  .= $virgula." db54_tabela = '$this->db54_tabela' ";
       $virgula = ",";
       if(trim($this->db54_tabela) == null ){ 
         $this->erro_sql = " Campo Tabela nao Informado.";
         $this->erro_campo = "db54_tabela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db54_sqlapaga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db54_sqlapaga"])){ 
       $sql  .= $virgula." db54_sqlapaga = '$this->db54_sqlapaga' ";
       $virgula = ",";
       if(trim($this->db54_sqlapaga) == null ){ 
         $this->erro_sql = " Campo SQL Apaga nao Informado.";
         $this->erro_campo = "db54_sqlapaga";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db54_sqlantes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db54_sqlantes"])){ 
       $sql  .= $virgula." db54_sqlantes = '$this->db54_sqlantes' ";
       $virgula = ",";
       if(trim($this->db54_sqlantes) == null ){ 
         $this->erro_sql = " Campo SQL Antes nao Informado.";
         $this->erro_campo = "db54_sqlantes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db54_sqldepois)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db54_sqldepois"])){ 
       $sql  .= $virgula." db54_sqldepois = '$this->db54_sqldepois' ";
       $virgula = ",";
       if(trim($this->db54_sqldepois) == null ){ 
         $this->erro_sql = " Campo SQL Depois nao Informado.";
         $this->erro_campo = "db54_sqldepois";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db54_sequencial!=null){
       $sql .= " db54_sequencial = $this->db54_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db54_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9124,'$this->db54_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db54_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1562,9124,'".AddSlashes(pg_result($resaco,$conresaco,'db54_sequencial'))."','$this->db54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db54_tabela"]))
           $resac = db_query("insert into db_acount values($acount,1562,9125,'".AddSlashes(pg_result($resaco,$conresaco,'db54_tabela'))."','$this->db54_tabela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db54_sqlapaga"]))
           $resac = db_query("insert into db_acount values($acount,1562,9126,'".AddSlashes(pg_result($resaco,$conresaco,'db54_sqlapaga'))."','$this->db54_sqlapaga',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db54_sqlantes"]))
           $resac = db_query("insert into db_acount values($acount,1562,9127,'".AddSlashes(pg_result($resaco,$conresaco,'db54_sqlantes'))."','$this->db54_sqlantes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db54_sqldepois"]))
           $resac = db_query("insert into db_acount values($acount,1562,9128,'".AddSlashes(pg_result($resaco,$conresaco,'db54_sqldepois'))."','$this->db54_sqldepois',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_listadump nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_listadump nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db54_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db54_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9124,'$db54_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1562,9124,'','".AddSlashes(pg_result($resaco,$iresaco,'db54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1562,9125,'','".AddSlashes(pg_result($resaco,$iresaco,'db54_tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1562,9126,'','".AddSlashes(pg_result($resaco,$iresaco,'db54_sqlapaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1562,9127,'','".AddSlashes(pg_result($resaco,$iresaco,'db54_sqlantes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1562,9128,'','".AddSlashes(pg_result($resaco,$iresaco,'db54_sqldepois'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_listadump
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db54_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db54_sequencial = $db54_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_listadump nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_listadump nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db54_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_listadump";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>