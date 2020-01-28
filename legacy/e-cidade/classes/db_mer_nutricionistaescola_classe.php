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
//CLASSE DA ENTIDADE mer_nutricionistaescola
class cl_mer_nutricionistaescola { 
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
   var $me31_i_codigo = 0; 
   var $me31_i_escola = 0; 
   var $me31_i_nutricionista = 0; 
   var $me31_i_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me31_i_codigo = int4 = Cdigo 
                 me31_i_escola = int4 = Escola 
                 me31_i_nutricionista = int4 = Nutricionista 
                 me31_i_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_mer_nutricionistaescola() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_nutricionistaescola"); 
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
       $this->me31_i_codigo = ($this->me31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me31_i_codigo"]:$this->me31_i_codigo);
       $this->me31_i_escola = ($this->me31_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["me31_i_escola"]:$this->me31_i_escola);
       $this->me31_i_nutricionista = ($this->me31_i_nutricionista == ""?@$GLOBALS["HTTP_POST_VARS"]["me31_i_nutricionista"]:$this->me31_i_nutricionista);
       $this->me31_i_ordem = ($this->me31_i_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["me31_i_ordem"]:$this->me31_i_ordem);
     }else{
       $this->me31_i_codigo = ($this->me31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me31_i_codigo"]:$this->me31_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me31_i_codigo){ 
      $this->atualizacampos();
     if($this->me31_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "me31_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me31_i_nutricionista == null ){ 
       $this->erro_sql = " Campo Nutricionista nao Informado.";
       $this->erro_campo = "me31_i_nutricionista";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me31_i_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "me31_i_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me31_i_codigo == "" || $me31_i_codigo == null ){
       $result = db_query("select nextval('mer_nutricionistaescola_me31_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_nutricionistaescola_me31_i_codigo_seq do campo: me31_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me31_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_nutricionistaescola_me31_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me31_i_codigo)){
         $this->erro_sql = " Campo me31_i_codigo maior que ltimo nmero da sequencia.";
         $this->erro_banco = "Sequencia menor que este nmero.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me31_i_codigo = $me31_i_codigo; 
       }
     }
     if(($this->me31_i_codigo == null) || ($this->me31_i_codigo == "") ){ 
       $this->erro_sql = " Campo me31_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_nutricionistaescola(
                                       me31_i_codigo 
                                      ,me31_i_escola 
                                      ,me31_i_nutricionista 
                                      ,me31_i_ordem 
                       )
                values (
                                $this->me31_i_codigo 
                               ,$this->me31_i_escola 
                               ,$this->me31_i_nutricionista 
                               ,$this->me31_i_ordem 
                      )";
          
     $result = db_query($sql); 
     
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Nutricionista Escola ($this->me31_i_codigo) nao Includo. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Nutricionista Escola j Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Nutricionista Escola ($this->me31_i_codigo) nao Includo. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me31_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me31_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16760,'$this->me31_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2952,16760,'','".AddSlashes(pg_result($resaco,0,'me31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2952,16761,'','".AddSlashes(pg_result($resaco,0,'me31_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2952,16762,'','".AddSlashes(pg_result($resaco,0,'me31_i_nutricionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2952,16767,'','".AddSlashes(pg_result($resaco,0,'me31_i_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me31_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_nutricionistaescola set ";
     $virgula = "";
     if(trim($this->me31_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me31_i_codigo"])){ 
       $sql  .= $virgula." me31_i_codigo = $this->me31_i_codigo ";
       $virgula = ",";
       if(trim($this->me31_i_codigo) == null ){ 
         $this->erro_sql = " Campo Cdigo nao Informado.";
         $this->erro_campo = "me31_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me31_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me31_i_escola"])){ 
       $sql  .= $virgula." me31_i_escola = $this->me31_i_escola ";
       $virgula = ",";
       if(trim($this->me31_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "me31_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me31_i_nutricionista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me31_i_nutricionista"])){ 
       $sql  .= $virgula." me31_i_nutricionista = $this->me31_i_nutricionista ";
       $virgula = ",";
       if(trim($this->me31_i_nutricionista) == null ){ 
         $this->erro_sql = " Campo Nutricionista nao Informado.";
         $this->erro_campo = "me31_i_nutricionista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me31_i_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me31_i_ordem"])){ 
       $sql  .= $virgula." me31_i_ordem = $this->me31_i_ordem ";
       $virgula = ",";
       if(trim($this->me31_i_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "me31_i_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me31_i_codigo!=null){
       $sql .= " me31_i_codigo = $this->me31_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me31_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16760,'$this->me31_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me31_i_codigo"]) || $this->me31_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2952,16760,'".AddSlashes(pg_result($resaco,$conresaco,'me31_i_codigo'))."','$this->me31_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me31_i_escola"]) || $this->me31_i_escola != "")
           $resac = db_query("insert into db_acount values($acount,2952,16761,'".AddSlashes(pg_result($resaco,$conresaco,'me31_i_escola'))."','$this->me31_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me31_i_nutricionista"]) || $this->me31_i_nutricionista != "")
           $resac = db_query("insert into db_acount values($acount,2952,16762,'".AddSlashes(pg_result($resaco,$conresaco,'me31_i_nutricionista'))."','$this->me31_i_nutricionista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me31_i_ordem"]) || $this->me31_i_ordem != "")
           $resac = db_query("insert into db_acount values($acount,2952,16767,'".AddSlashes(pg_result($resaco,$conresaco,'me31_i_ordem'))."','$this->me31_i_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Nutricionista Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Nutricionista Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alterao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me31_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me31_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16760,'$me31_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2952,16760,'','".AddSlashes(pg_result($resaco,$iresaco,'me31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2952,16761,'','".AddSlashes(pg_result($resaco,$iresaco,'me31_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2952,16762,'','".AddSlashes(pg_result($resaco,$iresaco,'me31_i_nutricionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2952,16767,'','".AddSlashes(pg_result($resaco,$iresaco,'me31_i_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_nutricionistaescola
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me31_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me31_i_codigo = $me31_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Nutricionista Escola nao Excludo. Excluso Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Nutricionista Escola nao Encontrado. Excluso no Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Excluso efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me31_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_nutricionistaescola";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionistaescola ";
     $sql .= "      inner join mer_nutricionista  on  mer_nutricionista.me02_i_codigo = mer_nutricionistaescola.me31_i_nutricionista";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_nutricionistaescola.me31_i_escola";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
    // $sql .= "      inner join db_usuacgm on db_usuacgm.cgmlogin = cgm.z01_numcgm";
     //$sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     //$sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     //$sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     //$sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     //$sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     //$sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     //$sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     //$sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($me31_i_codigo!=null ){
         $sql2 .= " where mer_nutricionistaescola.me31_i_codigo = $me31_i_codigo "; 
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
   function sql_query_file ( $me31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionistaescola ";
     $sql2 = "";
     if($dbwhere==""){
       if($me31_i_codigo!=null ){
         $sql2 .= " where mer_nutricionistaescola.me31_i_codigo = $me31_i_codigo "; 
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

  function sql_query_nutricionistausuario ($me31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionistaescola ";
     $sql .= "      inner join mer_nutricionista  on  mer_nutricionista.me02_i_codigo = mer_nutricionistaescola.me31_i_nutricionista";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_nutricionistaescola.me31_i_escola";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
     $sql .= "      inner join db_usuacgm on db_usuacgm.cgmlogin = cgm.z01_numcgm";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario = db_usuacgm.id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($me31_i_codigo!=null ){
         $sql2 .= " where mer_nutricionistaescola.me31_i_codigo = $me31_i_codigo "; 
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