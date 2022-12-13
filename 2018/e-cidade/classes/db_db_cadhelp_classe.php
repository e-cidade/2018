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
//CLASSE DA ENTIDADE db_cadhelp
class cl_db_cadhelp { 
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
   var $id_help = 0; 
   var $id_codtipo = 0; 
   var $dhelp_resum = null; 
   var $dhelp = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_help = int4 = Código Help 
                 id_codtipo = int4 = Tipo de Help 
                 dhelp_resum = varchar(60) = Resumo 
                 dhelp = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_db_cadhelp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cadhelp"); 
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
       $this->id_help = ($this->id_help == ""?@$GLOBALS["HTTP_POST_VARS"]["id_help"]:$this->id_help);
       $this->id_codtipo = ($this->id_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["id_codtipo"]:$this->id_codtipo);
       $this->dhelp_resum = ($this->dhelp_resum == ""?@$GLOBALS["HTTP_POST_VARS"]["dhelp_resum"]:$this->dhelp_resum);
       $this->dhelp = ($this->dhelp == ""?@$GLOBALS["HTTP_POST_VARS"]["dhelp"]:$this->dhelp);
     }else{
       $this->id_help = ($this->id_help == ""?@$GLOBALS["HTTP_POST_VARS"]["id_help"]:$this->id_help);
     }
   }
   // funcao para inclusao
   function incluir ($id_help){ 
      $this->atualizacampos();
     if($this->id_codtipo == null ){ 
       $this->erro_sql = " Campo Tipo de Help nao Informado.";
       $this->erro_campo = "id_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dhelp_resum == null ){ 
       $this->erro_sql = " Campo Resumo nao Informado.";
       $this->erro_campo = "dhelp_resum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dhelp == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "dhelp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($id_help == "" || $id_help == null ){
       $result = db_query("select nextval('db_cadhelp_id_help_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cadhelp_id_help_seq do campo: id_help"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->id_help = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_cadhelp_id_help_seq");
       if(($result != false) && (pg_result($result,0,0) < $id_help)){
         $this->erro_sql = " Campo id_help maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->id_help = $id_help; 
       }
     }
     if(($this->id_help == null) || ($this->id_help == "") ){ 
       $this->erro_sql = " Campo id_help nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cadhelp(
                                       id_help 
                                      ,id_codtipo 
                                      ,dhelp_resum 
                                      ,dhelp 
                       )
                values (
                                $this->id_help 
                               ,$this->id_codtipo 
                               ,'$this->dhelp_resum' 
                               ,'$this->dhelp' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos Helps ($this->id_help) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos Helps já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos Helps ($this->id_help) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_help;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_help));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4836,'$this->id_help','I')");
       $resac = db_query("insert into db_acount values($acount,652,4836,'','".AddSlashes(pg_result($resaco,0,'id_help'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,652,4837,'','".AddSlashes(pg_result($resaco,0,'id_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,652,4838,'','".AddSlashes(pg_result($resaco,0,'dhelp_resum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,652,4839,'','".AddSlashes(pg_result($resaco,0,'dhelp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_help=null) { 
      $this->atualizacampos();
     $sql = " update db_cadhelp set ";
     $virgula = "";
     if(trim($this->id_help)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_help"])){ 
        if(trim($this->id_help)=="" && isset($GLOBALS["HTTP_POST_VARS"]["id_help"])){ 
           $this->id_help = "0" ; 
        } 
       $sql  .= $virgula." id_help = $this->id_help ";
       $virgula = ",";
       if(trim($this->id_help) == null ){ 
         $this->erro_sql = " Campo Código Help nao Informado.";
         $this->erro_campo = "id_help";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_codtipo"])){ 
        if(trim($this->id_codtipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["id_codtipo"])){ 
           $this->id_codtipo = "0" ; 
        } 
       $sql  .= $virgula." id_codtipo = $this->id_codtipo ";
       $virgula = ",";
       if(trim($this->id_codtipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Help nao Informado.";
         $this->erro_campo = "id_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dhelp_resum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dhelp_resum"])){ 
       $sql  .= $virgula." dhelp_resum = '$this->dhelp_resum' ";
       $virgula = ",";
       if(trim($this->dhelp_resum) == null ){ 
         $this->erro_sql = " Campo Resumo nao Informado.";
         $this->erro_campo = "dhelp_resum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dhelp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dhelp"])){ 
       $sql  .= $virgula." dhelp = '$this->dhelp' ";
       $virgula = ",";
       if(trim($this->dhelp) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "dhelp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_help!=null){
       $sql .= " id_help = $this->id_help";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_help));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4836,'$this->id_help','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_help"]))
           $resac = db_query("insert into db_acount values($acount,652,4836,'".AddSlashes(pg_result($resaco,$conresaco,'id_help'))."','$this->id_help',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,652,4837,'".AddSlashes(pg_result($resaco,$conresaco,'id_codtipo'))."','$this->id_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dhelp_resum"]))
           $resac = db_query("insert into db_acount values($acount,652,4838,'".AddSlashes(pg_result($resaco,$conresaco,'dhelp_resum'))."','$this->dhelp_resum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dhelp"]))
           $resac = db_query("insert into db_acount values($acount,652,4839,'".AddSlashes(pg_result($resaco,$conresaco,'dhelp'))."','$this->dhelp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Helps nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_help;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Helps nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_help;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_help;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_help=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_help));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4836,'$id_help','E')");
         $resac = db_query("insert into db_acount values($acount,652,4836,'','".AddSlashes(pg_result($resaco,$iresaco,'id_help'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,652,4837,'','".AddSlashes(pg_result($resaco,$iresaco,'id_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,652,4838,'','".AddSlashes(pg_result($resaco,$iresaco,'dhelp_resum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,652,4839,'','".AddSlashes(pg_result($resaco,$iresaco,'dhelp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_cadhelp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_help != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_help = $id_help ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Helps nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_help;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Helps nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_help;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_help;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cadhelp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $id_help=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cadhelp ";
     $sql .= "      inner join db_tipohelp  on  db_tipohelp.id_codtipo = db_cadhelp.id_codtipo";
     $sql .= "      left outer join db_itenshelp on db_cadhelp.id_help = db_itenshelp.id_help";
     $sql .= "      left outer join db_itensmenu on db_itensmenu.id_item = db_itenshelp.id_item";
     $sql2 = "";
     if($dbwhere==""){
       if($id_help!=null ){
         $sql2 .= " where db_cadhelp.id_help = $id_help "; 
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
   function sql_query_file ( $id_help=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cadhelp ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_help!=null ){
         $sql2 .= " where db_cadhelp.id_help = $id_help "; 
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