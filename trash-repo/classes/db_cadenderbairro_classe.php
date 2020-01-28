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
//CLASSE DA ENTIDADE cadenderbairro
class cl_cadenderbairro { 
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
   var $db73_sequencial = 0; 
   var $db73_cadendermunicipio = 0; 
   var $db73_descricao = null; 
   var $db73_sigla = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db73_sequencial = int4 = Código do Bairro 
                 db73_cadendermunicipio = int4 = Código do Município 
                 db73_descricao = varchar(100) = Descrição do Bairro 
                 db73_sigla = varchar(2) = Sigla do Bairro 
                 ";
   //funcao construtor da classe 
   function cl_cadenderbairro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderbairro"); 
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
       $this->db73_sequencial = ($this->db73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db73_sequencial"]:$this->db73_sequencial);
       $this->db73_cadendermunicipio = ($this->db73_cadendermunicipio == ""?@$GLOBALS["HTTP_POST_VARS"]["db73_cadendermunicipio"]:$this->db73_cadendermunicipio);
       $this->db73_descricao = ($this->db73_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db73_descricao"]:$this->db73_descricao);
       $this->db73_sigla = ($this->db73_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["db73_sigla"]:$this->db73_sigla);
     }else{
       $this->db73_sequencial = ($this->db73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db73_sequencial"]:$this->db73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db73_sequencial){ 
      $this->atualizacampos();
     if($this->db73_cadendermunicipio == null ){ 
       $this->erro_sql = " Campo Código do Município nao Informado.";
       $this->erro_campo = "db73_cadendermunicipio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db73_descricao == null ){ 
       $this->erro_sql = " Campo Descrição do Bairro nao Informado.";
       $this->erro_campo = "db73_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db73_sequencial == "" || $db73_sequencial == null ){
       $result = db_query("select nextval('cadenderbairro_db73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderbairro_db73_sequencial_seq do campo: db73_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderbairro_db73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db73_sequencial)){
         $this->erro_sql = " Campo db73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db73_sequencial = $db73_sequencial; 
       }
     }
     if(($this->db73_sequencial == null) || ($this->db73_sequencial == "") ){ 
       $this->erro_sql = " Campo db73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderbairro(
                                       db73_sequencial 
                                      ,db73_cadendermunicipio 
                                      ,db73_descricao 
                                      ,db73_sigla 
                       )
                values (
                                $this->db73_sequencial 
                               ,$this->db73_cadendermunicipio 
                               ,'$this->db73_descricao' 
                               ,'$this->db73_sigla' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Bairros ($this->db73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Bairros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Bairros ($this->db73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15853,'$this->db73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2782,15853,'','".AddSlashes(pg_result($resaco,0,'db73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2782,15854,'','".AddSlashes(pg_result($resaco,0,'db73_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2782,15855,'','".AddSlashes(pg_result($resaco,0,'db73_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2782,15856,'','".AddSlashes(pg_result($resaco,0,'db73_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderbairro set ";
     $virgula = "";
     if(trim($this->db73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db73_sequencial"])){ 
       $sql  .= $virgula." db73_sequencial = $this->db73_sequencial ";
       $virgula = ",";
       if(trim($this->db73_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Bairro nao Informado.";
         $this->erro_campo = "db73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db73_cadendermunicipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db73_cadendermunicipio"])){ 
       $sql  .= $virgula." db73_cadendermunicipio = $this->db73_cadendermunicipio ";
       $virgula = ",";
       if(trim($this->db73_cadendermunicipio) == null ){ 
         $this->erro_sql = " Campo Código do Município nao Informado.";
         $this->erro_campo = "db73_cadendermunicipio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db73_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db73_descricao"])){ 
       $sql  .= $virgula." db73_descricao = '$this->db73_descricao' ";
       $virgula = ",";
       if(trim($this->db73_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição do Bairro nao Informado.";
         $this->erro_campo = "db73_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db73_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db73_sigla"])){ 
       $sql  .= $virgula." db73_sigla = '$this->db73_sigla' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db73_sequencial!=null){
       $sql .= " db73_sequencial = $this->db73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15853,'$this->db73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db73_sequencial"]) || $this->db73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2782,15853,'".AddSlashes(pg_result($resaco,$conresaco,'db73_sequencial'))."','$this->db73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db73_cadendermunicipio"]) || $this->db73_cadendermunicipio != "")
           $resac = db_query("insert into db_acount values($acount,2782,15854,'".AddSlashes(pg_result($resaco,$conresaco,'db73_cadendermunicipio'))."','$this->db73_cadendermunicipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db73_descricao"]) || $this->db73_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2782,15855,'".AddSlashes(pg_result($resaco,$conresaco,'db73_descricao'))."','$this->db73_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db73_sigla"]) || $this->db73_sigla != "")
           $resac = db_query("insert into db_acount values($acount,2782,15856,'".AddSlashes(pg_result($resaco,$conresaco,'db73_sigla'))."','$this->db73_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Bairros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Bairros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15853,'$db73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2782,15853,'','".AddSlashes(pg_result($resaco,$iresaco,'db73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2782,15854,'','".AddSlashes(pg_result($resaco,$iresaco,'db73_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2782,15855,'','".AddSlashes(pg_result($resaco,$iresaco,'db73_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2782,15856,'','".AddSlashes(pg_result($resaco,$iresaco,'db73_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadenderbairro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db73_sequencial = $db73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Bairros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Bairros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderbairro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderbairro ";
     $sql .= "      inner join cadendermunicipio  on  cadendermunicipio.db72_sequencial = cadenderbairro.db73_cadendermunicipio";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql2 = "";
     if($dbwhere==""){
       if($db73_sequencial!=null ){
         $sql2 .= " where cadenderbairro.db73_sequencial = $db73_sequencial "; 
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
   function sql_query_file ( $db73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderbairro ";
     $sql2 = "";
     if($dbwhere==""){
       if($db73_sequencial!=null ){
         $sql2 .= " where cadenderbairro.db73_sequencial = $db73_sequencial "; 
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