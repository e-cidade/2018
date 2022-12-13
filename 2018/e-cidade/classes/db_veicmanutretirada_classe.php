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
//CLASSE DA ENTIDADE veicmanutretirada
class cl_veicmanutretirada { 
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
   var $ve65_codigo = 0; 
   var $ve65_veicmanut = 0; 
   var $ve65_veicretirada = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve65_codigo = int4 = C�digo Seq. 
                 ve65_veicmanut = int4 = Manuten��o 
                 ve65_veicretirada = int4 = Retirada 
                 ";
   //funcao construtor da classe 
   function cl_veicmanutretirada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanutretirada"); 
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
       $this->ve65_codigo = ($this->ve65_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve65_codigo"]:$this->ve65_codigo);
       $this->ve65_veicmanut = ($this->ve65_veicmanut == ""?@$GLOBALS["HTTP_POST_VARS"]["ve65_veicmanut"]:$this->ve65_veicmanut);
       $this->ve65_veicretirada = ($this->ve65_veicretirada == ""?@$GLOBALS["HTTP_POST_VARS"]["ve65_veicretirada"]:$this->ve65_veicretirada);
     }else{
       $this->ve65_codigo = ($this->ve65_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve65_codigo"]:$this->ve65_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve65_codigo){ 
      $this->atualizacampos();
     if($this->ve65_veicmanut == null ){ 
       $this->erro_sql = " Campo Manuten��o nao Informado.";
       $this->erro_campo = "ve65_veicmanut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve65_veicretirada == null ){ 
       $this->erro_sql = " Campo Retirada nao Informado.";
       $this->erro_campo = "ve65_veicretirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve65_codigo == "" || $ve65_codigo == null ){
       $result = db_query("select nextval('veicmanutretirada_ve65_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanutretirada_ve65_codigo_seq do campo: ve65_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve65_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmanutretirada_ve65_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve65_codigo)){
         $this->erro_sql = " Campo ve65_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve65_codigo = $ve65_codigo; 
       }
     }
     if(($this->ve65_codigo == null) || ($this->ve65_codigo == "") ){ 
       $this->erro_sql = " Campo ve65_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanutretirada(
                                       ve65_codigo 
                                      ,ve65_veicmanut 
                                      ,ve65_veicretirada 
                       )
                values (
                                $this->ve65_codigo 
                               ,$this->ve65_veicmanut 
                               ,$this->ve65_veicretirada 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liga��o de uma manuten��o e uma retirada ($this->ve65_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liga��o de uma manuten��o e uma retirada j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liga��o de uma manuten��o e uma retirada ($this->ve65_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve65_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve65_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9346,'$this->ve65_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1606,9346,'','".AddSlashes(pg_result($resaco,0,'ve65_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1606,9347,'','".AddSlashes(pg_result($resaco,0,'ve65_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1606,9348,'','".AddSlashes(pg_result($resaco,0,'ve65_veicretirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve65_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicmanutretirada set ";
     $virgula = "";
     if(trim($this->ve65_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve65_codigo"])){ 
       $sql  .= $virgula." ve65_codigo = $this->ve65_codigo ";
       $virgula = ",";
       if(trim($this->ve65_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo Seq. nao Informado.";
         $this->erro_campo = "ve65_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve65_veicmanut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve65_veicmanut"])){ 
       $sql  .= $virgula." ve65_veicmanut = $this->ve65_veicmanut ";
       $virgula = ",";
       if(trim($this->ve65_veicmanut) == null ){ 
         $this->erro_sql = " Campo Manuten��o nao Informado.";
         $this->erro_campo = "ve65_veicmanut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve65_veicretirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve65_veicretirada"])){ 
       $sql  .= $virgula." ve65_veicretirada = $this->ve65_veicretirada ";
       $virgula = ",";
       if(trim($this->ve65_veicretirada) == null ){ 
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "ve65_veicretirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve65_codigo!=null){
       $sql .= " ve65_codigo = $this->ve65_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve65_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9346,'$this->ve65_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve65_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1606,9346,'".AddSlashes(pg_result($resaco,$conresaco,'ve65_codigo'))."','$this->ve65_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve65_veicmanut"]))
           $resac = db_query("insert into db_acount values($acount,1606,9347,'".AddSlashes(pg_result($resaco,$conresaco,'ve65_veicmanut'))."','$this->ve65_veicmanut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve65_veicretirada"]))
           $resac = db_query("insert into db_acount values($acount,1606,9348,'".AddSlashes(pg_result($resaco,$conresaco,'ve65_veicretirada'))."','$this->ve65_veicretirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga��o de uma manuten��o e uma retirada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve65_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga��o de uma manuten��o e uma retirada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve65_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve65_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve65_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve65_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9346,'$ve65_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1606,9346,'','".AddSlashes(pg_result($resaco,$iresaco,'ve65_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1606,9347,'','".AddSlashes(pg_result($resaco,$iresaco,'ve65_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1606,9348,'','".AddSlashes(pg_result($resaco,$iresaco,'ve65_veicretirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmanutretirada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve65_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve65_codigo = $ve65_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga��o de uma manuten��o e uma retirada nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve65_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga��o de uma manuten��o e uma retirada nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve65_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve65_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:veicmanutretirada";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve65_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutretirada ";
     $sql .= "      inner join veicretirada  on  veicretirada.ve60_codigo = veicmanutretirada.ve65_veicretirada";
     $sql .= "      inner join veicmanut  on  veicmanut.ve62_codigo = veicmanutretirada.ve65_veicmanut";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicretirada.ve60_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = veicretirada.ve60_coddepto";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicretirada.ve60_veiculo";
     $sql .= "      inner join veicmotoristas  on  veicmotoristas.ve05_codigo = veicretirada.ve60_veicmotoristas";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql2 = "";
     if($dbwhere==""){
       if($ve65_codigo!=null ){
         $sql2 .= " where veicmanutretirada.ve65_codigo = $ve65_codigo "; 
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
   function sql_query_file ( $ve65_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutretirada ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve65_codigo!=null ){
         $sql2 .= " where veicmanutretirada.ve65_codigo = $ve65_codigo "; 
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