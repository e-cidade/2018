<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE db_pluginitensmenu
class cl_db_pluginitensmenu { 
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
   var $db146_sequencial = 0; 
   var $db146_db_plugin = 0; 
   var $db146_db_itensmenu = 0; 
   var $db146_uid = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db146_sequencial = int4 = Código 
                 db146_db_plugin = int4 = Código 
                 db146_db_itensmenu = int4 = Código do ítem 
                 db146_uid = varchar(255) = Código Único 
                 ";
   //funcao construtor da classe 
   function cl_db_pluginitensmenu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_pluginitensmenu"); 
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
       $this->db146_sequencial = ($this->db146_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db146_sequencial"]:$this->db146_sequencial);
       $this->db146_db_plugin = ($this->db146_db_plugin == ""?@$GLOBALS["HTTP_POST_VARS"]["db146_db_plugin"]:$this->db146_db_plugin);
       $this->db146_db_itensmenu = ($this->db146_db_itensmenu == ""?@$GLOBALS["HTTP_POST_VARS"]["db146_db_itensmenu"]:$this->db146_db_itensmenu);
       $this->db146_uid = ($this->db146_uid == ""?@$GLOBALS["HTTP_POST_VARS"]["db146_uid"]:$this->db146_uid);
     }else{
       $this->db146_sequencial = ($this->db146_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db146_sequencial"]:$this->db146_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db146_sequencial){ 
      $this->atualizacampos();
     if($this->db146_db_plugin == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "db146_db_plugin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db146_db_itensmenu == null ){ 
       $this->erro_sql = " Campo Código do ítem não informado.";
       $this->erro_campo = "db146_db_itensmenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db146_sequencial == "" || $db146_sequencial == null ){
       $result = db_query("select nextval('db_pluginitensmenu_db146_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_pluginitensmenu_db146_sequencial_seq do campo: db146_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db146_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_pluginitensmenu_db146_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db146_sequencial)){
         $this->erro_sql = " Campo db146_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db146_sequencial = $db146_sequencial; 
       }
     }
     if(($this->db146_sequencial == null) || ($this->db146_sequencial == "") ){ 
       $this->erro_sql = " Campo db146_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_pluginitensmenu(
                                       db146_sequencial 
                                      ,db146_db_plugin 
                                      ,db146_db_itensmenu 
                                      ,db146_uid 
                       )
                values (
                                $this->db146_sequencial 
                               ,$this->db146_db_plugin 
                               ,$this->db146_db_itensmenu 
                               ,'$this->db146_uid' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens de Menu de Plugins ($this->db146_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens de Menu de Plugins já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens de Menu de Plugins ($this->db146_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db146_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db146_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20427,'$this->db146_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3673,20427,'','".AddSlashes(pg_result($resaco,0,'db146_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3673,20434,'','".AddSlashes(pg_result($resaco,0,'db146_db_plugin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3673,20435,'','".AddSlashes(pg_result($resaco,0,'db146_db_itensmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3673,21696,'','".AddSlashes(pg_result($resaco,0,'db146_uid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db146_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_pluginitensmenu set ";
     $virgula = "";
     if(trim($this->db146_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db146_sequencial"])){ 
       $sql  .= $virgula." db146_sequencial = $this->db146_sequencial ";
       $virgula = ",";
       if(trim($this->db146_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "db146_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db146_db_plugin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db146_db_plugin"])){ 
       $sql  .= $virgula." db146_db_plugin = $this->db146_db_plugin ";
       $virgula = ",";
       if(trim($this->db146_db_plugin) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "db146_db_plugin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db146_db_itensmenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db146_db_itensmenu"])){ 
       $sql  .= $virgula." db146_db_itensmenu = $this->db146_db_itensmenu ";
       $virgula = ",";
       if(trim($this->db146_db_itensmenu) == null ){ 
         $this->erro_sql = " Campo Código do ítem não informado.";
         $this->erro_campo = "db146_db_itensmenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db146_uid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db146_uid"])){ 
       $sql  .= $virgula." db146_uid = '$this->db146_uid' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db146_sequencial!=null){
       $sql .= " db146_sequencial = $this->db146_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db146_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20427,'$this->db146_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db146_sequencial"]) || $this->db146_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3673,20427,'".AddSlashes(pg_result($resaco,$conresaco,'db146_sequencial'))."','$this->db146_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db146_db_plugin"]) || $this->db146_db_plugin != "")
             $resac = db_query("insert into db_acount values($acount,3673,20434,'".AddSlashes(pg_result($resaco,$conresaco,'db146_db_plugin'))."','$this->db146_db_plugin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db146_db_itensmenu"]) || $this->db146_db_itensmenu != "")
             $resac = db_query("insert into db_acount values($acount,3673,20435,'".AddSlashes(pg_result($resaco,$conresaco,'db146_db_itensmenu'))."','$this->db146_db_itensmenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db146_uid"]) || $this->db146_uid != "")
             $resac = db_query("insert into db_acount values($acount,3673,21696,'".AddSlashes(pg_result($resaco,$conresaco,'db146_uid'))."','$this->db146_uid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de Menu de Plugins não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db146_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Itens de Menu de Plugins não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db146_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db146_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db146_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db146_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20427,'$db146_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3673,20427,'','".AddSlashes(pg_result($resaco,$iresaco,'db146_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3673,20434,'','".AddSlashes(pg_result($resaco,$iresaco,'db146_db_plugin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3673,20435,'','".AddSlashes(pg_result($resaco,$iresaco,'db146_db_itensmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3673,21696,'','".AddSlashes(pg_result($resaco,$iresaco,'db146_uid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_pluginitensmenu
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db146_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db146_sequencial = $db146_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de Menu de Plugins não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db146_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Itens de Menu de Plugins não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db146_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db146_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_pluginitensmenu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db146_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_pluginitensmenu ";
     $sql .= "      inner join db_itensmenu  on  db_itensmenu.id_item = db_pluginitensmenu.db146_db_itensmenu";
     $sql .= "      inner join db_plugin  on  db_plugin.db145_sequencial = db_pluginitensmenu.db146_db_plugin";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db146_sequencial)) {
         $sql2 .= " where db_pluginitensmenu.db146_sequencial = $db146_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($db146_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_pluginitensmenu ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db146_sequencial)){
         $sql2 .= " where db_pluginitensmenu.db146_sequencial = $db146_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
