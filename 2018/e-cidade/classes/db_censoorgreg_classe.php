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

//MODULO: Escola
//CLASSE DA ENTIDADE censoorgreg
class cl_censoorgreg { 
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
   var $ed263_i_codigo = 0; 
   var $ed263_i_censouf = 0; 
   var $ed263_i_codigocenso = null; 
   var $ed263_c_nome = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed263_i_codigo = int8 = Código 
                 ed263_i_censouf = int4 = Estado 
                 ed263_i_codigocenso = char(5) = Código Censo 
                 ed263_c_nome = char(100) = Nome 
                 ";
   //funcao construtor da classe 
   function cl_censoorgreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censoorgreg"); 
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
       $this->ed263_i_codigo = ($this->ed263_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed263_i_codigo"]:$this->ed263_i_codigo);
       $this->ed263_i_censouf = ($this->ed263_i_censouf == ""?@$GLOBALS["HTTP_POST_VARS"]["ed263_i_censouf"]:$this->ed263_i_censouf);
       $this->ed263_i_codigocenso = ($this->ed263_i_codigocenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed263_i_codigocenso"]:$this->ed263_i_codigocenso);
       $this->ed263_c_nome = ($this->ed263_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed263_c_nome"]:$this->ed263_c_nome);
     }else{
       $this->ed263_i_codigo = ($this->ed263_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed263_i_codigo"]:$this->ed263_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed263_i_codigo){ 
      $this->atualizacampos();
     if($this->ed263_i_censouf == null ){ 
       $this->erro_sql = " Campo Estado nao Informado.";
       $this->erro_campo = "ed263_i_censouf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed263_i_codigocenso == null ){ 
       $this->erro_sql = " Campo Código Censo nao Informado.";
       $this->erro_campo = "ed263_i_codigocenso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed263_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ed263_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed263_i_codigo == "" || $ed263_i_codigo == null ){
       $result = db_query("select nextval('censoorgreg_ed263_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: censoorgreg_ed263_i_codigo_seq do campo: ed263_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed263_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from censoorgreg_ed263_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed263_i_codigo)){
         $this->erro_sql = " Campo ed263_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed263_i_codigo = $ed263_i_codigo; 
       }
     }
     if(($this->ed263_i_codigo == null) || ($this->ed263_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed263_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoorgreg(
                                       ed263_i_codigo 
                                      ,ed263_i_censouf 
                                      ,ed263_i_codigocenso 
                                      ,ed263_c_nome 
                       )
                values (
                                $this->ed263_i_codigo 
                               ,$this->ed263_i_censouf 
                               ,'$this->ed263_i_codigocenso' 
                               ,'$this->ed263_c_nome' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela Nacional de Órgãos Regionais  Censo Escolar ($this->ed263_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela Nacional de Órgãos Regionais  Censo Escolar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela Nacional de Órgãos Regionais  Censo Escolar ($this->ed263_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed263_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed263_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13357,'$this->ed263_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2341,13357,'','".AddSlashes(pg_result($resaco,0,'ed263_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2341,13358,'','".AddSlashes(pg_result($resaco,0,'ed263_i_censouf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2341,13359,'','".AddSlashes(pg_result($resaco,0,'ed263_i_codigocenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2341,13360,'','".AddSlashes(pg_result($resaco,0,'ed263_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed263_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update censoorgreg set ";
     $virgula = "";
     if(trim($this->ed263_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed263_i_codigo"])){ 
       $sql  .= $virgula." ed263_i_codigo = $this->ed263_i_codigo ";
       $virgula = ",";
       if(trim($this->ed263_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed263_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed263_i_censouf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed263_i_censouf"])){ 
       $sql  .= $virgula." ed263_i_censouf = $this->ed263_i_censouf ";
       $virgula = ",";
       if(trim($this->ed263_i_censouf) == null ){ 
         $this->erro_sql = " Campo Estado nao Informado.";
         $this->erro_campo = "ed263_i_censouf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed263_i_codigocenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed263_i_codigocenso"])){ 
       $sql  .= $virgula." ed263_i_codigocenso = '$this->ed263_i_codigocenso' ";
       $virgula = ",";
       if(trim($this->ed263_i_codigocenso) == null ){ 
         $this->erro_sql = " Campo Código Censo nao Informado.";
         $this->erro_campo = "ed263_i_codigocenso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed263_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed263_c_nome"])){ 
       $sql  .= $virgula." ed263_c_nome = '$this->ed263_c_nome' ";
       $virgula = ",";
       if(trim($this->ed263_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ed263_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed263_i_codigo!=null){
       $sql .= " ed263_i_codigo = $this->ed263_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed263_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13357,'$this->ed263_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed263_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2341,13357,'".AddSlashes(pg_result($resaco,$conresaco,'ed263_i_codigo'))."','$this->ed263_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed263_i_censouf"]))
           $resac = db_query("insert into db_acount values($acount,2341,13358,'".AddSlashes(pg_result($resaco,$conresaco,'ed263_i_censouf'))."','$this->ed263_i_censouf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed263_i_codigocenso"]))
           $resac = db_query("insert into db_acount values($acount,2341,13359,'".AddSlashes(pg_result($resaco,$conresaco,'ed263_i_codigocenso'))."','$this->ed263_i_codigocenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed263_c_nome"]))
           $resac = db_query("insert into db_acount values($acount,2341,13360,'".AddSlashes(pg_result($resaco,$conresaco,'ed263_c_nome'))."','$this->ed263_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela Nacional de Órgãos Regionais  Censo Escolar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed263_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela Nacional de Órgãos Regionais  Censo Escolar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed263_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed263_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed263_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed263_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13357,'$ed263_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2341,13357,'','".AddSlashes(pg_result($resaco,$iresaco,'ed263_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2341,13358,'','".AddSlashes(pg_result($resaco,$iresaco,'ed263_i_censouf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2341,13359,'','".AddSlashes(pg_result($resaco,$iresaco,'ed263_i_codigocenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2341,13360,'','".AddSlashes(pg_result($resaco,$iresaco,'ed263_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from censoorgreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed263_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed263_i_codigo = $ed263_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela Nacional de Órgãos Regionais  Censo Escolar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed263_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela Nacional de Órgãos Regionais  Censo Escolar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed263_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed263_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:censoorgreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed263_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from censoorgreg ";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = censoorgreg.ed263_i_censouf";
     $sql2 = "";
     if($dbwhere==""){
       if($ed263_i_codigo!=null ){
         $sql2 .= " where censoorgreg.ed263_i_codigo = $ed263_i_codigo "; 
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
   function sql_query_file ( $ed263_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from censoorgreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed263_i_codigo!=null ){
         $sql2 .= " where censoorgreg.ed263_i_codigo = $ed263_i_codigo "; 
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