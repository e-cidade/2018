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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_restriitem
class cl_mer_restriitem { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me25_i_codigo        = 0; 
   var $me25_i_restricao        = 0; 
   var $me25_i_alimento        = 0; 
   var $me25_i_alimentosub        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me25_i_codigo = int4 = Codigo 
                 me25_i_restricao = int4 = Restrição 
                 me25_i_alimento = int4 = Alimento 
                 me25_i_alimentosub = int4 = Alimento Substituto 
                 ";
   //funcao construtor da classe 
   function cl_mer_restriitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_restriitem"); 
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
       $this->me25_i_codigo = ($this->me25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me25_i_codigo"]:$this->me25_i_codigo);
       $this->me25_i_restricao = ($this->me25_i_restricao == ""?@$GLOBALS["HTTP_POST_VARS"]["me25_i_restricao"]:$this->me25_i_restricao);
       $this->me25_i_alimento = ($this->me25_i_alimento == ""?@$GLOBALS["HTTP_POST_VARS"]["me25_i_alimento"]:$this->me25_i_alimento);
       $this->me25_i_alimentosub = ($this->me25_i_alimentosub == ""?@$GLOBALS["HTTP_POST_VARS"]["me25_i_alimentosub"]:$this->me25_i_alimentosub);
     }else{
       $this->me25_i_codigo = ($this->me25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me25_i_codigo"]:$this->me25_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me25_i_codigo){ 
      $this->atualizacampos();
     if($this->me25_i_restricao == null ){ 
       $this->erro_sql = " Campo Restrição nao Informado.";
       $this->erro_campo = "me25_i_restricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me25_i_alimento == null ){ 
       $this->erro_sql = " Campo Alimento nao Informado.";
       $this->erro_campo = "me25_i_alimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me25_i_alimentosub == null ){ 
       $this->erro_sql = " Campo Alimento Substituto nao Informado.";
       $this->erro_campo = "me25_i_alimentosub";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me25_i_codigo == "" || $me25_i_codigo == null ){
       $result = db_query("select nextval('mer_restriitem_me25_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_restriitem_me25_codigo_seq do campo: me25_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me25_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_restriitem_me25_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me25_i_codigo)){
         $this->erro_sql = " Campo me25_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me25_i_codigo = $me25_i_codigo; 
       }
     }
     if(($this->me25_i_codigo == null) || ($this->me25_i_codigo == "") ){ 
       $this->erro_sql = " Campo me25_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_restriitem(
                                       me25_i_codigo 
                                      ,me25_i_restricao 
                                      ,me25_i_alimento 
                                      ,me25_i_alimentosub 
                       )
                values (
                                $this->me25_i_codigo 
                               ,$this->me25_i_restricao 
                               ,$this->me25_i_alimento 
                               ,$this->me25_i_alimentosub 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Restrição por item ($this->me25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Restrição por item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Restrição por item ($this->me25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me25_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me25_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13728,'$this->me25_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2400,13728,'','".AddSlashes(pg_result($resaco,0,'me25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2400,13729,'','".AddSlashes(pg_result($resaco,0,'me25_i_restricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2400,13730,'','".AddSlashes(pg_result($resaco,0,'me25_i_alimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2400,14235,'','".AddSlashes(pg_result($resaco,0,'me25_i_alimentosub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me25_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_restriitem set ";
     $virgula = "";
     if(trim($this->me25_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me25_i_codigo"])){ 
       $sql  .= $virgula." me25_i_codigo = $this->me25_i_codigo ";
       $virgula = ",";
       if(trim($this->me25_i_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "me25_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me25_i_restricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me25_i_restricao"])){ 
       $sql  .= $virgula." me25_i_restricao = $this->me25_i_restricao ";
       $virgula = ",";
       if(trim($this->me25_i_restricao) == null ){ 
         $this->erro_sql = " Campo Restrição nao Informado.";
         $this->erro_campo = "me25_i_restricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me25_i_alimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me25_i_alimento"])){ 
       $sql  .= $virgula." me25_i_alimento = $this->me25_i_alimento ";
       $virgula = ",";
       if(trim($this->me25_i_alimento) == null ){ 
         $this->erro_sql = " Campo Alimento nao Informado.";
         $this->erro_campo = "me25_i_alimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me25_i_alimentosub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me25_i_alimentosub"])){ 
       $sql  .= $virgula." me25_i_alimentosub = $this->me25_i_alimentosub ";
       $virgula = ",";
       if(trim($this->me25_i_alimentosub) == null ){ 
         $this->erro_sql = " Campo Alimento Substituto nao Informado.";
         $this->erro_campo = "me25_i_alimentosub";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me25_i_codigo!=null){
       $sql .= " me25_i_codigo = $this->me25_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me25_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13728,'$this->me25_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me25_i_codigo"]) || $this->me25_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2400,13728,'".AddSlashes(pg_result($resaco,$conresaco,'me25_i_codigo'))."','$this->me25_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me25_i_restricao"]) || $this->me25_i_restricao != "")
           $resac = db_query("insert into db_acount values($acount,2400,13729,'".AddSlashes(pg_result($resaco,$conresaco,'me25_i_restricao'))."','$this->me25_i_restricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me25_i_alimento"]) || $this->me25_i_alimento != "")
           $resac = db_query("insert into db_acount values($acount,2400,13730,'".AddSlashes(pg_result($resaco,$conresaco,'me25_i_alimento'))."','$this->me25_i_alimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me25_i_alimentosub"]) || $this->me25_i_alimentosub != "")
           $resac = db_query("insert into db_acount values($acount,2400,14235,'".AddSlashes(pg_result($resaco,$conresaco,'me25_i_alimentosub'))."','$this->me25_i_alimentosub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Restrição por item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Restrição por item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me25_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me25_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13728,'$me25_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2400,13728,'','".AddSlashes(pg_result($resaco,$iresaco,'me25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2400,13729,'','".AddSlashes(pg_result($resaco,$iresaco,'me25_i_restricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2400,13730,'','".AddSlashes(pg_result($resaco,$iresaco,'me25_i_alimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2400,14235,'','".AddSlashes(pg_result($resaco,$iresaco,'me25_i_alimentosub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_restriitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me25_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me25_i_codigo = $me25_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Restrição por item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Restrição por item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me25_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_restriitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_restriitem ";
     $sql .= "      left join mer_restricao  on  mer_restricao.me24_i_codigo = mer_restriitem.me25_i_restricao";
     $sql .= "      left join mer_alimento  on  mer_alimento.me35_i_codigo = mer_restriitem.me25_i_alimento";
     $sql .= "      left join mer_alimento as alimento  on   alimento.me35_i_codigo = mer_restriitem.me25_i_alimentosub";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = mer_restricao.me24_i_aluno";
     $sql .= "      left join matunid  on  matunid.m61_codmatunid = mer_alimento.me35_i_unidade";
     $sql .= "      left join mer_grupoalimento  on  mer_grupoalimento.me30_i_codigo = mer_alimento.me35_i_grupoalimentar";
     $sql2 = "";
     if($dbwhere==""){
       if($me25_i_codigo!=null ){
         $sql2 .= " where mer_restriitem.me25_i_codigo = $me25_i_codigo "; 
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
  function sql_query_relatorio ( $me25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_restriitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = mer_restriitem.me25_i_alimento";
     $sql .= "      inner join mer_restricao  on  mer_restricao.me24_i_codigo = mer_restriitem.me25_i_restricao";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = mer_restricao.me24_i_aluno";
     $sql .= "      inner join matmater as matmatersub  on  matmatersub.m60_codmater = mer_restriitem.me25_i_alimentosub";
     $sql2 = "";
     if($dbwhere==""){
       if($me25_i_codigo!=null ){
         $sql2 .= " where mer_restriitem.me25_i_codigo = $me25_i_codigo "; 
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
   function sql_query_file ( $me25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_restriitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($me25_i_codigo!=null ){
         $sql2 .= " where mer_restriitem.me25_i_codigo = $me25_i_codigo "; 
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