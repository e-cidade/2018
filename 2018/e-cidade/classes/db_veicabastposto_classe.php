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
//CLASSE DA ENTIDADE veicabastposto
class cl_veicabastposto { 
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
   var $ve71_codigo = 0; 
   var $ve71_veicabast = 0; 
   var $ve71_veiccadposto = 0; 
   var $ve71_nota = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve71_codigo = int4 = Código seq. 
                 ve71_veicabast = int4 = Abastecimento 
                 ve71_veiccadposto = int4 = Posto 
                 ve71_nota = varchar(20) = Nº da nota fiscal 
                 ";
   //funcao construtor da classe 
   function cl_veicabastposto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicabastposto"); 
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
       $this->ve71_codigo = ($this->ve71_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve71_codigo"]:$this->ve71_codigo);
       $this->ve71_veicabast = ($this->ve71_veicabast == ""?@$GLOBALS["HTTP_POST_VARS"]["ve71_veicabast"]:$this->ve71_veicabast);
       $this->ve71_veiccadposto = ($this->ve71_veiccadposto == ""?@$GLOBALS["HTTP_POST_VARS"]["ve71_veiccadposto"]:$this->ve71_veiccadposto);
       $this->ve71_nota = ($this->ve71_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["ve71_nota"]:$this->ve71_nota);
     }else{
       $this->ve71_codigo = ($this->ve71_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve71_codigo"]:$this->ve71_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve71_codigo){ 
      $this->atualizacampos();
     if($this->ve71_veicabast == null ){ 
       $this->erro_sql = " Campo Abastecimento nao Informado.";
       $this->erro_campo = "ve71_veicabast";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve71_veiccadposto == null ){ 
       $this->erro_sql = " Campo Posto nao Informado.";
       $this->erro_campo = "ve71_veiccadposto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve71_codigo == "" || $ve71_codigo == null ){
       $result = db_query("select nextval('veicabastposto_ve71_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicabastposto_ve71_codigo_seq do campo: ve71_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve71_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicabastposto_ve71_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve71_codigo)){
         $this->erro_sql = " Campo ve71_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve71_codigo = $ve71_codigo; 
       }
     }
     if(($this->ve71_codigo == null) || ($this->ve71_codigo == "") ){ 
       $this->erro_sql = " Campo ve71_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicabastposto(
                                       ve71_codigo 
                                      ,ve71_veicabast 
                                      ,ve71_veiccadposto 
                                      ,ve71_nota 
                       )
                values (
                                $this->ve71_codigo 
                               ,$this->ve71_veicabast 
                               ,$this->ve71_veiccadposto 
                               ,'$this->ve71_nota' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Posto que foi realizado o abastecimento ($this->ve71_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Posto que foi realizado o abastecimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Posto que foi realizado o abastecimento ($this->ve71_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve71_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve71_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9377,'$this->ve71_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1611,9377,'','".AddSlashes(pg_result($resaco,0,'ve71_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1611,9378,'','".AddSlashes(pg_result($resaco,0,'ve71_veicabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1611,9379,'','".AddSlashes(pg_result($resaco,0,'ve71_veiccadposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1611,9380,'','".AddSlashes(pg_result($resaco,0,'ve71_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve71_codigo=null,$dbwhere=null) { 
      $this->atualizacampos();
     $sql = " update veicabastposto set ";
     $virgula = "";
     if(trim($this->ve71_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve71_codigo"])){ 
       $sql  .= $virgula." ve71_codigo = $this->ve71_codigo ";
       $virgula = ",";
       if(trim($this->ve71_codigo) == null ){ 
         $this->erro_sql = " Campo Código seq. nao Informado.";
         $this->erro_campo = "ve71_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve71_veicabast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve71_veicabast"])){ 
       $sql  .= $virgula." ve71_veicabast = $this->ve71_veicabast ";
       $virgula = ",";
       if(trim($this->ve71_veicabast) == null ){ 
         $this->erro_sql = " Campo Abastecimento nao Informado.";
         $this->erro_campo = "ve71_veicabast";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve71_veiccadposto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve71_veiccadposto"])){ 
       $sql  .= $virgula." ve71_veiccadposto = $this->ve71_veiccadposto ";
       $virgula = ",";
       if(trim($this->ve71_veiccadposto) == null ){ 
         $this->erro_sql = " Campo Posto nao Informado.";
         $this->erro_campo = "ve71_veiccadposto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve71_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve71_nota"])){ 
       $sql  .= $virgula." ve71_nota = '$this->ve71_nota' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ve71_codigo!=null){
       $sql .= " ve71_codigo = $this->ve71_codigo";
     }
     if ($ve71_codigo!=null&&$dbwhere!=null){
          $sql .= " and ";
     }

     if ($dbwhere != null){
          $sql .= $dbwhere;
     }

     $resaco = $this->sql_record($this->sql_query_file($this->ve71_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9377,'$this->ve71_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve71_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1611,9377,'".AddSlashes(pg_result($resaco,$conresaco,'ve71_codigo'))."','$this->ve71_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve71_veicabast"]))
           $resac = db_query("insert into db_acount values($acount,1611,9378,'".AddSlashes(pg_result($resaco,$conresaco,'ve71_veicabast'))."','$this->ve71_veicabast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve71_veiccadposto"]))
           $resac = db_query("insert into db_acount values($acount,1611,9379,'".AddSlashes(pg_result($resaco,$conresaco,'ve71_veiccadposto'))."','$this->ve71_veiccadposto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve71_nota"]))
           $resac = db_query("insert into db_acount values($acount,1611,9380,'".AddSlashes(pg_result($resaco,$conresaco,'ve71_nota'))."','$this->ve71_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Posto que foi realizado o abastecimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve71_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Posto que foi realizado o abastecimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve71_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve71_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve71_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve71_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9377,'$ve71_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1611,9377,'','".AddSlashes(pg_result($resaco,$iresaco,'ve71_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1611,9378,'','".AddSlashes(pg_result($resaco,$iresaco,'ve71_veicabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1611,9379,'','".AddSlashes(pg_result($resaco,$iresaco,'ve71_veiccadposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1611,9380,'','".AddSlashes(pg_result($resaco,$iresaco,'ve71_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicabastposto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve71_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve71_codigo = $ve71_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Posto que foi realizado o abastecimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve71_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Posto que foi realizado o abastecimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve71_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve71_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicabastposto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve71_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastposto ";
     $sql .= "      inner join veiccadposto on veiccadposto.ve29_codigo     = veicabastposto.ve71_veiccadposto";
     $sql .= "      inner join veicabast    on veicabast.ve70_codigo        = veicabastposto.ve71_veicabast";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario       = veicabast.ve70_usuario";
     $sql .= "      inner join veiculoscomb on veiculoscomb.ve06_sequencial = veicabast.ve70_veiculoscomb";
     $sql .= "      inner join veiccadcomb  on veiccadcomb.ve26_codigo      = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiculos     on veiculos.ve01_codigo         = veicabast.ve70_veiculos";
     $sql2 = "";
     if($dbwhere==""){
       if($ve71_codigo!=null ){
         $sql2 .= " where veicabastposto.ve71_codigo = $ve71_codigo "; 
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
   function sql_query_file ( $ve71_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastposto ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve71_codigo!=null ){
         $sql2 .= " where veicabastposto.ve71_codigo = $ve71_codigo "; 
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
   function sql_query_tip ( $ve71_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastposto ";
     $sql .= "      left join veicabastpostoempnota on veicabastpostoempnota.ve72_veicabastposto=veicabastposto.ve71_codigo ";
     $sql .= "      left join empnota on empnota.e69_codnota=veicabastpostoempnota.ve72_empnota";
     $sql .= "      inner join veiccadposto on veiccadposto.ve29_codigo = veicabastposto.ve71_veiccadposto";
     $sql .= "      inner join veicabast    on veicabast.ve70_codigo = veicabastposto.ve71_veicabast";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = veicabast.ve70_usuario";
     $sql .= "      inner join veiccadcomb  on  veiccadcomb.ve26_codigo = veicabast.ve70_veiculoscomb";
     $sql .= "      inner join veiculos     on veiculos.ve01_codigo         = veicabast.ve70_veiculos";
     $sql .= " 		left join veiccadpostointerno on veiccadpostointerno.ve35_veiccadposto = veiccadposto.ve29_codigo";
     $sql .= " 		left join db_depart           on db_depart.coddepto                    = veiccadpostointerno.ve35_depart";
     $sql .= " 		left join veiccadpostoexterno on veiccadpostoexterno.ve34_veiccadposto = veiccadposto.ve29_codigo";
     $sql .= " 		left join cgm                 on cgm.z01_numcgm                        = veiccadpostoexterno.ve34_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ve71_codigo!=null ){
         $sql2 .= " where veicabastposto.ve71_codigo = $ve71_codigo "; 
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