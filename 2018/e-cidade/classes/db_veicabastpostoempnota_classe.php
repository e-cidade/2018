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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicabastpostoempnota
class cl_veicabastpostoempnota { 
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
   var $ve72_codigo = 0; 
   var $ve72_veicabastposto = 0; 
   var $ve72_empnota = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve72_codigo = int4 = Código seq. 
                 ve72_veicabastposto = int4 = Código do posto do abastecimento 
                 ve72_empnota = int4 = Nota 
                 ";
   //funcao construtor da classe 
   function cl_veicabastpostoempnota() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicabastpostoempnota"); 
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
       $this->ve72_codigo = ($this->ve72_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve72_codigo"]:$this->ve72_codigo);
       $this->ve72_veicabastposto = ($this->ve72_veicabastposto == ""?@$GLOBALS["HTTP_POST_VARS"]["ve72_veicabastposto"]:$this->ve72_veicabastposto);
       $this->ve72_empnota = ($this->ve72_empnota == ""?@$GLOBALS["HTTP_POST_VARS"]["ve72_empnota"]:$this->ve72_empnota);
     }else{
       $this->ve72_codigo = ($this->ve72_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve72_codigo"]:$this->ve72_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve72_codigo){ 
      $this->atualizacampos();
     if($this->ve72_veicabastposto == null ){ 
       $this->erro_sql = " Campo Código do posto do abastecimento nao Informado.";
       $this->erro_campo = "ve72_veicabastposto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve72_empnota == null ){ 
       $this->ve72_empnota = "0";
     }
     if($ve72_codigo == "" || $ve72_codigo == null ){
       $result = db_query("select nextval('veicabastpostoempnota_ve72_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicabastpostoempnota_ve72_codigo_seq do campo: ve72_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve72_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicabastpostoempnota_ve72_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve72_codigo)){
         $this->erro_sql = " Campo ve72_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve72_codigo = $ve72_codigo; 
       }
     }
     if(($this->ve72_codigo == null) || ($this->ve72_codigo == "") ){ 
       $this->erro_sql = " Campo ve72_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicabastpostoempnota(
                                       ve72_codigo 
                                      ,ve72_veicabastposto 
                                      ,ve72_empnota 
                       )
                values (
                                $this->ve72_codigo 
                               ,$this->ve72_veicabastposto 
                               ,$this->ve72_empnota 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação do posto com a nota no sistema ($this->ve72_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação do posto com a nota no sistema já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação do posto com a nota no sistema ($this->ve72_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve72_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve72_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9381,'$this->ve72_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1612,9381,'','".AddSlashes(pg_result($resaco,0,'ve72_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1612,9382,'','".AddSlashes(pg_result($resaco,0,'ve72_veicabastposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1612,9383,'','".AddSlashes(pg_result($resaco,0,'ve72_empnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve72_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicabastpostoempnota set ";
     $virgula = "";
     if(trim($this->ve72_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve72_codigo"])){ 
       $sql  .= $virgula." ve72_codigo = $this->ve72_codigo ";
       $virgula = ",";
       if(trim($this->ve72_codigo) == null ){ 
         $this->erro_sql = " Campo Código seq. nao Informado.";
         $this->erro_campo = "ve72_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve72_veicabastposto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve72_veicabastposto"])){ 
       $sql  .= $virgula." ve72_veicabastposto = $this->ve72_veicabastposto ";
       $virgula = ",";
       if(trim($this->ve72_veicabastposto) == null ){ 
         $this->erro_sql = " Campo Código do posto do abastecimento nao Informado.";
         $this->erro_campo = "ve72_veicabastposto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve72_empnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve72_empnota"])){ 
        if(trim($this->ve72_empnota)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ve72_empnota"])){ 
           $this->ve72_empnota = "0" ; 
        } 
       $sql  .= $virgula." ve72_empnota = $this->ve72_empnota ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ve72_codigo!=null){
       $sql .= " ve72_codigo = $this->ve72_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve72_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9381,'$this->ve72_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve72_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1612,9381,'".AddSlashes(pg_result($resaco,$conresaco,'ve72_codigo'))."','$this->ve72_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve72_veicabastposto"]))
           $resac = db_query("insert into db_acount values($acount,1612,9382,'".AddSlashes(pg_result($resaco,$conresaco,'ve72_veicabastposto'))."','$this->ve72_veicabastposto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve72_empnota"]))
           $resac = db_query("insert into db_acount values($acount,1612,9383,'".AddSlashes(pg_result($resaco,$conresaco,'ve72_empnota'))."','$this->ve72_empnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação do posto com a nota no sistema nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve72_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação do posto com a nota no sistema nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve72_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve72_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9381,'$ve72_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1612,9381,'','".AddSlashes(pg_result($resaco,$iresaco,'ve72_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1612,9382,'','".AddSlashes(pg_result($resaco,$iresaco,'ve72_veicabastposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1612,9383,'','".AddSlashes(pg_result($resaco,$iresaco,'ve72_empnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicabastpostoempnota
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve72_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve72_codigo = $ve72_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação do posto com a nota no sistema nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve72_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação do posto com a nota no sistema nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve72_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve72_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicabastpostoempnota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastpostoempnota ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = veicabastpostoempnota.ve72_empnota";
     $sql .= "      inner join veicabastposto  on  veicabastposto.ve71_codigo = veicabastpostoempnota.ve72_veicabastposto";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join veiccadposto  on  veiccadposto.ve29_codigo = veicabastposto.ve71_veiccadposto";
     $sql .= "      inner join veicabast  as a on   a.ve70_codigo = veicabastposto.ve71_veicabast";
     $sql2 = "";
     if($dbwhere==""){
       if($ve72_codigo!=null ){
         $sql2 .= " where veicabastpostoempnota.ve72_codigo = $ve72_codigo "; 
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
   function sql_query_abastposto ( $ve72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from veicabastpostoempnota ";
     $sql .= " inner  join  veicabastposto on veicabastposto.ve71_codigo=veicabastpostoempnota.ve72_veicabastposto ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve72_codigo!=null ){
         $sql2 .= " where veicabastpostoempnota.ve72_codigo = $ve72_codigo ";
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
   function sql_query_file ( $ve72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastpostoempnota ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve72_codigo!=null ){
         $sql2 .= " where veicabastpostoempnota.ve72_codigo = $ve72_codigo "; 
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
   function sql_query_verificanota ( $ve72_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from veicabastpostoempnota ";
     $sql .= " left  join  veicabastposto on veicabastposto.ve71_codigo=veicabastpostoempnota.ve72_veicabastposto ";
     $sql .= " inner join  veicabast      on veicabast.ve70_codigo=veicabastposto.ve71_veicabast ";
     $sql .= " left  join  empnota         on empnota.e69_codnota=veicabastpostoempnota.ve72_empnota ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve72_codigo!=null ){
         $sql2 .= " where veicabastpostoempnota.ve72_codigo = $ve72_codigo ";
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