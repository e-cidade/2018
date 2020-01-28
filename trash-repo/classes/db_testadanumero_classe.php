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

//MODULO: cadastro
//CLASSE DA ENTIDADE testadanumero
class cl_testadanumero { 
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
   var $j15_codigo = 0; 
   var $j15_idbql = 0; 
   var $j15_face = 0; 
   var $j15_numero = 0; 
   var $j15_compl = null; 
   var $j15_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j15_codigo = int4 = Codigo sequencial 
                 j15_idbql = int4 = Codigo Lote 
                 j15_face = int4 = Face 
                 j15_numero = int4 = Numero do imovel 
                 j15_compl = varchar(50) = Complemento 
                 j15_obs = text = Observacoes 
                 ";
   //funcao construtor da classe 
   function cl_testadanumero() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("testadanumero"); 
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
       $this->j15_codigo = ($this->j15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_codigo"]:$this->j15_codigo);
       $this->j15_idbql = ($this->j15_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_idbql"]:$this->j15_idbql);
       $this->j15_face = ($this->j15_face == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_face"]:$this->j15_face);
       $this->j15_numero = ($this->j15_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_numero"]:$this->j15_numero);
       $this->j15_compl = ($this->j15_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_compl"]:$this->j15_compl);
       $this->j15_obs = ($this->j15_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_obs"]:$this->j15_obs);
     }else{
       $this->j15_codigo = ($this->j15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j15_codigo"]:$this->j15_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j15_codigo){ 
      $this->atualizacampos();
     if($this->j15_idbql == null ){ 
       $this->erro_sql = " Campo Codigo Lote nao Informado.";
       $this->erro_campo = "j15_idbql";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j15_face == null ){ 
       $this->j15_face = "0";
     }
     if($this->j15_numero == null ){ 
       $this->j15_numero = "0";
     }
     if($j15_codigo == "" || $j15_codigo == null ){
       $result = db_query("select nextval('testadanumero_j15_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: testadanumero_j15_codigo_seq do campo: j15_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j15_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from testadanumero_j15_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j15_codigo)){
         $this->erro_sql = " Campo j15_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j15_codigo = $j15_codigo; 
       }
     }
     if(($this->j15_codigo == null) || ($this->j15_codigo == "") ){ 
       $this->erro_sql = " Campo j15_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into testadanumero(
                                       j15_codigo 
                                      ,j15_idbql 
                                      ,j15_face 
                                      ,j15_numero 
                                      ,j15_compl 
                                      ,j15_obs 
                       )
                values (
                                $this->j15_codigo 
                               ,$this->j15_idbql 
                               ,$this->j15_face 
                               ,$this->j15_numero 
                               ,'$this->j15_compl' 
                               ,'$this->j15_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Numero do imovel ($this->j15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Numero do imovel já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Numero do imovel ($this->j15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7926,'$this->j15_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1328,7926,'','".AddSlashes(pg_result($resaco,0,'j15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1328,7927,'','".AddSlashes(pg_result($resaco,0,'j15_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1328,7928,'','".AddSlashes(pg_result($resaco,0,'j15_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1328,7929,'','".AddSlashes(pg_result($resaco,0,'j15_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1328,7930,'','".AddSlashes(pg_result($resaco,0,'j15_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1328,7931,'','".AddSlashes(pg_result($resaco,0,'j15_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j15_codigo=null) { 
      $this->atualizacampos();
     $sql = " update testadanumero set ";
     $virgula = "";
     if(trim($this->j15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j15_codigo"])){ 
       $sql  .= $virgula." j15_codigo = $this->j15_codigo ";
       $virgula = ",";
       if(trim($this->j15_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "j15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j15_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j15_idbql"])){ 
       $sql  .= $virgula." j15_idbql = $this->j15_idbql ";
       $virgula = ",";
       if(trim($this->j15_idbql) == null ){ 
         $this->erro_sql = " Campo Codigo Lote nao Informado.";
         $this->erro_campo = "j15_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j15_face)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j15_face"])){ 
        if(trim($this->j15_face)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j15_face"])){ 
           $this->j15_face = "0" ; 
        } 
       $sql  .= $virgula." j15_face = $this->j15_face ";
       $virgula = ",";
     }
     if(trim($this->j15_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j15_numero"])){ 
        if(trim($this->j15_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j15_numero"])){ 
           $this->j15_numero = "0" ; 
        } 
       $sql  .= $virgula." j15_numero = $this->j15_numero ";
       $virgula = ",";
     }
     if(trim($this->j15_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j15_compl"])){ 
       $sql  .= $virgula." j15_compl = '$this->j15_compl' ";
       $virgula = ",";
     }
     if(trim($this->j15_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j15_obs"])){ 
       $sql  .= $virgula." j15_obs = '$this->j15_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j15_codigo!=null){
       $sql .= " j15_codigo = $this->j15_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j15_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7926,'$this->j15_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j15_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1328,7926,'".AddSlashes(pg_result($resaco,$conresaco,'j15_codigo'))."','$this->j15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j15_idbql"]))
           $resac = db_query("insert into db_acount values($acount,1328,7927,'".AddSlashes(pg_result($resaco,$conresaco,'j15_idbql'))."','$this->j15_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j15_face"]))
           $resac = db_query("insert into db_acount values($acount,1328,7928,'".AddSlashes(pg_result($resaco,$conresaco,'j15_face'))."','$this->j15_face',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j15_numero"]))
           $resac = db_query("insert into db_acount values($acount,1328,7929,'".AddSlashes(pg_result($resaco,$conresaco,'j15_numero'))."','$this->j15_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j15_compl"]))
           $resac = db_query("insert into db_acount values($acount,1328,7930,'".AddSlashes(pg_result($resaco,$conresaco,'j15_compl'))."','$this->j15_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j15_obs"]))
           $resac = db_query("insert into db_acount values($acount,1328,7931,'".AddSlashes(pg_result($resaco,$conresaco,'j15_obs'))."','$this->j15_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Numero do imovel nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Numero do imovel nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j15_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j15_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7926,'$j15_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1328,7926,'','".AddSlashes(pg_result($resaco,$iresaco,'j15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1328,7927,'','".AddSlashes(pg_result($resaco,$iresaco,'j15_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1328,7928,'','".AddSlashes(pg_result($resaco,$iresaco,'j15_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1328,7929,'','".AddSlashes(pg_result($resaco,$iresaco,'j15_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1328,7930,'','".AddSlashes(pg_result($resaco,$iresaco,'j15_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1328,7931,'','".AddSlashes(pg_result($resaco,$iresaco,'j15_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from testadanumero
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j15_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j15_codigo = $j15_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Numero do imovel nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Numero do imovel nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j15_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:testadanumero";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from testadanumero ";
     $sql .= "      inner join testada  on  testada.j36_idbql = testadanumero.j15_idbql and  testada.j36_face = testadanumero.j15_face";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = testada.j36_codigo";
     $sql .= "      inner join lote  on  lote.j34_idbql = testada.j36_idbql";
     $sql .= "      inner join ruas  as a on   a.j14_codigo = testada.j36_codigo";
     $sql .= "      inner join lote  as b on   b.j34_idbql = testada.j36_idbql";
     $sql2 = "";
     if($dbwhere==""){
       if($j15_codigo!=null ){
         $sql2 .= " where testadanumero.j15_codigo = $j15_codigo "; 
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
   function sql_query_file ( $j15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from testadanumero ";
     $sql2 = "";
     if($dbwhere==""){
       if($j15_codigo!=null ){
         $sql2 .= " where testadanumero.j15_codigo = $j15_codigo "; 
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