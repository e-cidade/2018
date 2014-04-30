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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE db_modeloimpressao
class cl_db_modeloimpressao { 
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
   var $db66_sequencial = 0; 
   var $db66_db_tipomodeloimpressao = 0; 
   var $db66_db_impressora = 0; 
   var $db66_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db66_sequencial = int4 = Código Sequencial 
                 db66_db_tipomodeloimpressao = int4 = Código do Modelo de Impressão 
                 db66_db_impressora = int4 = Código da Impressora 
                 db66_descricao = varchar(50) = Descrição do Modelo de Impressão 
                 ";
   //funcao construtor da classe 
   function cl_db_modeloimpressao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_modeloimpressao"); 
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
       $this->db66_sequencial = ($this->db66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db66_sequencial"]:$this->db66_sequencial);
       $this->db66_db_tipomodeloimpressao = ($this->db66_db_tipomodeloimpressao == ""?@$GLOBALS["HTTP_POST_VARS"]["db66_db_tipomodeloimpressao"]:$this->db66_db_tipomodeloimpressao);
       $this->db66_db_impressora = ($this->db66_db_impressora == ""?@$GLOBALS["HTTP_POST_VARS"]["db66_db_impressora"]:$this->db66_db_impressora);
       $this->db66_descricao = ($this->db66_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db66_descricao"]:$this->db66_descricao);
     }else{
       $this->db66_sequencial = ($this->db66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db66_sequencial"]:$this->db66_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db66_sequencial){ 
      $this->atualizacampos();
     if($this->db66_db_tipomodeloimpressao == null ){ 
       $this->erro_sql = " Campo Código do Modelo de Impressão nao Informado.";
       $this->erro_campo = "db66_db_tipomodeloimpressao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db66_db_impressora == null ){ 
       $this->erro_sql = " Campo Código da Impressora nao Informado.";
       $this->erro_campo = "db66_db_impressora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db66_descricao == null ){ 
       $this->erro_sql = " Campo Descrição do Modelo de Impressão nao Informado.";
       $this->erro_campo = "db66_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db66_sequencial == "" || $db66_sequencial == null ){
       $result = db_query("select nextval('db_modeloimpressao_db66_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_modeloimpressao_db66_sequencial_seq do campo: db66_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db66_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_modeloimpressao_db66_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db66_sequencial)){
         $this->erro_sql = " Campo db66_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db66_sequencial = $db66_sequencial; 
       }
     }
     if(($this->db66_sequencial == null) || ($this->db66_sequencial == "") ){ 
       $this->erro_sql = " Campo db66_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_modeloimpressao(
                                       db66_sequencial 
                                      ,db66_db_tipomodeloimpressao 
                                      ,db66_db_impressora 
                                      ,db66_descricao 
                       )
                values (
                                $this->db66_sequencial 
                               ,$this->db66_db_tipomodeloimpressao 
                               ,$this->db66_db_impressora 
                               ,'$this->db66_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Modelo de Impressão ($this->db66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Modelo de Impressão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Modelo de Impressão ($this->db66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db66_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db66_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13751,'$this->db66_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2407,13751,'','".AddSlashes(pg_result($resaco,0,'db66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2407,13752,'','".AddSlashes(pg_result($resaco,0,'db66_db_tipomodeloimpressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2407,13753,'','".AddSlashes(pg_result($resaco,0,'db66_db_impressora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2407,13754,'','".AddSlashes(pg_result($resaco,0,'db66_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db66_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_modeloimpressao set ";
     $virgula = "";
     if(trim($this->db66_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db66_sequencial"])){ 
       $sql  .= $virgula." db66_sequencial = $this->db66_sequencial ";
       $virgula = ",";
       if(trim($this->db66_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db66_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db66_db_tipomodeloimpressao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db66_db_tipomodeloimpressao"])){ 
       $sql  .= $virgula." db66_db_tipomodeloimpressao = $this->db66_db_tipomodeloimpressao ";
       $virgula = ",";
       if(trim($this->db66_db_tipomodeloimpressao) == null ){ 
         $this->erro_sql = " Campo Código do Modelo de Impressão nao Informado.";
         $this->erro_campo = "db66_db_tipomodeloimpressao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db66_db_impressora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db66_db_impressora"])){ 
       $sql  .= $virgula." db66_db_impressora = $this->db66_db_impressora ";
       $virgula = ",";
       if(trim($this->db66_db_impressora) == null ){ 
         $this->erro_sql = " Campo Código da Impressora nao Informado.";
         $this->erro_campo = "db66_db_impressora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db66_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db66_descricao"])){ 
       $sql  .= $virgula." db66_descricao = '$this->db66_descricao' ";
       $virgula = ",";
       if(trim($this->db66_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição do Modelo de Impressão nao Informado.";
         $this->erro_campo = "db66_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db66_sequencial!=null){
       $sql .= " db66_sequencial = $this->db66_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db66_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13751,'$this->db66_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db66_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2407,13751,'".AddSlashes(pg_result($resaco,$conresaco,'db66_sequencial'))."','$this->db66_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db66_db_tipomodeloimpressao"]))
           $resac = db_query("insert into db_acount values($acount,2407,13752,'".AddSlashes(pg_result($resaco,$conresaco,'db66_db_tipomodeloimpressao'))."','$this->db66_db_tipomodeloimpressao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db66_db_impressora"]))
           $resac = db_query("insert into db_acount values($acount,2407,13753,'".AddSlashes(pg_result($resaco,$conresaco,'db66_db_impressora'))."','$this->db66_db_impressora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db66_descricao"]))
           $resac = db_query("insert into db_acount values($acount,2407,13754,'".AddSlashes(pg_result($resaco,$conresaco,'db66_descricao'))."','$this->db66_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Modelo de Impressão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Modelo de Impressão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db66_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db66_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13751,'$db66_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2407,13751,'','".AddSlashes(pg_result($resaco,$iresaco,'db66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2407,13752,'','".AddSlashes(pg_result($resaco,$iresaco,'db66_db_tipomodeloimpressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2407,13753,'','".AddSlashes(pg_result($resaco,$iresaco,'db66_db_impressora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2407,13754,'','".AddSlashes(pg_result($resaco,$iresaco,'db66_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_modeloimpressao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db66_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db66_sequencial = $db66_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Modelo de Impressão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Modelo de Impressão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db66_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_modeloimpressao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_modeloimpressao ";
     $sql .= "      inner join db_impressora  on  db_impressora.db64_sequencial = db_modeloimpressao.db66_db_impressora";
     $sql .= "      inner join db_tipomodeloimpressao  on  db_tipomodeloimpressao.db67_sequencial = db_modeloimpressao.db66_db_tipomodeloimpressao";
     $sql .= "      inner join db_tipoimpressora  on  db_tipoimpressora.db65_sequencial = db_impressora.db64_db_tipoimpressora";
     $sql2 = "";
     if($dbwhere==""){
       if($db66_sequencial!=null ){
         $sql2 .= " where db_modeloimpressao.db66_sequencial = $db66_sequencial "; 
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
   function sql_query_file ( $db66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_modeloimpressao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db66_sequencial!=null ){
         $sql2 .= " where db_modeloimpressao.db66_sequencial = $db66_sequencial "; 
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