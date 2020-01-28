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

//MODULO: agua
//CLASSE DA ENTIDADE aguahidromatricleitura
class cl_aguahidromatricleitura { 
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
   var $x05_codigo = 0; 
   var $x05_codhidrometro = 0; 
   var $x05_codleitura = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x05_codigo = int4 = Código 
                 x05_codhidrometro = int4 = Hidrômetro 
                 x05_codleitura = int4 = Leitura 
                 ";
   //funcao construtor da classe 
   function cl_aguahidromatricleitura() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguahidromatricleitura"); 
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
       $this->x05_codigo = ($this->x05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["x05_codigo"]:$this->x05_codigo);
       $this->x05_codhidrometro = ($this->x05_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x05_codhidrometro"]:$this->x05_codhidrometro);
       $this->x05_codleitura = ($this->x05_codleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x05_codleitura"]:$this->x05_codleitura);
     }else{
       $this->x05_codigo = ($this->x05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["x05_codigo"]:$this->x05_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($x05_codigo){ 
      $this->atualizacampos();
     if($this->x05_codhidrometro == null ){ 
       $this->erro_sql = " Campo Hidrômetro nao Informado.";
       $this->erro_campo = "x05_codhidrometro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x05_codleitura == null ){ 
       $this->erro_sql = " Campo Leitura nao Informado.";
       $this->erro_campo = "x05_codleitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x05_codigo == "" || $x05_codigo == null ){
       $result = db_query("select nextval('aguahidromatricleitura_x05_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguahidromatricleitura_x05_codigo_seq do campo: x05_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x05_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguahidromatricleitura_x05_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $x05_codigo)){
         $this->erro_sql = " Campo x05_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x05_codigo = $x05_codigo; 
       }
     }
     if(($this->x05_codigo == null) || ($this->x05_codigo == "") ){ 
       $this->erro_sql = " Campo x05_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguahidromatricleitura(
                                       x05_codigo 
                                      ,x05_codhidrometro 
                                      ,x05_codleitura 
                       )
                values (
                                $this->x05_codigo 
                               ,$this->x05_codhidrometro 
                               ,$this->x05_codleitura 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguahidromatricleitura ($this->x05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguahidromatricleitura já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguahidromatricleitura ($this->x05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x05_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x05_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9010,'$this->x05_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1541,9010,'','".AddSlashes(pg_result($resaco,0,'x05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1541,9011,'','".AddSlashes(pg_result($resaco,0,'x05_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1541,9012,'','".AddSlashes(pg_result($resaco,0,'x05_codleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x05_codigo=null) { 
      $this->atualizacampos();
     $sql = " update aguahidromatricleitura set ";
     $virgula = "";
     if(trim($this->x05_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x05_codigo"])){ 
       $sql  .= $virgula." x05_codigo = $this->x05_codigo ";
       $virgula = ",";
       if(trim($this->x05_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "x05_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x05_codhidrometro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x05_codhidrometro"])){ 
       $sql  .= $virgula." x05_codhidrometro = $this->x05_codhidrometro ";
       $virgula = ",";
       if(trim($this->x05_codhidrometro) == null ){ 
         $this->erro_sql = " Campo Hidrômetro nao Informado.";
         $this->erro_campo = "x05_codhidrometro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x05_codleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x05_codleitura"])){ 
       $sql  .= $virgula." x05_codleitura = $this->x05_codleitura ";
       $virgula = ",";
       if(trim($this->x05_codleitura) == null ){ 
         $this->erro_sql = " Campo Leitura nao Informado.";
         $this->erro_campo = "x05_codleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x05_codigo!=null){
       $sql .= " x05_codigo = $this->x05_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x05_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9010,'$this->x05_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x05_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1541,9010,'".AddSlashes(pg_result($resaco,$conresaco,'x05_codigo'))."','$this->x05_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x05_codhidrometro"]))
           $resac = db_query("insert into db_acount values($acount,1541,9011,'".AddSlashes(pg_result($resaco,$conresaco,'x05_codhidrometro'))."','$this->x05_codhidrometro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x05_codleitura"]))
           $resac = db_query("insert into db_acount values($acount,1541,9012,'".AddSlashes(pg_result($resaco,$conresaco,'x05_codleitura'))."','$this->x05_codleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguahidromatricleitura nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguahidromatricleitura nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x05_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x05_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9010,'$x05_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1541,9010,'','".AddSlashes(pg_result($resaco,$iresaco,'x05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1541,9011,'','".AddSlashes(pg_result($resaco,$iresaco,'x05_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1541,9012,'','".AddSlashes(pg_result($resaco,$iresaco,'x05_codleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguahidromatricleitura
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x05_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x05_codigo = $x05_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguahidromatricleitura nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguahidromatricleitura nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x05_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguahidromatricleitura";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguahidromatricleitura ";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = aguahidromatricleitura.x05_codhidrometro";
     $sql .= "      inner join agualeitura  on  agualeitura.x21_codleitura = aguahidromatricleitura.x05_codleitura";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agualeitura.x21_usuario";
     //$sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro";
     $sql .= "      inner join aguasitleitura  on  aguasitleitura.x17_codigo = agualeitura.x21_situacao";
     $sql .= "      inner join agualeiturista  on  agualeiturista.x16_numcgm = agualeitura.x21_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x05_codigo!=null ){
         $sql2 .= " where aguahidromatricleitura.x05_codigo = $x05_codigo "; 
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
   function sql_query_file ( $x05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguahidromatricleitura ";
     $sql2 = "";
     if($dbwhere==""){
       if($x05_codigo!=null ){
         $sql2 .= " where aguahidromatricleitura.x05_codigo = $x05_codigo "; 
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