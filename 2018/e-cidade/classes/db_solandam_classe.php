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

//MODULO: compras
//CLASSE DA ENTIDADE solandam
class cl_solandam { 
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
   var $pc43_codigo = 0; 
   var $pc43_solicitem = 0; 
   var $pc43_ordem = 0; 
   var $pc43_depto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc43_codigo = int4 = C�digo Seq. 
                 pc43_solicitem = int4 = C�d. item da Solicita��o 
                 pc43_ordem = int4 = Ordem Andamento 
                 pc43_depto = int4 = Depart. 
                 ";
   //funcao construtor da classe 
   function cl_solandam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solandam"); 
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
       $this->pc43_codigo = ($this->pc43_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc43_codigo"]:$this->pc43_codigo);
       $this->pc43_solicitem = ($this->pc43_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc43_solicitem"]:$this->pc43_solicitem);
       $this->pc43_ordem = ($this->pc43_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc43_ordem"]:$this->pc43_ordem);
       $this->pc43_depto = ($this->pc43_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["pc43_depto"]:$this->pc43_depto);
     }else{
       $this->pc43_codigo = ($this->pc43_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc43_codigo"]:$this->pc43_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc43_codigo){ 
      $this->atualizacampos();
     if($this->pc43_solicitem == null ){ 
       $this->erro_sql = " Campo C�d. item da Solicita��o nao Informado.";
       $this->erro_campo = "pc43_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc43_ordem == null ){ 
       $this->erro_sql = " Campo Ordem Andamento nao Informado.";
       $this->erro_campo = "pc43_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc43_depto == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "pc43_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc43_codigo == "" || $pc43_codigo == null ){
       $result = db_query("select nextval('solandam_pc43_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solandam_pc43_codigo_seq do campo: pc43_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc43_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from solandam_pc43_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc43_codigo)){
         $this->erro_sql = " Campo pc43_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc43_codigo = $pc43_codigo; 
       }
     }
     if(($this->pc43_codigo == null) || ($this->pc43_codigo == "") ){ 
       $this->erro_sql = " Campo pc43_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solandam(
                                       pc43_codigo 
                                      ,pc43_solicitem 
                                      ,pc43_ordem 
                                      ,pc43_depto 
                       )
                values (
                                $this->pc43_codigo 
                               ,$this->pc43_solicitem 
                               ,$this->pc43_ordem 
                               ,$this->pc43_depto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Andamento da Solicita��o ($this->pc43_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Andamento da Solicita��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Andamento da Solicita��o ($this->pc43_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc43_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc43_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7993,'$this->pc43_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1346,7993,'','".AddSlashes(pg_result($resaco,0,'pc43_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1346,7994,'','".AddSlashes(pg_result($resaco,0,'pc43_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1346,7995,'','".AddSlashes(pg_result($resaco,0,'pc43_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1346,7996,'','".AddSlashes(pg_result($resaco,0,'pc43_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc43_codigo=null) { 
      $this->atualizacampos();
     $sql = " update solandam set ";
     $virgula = "";
     if(trim($this->pc43_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc43_codigo"])){ 
       $sql  .= $virgula." pc43_codigo = $this->pc43_codigo ";
       $virgula = ",";
       if(trim($this->pc43_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo Seq. nao Informado.";
         $this->erro_campo = "pc43_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc43_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc43_solicitem"])){ 
       $sql  .= $virgula." pc43_solicitem = $this->pc43_solicitem ";
       $virgula = ",";
       if(trim($this->pc43_solicitem) == null ){ 
         $this->erro_sql = " Campo C�d. item da Solicita��o nao Informado.";
         $this->erro_campo = "pc43_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc43_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc43_ordem"])){ 
       $sql  .= $virgula." pc43_ordem = $this->pc43_ordem ";
       $virgula = ",";
       if(trim($this->pc43_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem Andamento nao Informado.";
         $this->erro_campo = "pc43_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc43_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc43_depto"])){ 
       $sql  .= $virgula." pc43_depto = $this->pc43_depto ";
       $virgula = ",";
       if(trim($this->pc43_depto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "pc43_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc43_codigo!=null){
       $sql .= " pc43_codigo = $this->pc43_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc43_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7993,'$this->pc43_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc43_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1346,7993,'".AddSlashes(pg_result($resaco,$conresaco,'pc43_codigo'))."','$this->pc43_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc43_solicitem"]))
           $resac = db_query("insert into db_acount values($acount,1346,7994,'".AddSlashes(pg_result($resaco,$conresaco,'pc43_solicitem'))."','$this->pc43_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc43_ordem"]))
           $resac = db_query("insert into db_acount values($acount,1346,7995,'".AddSlashes(pg_result($resaco,$conresaco,'pc43_ordem'))."','$this->pc43_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc43_depto"]))
           $resac = db_query("insert into db_acount values($acount,1346,7996,'".AddSlashes(pg_result($resaco,$conresaco,'pc43_depto'))."','$this->pc43_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento da Solicita��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc43_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento da Solicita��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc43_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc43_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc43_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc43_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7993,'$pc43_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1346,7993,'','".AddSlashes(pg_result($resaco,$iresaco,'pc43_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1346,7994,'','".AddSlashes(pg_result($resaco,$iresaco,'pc43_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1346,7995,'','".AddSlashes(pg_result($resaco,$iresaco,'pc43_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1346,7996,'','".AddSlashes(pg_result($resaco,$iresaco,'pc43_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solandam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc43_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc43_codigo = $pc43_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento da Solicita��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc43_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento da Solicita��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc43_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc43_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:solandam";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandam ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solandam.pc43_depto";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = solandam.pc43_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc43_codigo!=null ){
         $sql2 .= " where solandam.pc43_codigo = $pc43_codigo "; 
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
   function sql_query_and ( $pc43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandam ";
     $sql .= "       left join solandamand on pc42_solandam = pc43_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc43_codigo!=null ){
         $sql2 .= " where solandam.pc43_codigo = $pc43_codigo "; 
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
   function sql_query_andpad ( $pc43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandam ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solandam.pc43_depto";
     $sql .= "       left join solandamand on pc42_solandam = pc43_codigo";
     $sql .= "       left join solandpadrao on pc43_solicitem = pc47_solicitem and pc47_ordem=pc43_ordem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc43_codigo!=null ){
         $sql2 .= " where solandam.pc43_codigo = $pc43_codigo "; 
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
   function sql_query_file ( $pc43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandam ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc43_codigo!=null ){
         $sql2 .= " where solandam.pc43_codigo = $pc43_codigo "; 
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
   function sql_query_proc ( $pc43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandam ";
     $sql .= "      inner join pctipoandam on pctipoandam.pc44_codigo = solandam.pc43_ordem";
     $sql .= "      inner join db_depart   on db_depart.coddepto      = solandam.pc43_depto";
     $sql .= "      inner join solicitem   on solicitem.pc11_codigo   = solandam.pc43_solicitem";
     $sql .= "      inner join solicita    on solicita.pc10_numero    = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc43_codigo!=null ){
         $sql2 .= " where solandam.pc43_codigo = $pc43_codigo "; 
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