<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE db_registrosinconsistentesdados
class cl_db_registrosinconsistentesdados { 
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
   var $db137_sequencial = 0; 
   var $db137_db_registrosinconsistentes = 0; 
   var $db137_correto = 'f'; 
   var $db137_chave = 0; 
   var $db137_excluir = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db137_sequencial = int4 = Sequencial 
                 db137_db_registrosinconsistentes = int4 = Sequencial 
                 db137_correto = bool = Correto 
                 db137_chave = int4 = Chave 
                 db137_excluir = bool = Excluir 
                 ";
   //funcao construtor da classe 
   function cl_db_registrosinconsistentesdados() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_registrosinconsistentesdados"); 
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
       $this->db137_sequencial = ($this->db137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db137_sequencial"]:$this->db137_sequencial);
       $this->db137_db_registrosinconsistentes = ($this->db137_db_registrosinconsistentes == ""?@$GLOBALS["HTTP_POST_VARS"]["db137_db_registrosinconsistentes"]:$this->db137_db_registrosinconsistentes);
       $this->db137_correto = ($this->db137_correto == "f"?@$GLOBALS["HTTP_POST_VARS"]["db137_correto"]:$this->db137_correto);
       $this->db137_chave = ($this->db137_chave == ""?@$GLOBALS["HTTP_POST_VARS"]["db137_chave"]:$this->db137_chave);
       $this->db137_excluir = ($this->db137_excluir == "f"?@$GLOBALS["HTTP_POST_VARS"]["db137_excluir"]:$this->db137_excluir);
     }else{
       $this->db137_sequencial = ($this->db137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db137_sequencial"]:$this->db137_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db137_sequencial){ 
      $this->atualizacampos();
     if($this->db137_db_registrosinconsistentes == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "db137_db_registrosinconsistentes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db137_correto == null ){ 
       $this->db137_correto = "f";
     }
     if($this->db137_chave == null ){ 
       $this->erro_sql = " Campo Chave nao Informado.";
       $this->erro_campo = "db137_chave";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db137_excluir == null ){ 
       $this->erro_sql = " Campo Excluir nao Informado.";
       $this->erro_campo = "db137_excluir";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db137_sequencial == "" || $db137_sequencial == null ){
       $result = db_query("select nextval('db_registrosinconsistentesdados_db137_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_registrosinconsistentesdados_db137_sequencial_seq do campo: db137_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db137_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_registrosinconsistentesdados_db137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db137_sequencial)){
         $this->erro_sql = " Campo db137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db137_sequencial = $db137_sequencial; 
       }
     }
     if(($this->db137_sequencial == null) || ($this->db137_sequencial == "") ){ 
       $this->erro_sql = " Campo db137_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_registrosinconsistentesdados(
                                       db137_sequencial 
                                      ,db137_db_registrosinconsistentes 
                                      ,db137_correto 
                                      ,db137_chave 
                                      ,db137_excluir 
                       )
                values (
                                $this->db137_sequencial 
                               ,$this->db137_db_registrosinconsistentes 
                               ,'$this->db137_correto' 
                               ,$this->db137_chave 
                               ,'$this->db137_excluir' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros inconsistentes Dados ($this->db137_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros inconsistentes Dados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros inconsistentes Dados ($this->db137_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->db137_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19787,'$this->db137_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3547,19787,'','".AddSlashes(pg_result($resaco,0,'db137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3547,19788,'','".AddSlashes(pg_result($resaco,0,'db137_db_registrosinconsistentes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3547,19789,'','".AddSlashes(pg_result($resaco,0,'db137_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3547,19790,'','".AddSlashes(pg_result($resaco,0,'db137_chave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3547,19899,'','".AddSlashes(pg_result($resaco,0,'db137_excluir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db137_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_registrosinconsistentesdados set ";
     $virgula = "";
     if(trim($this->db137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db137_sequencial"])){ 
       $sql  .= $virgula." db137_sequencial = $this->db137_sequencial ";
       $virgula = ",";
       if(trim($this->db137_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db137_db_registrosinconsistentes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db137_db_registrosinconsistentes"])){ 
       $sql  .= $virgula." db137_db_registrosinconsistentes = $this->db137_db_registrosinconsistentes ";
       $virgula = ",";
       if(trim($this->db137_db_registrosinconsistentes) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db137_db_registrosinconsistentes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db137_correto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db137_correto"])){ 
       $sql  .= $virgula." db137_correto = '$this->db137_correto' ";
       $virgula = ",";
     }
     if(trim($this->db137_chave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db137_chave"])){ 
       $sql  .= $virgula." db137_chave = $this->db137_chave ";
       $virgula = ",";
       if(trim($this->db137_chave) == null ){ 
         $this->erro_sql = " Campo Chave nao Informado.";
         $this->erro_campo = "db137_chave";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db137_excluir)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db137_excluir"])){ 
       $sql  .= $virgula." db137_excluir = '$this->db137_excluir' ";
       $virgula = ",";
       if(trim($this->db137_excluir) == null ){ 
         $this->erro_sql = " Campo Excluir nao Informado.";
         $this->erro_campo = "db137_excluir";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db137_sequencial!=null){
       $sql .= " db137_sequencial = $this->db137_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->db137_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19787,'$this->db137_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db137_sequencial"]) || $this->db137_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3547,19787,'".AddSlashes(pg_result($resaco,$conresaco,'db137_sequencial'))."','$this->db137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db137_db_registrosinconsistentes"]) || $this->db137_db_registrosinconsistentes != "")
             $resac = db_query("insert into db_acount values($acount,3547,19788,'".AddSlashes(pg_result($resaco,$conresaco,'db137_db_registrosinconsistentes'))."','$this->db137_db_registrosinconsistentes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db137_correto"]) || $this->db137_correto != "")
             $resac = db_query("insert into db_acount values($acount,3547,19789,'".AddSlashes(pg_result($resaco,$conresaco,'db137_correto'))."','$this->db137_correto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db137_chave"]) || $this->db137_chave != "")
             $resac = db_query("insert into db_acount values($acount,3547,19790,'".AddSlashes(pg_result($resaco,$conresaco,'db137_chave'))."','$this->db137_chave',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db137_excluir"]) || $this->db137_excluir != "")
             $resac = db_query("insert into db_acount values($acount,3547,19899,'".AddSlashes(pg_result($resaco,$conresaco,'db137_excluir'))."','$this->db137_excluir',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros inconsistentes Dados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros inconsistentes Dados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db137_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($db137_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19787,'$db137_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3547,19787,'','".AddSlashes(pg_result($resaco,$iresaco,'db137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3547,19788,'','".AddSlashes(pg_result($resaco,$iresaco,'db137_db_registrosinconsistentes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3547,19789,'','".AddSlashes(pg_result($resaco,$iresaco,'db137_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3547,19790,'','".AddSlashes(pg_result($resaco,$iresaco,'db137_chave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3547,19899,'','".AddSlashes(pg_result($resaco,$iresaco,'db137_excluir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_registrosinconsistentesdados
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db137_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db137_sequencial = $db137_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros inconsistentes Dados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros inconsistentes Dados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_registrosinconsistentesdados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_registrosinconsistentesdados ";
     $sql .= "      inner join db_registrosinconsistentes  on  db_registrosinconsistentes.db136_sequencial = db_registrosinconsistentesdados.db137_db_registrosinconsistentes";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_registrosinconsistentes.db136_usuario";
     $sql .= "      inner join db_sysarquivo  on  db_sysarquivo.codarq = db_registrosinconsistentes.db136_tabela";
     $sql2 = "";
     if($dbwhere==""){
       if($db137_sequencial!=null ){
         $sql2 .= " where db_registrosinconsistentesdados.db137_sequencial = $db137_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $db137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_registrosinconsistentesdados ";
     $sql2 = "";
     if($dbwhere==""){
       if($db137_sequencial!=null ){
         $sql2 .= " where db_registrosinconsistentesdados.db137_sequencial = $db137_sequencial "; 
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
   /**
   * Busca dados dos registros inconsistentes 
   * 
   * @param mixed $iRegistroInconsitente - codigo do header das inconsistencias
   * @access public
   * @return string
   */
  function sql_query_buscaDependencias($iRegistroInconsitente) {
  
    $sSql  = "select db_syscampo.nomecam                         as campo,                                            ";
    $sSql .= "       db_sysarquivo.nomearq                       as tabela,                                           ";
    $sSql .= "       db_registrosinconsistentesdados.db137_chave as chave,                                            ";
    $sSql .= "       case                                                                                             ";
    $sSql .= "         when db_sysindices.campounico::integer > 0                                                     ";
    $sSql .= "          then true                                                                                     ";
    $sSql .= "         else false                                                                                     ";
    $sSql .= "       end                                           as campo_unico,                                    ";
    $sSql .= "       db_registrosinconsistentesdados.db137_excluir as excluir                                         ";
    $sSql .= "  from db_registrosinconsistentesdados                                                                  ";
    $sSql .= "       inner join db_registrosinconsistentes on db136_sequencial     = db137_db_registrosinconsistentes ";
    $sSql .= "       inner join db_sysforkey               on referen              = db136_tabela                     ";
    $sSql .= "       inner join db_sysarquivo              on db_sysforkey.codarq  = db_sysarquivo.codarq             ";
    $sSql .= "       inner join db_syscampo                on db_sysforkey.codcam  = db_syscampo.codcam               ";
    $sSql .= "       left  join db_syscadind               on db_syscadind.codcam  = db_syscampo.codcam               ";
    $sSql .= "       left  join db_sysindices              on db_sysindices.codind = db_syscadind.codind              ";
    $sSql .= "       inner join pg_tables                  on tablename            = db_sysarquivo.nomearq            ";
    $sSql .= " where db137_db_registrosinconsistentes = {$iRegistroInconsitente}                                      ";
    $sSql .= "   and db137_correto is false                                                                           ";
  
    return $sSql;
  }
}
?>