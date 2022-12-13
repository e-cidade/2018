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
//CLASSE DA ENTIDADE db_impressora
class cl_db_impressora { 
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
   var $db64_sequencial = 0; 
   var $db64_db_tipoimpressora = 0; 
   var $db64_nome = null; 
   var $db64_modelo = null; 
   var $db64_fabricante = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db64_sequencial = int4 = Código Sequencial 
                 db64_db_tipoimpressora = int4 = Código do Tipo 
                 db64_nome = varchar(50) = Nome da Impressora 
                 db64_modelo = varchar(50) = Modelo da Impressora 
                 db64_fabricante = varchar(50) = Fabricante da Impressora 
                 ";
   //funcao construtor da classe 
   function cl_db_impressora() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_impressora"); 
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
       $this->db64_sequencial = ($this->db64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db64_sequencial"]:$this->db64_sequencial);
       $this->db64_db_tipoimpressora = ($this->db64_db_tipoimpressora == ""?@$GLOBALS["HTTP_POST_VARS"]["db64_db_tipoimpressora"]:$this->db64_db_tipoimpressora);
       $this->db64_nome = ($this->db64_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["db64_nome"]:$this->db64_nome);
       $this->db64_modelo = ($this->db64_modelo == ""?@$GLOBALS["HTTP_POST_VARS"]["db64_modelo"]:$this->db64_modelo);
       $this->db64_fabricante = ($this->db64_fabricante == ""?@$GLOBALS["HTTP_POST_VARS"]["db64_fabricante"]:$this->db64_fabricante);
     }else{
       $this->db64_sequencial = ($this->db64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db64_sequencial"]:$this->db64_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db64_sequencial){ 
      $this->atualizacampos();
     if($this->db64_db_tipoimpressora == null ){ 
       $this->erro_sql = " Campo Código do Tipo nao Informado.";
       $this->erro_campo = "db64_db_tipoimpressora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db64_nome == null ){ 
       $this->erro_sql = " Campo Nome da Impressora nao Informado.";
       $this->erro_campo = "db64_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db64_sequencial == "" || $db64_sequencial == null ){
       $result = db_query("select nextval('db_impressora_db64_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_impressora_db64_sequencial_seq do campo: db64_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db64_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_impressora_db64_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db64_sequencial)){
         $this->erro_sql = " Campo db64_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db64_sequencial = $db64_sequencial; 
       }
     }
     if(($this->db64_sequencial == null) || ($this->db64_sequencial == "") ){ 
       $this->erro_sql = " Campo db64_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_impressora(
                                       db64_sequencial 
                                      ,db64_db_tipoimpressora 
                                      ,db64_nome 
                                      ,db64_modelo 
                                      ,db64_fabricante 
                       )
                values (
                                $this->db64_sequencial 
                               ,$this->db64_db_tipoimpressora 
                               ,'$this->db64_nome' 
                               ,'$this->db64_modelo' 
                               ,'$this->db64_fabricante' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Impressora ($this->db64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Impressora já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Impressora ($this->db64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db64_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db64_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13746,'$this->db64_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2406,13746,'','".AddSlashes(pg_result($resaco,0,'db64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2406,13747,'','".AddSlashes(pg_result($resaco,0,'db64_db_tipoimpressora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2406,13748,'','".AddSlashes(pg_result($resaco,0,'db64_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2406,13749,'','".AddSlashes(pg_result($resaco,0,'db64_modelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2406,13750,'','".AddSlashes(pg_result($resaco,0,'db64_fabricante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db64_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_impressora set ";
     $virgula = "";
     if(trim($this->db64_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db64_sequencial"])){ 
       $sql  .= $virgula." db64_sequencial = $this->db64_sequencial ";
       $virgula = ",";
       if(trim($this->db64_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db64_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db64_db_tipoimpressora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db64_db_tipoimpressora"])){ 
       $sql  .= $virgula." db64_db_tipoimpressora = $this->db64_db_tipoimpressora ";
       $virgula = ",";
       if(trim($this->db64_db_tipoimpressora) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "db64_db_tipoimpressora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db64_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db64_nome"])){ 
       $sql  .= $virgula." db64_nome = '$this->db64_nome' ";
       $virgula = ",";
       if(trim($this->db64_nome) == null ){ 
         $this->erro_sql = " Campo Nome da Impressora nao Informado.";
         $this->erro_campo = "db64_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db64_modelo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db64_modelo"])){ 
       $sql  .= $virgula." db64_modelo = '$this->db64_modelo' ";
       $virgula = ",";
     }
     if(trim($this->db64_fabricante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db64_fabricante"])){ 
       $sql  .= $virgula." db64_fabricante = '$this->db64_fabricante' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db64_sequencial!=null){
       $sql .= " db64_sequencial = $this->db64_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db64_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13746,'$this->db64_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db64_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2406,13746,'".AddSlashes(pg_result($resaco,$conresaco,'db64_sequencial'))."','$this->db64_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db64_db_tipoimpressora"]))
           $resac = db_query("insert into db_acount values($acount,2406,13747,'".AddSlashes(pg_result($resaco,$conresaco,'db64_db_tipoimpressora'))."','$this->db64_db_tipoimpressora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db64_nome"]))
           $resac = db_query("insert into db_acount values($acount,2406,13748,'".AddSlashes(pg_result($resaco,$conresaco,'db64_nome'))."','$this->db64_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db64_modelo"]))
           $resac = db_query("insert into db_acount values($acount,2406,13749,'".AddSlashes(pg_result($resaco,$conresaco,'db64_modelo'))."','$this->db64_modelo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db64_fabricante"]))
           $resac = db_query("insert into db_acount values($acount,2406,13750,'".AddSlashes(pg_result($resaco,$conresaco,'db64_fabricante'))."','$this->db64_fabricante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impressora nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impressora nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db64_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db64_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13746,'$db64_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2406,13746,'','".AddSlashes(pg_result($resaco,$iresaco,'db64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2406,13747,'','".AddSlashes(pg_result($resaco,$iresaco,'db64_db_tipoimpressora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2406,13748,'','".AddSlashes(pg_result($resaco,$iresaco,'db64_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2406,13749,'','".AddSlashes(pg_result($resaco,$iresaco,'db64_modelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2406,13750,'','".AddSlashes(pg_result($resaco,$iresaco,'db64_fabricante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_impressora
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db64_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db64_sequencial = $db64_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impressora nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impressora nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db64_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_impressora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_impressora ";
     $sql .= "      inner join db_tipoimpressora  on  db_tipoimpressora.db65_sequencial = db_impressora.db64_db_tipoimpressora";
     $sql2 = "";
     if($dbwhere==""){
       if($db64_sequencial!=null ){
         $sql2 .= " where db_impressora.db64_sequencial = $db64_sequencial "; 
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
   function sql_query_file ( $db64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_impressora ";
     $sql2 = "";
     if($dbwhere==""){
       if($db64_sequencial!=null ){
         $sql2 .= " where db_impressora.db64_sequencial = $db64_sequencial "; 
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