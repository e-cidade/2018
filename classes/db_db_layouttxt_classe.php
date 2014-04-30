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
//CLASSE DA ENTIDADE db_layouttxt
class cl_db_layouttxt { 
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
   var $db50_codigo = 0; 
   var $db50_layouttxtgrupo = 0; 
   var $db50_descr = null; 
   var $db50_quantlinhas = 0; 
   var $db50_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db50_codigo = int4 = Código do layout 
                 db50_layouttxtgrupo = int4 = Código do grupo 
                 db50_descr = varchar(40) = Descrição 
                 db50_quantlinhas = int4 = Quantidade de linhas 
                 db50_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_db_layouttxt() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_layouttxt"); 
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
       $this->db50_codigo = ($this->db50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db50_codigo"]:$this->db50_codigo);
       $this->db50_layouttxtgrupo = ($this->db50_layouttxtgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["db50_layouttxtgrupo"]:$this->db50_layouttxtgrupo);
       $this->db50_descr = ($this->db50_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db50_descr"]:$this->db50_descr);
       $this->db50_quantlinhas = ($this->db50_quantlinhas == ""?@$GLOBALS["HTTP_POST_VARS"]["db50_quantlinhas"]:$this->db50_quantlinhas);
       $this->db50_obs = ($this->db50_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db50_obs"]:$this->db50_obs);
     }else{
       $this->db50_codigo = ($this->db50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db50_codigo"]:$this->db50_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db50_codigo){ 
      $this->atualizacampos();
     if($this->db50_layouttxtgrupo == null ){ 
       $this->erro_sql = " Campo Código do grupo nao Informado.";
       $this->erro_campo = "db50_layouttxtgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db50_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db50_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db50_quantlinhas == null ){ 
       $this->db50_quantlinhas = "0";
     }
     if($db50_codigo == "" || $db50_codigo == null ){
       $result = db_query("select nextval('db_layouttxt_db50_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_layouttxt_db50_codigo_seq do campo: db50_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db50_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_layouttxt_db50_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db50_codigo)){
         $this->erro_sql = " Campo db50_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db50_codigo = $db50_codigo; 
       }
     }
     if(($this->db50_codigo == null) || ($this->db50_codigo == "") ){ 
       $this->erro_sql = " Campo db50_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_layouttxt(
                                       db50_codigo 
                                      ,db50_layouttxtgrupo 
                                      ,db50_descr 
                                      ,db50_quantlinhas 
                                      ,db50_obs 
                       )
                values (
                                $this->db50_codigo 
                               ,$this->db50_layouttxtgrupo 
                               ,'$this->db50_descr' 
                               ,$this->db50_quantlinhas 
                               ,'$this->db50_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de layouts ($this->db50_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de layouts já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de layouts ($this->db50_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db50_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db50_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9066,'$this->db50_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1553,9066,'','".AddSlashes(pg_result($resaco,0,'db50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1553,11882,'','".AddSlashes(pg_result($resaco,0,'db50_layouttxtgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1553,9067,'','".AddSlashes(pg_result($resaco,0,'db50_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1553,9105,'','".AddSlashes(pg_result($resaco,0,'db50_quantlinhas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1553,9097,'','".AddSlashes(pg_result($resaco,0,'db50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db50_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_layouttxt set ";
     $virgula = "";
     if(trim($this->db50_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db50_codigo"])){ 
       $sql  .= $virgula." db50_codigo = $this->db50_codigo ";
       $virgula = ",";
       if(trim($this->db50_codigo) == null ){ 
         $this->erro_sql = " Campo Código do layout nao Informado.";
         $this->erro_campo = "db50_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db50_layouttxtgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db50_layouttxtgrupo"])){ 
       $sql  .= $virgula." db50_layouttxtgrupo = $this->db50_layouttxtgrupo ";
       $virgula = ",";
       if(trim($this->db50_layouttxtgrupo) == null ){ 
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "db50_layouttxtgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db50_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db50_descr"])){ 
       $sql  .= $virgula." db50_descr = '$this->db50_descr' ";
       $virgula = ",";
       if(trim($this->db50_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db50_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db50_quantlinhas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db50_quantlinhas"])){ 
        if(trim($this->db50_quantlinhas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db50_quantlinhas"])){ 
           $this->db50_quantlinhas = "0" ; 
        } 
       $sql  .= $virgula." db50_quantlinhas = $this->db50_quantlinhas ";
       $virgula = ",";
     }
     if(trim($this->db50_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db50_obs"])){ 
       $sql  .= $virgula." db50_obs = '$this->db50_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db50_codigo!=null){
       $sql .= " db50_codigo = $this->db50_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db50_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9066,'$this->db50_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db50_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1553,9066,'".AddSlashes(pg_result($resaco,$conresaco,'db50_codigo'))."','$this->db50_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db50_layouttxtgrupo"]))
           $resac = db_query("insert into db_acount values($acount,1553,11882,'".AddSlashes(pg_result($resaco,$conresaco,'db50_layouttxtgrupo'))."','$this->db50_layouttxtgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db50_descr"]))
           $resac = db_query("insert into db_acount values($acount,1553,9067,'".AddSlashes(pg_result($resaco,$conresaco,'db50_descr'))."','$this->db50_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db50_quantlinhas"]))
           $resac = db_query("insert into db_acount values($acount,1553,9105,'".AddSlashes(pg_result($resaco,$conresaco,'db50_quantlinhas'))."','$this->db50_quantlinhas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db50_obs"]))
           $resac = db_query("insert into db_acount values($acount,1553,9097,'".AddSlashes(pg_result($resaco,$conresaco,'db50_obs'))."','$this->db50_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de layouts nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db50_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de layouts nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db50_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db50_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9066,'$db50_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1553,9066,'','".AddSlashes(pg_result($resaco,$iresaco,'db50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1553,11882,'','".AddSlashes(pg_result($resaco,$iresaco,'db50_layouttxtgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1553,9067,'','".AddSlashes(pg_result($resaco,$iresaco,'db50_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1553,9105,'','".AddSlashes(pg_result($resaco,$iresaco,'db50_quantlinhas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1553,9097,'','".AddSlashes(pg_result($resaco,$iresaco,'db50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_layouttxt
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db50_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db50_codigo = $db50_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de layouts nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db50_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de layouts nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db50_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db50_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_layouttxt";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layouttxt ";
     $sql .= "      inner join db_layouttxtgrupo  on  db_layouttxtgrupo.db56_sequencial = db_layouttxt.db50_layouttxtgrupo";
     $sql .= "      inner join db_layouttxtgrupotipo  on  db_layouttxtgrupotipo.db57_sequencial = db_layouttxtgrupo.db56_layouttxtgrupotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($db50_codigo!=null ){
         $sql2 .= " where db_layouttxt.db50_codigo = $db50_codigo "; 
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
   function sql_query_file ( $db50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layouttxt ";
     $sql2 = "";
     if($dbwhere==""){
       if($db50_codigo!=null ){
         $sql2 .= " where db_layouttxt.db50_codigo = $db50_codigo "; 
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
  
   function sql_query_campos( $db50_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     
     if($campos != "*" ){
       
     	 $campos_sql = split("#",$campos);
       $virgula = "";
       
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
       
     } else {
       $sql .= $campos;
     }
     
     $sql .= " from db_layouttxt ";
     $sql .= "      inner join db_layoutlinha  on db_layoutlinha.db51_layouttxt    = db_layouttxt.db50_codigo   ";
     $sql .= "      inner join db_layoutcampos on db_layoutcampos.db52_layoutlinha = db_layoutlinha.db51_codigo ";
     
     $sql2 = "";
     
     if($dbwhere==""){
       if($db50_codigo!=null ){
         $sql2 .= " where db_layouttxt.db50_codigo = $db50_codigo "; 
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