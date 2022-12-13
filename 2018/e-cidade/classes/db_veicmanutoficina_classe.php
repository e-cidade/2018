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
//CLASSE DA ENTIDADE veicmanutoficina
class cl_veicmanutoficina { 
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
   var $ve66_codigo = 0; 
   var $ve66_veicmanut = 0; 
   var $ve66_veiccadoficinas = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve66_codigo = int4 = Código Seq. 
                 ve66_veicmanut = int4 = Manutenção 
                 ve66_veiccadoficinas = int4 = Oficina 
                 ";
   //funcao construtor da classe 
   function cl_veicmanutoficina() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanutoficina"); 
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
       $this->ve66_codigo = ($this->ve66_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_codigo"]:$this->ve66_codigo);
       $this->ve66_veicmanut = ($this->ve66_veicmanut == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_veicmanut"]:$this->ve66_veicmanut);
       $this->ve66_veiccadoficinas = ($this->ve66_veiccadoficinas == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_veiccadoficinas"]:$this->ve66_veiccadoficinas);
     }else{
       $this->ve66_codigo = ($this->ve66_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_codigo"]:$this->ve66_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve66_codigo){ 
      $this->atualizacampos();
     if($this->ve66_veicmanut == null ){ 
       $this->erro_sql = " Campo Manutenção nao Informado.";
       $this->erro_campo = "ve66_veicmanut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_veiccadoficinas == null ){ 
       $this->erro_sql = " Campo Oficina nao Informado.";
       $this->erro_campo = "ve66_veiccadoficinas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve66_codigo == "" || $ve66_codigo == null ){
       $result = db_query("select nextval('veicmanutoficina_ve66_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanutoficina_ve66_codigo_seq do campo: ve66_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve66_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmanutoficina_ve66_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve66_codigo)){
         $this->erro_sql = " Campo ve66_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve66_codigo = $ve66_codigo; 
       }
     }
     if(($this->ve66_codigo == null) || ($this->ve66_codigo == "") ){ 
       $this->erro_sql = " Campo ve66_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanutoficina(
                                       ve66_codigo 
                                      ,ve66_veicmanut 
                                      ,ve66_veiccadoficinas 
                       )
                values (
                                $this->ve66_codigo 
                               ,$this->ve66_veicmanut 
                               ,$this->ve66_veiccadoficinas 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação da manutenção com uma oficina. ($this->ve66_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação da manutenção com uma oficina. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação da manutenção com uma oficina. ($this->ve66_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve66_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9349,'$this->ve66_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1607,9349,'','".AddSlashes(pg_result($resaco,0,'ve66_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1607,9350,'','".AddSlashes(pg_result($resaco,0,'ve66_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1607,9351,'','".AddSlashes(pg_result($resaco,0,'ve66_veiccadoficinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve66_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicmanutoficina set ";
     $virgula = "";
     if(trim($this->ve66_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_codigo"])){ 
       $sql  .= $virgula." ve66_codigo = $this->ve66_codigo ";
       $virgula = ",";
       if(trim($this->ve66_codigo) == null ){ 
         $this->erro_sql = " Campo Código Seq. nao Informado.";
         $this->erro_campo = "ve66_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_veicmanut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_veicmanut"])){ 
       $sql  .= $virgula." ve66_veicmanut = $this->ve66_veicmanut ";
       $virgula = ",";
       if(trim($this->ve66_veicmanut) == null ){ 
         $this->erro_sql = " Campo Manutenção nao Informado.";
         $this->erro_campo = "ve66_veicmanut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_veiccadoficinas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_veiccadoficinas"])){ 
       $sql  .= $virgula." ve66_veiccadoficinas = $this->ve66_veiccadoficinas ";
       $virgula = ",";
       if(trim($this->ve66_veiccadoficinas) == null ){ 
         $this->erro_sql = " Campo Oficina nao Informado.";
         $this->erro_campo = "ve66_veiccadoficinas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve66_codigo!=null){
       $sql .= " ve66_codigo = $this->ve66_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve66_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9349,'$this->ve66_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1607,9349,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_codigo'))."','$this->ve66_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_veicmanut"]))
           $resac = db_query("insert into db_acount values($acount,1607,9350,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_veicmanut'))."','$this->ve66_veicmanut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_veiccadoficinas"]))
           $resac = db_query("insert into db_acount values($acount,1607,9351,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_veiccadoficinas'))."','$this->ve66_veiccadoficinas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação da manutenção com uma oficina. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação da manutenção com uma oficina. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve66_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve66_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9349,'$ve66_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1607,9349,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1607,9350,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1607,9351,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_veiccadoficinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmanutoficina
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve66_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve66_codigo = $ve66_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação da manutenção com uma oficina. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve66_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação da manutenção com uma oficina. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve66_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve66_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmanutoficina";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve66_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutoficina ";
     $sql .= "      inner join veiccadoficinas  on  veiccadoficinas.ve27_codigo = veicmanutoficina.ve66_veiccadoficinas";
     $sql .= "      inner join veicmanut  on  veicmanut.ve62_codigo = veicmanutoficina.ve66_veicmanut";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = veiccadoficinas.ve27_numcgm";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql2 = "";
     if($dbwhere==""){
       if($ve66_codigo!=null ){
         $sql2 .= " where veicmanutoficina.ve66_codigo = $ve66_codigo "; 
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
   function sql_query_file ( $ve66_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutoficina ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve66_codigo!=null ){
         $sql2 .= " where veicmanutoficina.ve66_codigo = $ve66_codigo "; 
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