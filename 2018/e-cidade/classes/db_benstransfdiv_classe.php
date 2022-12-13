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

//MODULO: patrim
//CLASSE DA ENTIDADE benstransfdiv
class cl_benstransfdiv { 
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
   var $t31_codigo = 0; 
   var $t31_codtran = 0; 
   var $t31_bem = 0; 
   var $t31_divisao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t31_codigo = int4 = Código 
                 t31_codtran = int8 = Transferência 
                 t31_bem = int8 = Código do bem 
                 t31_divisao = int4 = Código da divisão 
                 ";
   //funcao construtor da classe 
   function cl_benstransfdiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benstransfdiv"); 
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
       $this->t31_codigo = ($this->t31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t31_codigo"]:$this->t31_codigo);
       $this->t31_codtran = ($this->t31_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t31_codtran"]:$this->t31_codtran);
       $this->t31_bem = ($this->t31_bem == ""?@$GLOBALS["HTTP_POST_VARS"]["t31_bem"]:$this->t31_bem);
       $this->t31_divisao = ($this->t31_divisao == ""?@$GLOBALS["HTTP_POST_VARS"]["t31_divisao"]:$this->t31_divisao);
     }else{
       $this->t31_codigo = ($this->t31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t31_codigo"]:$this->t31_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($t31_codigo){ 
      $this->atualizacampos();
     if($this->t31_codtran == null ){ 
       $this->erro_sql = " Campo Transferência nao Informado.";
       $this->erro_campo = "t31_codtran";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t31_bem == null ){ 
       $this->erro_sql = " Campo Código do bem nao Informado.";
       $this->erro_campo = "t31_bem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t31_divisao == null ){ 
       $this->erro_sql = " Campo Código da divisão nao Informado.";
       $this->erro_campo = "t31_divisao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t31_codigo == "" || $t31_codigo == null ){
       $result = db_query("select nextval('benstransfdiv_t31_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benstransfdiv_t31_codigo_seq do campo: t31_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t31_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from benstransfdiv_t31_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t31_codigo)){
         $this->erro_sql = " Campo t31_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t31_codigo = $t31_codigo; 
       }
     }
     if(($this->t31_codigo == null) || ($this->t31_codigo == "") ){ 
       $this->erro_sql = " Campo t31_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benstransfdiv(
                                       t31_codigo 
                                      ,t31_codtran 
                                      ,t31_bem 
                                      ,t31_divisao 
                       )
                values (
                                $this->t31_codigo 
                               ,$this->t31_codtran 
                               ,$this->t31_bem 
                               ,$this->t31_divisao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "divisão para qual vai ser transferido o bem ($this->t31_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "divisão para qual vai ser transferido o bem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "divisão para qual vai ser transferido o bem ($this->t31_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t31_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t31_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8937,'$this->t31_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1527,8937,'','".AddSlashes(pg_result($resaco,0,'t31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1527,8938,'','".AddSlashes(pg_result($resaco,0,'t31_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1527,8939,'','".AddSlashes(pg_result($resaco,0,'t31_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1527,8940,'','".AddSlashes(pg_result($resaco,0,'t31_divisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t31_codigo=null) { 
      $this->atualizacampos();
     $sql = " update benstransfdiv set ";
     $virgula = "";
     if(trim($this->t31_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t31_codigo"])){ 
       $sql  .= $virgula." t31_codigo = $this->t31_codigo ";
       $virgula = ",";
       if(trim($this->t31_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t31_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t31_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t31_codtran"])){ 
       $sql  .= $virgula." t31_codtran = $this->t31_codtran ";
       $virgula = ",";
       if(trim($this->t31_codtran) == null ){ 
         $this->erro_sql = " Campo Transferência nao Informado.";
         $this->erro_campo = "t31_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t31_bem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t31_bem"])){ 
       $sql  .= $virgula." t31_bem = $this->t31_bem ";
       $virgula = ",";
       if(trim($this->t31_bem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t31_bem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t31_divisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t31_divisao"])){ 
       $sql  .= $virgula." t31_divisao = $this->t31_divisao ";
       $virgula = ",";
       if(trim($this->t31_divisao) == null ){ 
         $this->erro_sql = " Campo Código da divisão nao Informado.";
         $this->erro_campo = "t31_divisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t31_codigo!=null){
       $sql .= " t31_codigo = $this->t31_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t31_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8937,'$this->t31_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t31_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1527,8937,'".AddSlashes(pg_result($resaco,$conresaco,'t31_codigo'))."','$this->t31_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t31_codtran"]))
           $resac = db_query("insert into db_acount values($acount,1527,8938,'".AddSlashes(pg_result($resaco,$conresaco,'t31_codtran'))."','$this->t31_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t31_bem"]))
           $resac = db_query("insert into db_acount values($acount,1527,8939,'".AddSlashes(pg_result($resaco,$conresaco,'t31_bem'))."','$this->t31_bem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t31_divisao"]))
           $resac = db_query("insert into db_acount values($acount,1527,8940,'".AddSlashes(pg_result($resaco,$conresaco,'t31_divisao'))."','$this->t31_divisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divisão para qual vai ser transferido o bem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t31_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divisão para qual vai ser transferido o bem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t31_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t31_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8937,'$t31_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1527,8937,'','".AddSlashes(pg_result($resaco,$iresaco,'t31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1527,8938,'','".AddSlashes(pg_result($resaco,$iresaco,'t31_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1527,8939,'','".AddSlashes(pg_result($resaco,$iresaco,'t31_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1527,8940,'','".AddSlashes(pg_result($resaco,$iresaco,'t31_divisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benstransfdiv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t31_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t31_codigo = $t31_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divisão para qual vai ser transferido o bem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t31_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divisão para qual vai ser transferido o bem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t31_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:benstransfdiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransfdiv ";
     $sql .= "      inner join bens  on  bens.t52_bem = benstransfdiv.t31_bem";
     $sql .= "      inner join benstransf  on  benstransf.t93_codtran = benstransfdiv.t31_codtran";
     $sql .= "      inner join departdiv  on  departdiv.t30_codigo = benstransfdiv.t31_divisao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  as a on   a.coddepto = benstransf.t93_depart";
     $sql .= "      inner join cgm  as b on   b.z01_numcgm = departdiv.t30_numcgm";
     $sql .= "      inner join db_depart  as c on   c.coddepto = departdiv.t30_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($t31_codigo!=null ){
         $sql2 .= " where benstransfdiv.t31_codigo = $t31_codigo "; 
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
   function sql_query_file ( $t31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransfdiv ";
     $sql2 = "";
     if($dbwhere==""){
       if($t31_codigo!=null ){
         $sql2 .= " where benstransfdiv.t31_codigo = $t31_codigo "; 
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