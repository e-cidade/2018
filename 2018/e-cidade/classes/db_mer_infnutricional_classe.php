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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_infnutricional
class cl_mer_infnutricional { 
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
   var $me08_i_codigo        = 0; 
   var $me08_i_nutriente        = 0; 
   var $me08_i_alimento        = 0; 
   var $me08_f_quant        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me08_i_codigo = int4 = Código 
                 me08_i_nutriente = int4 = Nutriente 
                 me08_i_alimento = int4 = Alimento 
                 me08_f_quant = float4 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_mer_infnutricional() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_infnutricional"); 
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
       $this->me08_i_codigo = ($this->me08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me08_i_codigo"]:$this->me08_i_codigo);
       $this->me08_i_nutriente = ($this->me08_i_nutriente == ""?@$GLOBALS["HTTP_POST_VARS"]["me08_i_nutriente"]:$this->me08_i_nutriente);
       $this->me08_i_alimento = ($this->me08_i_alimento == ""?@$GLOBALS["HTTP_POST_VARS"]["me08_i_alimento"]:$this->me08_i_alimento);
       $this->me08_f_quant = ($this->me08_f_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["me08_f_quant"]:$this->me08_f_quant);
     }else{
       $this->me08_i_codigo = ($this->me08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me08_i_codigo"]:$this->me08_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me08_i_codigo){ 
      $this->atualizacampos();
     if($this->me08_i_nutriente == null ){ 
       $this->erro_sql = " Campo Nutriente nao Informado.";
       $this->erro_campo = "me08_i_nutriente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me08_i_alimento == null ){ 
       $this->erro_sql = " Campo Alimento nao Informado.";
       $this->erro_campo = "me08_i_alimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me08_f_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "me08_f_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me08_i_codigo == "" || $me08_i_codigo == null ){
       $result = db_query("select nextval('merinfnutricional_me08_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: merinfnutricional_me08_codigo_seq do campo: me08_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me08_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from merinfnutricional_me08_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me08_i_codigo)){
         $this->erro_sql = " Campo me08_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me08_i_codigo = $me08_i_codigo; 
       }
     }
     if(($this->me08_i_codigo == null) || ($this->me08_i_codigo == "") ){ 
       $this->erro_sql = " Campo me08_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_infnutricional(
                                       me08_i_codigo 
                                      ,me08_i_nutriente 
                                      ,me08_i_alimento 
                                      ,me08_f_quant 
                       )
                values (
                                $this->me08_i_codigo 
                               ,$this->me08_i_nutriente 
                               ,$this->me08_i_alimento 
                               ,$this->me08_f_quant 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_infnutricional ($this->me08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_infnutricional já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_infnutricional ($this->me08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me08_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me08_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12789,'$this->me08_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2241,12789,'','".AddSlashes(pg_result($resaco,0,'me08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2241,12793,'','".AddSlashes(pg_result($resaco,0,'me08_i_nutriente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2241,12792,'','".AddSlashes(pg_result($resaco,0,'me08_i_alimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2241,17409,'','".AddSlashes(pg_result($resaco,0,'me08_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me08_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_infnutricional set ";
     $virgula = "";
     if(trim($this->me08_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me08_i_codigo"])){ 
       $sql  .= $virgula." me08_i_codigo = $this->me08_i_codigo ";
       $virgula = ",";
       if(trim($this->me08_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me08_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me08_i_nutriente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me08_i_nutriente"])){ 
       $sql  .= $virgula." me08_i_nutriente = $this->me08_i_nutriente ";
       $virgula = ",";
       if(trim($this->me08_i_nutriente) == null ){ 
         $this->erro_sql = " Campo Nutriente nao Informado.";
         $this->erro_campo = "me08_i_nutriente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me08_i_alimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me08_i_alimento"])){ 
       $sql  .= $virgula." me08_i_alimento = $this->me08_i_alimento ";
       $virgula = ",";
       if(trim($this->me08_i_alimento) == null ){ 
         $this->erro_sql = " Campo Alimento nao Informado.";
         $this->erro_campo = "me08_i_alimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me08_f_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me08_f_quant"])){ 
       $sql  .= $virgula." me08_f_quant = $this->me08_f_quant ";
       $virgula = ",";
       if(trim($this->me08_f_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "me08_f_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me08_i_codigo!=null){
       $sql .= " me08_i_codigo = $this->me08_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me08_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12789,'$this->me08_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me08_i_codigo"]) || $this->me08_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2241,12789,'".AddSlashes(pg_result($resaco,$conresaco,'me08_i_codigo'))."','$this->me08_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me08_i_nutriente"]) || $this->me08_i_nutriente != "")
           $resac = db_query("insert into db_acount values($acount,2241,12793,'".AddSlashes(pg_result($resaco,$conresaco,'me08_i_nutriente'))."','$this->me08_i_nutriente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me08_i_alimento"]) || $this->me08_i_alimento != "")
           $resac = db_query("insert into db_acount values($acount,2241,12792,'".AddSlashes(pg_result($resaco,$conresaco,'me08_i_alimento'))."','$this->me08_i_alimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me08_f_quant"]) || $this->me08_f_quant != "")
           $resac = db_query("insert into db_acount values($acount,2241,17409,'".AddSlashes(pg_result($resaco,$conresaco,'me08_f_quant'))."','$this->me08_f_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_infnutricional nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_infnutricional nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me08_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me08_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12789,'$me08_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2241,12789,'','".AddSlashes(pg_result($resaco,$iresaco,'me08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2241,12793,'','".AddSlashes(pg_result($resaco,$iresaco,'me08_i_nutriente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2241,12792,'','".AddSlashes(pg_result($resaco,$iresaco,'me08_i_alimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2241,17409,'','".AddSlashes(pg_result($resaco,$iresaco,'me08_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_infnutricional
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me08_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me08_i_codigo = $me08_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_infnutricional nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_infnutricional nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me08_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_infnutricional";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_infnutricional ";
     $sql .= "      inner join mer_nutriente  on  mer_nutriente.me09_i_codigo = mer_infnutricional.me08_i_nutriente";
     $sql .= "      inner join mer_alimento  on  mer_alimento.me35_i_codigo = mer_infnutricional.me08_i_alimento";
     $sql .= "      left join matunid  on  matunid.m61_codmatunid = mer_nutriente.me09_i_unidade";
     //$sql .= "      inner join mer_grupoalimento  on  mer_grupoalimento.me30_i_codigo = mer_alimento.me35_i_grupoalimentar";
     $sql2 = "";
     if($dbwhere==""){
       if($me08_i_codigo!=null ){
         $sql2 .= " where mer_infnutricional.me08_i_codigo = $me08_i_codigo "; 
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
   function sql_query_file ( $me08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_infnutricional ";
     $sql2 = "";
     if($dbwhere==""){
       if($me08_i_codigo!=null ){
         $sql2 .= " where mer_infnutricional.me08_i_codigo = $me08_i_codigo "; 
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