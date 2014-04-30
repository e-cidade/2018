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

//MODULO: protocolo
//CLASSE DA ENTIDADE cepbairros
class cl_cepbairros { 
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
   var $cp01_codlocalidade = 0; 
   var $cp01_bairro = null; 
   var $cp01_sigla = null; 
   var $cp01_codbairro = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cp01_codlocalidade = int8 = Codigo da Localidade 
                 cp01_bairro = varchar(72) = Nome do bairro 
                 cp01_sigla = varchar(2) = Sigla 
                 cp01_codbairro = int8 = Codigo do Bairro 
                 ";
   //funcao construtor da classe 
   function cl_cepbairros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cepbairros"); 
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
       $this->cp01_codlocalidade = ($this->cp01_codlocalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["cp01_codlocalidade"]:$this->cp01_codlocalidade);
       $this->cp01_bairro = ($this->cp01_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp01_bairro"]:$this->cp01_bairro);
       $this->cp01_sigla = ($this->cp01_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["cp01_sigla"]:$this->cp01_sigla);
       $this->cp01_codbairro = ($this->cp01_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp01_codbairro"]:$this->cp01_codbairro);
     }else{
       $this->cp01_codbairro = ($this->cp01_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp01_codbairro"]:$this->cp01_codbairro);
     }
   }
   // funcao para inclusao
   function incluir ($cp01_codbairro){ 
      $this->atualizacampos();
     if($this->cp01_codlocalidade == null ){ 
       $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
       $this->erro_campo = "cp01_codlocalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp01_bairro == null ){ 
       $this->erro_sql = " Campo Nome do bairro nao Informado.";
       $this->erro_campo = "cp01_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp01_sigla == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "cp01_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cp01_codbairro = $cp01_codbairro; 
     if(($this->cp01_codbairro == null) || ($this->cp01_codbairro == "") ){ 
       $this->erro_sql = " Campo cp01_codbairro nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cepbairros(
                                       cp01_codlocalidade 
                                      ,cp01_bairro 
                                      ,cp01_sigla 
                                      ,cp01_codbairro 
                       )
                values (
                                $this->cp01_codlocalidade 
                               ,'$this->cp01_bairro' 
                               ,'$this->cp01_sigla' 
                               ,$this->cp01_codbairro 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Bairros ($this->cp01_codbairro) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Bairros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Bairros ($this->cp01_codbairro) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp01_codbairro;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cp01_codbairro));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7173,'$this->cp01_codbairro','I')");
       $resac = db_query("insert into db_acount values($acount,1192,7176,'','".AddSlashes(pg_result($resaco,0,'cp01_codlocalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1192,7174,'','".AddSlashes(pg_result($resaco,0,'cp01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1192,7175,'','".AddSlashes(pg_result($resaco,0,'cp01_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1192,7173,'','".AddSlashes(pg_result($resaco,0,'cp01_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cp01_codbairro=null) { 
      $this->atualizacampos();
     $sql = " update cepbairros set ";
     $virgula = "";
     if(trim($this->cp01_codlocalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp01_codlocalidade"])){ 
       $sql  .= $virgula." cp01_codlocalidade = $this->cp01_codlocalidade ";
       $virgula = ",";
       if(trim($this->cp01_codlocalidade) == null ){ 
         $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
         $this->erro_campo = "cp01_codlocalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp01_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp01_bairro"])){ 
       $sql  .= $virgula." cp01_bairro = '$this->cp01_bairro' ";
       $virgula = ",";
       if(trim($this->cp01_bairro) == null ){ 
         $this->erro_sql = " Campo Nome do bairro nao Informado.";
         $this->erro_campo = "cp01_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp01_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp01_sigla"])){ 
       $sql  .= $virgula." cp01_sigla = '$this->cp01_sigla' ";
       $virgula = ",";
       if(trim($this->cp01_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "cp01_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp01_codbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp01_codbairro"])){ 
       $sql  .= $virgula." cp01_codbairro = $this->cp01_codbairro ";
       $virgula = ",";
       if(trim($this->cp01_codbairro) == null ){ 
         $this->erro_sql = " Campo Codigo do Bairro nao Informado.";
         $this->erro_campo = "cp01_codbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cp01_codbairro!=null){
       $sql .= " cp01_codbairro = $this->cp01_codbairro";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cp01_codbairro));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7173,'$this->cp01_codbairro','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp01_codlocalidade"]))
           $resac = db_query("insert into db_acount values($acount,1192,7176,'".AddSlashes(pg_result($resaco,$conresaco,'cp01_codlocalidade'))."','$this->cp01_codlocalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp01_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1192,7174,'".AddSlashes(pg_result($resaco,$conresaco,'cp01_bairro'))."','$this->cp01_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp01_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1192,7175,'".AddSlashes(pg_result($resaco,$conresaco,'cp01_sigla'))."','$this->cp01_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp01_codbairro"]))
           $resac = db_query("insert into db_acount values($acount,1192,7173,'".AddSlashes(pg_result($resaco,$conresaco,'cp01_codbairro'))."','$this->cp01_codbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Bairros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp01_codbairro;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Bairros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp01_codbairro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp01_codbairro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cp01_codbairro=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cp01_codbairro));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7173,'$cp01_codbairro','E')");
         $resac = db_query("insert into db_acount values($acount,1192,7176,'','".AddSlashes(pg_result($resaco,$iresaco,'cp01_codlocalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1192,7174,'','".AddSlashes(pg_result($resaco,$iresaco,'cp01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1192,7175,'','".AddSlashes(pg_result($resaco,$iresaco,'cp01_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1192,7173,'','".AddSlashes(pg_result($resaco,$iresaco,'cp01_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cepbairros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cp01_codbairro != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp01_codbairro = $cp01_codbairro ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Bairros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cp01_codbairro;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Bairros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cp01_codbairro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cp01_codbairro;
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
        $this->erro_sql   = "Record Vazio na Tabela:cepbairros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cp01_codbairro=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cepbairros ";
     $sql .= "      inner join cepestados  on  cepestados.cp03_sigla = cepbairros.cp01_sigla";
     $sql2 = "";
     if($dbwhere==""){
       if($cp01_codbairro!=null ){
         $sql2 .= " where cepbairros.cp01_codbairro = $cp01_codbairro "; 
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
   function sql_query_file ( $cp01_codbairro=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cepbairros ";
     $sql2 = "";
     if($dbwhere==""){
       if($cp01_codbairro!=null ){
         $sql2 .= " where cepbairros.cp01_codbairro = $cp01_codbairro "; 
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