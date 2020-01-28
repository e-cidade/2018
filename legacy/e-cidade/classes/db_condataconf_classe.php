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

//MODULO: contabilidade
//CLASSE DA ENTIDADE condataconf
class cl_condataconf { 
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
   var $c99_anousu = 0; 
   var $c99_instit = 0; 
   var $c99_data_dia = null; 
   var $c99_data_mes = null; 
   var $c99_data_ano = null; 
   var $c99_data = null; 
   var $c99_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c99_anousu = int4 = Exercício 
                 c99_instit = int4 = Instittuição 
                 c99_data = date = Data Limite 
                 c99_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_condataconf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("condataconf"); 
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
       $this->c99_anousu = ($this->c99_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_anousu"]:$this->c99_anousu);
       $this->c99_instit = ($this->c99_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_instit"]:$this->c99_instit);
       if($this->c99_data == ""){
         $this->c99_data_dia = ($this->c99_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_data_dia"]:$this->c99_data_dia);
         $this->c99_data_mes = ($this->c99_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_data_mes"]:$this->c99_data_mes);
         $this->c99_data_ano = ($this->c99_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_data_ano"]:$this->c99_data_ano);
         if($this->c99_data_dia != ""){
            $this->c99_data = $this->c99_data_ano."-".$this->c99_data_mes."-".$this->c99_data_dia;
         }
       }
       $this->c99_usuario = ($this->c99_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_usuario"]:$this->c99_usuario);
     }else{
       $this->c99_anousu = ($this->c99_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_anousu"]:$this->c99_anousu);
       $this->c99_instit = ($this->c99_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c99_instit"]:$this->c99_instit);
     }
   }
   // funcao para inclusao
   function incluir ($c99_anousu,$c99_instit){ 
      $this->atualizacampos();
     if($this->c99_data == null ){ 
       $this->c99_data = "null";
     }
     if($this->c99_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "c99_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c99_anousu = $c99_anousu; 
       $this->c99_instit = $c99_instit; 
     if(($this->c99_anousu == null) || ($this->c99_anousu == "") ){ 
       $this->erro_sql = " Campo c99_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c99_instit == null) || ($this->c99_instit == "") ){ 
       $this->erro_sql = " Campo c99_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into condataconf(
                                       c99_anousu 
                                      ,c99_instit 
                                      ,c99_data 
                                      ,c99_usuario 
                       )
                values (
                                $this->c99_anousu 
                               ,$this->c99_instit 
                               ,".($this->c99_data == "null" || $this->c99_data == ""?"null":"'".$this->c99_data."'")." 
                               ,$this->c99_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuracao Data ($this->c99_anousu."-".$this->c99_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuracao Data já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuracao Data ($this->c99_anousu."-".$this->c99_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c99_anousu."-".$this->c99_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c99_anousu,$this->c99_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8009,'$this->c99_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,8010,'$this->c99_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1350,8009,'','".AddSlashes(pg_result($resaco,0,'c99_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1350,8010,'','".AddSlashes(pg_result($resaco,0,'c99_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1350,8011,'','".AddSlashes(pg_result($resaco,0,'c99_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1350,8874,'','".AddSlashes(pg_result($resaco,0,'c99_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c99_anousu=null,$c99_instit=null) { 
      $this->atualizacampos();
     $sql = " update condataconf set ";
     $virgula = "";
     if(trim($this->c99_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c99_anousu"])){ 
       $sql  .= $virgula." c99_anousu = $this->c99_anousu ";
       $virgula = ",";
       if(trim($this->c99_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c99_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c99_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c99_instit"])){ 
       $sql  .= $virgula." c99_instit = $this->c99_instit ";
       $virgula = ",";
       if(trim($this->c99_instit) == null ){ 
         $this->erro_sql = " Campo Instittuição nao Informado.";
         $this->erro_campo = "c99_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c99_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c99_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c99_data_dia"] !="") ){ 
       $sql  .= $virgula." c99_data = '$this->c99_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c99_data_dia"])){ 
         $sql  .= $virgula." c99_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c99_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c99_usuario"])){ 
       $sql  .= $virgula." c99_usuario = $this->c99_usuario ";
       $virgula = ",";
       if(trim($this->c99_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "c99_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c99_anousu!=null){
       $sql .= " c99_anousu = $this->c99_anousu";
     }
     if($c99_instit!=null){
       $sql .= " and  c99_instit = $this->c99_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c99_anousu,$this->c99_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8009,'$this->c99_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,8010,'$this->c99_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c99_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1350,8009,'".AddSlashes(pg_result($resaco,$conresaco,'c99_anousu'))."','$this->c99_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c99_instit"]))
           $resac = db_query("insert into db_acount values($acount,1350,8010,'".AddSlashes(pg_result($resaco,$conresaco,'c99_instit'))."','$this->c99_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c99_data"]))
           $resac = db_query("insert into db_acount values($acount,1350,8011,'".AddSlashes(pg_result($resaco,$conresaco,'c99_data'))."','$this->c99_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c99_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1350,8874,'".AddSlashes(pg_result($resaco,$conresaco,'c99_usuario'))."','$this->c99_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao Data nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c99_anousu."-".$this->c99_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao Data nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c99_anousu."-".$this->c99_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c99_anousu."-".$this->c99_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c99_anousu=null,$c99_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c99_anousu,$c99_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8009,'$c99_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,8010,'$c99_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1350,8009,'','".AddSlashes(pg_result($resaco,$iresaco,'c99_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1350,8010,'','".AddSlashes(pg_result($resaco,$iresaco,'c99_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1350,8011,'','".AddSlashes(pg_result($resaco,$iresaco,'c99_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1350,8874,'','".AddSlashes(pg_result($resaco,$iresaco,'c99_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from condataconf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c99_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c99_anousu = $c99_anousu ";
        }
        if($c99_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c99_instit = $c99_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao Data nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c99_anousu."-".$c99_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao Data nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c99_anousu."-".$c99_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c99_anousu."-".$c99_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:condataconf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c99_anousu=null,$c99_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from condataconf ";
     $sql .= "      inner join db_config  on  db_config.codigo = condataconf.c99_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = condataconf.c99_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($c99_anousu!=null ){
         $sql2 .= " where condataconf.c99_anousu = $c99_anousu "; 
       } 
       if($c99_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " condataconf.c99_instit = $c99_instit "; 
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
   // funcao do sql 
   function sql_query_file ( $c99_anousu=null,$c99_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from condataconf ";
     $sql2 = "";
     if($dbwhere==""){
       if($c99_anousu!=null ){
         $sql2 .= " where condataconf.c99_anousu = $c99_anousu "; 
       } 
       if($c99_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " condataconf.c99_instit = $c99_instit "; 
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