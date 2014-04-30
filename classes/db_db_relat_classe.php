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
//CLASSE DA ENTIDADE db_relat
class cl_db_relat { 
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
   var $db91_codrel = 0; 
   var $db91_descr = null; 
   var $db91_quebra = 'f'; 
   var $db91_todos = 'f'; 
   var $db91_nomearq = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db91_codrel = int8 = Código do relatório 
                 db91_descr = varchar(40) = Descrição 
                 db91_quebra = bool = Quebrar página 
                 db91_todos = bool = Quebrar página por todos 
                 db91_nomearq = varchar(40) = Nome do arquivo PDF gerado 
                 ";
   //funcao construtor da classe 
   function cl_db_relat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_relat"); 
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
       $this->db91_codrel = ($this->db91_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["db91_codrel"]:$this->db91_codrel);
       $this->db91_descr = ($this->db91_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db91_descr"]:$this->db91_descr);
       $this->db91_quebra = ($this->db91_quebra == "f"?@$GLOBALS["HTTP_POST_VARS"]["db91_quebra"]:$this->db91_quebra);
       $this->db91_todos = ($this->db91_todos == "f"?@$GLOBALS["HTTP_POST_VARS"]["db91_todos"]:$this->db91_todos);
       $this->db91_nomearq = ($this->db91_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["db91_nomearq"]:$this->db91_nomearq);
     }else{
       $this->db91_codrel = ($this->db91_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["db91_codrel"]:$this->db91_codrel);
     }
   }
   // funcao para inclusao
   function incluir ($db91_codrel){ 
      $this->atualizacampos();
     if($this->db91_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db91_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db91_quebra == null ){ 
       $this->erro_sql = " Campo Quebrar página nao Informado.";
       $this->erro_campo = "db91_quebra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db91_todos == null ){ 
       $this->erro_sql = " Campo Quebrar página por todos nao Informado.";
       $this->erro_campo = "db91_todos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db91_codrel == "" || $db91_codrel == null ){
       $result = db_query("select nextval('db_relat_db91_codrel_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_relat_db91_codrel_seq do campo: db91_codrel"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db91_codrel = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_relat_db91_codrel_seq");
       if(($result != false) && (pg_result($result,0,0) < $db91_codrel)){
         $this->erro_sql = " Campo db91_codrel maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db91_codrel = $db91_codrel; 
       }
     }
     if(($this->db91_codrel == null) || ($this->db91_codrel == "") ){ 
       $this->erro_sql = " Campo db91_codrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_relat(
                                       db91_codrel 
                                      ,db91_descr 
                                      ,db91_quebra 
                                      ,db91_todos 
                                      ,db91_nomearq 
                       )
                values (
                                $this->db91_codrel 
                               ,'$this->db91_descr' 
                               ,'$this->db91_quebra' 
                               ,'$this->db91_todos' 
                               ,'$this->db91_nomearq' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de relatórios configuráveis ($this->db91_codrel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de relatórios configuráveis já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de relatórios configuráveis ($this->db91_codrel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db91_codrel;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db91_codrel));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8276,'$this->db91_codrel','I')");
       $resac = db_query("insert into db_acount values($acount,1395,8276,'','".AddSlashes(pg_result($resaco,0,'db91_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1395,8277,'','".AddSlashes(pg_result($resaco,0,'db91_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1395,8278,'','".AddSlashes(pg_result($resaco,0,'db91_quebra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1395,8279,'','".AddSlashes(pg_result($resaco,0,'db91_todos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1395,8280,'','".AddSlashes(pg_result($resaco,0,'db91_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db91_codrel=null) { 
      $this->atualizacampos();
     $sql = " update db_relat set ";
     $virgula = "";
     if(trim($this->db91_codrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db91_codrel"])){ 
       $sql  .= $virgula." db91_codrel = $this->db91_codrel ";
       $virgula = ",";
       if(trim($this->db91_codrel) == null ){ 
         $this->erro_sql = " Campo Código do relatório nao Informado.";
         $this->erro_campo = "db91_codrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db91_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db91_descr"])){ 
       $sql  .= $virgula." db91_descr = '$this->db91_descr' ";
       $virgula = ",";
       if(trim($this->db91_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db91_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db91_quebra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db91_quebra"])){ 
       $sql  .= $virgula." db91_quebra = '$this->db91_quebra' ";
       $virgula = ",";
       if(trim($this->db91_quebra) == null ){ 
         $this->erro_sql = " Campo Quebrar página nao Informado.";
         $this->erro_campo = "db91_quebra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db91_todos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db91_todos"])){ 
       $sql  .= $virgula." db91_todos = '$this->db91_todos' ";
       $virgula = ",";
       if(trim($this->db91_todos) == null ){ 
         $this->erro_sql = " Campo Quebrar página por todos nao Informado.";
         $this->erro_campo = "db91_todos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db91_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db91_nomearq"])){ 
       $sql  .= $virgula." db91_nomearq = '$this->db91_nomearq' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db91_codrel!=null){
       $sql .= " db91_codrel = $this->db91_codrel";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db91_codrel));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8276,'$this->db91_codrel','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db91_codrel"]))
           $resac = db_query("insert into db_acount values($acount,1395,8276,'".AddSlashes(pg_result($resaco,$conresaco,'db91_codrel'))."','$this->db91_codrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db91_descr"]))
           $resac = db_query("insert into db_acount values($acount,1395,8277,'".AddSlashes(pg_result($resaco,$conresaco,'db91_descr'))."','$this->db91_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db91_quebra"]))
           $resac = db_query("insert into db_acount values($acount,1395,8278,'".AddSlashes(pg_result($resaco,$conresaco,'db91_quebra'))."','$this->db91_quebra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db91_todos"]))
           $resac = db_query("insert into db_acount values($acount,1395,8279,'".AddSlashes(pg_result($resaco,$conresaco,'db91_todos'))."','$this->db91_todos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db91_nomearq"]))
           $resac = db_query("insert into db_acount values($acount,1395,8280,'".AddSlashes(pg_result($resaco,$conresaco,'db91_nomearq'))."','$this->db91_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de relatórios configuráveis nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db91_codrel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de relatórios configuráveis nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db91_codrel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db91_codrel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db91_codrel=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db91_codrel));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8276,'$db91_codrel','E')");
         $resac = db_query("insert into db_acount values($acount,1395,8276,'','".AddSlashes(pg_result($resaco,$iresaco,'db91_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1395,8277,'','".AddSlashes(pg_result($resaco,$iresaco,'db91_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1395,8278,'','".AddSlashes(pg_result($resaco,$iresaco,'db91_quebra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1395,8279,'','".AddSlashes(pg_result($resaco,$iresaco,'db91_todos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1395,8280,'','".AddSlashes(pg_result($resaco,$iresaco,'db91_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_relat
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db91_codrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db91_codrel = $db91_codrel ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de relatórios configuráveis nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db91_codrel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de relatórios configuráveis nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db91_codrel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db91_codrel;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_relat";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db91_codrel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_relat ";
     $sql2 = "";
     if($dbwhere==""){
       if($db91_codrel!=null ){
         $sql2 .= " where db_relat.db91_codrel = $db91_codrel "; 
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
   function sql_query_file ( $db91_codrel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_relat ";
     $sql2 = "";
     if($dbwhere==""){
       if($db91_codrel!=null ){
         $sql2 .= " where db_relat.db91_codrel = $db91_codrel "; 
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