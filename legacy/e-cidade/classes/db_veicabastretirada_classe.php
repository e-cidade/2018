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
//CLASSE DA ENTIDADE veicabastretirada
class cl_veicabastretirada { 
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
   var $ve73_codigo = 0; 
   var $ve73_veicabast = 0; 
   var $ve73_veicretirada = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve73_codigo = int4 = Código seq. 
                 ve73_veicabast = int4 = Abastecimento 
                 ve73_veicretirada = int4 = Retirada 
                 ";
   //funcao construtor da classe 
   function cl_veicabastretirada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicabastretirada"); 
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
       $this->ve73_codigo = ($this->ve73_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve73_codigo"]:$this->ve73_codigo);
       $this->ve73_veicabast = ($this->ve73_veicabast == ""?@$GLOBALS["HTTP_POST_VARS"]["ve73_veicabast"]:$this->ve73_veicabast);
       $this->ve73_veicretirada = ($this->ve73_veicretirada == ""?@$GLOBALS["HTTP_POST_VARS"]["ve73_veicretirada"]:$this->ve73_veicretirada);
     }else{
       $this->ve73_codigo = ($this->ve73_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve73_codigo"]:$this->ve73_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve73_codigo){ 
      $this->atualizacampos();
     if($this->ve73_veicabast == null ){ 
       $this->erro_sql = " Campo Abastecimento nao Informado.";
       $this->erro_campo = "ve73_veicabast";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve73_veicretirada == null ){ 
       $this->erro_sql = " Campo Retirada nao Informado.";
       $this->erro_campo = "ve73_veicretirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve73_codigo == "" || $ve73_codigo == null ){
       $result = db_query("select nextval('veicabastretirada_ve73_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicabastretirada_ve73_codigo_seq do campo: ve73_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve73_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicabastretirada_ve73_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve73_codigo)){
         $this->erro_sql = " Campo ve73_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve73_codigo = $ve73_codigo; 
       }
     }
     if(($this->ve73_codigo == null) || ($this->ve73_codigo == "") ){ 
       $this->erro_sql = " Campo ve73_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicabastretirada(
                                       ve73_codigo 
                                      ,ve73_veicabast 
                                      ,ve73_veicretirada 
                       )
                values (
                                $this->ve73_codigo 
                               ,$this->ve73_veicabast 
                               ,$this->ve73_veicretirada 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ligação do abastecimento com a retirada do veiculo ($this->ve73_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ligação do abastecimento com a retirada do veiculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ligação do abastecimento com a retirada do veiculo ($this->ve73_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve73_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve73_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9384,'$this->ve73_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1613,9384,'','".AddSlashes(pg_result($resaco,0,'ve73_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1613,9385,'','".AddSlashes(pg_result($resaco,0,'ve73_veicabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1613,9386,'','".AddSlashes(pg_result($resaco,0,'ve73_veicretirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve73_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicabastretirada set ";
     $virgula = "";
     if(trim($this->ve73_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve73_codigo"])){ 
       $sql  .= $virgula." ve73_codigo = $this->ve73_codigo ";
       $virgula = ",";
       if(trim($this->ve73_codigo) == null ){ 
         $this->erro_sql = " Campo Código seq. nao Informado.";
         $this->erro_campo = "ve73_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve73_veicabast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve73_veicabast"])){ 
       $sql  .= $virgula." ve73_veicabast = $this->ve73_veicabast ";
       $virgula = ",";
       if(trim($this->ve73_veicabast) == null ){ 
         $this->erro_sql = " Campo Abastecimento nao Informado.";
         $this->erro_campo = "ve73_veicabast";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve73_veicretirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve73_veicretirada"])){ 
       $sql  .= $virgula." ve73_veicretirada = $this->ve73_veicretirada ";
       $virgula = ",";
       if(trim($this->ve73_veicretirada) == null ){ 
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "ve73_veicretirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve73_codigo!=null){
       $sql .= " ve73_codigo = $this->ve73_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve73_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9384,'$this->ve73_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve73_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1613,9384,'".AddSlashes(pg_result($resaco,$conresaco,'ve73_codigo'))."','$this->ve73_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve73_veicabast"]))
           $resac = db_query("insert into db_acount values($acount,1613,9385,'".AddSlashes(pg_result($resaco,$conresaco,'ve73_veicabast'))."','$this->ve73_veicabast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve73_veicretirada"]))
           $resac = db_query("insert into db_acount values($acount,1613,9386,'".AddSlashes(pg_result($resaco,$conresaco,'ve73_veicretirada'))."','$this->ve73_veicretirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ligação do abastecimento com a retirada do veiculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve73_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ligação do abastecimento com a retirada do veiculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve73_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve73_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve73_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve73_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9384,'$ve73_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1613,9384,'','".AddSlashes(pg_result($resaco,$iresaco,'ve73_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1613,9385,'','".AddSlashes(pg_result($resaco,$iresaco,'ve73_veicabast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1613,9386,'','".AddSlashes(pg_result($resaco,$iresaco,'ve73_veicretirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicabastretirada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve73_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve73_codigo = $ve73_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ligação do abastecimento com a retirada do veiculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve73_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ligação do abastecimento com a retirada do veiculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve73_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve73_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicabastretirada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve73_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastretirada ";
     $sql .= "      inner join veicretirada  on  veicretirada.ve60_codigo = veicabastretirada.ve73_veicretirada";
     $sql .= "      inner join veicabast  on  veicabast.ve70_codigo = veicabastretirada.ve73_veicabast";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicretirada.ve60_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = veicretirada.ve60_coddepto";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicretirada.ve60_veiculo";
     $sql .= "      inner join veicmotoristas  on  veicmotoristas.ve05_codigo = veicretirada.ve60_veicmotoristas";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = veicabast.ve70_usuario";
     $sql .= "      inner join veiccadcomb  on  veiccadcomb.ve26_codigo = veicabast.ve70_veiculoscomb";
     $sql .= "      inner join veiculos  as b on   b.ve01_codigo = veicabast.ve70_veiculos";
     $sql2 = "";
     if($dbwhere==""){
       if($ve73_codigo!=null ){
         $sql2 .= " where veicabastretirada.ve73_codigo = $ve73_codigo "; 
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
   function sql_query_file ( $ve73_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicabastretirada ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve73_codigo!=null ){
         $sql2 .= " where veicabastretirada.ve73_codigo = $ve73_codigo "; 
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