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
//CLASSE DA ENTIDADE mer_nutricionista
class cl_mer_nutricionista { 
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
   var $me02_i_codigo = 0; 
   var $me02_c_crn = null; 
   var $me02_c_nutriativo = null; 
   var $me02_i_cgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me02_i_codigo = int4 = Código 
                 me02_c_crn = char(10) = N° CRN - Região 
                 me02_c_nutriativo = char(1) = Nutricionista Ativo 
                 me02_i_cgm = int4 = CGM 
                 ";
   //funcao construtor da classe 
   function cl_mer_nutricionista() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_nutricionista"); 
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
       $this->me02_i_codigo = ($this->me02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me02_i_codigo"]:$this->me02_i_codigo);
       $this->me02_c_crn = ($this->me02_c_crn == ""?@$GLOBALS["HTTP_POST_VARS"]["me02_c_crn"]:$this->me02_c_crn);
       $this->me02_c_nutriativo = ($this->me02_c_nutriativo == ""?@$GLOBALS["HTTP_POST_VARS"]["me02_c_nutriativo"]:$this->me02_c_nutriativo);
       $this->me02_i_cgm = ($this->me02_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["me02_i_cgm"]:$this->me02_i_cgm);
     }else{
       $this->me02_i_codigo = ($this->me02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me02_i_codigo"]:$this->me02_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me02_i_codigo){ 
      $this->atualizacampos();
     if($this->me02_c_crn == null ){ 
       $this->erro_sql = " Campo N° CRN - Região nao Informado.";
       $this->erro_campo = "me02_c_crn";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me02_c_nutriativo == null ){ 
       $this->erro_sql = " Campo Nutricionista Ativo nao Informado.";
       $this->erro_campo = "me02_c_nutriativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me02_i_cgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "me02_i_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me02_i_codigo == "" || $me02_i_codigo == null ){
       $result = db_query("select nextval('mernutricionista_me02_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mernutricionista_me02_codigo_seq do campo: me02_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me02_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mernutricionista_me02_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me02_i_codigo)){
         $this->erro_sql = " Campo me02_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me02_i_codigo = $me02_i_codigo; 
       }
     }
     if(($this->me02_i_codigo == null) || ($this->me02_i_codigo == "") ){ 
       $this->erro_sql = " Campo me02_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_nutricionista(
                                       me02_i_codigo 
                                      ,me02_c_crn 
                                      ,me02_c_nutriativo 
                                      ,me02_i_cgm 
                       )
                values (
                                $this->me02_i_codigo 
                               ,'$this->me02_c_crn' 
                               ,'$this->me02_c_nutriativo' 
                               ,$this->me02_i_cgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_nutricionista ($this->me02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_nutricionista já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_nutricionista ($this->me02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me02_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me02_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12715,'$this->me02_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2224,12715,'','".AddSlashes(pg_result($resaco,0,'me02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2224,13775,'','".AddSlashes(pg_result($resaco,0,'me02_c_crn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2224,17071,'','".AddSlashes(pg_result($resaco,0,'me02_c_nutriativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2224,16763,'','".AddSlashes(pg_result($resaco,0,'me02_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me02_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_nutricionista set ";
     $virgula = "";
     if(trim($this->me02_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me02_i_codigo"])){ 
       $sql  .= $virgula." me02_i_codigo = $this->me02_i_codigo ";
       $virgula = ",";
       if(trim($this->me02_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me02_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me02_c_crn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me02_c_crn"])){ 
       $sql  .= $virgula." me02_c_crn = '$this->me02_c_crn' ";
       $virgula = ",";
       if(trim($this->me02_c_crn) == null ){ 
         $this->erro_sql = " Campo N° CRN - Região nao Informado.";
         $this->erro_campo = "me02_c_crn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me02_c_nutriativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me02_c_nutriativo"])){ 
       $sql  .= $virgula." me02_c_nutriativo = '$this->me02_c_nutriativo' ";
       $virgula = ",";
       if(trim($this->me02_c_nutriativo) == null ){ 
         $this->erro_sql = " Campo Nutricionista Ativo nao Informado.";
         $this->erro_campo = "me02_c_nutriativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me02_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me02_i_cgm"])){ 
       $sql  .= $virgula." me02_i_cgm = $this->me02_i_cgm ";
       $virgula = ",";
       if(trim($this->me02_i_cgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "me02_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me02_i_codigo!=null){
       $sql .= " me02_i_codigo = $this->me02_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me02_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12715,'$this->me02_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me02_i_codigo"]) || $this->me02_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2224,12715,'".AddSlashes(pg_result($resaco,$conresaco,'me02_i_codigo'))."','$this->me02_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me02_c_crn"]) || $this->me02_c_crn != "")
           $resac = db_query("insert into db_acount values($acount,2224,13775,'".AddSlashes(pg_result($resaco,$conresaco,'me02_c_crn'))."','$this->me02_c_crn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me02_c_nutriativo"]) || $this->me02_c_nutriativo != "")
           $resac = db_query("insert into db_acount values($acount,2224,17071,'".AddSlashes(pg_result($resaco,$conresaco,'me02_c_nutriativo'))."','$this->me02_c_nutriativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me02_i_cgm"]) || $this->me02_i_cgm != "")
           $resac = db_query("insert into db_acount values($acount,2224,16763,'".AddSlashes(pg_result($resaco,$conresaco,'me02_i_cgm'))."','$this->me02_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_nutricionista nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_nutricionista nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me02_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me02_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12715,'$me02_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2224,12715,'','".AddSlashes(pg_result($resaco,$iresaco,'me02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2224,13775,'','".AddSlashes(pg_result($resaco,$iresaco,'me02_c_crn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2224,17071,'','".AddSlashes(pg_result($resaco,$iresaco,'me02_c_nutriativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2224,16763,'','".AddSlashes(pg_result($resaco,$iresaco,'me02_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_nutricionista
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me02_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me02_i_codigo = $me02_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_nutricionista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_nutricionista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me02_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_nutricionista";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
     // funcao do sql 
   /*function sql_query ( $me02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = mer_nutricionista.me02_i_rechumano";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rechumano.ed20_i_codigo";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicender and  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql2 = "";
     if($dbwhere==""){
       if($me02_i_codigo!=null ){
         $sql2 .= " where mer_nutricionista.me02_i_codigo = $me02_i_codigo "; 
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
  }*/
// funcao do sql
   /*function sql_query ( $codnutricionista=null,$escola=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
     $sql .= "      inner join mer_nutricionistaescola on mer_nutricionistaescola.me31_i_nutricionista=mer_nutricionista.me02_i_codigo";   
     $sql2 = "";
     if($dbwhere==""){
       if($codnutricionista!=null ){
         $sql2 .= " where mer_nutricionista.me02_i_codigo = $codnutricionista "; 
       } 
       if($escola!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " mer_nutricionistaescola.me31_i_escola = $escola "; 
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
  }*/
   
   
   
   function sql_query ( $me02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
     //$sql .= "      inner join mer_nutricionistaescola  on  mer_nutricionistaescola.me31_i_nutricionista = mer_nutricionista.me02_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($me02_i_codigo!=null ){
         $sql2 .= " where mer_nutricionista.me02_i_codigo = $me02_i_codigo "; 
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
function sql_query_nutricionista ( $me02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = mer_nutricionista.me02_i_cgm";
     $sql .= "      inner join mer_nutricionistaescola  on  mer_nutricionistaescola.me31_i_nutricionista = mer_nutricionista.me02_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($me02_i_codigo!=null ){
         $sql2 .= " where mer_nutricionista.me02_i_codigo = $me02_i_codigo "; 
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
   function sql_query_file ( $me02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_nutricionista ";
     $sql2 = "";
     if($dbwhere==""){
       if($me02_i_codigo!=null ){
         $sql2 .= " where mer_nutricionista.me02_i_codigo = $me02_i_codigo "; 
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