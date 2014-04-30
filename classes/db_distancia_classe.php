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

//MODULO: educação
//CLASSE DA ENTIDADE distancia
class cl_distancia { 
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
   var $ed223_i_codigo = 0; 
   var $ed223_f_km = 0; 
   var $ed223_i_bairroorigem = 0; 
   var $ed223_i_bairrodestino = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed223_i_codigo = int4 = Código 
                 ed223_f_km = float4 = Distancia entre Bairros (em metros) 
                 ed223_i_bairroorigem = int4 = Bairro Origem 
                 ed223_i_bairrodestino = int4 = Bairro Destino 
                 ";
   //funcao construtor da classe 
   function cl_distancia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("distancia"); 
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
       $this->ed223_i_codigo = ($this->ed223_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed223_i_codigo"]:$this->ed223_i_codigo);
       $this->ed223_f_km = ($this->ed223_f_km == ""?@$GLOBALS["HTTP_POST_VARS"]["ed223_f_km"]:$this->ed223_f_km);
       $this->ed223_i_bairroorigem = ($this->ed223_i_bairroorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed223_i_bairroorigem"]:$this->ed223_i_bairroorigem);
       $this->ed223_i_bairrodestino = ($this->ed223_i_bairrodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed223_i_bairrodestino"]:$this->ed223_i_bairrodestino);
     }else{
       $this->ed223_i_codigo = ($this->ed223_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed223_i_codigo"]:$this->ed223_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed223_i_codigo){ 
      $this->atualizacampos();
     if($this->ed223_f_km == null ){ 
       $this->erro_sql = " Campo Distancia entre Bairros (em metros) nao Informado.";
       $this->erro_campo = "ed223_f_km";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed223_i_bairroorigem == null ){ 
       $this->erro_sql = " Campo Bairro Origem nao Informado.";
       $this->erro_campo = "ed223_i_bairroorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed223_i_bairrodestino == null ){ 
       $this->erro_sql = " Campo Bairro Destino nao Informado.";
       $this->erro_campo = "ed223_i_bairrodestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed223_i_codigo == "" || $ed223_i_codigo == null ){
       $result = db_query("select nextval('distancia_ed223_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: distancia_ed223_i_codigo_seq do campo: ed223_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed223_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from distancia_ed223_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed223_i_codigo)){
         $this->erro_sql = " Campo ed223_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed223_i_codigo = $ed223_i_codigo; 
       }
     }
     if(($this->ed223_i_codigo == null) || ($this->ed223_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed223_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into distancia(
                                       ed223_i_codigo 
                                      ,ed223_f_km 
                                      ,ed223_i_bairroorigem 
                                      ,ed223_i_bairrodestino 
                       )
                values (
                                $this->ed223_i_codigo 
                               ,$this->ed223_f_km 
                               ,$this->ed223_i_bairroorigem 
                               ,$this->ed223_i_bairrodestino 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "distancia ($this->ed223_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "distancia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "distancia ($this->ed223_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed223_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed223_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11157,'$this->ed223_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1926,11157,'','".AddSlashes(pg_result($resaco,0,'ed223_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1926,11158,'','".AddSlashes(pg_result($resaco,0,'ed223_f_km'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1926,11159,'','".AddSlashes(pg_result($resaco,0,'ed223_i_bairroorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1926,11160,'','".AddSlashes(pg_result($resaco,0,'ed223_i_bairrodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed223_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update distancia set ";
     $virgula = "";
     if(trim($this->ed223_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed223_i_codigo"])){ 
       $sql  .= $virgula." ed223_i_codigo = $this->ed223_i_codigo ";
       $virgula = ",";
       if(trim($this->ed223_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed223_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed223_f_km)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed223_f_km"])){ 
       $sql  .= $virgula." ed223_f_km = $this->ed223_f_km ";
       $virgula = ",";
       if(trim($this->ed223_f_km) == null ){ 
         $this->erro_sql = " Campo Distancia entre Bairros (em metros) nao Informado.";
         $this->erro_campo = "ed223_f_km";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed223_i_bairroorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed223_i_bairroorigem"])){ 
       $sql  .= $virgula." ed223_i_bairroorigem = $this->ed223_i_bairroorigem ";
       $virgula = ",";
       if(trim($this->ed223_i_bairroorigem) == null ){ 
         $this->erro_sql = " Campo Bairro Origem nao Informado.";
         $this->erro_campo = "ed223_i_bairroorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed223_i_bairrodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed223_i_bairrodestino"])){ 
       $sql  .= $virgula." ed223_i_bairrodestino = $this->ed223_i_bairrodestino ";
       $virgula = ",";
       if(trim($this->ed223_i_bairrodestino) == null ){ 
         $this->erro_sql = " Campo Bairro Destino nao Informado.";
         $this->erro_campo = "ed223_i_bairrodestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed223_i_codigo!=null){
       $sql .= " ed223_i_codigo = $this->ed223_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed223_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11157,'$this->ed223_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed223_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1926,11157,'".AddSlashes(pg_result($resaco,$conresaco,'ed223_i_codigo'))."','$this->ed223_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed223_f_km"]))
           $resac = db_query("insert into db_acount values($acount,1926,11158,'".AddSlashes(pg_result($resaco,$conresaco,'ed223_f_km'))."','$this->ed223_f_km',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed223_i_bairroorigem"]))
           $resac = db_query("insert into db_acount values($acount,1926,11159,'".AddSlashes(pg_result($resaco,$conresaco,'ed223_i_bairroorigem'))."','$this->ed223_i_bairroorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed223_i_bairrodestino"]))
           $resac = db_query("insert into db_acount values($acount,1926,11160,'".AddSlashes(pg_result($resaco,$conresaco,'ed223_i_bairrodestino'))."','$this->ed223_i_bairrodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "distancia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed223_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "distancia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed223_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed223_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed223_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed223_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11157,'$ed223_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1926,11157,'','".AddSlashes(pg_result($resaco,$iresaco,'ed223_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1926,11158,'','".AddSlashes(pg_result($resaco,$iresaco,'ed223_f_km'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1926,11159,'','".AddSlashes(pg_result($resaco,$iresaco,'ed223_i_bairroorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1926,11160,'','".AddSlashes(pg_result($resaco,$iresaco,'ed223_i_bairrodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from distancia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed223_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed223_i_codigo = $ed223_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "distancia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed223_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "distancia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed223_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed223_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:distancia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed223_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from distancia ";
     $sql .= "      inner join bairro as bairroorigem on  bairroorigem.j13_codi = distancia.ed223_i_bairroorigem";
     $sql .= "      inner join bairro as bairrodestino on  bairrodestino.j13_codi = distancia.ed223_i_bairrodestino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed223_i_codigo!=null ){
         $sql2 .= " where distancia.ed223_i_codigo = $ed223_i_codigo "; 
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
   function sql_query_file ( $ed223_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from distancia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed223_i_codigo!=null ){
         $sql2 .= " where distancia.ed223_i_codigo = $ed223_i_codigo "; 
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